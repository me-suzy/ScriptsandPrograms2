<?php
/* database settings */

$GB_DB["dbName"] = "";
$GB_DB["host"]   = "localhost";
$GB_DB["user"]   = "root";
$GB_DB["pass"]   = "";

/* tables */

$GB_TBL["data"]  = "book_data";
$GB_TBL["auth"]  = "book_auth";
$GB_TBL["cfg"]   = "book_config";
$GB_TBL["com"]   = "book_com";
$GB_TBL["ip"]    = "book_ip";
$GB_TBL["words"] = "book_words";
$GB_TBL["ban"]   = "book_ban";
$GB_TBL["priv"]  = "book_private";
$GB_TBL["smile"] = "book_smilies";
$GB_TBL["pics"]  = "book_pics";

/* guestbook pages */

$GB_PG["index"]    = "index.php";
$GB_PG["admin"]    = "admin.php";
$GB_PG["comment"]  = "comment.php";
$GB_PG["addentry"] = "addentry.php";


/* guestbook templates */

$GB_TPL["adm_enter"]  = "admin_enter.php";
$GB_TPL["body"]       = "body.php";
$GB_TPL["entry"]      = "entry.php";
$GB_TPL["error"]      = "error.php";
$GB_TPL["form"]       = "form.php";
$GB_TPL["preview"]    = "preview.php";
$GB_TPL["prev_entry"] = "preview_entry.php";
$GB_TPL["header"]     = "header.php";
$GB_TPL["footer"]     = "footer.php";
$GB_TPL["icq"]        = "icq.php";
$GB_TPL["url"]        = "url.php";
$GB_TPL["aim"]        = "aim.php";
$GB_TPL["com"]        = "com.php";
$GB_TPL["email"]      = "email.php";
$GB_TPL["success"]    = "success.php";
$GB_TPL["frm_icq"]    = "form_icq.php";
$GB_TPL["frm_aim"]    = "form_aim.php";
$GB_TPL["frm_gender"] = "form_gender.php";
$GB_TPL["frm_image"]  = "form_image.php";
$GB_TPL["com_pass"]   = "com_pass.php";
$GB_TPL["com_form"]   = "comment.php";
$GB_TPL["image"]      = "user_pic.php";

/* misc */

define('IS_MODULE', false);  /* running as POST-Nuke 0.x or PHP-Nuke 5.x addon? */ 

$GB_PG["base_url"] = "";  /* e.g htpp://www.yourdomain.com/guestbook/img */

$DB_CLASS  = "mysql.class.php";
$TEC_MAIL  = "you_at_your_domain_dot_com";
$GB_UPLOAD = "public";
$GB_TMP    = "tmp";

if ($GB_PG["base_url"] == "") {
    $inter_type = php_sapi_name();
    if ($inter_type == "cgi") {
        if (isset($HTTP_SERVER_VARS["PATH_INFO"]) && !empty($HTTP_SERVER_VARS["PATH_INFO"])) {
            $GB_PG["base_url"] = dirname($HTTP_SERVER_VARS["PATH_INFO"]);
        } elseif (isset($HTTP_SERVER_VARS["REQUEST_URI"]) && !empty($HTTP_SERVER_VARS["REQUEST_URI"])) {
            $GB_PG["base_url"] = dirname($HTTP_SERVER_VARS["REQUEST_URI"]);
        } else {
            $GB_PG["base_url"] = dirname($HTTP_SERVER_VARS["SCRIPT_NAME"]);
        }
    } else {
        $GB_PG["base_url"] = dirname($HTTP_SERVER_VARS["PHP_SELF"]);
    }
}

?>