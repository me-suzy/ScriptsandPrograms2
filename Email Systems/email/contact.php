<?php
//Script is Written by Vincent Gabriele - CW3 Web Hosting - http://www.cw3host.com
//You may distribute it but may not alter this script in any way
//For info go to http://www.netbizcity.net
//version 1.02

include ('contact.conf');
if ($HTTP_SERVER_VARS['REQUEST_METHOD'] != "POST"){exit;}
$referer = $_SERVER['HTTP_REFERER'];
$smail=true;

if (strpos($referer, $url)==False){
Print "Abuse of our Mail system is not permitted!";
exit;
}

$name = stripslashes($HTTP_POST_VARS['name']);
$user_ip = urlencode(getenv("REMOTE_ADDR"));
$wmessage="<p><font face='Arial'>Hi </font>" . $name . "</p>\n";
$wmessage= $wmessage . "<p><font face='Arial'>We really appreciate you taking the time to get in touch with " . $Website_name . "</p>\n";
$wmessage= $wmessage . "<p>We will send you a reply (if required) as soon as possible.</font></p>\n";
$wmessage= $wmessage . "<p align='left'><font face='Arial'>Currently our turn around time for answering inquiries is " . $time_to_answer . " hrs</font></p>\n";
$wmessage= $wmessage . "<p align='left'><font face='Arial'>regards,</font></p>\n";
$wmessage= $wmessage . "<p align='left'><font size='3' face='Book Antiqua'>" . $Website_name . "</font></p>\n";

$comments = stripslashes($HTTP_POST_VARS['comments']);
$formemail = $HTTP_POST_VARS['formemail'];

if (empty($formemail) or strpos($formemail, '@')  == False or strpos($formemail, '.') == False or empty($comments) ){
  $smail=false;
  $wmessage="<p>Your message in not complete!</p>\n <p>Either you left a field blank or your return Email Address is missing or it is not a proper address!</p>\n<p>Please return to the contact page and enter all information and a proper email address!\n<p align='center'><input TYPE=button value=Return onclick=history.back(1)></p>";
}

$subject = $Website_name . " question";
$message = "From: $name \n Email address: $formemail\n\n$comments\n\nThis email was sent from " . $url . "\n by IP: " . $user_ip . "\n Referer is: " . $referer;

$meschk = strtolower($comments) . " " . strtolower($formemail) . " " . strtolower($name);

if ((strpos($meschk, 'content-type')  > -1) or (strpos($meschk, 'multipart/mixed')  > -1) or (strpos($meschk, 'mime-version') > -1) or (strpos($meschk,'bcc:') > -1)){
  $message="We have an email abuse attempt from - IP:  " . $user_ip . " Sent from: "  . $referer ;
  $formemail = $email;
  $subject="Abuse attempt";
  if (!$notify_abuse){$smail=false;}else{$smail=true;}
  $wmessage= "<p>We have noted your IP address: " . $user_ip . "</p>\n <p>Missuse of our system will result in a complaint filed to your ISP</p>\n <p>We do not take this lightly</p>" ;
}

if ($smail){
 mail_it($message,$subject,$formemail,$email);
 header ("location: http://" . $url . "/" . $return_page . "?message=" . urlencode($wmessage));
}else{  header ("location: http://" . $url . "/" . $return_page . "?message=" . urlencode($wmessage));
}

function mail_it($content, $subject, $email, $recipient) {
   $ob = "----=_OuterBoundary_000";
   $ib = "----=_InnerBoundery_001";
   
   $headers  = "MIME-Version: 1.0\r\n"; 
   $headers .= "From: ".$email."\n";
   $headers .= "To: ".$recipient."\n";
   $headers .= "Reply-To: ".$email."\n";
   $headers .= "X-Priority: 1\n";
   $headers .= "X-Mailer: CW3 Formmail Version 1.2 \n";
   $headers .= "Content-Type: multipart/mixed;\n\tboundary=\"".$ob."\"\n";
             
   $message  = "This is a multi-part message in MIME format.\n";
   $message .= "\n--".$ob."\n";
   $message .= "Content-Type: multipart/alternative;\n\tboundary=\"".$ib."\"\n\n";
   $message .= "\n--".$ib."\n";
   $message .= "Content-Type: text/plain;\n\tcharset=\"iso-8859-1\"\n";
   $message .= "Content-Transfer-Encoding: quoted-printable\n\n";
   $message .= $content."\n\n";
   $message .= "\n--".$ib."--\n";
   $message .= "\n--".$ob."--\n";
   
   mail($recipient, $subject, $message, $headers);
}
?>