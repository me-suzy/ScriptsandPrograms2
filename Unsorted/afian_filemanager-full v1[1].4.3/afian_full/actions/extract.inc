<?
require_once("$config[root_dir]/class/pclzip/pclzip.lib.php");

$filename = safeFilename($filename);

$zip = new PclZip("$base_dir/$filename");

$extractTo = str_replace(".zip", "", $filename)."_Extracted";

for ($i = 1 ; $i <= 20 ; $i++) {
   if (!is_dir($base_dir."/".$extractTo.$i)) {
		$extractTo .= $i;
		break;
   }
}

if (is_file("$base_dir/$extractTo") || is_dir("$base_dir/$extractTo")) {
	$extractTo = $extractTo . rand (123, 234);
}

$zip->extract(PCLZIP_OPT_PATH, "$base_dir/$extractTo");

$reMsg = "Files extracted to \"$extractTo\".";

//echo $zip->errorInfo(true);

?>