<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle - based on the code of Rainer Hungershausen 

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3b
  $Date: 2005/11/01 00:38 $
**********************************************/

require dirname(__FILE__) . "/config.php";


if (file_exists("skins/" . $skin . ".php")) {
  include "skins/" . $skin . ".php";
} 


// functions like getLast, getFirst, debug, gdversion  ...
include dirname(__FILE__) . "/inc/filefunctions.inc.php";
include dirname(__FILE__) . "/inc/imagefunctions.inc.php";
// needed to check if rotation was performed !!
include dirname(__FILE__) . "/inc/mysession.inc.php";

require (dirname(__FILE__) . "/language/language_" . $default_language . ".php");

/* 
based on the code of Rainer Hungershausen - thanks for the good work.
changes:
- recognizes gd version - does much better pictures when gd lib > 2 !!
- added debuging ;)
- changed cachedir to global one !! no need to create a cache for every directory!
- simplyfied putimage function
- only resize pictures when longes sid of pic > $small_pic_size
- twg_slideshow ...
- almost everything :) 
*/

$modifyheader = false;

/* set some vars */
// rotation control
if (isset($_GET['twg_rot'])) {
    $twg_rot = ($_GET['twg_rot'] >= 0 ? $_GET['twg_rot'] : -1);
    $twg_rot = $twg_rot >= 360 ? 0 : $twg_rot;
} else
    $twg_rot = -1; 
// twg_album
if (isset($_GET['twg_album'])) {
    $twg_album = replace_plus($_GET['twg_album']);
    $twg_album = urldecode(urldecode($twg_album)); // the double decode is because of some servers where this is needed!
    $twg_album = restore_plus($twg_album);
    $album_url = $twg_album;
    $album_enc = urlencode($twg_album);
} else {
    $twg_album = false;
    $album_url = false;
} 
// image
if (isset($_GET['twg_show'])) {
    $image = replace_plus(ereg_replace("/", "", $_GET['twg_show']));
    $image = urldecode($image); // the double decode is because of some servers where this is needed!
    $image = str_replace("\\'", "'", $image);
    $image = restore_plus($image); 
    $image_enc = urlencode($image);
} else
    $image = false;
// type
if (isset($_GET['twg_type'])) {
    $type = $_GET['twg_type'];
} else
    $type = false; 
// comment for this picture
if (isset($_GET['twg_comment'])) {
    $comment = true;
    $ccount = $_GET['twg_comment'];
} else {
    $comment = false;
} 
// randomnr
if (isset($_GET['twg_random'])) {
    $randomimage = $_GET['twg_random'];
} else
    $randomimage = false; 
// randomimagesize
if (isset($_GET['twg_random_size'])) {
    $randomimagesize = $_GET['twg_random_size'];
} else
    $randomimagesize = $thumb_pic_size;

$beschreibungXmlHttp = false;
$commentXmlHttp = false;
$viewXmlHttp = false;
$directXmlHttp = false;
$ratingXmlHttp = false;
$browserXmlHttp = false;

if (isset($_GET['twg_xmlhttp'])) {
    if ($_GET['twg_xmlhttp'] == 'b') {
        $beschreibungXmlHttp = true;
    } 
    if ($_GET['twg_xmlhttp'] == 'c') {
        $commentXmlHttp = true;
    } 
    if ($_GET['twg_xmlhttp'] == 'v') {
        $viewXmlHttp = true;
    } 
    if ($_GET['twg_xmlhttp'] == 'd') {
        $directXmlHttp = true;
    } 
    if ($_GET['twg_xmlhttp'] == 'r') {
        $browserXmlHttp = true;
    } 
    if ($_GET['twg_xmlhttp'] == 'a') {
        $ratingXmlHttp = true;
    } 
} 
if (isset($_GET['twg_nojs'])) {
    $browserNoJS = true;
} else {
    $browserNoJS = false;
} 

if ($type != "random") {
    include dirname(__FILE__) . "/inc/readxml.inc.php";
} 

