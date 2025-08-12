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

require "../language/language_" . $default_language . ".php";

if ($login <> "TRUE") {
echo $lang_email_admin_notloggedin;
return;
}

$titel = nl2br($titel);
$titel = stripslashes($titel);

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
<img name="imageField" alt='' onClick="closeiframe()" align="right" src="../buttons/close.gif" width="12" height="12" border="0" />
</td></tr><tr><td> 
 <input name="twg_album" type="hidden" value="<?php echo encodespace($twg_album);

?>"/>
 <input name="twg_show" type="hidden" value="<?php echo encodespace($image);

?>"/>
<?php echo $hiddenvals; ?>
<?php

if ($titel == false) {
    echo $lang_titel_php_titel;
} else {
    $_SESSION["actalbum"] = "LOAD NEW";
    loadXMLFiles(urldecode($twg_album));
    saveBeschreibung($titel, $twg_album, $image, $werte, $index);
    if (isset($_GET["PHPSESSID"])) {
       $closescript = "<script>closeiframe(); if (reload) { parent.location='" . urldecode($twg_root) ."?PHPSESSID=" . $_GET["PHPSESSID"] . "&twg_album=" . $album_enc  . "&twg_show=" . $image_enc . $twg_standalonejs . "'  }</script>";
    } else {
       $closescript = "<script>closeiframe(); if (reload) { parent.location='" . urldecode($twg_root) ."?twg_album=" . $album_enc  . "&twg_show=" . $image_enc . $twg_standalonejs . "'  }</script>";
    }
    // $closescript = "<script>closeiframe(); if (reload) { parent.location.reload();  }</script>";
    echo $closescript;
} 

echo'
  <center><img alt="" src="../buttons/1x1.gif" width="1" height="2" /><table summary=""><tr><td>';
if ($enable_smily_support) { 
echo'
  <img alt="" onmouseover="javascript:show_smilie_div()" src="../buttons/smilie.gif" width="15" height="15" /></td><td><img alt="" src="../buttons/1x1.gif" width="1" height="15" /></td><td>';
}
?>  
  <input id="twg_titel" name="twg_titel" type="text" size="25"/>
  <img alt='' src="../buttons/1x1.gif" width="5" height="5" />
  <input type="submit" name="twg_submit" value="<?php echo $lang_titel_php_save ?>"/> 
  </td></tr></table>
  </center>
</td></tr></table>  
</form>
<?php
if ($enable_smily_support) {
echo '
<div id="twg_smilie" class="twg_smiliediv"><table summary="" cellpadding="0" cellspacing="0"><tr><td class="twg_smilie">'. create_smilie_div() . '</td></tr></table></div>

<div id="twg_smilie_bord" class="twg_smiliedivborder" onmouseover="javascript:hide_smilie_div()" ></div>

';
}
?>
<?php include "i_bottom.inc.php"; ?>
</body>
</html>