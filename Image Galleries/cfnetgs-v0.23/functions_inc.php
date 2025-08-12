<?php

// function definitions
// define array_chunk if not available (PHP 4 >= 4.2.0, PHP 5)
if (!function_exists('array_chunk')) {
	function array_chunk( $input, $size, $preserve_keys = false) {
		@reset( $input );
		$i = $j = 0;
		while (@list($key, $value) = @each($input)) {
			if (!(isset($chunks[$i]))) {
				$chunks[$i] = array();
			}
			if (count($chunks[$i]) < $size) {
				if ($preserve_keys) {
					$chunks[$i][$key] = $value;
					$j++;
				} else {
					$chunks[$i][] = $value;
				}
			} else {
				$i++;
				if ($preserve_keys) {
					$chunks[$i][$key] = $value;
					$j++;
				} else {
					$j = 0;
					$chunks[$i][$j] = $value;
				}
			}
		}
		return $chunks;
	}
}

function Resize ($src_image, $dest_width, $library, $prefix) {

	global $directory_target;
	global $gd2;
	global $cache_dir;
	
	if (is_dir("$cache_dir")) {
	} else {
		mkdir ("$cache_dir", 0777);
		chmod ("$cache_dir", 0777);
	}
	if ($prefix == "folder"){
		$dest_image = rawurldecode ($cache_dir.'folder_icon');
	}else{
		$dest_image = rawurldecode ($cache_dir.$prefix.'_'.$dest_width. '_'. $src_image);
	}
	
	if (!is_file($dest_image)) {
		$pic_info = getimagesize($src_image);
		$pic_width = $pic_info[0];
		$pic_height = $pic_info[1];
		$x='x';
		if ($pic_width <= $dest_width) {
			$dest_width = $pic_width;
		}
		if ($pic_width > $pic_height) {
			$scale_factor = $dest_width / $pic_width;
			$new_pic_width = $dest_width;
			$new_pic_height = intval($pic_height * $scale_factor);
		} else {
			$scale_factor = $dest_width / $pic_height;
			$new_pic_height = $dest_width;
			$new_pic_width = intval($pic_width * $scale_factor);
		}
		if ($library == 'IM') {
			$make_magick = system("convert -quality 100 -size $new_pic_width$x$new_pic_height -geometry $new_pic_width$x$new_pic_height \"$src_image\" \"$dest_image\"", $retval);
			if ($retval) {
				echo 'That didn\'t work as planned. (imagemagick is not installed or working right)<br>';
			}
		} else {
			if (eregi(".(jpg|jpeg)$", $src_image)) {
				$src_img = imagecreatefromjpeg($src_image);
			}
			if (eregi(".(png)$", $src_image)) {
				 $src_img=imagecreatefrompng($src_image);
			}			
			if ($gd2 == 'yes') {
                                $tmp_img = ImageCreateTrueColor($new_pic_width, $new_pic_height);
                                imagecopyresampled($tmp_img, $src_img, 0, 0, 0, 0, $new_pic_width, $new_pic_height, $pic_width, $pic_height);
			} else {
				$tmp_img = ImageCreate($new_pic_width, $new_pic_height);
                                imagecopyresized($tmp_img,$src_img, 0, 0, 0, 0, $new_pic_width, $new_pic_height, $pic_width, $pic_height);
			}
			if (preg_match("/png/", $system[1])){
				imagepng($tmp_img, $dest_image);
			} else {
				imagejpeg($tmp_img, $dest_image);
			}
			imagedestroy($tmp_img);
			imagedestroy($src_img);
		}
	}
	return $dest_image;
}


function PageCheck ($currentpage) {
	
	if (empty($currentpage)) {
		$currentpage = 1;
	} else {
		$currentpage = $currentpage;
	}
	return $currentpage;
}

function NavBar ($current_dir, $gallery_url, $gallery_dir) {
	$str = strlen($gallery_dir);
	$url = substr($current_dir, $str);
	$url = str_replace(chr(92), chr(47), $url);
	$navlink = explode("/", trim($url));
	$ii = count($navlink);
	echo'Navigation:',"\n" , '/';
	for ($count = 0;$count < count($navlink) - 1;$count++) {
		echo '<a href="'. $gallery_url. '/index.php?directory=';
    		for ($i = 0; $i <= $count;$i++) {
				echo '/'. $navlink[$i];
			}
   			echo '">'. $navlink[$count]. '</a>'. '/';   
	}
	echo $navlink[count($navlink) - 1];
	echo '<br><br>'. "\n";
}

function checkgd(){
	$gd2="";
	ob_start();
	phpinfo(8);
	$phpinfo=ob_get_contents();
	ob_end_clean();
	$phpinfo=strip_tags($phpinfo);
	$phpinfo=stristr($phpinfo,"gd version");
	$phpinfo=stristr($phpinfo,"version");
	$end=strpos($phpinfo," ");
	$phpinfo=substr($phpinfo,0,$end);
	$phpinfo=substr($phpinfo,7);
	if (preg_match("/2./",$phpinfo)) {
		$gd2="yes";
	}
	return $gd2;
}

function list_directory($dir) {
	$file_list = '';
	$stack[] = $dir;
	while ($stack) {
		$current_dir = array_pop($stack);
		if ($dh = opendir($current_dir)) {
			while (($file = readdir($dh)) !== false) {
				if ($file !== '.' AND $file !== '..') {
					$current_file = "{$current_dir}/{$file}";
					if (is_file($current_file) && eregi(".(jpg|png|jpeg)$", $file)) {
						$file_list[] = "{$current_dir}/{$file}";
					} elseif (is_dir($current_file)) {
						$stack[] = $current_file;
					}
				}
			}
		}
	}
	return $file_list;
}

?>


