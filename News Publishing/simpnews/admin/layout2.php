<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$page_title=$l_layout;
$page="layout2";
$ccform="layoutform";
$colorchooser=true;
$bbcbuttons=true;
require_once('./heading.php');
include_once('./includes/color_chooser.inc');
include_once('./includes/gfx_selector.inc');
include_once("./includes/bbcode_buttons.inc");
if(!isset($layoutlang))
	$layoutlang=$act_lang;
if($admin_rights < 2)
{
	die($l_functionotallowed);
}
if(isset($dellayout))
{
	if(($admdelconfirm==1) && !isset($confirmed))
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="dellayout" value="1">
<input type="hidden" name="layoutid" value="<?php echo $layoutid?>">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"inforow\"><td align=\"center\">";
		echo "$l_confirmdel: Layout <i>$layoutid</i>";
		echo "</td></tr>";
		echo "<tr class=\"actionrow\"><td align=\"center\">";
		echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
		echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
		echo "</td></tr>";
		echo "</form></table></td></tr></table>";
		include('./trailer.php');
		exit;
	}
	else
	{
		$sql = "delete from ".$tableprefix."_layout where id='$layoutid'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.".mysql_error());
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_layoutdeleted?></td></tr>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_selectlayout?></a></div>
<?php
		include('./trailer.php');
		exit;
	}
}
if(!isset($layoutid) && !isset($mode))
{
	$sql = "select * from ".$tableprefix."_layout group by id";
	if(!$result = mysql_query($sql, $db)) {
	    die("Could not connect to the database.".mysql_error());
	}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<?php
	if($myrow=mysql_fetch_array($result))
	{
?>
<tr class="inputrow"><td align="center" colspan="2"><?php echo $l_selectlayout?>:
<select name="layoutid">
<?php
		do{
			echo "<option value=\"".$myrow["id"]."\"";
			if($myrow["deflayout"]==1)
				echo " selected class=\"deflayout\"";
			echo ">".$myrow["id"];
			if($myrow["deflayout"]==1)
				echo " [*]";
			echo "</option>";
		}while($myrow=mysql_fetch_array($result));
	}
?>
</select>&nbsp;&nbsp;
<input class="snbutton" type="submit" value="<?php echo $l_ok?>"><br>
<span class="remark"><?php echo $l_defremark?></span>
</td></tr></form>
<tr class="actionrow"><td align="center" colspan="2"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&layoutid=")?>"><?php echo $l_newlayout?></a>
<?php
if($upload_avail)
{
?>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&mode=import")?>"><?php echo $l_importlayout?></a>
<?php
}
?>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&mode=export")?>"><?php echo $l_exportlayout?></a>
</td></tr>
</table>
</td></tr></table>
<?php
	include('./trailer.php');
	exit;
}
if(isset($setdefault))
{
	$sql = "update ".$tableprefix."_layout set deflayout=0 where deflayout=1";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
	$sql = "update ".$tableprefix."_layout set deflayout=1 where id='$layoutid'";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
}
if(isset($mode))
{
	if($mode=="export")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td colspan="2" align="center"><b><?php echo $l_exportlayout?></b></td></tr>
<form action="layout_export.php" method="post">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	$sql = "select * from ".$tableprefix."_layout group by id";
	if(!$result = mysql_query($sql, $db)) {
	    die("Could not connect to the database.");
	}
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_selectlayout?>:</td>
<td>
<select name="layoutid">
<?php
	if($myrow=mysql_fetch_array($result))
	{
		do{
			echo "<option value=\"".$myrow["id"]."\"";
			if($myrow["deflayout"]==1)
				echo " selected";
			echo ">".$myrow["id"];
			echo "</option>";
		}while($myrow=mysql_fetch_array($result));
	}
?>
</select></td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="snbutton" type="submit" value="<?php echo $l_export?>">
</td></tr>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		echo "</form>";
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\">";
		echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_layoutselection</a>";
		echo "</div>";
		include_once('./trailer.php');
		exit;
	}
	if($mode=="import")
	{
		if(!$upload_avail)
			die($l_uploadnotavail);
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td colspan="2" align="center"><b><?php echo $l_importlayout?></b></td></tr>
<form ENCTYPE="multipart/form-data" action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="mode" value="doimport">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		$sql = "select * from ".$tableprefix."_layout group by id";
		if(!$result = mysql_query($sql, $db)) {
			die("Could not connect to the database.");
	}
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_file?>:</td>
<td><input class="snfile" name="layoutfile" type="file"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_layoutid?>:<br>
<span class="remark"><?php echo $l_layoutimportnote?></span></td>
<td><input class="sninput" name="layoutid" type="text" size="10" maxlength="10"></td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="snbutton" type="submit" value="<?php echo $l_import?>">
</td></tr>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		echo "</form>";
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\">";
		echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_layoutselection</a>";
		echo "</div>";
		include_once('./trailer.php');
		exit;
	}
	if($mode=="doimport")
	{
		if(preg_match("/^[-_a-zA-Z0-9]*$/",$layoutid)<1)
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_layoutid_error?></td></tr>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="javascript:history.back()"><?php echo $l_back?></a></div>
<?php
			include('./trailer.php');
			exit;
		}
		include_once('./includes/layout_import.inc');
		exit;
	}
	if($mode=="copylang")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form action="<?php echo $act_script_url?>" method="post">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="headingrow"><td colspan="2" align="center"><b><?php echo $l_copyotherlang?></b></td></tr>
