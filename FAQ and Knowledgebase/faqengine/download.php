<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('./config.php');
require('./functions.php');
$sql = "select * from ".$tableprefix."_misc";
if(!$result = faqe_db_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = faqe_db_fetch_array($result))
{
	if($myrow["shutdown"]==1)
		die("Service not available at the moment");
}
if(!isset($attachnr))
	die("Calling error");
$sql="select * from ".$tableprefix."_files where entrynr='$attachnr'";
if(!$result=faqe_db_query($sql,$db))
	die("unable to connect to database");
if(!$myrow=faqe_db_fetch_array($result))
	die("no attachement for this entry");
$content_type="Content-type: ".$myrow["mimetype"]."\n";
$filename=$myrow["filename"];
if(!$myrow["bindata"])
{
	if(!$attach_in_fs)
		die("no attachement for this entry (2)");
	if(!file_exists($path_attach."/".$myrow["fs_filename"]))
		die("no attachement for this entry (3)");
	$fsfile=@fopen($path_attach."/".$myrow["fs_filename"],"rb");
	if(!$fsfile)
	{
		if(!$dodebug)
			die("no attachement for this entry (4)");
		else
		{
			$errmsg.="Unable to open ".$path_attach."/".$myrow["fs_filename"]." for reading";
			die($errmsg);
		}
	}
	$filedata=fread($fsfile,filesize($path_attach."/".$myrow["fs_filename"])+1);
	fclose($fsfile);
}
else
	$filedata=$myrow["bindata"];
if(!isset($nodlcount))
{
	$tmpsql = "UPDATE ".$tableprefix."_files SET downloads = downloads + 1 WHERE (entrynr='$attachnr')";
	@faqe_db_query($tmpsql, $db);
}
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