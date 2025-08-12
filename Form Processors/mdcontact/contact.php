<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
	  	<title>Contact Me</title>
	</head>
<body>

<h2 align="left">Contact Me</h2>
<!-- Put headers above here -->
<?php
/*
--------------------------------------------------------------
|MD Contact version 2.1                                      |
|(c)Matthew Dingley 2002                                     |
|For more scripts or assistance go to MD Web at:             |
|www.matthewdingley.co.uk                                    |
|You may use this program only if the copyright remains      |
|intact. If it is not, it is a breach of your use of this    |
|program.                                                    |
|You are free to use this program on a non commercial site.  |
|To use this program on a commercial site, you need a licence|
|Go to my website for more details                           |
--------------------------------------------------------------
*/
//Here are the things you need to change
//Your email address
$emaddress = "youraddress@yourdomain.com";
//This is the colour that will go round the box if the user forgets to fill it in in HEX
$embordercolor = "#ff0000";
//The title of your website
$sitetitle = "My website";
//HTML E-mail. If you can recieve HTML e-mail you should set this to 1
//0 is off and 1 is on. Defult is off. You may want to change the $autobody variable in the code
$htmlemail = 1;
//Auto-responder. This will send a thank you message to the user. Note : Some people may find this annoying
//0 is off and 1 is on. Defult is off. You may want to change the $autobody variable in the code
$autoresponder = 1;
//Do not change anything below here
/*----------------------------------------------------------------------------------*/
$validstring = '^([._a-z0-9-]+[._a-z0-9-]*)@(([a-z0-9-]+\.)*([a-z0-9-]+)(\.[a-z]{2,3}))$';
if (!eregi($validstring,$email)&&$email) {
$emailcorrect = 0;
}
else  {
$emailcorrect = 1;
}
if($email&&$message&&$subject&&$name&&$emailcorrect) {
if($autoresponder)
{
$autobody = "Thank You, $name for sending me an e-mail through my website.
From
$sitetitle
";
$autosubject = "Thank you from $sitetitle";
mail($email, $autosubject, $autobody, "From: $sitetitle");
}
$headers = "From: " . trim($name) . "\n";
if($htmlemail)
{
$headers  .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$body = "E-mail is from " . $name . " at " . $email . " .<br>This is the message they sent:<br><br>" . $message . ".";

}
else
{
$body = "E-mail is from $name at $email. This is the message they sent: $message.";
}
$thesubject = "Website form: $subject";
if(mail($emaddress, $thesubject, $body, $headers)) {
echo "<br><br>Your e-mail was sent successfully. Thank you for the message " . $name . ".";
}
else {
echo "Sorry, $name, your e-mail was not sent successfully please try again<br><br>";
echo "<form name=\"theform\" method=\"get\" action=\"contact.php\">";
echo "Subject:<br><input name=\"subject\" type=\"text\" value=\"$subject\"><br>";
echo "Your name:<br><input name=\"name\" type=\"text\" value=\"$name\"><br>";
echo "Your e-mail:<br><input name=\"email\" type=\"text\" value=\"$email\"><br>";
echo "Message:<br><textarea name=\"message\" rows=4 cols=50>$message</textarea>";
echo "<br><input type=\"submit\" value=\"Send\" class=\"submit\"></form>";
echo "<br><font size=\"2\" color=\"#666666\">Contact form &copy; <a href=\"http://members.lycos.co.uk/matthewdingley/\">Matthew Dingley</a> 2002</font>";
}
}
if ((!$emailcorrect||!$email||!$message||!$subject||!$name)&&!(!$email&&!$message&&!$subject&&!$name)) {
echo "The Following errors have occurred.<br>";
echo "<ul>";
if (!$emailcorrect) {
echo "<li> Your e-mail address is not valid.";
}
if (!$email||!$message||!$subject||!$name) {
echo "<li> You have forgotten to fill in a detail.";
}
echo "</ul>Please correct the error(s) and resubmit the form";

echo "<style>";
if (!$email||!$emailcorrect) {
echo ".email {border-color:" . $embordercolor . ";}";
}
if (!$name) {
echo ".name {border-color:" . $embordercolor . ";}";
}
if (!$message) {
echo ".message {border-color:" . $embordercolor . ";}";
}
if (!$subject) {
echo ".subject {border-color:" . $embordercolor . ";}";
}
echo "</style>";
echo "<form name=\"theform\" method=\"get\" action=\"contact.php\">";
echo "Subject:<br><input name=\"subject\" type=\"text\" value=\"$subject\" class=\"subject\"><br>";
echo "Your name:<br><input name=\"name\" type=\"text\" value=\"$name\" class=\"name\"><br>";
echo "Your e-mail:<br><input name=\"email\" type=\"text\" value=\"$email\" class=\"email\"><br>";
echo "Message:<br><textarea name=\"message\" rows=4 cols=50 class=\"message\">$message</textarea>";
echo "<br><input type=\"submit\" value=\"Send\" class=\"submit\"></form>";
echo "<br><font size=\"2\" color=\"#666666\">Contact form &copy; <a href=\"http://members.lycos.co.uk/matthewdingley/\">Matthew Dingley</a> 2002</font>";
}
if(!$email&&!$message&&!$subject&&!$name) {
echo "If you would like to contact me please fill in the form below<BR>";
echo "<form name=\"theform\" method=\"get\" action=\"contact.php\">";
echo "Subject:</b><br><input name=\"subject\" type=\"text\" value=\"$subject\"><br>";
echo "Your name:</b><br><input name=\"name\" type=\"text\" value=\"$name\"><br>";
echo "Your e-mail:</b><br><input name=\"email\" type=\"text\" value=\"$email\"><br>";
echo "Message:</b><br><textarea name=\"message\" rows=4 cols=50>$message</textarea>";
echo "<br><input type=\"submit\" value=\"Send\" class=\"submit\"></form>";
echo "<br><font size=\"2\" color=\"#666666\">Contact form &copy; <a href=\"http://members.lycos.co.uk/matthewdingley/\">Matthew Dingley</a> 2002</font>";
}

?>
<!-- Put footers below here -->

</body>
</html>