<tr class="inputrow">
<td align="right" width="30%"><?php echo $l_destlang?>:</td>
<td>
<?php
		echo language_select("","destlang","../language/",$oldlang)
?>
</td></tr>
<tr class="optionrow"><td>&nbsp;</td><td>
<input type="checkbox" name="dooverwrite" value="1"> <?php echo $l_overwriteexisting?></td></tr>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="oldlang" value="<?php echo $oldlang?>">
<input type="hidden" name="layoutid" value="<?php echo $layoutid?>">
<input type="hidden" name="mode" value="dolangcopy">
<tr class="actionrow"><td colspan="2" align="center"><input class="snbutton" type="submit" value="<?php echo $l_copy?>"></td></tr>
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "</form>";
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\">";
		echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_layoutselection</a>";
		echo "</div>";
		include_once('./trailer.php');
		exit;
	}
	if($mode=="dolangcopy")
	{
		include_once('./includes/layout_dolangcopy.inc');
		include_once('./trailer.php');
		exit;
	}
	if($mode=="copy")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form action="<?php echo $act_script_url?>" method="post">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="headingrow"><td colspan="2" align="center"><b><?php echo $l_copylayout?></b></td></tr>
<tr class="inputrow">
<td align="right" width="30%"><?php echo $l_newlayoutid?>:</td>
<td><input class="sninput" type="text" name="newlayoutid" size="10" maxlength="10"></td></tr>
<tr class="optionrow"><td>&nbsp;</td><td>
<input type="checkbox" name="dooverwrite" value="1"> <?php echo $l_overwriteexisting?></td></tr>
<input type="hidden" name="oldid" value="<?php echo $oldid?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="layoutlang" value="<?php echo $layoutlang?>">
<input type="hidden" name="mode" value="docopy">
<tr class="actionrow"><td colspan="2" align="center"><input class="snbutton" type="submit" value="<?php echo $l_copy?>"></td></tr>
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "</form>";
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\">";
		echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_layoutselection</a>";
		echo "</div>";
		include_once('./trailer.php');
		exit;
	}
	if($mode=="docopy")
	{
		if(preg_match("/^[-_a-zA-Z0-9]*$/",$newlayoutid)<1)
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_layoutid_error?></td></tr>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="javascript:history.back()"><?php echo $l_back?></a></div>
<?php
			include('./trailer.php');
			exit;
		}
		include_once('./includes/layout_docopy.inc');
		include_once('./trailer.php');
		exit;
	}
	if(!$layoutid)
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center" colspan="2"><?php echo $l_nolayoutid?></td></tr>
<?php
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
		include('./trailer.php');
		exit;
	}
	if(preg_match("/^[-_a-zA-Z0-9]*$/",$layoutid)<1)
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_layoutid_error?></td></tr>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="javascript:history.back()"><?php echo $l_back?></a></div>
<?php
		include('./trailer.php');
		exit;
	}
	include('./includes/layout_action.inc');
	if(!isset($layoutnr))
	{
		include('./includes/layout_new.inc');
	}
	else
	{
		include('./includes/layout_update.inc');
	}
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
	$infotext=$l_layoutupdated;
}
$sql = "select * from ".$tableprefix."_layout where lang='$layoutlang' and id='$layoutid'";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
{
	include('./includes/layout_defs.inc');
}
else
{
	include('./includes/layout_get.inc');
}
$actualyear=date("Y");
list($news5startyear, $news5startmonth, $news5startday) = explode("-", $news5startdate);
if(($news5startyear==0) || ($news5startmonth==0) || ($news5startday==0))
	list($news5startyear, $news5startmonth, $news5startday) = explode("-",date("Y-m-d"));
