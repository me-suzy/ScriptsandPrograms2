<?php
header("Content-type: image/png");
include("mysql.php");
$id=$_GET[id];


$rank = mysql_fetch_assoc (mysql_query("SELECT * FROM `users` WHERE `id` = $id"));

$im = imagecreate(3,1);


if($w>0&&$rank[width]!=$w){
mysql_query("update users set `height`='$h' where `id`='$rank[id]'");
mysql_query("update users set `width`='$w' where `id`='$rank[id]'");
}

$rank = mysql_fetch_assoc (mysql_query("SELECT * FROM `users` WHERE `id` = $id"));
$w=$rank[width];
$h=$rank[height];

if($w<=0){
$background = imagecolorallocate($im, 255, 0, 0);
}else{
$background = imagecolorallocate($im, 0, 255, 0);
}


$string = "$w x $h";



//if($show){
imagepng($im);
//}
imagedestroy($im);
?>