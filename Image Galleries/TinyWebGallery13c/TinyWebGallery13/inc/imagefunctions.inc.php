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
function putimage($image)
{
    global $open_download_in_browser;
    global $modifyheader; 
    // set Documet Type
    header("Content-type: image/jpeg");
    $filename = basename($image);
    if (!$open_download_in_browser && $modifyheader) {
        header("Content-Disposition: attachment; filename=\"" . $filename . "\";\n");
    } 
    readfile($image);
} 

function putpngimage($image)
{ 
    // set Documet Type
    header("Content-type: image/png");
    readfile($image);
} 

function putwatermarkimage($image, $dir)
{
    global $font;
    global $fontsize_original;
    global $text;
    global $textcolor_R;
    global $textcolor_G;
    global $textcolor_B;
    global $watermark_big;
    global $open_download_in_browser;
    global $print_watermark_original;
    global $print_text_original;

    header("Content-type: image/jpeg");
    $filename = basename($image);
    if (!$open_download_in_browser) {
        header("Content-Disposition: attachment; filename=\"" . $filename . "\";\n");
    } 
    $oldsize = getImageSize($image);
    $height = $oldsize[1];
    $src = imagecreatefromjpeg($image);
    $dst = imagecreatetruecolor($oldsize[0], $oldsize[1]);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $oldsize[0], $oldsize[1], $oldsize[0], $oldsize[1]);
    if ($print_text_original) {
        $color = imagecolorallocate($dst, $textcolor_R, $textcolor_G, $textcolor_B);
        $text = getFileContent($dir . "/watermark.txt" , $text);
        imagettftext($dst, $fontsize_original, 0, 7, $height-7, $color, $font, $text);
    } 
    if ($print_watermark_original) {
        if (file_exists($dir . "/watermark_big.png")) {
            $watermark_big = $dir . "/watermark_big.png";
        } 
        watermark($dst, $watermark_big , $oldsize[0], $oldsize[1], $oldsize[2]);
    } 
    imagejpeg($dst, "", 100);
} 

function puttwg_rot($image, $angle)
{
    global $compression; 
    // set Documet Type
    header("Content-type: image/jpeg");
    $oldsize = getImageSize($image);
    $src = imagecreatefromjpeg($image);
    $dst = imagecreatetruecolor($oldsize[0], $oldsize[1]);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $oldsize[0], $oldsize[1], $oldsize[0], $oldsize[1]);
    $twg_rot = imagerotate($dst, $angle, 0);
    if (!imagejpeg($twg_rot, "", $compression)) {
        imagejpeg($dst, "", $compression);
    } 
} 

