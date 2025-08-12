<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3b
  $Date: 2005/11/25 00:38 $
**********************************************/

require (dirname(__FILE__) . "/config.php");

if (file_exists(dirname(__FILE__) . "/skins/" . $skin . ".php")) {
  include dirname(__FILE__) . "/skins/" . $skin . ".php";
} 

$detailswidth = 300;

$twg_root = $_SERVER['PHP_SELF']; // needed in some i_frames !! we store this later in the session for the other frames !
$numberofpics = floor(($numberofpics - 1) / 2);
$CurrentVer = $twg_version;
$webpath = "http://tinywebgallery.ti.funpic.de";
// $webpath = "http://localhost/TinyWebGallery/website";
/* does the prefered language detection of the browser */
if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
    $lang_browser = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
    if (file_exists(dirname(__FILE__) . "/language/language_" . $lang_browser . ".php")) {
        $default_language = $lang_browser;
    } 
} 

if (!file_exists(dirname(__FILE__) . "/language/language_" . $default_language . ".php")) {
    $default_language = "en"; // en is default language if the default in the config.php does not exist!
} 

/* set some session variables */
include (dirname(__FILE__) . "/inc/mysession.inc.php");

if ($test_connection && $test_client_connection) {
    include (dirname(__FILE__) . "/inc/speed.inc.php");
    return;
} 
// make some settings that should be done in the config.php but the user have not configured properly
if ($php_include) {
    $enable_maximized_view = false;
} 
if (!$enable_maximized_view) {
    $default_is_fullscreen = false;
} 

$basedir = $install_dir . $basedir;
$cachedir = $install_dir . $cachedir;
$counterdir = $install_dir . $counterdir;
$xmldir = $install_dir . $xmldir;

require (dirname(__FILE__) . "/language/language_" . $default_language . ".php");
include (dirname(__FILE__) . "/inc/fixfont.inc.php");
// we set the default title to $default_gallery_title if no one is set
if ($lang_titel == "") {
    $lang_titel = $default_gallery_title;
} 
// functions like getLast, getFirst, debug, gdversion  ... has to be before parserequests !
include dirname(__FILE__) . "/inc/filefunctions.inc.php";
// read the request parameters
include dirname(__FILE__) . "/inc/parserequest.inc.php";
include dirname(__FILE__) . "/inc/readxml.inc.php";
$relativepath = "";
include dirname(__FILE__) . "/inc/checkprivate.inc.php";
cleanup_cache();
$twg_rot_available = checktwg_rot();
// check private login
$twg_showprivatelogin = false;

if (($privategal == true) && (!in_array(trim($privatelogin), $passwd))) { // we want to have a login :)
    if (($twg_album != false)) {
        $twg_showprivatelogin = true;
    } 
} 
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
// important check if we already have toshow fullscreen
$default_is_fullscreen = ($image != false && $twg_album != false && $default_is_fullscreen); 
// set in counter.inc.php !
$generatecounter = false;
// $url = "http://localhost/TinyWebGallery/pictures/michi/";
// echo http_get($url);
if (!$php_include) {
    echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
';
} 

?>
<!--
Powered by TinyWebGallery 1.3b
Please go to http://www.tinywebgallery.de.vu for the latest version.

Please don't remove this header if you use TWG or a modified version of it!

Copyright (c) 2004-2005 TinyWebGallery written by Michael Dempfle

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
-->
<?php
if (!$php_include) {
    echo '
<head>
';
} 

?>
<script type="text/javascript">

// check if we are using Netscape < 4.x
var wrongBrowser = false;
if (parseInt(navigator.appVersion.substring(0,1)) <= 4) {
		if (navigator.appName == "Netscape") 
			wrongBrowser = true;
}
</script>	
<?php
$msie = stristr($_SERVER["HTTP_USER_AGENT"], "MSIE");
if (!$php_include) {
    echo '
<title>' . $browser_title_prefix . '</title>
<meta name="author" content="Michael Dempfle" >
<meta name="DC.Identifier" content="http://www.tinywebgallery.de.vu" >
<meta name="keywords" content="TinyWebGallery, php, images, image, gallery, pictures, web, image gallery, web gallery, galery, Bilder, Bildergalerie, gallerie, imagegallery, webgallery, easy to install, xml, title, titel, comments, kommentare, drehen, rotate, slideshow" >
';
    if ($metatags != "") {
        echo '<meta name="keywords" content="' . $metatags . '" >';
    } 
    echo '
<meta name="robots" content="index" >
<meta name="robots" content="follow" >
';
} 

