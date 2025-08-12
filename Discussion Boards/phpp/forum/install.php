<? echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PHP-Post | Install</title>
</head>
<body>
<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 

if(isset($submit)) {

if($sitename != "" && $siteemail != "" && $dbhost != "" && $dbusername != "" && $dbuserpassword != "" && $dbname != "" && $userid != "" && $userpassword != "" && $userpassword2 != "" && $useremail != "" && $userpassword == $userpassword2) {

$sitename = str_replace("\'", "'", $sitename);

$filecontents = "<?

\$language = \"$language\";
\$sitename = \"$sitename\";
\$template = \"$template\";
\$menutemplate = \"$menutemplate\";
\$deftimezone = \"$deftimezone\";
\$table_prefix = \"$table_prefix\";
\$dbhost = \"$dbhost\";
\$dbusername = \"$dbusername\";
\$dbuserpassword = \"$dbuserpassword\";
\$dbname = \"$dbname\";
\$postsperpage = \"$postsperpage\";
\$threadsperpage = \"$threadsperpage\";
\$hottopic = \"$hottopic\";
\$guestpost = \"$guestpost\";
\$siteemail = \"$siteemail\";
\$forumurl = \"$forumurl\";
\$dateform = \"$dateform\";
\$timeform = \"$timeform\";
\$split = \"$split\";
\$avatars = \"$avatars\";
\$gd = \"$gd\";

?>";

$handle= fopen("settings.php","w");
fputs($handle, $filecontents);
fclose($handle);

include "common_db.inc";

$user_tablename = "${table_prefix}users";
$user_table_def = "usernumber MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$user_table_def .= "userid VARCHAR(15) NOT NULL,";
$user_table_def .= "userpassword VARCHAR(20) BINARY NOT NULL,";
$user_table_def .= "username VARCHAR(30) NOT NULL,";
$user_table_def .= "userlocation VARCHAR(50) NOT NULL,";
$user_table_def .= "useremail VARCHAR(50) NOT NULL,";
$user_table_def .= "userprofile TEXT NOT NULL,";
$user_table_def .= "registerdate DATE DEFAULT '0000-00-00' NOT NULL,";
$user_table_def .= "lastaccesstime TIMESTAMP(14),";
$user_table_def .= "usermsn TEXT NOT NULL,";
$user_table_def .= "useraol TEXT NOT NULL,";
$user_table_def .= "usericq TEXT NOT NULL,";
$user_table_def .= "useryahoo TEXT NOT NULL,";
$user_table_def .= "userhomepage TEXT NOT NULL,";
$user_table_def .= "usersig TEXT NOT NULL,";
$user_table_def .= "userdob TEXT NOT NULL,";
$user_table_def .= "usersex TEXT NOT NULL,";
$user_table_def .= "dispemail TINYINT(1) DEFAULT '0' NOT NULL,";
$user_table_def .= "imgsig TINYINT(1) DEFAULT '0' NOT NULL,";
$user_table_def .= "pmnotify TINYINT(1) DEFAULT '0' NOT NULL,";
$user_table_def .= "timezone INT(3) DEFAULT '0' NOT NULL,";
$user_table_def .= "PRIMARY KEY (userid),";
$user_table_def .= "UNIQUE usernumber (usernumber)";

$public_tablename = "${table_prefix}public";
$public_table_def = "msgnumber MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$public_table_def .= "userfrom VARCHAR(15) NOT NULL,";
$public_table_def .= "subject TEXT NOT NULL,";
$public_table_def .= "message TEXT NOT NULL,";
$public_table_def .= "reply VARCHAR(15) NOT NULL,";
$public_table_def .= "posttime TIMESTAMP(14),";
$public_table_def .= "ip VARCHAR(15) NOT NULL,";
$public_table_def .= "PRIMARY KEY (msgnumber),";
$public_table_def .= "KEY totally (userfrom, posttime)";

$private_tablename = "${table_prefix}private";
$private_table_def = "msgnumber MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$private_table_def .= "userfrom VARCHAR(15) NOT NULL,";
$private_table_def .= "userto VARCHAR(15) NOT NULL,";
$private_table_def .= "subject TEXT NOT NULL,";
$private_table_def .= "message TEXT NOT NULL,";
$private_table_def .= "msgread TINYINT(1) DEFAULT '0' NOT NULL,";
$private_table_def .= "posttime TIMESTAMP(14),";
$private_table_def .= "PRIMARY KEY (msgnumber),";
$private_table_def .= "KEY thekey (userfrom, userto, posttime)";

$threads_tablename = "${table_prefix}threads";
$threads_table_def = "threadid MEDIUMINT(10) NOT NULL,";
$threads_table_def .= "forum MEDIUMINT(10) NOT NULL,";
$threads_table_def .= "lastrepid MEDIUMINT(10) NOT NULL,";
$threads_table_def .= "lastreptime TIMESTAMP(14),";
$threads_table_def .= "number MEDIUMINT(10) NOT NULL AUTO_INCREMENT,";
$threads_table_def .= "locked TINYINT(1) NOT NULL,";
$threads_table_def .= "type CHAR(1) NOT NULL,";
$threads_table_def .= "PRIMARY KEY (number)";

$forums_tablename = "${table_prefix}forums";
$forums_table_def = "forumno MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$forums_table_def .= "forumname VARCHAR(100) NOT NULL,";
$forums_table_def .= "forumdesc VARCHAR(255) NOT NULL,";
$forums_table_def .= "restricted TINYINT(1) DEFAULT '0' NOT NULL,";
$forums_table_def .= "locked TINYINT(1) DEFAULT '0' NOT NULL,";
$forums_table_def .= "fororder MEDIUMINT(10) DEFAULT '0' NOT NULL,";
$forums_table_def .= "cat MEDIUMINT(10) DEFAULT '0' NOT NULL,";
$forums_table_def .= "PRIMARY KEY (forumno),";
$forums_table_def .= "UNIQUE uniquecatplace (fororder, cat),";
$forums_table_def .= "UNIQUE uniquename (forumname, cat)";

$rights_tablename = "${table_prefix}userrights";
$rights_table_def = "autonumber MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$rights_table_def .= "userid VARCHAR(15) NOT NULL,";
$rights_table_def .= "access TEXT NOT NULL,";
$rights_table_def .= "mod TEXT NOT NULL,";
$rights_table_def .= "admin TINYINT(1) NOT NULL,";
$rights_table_def .= "overalladmin TINYINT(1) NOT NULL,";
$rights_table_def .= "PRIMARY KEY (autonumber),";
$rights_table_def .= "UNIQUE rightsname (userid)";

$categories_tablename = "${table_prefix}categories";
$categories_table_def = "catno MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$categories_table_def .= "catname VARCHAR(100) NOT NULL,";
$categories_table_def .= "catorder MEDIUMINT(10) DEFAULT '0' NOT NULL,";
$categories_table_def .= "PRIMARY KEY (catno),";
$categories_table_def .= "UNIQUE test (catname)";

$access_tablename = "${table_prefix}access";
$access_table_def = "accessid MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$access_table_def .= "ip VARCHAR(15) NOT NULL,";
$access_table_def .= "userid VARCHAR(15) NOT NULL,";
$access_table_def .= "time TIMESTAMP(14),";
$access_table_def .= "PRIMARY KEY (accessid)";

$ipbans_tablename = "${table_prefix}ipbans";
$ipbans_table_def = "autono MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$ipbans_table_def .= "ip VARCHAR(15) NOT NULL,";
$ipbans_table_def .= "PRIMARY KEY (autono)";

$report_tablename = "${table_prefix}reported";
$report_table_def = "autonumber MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$report_table_def .= "threadid MEDIUMINT(10) NOT NULL,";
$report_table_def .= "repby VARCHAR(15) NOT NULL,";
$report_table_def .= "repmsg TEXT NOT NULL,";
$report_table_def .= "time TIMESTAMP(14),";
$report_table_def .= "PRIMARY KEY (autonumber)";

$notif_tablename = "${table_prefix}notif";
$notif_table_def = "autono MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$notif_table_def .= "username VARCHAR(15) NOT NULL DEFAULT '',";
$notif_table_def .= "threadid MEDIUMINT(10) DEFAULT '0' NOT NULL,";
$notif_table_def .= "replies TINYINT(1) NOT NULL DEFAULT '0',";
$notif_table_def .= "PRIMARY KEY (autono),";
$notif_table_def .= "UNIQUE uni (username,threadid)";

$userbans_tablename = "${table_prefix}userbans";
$userbans_table_def = "autono MEDIUMINT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,";
$userbans_table_def .= "user VARCHAR(15) NOT NULL,";
$userbans_table_def .= "PRIMARY KEY (autono)";

error_reporting(0);

$link_id = db_connect();
if(!$link_id) die(sql_error());

if(!mysql_select_db($dbname)) die(sql_error());

if(!mysql_query("CREATE TABLE $user_tablename ($user_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $public_tablename ($public_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $private_tablename ($private_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $threads_tablename ($threads_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $forums_tablename ($forums_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $rights_tablename ($rights_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $access_tablename ($access_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $ipbans_tablename ($ipbans_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $report_tablename ($report_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $categories_tablename ($categories_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $notif_tablename ($notif_table_def)")) die (sql_error());
if(!mysql_query("CREATE TABLE $userbans_tablename ($userbans_table_def)")) die (sql_error());

$user_tablename = "${table_prefix}users";
$user_table_def = "NULL, '$userid', password('$userpassword'), '$username', '', '$useremail', '', curdate(), NULL, '', '', '', '', '', '', '0000-00-00', '0', '$dispemail', '0', '0', '0'";
$userrights_tablename = "${table_prefix}userrights";
$userrights_table_def = "NULL, '$userid', '', '', '1', '1'";

if(!mysql_query("INSERT INTO $user_tablename VALUES($user_table_def)")) die (sql_error());
if(!mysql_query("INSERT INTO $userrights_tablename VALUES($userrights_table_def)")) die (sql_error());

echo "<font size=\"+2\"><b>PHP-Post installer</b></font><p/>

The forum install has completed successfully. You must now delete the <i>install.php</i> file from your server before you can continue.<p/>

Once you have logged in, you can complete your own profile and set up the forums.<p/>

If the file has been deleted, click <a href=\"index.php\">here</a> to continue.";
}
else {

echo "<font size=\"+2\"><b>PHP-Post installer</b></font><p/>

Sorry, the install form hasn't been completed correctly. Please go <a href=\"javascript:history.back(-1)\">back</a> and try again.";
}
}

