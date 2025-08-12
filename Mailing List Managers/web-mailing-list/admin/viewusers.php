<?php
include("connect.php");
$link = "SELECT * FROM users";
$res = mysql_query($link) or die(mysql_error());
echo '
<html>
<head><title>View Users</title></head>
<body>
<table border="1" cellpadding="2">
<tr>
<td><b>Email</b></td>
<td><b>Edit</b></td>
<td><b>Delete</b></td>
<td><b>Subscribed (Click to Change)</b></td>
<td><b>Self-Unsubscrbied</b></td>
</tr>';

while ($r = mysql_fetch_assoc($res))
{
	if ($r['unsubscribed'] == "1")
		$b = "<b>Yes</b>";
	else
		$b = "No";
		
	if ($r['status'] == "subscribed")
		$a = "Yes";
	else
		$a = "No";
	
	echo '<tr>
	<td>' . $r['email'] . '</td>
	<td><a href="edituser.php?id=' . $r['id'] . '">Edit</a></td>
	<td><a href="deleteuser.php?id=' . $r['id'] . '">Delete</a></td>
	<td><a href="subscribed.php?id=' . $r['id']  . '">' . $a . '</a></td>
	<td>' . $b . '</td></tr>';
}
?>


