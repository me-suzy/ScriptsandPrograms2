<?
$reMsg = "";
for ($i = 0 ; $i < sizeof($item) ; $i++) {
	$item[$i] = safeFilename($item[$i]);
	$from_path = "$base_dir/".$item[$i]."";
	$to_path =  $config[root_dir] . "/clipboard/".$item[$i]."";
	//@chmod ($from_path, "0666");

	if (is_dir($from_path)) {
		if (!is_dir($to_path)) {
		 	@create_path($to_path);
		}
		@rec_copy($from_path, $to_path);
		@delete_dir($from_path);
		$reMsg .= "Folder \"".$item[$i]."\" moved to clipboard.<br>";
	} elseif (is_file($from_path)) {
			if (!@rename($from_path, $to_path)) {
				$erMsg .= "Failed to move \"".$item[$i]."\" to clipboard. (Check the clipboard for a file with same name.)<br>";
			} else {
				$reMsg .= "File \"".$item[$i]."\" moved to clipboard.<br>";
			}
	} else {
		$erMsg .= "\"".$item[$i]."\" doesn't exists.<br>";
	}
}
?>