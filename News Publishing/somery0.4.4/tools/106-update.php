<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// 106-UPDATE.PHP > 03-11-2005

extract($_POST);
extract($_GET);
include("config.php");
mysql_connect($sqlhost, $sqluser, $sqlpass);
mysql_select_db($sqldb);

$query = "ALTER TABLE `".$prefix."settings` ADD `gmt` INT DEFAULT '0' NOT NULL AFTER `archive` ;"; 
$q = mysql_query($query) or die ("<font face=ff0000>upgrading sql table for somery 4-106 failed</font>");
echo "<font color=00ff00>sql update succeeded!</font><br>";
?>