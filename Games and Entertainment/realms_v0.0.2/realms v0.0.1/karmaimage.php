<?php
header("Content-type: image/png");
include("mysql.php");

function fromhex($string){
   GLOBAL $im;
   sscanf($string, "%2x%2x%2x", $fred, $fgreen, $fblue);
   return ImageColorAllocate($im,$fred,$fgreen,$fblue);
}

function fromhexred($string){
   GLOBAL $im;
   sscanf($string, "%2x%2x%2x", $red, $ggreen, $gthis);
   return $red;
}

function fromhexgreen($string){
   GLOBAL $im;
   sscanf($string, "%2x%2x%2x", $dred, $green, $dthis);
   return $green;
}

function fromhexblue($string){
   GLOBAL $im;
   sscanf($string, "%2x%2x%2x", $rred, $rgreen, $this);
   return $this;
}

/*$email=$_COOKIE[email];
$pass=$_COOKIE[pass];

$stat = mysql_fetch_array(mysql_query("select * from users where email='$email' and pass='$pass'"));
*/
if($id<=0){
	$id=2;
}
$stat = mysql_fetch_array(mysql_query("select * from users where id='$id'"));
$style = mysql_fetch_array(mysql_query("select * from styles where owner='$stat[id]'"));

$style[back] = substr("$style[back]", 1);

if($stat[karma]<50){
$karma=evil;
}elseif($stat[karma]>50){
$karma=good;
}elseif($stat[karma]==50){
$karma=neutral;
}else{
$karma=error;
}

if(file_exists("img/karma$karma.png")){
$im = imagecreatefrompng("img/karma$karma.png");
}else{
$im = imagecreatefrompng("img/karmaneutral.png");
}

$white = fromhex("ffffff");
$black = fromhex("000000");
$back = fromhex("$style[back]");
$red = fromhex("ff0000");

$backg = imagecolorexact($im,255,0,0);
$backred=fromhexred("$style[back]");
$backgreen=fromhexgreen("$style[back]");
$backblue=fromhexblue("$style[back]");
imagecolorset($im,$backg,$backred,$backgreen,$backblue);

imagefilledrectangle($im,25,50,225,90,$white);
$karmayeah=$stat[karma]*2;
$karmayeahx=$karmayeah+25;
imagefilledrectangle($im,25,53,$karmayeahx,87,$red);
imageline($im,125,50,125,91,$black);
imagestring($im,4,118,60,"$stat[karma]",$black);
imagestring($im,4,30,35,"$stat[user] is $karma",$black);




imagepng($im);
imagedestroy($im);
?>