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
 * *************************************************************************/
require('../config.php');
require('./auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
$page_title=$l_downloadfiles;
$page="downloadfiles";
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
		if(psys_array_key_exists($admcookievals,"df_prognr"))
			$filterprog=$admcookievals["df_prognr"];
		if(psys_array_key_exists($admcookievals,"df_state"))
			$filterstate=$admcookievals["df_state"];
		if(psys_array_key_exists($admcookievals,"df_mirror"))
			$filtermirror=$admcookievals["df_mirror"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	// Page called with some special mode
	if($mode=="newfile")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newfile?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_description?>:</td><td><input class="psysinput" type="text" name="description" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td><td>
<select name="programm"><option value="0"><?php echo $l_undefined?></option>
<?php
		$tempsql="select * from ".$tableprefix."_programm order by language";
		if(!$tempresult = mysql_query($tempsql, $db))
			die("Could not connect to the database.");
		while($temprow=mysql_fetch_array($tempresult))
		{
			echo "<option value=\"".$temprow["prognr"]."\">";
			echo $temprow["programmname"]." [".$temprow["language"]."]</option>";
		}
?>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_mirror?>:</td><td><select name="mirrorserver">
<option value="0"><?php echo $l_mainserver?></option>
<?php
		$tempsql="select * from ".$tableprefix."_mirrorserver";
		if(!$tempresult = mysql_query($tempsql, $db))
			die("Could not connect to the database.");
		while($temprow=mysql_fetch_array($tempresult))
		{
			echo "<option value=\"".$temprow["servernr"]."\"";
			echo ">".$temprow["servername"]."</option>";
		}
?>
</select></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_downloadurl?>:</td><td><input class="psysinput" type="text" name="downloadurl" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="isbeta" value="1"> <?php echo $l_betaversion?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="psysbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_filelist?></a></div>
<?php
	}
	if($mode=="add")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(isset($isbeta))
			$betaversion=1;
		else
			$betaversion=0;
		if(!$downloadurl)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nodownloadurl</td></tr>";
			$errors=1;
		}
		if(!$description)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nodescription</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "INSERT INTO ".$tableprefix."_download_files (url, description, programm, mirrorserver, betaversion, downloadenabled) ";
			$sql .="VALUES ('$downloadurl','$description',$programm, $mirrorserver, $betaversion, 0)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add to database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_fileadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=newfile&lang=$lang")."\">$l_newfile</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="delete")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_download_files where (filenr=$input_filenr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_download_ips where (filenr=$input_filenr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_downloads where (filenr=$input_filenr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_compr_downloads where (filenr=$input_filenr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
	}
	if($mode=="deleteall")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(isset($filenr))
		{
    		while(list($null, $input_filenr) = each($_POST["filenr"]))
    		{
				$deleteSQL = "delete from ".$tableprefix."_download_files where (filenr=$input_filenr)";
				$success = mysql_query($deleteSQL);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete.");
				$deleteSQL = "delete from ".$tableprefix."_download_ips where (filenr=$input_filenr)";
				$success = mysql_query($deleteSQL);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete.");
				$deleteSQL = "delete from ".$tableprefix."_downloads where (filenr=$input_filenr)";
				$success = mysql_query($deleteSQL);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete.");
				$deleteSQL = "delete from ".$tableprefix."_compr_downloads where (filenr=$input_filenr)";
				$success = mysql_query($deleteSQL);
				if (!$success)
					die("<tr bclass=\"errorrow\"><td>$l_cantdelete.");
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_deleted<br>";
			echo "</td></tr>";
			echo "</table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
		}
		else
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noentriesselected</td></tr>";
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="edit")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_download_files where (filenr=$input_filenr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editfile?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_filenr" value="<?php echo $myrow["filenr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_description?>:</td>
<td><input class="psysinput" type="text" name="description" size="40" maxlength="80" value="<?php echo htmlentities($myrow["description"])?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><select name="programm">
<option value="0" <?php if($myrow["programm"]==0) echo "selected"?>><?php echo $l_undefined?></option>
<?php
		$tempsql="select * from ".$tableprefix."_programm order by language";
		if(!$tempresult = mysql_query($tempsql, $db))
			die("Could not connect to the database.");
		while($temprow=mysql_fetch_array($tempresult))
		{
			echo "<option value=\"".$temprow["prognr"]."\"";
			if($temprow["prognr"]==$myrow["programm"])
				echo " selected";
			echo ">".$temprow["programmname"]." [".$temprow["language"]."]</option>";
		}
?>
</select></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_mirror?>:</td><td><select name="mirrorserver">
<option value="0" <?php if($myrow["mirrorserver"]==0) echo "selected"?>><?php echo $l_mainserver?></option>
<?php
		$tempsql="select * from ".$tableprefix."_mirrorserver";
		if(!$tempresult = mysql_query($tempsql, $db))
			die("Could not connect to the database.");
		while($temprow=mysql_fetch_array($tempresult))
		{
			echo "<option value=\"".$temprow["servernr"]."\"";
			if($temprow["servernr"]==$myrow["mirrorserver"])
				echo " selected";
			echo ">".$temprow["servername"]."</option>";
		}
?>
</select></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_downloadurl?>:</td>
<td><input class="psysinput" type="text" name="downloadurl" size="40" maxlength="240" value="<?php echo htmlentities($myrow["url"])?>"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="isbeta" value="1" <?php if($myrow["betaversion"]==1) echo "checked"?>> <?php echo $l_betaversion?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="psysbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_filelist?></a></div>
<?php
	}
	if($mode=="disableall")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(isset($filenr))
		{
    		while(list($null, $input_filenr) = each($_POST["filenr"]))
    		{
				$sql = "update ".$tableprefix."_download_files set downloadenabled=0 where filenr=$input_filenr";
				if(!$result = mysql_query($sql, $db))
				    die("<tr bgcolor=\"#cccccc\"><td>Unable to update the database.");
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_downloaddisabled";
			echo "</td></tr>";
			echo "</table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
		}
		else
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noentriesselected</td></tr>";
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="enablefinfo")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "update ".$tableprefix."_download_files set nofinfo=0 where filenr=$input_filenr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_finfoenabled";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
	}
	if($mode=="disablefinfo")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "update ".$tableprefix."_download_files set nofinfo=1 where filenr=$input_filenr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_finfodisabled";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
	}
	if($mode=="disable")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "update ".$tableprefix."_download_files set downloadenabled=0 where filenr=$input_filenr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_downloaddisabled";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
	}
	if($mode=="enableall")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(isset($filenr))
		{
    		while(list($null, $input_filenr) = each($_POST["filenr"]))
    		{
				$sql = "update ".$tableprefix."_download_files set downloadenabled=1 where filenr=$input_filenr";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_downloadenabled";
			echo "</td></tr>";
			echo "</table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
		}
		else
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noentriesselected</td></tr>";
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="enable")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "update ".$tableprefix."_download_files set downloadenabled=1 where filenr=$input_filenr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_downloadenabled";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
	}
	if($mode=="moveall")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(isset($filenr))
		{
			while(list($null, $input_filenr) = each($_POST["filenr"]))
			{
				$sql = "update ".$tableprefix."_download_files set mirrorserver=$mirrorserver where filenr=$input_filenr";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_mirrorassigned";
			echo "</td></tr>";
			echo "</table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
		}
		else
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noentriesselected</td></tr>";
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="update")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(isset($isbeta))
			$betaversion=1;
		else
			$betaversion=0;
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(!$downloadurl)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nodownloadurl</td></tr>";
			$errors=1;
		}
		if(!$description)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nodescription</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "UPDATE ".$tableprefix."_download_files SET url='$downloadurl', description='$description', programm=$programm, mirrorserver=$mirrorserver, betaversion=$betaversion ";
			$sql .="WHERE (filenr = $input_filenr)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_fileupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_filelist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($admin_rights>1)
	{
?>
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=newfile&lang=$lang")?>"><?php echo $l_newfile?></a>
</td></tr>
</table></td></tr></table>
<?php
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
<tr><td align="right" width="50%" valign="top"><?php echo $l_statefilter?>:</td>
<td align="left" width="30%"><select name="filterstate">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
			echo "<option value=\"0\"";
			if(isset($filterstate) && ($filterstate==0))
				echo " selected";
			echo ">$l_downloadenabled</option>";
			echo "<option value=\"1\"";
			if(isset($filterstate) && ($filterstate==1))
				echo " selected";
			echo ">$l_downloaddisabled</option>";
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
	}
