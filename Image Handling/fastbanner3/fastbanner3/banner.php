<?php
$select_banner = $_POST["select_banner"];
$titletext = $_POST["titletext"];
$titlecolor = $_POST["titlecolor"];
$titlex = $_POST["titlex"];
$titley = $_POST["titley"];
$tagtext = $_POST["tagtext"];
$tagcolor = $_POST["tagcolor"];
$tagx = $_POST["tagx"];
$tagy = $_POST["tagy"];

if ((isset($titletext) || $titletext > "")
		and (isset($tagtext) || $tagtext > "")) {
	$titlecolor = substr($titlecolor, -6);
	$r1 = hexdec(substr($titlecolor, 0, 2));
	$g1 = hexdec(substr($titlecolor, 2, 2));
	$b1 = hexdec(substr($titlecolor, 4, 2));
	$tagcolor = substr($tagcolor, -6);
	$r2 = hexdec(substr($tagcolor, 0, 2));
	$g2 = hexdec(substr($tagcolor, 2, 2));
	$b2 = hexdec(substr($tagcolor, 4, 2));
	$titletext2 = stripslashes($titletext);
	$tagtext2 = stripslashes($tagtext);
	$image = imagecreatefrompng("banner/$select_banner");
	$titlecolor = imagecolorallocate($image, $r1, $g1, $b1);
	$tagcolor = imagecolorallocate($image, $r2, $g2, $b2);
	ImageString($image, 10, $titlex, $titley, $titletext2, $titlecolor);
	ImageString($image, 10, $tagx, $tagy, $tagtext2, $tagcolor);
	$counterval = 0;
	$filename = "banners/counter.txt";
	$fp = fopen($filename, "r");
	$counterval = fread($fp, 26);
	fclose($fp);
	$counterval = (integer)$counterval + 1;
	$fp = fopen($filename, "w+");
	fwrite($fp, $counterval, 26);
	fclose($fp);
	$newbanner = 'banners/' . $counterval . '.png';
	ImagePNG($image, $newbanner);
	include("header.inc");
	echo "\n";
	echo "<a href=\"$newbanner\"><img src=\"$newbanner\" width=\"468\" height=\"60\" alt=\"Your Generated Banner\" border=\"0\"></a>\n";
	echo "\n\n";
	include("footer.inc");
} 
if ($counterval > 5) {
	$deletebanner = (integer)$counterval - 5;
	$delete = 'banners/' . $deletebanner . '.png';
	unlink("$delete");
} 

?>
