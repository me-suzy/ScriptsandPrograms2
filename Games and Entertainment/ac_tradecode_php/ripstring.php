<?php

function all2int( $value )
{
	if(!is_string($value)) return $value;
	$tempval = (int) $value;
	if("".$tempval == $value) return $tempval;
	$tempval2 = $value;
	if(strtolower(substr($value,0,2)) == "0x")
	{
		$hexmod = strlen(substr($value,2));
		$tempval = hexdec(substr($value,2));
		$tempval2 = substr($value,2);
	}
	else $tempval = hexdec($value);
	if(str_pad(dechex($tempval),$hexmod,"0",STR_PAD_LEFT) == $tempval2) return $tempval;
	return 0;
}

$values = explode(" ",$getstring);

header("Content-type: image/png");
$im  = imagecreate((192/16)*sizeof($values), 256/16);
$chn = imagecreatefromPNG("charsheet.png");
for($idx=0;$idx<sizeof($values);$idx++)
{
	$char = "0x".$values[$idx];
	$dec = all2int($char);
	$hex = str_pad(dechex($dec),2,"0",STR_PAD_LEFT);

	imagecopy($im, $chn, (192/16)*$idx, 0, (192/16)*hexdec(substr($hex,1,1)), (256/16)*hexdec(substr($hex,0,1)), 192/16, 256/16);
}
$w=ImageColorAllocate($im, 255, 255, 255);
ImagePng($im);
ImageDestroy($im);
ImageDestroy($chn);
?>