function generatesmall($image, $small, $size, $compression, $twg_rot, $dir)
{
    global $login;
    global $comment;
    global $show_clipped_images;
    global $small_pic_size;
    global $print_text;
    global $font;
    global $fontsize;
    global $text;
    global $textcolor_R;
    global $textcolor_G;
    global $textcolor_B;
    global $use_small_pic_size_as_height;
    global $small_pic_size;
    global $thumb_pic_size;
    global $comment_corner_size;
    global $comment_corner_backcolor_R;
    global $comment_corner_backcolor_G;
    global $comment_corner_backcolor_B;
    global $print_watermark;
    global $watermark_small;
    global $maxXSize;

    $srcx = 0; //  for clipped images to center them!
    $srcy = 0;
    if (file_exists($image) || substr($image, 4, 3) == "://") {
        $oldsize = getimagesize($image);

        $oldsizex = $oldsize[0];
        $oldsizey = $oldsize[1];
        if (!$show_clipped_images) {
            if ($oldsizex > $oldsizey) { // querformat
                if (($use_small_pic_size_as_height) && ($size == $small_pic_size)) { // horizontals are bigger then verticals images
                    if (($twg_rot == 90 || $twg_rot == 270) && ($login == 'TRUE')) {
                        $width = $size;
                        $height = ($width / $oldsizex) * $oldsizey;
                    } else {
                        $height = $size;
                        $width = $height / $oldsizey * $oldsizex;
                        if ($width > $maxXSize && (($oldsizex / $oldsizey) > 1.5)) { // we fix images which are too wide! (factor 1.5!)
                            $width = $maxXSize;
                            $height = ($width / $oldsizex) * $oldsizey;
                        } 
                    } 
                } else { // this keeps the dimension between horzonal and vertical
                    $width = $size;
                    $height = ($width / $oldsizex) * $oldsizey;
                } 
            } else { // hochformat
                if (($use_small_pic_size_as_height) && ($size == $small_pic_size)) { // horizontals are bigger then verticals images
                    if (($twg_rot == 90 || $twg_rot == 270) && ($login == 'TRUE')) {
                        $height = ($size * $oldsizey) / $oldsizex;
                        $width = $size;
                    } else {
                        $height = $size;
                        $width = ($height / $oldsizey) * $oldsizex;
                    } 
                } else { // this keeps the dimension between horzonal an vertical
                    $height = $size;
                    $width = ($height / $oldsizey) * $oldsizex;
                } 
            } 
        } else {
            $width = $size;
            $height = $size;
            if ($oldsizex > $oldsizey) { // querformat
                $srcx = ($oldsizex - $oldsizey) / 2;
                $oldsizex = $oldsizey;
            } else {
                // $srcy =  ($oldsizey - $oldsizex) / 2;
                $oldsizey = $oldsizex;
            } 
        } 
        $src = imagecreatefromjpeg($image);
        if (gd_version() >= 2) {
            $dst = ImageCreateTrueColor($width, $height);
        } else {
            $dst = imagecreate ($width, $height);
            imageJPEG($dst, $small . '256');
            $dst = @imagecreatefromjpeg($small . '256');
        } 
        if (gd_version() >= 2) {
            // center clipped images ! - but only the vertical ones - horizontal are mainly  images of people and there the upper part should be shown
            imagecopyresampled($dst, $src, 0, 0, $srcx, $srcy , $width, $height, $oldsizex, $oldsizey);
        } else {
            ImageCopyResized($dst, $src, 0, 0, 0, 0, $width, $height, $oldsizex, $oldsizey);
        } 

        if ($comment && ($size == $thumb_pic_size)) {
            // set up array of points for polygon
            $values = array($width - $comment_corner_size, 0, $width, 0, $width , $comment_corner_size);
            $white = imagecolorallocate($dst, $comment_corner_backcolor_R, $comment_corner_backcolor_G, $comment_corner_backcolor_B); 
            // draw a polygon
            imagefilledpolygon($dst, $values, 3, $white);
        } 

        if (($twg_rot > 0) && ($login == 'TRUE')) {
            $dst = imagerotate($dst, $twg_rot, 0);
        } 

        if (($size == $small_pic_size) && $print_text) {
            $text = getFileContent($dir . "/watermark.txt" , $text);
            $color = imagecolorallocate($dst, $textcolor_R, $textcolor_G, $textcolor_B);
            if ($twg_rot == 90 || $twg_rot == 270) {
                imagettftext($dst, $fontsize, 0, 7, $width-7, $color, $font, $text);
            } else {
                imagettftext($dst, $fontsize, 0, 7, $height-7, $color, $font, $text);
            } 
        } 

        if (($size == $small_pic_size) && $print_watermark) {
            if (file_exists($dir . "/watermark.png")) {
                $watermark_small = $dir . "/watermark.png";
            } 
            if ($twg_rot == 90 || $twg_rot == 270) {
                watermark($dst, $watermark_small , $height, $width, $oldsize[2]);
            } else {
                watermark($dst, $watermark_small , $width, $height, $oldsize[2]);
            } 
        } 

        if (imagejpeg($dst, $small, $compression)) {
            imagedestroy($dst);
            return true;
        } else {
            debug('cannot save: ' . $small);
            imagedestroy($src);
            return false;
        } 
    } else
        return false;
} 

