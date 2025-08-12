<?php
$file=$_GET[file];
$type=$_GET[t];

if($type==1) $attr="image/gif";
if($type==2) $attr="image/jpeg";
if($type==3) $attr="image/png";

list($wi, $hi) = @getimagesize($file);

$w=$wi*$_GET[r]/100;
$h=$hi*$_GET[r]/100;

if($_GET[r]!=100)
{
	header("Content-type: $attr");
	switch($type)
	{
		case 1: $im = @imagecreatefromgif($file);  $ext="@imagegif(\$new);"; break;
		case 2: $im = @imagecreatefromjpeg($file); $ext="@imagejpeg(\$new, '' , 100);"; break;
		case 3: $im = @imagecreatefrompng($file); $ext="@imagepng(\$new);"; break;
	}
	
	if( $type != 1 )
	{
		$new = imagecreatetruecolor($w, $h);
		imagecopyresampled($new , $im , 0 , 0 , 0 , 0 , $w , $h , $wi , $hi);
	}
	else
	{
		$new = imagecreate($w , $h);
		imagecopyresized($new , $im , 0 , 0 , 0 , 0, $w , $h , $wi , $hi);
	}
	
	eval($ext);
	
	@imagedestroy($im);
	@imagedestroy($new);
}

else
{
	echo "<body topmargin=0 leftmargin=0>	
	<img src=$_GET[file] width=$w height=$h>
	</body>";
}	

?>