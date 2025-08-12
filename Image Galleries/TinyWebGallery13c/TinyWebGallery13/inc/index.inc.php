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
// the div setting are dynamic ;). - therefore can't be done in the stylesheet !
echo '<style type="text/css">';
echo '				#div1{height:' . $menu_pic_size_y . ';width:' . $menu_pic_size_x . ';text-align: center; }';
echo '</style>';

function show_folders($localdir, $twg_foffset)
{
    global $menu_pic_size_x;
    global $menu_pic_size_y;
    global $menu_x;
    global $show_number_of_pic;
    global $cachedir;
    global $privatelogin;
    global $privatepasswort;
    global $kwerte;
    global $kindex;
    global $lang_titel;
    global $lang_titel_no;
    global $default_language;
    global $basedir;
    global $skip_thumbnail_page;
    global $show_colage;
    global $sort_albums_ascending;
    global $extension_thumb;
    global $menu_x;
    global $menu_y;
    global $lang_thumb_back;
    global $lang_thumb_forward;
    global $install_dir;
    global $twg_standalone;
    global $twg_standalonejs;
    global $lang_height_private;
    global $double_encode_urls;
    global $use_random_image_for_folder;
    global $session_available;
    global $lang_no_session;
    global $sort_albums;
    global $menu_pic_size_x;
    global $hidemenuborder;

    $numofrows = 1;
    $verzeichnisse = get_directories($localdir);
    $nr = count($verzeichnisse);
    if ($nr > 0) {
        if ($basedir == $localdir) { // we are already in a substructure
            printf("<span class='twg_title'>" . $lang_titel . "</span><br/><br/>");
            $text = getDirectoryDescription($basedir);
            if ($text) {
                echo '<img height=5 width=1 alt=""  src="' . $install_dir . 'buttons/1x1.gif" /><br />';
                echo "<span class='twg_folderdescription'>" . $text . "</span><br />";
            } 
        } 
    } else {
        if ($basedir == $localdir) { // we are already in a substructure  - no message here
            printf("<span class='twg_bold'>" . $lang_titel_no . "</span><br/><br/>");
        } 
        return 0;
    } 

    $x = 0; // counts folders in a row
    $xx = 0; // counts actual folders
    print "<table summary='' class='thumbnails'>";
    print "<tr>";
    $menupage = $menu_x * $menu_y;
    while (list ($key, $val) = each ($verzeichnisse)) {
        $xx++;
        if ($xx > $twg_foffset && $xx <= ($twg_foffset + $menupage)) {
            if ($x++ == $menu_x) {
                print "</tr>";
                print "<tr>";
                $numofrows++;
                $x = 1;
            } 
            $twg_album = $val;

            if ($basedir != $localdir) {
                $twg_album = substr($localdir, strlen($basedir) + 1) . "/" . $twg_album;
            } 
            $album_enc = urlencode($twg_album); 
            // we check each twg_album !!
            $relativepath = "";
            include dirname(__FILE__) . "/checkprivate.inc.php";

            $imagelist = get_image_list($twg_album); 
            // we look for a folder.png
            $folderfile = $basedir . "/" . $twg_album . "/folder.png";
            if (file_exists($folderfile)) {
                $folderfileexists = true;
                $borderwidth = "";
                $overflowstyle = "";
            } else {
                $folderfileexists = false;
                $borderwidth = " style='width:" . $menu_pic_size_x . "px;' ";
                $overflowstyle = " style='overflow:hidden;' ";
            } 

            if (!($privategal == false) || in_array($privatelogin, $passwd)) {
                $privateimage = $basedir . "/" . $twg_album . "/private.png";
                if (file_exists($privateimage)) {
                    $borderwidth = "";
                    $overflowstyle = "";
                } else {
                    $borderwidth = " style='width:" . $menu_pic_size_x . "px;' ";
                    $overflowstyle = " style='overflow:hidden;' ";
                } 
            } 
            
            if (!isset($hidemenuborder)) { 
              $hidemenuborder = true;  
            }
            if ($hidemenuborder) {
              $menuborder="0";
            } else {
              $menuborder="1"; 
            }
            echo "<td class='mainnav'>";
            echo "<center><table class='twg' summary='' border='" . $menuborder . "' cellpadding='3'><tr><td " . $borderwidth . " class=twg><div class='div1' " . $overflowstyle . "><center>"; // center is needed - because thunderbird does otherwise not center !          
            // wenn angemeldet - alles gut
            if (($privategal == false) || in_array($privatelogin, $passwd)) {
                if ($skip_thumbnail_page) { // we jump direct to the details page - x ist not found - by default the 1st image is twg_shown
                    printf("<a href='%s?twg_album=%s&amp;twg_show=x%s'>", $_SERVER['PHP_SELF'], $album_enc, $twg_standalone);
                } else {
                    printf("<a href='%s?twg_album=%s%s'>", $_SERVER['PHP_SELF'], $album_enc, $twg_standalone);
                } 
            } 
            // if not logged in - we show the login iframe
            if (($privategal == false) || in_array($privatelogin, $passwd)) {
                $folderfile = $basedir . "/" . $twg_album . "/folder.png";
                if ($folderfileexists) {
                    $folderfileindirect = sprintf ('%simage.php?twg_album=%s&amp;twg_type=png&amp;twg_show=folder.png', $install_dir , $album_enc);
                    echo "<img src='" . $folderfileindirect . "' alt='' />"; // width='" . $menu_pic_size_x . "' height='" . $menu_pic_size_y . "'
                } else if ($imagelist == false) {
                    echo "<div style='border:none;text-align: center; vertical-align: middle;width:" . ($menu_pic_size_x) . "px;height:" . ($menu_pic_size_y) . "px;' ><img style='width:1px;height:" . (($menu_pic_size_y/2)+22) . "px;'src='" . $install_dir . "buttons/1x1.gif' alt='' /><img src='" . $install_dir . "buttons/ordner.gif' alt='' /></div>";
                   
                    // echo "<img src='" . $install_dir . "buttons/ordner.gif' alt='' width='" . $menu_pic_size_x . "' height='" . $menu_pic_size_y . "' />";
                } else {
                    if ($show_colage) { // here we decide if we use 1 image or 4 for he galleryimage
                        $show_col = 4;
                        $show_dif = 2;
                    } else {
                        $show_col = 1;
                        $show_dif = 1;
                    } 

                    if ($use_random_image_for_folder) {
                        srand ((double)microtime() * 1000000); // needed before 4.2 !
                        $nrrand = (count($imagelist) > 3) ? 4 : count($imagelist);
                        if (count($imagelist) == 1) {
                            $keylist = array(0);
                        } else {
                            $keylist = array_rand ($imagelist, $nrrand);
                        } 
                        // echo "vals" . count($imagelist) . ":" . $keylist[0] . $keylist[1] . $keylist[2] . $keylist[3];
                    } else {
                        $keylist = array(0, 1, 2, 3);
                    } 
                    // we twg_show a collage of 2x2 or 1 image depending on $show_colage
                    for($current = 0, $i = 0; $i < $show_col; $i++) {
                        if ($i >= count($imagelist)) {
                            printf("<img src='%sbuttons/1x1.gif' width='%d' height='%d' alt='' />", $install_dir, $menu_pic_size_x / $show_dif, $menu_pic_size_y / $show_dif);
                        } else {
                            $width = $menu_pic_size_x / $show_dif;
                            $height = $menu_pic_size_y / $show_dif;

                            $replaced_album = urldecode(str_replace("/", "_", $twg_album));
                            $aktimage = $imagelist[$keylist[$i]];
                            $thumbimage = urlencode($replaced_album . "_" . urldecode($aktimage));

                            $thumb = "./" . $cachedir . "/" . $thumbimage . "." . $extension_thumb;

                            if (!file_exists($thumb)) {
                                $ccomment = "";
                                loadXMLFiles(urldecode($twg_album));
                                $ccount = getKommentarCount($imagelist[$i], $twg_album, $kwerte, $kindex); 
                                if ( $ccount > 0) {
                                    $ccomment = "&amp;twg_comment=" . $ccount; // this is done to cut of the upper right corner to indicate a comment!
                                } 

                                if ($show_colage) {
                                    printf("<img src='%simage.php?twg_album=%s&amp;twg_type=thumb&amp;twg_show=%s%s' width='%d' height='%d' alt=''/>", $install_dir , $album_enc, $imagelist[$keylist[$i]], $ccomment, $width, $height);
                                } else {
                                    printf("<img src='%simage.php?twg_album=%s&amp;twg_type=thumb&amp;twg_show=%s%s' height='%d' alt=''/>", $install_dir, $album_enc, $imagelist[$keylist[$i]], $ccomment, $height);
                                } 
                            } else {
                                if ($double_encode_urls) {
                                    $thumbimage = urlencode($thumbimage);
                                } 
                                $thumb = "./" . $cachedir . "/" . urlencode($thumbimage) . "." . $extension_thumb;
                                if ($show_colage) {
                                    printf("<img src='%s' width='%d' height='%d' alt=''/>", $thumb, $width, $height);
                                } else {
                                    printf("<img src='%s'  height='%d' alt=''/>", $thumb, $height); 
                                    // this version shrinks the image to the right size - is not used because browser does the sameif you leave out the width
                                    // printf("<img src='%s' id='pic%s' onload=\"ShrinkToFit('pic%s', '%s', '%s');\" alt='%s'/>", $thumb, $xx, $xx, $width, $height, $twg_album);
                                } 
                            } 
                        } 
                        if ($i == 1) {
                            echo "<br />";
                        } 
                    } 
                } 
                echo "</a>";
            } else {
                $privateimage = $basedir . "/" . $twg_album . "/private.png";
                $have_priv_image = file_exists($privateimage);
                if (!$have_priv_image) {
                  echo "<div style='border:none;text-align: center; vertical-align: middle;width:" . ($menu_pic_size_x) . "px;height:" . ($menu_pic_size_y) . "px;' ><img style='width:1px;height:" . (($menu_pic_size_y/2)+22) . "px;'src='" . $install_dir . "buttons/1x1.gif' alt='' />";   
                 }
                // we show the private picture
                if ($session_available) {
                    echo "<a onclick='twg_showSec(" . $lang_height_private . ")'  target='details' href='" . $install_dir . "i_frames/i_privatelogin.php?twg_album=" . $album_enc . $twg_standalone . "'>";
                } else {
                    echo "<a href=\"javascript:alert('" . $lang_no_session . "'); \">";
                } 
                // echo "<a onclick='twg_showSec(100)' target='details' href='i_privatelogin.php?twg_album=" . $album_enc . "&amp;twg_lang=" . $default_language . $twg_standalone . "'>";
                // we look for a individual private gif!
                if ($have_priv_image) {
                    $privatefileindirect = sprintf ('%simage.php?twg_album=%s&amp;twg_type=png&amp;twg_show=private.png', $install_dir, $album_enc);
                    echo "<img src='" . $privatefileindirect . "' alt='' />";
                } else {
                     // echo "<img src='" . $install_dir . "buttons/private.gif' alt='' width='" . $menu_pic_size_x . "' height='" . $menu_pic_size_y . "' />";
                     echo "<img src='" . $install_dir . "buttons/private.gif' alt='' />";
                } 
                 echo "</a>";
                 if (!$have_priv_image) {
                   echo ("</div>");
                 }
            } 
            echo "</center></div></td></tr></table></center>";
            if (($privategal == false) || in_array($privatelogin, $passwd)) {
                if ($skip_thumbnail_page) { // we jump direct to the details page
                    printf("<a href='%s?twg_album=%s&amp;twg_show=x%s'>", $_SERVER['PHP_SELF'], $album_enc, $twg_standalone);
                } else {
                    printf("<a href='%s?twg_album=%s%s'>", $_SERVER['PHP_SELF'] , $album_enc, $twg_standalone);
                } 
            } else {
                if ($session_available) {
                    echo "<a onclick='twg_showSec(" . $lang_height_private . ")'  target='details' href='" . $install_dir . "i_frames/i_privatelogin.php?twg_album=" . $album_enc . $twg_standalone . "'>";
                } else {
                    echo "<a href=\"javascript:alert('" . $lang_no_session . "'); \">";
                } 
                // echo "<a onclick='twg_showSec(100)' target='details' href='i_privatelogin.php?twg_album=" . $album_enc . "&amp;twg_lang=" . $default_language . $twg_standalone .  "'>";
            } 
            $temp1 = explode ("/", $twg_album);
            $temp2 = array_pop($temp1);
            $titel = htmlspecialchars($temp2);
            $titel = getDirectoryName($basedir . "/" . $twg_album, $titel);
            printf("%s", $titel);
            if ($show_number_of_pic) {
                printf(" (%d)", count_tree($basedir . "/" . $twg_album));
            } 

            if ($privategal) {
                if (in_array($privatelogin, $passwd)) {
                    echo "<img class='twg_lock' src='" . $install_dir . "buttons/unlock.gif' alt=''/>";
                } else {
                    echo "<img class='twg_lock' src='" . $install_dir . "buttons/lock.gif' alt='' />";
                } 
            } 
            print "</a></td>";
        } 
    } 
    print "</tr>";
    print "</table>";

    echo '<br/>';
    $menuxy = $menu_x * $menu_y ;
    $actpage = 0;

    if ($basedir != $localdir) {
        $album_next = "&amp;twg_album=" . substr($localdir, strlen($basedir) + 1);
        $album_next_js = "&twg_album=" . substr($localdir, strlen($basedir) + 1);
    } else {
        $album_next = "";
        $album_next_js = "";
    } 
    if ($nr > $menuxy) {
        if ($twg_foffset > 0) {
            $hreflast = sprintf("%s?twg_foffset=%s%s", $_SERVER['PHP_SELF'] , $twg_foffset - $menuxy , $album_next);
            $hreflast_js = sprintf("%s?twg_foffset=%s%s", $_SERVER['PHP_SELF'] , $twg_foffset - $menuxy, $album_next_js);
            echo '<script type="text/javascript"> function key_back() { location.href="' . $hreflast_js . $twg_standalonejs . '" } </script>';
            printf(" <a href='%s'>%s</a>", $hreflast . $twg_standalone , $lang_thumb_back);
        } else {
            echo '<script type="text/javascript"> function key_back() {} </script>';
        } 
        print " |";
        $numpages = ceil($nr / $menuxy);
        for($i = 0; $i < $numpages ; $i++) {
            $twg_foffset_ = $i * ($menuxy);
            if ($twg_foffset == $twg_foffset_) {
                $actpage = $i;
                echo "<span class='twg_bold'>";
            } 
            printf(" <a href='%s?twg_foffset=%s%s%s'>%s</a>", $_SERVER['PHP_SELF'], $twg_foffset_, $album_next, $twg_standalone , $i + 1);
            if ($twg_foffset == $twg_foffset_) {
                echo "</span>";
            } 
            echo " | ";
        } 
        if ($actpage != $numpages - 1) {
            $hrefnext = sprintf("%s?twg_foffset=%s%s", $_SERVER['PHP_SELF'], $twg_foffset + $menuxy, $album_next);
            $hrefnext_js = sprintf("%s?twg_foffset=%s%s", $_SERVER['PHP_SELF'], $twg_foffset + $menuxy, $album_next_js);
            echo '<script type="text/javascript"> function key_foreward() { location.href="' . $hrefnext_js . $twg_standalonejs . '" } </script>';
            printf(" <a href='%s'>%s</a>", $hrefnext . $twg_standalone , $lang_thumb_forward);
        } else {
            echo '<script type="text/javascript"> function key_foreward() { } </script>';
        } 
    } 
    if ($basedir != $localdir) {
        echo '<br/>&nbsp;<br/>';
    } 
    // we return how many rows we twg_show because this is subtracted from the number of images rows twg_show on the same page
    return $numofrows;
} 