function generatefull($image, $size, $compression, $twg_rot, $dir)
{
    if (file_exists($image) || substr($image, 4, 3) == "://") {
        $oldsize = getimagesize($image);
        $oldsizex = $oldsize[0];
        $oldsizey = $oldsize[1];
        global $print_text;
        global $font;
        global $fontsize;
        global $text;
        global $textcolor_R;
        global $textcolor_G;
        global $textcolor_B;
        global $print_watermark;
        global $watermark_small;
        global $browserx;

        if ($oldsizex > $oldsizey) { // querformat
            if (($twg_rot == 0) || ($twg_rot == 180)) {
                $width = ($oldsizex / $oldsizey) * $size;
                $height = $size;
                if ($width > $browserx) {
                    $width = $browserx;
                    $height = $width / $oldsizex * $oldsizey;
                } 
            } else {
                $width = $size;
                $height = $size / $oldsizex * $oldsizey;
            } 
        } else {
            if (($twg_rot == 0) || ($twg_rot == 180)) {
                $height = $size;
                $width = ($height / $oldsizey) * $oldsizex;
            } else {
                $height = ($oldsizex / $oldsizey) * $size;
                $width = $size;
            } 
        } 
        $src = imagecreatefromjpeg($image);
        if (gd_version() >= 2) {
            $dst = ImageCreateTrueColor($width, $height);
        } else {
            $dst = imagecreate ($width, $height);
            imageJPEG($dst, $small . '256');
            $dst = @imagecreatefromjpeg($small . '256');
        } 
        if (gd_version() >= 2) {
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $oldsizex, $oldsizey);
        } else {
            ImageCopyResized($dst, $src, 0, 0, 0, 0, $width, $height, $oldsizex, $oldsizey);
        } 

        if ($twg_rot > 0) {
            $dst = imagerotate($dst, $twg_rot, 0);
        } 

        if ($print_text && ($size > 300)) {
            $text = getFileContent($dir . "/watermark.txt" , $text);
            $color = imagecolorallocate($dst, $textcolor_R, $textcolor_G, $textcolor_B);
            if ($twg_rot == 90 || $twg_rot == 270) {
                imagettftext($dst, $fontsize, 0, 7, $width-7, $color, $font, $text);
            } else {
                imagettftext($dst, $fontsize, 0, 7, $height-7, $color, $font, $text);
            } 
        } 

        if ($print_watermark && ($size > 300)) {
            // todo - look for a local small watermark
            if (file_exists($dir . "/watermark.png")) {
                $watermark_small = $dir . "/watermark.png";
            } 
            if ($twg_rot == 90 || $twg_rot == 270) {
                watermark($dst, $watermark_small , $height, $width, $oldsize[2]);
            } else {
                watermark($dst, $watermark_small , $width, $height, $oldsize[2]);
            } 
        } 
        // set Documet Type
        header("Content-type: image/jpeg");
        if (imagejpeg($dst, "", $compression)) {
            imagedestroy($dst);
            return true;
        } else {
            debug('cannot save: ' . $image);
            imagedestroy($src);
            return false;
        } 
    } else
        return false;
} 

/*
almost duplicate of generatefull - optimize later! 
*/
function generaterandom($image, $size, $compression, $twg_rot, $dir)
{
    if (file_exists($image) || substr($image, 4, 3) == "://") {
        $oldsize = getimagesize($image);
        $oldsizex = $oldsize[0];
        $oldsizey = $oldsize[1];
        global $print_text;
        global $font;
        global $fontsize;
        global $text;
        global $textcolor_R;
        global $textcolor_G;
        global $textcolor_B;
        global $print_watermark;
        global $watermark_small;
        global $browserx;

        if ($oldsizex > $oldsizey) { // querformat
            if (($twg_rot == 0) || ($twg_rot == 180)) {
                $width = $size;
                $height = $size / $oldsizex * $oldsizey;
            } else {
                $width = ($oldsizex / $oldsizey) * $size;
                $height = $size;
            } 
        } else {
            if (($twg_rot == 0) || ($twg_rot == 180)) {
                $height = $size;
                $width = ($height / $oldsizey) * $oldsizex;
            } else {
                $height = ($oldsizex / $oldsizey) * $size;
                $width = $size;
                
            } 
        } 
        $src = imagecreatefromjpeg($image);
        if (gd_version() >= 2) {
            $dst = ImageCreateTrueColor($width, $height);
        } else {
            $dst = imagecreate ($width, $height);
            imageJPEG($dst, $small . '256');
            $dst = @imagecreatefromjpeg($small . '256');
        } 
        if (gd_version() >= 2) {
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $oldsizex, $oldsizey);
        } else {
            ImageCopyResized($dst, $src, 0, 0, 0, 0, $width, $height, $oldsizex, $oldsizey);
        } 

        if ($twg_rot > 0) {
            $dst = imagerotate($dst, $twg_rot, 0);
        } 

        if ($print_text && ($size > 300)) {
            $text = getFileContent($dir . "/watermark.txt" , $text);
            $color = imagecolorallocate($dst, $textcolor_R, $textcolor_G, $textcolor_B);
            if ($twg_rot == 90 || $twg_rot == 270) {
                imagettftext($dst, $fontsize, 0, 7, $width-7, $color, $font, $text);
            } else {
                imagettftext($dst, $fontsize, 0, 7, $height-7, $color, $font, $text);
            } 
        } 

        if ($print_watermark && ($size > 300)) {
            // todo - look for a local small watermark
            if (file_exists($dir . "/watermark.png")) {
                $watermark_small = $dir . "/watermark.png";
            } 
            if ($twg_rot == 90 || $twg_rot == 270) {
                watermark($dst, $watermark_small , $height, $width, $oldsize[2]);
            } else {
                watermark($dst, $watermark_small , $width, $height, $oldsize[2]);
            } 
        } 
        // set Documet Type
        header("Content-type: image/jpeg");
        if (imagejpeg($dst, "", $compression)) {
            imagedestroy($dst);
            return true;
        } else {
            debug('cannot save: ' . $image);
            imagedestroy($src);
            return false;
        } 
    } else
        return false;
} 



