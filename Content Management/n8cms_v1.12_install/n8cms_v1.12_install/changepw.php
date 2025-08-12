<?
// check to see if use is signed in
session_start(); 
// make connection to database
Include('_.php');
error_reporting(E_ALL &~E_NOTICE &~E_WARNING);

mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());

$usr_lvl = $_SESSION['user_level'];

$email=$_SESSION[email_address];

//session_checker(); 
// determine whether to display form

if(!$_POST['visited']){
	include 'html/change_pw.html';
	exit();
} elseif($_POST['visited'] == "visited" && (!$_POST['old_pw'] || !$_POST['new_pw'])){
	echo "You didn't fill in all the required fields, stupid!";
	include 'html/change_pw.html';
	exit();
} elseif($_POST['visited'] == "visited" && isset($_POST['old_pw']) && isset($_POST['new_pw'])) {
	// rename post vars into easier to handle ones
	$oldpw = $_POST['old_pw'];
    $newpw = $_POST['new_pw'];
	changepw($oldpw, $newpw);
}

/* ********************************************************************************************
*  All the simple stuff is taken care of, it's time to write this function! 11/17/03
*  Everything written by Kevin Martin, based off of phpfreaks's membership area tutorial
*  ( http://www.phpfreaks.com/tutorials/40/0.php )
*
*  If you choose to use this function, this comment-block will be neccessary on your site
*
*  Function changes randomly generated password into user-specific one
*
*  Special thanks to Daeken and phpfreaks.com
************************************************************************************************/

function changepw($oldpw, $newpw){
	// ecrypt passwords
	$newpw = md5($newpw);
	$oldpw = md5($oldpw);
	// rename session variables for ease of use
	$email = $_SESSION['email_address'];
	// validate old password against DB
	$query = "SELECT * FROM users WHERE password='$oldpw' AND email_address='$email'";
	echo "<br>".$query."<br>";
	$result = mysql_query($query) or die (mysql_error());
 	echo "my sql num rows =".mysql_num_rows($result)."<br>";
	$rows = mysql_num_rows($result);
	if($rows != 1){
		// error handling
		echo "<b>Error!</b><br />You're current password does not match actual entry in the database, try again!";
		include 'html/change_pw.html';
		exit();
	} else {
		// everything is going smoothly, update password
	 	$query = "UPDATE users SET password='$newpw' WHERE email_address='$email'";
		$result = mysql_query($query);
		if(!$result){
			// theres been a mix up, error handling
			echo "<b>Error!</b><br />Your password can not be changed. Contact the admin";
			exit();
		} else {
			// display the good stuff!
			echo "<a class='db_nav'>Password successfully changed</a><br />
				<a href=\"logout.php\" class='db_nav1' >Logout</a> and try your new password!";
			exit();
		}
}
}
?>		