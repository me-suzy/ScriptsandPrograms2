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

// start of top row
$spaces_printed = true;
if ($image == false && $twg_album == false) { // we are in the main view !!
    echo "<td class='topnavleft'>";
    if (!$top10) {
        echo $lang_select_gallery;
    } else {
        echo "<a href='" . $_SERVER['PHP_SELF'] . "?" . $twg_standalone . "'>" . $lang_main_topx . "</a>";
    } 
    echo "</td>";
    echo "<td colspan=2 class='topnavright' nowrap>";

    $languagelist = get_language_list();
    if (!$session_available) {
        $languagelist = Array ($default_language);
    } 


    if (count($languagelist) > 1) {
        if ($show_languages_as_dropdown) {
            echo "<img class='twg_lock' title='" . get_language_string($default_language) . "'  alt='" . get_language_string($default_language) . "' src='" . $install_dir . "language/language_" . $default_language . ".gif' onclick='javascript:show_lang_div(10);'><img id='langpixel' alt='' class='twg_lock' width=1 src='" . $install_dir . "buttons/1x1.gif'><img alt='' class='twg_lock' src='" . $install_dir . "buttons/select.gif' height=10 onclick='javascript:show_lang_div(10); return true;'>";
            echo '<div id="twg_langdiv" class="twg_langdiv" style="left:' . $lang_xpos_lang_dropdown . 'px;"><table class="twg" summary="" align="center" width="22" border="0" cellspacing="1" cellpadding="1">';
        } 
        for($current = 0, $i = 0; $i < count($languagelist); $i++) {
            $lang = substr($languagelist[$i], 9, 2);
            if ($show_languages_as_dropdown) {
                echo "<tr onMouseOver=\"this.className='twg_hoverflag'\" onMouseOut=\"this.className='twg_unhoverflag'\"><td width='18' align='center'><a href='" . $_SERVER['PHP_SELF'] . "?twg_lang=" . $lang . $twg_standalone . "'  ><img title='" . get_language_string($lang) . "'  alt='" . get_language_string($lang) . "' src='" . $install_dir . "language/" . $languagelist[$i] . "' /></a></td></tr>";
            } else {
                echo "<a href='" . $_SERVER['PHP_SELF'] . "?twg_lang=" . $lang . $twg_standalone . "'  ><img class='twg_lock' title='" . get_language_string($lang) . "'  alt='" . get_language_string($lang) . "' src='" . $install_dir . "language/" . $languagelist[$i] . "' /></a>&nbsp;";
            } 
        } 
        if ($show_languages_as_dropdown) {
            echo '</table></div>&nbsp;';
        } 
    } 
    

    if (count($languagelist) > 1) {
        $langlist = true;
    } else {
        $langlist = false;
    } 
    if (($langlist) && ($show_count_views || $show_search || $show_topx || ($show_email_notification && $show_login) || $show_new_window || $show_login)) {
        echo "|&nbsp;";
        $spaces_printed = true;
    } 

    if ($show_topx) { // only twg_show the top x when the view count is enabled
        echo "<a href='" . $_SERVER['PHP_SELF'] . "?twg_top10=" . $topx_default . $twg_standalone . "'>" . sprintf($lang_topx, $number_top10) . "</a>";
        $spaces_printed = false;
    } 
    if ((!$spaces_printed) && ($show_new_window || $show_search || ($show_email_notification && $show_login) || $show_login)) {
        echo "&nbsp;|&nbsp;";
        $spaces_printed = true;
    } 

    if ($show_search) {
        echo "<a onclick='twg_showSec(" . $lang_height_search . ")' id='i_search' target='details' href='" . $install_dir . "i_frames/i_search.php?" . $twg_standalone . "'>" . $lang_search . "</a>";
        $spaces_printed = false;
    } 
    if ((!$spaces_printed) && ($show_new_window || ($show_email_notification && $show_login) || $show_login)) {
        echo "&nbsp;|&nbsp;";
        $spaces_printed = true;
    } 

    $adminfile = dirname(__FILE__) . "/../admin/admin.php";
    if ($show_email_notification && $show_login) {
        if ($login == 'TRUE') {
            if (file_exists($adminfile)) {
                echo "<a onclick='adjust_and_resize_admin_iframe(" . $lang_height_email_admin . ")' id='i_email' target='details' href='" . $install_dir . "admin/admin.php'>" . $lang_administration . "</a>";
            } else {
                echo "<a onclick='twg_showSec(" . $lang_height_email_admin . ")' id='i_email' target='details' href='" . $install_dir . "i_frames/i_email_admin.php'>" . $lang_email_menu_admin . "</a>";
            } 
        } else {
            echo "<a onclick='twg_showSec(" . $lang_height_email_user . ")' id='i_email' target='details' href='" . $install_dir . "i_frames/i_email_user.php?twg_lang=" . $default_language . "'>$lang_email_menu_user</a>";
        } 
        $spaces_printed = false;
    } else if (file_exists($adminfile) && $show_login) { // no emails is shown but still the administration when a user is loggen id!
        if ($login == 'TRUE') {
            echo "<a onclick='adjust_and_resize_admin_iframe(" . $lang_height_email_admin . ")' id='i_email' target='details' href='" . $install_dir . "admin/admin.php'>" . $lang_administration . "</a>";
            $spaces_printed = false;
        } 
    } 
    if ((!$spaces_printed) && ($show_new_window || $show_login)) {
        echo "&nbsp;|&nbsp;";
        $spaces_printed = true;
    } 
    if ($show_new_window) {
        echo "<a href='javascript:openNewWindow();'>" . $lang_new_window . "</a>";
        $spaces_printed = false;
    } 
    if ((!$spaces_printed) && ($show_login)) {
        echo "&nbsp;|&nbsp;";
        $spaces_printed = true;
    } 
} 

