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

require "../config.php";
include "../inc/mysession.inc.php";
include "../inc/filefunctions.inc.php";
set_error_handler("on_error_no_output"); // 4.x gives depreciated errors here but if I change it it does only work with 5.x - therefore I don't show any errors here !
include "../inc/exif.inc.php";
set_error_handler("on_error");
include "i_parserequest.inc.php";


if (!isset($show_exif_info)) {
  $show_exif_info = true;
}

$titel = '';

if (isset($_GET['twg_titel'])) {
    $titel = $_GET['twg_titel'];
} else {
    $titel = false;
} 

require "../language/language_" . $default_language . ".php";

$titel = nl2br($titel);
$titel = stripslashes($titel);

$xmldir = "../" . $xmldir;
include "../inc/readxml.inc.php";

$remote_image = checkurl("../"  . $basedir . "/" . $twg_album);

if ($remote_image) {
									$filename = $remote_image . encodespace($image);
								} else {
									$filename = "../" . $basedir . "/" . $twg_album . "/" . $image;
                }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TinyWebGallery</title>
<meta name="author" content="mid" />
<link rel="stylesheet" type="text/css" href="iframe.css" />
<script type="text/javaScript">reload = false;</script>
<script type="text/javaScript" src="../js/twg_image.js"></script>
</head>
<body>
<table summary=''  style="width: 100%; height:100%" cellpadding='0' cellspacing='0'><tr><td class="closebutton"> 
<img name="imageField" alt='' onClick="closeiframe()" align="right" src="../buttons/close.gif" width="12" height="12" border="0" />
</td></tr><tr><td>
<center>
<table summary=''  style="width: 80%; height:90px" cellpadding='0' cellspacing='0'>
<tr class="gray"><td class="fileinfoleft"><?php echo $lang_fileinfo_views; ?></td><td class="fileinforight"><?php echo increaseImageCount($twg_album, $image) ?></td></tr>

<?php
if ($show_download_counter && $enable_download_counter) {
echo '
<tr><td class="fileinfoleftbottom">' . $lang_fileinfo_dl . '</td><td class="fileinforightbottom">' . getDownloadCount($twg_album, $image) . '</td></tr>
';
}


if ($show_image_rating) {
  echo  '<tr class="gray"><td class="fileinfoleftbottom">' . $lang_fileinfo_rating . '</td><td class="fileinforightbottom">' . getVotesCount($twg_album, $image) . '</td></tr>';
}
?>
<tr><td class="fileinfoleftbottom"><?php echo $lang_fileinfo_name; ?></td><td class="fileinforightbottom"><?php echo substr(htmlentities($image),0,25); if (strlen(htmlentities($image)) > 25) { echo "..."; }  ?></td></tr>
<tr class="gray"><td class="fileinfoleftbottom"><?php echo $lang_fileinfo_date; ?></td><td class="fileinforightbottom"><?php 
if (!$remote_image) {
echo date ("j.n.Y", get_image_time($filename,  true , "" , true ));
} else {
echo $lang_fileinfo_not_available;
}
?></td></tr>
<tr><td class="fileinfoleftbottom"><?php echo $lang_fileinfo_size; ?></td><td class="fileinforightbottom"><?php 
if (!$remote_image) {
echo sprintf("%01.0f KB", filesize($filename)/1000); 
} else {
echo $lang_fileinfo_not_available;
}
?></td></tr>
<tr class="gray"><td class="fileinfoleftbottom"><?php echo $lang_fileinfo_resolution; ?></td><td class="fileinforightbottom"><?php 
$oldsize = getimagesize($filename);
echo  $oldsize[0] . " x " . $oldsize[1];
?></td></tr>
<?php

if(substr($filename, 4, 3) != "://") {
if($show_exif_info)
 show_exif_info($filename);
} else {
 	foreach($lang_exif_info as $label => $key) {
 			$data = $lang_fileinfo_not_available;
 		print "<tr class='gray'><td class='fileinfoleftbottom'>$label</td><td class='fileinforightbottom'>".trim($data)."</td></tr>";
	}
}

// if you want to extend the fileinfo simply add more lines like these and increate the size of the iframes in the language file!
/*
<tr class="gray"><td class="fileinfoleftbottom">text</td><td class="fileinforightbottom">value</td></tr>
*/
?>
</table>
</center>
</td></tr></table>
<?php include "i_bottom.inc.php"; ?>
</body>
</html>