<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 

$access_tablename = "${table_prefix}access";

$theip = $REMOTE_ADDR;
if(isset($logincookie[user])) {
$theuser = $logincookie[user];
}
else {
$theuser = "Guest";
}

$access_table_def = "NULL, '$theip', '$theuser', now()";

$timechecking = time() - 300;

$year = date("Y", $timechecking);
$month = date("m", $timechecking);
$day = date("d", $timechecking);
$hour = date("H", $timechecking);
$min = date("i", $timechecking);
$sec = date("s", $timechecking);

$timetocheck = $year.$month.$day.$hour.$min.$sec;

$currenttime = date("YmdHis");

if(!mysql_query("DELETE FROM $access_tablename WHERE time < '$timetocheck'")) die(sql_error());


$alreadythere = mysql_query("SELECT * FROM $access_tablename WHERE ip = '$theip'");
if (mysql_num_rows($alreadythere) > 0) {
if(!mysql_query("DELETE FROM $access_tablename WHERE ip = '$theip'")) die(sql_error());
}

if(!mysql_query("INSERT INTO $access_tablename VALUES($access_table_def)")) die(sql_error());

?>