?>
<script type="text/javascript">
if (wrongBrowser) {
   document.write('<meta http-equiv="refresh" content="0; URL=html/wrongbrowser.html">');
}
</script>
<script type="text/javaScript" src="<?php echo $install_dir ?>./js/twg_key.js"></script>
<script type="text/javaScript" src="<?php echo $install_dir ?>./js/twg_xhconn.js"></script>
<script for="document" event="onkeydown()" language="JScript" type="text/jscript">
<!-- 
if (window.event.keyCode == 37) { key_back();  } 
else if (window.event.keyCode == 39)  {	key_foreward(); } 
else if (window.event.keyCode == 38)  {	key_up(); } 
//-->
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $install_dir ?>style.css" >
<?php
if ($msie) {
  // echo '<script> alert("ie") </script>';
  echo '<link rel="stylesheet" type="text/css" href="' . $install_dir . 'style_ie.css" >';
}
?>
<link rel="shortcut icon" href="<?php echo $install_dir ?>favicon.ico" type="image/ico" />
<link rel="icon" href="<?php echo $install_dir ?>favicon.ico"  />
<?php

if ($myborder == 'TRUE' && !$default_is_fullscreen) {
    echo "<link rel='stylesheet' type='text/css' href='" . $install_dir . "framestyle.css' >";
} 

include dirname(__FILE__) . "/inc/index.inc.php";
// this stylesheet adds the border to the image gallery

if (file_exists($install_dir . "my_style.css")) {
    echo "<link rel='stylesheet' type='text/css' href='" . $install_dir . "my_style.css' >";
} 


if (file_exists($install_dir . "skins/" . $skin . ".css")) {
    echo "<link rel='stylesheet' type='text/css' href='" . $install_dir . "skins/" . $skin . ".css' >";
} 

if (!$default_is_fullscreen) {
    $custstylesheet = $basedir . "/" . $twg_album . "/style.css";
    if (file_exists($custstylesheet)) { // individual css
        $custstylesheet = $basedir . "/" . encodespace($twg_album) . "/style.css";
        echo "<link rel='stylesheet' type='text/css' href='" . $custstylesheet . "' >";
    } else {
        $custstylesheet = $cachedir . "/" . twg_urlencode(str_replace("/", "_", $twg_album)) . "_style.css"; // we link directly to the background - because of special characters like (+ü$§!%&-;) this type of encoding is used here
        if (file_exists($custstylesheet)) { // individual css
            echo "<link rel='stylesheet' type='text/css' href='" . $custstylesheet . "' >";
        } 
    } 
} 

if ($image != false && $twg_album != false && ($default_big_navigation != "HTML") && ($twg_smallnav == 'FALSE') && !$twg_slideshow) { // we are in the image view abd twg_show th dhtml navi
if ($msie) {   
      echo '<link rel="stylesheet" type="text/css" href="' . $install_dir . 'js/gallerystyle_ie.css" />';
    } else {
      echo '<link rel="stylesheet" type="text/css" href="' . $install_dir . 'js/gallerystyle.css" />';
    }
echo '
<script type="text/javascript" src="' . $install_dir . 'js/twg_motiongallery.js">
/***********************************************
* CMotion Image Gallery- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
* This notice must stay intact for legal use
***********************************************/
</script>
';
} 

if (!$php_include) {
    echo '
</head>';
    if ($image != false && $twg_album != false && $default_is_fullscreen) {
        echo '<body onload="draginit();" class="twg_body_fullscreen">';
    } else {
        if ($show_background_images) {
            if ($twg_album) {
                $backgroundimage = $basedir . "/" . $twg_album . "/back.png";
            } else {
                $backgroundimage = $basedir . "/back.png";
            } 
            if (file_exists($backgroundimage)) { // individual background image
                $background = sprintf ('%simage.php?twg_album=%s&amp;twg_type=png&amp;twg_show=back.png', $install_dir, $album_enc);
                echo '<body class="twg" style="background-image: url(' . $background . '); background-attachment:fixed;">';
            } else {
                $backgroundimage = $cachedir . "/" . twg_urlencode(str_replace("/", "_", $twg_album)) . "_back.png"; // we link directly to the background - because of special characters like (+ü$§!%&-;) this type of encoding is used here
                if (file_exists($backgroundimage)) { // individual background image
                    echo '<body class="twg" style="background-image: url(' . $cachedir . "/" . twg_urlencode(twg_urlencode($twg_album)) . "_back.png" . '); background-attachment:fixed;">';
                } else
                    echo '<body class="twg">';
            } 
        } else {
            echo '<body class="twg" style="background-image:none;">';
        } 
    } 
} 

