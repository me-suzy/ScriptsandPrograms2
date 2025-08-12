<?php
/*  
   Register Form Processor
   (c) 2004-2005 Philip Shaddock All rights reserved.
       pshaddock@wizardinteractive.com 
*/ 	


include_once("../../inc/config_cms/configuration.php");	
include_once("../../inc/languages/". $language .".public.php");	
include_once("../../inc/db/db.php");
include_once("../../inc/functions/validate.input.form.php");
include_once("../../inc/functions/user.php");

$username = $_POST['username'];	
$password = $_POST['password'];
$password2 = $_POST['password2'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$organization = $_POST['organization'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$country = $_POST['country'];
$postal = $_POST['postal'];

//Required fields
if((!$first_name) || (!$last_name) || (!$email) || (!$username) || (!$password )  || (!$password2 ))  {
    /* ********
	* *********  Note : put these in reverse order, since it is the last message that gets sent
	*/ 
	if(!$email){
		$message= ERROREMAIL;
	}
	if(!$last_name){
		$message= ERRORLAST;
	}
	if(!$first_name){
		$message= ERRORFIRSTNAME;
	}
	if(!$password){
		$message= ERRORPASS;
	}
	if(!$username){
		$message= ERRORUSER;
	}
	
	
	
	
	
    $location = "register_form.php?message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal";
	header("Location: $location");
	exit(); 

}


if( $password != $password2)
	{
		$message= MATCH;
		$location = "register_form.php?message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal";
		header("Location: $location");
		exit(); 
	}
	
//validate email
if (!validate_email($email)) {
    $message = EMAILBAD;
    $location = "register_form.php?message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal";
	header("Location: $location");
	exit(); 
}	
	
	
/* Let's strip some slashes in case the user entered
any escaped characters. */

$first_name = addslashes(htmlspecialchars(nl2br($first_name)));
$last_name = addslashes(htmlspecialchars(nl2br($last_name)));
$email = addslashes(htmlspecialchars(nl2br($email)));
$username = addslashes(htmlspecialchars(nl2br($username)));
$password = addslashes(htmlspecialchars(nl2br($password)));
$organization = addslashes(htmlspecialchars(nl2br($organization)));
$phone = addslashes(htmlspecialchars(nl2br($phone)));
$address = addslashes(htmlspecialchars(nl2br($address)));
$address2 = addslashes(htmlspecialchars(nl2br($address2)));
$city = addslashes(htmlspecialchars(nl2br($city))); 
$postal = addslashes(htmlspecialchars(nl2br($postal)));  
$state = addslashes(htmlspecialchars(nl2br($state)));   

//make username and password lowercase
$username=strtolower($username);
$password=strtolower($password);


if (!valid_username($username)) {
    $location = "register_form.php?message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal";
		header("Location: $location");
		exit();
}
	
if (!valid_password($password)) {
    $location = "register_form.php?message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal";
		header("Location: $location");
		exit();
}
 
 $db = new DB ();
  
 $sql_username_check = $db->query("SELECT username FROM ". DB_PREPEND . "users WHERE username='$username'");
 
 $username_check = $db->num_rows();
 
  	if($username_check > 0){
 		$message= USERALREADY;
 		
 	
	$location = "register_form.php?message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal";
	header("Location: $location");
	exit(); 
	 	
    }
 

//create a new hash to insert into the db and the confirmation email
$hash=md5($email.$hidden_hash_var);

$remote = $_SERVER[REMOTE_ADDR];

$db = new DB();
$db = $db->query("INSERT INTO ". DB_PREPEND . "users (username,password,first_name,last_name,email,register_date,phone, country,organization, address, address2, city, state, postal, remote_addr,confirm_hash,is_confirmed ) VALUES ('$username', '". md5($password) ."', '$first_name', '$last_name', '$email',now(),'$phone', '$country','$organization' ,'$address', '$address2', '$city' ,'$state','$postal', '$remote', '$hash', '0' )");
$uid = mysql_insert_id();

   
	
		
		// display status
		
    if (!$uid) {	
			$message = FAILED;
			$location = "register_form.php?message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal";
			header("Location: $location");
			exit(); 
		}
		else {		
		//send the confirm email
					user_send_confirm_email($email,$hash);
					$message = SUCCESS;
					$location = "register_form.php?message=$message";
					header("Location: $location");
					exit();
		}
	
?>