function print_big_navigation($twg_album, $album_enc, $image, $twg_rot, $current_id, $thumb_pic_size, $kwerte, $kindex, $dir)
{
    global $twg_rot_available;
    global $top10;
    global $show_big_left_right_buttons;
    global $lang_twg_rot_left;
    global $lang_twg_rot_right;
    global $lang_back;
    global $lang_forward;
    global $twg_showprivatelogin;
    global $twg_smallnav;
    global $show_comments;
    global $default_big_navigation;
    global $small_pic_size;
    global $install_dir;
    global $twg_standalone;
    global $twg_standalonejs;
    global $show_rotation_buttons;
    global $default_is_fullscreen;
    global $show_enter_comment_at_bottom;
    global $lang_comments;
    global $lang_height_comment;

    $nextimage = "";

    if ($twg_smallnav == 'FALSE') {
        if ($twg_rot == -1) { // we reset rotation
            $twg_rot = 0;
        } 
        $ccw = (($twg_rot-90) >= 0) ? ($twg_rot-90) : (360-90);
        $cw = $twg_rot + 90;

        print "<table summary='' class='twg_nav'><tr>";

        if ($last = get_last($twg_album, $image, $current_id)) {
            if (($default_big_navigation == "HTML") && $show_big_left_right_buttons) {
                printf("<td class='navicon'><a href='%s?twg_album=%s&amp;twg_show=%s%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('back','','%sbuttons/back_over.gif',1)\"><img src='%sbuttons/back_normal.gif' alt='%s' title='%s' id='back' width='64' height='64' /></a></td>\n", $_SERVER['PHP_SELF'], $album_enc, $last, $twg_standalone, $install_dir, $install_dir, $lang_back, $lang_back);
            } 
        } 
        if ((gd_version() >= 2) && ($twg_rot_available) && $show_rotation_buttons) {
            printf("<td class='navicon'><a id='twg_rotleft' href='%s?twg_album=%s&amp;twg_show=%s&amp;twg_rot=%s%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('cw','','%sbuttons/iuzs_over.gif',1)\"><img src='%sbuttons/iuzs_normal.gif' alt='%s' title='%s' id='cw' width='64' height='64' /></a></td>\n", $_SERVER['PHP_SELF'], $album_enc, $image, $cw, $twg_standalone, $install_dir, $install_dir, $lang_twg_rot_left, $lang_twg_rot_left);
        } 

        if ($default_big_navigation == "HTML") {
            $nextimage = print_next_last_pics($twg_album, $image, $thumb_pic_size);
        } else {
            print_cmotion_gallery($twg_album, $image, $thumb_pic_size, $dir);
        } 

        if ((gd_version() >= 2) && ($twg_rot_available) && $show_rotation_buttons) {
            printf("<td class='navicon'><a id='twg_rotright' href='%s?twg_album=%s&amp;twg_show=%s&amp;twg_rot=%s%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('ccw','','%sbuttons/guzs_over.gif',1)\"><img src='%sbuttons/guzs_normal.gif' alt='%s' title='%s' id='ccw' width='64' height='64' /></a></td>\n", $_SERVER['PHP_SELF'], $album_enc, $image, $ccw, $twg_standalone, $install_dir, $install_dir, $lang_twg_rot_right, $lang_twg_rot_right);
        } 

        if ($next = get_next($twg_album, $image, $current_id)) {
            if (($default_big_navigation == "HTML") && $show_big_left_right_buttons) {
                printf("<td class='navicon'><a href='%s?twg_album=%s&amp;twg_show=%s%s' onmouseout='MM_swapImgRestore()' onmouseover=\"MM_swapImage('next','','%sbuttons/next_over.gif',1)\"><img src='%sbuttons/next_normal.gif' alt='%s' title='%s' id='next' width='64' height='64' /></a></td>\n", $_SERVER['PHP_SELF'], $album_enc, $next, $twg_standalone , $install_dir, $install_dir, $lang_forward, $lang_forward);
            } 
        } 
        print "</tr></table>";
    } else {
        // TODO: we are looking for the next image and preload it! can be type = small or type = full!
        // this is not done if the big nav is not shown!
        if ($default_is_fullscreen) {
            $type = "full";
        } else {
            $type = "small";
        } 
        $next = get_next($twg_album, $image, $current_id);
        $nextimage = sprintf ('%simage.php?twg_album=%s&twg_type=%s&twg_show=%s&twg_rot=%s', $install_dir, $album_enc, $type , $next, $twg_rot);
    } 
    // is extracted to be w3c conform!
    if ($nextimage <> "") {
        echo "<script type='text/javascript'> MM_preloadImages('" . $nextimage . "') </script>";
    } 
} 

