<?php
include("connect.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$email = preg_replace("#'//\<>#","",$_POST['email']);
	if (empty($email))
	die("Please enter an email address.");
	$link = "UPDATE users SET status='un',unsubscribed='1' WHERE email='$email'";
	$res = mysql_query($link) or die(mysql_error());
	if ($res)
	die("Succesfully Unsubscrbied.");
}
else
{
	echo '
	<html><head><title>Unsubscribe</title></head>
	<body>
	<form action="" method="POST">
	Email <input type="text" name="email"><br /><br />
	<input type="submit" value="Unsubscribe">
	</form>
	</body>
	</html>';
}
?>
	
	
	
	
	
	
	