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
$page_title=$l_layout_title;
$page="layout";
require_once('./heading.php');
include_once('./includes/color_chooser.inc');
include_once('./includes/gfx_selector.inc');
$checked_pic="gfx/checked.gif";
$unchecked_pic="gfx/unchecked.gif";
$pagebgrepeats=array("","repeat-x","repeat-y","no-repeat","repeat");
$pagebgpositions=array("","top","center","bottom","left","right");
$pagebgattachs=array("","scroll","fixed");
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($dellayout))
{
	if(($admdelconfirm==1) && !isset($confirmed))
	{
?>
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
		if(!$result = faqe_db_query($sql, $db))
		    die("Could not connect to the database.".faqe_db_error());
?>
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
	if(!$result = faqe_db_query($sql, $db))
	    die("Could not connect to the database.".faqe_db_error());
?>
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
	if($myrow=faqe_db_fetch_array($result))
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
		}while($myrow=faqe_db_fetch_array($result));
	}
?>
</select>&nbsp;&nbsp;
<input class="faqebutton" type="submit" value="<?php echo $l_ok?>"><br>
<span class="remark"><?php echo $l_defremark?></span>
</td></tr></form>
<tr class="actionrow"><td align="center" colspan="2"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&layoutid=")?>"><?php echo $l_newlayout?></a></td></tr>
</table>
</td></tr></table>
<?php
	include('./trailer.php');
	exit;
}
if(isset($setdefault))
{
	$sql = "update ".$tableprefix."_layout set deflayout=0 where deflayout=1";
	if(!$result = faqe_db_query($sql, $db))
	    die("Unable to connect to database.".faqe_db_error());
	$sql = "update ".$tableprefix."_layout set deflayout=1 where id='$layoutid'";
	if(!$result = faqe_db_query($sql, $db))
	    die("Unable to connect to database.".faqe_db_error());
}
if(isset($mode))
{
	if($mode=="copy")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form action="<?php echo $act_script_url?>" method="post">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="headingrow"><td colspan="2" align="center"><b><?php echo $l_copylayout?></b></td></tr>
<tr class="inputrow">
<td align="right" width="30%"><?php echo $l_newlayoutid?>:</td>
<td><input class="faqeinput" type="text" name="newlayoutid" size="10" maxlength="10"></td></tr>
<tr class="optionrow"><td>&nbsp;</td><td>
<input type="checkbox" name="dooverwrite" value="1"> <?php echo $l_overwriteexisting?></td></tr>
<input type="hidden" name="oldid" value="<?php echo $oldid?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="layoutlang" value="<?php echo $layoutlang?>">
<input type="hidden" name="mode" value="docopy">
<tr class="actionrow"><td colspan="2" align="center"><input class="faqebutton" type="submit" value="<?php echo $l_copy?>"></td></tr>
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
		include_once('./includes/layout_docopy.inc');
		include_once('./trailer.php');
		exit;
	}
	if(!$layoutid)
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center" colspan="2"><?php echo $l_nolayoutid?></td></tr>
<?php
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
		include('./trailer.php');
		exit;
	}
	include('./includes/layout_action.inc');
	include('./trailer.php');
	exit;
}
$sql="select * from ".$tableprefix."_layout where id='$layoutid'";
if(!$result = faqe_db_query($sql, $db))
    die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
{
	include('./includes/layout_def.inc');
}
else
{
	include('./includes/layout_get.inc');
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
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
	echo "&nbsp;&nbsp;<a href=\"".do_url_session("$act_script_url?mode=copy&oldid=$layoutid&$langvar=$act_lang")."\">$l_copylayout</a>";
?>
</td></tr>
<form name="myform" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
?>
<input type="hidden" name="colorfield" value="">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="new" value="<?php echo $new?>">
<input type="hidden" name="mode" value="update">
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_id?>:</td>
<?php
if(isset($layoutnr))
	echo "<input type=\"hidden\" name=\"layoutnr\" value=\"$layoutnr\">";
if(strlen($layoutid)<1)
{
	echo "<td class=\"inputrow\"><input class=\"faqeinput\" type=\"text\" name=\"layoutid\" size=\"10\" maxlength=\"10\">";
}
else
{
	echo "<td>".$layoutid;
	echo "<input type=\"hidden\" name=\"layoutid\" value=\"$layoutid\">";
	echo "</td></tr>";
	if($deflayout==1)
	{
		echo "<tr class=\"displayrow\"><td>&nbsp;</td><td>";
		echo $l_default;
	}
	else
	{
		echo "<tr class=\"actionrow\"><td>&nbsp;</td><td>";
		echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&layoutid=$layoutid&setdefault=1")."\">$l_setdefault</a>";
	}
}
echo "</td></tr>";
?>
<tr class="listheading0">
<td align="left" colspan="2"><b><?php echo $l_layout_settings?></b></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_global?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_tablewidth?>:</td>
<td><input class="faqeinput" type="text" name="tablewidth" value="<?php echo $tablewidth?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_tablealign?>:</td>
<td><select name="tablealign">
<?php
for($i=0;$i<count($l_table_aligns);$i++)
{
	echo "<option value=\"$i\"";
	if($tablealign==$i)
		echo " selected";
	echo ">".$l_table_aligns[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_tablespacing?>:</td>
<td align="left"><input type="text" class="faqeinput" name="tablespacing" size="4" maxlength="4" value="<?php echo $tablespacing?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_tablepadding?>:</td>
<td align="left"><input type="text" class="faqeinput" name="tablepadding" size="4" maxlength="4" value="<?php echo $tablepadding?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_pagebgcolor?>:</td>
<?php echo color_chooser("pagebg",$pagebg)?>
<tr class="inputrow"><td align="right"><?php echo $l_bgpic?>:</td>
<?php echo gfx_selector("pagebgpic",$pagebgpic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_pagebgrepeat?>:</td>
<td><select name="pagebgrepeat">
<?php
for($i=0;$i<count($pagebgrepeats);$i++)
{
	echo "<option value=\"".$pagebgrepeats[$i]."\"";
	if($pagebgrepeats[$i]==$pagebgrepeat)
		echo " selected";
	echo ">".$pagebgrepeats[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_pagebgposition?>:</td>
<td><select name="pagebgposition">
<?php
$layoutpredef=false;
for($i=0;$i<count($pagebgpositions);$i++)
{
	echo "<option value=\"".$pagebgpositions[$i]."\"";
	if($pagebgpositions[$i]==$pagebgposition)
	{
		echo " selected";
		$layoutpredef=true;
	}
	echo ">".$pagebgpositions[$i]."</option>";
}
?>
</select>&nbsp;&nbsp;<input type="text" class="faqeinput" name="pagebgpostxt" value="<?php if(!$layoutpredef) echo $pagebgposition?>" size="10" maxlength="80">
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_pagebgattach?>:</td>
<td><select name="pagebgattach">
<?php
for($i=0;$i<count($pagebgattachs);$i++)
{
	echo "<option value=\"".$pagebgattachs[$i]."\"";
	if($pagebgattachs[$i]==$pagebgattach)
		echo " selected";
	echo ">".$pagebgattachs[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_textcolor?>:</td>
<?php echo color_chooser("fontcolor",$fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontface?>:</td>
<td><input class="faqeinput" type="text" name="fontface" size="50" value="<?php echo $fontface?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize1?>:</td>
<td><input class="faqeinput" type="text" name="fontsize1" value="<?php echo $fontsize1?>" size="6" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize4?>:</td>
<td><input class="faqeinput" type="text" name="fontsize4" value="<?php echo $fontsize4?>" size="6" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linkcolor?>:</td>
<?php echo color_chooser("linkcolor",$linkcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_vlinkcolor?>:</td>
<?php echo color_chooser("vlinkcolor",$vlinkcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_alinkcolor?>:</td>
<?php echo color_chooser("alinkcolor",$alinkcolor)?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customheader?>:<br>
<input name="enablecustomheader" value="1" type="checkbox"
<?php if($usecustomheader==1) echo " checked"?>> <?php echo $l_enable?><br>
<input type="checkbox" value="1" name="doheaderprint" <?php if ($printheader==1) echo "checked"?>> <?php echo $l_useatprint?>
</td>
<td>
<textarea class="faqeinput" name="pageheader" cols="40" rows="8"><?php echo do_htmlentities($pageheader)?></textarea>
<hr noshade color="#000000" size="1">
<input type="checkbox" value="1" name="clearcustomheader"><?php echo $l_clear?><br>
<input type="checkbox" name="chbrtrans" value="1" <?php if(bittst($donltrans,BIT_1)) echo "checked"?>><?php echo $l_dobrtranslation?><br>
<?php
if($upload_avail)
{
	echo "<hr noshade color=\"#000000\" size=\"1\">";
	echo $l_uploadfromfile.": ";
	echo "<input class=\"faqefile\" type=\"file\" name=\"customheaderfile\"><br>";
}
?>
<hr noshade color="#000000" size="1">
<?php echo $l_includefile?>: <input class="faqeinput" type="text" size="30" maxlength="240" name="headerfile" value="<?php echo $headerfile?>">
&nbsp;&nbsp;<a class="remark" href="javascript:openWindow3('infshow.php?<?php echo "$langvar=$act_lang"?>&note=l_incfile_remark','info',20,20,300,300);" onmouseover="popup(0)" onmouseout="popout()"><?php echo $l_note?></a><br>
<input type="radio" name="headerfilepos" value="0" <?php if ($headerfilepos==0) echo "checked"?>> <?php echo $l_beforecustomheader?><br>
<input type="radio" name="headerfilepos" value="1" <?php if ($headerfilepos==1) echo "checked"?>> <?php echo $l_aftercustomheader?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customfooter?>:<br>
<input name="enablecustomfooter" value="1" type="checkbox"
<?php if($usecustomfooter==1) echo " checked"?>> <?php echo $l_enable?><br>
<input type="checkbox" value="1" name="dofooterprint" <?php if ($printfooter==1) echo "checked"?>> <?php echo $l_useatprint?>
</td>
<td><textarea class="faqeinput" name="pagefooter" cols="40" rows="8"><?php echo do_htmlentities($pagefooter)?></textarea>
<hr noshade color="#000000" size="1">
<input type="checkbox" value="1" name="clearcustomfooter"><?php echo $l_clear?><br>
<input type="checkbox" name="cfbrtrans" value="1" <?php if(bittst($donltrans,BIT_2)) echo "checked"?>><?php echo $l_dobrtranslation?><br>
<?php
if($upload_avail)
{
	echo "<hr noshade color=\"#000000\" size=\"1\">";
	echo $l_uploadfromfile.": ";
	echo "<input class=\"faqefile\" type=\"file\" name=\"customfooterfile\"><br>";
}
?>
<hr noshade color="#000000" size="1">
<?php echo $l_includefile?>: <input class="faqeinput" type="text" size="30" maxlength="240" name="footerfile" value="<?php echo $footerfile?>">
&nbsp;&nbsp;<a class="remark" href="javascript:openWindow3('infshow.php?<?php echo "$langvar=$act_lang"?>&note=l_incfile_remark','info',20,20,300,300);" onmouseover="popup(0)" onmouseout="popout()"><?php echo $l_note?></a><br>
<input type="radio" name="footerfilepos" value="0" <?php if ($footerfilepos==0) echo "checked"?>> <?php echo $l_beforecustomfooter?><br>
<input type="radio" name="footerfilepos" value="1" <?php if ($footerfilepos==1) echo "checked"?>> <?php echo $l_aftercustomfooter?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_addbodytags?>:</td>
<td align="left"><input class="faqeinput" type="text" name="addbodytags" value="<?php echo do_htmlentities($addbodytags)?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_contentcopy?>:</td>
<td align="left"><input class="faqeinput" type="text" name="contentcopy" value="<?php echo display_encoded($contentcopy)?>" size="40" maxlength="240"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_treeviewframe?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_framewidth?>:</td>
<td><input class="faqeinput" name="navbarwidth" value="<?php echo $navbarwidth?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="synctree" value="1" <?php if($navsync==1) echo "checked"?>>
<?php echo $l_navsync?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_navtreepos?>:</td><td>
<input type="radio" name="navtreepos" value="0" <?php if($navtreepos==0) echo "checked"?>> <?php echo $l_left?><br>
<input type="radio" name="navtreepos" value="1" <?php if($navtreepos==1) echo "checked"?>> <?php echo $l_right?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_stylesheets?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_global?>:</td>
<td><input class="faqeinput" type="text" name="stylesheet" value="<?php echo $stylesheet?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_ns4?>:</td>
<td><input class="faqeinput" type="text" name="ns4style" value="<?php echo $ns4style?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_ns6?>:</td>
<td><input class="faqeinput" type="text" name="ns6style" value="<?php echo $ns6style?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_opera?>:</td>
<td><input class="faqeinput" type="text" name="operastyle" value="<?php echo $operastyle?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_gecko?>:</td>
<td><input class="faqeinput" type="text" name="geckostyle" value="<?php echo $geckostyle?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_konqueror?>:</td>
<td><input class="faqeinput" type="text" name="konquerorstyle" value="<?php echo $konquerorstyle?>"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_headings?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("headingbg",$headingbg)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("headingfontcolor",$headingfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="fontsize3" value="<?php echo $fontsize3?>" size="6" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_subheading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("subheadingbgcolor",$subheadingbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("subheadingfontcolor",$subheadingfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="fontsize2" value="<?php echo $fontsize2?>" size="6" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_grouping?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("bgcolor3",$bgcolor3)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("groupfontcolor",$groupfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="fontsize5" value="<?php echo $fontsize5?>" size="6" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_subcategories?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("subcatbgcolor",$subcatbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("subcatfontcolor",$subcatfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="subcatfontsize" value="<?php echo $subcatfontsize?>" size="6" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontstyle?>:</td>
<td><select name="subcatfontstyle">
<?php
for($i=0;$i<count($l_fontstyles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$subcatfontstyle)
		echo " selected";
	echo ">".$l_fontstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_tableheading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("tabledescfontcolor",$tabledescfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="tabledescfontsize" value="<?php echo $tabledescfontsize?>" size="6" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_table?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bordercolor?>:</td>
<?php echo color_chooser("bgcolor1",$bgcolor1)?>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("bgcolor2",$bgcolor2)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_actionline?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("actionbgcolor",$actionbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="actionlinefontsize" value="<?php echo $actionlinefontsize?>" size="6" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_newinfoline?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("newinfobgcolor",$newinfobgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("newinfofontcolor",$newinfofontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="newinfofontsize" value="<?php echo $newinfofontsize?>" size="6" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_langselect?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="langselectfontsize" value="<?php echo $langselectfontsize?>" size="6" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_jumpbox?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="jumpboxfontsize" value="<?php echo $jumpboxfontsize?>" size="6" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_displayorder?>:</td>
<td><select name="jumpboxsorting">
<?php
for($i=0;$i<count($l_jumpboxsortings);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$jumpboxsorting)
		echo " selected";
	echo ">".$l_jumpboxsortings[$i]."</option>";
}
?>
</select></td>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_newfaqbox?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="faqnewfontsize" value="<?php echo $faqnewfontsize?>" size="6" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_contentcopy?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontface?>:</td>
<td><input class="faqeinput" type="text" name="cc_font" value="<?php echo do_htmlentities(stripslashes($cc_font))?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="cc_fontsize" value="<?php echo $cc_fontsize?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_textcolor?>:</td>
<?php echo color_chooser("cc_fontcolor",$cc_fontcolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_shortbar?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="shortbarfontsize" value="<?php echo $shortbarfontsize?>" size="20" maxlength="20"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_inforow?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="faqeinput" type="text" name="irow_fontsize" value="<?php echo $irow_fontsize?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_textcolor?>:</td>
<?php echo color_chooser("irow_fontcolor",$irow_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("irow_bgcolor",$irow_bgcolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_addlinks?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="addlinks" value="1" <?php if(bittst($linkoptions,BIT_1)) echo "checked"?>>
<?php echo $l_enableaddlinks?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_prognamelink?>:</td><td>
<input type="radio" name="prognamelink" value="0" <?php if(!bittst($linkoptions,BIT_2)) echo "checked"?>> <?php echo $l_catlist?><br>
<input type="radio" name="prognamelink" value="1" <?php if(bittst($linkoptions,BIT_2)) echo "checked"?>> <?php echo $l_listallfaq?><br>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_graphics?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_subscriptionpic?>:</td>
<?php echo gfx_selector("subscriptionpic",$subscriptionpic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_searchpic?>:</td>
<?php echo gfx_selector("searchpic",$searchpic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_printpic?>:</td>
<?php echo gfx_selector("printpic",$printpic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_backpic?>:</td>
<?php echo gfx_selector("backpic",$backpic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_listpic?>:</td>
<?php echo gfx_selector("listpic",$listpic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_emailpic?>:</td>
<?php echo gfx_selector("emailpic",$emailpic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_questionpic?>:</td>
<?php echo gfx_selector("questionpic",$questionpic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_usercommentpic?>:</td>
<?php echo gfx_selector("usercommentpic",$usercommentpic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_attachpic?>:</td>
<?php echo gfx_selector("attachpic",$attachpic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_helppic?>:</td>
<?php echo gfx_selector("helppic",$helppic,"myform",1,true)?>
<tr class="inputrow"><td align="right"><?php echo $l_proginfopic?>:</td>
<?php echo gfx_selector("proginfopic",$proginfopic,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_closepic?>:</td>
<?php echo gfx_selector("closepic",$closepic,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_pagetoppic?>:</td>
<?php echo gfx_selector("pagetoppic",$pagetoppic,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_newpic?>:</td>
<?php echo gfx_selector("newpic",$newpic,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_srchtoolpic?>:</td>
<?php echo gfx_selector("srchtoolpic",$srchtoolpic,"myform",1,false)?>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_treeview?></b><br>
<span class="remark">(<?php echo $l_navpicremark?>)</span></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_programm?>:<br><?php echo $l_navpic_closed?></td>
<?php echo gfx_selector("navpic_progclosed",$navpic_progclosed,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_programm?>:<br><?php echo $l_navpic_open?></td>
<?php echo gfx_selector("navpic_progopen",$navpic_progopen,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_programm?>:<br><?php echo $l_navpic_locked?></td>
<?php echo gfx_selector("navpic_proglocked",$navpic_proglocked,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_category?>:<br><?php echo $l_navpic_closed?></td>
<?php echo gfx_selector("navpic_catclosed",$navpic_catclosed,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_category?>:<br><?php echo $l_navpic_open?></td>
<?php echo gfx_selector("navpic_catopen",$navpic_catopen,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_category?>:<br><?php echo $l_navpic_locked?></td>
<?php echo gfx_selector("navpic_catlocked",$navpic_catlocked,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_subcategory?>:<br><?php echo $l_navpic_closed?></td>
<?php echo gfx_selector("navpic_subcatclosed",$navpic_subcatclosed,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_subcategory?>:<br><?php echo $l_navpic_open?></td>
<?php echo gfx_selector("navpic_subcatopen",$navpic_subcatopen,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_subcategory?>:<br><?php echo $l_navpic_locked?></td>
<?php echo gfx_selector("navpic_subcatlocked",$navpic_subcatlocked,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_faq?>:</td>
<?php echo gfx_selector("navpic_faq",$navpic_faq,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_userquestions?>:</td>
<?php echo gfx_selector("navpic_question",$navpic_question,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_kbarticle?>:</td>
<?php echo gfx_selector("navpic_kbarticle",$navpic_kbarticle,"myform",1,false)?>
<tr class="listheading3"><td align="left" colspan="2"><b><?php echo $l_kb?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_wizardlink?>:</td>
<?php echo gfx_selector("navpic_kbwizard",$navpic_kbwizard,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_searchlink?>:</td>
<?php echo gfx_selector("navpic_kbsearch",$navpic_kbsearch,"myform",1,false)?>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_pagenavbox?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_firstpagepic?>:</td>
<?php echo gfx_selector("firstpagepic",$firstpagepic,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_lastpagepic?>:</td>
<?php echo gfx_selector("lastpagepic",$lastpagepic,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_prevpagepic?>:</td>
<?php echo gfx_selector("prevpagepic",$prevpagepic,"myform",1,false)?>
<tr class="inputrow"><td align="right"><?php echo $l_nextpagepic?>:</td>
<?php echo gfx_selector("nextpagepic",$nextpagepic,"myform",1,false)?>
<tr class="inforow"><td align="left" colspan="2"><span class="remark">
<img src="gfx\blpic.gif" border="0" align="absmiddle"> =
<?php echo $l_blank_gfx_info?></span></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_copyrightfooter?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("copyrightbgcolor",$copyrightbgcolor)?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_position?>:</td><td align="left">
<input type="radio" name="copyrightpos" value="1" <?php if($copyrightpos==1) echo "checked"?>><?php echo $l_beforefooter?><br>
<input type="radio" name="copyrightpos" value="0" <?php if($copyrightpos==0) echo "checked"?>><?php echo $l_afterfooter?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_asclist?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="noasclist" value="1" <?php if($disableasclist==1) echo "checked"?>>&nbsp;<?php echo $l_disableasclist?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_asclinelength?>:</td>
<td><input class="faqeinput" type="text" name="asclinelength" value="<?php echo $asclinelength?>" size="4" maxlength="4"> <?php echo $l_characters?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="forcewrap" value="1" <?php if($ascforcewrap==1) echo "checked"?>>
<?php echo $l_forcewrap?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_ascheader?>:<br>
<input name="enableascheader" value="1" type="checkbox"
<?php if($useascheader==1) echo " checked"?>> <?php echo $l_enable?></td>
<td><textarea class="faqeinput" name="ascheader" cols="40" rows="8"><?php echo do_htmlentities($ascheader)?></textarea>
<hr noshade color="#000000" size="1">
<?php
if($upload_avail)
	echo "<input class=\"faqefile\" type=\"file\" name=\"ascheaderupload\"><br>";
?>
<input type="checkbox" value="1" name="clearascheader"><?php echo $l_clear?>
<hr noshade color="#000000" size="1">
<?php echo $l_includefile?>: <input class="faqeinput" type="text" size="30" maxlength="240" name="ascheaderfile" value="<?php echo $ascheaderfile?>">
&nbsp;&nbsp;<a class="remark" href="javascript:openWindow3('infshow.php?<?php echo "$langvar=$act_lang"?>&note=l_incfile_remark','info',20,20,300,300);" onmouseover="popup(0)" onmouseout="popout()"><?php echo $l_note?></a><br>
<input type="radio" name="ascheaderfilepos" value="0" <?php if ($ascheaderfilepos==0) echo "checked"?>> <?php echo $l_beforecustomheader?><br>
<input type="radio" name="ascheaderfilepos" value="1" <?php if ($ascheaderfilepos==1) echo "checked"?>> <?php echo $l_aftercustomheader?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_mimetype?>:</td>
<td><select name="asclistmimetype">
<?php
for($i=0;$i<count($avail_mimetypes);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$asclistmimetype)
		echo " selected";
	echo ">".$avail_mimetypes[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_asclistcharset?>:</td>
<td><input class="faqeinput" type="text" name="asclistcharset" value="<?php echo $asclistcharset?>" size="40" maxlength="80"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_faqnewdisplay?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="dofaqnewdisplay" value="1" <?php if($enablefaqnewdisplay==1) echo "checked"?>>
<?php echo $l_activateselectionbox?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_displaymethod?>:</td>
<td>
<?php
for($i=0;$i<count($l_faqnewdisplaymethods);$i++)
{
	echo "<input type=\"radio\" name=\"newfaqdisplaymethod\" value=\"$i\"";
	if($faqnewdisplaymethod==$i)
		echo " checked";
	echo "> ".$l_faqnewdisplaymethods[$i]."<br>";
}
?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_markedbgcolor?>:</td>
<?php echo color_chooser("faqnewdisplaybgcolor",$faqnewdisplaybgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_markedfontcolor?>:</td>
<?php echo color_chooser("faqnewdisplayfontcolor",$faqnewdisplayfontcolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_pagenavbox?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("pagenavfontcolor",$pagenavfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td><td>
<input type="text" class="faqeinput" name="pagenavfontsize" value="<?php echo $pagenavfontsize?>" size="20" maxlength="20">
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_hovercells?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="enablehover" <?php if($hovercells==1) echo " checked"?>> <?php echo $l_hovercells?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_activcellcolor?>:</td>
<?php echo color_chooser("activcellcolor",$activcellcolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_browserscrollbar?></b></td>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="enablesbcolors" value="1" <?php if($colorscrollbars==1) echo "checked"?>> <?php echo $l_enable?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_sbfacecolor?>:</td>
<?php echo color_chooser("sbfacecolor",$sbfacecolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbhighlightcolor?>:</td>
<?php echo color_chooser("sbhighlightcolor",$sbhighlightcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbshadowcolor?>:</td>
<?php echo color_chooser("sbshadowcolor",$sbshadowcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbdarkshadowcolor?>:</td>
<?php echo color_chooser("sbdarkshadowcolor",$sbdarkshadowcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sb3dlightcolor?>:</td>
<?php echo color_chooser("sb3dlightcolor",$sb3dlightcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbarrowcolor?>:</td>
<?php echo color_chooser("sbarrowcolor",$sbarrowcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbtrackcolor?>:</td>
<?php echo color_chooser("sbtrackcolor",$sbtrackcolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_lists?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input name="listgotop" type="checkbox" value="1" <?php if(bittst($listoptions,BIT_1)) echo "checked"?>>
<?php echo $l_showgotop?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_gotoplinktype?>:</td><td>
<input type="radio" name="listtoptype" value="0" <?php if(!bittst($listoptions,BIT_2)) echo "checked"?>> <?php echo $l_astext?><br>
<input type="radio" name="listtoptype" value="1" <?php if(bittst($listoptions,BIT_2)) echo "checked"?>> <?php echo $l_aspic?>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_contentlist?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linkcolor?>:</td>
<?php echo color_chooser("clist_linkcolor",$clist_linkcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_vlinkcolor?>:</td>
<?php echo color_chooser("clist_vlinkcolor",$clist_vlinkcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_alinkcolor?>:</td>
<?php echo color_chooser("clist_alinkcolor",$clist_alinkcolor)?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_totallist?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="totalsummary" value="1" <?php if($summaryintotallist==1) echo "checked"?>>
<?php echo $l_showsummary?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_summarylength?>:</td><td>
<input class="faqeinput" type="text" name="summarychars" size="2" maxlength="2" value="<?php echo $summarychars?>"><?php echo $l_characters?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_textareas?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td>
<td><input class="faqeinput" type="text" name="textareawidth" value="<?php echo $textareawidth?>" size="4" maxlength="4"> <?php echo "$l_cols/$l_characters"?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td>
<td><input class="faqeinput" type="text" name="textareaheight" value="<?php echo $textareaheight?>" size="4" maxlength="4"> <?php echo $l_rows?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_proginfopopup?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td>
<td><input class="faqeinput" type="text" name="proginfowidth" value="<?php echo $proginfowidth?>" size="4" maxlength="4"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td>
<td><input class="faqeinput" type="text" name="proginfoheight" value="<?php echo $proginfoheight?>" size="4" maxlength="4"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_topoffset?>:</td>
<td><input class="faqeinput" type="text" name="proginfotop" value="<?php echo $proginfotop?>" size="4" maxlength="4"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_leftoffset?>:</td>
<td><input class="faqeinput" type="text" name="proginfoleft" value="<?php echo $proginfoleft?>" size="4" maxlength="4"> <?php echo $l_pixel?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_searchhelpwindow?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td>
<td><input class="faqeinput" type="text" name="helpwindowwidth" value="<?php echo $helpwindowwidth?>" size="4" maxlength="4"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td>
<td><input class="faqeinput" type="text" name="helpwindowheight" value="<?php echo $helpwindowheight?>" size="4" maxlength="4"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_topoffset?>:</td>
<td><input class="faqeinput" type="text" name="helpwindowtop" value="<?php echo $helpwindowtop?>" size="4" maxlength="4"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_leftoffset?>:</td>
<td><input class="faqeinput" type="text" name="helpwindowleft" value="<?php echo $helpwindowleft?>" size="4" maxlength="4"> <?php echo $l_pixel?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_faq?></b></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_treeviewframe?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="faqnav_expandsubtree" value="1" <?php if(bittst($faqnavoptions,BIT_1)) echo "checked"?>>
<?php echo $l_opentreeonlinks?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_kb?></b></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_defmode?>:</td>
<td><input type="radio" name="kbmode" value="wizard" <?php if ($kbmode=="wizard") echo "checked"?>> <?php echo $l_wizard?><br>
<input type="radio" name="kbmode" value="search" <?php if ($kbmode=="search") echo "checked"?>> <?php echo $l_search?><br>
<input type="radio" name="kbmode" value="proglist" <?php if ($kbmode=="proglist") echo "checked"?>> <?php echo $l_proglist?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_kbsortmethod?>:</td><td align="left">
<input type="radio" name="kbsortmethod" value="0" <?php if ($kbsortmethod==0) echo "checked"?>><?php echo $l_faqsortbydate?><br>
<input type="radio" name="kbsortmethod" value="1" <?php if ($kbsortmethod==1) echo "checked"?>><?php echo $l_faqsortgiven?><br>
<input type="radio" name="kbsortmethod" value="2" <?php if ($kbsortmethod==2) echo "checked"?>><?php echo $l_faqsortalpha?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_searchresults?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="kbsearch_displayprog" value="1" <?php if(bittst($kbsearchoptions,BIT_1)) echo "checked"?>>
<?php echo $l_displayprogname?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="kbsearch_displaycat" value="1" <?php if(bittst($kbsearchoptions,BIT_2)) echo "checked"?>>
<?php echo $l_displaycatname?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_treeviewframe?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="kbnav_wizard" value="1" <?php if(bittst($kbnavoptions,BIT_1)) echo "checked"?>> <?php echo $l_wizardlink?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="kbnav_search" value="1" <?php if(bittst($kbnavoptions,BIT_2)) echo "checked"?>> <?php echo $l_searchlink?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="kbnav_expandsubtree" value="1" <?php if(bittst($kbnavoptions,BIT_3)) echo "checked"?>>
<?php echo $l_opentreeonlinks?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_misc_settings?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td>
<td><input class="faqeinput" type="text" name="new_dateformat" value="<?php echo $dateformat?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_extdateformat?>:</td>
<td><input class="faqeinput" type="text" name="new_extdateformat" value="<?php echo $extdateformat?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_newtime?>:</td>
<td><input class="faqeinput" type="text" name="newtime" value="<?php echo $newtime?>" size="4" maxlength="4"> <?php echo $l_days?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxentries?></td>
<td><input class="faqeinput" type="text" name="maxentries" value="<?php echo $maxentries?>" size="5" maxlength="5"></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_search?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablesearch" value="1" type="checkbox"
<?php if($allowsearch==1) echo " checked"?>> <?php echo $l_allowsearch?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="dorestrict" value="1" type="checkbox"
<?php if($progrestrict==1) echo " checked"?>> <?php echo $l_progrestrict?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablesearchcomments" value="1" type="checkbox"
<?php if($searchcomments==1) echo " checked"?>> <?php echo $l_searchcomments?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablesearchquestions" value="1" type="checkbox"
<?php if($searchquestions==1) echo " checked"?>> <?php echo $l_searchquestions?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablesummary" value="1" type="checkbox"
<?php if($showsummary==1) echo " checked"?>> <?php echo $l_showsummary?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_summarylength?>:</td>
<td><input class="faqeinput" type="text" name="summarylength" value="<?php echo $summarylength?>" size="2" maxlength="2"> <?php echo $l_characters?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_defsearchmethod?>:</td>
<td><input type="radio" name="defsearchmethod" value="0" <?php if($defsearchmethod==0) echo "checked"?>> <?php echo $l_search_keywords?><br>
<input type="radio" name="defsearchmethod" value="1" <?php if($defsearchmethod==1) echo "checked"?>> <?php echo $l_search_fulltext?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" value="1" name="allowkeywordsearch" <?php if($enablekeywordsearch==1) echo "checked"?>>
<?php echo $l_enablekeywordsearch?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_keywordsearchmode?>:</td>
<td><input type="radio" name="keywordsearchmode" value="0" <?php if($keywordsearchmode==0) echo "checked"?>><?php echo $l_exactmatch?><br>
<input type="radio" name="keywordsearchmode" value="1" <?php if($keywordsearchmode==1) echo "checked"?>><?php echo $l_partialmatch?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="searchdisplayprog" value="1" onclick="srchdispprog()" <?php if(bittst($searchoptions,BIT_1)) echo "checked"?>><?php echo $l_displayprogname?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="searchdisplaycat" value="1" onclick="srchdispcat()" <?php if(bittst($searchoptions,BIT_2)) echo "checked"?> <?php if(!bittst($searchoptions,BIT_1)) echo "disabled"?>><?php echo $l_displaycatname?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="searchdisplaysubcat" value="1" <?php if(bittst($searchoptions,BIT_3)) echo "checked"?> <?php if(!bittst($searchoptions,BIT_2)) echo "disabled"?>><?php echo $l_displaysubcatname?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_inputfieldwidth?>:</td><td>
<input type="text" class="faqeinput" name="search_inputfieldwidth" value="<?php echo $search_inputfieldwidth?>" size="3" maxlength="4"> <?php echo $l_characters?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_highlightsearchterms?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="highlightsearch" value="1" <?php if($searchhighlight==1) echo "checked"?>><?php echo $l_highlightsearchterms?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("searchhighlightcolor",$searchhighlightcolor)?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_options?></b></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_global?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_faqsortmethod?>:</td><td align="left">
<input type="radio" name="faqsortmethod" value="0" <?php if ($faqsortmethod==0) echo "checked"?>><?php echo $l_faqsortbydate?><br>
<input type="radio" name="faqsortmethod" value="1" <?php if ($faqsortmethod==1) echo "checked"?>><?php echo $l_faqsortgiven?><br>
<input type="radio" name="faqsortmethod" value="2" <?php if ($faqsortmethod==2) echo "checked"?>><?php echo $l_faqsortalpha?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_listallfaqmethod?>:</td><td align="left">
<?php
for($i=0;$i<count($l_listallfaqmethods);$i++)
{
	echo "<input type=\"radio\" name=\"listallfaqmethod\" value=\"$i\"";
	if($listallfaqmethod==$i)
		echo "checked";
	echo "> ".$l_listallfaqmethods[$i]."<br>";
}
?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="useshortcutbar" value="1" <?php if ($enableshortcutbar==1) echo "checked"?>>
<?php echo $l_enableshortcutbar?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="showlanguageselector" value="1" <?php if ($enablelanguageselector==1) echo "checked"?>>
<?php echo $l_showlanguageselector?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="votesinline" value="1" type="checkbox"
<?php if($displayvotesinline==1) echo "checked"?>> <?php echo $l_displayvotesinline?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_votesinlinedisplaymode?>:</td><td>
<input type="radio" name="votesinlinedisplaymode" value="0" <?php if($votesinlinedisplaymode==0) echo "checked"?>><?php echo $l_astext?><br>
<input type="radio" name="votesinlinedisplaymode" value="1" <?php if($votesinlinedisplaymode==1) echo "checked"?>><?php echo $l_aspic?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="ratingspub" onclick="cfgratingspublic()" value="1" type="checkbox"
<?php if($ratingspublic==1) echo " checked"?>> <?php echo $l_ratingspublic?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="pubratecom" value="1" type="checkbox"
<?php
	if($ratingspublic==0)
		echo " disabled";
	else if($ratingcommentpublic==1)
		echo " checked";
?>> <?php echo $l_ratingcommentspublic?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablelists" value="1" type="checkbox"
<?php if($allowlists==1) echo " checked"?>> <?php echo $l_allowlists?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="allowjumpboxes" value="1" type="checkbox"
<?php if($enablejumpboxes==1) echo " checked"?>> <?php echo $l_enablejumpboxes?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablerelated" value="1" type="checkbox"
<?php if($displayrelated==1) echo " checked"?>> <?php echo $l_displayrelated?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="showattachinfo" value="1" <?php if($displayattachinfo==1) echo "checked"?>> <?php echo $l_displayattachinfo?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_userquestions?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="qautopub" value="1" type="checkbox"
<?php if($qesautopub==1) echo " checked"?>> <?php echo $l_autopublishnewquestion?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_minquestionlength?>:</td>
<td><input class="faqeinput" type="text" name="input_minquestion" value="<?php echo $minquestionlength?>" size="3" maxlength="3"> <?php echo $l_characters?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="questionosrequired" value="1" <?php if($questionrequireos==1) echo "checked"?>>
<?php echo $l_questionrequireos?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="versionrequired" value="1" <?php if($questionrequireversion==1) echo "checked"?>>
<?php echo $l_questionrequireversion?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_questionshorting?>:</td><td>
<input type="text" class="faqeinput" name="questionshorting" value="<?php echo $questionshorting?>" size="4" maxlength="10"> <?php echo $l_characters?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_usercomments?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_mincommentlength?>:</td>
<td><input type="text" class="faqeinput" name="input_mincomment" value="<?php echo $mincommentlength?>" size="3" maxlength="3"> <?php echo $l_characters?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_lists?></b></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_htmllisttype?>:</td>
<td><input type="radio" name="htmllisttype" value="0" <?php if($htmllisttype==0) echo "checked"?>> <?php echo $l_type?> 1<br>
<input type="radio" name="htmllisttype" value="1" <?php if($htmllisttype==1) echo "checked"?>> <?php echo $l_type?> 2</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="listcatgotop" value="1" <?php if(bittst($listoptions,BIT_3)) echo "checked"?>> <?php echo $l_catgotop?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_newfaqlist?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_numnewfaqs?>:</td>
<td><input class="faqeinput" type="text" name="numlatest" value="<?php echo $numlatest?>" size="4" maxlength="4"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_displayfaq?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="enablenextprev" value="1"
<?php if($shownextprev==1) echo "checked"?>> <?php echo $l_enablenextprev?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_nextprevmode?>:</td><td>
<input type="radio" name="nextprevmode" value="0" <?php if($nextprevmode==0) echo "checked"?>> <?php echo $l_withincategory?><br>
<input type="radio" name="nextprevmode" value="1" <?php if($nextprevmode==1) echo "checked"?>> <?php echo $l_withinprogram?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="displaysubcat" value="1" <?php if(bittst($displayoptions,BIT_1)) echo "checked"?>> <?php echo $l_displaysubcatname?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_pagenavbox?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="enablepagenavicons" value="1" <?php if($usepagenavicons==1) echo "checked"?>><?php echo $l_usepagenavicons?></td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="faqebutton" type="submit" name="submit" value="<?php echo $l_submit?>"></td></tr>
</table></td></tr></table>
<?php
include('./trailer.php');
?>