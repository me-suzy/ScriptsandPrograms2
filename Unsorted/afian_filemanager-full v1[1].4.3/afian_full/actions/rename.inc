<?
$oldName = safeFilename($oldName);
$newName = safeFilename($newName);

if (is_dir($base_dir."/".$oldName)) {
	$objectType = "folder";
} else {
	$objectType = "file";
}

if (@rename("$base_dir/$oldName", "$base_dir/$newName")) {
	$reMsg = ucfirst($objectType)." renamed from \"$oldName\" to \"$newName\".";
} else {
	$erMsg = "Unable to rename ".ucfirst($objectType)." from \"$oldName\" to \"$newName\". (Check ".$objectType."'s permissions.)";
}
?>