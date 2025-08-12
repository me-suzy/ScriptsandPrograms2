<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle - based on a script of
  www.dynamicdrive.com - see notice below

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/

require "../config.php";
// helperfunctions to navigate through images !!
include "../inc/filefunctions.inc.php";
include "../inc/mysession.inc.php";
include "i_parserequest.inc.php";

$basedir = "../" .  $basedir;
$cachedir = "../" .  $cachedir;
$xmldir = "../" . $xmldir;

$email = ""; // only needed here ?
require "../language/language_" . $default_language . ".php";

include "../inc/readxml.inc.php";

$image_list = get_image_list($twg_album);
$img_nr = get_image_number($twg_album, $image_enc);
$img_total = count($image_list);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TinyWebGallery</title>
<script type="text/javaScript"> 
// Set the twg_slideshow speed (in milliseconds)
var twg_slideshowSpeed = <?php echo ($twg_slideshow_time * 1000) ?>;

var Caption = new Array(); // don't change this
var PictureName = new Array(); // don't change this
var fadeimages=new Array()

<?php

$install_dir = "../"; // for smilies !

for ($i = 0; $i < $img_total; ++$i) {
    $act_image = $image_list[$i];
    $cacheimage =  urlencode(urlencode(str_replace("/", "_", $twg_album) . "_" . urldecode($act_image))) ;
    if ($double_encode_urls) {
      $cacheimage = urlencode($cacheimage);
    }
    echo "fadeimages[" . $i . "] = './" . $cachedir . "/" . $cacheimage . "." . $extension_slideshow . "';\n";
    echo "Caption[" . $i . "] = '" . replacesmilies(htmlentities(getBeschreibung($act_image , $werte , $index)), ENT_QUOTES) . "';\n";
    echo "PictureName[" . $i . "] = '" . $twg_root . "?twg_album=" . urlencode($twg_album) . "&twg_show=" . $act_image . $twg_standalonejs . "';\n";
} 

?>

var curpos=10
var degree=10
var curcanvas="canvas0"
var curimageindex=<?php echo $img_nr;
?>;   // TODO
var nextimageindex=(curimageindex<fadeimages.length-1)? curimageindex+1 : 0
var nrimages = fadeimages.length;
var img=0; 

start_picture_generation();

/*
This function does actally not loading an image but generating all the images
which are used in the twg_slideshow. I do this this way because than I can start with 
the twg_slideshow before all images are generatet - 1 image ~ 1 s -> 100 images
100 sec before I can start !!
*/
function start_picture_generation() 
{ 
  var picgen = 0;
  picgen=new Image(); /* neues Bild-Objekt anlegen */ 
  picgen.src= '<?php echo "../image.php?twg_type=twg_slideshowgenerate&twg_album=" . urlencode($twg_album) . "&twg_show=" . urlencode($image);

?>'; /* starting picture to generate !! */
 
} 

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
<table summary="" border=0 cellpadding=2 cellspacing=0> 
  <tr>
    <td width=<?php 
