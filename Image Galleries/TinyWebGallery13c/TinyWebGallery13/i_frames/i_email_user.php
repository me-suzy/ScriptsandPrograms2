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
// we make the encryption key a little bit longer :)
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

// Where is your newsletter located? (For deletion and confirmation (I this will ever be build :)) link)
$newsletterlocation = "http://localhost/easyletter.php";
// Name of the datafile
$filelocation = "../" . $xmldir . "/subscribers.xml";
// pattern for filtering out own emails // we don't do this
$pattern = "bar.bar";
$localmessage  = "";

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
$out = "";
$lines = explode("%", $content);
$found = 0;
$offsetcode = 0;
$offsetencode = 0;;
foreach($lines as $l) {
    $l = decryptEmail($l,$offsetencode++);
    if ($l != $email) {
        if ($l != "") {
          $out .= "%" . cryptEmail($l, $offsetcode++);
        }
    } else {
        $found = 1;
    } 
} 

?>
<table summary='' style="width: 100%; height:100%" class="centertable" cellpadding='0' cellspacing='0'><tr><td class="closebutton"> 
<img name="imageField" alt='' onClick="closeiframe()" align="right" src="../buttons/close.gif" width="12" height="12" border="0" />
</td></tr><tr><td>
<?php
if ($action == "sign") {
    if ($found == 1 or $email == "" or !checkmail($email) or preg_match("/" . $pattern . "/", $email)) {
        if ($email == "") {
            $localmessage =  $lang_email_sorryblankmailmessage;
        } else if ($found == 1) {
            $localmessage =  sprintf($lang_email_sorrysignmessage,$email);
        } else if (!checkmail($email)) {
            $localmessage =  sprintf($lang_email_sorryoddmailmessage,$email);
        } else if (preg_match("/" . $pattern . "/", $email)) {
            $localmessage = $lang_email_sorryownmailmessage;
        } 
    } else {
        $newfile = fopen($filelocation, "a+");
        $emailenc = cryptEmail($email,$offsetencode); // we cypt the email
        $add = "%" . $emailenc;
        fwrite($newfile, $add);
        fclose($newfile);
        // mail ($youremail,"New newsletter subscriber.",$email."\nDelete? $newsletterlocation?action=delete&email=".$email,"From: Newsletter\nReply-To: $email\n");
        $submailheaders = "From: $youremail\n";
        $submailheaders .= "Reply-To: $youremail\n";
        if ($enable_email_sending) {
					if(!@mail ($email, html_entity_decode ($lang_email_subscribemail_subject), html_entity_decode (str_replace("\n", "\r\n", $lang_email_subscribemail)), $submailheaders)) {
						$localmessage = $lang_email_error_send_mail;
					} else {
						$localmessage = $lang_email_subscribemessage; 
					}
        } else {
          $localmessage = $lang_email_subscribemail;
        }
        
    } 
} 

if ($action == "delete") {
    if ($email == "") {
            $localmessage =  $lang_email_sorryblankmailmessage;
    } else if ($found == 1) {
        $newfile = fopen($filelocation, "w+");
        fwrite($newfile, $out);
        fclose($newfile);
        $newfile = fopen($filelocation, "r");
        $localmessage =  sprintf($lang_email_unsubscribemessage, $email);
    } else if ($found != 1) {
        $localmessage =  sprintf($lang_email_failedunsubscriptionmessage,$email);
    } 
}  
// print $welcomemessage;

if (!$action) {	
  $localmessage = $lang_email_welcomemessage;
}

echo '
	<form action="' . $_SERVER['PHP_SELF'] . '" method="get">
	<input type="hidden" name="twg_lang" value="' . $default_language . '" /> 
	<table summary="" cellpadding="0" cellspacing="0" align="center">
	<tr><td class="messagecell">'
	. $localmessage . '
	</td></tr><tr><td class="centertable">
  <input type="text" name="email" class="inputsmall" value="" size="25" />
	</td></tr><tr><td class="centertable">
	<input type="radio" name="action" value="sign" checked="checked" />' . $lang_email_add  . '
	<input type="radio" name="action" value="delete" />' . $lang_email_remove . '
	</td></tr><tr><td class="centertable">
	<input type="submit" value=" ' . $lang_email_send . '! " class="buttonemail" /> 
	</td></tr></table>
	</form>
	';
print '</td></tr></table>';

function checkmail($string)
{
    $test1 =  preg_match("/^[^\s()<>@,;:\"\/\[\]?=]+@\w[\w-]*(\.\w[\w-]*)*\.[a-z]{2,}$/i", $string);
    if ($test1) {
      return testEmailDomain($string);
    } else {
      return true;
    }   
} 
?>
<?php include "i_bottom.inc.php"; ?>
</body>
</html>