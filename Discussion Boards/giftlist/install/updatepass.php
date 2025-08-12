<?
include "header.php";
// require('db_connect.php');	// database connect script.

if (!$_POST['ud_password'] | !$_POST['passwd_again']) {
		die('You did not fill in a required field.');
	}


if ($_POST['ud_password'] != $_POST['passwd_again']) {
		die('Passwords did not match.');
	}


$ud_id = $_POST['ud_id'];
$ud_password = md5($_POST['ud_password']);


$query="UPDATE users SET password='$ud_password' WHERE username='$ud_id'";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);
echo "Record Updated";

unset($_SESSION['username']);
unset($_SESSION['password']);
// kill session variables
$_SESSION = array(); // reset session array
session_destroy();   // destroy session.
// header('Location: login.php');
// redirect them to anywhere you like.


mysql_close();
?>
<br><br>
You have been logged out for the update to take place
<br>
<a href="login.php"><font color="#D68960">Login</font></a><font color="#D68960">
</font>