function generatetwg_slideshow($image, $small, $size, $compression, $small_cache, $twg_rot, $dir)
{
    global $small_pic_size;
    global $slideshow_backcolor_R;
    global $slideshow_backcolor_G;
    global $slideshow_backcolor_B;
    global $print_text;
    global $font;
    global $fontsize;
    global $text;
    global $textcolor_R;
    global $textcolor_G;
    global $textcolor_B;
    global $use_small_pic_size_as_height;
    global $resize_only_if_too_big;

    $resize = true;

    if (file_exists($small_cache)) {
        $image = $small_cache;
    } else {
        // we generate the small one first - is needed sometimes anyway :).
        if ($compression < 90) {
            $compression += 5;
        } 
        // we check if the size is ok - if the image is too small ...
        $oldsize = getimagesize($image);
        $resize = (!((($small_pic_size >= $oldsize[0]) || $use_small_pic_size_as_height) && ($small_pic_size >= $oldsize[1]) && $resize_only_if_too_big));
				//if (($small_pic_size >= $oldsize[0]) || $use_small_pic_size_as_height) && $resize_only_if_too_big) {
				//	$resize = false;
				// }

        if ($resize) {
            generatesmall($image, $small_cache, $size, $compression, $twg_rot, $dir);
            $image = $small_cache;
        } 
    } 
    $maxwidth = ceil($small_pic_size * 1.35); // this is the maximum width we show !! the factor has to be changed in the twg_slide_typetwg_show as well!! 
    if (file_exists($image) || substr($image, 4, 3) == "://") {
        $oldsize = getimagesize($image);

        if ($use_small_pic_size_as_height) {
            $pic_size_x = $maxwidth;
        } else {
            $pic_size_x = $small_pic_size;
        } 
        $pic_size_y = $small_pic_size;
        if ($oldsize[0] > $oldsize[1]) { // querformat
            if ($use_small_pic_size_as_height) {
                if ($resize_only_if_too_big) {
                    if ($oldsize[0] <= $small_pic_size) {
                        $width = $oldsize[0];
                        $height = $oldsize[1];
                    } else {
                        if (($oldsize[0] / $oldsize[1]) <= 1.35) { // normal image where the width will fit into ou height!
                            $width = ($size / $oldsize[1]) * $oldsize[0];
                            $height = $size;
                        } else { // panorama
                            $width = $maxwidth;
                            $height = ($maxwidth / $oldsize[0]) * $oldsize[1];
                        } 
                    } 
                } else if (($oldsize[0] / $oldsize[1]) <= 1.35) { // normal image where the width will fit into ou height!
                    $width = ($size / $oldsize[1]) * $oldsize[0];
                    $height = $size;
                } else { // panorama
                    $width = $maxwidth;
                    $height = ($maxwidth / $oldsize[0]) * $oldsize[1];
                } 
            } else if ($resize_only_if_too_big) {
                // we check if we have to resize at all!
                if (($oldsize[0] <= $small_pic_size) && ($oldsize[1] <= $small_pic_size)) {
                    $width = $oldsize[0];
                    $height = $oldsize[1];
                } else {
                    $width = $size;
                    $height = ($width / $oldsize[0]) * $oldsize[1];
                } 
            } else {
                $width = $size;
                $height = ($width / $oldsize[0]) * $oldsize[1];
            } 
        } else if ($resize_only_if_too_big) {
            // we check if we have to resize at all!
            if (($oldsize[0] <= $small_pic_size) && ($oldsize[1] <= $small_pic_size)) {
                $width = $oldsize[0];
                $height = $oldsize[1];
            } else {
                $height = $size;
                $width = ($height / $oldsize[1]) * $oldsize[0];
            } 
        } else {
            $height = $size;
            $width = ($height / $oldsize[1]) * $oldsize[0];
        } 

        $topleft_x = ($pic_size_x - $width) / 2;
        $topleft_y = ($pic_size_y - $height) / 2;
        $src = imagecreatefromjpeg($image);
        $dst = ImageCreateTrueColor($pic_size_x, $pic_size_y);
        $near_white = imageColorClosest($dst, $slideshow_backcolor_R, $slideshow_backcolor_G, $slideshow_backcolor_B);
        imagefilledrectangle ($dst, 0 , 0, $pic_size_x, $pic_size_y, $near_white);
        imagecopyresampled($dst, $src, $topleft_x, $topleft_y, 0, 0, $width, $height, $oldsize[0], $oldsize[1]);
        $near_black = imageColorClosest($dst, 0, 0, 0);
        imagerectangle($dst, $topleft_x , $topleft_y , $topleft_x + $width - 1 , $topleft_y + $height - 1 , $near_black);

        if (imagejpeg($dst, $small, $compression)) {
            imagedestroy($dst);
            return true;
        } else {
            imagedestroy($src);
            return false;
        } 
    } else
        return false;
} 
// optimize the small ones as well !!
function replaceSonderzeichen25($name)
{ 
    // $name = str_replace("%25", "%", $name);
    // $name = str_replace("%2B", "+", $name);
    return $name;
} 

