<? 
if(file_exists("settings.php")) { 
include "settings.php";
include "languages/$language.php";
}

if(file_exists("install.php")) {
echo "<font size=+2><b>PHP-Post</b></font><p/>
$txt_installonserver";
exit;
}

if ($connected != 1) {
include "common_db.inc";
error_reporting(0);
$link_id = db_connect();
if(!$link_id) die(sql_error());
if(!mysql_select_db($dbname)) die(sql_error());
$connected = 1;
}

if(isset($logincookie[user]) && !isset($logincookie[last]) || $mark == "read") {
include "lastvisit.php";
}

if(isset($logincookie[user])) {
$zonequery = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$logincookie[user]'");
$zone = mysql_result($zonequery, 0, "timezone");
}
else $zone = $deftimezone;

if(isset($msgid) && $s != "i") {
$query = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$msgid' ORDER BY posttime ASC");
$lastrepid = mysql_result($query, (mysql_num_rows($query) - 1), "msgnumber");
if ($lastrepid == "") {
$lastrepid = $msgid;
}
Setcookie("post[$msgid]","$lastrepid");
}

include "access.php";

?>