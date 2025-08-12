<?
$filename = safeFilename($filename);

header("Cache-control: private");
if (file_exists("$base_dir/$filename")) {
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$filename."");
	readfile("$base_dir/$filename");
} else {
	echo "Error: File \"$filename\" not found.";
}
exit();
?>