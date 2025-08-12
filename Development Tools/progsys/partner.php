<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
// partnersite click counter
require("./config.php");
require_once("./functions.php");
if(!isset($lang) || !$lang)
	$lang=$default_lang;
$actdate = date("Y-m-d");
if(!isset($sitenr))
	die("calling error");
$sql = "select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to database.");
if ($myrow = mysql_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Partnersite</title>
</head>
<body>
<div align="center"><?php echo $shutdowntext?></div>
</body></html>
<?php
		exit;
	}
}
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if ($myrow = mysql_fetch_array($result))
{
	$checkrefs=$myrow["checkrefs"];
	$refchkaffects=$myrow["refchkaffects"];
}
else
	die("Layout not set up");
$e404="no such site ";
$sql="select * from ".$tableprefix."_partnersites where sitenr='$sitenr'";
if(!$result=mysql_query($sql,$db))
	die("unable to connect to database".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die($e404);
$redirurl=$myrow["siteurl"];
$sql = "select * from ".$tableprefix."_partnerclicks where sitenr='$sitenr' and day='$actdate'";
if(!$result=mysql_query($sql,$db))
	die("unable to connect to database");
if(mysql_num_rows($result) > 0)
{
	$sql = "update ".$tableprefix."_partnerclicks set clicks=clicks+1 where sitenr='$sitenr' and day='$actdate'";
	if(!$result=mysql_query($sql,$db))
		die("unable to connect to database");
}
else
{
	$sql = "insert into ".$tableprefix."_partnerclicks (sitenr, day, clicks) values ($sitenr, '$actdate', 1)";
	if(!$result=mysql_query($sql,$db))
		die("unable to connect to database");
}
$downloadurl="Location: ".$redirurl;
header($downloadurl);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>
