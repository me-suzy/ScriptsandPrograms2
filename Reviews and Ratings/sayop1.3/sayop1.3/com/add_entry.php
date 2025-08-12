<?php
/*


	Copyright (C) 2004-2005 Alex B

	E-Mail: dirmass@devplant.com
	URL: http://www.devplant.com
	
    This file is part of SayOp.

    SayOp is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.

    SayOp is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with SayOp; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


*/
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////WARNING/////////////////////////////////////////////
/////THIS FILE CONTAINS OBSCENE LANGUAGE                                                   /////
/////THIS IS TO IDENTIFY AND FILER ANY 'BAD' WORDS THAT ARE ENTERED INTO THE FORMS         /////
/////THE AUTHOR OF THIS SCRIPT DOES NOT WANT TO OFFEND ANYONE WITH THIS CONTENT            /////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////










session_start();
include("db.php");
include("../inc/redir.php");
include("../inc/auth.php");






//
//Allowed HTML tags, you can add or remove tags that you want to be used in the comments. For example, to enable hyperlinks add the <a> tag/
//
$allowedtags = '<i><pre>';








//
$badwords = array(
///////////////////////
//You can add any bad words by using: 'badword' => 'what to replace with', 
///////////////////////
'sex' => '***',
'fuck' => '****',
'shit' => '****',
'piss' => '****',
'dick' => '****',
'motherfucker' => '************',
'fuckyou' => '*******',
'gay' => '***',
'homosexual' => '**********',
'fucking' => '*******',
'fuck_you' => '*********',
'dickless' => '********',
'mother fucker' => '****** ******',
'dumbass' => '*******',
'dumbarse' => '********',
' ass' => ' ***',
'arse' => '****',
'sperm,' => '*****',
'cock' => '****',
'coq' => '***',
'sexual' => '******',
'fucker' => '******',
'farking' => '*******',
'pussy' => '*****',
'fuxor' => '*****',
'viagra' => '',
'lesbian' => '*******',
'f u c k' => '* * * *',
'f u c k y o u' => '* * * * * * *',
'penis' => '*****',
'condom' => '******',
'shitface' => '********',
'dumbfuck' => '********',
'f_u_c_k' => '*_*_*_*',
'asshole' => '*******',
'cunt' => '****',
'cocksucker' => '**********',
'tits' => '****',
'blowjob' => '*******',
'suck' => '****',
'whore' => '*****',
'fuc k' => '*** *',
'f uck' => '* ***',
'fu ck' => '** **',
'f uck' => '* ***',
's e x' => '* * *',
'semen-sucking' => '*****-********',
'bitch' => '*****',
'damn' => '****',
'bullshit' => '********',
'motherfuker' => '***********',
'fuker' => '*****',
'semen'=>'*****',
'anal' => '****',
'wanker' => '******',
'bastard' => '*******',
'goddamn' => '*******',
'fuk' => '***',

);

$catid = $_POST["catid"];
$obj_name = $_POST["obj_name"];
$author = $_POST["author"];
$comment = $_POST["comment"];
$date = date("g:i a, j F Y");
$email = $_POST["email"];
$ip = $_SERVER['REMOTE_ADDR'];
$referrer =  $_SERVER['HTTP_REFERER'];

$time = explode(' ', microtime());
$post_time = $time[1];


$stripAttrib = 'javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|'.
               'onmousemove|onmouseout|onkeypress|onkeydown|onkeyup';
function tremove($source) {
   global $allowedtags;
   $source = strip_tags($source, $allowedtags);
   return preg_replace('/<(.*?)>/ie', "'<'.aremove('\\1').'>'", $source);
}
function aremove($tagSource) {
   global $stripAttrib;
   return stripslashes(preg_replace("/$stripAttrib/i", 'forbidden', $tagSource));
}


if(empty($comment) || $comment==" " || $comment=="\n") { 
smsg("Your comment was blank, please <a href='javascript:history.back()'>go back</a> to correct");
exit();
}


$cleancomm = tremove($comment);
$cleancomm = strtr($cleancomm, $badwords);
$getsmile = mysql_query("SELECT code,smilie FROM ".$so_prefix."_smilies") or die("Sql error >>>" . mysql_error());
if(mysql_num_rows($getsmile) > 0) {

while($s = mysql_fetch_array($getsmile)) 
{


$code[]=$s['code'];
$smilie[]=$s['smilie'];

$fincomm = str_replace($code, $smilie, $cleancomm);
}

} else {
$fincomm = $cleancomm;}
$fincomm = str_replace("\n", "<br />", ltrim(rtrim($fincomm)));

$cauthor = tremove($cauthor);
$cauthor = strtr($author, $badwords);
if (empty($cauthor)) { 
$cauthor = "Anonymous";
}



if(!empty($_POST["email"])) {
$email = $_POST["email"]; 
  if(ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $email)) {
    $email;
}
  else {
smsg("The email you entered in not valid, please <a href='javascript: history.back()'>go back</a> to correct.");
    exit();
}

}

$email = strtr($email, $badwords);
$email  = str_replace('@', ' [ at ] ', $email);
$email  = str_replace('.', ' [ dot ] ', $email);


$getbannedips = mysql_query("SELECT * FROM ".$so_prefix."_bannedip") or die("Sql error:" . mysql_error());

while ($iparray = mysql_fetch_row($getbannedips)) {
if($ip == $iparray[1]) {
smsg("YOU HAVE BEEN BANNED FROM POSTING ANY COMMENTS!");
exit();
}

}


if(!$user == $_SESSION["username"] && !$pass == $_SESSION["password"]) {
$getip = mysql_query("SELECT ip, date, post_time FROM ".$so_prefix."_main") or die("Sql error:" . mysql_error());

while ($udata = mysql_fetch_row($getip)) {

if($ip == $udata[0]) {

if($post_time - $udata[2] <= "30") {
$d = 30 - ( $post_time - $udata[2] );
smsg("You must wait ".$d." seconds before posting another comment.");
exit();
}

}

}
                                                                       }
	
if($mail_posts == "1") {	
																	   
	$subject = "SayOp - Comment added";
	$body = "
Hello, this email is to notify you that a comment has been posted in \"".$obj_name."\"\n
by ".$cauthor." at ".$date."

This is the posted entry:
---------------
 ".$fincomm." 
---------------
 
You can access your control panel to moderate the posts at: ".$fullurl."
	";

@mail($alerts_email_addy , $subject , $body, "From: SayOp-comments-alert@{$_SERVER['SERVER_NAME']}\nX-Mailer: PHP/" . phpversion());

					}
																		
$insert = "INSERT INTO ".$so_prefix."_main (obj_name,catid,author,email,comment,date,ip,post_time) 
    VALUES ('$obj_name','$catid','$cauthor','$email','$fincomm','$date','$ip','$post_time')"; 
    mysql_query($insert) or die("Sorry, could not add entry to table " . mysql_error());


smsg("New entry added successfully!<br />If the page doesn&#39;t refresh, follow <a href='$referrer'>this link</a>.");
header('Refresh: 3; URL=' . $referrer .' ');
?>