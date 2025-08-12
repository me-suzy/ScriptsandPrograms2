<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle - based on the code of Rainer Hungershausen

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/
function cleanup_cache()
{
    global $cachedir;
    global $cache_time; // in days !!
    global $extension_slideshow;
    global $extension_small;

    if ($cache_time == -1) {
        return;
    } 

    $cache_time = $cache_time * 86400;
    $del_time = time() - $cache_time;
    if (file_exists($cachedir)) {
        $d = opendir($cachedir);
        $i = 0;
        while (false !== ($entry = readdir($d))) {
            if (stristr($entry, $extension_slideshow) || stristr($entry, $extension_small)) {
                $atime = fileatime($cachedir . "/" . $entry);
                if ($atime < $del_time) {
                    unlink($cachedir . "/" . $entry);
                } 
            } 
        } 
        closedir($d);
    } else {
        echo 'Cannot find the cache directory at ' . $cachedir . ' - please check your configuration.';
    } 
} 

/* 
   Counts the number of jpegs in all trees
*/
function count_tree($file_dir)
{
    global $password_file;
    global $url_file;
    global $cache_dirs;

    $localfiles = 0;
    if (isset($_SESSION["count_tree" . $file_dir ]) && $cache_dirs) {
        if ($_SESSION["count_tree" . $file_dir ] == "__nix__") {
            return null;
        } 
        return $_SESSION["count_tree" . $file_dir ];
    } else {
        if ($handle = @opendir($file_dir)) {
            $i = 0;
            $list = null;
            while (false !== ($file = @readdir($handle))) {
                if ($file == $url_file) {
                    $dateiurl = fopen($file_dir . "/" . $url_file, "r");
                    $locurl = trim(fgets($dateiurl, 100));
                    fclose($dateiurl);
                    if ($list) {
                        $list = array_merge(http_get($locurl), $list);
                    } else {
                        $list = http_get($locurl);
                    } 
                    $i += count($list);
                } else if ($file != $password_file && $file != "." && $file != "..") {
                    $list[$i] = $file;
                    $i++;
                } 
            } 
            $dir_length = count($list); 
            // echo "<ul>";
            for($i = 0;$i < $dir_length;$i++) {
                // if (strrpos($list[$i], ".") == false) {
                if (isset($list[$i])) {
                    if (is_dir($file_dir . "/" . $list[$i])) {
                        $localfiles += count_tree($file_dir . "/" . $list[$i]);
                    } else {
                        if (preg_match("/.*\.(j|J)(p|P)(e|E){0,1}(g|G)$/", $list[$i])) {
                            $localfiles++; 
                            // echo "<li><a href=\"".$file_dir."/".$list[$i]."\">".$list[$i]."</a></li>\n";
                        } 
                    } 
                } 
            } 
            // echo "</ul>";
            closedir($handle);
        } 

        if ($cache_dirs) {
            $_SESSION["count_tree" . $file_dir ] = $localfiles;
        } 
        return $localfiles;
    } 
} 