function php_to_html_chars($data)
{
    $e = get_html_translation_table (HTML_ENTITIES);
    unset($e["<"]);
    unset($e[">"]);
    unset($e["&"]);
    return replacesmilies(strtr($data, $e));
} 

function watermark($dst, $watermark, $width, $heigth, $info)
{ 
    // Michael Müller, 05.03.2004 17:05, www.php4u.net
    // Positionen:
    // 1 oben links
    // 2 oben mittig
    // 3 oben rechts
    // 4 Mitte links
    // 5 Mitte
    // 6 Mitte rechts
    // 7 unten links
    // 8 unten mittig
    // 9 unten rechts
    // erlaubt sind png und jpeg
    global $position;
    global $transparency;
    global $t_x;
    global $t_y;

    $infos_img[0] = $width;
    $infos_img[1] = $heigth;
    $infos_img[2] = $info;

    if ($position < 1 || $position > 9) {
        debug("Wrong position of the watermark - image is not created!");
        return false;
    } 
    if (!file_exists($watermark)) {
        debug("Watermark not found - image is not created!");
        return false;
    } 
    $infos_wat = getimagesize($watermark);
    if (!in_array($infos_img[2], array(2, 3)) || !in_array($infos_wat[2], array(2, 3))) {
        debug("Wrong type of the watermark - image is not created!");
        return false;
    } 
    if ($infos_img[0] < $infos_wat[0] || $infos_img[1] < $infos_wat[1]) {
        debug("watermark is too big - image is not created!");
        return false;
    } 
    if ($infos_wat[0] < $t_x || $infos_wat[1] < $t_y) {
        debug("watermark is too big - image is not created!");
        return false;
    } 
    $transparency = 100 - $transparency;
    if ($transparency < 0 || $transparency > 100) {
        debug("transparency is out of range - image is not created!");
        return false;
    } 
    // Position x
    switch (($position-1) % 3) {
        case 0:
            $pos_x = 0;
            break;
        case 1:
            $pos_x = round(($infos_img[0] - $infos_wat[0]) / 2, 0);
            break;
        case 2:
            $pos_x = $infos_img[0] - $infos_wat[0];
            break;
    } 
    // Position y
    switch (floor(($position-1) / 3)) {
        case 0:
            $pos_y = 0;
            break;
        case 1:
            $pos_y = round(($infos_img[1] - $infos_wat[1]) / 2, 0);
            break;
        case 2:
            $pos_y = $infos_img[1] - $infos_wat[1];
            break;
    } 
    $img_image = $dst;
    if ($infos_wat[2] == 2)
        $img_watermark = imagecreatefromjpeg($watermark);
    if ($infos_wat[2] == 3)
        $img_watermark = imagecreatefrompng($watermark);
    imagealphablending($img_image, true);
    imagealphablending($img_watermark, true);
    if ($t_x != -1) {
        imagecolortransparent($img_watermark, imagecolorat($img_watermark, $t_x, $t_y));
    } 
    imagecopymerge($img_image, $img_watermark, $pos_x, $pos_y, 0, 0, $infos_wat[0], $infos_wat[1], $transparency);
    return $img_image;
} 