function print_next_last_pics($twg_album, $entry, $thumb_pic_size)
{
    global $numberofpics;
    global $cachedir;
    global $login;
    global $kwerte;
    global $kindex;
    global $werte;
    global $index;
    global $extension_thumb;
    global $extension_small;
    global $install_dir;
    global $twg_standalone;
    global $twg_standalonejs;
    global $double_encode_urls;

    $nextimage = "";

    $imagelist = get_image_list($twg_album);
    $act_nr = get_image_number($twg_album, $entry);
    $album_enc = urlencode($twg_album);

    for($current = 0, $i = 0; $i < count($imagelist); $i++) {
        if (urldecode($imagelist[$i]) == urldecode($entry)) {
            $current = $i;
        } 
    } 

    for($i = $current - $numberofpics; $i <= $current + $numberofpics; $i++) {
        if ($i < 0 || $i >= count($imagelist)) {
            printf("<td class='navicon' style='width:%spx; height:5px;'><img src='%sbuttons/1x1.gif' alt='' /></td>\n", $thumb_pic_size, $install_dir);
        } else {
            $aktimage = $imagelist[$i];
            $aktimage = replace_valid_url($aktimage);
            $replaced_album = str_replace("/", "_", $twg_album);
            $thumbimage = urlencode($replaced_album . "_" . urldecode($aktimage));
            $thumb = "./" . $cachedir . "/" . $thumbimage . "." . $extension_thumb; 
            // todo: check small cache!
            if (!file_exists($thumb)) {
                $src_value = $install_dir . "image.php?twg_album=" . $album_enc . "&amp;twg_type=thumb&amp;twg_show=" . $imagelist[$i];
                // echo $src_value . "<br />";
                $ccount = getKommentarCount($aktimage, $twg_album, $kwerte, $kindex) ; 
                if ( $ccount > 0) {
                    $src_value .= "&amp;twg_comment=" . $ccount; // this is done to cut of the upper right corner to indicate a comment!
                } 
            } else {
                if ($double_encode_urls) {
                    $thumbimage = urlencode($thumbimage);
                } 
                $src_value = "./" . $cachedir . "/" . urlencode($thumbimage) . "." . $extension_thumb;
            } 
            if ($i == $current) {
                printf("<td class=twg><div align='center'><img src='%sbuttons/hier_oben.gif' width='%s' height='7' alt='' /><br /><img alt='' src='%s' /><br /><img src='%sbuttons/hier.gif' width='%s' height='7'  alt='' /></div></td>\n", $install_dir, $thumb_pic_size, $src_value , $install_dir, $thumb_pic_size);
            } else {
                $beschreibung = php_to_all_html_chars(escapeHochkomma(getBeschreibung($aktimage, $werte, $index))); 
                // center is used because ie is ignoring css
                printf("<td class='navicon'  style='width:%s'><center><a href='%s?twg_album=%s&amp;twg_show=%s%s'><img src='%s' alt='%s' title='%s' /></a></center></td>\n", $thumb_pic_size, $_SERVER['PHP_SELF'], $album_enc, $aktimage, $twg_standalone , $src_value, $beschreibung , $beschreibung);
                if (($i - 1) == $current) { // we preload the next big image if available
                    $small_cache = "./" . $cachedir . "/" . $thumbimage . "." . $extension_small;
                    $nextimage = str_replace("'", "\'", $small_cache);
                } 
            } 
        } 
    } 
    return $nextimage;
} 

