<?
$reMsg = "";
if ($submit == "paste") {
	for ($i = 0 ; $i < sizeof($clipfiles) ; $i++) {

	$clipfiles[$i] = safeFilename($clipfiles[$i]);
	$from_path =  $config[root_dir] . "/clipboard/".$clipfiles[$i]."";
	$to_path = "$base_dir/".$clipfiles[$i]."";

			if (is_dir($from_path)) {
				if (!is_dir($to_path)) {
			 		@create_path($to_path);
					//echo $to_path;
					@rec_copy($from_path, $to_path);
					@delete_dir($from_path);
					$reMsg .= "Folder \"".$clipfiles[$i]."\" pasted.<br>";
				} else {
					$erMsg .= "A folder named \"$clipfiles[$i]\" already exists.<br>";
				}

			} else {
				if (file_exists($from_path)) {
					if (file_exists($to_path)) {
						$erMsg .= "A file named \"".$clipfiles[$i]."\" already exists.<br>";
					} else {
						if (@rename($from_path, $to_path)) {
						$reMsg .= "File \"".$clipfiles[$i]."\" pasted.<br>";
						} else {
						$erMsg .= "Failed to paste file \"".$clipfiles[$i]."\".<br>";
						}

					}
				} else {
					$erMsg .= "File \"".$clipfiles[$i]."\" not in clipboard.<br>";
				}
			}
	}
} else {
	for ($i = 0 ; $i < sizeof($clipfiles) ; $i++) {
	$clipfiles[$i] = stripslashes($clipfiles[$i]);
	$from_path =  $config[root_dir] . "/clipboard/".$clipfiles[$i]."";
		if (is_dir($from_path)) {
			@delete_dir($from_path);
			$reMsg .= "Folder \"$clipfiles[$i]\" deleted from clipboard.<br>";
		} else {
			if(@unlink($from_path)) {
			$reMsg .= "File \"$clipfiles[$i]\" deleted from clipboard.<br>";
			} else {
				$erMsg .= "Failed to delete file \"".$clipfiles[$i]."\" from clipboard.<br>";
			}
		}
	}
}


?>