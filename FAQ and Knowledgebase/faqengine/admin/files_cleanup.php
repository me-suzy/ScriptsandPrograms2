<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
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
if(!isset($mode) && !$attach_in_fs)
	$mode="refclean";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(isset($mode))
{
	if(($mode=="refclean") || ($mode=="cleanall"))
	{
		$faqcleaned=0;
		$kbcleaned=0;
		$sql="select * from ".$tableprefix."_faq_attachs";
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".faqe_db_error());
		while($myrow=faqe_db_fetch_array($result))
		{
			$tmpsql="select * from ".$tableprefix."_data where faqnr=".$myrow["faqnr"];
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".faqe_db_error());
			if(faqe_db_num_rows($tmpresult)<1)
			{
				$tmpsql="delete from ".$tableprefix."_faq_attachs where entrynr=".$myrow["entrynr"];
				if(!$tmpresult=faqe_db_query($tmpsql,$db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete. ".faqe_db_error());
				$faqcleaned++;
			}
		}
		$sql="select * from ".$tableprefix."_kb_attachs";
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".faqe_db_error());
		while($myrow=faqe_db_fetch_array($result))
		{
			$tmpsql="select * from ".$tableprefix."_kb_articles where articlenr=".$myrow["articlenr"];
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".faqe_db_error());
			if(faqe_db_num_rows($tmpresult)<1)
			{
				$tmpsql="delete from ".$tableprefix."_kb_attachs where entrynr=".$myrow["entrynr"];
				if(!$tmpresult=faqe_db_query($tmpsql,$db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete. ".faqe_db_error());
				$kbcleaned++;
			}
		}
		echo "<tr class=\"inforow\"><td align=\"center\"><b>$l_orphan_refs</b></td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		printf($l_cleaned_up,$faqcleaned,$kbcleaned);
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
				if(!$tmpresult = faqe_db_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database. ".faqe_db_error());
				if(faqe_db_num_rows($tmpresult)==0)
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
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database. ".faqe_db_error());
		while($tmprow=faqe_db_fetch_array($tmpresult))
		{
			$cleanedfiles++;
			$delsql="DELETE FROM ".$tableprefix."_faq_attachs where attachnr=".$tmprow["entrynr"];
			if(!$delresult = faqe_db_query($delsql, $db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".faqe_db_error());
			$delsql="DELETE FROM ".$tableprefix."_kb_attachs where attachnr=".$tmprow["entrynr"];
			if(!$delresult = faqe_db_query($delsql, $db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".faqe_db_error());
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
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database. ".faqe_db_error());
		while($tmprow=faqe_db_fetch_array($tmpresult))
		{
			$filename=stripslashes($tmprow["fs_filename"]);
			if(!file_exists($path_attach."/".$filename))
			{
				$cleanedfiles++;
				$delsql="DELETE FROM ".$tableprefix."_faq_attachs where attachnr=".$tmprow["entrynr"];
				if(!$delresult = faqe_db_query($delsql, $db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".faqe_db_error());
				$delsql="DELETE FROM ".$tableprefix."_kb_attachs where attachnr=".$tmprow["entrynr"];
				if(!$delresult = faqe_db_query($delsql, $db))
					die("<tr class=\"errorrow\"><td>$l_cantdelete ".$tmprow["entrynr"]." - ".faqe_db_error());
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
else
{
	$numoptions=0;
	$orphanfiles=array();
	$dir = opendir($path_attach);
	while ($file = readdir($dir))
	{
		if(is_file($path_attach."/".$file))
		{
			$tmpsql="select * from ".$tableprefix."_files where fs_filename='".$file."'";
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database. ".faqe_db_error());
			if(faqe_db_num_rows($tmpresult)==0)
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
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_orphan_files\" class=\"faqebutton\"></td></tr>";
		echo "</form>";
	}
	$tmpsql="select * from ".$tableprefix."_files where bindata !='' and fs_filename=''";
	if(!$tmpresult = faqe_db_query($tmpsql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database. ".faqe_db_error());
	if(faqe_db_num_rows($tmpresult)>0)
	{
		$numoptions++;
		echo "<tr class=\"inforow\"><td align=\"center\"><b>";
		echo "$l_orphan_bindata";
		echo "</b></td></tr>";
		while($tmprow=faqe_db_fetch_array($tmpresult))
		{
			echo "<tr class=\"displayrow\"><td align=\"center\">".$tmprow["entrynr"]." - ".$tmprow["filename"]."</td></tr>";
		}
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"binclean\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_orphan_bindata\" class=\"faqebutton\"></td></tr>";
		echo "</form>";
	}
	$orphanrefs=array();
	$tmpsql="select * from ".$tableprefix."_files where fs_filename!=''";
	if(!$tmpresult = faqe_db_query($tmpsql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database. ".faqe_db_error());
	while($tmprow=faqe_db_fetch_array($tmpresult))
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
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_orphan_frefs\" class=\"faqebutton\"></td></tr>";
		echo "</form>";
	}
	$numdeadrefs=0;
	$sql="select * from ".$tableprefix."_faq_attachs";
	if(!$result = faqe_db_query($sql, $db))
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".faqe_db_error());
	while($myrow=faqe_db_fetch_array($result))
	{
		$tmpsql="select * from ".$tableprefix."_data where faqnr=".$myrow["faqnr"];
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".faqe_db_error());
		if(faqe_db_num_rows($tmpresult)<1)
			$numdeadrefs++;
	}
	$sql="select * from ".$tableprefix."_kb_attachs";
	if(!$result = faqe_db_query($sql, $db))
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".faqe_db_error());
	while($myrow=faqe_db_fetch_array($result))
	{
		$tmpsql="select * from ".$tableprefix."_kb_articles where articlenr=".$myrow["articlenr"];
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".faqe_db_error());
		if(faqe_db_num_rows($tmpresult)<1)
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
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_orphan_refs\" class=\"faqebutton\"></td></tr>";
		echo "</form>";
	}
	if($numoptions>1)
	{
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"cleanall\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"actionrow\"><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"$l_cleanup_all\" class=\"faqebutton\"></td></tr>";
		echo "</form>";
	}
	if($numoptions==0)
	{
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		echo $l_nothing_2_cleanup;
		echo "</td></tr>";
	}
}
echo "</table></td></tr></table>";
include('./trailer.php');
?>
