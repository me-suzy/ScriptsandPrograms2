<?

if ($action == "move") {
	for ($i = 0 ; $i < sizeof($trashfiles) ; $i++) {
		$fileInfo = getLogInfo($trashfiles[$i]);
		$from_path = trim($config[root_dir] . "/trash/".$fileInfo[2]);
		$to_path = "$base_dir/".trim($fileInfo[1])."";
		//do something
		if (is_dir($from_path)) {
				if (!is_dir($to_path)) {
			 		create_path($to_path);
				}
			rec_copy($from_path, $to_path);
			delete_dir($from_path);
			getLogInfo($trashfiles[$i], true);
			$reMsg .= "Folder \"".$fileInfo[1]."\" moved here from trash.<br>";
		} else {
			if (@rename($from_path, $to_path)) {
				$reMsg .= "File \"".$fileInfo[1]."\" moved here from trash.<br>";
				getLogInfo($trashfiles[$i], true);
			} else {
				$erMsg .= "Failed to move file from trash.<br>";
			}
		}
	}
} elseif($action == "delete") {
	for ($i = 0 ; $i < sizeof($trashfiles) ; $i++) {
		$fileInfo = getLogInfo($trashfiles[$i], true);
		$from_path = trim($config[root_dir] . "/trash/".$fileInfo[2]);
		if (is_dir($from_path)) {
			delete_dir($from_path);
			$reMsg .= "Folder \"".$fileInfo[1]."\" deleted from trash.<br>";
		} else {
			unlink($from_path);
			$reMsg .= "File \"".$fileInfo[1]."\" deleted from trash.<br>";
		}
	}
} elseif($action == "restore") {
	for ($i = 0 ; $i < sizeof($trashfiles) ; $i++) {
		$fileInfo = getLogInfo($trashfiles[$i]);
		$from_path = trim($config[root_dir] . "/trash/".$fileInfo[2]);
		//do something
		$to_path = trim($config[base_dir] . $fileInfo[0]);
		if (is_dir($from_path)) {
				if (!is_dir($to_path)) {
			 		create_path($to_path);
				}
			rec_copy($from_path, $to_path);
			delete_dir($from_path);
			getLogInfo($trashfiles[$i], true);
			$pathInfo = pathinfo($fileInfo[0]);
			if ($pathInfo[dirname]=="\\" || $pathInfo[dirname]=="/") {
				$pathInfo[dirname]="";
			}
				$reMsg .= "File restored to \"<a href=\"".linkToDir($fileInfo[0])."\" style=\"text-decoration:none;\">".$pathInfo[dirname]."</a>/".$pathInfo[basename]."\"<br>";
		} else {
			if (@rename($from_path, $to_path)) {
			$pathInfo = pathinfo($fileInfo[0]);
			if ($pathInfo[dirname]=="\\" || $pathInfo[dirname]=="/") {
				$pathInfo[dirname]="";
			}
				$reMsg .= "File restored to \"<a href=\"".linkToDir($fileInfo[0])."\" style=\"text-decoration:none;\">".$pathInfo['dirname']."</a>/".$pathInfo['basename']."\"<br>";
				getLogInfo($trashfiles[$i], true);
			} else {
				$erMsg .= "Failed to restore file to \"".$fileInfo[0]."\". (Check the path for availability.)<br>";
			}
		}
	}
}



function getLogInfo($tempname, $delete = false){
	global $config;
	$fd = @fopen ("$config[root_dir]/trashundo.txt", "r");
	while (!feof ($fd)) {
		$line = @fgets($fd, 4096);
		if (strlen($line) > 3) {
			$splited = split(" @#@ ", $line);
			if ($splited[2] == $tempname) {
				$return = $splited;
				if (!$delete) {
					$fileContents .= $line . "\n";
				}
			} else {
				$fileContents .= $line . "\n";
			}
		}
	}
	@fclose ($fd);
	if ($delete) {
		$fd = @fopen ("$config[root_dir]/trashundo.txt", "w");
		@fputs($fd, $fileContents);
		@fclose ($fd);
	}
	return $return;
}
?>