list($news5endyear, $news5endmonth, $news5endday) = explode("-", $news5enddate);
if(($news5endyear==0) || ($news5endmonth==0) || ($news5endday==0))
	list($news5endyear, $news5endmonth, $news5endday) = explode("-",date("Y-m-d"));
if($customfooter)
	$customfooter=str_replace("<BR>","\n",$customfooter);
if($customheader)
	$customheader=str_replace("<BR>","\n",$customheader);
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(isset($infotext))
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\"><b>$infotext</b></td></tr>";
?>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="center" colspan="2"><?php echo $l_layoutlang?>: <?php echo language_select($layoutlang,"layoutlang","../language/")?>
<input class="snbutton" type="submit" value="<?php echo $l_change?>">
<input type="hidden" name="layoutid" value="<?php echo $layoutid?>">
</td></tr></form>
<tr class="actionrow"><td align="center" colspan="2"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_changelayout?></a>
<?php
if((strlen($layoutid)>0) && ($deflayout==0))
{
	echo "&nbsp;&nbsp;";
	$dellink=do_url_session("$act_script_url?$langvar=$act_lang&dellayout=1&layoutid=$layoutid");
	if($admdelconfirm==2)
		echo "<a class=\"listlink2\" href=\"javascript:confirmDel('Layout $layoutid','$dellink')\">";
	else
		echo "<a class=\"listlink2\" href=\"$dellink\">";
	echo "$l_deletelayout</a>";
}
if(strlen($layoutid)>0)
{
	echo "&nbsp;&nbsp;<a href=\"".do_url_session("$act_script_url?mode=copy&oldid=$layoutid&$langvar=$act_lang&layoutlang=$layoutlang")."\">$l_copylayout</a>";
	echo "&nbsp;&nbsp;<a href=\"".do_url_session("$act_script_url?mode=copylang&$langvar=$act_lang&oldlang=$layoutlang&layoutid=$layoutid")."\">$l_copyotherlang</a>";
}
?>
</td></tr>
<tr class="inforow"><td align="center" colspan="2"><b>
<?php
	echo "$l_actuallyselected: ";
	if($layoutid)
		echo $layoutid;
	else
		echo $l_new_layout;
	echo ", $layoutlang";
echo "</b></td></tr>";
if(strlen($layoutid)>0)
{
	echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\">$l_layout_id:</td>";
	echo "<td>".$layoutid;
	echo "</td></tr>";
	if($deflayout==1)
	{
		echo "<tr class=\"displayrow\"><td>&nbsp;</td><td>";
		echo $l_default;
	}
	else
	{
		echo "<tr class=\"actionrow\"><td>&nbsp;</td><td>";
		echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&layoutlang=$layoutlang&layoutid=$layoutid&setdefault=1")."\">$l_setdefault</a>";
	}
}
echo "</table></td></tr></table>";
?>
<form name="layoutform" onsubmit="return checkform();" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="colorfield" value="">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	$rndid=time();
	echo "<input type=\"hidden\" name=\"postid\" value=\"$rndid\">";
	if(strlen($layoutid)>0)
		echo "<input type=\"hidden\" name=\"layoutid\" value=\"$layoutid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="submit">
