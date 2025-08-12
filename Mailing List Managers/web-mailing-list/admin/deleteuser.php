<?php
include("connect.php");
$id = preg_replace("/'\/<>\"/","",$_GET['id']);
if (empty($id) || !is_numeric($id))
die("Invalid ID");
$link = "DELETE FROM users WHERE id='$id'";
$res = mysql_query($link) or die(mysql_error());
if ($res)
die("Succesfully Deleted.<br />Click <a href='index.php'>here</a> to go back.");
?>	
	