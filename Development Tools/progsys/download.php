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
// download counter
require("./config.php");
require_once("./functions.php");
if(!isset($lang) || !$lang)
	$lang=$default_lang;
$actdate = date("Y-m-d");
if(!isset($url) && !isset($filenr))
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
<title>Download</title>
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
$sql = "select * from ".$tableprefix."_texts where textid='e404' and lang='$lang'";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
if($myrow=mysql_fetch_array($result))
{
	$e404=stripslashes($myrow["text"]);
	$e404=undo_htmlspecialchars($e404);
}
else
	$e404="no such file";
$sql = "select * from ".$tableprefix."_texts where textid='nolk' and lang='$lang'";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
if($myrow=mysql_fetch_array($result))
{
	$nolk=stripslashes($myrow["text"]);
	$nolk=undo_htmlspecialchars($nolk);
}
else
	$nolk="Direct linking from this site ({refsite}) not allowed";
$nolk=str_replace("{refsite}",$HTTP_REFERER,$nolk);
$sql = "select * from ".$tableprefix."_texts where textid='tmpdis' and lang='$lang'";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
if($myrow=mysql_fetch_array($result))
{
	$tmpdis=stripslashes($myrow["text"]);
	$tmpdis=undo_htmlspecialchars($tmpdis);
}
else
	$tmpdis="temporary unavailable";
