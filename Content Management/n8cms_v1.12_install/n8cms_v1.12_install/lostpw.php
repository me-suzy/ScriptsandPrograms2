<?
include('functions.php');
include('_.php');
switch($_POST['recover']){
	default:
	include 'html/lost_pw.html';
	break;
	
	case "recover":
	recover_pw($_POST['email_address']);
	break;
}
function recover_pw($email_address){
	if(!$email_address){
		echo "You forgot to enter your Email address <strong>Knucklehead</strong><br />";
		include 'html/lost_pw.html';
		exit();
	}
	// quick check to see if record exists	
	$sql_check = mysql_query("SELECT * FROM users WHERE email_address='$email_address'");
	$sql_check_num = mysql_num_rows($sql_check);
	if($sql_check_num == 0){
		echo "No records found matching your email address<br />";
		include 'html/lost_pw.html';
		exit();
	}
	// Everything looks ok, generate password, update it and send it!
	
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
	
	$sql = mysql_query("UPDATE users SET password='$db_password' WHERE email_address='$email_address'");
	
	$subject = "Lost Password at ".$sitename;
	$message = "Your password has been reset.
	
	New Password: $random_password
	
	$path/db_exit.php
	
	Thanks!
	The Webmaster
	
	This is an automated response, please do not reply!";
	
	mail($email_address, $subject, $message, "From:".$sitename." N8cms Password reset");
	echo "Your password has been sent! Please check your email!<br />";
	include 'html/login_form.html';
}
?>