/* 
   Seach
*/
function search_filenames($file_dir, $searchstring, $dd)
{
    global $password_file;
    global $url_file;
    global $twg_standalone;
    global $basedir;
    global $install_dir;

    $topx = array();
    // echo "<br>searching " . $file_dir;

    if ($handle = @opendir($file_dir)) {
        $i = 0;
        $list = null;
        while (false !== ($file = @readdir($handle))) {
            if ($file == $url_file) {
                $dateiurl = fopen($file_dir . "/" . $url_file, "r");
                $locurl = trim(fgets($dateiurl, 100));
                fclose($dateiurl);
                if ($list) {
                    $list = array_merge(http_get($locurl), $list);
                } else {
                    $list = http_get($locurl);
                } 
                $i += count($list);
            } else if ($file != $password_file && $file != "." && $file != "..") {  
                $list[$i] = $file;
                $i++;
            } 
        } 
        $dir_length = count($list); 
        // echo "<ul>";
        for($i = 0;$i < $dir_length;$i++) {
            // if (strrpos($list[$i], ".") == false) {
            if (isset($list[$i])) {
                $full_dir = $file_dir . "/" . $list[$i];
                if (is_dir($full_dir)) { 
                        $result = search_filenames($file_dir . "/" . $list[$i], $searchstring, $dd);
                        if (count($result > 0)) {
                            $topx = array_merge($topx, $result);
                        } 
                } else {
                    // $album = substr($full_dir, strlen($basedir) + 1);
                    $album = substr($file_dir, strlen($basedir) + 1);
                    if (preg_match("/.*\.(j|J)(p|P)(e|E){0,1}(g|G)$/", $list[$i]) && in_array ($album, $dd)) {
                        if (stristr($list[$i], $searchstring)) {
                            $album = substr($file_dir, strlen($basedir) + 1);
                            $datum = "";
                            $name = htmlentities(restore_plus(urldecode(replace_plus($list[$i])))); // fix for some server
                            $komment = htmlentities ($album);
                            $line = $datum . "=||=" . $name . "=||=" . $komment ; 
                            // the decode at the end is important - if you remove them images with hard filenames are not displayed :).
                            $compare = $line . "=||=" . $install_dir . "image.php?twg_album=" . urlencode($album) . "&amp;twg_type=thumb&amp;twg_show=" . urlencode($list[$i]) . $twg_standalone; 
                            // echo $compare;
                            $topx[] = $compare;
                        } 
                    } 
                } 
            } 
        } 
        // echo "</ul>";
        closedir($handle);
    }
    return $topx;
} 

/*
returns all directories that can be included into the top x views
*/
function get_view_dirs($file_dir, $pass)
{
    global $privatepasswort;
    global $password_file;
    global $basedir;
    $dirs = array();

    if ($handle = @opendir($file_dir)) {
        $i = 0;
        $list = null;
        while (false !== ($file = @readdir($handle))) {
            if ($file != "." && $file != "..") {
                $list[$i] = $file;
                $i++;
            } 
        } 
        $dir_length = count($list);
        $goon = false;
        if ($dir_length > 0) {
            if (in_array ($password_file, $list)) {
                $privatefilename = $file_dir . "/" . $password_file;
                $dateipriv = fopen($privatefilename, "r");
                $passwd_line = fgets($dateipriv, 500);
                $passwd = split(",", $passwd_line);
                fclose($dateipriv);
                if ($passwd_line == "") {
                    $passwd = array($privatepasswort);
                } 
                if (in_array($pass, $passwd)) {
                    $goon = true;
                } 
            } else {
                $goon = true;
            } 
        } 
        if ($goon && ($file_dir != $basedir)) {
            $dirs = array (substr($file_dir, strlen($basedir) + 1));
            // echo substr($file_dir, strlen($basedir) + 1);
        } 

        for($i = 0;$i < $dir_length;$i++) {
            if (is_dir($file_dir . "/" . $list[$i]) && ($list[$i] != $password_file) && $goon) {
                $localdirs = get_view_dirs($file_dir . "/" . $list[$i], $pass);
                $dirs = array_merge ($dirs, $localdirs);
            } 
        } 
        closedir($handle);
    } 
    return $dirs;
} 

function getDirectoryDescription($directory)
{
    global $enable_folderdescription;
    global $default_language;

    if ($enable_folderdescription) {
        // we check for a languagedepentent file  first !
        $filename = $directory . "/folder_" . $default_language . ".txt";
        if (!file_exists($filename)) {
            $filename = $directory . "/folder.txt";
        } 

        if (file_exists($filename)) {
            $datei = fopen($filename, "r");
            $text = trim(fgets($datei, 1000));
            fclose($datei);
            if ($text != "") {
                return $text;
            } else {
                return false;
            } 
        } else {
            return false;
        } 
    } else
        return false;
} 

function getImagepageDescription($directory)
{
    global $enable_folderdescription;
    global $default_language;

    if ($enable_folderdescription) {
        // we check for a languagedepentent file  first !
        $filename = $directory . "/image_" . $default_language . ".txt";
        if (!file_exists($filename)) {
            $filename = $directory . "/image.txt";
        } 

        if (file_exists($filename)) {
            $datei = fopen($filename, "r");
            $text = trim(fgets($datei, 1000));
            fclose($datei);
            if ($text != "") {
                return $text;
            } else {
                return false;
            } 
        } else {
            return false;
        } 
    } else
        return false;
} 

