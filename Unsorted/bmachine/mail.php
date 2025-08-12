<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/

// MAIL ARTICLE TO A FRIEND SCRIPT


include_once "config.php";
if($db == "mysql") { include_once "inc/mysql/mail.mysql.php"; } else { include_once "inc/flat/mail.flat.php"; }

if(!$_POST[act] && !trim($_GET[id])) { header("Location: index.php?mail=false") or scrpt("index.php?mail=false"); exit(); }

if($_POST[in]) { $in=$_POST[in]; }
if($_GET[id]) { $in=$_GET[id]; }

$ar=getPostDetails($in); // Get the data for the post

// Return to the main page if invalid id is provided
if(!trim($ar[title])) { header("Location: index.php?mail=false") or scrpt("index.php?mail=false"); exit(); }

// Send to friend Form

if(!$_POST[act]) {
hdr("Send the article \"$ar[title]\" to a friend","");
include "inc/templates/mail.friend.php";
ftr("",""); exit();
}

////////////////////

// Some Form validation

if($_POST[email] == "" or strpos($_POST[email], "@") == "") {
hdr("Invalid Email Address!","");
$b=<<<EOF
<br><br>
<p align="center"><font color="red" size="4" face="Arial"><b>Invalid EMAIL address!</b><br></font><font color="red" size="2" face="Verdana">You 
have entered an invalid email in the "email" field!</font></p></br></br>
EOF;
echo $b;
ftr("","");
exit();
}

if($_POST[e1] && strpos($_POST[email], "@") == "") {
hdr("Invalid Email Address!","");
$b=<<<EOF
<br><br>
<p align="center"><font color="red" size="4" face="Arial"><b>Invalid EMAIL address!</b><br></font><font color="red" size="2" face="Verdana">You 
have entered an invalid email in the "to" email field!</font></p></br></br>
EOF;
echo $b;
ftr("","");
exit();
}

if($_POST[e2] && strpos($_POST[email], "@") == "") {
hdr("Invalid Email Address!","");
$b=<<<EOF
<br><br>
<p align="center"><font color="red" size="4" face="Arial"><b>Invalid EMAIL address!</b><br></font><font color="red" size="2" face="Verdana">You 
have entered an invalid email in the "to" email field!</font></p></br></br>
EOF;
echo $b;
ftr("","");
exit();
}

if($_POST[e3] && strpos($_POST[email], "@") == "") {
hdr("Invalid Email Address!","");
$b=<<<EOF
<br><br>
<p align="center"><font color="red" size="4" face="Arial"><b>Invalid EMAIL address!</b><br></font><font color="red" size="2" face="Verdana">You 
have entered an invalid email in the "to" email field!</font></p></br></br>
EOF;
echo $b;
ftr("","");
exit();
}

if($_POST[e4] && strpos($_POST[email], "@") == "") {
hdr("Invalid Email Address!","");
$b=<<<EOF
<br><br>
<p align="center"><font color="red" size="4" face="Arial"><b>Invalid EMAIL address!</b><br></font><font color="red" size="2" face="Verdana">You 
have entered an invalid email in the "to" email field!</font></p></br></br>
EOF;
echo $b;
ftr("","");
exit();
}

if($_POST[e5] && strpos($_POST[email], "@") == "") {
hdr("Invalid Email Address!","");
$b=<<<EOF
<br><br>
<p align="center"><font color="red" size="4" face="Arial"><b>Invalid EMAIL address!</b><br></font><font color="red" size="2" face="Verdana">You 
have entered an invalid email in the "to" email field!</font></p></br></br>
EOF;
echo $b;
ftr("","");
exit();
}

///////////////////////////////////////////////////
$dtm=date("d/m/Y");

$subject=str_replace("[NAME]", $_POST[name], $subject);


// Open the mail.txt template file and replace keywords
// with appropriate data

$ind=@fread(fopen("inc/templates/mail.txt", "r"), 1000000);

$ind=str_replace("[NAME]", $_POST[name], $ind);
$ind=str_replace("[URL]", "$c_urls/index.php?id=$_POST[in]", $ind);
$ind=str_replace("[EMAIL]", $_POST[email], $ind);
$ind=str_replace("[TITLE]", $ar[title], $ind);
$ind=str_replace("[AUTHOR]", $ar[a_name], $ind);
$ind=str_replace("[AUTHOR_EMAIL]", $ar[a_email], $ind);
$ind=str_replace("[COMMENTS]", $_POST[comments], $ind);
$ind=str_replace("[DATE]", $dtm, $ind);

// Set mail headers
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/plain\r\n";
$headers .= "From: $_POST[email]\r\n";
$headers .= "Reply-To: $_POST[email]\r\n";
$headers .= "X-Mailer: Server - $c_url";

// Send to the emails, if entered
if($_POST[e1]) { mail(trim($_POST[e1]), $subject, $ind, $headers); }
if($_POST[e2]) { mail(trim($_POST[e2]), $subject, $ind, $headers); }
if($_POST[e3]) { mail(trim($_POST[e3]), $subject, $ind, $headers); }
if($_POST[e4]) { mail(trim($_POST[e4]), $subject, $ind, $headers); }
if($_POST[e5]) { mail(trim($_POST[e5]), $subject, $ind, $headers); }


// Log the data if set
if($m_log) {
$a = fopen("$m_log", "a") or errd("Cannot write to the LOG file!", "Unable to write to the log.txt file!<br>Please check whether the file exists and its permission is 777");
$write = fputs($a, "$dtm|$_POST[name]|$_POST[email]|");
if($_POST[e1]) { fputs($a, "$_POST[e1]|"); }
if($_POST[e2]) { fputs($a, "$_POST[e2]|"); }
if($_POST[e3]) { fputs($a, "$_POST[e3]|"); }
if($_POST[e4]) { fputs($a, "$_POST[e4]|"); }
if($_POST[e5]) { fputs($a, "$_POST[e5]|"); }
fputs($a, "\n");
fclose($a);
}

hdr("Article sent successfully!","");
echo <<<EOF
<br><br>
<span class="content">Thank You!<br>The article "<a href="index.php?id=$_POST[in]">$ar[title]</a>" was sent successfully to the following 
recipients<br></span>
<table align="center" border="0" cellpadding="4" cellspacing="0" width="245">
<tr>
<td width="245">
<a href="mailto:$_POST[e1]"><span class="t_small">$_POST[e1]</span></a><br>
<a href="mailto:$_POST[e1]"><span class="t_small">$_POST[e2]</span></a><br>
<a href="mailto:$_POST[e1]"><span class="t_small">$_POST[e3]</span></a><br>
<a href="mailto:$_POST[e1]"><span class="t_small">$_POST[e4]</span></a><br>
<a href="mailto:$_POST[e1]"><span class="t_small">$_POST[e5]</span></a><br>
</td>
</tr>
</table>
<br><br>
EOF;
ftr("","");

?> 