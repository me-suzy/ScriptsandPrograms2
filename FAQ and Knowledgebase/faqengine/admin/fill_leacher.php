<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function fill_leacher($tableprefix, $db)
{
$arr = array(
"Web Downloader",
"WebZIP",
"WebStripper",
"Offline Explorer",
"Wget",
"WebCopier",
"Teleport Pro",
"ImageGrabDummy!",
"EmailSiphon",
"WebReaper"
);
for($i = 0; $i< count($arr); $i++)
{
	$sql = "INSERT INTO ".$tableprefix."_leachers (useragent) ";
	$sql .="values ('".$arr[$i]."')";
	if(!$result = mysql_query($sql, $db))
		die("Unable to insert data into ".$tableprefix."_leachers ($i)");
}
}
?>
