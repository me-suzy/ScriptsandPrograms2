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
require('../config.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
require('./auth.php');
$page_title=$l_downloadstats;
$page="downloadstats";
require('./heading.php');
if(!isset($dostorefilter) && ($admstorefilter==1))
{
	$admcookievals="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	else
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	if($admcookievals)
	{
		if(psys_array_key_exists($admcookievals,"ds_prognr"))
			$filterprog=$admcookievals["ds_prognr"];
		if(psys_array_key_exists($admcookievals,"ds_mirror"))
			$filtermirror=$admcookievals["ds_mirror"];
	}
}
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db)) {
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	$dateformat=$myrow["dateformat"];
	$dateformat.=" H:i:s";
	$watchlogins=$myrow["watchlogins"];
	$enablehostresolve=$myrow["enablehostresolve"];
}
else
{
	$dateformat="Y-m-d H:i:s";
	$watchlogins=1;
	$enablehostresolve=1;
}
if($admin_rights < 2)
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="details")
	{
		if(isset($action) && ($action=="resolve") && ($enablehostresolve==1))
		{
			$acthostname=gethostname($ipadr,$db,true);
		}
		$tempsql = "select * from ".$tableprefix."_download_files where filenr=$filenr";
		if(!$tempresult = mysql_query($tempsql,$db))
			die("unable to connect to database");
		$temprow=mysql_fetch_array($tempresult);
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow">
<td colspan="2" align="center"><b><?php echo $day?></b></td></tr>
<tr class="inforow">
<td colspan="2" align="center"><b><?php echo $temprow["description"].": ".$temprow["url"]?></b></td></tr>
<?php
		$sql = "select distinct ipadr from ".$tableprefix."_download_ips where day='$day' and filenr=$filenr";
		if(!$result = mysql_query($sql,$db))
			die("unable to connect to database");
		if($myrow=mysql_fetch_array($result))
		{
			echo "<tr class=\"rowheadings\">";
			echo "<td width=\"30%\" align=\"center\"><b>$l_ipadr</b></td>";
			echo "<td width=\"30%\" align=\"center\"><b>$l_hostname</b></td>";
			echo "</tr>";
			do{
				if(isset($action) && ($action=="resolveall") && ($enablehostresolve==1))
				{
					$hostname=gethostname($myrow["ipadr"],$db,true);
				}
				else
					$hostname=gethostname($myrow["ipadr"],$db,false);
				echo "<tr class=\"displayrow\">";
				echo "<td align=\"center\">";
				if(($admin_rights>2) && (strlen($hostname)<1) && ($enablehostresolve==1))
					$displayip="<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=details&action=resolve&ipadr=".$myrow["ipadr"]."&day=$day&filenr=$filenr")."\">".$myrow["ipadr"]."</a>";
				else
					$displayip=$myrow["ipadr"];
				echo $displayip;
				echo "</td><td>";
				echo $hostname;
				echo "</td>";
				echo "</tr>";
			}while($myrow=mysql_fetch_array($result));
		}
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\">";
		echo "<a href=\"".do_url_session("$act_script_url?mode=details&action=resolveall&day=$day&filenr=$filenr")."\">";
		echo "$l_determine_hostnames</a></div>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("downloadstats.php?lang=$lang")."\">$l_downloadstats</a></div>";
		include('trailer.php');
		echo "</body></html>";
		exit;
	}
	if($mode=="delete")
	{
		$sql = "delete from ".$tableprefix."_downloads where day='$day'";
		if(!$result = mysql_query($sql,$db))
			die("unable to connect to database");
		$sql = "delete from ".$tableprefix."_download_ips where day='$day'";
		if(!$result = mysql_query($sql,$db))
			die("unable to connect to database");
	}
	if($mode=="massdel")
	{
		if(isset($days))
		{
    		while(list($null, $day) = each($_POST["days"]))
    		{
				$sql = "delete from ".$tableprefix."_downloads where day='$day'";
				if(!$result = mysql_query($sql,$db))
					die("unable to connect to database");
				$sql = "delete from ".$tableprefix."_download_ips where day='$day'";
				if(!$result = mysql_query($sql,$db))
					die("unable to connect to database");
			}
		}
	}
}
if($topfilter==1)
{
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db))
		die("Could not connect to the database (3).".mysql_error());
	if ($temprow = mysql_fetch_array($result1))
	{
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="filterprog">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
		do {
			$progname=htmlentities($temprow["programmname"]);
			$proglang=$temprow["language"];
			echo "<option value=\"".$temprow["prognr"]."\"";
			if(isset($filterprog))
			{
				if($filterprog==$temprow["prognr"])
					echo " selected";
			}
			echo ">";
			echo "$progname [$proglang]";
			echo "</option>";
		} while($temprow = mysql_fetch_array($result1));
	}
