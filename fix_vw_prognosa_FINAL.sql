-- ================================================
-- FIX PROGNOSA VIEW - Versi Final dengan Column yang Benar
-- ================================================

-- Step 1: Drop view yang rusak
DROP VIEW IF EXISTS vw_prognosa;

-- Step 2: Recreate view dengan column yang benar (real_byr_bln, bukan renc_byr_bln)
CREATE VIEW vw_prognosa AS
SELECT 
    jenis_anggaran,
    CONCAT(COALESCE(nomor_prk, ''), ' - ', COALESCE(uraian_prk, '')) AS rekap,
    
    -- Prognosa per bulan (dari real_byr_bln karena renc_byr_bln tidak ada)
    SUM(COALESCE(real_byr_bln1, 0)) AS jan_25,
    SUM(COALESCE(real_byr_bln2, 0)) AS feb_25,
    SUM(COALESCE(real_byr_bln3, 0)) AS mar_25,
    SUM(COALESCE(real_byr_bln4, 0)) AS apr_25,
    SUM(COALESCE(real_byr_bln5, 0)) AS mei_25,
    SUM(COALESCE(real_byr_bln6, 0)) AS jun_25,
    SUM(COALESCE(real_byr_bln7, 0)) AS jul_25,
    SUM(COALESCE(real_byr_bln8, 0)) AS aug_25,
    SUM(COALESCE(real_byr_bln9, 0)) AS sep_25,
    SUM(COALESCE(real_byr_bln10, 0)) AS okt_25,
    SUM(COALESCE(real_byr_bln11, 0)) AS nov_25,
    SUM(COALESCE(real_byr_bln12, 0)) AS des_25
FROM entry_kontrak
WHERE jenis_anggaran IS NOT NULL 
  AND nomor_prk IS NOT NULL
GROUP BY jenis_anggaran, nomor_prk, uraian_prk;

-- Step 3: Test view
SELECT COUNT(*) as total_rows FROM vw_prognosa;
SELECT * FROM vw_prognosa LIMIT 5;
