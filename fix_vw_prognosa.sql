-- ================================================
-- FIX PROGNOSA VIEW - Recreate dengan Collation yang Benar
-- ================================================

-- Step 1: Drop view yang rusak
DROP VIEW IF EXISTS vw_prognosa;

-- Step 2: Recreate view dengan collation yang konsisten
-- View ini menghitung prognosa pembayaran per bulan berdasarkan entry_kontrak
CREATE VIEW vw_prognosa AS
SELECT 
    CAST(jenis_anggaran AS CHAR CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci) AS jenis_anggaran,
    CAST(CONCAT(
        COALESCE(nomor_prk, ''), ' - ',
        COALESCE(uraian_prk, '')
    ) AS CHAR CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci) AS rekap,
    
    -- Prognosa per bulan (dari renc_byr_bln atau real_byr_bln)
    SUM(COALESCE(renc_byr_bln1, 0)) AS jan_25,
    SUM(COALESCE(renc_byr_bln2, 0)) AS feb_25,
    SUM(COALESCE(renc_byr_bln3, 0)) AS mar_25,
    SUM(COALESCE(renc_byr_bln4, 0)) AS apr_25,
    SUM(COALESCE(renc_byr_bln5, 0)) AS mei_25,
    SUM(COALESCE(renc_byr_bln6, 0)) AS jun_25,
    SUM(COALESCE(renc_byr_bln7, 0)) AS jul_25,
    SUM(COALESCE(renc_byr_bln8, 0)) AS aug_25,
    SUM(COALESCE(renc_byr_bln9, 0)) AS sep_25,
    SUM(COALESCE(renc_byr_bln10, 0)) AS okt_25,
    SUM(COALESCE(renc_byr_bln11, 0)) AS nov_25,
    SUM(COALESCE(renc_byr_bln12, 0)) AS des_25
FROM entry_kontrak
WHERE jenis_anggaran IS NOT NULL 
  AND nomor_prk IS NOT NULL
GROUP BY 
    CAST(jenis_anggaran AS CHAR CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci),
    CAST(CONCAT(
        COALESCE(nomor_prk, ''), ' - ',
        COALESCE(uraian_prk, '')
    ) AS CHAR CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci);

-- Step 3: Test view
SELECT COUNT(*) as total_rows FROM vw_prognosa;
SELECT * FROM vw_prognosa LIMIT 5;
