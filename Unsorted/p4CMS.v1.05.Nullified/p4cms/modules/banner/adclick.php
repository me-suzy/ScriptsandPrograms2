<?
ob_start();
 include("../../include/config.inc.php"); 
 include("../../include/mysql-class.inc.php");
 include("../../include/functions.inc.php");
 
$sql =& new MySQLq();
$sql->Query("SELECT * FROM " . $sql_prefix . "banner where id='$_REQUEST[bannerid]'");
$row = $sql->FetchRow();

$linkto = $row->target;

$sql =& new MySQLq();
$sql->Query("UPDATE " . $sql_prefix . "banner SET hits=hits+1 where id='$_REQUEST[bannerid]'");
header("location:$linkto");
?>