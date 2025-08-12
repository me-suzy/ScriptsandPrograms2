<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_categories;
$ccform="inputform";
$colorchooser=true;
$bbcbuttons=true;
$page="categories";
require_once('./heading.php');
include_once("./includes/icon_selector.inc");
include_once("./includes/color_chooser.inc");
include_once("./includes/bbcode_buttons.inc");
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	// Page called with some special mode
	if($mode=="new")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(($admin_rights == 2) && !bittst($secsettings,BIT_7))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_addcategory?></b></td></tr>
<form name="inputform" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="colorfield" value="">
<tr class="inputrow" valign="top"><td align="right" width="30%"><?php echo $l_catname?>:</td><td><input class="sninput" type="text" name="catname" size="40" maxlength="40"><br>
<?php
$availlangs=language_list("../language");
echo "<table width=\"100%\" align=\"left\">";
for($i=0;$i<count($availlangs);$i++)
{
	echo "<tr class=\"inputrow\"><td align=\"right\" width=\"10%\">";
	echo $availlangs[$i];
	echo ":</td><td align=\"left\">";
	echo "<input class=\"sninput\" type=\"text\" size=\"40\" maxlength=\"40\" name=\"catname_".$availlangs[$i]."\"></td></tr>";
}
echo "</table>";
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_icon?>:</td>
<?php
		echo "<td valign=\"middle\" nowrap>";
		icon_selector("","headingicon");
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_displayiconon?>:</td>
<td>
<input type="checkbox" name="icon_news" value="1"> news.php<br>
<input type="checkbox" name="icon_news2" value="1"> news2.php<br>
<input type="checkbox" name="icon_news3" value="1"> news3.php<br>
<input type="checkbox" name="icon_news4" value="1"> news4.php<br>
<input type="checkbox" name="icon_news5" value="1"> news5.php<br>
<input type="checkbox" name="icon_events" value="1"> events.php<br>
<input type="checkbox" name="icon_events2" value="1"> evlist2.php<br>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_newsframelayout?>:</td>
<td><select name="newsframelayout"><option value="" selected><?php echo $l_nospeclayout?></option>
<?php
	$tmpsql="select * from ".$tableprefix."_layout group by id";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	while($tmprow=mysql_fetch_array($tmpresult))
	{
		echo "<option value=\"".$tmprow["id"]."\"";
		echo ">".$tmprow["id"];
		if($tmprow["deflayout"]==1)
			echo " [*]";
		echo "</option>";
	}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_evmarkcol?>:</td>