function getDirectoryName($directory, $dir_name)
{
    global $enable_foldername;
    global $default_language;

    if ($enable_foldername) {
        // we check for a languagedepentent file  first !
        $filename = $directory . "/foldername_" . $default_language . ".txt";
        if (!file_exists($filename)) {
            $filename = $directory . "/foldername.txt";
        } 

        if (file_exists($filename)) {
            $datei = fopen($filename, "r");
            $text = trim(fgets($datei, 1000));
            fclose($datei);
            if ($text != "") {
                return $text;
            } else {
                return $dir_name;
            } 
        } else {
            return $dir_name;
        } 
    } else
        return $dir_name;
} 

function getFileContent($filename, $oldcontent)
{
    if (file_exists($filename)) {
        $datei = fopen($filename, "r");
        $text = trim(fgets($datei, 1000));
        fclose($datei);
        if ($text != "") {
            return $text;
        } 
    } 
    return $oldcontent;
} 

/*
we cache this call later in the sesssion! 
*/
function get_directories($localdir)
{
    global $cache_dirs;
    global $sort_album_by_date;
    global $sort_albums;
    global $sort_albums_ascending;

    if (isset($_SESSION[ "dir" . $localdir ]) && $cache_dirs) {
        if ($_SESSION["dir" . $localdir ] == "__nix__") {
            return null;
        } 
        return $_SESSION["dir" . $localdir ];
    } else {
        if (!file_exists($localdir)) {
            echo "The link you are using is not valid. Please go back the the main page.";
            return null;
        } 
        $d = opendir($localdir);
        $nr = 0;
        while (false !== ($entry = readdir($d))) {
            if (is_dir($localdir . "/" . $entry) && $entry != "." && $entry != "..") {
                if (!check_empty_directories($localdir . "/" . $entry)) {
                    if ($sort_album_by_date) {
                        $sorttime = filemtime($localdir . "/" . $entry);
                        if ((strlen($sorttime) == 9)) {
                            $sorttime = "0" . $sorttime;
                        } 
                        $locverzeichnisse[] = $sorttime . $entry;
                    } else {
                        $locverzeichnisse[] = $entry;
                    } 
                } 
            } 
        } 
        closedir($d);

        if (isset($locverzeichnisse)) {
            // we sort the folders
            if ($sort_albums) {
                if ($sort_albums_ascending) {
                    sort($locverzeichnisse);
                } else {
                    rsort($locverzeichnisse);
                } 
                reset($locverzeichnisse);
            } 

            if ($sort_album_by_date) {
                for($x = 0;$x < count($locverzeichnisse);$x++) {
                    // we go through the array and remove the time :).
                    // echo $list[$x] . "<br>";
                    $locverzeichnisse[$x] = substr($locverzeichnisse[$x], 10);
                } 
            } 
            if ($cache_dirs) {
                $_SESSION["dir" . $localdir ] = $locverzeichnisse;
            } 
            return $locverzeichnisse;
        } else {
            if ($cache_dirs) {
                $_SESSION["dir" . $localdir ] = "__nix__";
            } 
            return null;
        } 
    } 
} 

/*
we cache this call later in the sesssion! 
*/
function check_empty_directories($localdir)
{
    $d = opendir($localdir);
    $nr = 0;
    while (false !== ($entry = readdir($d))) {
        if ($entry != "." && $entry != "..") {
            return false;
        } 
    } 
    closedir($d);
    return true;
} 

function checkUrl($path)
{
    global $url_file;
    if (file_exists($path . "/" . $url_file)) {
        $dateiurl = fopen($path . "/" . $url_file, "r");
        $locurl = trim(fgets($dateiurl, 100));
        fclose($dateiurl);
        $locurl = str_replace(" ", "%20", $locurl);
        return $locurl;
    } else {
        return false;
    } 
} 

