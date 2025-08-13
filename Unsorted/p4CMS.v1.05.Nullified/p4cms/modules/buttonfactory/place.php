<?

include("../../include/config.inc.php");
include("../../include/mysql-class.inc.php");
include("../../include/functions.inc.php");

function hextorgb($hexcode){
	$hexcode = str_replace("#","",$hexcode);
	$r[r] = hexdec(substr($hexcode, 0, 2));
	$r[g] = hexdec(substr($hexcode, 2, 2));
	$r[b] = hexdec(substr($hexcode, 4, 2));
	return ($r);
}

$im = imagecreatefrompng($_REQUEST[bbutton]);

$rgb = hextorgb($_REQUEST[bfarbe]);

$farbe = @ImageColorAllocate($im, $rgb[r], $rgb[g], $rgb[b]);
$font = str_replace("place.php", $_REQUEST[bfont], $_SERVER[SCRIPT_FILENAME]);

imagettftext($im, $_REQUEST[bsize], 0, $_REQUEST[bl], $_REQUEST[bt], $farbe, $font, "Text");

ob_start();
if (@imagepng($im)) {
	$bma = ob_get_contents();
	ob_end_clean();
	header("Content-Type: image/png");
	
	echo $bma;
	exit();
}

?>