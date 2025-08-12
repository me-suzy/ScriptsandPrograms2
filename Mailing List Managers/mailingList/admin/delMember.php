<?php
session_start();
if(@$_SESSION['admin'] != 1)
{
	header("location: login.php");
	exit();
}
/*Under the terms and condition of GPL license, you may use this software freely
  as long as you retain our copyright. I would like to thanks you for appriciating
  my time and effort contributed to this project.
  ~David Ausman - Hotwebtools.com 2005*/
include '../inc/config.php';
include '../inc/conn.php';
$q = mysql_query("delete from mailList where id = '".$id."'");
if($q)
{
	mysql_close($conn);
	header("location: manageSubs.php");
}
?>