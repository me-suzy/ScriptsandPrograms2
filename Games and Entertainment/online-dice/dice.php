<?php 
	include('config.php');
		$style = $_GET['style'];
		$num_dice = $_GET['dice'];
		
		$style_dir = 'styles/style' . $style . '/';
		
		$image_size_array = GetImageSize($style_dir . '1.png');
		$style_width = $image_size_array[0];
		$style_height = $image_size_array[1];
		$width = $style_width * $num_dice;
		$height = $image_size_array[1];
	
		$im = ImageCreate($width,$height);
		$x_offset = 0;
		for ($i = 0; $i < $num_dice; $i++) {
			$rand_num = rand(1,6);
			$im2 = ImageCreateFromPNG($style_dir . $rand_num . ".png");
			imagecopymerge($im,$im2,$x_offset,0,0,0,$style_width,$style_height,100);
			$x_offset = $x_offset + $style_width;
		}
		
		Header ("Content-type: image/png");
	
		
		ImagePng ($im);
		ImageDestroy($im); 
		ImageDestroy($im2);
	
	
?>
