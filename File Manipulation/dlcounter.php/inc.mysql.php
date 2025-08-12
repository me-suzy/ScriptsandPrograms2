<?
/*
you can include this in your scripts:
  include("inc.mysql.php");
this will open a new mysql connection
with your mysql data and selects the
database set below. then you're free
to execute mysql queries:
  $result = mysql_query($query);
*/

/* mysql data */
$db_host = "localhost";
$db_user = "username";
$db_pass = "password";
$db_name = "database";

/* connect to mysql */
$link = @mysql_connect($db_host, $db_user, $db_pass);

/* select database */
mysql_select_db($db_name, $link);
?>