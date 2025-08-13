<?
echo "starting";
$mysql_host = "localhost";
$mysql_user = "rmonk";
$mysql_db = "ezsellswizard.com";
$mysql_password = "welcome";
mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_db) || die("Could not connect to SQL db");
echo "all ok";
?>
