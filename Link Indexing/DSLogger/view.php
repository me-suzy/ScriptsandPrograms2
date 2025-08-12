<?
include("config.php");
$curr = "$logs/" . $_SERVER['QUERY_STRING'] . "";
$creader = file_get_contents("$curr");
echo $creader;
?>