function get_image_list($twg_album)
{
    global $basedir;
    global $sort_by_date;
    global $sort_images_ascending;
    global $sort_by_filedate;
    global $cache_dirs;

    if (isset($_SESSION["dir_images" . $twg_album ]) && $cache_dirs) {
        if ($_SESSION["dir_images" . $twg_album ] == "__nix__") {
            return null;
        } 
        return $_SESSION["dir_images" . $twg_album ];
    } else {
        $spacer = "000000000"; // we want to have the same length for all dates  for easier removing
        if (function_exists("exif_read_data")) {
            if ($sort_by_filedate) {
                $enable_exif = false;
            } else {
                $enable_exif = true;
            } 
        } else {
            $enable_exif = false;
        } 
        if (file_exists($basedir . "/" . $twg_album)) {
            $path = $basedir . "/" . $twg_album;
            $url = checkUrl($path);
            if ($url) {
                $list = http_get($url);
                $sort_by_date = false;
            } else {
                $d = opendir($path);
                $i = 0;
                while (false !== ($entry = readdir($d))) {
                    $filename = $path . "/" . $entry;
                    if (!is_dir($filename) && preg_match("/.*\.(j|J)(p|P)(e|E){0,1}(g|G)$/", $entry)
                            ) {
                        if ($sort_by_date) {
                            $sorttime = get_image_time($filename, $enable_exif , $spacer, false);
                            $list[$i++] = $sorttime . urlencode($entry);
                        } else {
                            $list[$i++] = urlencode($entry);
                        } 
                        // }
                    } 
                } 
                closedir($d);
            } 
            if (isset($list)) {
                if ($sort_images_ascending) {
                    sort($list);
                } else {
                    rsort($list);
                } 
                reset($list);

                if ($sort_by_date) {
                    for($x = 0;$x < count($list);$x++) {
                        // we go through the array and remove the time :).
                        // echo $list[$x] . "<br>";
                        $list[$x] = substr($list[$x], 19);
                    } 
                } 
                if ($cache_dirs) {
                    $_SESSION["dir_images" . $twg_album ] = $list;
                } 
                return $list;
            } else {
                if ($cache_dirs) {
                    $_SESSION["dir_images" . $twg_album ] = "__nix__";
                } 
                return false;
            } 
        } else {
            echo "The album ' " . $basedir . "/" . $twg_album . "' is not available anymore. Please close the browser to refresh the cache." ;
            return false;
        } 
    } 
} 

function get_image_time($filename, $enable_exif , $spacer, $checkexif)
{
    global $sort_by_filedate;

    if ($checkexif) {
        if (function_exists("exif_read_data")) {
            if ($sort_by_filedate) {
                $enable_exif = false;
            } else {
                $enable_exif = true;
            } 
        } else {
            $enable_exif = false;
        } 
    } 

    $sorttime = "";
    if ($enable_exif) {
        // we try to use the camerainformation!
        $exif_data = exif_read_data($filename);
        if ($exif_data) {
            if (isset($exif_data['DateTimeOriginal'])) {
                $sorttime = $exif_data['DateTimeOriginal'];
            } 
            if (strlen($sorttime) == 0) {
                if (isset($exif_data['DateTimel'])) {
                    $sorttime = $exif_data['DateTime'];
                    if (strlen(trim($sorttime)) != 19) {
                        // we use the filedate! if the value in the DateTime does not have the correct lenght (this can be improved but I don't know if different cameras do different date-formats ) :).
                        $sorttime = filemtime ($filename) . $spacer;
                    } 
                } else {
                    // we use the filedate! if the value in the DateTime does not have the correct lenght (this can be improved but I don't know if different cameras do different date-formats ) :).
                    $sorttime = filemtime ($filename) . $spacer;
                } 
            } 
        } else {
            // we use the filedate!
            $sorttime = filemtime ($filename) . $spacer;
        } 
    } else {
        // we use the filedate!
        $sorttime = filemtime ($filename) . $spacer;
    } 

    if (($spacer == "000000000") && (strlen($sorttime) == 18)) {
        $sorttime = "0" . $sorttime;
    } 
    return $sorttime;
} 

