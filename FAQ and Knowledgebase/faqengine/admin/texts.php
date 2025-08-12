<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_texts;
$page="texts";
$uses_bbcode=true;
require_once('./heading.php');
include_once("./includes/bbcode_buttons.inc");
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="display")
	{
		if($admin_rights < 1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_texts where textnr=$input_textnr";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$displaytext = stripslashes($myrow["text"]);
		$displaytext = undo_htmlspecialchars($displaytext);
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_displaytext?></b></td></tr>
<tr class="displayrow"><td align="right" width="20%"><?php echo $l_id?>:</td><td width="80%"><?php echo $l_textids[$myrow["textid"]]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_language?>:</td><td><?php echo $myrow["lang"]?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_text?>:</td><td><?php echo $displaytext?></td></tr>
<?php
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_texts</a></div>";
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
		$deleteSQL = "delete from ".$tableprefix."_texts where (textnr=$input_textnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_texts</a></div>";
	}
	if($mode=="new")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_newtext?></b></td></tr>
<form name="inputform" onsubmit="return checkform()" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_id?>:</td><td width="80%">
<select name="textid">
<?php
		while(list($key, $val) = each($l_textids))
		{
			echo "<option value=\"".$key."\">".$val."</option>";
		}
?>
</select></td></tr>
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_language?>:</td><td width="80%">
<?php echo language_select($act_lang, "text_language", "../language/")?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_text?>:</td>
<td><textarea class="faqeinput" name="inputtext" cols="60" rows="10" wrap="virtual"></textarea>
<br>
<?php display_bbcode_buttons($l_bbbuttons,"inputtext",false,false,false)?>
</td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?><br>
<input type="checkbox" name="disablehtml" value="1"> <?php echo $l_disablehtml?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="add">
<input class="faqebutton" type="submit" value="<?php echo $l_add?>">&nbsp;&nbsp;<input class="faqebutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_texts</a></div>";
	}
	if($mode=="edit")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_texts where textnr=$input_textnr";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_edittext?></b></td></tr>
<form name="inputform" onsubmit="return checkform()" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_id?>:</td><td width="80%">
<select name="textid">
<?php
		while(list($key, $val) = each($l_textids))
		{
			echo "<option value=\"".$key."\"";
			if($key==$myrow["textid"])
				echo "selected";
			echo ">".$val."</option>";
		}
		$inputtext=stripslashes($myrow["text"]);
		$inputtext = str_replace("<BR>", "\n", $inputtext);
		$inputtext = undo_htmlspecialchars($inputtext);
		$inputtext = bbdecode($inputtext);
		$inputtext = undo_make_clickable($inputtext);
