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
// rotation control
if (isset($_GET['twg_rot'])) {
    $twg_rot = ($_GET['twg_rot'] > 0 ? $_GET['twg_rot'] : 0);
    $twg_rot = $twg_rot >= 360 ? 0 : $twg_rot;
} else
    $twg_rot = -1; 
// twg_album
if (isset($_GET['twg_album'])) {
    // we have to save the + es here :).
    $twg_album = replace_plus($_GET['twg_album']);
    $twg_album = urldecode(urldecode($twg_album)); // the double decode is because of some servers where this is needed!
    $twg_album = restore_plus($twg_album);
    $album_enc = urlencode($twg_album); // Albumwert für links, damit diese richtig codiert werden.
} else {
    $twg_album = false;
    $album_enc = false;
} 
// image
/*
if (isset($_GET['twg_show'])) {
    $image = urldecode($_GET['twg_show']);
    echo $image;
} else
    $image = false;
*/
// image
if (isset($_GET['twg_show'])) {
    $image = replace_plus(ereg_replace("/", "", $_GET['twg_show']));
    $image = urldecode($image); // the double decode is because of some servers where this is needed!
    $image_orig = restore_plus($image);
    $image = str_replace("\\'", "'", $image_orig);
    $image_enc = urlencode($image); 
    // $image = $_GET['twg_show'];
    // $image = str_replace("\\'", "'", $image);
} else {
    $image = false;
    $image_enc = false;
} 
// twg_offset
if (isset($_GET['twg_offset']) && $_GET['twg_offset'] > 0) {
    $twg_offset = $_GET['twg_offset'];
} else
    $twg_offset = 0;
if (isset($_GET['twg_foffset']) && $_GET['twg_foffset'] > 0) {
    $twg_foffset = $_GET['twg_foffset'];
} else
    $twg_foffset = 0; 
// twg_slideshow
if (isset($_GET['twg_slideshow'])) {
    $twg_slideshow = $_GET['twg_slideshow'];
    $twg_smallnav = 'TRUE';
    $show_comments = false;
    $show_count_views = false;
} else
    $twg_slideshow = false;

if (isset($_GET['twg_top10'])) {
    $top10_type = $_GET['twg_top10'];
    $top10 = true;
} else
    $top10 = false;

if (isset($_GET['twg_dir'])) {
    $dir = $_GET['twg_dir'];
} else
    $dir = "next";

if (isset($_GET['twg_random'])) {
    if (isset($_SESSION['twg_random' . $_GET['twg_random']])) {
        $image = $_SESSION['twg_random' . $_GET['twg_random']];
        $image_enc = urlencode($image);
    } else { // if external html page was open toooo long we jump to the first image
        $image = "x";
        $image_enc = "x";
    } 
} 

if (isset($_GET['twg_random'])) {
    if (isset($_SESSION['twg_random_album' . $_GET['twg_random']])) {
        $twg_album = $_SESSION['twg_random_album' . $_GET['twg_random']];
        $album_enc = urlencode($twg_album);
    } else { // if external html page was open toooo long we jump to the first image
        $twg_album = false;
        $album_enc = false;
    } 
} 

if (isset($_GET['twg_search_term'])) {
    $twg_search_term = $_GET['twg_search_term'];
    if ($twg_search_term == "") {
        $twg_search_term = " ";
    } 
} else
    $twg_search_term = " ";

if (isset($_GET['twg_search_filename'])) {
    $twg_search_filename = true;
} else
    $twg_search_filename = false;

if (isset($_GET['twg_search_caption'])) {
    $twg_search_caption = true;
} else
    $twg_search_caption = false;

if (isset($_GET['twg_search_comment'])) {
    $twg_search_comment = true;
} else
    $twg_search_comment = false;

if (isset($_GET['twg_search_max'])) {
    $twg_search_max = $_GET['twg_search_max'];
} else
    $twg_search_max = 50;

?>