function get_language_list()
{
    global $install_dir;

    if (isset($_SESSION["dir_lang_list"])) {
        return $_SESSION["dir_lang_list"];
    } 
    $d = opendir($install_dir . "language");
    $i = 0;
    while (false !== ($entry = readdir($d))) {
        if (!is_dir($entry) && preg_match("/.*\.(G|g)(i|I)(F|f)$/", $entry)) {
            $list[$i++] = urlencode($entry);
        } 
    } 
    closedir($d);
    if (isset($list)) {
        sort($list);
        reset($list);
        $_SESSION["dir_lang_list"] = $list;
        return $list;
    } else {
        $_SESSION["dir_lang_list"] = false;
        return false;
    } 
} 

function get_language_string($lang)
{
    $lang_string = $lang;
    $fileName = "language/language_" . $lang . ".txt";
    if (file_exists($fileName)) {
        $datei = fopen($fileName, "r");
        $lang_string = fgets($datei, 30);
        fclose($datei);
    } 
    return $lang_string;
} 

function get_image_number($twg_album, $entry)
{
    $imagelist = get_image_list($twg_album);
    for($current = 0, $i = 0; $i < count($imagelist); $i++) {
        if (urldecode($imagelist[$i]) == urldecode($entry)) {
            $current = $i;
        } 
    } 
    return $current;
} 

function get_image_count($twg_album)
{
    return count(get_image_list($twg_album));
} 

function get_image_name($twg_album, $img_nr)
{
    $imagelist = get_image_list($twg_album);
    return $imagelist[$img_nr];
} 

function get_next($twg_album, $entry, $current_id)
{
    $imagelist = get_image_list($twg_album);
    return ($current_id + 1 < count($imagelist) ? $imagelist[$current_id + 1] : false);
} 
// this is the previos image
function get_last($twg_album, $entry, $current_id)
{
    $imagelist = get_image_list($twg_album);
    return ($current_id-1 >= 0 ? $imagelist[$current_id-1] : false);
} 

function get_end($twg_album)
{
    $imagelist = get_image_list($twg_album);
    return $imagelist[count($imagelist)-1];
} 

function get_twg_offset($twg_album, $entry, $current_id)
{
    global $thumbnails_x;
    global $thumbnails_y;
    global $autodetect_maximum_thumbnails;
    global $thumb_pic_size;

    if ($autodetect_maximum_thumbnails && isset($_SESSION["browserx"]) && isset($_SESSION["browsery"])) {
        $thumbnails_x = floor(($_SESSION["browserx"] - 30) / ($thumb_pic_size + 5));
        $thumbnails_y = floor(($_SESSION["browsery"] - 40) / ($thumb_pic_size + 5));
    } 

    if (isset($_SESSION["twg_minus_rows"])) {
        $thumbnails_y = $thumbnails_y - $_SESSION["twg_minus_rows"];
    } 
    $num_pic = $thumbnails_x * $thumbnails_y;
    return $num_pic * floor($current_id / ($num_pic));
} 

function get_page_nr($current_id)
{
    global $thumbnails_x;
    global $thumbnails_y;

    $num_pic = $thumbnails_x * $thumbnails_y;
    return floor($current_id / ($num_pic));
} 

function get_dirname($dir)
{
    $dirname = str_replace("\\", "/", dirname($dir));
    $dirname = "/" ? "" : ($dirname . "/");
    return $dirname;
} 

/* 
function:debug() 
*/
function debug($data)
{
    global $debug_file;
    if ($debug_file == '') {
        return;
    } 
    $debug_string = date("m.d.Y G:i:s") . " - " . $data . "\n\n";
    if (file_exists($debug_file)) {
        $debug_file_local = fopen($debug_file, 'a');
        fputs($debug_file_local, $debug_string);
        fclose($debug_file_local);
    } else {
        $debug_file_local = fopen($debug_file, 'w');
        fputs($debug_file_local, $debug_string);
        fclose($debug_file_local);
    } 
} 
/* 
end function debug() 
*/

