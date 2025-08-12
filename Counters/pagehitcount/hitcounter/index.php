<?php 

$TextFile = "counter.txt";
$Count = trim(file_get_contents($TextFile));
$FP = fopen($TextFile, "r");
$Count=fgets($FP, 4096);
fclose ($FP);
settype($Count, "integer");
$Count++;
if ($FP = fopen ($TextFile, "w")){
 fwrite ($FP, $Count);
 fclose ($FP);
}
///
///    IF YOU GET ERRORS ON LINE 17 CHANGE png to PNG (caps)
///
$image = "counterpic.png"; 
$im = imagecreatefrompng($image); 
$red = ImageColorAllocate ($im, 255, 0, 0); 
$blue = ImageColorAllocate ($im, 0, 0, 255); 
$hit = "$Count";
$ip = $_SERVER["REMOTE_ADDR"];



ImageString($im, 2, 18, 1, "www.spywire.net", $blue); 
ImageString($im, 2, 1, 19, " Your ip: $ip", $red); 
ImageString($im, 2, 1, 30, "  Viewed $hit times ", $red);  
header("Content-Type: image/png"); 
Imagepng($im,'',100); 
ImageDestroy ($im); 
?> 