<?

$reMsg = "";
for ($i = 0 ; $i < sizeof($item) ; $i++) {
	$item[$i] = safeFilename($item[$i]);
	$from_path = $base_dir."/".$item[$i]."";
	$tmpfname = md5(rand(0, 1000) . $item[$i] . rand(0, 1000));
	$to_path =  $config[root_dir] . "/trash/".$tmpfname."";

if (!eregi("win", strtolower(php_uname()))) {
	@superChmod ($from_path, "777");
}

	if (is_dir($from_path)) {
		if (!is_dir($to_path)) {
		 	@create_path($to_path);
		}
		@rec_copy($from_path, $to_path);
		@delete_dir($from_path);
		addToLog($item[$i], $dir."/".$item[$i], $tmpfname);
		$reMsg .= "Folder \"".$item[$i]."\" moved to trash.<br>";
	} else {
		if (file_exists($from_path)) {
			if (!@rename($from_path, $to_path)) {
				$erMsg .= "Failed to move \"".$item[$i]."\" to trash. (Check file permissions.)<br>";
			} else {
				addToLog($item[$i], $dir."/".$item[$i], $tmpfname);
				$reMsg .= "File \"".$item[$i]."\" moved to trash.<br>";
			}
			} else {
			$erMsg .= "\"".$item[$i]."\" doesn't exists.<br>";
			}
	}
}

function addToLog($filename, $restorePath, $tempname){
	global $config;
	$fd = fopen ("$config[root_dir]/trashundo.txt", "a+");
	$line = $restorePath . " @#@ " . $filename . " @#@ " . $tempname . "\r\n";
	fputs($fd, $line);
	fclose ($fd);
}
?>