function on_error($num, $str, $file, $line)
{
    if ((strpos ($file, "email.inc.php") === false) && (strpos ($line, "fopen") === false)) {
        debug ("ERROR $num in " . substr($file, -40) . ", line $line: $str");
    } 
} 

function on_error_no_output($num, $str, $file, $line) {
  if (strpos ($line, "fopen") === false) {
	         // debug ("ERROR $num in " . substr($file, -40) . ", line $line: $str");
    } 
} 

set_error_handler("on_error");

function gd_version()
{
    static $gd_version_number = null;
    if ($gd_version_number === null) {
        // Use output buffering to get results from phpinfo()
        // without disturbing the page we're in.  Output
        // buffering is "stackable" so we don't even have to
        // worry about previous or encompassing buffering.
        ob_start();
        phpinfo(8);
        $module_info = ob_get_contents();
        ob_end_clean();
        if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i",
                $module_info, $matches)) {
            $gd_version_number = $matches[1];
        } else {
            $gd_version_number = 0;
        } 
    } 
    return $gd_version_number;
} 

/* 
	Replaces some characters in urls which ledds to problems with cached images. Missing characters can be added here 
*/
function replace_valid_url($name)
{ 
    // $name = str_replace("%2C", ",", $name);
    // $name = str_replace("%28", "(", $name);
    // $name = str_replace("%29", ")", $name);
    // $name = str_replace("+", " ", $name);
    return $name;
} 

/*
  Replaces the ' in some places where thery are no valid characters (e.g. in strings which are teminated by ')
*/
function escapeHochkomma($name)
{
   $name = str_replace("'", "", $name);
   $name = str_replace("%27", "", $name);
    // $name = str_replace("&", "%2C", $name);
    return $name;
} 

function removeTitleChars($name)
{
    $name = str_replace("\"", "'", $name);
    return $name;
} 

function checkText()
{
    if (!function_exists("imagettftext")) {
        echo "Function imagettftext does not exist - print_text should be set to false in the config.php!";
    } ;
} 

function checktwg_rot()
{
    global $cachedir;
    global $install_dir;

    $image = $install_dir . "buttons/private.jpg";
    $outputimage = $cachedir . "/_rotation_available.jpg";
    $outputimageerror = $cachedir . "/_rotation_not_available.jpg"; 
    // we check only once - if one to the ouputimages exists we don't do he check again
    // delete the _twg_rot_not_available.jpg and _twg_rot_available.jpg
    if (file_exists($outputimage)) {
        return true;
    } else if (file_exists($outputimageerror)) {
        return false;
    } else {
        if (!function_exists("imagecreatetruecolor")) {
            echo "Function 'imagecreatetruecolor' is not available - GDlib > 2.0.1 is needed to run TinyWebGallery properly!";
        } else {
            if (!function_exists("imagerotate")) {
                $dst = imagecreatetruecolor(50, 37);
                imagejpeg($dst, $outputimageerror, 50);
                return false;
            } else {
                $oldsize = getImageSize($image);
                $src = imagecreatefromjpeg($image);
                $dst = imagecreatetruecolor(50, 37);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, 50, 37, 50, 37);
                $twg_rot = @imagerotate($dst, 90, 0);
                if (!imagejpeg($twg_rot, $outputimage, 50)) {
                    imagejpeg($dst, $outputimageerror, 50);
                    return false;
                } else {
                    return true;
                } 
            } 
        } 
    } 
} 

