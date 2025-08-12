<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/

require "../config.php";
$encrypt_emails_key = $encrypt_emails_key . str_rot13($encrypt_emails_key) . strrev($encrypt_emails_key); 
include "../inc/mysession.inc.php";
include "../inc/filefunctions.inc.php";
include "../inc/email.inc.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TinyWebGallery</title>
<meta name="author" content="mid" />
<link rel="stylesheet" type="text/css" href="iframe.css" />
<script type="text/javaScript">reload = false;</script>
<script type="text/javaScript" src="../js/twg_image.js"></script>
</head>
<body>
<?php

// TODO check the login !!!!
/*
	EasyLetter 1.2
	This script is part of Onlinetools 
	http://www.onlinetools.org/tools/easyletter.php
	
	The original script does still exist in may parts but was changed heavily to get
	a part of TWG
*/
$vars = explode(",", "pw,send,subject,message,email,action,sender");
foreach($vars as $v) {
    if ((isset($_GET[$v])) && ($_GET[$v] != "")) {
        $$v = $_GET[$v];
    } else {
        $$v = "";
    } 
    if ((isset($_POST[$v])) && ($_POST[$v] != "")) {
        $$v = $_POST[$v];
    } 
} 

require "../language/language_" . $default_language . ".php";

if (!$sender) {
  $sender = $youremail;
}

if (!$subject) {
  $subject = $default_subject;
}


if (!$message) {
  $message = sprintf($default_text, "http://" . $_SERVER['SERVER_NAME']  . $twg_root);
  // $message = $default_text;
}




// Name of the datafile
$filelocation = "../" . $xmldir . "/subscribers.xml";
// Title of the newsletter, will be displayed in the FROM field of the mailclient

if (!file_exists($filelocation)) {
    $newfile = fopen($filelocation, "w+");
    fclose($newfile);
} 
$newfile = fopen($filelocation, "r");
if (filesize($filelocation) != 0) {
    $content = fread($newfile, filesize($filelocation));
} else {
    $content = "";
} 
fclose($newfile);
$content = stripslashes($content);


?>
<table summary='' style="width: 100%; height:100%" class="centertable" cellpadding='0' cellspacing='0'><tr><td class="closebutton"> 
<?php
if ($login == "TRUE") {
echo '<img name="imageField" alt="" onClick="closeiframe()" align="right" src="../buttons/close.gif" width="12" height="12" border="0" />';
}
?>
</td></tr><tr><td class="messagecell">
<?php
if ($login <> "TRUE") {
echo $lang_email_admin_notloggedin;
$send = "no";
}

if ($send == "yes") { 
  if ($sender == "" or !checkmail($sender)) {
      $send = "no";
      printf ($lang_email_admin_sorryoddsendermailmessage, $sender);
  }
}

if (!$send) { 
    $nr_email = count(explode("%", $content));
    printf ($lang_email_admin_welcomemessage_send,($nr_email - 1)) ;
}

 if ($send != "yes" &&  ($login == "TRUE") ) {
    print '</td></tr><tr><td>';
    print'<form action="' .  $_SERVER['PHP_SELF'] . '" method="post"><input type="hidden" name="twg_lang" value="' . $default_language . '" />
    <input type="hidden" name="send" value="yes" /><input type="hidden" name="action" value="send" />
	    ' . $lang_email_admin_sendermail . ':<br />
		<input type="text" class="wideinput" name="sender" value="' . $sender . '" /><br />
		' . $lang_email_admin_subject . ':<br />
		<input type="text" class="wideinput" name="subject" value="' . $subject . '" /><br />
		' . $lang_email_admin_message . ':<br />
		<textarea rows="5" class="wideinput"  name="message">' . $message . '</textarea>&#160;
		<br /><input type="submit" class="sendbutton" value="' . $lang_email_admin_sendbutton . '" />
		</form>';
}

$mailheaders = "From: $sender\n";
$mailheaders .= "Reply-to: $sender\n";
// If you want to send HTML mail, uncomment this line!
// $mailheaders .= "Content-Type: text/html; charset=iso-8859-1\n";
if ($send == "yes") {
    $end = "\r\n\r\n -- \r\n" . sprintf($email_bottomtext, "http://" . $_SERVER['SERVER_NAME']  . $twg_root);
  //   $end = "test";
   //  $end = stripslashes($end);
    $htmlend = "<small>--<br/>" . sprintf($email_bottomtext, "http://" . $_SERVER['SERVER_NAME']  . $twg_root) . "</small>";
    $message = "" . stripslashes($message);
    $subject = stripslashes($subject);
    $lines = explode("%", $content);
    $offset = 0;
    foreach ($lines as $l) { 
        $l = decryptEmail($l,$offset++) ;// decrypt email  
        if ($l != "") {
          if ($enable_email_sending) {
            echo "sent: " . $l;
            mail ($l, $subject, str_replace("\n", "\r\n", $message . $end), $mailheaders);
          }
        }
    } 
    $message  = nl2br($message);
    if (!$enable_email_sending) {
        print "Debug mode is on - no real email was sent.<br />";
    }
    print "<b>" . $lang_email_admin_sent . "</b><br />";
    print "<table><tr><td class='left' >";
    print "<b>" . $lang_email_admin_from .":</b> ". $youremail . "<br><b>" . $lang_email_admin_sendermail . ":</b> " . $sender . "\n<br /><b>$lang_email_admin_subject: $subject</b>\n<br /><b>$lang_email_admin_message:</b><br />$message<p />$htmlend";
		print "</td></tr></table>";
}  
print '</td></tr></table>';

function checkmail($string)
{
    return preg_match("/^[^\s()<>@,;:\"\/\[\]?=]+@\w[\w-]*(\.\w[\w-]*)*\.[a-z]{2,}$/i", $string);
} 

?>
<?php include "i_bottom.inc.php"; ?>
</body>
</html>

