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
// helperfunctions to navigate through images !!
include "../inc/filefunctions.inc.php";
include "../inc/mysession.inc.php";
include "i_parserequest.inc.php";

$basedir = "../" . $basedir;
$cachedir = "../" . $cachedir;
$xmldir = "../" . $xmldir;

include "../inc/readxml.inc.php";

$img_nr = get_image_number($twg_album, $image_enc);
$img_total = count(get_image_list($twg_album));
$img_nr++;
$display_nr = $img_nr;
if ($img_nr >= $img_total) {
    $img_nr = 0;
} 
$img_next = get_image_name($twg_album, $img_nr);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TinyWebGallery</title>
<meta name="author" content="mid" />
<?php
echo "<meta http-equiv='refresh' content='" . $twg_slideshow_time . "; URL=" . $_SERVER['PHP_SELF'] . "?twg_album=" . urlencode($twg_album) . "&amp;twg_show=" . $img_next . $twg_standalone . "&amp;twg_root=" . $twg_root . "' />";
?>

<link rel="stylesheet" type="text/css" href="../style.css" />
<?php 
if (file_exists("../my_style.css")) {
  echo '<link rel="stylesheet" type="text/css" href="../my_style.css" />';
}
?>
<script type="text/javaScript" src="../js/twg_image.js"></script>
<style  type="text/css">
body.twg {
background-color:transparent;
}
</style>
</head>
<body class="twg">
<center>


<script type="text/javaScript"> 
if (document.getElementById) { 
  parent.document.getElementById("imagecounter").innerHTML = "<?php echo $display_nr;?>";
} else { 
  document.write('<?php echo '<span class="twg_Caption">' . ($display_nr) . "/" . $img_total . "<\/span><br \/>";  ?>'); 
}
</script>
<noscript>
<?php echo '<span class="twg_Caption">' . ($display_nr) . "/" . $img_total . "</span><br />";  ?>
</noscript>

<?php
$aktimage = $image;

// $aktimage = replace_valid_url($aktimage);
$actPicturejs = $twg_root . "?twg_album=" . urlencode($twg_album) . "&twg_show=" . $image_enc  . $twg_standalonejs . "&twg_root=" . $twg_root;
$replaced_album = str_replace("/", "_", $twg_album);
$thumbimage = urlencode($replaced_album . "_" . $aktimage);
$small = $cachedir . "/" . $thumbimage . "." . $extension_small;
$thumbimagenext = urlencode($replaced_album . "_" . urldecode($img_next));
if ($double_encode_urls) {
    $thumbimagenext = urlencode($thumbimagenext);
} 
$small_next = $cachedir . "/" . $thumbimagenext . "." . $extension_small; 
if (!file_exists($small)) {
    $src_value = "../image.php?twg_album=" . urlencode($twg_album) . "&amp;twg_type=small&amp;twg_rot=0&amp;twg_show=" . urlencode($image);
    $widthheight = "";
} else {
    $oldsize = getimagesize($small);
    if ($double_encode_urls) {
        $thumbimage = urlencode($thumbimage);
    } 
    $src_value = $cachedir . "/" . urlencode($thumbimage) . "." . $extension_small; 
    // we set the size of the image because we know it :) - looks nicer at the twg_slideshow
    $widthheight = "width='" . $oldsize[0] . "' height='" . $oldsize[1] . "' ";
} 

$install_dir = "../";
printf("<a href='../image.php?twg_album=%s&amp;twg_show=%s'><img class='imageview' src='%s' alt='twg_slideshow' %s /></a>" , urlencode($twg_album), urlencode($image), $src_value, $widthheight);
echo '<br /><span class="twg_Caption">&nbsp;' . replacesmilies(getBeschreibung($image, $werte, $index)) . '</span>&nbsp;';

if (!file_exists($small_next)) {
    $small_next = "../image.php?twg_album=" . urlencode($twg_album) . "&twg_type=small&twg_rot=0&twg_show=" . $img_next;
} else {
    if ($double_encode_urls) {
        $thumbimagenext = urlencode($thumbimagenext);
        $small_next = $cachedir . "/" . $thumbimagenext . "." . $extension_small;
    } 
} 
?>
</center>
<script type="text/javaScript">     
if (document.getElementById) parent.document.getElementById("stop_slideshow").href = "<?php echo $actPicturejs;
?>"; 
// it preloads the image on the nxt page - should be in the browser cache than ;)
MM_preloadImages('<?php echo $small_next;
?>');
</script>
</body>
</html>