?>

<script type="text/javaScript" src="<?php echo $install_dir ?>./js/twg_image.js"></script>

<?php
if ($use_dynamic_background && $show_background_images) {
echo ' 
<!-- if dyn -->
<!-- compliance patch for microsoft browsers -->
<!--[if lt IE 7]>
<script src="'. $install_dir . './js/ie7-standard-p.js" type="text/javascript"></script>
<![endif]-->
<script type="text/javascript">';

if ($twg_album) {
		$backgroundimage = $basedir . "/" . $twg_album . "/back.png";
} else {
		$backgroundimage = $basedir . "/back.png";
} 
if (!file_exists($backgroundimage)) { // individual background image
	 $backgroundimage = $background_default_image; 
} 

if ($backgroundimage != "") {
	$backsize =  @getimagesize($backgroundimage);
	echo '
	imSRC = "' . $backgroundimage . '";
	imgSRC_x = ' . $backsize[0] . ';
	imgSRC_y = ' . $backsize[1] . ';';
	if ($resize_only_if_too_small) {
		echo 'resize_always=false;';
	} else {
		echo 'resize_always=true;';
	}
		echo 'makeIm();';
	}
	echo '
	</script>
	<div id="bodydiv" class="twg_bodydiv">
	';
}
?>
<script type="text/javascript">
resizetimestamp = (new Date().getTime());
window.onresize=rz;
function rz(e) {
  hideAll(); 
  window.setTimeout("window.location.reload()",100);
}
</script>
<?php
if ($disable_frame_adjustment_ie) {
    $starty = "40";
} else {
    $starty = "-400";
} 
?>
<iframe id="details" name="details" src="<?php echo $install_dir; ?>i_frames/i_login.php" width="<?php echo $detailswidth; ?>" height="1" marginwidth="0" marginheight="0"  scrolling="auto" style="z-index: 50; visibility: hidden;	position: absolute; right: 36px; top: <?php echo $starty;

