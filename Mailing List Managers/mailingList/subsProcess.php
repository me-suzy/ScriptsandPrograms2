<?php
session_start();
///No robots please
$number = @$_POST['verifyImage'];
if(@md5($number) != $_SESSION['image_random_value'])
	{
		header("location: subscribe.php?m=1");
		exit();
	}
///check for empty field
if(empty($email))
{
	header("location: subscribe.php?m=2");
	exit();
}
if(empty($name))
{
	header("location: subscribe.php?m=3");
	exit();
}
include 'inc/config.php';
include 'inc/conn.php';
///check for the existing email address
$q = mysql_query("select * from mailList where emailAddress = '".$email."'");
$num = mysql_num_rows($q);
if($num > 0)
{
	header("location: subscribe.php?m=4");
	exit();
}
///Ok, no email found, insert temporary DB for verification
$verify = rand(000000000, 999999999);
$values = 'values("'.$name.'", "'.$email.'", "'.$verify.'")';
$insert = mysql_query("insert into temp(name, emailAddress, verifyKey) ".$values);
if($insert)
{
	///send verification to that guy
	$to = $email;
	$subject = 'verify code';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$body = '<html><head><title>Verification</title></head><body><table width="100%" cellpadding="3" border="0" cellspacing="0">
			 <tr><td><font face="verdana" size="2">Either you or someone had subscribe 
		 	 this email address to <a href="'.$homePageUrl.'" target="_blank">'.$homePageUrl.'</a>, if 
			 you feel this email is send by error, please do not respond or verify it. If you are the one who 
			 subscribe to our mailing system, please verify your email address in order to prevent spam.<br><br>
			 ***Click the link below to verify:<br>
			 <a href="'.$installationUrl.'/verifyEmail.php?code='.$verify.'&add='.$email.'" target="_blank">'.$installationUrl.'/verifyEmail.php?code='.$verify.'&add='.$email.'</a>
			 </font></td></tr></table></body></html>';
	$mail = mail($to, $subject, $body, $headers);
	mysql_close($conn);
	header("location: subscribe.php?m=5");
}
?>