
<? 
include ("atho.inc.php");
include ("config.inc.php");
include ("header.php");
?>
<?php /* This page was wriiten by Joe Norman, www.intranet2internet.com */ ?>
<html>
<head>
<title></title>
<STYLE>
	TD, P, LI{			font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 8pt;}
	A{				font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 8pt;color:#336666;text-decoration: none;}
	A:HOVER{			font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 8pt;color:#996600;text-decoration: underline;}
	INPUT, TEXTAREA, SELECT{	font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 8pt;color: black;background: white;}
	.MENU{				font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 8pt;color: #000000;border : 1px Solid Silver;background: #ffffff;}
	H2{				font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12pt;color: #996600;}
</STYLE>
</head>
<BODY>
<?php

if(empty($SEND)){$SEND="";}

switch($SEND){
	case "Send":
		if(!empty($MESSAGE)){
			if(empty($SUBJECT)){$SUBJECT="";}
			$HEADERS  = "MIME-Version: 1.0\r\n";
			$HEADERS .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$HEADERS .= "From: Lizard Cart <[ ts@2tonewebdesign.com ]>\r\n";
			/* [ REPLY EMAIL ADDRESS] should be replaced with the appropriate email address */

			/* This script is based on pulling email addresses from a MySQL user database The code below should be changed to the appropriate information */
			

			//$linkID = mysql_connect([hostname [:port] [:path/to/socket], [username], [password]);
			//mysql_select_db([database], $linkID);

			$dbResult = mysql_query("SELECT email FROM orders");
		
			while($row = mysql_fetch_row($dbResult)){
				foreach ($row as $field){
					mail($field, $SUBJECT, $MESSAGE, $HEADERS);
					print "Message sent to: <b>$field</b><br>";
				}
			}

			mysql_close($dbh);
			print "<hr><b>MESSAGE DETAILS</b><hr>";
			print "<b>Subject:</b> $SUBJECT<br>";
			print "<b>Message:</b><p>$MESSAGE";
		}
		break;
	case "Preview":
		print "<TABLE BORDER=1 CELLSPACING=0 WIDTH=100% ><TR><TD VALIGN=TOP>$MESSAGE</TD>";
		print "<TD VALIGN=TOP><FORM ACTION=automail.php  METHOD=POST NAME=FORM1 >";
		print "<B>SUBJECT: </B><Input TYPE=TEXT NAME=SUBJECT VALUE='$SUBJECT' ><BR>";
		print "<TEXTAREA NAME=MESSAGE STYLE='width:500;height:300' WRAP=off  >$MESSAGE</TEXTAREA><BR>";
		print "<INPUT TYPE=SUBMIT VALUE=Preview NAME=SEND > <INPUT TYPE=BUTTON ONCLICK=\"document.FORM1.MESSAGE.value=''\" VALUE=Reset >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE=SUBMIT VALUE=Send NAME=SEND > ";
		print "</FORM></TD></TR></TABLE>";
		break;
	default:
		print "<FORM ACTION=automail.php  METHOD=POST NAME=FORM1 >";
		print "<B>SUBJECT: </B><Input TYPE=TEXT NAME=SUBJECT VALUE='' ><BR>";
		print "Please type in html code below.<BR>";
		print "<TEXTAREA NAME=MESSAGE STYLE='width:500;height:300' WRAP=off  ></TEXTAREA><BR>";
		print "<INPUT TYPE=SUBMIT VALUE=Preview NAME=SEND > <INPUT TYPE=BUTTON ONCLICK=\"document.FORM1.MESSAGE.value=''\" VALUE=Reset >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE=SUBMIT VALUE=Send NAME=SEND > ";
		print "</FORM>";
}

?>
</BODY></HTML>
<? include ("footer.php");?>