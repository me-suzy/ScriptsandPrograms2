<?php
// ----------------------------------------------------------------------
// Purpose: Indonesia Definition Module
// Author: Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [global.php] file directly...");

// ----------------------------------------------------------------------
// Global Definitions
// ----------------------------------------------------------------------
define('_POWERED_BY_CHAKRAWEB', '<a href="http://chakra.quick4all.com/Products/ChakraWeb/"><img border="0" src="/images/chweb_power.gif"></a>');
define('_MADE_BY_CHAKRAWEB', '
Website ini dibuat dengan <a href="http://chakra.quick4all.com/Products/ChakraWeb/">ChakraWeb</a>,
sebuah sistem portal yang ditulis dengan bahasa PHP. ChakraWeb adalah software bebas yang di-release
dengan <a href="http://www.gnu.org/">Lisensi GNU/GPL</a>.');


// ----------------------------------------------------------------------
// Field Definitions
// ----------------------------------------------------------------------
define('_FLD_ACTIVE', 'Aktif');
define('_FLD_ADMIN', 'Admin');
define('_FLD_ATTR', 'Atribut');
define('_FLD_DESCRIPTION', 'Deskripsi');
define('_FLD_EMPTY', 'KOSONG');
define('_FLD_FOLDER', 'FOLDER');
define('_FLD_FULLNAME', 'Nama Langkap');
define('_FLD_GREAT', 'Bagus Sekali');
define('_FLD_HITS', 'Hit');
define('_FLD_ID', 'Id');
define('_FLD_MEMBER_LEVEL', 'Level Anggota');
define('_FLD_MISC_ATTR', 'Atribut');
define('_FLD_NAME', 'Nama');
define('_FLD_ORDER', 'Urutan');
define('_FLD_ORDER_NOTE', 'Urutan tampil dalam daftar.');
define('_FLD_PAGE', 'HALAMAN');
define('_FLD_PASSWORD', 'PSW');
define('_FLD_REDIRECT', 'Redirect Ke');
define('_FLD_SEEALSO_TITLE', 'Lihat Juga');
define('_FLD_SELECT_COUNTRY', 'Pilih Negara');
define('_FLD_SHOW', 'Tampil');
define('_FLD_TITLE', 'Judul');
define('_FLD_USERID', 'UID');
define('_FLD_VISIT', 'Kunjungan');
define('_FLD_NOACTIVE', 'Non-Aktif');
define('_FLD_WEB_ACTIVATE', 'Mengaktifkan');
define('_FLD_WEB_ACTIVE', 'AKTIF');
define('_FLD_WEB_MAINTENANCE', 'Menonaktifkan (Melakukan Perawatan)');
define('_FLD_WEB_NOACTIVE', 'NON-AKTIF');


// ----------------------------------------------------------------------
// Navigation Definitions
// ----------------------------------------------------------------------
define('_NAV_ABOUTUS', 'Tentang Kami');
define('_NAV_ADDNEW', 'BUAT BARU');
define('_NAV_ADVRND', 'Daftar Teks Iklan Random');
define('_NAV_ADVRND_EDIT', 'Ubah');
define('_NAV_ADVTEXT', 'Daftar Teks Iklan');
define('_NAV_ADVTEXT_EDIT', 'Ubah');
define('_NAV_ADV_SEARCH', 'Pencarian Canggih');
define('_NAV_APPROVE', 'SETUJUI');
define('_NAV_ATTR', 'ATRIBUT');
define('_NAV_BOOKMARKUS', 'Bookmark');
define('_NAV_CONTROL_PANEL', 'Panel Kontrol');
define('_NAV_DELETE', 'HAPUS');
define('_NAV_DIR_HELP', 'Bantuan Direktori');
define('_NAV_DOWNLOAD_PAGE', 'Download');
define('_NAV_EDIT', 'UBAH');
define('_NAV_FEEDBACK', 'Tanggapan');
define('_NAV_FILE_MANAGEMENT', 'Manajemen File');
define('_NAV_FRONTPAGE', 'Beranda');
define('_NAV_HELP', 'Bantuan');
define('_NAV_HIDE', 'SEMBUNYIKAN');
define('_NAV_HOME', 'Beranda');
define('_NAV_LINKTOUS', 'Link ke Kami');
define('_NAV_MACROTEXT', 'Teks Makro');
define('_NAV_MACROTEXT_EDIT', 'Ubah Teks Makro');
define('_NAV_MEMBERS', 'Aggota Terdaftar');
define('_NAV_MEMBER_ADD', 'Penambahan Anggota');
define('_NAV_MEMBER_INFO', 'Info Anggota');
define('_NAV_MEMBER_LOGOUT', 'Logout');
define('_NAV_MEMBER_PROFILE', 'Profil Anggota');
define('_NAV_MEMBER_SENDMAIL', 'Kirim Email');
define('_NAV_MEMBER_SERVICE', 'Layanan Anggota');
define('_NAV_MEMBER_SERVICE_EDIT', 'Ubah Layanan');
define('_NAV_MEMBER_SERVICE_SENDMAIL', 'Kirim Email');
define('_NAV_MEMBER_STARTPAGE', 'Halaman Awal');
define('_NAV_MEMBER_VISIT', 'Daftar Kunjungan Anggota');
define('_NAV_MORE_LIST', 'Selengkapnya...');
define('_NAV_MOVE', 'PINDAH');
define('_NAV_MYPROFILE', 'Profil Saya');
define('_NAV_PAGE_HITS_ASC', 'Halaman Tak Laku');
define('_NAV_PAGE_HITS_DESC', 'Hal Favorit');
define('_NAV_PAGE_HITS_RESET', 'Reset Hit');
define('_NAV_PRIVACY_POLICY', 'Kebijakan Personal');
define('_NAV_REGISTER', 'Registrasi');
define('_NAV_SEARCH_TIPS', 'Tips Pencarian');
define('_NAV_SERVICE_MEMBER_LIST', 'Daftar Anggota');
define('_NAV_SHOW', 'TAMPILKAN');
define('_NAV_SITEMAP', 'Sitemap');
define('_NAV_SYSDBASE_EDIT', 'Ubah Basisdata');
define('_NAV_SYSOTHER_EDIT', 'Ubah Parameter Lain');
define('_NAV_SYSVAR_EDIT', 'Ubah Variabel Sistem');
define('_NAV_TERM_OF_USE', 'Aturan Pemakaian');
define('_NAV_TODO_LINK', 'Link');
define('_NAV_TODO_LIST', 'Daftar Pekerjaan');
define('_NAV_WEB_ACTIVATE', 'Waktu Perawatan');
define('_NAV_GOTO_PAGE', 'Pergi Ke Halaman');

// ----------------------------------------------------------------------
// Misc Definitions
// ----------------------------------------------------------------------

define(DEFAULT_PAGE_CONTENT, "<h1>{page_title}</h1>\n\n{style:info:\$page_desc}\n<p>Mohon maaf. Halaman ini dalam perbaikan.</p>");

define('_MEMBER_REGISTRATION_TITLE', 'Registrasi Anggota');
define('_MEMBER_REGISTRATION_CONTENT', '
<p>Pendaftaran anggota tidak dipungut biaya. Sebelum anda mengisi form di bawah ini, pastikan anda telah mengerti dengan benar <a href="/privacy_policy.html">Kebijakan Kerahasiaan</a> dan <a href="/terms_of_use.html">Term Of Use</a>. Manfaat keanggotaan dapat dilihat pada bagian bawah form.</p>
<p>Setelah anda mengisi form ini, kami akan segera mengirim e-mail yang berisi password anda dan anda dapat langsung login ke homepage kami.</p>
');

define('_LINK_ADDNEW_MESSAGE', '<a href="/phpmod/link.php?op=show&cat={folder_id}">Tambahkan Link</a> ke Halaman ini');
define('_LINK_FORM_TITLE', 'TAMBAHKAN LINK');
define('_LINK_ADD_TITLE', 'Tambah Link');
define('_LINK_UPDATE_TITLE', 'UBAH LINK');
define('_LINK_ADD_NOLOGIN', '
<p>Anda belum menjadi anggota atau belum login.
Jika anda telah menjadi anggota, anda dapat menambahkan link di website ini.

<p>Proses menjadi anggota sangat cepat dan mudah.
Kenapa kami membutuhkan registrasi untuk mengakses fitur-fitur tertentu?
Dengan menjadi anggota, kami dapat menyediakan informasi yang berkualitas tinggi,
yang setiap item-nya direview dan diotorisasi secara individual oleh staf kami.
Kami berharap bisa menyediakan informasi yang berharga buat anda.

<p><a href="/phpmod/register.php">Register Sebagai Anggota</a>
');

define('_LINK_ADD_THANKYOU', '
<p>Terima kasih atas peran serta anda. Link yang anda tambahkan akan segera kami 
review untuk ditambahkan dalam halaman ini.</p>
');

define('_COMMENT_PROMO', 'Jika anda menjadi anggota website ini, anda dapat memberikan komentar. 
    <a href="/phpmod/register.php">Registrasikan</a> diri anda sekarang juga. Proses mudah, cepat dan GRATIS. :)');


define('_ABOUT_AUTHOR', 'Tentang Pengarang');
define('_ADVRND_EDIT_TITLE', 'Ubah Teks Iklan Random');
define('_ADVRND_TITLE', 'Daftar Teks Iklan Random');
define('_ADVTEXT_EDIT_TITLE', 'Ubah Teks Iklan');
define('_ADVTEXT_TITLE', 'Daftar Teks Iklan');

define('_AUTHOR', 'Pengarang');
define('_AUTHOR_BY', 'Oleh');

define('_COMMENT_FORM_TITLE', 'SILAKAN TULIS KOMENTAR ANDA DISINI');
define('_CONTROL_PANEL_TITLE', 'Panel Kontrol');
define('_DOWNLOAD_ERROR_TITLE', 'Download Gagal');

define('_FEEDBACK_MESSAGE', 'Kami sangat menghargai tanggapan anda. Isikan pendapat, ide, dan komentar anda dengan mengisi form yang kami sediakan di bagian bawah halaman ini.');
define('_FEEDBACK_THANK_MESSAGE', '<p>Kami sangat menghargai apa yang telah anda lakukan.</p>');
define('_FEEDBACK_THANK_TITLE', 'Terimakasih Atas Tanggapan Anda');
define('_FEEDBACK_TITLE', 'Tanggapan Anda');

define('_FILE_MANAGEMENT_TITLE', 'Manajemen File');

define('_FOLDER_ADD_TITLE', 'Tambahkan SubFolder');
define('_FOLDER_ATTR_TITLE', 'Ubah Atribut Folder');
define('_FOLDER_DELETE_TITLE', 'Hapus Folder %s');

define('_HPAGE_DELETE_MESSAGE', 'Mohon maaf, anda hanya dapat menghapus folder lain selain homepage.');
define('_HPAGE_DELETE_TITLE', 'Hapus Homepage');
define('_HPAGE_MOVE_MESSAGE', 'Mohon maaf, anda hanya dapat memindahkan folder lain selain homepage.');
define('_HPAGE_MOVE_TITLE', 'Pindahkan Homepage');
define('_HPAGE_SEARCH_MESSAGE', 'Kata kunci: <b>%s</b>');
define('_HPAGE_SEARCH_TITLE', 'Hasil Pencarian');

define('_LANG_CHANGE', 'Ubah Bahasa');

define('_LOSTPASSWORD_SUBJECT_FMT', 'Password Baru %s');
define('_LOST_PASSWORD', 'Lupa Password?');
define('_LOST_PASSWORD_MESSAGE', 'Tidak masalah, kami akan mengirimkan password baru untuk anda melalui e-mail. Isikan ID dan e-mail anda (e-mail yang anda daftarkan ke website ini).');
define('_LOST_PASSWORD_TITLE', 'Lupa Password');

define('_MACROTEXT_EDIT_TITLE', 'Ubah Teks Makro');
define('_MACROTEXT_TITLE', 'Daftar Teks Makro');

define('_MEMBER_ADD_SUBJECT', 'Anda Kami Daftarkan Sebagai Anggota');
define('_MEMBER_ADD_TITLE', 'Penambahan Anggota');
define('_MEMBER_INFO_MESSAGE', 'Ubah informasi pribadi anda dengan mengisi form di bawah ini:');
define('_MEMBER_INFO_TITLE', 'Info Anggota');
define('_MEMBER_LOGIN', 'Login Anggota');
define('_MEMBER_ONLY_MESSAGE', 'File/Halaman yang anda minta hanya bisa diakses jika anda menjadi anggota. <p>Daftarkan diri anda dengan mengisi <a href="/phpmod/register.php">Form Registrasi</a>. (Pendaftaran anggota tidak dipungut biaya).');
define('_MEMBER_ONLY_TITLE', 'Khusus Untuk Anggota');
define('_MEMBER_PAGE', 'Anggota');
define('_MEMBER_PAGE_EMPTY_FMT', '<p><b>Halo %s.</b><br>Anda dapat mempromosikan diri anda disini dengan mengisi profil lengkap anda. Klik <a href="%s">DISINI</a>.</p>');
define('_MEMBER_PAGE_FOOTNOTE_FMT', '<p><b>%s</b>, Anda dapat mengedit profil lengkap anda. Klik <a href="%s">DISINI</a>.</p>');
define('_MEMBER_PAGE_FMT', 'Halaman Anggota - %s');
define('_MEMBER_PAGE_EDIT_FMT', 'Ubah Halaman Anggota - %s');
define('_MEMBER_PAGE_MESSAGE_FMT', '<p>Halaman ini berisi daftar anggota website ini. Saat ini jumlah anggota yang tercatat sejumlah %d anggota. Silahkan telusuri daftar anggota ini dengan memilih huruf awal nama lengkap mereka atau semua anggota.</p>');
define('_MEMBER_PAGE_TITLE', 'Daftar Anggota');
define('_MEMBER_REGISTRATION', 'Registrasi');
define('_MEMBER_SERVICE_EDIT_TITLE', 'Ubah Layanan Anggota');
define('_MEMBER_SERVICE_SENDMAIL_TITLE', 'Kirim Email');
define('_MEMBER_SERVICE_TITLE', 'Layanan Anggota');
define('_MEMBER_STARTPAGE_MESSAGE', 'Halaman awal merupakan halaman yang akan dituju ketika anda melakukan login. Ubah halaman awal dengan mengisi form di bawah ini.');
define('_MEMBER_STARTPAGE_TITLE', 'Ubah Halaman Awal');
define('_MEMBER_VISIT_RESET', '<p>Untuk mereset angka kunjungan dan hit, klik <a href="/phpmod/member_visit.php?op=reset">DISINI</a></p>');
define('_MEMBER_VISIT_TITLE', 'Daftar Kunjungan Anggota');

define('_NEWS_EDIT_MESSAGE', '');
define('_NEWS_EDIT_TITLE', 'Ubah Berita');
define('_NEWS_FORM_TITLE', 'SILAKAN ISI KABAR BARU');

define('_PAGE_EDIT_MESSAGE', 'Apakah anda ingin membuat/mengubah halaman tersebut?? Klik <a href="%s">DISINI</a>.');
define('_PAGE_HITS', 'Hits');
define('_PAGE_HITS_ASC_MESSAGE', '<p>Berikut ini adalah halaman-halaman yang paling jarang dikunjungi di website ini.</p>');
define('_PAGE_HITS_ASC_TITLE_FMT', '%d Halaman Tak Laku');
define('_PAGE_HITS_DESC_MESSAGE', '<p>Berikut ini adalah halaman-halaman yang sering dikunjungi di website ini.</p>');
define('_PAGE_HITS_DESC_TITLE_FMT', '%d Halaman Favorit');
define('_PAGE_HITS_RESET_TITLE', 'Reset Hit Semua Halaman');
define('_PAGE_HITS_TITLE_FMT', 'Daftar Hits Folder "%s"');
define('_PAGE_NOTFOUND_MESSAGE', 'Halaman/File yang anda minta (<b>%s</b>) tidak diketemukan pada server ini.');
define('_PAGE_NOTFOUND_TITLE', 'Halaman/File Tidak Ada');
define('_PAGE_RATING', 'Rating');
define('_PAGE_SOURCE_FMT', 'Sumber: %s dari website %s');
define('_PAGE_UPDATE_ON', 'Diubah');
define('_PAGE_VOTE_BY', 'Oleh');
define('_REGISTRATION_SUBJECT_FMT', 'Registrasi Anggota %s');
define('_REGIST_STATUS_MSG1', 'Selamat, anda telah menjadi anggota. Password anda telah kami kirimkan melalui e-mail. Silakan buka e-mail anda dan kembali lagi ke homepage kami untuk login. Terimakasih.');
define('_REGIST_STATUS_MSG2', 'Selamat, anda telah menjadi anggota. Password anda adalah: %s. Gunakan password ini untuk melakukan login.');
define('_REGIST_STATUS_TITLE', 'Registrasi Anggota');
define('_SENDPASSWORD_STATUS_MSG1', 'Password anda telah kami kirimkan melalui e-mail. Silakan buka e-mail anda dan kembali lagi ke homepage kami untuk login. Terimakasih.');
define('_SENDPASSWORD_STATUS_MSG2', 'Pengiriman melalui e-mail gagal. Password anda adalah: %s. Gunakan password ini untuk melakukan login.');
define('_SENDPASSWORD_STATUS_TITLE', 'Password Baru');
define('_SERVICE_MEMBER_LIST_TITLE_FMT', 'Daftar Anggota Yang Berlangganan Layanan <br>"%s"');
define('_MAIN_SUBFOLDER', 'NAVIGASI');
define('_SYSDBASE_EDIT_TITLE', 'Ubah Basisdata');
define('_SYSOTHER_EDIT_TITLE', 'Ubah Parameter Lain');
define('_SYSVAR_EDIT_TITLE', 'Ubah Variabel Sistem');
define('_THEME_CHANGE', 'Ubah Tema');

define('_TODO_LINK_MESSAGE', '<p>Berikut ini daftar link yang perlu diotorisasi. 
Anda dapat menghapus, mengubah, atau meluluskan link yang ada dalam daftar.</p>');
define('_TODO_LINK_TITLE', 'Otorisasi Link');

define('_TODO_LIST_TITLE', 'Daftar Pekerjaan Administrator');

define('_UNAUTHORISIZED_ACCESS_MESSAGE', 'File/Halaman yang anda minta tidak bisa dilayani. Perlu otorisasi hak akses.');
define('_UNAUTHORISIZED_ACCESS_TITLE', 'Akses Perlu Otorisasi');

define('_WEB_ACTIVATE_TITLE', 'Waktu Perawatan');

define('_WEB_SEARCH', 'Cari');
define('_WEB_SEARCH_BTN', 'Go');

define('_WPAGE_DELETE_TITLE', 'Hapus Halaman %s');
define('_WPAGE_EDIT_CONTENT', '<h1>Ubah Halaman Web</h1>Silakan masukkan atribut halaman di bawah ini.');
define('_WPAGE_MOVE_TITLE', 'Pindahkan Halaman %s');

define('_YOU_ARE_HERE', 'Anda Disini');
define('_GUEST_FULLNAME', 'Pengunjung');
define('_GUEST_NAME', 'Pengunjung');
define('_USER_NAME_FMT', '%s Yth.');

define('_SETUP_FOLDER_EXIST', '<b>Sekedar Mengingatkan</b>. Anda belum menghapus folder /setup/. Jika tidak dihapus, pengunjung bisa secara sengaja/tidak menghancurkan basis data anda.');

define('_NEW_COMMENT_LIST_TITLE', 'Komentar Baru');
define('_NEW_FEEDBACK_LIST_TITLE', 'Tanggapan Baru');

// ----------------------------------------------------------------------
// Error Messages
// ----------------------------------------------------------------------
define('_ERR_ADDNEW_MEMBER_FAILED', 'Penambahan anggota baru gagal.');
define('_ERR_ADM_AUTHOR_ONLY', 'Permintaan anda ditolak. Hanya Administrator dan pengarang yang bersangkutan yang boleh mengubah folder/halaman.');
define('_ERR_CHANGE_OTHER_MEMBER_INFO', 'Mohon maaf, anda tidak bisa merubah informasi anggota lainnya, atau anda belum login.');
define('_ERR_DBHOST_EMPTY', 'Nama Host Basisdata tidak boleh kosong.');
define('_ERR_DBNAME_EMPTY', 'Nama Basisdata tidak boleh kosong.');
define('_ERR_DBUSER_EMPTY', 'Nama Pemakai Basisdata tidak boleh kosong.');
define('_ERR_EMPTY_TITLE', 'Judul tidak boleh kosong. Mohon diisi ulang.');
define('_ERR_FEEDBACK_MESSAGE_EMPTY', 'Mohon maaf, tanggapan anda kosong. Mohon diulangi lagi.');
define('_ERR_FILE_ALREADY_EXIST', 'File dengan nama tersebut telah ada di server.');
define('_ERR_FOLDER_ALREADY_EXIST', 'Folder dengan nama tersebut telah ada di basis data');
define('_ERR_INVALID_ADVRND', 'Mohon semua field diisi dengan benar.');
define('_ERR_INVALID_ADVTEXT', 'Mohon semua field diisi dengan benar.');
define('_ERR_INVALID_MACROTEXT', 'Mohon semua field diisi dengan benar.');
define('_ERR_INVALID_COUNTRY', 'Mohon asal negara anda diisi dengan benar');
define('_ERR_INVALID_EMAIL_FORMAT', 'Mohon kiranya e-mail anda ditulis dengan benar.');
define('_ERR_INVALID_EMAIL_SERVICE', 'Mohon semua field diisi dengan benar.');
define('_ERR_INVALID_HPAGE', 'Mohon nama homepage diisi dengan benar. Silakan ulangi lagi.');
define('_ERR_INVALID_PASSWORD', 'Password tidak valid.');
define('_ERR_INVALID_PASSWORD2', 'Kedua password anda harus sama.');
define('_ERR_INVALID_REDIRECT_URL', 'Url Redirect tidak valid');
define('_ERR_INVALID_SOURCE_URL', 'Url sumber dokumen tidak valid');
define('_ERR_INVALID_URL', 'URL yang anda isikan tidak valid');
define('_ERR_INVALID_USER_FULLNAME', 'Anda harus mengisi nama lengkap anda');
define('_ERR_INVALID_USER_NAME', 'Nama pemakai tidak valid.');
define('_ERR_LOGIN_FAILED_MESSAGE', 'User ID atau password salah. Silakan ulangi lagi.');
define('_ERR_LOGIN_FAILED_TITLE', 'Login Gagal');
define('_ERR_NEWS_NOT_FOUND_MESSAGE', 'Mohon maaf. Kabar baru yang anda minta tisak ada pada server ini.');
define('_ERR_NOT_USE_BROWSER', 'Permintaan anda ditolak. Anda harus gunakan browser internet untuk memproses permintaan anda.');
define('_ERR_OPR_DELETE', 'Permintaan anda ditolak. Hanya Administrator yang boleh menghapus folder/halaman.');
define('_ERR_OPR_DENIED_MESSAGE', 'Permintaan anda ditolak. Silakan coba permintaan yang lainnya.');
define('_ERR_OPR_DENIED_TITLE', 'Operasi Ditolak');
define('_ERR_OPR_MOVE', 'Permintaan anda ditolak. Hanya Administrator yang boleh memindahkan folder/halaman.');
define('_ERR_REGISTER_FAILED', '<p>Registrasi gagal. Anda menggunakan ID atau e-mail yang telah ada dengan salah satu anggota kami. Gunakan ID atau e-mail lain.</p>');
define('_ERR_SEND_EMAIL_FAILED', 'Pengiriman Email Gagal.');
define('_ERR_UNABLE_TO_CONNECT_DBASE', 'Gagal koneksi ke Basisdata.');
define('_ERR_UNABLE_TO_CONNECT_DBASE_HOST', 'Gagal koneksi Host ke Basisdata.');
define('_ERR_UNKNOWN_OPERATION', 'Operasi Tak dikenal');
define('_ERR_USER_EMAIL_NOT_MATCH', 'Email yang anda isikan tidak sesuai dengan e-mail anggota');
define('_ERR_USER_NAME_NOT_FOUND', 'Nama yang anda isikan tidak terdaftar sebagai anggota');


// ----------------------------------------------------------------------
// Lists
// ----------------------------------------------------------------------

$gLanguageList = array(
				'en' => 'Inggris',
				'id' => 'Indonesia',
			);


?>
