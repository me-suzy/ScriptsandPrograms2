<?php 
//Load Random Images & text
$file_type = ".jpg";
$text_file_type = ".txt";

$image_folder = "images"; //CHANGE THIS ENTRY TO YOUR IMAGES FOLDER

$handle = opendir("./$image_folder"); 
while ($file = readdir($handle)) 
	$names[count($names)] = $file; 
closedir($handle);

sort($names);

for ($i=0;$names[$i];$i++){
	$ext=strtolower(substr($names[$i],-4));
	if ($ext==".jpg"){
		$names1[$tempvar]=$names[$i];$tempvar++;
		}
	}

$random = mt_rand(1, $tempvar);

$image_name = $random . $file_type;
$text_name = $random . $text_file_type;
?>