<input type="hidden" name="layoutlang" value="<?php echo $layoutlang?>">
<?php
if(isset($layoutnr))
	echo "<input type=\"hidden\" name=\"layoutnr\" value=\"$layoutnr\">";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(strlen($layoutid)<1)
{
	echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\">$l_layout_id:</td>";
	echo "<td class=\"inputrow\"><input class=\"sninput\" type=\"text\" name=\"layoutid\" size=\"10\" maxlength=\"10\">";
	echo "</td></tr>";
}
?>
<tr><td class="displayrow" valign="bottom" colspan="2">
<div class="tabs">
	<a href="#submitline"><img class="pagebottom" src="gfx/pagebottom.gif" height="16" width="16" border="0" align="right" alt="<?php echo $l_pagebottom?>" title="<?php echo $l_pagebottom?>"></a>
	<span id="tab1" class="tab tabActive"><?php echo $l_heading?></span>
	<span id="tab2" class="tab"><?php echo $l_categories?></span>
	<span id="tab3" class="tab"><?php echo $l_news?></span>
	<span id="tab4" class="tab"><?php echo $l_linkcolors?></span>
	<span id="tab5" class="tab"><?php echo $l_grafiks?></span>
	<span id="tab6" class="tab"><?php echo $l_announce?></span>
	<span id="tab7" class="tab"><?php echo "$l_attachements/$l_searching"?></span><br>
	<span id="tab8" class="tab"><?php echo $l_proped?></span>
	<span id="tab9" class="tab"><?php echo "$l_eventlists/$l_eventcal"?></span>
	<span id="tab10" class="tab"><?php echo $l_propose_entries?></span>
	<span id="tab11" class="tab"><?php echo $l_pagelayout?></span><br>
	<span id="tab12" class="tab"><?php echo $l_userstyles?></span>
	<span id="tab13" class="tab"><?php echo "$l_emails/$l_subscriptions"?></span>
	<span id="tab14" class="tab"><?php echo $l_applets?></span>
	<span id="tab15" class="tab"><?php echo $l_hotscripts2?></span>
	<span id="tab16" class="tab"><?php echo $l_js_scripts?></span>
	<span id="tab17" class="tab"><?php echo $l_newsletter?></span><br>
	<span id="tab18" class="tab"><?php echo $l_newsmail?></span>
	<span id="tab19" class="tab"><?php echo $l_includescripts?></span>
	<span id="tab20" class="tab"><?php echo $l_rss_newsfeed?></span>
	<span id="tab21" class="tab"><?php echo $l_wap_newsfeed?></span>
</div></td></tr></table></td></tr></table>
<div id="content1" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page0.inc");
?>
</table></td></tr></table></div>
<div id="content2" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page1.inc");
?>
</table></td></tr></table></div>
<div id="content3" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page2.inc");
?>
</table></td></tr></table></div>
<div id="content4" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page3.inc");
?>
</table></td></tr></table></div>
<div id="content5" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page4.inc");
?>
</table></td></tr></table></div>
<div id="content6" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page5.inc");
?>
</table></td></tr></table></div>
<div id="content7" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page6.inc");
?>
</table></td></tr></table></div>
<div id="content8" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page7.inc");
?>
</table></td></tr></table></div>
<div id="content9" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page8.inc");
?>
</table></td></tr></table></div>
<div id="content10" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page9.inc");
?>
</table></td></tr></table></div>
<div id="content11" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page10.inc");
?>
</table></td></tr></table></div>
<div id="content12" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page11.inc");
?>
</table></td></tr></table></div>
<div id="content13" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page12.inc");
?>
</table></td></tr></table></div>
<div id="content14" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page13.inc");
?>
</table></td></tr></table></div>
<div id="content15" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page14.inc");
?>
</table></td></tr></table></div>
<div id="content16" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page15.inc");
?>
</table></td></tr></table></div>
<div id="content17" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page16.inc");
?>
</table></td></tr></table></div>
<div id="content18" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page17.inc");
?>
</table></td></tr></table></div>
<div id="content19" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page18.inc");
?>
</table></td></tr></table></div>
<div id="content20" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page19.inc");
?>
</table></td></tr></table></div>
<div id="content21" class="content">
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page20.inc");
?>
</table></td></tr></table></div>
<div id="submitline"><a id="submitline"></a>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="actionrow"><td align="center"><input class="snbutton" type="submit" value="<?php if(strlen($layoutid)>0) echo $l_update; else echo $l_add?>"></td></tr>
</table></td></tr></table></div>
</form></table></td></tr></table>
<?php
include_once('./trailer.php');
echo "</body></html>";
?>
