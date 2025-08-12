<?php
/*
******************************************************************************************
** SB|photoAlbum                                                                        **
** Copyright (C)2005 Ladislav Soukup                                                    **
**                                                                                      **
** URL: http://php.soundboss.cz                                                         **
** URL: http://www.soundboss.cz                                                         **
******************************************************************************************
*/

class pa_core {
	
	var $version =  "0.36" ;
	
	function make_tree($path, $loop = 1) {
		global $pa_start_image;
		$prev_idx = ($loop-1);
		if (USE_PHP4 == false) {
			$dirs = scandir($path);
		} else {
			$dh  = opendir($path);
			while (false !== ($filename = readdir($dh))) {
				$dirs[] = $filename;
			}
		}
		if (is_array($dirs)) {
			foreach($dirs as $dir) {
				if(!preg_match('/^\./',$dir)) {
					$full_path = $path ."/". $dir;
					$full_path_js = str_replace(pa_image_dir, "", $full_path);
					if(is_dir($full_path)){
						$return .= "pa_menu.add(".$loop.",".$prev_idx.",\"".$dir."\",\"javascript:pa_chdir('".$full_path_js."','".$loop."', true);\");\n";
						if ($loop == 1) { $return .= "painit_dir = \"".$full_path_js."\";\n"; }
						if ($full_path_js == $pa_start_image[0]) {
							$return .= "painit_dir_id = ".$loop.";\n";
							$return .= "painit_dir = \"".$full_path_js."\";\n";
						}
						$loop++;
						$return .= $this->make_tree($full_path, $loop);
						$loop++;
					}
				}
			}
		}
		return $return;
	}
	
	function make_tree_from_cache($cache_file) {
		global $pa_start_image;
		$pa_dir_tree_data = file($cache_file);
		$pa_dir_tree = unserialize($pa_dir_tree_data[0]);
		if(is_array($pa_dir_tree)) {
			foreach($pa_dir_tree as $pa_dir_tree_line) {
				$temp = explode("'", $pa_dir_tree_line[3]);
				$full_path_js = $temp[1];
				if ($pa_dir_tree_line[0] == 1) { $return .= "painit_dir = \"".$full_path_js."\";\n"; }
				if ($full_path_js == $pa_start_image[0]) {
					$return .= "painit_dir_id = ".$pa_dir_tree_line[0].";\n";
					$return .= "painit_dir = \"".$full_path_js."\";\n";
				}
				$return .= "pa_menu.add(".$pa_dir_tree_line[0].",".$pa_dir_tree_line[1].",\"".$pa_dir_tree_line[2]."\",\"".$pa_dir_tree_line[3]."\");\n";
			}
		}
		return $return;
	}
	
	function FileSizeToString($size){
		$bytes[0]="B";
		$bytes[1]="kB";
		$bytes[2]="MB";
		$bytes[3]="GB";
		$bytes[4]="TB";
		$i=0;
		while ($size>1023){
			$size=$size/1024;
			$i++;
		}
		$size=round($size,2);
		if ($size==0){
			$size=" ";
		}else {
			$size.=" " . $bytes[$i];
		}
		return ($size);
	}
	
	function URLStripLastDir($url){
		$url = explode("/", $url);
		if (!empty($url)) {
			for($loop=0; $loop < sizeof($url)-2; $loop++) {
				$newurl .= $url[$loop] . "/";
			}
		}
		return($newurl);
	}
	
	function GetFiles($dir) {
		$return = "";
		if (USE_PHP4 == false) {
			$files = scandir($dir);
		} else {
			$dh  = opendir($dir);
			while (false !== ($filename = readdir($dh))) {
				$files[] = $filename;
			}
		}
		if (is_array($files)) {
			foreach($files as $file) {
				$full_path = $dir . $file;
				if (is_file($full_path)) {
					$img_info = @getimagesize($full_path);
					if ($img_info[2] > 0 && $img_info[2] < 4 ) {   //1,2,3
						if (!strstr($file, pa_thumb_prefix)){
							$return_tmp["name"] = $file;
							$return_tmp["time"] = filemtime($dir.$file);
							$return[] = $return_tmp;
							unset($return_tmp);
						}
					}
				}
			}
		}
		if ((strtolower(pa_sort_by) == "name") || (strtolower(pa_sort_by) == "time")) {
			$sort_func = strtolower(pa_sort_by)."sort_";
		} else {
			$sort_func = "namesort_";
		}
		if ((strtolower(pa_sort_order) == "asc") || (strtolower(pa_sort_order) == "desc")) {
			$sort_func .= strtolower(pa_sort_order);
		} else {
			$sort_func .= "asc";
		}
		if (is_array($return)) usort($return, $sort_func);
		return $return;
	}

