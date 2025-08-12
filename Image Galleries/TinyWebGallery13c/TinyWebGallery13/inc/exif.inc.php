<?php 
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Chris - modified by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/
include "exifReader.inc.php";

function show_exif_info($filename)
{
	global $lang_exif_info;
	global $lang_exif_not_available;


set_error_handler("on_error_no_output"); // is needed because error are most likly but we don't care about fields we don't even know
	$er = new phpExifReader($filename);
	$er->processFile();
	$exif_info = $er->getImageInfo();
set_error_handler("on_error");

	// odd behaviour patches here
	if(!isset($exif_info['fnumber'])) {
		  if (isset($exif_info['aperture'])) {
		    $exif_info['fnumber'] = "f/".round($exif_info['aperture'],1);
		  } 
		}
	if(!isset($exif_info['exposureTime'])) {	
	  if (isset($er->ImageInfo['TAG_SHUTTERSPEED'])) {
		  $exif_info['exposureTime'] = round($er->ImageInfo['TAG_SHUTTERSPEED'],3)." s (1/".(int)(1/$er->ImageInfo['TAG_SHUTTERSPEED']).")";
 		}
	} else {
	    $exifsplit= split( "\(" , $exif_info['exposureTime']);
	    if (isset($exifsplit[2])) {
	      $exif_info['exposureTime'] = $exifsplit[0] . " (" . $exifsplit[2];  
	    } else {
	      $exif_info['exposureTime'] = $exifsplit[0] . " (" . $exifsplit[1];  
	    }
	}

  if (isset($exif_info['focalLength'])) {	
   $exif_info['focalLength'] = round(substr($exif_info['focalLength'], 0, strpos($exif_info['focalLength'], '(')), 1)." mm";
  }

	foreach($lang_exif_info as $label => $key) {
		if (!isset($exif_info[$key])) {
			$data = $lang_exif_not_available;
		} else {
		  if (($exif_info[$key] != "0") && trim($exif_info[$key]) != "") {
			  $data = $exif_info[$key]; 
			} else {
			  $data = $lang_exif_not_available;
			}
		}
		print "<tr class='gray'><td class='fileinfoleftbottom'>$label</td><td class='fileinforightbottom'>".trim($data)."</td></tr>";
	}
}
?>
