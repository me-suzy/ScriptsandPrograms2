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
?>

