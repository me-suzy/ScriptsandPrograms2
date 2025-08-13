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

function invert($num) {
	$num = ( 255-$num );
	return $num;
}

function invrgb($in) {
	$res = array();
	$res[r] = invert($in[r]);
	$res[g] = invert($in[g]);
	$res[b] = invert($in[b]);
	return $res;
}

$sql =& new MySQLq();
$sql->Query("SELECT * FROM " . $sql_prefix . "buttons WHERE id='$_REQUEST[bbutton]'");
$row = $sql->FetchRow();
$sql->Close();

$im = imagecreatefrompng($row->bild);

$rgb = hextorgb($_REQUEST[bfarbe]);
$rgb2 = invrgb($rgb);

$farbe = @ImageColorAllocate($im, $rgb[r], $rgb[g], $rgb[b]);
$farbe2 = @ImageColorAllocate($im, $rgb2[r], $rgb2[g], $rgb2[b]);

$font = str_replace("draw.php", $_REQUEST[bfont], $_SERVER[SCRIPT_FILENAME]);

if ($_REQUEST[banti]=="n") {
	$farbe = $farbe * -1;
	$farbe2 = $farbe2 * -1;
}

if ($_REQUEST[bshad]=="y") {
	@imagettftext($im, $_REQUEST[bsize], $_REQUEST[bn], $_REQUEST[bl]+1, $_REQUEST[bt]+1, $farbe2, $font, stripslashes($_REQUEST[btext]));
}

@imagettftext($im, $_REQUEST[bsize], $_REQUEST[bn], $_REQUEST[bl], $_REQUEST[bt], $farbe, $font, stripslashes($_REQUEST[btext]));

ob_start();
if (@imagepng($im)) {
	$bma = ob_get_contents();
	ob_end_clean();
	
	if (isset($_REQUEST[dosave]) && $_REQUEST[dosave]=="y") {
		imagepng($im, "../../.." . $_REQUEST[filename]);
	}
	
	header("Content-Type: image/png");
	
	if ($_REQUEST[download]=="y") {
		header ("Content-Disposition: attachment; filename=\"button.png\"");
	}
	
	echo $bma;
	exit();
}

?>