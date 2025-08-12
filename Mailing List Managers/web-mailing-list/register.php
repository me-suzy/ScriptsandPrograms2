<?php
include("connect.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if (!preg_match('/([a-zA-z0-9\.\-]+)@([a-zA-Z0-9\.\-]+)\.([a-zA-Z]{2,3})/',$_POST['email'],$m))
	die('Invalid Email Address.');	
	$name = htmlentities($_POST['name']);
	
	if (empty($name))
	die("Please fill in out the whole form.");
	$email = $m[0];
	$link = "SELECT * FROM users WHERE email='$email'";
	$res = mysql_query($link) or die(mysql_error());
	
	if (mysql_num_rows($res) > 0)
	die("This email address is already registered.");	
	
	$link = "INSERT INTO users VALUES ('$name','$email',NOW(),'subscribed','0','')";
	$res= mysql_query($link) or die(mysql_error());
	if ($res)
	die("Email Succesfully Registerted.");
}
else
{
	echo '<html><head><title>Sign Up</title></head>
	<body>
	<form action="" method="POST">
	Name <input type="text" name="name"><br />
	Email&nbsp; <input type="text" name="email"><br /><br />
	<input type="submit" value="Sign up">
	</form>
	</body>
	</html>';
}
?>
	
	
	
	
	
	
	