	function parseImgInfo($dir, $img) {
		$img_path = pa_image_dir . $dir . "/" . $img;
		if (file_exists($img_path)) {
			$return["date"] = date(pa_date_format, filemtime($img_path));
			$return["filesize"] = $this->FileSizeToString(filesize($img_path));
			$temp = getimagesize($img_path);
			$return["imageresultion"] = $temp[0] . "x" . $temp[1];
		}
		// XML info
		$xml_path = pa_image_dir . $dir . "/_info_" . $_GET["lang"] . ".xml";
		$return["xmlpath"] = $xml_path;
		if ((file_exists($xml_path)) && (function_exists("simplexml_load_file"))) {
			$xml_data = simplexml_load_file($xml_path);
			if (!empty($xml_data->$img)){
				$return["text"] = $xml_data->$img->text;
				$return["author"] = $xml_data->$img->author;
				$return["date"] = $xml_data->$img->date;
			}
		}
		return($return);
	}
	
	function parseImgExif($dir, $img) {
		$img_path = pa_image_dir . $dir . "/" . $img;
define("pa_enable_exif", true);
		if (pa_enable_exif) {
			$return = @read_exif_data ($img_path);
		}
		return $return;
	}
	
	function ImageOfDay(){
		$xml_path = "./pa_imageofday.xml";
		if ((file_exists($xml_path)) && (function_exists("simplexml_load_file"))) {
			$xml_data = simplexml_load_file($xml_path);
			$day = "day-".date("w");
			$img_of_day = $xml_data->$day;
			$img_of_day_path = pa_image_dir . $img_of_day;
			if (file_exists($img_of_day_path)){
				$img_of_day = explode("/", $img_of_day);
				for ($loop=0; $loop<(sizeof($img_of_day)-1); $loop++){
					if (!empty($return[0])) $return[0] .= "/";
					$return[0] .= $img_of_day[$loop];
				}
				$return[0] = "/" . $return[0];
				$return[1] = $img_of_day[(sizeof($img_of_day)-1)];
				$img_of_day_size = getimagesize($img_of_day_path);
				$return[2] = $img_of_day_size[0];
				$return[3] = $img_of_day_size[1];
			} else {
				$return = array("/", "", 1, 1);
			}
		} else {
			$return = array("/", "", 1, 1);
		}
		return ($return);
	}
	
	function ImageDirectLink(){
		if (!empty($_GET["folder"])) {
			$return = array($_GET["folder"], "", 1, 1);
			if (!empty($_GET["image"])) {
				$start_image_path = pa_image_dir . $_GET["folder"] . "/" . $_GET["image"];
				if (file_exists($start_image_path)) {
					$start_image = getimagesize($start_image_path);
					$return[1] = $_GET["image"];
					$return[2] = $start_image[0];
					$return[3] = $start_image[1];
				}
			}
		} else {
			$return = false;
		}
		return($return);
	}
	
}
// SORT FUNCTIONs (helper)
function namesort_asc($v1, $v2){
	return strcmp($v1['name'], $v2['name']);
}
function namesort_desc($v1, $v2){
	return strcmp($v2['name'], $v1['name']);
}
function timesort_asc($v1, $v2){
	if ($v1['time'] == $v2['time']) return 0;
	return ($v1['time'] < $v2['time']) ? -1 : 1;
}
function timesort_desc($v1, $v2){
	if ($v1['time'] == $v2['time']) return 1;
	return ($v1['time'] > $v2['time']) ? -1 : 1;
}
?>
