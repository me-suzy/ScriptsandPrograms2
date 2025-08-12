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
require_once('../config.php');
require_once('./auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include_once('./language/lang_'.$lang.'.php');
$page_title=$l_programm_title;
require_once('./heading.php');
$checked_pic="gfx/checked.gif";
$unchecked_pic="gfx/unchecked.gif";
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
	if($mode=="display")
	{
		if($admin_rights < 1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_programm where (prognr=$input_prognr)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displayprogs?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_progname?>:</td>
<td><?php echo htmlentities($myrow["programmname"])?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_id?>:</td>
<td><?php echo $myrow["progid"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td>
<?php echo $myrow["language"]?>
</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_stylesheet?>:</td>
<td><?php echo $myrow["stylesheet"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_emailsendername?>:</td>
<td valign="top"><?php echo $myrow["emailname"]?></td></tr>
<?php
		if($myrow["usecustomheader"]==1)
		{
			echo "<tr class=\"displayrow\"><td align=\"right\" valign=\"top\">$l_customheader:</td>\n";
			echo "<td>".htmlentities($myrow["pageheader"]);
			if($myrow["headerfile"])
				echo "<br><hr noshade color=\"#000000\" size=\"1\">$l_includefile: ".$myrow["headerfile"];
			echo "</td></tr>\n";
		}
		if($myrow["usecustomfooter"]==1)
		{
			echo "<tr class=\"displayrow\"><td align=\"right\" valign=\"top\">$l_customfooter:</td>\n";
			echo "<td>".htmlentities($myrow["pagefooter"]);
			if($myrow["footerfile"])
				echo "<br><hr noshade color=\"#000000\" size=\"1\">$l_includefile: ".$myrow["footerfile"];
			echo "</td></tr>\n";
		}
?>
<tr class="displayrow"><td align="right"><?php echo $l_supportedos?>:</td>
<td valign="top">
<?php
		$sql = "SELECT o.osname, o.osnr FROM ".$tableprefix."_os o, ".$tableprefix."_prog_os po WHERE po.prognr = '$input_prognr' AND o.osnr = po.osnr order by o.osnr";
		if(!$r = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if ($row = mysql_fetch_array($r))
		{
			 do {
			    echo $row["osname"]."<BR>";
			 } while($row = mysql_fetch_array($r));
		}
		else
			echo "$l_noos<br>";
?>
</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_admins?>:</td>
<td>
<?php
		$sql = "SELECT u.username, u.usernr FROM ".$tableprefix."_admins u, ".$tableprefix."_programm_admins f WHERE f.prognr = '$input_prognr' AND u.usernr = f.usernr order by u.username";
		if(!$r = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if ($row = mysql_fetch_array($r))
		{
			 do {
			    echo $row["username"]."<BR>";
			 } while($row = mysql_fetch_array($r));
		}
		else
			echo "$l_noadmins<br>";
?>
</td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["enabletodorating"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_enabletodorating?></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["hasbeta"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_hasbetasection?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_downpath?>:</td><td align="left"><?php echo $myrow["downpath"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_downpath." (".$l_betaversion.")"?>:</td><td align="left"><?php echo $myrow["betapath"]?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_bugreports?></b></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["enablebugentries"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_enablebugentries?></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["publishnewbugentries"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_publishnewbugentries?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_featurerequests?></b></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["enablefeaturerequests"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_enablefeaturerequests?></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["featurerequestspublic"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_featurerequestspublic?></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["ratefeaturerequests"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_ratefeaturerequests?></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["requestratingspublic"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_requestratingspublic?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_newsletter?></b></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["enablenewsletter"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_enablenewsletter?></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["newsletterfreemailer"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_newsletterfreemailer?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_maxconfirmtime?>:</td><td align="left" valign="top">
<?php
		if($myrow["maxconfirmtime"]==0)
			echo $l_noconfirm;
		else
			echo $myrow["maxconfirmtime"]." $l_days";
?>
</td></tr>
<?php
	$displayremark=htmlentities($myrow["newsletterremark"]);
	$displayremark=str_replace("\n","<BR>",$displayremark);
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_emailremark?>:</td><td align="left" valign="top">
<?php echo $displayremark?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_miscsettings?></b></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><img src="<?php if($myrow["disableref"]==1) echo $checked_pic; else echo $unchecked_pic;?>" align="middle">
<?php echo $l_disableref?></td></tr>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_proglist</a></div>";
	}
	// Page called with some special mode
	if($mode=="new")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		// Display empty form for entering programm
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newprogramm?></b></td></tr>
<form name="myform" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_progname?>:</td><td><input class="psysinput" type="text" name="programmname" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_id?>:</td><td><input class="psysinput" type="text" name="progid" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td>
<?php print language_select($default_lang, "proglang", "../language");?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_supportedos?>:</td>
<td>
<SELECT NAME="os[]" size="5" multiple>
<?php
	$sql = "SELECT osnr, osname FROM ".$tableprefix."_os ORDER BY osnr";
	if(!$r = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if($row = mysql_fetch_array($r)) {
		do {
			echo "<OPTION VALUE=\"".$row["osnr"]."\" >".$row["osname"]."</OPTION>\n";
		} while($row = mysql_fetch_array($r));
	}
	else {
		echo "<OPTION VALUE=\"0\">$l_none</OPTION>\n";
	}
?>
</select>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_stylesheet?>:</td>
<td><input class="psysinput" type="text" name="stylesheet"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customheader?>:<br>
<input name="enablecustomheader" value="1" type="checkbox"> <?php echo $l_enable?><br>
</td>
<td>
<textarea class="psysinput" name="customheader" rows="8" cols="40"></textarea><br>
<hr noshade color="#000000" size="1" width="90%">
<?php
if($upload_avail)
{
?>
<?php echo $l_uploadfile?>: <input class="psysfile" type="file" name="customheaderfile"><br>
<?php
}
?>
<input type="checkbox" value="1" name="clearcustomheader"><?php echo $l_clear?><br>
<hr noshade color="#000000" size="1">
<?php echo $l_includefile?>: <input class="psysinput" type="text" size="30" maxlength="240" name="headerfile">
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customfooter?>:<br>
<input name="enablecustomfooter" value="1" type="checkbox"> <?php echo $l_enable?><br>
</td>
<td>
<textarea class="psysinput" name="customfooter" rows="8" cols="40"></textarea><br>
<hr noshade color="#000000" size="1" width="90%">
<?php
if($upload_avail)
{

?>
<?php echo $l_uploadfile?>: <input class="psysfile" type="file" name="customfooterfile"><br>
<?php

}

?>
<input type="checkbox" value="1" name="clearcustomfooter"><?php echo $l_clear?><br>
<hr noshade color="#000000" size="1">
<?php echo $l_includefile?>: <input class="psysinput" type="text" size="30" maxlength="240" name="footerfile">
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_downpath?>:</td><td>
<input type="text" class="psysinput" size="40" maxlength="255" name="downpath"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_downpath." (".$l_betaversion.")"?>:</td><td>
<input type="text" class="psysinput" size="40" maxlength="255" name="betapath"></td></tr>
<?php
	if($admin_rights>2)
	{
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_admins?>:</td>
<td>
<SELECT NAME="mods[]" size="5" multiple>
<?php
		$sql = "SELECT usernr, username FROM ".$tableprefix."_admins WHERE rights > 1 ORDER BY username";
		if(!$r = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($row = mysql_fetch_array($r))
		{
			do {
				echo "<OPTION VALUE=\"$row[usernr]\" >$row[username]</OPTION>\n";
			} while($row = mysql_fetch_array($r));
		}
		else {
			echo "<OPTION VALUE=\"0\">$l_none</OPTION>\n";
		}
?>
</select>
</td></tr>
<?php
	}
	else
		echo "<input type=\"hidden\" name=\"mods[]\" value=\"$act_usernr\">";
?>
<tr class="inputrow"><td align="right" valign="top">&nbsp;</td><td>
<input type="checkbox" name="todorating" value="1" checked>
<?php echo $l_enabletodorating?></td></tr>
<tr class="inputrow"><td align="right" valign="top">&nbsp;</td><td>
<input type="checkbox" name="hasbeta" value="1">
<?php echo $l_hasbetasection?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_bugreports?></b></td></tr>
<tr class="inputrow"><td align="right" valign="top">&nbsp;</td><td>
<input type="checkbox" name="enterbugs" value="1" checked>
<?php echo $l_enablebugentries?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left">
<input type="checkbox" name="newbugspublic" value="1">
<?php echo $l_publishnewbugentries?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_featurerequests?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="featurerequests" value="1" checked>
<?php echo $l_enablefeaturerequests?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="requestspublic" value="1">
<?php echo $l_featurerequestspublic?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="raterequests" value="1" checked>
<?php echo $l_ratefeaturerequests?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="ratespublic" value="1" checked>
<?php echo $l_requestratingspublic?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_newsletter?></b></td></tr>
<tr class="inputrow"><td align="right" valign="top">&nbsp;</td><td>
<input type="checkbox" name="newsletter" value="1" checked>
<?php echo $l_enablenewsletter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablenewsletterfreemailer" value="1" type="checkbox"
checked> <?php echo $l_newsletterfreemailer?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_maxconfirmtime?>:</td><td align="left" valign="top">
<input type="checkbox" name="nosubscriptionconfirm" onClick="program_maxconfirmtime()" value="1"> <?php echo $l_noconfirm?><br>
<select name="maxconfirmtime">
<?php
for($i=1;$i<10;$i++)
{
	echo "<option value=\"$i\"";
	if($i==2)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>&nbsp;<?php echo $l_days?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_emailremark?>:</td><td align="left" valign="top">
<textarea class="psysinput" name="emailremark" rows="5" cols="40"></textarea>
<a href="javascript:void(0);" onmouseover="this.T_ABOVE=true; return escape('<?php echo $l_prognlinfo?>')"><img src="gfx/info.gif" border="0"></a>
</td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_miscsettings?></b></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="allownoref" value="1">
<?php echo $l_disableref?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add">
<input class="psysbutton" type="submit" value="<?php echo $l_add?>">
&nbsp;&nbsp;<input class="psysbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_proglist?></a></div>
<?php
	}
	if($mode=="add")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		// Add new programm to database
		$errors=0;
		if(!$proglang)
			$proglang="";
		if(!$programmname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogname</td></tr>";
			$errors=1;
		}
		if(!$progid)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noid</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(strlen($downpath)>0)
			{
				$downpath=stripslashes($downpath);
				$downpath=str_replace("\\","/",$downpath);
			}
			if(strlen($betapath)>0)
			{
				$betapath=stripslashes($betapath);
				$betapath=str_replace("\\","/",$betapath);
			}
			if(isset($nosubscriptionconfirm))
				$maxconfirmtime=0;
			if(isset($preview))
			{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newprogramm?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_progname?>:</td><td><?php echo $programmname?><input type="hidden" name="programmname" value="<?php echo $programmname?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_id?>:</td><td><?php echo $progid?><input type="hidden" name="progid" value="<?php echo $progid?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td><?php echo $proglang?><input type="hidden" name="proglang" value="<?php echo $proglang?>"></td></tr>
<input type="hidden" name="footerfile" value="<?php echo $footerfile?>">
<input type="hidden" name="headerfile" value="<?php echo $headerfile?>">
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_stylesheet?>:</td><td><?php echo $stylesheet?>
<input type="hidden" name="stylesheet" value="<?php echo $stylesheet?>">
</td></tr>
<?php
				if(isset($clearcustomheader))
					echo "<input type=\"hidden\" name=\"clearcustomheader\" value=\"1\">\n";
				if(isset($clearcustomfooter))
					echo "<input type=\"hidden\" name=\"clearcustomfooter\" value=\"1\">\n";
				if($new_global_handling)
					$tmp_file=$HTTP_POST_FILES['customheaderfile']['tmp_name'];
				else
					$tmp_file=$_FILES['customheaderfile']['tmp_name'];
				if($upload_avail && is_uploaded_file($tmp_file))
				{
					$customheader = addslashes(fread(fopen($tmp_file,"r"), filesize($tmp_file)));
					echo "<input type=\"hidden\" name=\"customheader\" value=\"".htmlentities($customheader)."\">\n";
				}
				else
					$customheader="";
				if($new_global_handling)
					$tmp_file=$HTTP_POST_FILES['customfooterfile']['tmp_name'];
				else
					$tmp_file=$_FILES['customfooterfile']['tmp_name'];
				if($upload_avail && is_uploaded_file($tmp_file))
				{
					$customfooter = addslashes(fread(fopen($tmp_file,"r"), filesize($tmp_file)));
					echo "<input type=\"hidden\" name=\"customfooter\" value=\"".htmlentities($customfooter)."\">\n";
				}
				else
					$customfooter="";
				if(isset($enablecustomheader))
				{
					echo "<input type=\"hidden\" name=\"enablecustomheader\" value=\"1\">\n";
					echo "<tr class=\"displayrow\"><td align=\"right\">$l_customheader:</td>\n";
					echo "<td>".htmlentities($customheader);
					if($headerfile)
						echo "<br><hr noshade color=\"#000000\" size=\"1\">$l_includefile: $headerfile";
					echo "</td></tr>\n";
				}
				if(isset($enablecustomfooter))
				{
					echo "<input type=\"hidden\" name=\"enablecustomfooter\" value=\"1\">\n";
					echo "<tr class=\"displayrow\"><td align=\"right\">$l_customfooter:</td>\n";
					echo "<td>".htmlentities($customfooter);
					if($footerfile)
						echo "<br><hr noshade color=\"#000000\" size=\"1\">$l_includefile: $footerfile";
					echo "</td></tr>\n";
				}
?>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_supportedos?>:</td><td>
<?php
				if(isset($os))
				{
					while(list($null, $os) = each($_POST["os"]))
					{
						$os_query = "SELECT * from ".$tableprefix."_os where osnr=$os";
	    			   	if(!$os_result=mysql_query($os_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
						if($os_row=mysql_fetch_array($os_result))
						{
							echo $os_row["osname"];
							echo "<input type=\"hidden\" name=\"os[]\" value=\"$os\"><br>";
						}

					}
				}
?>
</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_downpath?>:</td><td>
<?php echo $downpath?>
<input type="hidden" name="downpath" value="<?php echo $downpath?>"></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_downpath." (".$l_betaversion.")"?>:</td><td>
<?php echo $betapath?>
<input type="hidden" name="betapath" value="<?php echo $betapath?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_admins?>:</td><td>
<?php
				if(isset($mods))
				{
					while(list($null, $mod) = each($_POST["mods"]))
					{
						$mod_query = "SELECT * from ".$tableprefix."_admins where usernr=$mod";
	    			   	if(!$mod_result=mysql_query($mod_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
						if($mod_row=mysql_fetch_array($mod_result))
						{
							echo $mod_row["username"];
							echo "<input type=\"hidden\" name=\"mods[]\" value=\"$mod\"><br>";
						}

					}
				}
?>
</td></tr>
<?php
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($todorating))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"todorating\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo " $l_enabletodorating</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($hasbeta))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"hasbeta\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_hasbetasection</td></tr>";
?>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_bugreports?></b></td></tr>
<?php
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($enterbugs))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"enterbugs\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_enablebugentries</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($newbugspublic))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"newbugspublic\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_publishnewbugentries</td></tr>";
?>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_featurerequests?></b></td></tr>
<?php
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($featurerequests))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"featurerequests\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_enablefeaturerequests</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($requestspublic))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"requestspublic\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_featurerequestspublic</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($raterequests))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"raterequests\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_ratefeaturerequests</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($ratespublic))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"ratespublic\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_requestratingspublic</td></tr>";
?>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_newsletter?></b></td></tr>
<?php
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($newsletter))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"newsletter\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_enablenewsletter</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($enablenewsletterfreemailer))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"enablenewsletterfreemailer\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_newsletterfreemailer</td></tr>";
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_maxconfirmtime?>:</td><td align="left" valign="top">
<?php
				if($maxconfirmtime==0)
					echo "$l_noconfirm";
				else
					echo "$maxconfirmtime $l_days";
				echo "</td></tr><input type=\"hidden\" name=\"maxconfirmtime\" value=\"$maxconfirmtime\">";
				$displayremark=str_replace("\n","<br>",htmlentities($emailremark));
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_emailremark?>:</td><td align="left" valign="top">
<input type="hidden" name="emailremark" value="<?php echo $emailremark?>"><?php echo $displayremark?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_miscsettings?></b></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left">
<?php
				if(isset($allownoref))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"allownoref\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_disableref</td></tr>";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="psysbutton" type="submit" value="<?php echo $l_enter?>">&nbsp;&nbsp;
<input class="psysbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="add">
</td></tr></form></table></td></tr></table>
<?php
			}
			else
			{
				if(!isset($hasbeta))
					$hasbeta=0;
				if(isset($ratespublic))
					$requestratingspublic=1;
				else
					$requestratingspublic=0;
				if(isset($newbugspublic))
					$publishnewbugentries=1;
				else
					$publishnewbugentries=0;
				if(isset($raterequests))
					$ratefeaturerequests=1;
				else
					$ratefeaturerequests=0;
				if(isset($requestspublic))
					$featurerequestspublic=1;
				else
					$featurerequestspublic=0;
				if(isset($featurerequests))
					$enablefeaturerequests=1;
				else
					$enablefeaturerequests=0;
				if(isset($enablenewsletterfreemailer))
					$newsletterfreemailer=1;
				else
					$newsletterfreemailer=0;
				if(isset($enterbugs))
					$enablebugentries=1;
				else
					$enablebugentries=0;
				if(isset($allownoref))
					$disableref=1;
				else
					$disableref=0;
				if(isset($newsletter))
					$enablenewsletter=1;
				else
					$enablenewsletter=0;
				if(isset($todorating))
					$enabletodorating=1;
				else
					$enabletodorating=0;
				if(isset($enablecustomheader))
					$usecustomheader=1;
				else
					$usecustomheader=0;
				if(isset($enablecustomfooter))
					$usecustomfooter=1;
				else
					$usecustomfooter=0;
				if(!isset($customheader))
					$customheader="";
				if(!isset($customfooter))
					$customfooter="";
				if(isset($clearcustomheader))
					$customheader="";
				else
				{
					if(isset($customheaderfile) && ($customheaderfile!="none"))
						$customheader = addslashes(fread(fopen($customheaderfile,"r"), filesize($customheaderfile)));
				}
				if(isset($clearcustomfooter))
					$customfooter="";
				else
				{
					if(isset($customfooterfile) && ($customfooterfile!="none"))
						$customfooter = addslashes(fread(fopen($customfooterfile,"r"), filesize($customfooterfile)));
				}
				$footerfile=addslashes($footerfile);
				$headerfile=addslashes($headerfile);
				$programmname=addslashes($programmname);
				$sql = "INSERT INTO ".$tableprefix."_programm (programmname, progid, language, usecustomheader, usecustomfooter, pageheader, pagefooter, headerfile, footerfile, stylesheet, enablebugentries, enablenewsletter, enabletodorating, newsletterfreemailer, maxconfirmtime, newsletterremark, enablefeaturerequests, featurerequestspublic, ratefeaturerequests, publishnewbugentries, requestratingspublic, hasbeta, downpath, betapath, disableref) ";
				$sql .="VALUES ('$programmname', '$progid', '$proglang', $usecustomheader, $usecustomfooter, '$customheader', '$customfooter', '$headerfile', '$footerfile', '$stylesheet', $enablebugentries, $enablenewsletter, $enabletodorating, $newsletterfreemailer, $maxconfirmtime, '$emailremark', $enablefeaturerequests, $featurerequestspublic, $ratefeaturerequests, $publishnewbugentries, $requestratingspublic, $hasbeta, '$downpath', '$betapath', $disableref)";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Unable to add programm to database.".mysql_error());
				$prognr = mysql_insert_id($db);
				if(isset($mods))
				{
	    				while(list($null, $mod) = each($_POST["mods"]))
	    				{
						$mod_query = "INSERT INTO ".$tableprefix."_programm_admins (prognr, usernr) VALUES ('$prognr', '$mod')";
	    				   	if(!mysql_query($mod_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				if(isset($os))
				{
					while(list($null, $os) = each($_POST["os"]))
					{
						$os_query = "INSERT INTO ".$tableprefix."_prog_os (prognr, osnr) VALUES ('$prognr', '$os')";
	    				   	if(!mysql_query($os_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_progadded";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&lang=$lang")."\">$l_newprogramm</a></div>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_proglist</a></div>";
			}
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
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_programm where (prognr=$input_prognr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_programm_admins where (prognr=$input_prognr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_prog_os where (prognr=$input_prognr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "<i>$progname</i> $l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_proglist</a></div>";
	}
	if($mode=="editdir")
	{
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=$input_prognr and usernr=$act_usernr";
		if(!$modresult = mysql_query($modsql, $db)) {
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
		if($modrow=mysql_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights < 2) || (($admin_rights < 3) && ($ismod==0)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_proglist</a></div>";
			include('trailer.php');
			exit;
		}
		$sql = "select * from ".$tableprefix."_programm where (prognr=$input_prognr)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo "$l_editprogs<br><span class=\"remark\">($l_editdir)</span>"?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td><td><?php echo htmlentities(stripslashes($myrow["programmname"]))." (".$myrow["language"].")"?></td></tr>
<form name="myform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_prognr" value="<?php echo $myrow["prognr"]?>">
<input type="hidden" name="mode" value="updatedir">
<tr class="inputrow"><td align="right"><?php echo $l_downpath?>:</td><td>
<input type="text" class="psysinput" size="40" maxlength="255" name="downpath" value="<?php echo $myrow["downpath"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_downpath." (".$l_betaversion.")"?>:</td><td>
<input type="text" class="psysinput" size="40" maxlength="255" name="betapath" value="<?php echo $myrow["betapath"]?>"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="psysbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></tr></td></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_proglist?></a></div>
<?php
	}
	if($mode=="edit")
	{
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=$input_prognr and usernr=$act_usernr";
		if(!$modresult = mysql_query($modsql, $db)) {
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
		if($modrow=mysql_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights < 2) || (($admin_rights < 3) && ($ismod==0)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_proglist</a></div>";
			include('trailer.php');
			exit;
		}
		$sql = "select * from ".$tableprefix."_programm where (prognr=$input_prognr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editprogs?></b></td></tr>
<form name="myform" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_prognr" value="<?php echo $myrow["prognr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_progname?>:</td><td><input class="psysinput" type="text" name="programmname" size="40" maxlength="80" value="<?php echo htmlentities(stripslashes($myrow["programmname"]))?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_id?>:</td><td><input class="psysinput" type="text" name="progid" size="10" maxlength="10" value="<?php echo $myrow["progid"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td>
<?php print language_select($myrow["language"], "proglang", "../language");?>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_stylesheet?>:</td>
<td><input class="psysinput" type="text" name="stylesheet" value="<?php echo $myrow["stylesheet"]?>" size="40" maxlength="250"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_emailsendername?>:</td>
<td><input class="psysinput" type="text" name="emailname" value="<?php echo $myrow["emailname"]?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customheader?>:<br>
<input name="enablecustomheader" value="1" type="checkbox" <?php if($myrow["usecustomheader"]==1) echo "checked"?>> <?php echo $l_enable?><br>
</td>
<td><textarea class="psysinput" name="customheader" rows="8" cols="40"><?php echo htmlentities($myrow["pageheader"])?></textarea><br>
<hr noshade color="#000000" size="1" width="90%">
<?php 
if($upload_avail)
{
?>
<?php echo $l_uploadfile?>: <input class="psysfile" type="file" name="customheaderfile"><br>
<?php
}
?>
<input type="checkbox" value="1" name="clearcustomheader"><?php echo $l_clear?><br>
<hr noshade color="#000000" size="1">
<?php echo $l_includefile?>: <input class="psysinput" type="text" size="30" maxlength="240" name="headerfile" value="<?php echo $myrow["headerfile"]?>">
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customfooter?>:<br>
<input name="enablecustomfooter" value="1" type="checkbox" <?php if($myrow["usecustomfooter"]==1) echo "checked"?>> <?php echo $l_enable?><br>
</td>
<td><textarea class="psysinput" name="customfooter" rows="8" cols="40"><?php echo htmlentities($myrow["pagefooter"])?></textarea><br>
<hr noshade color="#000000" size="1" width="90%">
<?php
if($upload_avail)
{
?>
<?php echo $l_uploadfile?>: <input class="psysfile" type="file" name="customfooterfile"><br>
<?php
}
?>
<input type="checkbox" value="1" name="clearcustomfooter"><?php echo $l_clear?><br>
<hr noshade color="#000000" size="1">
<?php echo $l_includefile?>: <input class="psysinput" type="text" size="30" maxlength="240" name="footerfile" value="<?php echo $myrow["footerfile"]?>">
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_downpath?>:</td><td>
<input type="text" class="psysinput" size="40" maxlength="255" name="downpath" value="<?php echo $myrow["downpath"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_downpath." (".$l_betaversion.")"?>:</td><td>
<input type="text" class="psysinput" size="40" maxlength="255" name="betapath" value="<?php echo $myrow["betapath"]?>"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_supportedos?>:</td>
<td>
<?php
	$sql = "SELECT o.osname, o.osnr FROM ".$tableprefix."_os o, ".$tableprefix."_prog_os po WHERE po.prognr = '$input_prognr' AND o.osnr = po.osnr order by o.osnr";
	if(!$r = mysql_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
	if ($row = mysql_fetch_array($r))
	{
		 do {
		    echo $row["osname"]." (<input type=\"checkbox\" name=\"rem_os[]\" value=\"".$row["osnr"]."\"> $l_remove)<BR>";
		    $current_os[] = $row["osnr"];
		 } while($row = mysql_fetch_array($r));
		 echo "<br>";
	}
	else
		echo "$l_noos<br><br>";
	$sql = "SELECT osnr, osname FROM ".$tableprefix."_os ";
	$first=1;
	if(isset($current_os))
	{
    	while(list($null, $curros) = each($current_os)) {
    		if($first==1)
    		{
				$sql .= "WHERE osnr != $curros ";
				$first=0;
			}
			else
				$sql .= "AND osnr != $curros ";
    	}
    }
    $sql .= "ORDER BY osnr";
    if(!$r = mysql_query($sql, $db))
		die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
    if($row = mysql_fetch_array($r)) {
		echo "<span class=\"inlineheading1\">$l_add:</span><br>";
		echo "<SELECT NAME=\"os[]\" size=\"5\" multiple>";
		do {
			echo "<OPTION VALUE=\"$row[osnr]\" >$row[osname]</OPTION>\n";
		} while($row = mysql_fetch_array($r));
		echo"</select>";
	}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_admins?>:</td>
<td>
<?php
	$sql = "SELECT u.username, u.usernr FROM ".$tableprefix."_admins u, ".$tableprefix."_programm_admins f WHERE f.prognr = '$input_prognr' AND u.usernr = f.usernr order by u.username";
	if(!$r = mysql_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
	if ($row = mysql_fetch_array($r))
	{
		 do {
		    echo $row["username"]." (<input type=\"checkbox\" name=\"rem_mods[]\" value=\"".$row["usernr"]."\"> $l_remove)<BR>";
		    $current_mods[] = $row["usernr"];
		 } while($row = mysql_fetch_array($r));
		 echo "<br>";
	}
	else
		echo "$l_noadmins<br><br>";
	$sql = "SELECT usernr, username FROM ".$tableprefix."_admins WHERE rights > 1 ";
	if(isset($current_mods))
	{
    		while(list($null, $currMod) = each($current_mods)) {
			$sql .= "AND usernr != $currMod ";
    		}
	}	
	$sql .= "ORDER BY username";
    if(!$r = mysql_query($sql, $db))
		die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
    if($row = mysql_fetch_array($r)) {
		echo"<span class=\"inlineheading1\">$l_add:</span><br>";
		echo"<SELECT NAME=\"mods[]\" size=\"5\" multiple>";
		do {
			echo "<OPTION VALUE=\"$row[usernr]\" >$row[username]</OPTION>\n";
		} while($row = mysql_fetch_array($r));
		echo"</select>";
	}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top">&nbsp;</td><td>
<input type="checkbox" name="todorating" value="1" <?php if($myrow["enabletodorating"]==1) echo "checked"?>>
<?php echo $l_enabletodorating?></td></tr>
<tr class="inputrow"><td align="right" valign="top">&nbsp;</td><td>
<input type="checkbox" name="hasbeta" value="1" <?php if($myrow["hasbeta"]==1) echo "checked"?>>
<?php echo $l_hasbetasection?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_bugreports?></b></td></tr>
<tr class="inputrow"><td align="right" valign="top">&nbsp;</td><td>
<input type="checkbox" name="enterbugs" value="1" <?php if($myrow["enablebugentries"]==1) echo "checked"?>>
<?php echo $l_enablebugentries?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left">
<input type="checkbox" name="newbugspublic" value="1" <?php if($myrow["publishnewbugentries"]==1) echo "checked"?>>
<?php echo $l_publishnewbugentries?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_featurerequests?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="featurerequests" value="1" <?php if($myrow["enablefeaturerequests"]==1) echo "checked"?>>
<?php echo $l_enablefeaturerequests?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="requestspublic" value="1" <?php if($myrow["featurerequestspublic"]==1) echo "checked"?>>
<?php echo $l_featurerequestspublic?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="raterequests" value="1" <?php if($myrow["ratefeaturerequests"]==1) echo "checked"?>>
<?php echo $l_ratefeaturerequests?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="ratespublic" value="1" <?php if($myrow["requestratingspublic"]==1) echo "checked"?>>
<?php echo $l_requestratingspublic?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_newsletter?></b></td></tr>
<tr class="inputrow"><td align="right" valign="top">&nbsp;</td><td>
<input type="checkbox" name="newsletter" value="1" <?php if($myrow["enablenewsletter"]==1) echo "checked"?>>
<?php echo $l_enablenewsletter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablenewsletterfreemailer" value="1" type="checkbox"
<?php if($myrow["newsletterfreemailer"]==1) echo " checked"?>> <?php echo $l_newsletterfreemailer?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_maxconfirmtime?>:</td><td align="left" valign="top">
<input type="checkbox" name="nosubscriptionconfirm" onClick="program_maxconfirmtime()" value="1" <?php if ($myrow["maxconfirmtime"]==0) echo "checked"?>> <?php echo $l_noconfirm?><br>
<select name="maxconfirmtime" <?php if($myrow["maxconfirmtime"]==0) echo "disabled"?>>
<?php
for($i=1;$i<10;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$myrow["maxconfirmtime"])
		echo " selected";
	echo ">$i</option>";
}
?>
</select>&nbsp;<?php echo $l_days?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_emailremark?>:</td><td align="left" valign="top">
<textarea class="psysinput" name="emailremark" rows="5" cols="40"><?php echo $myrow["newsletterremark"]?></textarea>
<a href="javascript:void(0);" onmouseover="this.T_ABOVE=true; return escape('<?php echo $l_prognlinfo?>')"><img src="gfx/info.gif" border="0"></a>
</td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_miscsettings?></b></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="allownoref" value="1" <?php if($myrow["disableref"]==1) echo "checked"?>>
<?php echo $l_disableref?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>">
&nbsp;&nbsp;<input class="psysbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></tr></td></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_proglist?></a></div>
<?php
	}
	if($mode=="updatedir")
	{
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=$input_prognr and usernr=$act_usernr";
		if(!$modresult = mysql_query($modsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($modrow=mysql_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights < 2) || (($admin_rights < 3) && ($ismod==0)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_proglist</a></div>";
			include('trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "UPDATE ".$tableprefix."_programm SET downpath='$downpath', betapath='$betapath' where prognr=$input_prognr";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_progupdated ($l_dirs)";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_proglist</a></div>";
	}
	if($mode=="update")
	{
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=$input_prognr and usernr=$act_usernr";
		if(!$modresult = mysql_query($modsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($modrow=mysql_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights < 2) || (($admin_rights < 3) && ($ismod==0)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_proglist</a></div>";
			include('trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(!$programmname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogname</td></tr>";
			$errors=1;
		}
		if(!$progid)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noid</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(strlen($downpath)>0)
			{
				$downpath=stripslashes($downpath);
				$downpath=str_replace("\\","/",$downpath);
			}
			if(strlen($betapath)>0)
			{
				$betapath=stripslashes($betapath);
				$betapath=str_replace("\\","/",$betapath);
			}
			if(isset($nosubscriptionconfirm))
				$maxconfirmtime=0;
			if(isset($preview))
			{
				if(isset($clearcustomheader))
					$customheader="";
				else
				{
					if($new_global_handling)
						$tmp_file=$HTTP_POST_FILES['customheaderfile']['tmp_name'];
					else
						$tmp_file=$_FILES['customheaderfile']['tmp_name'];
					if($upload_avail && is_uploaded_file($tmp_file))
						$customheader = addslashes(fread(fopen($tmp_file,"r"), filesize($tmp_file)));
				}
				if(isset($clearcustomfooter))
					$customfooter="";
				else
				{
					if($new_global_handling)
						$tmp_file=$HTTP_POST_FILES['customfooterfile']['tmp_name'];
					else
						$tmp_file=$_FILES['customfooterfile']['tmp_name'];
					if($upload_avail && is_uploaded_file($tmp_file))
						$customfooter = addslashes(fread(fopen($tmp_file,"r"), filesize($tmp_file)));
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newprogramm?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_progname?>:</td><td><?php echo $programmname?><input type="hidden" name="programmname" value="<?php echo $programmname?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_id?>:</td><td><?php echo $progid?><input type="hidden" name="progid" value="<?php echo $progid?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td><?php echo $proglang?><input type="hidden" name="proglang" value="<?php echo $proglang?>"></td></tr>
<input type="hidden" name="footerfile" value="<?php echo $footerfile?>">
<input type="hidden" name="headerfile" value="<?php echo $headerfile?>">
<input type="hidden" name="customheader" value="<?php echo $customheader?>">
<input type="hidden" name="customfooter" value="<?php echo $customfooter?>">
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_stylesheet?>:</td><td><?php echo $stylesheet?>
<input type="hidden" name="stylesheet" value="<?php echo $stylesheet?>">
</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_emailsendername?>:</td><td><?php echo $emailname?>
<input type="hidden" name="emailname" value="<?php echo $emailname?>">
</td></tr>
<?php
				if(isset($clearcustomheader))
					echo "<input type=\"hidden\" name=\"clearcustomheader\" value=\"1\">\n";
				if(isset($clearcustomfooter))
					echo "<input type=\"hidden\" name=\"clearcustomfooter\" value=\"1\">\n";
				if(isset($enablecustomheader))
				{
					echo "<input type=\"hidden\" name=\"enablecustomheader\" value=\"1\">\n";
					echo "<tr class=\"displayrow\"><td align=\"right\">$l_customheader:</td>\n";
					echo "<td>".htmlentities($customheader);
					if($headerfile)
						echo "<br><hr noshade color=\"#000000\" size=\"1\">$l_includefile: $headerfile";
					echo "</td></tr>\n";
				}
				if(isset($enablecustomfooter))
				{
					echo "<input type=\"hidden\" name=\"enablecustomfooter\" value=\"1\">\n";
					echo "<tr class=\"displayrow\"><td align=\"right\">$l_customfooter:</td>\n";
					echo "<td>".htmlentities($customfooter);
					if($footerfile)
						echo "<br><hr noshade color=\"#000000\" size=\"1\">$l_includefile: $footerfile";
					echo "</td></tr>\n";
				}
?>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_supportedos?>:</td><td>
<?php
				$os_query="select os.* from ".$tableprefix."_os os, ".$tableprefix."_prog_os po where po.prognr=$input_prognr and os.osnr=po.osnr";
				if(isset($rem_os))
				{
					while(list($null, $os) = each($_POST["rem_os"]))
					{
						echo "<input type=\"hidden\" name=\"rem_os[]\" value=\"$os\">";
						$os_query.=" and os.osnr!=$os";
					}
				}
   			   	if(!$os_result=mysql_query($os_query, $db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
				if($os_row=mysql_fetch_array($os_result))
				{
					do{
						echo $os_row["osname"];
						echo "<br>";
					}while($os_row=mysql_fetch_array($os_result));
				}
				if(isset($os))
				{
					while(list($null, $os) = each($_POST["os"]))
					{
						$os_query = "SELECT * from ".$tableprefix."_os where osnr=$os";
	    			   	if(!$os_result=mysql_query($os_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
						if($os_row=mysql_fetch_array($os_result))
						{
							echo $os_row["osname"];
							echo "<input type=\"hidden\" name=\"os[]\" value=\"$os\"><br>";
						}

					}
				}
?>
</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_downpath?>:</td><td>
<?php echo $downpath?>
<input type="hidden" name="downpath" value="<?php echo $downpath?>"></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_downpath." (".$l_betaversion.")"?>:</td><td>
<?php echo $betapath?>
<input type="hidden" name="betapath" value="<?php echo $betapath?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_admins?>:</td><td>
<?php
				$mod_query="select mod.* from ".$tableprefix."_admins mod, ".$tableprefix."_programm_admins pa where pa.prognr=$input_prognr and mod.usernr=pa.usernr";
				if(isset($rem_mods))
				{
					while(list($null, $mod) = each($_POST["rem_mods"]))
					{
						echo "<input type=\"hidden\" name=\"rem_mods[]\" value=\"$mod\">";
						$mod_query.=" and mod.usernr!=$mod";
					}
				}
   			   	if(!$mod_result=mysql_query($mod_query, $db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
				if($mod_row=mysql_fetch_array($mod_result))
				{
					do{
						echo $mod_row["username"];
						echo "<br>";
					}while($mod_row=mysql_fetch_array($mod_result));
				}
				if(isset($mods))
				{
					while(list($null, $mod) = each($_POST["mods"]))
					{
						$mod_query = "SELECT * from ".$tableprefix."_admins where usernr=$mod";
	    			   	if(!$mod_result=mysql_query($mod_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
						if($mod_row=mysql_fetch_array($mod_result))
						{
							echo $mod_row["username"];
							echo "<input type=\"hidden\" name=\"mods[]\" value=\"$mod\"><br>";
						}

					}
				}
				echo "</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($todorating))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"todorating\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo " $l_enabletodorating</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($hasbeta))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"hasbeta\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_hasbetasection</td></tr>";
?>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_bugreports?></b></td></tr>
<?php
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($enterbugs))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"enterbugs\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_enablebugentries</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($newbugspublic))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"newbugspublic\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_publishnewbugentries</td></tr>";
?>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_featurerequests?></b></td></tr>
<?php
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($featurerequests))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"featurerequests\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_enablefeaturerequests</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($requestspublic))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"requestspublic\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_featurerequestspublic</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($raterequests))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"raterequests\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_ratefeaturerequests</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($ratespublic))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"ratespublic\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_requestratingspublic</td></tr>";
?>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_newsletter?></b></td></tr>
<?php
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($newsletter))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"newsletter\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_enablenewsletter</td></tr>";
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td align=\"left\">";
				if(isset($enablenewsletterfreemailer))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"enablenewsletterfreemailer\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_newsletterfreemailer</td></tr>";
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_maxconfirmtime?>:</td><td align="left" valign="top">
<?php
				if($maxconfirmtime==0)
					echo "$l_noconfirm";
				else
					echo "$maxconfirmtime $l_days";
				echo "</td></tr><input type=\"hidden\" name=\"maxconfirmtime\" value=\"$maxconfirmtime\">";
				$displayremark=str_replace("\n","<br>",htmlentities($emailremark));
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_emailremark?>:</td><td align="left" valign="top">
<input type="hidden" name="emailremark" value="<?php echo $emailremark?>"><?php echo $displayremark?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_miscsettings?></b></td></tr>
<tr class="displayrow"><td>&nbsp;</td><td align="left">
<?php
				if(isset($allownoref))
				{
					echo "<img src=\"$checked_pic\" align=\"middle\">";
					echo "<input type=\"hidden\" name=\"allownoref\" value=\"1\">\n";
				}
				else
					echo "<img src=\"$unchecked_pic\" align=\"middle\">";
				echo "$l_disableref</td></tr>";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input type="hidden" name="input_prognr" value="<?php echo $input_prognr?>">
<input type="hidden" name="frompreview" value="1">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="psysbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="update">
</td></tr></form></table></td></tr></table>
<?php
			}
			else
			{
				if(isset($allownoref))
					$disableref=1;
				else
					$disableref=0;
				if(!isset($hasbeta))
					$hasbeta=0;
				if(isset($ratespublic))
					$requestratingspublic=1;
				else
					$requestratingspublic=0;
				if(isset($newbugspublic))
					$publishnewbugentries=1;
				else
					$publishnewbugentries=0;
				if(isset($raterequests))
					$ratefeaturerequests=1;
				else
					$ratefeaturerequests=0;
				if(isset($requestspublic))
					$featurerequestspublic=1;
				else
					$featurerequestspublic=0;
				if(isset($featurerequests))
					$enablefeaturerequests=1;
				else
					$enablefeaturerequests=0;
				if(isset($enablenewsletterfreemailer))
					$newsletterfreemailer=1;
				else
					$newsletterfreemailer=0;
				if(isset($enterbugs))
					$enablebugentries=1;
				else
					$enablebugentries=0;
				if(isset($newsletter))
					$enablenewsletter=1;
				else
					$enablenewsletter=0;
				if(isset($todorating))
					$enabletodorating=1;
				else
					$enabletodorating=0;
				if(isset($enablecustomheader))
					$usecustomheader=1;
				else
					$usecustomheader=0;
				if(isset($enablecustomfooter))
					$usecustomfooter=1;
				else
					$usecustomfooter=0;
				if(isset($clearcustomheader))
					$customheader="";
				else if(!isset($frompreview))
				{
					if($new_global_handling)
						$tmp_file=$HTTP_POST_FILES['customheaderfile']['tmp_name'];
					else
						$tmp_file=$_FILES['customheaderfile']['tmp_name'];
					if($upload_avail && is_uploaded_file($tmp_file))
						$customheader = addslashes(fread(fopen($tmp_file,"r"), filesize($tmp_file)));
				}
				if(isset($clearcustomfooter))
					$customfooter="";
				else if(!isset($frompreview))
				{
					if($new_global_handling)
						$tmp_file=$HTTP_POST_FILES['customfooterfile']['tmp_name'];
					else
						$tmp_file=$_FILES['customfooterfile']['tmp_name'];
					if($upload_avail && is_uploaded_file($tmp_file))
						$customfooter = addslashes(fread(fopen($tmp_file,"r"), filesize($tmp_file)));
				}
				$footerfile=addslashes($footerfile);
				$headerfile=addslashes($headerfile);
				$programmname=addslashes($programmname);
				$sql = "UPDATE ".$tableprefix."_programm SET programmname='$programmname', progid='$progid', language='$proglang', ";
				$sql.="pageheader='$customheader', ";
				$sql.="pagefooter='$customfooter', ";
				$sql.="usecustomheader=$usecustomheader, usecustomfooter=$usecustomfooter, headerfile='$headerfile', footerfile='$footerfile', stylesheet='$stylesheet', ";
				$sql.="enabletodorating=$enabletodorating, enablenewsletter=$enablenewsletter, enablebugentries=$enablebugentries, newsletterfreemailer=$newsletterfreemailer, ";
				$sql.="maxconfirmtime=$maxconfirmtime, newsletterremark='$emailremark', enablefeaturerequests=$enablefeaturerequests, featurerequestspublic=$featurerequestspublic,  ";
				$sql.="ratefeaturerequests=$ratefeaturerequests, publishnewbugentries=$publishnewbugentries, requestratingspublic=$requestratingspublic, hasbeta=$hasbeta, ";
				$sql.="emailname='$emailname', downpath='$downpath', betapath='$betapath', disableref=$disableref ";
				$sql .="WHERE (prognr = $input_prognr)";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error()."<br>$sql");
				if(isset($mods))
				{
		    		while(list($null, $mod) = each($_POST["mods"]))
		    		{
						$mod_query = "INSERT INTO ".$tableprefix."_programm_admins (prognr, usernr) VALUES ('$input_prognr', '$mod')";
		    		   	if(!mysql_query($mod_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				if(isset($rem_mods))
				{
					while(list($null, $mod) = each($_POST["rem_mods"]))
					{
						$rem_query = "DELETE FROM ".$tableprefix."_programm_admins WHERE prognr = '$input_prognr' AND usernr = '$mod'";
		       			if(!mysql_query($rem_query))
						    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				if(isset($os))
				{
					while(list($null, $os) = each ($_POST["os"]))
					{
						$os_query = "INSERT INTO ".$tableprefix."_prog_os (osnr, prognr) VALUES ('$os', '$input_prognr')";
		    		   	if(!mysql_query($os_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				if(isset($rem_os))
				{
					while(list($null, $os) = each($_POST["rem_os"]))
					{
						$rem_query = "DELETE FROM ".$tableprefix."_prog_os WHERE prognr = '$input_prognr' AND osnr='$os'";
		       			if(!mysql_query($rem_query))
						    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_progupdated";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_proglist</a></div>";
			}
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
<a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newprogramm?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
// Display list of actual programms
$sql = "select * from ".$tableprefix."_programm order by prognr";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\"><b>#</b></td>";
	echo "<td align=\"center\" width=\"10%\"><b>$l_id</b></td>";
	echo "<td align=\"center\" width=\"50%\"><b>$l_progname</b></td>";
	echo "<td align=\"center\" width=\"15%\"><b>$l_language</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$act_id=$myrow["prognr"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"right\">".$myrow["prognr"]."</td>";
		echo "<td align=\"right\">".$myrow["progid"]."</td>";
		echo "<td>".htmlentities($myrow["programmname"])."</td>";
		echo "<td align=\"center\">".$myrow["language"]."</td>";
		echo "<td>";
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=$act_id and usernr=$act_usernr";
		if(!$modresult = mysql_query($modsql, $db)) {
		    die("Could not connect to the database.");
		}
		if($modrow=mysql_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights>2) || ($ismod==1))
		{
			$dellink=do_url_session("$act_script_url?mode=delete&input_prognr=$act_id&lang=$lang&progname=".urlencode($myrow["programmname"]));
			if($admdelconfirm==1)
				echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_programm #$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
			echo "<img src=\"gfx/delete.gif\" alt=\"$l_delete\" title=\"$l_delete\" border=\"0\"></a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&lang=$lang&input_prognr=$act_id")."\">";
			echo "<img src=\"gfx/edit.gif\" title=\"$l_edit\" alt=\"$l_edit\" border=\"0\"></a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=editdir&lang=$lang&input_prognr=$act_id")."\">";
			echo "<img src=\"gfx/chdir.gif\" title=\"$l_editdir\" alt=\"$l_editdir\" border=\"0\"></a>&nbsp; ";
		}
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_prognr=$act_id&lang=$lang&progname=".urlencode($myrow["programmname"]))."\">";
		echo "<img src=\"gfx/view.gif\" alt=\"$l_display\" title=\"$l_display\" border=\"0\"></a>";
		echo "</td></tr>";
	} while($myrow = mysql_fetch_array($result));
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newprogramm?></a></div>
<?php
}
}
include('trailer.php');
?>