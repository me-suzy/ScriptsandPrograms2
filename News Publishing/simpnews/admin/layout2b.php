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
if(!isset($layoutpage))
	$layoutpage=0;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$ccform="layoutform";
$colorchooser=true;
$bbcbuttons=true;
$page_title=$l_layout;
$page="layout2b";
require_once('./heading.php');
include_once('./includes/color_chooser.inc');
include_once('./includes/gfx_selector.inc');
include_once("./includes/bbcode_buttons.inc");
if(!isset($layoutlang))
	$layoutlang=$act_lang;
if(!isset($srcpage))
	$srcpage=-1;
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
		die("<tr class=\"errorrow\"><td>Could not connect to the database.");
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
	switch($srcpage)
	{
		case 1:
			if(isset($usecatlist))
				$enablecatlist=1;
			else
				$enablecatlist=0;
			if(isset($noclwrap))
				$clnowrap=1;
			else
				$clnowrap=0;
			if(isset($noclactlink))
				$clactdontlink=1;
			else
				$clactdontlink=0;
			break;
		case 2:
			if(isset($icemail))
				$icdisplayemail=1;
			else
				$icdisplayemail=0;
			if(isset($futurenews))
				$showfuturenews=1;
			else
				$showfuturenews=0;
			if(isset($singlenodate))
				$snnodate=1;
			else
				$snnodate=0;
			if(isset($proposershow))
				$showproposer=1;
			else
				$showproposer=0;
			if(isset($news4catshow))
				$news4showcat=1;
			else
				$news4showcat=0;
			if(isset($news5icons))
				$news5displayicons=1;
			else
				$news5displayicons=0;
			if(isset($news5poster))
				$news5displayposter=1;
			else
				$news5displayposter=0;
			if(isset($news5ddlink))
				$news5useddlink=1;
			else
				$news5useddlink=0;
			if(isset($news5displayyear))
				$news5monthdisplayyear=1;
			else
				$news5monthdisplayyear=0;
			if(isset($disablen5globalprint))
				$news5noglobalprint=1;
			else
				$news5noglobalprint=0;
			if(isset($posterlink))
				$linkposter=1;
			else
				$linkposter=0;
			if(isset($showposter))
				$displayposter=1;
			else
				$displayposter=0;
			$csvexportfields=0;
			if(isset($csv_date))
				$csvexportfields=setbit($csvexportfields,BIT_1);
			if(isset($csv_lang))
				$csvexportfields=setbit($csvexportfields,BIT_2);
			if(isset($csv_category))
				$csvexportfields=setbit($csvexportfields,BIT_3);
			if(isset($csv_heading))
				$csvexportfields=setbit($csvexportfields,BIT_4);
			if(isset($csv_entry))
				$csvexportfields=setbit($csvexportfields,BIT_5);
			if(isset($csv_poster))
				$csvexportfields=setbit($csvexportfields,BIT_6);
			if(isset($events2onlyheadings))
				$ev2onlyheadings=1;
			else
				$ev2onlyheadings=0;
			if(isset($news3ddlink))
				$news3useddlink=1;
			else
				$news3useddlink=0;
			if(isset($news4ddlink))
				$news4useddlink=1;
			else
				$news4useddlink=0;
			if(isset($sn_hideallnews))
				$sn_hideallnewslink=1;
			else
				$sn_hideallnewslink=0;
			if(isset($inlinecomments))
				$commentsinline=1;
			else
				$commentsinline=0;
			$newsnodate=0;
			if(isset($nnodate))
				$newsnodate=setbit($wap_options,BIT_1);
			if(isset($non4date))
				$n4nodate=1;
			else
				$n4nodate=0;
			if(isset($nonewsicons))
				$newsnoicons=1;
			else
				$newsnoicons=0;
			$news4addoptions=0;
			if(isset($n4addbr))
				$news4addoptions=setbit($news4addoptions,BIT_1);
			break;
		case 5:
			if(isset($an_newslist))
				$announceoptions=setbit($announceoptions,BIT_2);
			if(isset($an_evcal_on))
				$announceoptions=setbit($announceoptions,BIT_3);
			if(isset($an_evcal))
				$announceoptions=setbit($announceoptions,BIT_4);
			if(isset($an_n5dmode) && ($an_n5dmode==1))
				$announceoptions=setbit($announceoptions,BIT_5);
			if(isset($an_ev2dmode) && ($an_ev2dmode==1))
				$announceoptions=setbit($announceoptions,BIT_6);
			if(isset($an_ev2asc))
				$announceoptions=setbit($announceoptions,BIT_7);
			if(isset($an_newsticker))
				$announceoptions=setbit($announceoptions,BIT_8);
			if(isset($an_curev))
				$announceoptions=setbit($announceoptions,BIT_9);
			if(isset($an_curevdisp) && ($an_curevdisp==1))
				$announceoptions=setbit($announceoptions,BIT_10);
			if(isset($an_nnotify))
				$announceoptions=setbit($announceoptions,BIT_11);
			if(isset($an_hotnews))
				$announceoptions=setbit($announceoptions,BIT_12);
			if(isset($an_h5dmode) && ($an_hn5dmode==1))
				$announceoptions=setbit($announceoptions,BIT_13);
			if(isset($an_hn6show))
				$announceoptions=setbit($announceoptions,BIT_14);
			if($an_hn6dmode==1)
				$announceoptions=setbit($announceoptions,BIT_15);
			break;
		case 6:
			$srchaddoptions=0;
			if(isset($srchdispcats))
				$srchaddoptions=setbit($srchaddoptions,BIT_1);
			if(isset($srchselectparts))
				$srchaddoptions=setbit($srchaddoptions,BIT_2);
			if(isset($hidefileinfo))
				$nofileinfo=1;
			else
				$nofileinfo=0;
			if(isset($srchonlyheadings))
				$searchonlyheadings=1;
			else
				$searchonlyheadings=0;
			if(isset($highlightsearch))
				$searchhighlight=1;
			else
				$searchhighlight=0;
			if(isset($nosrchlimit))
				$srchnolimit=1;
			else
				$srchnolimit=0;
			break;
		case 8:
			if(isset($eventcalshorts))
				$eventcalshortnews=1;
			else
				$eventcalshortnews=0;
			if(isset($shortheadings))
				$eventcalshortonlyheadings=1;
			else
				$eventcalshortonlyheadings=0;
			if(isset($eventcalmarkers))
				$eventcalonlymarkers=1;
			else
				$eventcalonlymarkers=0;
			if(isset($showevnum))
				$displayevnum=1;
			else
				$displayevnum=0;
			if(isset($evcalweek))
				$evshowcalweek=1;
			else
				$evshowcalweek=0;
			if(isset($caljump))
				$caljumpbox=1;
			else
				$caljumpbox=0;
			break;
		case 9:
			$proposereq=0;
			if(isset($propose_name))
				$proposereq=setbit($proposereq,BIT_1);
			if(isset($propose_email))
				$proposereq=setbit($proposereq,BIT_2);
			if(isset($propose_heading))
				$proposereq=setbit($proposereq,BIT_3);
			break;
		case 10:
			if(isset($nocheadbr))
				$cheadnobr=1;
			else
				$cheadnobr=0;
			if(isset($nocfootbr))
				$cfootnobr=1;
			else
				$cfootnobr=0;
			if(isset($clearcustomheader))
				$customheader="";
			else
			{
				if($new_global_handling)
					$tmp_file=$_FILES['customheaderfile']['tmp_name'];
				else
					$tmp_file=$HTTP_POST_FILES['customheaderfile']['tmp_name'];
				if($upload_avail && is_uploaded_file($tmp_file))
				{
					if(isset($path_tempdir) && $path_tempdir)
					{
						if($new_global_handling)
							$filename=$_FILES['customheaderfile']['name'];
						else
							$filename=$HTTP_POST_FILES['customheaderfile']['name'];
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
					$customheader = addslashes(get_file($orgfile));
					if(isset($path_tempdir) && $path_tempdir)
						unlink($orgfile);
				}
			}
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
							printf($l_cantmovefile,$path_attach."/".$physfile);
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
			if(isset($sepbylang))
				$separatebylang=1;
			else
				$separatebylang=0;
			if(isset($enablecurrtime))
				$showcurrtime=1;
			else
				$showcurrtime=0;
			if(isset($disableprinticon))
				$noprinticon=1;
			else
				$noprinticon=0;
			if(isset($disablegotopicon))
				$nogotopicon=1;
			else
				$nogotopicon=0;
			if(isset($enablepagenavdetails))
				$pagenavdetails=1;
			else
				$pagenavdetails=0;
			if(isset($enablecustomheader))
				$usecustomheader=1;
			else
				$usecustomheader=0;
			if(isset($enablecustomfooter))
				$usecustomfooter=1;
			else
				$usecustomfooter=0;
			if(isset($headerprint))
				$printheader=1;
			else
				$printheader=0;
			if(($customfooter) && !isset($cfnobrtrans))
				$customfooter=str_replace("\n","<BR>",$customfooter);
			if(($customheader) && !isset($chnobrtrans))
				$customheader=str_replace("\n","<BR>",$customheader);
			break;
		case 11:
			if(isset($enablesbcolors))
				$colorscrollbars=1;
			else
				$colorscrollbars=0;
			if(isset($textareanoscrbar))
				$textareanoscroll=1;
			else
				$textareanoscroll=0;
			break;
		case 13:
			$appletoptions=0;
			if($applet_anlink==1)
				$appletoptions=setbit($appletoptions,BIT_1);
			if(isset($nsheadingsep))
				$newsscrollerheadingsep=1;
			else
				$newsscrollerheadingsep=0;
			if(isset($scrollerstoponmouse))
				$newsscrollermousestop=1;
			else
				$newsscrollermousestop=0;
			if(isset($scrollerdate))
				$newsscrollerdisplaydate=1;
			else
				$newsscrollerdisplaydate=0;
			if(isset($scrollerwordwrap))
				$newsscrollerwordwrap=1;
			else
				$newsscrollerwordwrap=0;
			if(isset($disablescrollerlink))
				$newsscrollernolinking=1;
			else
				$newsscrollernolinking=0;
			if(isset($typerscroll))
				$newstyperscroll=1;
			else
				$newstyperscroll=0;
			if(isset($typerdate))
				$newstyperdisplaydate=1;
			else
				$newstyperdisplaydate=0;
			if(isset($typer2newscreen))
				$newstyper2newscreen=1;
			else
				$newstyper2newscreen=0;
			if(isset($typer2waitentry))
				$newstyper2waitentry=1;
			else
				$newstyper2waitentry=0;
			if(isset($typer2date))
				$newstyper2displaydate=1;
			else
				$newstyper2displaydate=0;
			if(isset($eventscrollerdate))
				$eventscrolleractdate=1;
			else
				$eventscrolleractdate=0;
			if(isset($disablestarlink))
				$ss_nolinking=1;
			else
				$ss_nolinking=0;
			if(isset($evscrollcal2))
				$evscrollevcal2=1;
			else
				$evscrollevcal2=0;
			break;
		case 14:
			if(isset($nohnlinking))
				$hnnolinking=1;
			else
				$hnnolinking=0;
			if(isset($hotnoheading))
				$hotscriptsnoheading=1;
			else
				$hotscriptsnoheading=0;
			if(isset($hotevposter))
				$hotevdisplayposter=1;
			else
				$hotevdisplayposter=0;
			if(isset($hotevnohtml))
				$hotevnohtmlformatting=1;
			else
				$hotevnohtmlformatting=0;
			if(isset($hotevdisplayicons))
				$hotevicons=1;
			else
				$hotevicons=0;
			if(isset($hotnewsposter))
				$hotnewsdisplayposter=1;
			else
				$hotnewsdisplayposter=0;
			if(isset($hotnewsnohtml))
				$hotnewsnohtmlformatting=1;
			else
				$hotnewsnohtmlformatting=0;
			if(isset($hotnewsdisplayicons))
				$hotnewsicons=1;
			else
				$hotnewsicons=0;
			if(isset($hotnews7ddlink))
				$hotnews7useddlink=1;
			else
				$hotnews7useddlink=0;
			if(isset($hncommentslink))
				$hotnewscommentslink=1;
			else
				$hotnewscommentslink=0;
			$hn_catlinking=0;
			if(isset($hn6nocatlink))
				$hn_catlinking=setbit($hn_catlinking,BIT_1);
			if(isset($hn7nocatlink))
				$hn_catlinking=setbit($hn_catlinking,BIT_2);
			if(isset($hn2nocatlink))
				$hn_catlinking=setbit($hn_catlinking,BIT_3);
			if(isset($hncommentslink))
				$hotnewscommentslink=1;
			else
				$hotnewscommentslink=0;
			break;
		case 15:
			if(!$hnlinkdest)
				$usehnlinkdest=0;
			if(isset($jsnf_linkingoff))
				$jsnf_nolinking=1;
			else
				$jsnf_nolinking=0;
			if(isset($jsnf_showdate))
				$jsnf_displaydate=1;
			else
				$jsnf_displaydate=0;
			if(isset($jsns_headingsep))
				$jsns_sepheading=1;
			else
				$jsns_sepheading=0;
			if(isset($jsns_linkingoff))
				$jsns_nolinking=1;
			else
				$jsns_nolinking=0;
			if(isset($jsns_showdate))
				$jsns_displaydate=1;
			else
				$jsns_displaydate=0;
			break;
		case 16:
			if($newslettercustomheader)
			{
				$newslettercustomheader=bbencode($newslettercustomheader);
				if(!isset($nchnobrtrans))
					$newslettercustomheader=str_replace("\n","<BR>",$newslettercustomheader);
			}
			if($newslettercustomfooter)
			{
				$newslettercustomfooter=bbencode($newslettercustomfooter);
				if(!isset($ncfnobrtrans))
					$newslettercustomfooter=str_replace("\n","<BR>",$newslettercustomfooter);
			}
			$masssuboptions=0;
			if(isset($msublastsenddate))
				$masssuboptions=setbit($masssuboptions,BIT_1);
			break;
		case 18:
			$sns_options=0;
			if(isset($sns_catlink))
				$sns_options=setbit($sns_options,BIT_1);
			if(isset($sns_displaydate))
				$sns_options=setbit($sns_options,BIT_2);
			if(isset($sns_displayheading))
				$sns_options=setbit($sns_options,BIT_3);
			if(isset($sns_displaypageheading))
				$sns_options=setbit($sns_options,BIT_4);
			if(isset($sns_displayposter))
				$sns_options=setbit($sns_options,BIT_5);
			if(isset($sns_displaycat))
				$sns_options=setbit($sns_options,BIT_6);
			if(isset($sns_linkposter))
				$sns_options=setbit($sns_options,BIT_7);
			if(isset($sns_printicon))
				$sns_options=setbit($sns_options,BIT_8);
			if(isset($sns_gotopicon))
				$sns_options=setbit($sns_options,BIT_9);
			if(isset($sns_emailnews))
				$sns_options=setbit($sns_options,BIT_10);
			if(isset($sns_rating))
				$sns_options=setbit($sns_options,BIT_11);
			if(isset($sns_displaynav))
				$sns_options=setbit($sns_options,BIT_12);
			if(isset($sns_displaycatinfo))
				$sns_options=setbit($sns_options,BIT_13);
			$aninc_options=0;
			if(isset($aninc_displaycat))
				$aninc_options=setbit($aninc_options,BIT_1);
			if(isset($aninc_catlink))
				$aninc_options=setbit($aninc_options,BIT_2);
			if(isset($aninc_displaydate))
				$aninc_options=setbit($aninc_options,BIT_3);
			if(isset($aninc_displayheading))
				$aninc_options=setbit($aninc_options,BIT_4);
			if(isset($aninc_displayposter))
				$aninc_options=setbit($aninc_options,BIT_5);
			if(isset($aninc_linkposter))
				$aninc_options=setbit($aninc_options,BIT_6);
			if(isset($aninc_printicon))
				$aninc_options=setbit($aninc_options,BIT_7);
			if(isset($aninc_gotopicon))
				$aninc_options=setbit($aninc_options,BIT_8);
			if(isset($aninc_emailnews))
				$aninc_options=setbit($aninc_options,BIT_9);
			if(isset($aninc_displaycatinfo))
				$aninc_options=setbit($aninc_options,BIT_10);
			$evinc_options=0;
			if(isset($evinc_displaypageheading))
				$evinc_options=setbit($evinc_options,BIT_1);
			if(isset($evinc_displaydate))
				$evinc_options=setbit($evinc_options,BIT_2);
			if(isset($evinc_displayheading))
				$evinc_options=setbit($evinc_options,BIT_3);
			if(isset($evinc_displayposter))
				$evinc_options=setbit($evinc_options,BIT_4);
			if(isset($evinc_linkposter))
				$evinc_options=setbit($evinc_options,BIT_5);
			if(isset($evinc_printicon))
				$evinc_options=setbit($evinc_options,BIT_6);
			if(isset($evinc_gotopicon))
				$evinc_options=setbit($evinc_options,BIT_7);
			if(isset($evinc_displaycatinfo))
				$evinc_options=setbit($evinc_options,BIT_8);
			if(isset($evinc_displaycat))
				$evinc_options=setbit($evinc_options,BIT_9);
			if(isset($evinc_catlink))
				$evinc_options=setbit($evinc_options,BIT_10);
			break;
		case 19:
			if($rss_auto_title>100)
				$rss_auto_title=100;
			if($rss_auto_short>500)
				$rss_auto_short=500;
			break;
		case 20:
			$wap_options=0;
			if(isset($wap_rss_short))
				$wap_options=setbit($wap_options,BIT_1);
			if($wap_ev_mode==1)
				$wap_options=setbit($wap_options,BIT_2);
			if(isset($wap_show_numentries))
				$wap_options=setbit($wap_options,BIT_3);
			if(isset($wap_evs_llink))
				$wap_options=setbit($wap_options,BIT_4);
			if(isset($wap_evs_drange))
				$wap_options=setbit($wap_options,BIT_5);
			if(isset($wap_catlist))
				$wap_options=setbit($wap_options,BIT_6);
			if(isset($wap_evs_text))
				$wap_options=setbit($wap_options,BIT_7);
			if($wap_evs_searchtype==1)
				$wap_options=setbit($wap_options,BIT_8);
			if(isset($wap_show_lastupdated))
				$wap_options=setbit($wap_options,BIT_9);
			if(isset($wap_link_catlist))
				$wap_options=setbit($wap_options,BIT_10);
			if(isset($wap_cl_nomode))
				$wap_options=setbit($wap_options,BIT_11);
			if(isset($wap_nopagecount))
				$wap_options=setbit($wap_options,BIT_12);
			if($wap_evs_dayrange<1)
				$wap_evs_dayrange=1;
			if($wap_evs_maxldays<2)
				$wap_evs_maxldays=2;
			if($wap_auto_title>100)
				$wap_auto_title=100;
			if($wap_auto_short>500)
				$wap_auto_short=500;
			break;
	}
	if(isset($news5startyear))
		$news5startdate=$news5startyear."-".$news5startmonth."-".$news5startday;
	if(isset($news5endyear))
		$news5enddate=$news5endyear."-".$news5endmonth."-".$news5endday;
	if($newsscrollermaxlines>4000)
		$newsscrollermaxlines=4000;
	$heading=trim($heading);
	$heading=stripslashes($heading);
	$heading=strip_tags($heading);
	$heading=addslashes($heading);
	$defsignature=strip_tags($defsignature);
	if($mode!="changepage")
	{
		$nonltrans=0;
		if(isset($chnobrtrans))
			$nonltrans=setbit($nonltrans,BIT_1);
		if(isset($cfnobrtrans))
			$nonltrans=setbit($nonltrans,BIT_2);
		if(isset($nchnobrtrans))
			$nonltrans=setbit($nonltrans,BIT_3);
		if(isset($ncfnobrtrans))
			$nonltrans=setbit($nonltrans,BIT_4);
		if(!isset($layoutnr))
		{
			include('./includes/layout_new.inc');
		}
		else
		{
			include('./includes/layout_update.inc');
		}
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		$infotext=$l_layoutupdated;
	}
}
if(!isset($mode) || ($mode!="changepage"))
{
	$sql = "select * from ".$tableprefix."_layout where lang='$layoutlang' and id='$layoutid'";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(!$myrow=mysql_fetch_array($result))
	{
		include('./includes/layout_defs.inc');
	}
	else
	{
		include('./includes/layout_get.inc');
	}
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
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="center" colspan="2"><?php echo $l_layoutlang?>: <?php echo language_select($layoutlang,"layoutlang","../language/")?>&nbsp;
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
<form name="layoutform" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>">
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
<input type="hidden" name="layoutpage" value="0">
<input type="hidden" name="srcpage" value="-1">
<?php
if(isset($layoutnr))
	echo "<input type=\"hidden\" name=\"layoutnr\" value=\"$layoutnr\">";
if(strlen($layoutid)<1)
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\">$l_layout_id:</td>";
	echo "<td class=\"inputrow\"><input class=\"sninput\" type=\"text\" name=\"layoutid\" size=\"10\" maxlength=\"10\">";
	echo "</td></tr></table></td></tr>";
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include_once("./includes/layout_pagevars.inc");
$maxtabcols=7;
$tabcolspan=$maxtabcols;
$tabcolwidth=100/$maxtabcols;
for($i=0;$i<count($destpages);$i++)
{
	if(($i%($maxtabcols))==0)
	{
		if((count($destpages)-$i-1)>0)
		{
			if($i>0)
				echo "</tr>";
			echo "<tr class=\"tabrow\">";
			$tabcolspan=$maxtabcols;
		}
	}
	$tabcolspan--;
	if($layoutpage!=$i)
	{
		echo "<td align=\"center\" valign=\"bottom\" width=\"$tabcolwidth%\" onmouseout=\"this.className='tabcol'\" onmouseover=\"this.className='tabhover'\">";
		echo "<a class=\"tablink\" href=\"";
		echo "javascript:changePage(".$i.");\">";
		echo $destpages[$i]."</a>";
	}
	else
	{
		echo "<td class=\"tabselected\" align=\"center\" valign=\"bottom\" width=\"$tabcolwidth%\">";
		echo "<span class=\"tabselected\">";
		echo $destpages[$i]."</span>";
	}
	echo "</td>";
}
if($tabcolspan>0)
	echo "<td colspan=\"$tabcolspan\">&nbsp;</td>";
echo "</tr></table></td></tr></table>";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
include("./includes/layoutpages/page".$layoutpage.".inc");
echo "</table></td></tr></table>";
include("./includes/layout_hiddenvars.inc");
?>
<div id="submitline"><a id="submitline"></a>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="actionrow"><td align="center"><input class="snbutton" type="submit" value="<?php if(strlen($layoutid)>0) echo $l_update; else echo $l_add?>"></td></tr>
</table></td></tr></table></div>
</form></td></tr></table>
<?php
include_once('./trailer.php');
echo "</body></html>";
?>
