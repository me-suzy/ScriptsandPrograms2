<?php
include "data.inc.php";
$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
mysql_select_db($DB_name);

$toDay = date("Y-m-d");
$Get = mysql_query("SELECT * FROM $Table_reminders WHERE date=\"$toDay\" && status=0");

while($R=mysql_fetch_object($Get)) {
	mail("$YOUR_EMAIL",$R->subject,$R->message,"FROM: My_DataBook@tacticalgaming.com");
	$Change = mysql_query("UPDATE $Table_reminders SET status=\"1\" WHERE R_ID=\"$R->R_ID\"");
}

mysql_close($CONNECT);

?>