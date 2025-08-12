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
if (isset($twg_root)) { // switching between different instances needs this!
  $twg_save = $twg_root;
}
@session_start();
if (isset($twg_save)) {
  $twg_root = $twg_save;
}
if (@isset($_SERVER['SERVER_NAME'])) {
    $current = @$_SERVER['SERVER_NAME'] . $TWG_SESSION_PREFIX; // used to avoid session infererence between two twg installations
} else {
    $current = $TWG_SESSION_PREFIX;
} 

if (isset($_SESSION["twg_latestlocation"])) {
    if ($_SESSION["twg_latestlocation"] != $current) {
        @session_destroy();
        @session_start();
    } 
} 
$_SESSION["twg_latestlocation"] = $current;

if (isset($_GET["twg_smallnav"])) {
    $_SESSION["nav_small"] = 'TRUE';
} 

if (isset($_GET["twg_bignav"])) {
    $_SESSION["nav_small"] = 'FALSE';
} 
// we set a dummy - twg_smallnav = false!
if (!isset($_SESSION["nav_small"])) {
    $_SESSION["nav_small"] = $show_only_small_navigation;
} 

$twg_smallnav = $_SESSION["nav_small"]; 
// border part
if (isset($_GET["twg_noborder"])) {
    $_SESSION["myborder"] = 'FALSE';
} 

if (isset($_GET["twg_withborder"])) {
    $_SESSION["myborder"] = 'TRUE';
} 
// we set a dummy - myborder = false!
if (!isset($_SESSION["myborder"])) {
    $_SESSION["myborder"] = $show_border;
} 

if (!isset($_SESSION["actalbum"])) {
    $_SESSION["actalbum"] = 'LOAD NEW';
} 
$myborder = $_SESSION["myborder"]; 
// login part
$login = 'FALSE';
if (isset($_SESSION["mywebgallerie_login"])) {
    $login = 'TRUE';
} else {
} 

$twg_standalone = "";
$twg_standalonejs = "";
// setup standalone gal!
if (isset($_GET['twg_standalone'])) {
    $twg_standalone = "&amp;twg_standalone=true";
    $twg_standalonejs = "&twg_standalone=true";
} 

if ($twg_standalone != "") {
    $install_dir = "";
    $php_include = false;
} 
// we add non-TWG variables to the links again !!
$hiddenvals = "";

$twg_array = array('twg_rating_page2', 'twg_rating', 'twg_album', 'twg_show', 'twg_private_login', 'twg_rot', 'twg_zoom', 'twg_smallnav', 'twg_bignav', 'twg_noborder', 'twg_withborder', 'twg_slideshow_time', 'twg_slideshow', 'twg_slide_type', 'twg_lang', 'twg_nav_dhtml', 'twg_nav_html', 'PHPSESSID', 'twg_offset', 'twg_foffset', 'twg_top10', 'twg_root', 'twg_standalone', 'twg_titel', 'twg_name', 'twg_submit', 'twg_passwort' , 'twg_logout', 'twg_xmlhttp', 'twg_nojs', 'twg_highbandwidth', 'twg_lowbandwidth', 'twg_dir', 'twg_delcomment', 'twg_random_size', 'twg_random', 'twg_comment', 'twg_type', 'twg_search_max', 'twg_search_caption', 'twg_search_comment', 'twg_search_term' , 'twg_search_filename', 'twg_submit');

$twg_array = array_merge($twg_array, $ignore_parameter);
while (list ($key, $val) = each ($_GET)) {
    if (!in_array ($key, $twg_array)) {
        $twg_standalone .= "&amp;" . $key . "=" . $val;
        $twg_standalonejs .= "&" . $key . "=" . $val;
        $hiddenvals .= '<input name="' . $key . '" type="hidden" value="' . $val . '"/>';
    } 
} 
// getting the twg_slideshow time
if (isset($_GET["twg_slideshow_time"])) {
    $_SESSION["twg_slideshow_time"] = $_GET["twg_slideshow_time"];
} 
if (isset($_SESSION["twg_slideshow_time"])) {
    $twg_slideshow_time = $_SESSION["twg_slideshow_time"];
} 
// getting the twg_zoom
if (isset($_GET["twg_zoom"])) {
    $_SESSION["twg_zoom"] = $_GET["twg_zoom"];
} 
if (isset($_SESSION["twg_zoom"])) {
    if ($_SESSION["twg_zoom"] == "TRUE") {
        $twg_smallnav = false; 
        // $default_big_navigation = "HTML";
        $default_is_fullscreen = true;
    } else {
        $default_is_fullscreen = false;
    } 
} 
// getting the twg_slideshowtype
if (isset($_GET["twg_slide_type"])) {
    $_SESSION["twg_slide_type"] = $_GET["twg_slide_type"];
} 
if (isset($_SESSION["twg_slide_type"])) {
    $twg_slide_type = $_SESSION["twg_slide_type"];
} 

if ($enable_external_privategal_login) {
    if (isset($_GET["twg_private_login"])) {
        $_SESSION["privategallogin"] = $_GET["twg_private_login"];
    } 
} 
// check if the user can view private galleries
$privatelogin = 'FALSE';
if (isset($_SESSION["privategallogin"])) {
    $privatelogin = $_SESSION["privategallogin"];
} 
// check if the language is present - if not we keep the default - if yes we set the new one and store this in
// the session
if (!isset($_SESSION["twg_lang"])) {
    $_SESSION["twg_lang"] = $default_language;
} 

if (isset($_GET["twg_lang"])) {
    $_SESSION["twg_lang"] = $_GET["twg_lang"];
} 

if (isset($_SESSION['twg_lang'])) {
    $default_language = $_SESSION["twg_lang"];
} 


