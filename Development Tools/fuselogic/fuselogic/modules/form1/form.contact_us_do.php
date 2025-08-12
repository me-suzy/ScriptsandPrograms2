<?php
$contact_us = 'contact_us@haltebis.com';

require_once('class.phpmailer.php');

$mail = new PHPMailer();

//$mail->IsSMTP();                                   // send via SMTP
//$mail->Host     = "smtp1.site.com;smtp2.site.com"; // SMTP servers
//$mail->SMTPAuth = true;     // turn on SMTP authentication
//$mail->Username = "jswan";  // SMTP username
//$mail->Password = "secret"; // SMTP password

$mail->From     = $_POST['email'];
$mail->FromName = "FuseLogic Contact Us";
$mail->AddAddress($contact_us); 
//$mail->AddAddress("ellen@site.com");               // optional name
$mail->AddReplyTo($_POST['email'],$_POST['email']);

$mail->WordWrap = 50;                              // set word wrap
//$mail->AddAttachment("/var/tmp/file.tar.gz");      // attachment
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); 
//$mail->IsHTML(true);                               // send as HTML

$mail->Subject  =  $_POST['subject'];
$mail->Body     =  $_POST['body'];
//$mail->AltBody  =  "This is the text-only body";

if(!$mail->Send()){
    echo '<div align="center"><h3>Message was not sent<br>';
    echo 'Mailer Error: '. $mail->ErrorInfo.'</h3></div>';
}else{
    echo '<div align="center"><h3>Thank for contacting us';
		echo '<br>Your message has been sent to us</h3></div>';    
}
?>