function get_counter_data($file)
{
    $return_array = array(-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1);
    if (file_exists($file)) {
        $datei = file($file);
        $index1 = 0;
        $counter = 0;

        $lines = count($datei);
        if ($lines > 30) {
            $index1 = $lines - 30;
        } 
        if ($lines < 30) {
            $counter = 30 - $lines;
        } 
        $oldtimestamp = 0;
        $day = 3600 * 24;

        while ($index1 < $lines) {
            $dat = explode("&", $datei[$index1]);

            $timestamp = mktime(0, 0, 0, $dat[1], $dat[0], $dat[2]);
            if ($oldtimestamp <> 0) {
                while (($oldtimestamp + $day) < $timestamp) { // we have a gap!
                    $return_array[$counter++] = 0;
                    $oldtimestamp += $day;
                } 
            } 
            $count = $dat[3];
            $return_array[$counter++] = $count;
            $index1++;
            $oldtimestamp = $timestamp;
        } 

        $timestamp = time() - $day; // only fill up till the last day!          
        // the last days !
        while (($oldtimestamp + $day) < $timestamp) { // we have a gap!
            $return_array[$counter++] = 0;
            $oldtimestamp += $day;
        } 
    } 
    $return_array = array_slice($return_array, count($return_array) - 30);
    return $return_array;
} 

function check_image_extension($image)
{
    return preg_match("/.*\.(j|J)(p|P)(e|E){0,1}(g|G)$/", $image);
} 

/*
replaces a + or a + encode(+) with __PLUS__   :  we have to doubleencode for some servers (like ed's :) and therefore would loose the +)
*/
function replace_plus($plus)
{
    $plus = str_replace("+", "__PLUS__", $plus);
    $plus = str_replace(urlencode("+"), "__PLUS__", $plus);
    return $plus;
} 

/*
 replaces a __PLUS__ with +
*/
function restore_plus($plus)
{
    return str_replace("__PLUS__", "+", $plus);
} 
// suche / in pfad - wenn keiner drin leer zurück - sonst rest vor /
function getupperdirectory($twg_album)
{
    return substr($twg_album, 0, strrpos ($twg_album, "/"));
} 

/*
 Duplicate in imagefunctions !!!!
*/
function php_to_html_chars_all($data)
{
    $e = get_html_translation_table (HTML_ENTITIES);
    unset($e["&"]);
    return replacesmilies(strtr($data, $e));
} 

/*
  insert smilies into comments and captions - only the smilies have to be added to the smilies folder in 
  the buttons dir . e.g. :).gif  ;).gif ....  : is not a valid representation therefore the following
  coding is used:
  
  : -> a
  \ -> b
  / -> c
  * -> d
  
  z.B. :) is the file a).gif !
  
*/
function replacesmilies($data)
{
    global $install_dir;
    global $enable_smily_support; 
    // $path = $_SERVER['SERVER_NAME'] . substr($_SERVER['SCRIPT_NAME'], 0, -10);
    if ($enable_smily_support) {
        // read the smilies !
        if (isset($_SESSION["dir_smilies_list"])) {
            $list = $_SESSION["dir_smilies_list"];
            $filelist = $_SESSION["dir_smilies_list_names"];
        } else {
            $d = opendir($install_dir . "buttons/smilies");
            $i = 0;
            while (false !== ($entry = readdir($d))) {
                if (preg_match("/.*\.(G|g)(i|I)(F|f)$/", $entry)) {
                    $filelist[$i] = $entry;
                    $entry = switch_smilie_letters($entry);
                    $entry = substr($entry, 0, strlen($entry)-4); // we strip the extension
                    $list[$i++] = $entry;
                } 
            } 
            closedir($d);
            if (isset($list)) {
                $_SESSION["dir_smilies_list"] = $list;
                $_SESSION["dir_smilies_list_names"] = $filelist;
            } 
        } 
        if (isset($list)) {
            // we start replacing ...
            for($i = 0; $i < count($list); $i++) {
                $data = str_replace($list[$i], "<img style=\"vertical-align:middle; padding-bottom:3px\" src=\"" . $install_dir . "buttons/smilies/" . $filelist[$i] . "\" alt=\"\" />", $data);
            } 
        } 
    } 
    return $data;
} 

function switch_smilie_letters($entry)
{
    $entry = str_replace("a", ":", $entry);
    $entry = str_replace("b", "\\", $entry);
    $entry = str_replace("c", "/", $entry);
    $entry = str_replace("d", "*", $entry);
    $entry = str_replace("e", "|", $entry);
    $entry = str_replace("f", "?", $entry);
    return $entry;
} 