if (isset($_SESSION['twg_root'])) {
    $twg_root = trim($_SESSION['twg_root']);
} else if (isset($twg_root)) {
    $_SESSION['twg_root'] = trim($twg_root);
} else {
    // this is only backup - 
    $twg_root = $install_dir . "../index.php";
} 

if (!isset($_SESSION["dhtml_nav"])) {
    $_SESSION["dhtml_nav"] = $default_big_navigation;
} 
if (isset($_GET["twg_nav_dhtml"])) {
    $_SESSION["dhtml_nav"] = 'DHTML';
} else if (isset($_GET["twg_nav_html"])) {
    $_SESSION["dhtml_nav"] = 'HTML';
} 
$default_big_navigation = $_SESSION["dhtml_nav"];

if (isset($_SESSION["browserx"])) {
    $browserx = $_SESSION["browserx"];
} else {
    $browserx = 930;
} 

if (isset($_SESSION["fontscale"])) {
    $fontscale = $_SESSION["fontscale"];
} else {
    $fontscale = 1;
} 

if (isset($_SESSION["browsery"])) {
    $browsery = $_SESSION["browsery"];
} else {
    $browsery = 500;
} 
// getting the twg_zoom
if (isset($_GET["twg_zoom"])) {
    $_SESSION["twg_zoom"] = $_GET["twg_zoom"];
} 
if (isset($_SESSION["twg_zoom"])) {
    if ($_SESSION["twg_zoom"] == "TRUE") {
        $twg_smallnav = false;
        $default_big_navigation = "HTML";
        $default_is_fullscreen = true;
    } else {
        $default_is_fullscreen = false;
    } 
} 

if (isset($_GET["twg_lowbandwidth"])) {
    $_SESSION["twg_lowbandwidth"] = 'TRUE';
} 

if (isset($_GET["twg_highbandwidth"])) {
    $_SESSION["twg_lowbandwidth"] = 'FALSE';
} 

$test_connection = false;
// we set a dummy - lowbandwidth = false!
if (!isset($_SESSION["twg_lowbandwidth"])) {
    $test_connection = true;
    $_SESSION["twg_lowbandwidth"] = 'FALSE';
} 
$lowbandwidth = $_SESSION["twg_lowbandwidth"];

if ($lowbandwidth == "TRUE") {
    $show_colage = $low_show_colage;
    $show_count_views = $low_count_views;
    $cmotion_gallery_limit_ie = $low_cmotion_gallery_limit_ie;
    $cmotion_gallery_limit_firefox = $low_cmotion_gallery_limit_firefox;
    $compression = $low_compression;
    $thumbnails_x = $low_thumbnails_x;
    $thumbnails_y = $low_thumbnails_y;
    $show_background_images = $low_show_background_images;
    $enable_maximized_view = $low_enable_maximized_view;
    $default_is_fullscreen = $low_default_is_fullscreen;
    $number_top10 = $low_number_top10;
    $low_show_big_left_right_buttons = $low_show_big_left_right_buttons;
    $autodetect_maximum_thumbnails = false;
    if (isset($_GET["twg_lowbandwidth"])) { // seting for th 1.st time - can be later changed by the user !
        $twg_smallnav = $low_show_big_navigation;
        $twg_slide_type = $low_twg_slide_type;
        $default_big_navigation = $low_default_big_navigation;
        $_SESSION["nav_small"] = $twg_smallnav;
        $_SESSION["twg_slide_type"] = $low_twg_slide_type;
        $_SESSION["dhtml_nav"] = $default_big_navigation;
    } 
} 
// we check if the session is available! if not we disable login, options, private login, language selection and include is not possible
if (session_id()) {
    $session_available = true;
} else {
    $session_available = false;
    $show_login = false;
    $show_optionen = false;
    $privatelogin = "";
} 
// restrictions for Opera 7.x - xmlhttp is not available there - this check is not done in the first call of the gallery
if ((!isset($_SESSION["twg_XMLHTTP"])) && isset($_SESSION["twg_root"])) {
    $default_big_navigation = "HTML";
    $twg_slide_type = "FALSE";
    $xml_http = false;
    $enable_maximized_view = false;
    $default_is_fullscreen = false;
    $show_optimized_slideshow = false;
} else {
    $xml_http = true;
} 

if (stristr($_SERVER["HTTP_USER_AGENT"], "safari")) {
    if ($twg_slide_type = "TRUE") {
        $twg_slide_type = "FALSE";
    } 
    $show_optimized_slideshow = false;
    $default_big_navigation = "HTML";
} 

if (stristr($_SERVER["HTTP_USER_AGENT"], "Opera")) {
    if ($twg_slide_type = "TRUE") {
        // $twg_slide_type = "FALSE";
    } 
    // $show_optimized_slideshow = false;
} 

if (isset($_SESSION["twg_download"])) { // we know what to do !
    $twg_download = $_SESSION["twg_download"];
} else {
    $twg_download = false;
} 

if (isset($_SESSION["twg_nojs"])) { // no jacascript - we turn off lots of stuff
    $default_big_navigation = "HTML";
    $twg_slide_type = "FALSE";
    $xml_http = false;
    $enable_maximized_view = false;
    $default_is_fullscreen = false;
    $show_comments = false;
    $show_login = false;
    $show_optionen = false;
    $show_image_rating = false;
    $show_search=false;
    $show_new_window = false;
    $enable_counter_details = false;
    $show_enhanced_file_infos = false;
    $show_email_notification = false;
    $center_cmotiongal_over_image = false;
    $show_languages_as_dropdown = false;
    if ($topx_default == "comments") {
        $topx_default = "views";
    } 
} 

// new variablessince 1.3 - to avoid updates!
if (!isset($show_exif_info)) {
  $show_exif_info = true;
}

?>