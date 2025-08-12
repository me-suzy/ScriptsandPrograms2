<?php
// set time limit to 15 minutes (900/60)
set_time_limit(900);
include("connect.php");
	$id = preg_replace("/'\/<>\"/","",$_GET['id']);
	if (empty($id))
	die("Invalid ID");
	$link = "SELECT * FROM newsletters WHERE id='$id'";
	$res = mysql_query($link) or die(mysql_error());
	$r = mysql_fetch_assoc($res);
	$subject = $r['name'];
	$message = $r['content'];

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: newsletter@' . $_SERVER['SERVER_NAME'] . "\r\n" .
	'Reply-To: newsletter@' . $_SERVER['SERVER_NAME'] . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	       
	$link = "SELECT * FROM users WHERE status='subscribed'";
	$res = mysql_query($link) or die(mysql_error());
	while ($r = mysql_fetch_row($res))
	{
		$email = $r['email'];
		$mail = mail($email, $subject, $message, $headers);
	}
    /*
    if ($mail)
    {
    echo "Email sent to " . $email . '<br>';
    die;
    }
    else
    {
    echo "Error in mailing " . $email . '<br>';
    die;
    } 
    */
?>