/*
Displays the top x page
*/
function print_top_10($twg_album, $top10_type)
{
    global $thumbnails_x;
    global $thumbnails_y;
    global $cachedir;
    global $kwerte;
    global $kindex;
    global $werte;
    global $index;
    global $basedir;
    global $extension_thumb;
    global $top10;
    global $privatelogin;
    global $thumb_pic_size;

    global $lang_thumb_forward;
    global $lang_thumb_back;
    global $number_top10;
    global $lang_views;
    global $lang_topx;
    global $install_dir;
    global $double_encode_urls;
    global $lang_no_topx_images;

    global $twg_standalone;
    global $twg_standalonejs;
    global $lang_fileinfo_views;
    global $lang_fileinfo_dl;
    global $lang_fileinfo_rating;
    global $lang_rating_vote;
    global $lang_last_comments;

    global $show_count_views;
    global $enable_download_counter;
    global $show_download_counter;
    global $show_image_rating;
    global $show_comments;
    global $topx_default;
    global $show_topx_comments_details;
    global $enable_download; 
    // for Search
    global $lang_search_results;
    global $twg_search_term;
    global $twg_search_filename;
    global $twg_search_caption;
    global $twg_search_comment;
    global $twg_search_max;
    global $lang_search_hits;
    global $lang_search_hits_limit;
    global $twg_offset;
    global $show_clipped_images;
    global $show_topx_search_details;
    global $autodetect_maximum_thumbnails;

    $album_enc = $twg_album;
    $album_dec = urldecode($twg_album);
    
    if ($autodetect_maximum_thumbnails && isset($_SESSION["browserx"]) /* && ($top10_type == "search") */) {
		        $thumbnails_x = floor(($_SESSION["browserx"] - 30) / ($thumb_pic_size + 30));
    } 
    
    $thumbnails_y = ceil(($number_top10-1) / $thumbnails_x) + 2;
    $showcharacters = ceil($thumb_pic_size / 7)-2;
    
    if ($top10_type == "search") {
        echo "<span class='twg_bold'>" . $lang_search_results . "</span><br />&nbsp;<br />";
    } else {
        $show_spacer = false;
        echo "<span class='twg_bold'>" . sprintf($lang_topx, $number_top10) . "</span><br />";
        echo "<img src='" . $install_dir . "buttons/1x1.gif' width='200' height='3' alt='' /><br />";
        $hrefstart = $install_dir . "image.php?twg_album=";
        echo "<span class='twg_topx_sel'>"; 
        // views
        if ($show_count_views) {
            if ($top10_type == "views") {
                echo "<span class='twg_topx_selected'>" . $lang_fileinfo_views . "</span>";
            } else {
                echo "<a href='" . $_SERVER['PHP_SELF'] . "?twg_album=" . $twg_album . "&amp;twg_top10=views'>" . $lang_fileinfo_views . "</a>";
            } 
            $show_spacer = true;
        } 
        // download
        if ($enable_download_counter && $show_download_counter && $enable_download) {
            if ($show_spacer == true) {
                echo " | ";
                $show_spacer = true;
            } 
            if ($top10_type == "dl") {
                echo "<span class='twg_topx_selected'>" . $lang_fileinfo_dl . "</span>";
            } else {
                echo "<a href='" . $_SERVER['PHP_SELF'] . "?twg_album=" . $twg_album . "&amp;twg_top10=dl'>" . $lang_fileinfo_dl . "</a>";
            } 
        } 
        // rating
        if ($show_image_rating) {
            if ($show_spacer == true) {
                echo " | ";
                $show_spacer = true;
            } 
            if ($top10_type == "average") {
                echo "<span class='twg_topx_selected'>" . $lang_fileinfo_rating . "</span>";
            } else {
                echo "<a href='" . $_SERVER['PHP_SELF'] . "?twg_album=" . $twg_album . "&amp;twg_top10=average'>" . $lang_fileinfo_rating . "</a>";
            } 
            echo " | ";
            if ($top10_type == "votes") {
                echo "<span class='twg_topx_selected'>" . $lang_rating_vote . "</span>";
            } else {
                echo "<a href='" . $_SERVER['PHP_SELF'] . "?twg_album=" . $twg_album . "&amp;twg_top10=votes'>" . $lang_rating_vote . "</a>";
            } 
        } 
        // comments
        if ($show_comments) {
            if ($show_spacer == true) {
                echo " | ";
                $show_spacer = true;
            } 
            if ($top10_type == "comments") {
                echo "<span class='twg_topx_selected'>" . $lang_last_comments . "</span>";
            } else {
                echo "<a href='" . $_SERVER['PHP_SELF'] . "?twg_album=" . $twg_album . "&amp;twg_top10=comments'>" . $lang_last_comments . "</a>";
            } 
        } 
        echo "</span><br/><br/>";
    } 
    $minus_rows = 2;
    if ($twg_album != false) {
        $dd = get_view_dirs($basedir . "/" . $album_dec, $privatelogin);
    } else {
        $dd = get_view_dirs($basedir, $privatelogin);
    } 

    $offset = 10;

    if ($top10_type == "dl") {
        $imagelist = getTopXDownloads($dd);
        $lang_views = "";
    } else if ($top10_type == "average") {
        $imagelist = getTopXAverage($dd);
        $lang_views = "";
    } else if ($top10_type == "votes") {
        $imagelist = getTopXVotes($dd);
        $lang_views = "";
    } else if ($top10_type == "comments") {
        $imagelist = getLatestKomments($dd); 
        // $imagelist = searchCaption($dd,"a");
        $lang_views = "";
    } else if ($top10_type == "search") {
        $imagelist = Array();
        if ($twg_search_caption) {
            $resultlist = searchCaption($dd, $twg_search_term);
            if ($resultlist) {
                $imagelist = array_merge($imagelist , $resultlist);
            } 
        } 
        if ($twg_search_comment) {
            $resultlist = searchComments($dd, $twg_search_term);
            if ($resultlist) {
                $imagelist = array_merge($imagelist , $resultlist);
            } 
        } 
        if ($twg_search_filename) {
            $resultlist = search_filenames($basedir, $twg_search_term, $dd);
            if ($resultlist) {
                $imagelist = array_merge($imagelist , $resultlist);
            } 
        } 
        
        if (count($imagelist) == 0) {
            $imagelist = false;
        } else {
            $nrimages = count($imagelist);
            $imagelist = array_slice($imagelist, $twg_offset, $twg_search_max);
        } 
        $lang_views = "";
    } else {
        $imagelist = getTopXViews($dd);
    } 
    $imageid = 0;

    if ($imagelist) {
        if ($top10_type != "search") {
            $imageid = 1; 
            // we twg_show nr 1 bigger
            if ($top10_type == "comments") {
                list ($cdatum, $cname, $ccomment, $clink) = explode("=||=", $imagelist[0]);
                $imagelist[0] = sprintf("%010s", $cdatum) . "_" . $clink;
            } 
            $src_value = str_replace("type=thumb", "type=small", $imagelist[0]); 
            // the href value has to be encoded differently because the image is sent to
            // the index.php and not the image.php
            $href_value = substr(str_replace($install_dir . "image.php", $_SERVER['PHP_SELF'], $imagelist[0]), 11);
            $pos = strrpos($href_value, "=");
            $posimage = substr($href_value, $pos + 5);
            $href_value = str_replace(urlencode($posimage), $posimage, $href_value) . "&amp;twg_top10=" . $top10_type;

            $beschreibung = " alt='' ";
            echo "<table summary='' class='thumbnails' cellpadding='2' cellspacing='1'>";
            echo "<tr><td class='thumbnails' onMouseOver=\"this.className='twg_hoverThumbnail'\" onMouseOut=\"this.className='twg_unhoverThumbnail'\">";
            echo "<a href='" . $href_value . "'><img " . $beschreibung . " src='" . substr($src_value, $offset + 1) . "' height='" . $thumb_pic_size * (1.5) . "' /></a></td></tr></table>";
            if (($top10_type == "comments") && ($show_topx_comments_details)) {
                if ($cdatum != "") {
                    $image_date = "  <span class='twg_kommentar_date'>(" . date("j.n.Y G:i", $cdatum) . ")</span>" ;
                } else {
                    $image_date = "";
                } 
                echo "<span class='twg_bold'>" . $cname . "</span>" . $image_date . "<br />" . $ccomment;
            } else {
                if ($top10_type == "comments") {
                    echo date("j.n.Y G:i", substr($src_value, 0, $offset));
                } else if ($top10_type == "average") {
                    echo sprintf('%3.2f', substr($src_value, 0, $offset));
                } else {
                    echo sprintf('%d', substr($src_value, 0, $offset));
                } 
                echo $lang_views;
            } 
            echo "<br />&nbsp;<br />";
        } else {
            echo sprintf($lang_search_hits, $nrimages);
            if ($nrimages > $twg_search_max) {
                // we show 1 | 2 | 3 ...
                echo "<br />"; 
                // add the parameters !!
                $actpage = 0;
                
                $s1 = "twg_search_term=" . $twg_search_term;
                $s2 = "twg_search_filename=" . $twg_search_filename;
                $s3 = "twg_search_caption=" . $twg_search_caption;
                $s4 = "twg_search_comment=" . $twg_search_comment;
                $s5 = "twg_search_max=" . $twg_search_max;

                $twg_standalone .= "&amp;twg_top10=search&amp;" . $s1 . "&amp;" . $s2 . "&amp;" . $s3 . "&amp;" . $s4 . "&amp;" . $s5 ;
                $twg_standalonejs .= "&twg_top10=search&" . $s1 . "&" . $s2 . "&" . $s3 . "&" . $s4 . "&" . $s5 ;


                if ($twg_offset > 0) {
                    $hreflast = sprintf("%s?twg_album=%s&amp;twg_offset=%s%s", $_SERVER['PHP_SELF'], $album_enc, $twg_offset - $twg_search_max, $twg_standalone);
                    $hreflastjs = sprintf("%s?twg_album=%s&twg_offset=%s%s", $_SERVER['PHP_SELF'], $album_enc, $twg_offset - $twg_search_max, $twg_standalonejs);
                    echo '<script type="text/javascript"> function key_back() { location.href="' . $hreflastjs . '" } </script>';
                    printf(" <a href='%s'>%s</a>", $hreflast , $lang_thumb_back);
                } else {
                    echo '<script type="text/javascript"> function key_back() {} </script>';
                } 
                print " |";
                $numpages = ceil($nrimages / $twg_search_max);
                for($i = 0; $i < $numpages ; $i++) {
                    $twg_offset_ = $i * ($twg_search_max);
                    if ($twg_offset == $twg_offset_) {
                        $actpage = $i;
                        echo "<span class='twg_bold'>";
                    } 
                    printf(" <a href='%s?twg_album=%s&amp;twg_offset=%s%s'>%s</a>", $_SERVER['PHP_SELF'], $album_enc, $twg_offset_, $twg_standalone, $i + 1);
                    if ($twg_offset == $twg_offset_) {
                        echo "</span>";
                    } 
                    echo " | ";
                } 
                if ($actpage != $numpages - 1) {
                    $hrefnext = sprintf("%s?twg_album=%s&amp;twg_offset=%s%s", $_SERVER['PHP_SELF'], $album_enc, $twg_offset + $twg_search_max, $twg_standalone);
                    $hrefnextjs = sprintf("%s?twg_album=%s&twg_offset=%s%s", $_SERVER['PHP_SELF'], $album_enc, $twg_offset + $twg_search_max, $twg_standalonejs);
                    echo '<script type="text/javascript"> function key_foreward() { location.href="' . $hrefnextjs . '" } </script>';
                    printf(" <a href='%s'>%s</a>", $hrefnext , $lang_thumb_forward);
                } else {
                    echo '<script type="text/javascript"> function key_foreward() { } </script>';
                } 
            } 
            echo "<br />&nbsp;<br />";
        }
        
        if (($top10_type == "comments") && ($show_topx_comments_details)) {
						$thumbnails_x = 2;
						$thumbnails_y = ceil((count($imagelist)-1) / 2);
						if ($thumbnails_y > (($number_top10-1) / 2 )) {
						  $thumbnails_y = ceil(($number_top10-1) / 2);   
						}
						$minus_rows = 0;
				} 

				if ($top10_type == "search") {
						if ($show_topx_search_details) {
							$thumbnails_y = ceil(count($imagelist) / 2); 
							// max detection here !!
							$thumbnails_x = 2;
						} else {
							$thumbnails_y = ceil(count($imagelist) / $thumbnails_x); 
						}
						$minus_rows = 0;
				} 
				
        // we show the remaining pictures
        if ((($top10_type == "comments") && ($show_topx_comments_details)) || ($top10_type == "search")) {   
          if ($show_topx_search_details || (($top10_type == "comments") && ($show_topx_comments_details))) {
              $extraheight = "style='height:" . ($thumbnails_y * ($thumb_pic_size)) . "px;'";
            } else {
              $extraheight = "style='height:" . ($thumbnails_y * ($thumb_pic_size + 55)) . "px;'";;
            }
            echo "<table summary='' class='thumbnails_top10' " . $extraheight  . " cellpadding='0' cellspacing='0'>\n";
        } else {
            echo "<table summary='' style='border:4px #777777;'  class='thumbnails' cellpadding='0' cellspacing='0'>\n";
        } 
        
        for($i = 0; $i < $thumbnails_y - $minus_rows; $i++) {
            print "<tr>";
            for($j = 0; $j < $thumbnails_x; $j++) {
                if (($imageid >= count($imagelist)) /* || ($imageid >= $number_top10 && $top10_type != "search") */ ) { // we fill the last line to get a nice layout
                    printf("<td class=twg></td>"); // class='left_top10'
                } else {
                    if (($top10_type == "comments") || ($top10_type == "search")) {
                        list ($cdatum, $cname, $ccomment, $clink) = explode("=||=", $imagelist[$imageid]);
                        $imagelist[$imageid] = sprintf("%010s", $cdatum) . "_" . $clink;
                    } 

                    $src_value = $imagelist[$imageid]; 
                    // the href value has to be encoded differently because the image is sent to
                    // the index.php and not the image.php
                    $href_value = substr(str_replace($install_dir . "image.php", $_SERVER['PHP_SELF'], $imagelist[$imageid]), 11);
                    $pos = strrpos($href_value, "=");
                    $posimage = substr($href_value, $pos + 5);
                    $href_value = str_replace(urlencode($posimage), $posimage, $href_value) . "&amp;twg_top10=" . $top10_type;
                    $beschreibung = " alt='' ";
                    if ((($top10_type == "comments") && ($show_topx_comments_details)) || (($top10_type == "search") && ($show_topx_search_details))) {
                        if ($cdatum != "") {
                            $image_date = "  <span class='twg_kommentar_date'>(" . date("j.n.Y G:i", $cdatum) . ")</span>" ;
                        } else {
                            $image_date = "";
                        } 
                        $count_str = "<span class='twg_bold'>" . $cname . "</span>" . $image_date . "<br />" . $ccomment;
                    } else if ($top10_type == "comments") {
                        $count_str = date("j.n.Y G:i", substr($src_value, 0, $offset));
                    } else if ($top10_type == "average") {
                        $count_str = sprintf('%3.2f', substr($src_value, 0, $offset));
                    } else if ($top10_type == "search") {
                        if ($cdatum != "") {
														$image_date = "  <span class='twg_kommentar_date'>(" . date("j.n.Y G:i", $cdatum) . ")</span><br />" ;
														$spacer = "";
												} else {
														$image_date = "";
														$spacer = "<br />&nbsp;";
												} 
                        $count_str =  $image_date . "<span class='twg_bold'>" . htmlentities(substr(html_entity_decode(strip_tags($cname)), 0, $showcharacters)) . "</span>" . "<br />" . htmlentities(substr(html_entity_decode(strip_tags($ccomment)),0,$showcharacters)) . $spacer ;
                    } else {
                        $count_str = sprintf('%d', substr($src_value, 0, $offset));
                    } 
                    if ((($top10_type == "comments") && ($show_topx_comments_details)) || (($top10_type == "search") && $show_topx_search_details )) {
                        if ($show_clipped_images) {
                            $defineheight = "width:" . ($thumb_pic_size + 2) . "px; height:" . ($thumb_pic_size + 4) . "px;";
                        } else {
                            $defineheight = "width:" . ($thumb_pic_size + 2) . "px;";
                        } 
                        echo "<td class='left_top10'>
		                     <table summary='' class='thumbnails_top10' cellpadding='2' cellspacing='1'><tr><td style='text-align:center;" . $defineheight . "' class='thumbnails_top10' onMouseOver=\"this.className='twg_hoverThumbnail'\" onMouseOut=\"this.className='twg_unhoverThumbnail'\"><a href='" . $href_value . "'><img " . $beschreibung . " src='" . substr($src_value, 11) . "'  /></a></td><td class='thumbnails_top10' >" . $count_str . "</td></tr></table></td>";
                    } else {
            						$extrastyle = "style='vertical-align:top;'";
                        echo "<td class='thumbnails' " . $extrastyle . ">
		                     <table summary='' class='thumbnails' cellpadding='2' cellspacing='1'><tr><td class='thumbnails' onMouseOver=\"this.className='twg_hoverThumbnail'\" onMouseOut=\"this.className='twg_unhoverThumbnail'\"><a href='" . $href_value . "'><img " . $beschreibung . " src='" . substr($src_value, 11) . "'  /></a></td></tr></table>" . $count_str . $lang_views . "</td>";
                    } 
                } 
                $imageid++;
            } 
            print "</tr>\n";
        } 
        if ($top10_type == "search") {
            // echo "<tr><td style='height:" . $thumb_pic_size . "px;'>&nbsp;</td><td>&nbsp;</td></tr>";
        } 
        print "</table>\n";
    } else { // if we on't have anything we twg_show an empty image :)
        echo "<img src='" . $install_dir . "buttons/1x1.gif' width='200' height='100' alt='' /><br />";
        echo $lang_no_topx_images;
    } 
} 

