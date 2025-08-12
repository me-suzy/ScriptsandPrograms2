<?php
include("functions.php");
//Form Variables
$imgfile = 		"pics/".$HTTP_POST_FILES['imgfile']['name'];
$text = 		$_POST['user_text'];
$date = 		$_POST['user_date'];
$mode = 		$_POST['mode'];
$dropshadow = 	$_POST['dropshadow'];
$border = 		$_POST['border'];
$picture = 		$_POST['picture'];
$font_size = 	$_POST['font_size'];
$xpos = 		$_POST['xpos'];
$ypos = 		$_POST['ypos'];
$yposoff = 		$_POST['xposoff'];
$yposoff = 		$_POST['yposoff'];

//Combine the color into the format RRR.GGG.BBB
$bgcolor = 		colorCombine($_POST['bgred'], $_POST['bggreen'], $_POST['bgblue']);
$txtcolor = 	colorCombine($_POST['txtred'], $_POST['txtgreen'], $_POST['txtblue']);
$shadowcolor = 	colorCombine($_POST['shadowred'], $_POST['shadowgreen'], $_POST['shadowblue']);
$bordercolor = 	colorCombine($_POST['borderred'], $_POST['bordergreen'], $_POST['borderblue']);

$date_array = explode('-', $date);
$date = mktime(0,0,0, $date_array[0], $date_array[1], $date_array[2]);

// if (move_uploaded_file($HTTP_POST_FILES['imgfile']['tmp_name'], $imgfile)) {
//    print "File is valid, and was successfully uploaded. ";
// } else {
//    print "Upload Failed";
// }

if(!isset($dropshadow))
	$dropshadow = 0;
if(!isset($border))
	$border = 0;


$fh = fopen('pics/data.dat', 'w') or die("can't open file: $php_errormsg");
fwrite($fh, $imgfile."\n");
fwrite($fh, $text."\n");
fwrite($fh, $date."\n");
fwrite($fh, $mode."\n");
fwrite($fh, $dropshadow."\n");
fwrite($fh, $border."\n");
fwrite($fh, $picture."\n");
fwrite($fh, $font_size."\n");
fwrite($fh, $xpos."\n");
fwrite($fh, $ypos."\n");
fwrite($fh, $xposoff."\n");
fwrite($fh, $yposoff."\n");

//Write Color Information
fwrite($fh, $bgcolor."\n");
fwrite($fh, $txtcolor."\n");
fwrite($fh, $shadowcolor."\n");
fwrite($fh, $bordercolor."\n");
fclose($fh);

$location = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

echo "<h3>HTML Code</h3>";
echo "<p>Copy this HTML code onto the page you want your coutndown image displayed.</p>";
echo "<form><textarea rows='4' cols='70'>";
echo "<img src=' $location '/>";
echo "</textarea> </form>";
?>
<a href="example.php">Preview the Countdown!</a>