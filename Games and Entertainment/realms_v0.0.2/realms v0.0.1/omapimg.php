<?php
include("mysql.php");

$username=$_COOKIE[username];
$pass=$_COOKIE[pass];
$user = mysql_fetch_array(mysql_query("select * from users where username='$username' and password='$pass'"));
if(empty($user[username])||!$user[username]||!isset($user[username])){
$username="guest";
$pass="guest";
$user = mysql_fetch_array(mysql_query("select * from users where username='$username' and password='$pass'"));
}

$stat = mysql_fetch_array(mysql_query("select * from characters where id='$user[activechar]'"));

if(!$stat[map]){
$stat[map]=1;
}

$map = mysql_fetch_array(mysql_query("select * from `map` where `realm`='$stat[realm]' and `tile`='$stat[map]' limit 1"));

if(!$map[id]){
$map[map]= "w*w*w*w*g*g*b*b*b*b*j*w*w*w*w*w*g*b*b*c*b*j*w*w*w*w*w*g*b*b*b*b*j*w*w*w*g*g*g*b*b*h*b*j*w*w*g*g*b*b*b*b*b*b*j*g*g*g*g*g*b*b*b*b*b*j*w*w*w*w*g*g*b*b*b*b*j*w*g*g*g*w*g*g*b*b*b*j*w*g*w*g*g*g*g*b*b*b*j*g*g*g*g*g*g*g*g*b*b";
}

header ("Content-type: image/png");
function fromhex($string){
   GLOBAL $im;
   sscanf($string, "%2x%2x%2x", $red, $green, $this);
   return ImageColorAllocate($im,$red,$green,$this);
}

include("map.php");
$loadmap = str_replace("\n","",$map[map]);

$sidea=10;
$sideb=10;
$size=$sidea*$sideb;

$im = imagecreatetruecolor(420,300);
         if($user[template]=="darkrealmsie"){
$white = fromhex("000000");
         }else{
$white = fromhex("ffffff");
         }

imagefilledrectangle($im,0,0,420,300,$white);

$bottom = imagecreatefrompng("img\\tiles\\bottom.png");
imagecolortransparent($bottom,imagecolorexact($bottom,255,0,255));
$green = imagecolorexact($bottom,0,255,0);

if($user[template]=="darkrealmsie"){
imagecolorset($bottom,$green,255,255,255);
         }else{
imagecolorset($bottom,$green,0,0,0);
         }

imagecopymerge($im, $bottom, 0,0,0,0, 420, 300, 100);




$image=explode("*", $loadmap);




$px="180";
$py="20";






foreach ($image as $key => $value) {

if($value=="j"){
$px=$px-230;
$py=$py-130+10;
}else{
if(file_exists("img\\tiles\\$value.png")){
$load = imagecreatefrompng("img\\tiles\\$value.png");
}else{
$load = imagecreatefrompng("img\\tiles\\x.png");
}
imagecolortransparent($load,imagecolorexact($load,255,0,255));
imagecopymerge($im, $load, $px,$py,0,0, 50, 50, 100);
imagedestroy($load);
$px=$px+21;
$py=$py+13;
}
}

imagepng($im);
imagedestroy($im);