if ($image == false && $twg_album != false) { // we are in the thumbnail view !!
    $imagelist = get_image_list($twg_album);
    echo "<td class='topnavleft'>";
    echo "<a href='" . getRootLink($basedir . "/" . $twg_album) . "'  ><span class='twg_bold'>" . $lang_galleries . "</span></a> > ";
    $path = explode ("/" , $twg_album);
    $nr_count = count($path);
    $nr_act_count = 1;
    $actpath = "";
    reset ($path);
    while (list ($key, $val) = each ($path)) {
        if ($nr_count == $nr_act_count) {
            $val = getDirectoryName($basedir . "/" . $actpath . $val, $val);
            echo $val . " > ";
        } else {
            $actpath = $actpath . $val;
            echo "<span class='twg_bold'><a href='" . $_SERVER['PHP_SELF'] . "?twg_album=" . urlencode($actpath) . "&amp;twg_offset=0" . $twg_standalone . "'>";
            $val = getDirectoryName($basedir . "/" . $actpath . $val, $val);
            echo $val . '</a></span> > ';
            $actpath = $actpath . "/";
        } 
        $nr_act_count++;
    } 

    if ($imagelist) {
        $nr_images = count($imagelist);
        echo $nr_images . "&nbsp;" . (($nr_images == 1) ? $lang_picture : $lang_pictures);
    } else {
        // not sure if I want to display text here ;)
        // echo "Galerie&nbsp;ausw&auml;hlen";
    } 
    echo "</td>";
    echo "<td class='topnav' nowrap>&nbsp;";

    if (!$top10) {   
        $upper_level_alb = getupperdirectory($twg_album);
        if ($upper_level_alb) {
            $upper_level = getRootLink($basedir . "/" . $twg_album) . "&amp;twg_album=" . urlencode($upper_level_alb);
            $upper_leveljs = getRootLink($basedir . "/" . $twg_album) . "&twg_album=" . urlencode($upper_level_alb);
        
        } else {
            $upper_level = getRootLink($basedir . "/" . $twg_album);
            $upper_leveljs = getRootLink($basedir . "/" . $twg_album);
        } 
    } else {
        $upper_level = getRootLink($basedir . "/" . $twg_album) . "&amp;twg_album=" . $album_enc;
        $upper_leveljs = getRootLink($basedir . "/" . $twg_album) . "&twg_album=" .   $album_enc;      
    } 
    printf("<a href='%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('topthumb','','%sbuttons/menu_up_over.gif',1)\"><img src='%sbuttons/menu_up.gif' alt='%s' title='%s' id='topthumb' height='24' /></a>", $upper_level , $install_dir, $install_dir, $lang_overview, $lang_overview);
    printf("<script type='text/javascript'> function key_up() { location.href='%s'; } </script>", $upper_leveljs) ;

    echo "&nbsp;</td>";
    printf("<td class='topnavright' align='right'>");
    if ($show_topx) { // only twg_show the top x when the view count is enabled
        echo "<a href='" . $_SERVER['PHP_SELF'] . "?twg_album=" . $album_enc . "&amp;twg_top10=" . $topx_default . $twg_standalone . "'>" . sprintf($lang_topx, $number_top10) . "</a>";
    } 
    
     if ($show_search) {
             if ($show_topx) {
						            echo "&nbsp;|&nbsp;";
        } 
		        echo "<a onclick='twg_showSec(" . $lang_height_search . ")' id='i_search' target='details' href='" . $install_dir . "i_frames/i_search.php?twg_album=" . $album_enc . $twg_standalone . "'>" . $lang_search . "</a>";
		        
    } 
    
    if ($show_new_window) {
        if ($show_topx || $show_search) {
            echo "&nbsp;|&nbsp;";
        } 
        echo "<a href='javascript:openNewWindow();'>" . $lang_new_window . "</a>";
    } 
    if ($show_login && ($show_topx || $show_new_window || $show_search)) {
        echo "&nbsp;|&nbsp;";
    } 
} 