else {

echo "<font size=\"+2\"><b>PHP-Post installer</b></font><p/>

Welcome to PHP-Post. You must enter the following details before you start.<p/>

<form method=\"post\" action=\"install.php\"><table border=\"0\" cellspacing=\"2\" cellpadding=\"0\">

<tr><td colspan=\"3\"><b>Site settings</b></td></tr>
<tr><td>Language:</td><td><select name=\"language\">";
$dirpos = "languages/";
$dir = opendir($dirpos);
while (false !== ($file = readdir($dir))) {
$dispfile = str_replace(".php", "", $file);
if($file != "." && $file != ".." && $file != "index.htm") {
echo "<option value=\"$dispfile\"";
if($dispfile == $language) echo " selected=\"selected\"";
echo">$dispfile</option>";
}
}
echo "</select></td></tr>
<tr><td width=\"150\">Site name:</td><td><input type=\"text\" name=\"sitename\"/></td><td>The name of your site appears at the top of each forum page</td></tr>
<tr><td>Site URL:</td><td><input type=\"text\" name=\"forumurl\"/></td><td>The address of the folder your forum is installed in - should end with a /</td></tr>
<tr><td>Site e-mail address:</td><td><input type=\"text\" name=\"siteemail\"/></td><td>The address e-mails sent from the site will appear from</td></tr>
<tr><td>Menu style:</td><td><input type=\"text\" name=\"menutemplate\" value=\"&lt;td class=&quot;menumid&quot;&gt;&lt;a href=&quot;#link#&quot; class=&quot;menu&quot;&gt;#text#&lt;/a&gt;&lt;/td&gt;&lt;td class=&quot;menurt&quot;&gt;&lt;/td&gt;\"/></td><td>The menu link style - goes with individual templates</td></tr>


<tr><td colspan=\"3\"><b>MySQL settings</b></td></tr>

<tr><td>Database host:</td><td><input type=\"text\" name=\"dbhost\"/></td><td>The address of your MySQL server</td></tr>
<tr><td>Database username:</td><td><input type=\"text\" name=\"dbusername\"/></td><td>Your MySQL username</td></tr>
<tr><td>Database password:</td><td><input type=\"text\" name=\"dbuserpassword\"/></td><td>Your MySQL password</td></tr>
<tr><td>Database name:</td><td><input type=\"text\" name=\"dbname\"/></td><td>The name of your MySQL database</td></tr>
<tr><td>Table prefix:</td><td><input type=\"text\" name=\"table_prefix\" value=\"phpp_\"/></td><td>If the database is shared between forums, change this</td></tr>
<tr><td colspan=\"3\"><b>Administrator settings</b></td></tr>
<tr><td>User ID:</td><td><input type=\"text\" name=\"userid\"/></td></tr>
<tr><td>Password:</td><td><input type=\"password\" name=\"userpassword\"/></td></tr>
<tr><td>Confirm password:</td><td><input type=\"password\" name=\"userpassword2\"/></td></tr>
<tr><td>Real name:</td><td><input type=\"text\" name=\"username\"/></td></tr>
<tr><td>E-mail address:</td><td><input type=\"text\" name=\"useremail\"/></td></tr>
<tr><td>Display e-mail?</td><td><input type=\"radio\" name=\"dispemail\" value=\"1\" checked=\"checked\"/> Yes <input type=\"radio\" name=\"dispemail\" value=\"0\"/> No</td></tr>


<tr><td colspan=\"3\"><b>General settings</b></td></tr>
<tr><td>Template:</td><td><select name=\"template\">";

$handle = opendir('templates/');
while (false !== ($file = readdir($handle))) {
$file = str_replace("_h.php", "", $file);
if (!stristr($file,".css") && !stristr($file,".php")) {
if ($file != "index.htm" && $file != "." && $file != "..") {

echo "<option value=\"$file\">$file</option>";

}
}
}
closedir($handle);


echo "</select>
</td><td>The template for your forum's design</td></tr>
<tr><td>Menu style:</td><td><input type=\"text\" name=\"menutemplate\" value=\"&lt;td class=&quot;menumid&quot;&gt;&lt;a href=&quot;#link#&quot; class=&quot;menu&quot;&gt;#text#&lt;/a&gt;&lt;/td&gt;&lt;td class=&quot;menurt&quot;&gt;&lt;/td&gt;\"/></td></tr>
<tr><td>Splitter character:</td><td><input type=\"text\" name=\"split\" value=\"»\"/></td><td>Used to divide sections of text</td></tr>

<tr><td>Default time zone:</td><td><select name=\"deftimezone\">";

for ($i = -24; $i < 24; $i++) {

echo "<option value=\"$i\"";

if ($i == 0) echo " selected=\"selected\"";

echo ">$i</option>";

}

$current = date("D, M d, Y H:i:s");

echo "</select></td><td>Current server time is $current - auto-adjust by changing this</td></tr>
<tr><td>Posts per page:</td><td><select name=\"postsperpage\">";

for ($i = 1; $i <= 100; $i++) {
echo "<option value=\"$i\"";

if ($i == 10) echo " selected=\"selected\"";

echo ">$i</option>";

}

echo "</select></td><td>The number of posts per page in a topic</td></tr>
<tr><td>Topics per page:</td><td><select name=\"threadsperpage\">";

for ($i = 1; $i <= 100; $i++) {
echo "<option value=\"$i\"";

if ($i == 20) echo " selected=\"selected\"";

echo ">$i</option>";

}

echo "</select></td><td>The number of topics which will appear on each forum page</td></tr>
<tr><td>Posts for hot topic:</td><td><select name=\"hottopic\">";

for ($i = 1; $i <= 100; $i++) {
echo "<option value=\"$i\"";

if ($i == 25) echo " selected=\"selected\"";

echo ">$i</option>";

}

echo "</select></td><td>The number of posts required before a topic becomes &quot;hot&quot;</td></tr>
<tr><td>Allow guests to post?</td><td><input type=\"radio\" name=\"guestpost\" value=\"1\"/> Yes <input type=\"radio\" name=\"guestpost\" value=\"0\" checked=\"checked\"/> No</td><td>Users can post without registering if this is selected</td></tr>
<tr><td>Date format:</td><td><input type=\"text\" name=\"dateform\" value=\"M d, Y\"/></td><td rowspan=\"2\">The way dates and times appear on the site - uses <a href=\"http://www.php.net/date\" target=\"_blank\">PHP Date</a> syntax.</td></tr>
<tr><td>Time format:</td><td><input type=\"text\" name=\"timeform\" value=\"H:i \\o\\n D, M d\"/></td></tr>
<tr><td>Show avatars?</td><td><input type=\"radio\" name=\"avatars\" value=\"1\"/> Yes <input type=\"radio\" name=\"avatars\" value=\"0\" checked=\"checked\"/> No</td></tr>
<tr><td>GD version:</td><td><select name=\"gd\">
<option value=\"0\" selected=\"selected\">None</option>
<option value=\"1\">1</option><option value=\"2\">2</option>
</select></td></tr>
<tr><td colspan=\"3\"><input type=\"submit\" value=\"Install\"/><input type=\"hidden\" name=\"submit\" value=\"y\"/></td></tr>
</table></form>";
}
?>
</body>
</html>