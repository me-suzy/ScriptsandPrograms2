<?php
/*  
   Contact Us Form Processor
   (c) 2006 Philip Shaddock All rights reserved.
       www.ragepictures.com 
*/ 	
function MAILMAN($fromname, $fromaddress, $toname, $toaddress, $subject, $message)
{
   $headers  = "MIME-Version: 1.0\n";
   $headers .= "Content-type: text/plain; charset=iso-8859-1\n";
   $headers .= "X-Priority: 3\n";
   $headers .= "X-MSMail-Priority: Normal\n";
   $headers .= "X-Mailer: php\n";
   $headers .= "From: \"".$fromname."\" <".$fromaddress.">\n";
   return mail($toaddress, $subject, $message, $headers);
}




include_once("../../inc/config_cms/configuration.php");
include_once("../../inc/db/db.php");	
include_once '../../inc/languages/' . $language . '.public.php';
include_once("../../inc/functions/validate.input.form.php");


$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$organization = $_POST['organization'];
$email = $_POST['email'];
$email2 = $_POST['email2'];
$comment = $_POST['comment'];



//required fields
if((!$first_name) || (!$last_name) || (!$email)  || (!$email2)  )  {

	//Note place these required fields in reverse order...as you see here.
	if(!$email2){
		$message= "Please enter your email address twice.";
	}
	if(!$email){
		$message= ERROREMAIL;
	}
	if(!$last_name){
		$message= ERRORLAST;
	}
	if(!$first_name){
		$message= ERRORFIRSTNAME;
	}
			
    $location = CMS_WWW . "/templates/forms/contact_form.php?message=$message&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&email2=$email2";
	header("Location: $location");
	exit(); 

}

//email addresses do not match
	if ($email == $email2) { }
	else 
	{
	$message = "Sorry, your email addresses did not match.";
	$location = CMS_WWW . "/templates/forms/contact_form.php?message=$message&first_name=$first_name&last_name=$last_name&organization=$organization&email=$email&email2=$email2";
	header("Location: $location");
	exit(); 
}


$first_name = addslashes(htmlspecialchars(nl2br($first_name)));
$last_name = addslashes(htmlspecialchars(nl2br($last_name)));
$email = addslashes(htmlspecialchars(nl2br($email)));
$organization = addslashes(htmlspecialchars(nl2br($organization)));
$comment = addslashes(htmlspecialchars(nl2br($comment)));

$first_name = cleanhtml($first_name);
$last_name = cleanhtml($last_name);
$email = cleanhtml($email);
$organization = cleanhtml($organization);
$comment = cleanhtml($comment);

$fromaddress = $email;
$db = new DB();
$db->query("SELECT email,name FROM ". DB_PREPEND . "config LIMIT 1");
$i = $db->next_record();
$toname = $i['name'];
$toaddress = $i['email'];
$subject = $i['name']. " " . CF_REQUEST_INFO;
$fromname = $first_name . " " . $last_name;
if (!$organization) { $organization = "\"None\""; }
$message = CF_RECEIVED . "\r\n\r\n" .  CF_NAME . ": " . $fromname . "\r\n" . ORGANIZATION . ": " . $organization . " \r\n" . EMAIL . ": " . $email ." \r\n\r\n". CF_REQUEST_INFO . ":\r\n" . $comment ." \r\n";

MAILMAN($fromname, $fromaddress, $toname, $toaddress, $subject, $message);
	
$message = CF_THANK_YOU;
$location = CMS_WWW . "/templates/forms/contact_form.php?message=$message";
header("Location: $location");
exit();
	
	
?>