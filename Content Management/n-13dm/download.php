<?php
include 'config.php';
ob_start();
$category = $_GET['cat'];
$download = $_GET['download'];
$fileid = $_GET['download'];
if($category == ""){echo "File not found"; die;}
if($download == ""){echo "File not found"; die;}

$query = mysql_query("SELECT downloadurl FROM $category WHERE fileid=$fileid");
$row = mysql_fetch_array($query) or die ("File not found");
$downloadurl = mysql_result($query,0);
$filename = basename($downloadurl);

$query2 = mysql_query("SELECT downloads from $category WHERE fileid=$fileid");
$downloads = mysql_result($query2,0);
$downloads = $downloads + 1;
echo $downloads;
$sql = "UPDATE $category SET downloads = $downloads WHERE fileid=$fileid";
$blah = mysql_query($sql);

$blah = mysql_result($sql,0);

header("Location: $downloadurl");
ob_end_flush();

?>