function print_thumbnails($twg_album, $twg_offset, $werte, $index, $twg_foffset)
{
    global $thumbnails_x;
    global $thumbnails_y;
    global $cachedir;
    global $kwerte;
    global $kindex;
    global $basedir;
    global $extension_thumb;
    global $top10;
    global $privatelogin;

    global $lang_thumb_forward;
    global $lang_thumb_back;
    global $install_dir;
    global $twg_standalone;
    global $twg_standalonejs;
    global $double_encode_urls;
    global $autodetect_maximum_thumbnails;
    global $thumb_pic_size;
    global $show_number_of_comments;
    global $lang_comments;

    $album_enc = urlencode($twg_album);
    $imagelist = get_image_list($twg_album);
    $imageid = ($twg_offset > 0 ? $twg_offset : 0);
    $temp1 = explode ("/", $twg_album);
    $titel = array_pop($temp1);
    $titel = getDirectoryName($basedir . "/" . $twg_album, $titel);

    $offset_text = 0;
    printf("<span class='twg_title'>%s</span><br/>", $titel);
    $text = getDirectoryDescription($basedir . "/" . $twg_album);
    if ($text) {
        $offset_text = 20;
        echo '<img height=5 width=1 alt=""  src="' . $install_dir . 'buttons/1x1.gif" /><br />';
        echo "<span class='twg_folderdescription'>" . $text . "</span><br />";
    } 
    echo "<br/>"; 
    // we do autowidthdetection here !
    if ($autodetect_maximum_thumbnails && isset($_SESSION["browserx"]) && isset($_SESSION["browsery"])) {
        $thumbnails_x = floor(($_SESSION["browserx"] - 30) / ($thumb_pic_size + 5));
        $thumbnails_y = floor(($_SESSION["browsery"] - 50 - $offset_text) / ($thumb_pic_size + 5));
    } 

    $minus_rows = floor(show_folders($basedir . "/" . $twg_album, $twg_foffset) * 1.4);
    if ($minus_rows >= $thumbnails_y) {
        $minus_rows = $thumbnails_y -1;
    } 

    if ($imagelist[0] != "") {
        print "<table summary='' class='thumbnails' cellpadding='0' cellspacing='0'>\n";
        for($i = 0; $i < $thumbnails_y - $minus_rows; $i++) {
            print "<tr>";
            for($j = 0; $j < $thumbnails_x; $j++) {
                if ($imageid >= count($imagelist)) {
                    printf("<td class='thumbnails'>&nbsp;</td>");
                } else {
                    $aktimage = $imagelist[$imageid];

                    $aktimage = replace_valid_url($aktimage);
                    $replaced_album = str_replace("/", "_", $twg_album);
                    $thumbimage = urlencode($replaced_album . "_" . urldecode($aktimage));
                    $thumb = "./" . $cachedir . "/" . $thumbimage . "." . $extension_thumb;

										$ccount = getKommentarCount($imagelist[$imageid], $twg_album, $kwerte, $kindex);
                        
                    if (!file_exists($thumb)) {
                        $src_value = $install_dir . "image.php?twg_album=" . $album_enc . "&amp;twg_type=thumb&amp;twg_show=" . $aktimage;
                        $ccount = getKommentarCount($imagelist[$imageid], $twg_album, $kwerte, $kindex);
                        if ($ccount > 0) {
                            $src_value .= "&amp;twg_comment=" . $ccount;
                        } 
                    } else {
                        if ($double_encode_urls) {
                            $thumbimage = urlencode($thumbimage);
                        } 
                        $src_value = "./" . $cachedir . "/" . urlencode($thumbimage) . "." . $extension_thumb;
                    } 
                    
                    $beschreibung = getBeschreibung($imagelist[$imageid], $werte, $index);
                    if (($beschreibung <> " ") && ($beschreibung <> "")) {
                        $beschreibunga = php_to_all_html_chars(escapeHochkomma($beschreibung));
                        if ($show_number_of_comments && ($ccount > 0)) {
                          $beschreibunga .= " | " . $lang_comments . ": " .  $ccount;  
                        }
                        $beschreibung = "title='" . $beschreibunga . "'";
                        $beschreibung .= " alt='" . $beschreibunga . "'";
                    } else if ($ccount > 0)  {
                        $beschreibunga = $lang_comments . ": " .  $ccount;  
                        $beschreibung = "title='" . $beschreibunga . "'";
                        $beschreibung .= " alt='" . $beschreibunga . "'";	
                    } else {
                        $beschreibung = " alt='' ";
                    } 
                    printf("<td class='thumbnails' >
                    <table summary='' class='thumbnails' cellpadding='2' cellspacing='1'><tr><td class='thumbnails' onMouseOver=\"this.className='twg_hoverThumbnail'\" onMouseOut=\"this.className='twg_unhoverThumbnail'\"><a href='%s?twg_album=%s&amp;twg_show=%s%s'><img src='%s' %s /></a></td></tr></table></td>", $_SERVER['PHP_SELF'], $album_enc, $aktimage, $twg_standalone, $src_value, $beschreibung);
                    $imageid++;
                } 
            } 
            print "</tr>\n";
        } 
        print "</table>\n";
    } 

    $_SESSION["twg_minus_rows"] = $minus_rows; // stored for offset needed in details page
    $thumbnails_y = $thumbnails_y - $minus_rows;
    $thumbnails = $thumbnails_x * $thumbnails_y ;
    $actpage = 0;
    if (count($imagelist) > $thumbnails) {
        if ($twg_offset > 0) {
            $hreflast = sprintf("%s?twg_album=%s&amp;twg_offset=%s%s", $_SERVER['PHP_SELF'], $album_enc, $twg_offset - $thumbnails, $twg_standalone);
            $hreflastjs = sprintf("%s?twg_album=%s&twg_offset=%s%s", $_SERVER['PHP_SELF'], $album_enc, $twg_offset - $thumbnails, $twg_standalonejs);
            echo '<script type="text/javascript"> function key_back() { location.href="' . $hreflastjs . '" } </script>';
            printf(" <a href='%s'>%s</a>", $hreflast , $lang_thumb_back);
        } else {
            echo '<script type="text/javascript"> function key_back() {} </script>';
        } 
        print " |";
        $numpages = ceil(count($imagelist) / ($thumbnails_x * $thumbnails_y));
        for($i = 0; $i < $numpages ; $i++) {
            $twg_offset_ = $i * ($thumbnails_x * $thumbnails_y);
            if ($twg_offset == $twg_offset_) {
                $actpage = $i;
                echo "<span class='twg_bold'>";
            } 
            printf(" <a href='%s?twg_album=%s&amp;twg_offset=%s%s'>%s</a>", $_SERVER['PHP_SELF'], $album_enc, $twg_offset_, $twg_standalone, $i + 1);
            if ($twg_offset == $twg_offset_) {
                echo "</span>";
            } 
            echo " | ";
        } 
        if ($actpage != $numpages - 1) {
            $hrefnext = sprintf("%s?twg_album=%s&amp;twg_offset=%s%s", $_SERVER['PHP_SELF'], $album_enc, $twg_offset + $thumbnails, $twg_standalone);
            $hrefnextjs = sprintf("%s?twg_album=%s&twg_offset=%s%s", $_SERVER['PHP_SELF'], $album_enc, $twg_offset + $thumbnails, $twg_standalonejs);
            echo '<script type="text/javascript"> function key_foreward() { location.href="' . $hrefnextjs . '" } </script>';
            printf(" <a href='%s'>%s</a>", $hrefnext , $lang_thumb_forward);
        } else {
            echo '<script type="text/javascript"> function key_foreward() { } </script>';
        } 
    } 
} 