/* 
encodes only the part without the /
*/
function twg_urlencode($data)
{
    global $double_encode_urls;

    $data = str_replace("/", "__TWG__", $data);
    $data = urlencode ($data);
    if ($double_encode_urls) {
        $data = urlencode ($data);
    } 
    return str_replace("__TWG__", "/", $data);
} 

function create_smilie_div()
{
    global $enable_smily_support;

    $smilies = "";
    if ($enable_smily_support) {
        // read the smilies !
        if (isset($_SESSION["dir_smilies_list_pop"])) {
            $list = $_SESSION["dir_smilies_list_pop"];
            $filelist = $_SESSION["dir_smilies_list_names_pop"];
        } else {
            $d = opendir("../buttons/smilies");
            $i = 0;
            while (false !== ($entry = readdir($d)) && $i < 12) {
                if (preg_match("/.*\.(G|g)(i|I)(F|f)$/", $entry)) {
                    $pos = strpos ($entry, "-");
                    if ($pos === false) {
                        $filelist[$i] = $entry;
                        $entry = switch_smilie_letters($entry);
                        $entry = substr($entry, 0, strlen($entry)-4); // we strip the extension
                        $list[$i++] = $entry;
                    } 
                } 
            } 
            closedir($d);
            if (isset($list)) {
                $_SESSION["dir_smilies_list_pop"] = $list;
                $_SESSION["dir_smilies_list_names_pop"] = $filelist;
            } 
        } 
        if (isset($list)) {
            // we start replacing ...
            for($i = 0; $i < count($list); $i++) {
                $smilies .= "<img class='twg_smilie_image' onclick='javascript:document.getElementById(\"twg_titel\").value=document.getElementById(\"twg_titel\").value + \"" . $list[$i] . "\"; hide_smilie_div();' src='../buttons/smilies/" . $filelist[$i] . "' alt='" . $list[$i] . "' />";
                if (($i % 4) == 3) {
                    $smilies .= "<br />";
                } 
            } 
        } 
    } 
    return $smilies;
} 

/* we don't cache this because all calling functions are cached already ! */
function http_get($url)
{
    $buffer = "";
    $url_stuff = parse_url($url);
    $port = isset($url_stuff['port']) ? $url_stuff['port'] : 80;
    $fp = @fsockopen($url_stuff['host'], $port);

    if (!$fp) {
        echo "<br />Error opening external url<br />check _mydebug.out<br/>Most likely fsockopen is disabled\n";
        return array(); ;
    } else {
        $query = 'GET ' . $url_stuff['path'] . " HTTP/1.0\n";
        $query .= 'Host: ' . $url_stuff['host'];
        $query .= "\n\n";

        fwrite($fp, $query);

        while ($tmp = fread($fp, 1024)) {
            $buffer .= $tmp; 
            // echo $tmp;
        } 
        // preg_match('/Content-Length: ([0-9]+)/', $buffer, $parts);
        // return scan_string_for_jpgs(substr($buffer, - $parts[1]));
        return scan_string_for_jpgs($buffer);
    } 
} 

function scan_string_for_jpgs($jpg_string)
{ 
    // echo $jpg_string;
    $pics = array();
    $search = substr(stristr($jpg_string, 'href='), 0, 5);
    $scanstring = $search . "\"";
    if ($jpg_string) {
        $teile = explode($scanstring, $jpg_string);
        $dir_length = count($teile);
        for($i = 0;$i < $dir_length;$i++) {
            $teile[$i] = substr($teile[$i], 0 , strpos($teile[$i], "\""));
            if (preg_match("/.*\.(j|J)(p|P)(e|E){0,1}(g|G)$/", $teile[$i])) {
                array_push ($pics, $teile[$i]);
            } 
        } 
    } 
    return $pics;
} 

function getRootLink($directory)
{
    global $twg_standalone;

    $filename = $directory . "/root.txt";
    return getFileContent($filename, $_SERVER['PHP_SELF'] . "?" . $twg_standalone);
} 

function encodespace($name)
{ 
    // $name = str_replace("%2B", "+", $name);
    $name = urlencode($name);
    $name = str_replace("+", "%20", $name);
    return $name;
} 

?>
