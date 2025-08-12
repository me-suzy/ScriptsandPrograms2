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

$rating = '';

if (isset($_GET['twg_rating'])) {
    $rating = $_GET['twg_rating'];
} else {
    $rating = false;
} 

if (isset($_GET['twg_rating_page2'])) {
    $page2 = true;
} else {
    $page2 = false;
} 

if (isset($_GET['c'])) {
    $c = $_GET['c'];
} else {
    $c = false;
} 

require "../language/language_" . $default_language . ".php";

$xmldir = "../" . $xmldir;
include "../inc/readxml.inc.php";
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
 <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="get">
<table summary=''  style="width: 100%; height:100%" cellpadding='0' cellspacing='0'><tr><td class="closebutton"> 
<img name="imageField" alt='' onClick="closeiframe();" align="right" src="../buttons/close.gif" width="12" height="12" border="0" />
</td></tr><tr><td class="pad"> 
 <input name="twg_album" type="hidden" value="<?php echo encodespace($twg_album);
?>"/>
 <input name="twg_show" type="hidden" value="<?php echo encodespace($image);
?>"/>
<?php echo $hiddenvals; ?>
<?php

if (!$show_rating_security_image) { // we skip the security question
 $page2 = false;
 $c = "1";
 $_SESSION['twg_key'] = 1;
}

if ($rating == false) {
    echo $lang_rating_text;
    echo '<input name="twg_rating_page2" type="hidden" value="true" />';    
} else if ($page2) {
echo $lang_rating_security . '
  <br />
  <img alt="" src="../buttons/1x1.gif" width="1" height="7" /><br />
	<center><table summary="" cellpadding=5><tr><td>
	<a href="javascript:location.reload();"><img border="0" src="i_tacs.inc.php" alt="CAPTCHA IMAGE" /></a></td><td>
	<input type="text" name="c" size="10" /><br /><img alt="" src="../buttons/1x1.gif" width="1" height="3" /><br />
	<input name="twg_rating" type="hidden" value="' . $rating . '" />
	<input type="submit" name="check" value="' . $lang_rating_send . '" />
	</td></tr></table></center><span class=help>'. $lang_rating_help . '</span>';
	echo '</td></tr></table></form></body></html>';
   return;
   } else {
        if ($c &&  $_SESSION['twg_key'] == strtolower($c)) {
		        if (!increaseVotesCount($twg_album, $image, $rating)) {
						      echo "<br />&nbsp;<br/>";
						      echo $lang_rating_message1 . "<br />&nbsp;<br/>". $lang_rating_message2 ;
						    } else {
						      // send an email if set to true !
						      if ($send_notification_if_rating) {
						        $submailheaders = "From: $youremail\n";
                    $submailheaders .= "Reply-To: $youremail\n";
                    if ($enable_email_sending) {
                      $link = "http://" . $_SERVER['SERVER_NAME']  . urldecode($twg_root) ."?twg_album=" . $album_enc  . "&twg_show=" . $image_enc;
					            @mail($admin_email, html_entity_decode ($notification_rating_subject), html_entity_decode (str_replace("\n", "\r\n", sprintf($notification_rating_text, $link)) . "\r\n\r\n" . $rating), $submailheaders); 					  
						        }
						      }  
						      //
						      $_SESSION["actalbum"] = "LOAD NEW";
						      echo "<br />&nbsp;<br/>";
						    	echo $lang_rating_message3 . "<br />" . $lang_rating_new . getVotesCount($twg_album, $image) ."<br />&nbsp;<br/>" . $lang_rating_message2;
    }
		             
		        } else {
		            	echo $lang_rating_message4 . "<br />";
						    	echo '
									  <img alt="" src="../buttons/1x1.gif" width="1" height="7" /><br />
										<center><table cellpadding=5 summary=""><tr><td>
										<a href="javascript:location.reload();"><img border="0" src="i_tacs.inc.php" alt="CAPTCHA IMAGE" /></a></td><td>
										<input type="text" name="c" size="10" /><br /><img alt="" src="../buttons/1x1.gif" width="1" height="3" /><br />
										<input name="twg_rating" type="hidden" value="' . $rating . '">
										<input type="submit" name="check" value="' . $lang_rating_send . '" />
	                  </form></td></tr></table></center><span class=help>'. $lang_rating_help . '</span>';
		}
		
    if (isset($_GET["PHPSESSID"])) {
       $closescript = "<script>closeiframe(); if (reload) { parent.location='" . urldecode($twg_root) ."?PHPSESSID=" . $_GET["PHPSESSID"] . "&twg_album=" . $album_enc  . "&twg_show=" . $image_enc . $twg_standalonejs . "'  }</script>";
    } else {
       $closescript = "<script>closeiframe(); if (reload) { parent.location='" . urldecode($twg_root) ."?twg_album=" . $album_enc  . "&twg_show=" . $image_enc . $twg_standalonejs . "'  }</script>";
    }
     // $closescript = "<script>closeiframe(); if (reload) { parent.location.reload();  }</script>";
    // echo $closescript;
    echo '</td></tr></table></form></body></html>';
    return;
    }
    

echo'
  <center><img alt="" src="../buttons/1x1.gif" width="1" height="2" /><table summary="" ><tr><td  class="vote">';
?>   
<table summary=''  cellpadding='0' cellspacing='0'>
<tr align="center"><td colspan=5  class="vote"><?php echo $lang_rating . ": " . getVotesCount($twg_album, $image); ?><br /><img alt='' src="../buttons/1x1.gif" width="5" height="10" /></td>

<tr align="center"><td class="vote">
  <img alt="<?php echo $lang_rating1 ?>"  src="../buttons/smilie_1.gif" width="15" height="15" /></td>
<td class="vote">
  <img alt="<?php echo $lang_rating2 ?>"  src="../buttons/smilie_2.gif" width="15" height="15" /></td>
<td class="vote">
  <img alt="<?php echo $lang_rating3 ?>"  src="../buttons/smilie_3.gif" width="15" height="15" /></td>
<td class="vote">
  <img alt="<?php echo $lang_rating4 ?>"  src="../buttons/smilie_4.gif" width="15" height="15" /></td>
<td class="vote">
  <img alt="<?php echo $lang_rating5 ?>"  src="../buttons/smilie_5.gif" width="15" height="15" /></td>
</tr>
<tr align="center"><td class="vote">
<input name="twg_rating" type="radio" value="1" /></td><td class="vote">
<input name="twg_rating" type="radio" value="2" /></td><td class="vote">
<input name="twg_rating" type="radio" value="3" /></td><td class="vote">
<input name="twg_rating" type="radio" value="4" /></td><td class="vote">
<input name="twg_rating" type="radio" value="5" /></td>
</tr>
<tr align="center">
<td colspan=5 class="vote">
<img alt='' src="../buttons/1x1.gif" width="5" height="10" /><br />
  <input type="submit" name="twg_submit" value="<?php echo $lang_rating_button ?>"/></td>
</tr>
</table>

  
  </td></tr></table>
  </center>
</td></tr></table>  
</form>
<?php include "i_bottom.inc.php"; ?>
</body>
</html>