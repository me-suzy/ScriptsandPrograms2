<?php
include ("config.php");
//Verbindung zur Datenbank herstellen
//---------------------------------------------------------------------------------------------
mysql_connect($dbserver,$dbuser,$dbpass) or die ("Die Verbindung zum Datenbankserver ist fehlgeschlagen");
mysql_select_db($db) or die ("Die benötigte Datenbank konnte nicht gefunden werden");
//---------------------------------------------------------------------------------------------
//neue Tabelle erstellen
$datenbank=mysql_query ("CREATE TABLE $dbtable (`counter` VARCHAR( 10 ) NOT NULL );");
if ($datenbank==1)
{
$startwert=mysql_query ("INSERT INTO `azimpression` ( `counter` ) VALUES ('0');");
echo "<font color=\"#009933\"><b>Die Datenbankstruktur für AZIMPRESSION wurde erfolgreich angelegt !</b></font>";
echo "<br><br>";
echo "Es ist ratsam diese Datei vor Betrieb des Scripts wieder von Ihrem Server zu entfernen";
echo "<br><br>";
echo "Viel Spaß mit AZIMPRESSION";
}
else 
{
echo "<font color=\"#FF0000\"><b>Beim Anlegen der Datenbankstruktur traten Fehler auf !</b></font>";
echo "<br><br>";
echo "Bitte überprüfen Sie die Einstellungen in config.php oder setzten Sie sich mit Ihrem Webhoster in Verbindung !";
}
?>