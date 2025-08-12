<?php 
#This script has been developped by Nenad Motika [nmotika@bezveze.com]
#Please feel free to use it and to contact me for any reason

//read folder
$folder=opendir("."); 
while ($file = readdir($folder)) 
$names[count($names)] = $file; 
closedir($folder);
//sort file names in array
sort($names);
//remove any non-images from array
$tempvar=0;
for ($i=0;$names[$i];$i++){
$ext=strtolower(substr($names[$i],-4));
if ($ext==".jpg"||$ext==".gif"||$ext=="jpeg"||$ext==".png"){$names1[$tempvar]=$names[$i];$tempvar++;}
}
//random
srand ((double) microtime() * 10000000);
$rand_keys = array_rand ($names1, 2);
//random image from array
$slika=$names1[$rand_keys[0]]; 
//image dimensions
$dimensions = GetImageSize($slika); 
if (isset($HTTP_GET_VARS["pic"])){header ("Location: $slika");}
else {echo "<img src=\"$slika\" $dimensions[3]>";}
?>