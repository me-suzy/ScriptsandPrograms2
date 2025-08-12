<?
session_start();
require ('_.php');
require ('functions.php');

//admin headers, replace this with a better function
if (!$usr_lvl){
		header("Location: notyet.php");
		}else {//echo "you user level is ".$usr_lvl;
			if	($usr_lvl < 1) {include ('notyet.php'); exit();}
			if	($usr_lvl ==1){editorpageheader();}
			if	($usr_lvl ==2){Adminpageheader();}
			if	($usr_lvl ==3){Mastpageheader();}
			if 	($usr_lvl ==4){dietypageheader();}
			 echo" <a class=nav_links href=index.php?dir=".$dir."&page_id=".$page_id." target=_new>Preview</a> |<br> ";
		}

error_reporting(E_ALL & ~E_NOTICE);

if (!$_POST[add_user]){Include('html/add_user_form.html');}
else{
// Define post fields into simple variables
$new_first_name = $_POST['new_first_name'];
$new_last_name = $_POST['new_last_name'];
$new_email_address = $_POST['new_email_address'];
$new_username = $_POST['new_username'];
$new_user_password=$_POST['new_user_password'];
$new_info = $_POST['new_info'];

/* Let's strip some slashes in case the user entered
any escaped characters. */

$new_first_name = stripslashes($new_first_name);
$new_last_name = stripslashes($new_last_name);
$new_email_address = stripslashes($new_email_address);
$new_new_username = stripslashes($new_username);
$new_user_password=stripslashes($new_user_password);
$new_info = stripslashes($new_info);


/* Do some error checking on the form posted fields */

if((!$new_first_name) || (!$new_last_name) || (!$new_email_address) || (!$new_new_username) || (!$new_user_password)){
	echo 'You did not submit the following required information! <br />';
	if(!$new_first_name){
		echo "First Name is a required field. Please enter it below.<br />";
	}
	if(!$new_last_name){
		echo "Last Name is a required field. Please enter it below.<br />";
	}
	if(!$new_email_address){
		echo "Email Address is a required field. Please enter it below.<br />";
	}
	if(!$new_new_username){
		echo "Desired Username is a required field. Please enter it below.<br />";
	}
	if(!$new_user_password){
		echo "Desired Password is a required field. Please enter it below.<br />";
	}
	include 'html/add_user_form.html'; // Show the form again!
	/* End the error checking and if everything is ok, we'll move on to
	 creating the user account */
	exit(); // if the error checking has failed, we'll exit the script!
}
	
/* Let's do some checking and ensure that the user's email address or username
 does not exist in the database */
include('_.php');
mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());

 
 $sql_email_check = mysql_query("SELECT email_address FROM users WHERE email_address='$new_email_address'");
 $sql_username_check = mysql_query("SELECT username FROM users WHERE username='$new_username'");
 
 $email_check = mysql_num_rows($sql_email_check);
 $username_check = mysql_num_rows($sql_username_check);
 
 if(($email_check > 0) || ($username_check > 0)){
 	echo "Please fix the following errors: <br />";
 	if($email_check > 0){
 		echo "<strong>Your email address has already been used by another member in our database. Please submit a different Email address!<br />";
 		unset($new_email_address);
 	}
 	if($username_check > 0){
 		echo "The username you have selected has already been used by another member in our database. Please choose a different Username!<br />";
 		unset($new_username);
 	}
 	include 'html/add_user_form.html'; // Show the form again!
 	exit();  // exit the script so that we do not create this account!
 }

$db_password = md5($new_user_password);

// Enter info into the Database.
$info2 = htmlspecialchars($info);
$sql = mysql_query("INSERT INTO users (first_name, last_name, email_address, username, PASSWORD, info, user_level, signup_date, activated)	
		VALUES('$new_first_name', '$new_last_name', '$new_email_address', '$new_username', '$db_password', '$info2', '1', now(), '1')") or die (mysql_error());

if(!$sql){
	echo 'There has been an error creating your account. Please contact the webmaster.';
} else {
$debug=mysql_query("SELECT * FROM users WHERE username = '".$new_username."'") or exit (mysql_error());}
$debug_info = mysql_fetch_array($debug);
echo "added user successfully<br><table><tr>
				<tr><td>userid ".$debug_info[userid]."</td></tr>
				<tr><td>first name ".$debug_info[first_name]."</td></tr>
				<tr><td>last name ".$debug_info[last_name]."</td></tr>
				<tr><td>username ".$debug_info[username]."</td></tr>
				<tr><td>email ".$debug_info[email_address]."</td></tr>
				<tr><td>userlevel=".$debug_info[user_level]."</td></tr>
				<tr><td>info ".$debug_info[info]."</td></tr>
				</table>";


;
}
?>