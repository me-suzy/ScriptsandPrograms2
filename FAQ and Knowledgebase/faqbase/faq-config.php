<?
################################
# MySQL Variables - Must be configured first!
$sqlhost = "";
$sqllogin = "";
$sqlpass = "";
$sqldb = "";
$table = "faq_entries";
$faqcats = "faq_categories";
################################

$adminpass = "theadmin"; //Password for Administration Area
$mainfile = "index.php";
$adminfile = "faq-admin.php";
$headerfile = "../../../head.php";
$footerfile = "../../../foot.php";
$faq_title = "FAQBase Demo";
$tablewidth = "90%";
$bordersize = "1";
$bordercolor = "#000000";
$cellspacing = "0";
$cellpadding = "2";
$bgcolor = "#507ca0";
$textcolor = "#FFFFFF";
$fontname = "Verdana, Arial, Helvetica, sans-serif";
$fontsize = "-1";

#This doesn't need to be edited. These lines are used to connect to MySQL.
$db = mysql_connect($sqlhost, $sqllogin, $sqlpass) or die("OOps!");
mysql_select_db($sqldb, $db);
?>