?>px; border: 1px solid;"></iframe>      
<script type="text/javascript">
hideAll();
// opens the gallery in a new window
function openNewWindow() {
 window.open("<?php

if ($new_window_x == "auto" || $new_window_y == "auto") {
    $widthheight = 'width=" + screen.width + ", height=" + screen.height + "';
} else {
    $widthheight = "width=" . $new_window_x . " ,height=" . $new_window_y;
} 

echo $install_dir . "index.php?twg_album=" . $twg_album . "&amp;twg_show=" . $image . "&twg_withborder=true&twg_bignav=true&twg_standalone=true";

?>","Webgalerie","<?php echo $widthheight;

?>,left=0,top=0,menubar=no , scrollbars=yes, status=no, resizable=yes");
}
</script>

<?php
if ($myborder == 'TRUE' && !$default_is_fullscreen) {
    echo "
<table class='twg' summary='' style='width: 100%; height:100%' cellpadding='0' cellspacing='0'>
";
    if ($enable_external_html_include && !$default_is_fullscreen) {
        $headerhtml = dirname(__FILE__) . "/header.htm";
        if (file_exists($headerhtml)) {
            echo "<tr><td colspan = 3 class='twg_headerhtml'>";
            include ($headerhtml);
            echo "</td></tr>";
        } 
    } 
    echo "
<tr>
<td class='sideframe'></td>
<td valign='top'>";
} 

?>

<table class='twg' summary='' style="width: 100%; height:100%" cellpadding='0' cellspacing='0' border='0'>
<?php
if ($enable_external_html_include && !$default_is_fullscreen) {
    $headerhtml = dirname(__FILE__) . "/header.htm";
    if (file_exists($headerhtml) && $myborder != 'TRUE') {
        echo "<tr><td colspan=3 class='twg_headerhtml'>";
        include ($headerhtml);
        echo "</td></tr>";
    } 
} 

?>
<!-- start of small top navigation -->
<?php
if (!$default_is_fullscreen) {
    echo '<tr>';
    include (dirname(__FILE__) . "/inc/topnavigation.inc.php");
    echo '</tr>';
} 

?>
<!-- end of small top navigation -->
<tr onmouseover="javascript:hide_lang_div();">
<?php
if (!$default_is_fullscreen) {
    echo "<td colspan='3' class='twg_info'>";
} else {
    echo "<td colspan='3'>";
} 

?>
<div id="imagetable" style="width: 100%; height:100%">  
<table class='twg' summary='' style="width: 100%; height:100%" border='0' cellpadding='0' cellspacing='0'>
<tr>
<?php
if (!$default_is_fullscreen) {
    echo "<td class='twg_image'>";
} else {
    echo "<td align='center' onmousemove='javascript:setTimer(10);show_control_div();'>";
} 
// start of image section
if ($twg_showprivatelogin) {
    // has to be translated (LANG)
    echo $lang_not_loggedin;
} else if ($image != false && $twg_album != false) { // imageview
    echo '<center>';
    if ($twg_slideshow == false) {
        if (!$default_is_fullscreen) {
            if ($enable_dir_description_on_image) {
                $foldertext = getDirectoryDescription($basedir . "/" . $twg_album);
                if ($foldertext) {
                    echo '<img height=1 width=1 alt=""  src="' . $install_dir . 'buttons/1x1.gif" /><br />';
                    echo "<span class='twg_folderdescription'>" . $foldertext . "</span><br />";
                } 
            } 
            $imagetext = getImagepageDescription($basedir . "/" . $twg_album);
            if ($imagetext) {
                echo '<img height=1 width=1 alt=""  src="' . $install_dir . 'buttons/1x1.gif" /><br />';
                echo "<span class='twg_folderdescription'>" . $imagetext . "</span><br />";
            } 
        } 
        $beschreibung = getBeschreibung($image, $werte, $index);
        $beschreibungalt = "alt=''";
        if (($beschreibung <> " ") && ($beschreibung <> "")) {
            $altbeschreibung = "title='" . escapeHochkomma($beschreibung) . "'";
            $altbeschreibung .= " alt='" . escapeHochkomma($beschreibung) . "'";
            echo '<script type="text/javascript">document.title = "' . $browser_title_prefix . ' - ' . removeTitleChars($beschreibung) . '";</script>';
        } 
        if (($image_rating_position == 'over_image') && $show_image_rating && !$default_is_fullscreen) {
            include dirname(__FILE__) . "/inc/rating.inc.php";
        } 

        $linkfilename = $basedir . "/" . $twg_album . "/link.txt"; 
        // we enable or disable the download of images or link to a location!
        if (file_exists($linkfilename)) { // link file exists !!!
            $dateilink = fopen($linkfilename, "r");
            $download1 = trim(fgets($dateilink, 500));
            $download2 = "</a>";
            fclose($dateilink);
        } else if ($enable_download) {
            if ($open_download_in_new_window) {
                $target = " target='_blank' ";
            } else {
                $target = "";
            } 

            $zip_found = false;
            if ($enable_download_as_zip) {
                $zipfile = $basedir . "/" . $twg_album . "/" . str_replace("/", "_", $twg_album) . ".zip"; 
                $zipfile2 = $basedir . "/" . $twg_album . "/" . str_replace("/", "_", $twg_album) . ".txt"; 
                // echo $zipfile;
                if (file_exists($zipfile) || file_exists($zipfile2)  ) { // && $twg_download != 'single') { // hard for dhtml !
                    $target = "";
                    $download1 = "<a class='twg_fullscreen' id='adefaultslide' onclick='twg_showSec(" . $lang_height_dl_manager . ")' target='details' href='" . $install_dir . "i_frames/i_downloadmanager.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image_enc . $twg_standalone . "'>";
                    $zip_found = true;
                } 
            } 

            if (!$zip_found) {
                if ($enable_direct_download) {
                    $remote_image = checkurl($basedir . "/" . $twg_album);
                    if ($remote_image) {
                        $image_full = $remote_image . encodespace($image);
                    } else {
                        $image_full = $basedir . "/" . $twg_album . "/" . $image;
                    } 
                    $download1 = sprintf("<a class='twg_fullscreen' id='adefaultslide' %s href='%s'>", $target, $image_full);
                } else {
                    $download1 = sprintf("<a class='twg_fullscreen' id='adefaultslide' %s href='%simage.php?twg_album=%s&amp;twg_show=%s'>", $target, $install_dir, $album_enc, $image);
                } 
            } 
            $download2 = "</a>";
        } else {
            $download1 = "";
            $download2 = "";
        } 

        if ($default_is_fullscreen) {
            $type = "fullscreen";
        } else {
            $type = "small";
        } 

        if ($center_cmotiongal_over_image) {
            $jscenter = " onMouseOver='javascript:centerGalLater()' ";
        } else {
            $jscenter = "";
        } 
        if ($use_small_pic_size_as_height) { // fastway to get the height
            if ($enable_drop_shadow) {
                $tdheight = "style='height:" . ($small_pic_size + 24) . "px;' ";
            } else {
                $tdheight = "style='height:" . ($small_pic_size + 6) . "px;' ";
            } 
        } else {
            $aktimage = replace_valid_url($image);
            $replaced_album = str_replace("/", "_", $twg_album);
            $thumbimage = urlencode($replaced_album . "_" . urldecode($aktimage));
            $small = "./" . $cachedir . "/" . $thumbimage . "." . $extension_small;
            if (file_exists($small)) {
                // get size ....
				        $smallsize = getimagesize($small);
                $pic_size_y = $smallsize[1];
                if ($enable_drop_shadow) {
                    $tdheight = "style='height:" . ($pic_size_y + 24) . "px;' ";
                } else {
                    $tdheight = "style='height:" . ($pic_size_y + 6) . "px;' ";
                } 
            } else {
                $tdheight = "";
            } 
        } 
        
        if (!$default_is_fullscreen) {
            if ($enable_drop_shadow) {
                printf("<table class='twg' summary='' border=0 cellpadding='0' cellspacing='0'><tr><td %s class=twg><div class='twg_img-shadow' align='center'><table class='twg' summary='' border=1 cellpadding='0' cellspacing='4'><tr><td class=twg>%s<img class='twg_imageview' id=defaultslide src='%simage.php?twg_album=%s&amp;twg_type=%s&amp;twg_show=%s&amp;twg_rot=%s' %s %s />%s</td></tr></table></div></td></tr></table>", $tdheight, $download1 , $install_dir, $album_enc, $type , $image_enc, $twg_rot, $beschreibungalt, $jscenter, $download2);
            } else {
                printf("<table class='twg' summary='' border=0 cellpadding='3' cellspacing='0'><tr><td %s class=twg>%s<img class='twg_imageview' id=defaultslide src='%simage.php?twg_album=%s&amp;twg_type=%s&amp;twg_show=%s&amp;twg_rot=%s' %s %s />%s</td></tr></table>", $tdheight, $download1 , $install_dir, $album_enc, $type , $image_enc, $twg_rot, $beschreibungalt, $jscenter, $download2);
            } 
            echo '&nbsp;<span class="twg_Caption" id=CaptionBox>' . replacesmilies(php_to_all_html_chars($beschreibung)) . '</span>&nbsp;';
        } else {
            printf("<table class='twg' summary='' border=0 cellpadding='0' cellspacing='0'><tr><td onmousemove='javascript:setTimer(10);show_control_div();'>%s<img id=defaultslide src='%simage.php?twg_album=%s&amp;twg_type=%s&amp;twg_show=%s&amp;twg_rot=%s' %s %s />%s</td></tr></table>", $download1 , $install_dir , $album_enc, $type , $image_enc, $twg_rot, $beschreibungalt, $jscenter, $download2);
        } 
        
    } else {
        if ($twg_slide_type == 'TRUE') {
            if ($use_small_pic_size_as_height) { // 
                $widht_ie = ceil($small_pic_size * 1.35) + 10;
            } else {
                $widht_ie = $small_pic_size + 10;
            } 
            echo "<iframe id='slideframe' allowtransparency='true' name='slideframe' src='" . $install_dir . "i_frames/i_slideshowie.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image . $twg_standalone . "' width='" . $widht_ie . "' height='" . ($small_pic_size + 60) . "' marginwidth='0' marginheight='0'  frameborder='0' scrolling='no' style='position: relative;'></iframe>";
        } else if ($twg_slide_type == 'FALSE') {
            echo "<iframe id='slideframe' allowtransparency='true' name='slideframe' src='" . $install_dir . "i_frames/i_slideshow.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image . $twg_standalone . "' width='" . (($small_pic_size * 1.5) + 10) . "' height='" . ($small_pic_size + 60) . "' marginwidth='0' marginheight='0'  frameborder='0' scrolling='no' style='position: relative;'></iframe>";
        } else {
            echo "<iframe id='slideframe' allowtransparency='true' name='slideframe' src='" . $install_dir . "i_frames/i_slideshowfull.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image . $twg_standalone . "' width='" . ($browserx - 5) . "' height='" . ($browsery) . "' marginwidth='0' marginheight='0'  frameborder='0' scrolling='no' style='position: relative;'></iframe>";
        } 
    } 
    echo '</center>'; // end image view
} elseif ($top10) {
    print_top_10($album_enc, $top10_type);
} elseif ($twg_album != false) { // thumbnailview - or top 10 wiew
   print_thumbnails($twg_album, $twg_offset, $werte, $index, $twg_foffset);
} else { // main view!
   show_folders($basedir, $twg_foffset);
} 

?>
</td>
</tr>
<?php 
// <!-- end of image part -->
// <!-- navbar bottom -->
if (!$default_is_fullscreen) {
    if ($twg_slideshow == false) {
        echo "<tr><td class='navbar'>";
        if (!$twg_showprivatelogin) {
            if ($image != false && $twg_album != false) { // imageview bottom navigation
                if (!$top10) {
                    print_big_navigation($twg_album, $album_enc, $image, $twg_rot, $current_id, $thumb_pic_size, $kwerte, $kindex, $dir);
                } 

                if (($image_rating_position == "below_navigation") && $show_image_rating) {
                    include dirname(__FILE__) . "/inc/rating.inc.php";
                } 
                // twg_shows comments
                if ($show_comments && !$default_is_fullscreen) {
                    if (!$show_comments_in_layer) {
                        $comment_data = substr(getKommentar(urldecode($image), $twg_album, $kwerte, $kindex, false), 10);
                        echo "<center><table class='twg' summary='' width='" . $small_pic_size . "'>";
                        echo "<tr><td id='kommentartd' class='twg_kommentar' >";
                        echo $comment_data;
                        echo "</td></tr></table></center>";
                    } else {
                        if ($show_enter_comment_at_bottom) {
                            if ($show_number_of_comments) {
                                $com_count = " (<span id='kommentarnumber'>" . getKommentarCount($image, $twg_album, $kwerte, $kindex) . "</span>)";
                            } else {
                                $com_count = "";
                            } 
                            // $lang_height_comment += $height_of_comment_layer;
                            $headerkommentar = "<div class='twg_underlineb'><a id='kommentarenter' onclick='twg_showSec(" . $lang_height_comment . ")' target='details' href='" . $install_dir . "i_frames/i_kommentar.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image . $twg_standalone . "'>" . $lang_show_kommentar . $com_count;
                            $headerkommentar .= '<img alt="" width=5   src="' . $install_dir . 'buttons/1x1.gif" /><img alt="' . $lang_add_kommentar . '" title="' . $lang_add_kommentar . '"  src="' . $install_dir . 'buttons/add.gif" />';
                            $headerkommentar .= "</a></div>";
                            echo $headerkommentar;
                        } 
                    } 
                } 
            } 
        } 
        echo "</td></tr>";
    } 

    /* include the extra html pages */
    include (dirname(__FILE__) . "/inc/html.inc.php");

    /* include the tips */
    include (dirname(__FILE__) . "/inc/tips.inc.php");

    echo '<tr><td style="text-align:left;height:1px;">';
    echo '<img height=1 width=1 alt="" align="top" id="counterpixel"  src="' . $install_dir . 'buttons/1x1.gif" />';
    echo '</td></tr>';

} 
?>
</table>
</div>
</td></tr>
<?php
if (!$default_is_fullscreen) {
    echo '<tr><td colspan="3" class="twg_bottom">
		<table summary="" class="twg_bottom" width="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td class="bottomtablesideleft"';

    if ($enable_counter) {
        if ($enable_counter_details) {
            if ($enable_counter_details_by_mouseover) {
                echo "onmouseover";
            } else {
                echo "onclick";
            } 
            echo '="javascript:show_counter_div();" onmouseout="javascript:hide_counter_div()">';
        } else {
            echo '>';
        } 
        include dirname(__FILE__) . "/inc/counter.inc.php";
    } 
    echo '</td>
		<td class="bottomtable"><a target="_blank" href="' . $webpath . '/index.php?twg_lang=' . $default_language
     . '">powered&nbsp;by&nbsp;TWG&nbsp;' . $CurrentVer . '</a>';
    if ($show_translator) {
        echo "<small>" . $lang_translator . "</small>";
    } 
    echo '</td>
		<td class="bottomtableside"><noscript><span class="twg_nojs">JavaScript</span><img height="0" width="0" src="' . $install_dir . 'image.php?twg_nojs=true" alt="" />&nbsp;|&nbsp;</noscript>
	  <a target="_blank" href="' . $webpath . '/index.php?page=help">';
    if ($lowbandwidth == "TRUE") {
        echo '<img style="height:7px; width:16px;" alt="' . $lang_lowbandwidth . '" title="' . $lang_lowbandwidth . '" src="' . $install_dir . 'buttons/lbw.gif" />';
    } else {
        echo '<img style="height:7px; width:16px;" alt="' . $lang_highbandwidth . '" title="' . $lang_highbandwidth . '" src="' . $install_dir . 'buttons/hbw.gif" />';
    }
    echo '</a>';

    if ($show_help_link) {
        echo '&nbsp;|&nbsp;<a target="_blank" href="' . $webpath . '/index.php?page=help&amp;language=' . $default_language . '">' . $lang_help . '</a>';
    } 
    echo '
		</td>
		</tr>
		</table>
		</td>
		</tr>';

    if ($myborder != 'TRUE') {
        if ($enable_external_html_include) {
            $footerhtml = dirname(__FILE__) . "/footer.htm";
            if (file_exists($footerhtml)) {
                echo "<tr><td colspan=3 class='twg_footerhtml'>";
                include ($footerhtml);
                echo "</td></tr>";
            } 
        } 
    } 
    echo '</table>';

    if ($myborder == 'TRUE') {
        echo "
		</td>
		<td class='sideframe'></td>
		</tr>
		";
        if ($enable_external_html_include) {
            $footerhtml = dirname(__FILE__) . "/footer.htm";
            if (file_exists($footerhtml)) {
                echo "<tr><td colspan=3 class='twg_footerhtml'>";
                include ($footerhtml);
                echo "</td></tr>";
            } 
        } 
        echo "
		</table>";
    } 
} else {
    echo "</table>";
} 

if ($use_dynamic_background  && $show_background_images) {
  echo '</div>'; 
  if ($backgroundimage != "") { // we make this later because then it it loaded afterwards!
    echo '<script type="text/javascript">';
    echo 'makeIm();';
    echo '</script>';
  }
}

echo '<script type="text/javascript">';
if ($php_include) {
    echo 'window.setTimeout("send_Browser_resolution(\'yes\',\'' . $install_dir . '\')",200);';
} else {
    echo 'send_Browser_resolution(\'no\', \'' . $install_dir . '\');';
} 
echo '</script>';

if (!$default_is_fullscreen) {
    if ($generatecounter || (!file_exists($cachedir . '/counter.png'))) {
        $counterimage = $install_dir . 'image.php?twg_type=counterimage';
        echo "<script type='text/javascript'> MM_preloadImages('" . $counterimage . "');</script>";
        $enable_counter_details = false; // when the history image is created it cannot be displayed
    } 
    // if ($install_dir <> "") { // not used because include in same directory is also possible ;)
    echo '
		<script type="text/javascript">';
    if ($disable_frame_adjustment_ie && (!stristr($_SERVER["HTTP_USER_AGENT"], "MSIE") || stristr($_SERVER["HTTP_USER_AGENT"], "Opera"))) {
        echo 'enable_adjust_iframe();';
    } 
    if (!$disable_frame_adjustment_ie) {
        echo 'enable_adjust_iframe();';
    } 
    echo '</script>'; 
    // }
    if ($enable_counter_details) {
        echo '
		<div id="twg_counterdiv"><table class="twg" summary=""><tr><td 
		class="twg_counterdivtext"><img src="' . $cachedir . '/counter.png" alt="" height="70" width="138"></td></tr><tr><td 
		class="twg_counterdivtext">' . $lang_visitor_30 . '</td></tr></table></div>';
    } 
    
} else if (true) {
    echo '<script type="text/javaScript" src="' . $install_dir . './js/twg_mydrag.js"></script>';

    $current_id = get_image_number($twg_album, $image_enc);
    $image_count = get_image_count($twg_album);
    increaseImageCount($twg_album, $image); // we don't show this value - but we increase !
    $bildtext = $lang_picture . ' ' . ($current_id + 1) . ' ' . $lang_of . ' ' . $image_count ;
    $twg_offset = get_twg_offset($twg_album, $image, $current_id);
    if ($skip_thumbnail_page) {
        $jump_album = "";
    } else {
        $jump_album = $album_enc;
    } 

    echo '<div onmouseover="javascript:setTimer(400);" onmousedown=dragstart(this); onmouseout="javascript:setTimer(10);"  class="twg_fullscreencontrol" id="twg_fullscreencontrol">';
    echo '<table class="twg_centertable" summary="" cellpadding="0" width=100% cellspacing="0"><tr><td class=twg><img id="closebutton" class="twg_hand" onclick="javascript:closeFullscreen();" alt="' . $lang_close_fullscreen . '" title="' . $lang_close_fullscreen . '"  onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage(\'closebutton\',\'\',\'' . $install_dir . 'buttons/close_over.gif\',1)"  align=right src="' . $install_dir . 'buttons/close.gif" />';
    echo '<img alt=""  width=5 height=5 src="' . $install_dir . 'buttons/1x1.gif" /><br />';
    echo '<span id="twg_contol_text" class="twg_contol_text">' . $bildtext . '</span></td></tr><tr><td class=twg>';
    if ($show_first_last_buttons) {
        echo '<img class="twg_hand" id="firstbutton" alt="' . $lang_first . '"  title="' . $lang_first . '"  onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage(\'firstbutton\',\'\',\'' . $install_dir . 'buttons/menu_first_over.gif\',1)" src="' . $install_dir . 'buttons/menu_first.gif" onclick="javascript:changeContent(-100000);" />';
    } 
    echo '<img ';
    if ($current_id == 0) {
        echo ' style="visibility:hidden;" ';
    } 
    echo ' class="twg_hand" id="backbutton" alt="' . $lang_back . '" title="' . $lang_back . '" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage(\'backbutton\',\'\',\'' . $install_dir . 'buttons/menu_left_over.gif\',1)" src="' . $install_dir . 'buttons/menu_left.gif" onclick="javascript:changeContent(-1);" />'; 
    // overview up button
    printf("<a href='%s?twg_album=%s&amp;twg_offset=%s%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('topthumb','','%sbuttons/menu_up_over.gif',1)\"><img src='%sbuttons/menu_up.gif' alt='%s' title='%s' id='topthumb' height='24' /></a>", $_SERVER['PHP_SELF'], $jump_album, $twg_offset, $twg_standalone, $install_dir, $install_dir, $lang_overview, $lang_overview);
    printf("<script type='text/javascript'> function key_up() { location.href='%s?twg_album=%s&twg_offset=%s%s'; } </script>", $_SERVER['PHP_SELF'], $jump_album, $twg_offset, $twg_standalonejs) ;

    echo '<img ';
    if ($current_id == ($image_count-1)) {
        echo ' style="visibility:hidden;" ';
    } 
    echo ' class="twg_hand" id="nextbutton" alt="' . $lang_forward . '" title="' . $lang_forward . '" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage(\'nextbutton\',\'\',\'' . $install_dir . 'buttons/menu_right_over.gif\',1)" src="' . $install_dir . 'buttons/menu_right.gif" onclick="javascript:changeContent(1);" />';
    if ($show_first_last_buttons) {
        echo '<img class="twg_hand" id="lastbutton" alt="' . $lang_last . '" title="' . $lang_last . '" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage(\'lastbutton\',\'\',\'' . $install_dir . 'buttons/menu_last_over.gif\',1)" src="' . $install_dir . 'buttons/menu_last.gif" onclick="javascript:changeContent(100000);" />';
    } 
    if ($show_slideshow) {
        echo '<img alt=""  width=5 src="' . $install_dir . 'buttons/1x1.gif" />';
        echo '<span id="slideshowarea"><img class="twg_hand" id="slideshowbutton" alt="' . $lang_start_slideshow . '" title="' . $lang_start_slideshow . '" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage(\'slideshowbutton\',\'\',\'' . $install_dir . 'buttons/menu_start_over.gif\',1)" onclick="javascript:startSlideshow();" src="' . $install_dir . 'buttons/menu_start.gif" /></span>';
    } 
    echo '</td></tr></table></div>'; 
    // echo '<script type="text/javascript">SET_DHTML("twg_fullscreencontrol"); </script>';
    if ($show_caption_at_maximized_view) {
        echo '<div id="twg_fullscreencaption" class="twg_fullscreencaption">' . getBeschreibung($image, $werte, $index) . '</div>';
    } 

    createFullscreenControl($twg_album, $image);
} 
if (!$php_include) {
    echo '</body></html>
		';
} 
