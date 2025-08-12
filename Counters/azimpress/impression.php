<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
include ("config.php");
mysql_connect($dbserver,$dbuser,$dbpass) or die ("Die Verbindung zum MySQL-Datenbankserver ist fehlgeschlagen");
mysql_select_db($db) or die ("Die benötigte Datenbank konnte nicht gefunden werden");

$abfrage=mysql_query("SELECT * FROM $dbtable");
while ($row=mysql_fetch_object($abfrage))
{
$counterstand=$row->counter;
}
echo $counterstand;
$counterstand++;
mysql_query("UPDATE $dbtable Set counter='$counterstand'");


?>
</body>
</html>
