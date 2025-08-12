<?
$reMsg = "";
for ($i = 0 ; $i < sizeof($item) ; $i++) {

	$item[$i] = safeFilename($item[$i]);
	$from_path = "$base_dir/".$item[$i]."";
	$to_path =  $config[root_dir] . "/clipboard/".$item[$i]."";

	//echo $from_path . "<br>" . $to_path . "<br>";
	if (is_dir($from_path)) {
		if (!is_dir($to_path)) {
		 	@create_path($to_path);
		}
		@rec_copy($from_path, $to_path);
		$reMsg .= "Folder \"".$item[$i]."\" copied to clipboard.<br>";
	} else {
	if (file_exists($to_path)) {
		$erMsg .= "Clipboard already contains a file named \"".$item[$i]."\". Paste it before copy or cut another file with same name.";
	} else {
	@copy($from_path, $to_path);
	clearstatcache();
	if (file_exists($to_path)) {
			$reMsg .= "File \"".$item[$i]."\" copied to clipboard.<br>";
		} else {
			$erMsg .= "Failed to copy file \"".$item[$i]."\". Click \"refresh\" button to see if file is still available.<br>";
		}
	}
	}
}

?>