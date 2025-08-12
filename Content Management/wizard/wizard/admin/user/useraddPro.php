<?php
/*  
   Add User Processor
   (c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
include_once("../../inc/config_cms/configuration.php");	
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
$fax = $_POST['fax'];
$address = $_POST['address'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$country = $_POST['country'];
$postal = $_POST['postal'];
$subscribe = $_POST['subscribe'];
$is_confirmed = $_POST['is_confirmed'];
$comment = $_POST['comment'];
$gid = $_POST['gid'];



if((!$first_name) || (!$last_name) || (!$email) || (!$username) || (!$password )  || (!$password2 ))  {

	if(!$first_name){
		$message="Error: Please enter your first name.<br />";
	}
	if(!$last_name){
		$message="Error: Please enter your last name.<br />";
	}
	if(!$email){
		$message="Error: Please enter a valid email address.<br />";
	}
	if(!$username){
		$message="Error: Please enter a username.<br />";
	}
	if(!$password){
		$message="Error: Please enter a password.<br />";
	}
			
    $location = CMS_WWW . "/admin.php?id=2&item=27&sub=29&message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&fax=$fax&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal&is_confirmed=$is_confirmed&comment=$comment";
	header("Location: $location");
	exit(); 

}


if( $password != $password2)
	{
		$message="Error: Passwords do not match.<br />";
		$location = CMS_WWW . "/admin.php?id=2&item=27&sub=29&message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&fax=$fax&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal&is_confirmed=$is_confirmed&comment=$comment";
		header("Location: $location");
		exit(); 
	}
	
/* Let's strip some slashes in case the user entered
any escaped characters. */

$first_name = strip_tags($first_name, '<b><i><em><strong><u><br><p>');
$last_name = strip_tags($last_name, '<b><i><em><strong><u><br><p>');
$email = strip_tags($email, '<b><i><em><strong><u><br><p>');
$username = strip_tags($username, '<b><i><em><strong><u><br><p>');
$password = strip_tags($password, '<b><i><em><strong><u><br><p>');
$organization = strip_tags($organization, '<b><i><em><strong><u><br><p>');
$phone = strip_tags($phone, '<b><i><em><strong><u><br><p>');
$address = strip_tags($address, '<b><i><em><strong><u><br><p>');
$address2 = strip_tags($address2, '<b><i><em><strong><u><br><p>');
$city = strip_tags($city, '<b><i><em><strong><u><br><p>');
$postal = strip_tags($postal, '<b><i><em><strong><u><br><p>');
$state = strip_tags($state, '<b><i><em><strong><u><br><p>');
$is_confirmed = strip_tags($is_confirmed, '<b><i><em><strong><u><br><p>');
$fax = strip_tags($fax, '<b><i><em><strong><u><br><p>');

$first_name = checkSlashes($first_name);
$last_name = checkSlashes($last_name);
$email = checkSlashes($email);
$username = checkSlashes($username);
$password = checkSlashes($password);
$organization = checkSlashes($organization);
$phone = checkSlashes($phone);
$address = checkSlashes($address);
$address2 = checkSlashes($address2);
$city = checkSlashes($city); 
$postal = checkSlashes($postal);  
$state = checkSlashes($state); 
$fax =  checkSlashes($fax);  
$comment =  checkSlashes($comment);

//make username and password lowercase
$username=strtolower($username);
$password=strtolower($password);



if (!valid_password($password)) {
    $location = CMS_WWW . "/admin.php?id=2&item=27&sub=29&message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&fax=$fax&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal&is_confirmed=$is_confirmed&comment=$comment";
		header("Location: $location");
		exit();
}
	
if (!valid_username($username)) {
    $location = CMS_WWW . "/admin.php?id=2&item=27&sub=29&message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&fax=$fax&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal&is_confirmed=$is_confirmed&comment=$comment";
		header("Location: $location");
		exit();
}
	

 
 $db = new DB ();
  
 $sql_username_check = $db->query("SELECT username FROM ". DB_PREPEND . "users WHERE username='$username'");
 
 $username_check = $db->num_rows();
 
  	if($username_check > 0){
 		$message="Error: The username is already in use. Please choose a different one.<br />";
 		
 	
	$location = CMS_WWW . "/admin.php?id=2&item=27&sub=29&message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&fax=$fax&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal&is_confirmed=$is_confirmed&comment=$comment";
	header("Location: $location");
	exit(); 
	 	
    }
 
//validate email
if (!validate_email($email)) {
    $message = "Email format is invalid.";
    $location = CMS_WWW . "/admin.php?id=2&item=27&sub=29&message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&fax=$fax&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal&is_confirmed=$is_confirmed&comment=$comment";
	header("Location: $location");
	exit(); 
}

//create a new hash to insert into the db and the confirmation email
$hash=md5($email.$hidden_hash_var);


$remote = $_SERVER[REMOTE_ADDR];


$db = new DB();
$db = $db->query("INSERT INTO ". DB_PREPEND . "users (username,password,first_name,last_name,email,register_date,phone, fax, country,organization, address, address2, city, state, postal, remote_addr,confirm_hash,is_confirmed,comment ) VALUES ('$username', '". md5($password) ."', '$first_name', '$last_name', '$email',now(),'$phone', '$fax', '$country','$organization' ,'$address', '$address2', '$city' ,'$state','$postal', '$remote', '$hash', '$is_confirmed', '$comment' )");
$uid = mysql_insert_id();

if ($_POST['gid']) {
	        foreach ($_POST['gid'] as $g)
			{
			$db = new DB();
			$db = $db->query("INSERT INTO ". DB_PREPEND . "groupusers (uid, gid) VALUES ('$uid', '$g') ");
			
			}
	    }	   
	
		
		// display status
		
    if (!$uid) {	
			$message = "Registration failed. Please try again or contact us.";
			$location = CMS_WWW . "/admin.php?id=2&item=27&sub=29&message=$message&username=$username&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&phone=$phone&fax=$fax&address=$address&address2=$address2&city=$city&state=$state&country=$country&postal=$postal&is_confirmed=$is_confirmed&subscribe=$subscribe";
			header("Location: $location");
			exit(); 
		}
		else {		
		$message = "The user was registered successfully.";
			$location = CMS_WWW . "/admin.php?id=2&item=27&sub=29&message=$message";
			header("Location: $location");
			exit(); 
		}
	
?>