if(($checkrefs==1) && bittst($refchkaffects,BIT_1))
{
	if(!ref_allowed())
		die($nolk);
}
else if($checkrefs==2)
{
	if(ref_forbidden())
		die($nolk);
}
if(isset($url))
{
	$sql = "select * from ".$tableprefix."_download_files where url='$url'";
	if(!$result=mysql_query($sql,$db))
		die("unable to connect to database".mysql_error());
	if(!$myrow=mysql_fetch_array($result))
	{
		if(!$auto_download_file)
			die($e404);
		$sql = "insert into ".$tableprefix."_download_files (description, url, programm) ";
		$sql.= "values ('auto added','$url',0)";
		if(!$result=mysql_query($sql,$db))
			die("unable to connect to database".mysql_error());
		$filenr=mysql_insert_id();
	}
	else
		$filenr=$myrow["filenr"];
}
$sql="select * from ".$tableprefix."_download_files  where filenr='$filenr'";
if(!$result=mysql_query($sql,$db))
	die("unable to connect to database".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die($e404);
if($myrow["downloadenabled"]!=1)
	die($tmpdis);
$sql="select * from ".$tableprefix."_download_files where filenr='$filenr'";
if(!$result=mysql_query($sql,$db))
	die("unable to connect to database".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die($e404);
$downloadurl="";
if($myrow["mirrorserver"]>0)
{
	$tmpsql="select * from ".$tableprefix."_mirrorserver where servernr=".$myrow["mirrorserver"];
	if(!$tmpresult=mysql_query($tmpsql,$db))
		die("unable to connect to database".mysql_error());
	if(!$tmprow=mysql_fetch_array($tmpresult))
		die($e404);
	if(strlen($tmprow["downurl"])>0)
	{
		$tmpurl=$tmprow["downurl"];
		if($tmpurl[strlen($tmpurl)-1]!="/")
			$tmpurl.="/";
		if(($tmpurl[0]=="/") && (strlen($downloadurl)>0) && ($downloadurl[strlen($downloadurl)-1]=="/"))
			$tmpurl=substr($tmpurl,1);
		$downloadurl.=$tmpurl;
	}
}
if($myrow["programm"]>0)
{
	$tmpsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
	if(!$tmpresult=mysql_query($tmpsql,$db))
		die("unable to connect to database".mysql_error());
	if(!$tmprow=mysql_fetch_array($tmpresult))
		die($e404);
	if($myrow["betaversion"]==0)
	{
		if(strlen($tmprow["downpath"])>0)
		{
			$tmpurl=$tmprow["downpath"];
			if($tmpurl[strlen($tmpurl)-1]!="/")
				$tmpurl.="/";
			if(($tmpurl[0]=="/") && (strlen($downloadurl)>0) && ($downloadurl[strlen($downloadurl)-1]=="/"))
				$tmpurl=substr($tmpurl,1);
			$downloadurl.=$tmpurl;
		}
	}
	else
	{
		if(strlen($tmprow["betapath"])>0)
		{
			$tmpurl=$tmprow["betapath"];
			if($tmpurl[strlen($tmpurl)-1]!="/")
				$tmpurl.="/";
			if(($tmpurl[0]=="/") && (strlen($downloadurl)>0) && ($downloadurl[strlen($downloadurl)-1]=="/"))
				$tmpurl=substr($tmpurl,1);
			$downloadurl.=$tmpurl;
		}
	}
}
$downloadurl.=$myrow["url"];
$sql = "select * from ".$tableprefix."_counts where entrynr=1";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
if(!$myrow=mysql_fetch_array($result))
{
	$sql = "insert into ".$tableprefix."_counts (lastdownload) values ('$actdate')";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to database.");
}
else
{
	list($tmpyear, $tmpmonth, $tmpday) = explode("-", $myrow["lastdownload"]);
	$tmpdate=getdate(mktime(0,0,0,$tmpmonth,1,$tmpyear));
	$date_today=getdate();
	if($date_today["mon"]!=$tmpdate["mon"])
	{
 		if($compress_download_stats)
 		{
 			$startdate=$tmpyear."-".$tmpmonth."-01";
			$sql = "select * from ".$tableprefix."_download_files";
			if(!$result = mysql_query($sql, $db))
			    die("Could not connect to database.");
			while($myrow=mysql_fetch_array($result))
			{
				$tempsql = "select sum(uni) as uni, sum(raw) as raw from ".$tableprefix."_downloads ";
				$tempsql.= "where day >= '$startdate' and day < date_add('$startdate', interval 1 month) and filenr=".$myrow["filenr"]." group by filenr";
				if(!$tempresult = mysql_query($tempsql, $db))
				    die("Could not connect to database.<br>".mysql_error());
				if($temprow=mysql_fetch_array($tempresult))
				{
					if($temprow["uni"]>0)
					{
						$tempsql2 = "insert into ".$tableprefix."_compr_downloads (month, filenr, raw, uni) values ('$startdate', ".$myrow["filenr"].", ".$temprow["raw"].", ".$temprow["uni"].")";
						if(!$tempresult2 = mysql_query($tempsql2, $db))
						    die("Could not connect to database.<br>".mysql_error());
						$tempsql2 = "delete from ".$tableprefix."_downloads where day >= '$startdate' and day < date_add('$startdate', interval 1 month) and filenr=".$myrow["filenr"];
						if(!$tempresult2 = mysql_query($tempsql2, $db))
						    die("Could not connect to database.<br>".mysql_error());
						$tempsql2 = "delete from ".$tableprefix."_download_ips where day >= '$startdate' and day < date_add('$startdate', interval 1 month) and filenr=".$myrow["filenr"];
						if(!$tempresult2 = mysql_query($tempsql2, $db))
						    die("Could not connect to database.<br>".mysql_error());
					}
				}
			}
		}
	}
	$sql = "update ".$tableprefix."_counts set lastdownload='$actdate' where entrynr=1";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to database.");
}
$sql = "select * from ".$tableprefix."_downloads where filenr='$filenr' and day='$actdate'";
if(!$result=mysql_query($sql,$db))
	die("unable to connect to database");
if(mysql_num_rows($result) > 0)
{
	$sql = "select UNIX_TIMESTAMP(time) from ".$tableprefix."_download_ips where filenr='$filenr' and ipadr='".get_userip()."' and day='$actdate'";
	if(!$result=mysql_query($sql,$db))
		die("unable to connect to database");
	if($myrow = mysql_fetch_row($result))
	{
		if(($myrow[0]+3600) < time())
		{
			$sql = "update ".$tableprefix."_downloads set raw=raw+1, uni=uni+1 where filenr='$filenr' and day='$actdate'";
			if(!$result=mysql_query($sql,$db))
				die("unable to connect to database");
			$sql = "update ".$tableprefix."_download_ips set time=NOW() where filenr='$filenr' and ipadr='".get_userip()."' and day='$actdate'";
			if(!$result=mysql_query($sql,$db))
				die("unable to connect to database");
		}
		else
		{
			$sql = "update ".$tableprefix."_downloads set raw=raw+1 where filenr='$filenr' and day='$actdate'";
			if(!$result=mysql_query($sql,$db))
				die("unable to connect to database");
		}
	}
	else
	{
		$sql = "update ".$tableprefix."_downloads set raw=raw+1, uni=uni+1 where filenr='$filenr' and day='$actdate'";
		if(!$result=mysql_query($sql,$db))
			die("unable to connect to database");
		$sql = "insert into ".$tableprefix."_download_ips (filenr, ipadr, day) values ($filenr, '".get_userip()."', '$actdate')";
		if(!$result=mysql_query($sql,$db))
			die("unable to connect to database");
	}
}
else
{
	$sql = "insert into ".$tableprefix."_download_ips (filenr, ipadr, day) values ($filenr, '".get_userip()."', '$actdate')";
	if(!$result=mysql_query($sql,$db))
		die("unable to connect to database");
	$sql = "insert into ".$tableprefix."_downloads (filenr, day, raw, uni) values ($filenr, '$actdate', 1, 1)";
	if(!$result=mysql_query($sql,$db))
		die("unable to connect to database");
}
$downloadurl="Location: ".$downloadurl;
header($downloadurl);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>