if ($use_small_pic_size_as_height) { // 
  echo ceil($small_pic_size * 1.35);
} else {
  echo $small_pic_size; 
}
?> align=center height=<?php echo $small_pic_size ?>>
<script language="JavaScript1.2" type="text/javascript">
/***********************************************
* Fade-in image slideshow script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/
var twg_slideshow_width='<?php 

if ($use_small_pic_size_as_height) { // 
  echo ceil($small_pic_size * 1.35);
} else {
  echo $small_pic_size; 
}

?>px' //SET IMAGE WIDTH
var twg_slideshow_height='<?php echo $small_pic_size; ?>px' //SET IMAGE HEIGHT
var pause=<?php echo ($twg_slideshow_time * 1000) ?> //SET PAUSE BETWEEN SLIDE (3000=3 seconds)

////NO need to edit beyond here/////////////
var ie4=document.all
var dom=document.getElementById

if (ie4||dom)
document.write('<div style="position:relative;width:'+twg_slideshow_width+';height:'+twg_slideshow_height+';overflow:hidden"><div  id="canvas0" style="position:absolute;width:'+twg_slideshow_width+';height:'+twg_slideshow_height+';top:0;left:0;filter:alpha(opacity=10);-moz-opacity:10"><\/div><div id="canvas1" style="position:absolute;width:'+twg_slideshow_width+';height:'+twg_slideshow_height+';top:0;left:0;filter:alpha(opacity=10);-moz-opacity:10;visibility: hidden"><\/div><\/div>')
else
document.write('<img name="defaultslide" src="'+fadeimages[0]+'">')


function fadepic(){
if (curpos<100){
curpos+=10
if (tempobj.filters)
tempobj.filters.alpha.opacity=curpos
else if (tempobj.style.MozOpacity)
tempobj.style.MozOpacity=curpos/101
}
else{
clearInterval(dropslide)
nextcanvas=(curcanvas=="canvas0")? "canvas0" : "canvas1"
tempobj=ie4? eval("document.all."+nextcanvas) : document.getElementById(nextcanvas)
tempobj.innerHTML='<img src="'+fadeimages[nextimageindex]+'">'
nextimageindex=(nextimageindex<fadeimages.length-1)? nextimageindex+1 : 0
var tempobj2=ie4? eval("document.all."+nextcanvas) : document.getElementById(nextcanvas)
tempobj2.style.visibility="hidden"
setTimeout("twg_rotimage()",pause)
}
}

waited = 0;
wait_safty=0;

function twg_rotimage(){
  if ((img!=0)&&(!img.complete)) /* wenn noch nicht fertig geladen */ 
     {
     // we wait until the 1st image is loaded !! after that we have a litle more time until we need the next images :).
     window.setTimeout("twg_rotimage()",1000);
     waited++;
     
     if (waited >= 30) {
        alert('It seams that you connection is not fast enough to twg_show \na smooth slideshow in the optimzed version.\n\nPlease switch to the non optimized version,\nif this message occurs frequently.'); 
        waited = 0;
     }
     wait_safty=0;
     return;
     }
waited = 0;

if (wait_safty == 0) { // we check twice if the image is loaded - I had too much troule here!
	wait_safty = 1;
  window.setTimeout("twg_rotimage()",20);
  return;
}  
wait_safty = 0;

if (ie4||dom){
		resetit(curcanvas)
		var crossobj=tempobj=ie4? eval("document.all."+curcanvas) : document.getElementById(curcanvas)
		crossobj.style.zIndex++
		tempobj.style.visibility="visible"
		var temp='setInterval("fadepic()",50)'
		dropslide=eval(temp)
		curcanvas=(curcanvas=="canvas0")? "canvas1" : "canvas0"
		if (document.getElementById) document.getElementById("CaptionBox").innerHTML = Caption[curimageindex];
		// if (document.getElementById) document.getElementById("NumberBox").innerHTML= (curimageindex + 1) + "/" + nrimages; 
		if (document.getElementById) { parent.document.getElementById("imagecounter").innerHTML = curimageindex + 1; }
		if (document.getElementById) parent.document.getElementById("stop_slideshow").href = PictureName[curimageindex];
}
else {
    document.images.defaultslide.src=fadeimages[curimageindex]
}
curimageindex=(curimageindex<fadeimages.length-1)? curimageindex+1 : 0
// we are preloading the next image - we don't to this like in the orignalscript in the beginning because we generate 
// the images during twg_slideshow.
load_img(curimageindex);

if ((curimageindex % 3) == 2) {
    start_picture_generation();
  }
}

function resetit(what){
curpos=10
var crossobj=ie4? eval("document.all."+what) : document.getElementById(what)
if (crossobj.filters)
crossobj.filters.alpha.opacity=curpos
else if (crossobj.style.MozOpacity)
crossobj.style.MozOpacity=curpos/101
}

function startit(){
var crossobj=ie4? eval("document.all."+curcanvas) : document.getElementById(curcanvas)
crossobj.innerHTML='<img src="'+fadeimages[curimageindex]+'">'
twg_rotimage();
}


// we finally start the twg_slideshow !
function starttwg_show() { 
if  ((img!=0)&&(!img.complete)) /* wenn noch nicht fertig geladen */ 
  {
    window.setTimeout("starttwg_show()",1000); 
    return;
  }
if (ie4||dom) {
startit(); 
// window.onload=startit
} else {
setInterval("twg_rotimage()",pause)
}
}

// we precache the 1st image!
function startpreLoading() {
  delete img;
  load_img(curimageindex);
  window.setTimeout("starttwg_show()",500);
  return;
}

// we wait until some images on the server are pregenerated!
function delayStart() {
  {
    window.setTimeout("startpreLoading()",1000);
    return;
  }
}

// this starts the twg_slideshow
delayStart();

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