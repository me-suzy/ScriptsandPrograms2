<?
require ('_.php');
require ('functions.php');
$userid = $_GET['userid'];


  //Create form 
if ($_POST[op] != "ds") 
	{$query = "SELECT first_name, last_name, email_address FROM users WHERE userid=". $userid;
$result =mysql_query($query);
$row=mysql_fetch_array($result);
$sendto_name = "$row[first_name]&nbsp;$row[last_name]";
$email = "$row[e_mail]";
mysql_close();
pageheader();
?>
<title>Send Mail to <?=$sendto_name?></title> 
</head> 


<?
include ('html/mail_form.html');
} 
else if ($_POST[op] == "ds") 
{
$userid=$_POST[userid];
	$query = "SELECT email_address FROM users WHERE userid=".$userid;
	$result =mysql_query($query);
	$row=mysql_fetch_array($result);
//	$sendto_name = "$row[first_name]&nbsp;$row[last_name]";
	$email= $row['email_address'];
	mysql_close();

// check value of $_POST[sender_name] 
if ($_POST[sender_name] == "") 
     { 
$name_err = "<font color=red>Please enter your name!<br></font>"; 
$send = "no"; 
} 
// check value of $_POST[sender_email] 
if ($_POST[sender_email] == "") 
{ 
$email_err = "<font color=red>Please enter your e-mail address!<br></font>"; 
$send = "no"; 
} 
//check value of $_POST[message] 
if ($_POST[message] == "") 
{ 
$message_err = "<font color=red>Please enter a message!<br></font>"; 
$send = "no"; 
} 


if ($send == "no"){
include ('html/mail_form.html&userid=".$userid."');
}

if ($send != "no") 
{ 
// it's ok to send, build mail 

// 

$subject = "Sent from N8cms\r\n"; 
$mailheaders = "From: N8s CMS  Mail Form <> \r\n"; 
$mailheaders .= "Reply-To: $_POST[sender_email]\r\n\r\n"; 

$msg = "E-MAIL SENT FROM WWW SITE\r\n"; 
$msg .= "Sender's Name:    $_POST[sender_name]\r\n"; 
$msg .= "Sender's E-Mail:   $_POST[sender_email]\r\n"; 
$msg .= "Message:              $_POST[message]\r\n\r\n"; 
// send the mail 
mail($email, $subject, $msg, $mailheaders); 
//display confirmation to user 
echo "<P>Mail has been sent! to ".$sendto_name."</p>"; 
echo"<script>setTimeout(document.location.replace('index.php'),90000);</script>";
} 
else if ($send == "no") 
{ 
echo "$name_err"; 
echo "$email_err"; 
echo "$message_err"; 
echo "$form_block"; 
    } 
} 
?> 
</body> 
</html> 
