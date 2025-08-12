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

$values = $getstring;

header("Content-type: image/png");
$im  = imagecreate((192/16)*min(strlen($values),$limit), (256/16)*ceil(strlen($values)/$limit));
$chn = imagecreatefromPNG("charsheet.png");
for($idx=0;$idx<strlen($values);$idx++)
{
	$dec = ord($values[$idx]);
	$hex = str_pad(dechex($dec),2,"0",STR_PAD_LEFT);

	imagecopy($im, $chn, (192/16)*($idx % $limit), (256/16)*intval($idx/$limit), (192/16)*hexdec(substr($hex,1,1)), (256/16)*hexdec(substr($hex,0,1)), 192/16, 256/16);
}
$w=ImageColorAllocate($im, 255, 255, 255);
ImagePng($im);
ImageDestroy($im);
ImageDestroy($chn);
?>