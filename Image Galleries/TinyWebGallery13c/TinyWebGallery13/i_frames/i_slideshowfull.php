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

require  "../config.php";
// helperfunctions to navigate through images !!
include  "../inc/filefunctions.inc.php";
include "../inc/mysession.inc.php";
include "i_parserequest.inc.php";

$basedir = "../" .  $basedir;
$cachedir = "../" .  $cachedir;
$xmldir = "../" . $xmldir;

/*
if (isset($_GET['twg_show'])) {
    $image_orig = urldecode($_GET['twg_show']);
		$image = str_replace("\\'", "'", $image_orig);
} else
    $image = 'FALSE';

if (isset($_GET['twg_album'])) {
    $twg_album = urldecode($_GET['twg_album']); // fixed for ED ;)
    $album_enc = urlencode($twg_album);
} else {
    $twg_album = 'FALSE';
    $album_enc = 'FALSE';
}
*/

require "../language/language_" . $default_language . ".php";
include "../inc/readxml.inc.php";

$image_list = get_image_list($twg_album);
$img_nr = get_image_number($twg_album, $image_enc);
$img_total = count($image_list);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>TinyWebGallery</title>
<script type="text/javaScript"> 
var Caption = new Array(); // don't change this
var PictureName = new Array(); // don't change this
var fadeimages=new Array();

<?php

$install_dir = "../";
for ($i = 0; $i < $img_total; ++$i) {
    $act_image = $image_list[$i];
    echo "fadeimages[" . $i . "] = '../image.php?twg_album=" . $album_enc . "&twg_type=full&twg_show=" . $act_image . "';\n";
    echo "Caption[" . $i . "] = '" . replacesmilies(htmlentities(getBeschreibung($act_image , $werte , $index), ENT_QUOTES)) . "';\n";
    echo "PictureName[" . $i . "] = '" . $twg_root . "?twg_album=" . $album_enc . "&twg_show=" . $act_image . $twg_standalonejs . "';\n";
} 
?>

var curimageindex=<?php echo $img_nr;
?>;   // TODO
var nextimageindex=(curimageindex<fadeimages.length-1)? curimageindex+1 : 0
var nrimages = fadeimages.length;
var img=0; 

function load_img(srcnum) 
{ 
   if (img!=0) 
     delete img; /* altes Bild entsorgen */ 
   img=new Image(); /* neues Bild-Objekt anlegen */ 
   img.src=fadeimages[srcnum]; /* Bild laden lassen */ 
} 
</script>

<link rel="stylesheet" type="text/css" href="../style.css" />
<?php 
if (file_exists("../my_style.css")) {
  echo '<link rel="stylesheet" type="text/css" href="../my_style.css" />';
}
?>
<style type="text/css">
body.twg {
background-color:transparent;
}
</style>
</head>
<body class="twg">
<center>
<table summary="" border=0 cellpadding=0 cellspacing=0  style="height: <?php echo $browsery; ?>px; vertical-align: middle;">
<!-- counter box on top
   <tr>
    <td id=NumberBox class=twg_Caption align=center>
    <?php echo $lang_slideshowid_php_loading ?>
    </td>
  </tr>
-->  
  <tr style="height: 100%; vertical-align: middle;">
  <td align=center style="height: 100%; vertical-align: middle;">
<script language="JavaScript1.2" type="text/javascript">

var pause=<?php echo ($twg_slideshow_time * 1000) ?> //SET PAUSE BETWEEN SLIDE (3000=3 seconds)
var ie4=document.all
var dom=document.getElementById
document.write('<div class="twg_img-shadow" align="center"><table border=1 cellpadding=0 cellspacing=4><tr><td><img class="imageview" name="defaultslide" src="<?php echo "../image.php?twg_album=" . urlencode($twg_album) . "&twg_type=small&twg_show=" . urlencode($image); ?>"><\/div><\/td><\/tr><\/table>')
waited = 0;

function twg_rotimage(){
  if ((img!=0)&&(!img.complete)) /* wenn noch nicht fertig geladen */ 
     {
     // we wait until the 1st image is loaded !! after that we have a litle more time until we need the next images :).
     window.setTimeout("twg_rotimage()",500);
     waited++;
     if (waited >= 30) {
        alert('It seams that you connection is not fast enough to twg_show \na smooth twg_slideshow in the optimzed version.\n\nPlease switch to the normal version,\nif this message occurs frequently.'); 
        waited = 0;
     }
     return;
}
waited = 0;

document.images.defaultslide.src=fadeimages[curimageindex]
if (document.getElementById) document.getElementById("CaptionBox").innerHTML = Caption[curimageindex];
// number in the page
// if (document.getElementById) document.getElementById("NumberBox").innerHTML= (curimageindex + 1) + "/" + nrimages; 
// change number outside
if (document.getElementById) { parent.document.getElementById("imagecounter").innerHTML = curimageindex + 1; }
if (document.getElementById) parent.document.getElementById("stop_slideshow").href = PictureName[curimageindex];
curimageindex=(curimageindex<fadeimages.length-1)? curimageindex+1 : 0
load_img(curimageindex);
window.setTimeout("twg_rotimage()",pause);
}

function starttwg_show() { 
if  ((img!=0)&&(!img.complete)) /* wenn noch nicht fertig geladen */ 
  { 
    load_img(curimageindex);
    window.setTimeout("starttwg_show()",pause); 
  }
window.setTimeout("twg_rotimage()",pause);
}

delete img;
starttwg_show();
</script>    
    </td>
  </tr>
  <tr>
    <td id=CaptionBox class=twg_Caption align=center><?php echo $lang_slideshowid_php_loading ?></td>
  </tr>
</table>
</center>
</body>
</html>