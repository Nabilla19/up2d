<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportJob_model extends CI_Model {
    private $jobs_table = 'import_jobs';
    private $errors_table = 'import_job_errors';
    private $staging_gi = 'gi_import_raw';

    public function __construct()
    {
        parent::__construct();
    }

    public function create_job($data)
    {
        $data['started_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->jobs_table, $data);
        return (int)$this->db->insert_id();
    }

    public function get_job($id)
    {
        return $this->db->get_where($this->jobs_table, ['id' => (int)$id])->row();
    }

    public function finish_job($id, $updates)
    {
        $updates['finished_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', (int)$id)->update($this->jobs_table, $updates);
    }

    public function ensure_schema()
    {
        // Create jobs and errors table if not exists
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->jobs_table}` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NULL,
            `target_table` VARCHAR(64) NOT NULL,
            `staging_table` VARCHAR(64) NULL,
            `original_filename` VARCHAR(255) NOT NULL,
            `stored_path` VARCHAR(255) NOT NULL,
            `file_size` INT NULL,
            `chunk_size` INT NULL,
            `duplicate_policy` VARCHAR(16) DEFAULT 'ask',
            `chosen_unique_key` VARCHAR(255) NULL,
            `total_rows` INT DEFAULT 0,
            `inserted` INT DEFAULT 0,
            `updated` INT DEFAULT 0,
            `skipped` INT DEFAULT 0,
            `failed` INT DEFAULT 0,
            `status` VARCHAR(32) DEFAULT 'preview',
            `started_at` DATETIME NULL,
            `finished_at` DATETIME NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        // Jika tabel sudah ada tapi belum ada kolom staging_table
        if (!$this->db->field_exists('staging_table', $this->jobs_table)) {
            $this->db->query("ALTER TABLE `{$this->jobs_table}` ADD COLUMN `staging_table` VARCHAR(64) NULL AFTER `target_table`");
        }

        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->errors_table}` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `job_id` INT NOT NULL,
            `row_no` INT NOT NULL,
            `column_name` VARCHAR(128) NULL,
            `col_value` TEXT NULL,
            `error_code` VARCHAR(64) NULL,
            `error_message` TEXT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            KEY `job_id_idx` (`job_id`),
            CONSTRAINT `fk_job_err` FOREIGN KEY (`job_id`) REFERENCES `{$this->jobs_table}`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    public function ensure_staging_for_gi()
    {
        $this->ensure_schema();
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->staging_gi}` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `import_id` INT NOT NULL,
            `row_no` INT NOT NULL,
            `UNIT_LAYANAN` VARCHAR(255) NULL,
            `GARDU_INDUK` VARCHAR(255) NULL,
            `LONGITUDEX` VARCHAR(64) NULL,
            `LATITUDEY` VARCHAR(64) NULL,
            `STATUS_OPERASI` VARCHAR(64) NULL,
            `JML_TD` VARCHAR(32) NULL,
            `INC` VARCHAR(32) NULL,
            `OGF` VARCHAR(32) NULL,
            `SPARE` VARCHAR(32) NULL,
            `COUPLE` VARCHAR(32) NULL,
            `BUS_RISER` VARCHAR(32) NULL,
            `BBVT` VARCHAR(32) NULL,
            `PS` VARCHAR(32) NULL,
            `STATUS_SCADA` VARCHAR(64) NULL,
            `IP_GATEWAY` VARCHAR(64) NULL,
            `IP_RTU` VARCHAR(64) NULL,
            `MERK_RTU` VARCHAR(128) NULL,
            `SN_RTU` VARCHAR(128) NULL,
            `THN_INTEGRASI` VARCHAR(16) NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            KEY `import_id_idx` (`import_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    public function insert_staging_batch($entity, $rows)
    {
        if ($entity !== 'gi') return false;
        return $this->db->insert_batch($this->staging_gi, $rows) !== false;
    }

    /**
     * Commit rows from staging table into the target table.
     * Strategy: append-only (no upsert). Only columns that exist in both tables are copied.
     * Returns number of rows inserted, or -1 if target table missing, -2 if PK not auto-increment, 0 if no match cols.
     */
    public function commit_staging_to_target($job_id, $target_table)
    {
        $job_id = (int)$job_id;

        if (!$this->db->table_exists($target_table)) {
            return -1;
        }

        // PK check
        $dbName = $this->db->database;
        $pkQuery = $this->db->query(
            "SELECT k.COLUMN_NAME, c.EXTRA FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS t
             JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
               ON t.CONSTRAINT_NAME=k.CONSTRAINT_NAME AND t.TABLE_SCHEMA=k.TABLE_SCHEMA AND t.TABLE_NAME=k.TABLE_NAME
             JOIN INFORMATION_SCHEMA.COLUMNS c
               ON c.TABLE_SCHEMA=k.TABLE_SCHEMA AND c.TABLE_NAME=k.TABLE_NAME AND c.COLUMN_NAME=k.COLUMN_NAME
             WHERE t.TABLE_SCHEMA=? AND t.TABLE_NAME=? AND t.CONSTRAINT_TYPE='PRIMARY KEY'",
            [$dbName, $target_table]
        );
        $pkCols = $pkQuery->result_array();
        if (count($pkCols) === 1) {
            $pkExtra = strtolower((string)$pkCols[0]['EXTRA']);
            $isAI = (strpos($pkExtra, 'auto_increment') !== false);
            if (!$isAI) return -2;
        }

        $target_fields = $this->db->list_fields($target_table);
        $staging_fields = $this->db->list_fields($this->staging_gi);
        $cols = array_values(array_intersect($staging_fields, $target_fields));
        if (empty($cols)) return 0;

        $query = $this->db->select($cols)->from($this->staging_gi)->where('import_id', $job_id)->get();
        $rows = $query->result_array();
        if (empty($rows)) return 0;

        $batchSize = 500;
        $inserted = 0;
        for ($i = 0; $i < count($rows); $i += $batchSize) {
            $batch = array_slice($rows, $i, $batchSize);
            $this->db->insert_batch($target_table, $batch);
            $aff = $this->db->affected_rows();
            if ($aff > 0) $inserted += $aff;
        }

        return $inserted;
    }
}
