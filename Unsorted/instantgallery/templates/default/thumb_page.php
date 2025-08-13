<?
/************************************************************
************* thumbnail template configuration **************
************************************************************/

$thumbrowcnt = 6;				// number of images per row
$imgwidth = ""; 				// leave empty if thumb image width varies
$imgheight = "";				// leave empty if thumb image height varies
$imgspacing = "1";				// space between images in pixels

// you can add a border to each image here, but note that doing so adds a 
// significant amount of html to the page output.  leave $borderWidth empty to 
// omit border.
$borderwidth = "";				// border width in pixels
$bordercolor = "";				// hexidecimal value for border color
?>

<br><br><br>
<center>

<? thumbTable($thumbrowcnt, $imgwidth, $imgheight, $imgspacing, $borderwidth, $bordercolor); ?>

</center>
