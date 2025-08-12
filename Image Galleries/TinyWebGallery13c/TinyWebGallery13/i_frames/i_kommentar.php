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
include "../inc/mysession.inc.php";
include "../inc/filefunctions.inc.php";
include "i_parserequest.inc.php";


$titel = '';

if (isset($_GET['twg_titel'])) {
    $titel = $_GET['twg_titel'];
} else {
    $titel = false;
} 

if (isset($_GET['twg_name'])) {
    $name = $_GET['twg_name'];
} else {
    $name = false;
} 

/*
if (isset($_GET['twg_show'])) {
    $image_orig = urldecode($_GET['twg_show']);
		$image = str_replace("\\'", "'", $image_orig);
		$image_enc = urlencode($image);
} 

if (isset($_GET['twg_album'])) {
    $twg_album = urldecode($_GET['twg_album']); // fixed for ED ;)
    $album_enc = urlencode($twg_album);
}

*/

if (isset($_GET['twg_submit'])) {
    $submit = $_GET['twg_submit'];
} else {
    $submit = false;
}

require "../language/language_" . $default_language . ".php";

$titel = nl2br($titel);
$titel = stripslashes($titel);

$name = nl2br($name);
$name = stripslashes($name);

$xmldir = "../" . $xmldir;
include "../inc/readxml.inc.php";

// delete kommentare
if (isset($_GET['twg_delcomment'])) {
    if ($login <> "TRUE") {
		echo $lang_email_admin_notloggedin;
		return;
		}
    $twg_delcomment = $_GET['twg_delcomment'];
    $twg_delcomment = stripslashes($twg_delcomment);
    deleteKommentar($twg_delcomment, $twg_album , $image , $kwerte , $kindex);
} 

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
<table summary='' style="width: 100%; height:100%" cellpadding='0' cellspacing='0'><tr><td class="closebutton"> 
<img name="imageField" alt='' onClick="closeiframe()" align="right" src="../buttons/close.gif" width="12" height="12" border="0" />
</td></tr><tr><td> 
 <form action="<?php print $_SERVER['PHP_SELF'];

?>" method="get">
 <input name="twg_album" type="hidden" value="<?php echo encodespace($twg_album);

?>"/>
 <input name="twg_show" type="hidden" value="<?php echo encodespace($image);

?>"/>
 <input name="twg_submit" type="hidden" value="true"/>
<?php echo $hiddenvals; ?>
<?php
loadXMLFiles(urldecode($twg_album));

if (($name == false) && ($titel == false) && $submit) {
    echo $lang_kommenar_php_both_fields;
} else if ($name == false && $submit) {
    echo $lang_kommenar_php_enter_name;
} else if ($titel == false) {
    echo $lang_kommenar_php_enter_comment;
} else {
    $_SESSION["actalbum"] = "LOAD NEW";
    loadXMLFiles(urldecode($twg_album));
    saveKommentar($titel, $name, $twg_album, $image, $kwerte, $kindex, $image_orig);
    // send an email if set to true !
				if ($send_notification_if_comment) {
					$submailheaders = "From: $youremail\n";
					$submailheaders .= "Reply-To: $youremail\n";
					if ($enable_email_sending) {
						$link = "http://" . $_SERVER['SERVER_NAME']  . urldecode($twg_root) ."?twg_album=" . $album_enc  . "&twg_show=" . $image_enc;
						@mail($admin_email, html_entity_decode ($notification_comment_subject), html_entity_decode (str_replace("\n", "\r\n", sprintf($notification_comment_text, $link)) . "\r\n\r\n" . $name . "\r\n" . $titel), $submailheaders); 					  
					}
				}  
		//
    
    if (isset($_GET["PHPSESSID"])) {
		       $closescript = "<script>closeiframe(); if (reload) { parent.location='" . urldecode($twg_root) ."?PHPSESSID=" . $_GET["PHPSESSID"] . "&twg_album=" . $album_enc  . "&twg_show=" . $image_enc . $twg_standalone . "'  }</script>";
		    } else {
		       $closescript = "<script>closeiframe(); if (reload) { parent.location='" . urldecode($twg_root) ."?twg_album=" . $album_enc  . "&twg_show=" . $image_enc . $twg_standalonejs . "'  }</script>";
    }
    echo $closescript;
} 

?>
<br/><img alt='' src="../buttons/1x1.gif" width="6" height="6" /><br/>
  <?php echo $lang_kommenar_php_name ?><br/><input name="twg_name" type="text" value="<?php echo $name ?>" size="20"/>
  <br/><img alt='' src="../buttons/1x1.gif" width="6" height="6" /><br/><?php echo $lang_kommenar_php_comment ?><center><table  summary='' border=0 cellpadding='0' cellspacing='0'><tr><td class='tdcomment'><?php
  if ($enable_smily_support) { 
	echo'
	  <img alt="" onmouseover="javascript:show_smilie_div()" src="../buttons/smilie.gif" width="15" height="15" /><img alt="" src="../buttons/1x1.gif" width="6" height="6" /></td><td class="tdcomment">';
}
  
  ?><input id="twg_titel" name="twg_titel" type="text" size="38" value="<?php echo $titel ?>"/></td></tr></table></center>
  <img alt='' src="../buttons/1x1.gif" width="6" height="6" /><br/>
  <input type="submit" name="twg_submit" value="<?php echo $lang_kommenar_php_speichern ?>" />
</form>
</td></tr></table>
<?php
if ($enable_smily_support) {
echo '
<div id="twg_smilie" class="twg_smiliedivcomment"><table summary="" cellpadding="0" cellspacing="0"><tr><td class="twg_smilie">'. create_smilie_div() . '</td></tr></table></div>

<div id="twg_smilie_bord" class="twg_smiliedivbordercomment" onmouseover="javascript:hide_smilie_div()" ></div>

';
}

if ($show_comments_in_layer) {
  $comment_data_raw =  getKommentar($image, $twg_album, $kwerte, $kindex, true);
  $comment_data = substr($comment_data_raw,10);
  $comment_count = sprintf("%d", substr($comment_data_raw,0,10));
 
	echo "<br /><div class='twg_underlineb'>" . $lang_comments . " (" . $comment_count .  ")" .  "</div><br/>";
	echo "<center><table summary=''>";
	echo "<tr><td id='kommentartd' class='twg_kommentar'><img alt='' src='../buttons/1x1.gif' width='260' height='1' /><br />";
	echo $comment_data;
	echo "</td></tr></table></center>";
}
?>
<?php include "i_bottom.inc.php"; ?>
</body>
</html>