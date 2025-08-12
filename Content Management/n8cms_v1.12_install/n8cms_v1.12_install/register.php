<?
error_reporting(E_ALL & ~E_NOTICE);


// Define post fields into simple variables
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email_address = $_POST['email_address'];
$username = $_POST['username'];
$info = $_POST['info'];

/* Let's strip some slashes in case the user entered
any escaped characters. */

$first_name = stripslashes($first_name);
$last_name = stripslashes($last_name);
$email_address = stripslashes($email_address);
$username = stripslashes($username);
$info = stripslashes($info);


/* Do some error checking on the form posted fields */

if((!$first_name) || (!$last_name) || (!$email_address) || (!$username)){
	echo 'You did not submit the following required information! <br />';
	if(!$first_name){
		echo "First Name is a required field. Please enter it below.<br />";
	}
	if(!$last_name){
		echo "Last Name is a required field. Please enter it below.<br />";
	}
	if(!$email_address){
		echo "Email Address is a required field. Please enter it below.<br />";
	}
	if(!$username){
		echo "Desired Username is a required field. Please enter it below.<br />";
	}
	include 'html/join_form.html'; // Show the form again!
	/* End the error checking and if everything is ok, we'll move on to
	 creating the user account */
	exit(); // if the error checking has failed, we'll exit the script!
}
	
/* Let's do some checking and ensure that the user's email address or username
 does not exist in the database */
include('_.php');
mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());

 
 $sql_email_check = mysql_query("SELECT email_address FROM users WHERE email_address='$email_address'");
 $sql_username_check = mysql_query("SELECT username FROM users WHERE username='$username'");
 
 $email_check = mysql_num_rows($sql_email_check);
 $username_check = mysql_num_rows($sql_username_check);
 
 if(($email_check > 0) || ($username_check > 0)){
 	echo "Please fix the following errors: <br />";
 	if($email_check > 0){
 		echo "<strong>Your email address has already been used by another member in our database. Please submit a different Email address!<br />";
 		unset($email_address);
 	}
 	if($username_check > 0){
 		echo "The username you have selected has already been used by another member in our database. Please choose a different Username!<br />";
 		unset($username);
 	}
 	include 'html/join_form.html'; // Show the form again!
 	exit();  // exit the script so that we do not create this account!
 }
 
/* Everything has passed both error checks that we have done.
It's time to create the account! */

/* Random Password generator. 
http://www.phpfreaks.com/quickcode/Random_Password_Generator/56.php

We'll generate a random password for the
user and encrypt it, email it and then enter it into the db.
*/

function makeRandomPassword() {
  $salt = "abchefghjkmnpqrstuvwxyz0123456789";
  srand((double)microtime()*1000000); 
  	$i = 0;
  	while ($i <= 7) {
    		$num = rand() % 33;
    		$tmp = substr($salt, $num, 1);
    		$pass = $pass . $tmp;
    		$i++;
  	}
  	return $pass;
}

$random_password = makeRandomPassword();

$db_password = md5($random_password);

// Enter info into the Database.
$info2 = htmlspecialchars($info);
$sql = mysql_query("INSERT INTO users (first_name, last_name, email_address, username, password, info, user_level, signup_date)
		VALUES('$first_name', '$last_name', '$email_address', '$username', '$db_password', '$info2', '2', now())") or die (mysql_error());

if(!$sql){
	echo 'There has been an error creating your account. Please contact the webmaster.';
} else {
	$userid = mysql_insert_id();
	// Let's mail the user!
	$subject = $site_name." Editor Registration!";
	$message = "
	<html><body>
	You are two steps away from logging in and accessing the admin area.
	
	To activate your membership, please use the following link or cut and past the URL into your browser
	 <a href="cms_path"/activate.php?id=$userid&code=$db_password>click here:</a>
	
	Once you activate your memebership, you will be able to login with the following information:<br>
	<b>Username: $username </b><br>
	<b>Password: $random_password</b><br>
	change your password after your log in
	
	Thanks!
	N8 RoX
	
	This is an automated response, please do not reply!
	</body>
	</html>";
mail($email_address , $subject, $message, "From n8'scms" ) or die("mail error!");
	echo 'Your login information has been mailed to your email address! Please check it and follow the directions!';
	//include ('html/login_form.html');
}

?>