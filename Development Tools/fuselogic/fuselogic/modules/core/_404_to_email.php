<?php
$count = 0;
$email_to = 'contact_us@haltebis.com';
//$email_to = 'ekobudi@singnet.com.sg';

// don't send mail if we don't know the referring page
// it's probably a searchengine or infected windows system requesting known NT server exploits
if ($_SERVER['HTTP_REFERER'] == "")
{
$count++;
}
if ($email_to == "")
{
$count++;
}

// debug comment, you can remove this line if you want:
//echo "<!-- count: $count -->\n";
if ($count == 0)
{
// see table 1 on http://www.php.net/manual/en/function.date.php for the meaning of these codes:
$today = date("j F Y, G:i:s");

@$message = "Date and Time: $today\nRequest URL: http://$SERVER_NAME$REQUEST_URI\nReferring page: $HTTP_REFERER\n\nClient: $HTTP_USER_AGENT\nRemote IP: $REMOTE_ADDR\n\n";
$message .= "This is an automated message, no need to reply.\nThis message is sent every time a non-existant document is requested and we know where the visitor came from.\n\nHave a nice day.";
@mail($email_to, "FuseLogic Error 404", $message, "From: $email_to\nReply-To: $email_to");
}
?>
