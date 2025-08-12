<?php
include("connect.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$name = htmlentities($_POST['name']);
	$email =  htmlentities($_POST['email']);
	$date =  htmlentities($_POST['date']);
	$id =  htmlentities($_POST['id']);
	
	if ($name == "" || $email == "" || $date == "" || $id == "")
	die("Please fill out the whole form.");
	
	$link = "UPDATE users SET name='$name',email='$email',`date`='$date' WHERE id='$id'";
	$res = mysql_query($link) or die(mysql_error());
	if ($res)
	die("Updated Succesfully.<br />Click <a href='index.php'>here</a> to go back.");	
}
else
{
	$id = preg_replace("/'\/<>\"/","",$_GET['id']);
	if (empty($id))
	die("Invalid ID");	
	$link = "SELECT * FROM users WHERE id='$id'";
	$res = mysql_query($link) or die(mysql_error());
	$r = mysql_fetch_assoc($res);
	
	echo '
	<html>
	<head><title>Edit User</title></head>
	<body>
	<form action="" method="POST">
	Name <input type="text" name="name" value="' . $r['name'] . '"><br />
	Email <input type="text" name="email" value="' . $r['email'] . '"><br />
	Date <input type="text" name="date" value="' . $r['date'] . '"><br /><br />
	<input type="hidden" name="id" value="' . $r['id'] . '">
	<input type="submit" value="Edit User">
	</form>
	</body>
	</html>';
}
?>
