<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$page_title=$l_files_cleanup;
require_once('./heading.php');
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(isset($mode))
{
	if(($mode=="refclean") || ($mode=="cleanall"))
	{
		$ancleaned=0;
		$evcleaned=0;
		$newscleaned=0;
		$sql="select * from ".$tableprefix."_announce_attachs";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
		while($myrow=mysql_fetch_array($result))
		{
			$tmpsql="select * from ".$tableprefix."_announce where entrynr=".$myrow["announcenr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
			if(mysql_num_rows($tmpresult)<1)
			{
				$tmpsql="delete from ".$tableprefix."_announce_attachs where entrynr=".$myrow["entrynr"];
				if(!$tmpresult=mysql_query($tmpsql,$db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete. ".mysql_error());
				$ancleaned++;
			}
		}
		$sql="select * from ".$tableprefix."_news_attachs";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
		while($myrow=mysql_fetch_array($result))
		{
			$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["newsnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
			if(mysql_num_rows($tmpresult)<1)
			{
				$tmpsql="delete from ".$tableprefix."_news_attachs where newsnr=".$myrow["newsnr"];
				if(!$tmpresult=mysql_query($tmpsql,$db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete. ".mysql_error());
				$newscleaned++;
			}
		}
		$sql="select * from ".$tableprefix."_events_attachs";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
		while($myrow=mysql_fetch_array($result))
		{
			$tmpsql="select * from ".$tableprefix."_events where eventnr=".$myrow["eventnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
			if(mysql_num_rows($tmpresult)<1)
			{
				$tmpsql="delete from ".$tableprefix."_events_attachs where eventnr=".$myrow["eventnr"];
				if(!$tmpresult=mysql_query($tmpsql,$db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete. ".mysql_error());
				$evcleaned++;
			}
		}
		echo "<tr class=\"inforow\"><td align=\"center\"><b>$l_orphan_refs</b></td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		printf($l_cleaned_up,$ancleaned,$evcleaned,$newscleaned);
		echo "</td></tr>";
	}
	if(($mode=="fileclean") || ($mode=="cleanall"))
	{
		$cleanedfiles=0;
		$dir = opendir($path_attach);
		while ($file = readdir($dir))
		{
			if(is_file($path_attach."/".$file))
			{
				$tmpsql="select * from ".$tableprefix."_files where fs_filename='".$file."'";
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
				if(mysql_num_rows($tmpresult)==0)
				{
					$cleanedfiles++;
					if(!unlink($path_attach."/".$file))
						die("<tr class=\"errorrow\"><td>$l_cantdelete ".$path_attach."/".$file);
				}
			}
		}
		echo "<tr class=\"inforow\"><td align=\"center\"><b>";
		printf ($l_orphan_files,$path_attach);
		echo "</b></td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		printf($l_cleaned_up_files,$cleanedfiles);
		echo "</td></tr>";
	}
	if(($mode=="binclean") || ($mode=="cleanall"))
	{
		$cleanedfiles=0;
		$tmpsql="select * from ".$tableprefix."_files where bindata !='' and fs_filename=''";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			$cleanedfiles++;
			$delsql="DELETE FROM ".$tableprefix."_events_attachs where attachnr=".$tmprow["entrynr"];
			if(!$delresult = mysql_query($delsql, $db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
			$delsql="DELETE FROM ".$tableprefix."_announce_attachs where attachnr=".$tmprow["entrynr"];
			if(!$delresult = mysql_query($delsql, $db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
			$delsql="DELETE FROM ".$tableprefix."_news_attachs where attachnr=".$tmprow["entrynr"];
			if(!$delresult = mysql_query($delsql, $db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
			$delsql="DELETE FROM ".$tableprefix."_tmpevents_attachs where attachnr=".$tmprow["entrynr"];
			if(!$delresult = mysql_query($delsql, $db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
			$delsql="DELETE FROM ".$tableprefix."_tmpnews_attachs where attachnr=".$tmprow["entrynr"];
			if(!$delresult = mysql_query($delsql, $db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
			$delsql="DELETE FROM ".$tableprefix."_files where entrynr=".$tmprow["entrynr"];
			if(!$delresult = mysql_query($delsql, $db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
		}
		echo "<tr class=\"inforow\"><td align=\"center\"><b>";
		echo "$l_orphan_bindata";
		echo "</b></td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		printf($l_cleaned_up_entries,$cleanedfiles);
		echo "</td></tr>";
	}
	if(($mode=="frefclean") || ($mode=="cleanall"))
	{
		$cleanedfiles=0;
		$tmpsql="select * from ".$tableprefix."_files where fs_filename!=''";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			$filename=stripslashes($tmprow["fs_filename"]);
			if(!file_exists($path_attach."/".$filename))
			{
				$cleanedfiles++;
				$delsql="DELETE FROM ".$tableprefix."_events_attachs where attachnr=".$tmprow["entrynr"];
				if(!$delresult = mysql_query($delsql, $db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
				$delsql="DELETE FROM ".$tableprefix."_announce_attachs where attachnr=".$tmprow["entrynr"];
				if(!$delresult = mysql_query($delsql, $db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
				$delsql="DELETE FROM ".$tableprefix."_news_attachs where attachnr=".$tmprow["entrynr"];
				if(!$delresult = mysql_query($delsql, $db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
				$delsql="DELETE FROM ".$tableprefix."_tmpevents_attachs where attachnr=".$tmprow["entrynr"];
				if(!$delresult = mysql_query($delsql, $db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
				$delsql="DELETE FROM ".$tableprefix."_tmpnews_attachs where attachnr=".$tmprow["entrynr"];
				if(!$delresult = mysql_query($delsql, $db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
				$delsql="DELETE FROM ".$tableprefix."_files where entrynr=".$tmprow["entrynr"];
				if(!$delresult = mysql_query($delsql, $db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".mysql_error());
			}
		}
		echo "<tr class=\"inforow\"><td align=\"center\"><b>";
		printf ($l_orphan_frefs,$path_attach);
		echo "</b></td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		printf($l_cleaned_up_entries,$cleanedfiles);
		echo "</td></tr>";
	}
}
else if($attach_in_fs)
{
	$numoptions=0;
	$orphanfiles=array();
	$dir = opendir($path_attach);
	while ($file = readdir($dir))
	{
		if(is_file($path_attach."/".$file))
		{
			$tmpsql="select * from ".$tableprefix."_files where fs_filename='".$file."'";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
			if(mysql_num_rows($tmpresult)==0)
				array_push($orphanfiles,$file);
		}
	}
	if(count($orphanfiles)>0)
	{
		$numoptions++;
		echo "<tr class=\"inforow\"><td align=\"center\"><b>";
		printf ($l_orphan_files,$path_attach);
		echo "</b></td></tr>";
		while(list($null, $curfile) = each($orphanfiles))
		{
			echo "<tr class=\"displayrow\"><td align=\"center\">$curfile</td></tr>";
		}
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"fileclean\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_orphan_files\" class=\"snbutton\"></td></tr>";
		echo "</form>";
	}
	$tmpsql="select * from ".$tableprefix."_files where bindata !='' and fs_filename=''";
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
	if(mysql_num_rows($tmpresult)>0)
	{
		$numoptions++;
		echo "<tr class=\"inforow\"><td align=\"center\"><b>";
		echo "$l_orphan_bindata";
		echo "</b></td></tr>";
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			echo "<tr class=\"displayrow\"><td align=\"center\">".$tmprow["entrynr"]." - ".$tmprow["filename"]."</td></tr>";
		}
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"binclean\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_orphan_bindata\" class=\"snbutton\"></td></tr>";
		echo "</form>";
	}
	$orphanrefs=array();
	$tmpsql="select * from ".$tableprefix."_files where fs_filename!=''";
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
	while($tmprow=mysql_fetch_array($tmpresult))
	{
		$filename=stripslashes($tmprow["fs_filename"]);
		if(!file_exists($path_attach."/".$filename))
			array_push($orphanrefs,$filename);
	}
	if(count($orphanrefs)>0)
	{
		$numoptions++;
		echo "<tr class=\"inforow\"><td align=\"center\"><b>";
		printf ($l_orphan_files,$path_attach);
		echo "</b></td></tr>";
		while(list($null, $curfile) = each($orphanrefs))
		{
			echo "<tr class=\"displayrow\"><td align=\"center\">$curfile</td></tr>";
		}
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"frefclean\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_orphan_frefs\" class=\"snbutton\"></td></tr>";
		echo "</form>";
	}
	$numdeadrefs=0;
	$sql="select * from ".$tableprefix."_announce_attachs";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		$tmpsql="select * from ".$tableprefix."_announce where entrynr=".$myrow["announcenr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
		if(mysql_num_rows($tmpresult)<1)
			$numdeadrefs++;
	}
	$sql="select * from ".$tableprefix."_news_attachs";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["newsnr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
		if(mysql_num_rows($tmpresult)<1)
			$numdeadrefs++;
	}
	$sql="select * from ".$tableprefix."_events_attachs";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		$tmpsql="select * from ".$tableprefix."_events where eventnr=".$myrow["eventnr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
		if(mysql_num_rows($tmpresult)<1)
			$numdeadrefs++;
	}
	if($numdeadrefs>0)
	{
		$numoptions++;
		echo "<tr class=\"inforow\"><td align=\"center\"><b>";
		echo $l_orphan_refs;
		echo "</b></td></tr>";
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"refclean\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_orphan_refs\" class=\"snbutton\"></td></tr>";
		echo "</form>";
	}
	if($numoptions>1)
	{
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"cleanall\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_all\" class=\"snbutton\"></td></tr>";
		echo "</form>";
	}
	if($numoptions==0)
	{
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		echo $l_nothing_2_cleanup;
		echo "</td></tr>";
	}
}
else
{
	echo "<tr class=\"inforow\"><td align=\"center\">";
	echo $l_fcleanupwarning;
	echo "</td></tr>";
	$numdeadrefs=0;
	$sql="select * from ".$tableprefix."_announce_attachs";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		$tmpsql="select * from ".$tableprefix."_announce where entrynr=".$myrow["announcenr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
		if(mysql_num_rows($tmpresult)<1)
			$numdeadrefs++;
	}
	$sql="select * from ".$tableprefix."_news_attachs";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["newsnr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
		if(mysql_num_rows($tmpresult)<1)
			$numdeadrefs++;
	}
	$sql="select * from ".$tableprefix."_events_attachs";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		$tmpsql="select * from ".$tableprefix."_events where eventnr=".$myrow["eventnr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
		if(mysql_num_rows($tmpresult)<1)
			$numdeadrefs++;
	}
	if($numdeadrefs>0)
	{
		$numoptions++;
		echo "<tr class=\"inforow\"><td align=\"center\"><b>";
		echo $l_orphan_refs;
		echo "</b></td></tr>";
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"refclean\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_orphan_refs\" class=\"snbutton\"></td></tr>";
		echo "</form>";
	}
	else
	{
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		echo $l_nothing_2_cleanup;
		echo "</td></tr>";
	}
}
echo "</table></td></tr></table>";
include('./trailer.php');
?>
