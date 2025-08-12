<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function displayattachs_announce($announcenr, $attachpos, $nofileinfo)
{
	global $db, $tableprefix, $act_lang, $l_attachement, $url_gfx, $attachpic;
	global $contentfont, $contentfontsize, $contentfontcolor;
	global $url_simpnews;
	
	$attachsql="select f.filename, f.filesize, f.mimetype, f.description, ana.* from ".$tableprefix."_announce_attachs ana, ".$tableprefix."_files f where f.entrynr=ana.attachnr and ana.announcenr=".$announcenr;
	if(!$attachresult = mysql_query($attachsql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if($attachrow=mysql_fetch_array($attachresult))
	{
		if($attachpos==1)
			echo "<td align=\"left\">";
		echo "<table class=\"attachbox\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
		do{
			echo "<TR ALIGN=\"LEFT\"><td>";
			echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
			if($attachrow["description"])
				$fileinfo.=" ".$attachrow["description"];
			echo "&nbsp;<a href=\"".$url_simpnews."/sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">";
			$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
			if(!$mimeresult = mysql_query($mimesql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($mimerow=mysql_fetch_array($mimeresult))
			{
				$fdsql="select * from ".$tableprefix."_filetypedescription where language='$act_lang' and mimetype=".$mimerow["entrynr"];
				if(!$fdresult = mysql_query($fdsql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if($fdrow=mysql_fetch_array($fdresult))
					$dfileinfo=$fdrow["description"].": ".$fileinfo;
				else
					$dfileinfo=$fileinfo;
				if($mimerow["icon"])
					echo "<img class=\"attach\" src=\"$url_gfx/".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
				else
					echo "<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\">";
			}
			else
				echo "<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\">";
			echo " $l_attachement&nbsp;</a>";
			if($nofileinfo==0)
				echo " [".$fileinfo."]";
			echo "</td></tr>";
		}while($attachrow=mysql_fetch_array($attachresult));
		echo "</table>";
		if($attachpos==1)
			echo "</td></tr>";
	}
	else if($attachpos==0)
		echo "&nbsp;";
	if($attachpos==0)
		echo "</td>";
}
function displayattachs_news($newsnr, $attachpos, $nofileinfo)
{
	global $db, $tableprefix, $act_lang, $l_attachement, $url_gfx, $attachpic;
	global $contentfont, $contentfontsize, $contentfontcolor, $contentbgcolor;
	global $url_simpnews;

	$attachsql="select f.filename, f.filesize, f.mimetype, f.description, na.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files f where f.entrynr=na.attachnr and na.newsnr=".$newsnr;
	if(!$attachresult = mysql_query($attachsql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if($attachrow=mysql_fetch_array($attachresult))
	{
		if($attachpos==1)
			echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
		echo "<table class=\"attachbox\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
		do{
			echo "<TR ALIGN=\"LEFT\"><td>";
			echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
			if($attachrow["description"])
				$fileinfo.=" ".$attachrow["description"];
			echo "&nbsp;<a href=\"".$url_simpnews."/sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">";
			$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
			if(!$mimeresult = mysql_query($mimesql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($mimerow=mysql_fetch_array($mimeresult))
			{
				$fdsql="select * from ".$tableprefix."_filetypedescription where language='$act_lang' and mimetype=".$mimerow["entrynr"];
				if(!$fdresult = mysql_query($fdsql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if($fdrow=mysql_fetch_array($fdresult))
					$dfileinfo=$fdrow["description"].": ".$fileinfo;
				else
					$dfileinfo=$fileinfo;
				if($mimerow["icon"])
					echo "<img class=\"attach\" src=\"$url_gfx/".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
				else
					echo "<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\">";
			}
			else
				echo "<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\">";
			echo " $l_attachement&nbsp;</a>";
			if($nofileinfo==0)
				echo " [".$fileinfo."]";
			echo "</font></td></tr>";
		}while($attachrow=mysql_fetch_array($attachresult));
		echo "</table>";
		if($attachpos==1)
			echo "</td></tr>";
	}
	else if($attachpos==0)
		echo "&nbsp;";
	if($attachpos==0)
		echo "</td>";
}
function displayattachs_events($eventnr, $attachpos, $nofileinfo)
{
	global $db, $tableprefix, $act_lang, $l_attachement, $url_gfx, $attachpic;
	global $contentfont, $contentfontsize, $contentfontcolor;
	global $url_simpnews;

	$attachsql="select f.filename, f.filesize, f.mimetype, f.description, eva.* from ".$tableprefix."_events_attachs eva, ".$tableprefix."_files f where f.entrynr=eva.attachnr and eva.eventnr=".$eventnr;
	if(!$attachresult = mysql_query($attachsql, $db))
	   	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if($attachrow=mysql_fetch_array($attachresult))
	{
		if($attachpos==1)
			echo "<td align=\"left\">";
		echo "<table class=\"attachbox\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
		do{
			echo "<TR ALIGN=\"LEFT\"><td>";
			echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
			if($attachrow["description"])
				$fileinfo.=" ".$attachrow["description"];
			echo "&nbsp;<a href=\"".$url_simpnews."/sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">";
			$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
			if(!$mimeresult = mysql_query($mimesql, $db))
			   	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($mimerow=mysql_fetch_array($mimeresult))
			{
				$fdsql="select * from ".$tableprefix."_filetypedescription where language='$act_lang' and mimetype=".$mimerow["entrynr"];
				if(!$fdresult = mysql_query($fdsql, $db))
				   	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if($fdrow=mysql_fetch_array($fdresult))
					$dfileinfo=$fdrow["description"].": ".$fileinfo;
				else
					$dfileinfo=$fileinfo;
				if($mimerow["icon"])
					echo "<img class=\"attach\" src=\"$url_gfx/".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
				else
					echo "<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\">";
			}
			else
				echo "<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\">";
			echo " $l_attachement&nbsp;</a>";
			if($nofileinfo==0)
				echo " [".$fileinfo."]";
			echo "</font></td></tr>";
		}while($attachrow=mysql_fetch_array($attachresult));
		echo "</table>";
		if($attachpos==1)
			echo "</td></tr>";
	}
	else if($attachpos==0)
		echo "&nbsp;";
	if($attachpos==0)
		echo "</td>";
}

function posterline2($posterid, $postername, $linkposter, $exposter=0)
{
	if($linkposter)
		posterline($posterid,$postername,1, $exposter);
	else
		posterline($posterid,$postername,0, $exposter);
}

function posterline($posterid, $postername, $linkposter, $exposter=0)
{
	global $db, $tableprefix, $l_poster;
	global $posterfont, $posterfontsize, $posterfontcolor, $posterstyle, $posterbgcolor;

	echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\" colspan=\"3\">";
	if($exposter>0)
	{
		$sql="select * from ".$tableprefix."_poster where entrynr=".$exposter;
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			$posterlinked=false;
			if($linkposter==1)
			{
				$email=emailencode(stripslashes($myrow["email"]));
				echo "<a class=\"posterlink\" href=\"mailto:".$email."\">";
				$posterlinked=true;
			}
			echo "<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
			echo get_start_tag($posterstyle);
			echo "$l_poster: ".do_htmlentities($myrow["name"]);
			echo get_end_tag($posterstyle);
			echo "</font>";
			if($posterlinked)
				echo "</a>";
			echo "</td></tr>";
			return;
		}
	}
	$posterlinked=false;
	if($linkposter==1)
	{
		$postersql="select * from ".$tableprefix."_users where usernr=".$posterid;
		if(!$posterresult = mysql_query($postersql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($posterrow=mysql_fetch_array($posterresult))
		{
			$email=emailencode(stripslashes($posterrow["email"]));
			echo "<a class=\"posterlink\" href=\"mailto:".$email."\">";
			$posterlinked=true;
		}
	}
	echo "<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
	echo get_start_tag($posterstyle);
	echo "$l_poster: ".display_encoded($postername);
	echo get_end_tag($posterstyle);
	echo "</font>";
	if($posterlinked)
		echo "</a>";
	echo "</td></tr>";
}
?>