function print_cmotion_gallery($twg_album, $entry, $thumb_pic_size, $dir)
{
    global $numberofpics; // the number of pics on each side which are loaded for ie - ff needs all!
    global $cachedir;
    global $basedir;
    global $login;
    global $kwerte;
    global $kindex;
    global $werte;
    global $index;
    global $extension_thumb;
    global $extension_small;
    global $show_count_views;
    global $default_language;
    global $login;
    global $cmotion_gallery_limit_ie;
    global $cmotion_gallery_limit_firefox;
    global $lang_forward;
    global $lang_back;
    global $enable_download;
    global $thumb_pic_size;
    global $lang_loading;
    global $twg_rot_available;
    global $enable_direct_download;
    global $enable_optimize_cmotion_gallery_limit_ie;
    global $install_dir;
    global $show_optionen;
    global $show_comments;
    global $show_login;
    global $browser_title_prefix;
    global $twg_standalone;
    global $twg_standalonejs;
    global $double_encode_urls;
    global $show_rotation_buttons;
    global $show_enter_comment_at_bottom;
    global $show_enhanced_file_infos;
    global $show_image_rating;
    global $show_comments_in_layer;
    global $image_rating_position;
    global $show_number_of_comments;
    global $enable_download_as_zip;

    $msie = stristr($_SERVER["HTTP_USER_AGENT"], "MSIE");
    $opera8 = stristr($_SERVER["HTTP_USER_AGENT"], "Opera");
    $isns = stristr($_SERVER["HTTP_USER_AGENT"], "Mozilla") && (!(stristr($_SERVER["HTTP_USER_AGENT"], "compatible")));

    $preloadrange = 4;
    $cmotionoverlap = 3;

    if ($msie && $enable_optimize_cmotion_gallery_limit_ie && !$opera8) {
        $cmotion_gallery_limit = $cmotion_gallery_limit_ie;
    } else {
        $preloadrange = 1000; // dummy value to load all pictures of this set!
        $cmotion_gallery_limit = $cmotion_gallery_limit_firefox;
    } 

    $space = 8;
    $imagelist = get_image_list($twg_album);
    $act_nr = get_image_number($twg_album, $entry);
    $album_enc = urlencode($twg_album);
    $imgtwg_offset = 0;
    for($current = 0, $i = 0; $i < count($imagelist); $i++) {
        if (urldecode($imagelist[$i]) == urldecode($entry)) {
            $current = $i;
        } 
    } 
    // we calculate the pre and posts
    if ($dir == "next") {
        $startgal = $act_nr - $cmotionoverlap;
        $stopgal = $startgal + $cmotion_gallery_limit;
    } else {
        $startgal = $act_nr - $cmotion_gallery_limit + $cmotionoverlap;
        $stopgal = $act_nr + $cmotionoverlap;
    } 

    if (($startgal < 0 && $dir == "next") || ($startgal < 2 && $dir == "back")) { // for downwardsfix
        $startgal = 0;
        $stopgal = $cmotion_gallery_limit;
    } 
    // for upward I don't want to have more than 2 images for the next galerie and the rest than backwards);
    if ($dir == "next" && $stopgal > (count($imagelist)-2)) {
        $stopgal = count($imagelist);
        $startgal = $stopgal - $cmotion_gallery_limit;
        if ($startgal < 0) $startgal = 0;
    } 

    $num_twg_shown_images = $stopgal - $startgal;
    echo "<td class=twg>";
    echo '<div id="motioncontainer" style="position:relative;width:' . (($numberofpics * 2 + 1) * $thumb_pic_size + ($numberofpics * 2) * $space) . 'px;height:' . ($thumb_pic_size + 2) . 'px;overflow:hidden;">
<div id="motiongallery" style="position:absolute;left:0;top:0;white-space: nowrap;vertical-align: middle;"><!--<nobr>-->';
    $thumbimage = str_replace("/", "_", $twg_album) . "_" . $imagelist[0];
    $thumb = "./" . $cachedir . "/" . $thumbimage . "." . $extension_thumb;
    if (file_exists($thumb)) {
        $size1st = getimagesize($thumb);
        $size1stX = $size1st[0];
    } else {
        $size1stX = $thumb_pic_size;
    } 

    $twg_offset1st = floor(($thumb_pic_size - $size1stX) / 2); 
    // echo $twg_offset1st;
    // $imgtwg_offset = -$twg_offset1st; // we have to add the starting twg_offset!
    echo '<img src="' . $install_dir . 'buttons/1x1.gif" alt="" align="middle" width=' . ($twg_offset1st + ($numberofpics * $space) + ($numberofpics * $thumb_pic_size) -30) . ' height=' . $thumb_pic_size . ' />';
    if ($startgal > 0) {
        $hreflast = $_SERVER['PHP_SELF'] . "?twg_album=" . urlencode($twg_album) . "&amp;twg_dir=back&amp;twg_show=" . $imagelist[$startgal-1] . $twg_standalone;
        $hreflastjs = $_SERVER['PHP_SELF'] . "?twg_album=" . urlencode($twg_album) . "&twg_dir=back&twg_show=" . $imagelist[$startgal-1] . $twg_standalonejs;

        printf("<a href='%s'><img style='border: 0px;' src='%sbuttons/menu_left.gif' alt='%s' align='middle' title='%s' width='22' /></a>", $hreflast, $install_dir, $lang_back, $lang_back);
    } else {
        echo '<img src="' . $install_dir . 'buttons/1x1.gif" alt="" width=22 height=1 />';
        $hreflast = "#";
        $hreflastjs = "#";
    } 
    echo '<img src="' . $install_dir . 'buttons/1x1.gif" alt="" width=6 height=1 />';

    for($i = $startgal; (($i < count($imagelist)) && ($i < $stopgal)) ; $i++) {
        $thumbimage = urlencode(str_replace("/", "_", $twg_album) . "_" . urldecode($imagelist[$i]));
        $thumb = "./" . $cachedir . "/" . $thumbimage . "." . $extension_thumb;
        $thumbexists = false;
        if (file_exists($thumb)) {
            $size = getimagesize($thumb);
            $sizeX = $size[0];
            $sizeY = $size[1];
            $thumbexists = true;
        } else {
            $sizeX = $thumb_pic_size;
            $sizeY = $thumb_pic_size; 
            // echo $thumb;
        } 
        // here we calculate the point how much we have to jump the cmotion gallery forward
        if ($i < $current) {
            $imgtwg_offset += $sizeX + $space;
        } 
        if ($i == $current) { // the last image!
            // echo floor((120 - $sizeX) /2);
            $imgtwg_offset += floor(($sizeX / 2) - ($size1stX / 2)) ;
        } 
        // echo $imgtwg_offset;
        loadXMLFiles(urldecode($twg_album));
        $beschreibung = getBeschreibung($imagelist[$i], $werte, $index);
        if (($beschreibung <> " ") && ($beschreibung <> "")) {
            $beschreibunga = escapeHochkomma($beschreibung);
            $beschreibung = "title='" . $beschreibunga . "'";
            $beschreibung .= " alt='" . $beschreibunga . "'";
        } else {
            $beschreibung = " alt='' ";
        } 

        if ($double_encode_urls) {
            $thumbimage = urlencode($thumbimage);
        } 
        if (($i > ($act_nr - $preloadrange)) && ($i < ($act_nr + $preloadrange))) {
            if ($thumbexists) {
                $src = "./" . $cachedir . "/" . urlencode($thumbimage) . "." . $extension_thumb;
            } else {
                $ccomment = "";
                $ccount = getKommentarCount($imagelist[$i], $twg_album, $kwerte, $kindex); 
                if ($ccount > 0) {
                    $ccomment = "&amp;twg_comment=" . $ccount; // this is done to cut of the upper right corner to indicate a comment!
                } 
                $src = $install_dir . 'image.php?twg_album=' . urlencode($twg_album) . '&amp;twg_type=thumb&amp;twg_show=' . $imagelist[$i] . $ccomment;
            } 
            echo '<a href="javascript:changeContent(\'' . $i . '\')"><img align="middle" name="name' . $i . '" ' . $beschreibung . ' src="' . $src . '" border=1 /></a><img src="' . $install_dir . 'buttons/1x1.gif" alt="" align="middle" width=6 height=1 />';
        } else {
            echo '<a href="javascript:changeContent(\'' . $i . '\')" ><img src="' . $install_dir . 'buttons/1x1.gif" alt="" name=name' . $i . ' ' . $beschreibung . ' align="middle" width=' . $sizeX . ' height=' . $sizeY . ' /></a><img src="' . $install_dir . 'buttons/1x1.gif" alt="" align="middle" width=6 height=1 />';
        } 
    } // for       
    // now we create the Array with the imagesources we have to replace!
    echo '<script type="text/javascript">';
    echo 'var thumbs=new Array();';
    echo 'var thumbstwg_offset=new Array();';
    $sum = 0;
    for($i = $startgal; (($i < count($imagelist)) && ($i < $stopgal)) ; $i++) {
        $ccomment = "";
        loadXMLFiles(urldecode($twg_album));
        $ccount = getKommentarCount($imagelist[$i], $twg_album, $kwerte, $kindex); 
        if ($ccount > 0) {
            $ccomment = "&amp;twg_comment=" . $ccount; // this is done to cut of the upper right corner to indicate a comment!
        } 
        echo "thumbs[" . $i . "] = 'twg_album=" . urlencode($twg_album) . "&twg_show=" . $imagelist[$i] . $ccomment . $twg_standalonejs . "';\n";
        $thumbimage = urlencode(str_replace("/", "_", $twg_album) . "_" . urldecode($imagelist[$i]));

        $thumb = $cachedir . "/" . $thumbimage . "." . $extension_thumb;
        $thumbexists = false;
        if (file_exists($thumb)) {
            $size = getimagesize($thumb);
            $sizeX = $size[0];
            $thumbexists = true;
        } else {
            $sizeX = $thumb_pic_size;
        } 
        if ($double_encode_urls) {
            $thumbimage = urlencode($thumbimage);
        } 
        $twg_offset = floor(($sizeX / 2) - ($size1stX / 2)) ;
        echo "thumbstwg_offset[" . $i . "] = " . ($sum + $twg_offset) . ";\n"; // 1st sum has to be the startpoint!!!
        $sum = $sum + $sizeX + $space;
    } 

    echo '</script>'; 
    // we insert the script here because otherwise the function os not known sometimes
    echo '<script type="text/javaScript" src="./js/twg_xhconn.js"></script>';
    echo '<script type="text/javascript">

var lastpos = ' . $current . ';
var reload = 0;
var img;
var newCaption = "old";
var loadedCaption = false;
var newComment = "";
var loadedComment = false;
var newView = "";
var loadedView = false;
var newDirect = "";
var loadedDirect = false;
var newRating = "";
var loadedRating = false;

var ready = true;

function load_img(srcnum, type) 
{ 
   if (img!=0) 
     delete img; /* altes Bild entsorgen */ 
   img=new Image(); /* neues Bild-Objekt anlegen */ 
   img.src="' . $install_dir . 'image.php?" + thumbs[srcnum] + type + "&id=" + lastpos; /* Bild laden lassen */ 
} 

function load_caption(pos) {
var myConn = new XHConn();
if (!myConn) alert("XMLHTTP not available. Try a newer/better browser.");
var fnWhenDone = function (oXML) { newCaption = oXML.responseText; loadedCaption = true; };
myConn.connect("' . $install_dir . 'image.php?" + thumbs[pos] + "&twg_xmlhttp=b", fnWhenDone);
}

function load_comment(pos) {
var myConn2 = new XHConn();
var fnWhenDoneC = function (coXML) { newComment = coXML.responseText; loadedComment = true; };
myConn2.connect("' . $install_dir . 'image.php?" +thumbs[pos] + "&twg_xmlhttp=c", fnWhenDoneC);
}

function load_view(pos) {
var myConn3 = new XHConn();
var fnWhenDoneV = function (voXML) { newView = voXML.responseText; loadedView = true; };
myConn3.connect("' . $install_dir . 'image.php?" +thumbs[pos] + "&twg_xmlhttp=v", fnWhenDoneV);
}

function load_direct(pos) {
var myConn4 = new XHConn();
var fnWhenDoneD = function (doXML) { newDirect = doXML.responseText; loadedDirect = true; };
myConn4.connect("' . $install_dir . 'image.php?" + thumbs[pos] + "&twg_xmlhttp=d", fnWhenDoneD);
}

function load_rating(pos) {
var myConn5 = new XHConn();
var fnWhenDoneR = function (doXML) { newRating = doXML.responseText; loadedRating = true; };
myConn5.connect("' . $install_dir . 'image.php?" + thumbs[pos] + "&twg_xmlhttp=a", fnWhenDoneR);
}

function startPostLoadImages() {
';
    for($i = $startgal; (($i < count($imagelist)) && ($i < $stopgal)) ; $i++) {
        $thumbimage = urlencode(str_replace("/", "_", $twg_album) . "_" . urldecode($imagelist[$i]));

        $thumb = "./" . $cachedir . "/" . $thumbimage . "." . $extension_thumb;
        if (($i <= ($act_nr - $preloadrange)) || ($i >= ($act_nr + $preloadrange))) {
            if (file_exists($thumb)) {
                if ($double_encode_urls) {
                    $thumbimage = urlencode($thumbimage);
                } 
                echo "document.images.name" . $i . ".src = '" . $cachedir . "/" . urlencode($thumbimage) . "." . $extension_thumb . "';\n";
            } else {
                $ccomment = "";
                $ccount = getKommentarCount($imagelist[$i], $twg_album, $kwerte, $kindex);  
                if ($ccount > 0) {
                    $ccomment = "&amp;twg_comment=" . $ccount; // this is done to cut of the upper right corner to indicate a comment!
                } 
                echo "document.images.name" . $i . ".src = '" . $install_dir . "image.php?twg_album=" . urlencode($twg_album) . "&amp;twg_type=thumb&amp;twg_show=" . $imagelist[$i] . $ccomment . "';\n";
            } 
        } 
    } 
    echo '}';
    echo' 
function startpreLoadImages() { ';
    echo 'MM_preloadImages(';
    for($i = $stopgal; (($i < count($imagelist)) && ($i < $stopgal + $cmotion_gallery_limit)) ; $i++) {
        $thumbimage = urlencode(str_replace("/", "_", $twg_album) . "_" . urldecode($imagelist[$i]));
        $thumb = "./" . $cachedir . "/" . $thumbimage . "." . $extension_thumb;
        if (file_exists($thumb)) {
            if ($double_encode_urls) {
                $thumbimage = urlencode($thumbimage);
            } 
            echo "'" . $cachedir . "/" . urlencode($thumbimage) . "." . $extension_thumb . "',";
        } else {
            $ccomment = "";
            $ccount = getKommentarCount($imagelist[$i], $twg_album, $kwerte, $kindex);  
            if ($ccount > 0) {
                $ccomment = "&amp;twg_comment=" . $ccount; // this is done to cut of the upper right corner to indicate a comment!
            } 
            echo "'" . $install_dir . "image.php?twg_album=" . urlencode($twg_album) . "&twg_type=thumb&twg_show=" . $imagelist[$i] . $ccomment . "',";
        } 
    } 
    echo "'');}";

    echo'
var centerStart = 0;
function centerGal() {
 if (centerStart++ == 0) {
    window.setTimeout("startPostLoadImages()",2000);
    setPos(\'' . $imgtwg_offset . '\');
    window.setTimeout("startpreLoadImages()",4000);
 }
}

centerpos = ' . $imgtwg_offset . ';

function setCenterGal(cpos) {
  centerpos = parseInt(cpos);
}

function centerGalLater() {
    setPos(centerpos);
}

</script>
';

    $thumbimage = str_replace("/", "_", $twg_album) . "_" . $imagelist[count($imagelist)-1];
    $thumb = "./" . $cachedir . "/" . $thumbimage . "." . $extension_thumb;
    if (file_exists($thumb)) {
        $size1st = getimagesize($thumb);
        $size1stX = $size1st[0];
    } else {
        $size1stX = $thumb_pic_size;
    } 
    // firefox add 2 pixel per image because it dows not calculate
    $firefox_fix = 0;
    if ($isns) {
        $firefox_fix = 2 * ($num_twg_shown_images-1);
    } 
    echo '<img src="' . $install_dir . 'buttons/1x1.gif" alt="" align=middle width=6 height=1/>';
    if ($stopgal < count($imagelist)) {
        $hrefnext = $_SERVER['PHP_SELF'] . "?twg_album=" . urlencode($twg_album) . "&amp;twg_dir=next&amp;twg_show=" . $imagelist[$stopgal] . $twg_standalone ;
        $hrefnextjs = $_SERVER['PHP_SELF'] . "?twg_album=" . urlencode($twg_album) . "&twg_dir=next&twg_show=" . $imagelist[$stopgal] . $twg_standalonejs;

        printf("<a href='%s'><img style='border: 0px;' src='%sbuttons/menu_right.gif' alt='%s' align='middle' title='%s' width='22' /></a>", $hrefnext, $install_dir, $lang_forward, $lang_forward);
    } else {
        echo '<img src="' . $install_dir . 'buttons/1x1.gif" width=22 height=1 alt="" />';
        $hrefnext = "#";
        $hrefnextjs = "#";
    } 
    echo '<img id="lastimage" align=middle src="' . $install_dir . 'buttons/1x1.gif" alt="" width=' . ((($thumb_pic_size - $size1stX) / 2) + ($thumb_pic_size * $numberofpics) - (39 - ($numberofpics * 8)) + $firefox_fix) . ' height=1 />'; 
    // echo '</nobr>';
    echo '</div></div>'; 
    // thin function is need after all the others because we need some content from the php function before!
    echo '<script type="text/javascript">

function changeContent(pos) {
centerpos = thumbstwg_offset[parseInt(pos)];
reload=0;
changeContentWait(pos);
}

function changeContentWaitLast() {
   changeContentWait(lastpos);
}

function changeContentWait(poss) { 
 pos=parseInt(poss);
 changeinfo = hideAll();
 if (pos >= ' . count($imagelist) . ') { return; }
 if (pos < ' . $startgal . ') {  window.location="' . $hreflastjs . '"; return; }
 if ( pos > ' . ($stopgal-1) . ') {  window.location="' . $hrefnextjs . '"; return;  }
 ready = false;
 lastpos = pos;
 var box = document.getElementById("CaptionBox");
 if (reload == 0) {
    load_caption(pos);
    load_comment(pos);';
    if ($enable_download) {
        echo 'load_direct(pos);';
    } 
    if ($show_count_views) {
        echo 'load_view(pos);';
    } 
    if ($show_image_rating && ($image_rating_position != "menu")) {
        echo 'load_rating(pos);';
    } 
    echo 'load_img(pos, "&twg_type=small");    
 } else if (reload==1) {
    box.innerHTML = "' . $lang_loading . '";
 } 
 reload++;
 ';

    if ($show_count_views) {
        $vv = ' && (loadedView) ';
    } else {
        $vv = "";
    } 
    if ($enable_download) {
        $dd = ' && (loadedDirect) ';
    } else {
        $dd = "";
    } 

    if ($show_image_rating && ($image_rating_position != "menu")) {
        $rr = ' && (loadedRating) ';
    } else {
        $rr = "";
    } 

    echo '
 if ((img.complete) && (loadedCaption) && (loadedComment) ' . $vv . $dd . ') {
      document.images.defaultslide.src=img.src;';

    $linkfilename = $basedir . "/" . $twg_album . "/link.txt";
    if (file_exists($linkfilename)) { // link file exists !!!
        // we don't change the link - if is fine because it links the another website !
    } else if ($enable_download) {
        $zipfile = $basedir . "/" . $twg_album . "/" . str_replace("/", "_", $twg_album) . ".zip";
        if ($enable_download_as_zip && file_exists($zipfile)) {
            echo '  document.getElementById("adefaultslide").href = "' . $install_dir . 'i_frames/i_downloadmanager.php?" + thumbs[pos];';
        } else if ($enable_direct_download) {
            echo '  document.getElementById("adefaultslide").href = newDirect;';
        } else {
            echo '  document.getElementById("adefaultslide").href = "' . $install_dir . 'image.php?" + thumbs[pos];';
        } 
    } 

    echo 'box.innerHTML = newCaption;';
    echo 'document.getElementById("imagecounter").innerHTML = (parseInt(pos) + 1);';
    
    if ($show_count_views) {
        echo '		  document.getElementById("viewcounter").innerHTML = newView;';
    } 
    echo' document.getElementById("start_slideshow").href = "' . $_SERVER['PHP_SELF'] . '?" + thumbs[pos] +  "&twg_slideshow=true' . $twg_standalonejs . '";';
    if ($show_comments) {
        echo 'numComments = newComment.substring(0,10);';
        echo 'newComments = newComment.substring(10, newComment.length-1); ';
        echo 'if (newComments.length == 1) { newComments = "" } ';
        if (!$show_comments_in_layer) {
            echo 'document.getElementById("kommentartd").innerHTML = newComments;';
        } else {
            if ($show_enter_comment_at_bottom) {
                echo' document.getElementById("kommentarenter").href = "' . $install_dir . 'i_frames/i_kommentar.php?" + thumbs[pos] +  "' . $twg_standalonejs . '";'; 
                // the number of the comments !
                if ($show_number_of_comments) {
                    echo 'document.getElementById("kommentarnumber").innerHTML = numComments.replace(/\s*/, "") ;';
                } 
            } 
        } 
        echo 'document.getElementById("commentcount").innerHTML = numComments.replace(/\s*/, "") ;';
        echo' document.getElementById("i_comment").href = "' . $install_dir . 'i_frames/i_kommentar.php?" + thumbs[pos] +  "' . $twg_standalonejs . '";';
    } 
    if ($show_enhanced_file_infos) {
        echo' document.getElementById("i_info").href = "' . $install_dir . 'i_frames/i_info.php?" + thumbs[pos] +  "' . $twg_standalonejs . '";';
        echo' if (changeinfo) parent["details"].location.href = "' . $install_dir . 'i_frames/i_info.php?" + thumbs[pos] +  "' . $twg_standalonejs . '";';
    } 
    if ($show_image_rating) {
        echo' document.getElementById("i_rate").href = "' . $install_dir . 'i_frames/i_rate.php?" + thumbs[pos] +  "' . $twg_standalonejs . '";';
    } 

    if ($show_image_rating && ($image_rating_position != "menu")) {
        echo 'document.getElementById("img_rating").innerHTML = newRating;';
    } 
    // if ($show_comments && $show_enter_comment_at_bottom) {
    // echo' document.getElementById("i_comment2").href = "' . $install_dir . 'i_frames/i_kommentar.php?" + thumbs[pos] +  "' . $twg_standalonejs . '";';
    // }
    if ($show_optionen) {
        echo' document.getElementById("i_options").href = "' . $install_dir . 'i_frames/i_optionen.php?" + thumbs[pos] +  "' . $twg_standalonejs . '";';
    } 
    if ($twg_rot_available && $show_rotation_buttons) {
        echo 'document.getElementById("twg_rotleft").href = "' . $_SERVER['PHP_SELF'] . '?" + thumbs[pos] +  "&twg_rot=90' . $twg_standalonejs . '";
							document.getElementById("twg_rotright").href = "' . $_SERVER['PHP_SELF'] . '?" + thumbs[pos] +  "&twg_rot=270' . $twg_standalonejs . '";';
    } 
    if ($show_login) {
        if ($login == 'TRUE') {
            echo 'document.getElementById("i_caption").href = "' . $install_dir . 'i_frames/i_titel.php?" + thumbs[pos] +  "' . $twg_standalonejs . '";
									document.getElementById("logoutlink").href = "' . $install_dir . 'i_frames/i_login.php?" + thumbs[pos] +  "&twg_logout=true' . $twg_standalone . '";
	';
        } else {
            echo 'document.getElementById("loginlink").href = "' . $install_dir . 'i_frames/i_login.php?" + thumbs[pos] +  "' . $twg_standalonejs . '";';
        } 
    } 
    echo 'reload=0;
      document.title = "' . $browser_title_prefix . ' - " + newCaption;
      if (pos==0) { 
         document.getElementById("backbutton").style.visibility = "hidden";
      } else {
         document.getElementById("backbutton").style.visibility = "visible";
      }
      if (pos >= ' . (count($imagelist)-1) . ') { 
			    document.getElementById("nextbutton").style.visibility = "hidden";
			} else {
					 if (pos > ' . ($stopgal-1) . ') { 
							 window.location="' . $hrefnextjs . '"; return;
						} else {
				    	 document.getElementById("nextbutton").style.visibility = "visible";
						}
      }
		  loadedCaption = false;
		  loadedComment = false;
		  loadedView = false;
		  loadedDirect = false;
		  loadedRating = false;
	    ready = true;
   } else {
      if (reload>50) {
        window.location = "' . $_SERVER['PHP_SELF'] . '?" + thumbs[pos] + "' . $twg_standalonejs . '";
      }
      window.setTimeout("changeContentWaitLast()",500);
      return;
   } 
}

window.setTimeout("centerGal()",200);
</script>';
} 

function php_to_all_html_chars($data)
{
    $e = get_html_translation_table (HTML_ENTITIES);
    $data = strtr($data, $e);
    return $data;
   //  return str_replace(" ", "&nbsp;", $data);
} 

function createFullscreenControl($twg_album, $image)
{
    global $lang_loading;
    global $install_dir;
    global $twg_standalonejs;
    global $lang_stop_slideshow;
    global $lang_start_slideshow;
    global $browser_title_prefix;
    global $show_caption_at_maximized_view;
    global $twg_slideshow_time;
    global $lang_of;

    $imagelist = get_image_list($twg_album);
    $act_nr = get_image_number($twg_album, $image);
    $album_enc = urlencode($twg_album);
    for($current = 0, $i = 0; $i < count($imagelist); $i++) {
        if (urldecode($imagelist[$i]) == urldecode($image)) {
            $current = $i;
        } 
    } 
    // now we create the Array with the imagesources we have to replace!
    echo '<script type="text/javascript">';
    echo 'var thumbs=new Array();';
    $sum = 0;
    for($i = 0; $i < count($imagelist) ; $i++) {
        echo "thumbs[" . $i . "] = 'twg_album=" . urlencode($twg_album) . "&twg_show=" . $imagelist[$i] . $twg_standalonejs . "';\n";
    } 

    echo '</script>'; 
    // we insert the script here because otherwise the function os not known sometimes
    echo '<script type="text/javaScript" src="./js/twg_xhconn.js"></script>';
    echo '<script type="text/javascript">
var lastpos = ' . $current . ';
var endpos = ' . count($imagelist) . ';
var actpos = 0;
var ready = true;

var reload = 0;
var img = new Array();
var pospreloaded = 0;

function load_img(srcnum, type) 
{ 
   //if (img!=0) 
   //  delete img; /* altes Bild entsorgen */ 
   if (img[srcnum]) {
      // nothing right now
   } else {
     img[srcnum]=new Image(); /* neues Bild-Objekt anlegen */ 
     img[srcnum].src="' . $install_dir . 'image.php?" + thumbs[srcnum] + type /* Bild laden lassen . + "&id=" + resizetimestamp  */
   }
}

function changeContent(pos) {
	if (ready != true) {
	  return;
	}
	reload=0;
	nextpos = lastpos + pos;
	if ((nextpos >= endpos) && (slideshow == true)) {
		nextpos = 0;
	}
	changeContentWait(nextpos);
}

var newCaption = "old";
var loadedCaption = false;

var newView = "old";
var loadedView = false;

function load_caption(pos) {
var myConn = new XHConn();
if (!myConn) alert("XMLHTTP not available. Try a newer/better browser.");
var fnWhenDone = function (oXML) { newCaption = oXML.responseText; loadedCaption = true; };
myConn.connect("' . $install_dir . 'image.php?" + thumbs[pos] + "&twg_xmlhttp=b", fnWhenDone);
}

function load_view(pos) {
var myConn3 = new XHConn();
var fnWhenDoneV = function (voXML) { newView = voXML.responseText; loadedView = true; };
myConn3.connect("' . $install_dir . 'image.php?" +thumbs[pos] + "&twg_xmlhttp=v", fnWhenDoneV);
}


function changeContentWaitLast() {
   changeContentWait(actpos);
}

function changeContentWait(poss) { 
 pos=parseInt(poss);
 
 if (pos < 0) {
    lastpos = 0;
    if (pos < -90000) {
      pos=lastpos;
    } else {
      return;
    }
   } else if (pos >= endpos) {
    lastpos = endpos-1;
    if (pos > 90000) {
	       pos=lastpos;
	     } else {
	       return;
    }
 } 
 
 actpos = pos;
 ready = false;
 
 var box = document.getElementById("twg_contol_text");
    if (reload == 0) {
      load_view(pos); // no view - only counting !
      load_img(pos, "&twg_type=fullscreen");
      load_caption(pos); 
    }
    box.innerHTML = "' . $lang_loading . '";
      
    reload++;
    if (pos==0) { 
			 document.getElementById("backbutton").style.visibility = "hidden";
		} else {
				document.getElementById("backbutton").style.visibility = "";
		}
		if (pos >= endpos-1) { 
				document.getElementById("nextbutton").style.visibility = "hidden";
		} else {
				document.getElementById("nextbutton").style.visibility = "";
		}
    
     if (img[actpos].complete && loadedCaption) {
          document.images.defaultslide.src=img[actpos].src;
          box.innerHTML = "Bild " + (pos+1) + " ' . $lang_of . ' " + (endpos) ;';
    if ($show_caption_at_maximized_view) {
        echo 'document.getElementById("twg_fullscreencaption").innerHTML = newCaption;
            document.title = "' . $browser_title_prefix . ' - " + newCaption;';
    } 
    echo 'if (lastpos<pos) {
            load_img(pos+1, "&twg_type=fullscreen");  
          } else {
            load_img(pos-1, "&twg_type=fullscreen");  
          }
          lastpos = pos;
          ready=true;
          loadedCaption = false;
          setTimer(4);
               
    } else {
      if (reload==50) { // 10 sekunden - reload
        window.location = "' . $_SERVER['PHP_SELF'] . '?" + thumbs[actpos] + "' . $twg_standalonejs . '";
      }
      window.setTimeout("changeContentWaitLast()",200);
      return;    
    }
} 

var slideshow=false;

function startSlideshow() {
  newHtml = "<img class=\"twg_hand\" id=\"slideshowbutton\" alt=\"' . $lang_stop_slideshow . '\" title=\"' . $lang_stop_slideshow . '\" onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage(\'slideshowbutton\',\'\',\'' . $install_dir . 'buttons/menu_stop_over.gif\',1)\" onclick=\"javascript:stopSlideshow();\" src=\"' . $install_dir . 'buttons/menu_stop.gif\" />";
  document.getElementById("slideshowarea").innerHTML = newHtml; 
  slideshow=true;
  runSlideshow();
}


function runSlideshow() {
  if (slideshow != false) {
      changeContent(1);
  		window.setTimeout("runSlideshow()",' . ($twg_slideshow_time * 1000) . ');    
  }
}

function stopSlideshow() {
  newHtml = "<img class=\"twg_hand\" id=\"slideshowbutton\" alt=\"' . $lang_start_slideshow . '\" title=\"' . $lang_start_slideshow . '\" onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage(\'slideshowbutton\',\'\',\'' . $install_dir . 'buttons/menu_start_over.gif\',1)\" onclick=\"javascript:startSlideshow();\" src=\"' . $install_dir . 'buttons/menu_start.gif\" />";
	document.getElementById("slideshowarea").innerHTML = newHtml; 
  changeContent(0);
  slideshow=false;
}

function key_back() {
  changeContent(-1);
  setTimer(3);
  show_control_div();
}

function key_foreward() {
  changeContent(1);
  setTimer(3);
  show_control_div();
}

var timer = 10;
function closeControl() {
	if (timer-- < 0) {
		hide_control_div();
	}
window.setTimeout("closeControl()",500);
}

function setTimer(time) {
  timer=time;
}

closeControl();
load_img(lastpos+1, "&twg_type=fullscreen"); 

function closeFullscreen() {
  window.location = "' . $_SERVER['PHP_SELF'] . '?" + thumbs[lastpos] +  "&twg_zoom=FALSE' . $twg_standalonejs . '";';
    echo '}</script>';
} 

?>