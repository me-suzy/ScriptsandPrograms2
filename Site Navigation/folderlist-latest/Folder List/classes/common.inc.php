<?php
/* FolderList v1.1 - by Bruno_Funny $$ classes/common.inc.php */

class Common {

	function formatSize($v){
	    $units = array('b', 'kb', ' mb', 'gb', 'tb');
	    for($i=0; $v > 1024 && $i < count($units) - 1; $i++, $v /= 1024);
	    return number_format($v, 2, ',', '.') . ' ' . $units[$i];
	}
	
	function getFolderSize($var) {
		$dh = opendir($var);
		$size = 0;
		while(($file = readdir($dh)) !== false) {
			//(@filetype($dir ."/". $file) != "dir") 
			$size += filesize($var ."/". $file);
			/*if(@filetype($var ."/". $file) != "dir") { 
				$size += filesize($var ."/". $file);
			} else {
				$this->getFolderSize($var."/".$entry);
			}*/
		}
		if($size == 0) { return "empty"; } else { return $this->formatSize($size); }
	}
}

?>