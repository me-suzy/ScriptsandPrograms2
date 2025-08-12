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
$page_title=$l_settings_title;
$page="settings";
require_once('./heading.php');
if($admin_rights < 3)
{
	die($l_functionotallowed);
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="settingsform" method="post" action="<?php echo $act_script_url?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
if(isset($mode))
{
	include_once("./includes/settings_action.inc");
	if($errors==0)
	{
		if($settingsnew==1)
			include_once("./includes/settings_new.inc");
		else
			include_once("./includes/settings_update.inc");
		include_once("./includes/settings_adddata.inc");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_settingsupdated";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_editsettings</a></div>";
		include('./trailer.php');
		exit;
	}
	else
	{
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
		include('./trailer.php');
		exit;
	}
}
$sql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$result = faqe_db_query($sql, $db))
	db_die("Unable to connect to database.");
if(!$myrow=faqe_db_fetch_array($result))
{
	$settingsnew=1;
	include_once("./includes/settings_def.inc");
}
else
{
	$settingsnew=0;
	include_once("./includes/settings_get.inc");
}
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="submit">
<input type="hidden" name="settingsnew" value="<?php echo $settingsnew?>">
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_global?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="nohtmlemail" value="1" type="checkbox"
<?php if($disablehtmlemail==1) echo " checked"?>> <?php echo $l_disablehtmlemail?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_msendlimit?>:</td><td>
<input type="text" name="new_msendlimit" value="<?php echo $msendlimit?>" size="4" maxlength="10" class="faqeinput" > <?php echo $l_seconds?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_defmailsig?>:</td><td>
<textarea class="faqeinput" name="input_defmailsig" cols="40" rows="6"><?php echo $defmailsig?></textarea></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_servertimezone?>:</td>
<td>
<?php echo tz_select($timezone,"timezone")?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_faqemailadr?>:</td>
<td><input class="faqeinput" type="text" name="input_faqemail" value="<?php echo $faqemail?>" size="30" maxlength="140"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_faqhostname?>:</td>
<td><input class="faqeinput" type="text" name="faqengine_hostname" value="<?php echo $faqengine_hostname?>" size="30" maxlength="140"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="dontnotifysu" value="1" <?php if($nosunotify==1) echo "checked"?>>
<?php echo $l_nosunotify?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="haszlib" value="1" <?php if($zlibavail==1) echo "checked"?>> <?php echo $l_zlibavail?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="noleacher" value="1" <?php if($blockleacher==1) echo "checked"?>> <?php echo $l_blockleacher?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="enabletimezone" value="1" <?php if($showtimezone==1) echo "checked"?>> <?php echo $l_showtimezone?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="enablecurrenttime" value="1" <?php if($showcurrtime==1) echo "checked"?>> <?php echo $l_showcurrtime?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_logging?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td><td>
<input type="text" name="logdateformat" class="faqeinput" value="<?php echo $logdateformat?>" size="20" maxlength="20"></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_userview?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="blockold" value="1" <?php if($blockoldbrowser==1) echo "checked"?>>
<?php echo $l_blockoldbrowser?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="linkhide" value="1" <?php if($lhide==1) echo "checked"?>>
<?php echo $l_linkhide?></td></tr>
<tr class="inputrow"><td width="30%">&nbsp;</td><td align="left"><input name="enableemail" value="1" type="checkbox"
<?php if($allowemail==1) echo " checked"?>> <?php echo $l_allowemail?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left">
<input name="proglist" value="1" type="checkbox"
<?php if($showproglist==1) echo " checked"?>> <?php echo $l_showproglist?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="dorating" value="1" type="checkbox"
<?php if($displayrating==1) echo " checked"?>> <?php echo $l_displayrating?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="ratingcom" value="1" <?php if ($ratingcomment==1) echo "checked"?>>
<?php echo $l_ratingcomment?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="logsearches" value="1" <?php if($dosearchlog==1) echo "checked"?>>
<?php echo $l_logsearches?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_kb?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="dokbrating" value="1" type="checkbox"
<?php if($enablekbrating==1) echo " checked"?>> <?php echo $l_kbrating?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_usercomments?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enableusercomments" value="1" type="checkbox"
<?php if($allowusercomments==1) echo " checked"?>> <?php echo $l_allowusercomments?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="docommentrating" value="1" type="checkbox"
<?php if($ratecomments==1) echo " checked"?>> <?php echo $l_commentrating?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="commentnotify" value="1" type="checkbox"
<?php if($newcommentnotify==1) echo " checked"?>> <?php echo $l_newcommentnotify?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="dobwfilter" value="1" type="checkbox"
<?php if($usebwlist==1) echo " checked"?>> <?php echo $l_enablebadwordlist?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_lists?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="all_compress" value="1" <?php if($allsendcompressed==1) echo "checked"?>> <?php echo $l_compresslists?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_subscriptions?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="enablesubscription" value="1" <?php if($subscriptionavail==1) echo "checked"?>>
<?php echo $l_subscriptionavail?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxconfirmtime?>:</td><td>
<input type="text" class="faqeinput" name="new_maxconfirmtime" value="<?php echo $maxconfirmtime?>" size="2" maxlength="2"> <?php echo $l_days?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_userquestions?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablequestions" value="1" type="checkbox"
<?php if($allowquestions==1) echo " checked"?>> <?php echo $l_allowquestions?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="uq_noemail" value="1" type="checkbox" <?php if($uq_allownoemail==1) echo "checked"?>>
<?php echo $l_noemailrequired?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="sendanswermail" value="1" type="checkbox"
<?php if($userquestionanswermail==1) echo " checked"?>> <?php echo $l_userquestionanswermail?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="autopublishquestion" value="1" type="checkbox"
<?php if($userquestionautopublish==1) echo " checked"?>> <?php echo $l_autopublishquestion?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="qscmail" value="1" type="checkbox"
<?php if($uqscmail==1) echo " checked"?>> <?php echo $l_uqscmail?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_userquestionanswermode?>:</td><td align="left">
<?php
	for($i=0;$i<count($l_userquestionanswermodes);$i++)
	{
		echo "<input type=\"radio\" name=\"uqanswermode\" value=\"$i\"";
		if($i==$userquestionanswermode)
			echo " checked";
		echo "> ".$l_userquestionanswermodes[$i]."<br>";
	}
