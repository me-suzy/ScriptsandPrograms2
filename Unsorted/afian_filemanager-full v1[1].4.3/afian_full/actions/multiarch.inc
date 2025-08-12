<?
$archName = "ZipArchive";

for ($i = 1 ; $i < 20; $i++) {
   if (!file_exists($base_dir."/".$archName.$i.".zip")) {
		$archName .= $i;
		break;
   }
}
$archName .= ".zip";


if (sizeof($item) > 0) {
	require_once("$config[root_dir]/class/pclzip/pclzip.lib.php");
	$zip = new PclZip("$base_dir/$archName");
	$reMsg .= "Archive \"$archName\" created.<br>";
	for($i=0;$i<sizeof($item);$i++) {

		$item[$i] = safeFilename($item[$i]);
		$filelist = $zip->add("$base_dir/$item[$i]", PCLZIP_OPT_REMOVE_PATH, "$base_dir/");

		if (is_dir("$base_dir/$item[$i]")) {
			$reMsg .= "Folder \"".$item[$i]."\" added to created archive.<br>";
		} else {
			$reMsg .= "File \"".$item[$i]."\" added  to created archive.<br>";
		}
	}
	if ($filelist == 0) {
		die("Error : ".$zip->errorInfo(true));
	}
} else {
	$erMsg = "Please select a file to archive.";
}



/*
// show info about added files
$list = $filelist;
for ($i=0; $i<sizeof($list); $i++) {
    for(reset($list[$i]);$key = key($list[$i]);next($list[$i])) {
     	$reMsg .= "File $i / [$key] = ".$list[$i][$key]."<br>";
    }
}
*/
?>