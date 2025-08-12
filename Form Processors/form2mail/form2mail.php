<?php 
# You can use this script to submit your forms or to receive orders by email.
$MailToAddress = "you@yoursite.com"; // your email address
$redirectURL = "http://www.web4future.com/thankyou.htm"; // the URL of the thank you page.
$MailSubject = "[Message from the contact form]"; // the subject of the email

# copyright 2005 Web4Future.com =================================================================================================

# If you are asking for a name and an email address in your form, you can name the input fields "name" and "email".
# If you do this, the message will apear to come from that email address and you can simply click the reply button to answer it.

# To block an IP, simply add it to the blockip.txt text file.

# If you have a multiple selection box or multiple checkboxes, you MUST name the multiple list box or checkbox as "name[]" instead of just "name" 
# you must also add "multiple" at the end of the tag like this: <select name="myselectname[]" multiple> 
# you have to do the same with checkboxes

# This script was written by George A. & Calin S. from Web4Future.com
# There are no copyrights in the sent emails.

/*****************************************************************

	Web4Future Easiest Form2Mail (GPL).
	Copyright (C) 1998-2005 Web4Future.com All Rights Reserved. 
	http://www.Web4Future.com/

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

*****************************************************************/

# DO NOT EDIT BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING ===================================================
$w4fver =  "2.0.4";
$w4fx = strstr(file_get_contents('blockip.txt'),getenv('REMOTE_ADDR')); 

if (preg_match ("/".str_replace("www.", "", $_SERVER["SERVER_NAME"])."/i", $_SERVER["HTTP_REFERER"]) && ($w4fx === FALSE)) {
$w4fMessage = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\"><html>\n<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"></head><body>\n";
if (count($_GET) >0) {
	reset($_GET);
	while(list($key, $val) = each($_GET)) {
		$GLOBALS[$key] = $val;
		if (is_array($val)) { 
			$w4fMessage .= "<b>$key:</b> ";
			foreach ($val as $vala) { 
				$vala =stripslashes($vala);
				$vala = htmlspecialchars($vala);
				$w4fMessage .= "$vala, ";
			} 
			$w4fMessage .= "<br>\n";
		} 	
		else {
			$val = stripslashes($val);
			if (($key == "Submit") || ($key == "submit")) { } 	
			else { 	if ($val == "") { $w4fMessage .= "$key: - <br>\n"; }
					else { $w4fMessage .= "<b>$key:</b> $val<br>\n"; }
			}
		}
	} // end while
}//end if
else {
	reset($_POST);
	while(list($key, $val) = each($_POST)) {
		$GLOBALS[$key] = $val;
		if (is_array($val)) { 
			$w4fMessage .= "<b>$key:</b> ";
			foreach ($val as $vala) { 
				$vala =stripslashes($vala);
				$vala = htmlspecialchars($vala);
				$w4fMessage .= "$vala, ";
			} 
			$w4fMessage .= "<br>\n";
		} 	
		else {
			$val = stripslashes($val);
			if (($key == "Submit") || ($key == "submit")) { } 	
			else { 	if ($val == "") { $w4fMessage .= "$key: - <br>\n"; }
					else { $w4fMessage .= "<b>$key:</b> $val<br>\n"; }
			}
		}
	} // end while
	}//end else
$w4fMessage .= "<font size=3D1><br><br>\n Sender IP: ".getenv('REMOTE_ADDR')."</font></font></body></html>";	
   $w4f_what = array("/To:/i", "/Cc:/i", "/Bcc:/i","/Content-Type:/i","/\n/");
	$name = preg_replace($w4f_what, "", $name);
	$email = preg_replace($w4f_what, "", $email);
if (!$email) {$email = $MailToAddress;}
	$mailHeader = "From: $name <$email>\r\n";
	$mailHeader .= "Reply-To: $name <$email>\r\n";
	$mailHeader .= "Message-ID: <". md5(rand()."".time()) ."@". ereg_replace("www.","",$_SERVER["SERVER_NAME"]) .">\r\n";
	$mailHeader .= "MIME-Version: 1.0\r\n";
	$mailHeader .= "Content-Type: multipart/alternative;";			
	$mailHeader .= " 	boundary=\"----=_NextPart_000_000E_01C5256B.0AEFE730\"\r\n";					
	$mailHeader .= "X-Priority: 3\r\n";
	$mailHeader .= "X-Mailer: PHP/" . phpversion()."\r\n";
	$mailHeader .= "X-MimeOLE: Produced By Web4Future Easiest Form2Mail $w4fver\r\n";
	$mailMessage = "This is a multi-part message in MIME format.\r\n\r\n";
	$mailMessage .= "------=_NextPart_000_000E_01C5256B.0AEFE730\r\n";
	$mailMessage .= "Content-Type: text/plain;   charset=\"ISO-8859-1\"\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n";			
	$mailMessage .= strip_tags($w4fMessage)."\r\n\r\n";			
	$mailMessage .= "------=_NextPart_000_000E_01C5256B.0AEFE730\r\n";			
	$mailMessage .= "Content-Type: text/html;   charset=\"ISO-8859-1\"\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n";			
	$mailMessage .= "$w4fMessage\r\n\r\n";			
	$mailMessage .= "------=_NextPart_000_000E_01C5256B.0AEFE730--\r\n";			

	if (!mail($MailToAddress, $MailSubject, $mailMessage,$mailHeader)) { echo "Error sending e-mail!";}
	else { header("Location: ".$redirectURL); }	
} else { echo "<center><font face=verdana size=3 color=red><b>ILLEGAL EXECUTION DETECTED!</b></font></center>";}
?>
