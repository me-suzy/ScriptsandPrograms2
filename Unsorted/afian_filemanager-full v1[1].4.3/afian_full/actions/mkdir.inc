<?
$dirName = safeFilename($dirName);

if (strlen($dirName) > 0) {
$res = @mkdir ("$base_dir/$dirName", 0777);

	if ($res) {
		$reMsg = "Directory \"$dirName\" created.";
	} else {
		if (is_dir("$base_dir/$dirName")) {
			$erMsg = "A folder with the name you specified already exists. (Specify another name.)";
		} else {
			$erMsg = "Failed to create dir. (Check working folder's permissions.)";
		}
		
	}
}
?>