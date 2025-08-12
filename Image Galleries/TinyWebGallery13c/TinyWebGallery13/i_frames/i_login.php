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

$passwort = false;

if (isset($_GET['twg_passwort'])) {
    $passwort = urlencode($_GET['twg_passwort']);
    if ($passwort == $titelpasswort) {
        $_SESSION["mywebgallerie_login"] = "ok"; 
    } 
} 

$logout = false;
if (isset($_GET['twg_logout'])) {
    session_unregister("mywebgallerie_login");
    $logout = true;
} 

require "../language/language_" . $default_language . ".php";
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
<table summary='' style="width: 100%; height:100%" cellpadding='0' cellspacing='0'><tr><td class="closebutton"> 
<img name="imageField" onClick="closeiframe()" alt='' align="right" src="../buttons/close.gif" width="12" height="12" border="0" />
</td></tr><tr><td> 
 <input name="twg_album" type="hidden" value="<?php echo encodespace($twg_album); ?>"/>
 <input name="twg_show" type="hidden" value="<?php echo encodespace($image); ?>"/>
 <?php echo $hiddenvals; ?>
<?php
if (!$logout) {
	$closescript = "<script>closeiframe(); if (reload) { parent.location='" . urldecode($twg_root) ."' + location.search.substring(0,location.search.indexOf('twg_passwort')-1);  }</script>";
} else {
	$closescript = "<script>closeiframe(); if (reload) { parent.location='" . urldecode($twg_root) . "' + location.search;  }</script>";
}
if ($logout) {
    echo $closescript;
} else if ($passwort == false) {
    echo $lang_login_php_enter;
} else if ($passwort != $titelpasswort) {
    echo $lang_login_php_enter_again;
} else {
    echo $closescript;
} 

?><br/><img alt='' src='../buttons/1x1.gif' height='4' /><br/><input name="twg_passwort" type="password" size="20"/>
  &nbsp;
  <input type="submit" name="Submit" value="Login"/>
</td></tr></table>
</form>
<?php include "i_bottom.inc.php"; ?>
</body>
</html>