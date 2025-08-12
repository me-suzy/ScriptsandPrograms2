<?php



if (!empty($_REQUEST['image_filename'])) {
	$uploaded_img_size = getimagesize($cfg_pics_path."/".$_REQUEST['image_filename']);

	/**     
	* position of watermark text on image
	* 0 = top 
	* 1 = bottom 
	* 2 = middle left
	*/
	
	if ($cfg_font_pos == 0) {
		$h_pos = $cfg_font_h_padding;
		$v_pos = $cfg_font_v_padding;
	} elseif ($cfg_font_pos == 1) {
		$h_pos = $cfg_font_h_padding;
		$v_pos = round($uploaded_img_size[1] - $cfg_font_v_padding);
	} elseif ($cfg_font_pos == 2) {
		$h_pos = $cfg_font_h_padding;
		$v_pos = round($uploaded_img_size[1]/2);
	} else {
		$h_pos = $cfg_font_h_padding;
		$v_pos = $cfg_font_v_padding;
	}

	echo $gd_info_array["GD Version"]; 
	preg_match('%\d+(\.\d+)*%', $gd_info_array["GD Version"], $m); 
	$current_gd_version =  $m[0];	

	// The function ImageCreate() creates a PALETTE image.
	// The function ImageCreateFromJPEG() creates a TRUE COLOR image.

	/*
	* If the cache option is turned on, create a copy of the 
	*/
	
	if ($cfg_cache_path==1) {
		$use_filename = $cfg_cache_path."/".$_REQUEST['image_filename'];
	} else {
		$use_filename = $cfg_pics_path."/".$_REQUEST['image_filename'];
	}

	if ($current_gd_version >= 2) {
		$image = imagecreatetruecolor($uploaded_img_size[0], $uploaded_img_size[1]); 
	} else {			
		$image = imagecreate($uploaded_img_size[0], $uploaded_img_size[1]);
	}

	if ($uploaded_img_size[2]==2) {
		$image = imagecreatefromjpeg($use_filename);
	} elseif ($uploaded_img_size[2]==3) {
		$image = imagecreatefrompng($use_filename);	
	} 



	// in this case, the color is white, but you can replace the numbers with the RGB values
	// of any color you want
	$color = imagecolorallocate($image, 255,255,255);

	// make our drop shadow color
	$black = imagecolorallocate($image, 0,0,0);	

	ImageTTFText ($image, $cfg_font_size, 0, ($h_pos+2), ($v_pos+2), $black, $cfg_font_path."/".$cfg_font_name,stripslashes($this_watermark_txt));

	/*
	* Now add the colored text "on top"
	*/

	ImageTTFText ($image, $cfg_font_size, 0, $h_pos, $v_pos, $color,  $cfg_font_path."/".$cfg_font_name,stripslashes($this_watermark_txt));

	if ($uploaded_img_size[2]==2) {
		imagejpeg($image);
	} elseif ($uploaded_img_size[2]==3) {	
		imagepng($image);
		
	} 
	
imagedestroy($image); 

}
?>