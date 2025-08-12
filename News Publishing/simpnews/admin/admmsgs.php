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
if(!isset($entrylang))
	$entrylang=$act_lang;
if(!isset($start))
	$start=0;
require_once('./auth.php');
$page_title=$l_admmsgs;
$bbcbuttons=true;
$page="admmsgs";
require_once('./heading.php');
include_once("./includes/bbcode_buttons.inc");
if(!isset($headingtext))
	$headingtext="";
$errmsg="";
if(!isset($sorting))
	$sorting=32;
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
		if(sn_array_key_exists($admcookievals,"admmsg_lang"))
			$entrylang=$admcookievals["admmsg_lang"];
		if(sn_array_key_exists($admcookievals,"admmsg_sorting"))
			$sorting=$admcookievals["admmsg_sorting"];
	}
}
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
$entrydate=date("Y-m-d H:i:s");
if(isset($mode))
{
	if($mode=="add")
	{
		$entrytext=trim($entrytext);
		if(!isset($entrytext) || !$entrytext)
		{
			unset($preview);
			$errmsg=$l_noentrytext;
			$headingtext=do_htmlentities($heading);
		}
		else
		{
			if(isset($preview))
			{
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
				if(isset($urlautoencode))
					echo "<input type=\"hidden\" name=\"urlautoencode\" value=\"1\">";
				if(isset($enablespcode))
					echo "<input type=\"hidden\" name=\"enablespcode\" value=\"1\">";
				$entrytext=stripslashes($entrytext);
				$heading=stripslashes($heading);
?>
<input type="hidden" name="entrylang" value="<?php echo $entrylang?>">
<input type="hidden" name="entrytext" value="<?php echo display_encoded($entrytext)?>">
<input type="hidden" name="heading" value="<?php echo display_encoded($heading)?>">
<input type="hidden" name="mode" value="add">
<input type="hidden" name="frompreview" value="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_previewprelude?></td></tr>
<?php
				if(isset($urlautoencode))
					$entrytext = make_clickable($entrytext);
				if(isset($enablespcode))
					$entrytext = bbencode($entrytext);
				if(!isset($disableemoticons))
					$entrytext = encode_emoticons($entrytext, $url_emoticons, $db);
				$entrytext = do_htmlentities($entrytext);
				$entrytext = str_replace("\n", "<BR>", $entrytext);
				$entrytext = str_replace("\r","",$entrytext);
				$entrytext = undo_htmlspecialchars($entrytext);
				$acttime=transposetime(time(),$servertimezone,$displaytimezone);
				$actdate = date($l_admdateformat,$acttime);
				echo "<tr>";
				echo "<td align=\"center\"><table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">";
				echo "<tr><td align=\"left\" class=\"newsdate\">";
				echo $actdate."</td></tr>";
				if(strlen($heading)>0)
				{
					echo "<tr class=\"newsheading\"><td align=\"left\">";
					echo display_encoded(stripslashes($heading));
					echo "</td></tr>";
				}
				echo "<tr class=\"newsentry\"><td align=\"left\">";
				echo $entrytext;
				echo "</td></tr>";
				echo "</table></td></tr>";
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"submit\" value=\"$l_add\">&nbsp;&nbsp;<input class=\"snbutton\" type=\"button\" value=\"$l_back\" onclick=\"self.history.back();\"></td></tr>";
				echo "</table></td></tr></table>";
			}
			else
			{
				if(isset($urlautoencode))
					$entrytext = make_clickable($entrytext);
				if(isset($enablespcode))
					$entrytext = bbencode($entrytext);
				if(!isset($disableemoticons))
					$entrytext = encode_emoticons($entrytext, $url_emoticons, $db);
				$entrytext = do_htmlentities($entrytext);
				$entrytext = str_replace("\n", "<BR>", $entrytext);
				$entrytext = str_replace("\r", "", $entrytext);
				$entrytext=addslashes($entrytext);
				$actdate = date("Y-m-d H:i:s");
				$sql = "insert into ".$tableprefix."_globalmsg (lang, added, text, heading";
				$sql.= ") values ('$entrylang', '$actdate', '$entrytext', '$heading'";
				$sql.=")";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
			}
		}
	}
	if($mode=="massdel")
	{
		if(isset($entrynr))
		{
    		while(list($null, $input_entrynr) = each($_POST["entrynr"]))
    		{
				$sql = "delete from ".$tableprefix."_globalmsg where entrynr=$input_entrynr";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			}
		}
	}
	if($mode=="del")
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
<input type="hidden" name="entrylang" value="<?php echo $entrylang?>">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="input_entrynr" value="<?php echo $input_entrynr?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel: Message #$input_newsnr";
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$sql = "delete from ".$tableprefix."_globalmsg where entrynr=$input_entrynr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	}
	if($mode=="edit")
	{
		$sql = "select * from ".$tableprefix."_globalmsg where entrynr=$input_entrynr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		if($myrow = mysql_fetch_array($result))
		{
			$doedit=1;
			$headingtext=display_encoded(stripslashes($myrow["heading"]));
			$entrytext = stripslashes($myrow["text"]);
			$entrytext = str_replace("<BR>", "\n", $entrytext);
			$entrytext = undo_htmlspecialchars($entrytext);
			$entrytext = decode_emoticons($entrytext, $url_emoticons, $db);
			$entrytext = bbdecode($entrytext);
			$entrytext = undo_make_clickable($entrytext);
		}
	}
	if($mode=="update")
	{
		if(!isset($headingicon))
			$headingicon="";
		$entrytext=trim($entrytext);
		if(isset($entrytext) && $entrytext)
		{
			if(isset($preview))
			{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
				$entrytext=stripslashes($entrytext);
				$heading=stripslashes($heading);
				if(is_konqueror())
					echo "<tr><td></td></tr>";
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
				if(isset($urlautoencode))
					echo "<input type=\"hidden\" name=\"urlautoencode\" value=\"1\">";
				if(isset($enablespcode))
					echo "<input type=\"hidden\" name=\"enablespcode\" value=\"1\">";
?>
<input type="hidden" name="entrylang" value="<?php echo $entrylang?>">
<input type="hidden" name="entrytext" value="<?php echo do_htmlentities($entrytext)?>">
<input type="hidden" name="heading" value="<?php echo do_htmlentities($heading)?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_entrynr" value="<?php echo $input_entrynr?>">
<input type="hidden" name="frompreview" value="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_previewprelude?></td></tr>
<?php
				if(isset($urlautoencode))
					$entrytext = make_clickable($entrytext);
				if(isset($enablespcode))
					$entrytext = bbencode($entrytext);
				if(!isset($disableemoticons))
					$entrytext = encode_emoticons($entrytext, $url_emoticons, $db);
				$entrytext = do_htmlentities($entrytext);
				$entrytext = str_replace("\n", "<BR>", $entrytext);
				$entrytext = str_replace("\r", "", $entrytext);
				$entrytext = undo_htmlspecialchars($entrytext);
				$acttime=transposetime(time(),$servertimezone,$displaytimezone);
				$actdate = date($l_admdateformat,$acttime);
				echo "<tr>";
				echo "<td align=\"center\"><table width=\"100%\" align=\"center\" bgcolor=\"#c0c0c0\" cellspacing=\"0\" cellpadding=\"0\">";
				echo "<tr><td align=\"left\" class=\"newsdate\">";
				echo $actdate."</td></tr>";
				if(strlen($heading)>0)
				{
					echo "<tr class=\"newsheading\"><td align=\"left\">";
					echo do_htmlentities($heading);
					echo "</td></tr>";
				}
				echo "<tr class=\"newsentry\"><td align=\"left\">";
				echo $entrytext;
				echo "</td></tr>";
				echo "</table></td></tr>";
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"submit\" value=\"$l_update\">&nbsp;&nbsp;<input class=\"snbutton\" type=\"button\" value=\"$l_back\" onclick=\"self.history.back();\"></td></tr>";
				echo "</table></td></tr></table>";
			}
			else
			{
				if(isset($urlautoencode))
					$entrytext = make_clickable($entrytext);
				if(isset($enablespcode))
					$entrytext = bbencode($entrytext);
				if(!isset($disableemoticons))
					$entrytext = encode_emoticons($entrytext, $url_emoticons, $db);
				$entrytext = do_htmlentities($entrytext);
				$entrytext = str_replace("\n", "<BR>", $entrytext);
				$entrytext = str_replace("\r", "", $entrytext);
				$entrytext=addslashes($entrytext);
				$actdate = date("Y-m-d H:i:s");
				$sql = "update ".$tableprefix."_globalmsg set text='$entrytext', heading='$heading', added='$actdate'";
				$sql.= " where entrynr=$input_entrynr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
			}
		}
	}
}
if(!isset($preview))
{
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
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
?>
<tr class="inputrow"><td align="center" colspan="2"><?php echo $l_edlang3?>: <?php echo language_select($entrylang,"entrylang")?>
<input class="snbutton" type="submit" value="<?php echo $l_change?>"></td></tr></form>
<?php
echo "<tr class=\"inforow\"><td align=\"center\" valign=\"top\" colspan=\"2\"><b>$l_actuallyselected: $entrylang</b></td></tr>";
if($errmsg)
	echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$errmsg</td></tr>";
?>
<form <?php if($upload_avail) echo "enctype=\"multipart/form-data\""?>  name="inputform" method="post" action="<?php echo $act_script_url?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_heading?>:</td>
<td><input class="sninput" type="text" name="heading" value="<?php echo $headingtext?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" valign="top" width="20%"><?php echo $l_message?>:
</td>
<td align="left"><textarea class="sninput" name="entrytext" rows="10" cols="50">
<?php
	if(isset($doedit))
		echo $entrytext;
	echo "</textarea><br>";
	display_smiliebox("entrytext");
	display_bbcode_buttons($l_bbbuttons,"entrytext",true,true,"inputform",true);
	echo "</td></tr>";
	if(isset($doedit))
	{
		echo "<input type=\"hidden\" name=\"mode\" value=\"update\">";
		echo "<input type=\"hidden\" name=\"input_entrynr\" value=\"$input_entrynr\">";
	}
	else
		echo "<input type=\"hidden\" name=\"mode\" value=\"add\">";
	echo "<tr class=\"optionrow\"><td align=\"right\" valign=\"top\">$l_options:</td><td align=\"left\">";
	echo "<input type=\"checkbox\" name=\"urlautoencode\" value=\"1\" checked> $l_urlautoencode<br>";
	echo "<input type=\"checkbox\" name=\"enablespcode\" value=\"1\" checked> $l_enablespcode<br>";
	echo "<input type=\"checkbox\" name=\"disableemoticons\" value=\"1\"> $l_disableemoticons";
	echo "</td></tr>";
?>
<tr class="actionrow"><td align="center" colspan="2"><input class="snbutton" type="submit" name="submit" value="<?php if(isset($doedit)) echo $l_update; else echo $l_add?>">&nbsp;&nbsp;<input class="snbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<p></p>
<?php
$sql = "select * from ".$tableprefix."_globalmsg where lang='$entrylang' ";
switch($sorting)
{
	case 11:
		$sql.="order by entrynr asc";
		break;
	case 12:
		$sql.="order by entrynr desc";
		break;
	case 21:
		$sql.="order by heading asc, text asc";
		break;
	case 22:
		$sql.="order by heading desc, text desc";
		break;
	case 31:
		$sql.="order by added asc";
		break;
	case 32:
		$sql.="order by added desc";
		break;
}
if(!$result = mysql_query($sql, $db))
    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
$numentries=mysql_num_rows($result);
if($admepp>0)
{
	if(($start>0) && ($numentries>$admepp))
	{
		$sql .=" limit $start,$admepp";
	}
	else
	{
		$sql .=" limit $admepp";
	}
	if(!$result = mysql_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
		echo "<tr><TD BGCOLOR=\"#000000\">";
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		if(($admepp+$start)>$numentries)
			$displayresults=$numentries;
		else
			$displayresults=($admepp+$start);
		$displaystart=$start+1;
		$displayend=$displayresults;
		echo "<tr class=\"pagenav\"><td align=\"center\">";
		echo "<a id=\"list\"><b>$l_page ".ceil(($start/$admepp)+1)."/".ceil(($numentries/$admepp))."</b><br><b>($l_entries $displaystart - $displayend $l_of $numentries)</b></a>";
		echo "</td></tr></table></td></tr></table>";
	}
}
if(($admepp>0) && ($numentries>$admepp))
{
	$baselink="$act_script_url?$langvar=$act_lang&entrylang=$entrylang";
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"pagenav\"><td align=\"center\">";
	echo "<b>$l_page</b> ";
	if(floor(($start+$admepp)/$admepp)>1)
	{
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=0")."#list\">";
		echo "<img src=\"../gfx/first.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start-$admepp))."#list\">";
		echo "<img src=\"../gfx/prev.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
		echo "</a> ";
	}
	for($i=1;$i<($numentries/$admepp)+1;$i++)
	{
		if(floor(($start+$admepp)/$admepp)!=$i)
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-1)*$admepp));
			echo "#list\"><b>[$i]</b></a> ";
		}
		else
			echo "<b>($i)</b> ";
	}
	if($start < (($i-2)*$admepp))
	{
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start+$admepp))."#list\">";
		echo "<img src=\"../gfx/next.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-2)*$admepp))."#list\">";
		echo "<img src=\"../gfx/last.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
		echo "</a> ";
	}
	echo "</font></td></tr></table></td></tr></table>";
}
if ($myrow = mysql_fetch_array($result))
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="newslist" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="entrylang" value="<?php echo $entrylang?>">
<input type="hidden" name="mode" value="massdel">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	$baseurl=$act_script_url."?".$langvar."=".$act_lang;
	$maxsortcol=3;
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
	if(isset($entrylang))
		$baseurl.="&entrylang=$entrylang";
	echo "<tr class=\"rowheadings\">";
	echo "<td><a id=\"resultlist\">&nbsp;</a></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "#</a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_entry</a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_date</a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "<td colspan=\"2\">&nbsp;</td>";
	echo "</tr>";
	do{
		$act_id=$myrow["entrynr"];
		$entrytext = stripslashes($myrow["text"]);
		$entrytext = undo_htmlspecialchars($entrytext);
		if($admentrychars>0)
		{
			$entrytext=undo_htmlentities($entrytext);
			$entrytext=strip_tags($entrytext);
			$entrytext=substr($entrytext,0,$admentrychars);
			$entrytext.="[...]";
		}
		if($admonlyentryheadings==0)
		{
			if($myrow["heading"])
				$displaytext="<b>".$myrow["heading"]."</b><br>".$entrytext;
			else
				$displaytext=$entrytext;
		}
		else
		{
			if($myrow["heading"])
				$displaytext="<b>".$myrow["heading"]."</b>";
			else
			{
				$displaytext=strip_tags($myrow["text"]);
				if($admentrychars>0)
					$displaytext=substr($displaytext,0,$admentrychars);
				else
					$displaytext=substr($displaytext,0,20);
				$displaytext.="[...]";
			}
		}
		echo "<tr>";
		echo "<td class=\"actionrow\" align=\"center\" width=\"1%\" valign=\"top\">";
		echo "<input type=\"checkbox\" name=\"entrynr[]\" value=\"$act_id\">";
		echo "</td>";
		echo "<td class=\"displayrow\" align=\"center\" width=\"8%\" valign=\"top\">";
		$showurl=do_url_session("msgshow.php?$langvar=$act_lang&entrynr=$act_id");
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo $myrow["entrynr"];
		echo "</a></td>";
		echo "<td class=\"newsentry\" align=\"left\" valign=\"top\">";
		echo "$displaytext</td>";
		echo "<td class=\"newsdate\" align=\"center\" width=\"20%\" valign=\"top\">";
		list($mydate,$mytime)=explode(" ",$myrow["added"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$temptime=mktime($hour,$min,$sec,$month,$day,$year);
		$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
		$displaydate=date($l_admdateformat,$temptime);
		echo "$displaydate</td>";
		echo "<td class=\"adminactions\" align=\"center\" width=\"2%\" valign=\"top\">";
		echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&entrylang=$entrylang&mode=edit&input_entrynr=$act_id")."\"><img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
		echo "</td>";
		echo "<td class=\"adminactions\" align=\"center\" width=\"2%\" valign=\"top\">";
		$dellink=do_url_session("$act_script_url?$langvar=$act_lang&entrylang=$entrylang&mode=del&input_entrynr=$act_id");
		if($admdelconfirm==2)
			echo "<a class=\"listlink2\" href=\"javascript:confirmDel('Message #$act_id','$dellink')\">";
		else
			echo "<a class=\"listlink2\" href=\"$dellink\" valign=\"top\">";
		echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a></td>";
		echo "</tr>";
	}while($myrow=mysql_fetch_array($result));
	echo "<tr class=\"actionrow\"><td colspan=\"11\" align=\"left\"><input class=\"snbutton\" type=\"submit\" value=\"$l_delselected\">";
	echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"checkAll(document.newslist)\" value=\"$l_checkall\">";
	echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"uncheckAll(document.newslist)\" value=\"$l_uncheckall\">";
	echo "</td></tr>";
	echo "</form></table></td></tr></table>";
}
if(($admepp>0) && ($numentries>$admepp))
{
	$baselink="$act_script_url?$langvar=$act_lang&entrylang=$entrylang";
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"pagenav\"><td align=\"center\">";
	echo "<b>$l_page</b> ";
	if(floor(($start+$admepp)/$admepp)>1)
	{
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=0")."#list\">";
		echo "<img src=\"../gfx/first.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start-$admepp))."#list\">";
		echo "<img src=\"../gfx/prev.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
		echo "</a> ";
	}
	for($i=1;$i<($numentries/$admepp)+1;$i++)
	{
		if(floor(($start+$admepp)/$admepp)!=$i)
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-1)*$admepp));
			echo "#list\"><b>[$i]</b></a> ";
		}
		else
			echo "<b>($i)</b> ";
	}
	if($start < (($i-2)*$admepp))
	{
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start+$admepp))."#list\">";
		echo "<img src=\"../gfx/next.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-2)*$admepp))."#list\">";
		echo "<img src=\"../gfx/last.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
		echo "</a> ";
	}
	echo "</font></td></tr></table></td></tr></table>";
}
}
include('./trailer.php');
?>
