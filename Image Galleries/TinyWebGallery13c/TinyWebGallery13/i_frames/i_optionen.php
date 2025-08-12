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

require dirname(__FILE__) . "/../language/language_" . $default_language . ".php";

if ($new_window_x == "auto" || $new_window_y == "auto" ) {
    $widthheight = 'width=" + screen.width + ", height=" + screen.height + "';
 } else {
    $widthheight = "width=" . $new_window_x . " ,height=" . $new_window_y;
 }
 
// make some settings that should be done in the config.php but the user have not configured properly
if ($php_include) { $enable_maximized_view = false;  }
if (!$enable_maximized_view) { $default_is_fullscreen = false; }


?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TinyWebGallery</title>
<meta name="author" content="mid" />
<link rel="stylesheet" type="text/css" href="iframe.css" />
<script type="text/javaScript" src="../js/twg_xhconn.js"></script>
<script type="text/javaScript">reload = false;</script>
<script type="text/JavaScript">
<!--
function openNewWindow() {
 window.open("<?php
echo "../index.php?twg_album=" . $album_enc . "&twg_show=" . urlencode($image) . "&twg_withborder=true&twg_standalone=true";

?>","Webgalerie","width=900,height=690,left=0,top=0,menubar=no, status=no, resize=yes");
closeiframe();
}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
  closeiframe();
}

//-->
</script>
<script type="text/javaScript" src="../js/twg_image.js"></script>
</head>
<body>
<table summary='' style="width: 100%; height:100%" cellpadding='0' cellspacing='0'><tr><td class="closebutton"> 
<img name="imageField" alt='' onClick="closeiframe()" align="right" src="../buttons/close.gif" width="12" height="12" border="0" />
</td></tr><tr><td>
<center>
<table width="266" border="0" summary='' cellpadding='0' cellspacing='0'>
<?php 
 if ($enable_maximized_view) {
 echo '
 <tr>
    <td class="left">' .  $lang_opionen_php_zoom . '</td>
    <td class="right"> 
        <select name="menu1" onChange="';
        if ($show_warning_message_at_maximized_view) 
        { 
          echo "if (confirm('" . $lang_opionen_php_zoom_message . "') != 0) { MM_jumpMenu('parent',this,0); } else { closeiframe();} ";
        }else {
          echo "MM_jumpMenu('parent',this,0);";
        }
       echo  '">
<script type="text/javaScript"> 
document.write("<option value=\''. $twg_root . '" + location.search + "&twg_zoom=TRUE\'>");
</script>
'. $lang_opionen_php_yes . '</option>
          <script type="text/javaScript"> 
document.write("<option value=\'' .  $twg_root . '" + location.search + "&twg_zoom=FALSE\' ");
</script>';
 if (!$default_is_fullscreen) {
    echo " selected ";
} 

echo '
>' . $lang_opionen_php_no . '</option>
        </select>
</td>
</tr>';
} 
?>
  <tr>
    <td class="left"><?php echo $lang_opionen_php_top_nav;

?></td>
    <td class="right"> 
        <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
<script type="text/javaScript"> 
document.write("<option value='<?php echo $twg_root; ?>" + location.search + "&twg_smallnav=true'>");
</script>
<?php echo $lang_opionen_php_yes;

?></option>
          <script type="text/javaScript"> 
document.write("<option value='<?php echo $twg_root; ?>" + location.search + "&twg_bignav=true' ");
</script><?php if ($twg_smallnav == 'FALSE') {
    echo " selected ";
} 

?>
><?php echo $lang_opionen_php_no; ?></option>
        </select>
</td>
</tr>
 <tr>
    <td class="left"><?php echo $lang_dhtml_navigation;

?></td>
    <td class="right"> 
        <select name="menu1" onChange="var myConnB = new XHConn(); if (!myConnB) { alert('You are using a browser that does not support this feature!'); } else { MM_jumpMenu('parent',this,0); }">
<script type="text/javaScript"> 
document.write("<option value='<?php echo $twg_root; ?>" + location.search + "&twg_nav_dhtml=true'>");
</script>
<?php echo $lang_opionen_php_yes;

