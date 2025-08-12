<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
$sql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("SimpNews not set up.");
$usedlcounts=$myrow["usedlcounts"];
$sql = "select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	if($myrow["shutdown"]==1)
		die("Service not available at the moment");
}
if(!isset($entrynr))
	die("Calling error");
$sql="select * from ".$tableprefix."_files where entrynr=$entrynr";
if(!$result=mysql_query($sql,$db))
	die("unable to connect to database");
if(!$myrow=mysql_fetch_array($result))
	die("no such attachement");
if(!$myrow["bindata"])
{
	if(!$attach_in_fs)
		die("no attachement for this entry (2)");
	$fsfile=@fopen($path_attach."/".$myrow["fs_filename"],"r");
	if(!$fsfile)
		die("no attachement for this entry (3)");
	$filedata=fread($fsfile,filesize($path_attach."/".$myrow["fs_filename"])+1);
}
else
	$filedata=$myrow["bindata"];
if(($usedlcounts==1) && !isset($nodlcount))
{
	$tmpsql = "UPDATE ".$tableprefix."_files SET downloads = downloads + 1 WHERE (entrynr=$entrynr)";
	@mysql_query($tmpsql, $db);
}
$content_type="Content-type: ".$myrow["mimetype"]."\n";
$filename=$myrow["filename"];
header($content_type);
header("Content-Disposition: atachment; filename=$filename");
header("Content-Transfer-Encoding: binary\n");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Length: " . $myrow["filesize"] . "\n");
echo $filedata;
?>