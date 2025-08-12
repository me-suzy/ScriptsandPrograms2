<?php
include "header.php";
//require('db_connect.php');	// database connect script.

?>

<html>
<head>
<title>Register an Account</title>
</head>
<body>

<?php

if (isset($_POST['submit'])) { // if form has been submitted
	/* check they filled in what they supposed to, 
	passwords matched, username
	isn't already taken, etc. */

	if (!$_POST['uname'] | !$_POST['passwd'] | !$_POST['passwd_again'] | !$_POST['email']) {
		die('You did not fill in a required field.');
	}

	// check if username exists in database.

	if (!get_magic_quotes_gpc()) {
		$_POST['uname'] = addslashes($_POST['uname']);
	}



	$name_check = $db_object->query("SELECT username FROM users WHERE username = '".$_POST['uname']."'");

	if (DB::isError($name_check)) {
		die($name_check->getMessage());
	}

	$name_checkk = $name_check->numRows();

	if ($name_checkk != 0) {
		die('Sorry, the username: <strong>'.$_POST['uname'].'</strong> is already taken, please pick another one.');
	}

	// check passwords match

	if ($_POST['passwd'] != $_POST['passwd_again']) {
		die('Passwords did not match.');
	}

	// check e-mail format

	if (!preg_match("/.*@.*..*/", $_POST['email']) | preg_match("/(<|>)/", $_POST['email'])) {
		die('Invalid e-mail address.');
	}

	// no HTML tags in username, website, location, password

	$_POST['uname'] = strip_tags($_POST['uname']);
	$_POST['passwd'] = strip_tags($_POST['passwd']);
	$_POST['website'] = strip_tags($_POST['website']);
	$_POST['location'] = strip_tags($_POST['location']);



	// check show_email data

	if ($_POST['show_email'] != 0 & $_POST['show_email'] != 1) {
		die('Nope');
	}

	/* the rest of the information is optional, the only thing we need to 
	check is if they submitted a website, 
	and if so, check the format is ok. */

	//if ($_POST['website'] != '' & !preg_match("/^(http|ftp):///", $_POST['website'])) {
		//$_POST['website'] = 'http://'.$_POST['website'];
	//}

	// now we can add them to the database.
	// encrypt password

	$_POST['passwd'] = md5($_POST['passwd']);

	if (!get_magic_quotes_gpc()) {
		$_POST['passwd'] = addslashes($_POST['passwd']);
		$_POST['email'] = addslashes($_POST['email']);
		$_POST['website'] = addslashes($_POST['website']);
		$_POST['location'] = addslashes($_POST['location']);
	}



	$regdate = date('m d, Y');

	$insert = "INSERT INTO users (
			username, 
			password, 
			regdate, 
			email,  
                  movies,
                  music,
                  books,
                  vouchers,
                  misc,
			last_login) 
			VALUES (
			'".$_POST['uname']."', 
			'".$_POST['passwd']."', 
			'$regdate', 
			'".$_POST['email']."',   
			'".$_POST['movies']."',
			'".$_POST['music']."',
			'".$_POST['books']."',
			'".$_POST['vouchers']."',
			'".$_POST['misc']."', 
			'Never')";

	$add_member = $db_object->query($insert);

	if (DB::isError($add_member)) {
		die($add_member->getMessage());
	}

	$db_object->disconnect();
?>


<h1>Registered</h1>

<p>Thank you, your information has been added to the database, you may now <a href="login.php" title="Login">log in</a>.</p>

<?php

} else {	// if form hasn't been submitted

?>

<h1>Register</h1>
<font color="red">*<font color="#ffffff"> = Required Field
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table align="center" border="0" cellspacing="0" cellpadding="3">

<tr><td><font color="ffffff">Username<font color="red">*</td><td>
<input type="text" name="uname" maxlength="40">
</td></tr>
<tr><td><font color="ffffff">Password<font color="red">*</td><td>
<input type="password" name="passwd" maxlength="50">
</td></tr>
<tr><td><font color="ffffff">Confirm Password<font color="red">*</td><td>
<input type="password" name="passwd_again" maxlength="50">
</td></tr>
<tr><td><font color="ffffff">E-Mail<font color="red">*</td><td>
<input type="text" name="email" maxlength="100">
</td></tr>


<tr><td> </td><td>
<tr><td> </td><td>
<tr><td> </td><td>
<tr><td> </td><td>
<tr><td> </td><td>
<tr><td> </td><td>
<tr><td> </td><td>
</td><td>

<tr><td> </td><td>
<tr><td> </td><td>
<tr><td> </td><td>
<tr><td> </td><td>
<font color='#ffffff'>User Profile - Enter your preferences below
<tr><td> </td><td>
<tr><td> </td><td>
<font color='#ffffff'>For example, type of film/music, favourite author or series
<tr><td> </td><td>
<tr><td> </td><td>
<font color='#ffffff'>of books, vouchers from certain shops etc...
<tr><td> </td><td>
<tr><td> </td><td>


</td></tr>

<tr><td><font color="#ffffff">Movies</td><td>
<textarea name="movies" rows="5" cols="40" ></textarea>

<tr><td><font color="#ffffff">Music</td><td>
<textarea name="music" rows="5" cols="40" ></textarea>

<tr><td><font color="#ffffff">Books</td><td>
<textarea name="books" rows="5" cols="40" ></textarea>

<tr><td><font color="#ffffff">Vouchers</td><td>
<textarea name="vouchers" rows="5" cols="40" ></textarea>

<tr><td><font color="#ffffff">Misc</td><td>
<textarea name="misc" rows="5" cols="40" ></textarea>


<tr><td colspan="5" align="right">

<input type="submit" name="submit" value="Sign Up">
</table>
</form>

<?php

}

?>
</body>
</html>