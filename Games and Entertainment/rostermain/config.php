<?
ob_start();
session_start();
header("Cache-control: private"); //IE 6 Fix 
$db_conn = mysql_connect("database host here", "database user name here", "database password here") or die("unable to connect to the database");
  mysql_select_db("database name here", $db_conn) or die("unable to select the database");
if(!isset($_REQUEST['page'])||($_REQUEST['page']==NULL))
{
$page="index";
}
else
{
$page=$_REQUEST['page'];
}
?>

