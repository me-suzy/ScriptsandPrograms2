<?php 
include("eyelash_files/global.inc.php");


//thumbnail max dimensions
$width_mini = 50;
$height_mini = 50;

//do we want a square (1) ? or do we keep proportions (0)
$square=1;

$str_xml=array();
//main node
$str_xml[]="<gallery images='" .$arr_global["images"]. "' thumbnails='" . $arr_global["thumbnails"]. "'>"; 

//open directory
$rep=opendir($arr_global["images"]);

//read jpg files in directory
while ($file = readdir($rep)){ 

	if(strtolower(substr($file,-3)) == "jpg"){ 

	
      	$src_img = imagecreatefromjpeg($arr_global["images"] . $file);

		
		// if text file does not exist
		$txtFile = str_replace("jpg","html", strtolower($file));
		if (!file_exists($arr_global["texts"] . $txtFile ))
			{
			$fp = fopen($arr_global["texts"] . $txtFile,"w");
			fwrite ($fp,"imgDescription=");
			fclose($fp);
			}
		
		
		//if image is to wide, reduce it
		if (imagesx($src_img)>$arr_global["image_max_width"])
			{
			$proportion = $arr_global["image_max_width"]/imagesx($src_img);
			$new_w=$arr_global["image_max_width"];
			$new_h= imagesy($src_img)*$proportion;
			$dst_img = imagecreatetruecolor($new_w,$new_h);
			imagecopyresized($dst_img,$src_img,
				0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));
			imagejpeg($dst_img,$arr_global["images"] . $file);
			}

		//if thumbnail does not exist
		if(!file_exists($arr_global["thumbnails"] . $file )){

			//get biggest width or height
			if(imagesx($src_img) > imagesy($src_img)){
				//proportion
				$proportion = $width_mini/imagesx($src_img);
				//New dimension
				$new_w = $width_mini;
				$new_h = imagesy($src_img)*$proportion;
				if ($square) $new_wh=$new_w;
			}else{
				//proportion
				$proportion = $height_mini/imagesy($src_img);
				//New dimension
				$new_h = $height_mini;
				$new_w = imagesx($src_img)*$proportion;
				if ($square) $new_wh=$new_h;
			}

			//create the new image, if square, adapt proportion
			if ($square)
				{
				$dst_img = imagecreatetruecolor($new_wh,$new_wh);
				imagecopyresized($dst_img,$src_img,
				0,0,0,0,$new_wh,$new_wh,
				imagesx($src_img),imagesy($src_img));}
			else
				{$dst_img = imagecreatetruecolor($new_w,$new_h);	
				imagecopyresized($dst_img,$src_img,
				0,0,0,0,$new_w,$new_h,
				imagesx($src_img),imagesy($src_img));}
					
	

			//save to thumnail folder
			imagejpeg($dst_img,$arr_global["thumbnails"] . $file);
		}

		//new xml node with dimensions
		$str_xml[] = "<img name='$file' width='".imagesx($src_img)."' height='".imagesy($src_img)."' />"; 
	} 
}
//close gallery node
$str_xml[] = "</gallery>"; 

closedir($rep); 

//print xml 
echo utf8_encode(join('', $str_xml)); 

?>