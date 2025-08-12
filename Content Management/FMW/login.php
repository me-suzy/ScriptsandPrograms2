<?php
// database connect script.
require 'db_connect.php';


?>

<html>
<head>

<title>Login</title>
</head>
<body>
<body bgcolor="#000000">

<p align="center"><img src="images/logo.gif">
<br>
 

<?php

if (isset($_POST['submit'])) { // if form has been submitted


	/* check they filled in what they were supposed to and authenticate */
	if(!$_POST['uname'] | !$_POST['passwd']) {
	?><font color="#FFFFFF"><?php die('You did not fill in a required field.');
	}

	// authenticate.

	if (!get_magic_quotes_gpc()) {
		$_POST['uname'] = addslashes($_POST['uname']);
	}

	$check = $db_object->query("SELECT username, password FROM users WHERE username = '".$_POST['uname']."'");

	if (DB::isError($check) || $check->numRows() == 0) {
	?><font color="#FFFFFF"><?php	die('That username does not exist in our database.');
	}

	$info = $check->fetchRow();

	// check passwords match

	$_POST['passwd'] = stripslashes($_POST['passwd']);
	$info['password'] = stripslashes($info['password']);
	$_POST['passwd'] = md5($_POST['passwd']);

	if ($_POST['passwd'] != $info['password']) {
	?><font color="#FFFFFF"><?php die('Incorrect password, please try again.');
	}

	// if we get here username and password are correct, 
	//register session variables and set last login time.

	
	$_POST['uname'] = stripslashes($_POST['uname']);
	$_SESSION['username'] = $_POST['uname'];
	$_SESSION['password'] = $_POST['passwd'];

?>

<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=main.php">


<?php


} else {	// if form hasn't been submitted

?>


<br><br>



<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
<table align="center" border="1" cellspacing="0" cellpadding="3">
<tr><td><font color="#ffffff">Username:</td><td>
<input type="text" name="uname" maxlength="40">
</td></tr>
<tr><td><font color="#ffffff">Password:</td><td>
<input type="password" name="passwd" maxlength="50">
</td></tr>
<tr><td colspan="2" align="right">

<input type="submit" name="submit" value="Login">
</td></tr>
</table>
</form>

<?php
}
?>
</body>
</html>

