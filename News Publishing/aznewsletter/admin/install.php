<?php
include ("../config.php");
//Verbindung zur Datenbank herstellen
//---------------------------------------------------------------------------------------------
mysql_connect($dbserver,$dbuser,$dbpass) or die ("Die Verbindung zum Datenbankserver ist fehlgeschlagen");
mysql_select_db($db) or die ("Die benötigte Datenbank konnte nicht gefunden werden");
//---------------------------------------------------------------------------------------------
//neue Tabelle erstellen
$datenbank=mysql_query("CREATE TABLE $dbtable (
`ID` TINYINT( 8 ) NOT NULL AUTO_INCREMENT ,
`MAIL` VARCHAR( 150 ) NOT NULL ,
PRIMARY KEY ( `ID` ) 
)");
if ($datenbank==1)
{
echo "<font color=\"#009933\"><b>Die Datenbankstruktur für AZNEWSLETTER wurde erfolgreich angelegt !</b></font>";
echo "<br><br>";
echo "Es ist ratsam diese Datei vor Betrieb des Scripts wieder von Ihrem Server zu entfernen !";
echo "<br><br>";
echo "Viel Spaß mit AZNEWSLETTER";
}
else 
{
echo "<font color=\"#FF0000\"><b>Beim Anlegen der Datenbankstruktur traten Fehler auf !</b></font>";
echo "<br><br>";
echo "Bitte überprüfen Sie die Einstellungen in config.php oder setzten Sie sich mit Ihrem Webhoster in Verbindung !";
}
?>