<?php 
// ----------------------------------------------------------------------
// ModName: lang_id.php
// Purpose: Setup Definitiion for Bahasa Indonesia Language
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------


// ----------------------------------------------------------------------
// Setup Stages Titles
// ----------------------------------------------------------------------

define('SETUP_STAGE_START_TITLE', 'Setup ChakraWeb');
define('SETUP_STAGE_CHMOD_TITLE', 'Setup ChakraWeb: Pengecekan CHMOD');
define('SETUP_STAGE_WEBINFO_TITLE', 'Setup ChakraWeb: Informasi Website');
define('SETUP_STAGE_DBINFO_TITLE', 'Setup ChakraWeb: Informasi Basisdata');
define('SETUP_STAGE_DBCREATE_TITLE', 'Setup ChakraWeb: Buat Basisdata dan Tabel');
define('SETUP_STAGE_ADMIN_TITLE', 'Setup ChakraWeb: Administrasi');
define('SETUP_STAGE_FINISH_TITLE', 'Setup ChakraWeb: Selesai');

// ----------------------------------------------------------------------
// Setup Stage Navigation
// ----------------------------------------------------------------------

define('PREVIOUS_STAGE', 'Kembali Ke Tahap Sebelumnya');
define('NEXT_STAGE', 'Tahap Selanjutnya');

// ----------------------------------------------------------------------
// Setup Status
// ----------------------------------------------------------------------

define('STATUS_SUCCESS', 'Sukses');
define('STATUS_FAILED', 'Gagal');
define('STATUS_ALREADY_CCREATED', 'Telah Dibuat');
define('STATUS_UNKNOWN', 'Tak Diketahui');
define('STATUS_OK', 'OK');


// ----------------------------------------------------------------------
// Setup Error Messages
// ----------------------------------------------------------------------

define('SETUP_ERROR_HPINFO', 'Mohon kiranya anda mengisi informasi homepage dengan benar.');
define('SETUP_ERROR_ADMIN_INFO', 'Mohon nama, nama lengkap, dan email administrator diisi dengan banar');
define('SETUP_ERROR_ADMIN_PASSWORD', 'Password tidak valid atau kedua password tidak sama.');

// ----------------------------------------------------------------------
// Other Definitions
// ----------------------------------------------------------------------

define ('CHMOD_SUCCESS_FMT', 'Hak akses untuk file <b>%s</b> adalah 666 -- benar, skrip ini dapat menulis ke file');
define ('CHMOD_FAILED_FMT', 'Gagal merubah hak akses untuk <b>%s</b> menjadi 666 -- skrip ini tidak dapat menulis ke file');

define('DB_INFO_FMT', '%s pada %s');

define('GUEST_ACCOUNT', 'tamu');
define('GUEST_ACCOUNT_FULLNAME', 'Tamu Website');


?>