if ($beschreibungXmlHttp || $commentXmlHttp || $viewXmlHttp || $directXmlHttp || $browserXmlHttp || $browserNoJS || $ratingXmlHttp) {
    $album_enc = urlencode($twg_album);
    if ($beschreibungXmlHttp) {
        echo php_to_html_chars(getBeschreibung(urlencode($image), $werte, $index)); // the encode is a fix right now - has to be made nice sometimes !!
    } else if ($commentXmlHttp) {
         $comment_xmlhttp = str_replace("image.php", "index.php", getKommentar($image, $twg_album, $kwerte, $kindex, false));
         // debug ($comment_xmlhttp);
         echo $comment_xmlhttp;
    } else if ($viewXmlHttp) {
        echo increaseImageCount($twg_album, $image);
    } else if ($directXmlHttp) {
        echo $basedir . "/" . $twg_album . "/" . $image;
    } else if ($browserNoJS) {
        $_SESSION["twg_nojs"] = 'TRUE';
    } else if ($ratingXmlHttp) {
        $rating = substr(getVotesCount($twg_album, $image), 0, 4);
        if (round($rating) == floor($rating)) {
            $rateimage = floor($rating) . "0";
        } else {
            $rateimage = floor($rating) . "5";
        } 
        echo '<img alt="' . $rating . '" title="' . $rating . '"  src="' . $install_dir . 'buttons/s' . $rateimage . '.gif" />';
    } else if ($browserXmlHttp) { // browserhttp
        // set the resolution of the browser in the session !!
        $_SESSION["browserx"] = $_GET["browserx"] - 75;
        $_SESSION["browsery"] = $_GET["browsery"] - 75; 
        if (isset($_GET["fontscale"])) {
          $_SESSION["fontscale"] = $_GET["fontscale"];  
        }
        // debug("Erkannte Auflösung: " . $_SESSION["browserx"] . ":" . $_SESSION["browsery"]);
        // debug("Scale: " . $_SESSION["fontscale"]);
        $_SESSION["twg_XMLHTTP"] = "TRUE";
        echo "";
    } 
} else if ($twg_album != false && $image != false && $type != "png") {
    if ($double_encode_urls) { // the restore and replace is only needed for ed's server :).
        $imagepath = $basedir . "/" . $twg_album . "/" . restore_plus(urldecode(replace_plus($image)));
    } else {
        $imagepath = $basedir . "/" . $twg_album . "/" . $image;
    } 

    $remote_image = checkurl($basedir . "/" . $twg_album);

    if (file_exists($imagepath) || $remote_image) {
        $existing_rot = getRotation($twg_album, $image);
        switch ($type) {
            case "small": 
                // sideeffects are heavy - more time to fix this !
                // if ($twg_rot != -1) {
                // $twg_rot = $twg_rot + $existing_rot;
                // debug('rot:' + $twg_rot);
                // }
                $show_clipped_images_thumb = $show_clipped_images;
                $show_clipped_images = false;
                $small = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . "." . $extension_small;

                if ($remote_image) {
                    $image_full = $remote_image . encodespace($image);
                } else {
                    $image_full = $basedir . "/" . $twg_album . "/" . $image;
                } 
                if (!$remote_image) {
                    $oldsize = @getimagesize($image_full);
                } else {
                    $oldsize[0] = 9000;
                    $oldsize[1] = 9000;
                } 
                // we don't resise when picture is smaller - if it is remote we do this all the time because of the caching!!! - but we twg_rot !!
                if ((($small_pic_size >= $oldsize[0]) || $use_small_pic_size_as_height) && ($small_pic_size >= $oldsize[1]) && !file_exists($small) && $resize_only_if_too_big) {
                    if (($login == 'TRUE') && ($twg_rot >= 0)) {
                        $act_pic_size = ($oldsize[0] > $oldsize[1]) ? $oldsize[0] : $oldsize[1];
                        generatesmall($image_full, $small, $act_pic_size, $compression, $twg_rot, $basedir . "/" . $twg_album);
                    } else {
                        if ($existing_rot > 0) {
                            $twg_rot = $existing_rot;
                            $login = 'TRUE';
                            $act_pic_size = ($oldsize[0] > $oldsize[1]) ? $oldsize[0] : $oldsize[1];
                            generatesmall($image_full, $small, $act_pic_size, $compression, $twg_rot, $basedir . "/" . $twg_album);
                        } else {
                            $small = $image_full;
                        } 
                    } 
                } else

                if ((!file_exists($small)) || (($login == 'TRUE') && ($twg_rot >= 0))) {
                    if ($twg_rot == -1 && $existing_rot > 0) {
                        $twg_rot = $existing_rot;
                        $login = 'TRUE';
                    } 
                    generatesmall($image_full, $small, $small_pic_size, $compression, $twg_rot, $basedir . "/" . $twg_album);
                } 
                // we have to generate a turned thumbnail if login == true and delete the file in the twg_slideshow !!
                if (($login == 'TRUE') && ($twg_rot >= 0)) {
                    $show_clipped_images = $show_clipped_images_thumb;
                    $thumb = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . "." . $extension_thumb;
                    generatesmall($image_full, $thumb, $thumb_pic_size, $compression_thumb, $twg_rot, $basedir . "/" . $twg_album);

                    $smallslide = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . "." . $extension_slideshow; 
                    // $smallslide = replaceSonderzeichen25($smallslide);
                    if (file_exists($smallslide)) {
                        unlink($smallslide);
                    } else {
                        // debug($smallslide . ' not found when deleting thumbnail - image.php/214');
                    } 
                    // we store a file with the imagename but the following extension r090, r180, r270
                    // we need this for the fullscreen twg_slideshow do know if we have to twg_rot the original!
                    $rot = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . ".rot";
                    $rot_file = fopen($rot, 'w');
                    fputs($rot_file, $twg_rot);
                    fclose($rot_file);
                    $show_clipped_images = false;
                } 

                if (($twg_rot > 0) && ($login == 'FALSE')) {
                    puttwg_rot($small, $twg_rot);
                } else {
                    putimage($small);
                } 
                break;
            /*
            case "twg_slideshow":
                $show_clipped_images = false;
                $small = "./" . $cachedir . "/" . str_replace("/", "_", $twg_album) . "_" . $image . "." . $extension_slideshow;
                $small_cache = "./" . $cachedir . "/" . str_replace("/", "_", $twg_album) . "_" . $image . "." . $extension_small;

                if ($remote_image) {
                    $image_full = $remote_image . encodespace($image);
                } else {
                    $image_full = $basedir . "/" . $twg_album . "/" . $image;
                } 

                if (!file_exists($small)) {
                    $rot = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . ".rot";
                    if (file_exists($rot)) {
                        $rot_file = fopen($rot, 'r');
                        $twg_rot = fgets($rot_file, 30);
                        fclose($rot_file);
                    } 
                    generatetwg_slideshow($image_full, $small, $small_pic_size, $compression, $small_cache, $twg_rot, $basedir . "/" . $twg_album);
                } 
                // this is the image we are pasting into this other picture
                putimage($small);
                break;
            */
            case "twg_slideshowgenerate": 
                // we generate always 3 images in advance from this twg_album !! starting with the image which is given !
                // the twg_slideshows send a new generation event every 3 pictures - therefore the galerie does not
                // has to wait until all pictures are generated.
                $show_clipped_images = false; 
                // $twg_album = urldecode($twg_album);
                $image = urldecode($image);
                $numgenpic = 0;
                $imagelist = get_image_list($twg_album);

                $imagelistSize = count($imagelist);
                $image_nr = get_image_number($twg_album, $image);
                for($i = 0; $i < $imagelistSize; $i++) {
                    $smallslide = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . urldecode($imagelist[$image_nr])) . "." . $extension_slideshow;
                    $small_cache = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . urldecode($imagelist[$image_nr])) . "." . $extension_small;

                    $smallslide = replaceSonderzeichen25($smallslide);
                    if ($remote_image) {
                        $image_full = $remote_image . encodespace(urldecode($imagelist[$image_nr]));
                    } else {
                        $image_full = $basedir . "/" . $twg_album . "/" . urldecode($imagelist[$image_nr]);
                    } 
                    // $image = $basedir . "/" . $twg_album . "/" . urldecode($imagelist[$image_nr]);
                    // wir müssen evtl. 2 bilder generieren - weil javascript twg_slideshow und hml twg_slideshow andere Bildernamen brauchen
                    if (!file_exists($smallslide)) {
                        $rot = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . ".rot";
                        if (file_exists($rot)) {
                            $rot_file = fopen($rot, 'r');
                            $twg_rot = fgets($rot_file, 30);
                            fclose($rot_file);
                        } 
                        generatetwg_slideshow($image_full, $smallslide, $small_pic_size, $compression, $small_cache, $twg_rot, $basedir . "/" . $twg_album);
                        if ($numgenpic++ > 3) {
                            return;
                        } 
                    } 
                    if (++$image_nr >= $imagelistSize) {
                        $image_nr = 0;
                    } 
                } 
                break;
            case "thumb":

                $thumb = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . "." . $extension_thumb;
                if ($remote_image) {
                    $imagefull = $remote_image . encodespace($image);
                } else {
                    $imagefull = $basedir . "/" . $twg_album . "/" . $image;
                } 
                if (file_exists($thumb)) {
                    putimage($thumb);
                } else {
                    if ($twg_rot == -1) {
                        $rot = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . ".rot";
                        if (file_exists($rot)) {
                            $rot_file = fopen($rot, 'r');
                            $twg_rot = fgets($rot_file, 30);
                            fclose($rot_file);
                            $login = 'TRUE';
                        } 
                    } 
                    if (generatesmall($imagefull, $thumb, $thumb_pic_size, $compression_thumb, $twg_rot, $basedir . "/" . $twg_album))
                        putimage($thumb);
                } 
                break;
            case "full": // fullscreen for slideshow - size is stored in the session ! // check for twg_rotd images !!
                if ($remote_image) {
                    $image_full = $remote_image . encodespace($image);
                } else {
                    $image_full = $basedir . "/" . $twg_album . "/" . $image;
                } 
                $rot = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . ".rot";
                if (file_exists($rot)) {
                    $rot_file = fopen($rot, 'r');
                    $twg_rot = fgets($rot_file, 30);
                    fclose($rot_file);
                } else {
                    $twg_rot = 0;
                } 

                if ($show_image_rating && ($image_rating_position != "menu")) {
                    $browsery -= 20;
                } 
                $size = ($browserx > $browsery) ? ($browsery-40) : ($browserx); // we subtract 55 because this is the twg_offset like in the twg_slideshow_full (line 87)
                generatefull($image_full, $size, $compression, $twg_rot, $basedir . "/" . $twg_album);
                if ($show_image_rating && ($image_rating_position != "menu")) {
                    $browsery += 20;
                } 
                break;
            case "fullscreen": // fullscreen - size is stored in the session ! // check for twg_rotd images !!
                if ($remote_image) {
                    $image_full = $remote_image . encodespace($image);
                } else {
                    $image_full = $basedir . "/" . $twg_album . "/" . $image;
                } 
                $rot = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . ".rot";
                if (file_exists($rot)) {
                    $rot_file = fopen($rot, 'r');
                    $twg_rot = fgets($rot_file, 30);
                    fclose($rot_file);
                } else {
                    $twg_rot = 0;
                } 

                $browserx += 75;
                $browsery += 75;
                $size = ($browserx > $browsery) ? ($browsery) : ($browserx); 
                // $size -= 6;
                generatefull($image_full, $size, $compression-5, $twg_rot, $basedir . "/" . $twg_album);
                $browserx -= 75;
                $browsery -= 75;

                break;
            default: // original image
                $show_clipped_images = false;
                if ($remote_image) {
                    $image_full = $remote_image . encodespace($image);
                } else {
                    $image_full = $basedir . "/" . $twg_album . "/" . $image;
                } 
                if ($enable_download_counter) {
                    increaseDownloadCount($twg_album , $image);
                } 
                if ($print_text_original || $print_watermark_original) {
                    putwatermarkimage($image_full, $basedir . "/" . $twg_album);
                } else {
                    $modifyheader = true;
                    putimage($image_full);
                } 
                break;
        } 
    } else {
        debug("'" . $imagepath . "' does not exist");
    } 
} else if ($type == "counterimage") {
    $filename = $counterdir . "/user_log.txt";
    generatecounterimage($filename);
} else if ($type == "png") {
    if ($double_encode_urls) { // the restore and replace is only needed for ed's server :).
        $filename = $basedir . "/" . $twg_album . "/" . restore_plus(urldecode(replace_plus($image)));
    } else {
        $filename = $basedir . "/" . $twg_album . "/" . $image;
    } 
    putpngimage($filename);
} else if ($type == "random") {
    $use_small_pic_size_as_height=false;
    if ($twg_album) {
        // we split the albums and pick an random one ... dividor = |
        $teile = explode("|", $twg_album);
        $key = array_rand($teile);
        $twg_album = $teile[$key];
    } 
    // check if private - if yes return the private.gif!
    if ($twg_album) {
        if (!file_exists($basedir . "/" . $twg_album)) {
            $path = "buttons/noalbum.jpg";
            return generaterandom($path, $randomimagesize, $compression, $twg_rot, $basedir . "/" . $twg_album);
        } 
        $privatefilename = $basedir . "/" . $twg_album . "/" . $password_file;
    } else {
        $path = "buttons/noalbum.jpg";
        return generaterandom($path, $randomimagesize, $compression, $twg_rot , $basedir . "/" . $twg_album);
    } 
    if (file_exists($privatefilename)) {
        $path = "buttons/private.jpg";
        return generaterandom($path, $randomimagesize, $compression, $twg_rot, $basedir . "/" . $twg_album);
    } 

    $imagelist = get_image_list($twg_album);
    if (!$imagelist) {
        return "not found";
    } 
    $image = $imagelist[array_rand($imagelist)]; 
    // storing this image in the session !!
    $_SESSION['twg_random' . $randomimage] = urldecode($image); 
    // and the album because we can have multiple sinc 1.3
    $_SESSION['twg_random_album' . $randomimage] = $twg_album;
    $remote_image = checkurl($basedir . "/" . $twg_album);
    if ($remote_image) {
        $path = $remote_image . encodespace(urldecode($image));
    } else {
        $path = $basedir . "/" . $twg_album . "/" . urldecode($image);
    } 
    /*
		$thumb = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . "." . $extension_thumb; 
		if (file_exists($thumb) && ($randomimagesize == $thumb_pic_size)) {
			  putimage($thumb);
		} else {
		*/
    $rot = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . ".rot";
    if (file_exists($rot)) {
        $rot_file = fopen($rot, 'r');
        $twg_rot = fgets($rot_file, 30);
        fclose($rot_file);
    } else {
        $twg_rot = 0;
    } 
    generaterandom($path, $randomimagesize, $compression, $twg_rot, $basedir . "/" . $twg_album); 
    // }
} 

?>