?>
</select></td></tr>
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_language?>:</td><td width="80%">
<?php echo language_select($myrow["lang"], "text_language", "../language/")?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_text?>:</td>
<td><textarea class="faqeinput" name="inputtext" cols="60" rows="10" wrap="virtual"><?php echo $inputtext?></textarea>
<br>
<?php display_bbcode_buttons($l_bbbuttons,"inputtext",false,false,false)?>
</td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?><br>
<input type="checkbox" name="disablehtml" value="1"> <?php echo $l_disablehtml?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_textnr" value="<?php echo $input_textnr?>">
<input class="faqebutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;<input class="faqebutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_texts</a></div>";
	}
	if($mode=="update")
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
		if(!$inputtext)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_notext</td></tr>";
			$errors=1;
		}
		$checksql="select * from ".$tableprefix."_texts where textid='$textid' and lang='$text_language' and textnr!=$input_textnr";
		if(!$result = faqe_db_query($checksql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if(faqe_db_num_rows($result)>0)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_textexists</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($preview))
			{
				$displaytext=$inputtext;
				if(isset($disablehtml))
				{
					$displaytext = htmlspecialchars($displaytext);
					$displaytext = undo_html_ampersand($displaytext);
				}
				if($urlautoencode==1)
					$displaytext = make_clickable($displaytext);
				if($enablespcode==1)
					$displaytext = bbencode($displaytext);
				$displaytext = stripslashes($displaytext);
				$displaytext = do_htmlentities($displaytext);
				$displaytext = str_replace("\n", "<BR>", $displaytext);
				$displaytext = undo_htmlspecialchars($displaytext);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_edittext?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_id?>:</td><td><?php echo $l_textids[$textid]?><input type="hidden" name="textid" value="<?php echo $textid?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td><?php echo $text_language?><input type="hidden" name="text_language" value="<?php echo $text_language?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_text?>:</td><td><?php echo $displaytext?><input type="hidden" name="inputtext" value="<?php echo do_htmlentities($inputtext)?>"></td></tr>
<?php
		if(isset($local_urlautoencode))
			echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
		if(isset($local_enablespcode))
			echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
		if(isset($disablehtml))
			echo "<input type=\"hidden\" name=\"disablehtml\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="faqebutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_textnr" value="<?php echo $input_textnr?>">
</td></tr></form></table></td></tr></table>
<?php
			}
			else
			{
				if(isset($disablehtml))
				{
					$inputtext = htmlspecialchars($inputtext);
					$inputtext = undo_html_ampersand($inputtext);
				}
				if(!isset($local_urlautoencode))
					$urlautoencode=0;
				else
					$urlautoencode=1;
				if(!isset($local_enablespcode))
					$enablespcode=0;
				else
					$enablespcode=1;
				$inputtext=stripslashes($inputtext);
				if($urlautoencode==1)
					$inputtext = make_clickable($inputtext);
				if($enablespcode==1)
					$inputtext = bbencode($inputtext);
				$inputtext = do_htmlentities($inputtext);
				$inputtext = str_replace("\n", "<BR>", $inputtext);
				$inputtext = addslashes($inputtext);
				$sql = "update ".$tableprefix."_texts set textid='$textid', lang='$text_language', text='$inputtext' where textnr=$input_textnr";
				if(!$result = faqe_db_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to update database.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_textupdated";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_texts</a></div>";
			}
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
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
		if(!$inputtext)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_notext</td></tr>";
			$errors=1;
		}
		$checksql="select * from ".$tableprefix."_texts where textid='$textid' and lang='$text_language'";
		if(!$result = faqe_db_query($checksql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.".faqe_db_error());
		if(faqe_db_num_rows($result)>0)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_textexists</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(!isset($local_urlautoencode))
				$urlautoencode=0;
			else
				$urlautoencode=1;
			if(!isset($local_enablespcode))
				$enablespcode=0;
			else
				$enablespcode=1;
			if(isset($preview))
			{
				$displaytext=$inputtext;
				if(isset($disablehtml))
				{
					$displaytext = htmlspecialchars($displaytext);
					$displaytext = undo_html_ampersand($displaytext);
				}
				if($urlautoencode==1)
					$displaytext = make_clickable($displaytext);
				if($enablespcode==1)
					$displaytext = bbencode($displaytext);
				$displaytext = stripslashes($displaytext);
				$displaytext = do_htmlentities($displaytext);
				$displaytext = str_replace("\n", "<BR>", $displaytext);
				$displaytext = undo_htmlspecialchars($displaytext);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newtext?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_id?>:</td><td><?php echo $l_textids[$textid]?><input type="hidden" name="textid" value="<?php echo $textid?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td><?php echo $text_language?><input type="hidden" name="text_language" value="<?php echo $text_language?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_text?>:</td><td><?php echo $displaytext?><input type="hidden" name="inputtext" value="<?php echo do_htmlentities($inputtext)?>"></td></tr>
<?php
		if(isset($local_urlautoencode))
			echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
		if(isset($local_enablespcode))
			echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
		if(isset($disablehtml))
			echo "<input type=\"hidden\" name=\"disablehtml\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="faqebutton" type="submit" value="<?php echo $l_enter?>">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="add">
</td></tr></form></table></td></tr></table>
<?php
			}
			else
			{
				if(isset($disablehtml))
				{
					$inputtext = htmlspecialchars($inputtext);
					$inputtext = undo_html_ampersand($inputtext);
				}
				if($urlautoencode==1)
					$inputtext = make_clickable($inputtext);
				if($enablespcode==1)
					$inputtext = bbencode($inputtext);
				$inputtext = stripslashes($inputtext);
				$inputtext = do_htmlentities($inputtext);
				$inputtext = str_replace("\n", "<BR>", $inputtext);
				$inputtext=addslashes($inputtext);
				$sql = "insert into ".$tableprefix."_texts (textid, lang, text) values ('$textid', '$text_language', '$inputtext')";
				if(!$result = faqe_db_query($sql, $db))
				    die("Unable to add Text to database.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_textadded";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_texts</a></div>";
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
	if($admin_rights>2)
	{
?>
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newtext?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
$sql = "select * from ".$tableprefix."_texts order by textid";
if(!$result = faqe_db_query($sql, $db)) {
    die("Could not connect to the database. (".faqe_db_error().")");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr bgcolor=\"#c0c0c0\"><td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
?>
<tr class="rowheadings">
<td align="center" width="70%"><b><?php echo $l_id?></b></td>
<td align="center" width="20%"><b><?php echo $l_language?></b></td>
<td>&nbsp;</td></tr>
<?php
		do {
			echo "<tr class=\"displayrow\">";
			$act_id=$myrow["textnr"];
			echo "<td width=\"60%\" align=\"center\">".$l_textids[$myrow["textid"]]."</td>";
			echo "<td width=\"20%\" align=\"center\">".$myrow["lang"]."</td>";
			echo "<td>";
			if(($admin_rights > 2))
			{
				echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_textnr=$act_id")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
				echo "&nbsp; ";
				echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_textnr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
				echo "&nbsp; ";
			}
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&$langvar=$act_lang&input_textnr=$act_id")."\">";
			echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
			echo "</td></tr>";
	   } while($myrow = faqe_db_fetch_array($result));
	}
	echo "</table></tr></td></table>";
if($admin_rights > 2)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newtext?></a></div>
<?php
}
}
include('./trailer.php');
?>