?></option>
          <script type="text/javaScript"> 
document.write("<option value='<?php echo $twg_root; ?>" + location.search + "&twg_nav_html=true' ");
</script><?php if ($default_big_navigation == 'HTML') {
    echo " selected ";
} 

?>
><?php echo $lang_opionen_php_no;

?></option>
        </select>
</td>
  </tr>  
<?php 
if ($show_new_window) {
echo '
  <tr>
    <td class="left">' . $lang_opionen_php_new_window . '</td>
    <td><div align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="buttonok" 
	onclick="javascript:openNewWindow()" name="Submitmit" value="' .$lang_opionen_php_ok .'" 
	onMouseOver="self.status=\'\'; return true" 
	onMouseOut="self.status=\'\'; return true" />
	</div></td>
  </tr>';
}

if ($show_slideshow) {  
echo '
  <tr>
    <td class="left">' . $lang_opionen_php_slideshowintervall .'</td>
    <td width="110"><div align="right">
      <select name="select" onChange="MM_jumpMenu(\'parent\',this,0);" class="checkbox">
	   <script type="text/javaScript"> 
				document.write("<option value=\'' . $twg_root . '" + location.search + "&twg_slideshow_time=3\' ");
</script>';
if ($twg_slideshow_time == '3') {
    echo " selected ";
} 
echo '>3</option>       
          <script type="text/javaScript"> 
				document.write("<option value=\'' . $twg_root .'" + location.search + "&twg_slideshow_time=5\' ");
</script>';
if ($twg_slideshow_time == '5') {
    echo " selected ";
} 
echo '>5</option>
          <script type="text/javaScript"> 
				document.write("<option value=\'' . $twg_root . '" + location.search + "&twg_slideshow_time=10\' ");
</script>';
if ($twg_slideshow_time == '10') {
    echo " selected ";
} 
echo '>10</option>
          <script type="text/javaScript"> 
				document.write("<option value=\'' . $twg_root . '" + location.search + "&twg_slideshow_time=20\' ");
</script>';
if ($twg_slideshow_time == '20') {
    echo " selected ";
} 

echo '>20</option>
          <script type="text/javaScript"> 
				document.write("<option value=\'' . $twg_root . '" + location.search + "&twg_slideshow_time=30\' ");
</script>';
if ($twg_slideshow_time == '30') {
    echo " selected ";
} 
echo '>30</option>
          <script type="text/javaScript"> 
				document.write("<option value=\'' . $twg_root . '" + location.search + "&twg_slideshow_time=60\' ");
</script>';
if ($twg_slideshow_time == '60') {
    echo " selected ";
} 
echo '>60 </option>
      </select>
    </div></td>
  </tr>';

echo '<tr>
    <td class="left" height="30">' . $lang_optionen_slideshow . '</td>
    <td><div align="right">
      <select class="selectbig" name="select2" onChange="MM_jumpMenu(\'parent\',this,0);">
';

if ($show_optimized_slideshow) {
echo '
<script type="text/javaScript"> 
				document.write("<option value=\'' . $twg_root . '" + location.search + "&twg_slide_type=TRUE\'>");
</script>' . $lang_optionen_slideshow_optimized . '</option>';
}
echo '
        <script type="text/javaScript"> 
				document.write("<option value=\''. $twg_root . '" + location.search + "&twg_slide_type=FALSE\' ");
</script>';
if ($twg_slide_type == 'FALSE') {
    echo " selected ";
} 
echo '>' . $lang_optionen_slideshow_normal . '
</option>';

if ($show_maximized_slideshow) {
echo'
   <script type="text/javaScript"> 
				document.write("<option value=\'' . $twg_root . '" + location.search + "&twg_slide_type=FULL\' ");
</script>'; 
if ($twg_slide_type == 'FULL') {
    echo " selected ";
} 

 echo '>' . $lang_optionen_slideshow_fullscreen . '</option>';
}
echo '
        </select>
    </div></td>
  </tr>';
}  

?>
</table>
</center>
</td></tr></table>
<?php include "i_bottom.inc.php"; ?>
</body>
</html>