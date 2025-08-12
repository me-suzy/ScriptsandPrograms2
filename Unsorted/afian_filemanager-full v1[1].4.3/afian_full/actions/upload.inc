<?
for ($i=0;$i<sizeof($_FILES['uploadfiles']['name']);$i++) {
	$uploadFilename = $_FILES['uploadfiles']['tmp_name'];
	$niceFilename = $_FILES['uploadfiles']['name'];
	$toFile = $base_dir."/".safeFilename($niceFilename[$i])."";


	if (file_exists($toFile) && $niceFilename[$i] != "") {
		$erMsg .= "A file with the name \"".$niceFilename[$i]."\" already exists.";
	} else {
		if (is_uploaded_file($uploadFilename[$i])) {
			
			if (copy($uploadFilename[$i], $toFile)) {
				$reMsg .= "File \"".$niceFilename[$i]."\" uploaded.<br>";
			} else {
				$erMsg .= "Unable to move uploaded file \"".$niceFilename[$i]."\" to current folder. (Check this folder's permissions.)<br>";
			}
		}
	}
}

?>