?>
</td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_admininterface?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td>
<td><input class="faqeinput" type="text" name="new_admdateformat" value="<?php echo $admdateformat?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_loginlimit?>:</td>
<td><input class="faqeinput" type="text" name="new_loginlimit" value="<?php echo $loginlimit?>" size="5" maxlength="5"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="dologincount" value="1" type="checkbox"
<?php if($watchlogins==1) echo " checked"?>> <?php echo $l_watchlogins?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="doextfailedlog" value="1" type="checkbox"
<?php if($extfailedlog==1) echo " checked"?>> <?php echo $l_extfailedlog?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablefaqshortcuts" value="1" type="checkbox"
<?php if($faqlistshortcuts==1) echo " checked"?>> <?php echo $l_faqlistshortcuts?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enableautourl" value="1" type="checkbox"
<?php if($urlautoencode==1) echo " checked"?>> <?php echo $l_urlautoencode?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="allowspcode" value="1" type="checkbox"
<?php if($enablespcode==1) echo " checked"?>> <?php echo $l_enablespcode?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablefreemailer" value="1" type="checkbox"
<?php if($nofreemailer==0) echo " checked"?>> <?php echo $l_allowfreemailer?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablemenubar" value="1" type="checkbox"
<?php if($usemenubar==1) echo " checked"?>> <?php echo $l_usemenubar?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="storefaqfilters" value="1" type="checkbox"
<?php if($admstorefaqfilters==1) echo " checked"?>> <?php echo $l_admstorefaqfilters?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="allowhostresolve" value="1" type="checkbox"
<?php if($enablehostresolve==1) echo " checked"?>> <?php echo $l_enablehostresolve?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="limitrelated" value="1" <?php if($faqlimitrelated==1) echo "checked"?>>
<?php echo $l_faqlimitrelated?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="usecolorbar" value="1" <?php if($bbccolorbar==1) echo "checked"?>>
<?php echo $l_bbccolorbar?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="hideunassigned" value="1" <?php if($admhideunassigned==1) echo "checked"?>>
<?php echo $l_admhideunassigned?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_confirmdelentries?>:</td><td align="left">
<input type="radio" name="new_admdelconfirm" value="0" <?php if($admdelconfirm==0) echo "checked"?>> <?php echo $l_none?><br>
<input type="radio" name="new_admdelconfirm" value="1" <?php if($admdelconfirm==1) echo "checked"?>> <?php echo $l_onnextpage?><br>
<input type="radio" name="new_admdelconfirm" value="2" <?php if($admdelconfirm==2) echo "checked"?>> <?php echo $l_usingjavascript?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_failednotify?>:<br><input name="dofailednotify" value="1" type="checkbox"
<?php if($enablefailednotify==1) echo " checked"?>><?php echo $l_enable?></td>
<td align="left" valign="top">
<?php
	$sql = "SELECT * FROM ".$tableprefix."_failed_notify fn, ".$tableprefix."_admins u where u.usernr=fn.usernr order by u.username";
	if(!$r = faqe_db_query($sql, $db))
	    die("Could not connect to the database.");
	if ($row = faqe_db_fetch_array($r))
	{
		 do {
		    echo $row["username"]." (<input type=\"checkbox\" name=\"rem_mods[]\" value=\"".$row["usernr"]."\"> $l_remove)<BR>";
		    $current_mods[] = $row["usernr"];
		 } while($row = faqe_db_fetch_array($r));
		 echo "<br>";
	}
	else
		echo "$l_noadmins<br><br>";
	$sql = "SELECT usernr, username FROM ".$tableprefix."_admins WHERE rights > 2 ";
	if(isset($current_mods))
	{
    	while(list($null, $currMod) = each($current_mods)) {
			$sql .= "AND usernr != $currMod ";
    	}
    }
    $sql .= "ORDER BY username";
    if(!$r = faqe_db_query($sql, $db))
		die("Could not connect to the database.");
    if($row = faqe_db_fetch_array($r)) {
		echo"<span class=\"inlineheading1\">$l_add:</span><br>";
		echo"<SELECT NAME=\"mods[]\" size=\"5\" multiple>";
		do {
			echo "<OPTION VALUE=\"$row[usernr]\" >$row[username]</OPTION>\n";
		} while($row = faqe_db_fetch_array($r));
		echo"</select>";
	}
?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_admedoptions?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="edfilterrestrict" value="1" <?php if(bittst($admedoptions,BIT_1)) echo "checked"?>> <?php echo $l_filterrestrict?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_sortorder?>:</td><td>
<input type="radio" name="edsortorder" value="1" <?php if(bittst($admedoptions,BIT_2)) echo "checked"?>> <?php echo $l_sortalpha?><br>
<input type="radio" name="edsortorder" value="0" <?php if(!bittst($admedoptions,BIT_2)) echo "checked"?>> <?php echo $l_sortdisplaypos?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>Textareas</b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td><td>
<input type="text" size="10" maxlength="10" class="faqeinput" name="new_admtextareascols" value="<?php echo $admtextareascols?>"> <?php echo "$l_cols/$l_characters"?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td><td>
<input type="text" size="10" maxlength="10" class="faqeinput" name="new_admtextareasrows" value="<?php echo $admtextareasrows?>"> <?php echo $l_rows?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="faqebutton" type="submit" value="<?php echo $l_submit?>" name="submit"></td></tr>
</form>
</table></td></tr></table>
<?php
include('./trailer.php')
?>
