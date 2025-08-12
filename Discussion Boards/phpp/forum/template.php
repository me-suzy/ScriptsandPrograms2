<?
if(file_exists("templates/${template}_h.php")) {
include "templates/${template}_h.php";
$doneheader = 1;
}
else {
echo "<b>$txt_error</b><p/>

$txt_templatenotexist";
exit;
}

if(isset($logincookie[user])) {
$userbannedquery = mysql_query("SELECT * FROM ${table_prefix}userbans WHERE user='$logincookie[user]'");
if (mysql_num_rows($userbannedquery) > 0) {
echo "<head><title>$sitename &gt; $txt_userbanned</title></head>
<font class=\"header\"><b>$sitename &gt; $txt_userbanned</b></font><p/>

$txt_userbanmsg";
exit;
}
}

$currentip = $_SERVER[REMOTE_ADDR];
$ipbannedquery = mysql_query("SELECT * FROM ${table_prefix}ipbans WHERE ip='$currentip'");
if(mysql_num_rows($ipbannedquery) > 0) {
echo "<head><title>$sitename &gt; $txt_ipban</title></head>
<font class=\"header\"><b>$sitename &gt; $txt_ipban</b></font><p/>

$txt_ipbanmsg";
exit;
}
?>