?>
</select></td><td>&nbsp;</td></tr>
<tr><td align="right" width="50%" valign="top"><?php echo $l_mirrorfilter?>:</td>
<td align="left" width="30%"><select name="filtermirror">
<option value="-2"><?php echo $l_nofilter?></option>
<?php
			echo "<option value=\"-1\"";
			if(isset($filtermirror) && ($filtermirror==-1))
				echo " selected";
			echo ">$l_anymirror</option>";
			echo "<option value=\"0\"";
			if(isset($filtermirror) && ($filtermirror==0))
				echo " selected";
			echo ">$l_mainserver</option>";
			$tempsql="select * from ".$tableprefix."_mirrorserver";
			if(!$tempresult = mysql_query($tempsql, $db))
				die("Could not connect to the database (4).".mysql_error());
			while($temprow = mysql_fetch_array($tempresult))
			{
				echo "<option value=\"".$temprow["servernr"]."\"";
				if(isset($filtermirror) && ($filtermirror==$temprow["servernr"]))
					echo " selected";
				echo ">".$temprow["servername"]."</option>";
			}
?>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
   	$lastday = " ";
   	$sql = "select * from ".$tableprefix."_downloads down, ".$tableprefix."_download_files df where down.filenr=df.filenr ";
   	if(isset($filterprog) && ($filterprog>=0))
   	{
   		$sql.="and df.programm=".$filterprog." "; 
		$tmpsql="select * from ".$tableprefix."_programm where prognr=$filterprog";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($tmprow=mysql_fetch_array($tmpresult))
			$progname=$tmprow["programmname"];
		else
			$progname=$l_undefined;
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"5\"><b>$l_onlyprog: $progname</b></td></tr>";
   	}
	if(isset($filtermirror) && ($filtermirror>=-1))
	{
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"5\"><b>$l_onlymirror: ";
		if($filtermirror==-1)
		{
			$sql.="and df.mirrorserver!=0 ";
			echo $l_anymirror;
		}
		else
		{
			$sql.="and df.mirrorserver=$filtermirror ";
			if($filtermirror>0)
			{
				$tmpsql="select * from ".$tableprefix."_mirrorserver where servernr=$filtermirror";
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if($tmprow=mysql_fetch_array($tmpresult))
					echo $tmprow["servername"];
				else
					echo $l_undefined;
			}
			else
				echo $l_mainserver;
		}
		echo "</b></td></tr>";
	}
   	$sql.= "order by down.day desc, df.betaversion asc, df.mirrorserver asc, df.programm asc, df.filenr asc";
	if(!$result = mysql_query($sql,$db))
		die("<tr class=\"errorrow\"><td>unable to connect to database".mysql_error());
	$numentries=mysql_num_rows($result);
	if($numentries>0)
	{
		echo "<tr class=\"displayrow\"><td>";
		echo "<form method=\"post\" name=\"downloadstats\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"lang\" value=\"$lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	}
	else
	{
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		echo $l_noentries;
		echo "</td></tr>";
	}
	while($myrow = mysql_fetch_array($result))
	{
		if(!strstr($lastday,$myrow["day"]))
		{
			if($lastday != " ")
				print("</table><br>\n");
			echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\" align=\"center\" bgcolor=\"#000000\">\n";
			echo "<tr class=\"grouprow1\"><td align=\"left\"><input type=\"checkbox\" name=\"days[]\" value=\"".$myrow["day"]."\"></td>";
			echo "<td colspan=\"5\" align=\"left\"><b>".$myrow["day"]."</b>";
			echo "&nbsp;&nbsp;";
			$dellink=do_url_session("$act_script_url?mode=delete&day=".$myrow["day"]."&lang=$lang");
			if($admdelconfirm==1)
				echo "<a class=\"listlink\" href=\"javascript:confirmDel('".$myrow["day"]."','$dellink')\">";
			else
				echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>";
			echo "</td></tr>";
			echo "<tr class=\"rowheadings\"><td width=\"5%\">&nbsp;</td><td><b>$l_description</b></td><td><b>$l_programm</b></td><td><b>$l_downloadurl</b></td><td><b>$l_raw</b></td><td><b>$l_unique</b></td></tr>";

		}
		$downloadurl="";
		$tempsql="select * from ".$tableprefix."_download_files where filenr=".$myrow["filenr"];
		if(!$tempresult = mysql_query($tempsql,$db))
			die("unable to connect to database".mysql_error());
		$temprow=mysql_fetch_array($tempresult);
		echo "<tr class=\"displayrow\"><td>";
		if($temprow["mirrorserver"]>0)
		{
			echo "<img src=\"gfx/mirror.gif\" border=\"0\" title=\"$l_mirror: ";
			$mirrorsql="select * from ".$tableprefix."_mirrorserver where servernr=".$temprow["mirrorserver"];
			if(!$mirrorresult=mysql_query($mirrorsql,$db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($mirrorrow=mysql_fetch_array($mirrorresult))
			{
				echo $mirrorrow["servername"];
				if(strlen($mirrorrow["downurl"])>0)
				{
					$tmpurl=$mirrorrow["downurl"];
					if($tmpurl[strlen($tmpurl)-1]!="/")
						$tmpurl.="/";
					$downloadurl.=$tmpurl;
				}
			}
			echo "\">";
		}		
		$progsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$progresult = mysql_query($progsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($progrow=mysql_fetch_array($progresult))
		{
			if($temprow["betaversion"]==0)
			{
				if(strlen($progrow["downpath"])>0)
				{
					$tmpurl=$progrow["downpath"];
					if($tmpurl[strlen($tmpurl)-1]!="/")
						$tmpurl.="/";
					if(($tmpurl[0]=="/") && (strlen($downloadurl)>0) && ($downloadurl[strlen($downloadurl)-1]=="/"))
						$tmpurl=substr($tmpurl,1);
					$downloadurl.=$tmpurl;
				}
			}
			else
			{
				if(strlen($progrow["betapath"])>0)
				{
					$tmpurl=$progrow["betapath"];
					if($tmpurl[strlen($tmpurl)-1]!="/")
						$tmpurl.="/";
					if(($tmpurl[0]=="/") &&(strlen($downloadurl)>0) && ($downloadurl[strlen($downloadurl)-1]=="/"))
						$tmpurl=substr($tmpurl,1);
					$downloadurl.=$tmpurl;
				}
			}
		}
		if($temprow["betaversion"]==1)
			echo "<img src=\"gfx/beta.gif\" border=\"0\" title=\"$l_betaversion\">";
		echo "</td><td>";
		echo $temprow["description"]."</td>";
		if($temprow["programm"]>0)
		{
			$tempsql2="select * from ".$tableprefix."_programm where prognr=".$temprow["programm"];
			if(!$tempresult2 = mysql_query($tempsql2,$db))
				die("unable to connect to database".mysql_error());
			if($temprow2=mysql_fetch_array($tempresult2))
			{
				echo "<td>".$temprow2["programmname"]." [".$temprow2["language"]."]</td>";
			}
			else
				echo "<td>$l_undefined</td>";
		}
		else
			echo "<td>$l_undefined</td>";
		echo "<td>";
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=details&filenr=".urlencode($myrow["filenr"])."&day=".$myrow["day"]."&lang=$lang")."\">";
		echo $temprow["url"]."</a>";
		if(strlen($downloadurl)>0)
			echo "<br><span class=\"remark\">".$downloadurl.$temprow["url"]."</span>";
		echo "</td>";
		echo "<td>".$myrow["raw"]."</td>";
		echo "<td>".$myrow["uni"]."</td></tr>";
		$lastday = $myrow["day"];
	}
	if($numentries>0)
	{
		echo "</table></td></tr>";
		echo "<input type=\"hidden\" name=\"mode\" value=\"massdel\">";
		echo "<tr class=\"actionrow\"><td align=\"left\">";
		echo "<input class=\"psysbutton\" type=\"submit\" value=\"$l_delsel\">&nbsp;&nbsp;";
		echo "<input class=\"psysbutton\" type=\"button\" onclick=\"checkAll(document.downloadstats)\" value=\"$l_checkall\">&nbsp;&nbsp;";
		echo "<input class=\"psysbutton\" type=\"button\" onclick=\"uncheckAll(document.downloadstats)\" value=\"$l_uncheckall\">";
		echo "</td></tr></form>";
	}
	echo "</table></td></tr></table>";
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).".mysql_error());
	}
	if ($temprow = mysql_fetch_array($result1))
	{
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="filterprog">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
		do {
			$progname=htmlentities($temprow["programmname"]);
			$proglang=$temprow["language"];
			echo "<option value=\"".$temprow["prognr"]."\"";
			if(isset($filterprog))
			{
				if($filterprog==$temprow["prognr"])
					echo " selected";
			}
			echo ">";
			echo "$progname [$proglang]";
			echo "</option>";
		} while($temprow = mysql_fetch_array($result1));
	}
?>
</select></td><td>&nbsp;</td></tr>
<tr><td align="right" width="50%" valign="top"><?php echo $l_mirrorfilter?>:</td>
<td align="left" width="30%"><select name="filtermirror">
<option value="-2"><?php echo $l_nofilter?></option>
<?php
			echo "<option value=\"-1\"";
			if(isset($filtermirror) && ($filtermirror==-1))
				echo " selected";
			echo ">$l_anymirror</option>";
			echo "<option value=\"0\"";
			if(isset($filtermirror) && ($filtermirror==0))
				echo " selected";
			echo ">$l_mainserver</option>";
			$tempsql="select * from ".$tableprefix."_mirrorserver";
			if(!$tempresult = mysql_query($tempsql, $db))
				die("Could not connect to the database (4).".mysql_error());
			while($temprow = mysql_fetch_array($tempresult))
			{
				echo "<option value=\"".$temprow["servernr"]."\"";
				if(isset($filtermirror) && ($filtermirror==$temprow["servernr"]))
					echo " selected";
				echo ">".$temprow["servername"]."</option>";
			}
?>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
include('trailer.php');
?>
</body>
</html>