if ($image != false && $twg_album != false) { // we are in the image view
    // $image_enc = urlencode($image);
    $current_id = get_image_number($twg_album, $image_enc);
    $imagelist = get_image_list($twg_album);
    $image = $imagelist[$current_id];
    $image_enc = $image;
    echo "<td class='topnavleft'>";
    echo "<a href='" . getRootLink($basedir . "/" . $twg_album) . "'  ><span class='twg_bold'>" . $lang_galleries . "</span></a> > ";
    $path = explode ("/" , $twg_album);
    $nr_count = count($path);
    $actpath = "";
    reset ($path);
    while (list ($key, $val) = each ($path)) {
        $actpath = $actpath . $val;
        echo "<span class='twg_bold'><a href='" . $_SERVER['PHP_SELF'] . "?twg_album=" . urlencode($actpath) . "&amp;twg_offset=0" . $twg_standalone . "'>";
        $val = getDirectoryName($basedir . "/" . $actpath, $val);
        echo $val . '</a></span> > ';
        $actpath = $actpath . "/";
    } 

    if ($image) {
        echo $lang_picture . "&nbsp;<span id='imagecounter'>" . ($current_id + 1) . "</span>&nbsp;" . $lang_of . "&nbsp;" . count($imagelist);
        if ($show_count_views) {
            echo '&nbsp;(<span id="viewcounter">' . increaseImageCount($twg_album, urldecode($image)) . '</span>' . $lang_views . ')';
        } 
    } 
    echo "</td><td class='topnav' nowrap>";
    if (!$top10) {
        // this is to center all images on the top
        if ($show_slideshow) {
            printf("<img src='%sbuttons/1x1.gif'width='31' height='24' alt='' />&nbsp;&nbsp;", $install_dir);
        } 
        if ($show_first_last_buttons) {
            // 1st button
            $hreffirst = sprintf("%s?twg_album=%s&amp;twg_show=x%s", $_SERVER['PHP_SELF'], $album_enc, $twg_standalone);
            printf("<a href='%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('topfirst','','%sbuttons/menu_first_over.gif',1)\"><img src='%sbuttons/menu_first.gif' alt='%s' title='%s' id='topfirst' height='24' /></a>", $hreffirst, $install_dir, $install_dir, $lang_first, $lang_first);
        } 
        if ($default_big_navigation == "HTML" || $twg_smallnav == "TRUE") {
            if ($last = get_last($twg_album, $image, $current_id)) {
                $hreflast = sprintf("%s?twg_album=%s&amp;twg_show=%s%s", $_SERVER['PHP_SELF'], $album_enc, $last, $twg_standalone);
                $hreflastjs = sprintf("%s?twg_album=%s&twg_show=%s%s", $_SERVER['PHP_SELF'], $album_enc, $last, $twg_standalonejs);
                printf("<a href='%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('topback','','%sbuttons/menu_left_over.gif',1)\"><img src='%sbuttons/menu_left.gif' alt='%s' title='%s' id='topback' height='24' /></a>", $hreflast, $install_dir, $install_dir, $lang_back, $lang_back);
                echo '<script type="text/javascript">';
                echo 'function key_back()     { location.href="' . $hreflastjs . '" } ';
                echo '</script>';
            } else {
                printf("<img src='%sbuttons/1x1.gif' alt='' width='24' height='24' />", $install_dir);
                echo '<script type="text/javascript"> function key_back() { } </script>';
            } 
        } else { // dhtml solution !!
            printf("<span id='backbutton'><a href=\"javascript:key_back();  \" onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('topback','','%sbuttons/menu_left_over.gif',1); window.status=''; return true;\"><img src='%sbuttons/menu_left.gif' alt='%s' title='%s' id='topback' height='24' /></a></span>", $install_dir, $install_dir, $lang_back, $lang_back);
            echo '<script type="text/javascript">';
            echo 'function key_back()    { if ((lastpos >0) && ready) { location.href="javascript:changeContent(lastpos - 1); setPos(thumbstwg_offset[lastpos]);" }} ';
            echo '</script>';
            if (!get_last($twg_album, $image, $current_id)) {
                echo '<script type="text/javascript">';
                echo 'document.getElementById("backbutton").style.visibility = "hidden";';
                echo '</script>';
            } ;
        } 
        // the overview
        $twg_offset = get_twg_offset($twg_album, $image, $current_id);
        if ($skip_thumbnail_page) {
            $jump_album = "";
        } else {
            $jump_album = $album_enc;
        } 
        printf("<a href='%s?twg_album=%s&amp;twg_offset=%s%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('topthumb','','%sbuttons/menu_up_over.gif',1)\"><img src='%sbuttons/menu_up.gif' alt='%s' title='%s' id='topthumb' height='24' /></a>", $_SERVER['PHP_SELF'], $jump_album, $twg_offset, $twg_standalone, $install_dir, $install_dir, $lang_overview, $lang_overview);
        printf("<script type='text/javascript'> function key_up() { location.href='%s?twg_album=%s&twg_offset=%s%s'; } </script>", $_SERVER['PHP_SELF'], $jump_album, $twg_offset, $twg_standalonejs) ;

        if ($default_big_navigation == "HTML" || $twg_smallnav == "TRUE") {
            if ($next = get_next($twg_album, $image, $current_id)) {
                $hrefnext = sprintf("%s?twg_album=%s&amp;twg_show=%s%s", $_SERVER['PHP_SELF'], $album_enc, $next, $twg_standalone);
                $hrefnextjs = sprintf("%s?twg_album=%s&twg_show=%s%s", $_SERVER['PHP_SELF'], $album_enc, $next, $twg_standalonejs);
                printf("<a href='%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('topnext','','%sbuttons/menu_right_over.gif',1)\"><img src='%sbuttons/menu_right.gif' alt='%s' title='%s' id='topnext' height='24' /></a>", $hrefnext, $install_dir, $install_dir, $lang_forward, $lang_forward);
                echo '<script type="text/javascript">';
                echo 'function key_foreward()     { location.href="' . $hrefnextjs . '" } ';
                echo '</script>';
            } else {
                printf("<img src='%sbuttons/1x1.gif' alt='' width='24' height='24' />", $install_dir);
                echo '<script type="text/javascript"> function key_foreward() { } </script>';
            } 
        } else { // dhtml solution!
            printf("<span id='nextbutton'><a href=\"javascript:key_foreward(); \" onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('topnext','','%sbuttons/menu_right_over.gif',1); window.status=''; return true;\"><img src='%sbuttons/menu_right.gif' alt='%s' title='%s' id='topnext' height='24' /></a></span>", $install_dir, $install_dir, $lang_forward, $lang_forward);
            echo '<script type="text/javascript">';
            echo 'function key_foreward()     { if (lastpos <(thumbs.length-1) && ready) { location.href="javascript:changeContent(lastpos + 1); setPos(thumbstwg_offset[lastpos]);" } else if (lastpos <(thumbs.length) && ready) { location.href="javascript:changeContent(lastpos + 1);" } } ';
            echo '</script>';
            if (!get_next($twg_album, $image, $current_id)) {
                echo '<script type="text/javascript">';
                echo 'document.getElementById("nextbutton").style.visibility = "hidden";';
                echo '</script>';
            } ;
        } 
        if ($show_first_last_buttons) {
            // last button
            $end = get_end($twg_album);
            $hrefend = sprintf("%s?twg_album=%s&amp;twg_show=%s%s", $_SERVER['PHP_SELF'], $album_enc, $end, $twg_standalone);
            printf("<a href='%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('topend','','%sbuttons/menu_last_over.gif',1)\"><img src='%sbuttons/menu_last.gif' alt='%s' title='%s' id='topend' height='24' /></a>", $hrefend, $install_dir, $install_dir, $lang_last, $lang_last);
        } 
        if ($show_slideshow) {
            if ($twg_slideshow) {
                // the slidestop=true is only needed to find this link with javascript and be able to excange this
                // dynamically -> if a user stop the slidtwg_show we can jump to the actual twg_shown picture !!
                printf("&nbsp;&nbsp;<a id='stop_slideshow' href='%s?twg_album=%s&amp;twg_show=%s%s'                         onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('stop_slideshow_img','','%sbuttons/menu_stop_over.gif',1)\"><img src='%sbuttons/menu_stop.gif' alt='%s' title='%s' id='stop_slideshow_img' height='24' /></a>", $_SERVER['PHP_SELF'], $album_enc, $image_enc, $twg_standalone, $install_dir, $install_dir, $lang_stop_slideshow, $lang_stop_slideshow);
            } else {
                printf("&nbsp;&nbsp;<a id='start_slideshow' href='%s?twg_album=%s&amp;twg_show=%s&amp;twg_slideshow=true%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('slideshow','','%sbuttons/menu_start_over.gif',1)\"><img src='%sbuttons/menu_start.gif' alt='%s' title='%s' id='slideshow' height='24' /></a>", $_SERVER['PHP_SELF'], $album_enc, $image_enc, $twg_standalone, $install_dir, $install_dir, $lang_start_slideshow, $lang_start_slideshow);
            } 
        } 
    } else {
        if ($top10_type=="search") {
          $lang_back_topx = $lang_search_back;
        }
        echo "<a href='javascript:history.back()'>" . sprintf($lang_back_topx, $number_top10) . "</a>";
    } 
    printf("</td><td class='topnavright'>");

    if ($show_image_rating && $image_rating_position == "menu") {
        echo "<a onclick='twg_showSec(" . $lang_height_rating . ")' target='details' id='i_rate' href='" . $install_dir . "i_frames/i_rate.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image_enc . $twg_standalone . "'>$lang_rating</a>";
        if ($show_enhanced_file_infos || $show_comments || $show_optionen || $show_login) {
            echo "&nbsp;| ";
        } 
    } 
    if ($show_enhanced_file_infos) {
        // todo: remove lang_height_comment
        echo "<a onclick='stickyLayer();twg_showSec(" . $lang_height_info . ")' target='details' id='i_info' href='" . $install_dir . "i_frames/i_info.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image_enc . $twg_standalone . "'>$lang_fileinfo</a>";
        if ($show_comments || $show_optionen || $show_login) {
            echo "&nbsp;| ";
        } 
    } 
    if ($show_comments) {
        if ($show_number_of_comments) {
            $com_counter = " (<span id='commentcount'>" . getKommentarCount($image, $twg_album, $kwerte, $kindex) . "</span>)";
        } else {
            $com_counter = "";
        } 

        if ($show_comments_in_layer) {
            $lang_height_comment += $height_of_comment_layer;
        } 
        echo "<a onclick='twg_showSec(" . $lang_height_comment . ")' target='details' id='i_comment' href='" . $install_dir . "i_frames/i_kommentar.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image_enc . $twg_standalone . "'>" . $lang_comments . $com_counter . "</a>";
        if ($show_optionen || ($show_login)) {
            echo "&nbsp;| ";
        } 
    } 
    if ($show_optionen) {
        echo "<a onclick='twg_showSec(" . $lang_height_options . ")' id='i_options' target='details' href='" . $install_dir . "i_frames/i_optionen.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image_enc . $twg_standalone . "'>" . $lang_optionen . "</a>";
        if ($show_login) {
            echo "&nbsp;| ";
        } 
    } 
    if ($login == 'TRUE') {
        echo "<a onclick='twg_showSec(" . $lang_height_caption . ")' id='i_caption' target='details' href='" . $install_dir . "i_frames/i_titel.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image_enc . $twg_standalone . "'>$lang_menu_titel</a>&nbsp;| ";
    } 
} 

if (!isset($image_enc)) {
    $image_enc = "";
} 
if (!isset($album_enc)) {
    $album_enc = "";
} 
// twg_shows login/logout
if ($show_login) {
    if ($login == 'TRUE') {
        echo "<a target='details' id='logoutlink' href='" . $install_dir . "i_frames/i_login.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image_enc . "&amp;twg_logout=true" . $twg_standalone . "'>$lang_logout</a>";
    } else {
        echo "<a onclick='twg_showSec(" . $lang_height_login . ")' id='loginlink' target='details' href='" . $install_dir . "i_frames/i_login.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image_enc . $twg_standalone . "'>$lang_login</a>";
    } 
} 

echo '<img height=1 width=1 alt="" id="cornerpixel"  src="' . $install_dir . 'buttons/1x1.gif" />';
echo '</td>';

?>