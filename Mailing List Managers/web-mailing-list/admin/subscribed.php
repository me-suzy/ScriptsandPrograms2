<?php
include("connect.php");

	$id = preg_replace("/'\/<>\"/","",$_GET['id']);
	if (empty($id))
	die("Invalid ID");	
	$link = "SELECT * FROM users WHERE id='$id'";
	$res = mysql_query($link) or die(mysql_error());
	$r = mysql_fetch_assoc($res);
	
	if ($r['status'] == "subscribed")
	$up = "un";
	elseif ($r['status'] == "un")
	$up = "subscribed";
	
	$link = "UPDATE users SET status='$up' WHERE id='$id'";
	$res = mysql_query($link) or die(mysql_error());
	if ($res)
	die("Updated.<br />Click <a href='index.php'>here</a> to go back.");
?>