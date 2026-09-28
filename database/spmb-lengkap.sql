-- SQL lengkap fitur SPMB, sesuai migrasi 2026_09_28_100000 s.d. 140000.
-- MySQL 8 / MariaDB dengan RANDOM_BYTES(). Pilih database aplikasi dahulu.
-- Prasyarat: tabel aplikasi users, role, siswa, menu, hak_akses, migrations tersedia.
-- Tidak menghapus pendaftaran, lampiran, atau hak akses yang sudah ada.
-- Jalankan saat tidak ada proses pendaftaran / migrasi lain yang berjalan.
-- Jika ada error, BERHENTI dan perbaiki sebelum melanjutkan pencatatan migrasi.
-- DDL MySQL tidak dapat dibatalkan hanya dengan ROLLBACK.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS spmb_pendaftaran (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    jk VARCHAR(30) NOT NULL,
    ortu VARCHAR(255) NOT NULL,
    wa VARCHAR(25) NOT NULL,
    bukti_path VARCHAR(255) NOT NULL,
    bukti_mime VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kolom lanjutan: cek dahulu agar aman untuk database yang sudah diperbarui sebagian.
SET @spmb_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='spmb_pendaftaran' AND COLUMN_NAME='token'),
    'SELECT ''Kolom token sudah ada'' AS info',
    'ALTER TABLE spmb_pendaftaran ADD COLUMN token VARCHAR(10) NULL');
PREPARE spmb_stmt FROM @spmb_sql;
EXECUTE spmb_stmt;
DEALLOCATE PREPARE spmb_stmt;

SET @spmb_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='spmb_pendaftaran' AND COLUMN_NAME='status'),
    'SELECT ''Kolom status sudah ada'' AS info',
    'ALTER TABLE spmb_pendaftaran ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT ''formulir''');
PREPARE spmb_stmt FROM @spmb_sql;
EXECUTE spmb_stmt;
DEALLOCATE PREPARE spmb_stmt;

SET @spmb_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='spmb_pendaftaran' AND COLUMN_NAME='siswa_id'),
    'SELECT ''Kolom siswa_id sudah ada'' AS info',
    'ALTER TABLE spmb_pendaftaran ADD COLUMN siswa_id BIGINT UNSIGNED NULL');
PREPARE spmb_stmt FROM @spmb_sql;
EXECUTE spmb_stmt;
DEALLOCATE PREPARE spmb_stmt;

SET @spmb_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='spmb_pendaftaran' AND COLUMN_NAME='wa_dikirim_at'),
    'SELECT ''Kolom wa_dikirim_at sudah ada'' AS info',
    'ALTER TABLE spmb_pendaftaran ADD COLUMN wa_dikirim_at TIMESTAMP NULL DEFAULT NULL');
PREPARE spmb_stmt FROM @spmb_sql;
EXECUTE spmb_stmt;
DEALLOCATE PREPARE spmb_stmt;

SET @spmb_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='spmb_pendaftaran' AND COLUMN_NAME='selesai_at'),
    'SELECT ''Kolom selesai_at sudah ada'' AS info',
    'ALTER TABLE spmb_pendaftaran ADD COLUMN selesai_at TIMESTAMP NULL DEFAULT NULL');
PREPARE spmb_stmt FROM @spmb_sql;
EXECUTE spmb_stmt;
DEALLOCATE PREPARE spmb_stmt;

SET @spmb_sql = IF(EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='spmb_pendaftaran' AND COLUMN_NAME='jenjang'),
    'SELECT ''Kolom jenjang sudah ada'' AS info',
    'ALTER TABLE spmb_pendaftaran ADD COLUMN jenjang VARCHAR(10) NOT NULL DEFAULT ''SD'' AFTER jk');
PREPARE spmb_stmt FROM @spmb_sql;
EXECUTE spmb_stmt;
DEALLOCATE PREPARE spmb_stmt;

-- Pertahankan token lama agar tautan formulir yang sudah dikirim tetap berlaku.
UPDATE spmb_pendaftaran SET token = LOWER(HEX(RANDOM_BYTES(5))) WHERE token IS NULL OR token = '';
ALTER TABLE spmb_pendaftaran MODIFY COLUMN token VARCHAR(10) NOT NULL;

SET @spmb_sql = IF(EXISTS(SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='spmb_pendaftaran' AND INDEX_NAME='spmb_pendaftaran_token_unique'),
    'SELECT ''Indeks token sudah ada'' AS info',
    'ALTER TABLE spmb_pendaftaran ADD UNIQUE KEY spmb_pendaftaran_token_unique (token)');
PREPARE spmb_stmt FROM @spmb_sql;
EXECUTE spmb_stmt;
DEALLOCATE PREPARE spmb_stmt;

SET @spmb_sql = IF(EXISTS(SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='spmb_pendaftaran' AND INDEX_NAME='spmb_pendaftaran_siswa_id_unique'),
    'SELECT ''Indeks siswa_id sudah ada'' AS info',
    'ALTER TABLE spmb_pendaftaran ADD UNIQUE KEY spmb_pendaftaran_siswa_id_unique (siswa_id)');
