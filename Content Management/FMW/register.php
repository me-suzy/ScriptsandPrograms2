<?php
include "header.php";

?><font color="#<?php echo $col_text ?>"><?php


session_start();
if (($_SESSION['perm'] < "5")){
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}




?>

<html>
<head>
<title>Add Member</title>
</head>
<body>



<?php

if (isset($_POST['submit'])) { // if form has been submitted
	/* check they filled in what they supposed to, 
	passwords matched, username
	isn't already taken, etc. */


$user = $_POST['uname'];
$password = $_POST['passwd'];
$player = $_POST['player'];


	if (!$_POST['uname'] | !$_POST['displayname'] | !$_POST['role_title']  | !$_POST['passwd'] | !$_POST['passwd_again']) {
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



	// no HTML tags in username, website, location, password

	$_POST['uname'] = strip_tags($_POST['uname']);
	$_POST['passwd'] = strip_tags($_POST['passwd']);






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



	$regdate = date('Y-m-d');

	$insert = "INSERT INTO users (
			username,
			displayname, 
			password, 
			regdate, 
			email,
                  rights,
			role,
			player,
			last_login) 
			VALUES (
			'".$_POST['uname']."',
			'".$_POST['displayname']."', 
			'".$_POST['passwd']."', 
			'$regdate', 
			'".$_POST['email']."',  
			'".$_POST['rights']."',
			'".$_POST['role_title']."', 
			'".$_POST['player']."',  
 
			'Never')";

	$add_member = $db_object->query($insert);

	if (DB::isError($add_member)) {
		die($add_member->getMessage());
	}

	$db_object->disconnect();



if ($_POST['emailuser'] == 'yes' AND $_POST['email'] !='') {



/* recipients */

$to = "$email";

/* subject */
$subject = 'New user registration';

/* message */
$message = 'You are receiving this email as a new account has been created for you at '.$site_url.'

Please visit the above link to sign in and edit your profile. Your login details are as follows

username:  '.$user.'
Password:  '.$password.'

PLEASE CHANGE YOUR PASSWORD FROM THE EDIT PROFILE SCREEN ONCE LOGGED IN !



If you have any questions, please email: '.$admin_email.'

';




/* additional headers */
$headers .= "From: $admin_email\r\n";


/* and now mail it */
mail($to, $subject, $message, $headers);


?>





<font color="#<?php echo $col_text ?>">

<h1>Registered</h1>

<H4>The user information has been added to the database.
<BR>
An email has been sent to the user giving them their login details</p>

<?php
}

else {
?>
<font color="#<?php echo $col_text ?>">
<H4>The user information has been added to the database.
<BR><BR>Either no email address was entered or send email to user was not selected.
<br><br>
Please inform the user of their login details</H4>
<?php
}






} else {	// if form hasn't been submitted

?>


<center>
<table border="0" width="50%" height="30" cellpadding="0" cellspacing="0">
<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopleft.png" width="5" height="25" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbacktop.png" width="100%" height="25" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopright.png" width="5" height="25" alt=""></td>
</tr>



<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="25" alt=""></td>
</tr>



<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" bgcolor="#<?php echo "$col_back"; ?>" align="center">
<H3><?php echo "<font color='#$col_text'>"; ?>Add Member</H3>



<font color="red">*<?php echo "<font color='#$col_text'>"; ?> = Required Field
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">


Username*<br>
(This will be the users login)<br>
<input id="uname" size="30" name="uname" maxlength="40">
<br><br>
Full Name*<br>
(This will be their display name within the site)<br>
<input id="displayname" size="40" name="displayname" maxlength="40">
<br><br>
Password*<br>
<input type="password" name="passwd" maxlength="50">
<br><br>
Confirm Password*<br>
<input type="password" name="passwd_again" maxlength="50">
<br><br>
E-Mail<br>
<input id="email" size="60" name="email"><br>

<br>
Member Rights<br>
<br>
*Member - Access general pages, but can not post news<br>
*Super Member - Post and edit all news<br>
*Admin - Full access<br>

<?php
echo '<select name="rights">'; 
echo '<option value="0">Member</option>'; 
echo '<option value="3">Super Member</option>'; 
echo '<option value="5">Admin</option>'; 
?>
</select> 
<BR><BR>
Select Role Of Player*
<br>
(This can be changed in admin options later)
<br>
<?php
$dbQuery = "SELECT * "; 

$dbQuery .= "FROM roles"; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result);
?>


<SELECT NAME="role_title">
<?php
while($row = mysql_fetch_array($result))
print "<OPTION VALUE=\"$row[1]\">$row[1]</OPTION>\n";
?>
</select>

<br><br>
Is Member a Player In The Team ?<br>
(If Yes They Will Be Available On Team Selection)<br>

<?php
echo '<select name="player">'; 
echo '<option value="yes">Yes</option>';  
echo '<option value="no">No</option>'; 
?>

</select>


<br><br>
Email User Their Login Details ?<br>
(Must have supplied a valid email address!)<br>

<?php
echo '<select name="emailuser">'; 
echo '<option value="no">No</option>'; 
echo '<option value="yes">Yes</option>';  
?>

</select>


<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>



<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>

<br><br>

<br><br>

<input type="submit" name="submit" value="Sign Up">
</table>
</form>

<?php

}

?>
</body>
</html>