$sql = "select * from ".$tableprefix."_download_files ";
$firstarg=true;
if(isset($filterprog) && ($filterprog>=0))
{
	$tmpsql="select * from ".$tableprefix."_programm where prognr=$filterprog";
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if($tmprow=mysql_fetch_array($tmpresult))
		$progname=$tmprow["programmname"];
	else
		$progname=$l_undefined;
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"7\"><b>$l_onlyprog: $progname</b></td></tr>";
	if($firstarg)
	{
		$sql.="where ";
		$firstarg=false;
	}
	else
		$sql.="and ";
	$sql.="programm=$filterprog ";
}
if(isset($filterstate) && ($filterstate>=0))
{
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"7\"><b>$l_onlystate: ";
	if($firstarg)
	{
		$sql.="where ";
		$firstarg=false;
	}
	else
		$sql.="and ";
	switch($filterstate)
	{
		case 0:
			$sql.="downloadenabled=1 ";
			echo $l_downloadenabled;
			break;
		case 1:
			$sql.="downloadenabled=0 ";
			echo $l_downloaddisabled;
			break;
	}
	echo "</b></td></tr>";
}
if(isset($filtermirror) && ($filtermirror>=-1))
{
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"7\"><b>$l_onlymirror: ";
	if($firstarg)
	{
		$sql.="where ";
		$firstarg=false;
	}
	else
		$sql.="and ";
	if($filtermirror==-1)
	{
		$sql.="mirrorserver!=0 ";
		echo $l_anymirror;
	}
	else
	{
		$sql.="mirrorserver=$filtermirror ";
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
$sql.="order by programm asc, betaversion asc, mirrorserver asc, filenr asc";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"7\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
if($admin_rights > 2)
{
	echo "<form name=\"downloadfiles\" method=\"post\" action=\"$act_script_url\">";
	echo "<input type=\"hidden\" name=\"lang\" value=\"$lang\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"\">";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\">&nbsp;</td>";
}
else
	echo "<tr class=\"rowheadings\">";
?>
<td align="center" width="5%"><b>#</b></td>
<td align="center" width="15%"><b><?php echo $l_programm?></b></td>
<td align="center" width="25%"><b><?php echo $l_description?></b></td>
<td align="center" width="30%"><b><?php echo $l_downloadurl?></b></td>
<td width="10%">&nbsp;</td></tr>
<?php
	do {
		if($myrow["programm"]>0)
		{
			$tempsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
			if(!$tempresult = mysql_query($tempsql, $db))
	    			die("Could not connect to the database.");
	    		if($temprow=mysql_fetch_array($tempresult))
	    			$progname=$temprow["programmname"];
	    		else
	    			$progname=$l_undefined;
		}
		else
			$progname=$l_undefined;
		$act_id=$myrow["filenr"];
		echo "<tr class=\"displayrow\">";
		if($admin_rights>2)
		{
			echo "<td align=\"center\"";
			if($myrow["downloadenabled"]==0)
				echo " class=\"nodownload\"";
			else
				echo " class=\"download\"";
			echo ">";
			echo "<input type=\"checkbox\" name=\"filenr[]\" value=\"$act_id\"></td>";
		}
		echo "<td align=\"center\">$act_id";
		if($myrow["betaversion"]==1)
			echo " <img src=\"gfx/beta.gif\" border=\"0\" title=\"$l_betaversion\">";
		$downloadurl="";
		if($myrow["mirrorserver"]>0)
		{
			echo " <img src=\"gfx/mirror.gif\" border=\"0\" title=\"$l_mirror: ";
			$mirrorsql="select * from ".$tableprefix."_mirrorserver where servernr=".$myrow["mirrorserver"];
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
		echo "</td>";
		echo "<td align=\"center\">$progname</td>";
		echo "<td align=\"center\">".$myrow["description"]."</td>";
		echo "<td align=\"center\">".htmlentities($myrow["url"]);
		$tmpsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($tmprow=mysql_fetch_array($tmpresult))
		{
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
					if(($tmpurl[0]=="/") &&(strlen($downloadurl)>0) && ($downloadurl[strlen($downloadurl)-1]=="/"))
						$tmpurl=substr($tmpurl,1);
					$downloadurl.=$tmpurl;
				}
			}
		}
		if(strlen($downloadurl)>0)
			echo "<br><span class=\"remark\">".$downloadurl.$myrow["url"]."</span>";
		echo "</td>";
		echo "<td>";
		if($admin_rights > 2)
		{
			$dellink=do_url_session("$act_script_url?mode=delete&input_filenr=$act_id&lang=$lang");
			if($admdelconfirm==1)
				echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_file #$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&lang=$lang&input_filenr=$act_id")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a> ";
			if($myrow["downloadenabled"]==1)
			{
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=disable&lang=$lang&input_filenr=$act_id")."\">";
				echo "<img src=\"gfx/noentry.gif\" border=\"0\" title=\"$l_disabledownload\" alt=\"$l_disabledownload\"></a>";
			}
			else
			{
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=enable&lang=$lang&input_filenr=$act_id")."\">";
				echo "<img src=\"gfx/go.gif\" border=\"0\" title=\"$l_enabledownload\" alt=\"$l_enabledownload\"></a>";
			}

		}
		else
			echo "&nbsp;";
		echo "</td></tr>";
   } while($myrow = mysql_fetch_array($result));
   if($admin_rights>2)
   {
		echo "<tr class=\"actionrow\"><td align=\"right\" colspan=\"7\">";
		echo "<input class=\"psysbutton\" type=\"button\" onclick=\"checkAll(document.downloadfiles)\" value=\"$l_checkall\">";
		echo "&nbsp; <input class=\"psysbutton\" type=\"button\" onclick=\"uncheckAll(document.downloadfiles)\" value=\"$l_uncheckall\">";
		echo "</td></tr>";
		echo "<tr class=\"actionrow\"><td align=\"right\" colspan=\"7\">";
		echo "<input class=\"psysbutton\" type=\"button\" onclick=\"dosubmit('disableall')\" value=\"$l_disabledownload\">";
		echo "&nbsp; <input class=\"psysbutton\" type=\"button\" onclick=\"dosubmit('enableall')\" value=\"$l_enabledownload\">";
		echo "&nbsp; <input class=\"psysbutton\" type=\"button\" onclick=\"dosubmit('deleteall')\" value=\"$l_delete\">";
		echo "</td></tr>";
		echo "<tr class=\"actionrow\"><td align=\"right\" colspan=\"7\">";
		echo "<input class=\"psysbutton\" type=\"button\" onclick=\"dosubmit('moveall')\" value=\"$l_movetomirror\">&nbsp; ";
		echo "<select name=\"mirrorserver\">";
		echo "<option value=\"0\">$l_mainserver</option>";
		$tempsql="select * from ".$tableprefix."_mirrorserver";
		if(!$tempresult = mysql_query($tempsql, $db))
			die("Could not connect to the database.");
		while($temprow=mysql_fetch_array($tempresult))
		{
			echo "<option value=\"".$temprow["servernr"]."\"";
			echo ">".$temprow["servername"]."</option>";
		}
		echo "</select>";
		echo "</td></tr></form>";
   }
   echo "</table></tr></td></table>";
}
if($admin_rights>1)
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
<tr><td align="right" width="50%" valign="top"><?php echo $l_statefilter?>:</td>
<td align="left" width="30%"><select name="filterstate">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
	echo "<option value=\"0\"";
	if(isset($filterstate) && ($filterstate==0))
		echo " selected";
	echo ">$l_downloadenabled</option>";
	echo "<option value=\"1\"";
	if(isset($filterstate) && ($filterstate==1))
		echo " selected";
	echo ">$l_downloaddisabled</option>";
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
if($admin_rights > 2)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=newfile&lang=$lang")?>"><?php echo $l_newfile?></a></div>
<?php
}
}
include('trailer.php');
?>