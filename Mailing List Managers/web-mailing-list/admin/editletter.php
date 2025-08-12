<?php
include("connect.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$name = htmlentities($_POST['name']);
	$con = nl2br(htmlentities($_POST['con']));
	$id = htmlentities($_POST['id']);

	if ($id == "" || $name == "" || $con == "")
	die("Please fill out the whole form.");
	
	$link = "UPDATE newsletters SET `name`='$name',`content`='$con' WHERE id='$id'";
	$res = mysql_query($link) or die(mysql_error());
	if ($res)
	die("Updated.<br />Click <a href='index.php'>here</a> to go back.");
}
else
{
	$id = preg_replace("/'\/<>\"/","",$_GET['id']);
	if (empty($id))
	die("Invalid ID");
	$link = "SELECT * FROM newsletters WHERE id='$id'";
	$res = mysql_query($link) or die(mysql_error());
	$r = mysql_fetch_assoc($res);
	
	echo '<html><head><title>Edit Letter</title></head>
	<body>
	<form action="" method="POST">
	Name: <input type="text" name="name" value="' . $r['name'] . '"><br />
	<textarea name="con" cols="100" rows="30">' .  $r['content'] . '</textarea><br /><br />
	<input type="hidden" name="id" value="' . $r['id'] . '">
	<input type="submit" value="Update Letter">
	</form>
	</body>
	</html>';
}
?>
	
	
	
	
