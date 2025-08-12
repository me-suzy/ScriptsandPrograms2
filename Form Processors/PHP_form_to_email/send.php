<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
       "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
  <head>
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Revisit-After" content="5 Days">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Thank you for contacting us</title>
  </head>
  <body bgcolor="#FFFFFF" text="#000000" link="#0000FF" alink="#0000FF" vlink="#0000FF">
   <h1>Thank you for contacting us</h1>

   <!-- your html -->

<?php
$to       = "you@where-ever.com"; // change to your email address
$name     = $_POST['name'];
$email    = $_POST['email'];
$subject  = $_POST['subject'];
$msg      = $_POST['msg'];
$d        = date('l dS \of F Y h:i:s A');
$sub      = "form to mail";
$headers  = "From: $name <$email>\n";  
$headers .= "Content-Type: text/plain; charset=iso-8859-1\n";
$mes      = "Subject: ".$subject."\n";
$mes     .= "Message: ".$msg."\n";
$mes     .= "Name: ".$name."\n";
$mes     .= 'Email: '.$email."\n";
$mes     .= 'Date & Time: '.$d;

if (empty($name) || empty($email) || empty($subject) || empty($msg))
{
     echo "   <h3>Sorry all fields are required.</h3>";
}
elseif(!ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
     print "   <h3>Sorry the email address you entered looks like it's invalid.</h3>";
}
else
{
     mail($to, $sub, $mes, $headers);
     print "   <h3><center>Thank you ".$name." for contacting us.<br>We will get back to you as soon as posiable</center></h3>"; 
}
?>

   <!-- your html -->

 </body>
</html>