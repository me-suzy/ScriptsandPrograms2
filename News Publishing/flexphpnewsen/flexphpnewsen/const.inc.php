<?php

//Database settings

$DBName = "dingyenews";
$DBUser = "root";
$DBPassword = "";
$DBHost = "localhost";
$adminemail = "";

//Front Language settings
$front_charset = "iso-8859-1";
$front_previouspage = "Previous page";
$front_nextpage = "Next page";
$front_submititle = "Title";
$front_categories = "Categories";
$front_indextitle = "Index";
$front_searchresult = "Search result";
$front_searchsubmit = "Search";
$front_latestnews = "Latest news";
$front_more = "More";
$front_rateit = "Rate it";
$front_rating = "Rating";
$front_letmerateit = "Let me rate it";
$front_ratebest = "best";
$front_ratesubmit = "Rate!";
$front_adddate = "Date of this item added";
$front_source = "Source of this article";

$front_latestonhomerecord = 10;
$front_latestoncatarecord = 20;
$front_searchresultrecord = 20;
$front_catnewsonhomerecord = 5;
$front_catnewsoncatarecord = 5;

//Admin Language settings
$admin_charset = "iso-8859-1";
$admin_url = "URL";
$admin_ok = "OK";
$admin_back = "Back";
$admin_add = "Add";
$admin_name = "Name";
$admin_del = "Del";
$admin_previouspage = "Previous page";
$admin_nextpage = "Next page";
$admin_news = "News";
$admin_delconfirm = "Really want to delete it?";
$admin_yes = "Yes";
$admin_no = "No";
$admin_edit = "Edit";
$admin_adminindex = "Admin index";
$admin_next = "Next";
$admin_reset = "Reset";
$admin_description = "Description";
$admin_adminsystem = "Admin system";
$admin_admin = "Admin";
$admin_welcome = "Welcome to admin system";
$admin_existing = "Existing";
$admin_opreation = "Opreation";
$admin_save = "Save";
$admin_picture = "Picture";
$admin_install = "install";
$admin_databasename = "Database Name";
$admin_databaseuser = "Database User";
$admin_databasepass = "Database Password";
$admin_databasehost = "Database Host";
$admin_adminemail = "Admin Email";
$admin_databasesetting = "Database setting";
$admin_setadminpassword = "Please choose username and password for administrator";
$admin_username = "username";
$admin_password = "password";
$admin_loginfail = "Wrong username or password";
$admin_login = "log in";
$admin_linkadded = "link added";
$admin_constisnotwriteable = "const.inc.php is not writeable,please change its permission";
$admin_catalogadmin = "Category admin";
$admin_newsadmin = "News admin";
$admin_parentcatalog = "Parent category";
$admin_catalogalreadyexist = "Category already exist";
$admin_catalog = "Category";
$admin_isdisplay = "Is displayed?";
$admin_none = "none";
$admin_title = "Title";
$admin_content = "Content";
$admin_viewnumber = "Hits";
$admin_rating = "Rating";
$admin_ratenumber = "Rate number";
$admin_source = "Source";
$admin_sourceurl = "Source URL";

$help_source = "If your text is from other source.";

if (count($HTTP_POST_VARS)) {
while (list($key, $val) = each($HTTP_POST_VARS)) {
$$key = $val;
}
}

if (count($HTTP_GET_VARS)) {
while (list($key, $val) = each($HTTP_GET_VARS)) {
$$key = $val;
}
}

if (count($HTTP_COOKIE_VARS)) {
while (list($key, $val) = each($HTTP_COOKIE_VARS)) {
$$key = $val;
}
}

if (count($HTTP_SESSION_VARS)) {
while (list($key, $val) = each($HTTP_SESSION_VARS)) {
$$key = $val;
}
}

?>