PREPARE spmb_stmt FROM @spmb_sql;
EXECUTE spmb_stmt;
DEALLOCATE PREPARE spmb_stmt;

SET @spmb_sql = IF(EXISTS(SELECT 1 FROM information_schema.KEY_COLUMN_USAGE WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='spmb_pendaftaran' AND COLUMN_NAME='siswa_id' AND REFERENCED_TABLE_NAME='siswa'),
    'SELECT ''Relasi siswa sudah ada'' AS info',
    'ALTER TABLE spmb_pendaftaran ADD CONSTRAINT spmb_pendaftaran_siswa_id_foreign FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE SET NULL');
PREPARE spmb_stmt FROM @spmb_sql;
EXECUTE spmb_stmt;
DEALLOCATE PREPARE spmb_stmt;

CREATE TABLE IF NOT EXISTS spmb_lampiran (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    spmb_pendaftaran_id BIGINT UNSIGNED NOT NULL,
    jenis VARCHAR(40) NOT NULL,
    path VARCHAR(255) NOT NULL,
    nama_asli VARCHAR(255) NOT NULL,
    mime VARCHAR(100) NOT NULL,
    ukuran BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY spmb_lampiran_spmb_pendaftaran_id_jenis_unique (spmb_pendaftaran_id, jenis),
    CONSTRAINT spmb_lampiran_spmb_pendaftaran_id_foreign
        FOREIGN KEY (spmb_pendaftaran_id) REFERENCES spmb_pendaftaran(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menu SPMB dan akses Admin Sekolah.
SET @spmb_order = (SELECT COALESCE(MAX(urutan), 0) + 1 FROM menu);
INSERT INTO menu (id_parent, title, route_name, icon, urutan, lihat, tambah, edit, hapus)
SELECT 0, 'SPMB', 'admin.spmb.index', 'bi bi-person-plus-fill', @spmb_order, 1, 0, 0, 0
WHERE NOT EXISTS (SELECT 1 FROM menu WHERE route_name='admin.spmb.index');
SET @spmb_menu_id = (SELECT MIN(id) FROM menu WHERE route_name='admin.spmb.index');

INSERT INTO hak_akses (id_user, id_menu, lihat, beranda, tambah, edit, hapus)
SELECT u.id, @spmb_menu_id, 1, 0, 0, 0, 0
FROM users u JOIN role r ON r.id=u.id_role
WHERE r.role='Admin Sekolah'
AND NOT EXISTS (SELECT 1 FROM hak_akses h WHERE h.id_user=u.id AND h.id_menu=@spmb_menu_id);

-- Menu Pendaftar: akses mengikuti pengguna yang dapat melihat menu SPMB.
SET @spmb_order = (SELECT COALESCE(MAX(urutan), 0) + 1 FROM menu);
INSERT INTO menu (id_parent, title, route_name, icon, urutan, lihat, tambah, edit, hapus)
SELECT 0, 'Pendaftar', 'admin.pendaftar.index', 'bi bi-person-lines-fill', @spmb_order, 1, 0, 0, 0
WHERE NOT EXISTS (SELECT 1 FROM menu WHERE route_name='admin.pendaftar.index');
SET @spmb_pendaftar_menu_id = (SELECT MIN(id) FROM menu WHERE route_name='admin.pendaftar.index');

INSERT INTO hak_akses (id_user, id_menu, lihat, beranda, tambah, edit, hapus)
SELECT DISTINCT h.id_user, @spmb_pendaftar_menu_id, 1, 0, 0, 0, 0
FROM hak_akses h JOIN menu m ON m.id=h.id_menu
WHERE m.route_name='admin.spmb.index' AND h.lihat=1
AND NOT EXISTS (SELECT 1 FROM hak_akses existing WHERE existing.id_user=h.id_user AND existing.id_menu=@spmb_pendaftar_menu_id);

-- Catat sebagai migrasi yang sudah diterapkan, HANYA jika seluruh SQL di atas berhasil.
-- Ini mencegah artisan migrate membuat ulang tabel / kolom / menu yang sama.
SET @spmb_batch = (SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations);
INSERT INTO migrations (migration, batch)
SELECT pending.migration, @spmb_batch FROM (
    SELECT '2026_09_28_100000_create_spmb_pendaftaran_table' AS migration
    UNION ALL SELECT '2026_09_28_110000_add_followup_to_spmb_pendaftaran'
    UNION ALL SELECT '2026_09_28_120000_add_jenjang_to_spmb_pendaftaran'
    UNION ALL SELECT '2026_09_28_130000_create_spmb_lampiran_table'
    UNION ALL SELECT '2026_09_28_140000_add_pendaftar_menu'
) pending
WHERE NOT EXISTS (SELECT 1 FROM migrations applied WHERE applied.migration=pending.migration);

SHOW COLUMNS FROM spmb_pendaftaran;
SHOW COLUMNS FROM spmb_lampiran;
SELECT id, title, route_name FROM menu WHERE route_name IN ('admin.spmb.index', 'admin.pendaftar.index');