<?php echo color_chooser("evmarkcolor","#999900","inputform")?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_infotext?>:</td><td>
<textarea name="headertext" class="sninput" rows="8" cols="40"></textarea>
<?php
		if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
			display_bbcode_buttons($l_bbbuttons,"headertext");
		echo "</td></tr>";
		if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
		{
			echo "<tr class=\"optionrow\"><td align=\"right\" valign=\"top\">$l_options:</td><td align=\"left\">";
			if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
			{
				echo "<input type=\"checkbox\" name=\"urlautoencode\" value=\"1\" checked> $l_urlautoencode<br>";
				echo "<input type=\"checkbox\" name=\"enablespcode\" value=\"1\" checked> $l_enablespcode<br>";
			}
			echo "</td></tr>";
		}
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customfooter?>:<br>
<input name="enablecustomfooter" value="1" type="checkbox"> <?php echo $l_enable?></td>
<td align="left"><textarea class="sninput" name="customfooter" rows="8" cols="40"></textarea>
<?php
if($upload_avail)
{
?>
<hr noshade color="#000000" size="1">
<?php echo $l_uploadfromfile?>: <input class="sninput" type="file" name="customfooterfile">
<?php
}
?>
<hr noshade color="#000000" size="1">
<b><?php echo $l_options?>:</b><br>
<input type="radio" name="footerpos" value="0"> <?php echo $l_replaceglobalfooter?><br>
<input type="radio" name="footerpos" value="1" checked> <?php echo $l_beforeglobalfooter?><br>
<input type="radio" name="footerpos" value="2" > <?php echo $l_afterglobalfooter?>
</td></tr>
<?php
if($admin_rights>2)
{
?>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_admins?>:</td>
<td>
<SELECT NAME="mods[]" size="5" multiple>
<?php
		$sql = "SELECT usernr, username FROM ".$tableprefix."_users WHERE rights = 2 ORDER BY username";
	    if(!$r = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	    if($row = mysql_fetch_array($r)) {
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
{
	echo "<input type=\"hidden\" name=\"mods[]\" value=\"".$userdata["usernr"]."\">";
}
?>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="catlisthide" value="1">&nbsp;<?php echo $l_catlisthide?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="totallisthide" value="1">&nbsp;<?php echo $l_totallisthide?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="searchignore" value="1">&nbsp;<?php echo $l_ignoreonsearch?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="newsletterexclude" value="1">&nbsp;<?php echo $l_newsletterexclude?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="allowpropose" value="1"> <?php echo $l_proposenews?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="archiv" value="1"> <?php echo $l_isarchiv?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="catrating" value="1" checked> <?php echo $l_enablerating?></td></tr>
<?php
if($rss_enable==1)
{
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_rss_newsfeed_channel?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_title?>:</td>
<td><input type="text" class="sninput" name="rss_channel_title" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_description?>:</td>
<td><input type="text" class="sninput" name="rss_channel_description" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_website_link?>:</td>
<td><input type="text" class="sninput" name="rss_channel_link" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_copyrightnotice?>:</td>
<td><input type="text" class="sninput" name="rss_channel_copyright" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_managingeditor?>:<br><?php echo $l_rss_mailformat?></td>
<td><input type="text" class="sninput" name="rss_channel_editor" size="30" maxlength="100"></td></tr>
<?php
}
?>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="snbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_categories?></a></div>
<?php
	}
	if($mode=="add")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		} else if(($admin_rights == 2) && !bittst($secsettings,BIT_7))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(!$catname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocatname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($catrating))
				$enablerating=1;
			else
				$enablerating=0;
			if(isset($searchignore))
				$ignoreonsearch=1;
			else
				$ignoreonsearch=0;
			if(isset($archiv))
				$isarchiv=1;
			else
				$isarchiv=0;
			if(isset($newsletterexclude))
				$excludefromnewsletter=1;
			else
				$excludefromnewsletter=0;
			if(isset($totallisthide))
				$hideintotallist=1;
			else
				$hideintotallist=0;
			$iconoptions=0;
			if(isset($icon_news))
				$iconoptions=setbit($iconoptions,BIT_1);
			if(isset($icon_news2))
				$iconoptions=setbit($iconoptions,BIT_2);
			if(isset($icon_news3))
				$iconoptions=setbit($iconoptions,BIT_3);
			if(isset($icon_news4))
				$iconoptions=setbit($iconoptions,BIT_4);
			if(isset($icon_news5))
				$iconoptions=setbit($iconoptions,BIT_5);
			if(isset($icon_events))
				$iconoptions=setbit($iconoptions,BIT_6);
			if(isset($icon_events2))
				$iconoptions=setbit($iconoptions,BIT_7);
			$footeroptions=0;
			if(isset($enablecustomfooter))
				$footeroptions=setbit($footeroptions,BIT_1);
			if($footerpos==0)
				$footeroptions=setbit($footeroptions,BIT_2);
			if($footerpos==2)
				$footeroptions=setbit($footeroptions,BIT_3);
			if($new_global_handling)
				$tmp_file=$_FILES['customfooterfile']['tmp_name'];
			else
				$tmp_file=$HTTP_POST_FILES['customfooterfile']['tmp_name'];
			if($upload_avail && is_uploaded_file($tmp_file))
			{
				if(isset($path_tempdir) && $path_tempdir)
				{
					if($new_global_handling)
						$filename=$_FILES['customfooterfile']['name'];
					else
						$filename=$HTTP_POST_FILES['customfooterfile']['name'];
					if(!move_uploaded_file ($tmp_file, $path_tempdir."/".$filename))
					{
						echo "<tr class=\"errorrow\"><td align=\"center\">";
						printf($l_cantmovefile,$path_tempdir."/".$filename);
						echo "</td></tr>";
						die();
					}
					$orgfile=$path_tempdir."/".$filename;
				}
				else
					$orgfile=$tmp_file;
				$customfooter = addslashes(get_file($orgfile));
				if(isset($path_tempdir) && $path_tempdir)
					unlink($orgfile);
			}
			if($customfooter)
				$customfooter=str_replace("\n","<BR>",$customfooter);
			if(isset($allowpropose))
				$enablepropose=1;
			else
				$enablepropose=0;
			if(isset($catlisthide))
				$hideincatlist=1;
			else
				$hideincatlist=0;
			if(isset($urlautoencode))
				$headertext = make_clickable($headertext);
			if(isset($enablespcode))
				$headertext = bbencode($headertext);
			$headertext = do_htmlentities($headertext);
			$headertext = str_replace("\n", "<BR>", $headertext);
			$headertext=addslashes($headertext);
			$sql = "select max(displaypos) as newdisplaypos from ".$tableprefix."_categories";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add category to database (1).".mysql_error());
			if($myrow=mysql_fetch_array($result))
				$displaypos=$myrow["newdisplaypos"]+1;
			else
				$displaypos=1;
			$sql = "INSERT INTO ".$tableprefix."_categories (headertext, catname, hideincatlist, enablepropose, customfooter, footeroptions, newsframelayout, displaypos, iconoptions, icon, excludefromnewsletter, hideintotallist, ignoreonsearch, evmarkcolor, isarchiv, enablerating";
			if($rss_enable==1)
				$sql.=", rss_channel_title, rss_channel_description, rss_channel_link, rss_channel_copyright, rss_channel_editor";
			$sql.= ") ";
			$sql.="VALUES ('$headertext', '$catname', $hideincatlist, $enablepropose, '$customfooter', $footeroptions, '$newsframelayout', $displaypos, $iconoptions, '$headingicon', $excludefromnewsletter, $hideintotallist, $ignoreonsearch, '$evmarkcolor', $isarchiv, $enablerating";
			if($rss_enable==1)
				$sql.=", '$rss_channel_title', '$rss_channel_description', '$rss_channel_link', '$rss_channel_copyright', '$rss_channel_editor'";
			$sql.=")";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to add category to database (2).".mysql_error());
			$catnr = mysql_insert_id($db);
			if(isset($mods))
			{
				while(list($null, $mod) = each($_POST["mods"]))
				{
					$mod_query = "INSERT INTO ".$tableprefix."_cat_adm (catnr, usernr) VALUES ('$catnr', '$mod')";
					if(!mysql_query($mod_query, $db))
						die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			$availlangs=language_list("../language");
			for($i=0;$i<count($availlangs);$i++)
			{
				$catlvar="catname_".$availlangs[$i];
				if(isset($$catlvar))
				{
					$cname_query = "DELETE FROM ".$tableprefix."_catnames WHERE catnr=$catnr AND lang='".$availlangs[$i]."'";
					if(!mysql_query($cname_query,$db))
						die("<tr class=\"errorrow\"><td>Unable to update the database. ".mysql_error());
					$cname_query = "INSERT INTO ".$tableprefix."_catnames (catnr, lang, catname) VALUES ($catnr,'".$availlangs[$i]."','".$$catlvar."')";
					if(!mysql_query($cname_query,$db))
						die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_categoryadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_categories</a></div>";
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
		if(($admin_rights == 2) && !bittst($secsettings,BIT_7))
		{
			echo "<tr bgcolor=\"#cccccc\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(($admdelconfirm==1) && !isset($confirmed))
		{
?>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="mode" value="delete">
<input type="hidden" name="input_catnr" value="<?php echo $input_catnr?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel: Kategorie #$input_catnr";
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$deleteSQL = "delete from ".$tableprefix."_cat_adm where (catnr=$input_catnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_hn6cats where (catnr=$input_catnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_wap_catlist where (catnr=$input_catnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$sql = "select * from ".$tableprefix."_events where category=$input_catnr";
		if(!$result = mysql_query($sql,$db))
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		while($myrow=mysql_fetch_array($result))
		{
			$tmpsql="delete from ".$tableprefix."_events_attachs where eventnr=".$myrow["eventnr"];
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$tmpsql="delete from ".$tableprefix."_evsearch where eventnr=".$myrow["eventnr"];
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$tmpsql="delete from ".$tableprefix."_events where linkeventnr=".$myrow["eventnr"];
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		}
		$tmpsql="delete from ".$tableprefix."_events where category=$input_catnr";
		if(!$tmpresult=mysql_query($tmpsql,$db))
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$sql = "select * from ".$tableprefix."_data where category=$input_catnr";
		if(!$result = mysql_query($sql,$db))
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		while($myrow=mysql_fetch_array($result))
		{
			$tmpsql="delete from ".$tableprefix."_news_attachs where newsnr=".$myrow["newsnr"];
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$tmpsql="delete from ".$tableprefix."_comments where entryref=".$myrow["newsnr"];
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$tmpsql="delete from ".$tableprefix."_search where newsnr=".$myrow["newsnr"];
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$tmpsql="delete from ".$tableprefix."_data where linknewsnr=".$myrow["newsnr"];
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		}
		$tmpsql="delete from ".$tableprefix."_data where category=$input_catnr";
		if(!$tmpresult=mysql_query($tmpsql,$db))
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_tmpdata where category=$input_catnr";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_tmpevents where category=$input_catnr";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$sql = "select * from ".$tableprefix."_announce where category=$input_catnr";
		if(!$result = mysql_query($sql,$db))
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		while($myrow=mysql_fetch_array($result))
		{
			$tmpsql="delete from ".$tableprefix."_announce_attachs where entrynr=".$myrow["entrynr"];
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$tmpsql="delete from ".$tableprefix."_ansearch where annr=".$myrow["entrynr"];
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		}
		$tmpsql="delete from ".$tableprefix."_announce where category=$input_catnr";
		if(!$tmpresult=mysql_query($tmpsql,$db))
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_subscriptions where (category=$input_catnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_categories where (catnr=$input_catnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_categories</a></div>";
	}
	if($mode=="copy")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(($admin_rights == 2) && !bittst($secsettings,BIT_7))
		{
			echo "<tr bgcolor=\"#cccccc\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$sql = "select * from ".$tableprefix."_categories where (catnr=$input_catnr)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$customfooter=$myrow["customfooter"];
		if($customfooter)
			$customfooter=str_replace("<BR>","\n",$customfooter);
		$footeroptions=$myrow["footeroptions"];
		$headertext = stripslashes($myrow["headertext"]);
		$headertext = str_replace("<BR>", "\n", $headertext);
		$headertext = undo_htmlspecialchars($headertext);
		$headertext = bbdecode($headertext);
		$headertext = undo_make_clickable($headertext);
		$rss_channel_title=$myrow["rss_channel_title"];
		$rss_channel_description=$myrow["rss_channel_description"];
		$rss_channel_link=$myrow["rss_channel_link"];
		$rss_channel_copyright=$myrow["rss_channel_copyright"];
		$rss_channel_editor=$myrow["rss_channel_editor"];
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editcategory?></b></td></tr>
<form name="inputform" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="colorfield" value="">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_catname?>:</td><td><input class="sninput" type="text" name="catname" size="40" maxlength="40"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_icon?>:</td>
<?php
		echo "<td valign=\"middle\" nowrap>";
		icon_selector($myrow["icon"],"headingicon");
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_displayiconon?>:</td>
<td>
<input type="checkbox" name="icon_news" value="1" <?php if(bittst($myrow["iconoptions"],BIT_1)) echo "checked"?>> news.php<br>
<input type="checkbox" name="icon_news2" value="1" <?php if(bittst($myrow["iconoptions"],BIT_2)) echo "checked"?>> news2.php<br>
<input type="checkbox" name="icon_news3" value="1" <?php if(bittst($myrow["iconoptions"],BIT_3)) echo "checked"?>> news3.php<br>
<input type="checkbox" name="icon_news4" value="1" <?php if(bittst($myrow["iconoptions"],BIT_4)) echo "checked"?>> news4.php<br>
<input type="checkbox" name="icon_news5" value="1" <?php if(bittst($myrow["iconoptions"],BIT_5)) echo "checked"?>> news5.php<br>
<input type="checkbox" name="icon_events" value="1" <?php if(bittst($myrow["iconoptions"],BIT_6)) echo "checked"?>> events.php<br>
<input type="checkbox" name="icon_events2" value="1" <?php if(bittst($myrow["iconoptions"],BIT_7)) echo "checked"?>> evlist2.php<br>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_newsframelayout?>:</td>
<td><select name="newsframelayout"><option value="" <?php if(!$myrow["newsframelayout"]) echo "selected"?>><?php echo $l_nospeclayout?></option>
<?php
		$tmpsql="select * from ".$tableprefix."_layout group by id";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			echo "<option value=\"".$tmprow["id"]."\"";
			if($tmprow["id"]==$myrow["newsframelayout"])
				echo " selected";
			echo ">".$tmprow["id"];
			if($tmprow["deflayout"]==1)
				echo " [*]";
			echo "</option>";
		}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_evmarkcol?>:</td>
<?php echo color_chooser("evmarkcolor",$myrow["evmarkcolor"],"inputform")?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_infotext?>:</td><td>
<textarea name="headertext" class="sninput" rows="8" cols="40"><?php echo $headertext?></textarea>
<?php
		if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
			display_bbcode_buttons($l_bbbuttons,"headertext");
		echo "</td></tr>";
		if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
		{
			echo "<tr class=\"optionrow\"><td align=\"right\" valign=\"top\">$l_options:</td><td align=\"left\">";
			echo "<input type=\"checkbox\" name=\"urlautoencode\" value=\"1\" checked> $l_urlautoencode<br>";
			echo "<input type=\"checkbox\" name=\"enablespcode\" value=\"1\" checked> $l_enablespcode<br>";
			echo "</td></tr>";
		}
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customfooter?>:<br>
<input name="enablecustomfooter" value="1" type="checkbox"
<?php if(bittst($footeroptions,BIT_1)) echo " checked"?>> <?php echo $l_enable?></td>
<td align="left"><textarea class="sninput" name="customfooter" rows="8" cols="40"><?php echo do_htmlentities($customfooter)?></textarea>
<?php
if($upload_avail)
{
	echo "<hr noshade color=\"#000000\" size=\"1\">";
	echo "$l_uploadfromfile: <input class=\"sninput\" type=\"file\" name=\"customfooterfile\">";
}
?>
<hr noshade color="#000000" size="1">
<input type="checkbox" value="1" name="clearcustomfooter"><?php echo $l_clear?>
<hr noshade color="#000000" size="1">
<b><?php echo $l_options?>:</b><br>
<input type="radio" name="footerpos" value="0" <?php if(bittst($footeroptions,BIT_2)) echo "checked"?>> <?php echo $l_replaceglobalfooter?><br>
<input type="radio" name="footerpos" value="1" <?php if(!bittst($footeroptions,BIT_2) && !bittst($footeroptions,BIT_3)) echo "checked"?>> <?php echo $l_beforeglobalfooter?><br>
<input type="radio" name="footerpos" value="2" <?php if(bittst($footeroptions,BIT_3)) echo "checked"?>> <?php echo $l_afterglobalfooter?>
</td></tr>
<?php
if($admin_rights > 2)
{
?>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_admins?>:</td>
<td>
<?php
	$sql = "SELECT u.username, u.usernr FROM ".$tableprefix."_users u, ".$tableprefix."_cat_adm ca WHERE ca.catnr = '$input_catnr' AND u.usernr = ca.usernr order by u.username";
	if(!$r = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
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
	$sql = "SELECT usernr, username FROM ".$tableprefix."_users WHERE rights = 2 ";
	if(isset($current_mods))
	{
    	while(list($null, $currMod) = each($current_mods)) {
			$sql .= "AND usernr != $currMod ";
    	}
    }
    $sql .= "ORDER BY username";
    if(!$r = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
    if($row = mysql_fetch_array($r)) {
		echo "<span class=\"inlineheading1\">$l_add:</span><br>";
		echo"<SELECT NAME=\"mods[]\" size=\"5\" multiple>";
		do {
			echo "<OPTION VALUE=\"$row[usernr]\" >$row[username]</OPTION>\n";
		} while($row = mysql_fetch_array($r));
		echo"</select>";
	}
	echo "</td></tr>";
}
?>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="catlisthide" value="1" <?php if($myrow["hideincatlist"]==1) echo "checked"?>>&nbsp;<?php echo $l_catlisthide?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="totallisthide" value="1" <?php if($myrow["hideintotallist"]==1) echo "checked"?>>&nbsp;<?php echo $l_totallisthide?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="searchignore" value="1">&nbsp;<?php echo $l_ignoreonsearch?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="newsletterexclude" value="1" <?php if($myrow["excludefromnewsletter"]==1) echo "checked"?>>&nbsp;<?php echo $l_newsletterexclude?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="allowpropose" value="1" <?php if($myrow["enablepropose"]==1) echo "checked"?>> <?php echo $l_proposenews?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="archiv" value="1" <?php if($myrow["isarchiv"]==1) echo "checked"?>> <?php echo $l_isarchiv?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="catrating" value="1" <?php if($myrow["enablerating"]==1) echo "checked"?>> <?php echo $l_enablerating?></td></tr>
<?php
if($rss_enable==1)
{
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_rss_newsfeed_channel?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_title?>:</td>
<td><input type="text" class="sninput" name="rss_channel_title" value="<?php echo $rss_channel_title?>" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_description?>:</td>
<td><input type="text" class="sninput" name="rss_channel_description" value="<?php echo $rss_channel_description?>" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_website_link?>:</td>
<td><input type="text" class="sninput" name="rss_channel_link" value="<?php echo $rss_channel_link?>" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_copyrightnotice?>:</td>
<td><input type="text" class="sninput" name="rss_channel_copyright" value="<?php echo $rss_channel_copyright?>" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_managingeditor?>:<br><?php echo $l_rss_mailformat?></td>
<td><input type="text" class="sninput" name="rss_channel_editor" value="<?php echo $rss_channel_editor?>" size="30" maxlength="100"></td></tr>
<?php
}
?>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="snbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_categories?></a></div>
<?php
	}
	if($mode=="edit")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(($admin_rights == 2) && !bittst($secsettings,BIT_7))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$sql = "select * from ".$tableprefix."_categories where (catnr=$input_catnr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$customfooter=$myrow["customfooter"];
		if($customfooter)
			$customfooter=str_replace("<BR>","\n",$customfooter);
		$footeroptions=$myrow["footeroptions"];
		$headertext = stripslashes($myrow["headertext"]);
		$headertext = str_replace("<BR>", "\n", $headertext);
		$headertext = undo_htmlspecialchars($headertext);
		$headertext = bbdecode($headertext);
		$headertext = undo_make_clickable($headertext);
		$rss_channel_title=$myrow["rss_channel_title"];
		$rss_channel_description=$myrow["rss_channel_description"];
		$rss_channel_link=$myrow["rss_channel_link"];
		$rss_channel_copyright=$myrow["rss_channel_copyright"];
		$rss_channel_editor=$myrow["rss_channel_editor"];
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editcategory?></b></td></tr>
<form name="inputform" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="colorfield" value="">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_catnr" value="<?php echo $myrow["catnr"]?>">
<tr class="inputrow" valign="top"><td align="right" width="30%"><?php echo $l_catname?>:</td><td><input class="sninput" type="text" name="catname" size="40" maxlength="40" value="<?php echo display_encoded($myrow["catname"])?>"><br>
<?php
$availlangs=language_list("../language");
echo "<table width=\"100%\" align=\"left\">";
for($i=0;$i<count($availlangs);$i++)
{
	echo "<tr class=\"inputrow\"><td align=\"right\" width=\"10%\">";
	echo $availlangs[$i];
	echo ":</td><td align=\"left\">";
	echo "<input class=\"sninput\" type=\"text\" size=\"40\" maxlength=\"40\" name=\"catname_".$availlangs[$i]."\"";
	$tmpsql="select * from ".$tableprefix."_catnames where catnr=$input_catnr and lang='".$availlangs[$i]."'";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if ($tmprow = mysql_fetch_array($tmpresult))
		echo " value=\"".display_encoded($tmprow["catname"])."\"";
	echo "></td></tr>";
}
echo "</table>";
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_icon?>:</td>
<?php
		echo "<td valign=\"middle\" nowrap>";
		icon_selector($myrow["icon"],"headingicon");
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_displayiconon?>:</td>
<td>
<input type="checkbox" name="icon_news" value="1" <?php if(bittst($myrow["iconoptions"],BIT_1)) echo "checked"?>> news.php<br>
<input type="checkbox" name="icon_news2" value="1" <?php if(bittst($myrow["iconoptions"],BIT_2)) echo "checked"?>> news2.php<br>
<input type="checkbox" name="icon_news3" value="1" <?php if(bittst($myrow["iconoptions"],BIT_3)) echo "checked"?>> news3.php<br>
<input type="checkbox" name="icon_news4" value="1" <?php if(bittst($myrow["iconoptions"],BIT_4)) echo "checked"?>> news4.php<br>
<input type="checkbox" name="icon_news5" value="1" <?php if(bittst($myrow["iconoptions"],BIT_5)) echo "checked"?>> news5.php<br>
<input type="checkbox" name="icon_events" value="1" <?php if(bittst($myrow["iconoptions"],BIT_6)) echo "checked"?>> events.php<br>
<input type="checkbox" name="icon_events2" value="1" <?php if(bittst($myrow["iconoptions"],BIT_7)) echo "checked"?>> evlist2.php<br>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_newsframelayout?>:</td>
<td><select name="newsframelayout"><option value="" <?php if(!$myrow["newsframelayout"]) echo "selected"?>><?php echo $l_nospeclayout?></option>
<?php
		$tmpsql="select * from ".$tableprefix."_layout group by id";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			echo "<option value=\"".$tmprow["id"]."\"";
			if($tmprow["id"]==$myrow["newsframelayout"])
				echo " selected";
			echo ">".$tmprow["id"];
			if($tmprow["deflayout"]==1)
				echo " [*]";
			echo "</option>";
		}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_evmarkcol?>:</td>
<?php echo color_chooser("evmarkcolor",$myrow["evmarkcolor"],"inputform")?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_infotext?>:</td><td>
<textarea name="headertext" class="sninput" rows="8" cols="40"><?php echo $headertext?></textarea>
<?php
		if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
			display_bbcode_buttons($l_bbbuttons,"headertext",false,false,"inputform",true);
		echo "</td></tr>";
		if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
		{
			echo "<tr class=\"optionrow\"><td align=\"right\" valign=\"top\">$l_options:</td><td align=\"left\">";
			echo "<input type=\"checkbox\" name=\"urlautoencode\" value=\"1\" checked> $l_urlautoencode<br>";
			echo "<input type=\"checkbox\" name=\"enablespcode\" value=\"1\" checked> $l_enablespcode<br>";
			echo "</td></tr>";
		}
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customfooter?>:<br>
<input name="enablecustomfooter" value="1" type="checkbox"
<?php if(bittst($footeroptions,BIT_1)) echo " checked"?>> <?php echo $l_enable?></td>
<td align="left"><textarea class="sninput" name="customfooter" rows="8" cols="40"><?php echo do_htmlentities($customfooter)?></textarea>
<?php
if($upload_avail)
{
	echo "<hr noshade color=\"#000000\" size=\"1\">";
	echo "$l_uploadfromfile: <input class=\"sninput\" type=\"file\" name=\"customfooterfile\">";
}
?>
<hr noshade color="#000000" size="1">
<input type="checkbox" value="1" name="clearcustomfooter"><?php echo $l_clear?>
<hr noshade color="#000000" size="1">
<b><?php echo $l_options?>:</b><br>
<input type="radio" name="footerpos" value="0" <?php if(bittst($footeroptions,BIT_2)) echo "checked"?>> <?php echo $l_replaceglobalfooter?><br>
<input type="radio" name="footerpos" value="1" <?php if(!bittst($footeroptions,BIT_2) && !bittst($footeroptions,BIT_3)) echo "checked"?>> <?php echo $l_beforeglobalfooter?><br>
<input type="radio" name="footerpos" value="2" <?php if(bittst($footeroptions,BIT_3)) echo "checked"?>> <?php echo $l_afterglobalfooter?>
</td></tr>
<?php
if($admin_rights > 2)
{
?>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_admins?>:</td>
<td>
<?php
	$sql = "SELECT u.username, u.usernr FROM ".$tableprefix."_users u, ".$tableprefix."_cat_adm ca WHERE ca.catnr = '$input_catnr' AND u.usernr = ca.usernr order by u.username";
	if(!$r = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
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
	$sql = "SELECT usernr, username FROM ".$tableprefix."_users WHERE rights = 2 ";
	if(isset($current_mods))
	{
    	while(list($null, $currMod) = each($current_mods)) {
			$sql .= "AND usernr != $currMod ";
    	}
    }
    $sql .= "ORDER BY username";
    if(!$r = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
    if($row = mysql_fetch_array($r)) {
		echo "<span class=\"inlineheading1\">$l_add:</span><br>";
		echo"<SELECT NAME=\"mods[]\" size=\"5\" multiple>";
		do {
			echo "<OPTION VALUE=\"$row[usernr]\" >$row[username]</OPTION>\n";
		} while($row = mysql_fetch_array($r));
		echo"</select>";
	}
	echo "</td></tr>";
}
?>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="catlisthide" value="1" <?php if($myrow["hideincatlist"]==1) echo "checked"?>>&nbsp;<?php echo $l_catlisthide?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="totallisthide" value="1" <?php if($myrow["hideintotallist"]==1) echo "checked"?>>&nbsp;<?php echo $l_totallisthide?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="searchignore" value="1" <?php if($myrow["ignoreonsearch"]==1) echo "checked"?>>&nbsp;<?php echo $l_ignoreonsearch?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="newsletterexclude" value="1" <?php if($myrow["excludefromnewsletter"]==1) echo "checked"?>>&nbsp;<?php echo $l_newsletterexclude?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="allowpropose" value="1" <?php if($myrow["enablepropose"]==1) echo "checked"?>> <?php echo $l_proposenews?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="archiv" value="1" <?php if($myrow["isarchiv"]==1) echo "checked"?>> <?php echo $l_isarchiv?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="catrating" value="1" <?php if($myrow["enablerating"]==1) echo "checked"?>> <?php echo $l_enablerating?></td></tr>
<?php
if($rss_enable==1)
{
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_rss_newsfeed_channel?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_title?>:</td>
<td><input type="text" class="sninput" name="rss_channel_title" value="<?php echo $rss_channel_title?>" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_description?>:</td>
<td><input type="text" class="sninput" name="rss_channel_description" value="<?php echo $rss_channel_description?>" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_website_link?>:</td>
<td><input type="text" class="sninput" name="rss_channel_link" value="<?php echo $rss_channel_link?>" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_copyrightnotice?>:</td>
<td><input type="text" class="sninput" name="rss_channel_copyright" value="<?php echo $rss_channel_copyright?>" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_managingeditor?>:<br><?php echo $l_rss_mailformat?></td>
<td><input type="text" class="sninput" name="rss_channel_editor" value="<?php echo $rss_channel_editor?>" size="30" maxlength="100"></td></tr>
<?php
}
?>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="snbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_categories?></a></div>
<?php
	}
	if($mode=="update")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(($admin_rights == 2) && !bittst($secsettings,BIT_7))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(!$catname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocatname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($catrating))
				$enablerating=1;
			else
				$enablerating=0;
			if(isset($searchignore))
				$ignoreonsearch=1;
			else
				$ignoreonsearch=0;
			if(isset($archiv))
				$isarchiv=1;
			else
				$isarchiv=0;
			if(isset($newsletterexclude))
				$excludefromnewsletter=1;
			else
				$excludefromnewsletter=0;
			if(isset($totallisthide))
				$hideintotallist=1;
			else
				$hideintotallist=0;
			$iconoptions=0;
			if(isset($icon_news))
				$iconoptions=setbit($iconoptions,BIT_1);
			if(isset($icon_news2))
				$iconoptions=setbit($iconoptions,BIT_2);
			if(isset($icon_news3))
				$iconoptions=setbit($iconoptions,BIT_3);
			if(isset($icon_news4))
				$iconoptions=setbit($iconoptions,BIT_4);
			if(isset($icon_news5))
				$iconoptions=setbit($iconoptions,BIT_5);
			if(isset($icon_events))
				$iconoptions=setbit($iconoptions,BIT_6);
			if(isset($icon_events2))
				$iconoptions=setbit($iconoptions,BIT_7);
			$footeroptions=0;
			if(isset($enablecustomfooter))
				$footeroptions=setbit($footeroptions,BIT_1);
			if($footerpos==0)
				$footeroptions=setbit($footeroptions,BIT_2);
			if($footerpos==2)
				$footeroptions=setbit($footeroptions,BIT_3);
			if(isset($clearcustomfooter))
				$customfooter="";
			else
			{
				if($new_global_handling)
					$tmp_file=$_FILES['customfooterfile']['tmp_name'];
				else
					$tmp_file=$HTTP_POST_FILES['customfooterfile']['tmp_name'];
				if($upload_avail && is_uploaded_file($tmp_file))
				{
					if(isset($path_tempdir) && $path_tempdir)
					{
						if($new_global_handling)
							$filename=$_FILES['customfooterfile']['name'];
						else
							$filename=$HTTP_POST_FILES['customfooterfile']['name'];
						if(!move_uploaded_file ($tmp_file, $path_tempdir."/".$filename))
						{
							echo "<tr class=\"errorrow\"><td align=\"center\">";
							printf($l_cantmovefile,$path_tempdir."/".$filename);
							echo "</td></tr>";
							die();
						}
						$orgfile=$path_tempdir."/".$filename;
					}
					else
						$orgfile=$tmp_file;
					$customfooter = addslashes(get_file($orgfile));
					if(isset($path_tempdir) && $path_tempdir)
						unlink($orgfile);
				}
			}
			if($customfooter)
				$customfooter=str_replace("\n","<BR>",$customfooter);
			if(isset($allowpropose))
				$enablepropose=1;
			else
				$enablepropose=0;
			if(isset($catlisthide))
				$hideincatlist=1;
			else
				$hideincatlist=0;
			$headertext=stripslashes($headertext);
			if(isset($urlautoencode))
				$headertext = make_clickable($headertext);
			if(isset($enablespcode))
				$headertext = bbencode($headertext);
			$headertext = do_htmlentities($headertext);
			$headertext = str_replace("\n", "<BR>", $headertext);
			$headertext = addslashes($headertext);
			$sql = "UPDATE ".$tableprefix."_categories SET headertext='$headertext', iconoptions=$iconoptions, icon='$headingicon', newsframelayout='$newsframelayout', catname='$catname', hideincatlist=$hideincatlist, enablepropose=$enablepropose, customfooter='$customfooter', footeroptions=$footeroptions, excludefromnewsletter=$excludefromnewsletter, hideintotallist=$hideintotallist, isarchiv=$isarchiv, ignoreonsearch=$ignoreonsearch, evmarkcolor='$evmarkcolor', enablerating=$enablerating";
			if($rss_enable==1)
				$sql.=", rss_channel_title='$rss_channel_title', rss_channel_description='$rss_channel_description', rss_channel_link='$rss_channel_link', rss_channel_copyright='$rss_channel_copyright', rss_channel_editor='$rss_channel_editor'";
			$sql .=" WHERE (catnr = $input_catnr)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
			if(isset($mods))
			{
				while(list($null, $mod) = each($_POST["mods"]))
				{
					$mod_query = "INSERT INTO ".$tableprefix."_cat_adm (catnr, usernr) VALUES ('$input_catnr', '$mod')";
					if(!mysql_query($mod_query, $db))
						die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			if(isset($rem_mods))
			{
				while(list($null, $mod) = each($_POST["rem_mods"]))
				{
					$rem_query = "DELETE FROM ".$tableprefix."_cat_adm WHERE catnr = '$input_catnr' AND usernr = '$mod'";
					if(!mysql_query($rem_query,$db))
						die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			$availlangs=language_list("../language");
			for($i=0;$i<count($availlangs);$i++)
			{
				$catlvar="catname_".$availlangs[$i];
				if(isset($$catlvar))
				{
					$cname_query = "DELETE FROM ".$tableprefix."_catnames WHERE catnr=$input_catnr AND lang='".$availlangs[$i]."'";
					if(!mysql_query($cname_query,$db))
						die("<tr class=\"errorrow\"><td>Unable to update the database. ".mysql_error());
					$cname_query = "INSERT INTO ".$tableprefix."_catnames (catnr, lang, catname) VALUES ($input_catnr,'".$availlangs[$i]."','".$$catlvar."')";
					if(!mysql_query($cname_query,$db))
						die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_categoryupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_categories</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="move")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if($direction=="up")
		{
			$sql="select displaypos from ".$tableprefix."_categories where catnr=$input_catnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			if(!$myrow=mysql_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]-1;
			$sql="update ".$tableprefix."_categories set displaypos=displaypos+1 where displaypos=$newpos";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			$sql="update ".$tableprefix."_categories set displaypos=$newpos where catnr=$input_catnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
		}
		if($direction=="down")
		{
			$sql="select displaypos from ".$tableprefix."_categories where catnr=$input_catnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			if(!$myrow=mysql_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]+1;
			$sql="update ".$tableprefix."_categories set displaypos=displaypos-1 where displaypos=$newpos";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			$sql="update ".$tableprefix."_categories set displaypos=$newpos where catnr=$input_catnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
		}
	}
	if($mode!="move")
	{
		include('./trailer.php');
		exit;
	}
}
echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
$actionsallowed=false;
if($admin_rights>1)
{
	if($admin_rights>2)
		$actionsallowed=true;
	else if(bittst($secsettings,BIT_7))
		$actionsallowed=true;
}
if($actionsallowed)
{
?>
<tr class="actionrow"><td align="center" colspan="5">
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_addcategory?>
</td></tr>
<?php
}
if(($admin_rights<3) && bittst($secsettings,BIT_22))
	$sql = "select cat.* from ".$tableprefix."_cat_adm ca, ".$tableprefix."_categories cat where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"];
else
	$sql = "select * from ".$tableprefix."_categories order by displaypos asc";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.".mysql_error());
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"5\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
		echo "<tr class=\"rowheadings\">";
		echo "<td align=\"center\" width=\"10%\"><b>#</b></td>";
		echo "<td align=\"center\" width=\"75%\"><b>$l_catname</b> <span class=\"remark\">(<sup>[*]</sup>=$l_isarchiv)</span></td>";
		if($admin_rights>2)
			echo "<td width=\"16\">&nbsp;</td><td width=\"16\">&nbsp;</td>";
		echo "<td>&nbsp;</td></tr>";
		$mycount=0;
		do {
			$mycount++;
			$act_id=$myrow["catnr"];
			echo "<tr class=\"displayrow\">";
			echo "<td align=\"center\">".$myrow["catnr"]."</td>";
			echo "<td align=\"center\">".display_encoded($myrow["catname"]);
			if($myrow["isarchiv"]==1)
				echo " <span class=\"remark\"><sup>[*]</sup></span>";
			echo "</td>";
			if($admin_rights>2)
			{
				if($myrow["displaypos"]==0)
				{
					$tempsql="select max(displaypos) as newdisplaypos from ".$tableprefix."_categories";
					if(!$tempresult = mysql_query($tempsql, $db))
						die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
					if(!$temprow = mysql_fetch_array($tempresult))
						$newpos=1;
					else
						$newpos=$temprow["newdisplaypos"]+1;
					$updatesql="update ".$tableprefix."_categories set displaypos=$newpos where catnr=".$myrow["catnr"];
					if(!$updateresult = mysql_query($updatesql, $db))
						die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
				}
				if($mycount>1)
				{
					echo "<td class=\"inputrow\" align=\"center\">";
					echo "<a href=\"".do_url_session("$act_script_url?mode=move&input_catnr=$act_id&$langvar=$act_lang&direction=up")."\">";
					echo "<img width=\"16\" height=\"16\" src=\"gfx/up.gif\" border=\"0\" title=\"$l_move_up\" alt=\"$l_move_up\"></a>";
					echo "</td>";
				}
				else
					echo "<td class=\"inputrow\" width=\"16\">&nbsp;</td>";
				if($mycount<mysql_num_rows($result))
				{
					echo "<td class=\"inputrow\" align=\"center\">";
					echo "<a href=\"".do_url_session("$act_script_url?mode=move&$langvar=$act_lang&input_catnr=$act_id&direction=down")."\">";
					echo "<img width=\"16\" height=\"16\" src=\"gfx/down.gif\" border=\"0\" title=\"$l_move_down\" alt=\"$l_move_down\"></a>";
					echo "</td>";
				}
				else
					echo "<td class=\"inputrow\" width=\"16\">&nbsp;</td>";
			}
			echo "<td>";
			if($actionsallowed)
			{
				$localallowed=true;
				if($admin_rights==2)
				{
					$tmpsql="select * from ".$tableprefix."_cat_adm where catnr=$act_id and usernr=".$userdata["usernr"];
					if(!$tmpresult = mysql_query($tmpsql, $db))
						die("Could not connect to the database.".mysql_error());
					if(mysql_num_rows($tmpresult)<1)
						$localallowed=false;
				}
				if($localallowed)
				{
					$dellink=do_url_session("$act_script_url?mode=delete&input_catnr=$act_id&$langvar=$act_lang");
					if($admdelconfirm==2)
						echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_category #$act_id','$dellink')\">";
					else
						echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
					echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
					echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_catnr=$act_id")."\">";
					echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a> ";
					echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=copy&$langvar=$act_lang&input_catnr=$act_id")."\">";
					echo "<img src=\"gfx/copy.gif\" border=\"0\" title=\"$l_copy\" alt=\"$l_copy\"></a>";
				}
			}
			echo "</td></tr>";
	   } while($myrow = mysql_fetch_array($result));
   echo "</table></tr></td></table>";
}
if($actionsallowed)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_addcategory?></a></div>
<?php
}
include('./trailer.php');
?>