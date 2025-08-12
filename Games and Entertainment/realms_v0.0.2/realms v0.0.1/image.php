<?php 
header("Content-type: image/png"); 
  
$random=rand(1,20);

if(file_exists("image$rand.png")){
$im = imagecreatefrompng("image$rand.png"); 
}else{
$im = imagecreatefrompng("image.png"); 
}



imagepng($im); 
imagedestroy($im); 
?>