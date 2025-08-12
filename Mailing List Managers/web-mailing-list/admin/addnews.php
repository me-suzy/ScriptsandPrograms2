<?php
include("connect.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$name = htmlentities($_POST['name']);
	$content = htmlentities($_POST['content']);
	
	if (empty($name))
	die("Please fill out the name section.");
	if (empty($content))
	die("Please fill out the content section.");
	
	$link = "INSERT INTO newsletters VALUES('$name','$content','')";
	$res = mysql_query($link) or die(mysql_error());
	if ($res)
	die("Succesfully inserted.<br />Click <a href='index.php'>here</a> to go back.");
}
else
{
	echo '<html><head><title>Add News</title></head>
	<body>
	<form action="" method="POST">
	Name <input type="text" name="name" size="30"><br /><br />
	<textarea name="content" cols="100" rows="30">Content</textarea><br /><br />
	<input type="submit" value="Add News">
	</form>
	</body>
	</html>';
}
?>
	
