-- Pemeriksaan saja, tidak mengubah data / struktur database (MySQL / MariaDB).
-- Sesi perubahan galeri, video, hero, Maps, menu aktif, tombol detail,
-- dan pesan error registrasi tidak menambahkan perubahan skema.
-- Jalankan setelah memilih database aplikasi.

SELECT expected.table_name, expected.column_name,
       CASE WHEN actual.COLUMN_NAME IS NULL THEN 'BELUM ADA' ELSE 'OK' END AS status
FROM (
    SELECT 'spmb_pendaftaran' AS table_name, 'token' AS column_name
    UNION ALL SELECT 'spmb_pendaftaran', 'status'
    UNION ALL SELECT 'spmb_pendaftaran', 'siswa_id'
    UNION ALL SELECT 'spmb_pendaftaran', 'wa_dikirim_at'
    UNION ALL SELECT 'spmb_pendaftaran', 'selesai_at'
    UNION ALL SELECT 'spmb_pendaftaran', 'jenjang'
    UNION ALL SELECT 'spmb_lampiran', 'spmb_pendaftaran_id'
    UNION ALL SELECT 'spmb_lampiran', 'jenis'
    UNION ALL SELECT 'spmb_lampiran', 'path'
) AS expected
LEFT JOIN information_schema.COLUMNS AS actual
    ON actual.TABLE_SCHEMA = DATABASE()
    AND actual.TABLE_NAME = expected.table_name
    AND actual.COLUMN_NAME = expected.column_name;

SELECT id, title, route_name FROM menu
WHERE route_name IN ('admin.spmb.index', 'admin.pendaftar.index');