function generatecounterimage($filename)
{
    global $comment_corner_size;
    global $cachedir;

    $counter_array = get_counter_data($filename); // returns 30 values (0 if none available!)
    $width = 138;
    $height = 70;

    if (gd_version() >= 2) {
        $dst = ImageCreateTrueColor($width, $height);
    } else {
        $dst = imagecreate ($width, $height);
    } 
    $white = imagecolorallocate($dst, 255, 255, 255);
    $bar_color1 = imagecolorallocate($dst, 140, 140, 140);
    $bar_color2 = imagecolorallocate($dst, 190, 190, 190);

    $linecolor = imagecolorallocate($dst, 0, 0, 0);

    imagefill($dst, 0, 0 , imagecolortransparent($dst, $white)); 
    // draw the lines :)
    $maxvalue = 1;
    $max_height = 58;
    imageline($dst, 3, 67 , 132 , 67 , $linecolor);
    imageline($dst, 5, 5 , 5 , 70 , $linecolor);
    imageline($dst, 3, 67 - $max_height , 7 , 67 - $max_height , $linecolor);

    $counter_length = count($counter_array);
    for($i = 0;$i < $counter_length;$i++) {
        $y = $counter_array[$i];
        if ($y > $maxvalue) {
            $maxvalue = $y;
        } 
    } 
    imagestring($dst, 1, 8 , 0 , $maxvalue , $linecolor);

    $factor = ($max_height-3) / $maxvalue;
    $x = 8;

    $tag_counter = date("w") + 4; 
    // echo "<ul>";
    for($i = 0;$i < $counter_length;$i++) {
        if ($counter_array[$i] > -1) {
            $y = floor(64 - ($counter_array[$i] * $factor));
            imagefilledrectangle($dst, $x , $y , $x + 1 , 64 , $bar_color2);
            imagefilledrectangle($dst, $x + 2 , $y , $x + 2 , 64 , $bar_color1);
        } 
        if (($tag_counter++ % 7) == 0) {
            imageline($dst, $x-1, 66 , $x-1 , 68 , $linecolor);
        } 
        $x += 4;
    } 
    // set Documet Type
    header("Content-type: image/png");
    if (imagepng($dst, $cachedir . "/counter.png")) {
        imagedestroy($dst);
        return true;
    } else {
        debug('cannot return');
        return false;
    } 
} 

function getimagesize_remote($image_url)
{
    $handle = fopen ($image_url, "rb");
    $contents = "";
    if ($handle) {
        do {
            $count += 1;
            $data = fread($handle, 8192);
            if (strlen($data) == 0) {
                break;
            } 
            $contents .= $data;
        } while (true);
    } else {
        return false;
    } 
    fclose ($handle);

    $im = ImageCreateFromString($contents);
    if (!$im) {
        return false;
    } 
    $gis[0] = ImageSX($im);
    $gis[1] = ImageSY($im); 
    // array member 3 is used below to keep with current getimagesize standards
    $gis[3] = "width={$gis[0]} height={$gis[1]}";
    ImageDestroy($im);
    return $gis;
} 

function getRotation ($twg_album, $image)
{
    global $cachedir;
    $rot = "./" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . ".rot";
    if (file_exists($rot)) {
        $rot_file = fopen($rot, 'r');
        $twg_rot = fgets($rot_file, 30);
        fclose($rot_file);
        return $twg_rot;
    } else {
        return 0;
    } 
} 

?>
