<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		include("./includes/bbcode_buttons.inc");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_kb_articles where (articlenr=$input_articlenr)";
		if(!$result = faqe_db_query($sql, $db))
			db_die("<tr class=\"errorrow\"><td>Unable to get article from DB.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");

		if($admin_rights <3)
		{
			$sql2="select * from ".$tableprefix."_programm_admins where (prognr=".$myrow["programm"].") and (usernr=".$userdata["usernr"].")";
			if(!$result2 = faqe_db_query($sql2, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get program admins from DB.");
			if(!$tmprow = faqe_db_fetch_array($result2))
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				die("$l_functionnotallowed");
			}
		}
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editkbarticle?> (1/2)</b></td></tr>
<form onSubmit="return checkform1();" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> name="inputform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		echo "<input type=\"hidden\" name=\"selprog\" value=\"".$myrow["programm"]."\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="input_articlenr" value="<?php echo $myrow["articlenr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_heading?>:</td><td><input class="faqeinput" type="text" name="heading" size="40" maxlength="80" value="<?php echo undo_html_ampersand(stripslashes($myrow["heading"]))?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_programm?>:</td>
<td>
<?php
	$firstarg=true;
	if($admin_rights<3)
	{
		$sql = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr ";
		$firstarg=false;
	}
	else
		$sql = "select prog.* from ".$tableprefix."_programm prog ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterprog) && ($filterprog>=0))
		{
			if($firstarg)
			{
				$sql.="where ";
				$firstarg=false;
			}
			else
				$sql.="and ";
			$sql.="prog.prognr=$filterprog ";
		}
		else if(isset($filterlang) && ($filterlang!="none"))
		{
			if($firstarg)
			{
				$sql.="where ";
				$firstarg=false;
			}
			else
				$sql.="and ";
			$sql.="prog.language='$filterlang' ";
		}
	}
	$sql.="order by prog.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql.=", prog.programmname asc";
	else
		$sql.=", prog.displaypos asc";
	if(!$result = faqe_db_query($sql, $db)) {
		db_die("<tr class=\"errorrow\"><td>Unable to get programs from DB.");
	}
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<a href=\"".do_url_session("program.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="programm" onchange="document.inputform.selprog.value=this.options[this.selectedIndex].value">
<option value="-1">???</option>
<?php
	do {
		echo "<option value=\"".$temprow["prognr"]."\"";
		if($myrow["programm"]==$temprow["prognr"])
			echo " selected";
		echo ">";
		$catname=display_encoded($temprow["programmname"]);
		echo display_encoded($temprow["programmname"])." [".$temprow["language"]."]";
		echo "</option>";
	} while($temprow = faqe_db_fetch_array($result));
?>
</select>
<?php
	}
?>
</td></tr>
<?php
	$sql = "select kw.* from ".$tableprefix."_keywords kw, ".$tableprefix."_kb_keywords ak where ak.articlenr=$input_articlenr and kw.keywordnr=ak.keywordnr";
	if(!$result = faqe_db_query($sql, $db)) {
		db_die("<tr class=\"errorrow\"><td>Unable to get keywords from DB.");
	}
	$keywords="";
	if($temprow=faqe_db_fetch_array($result))
	{
		$firstkw=1;
		do{
			if($firstkw==1)
				$firstkw=0;
			else
				$keywords.="| ";
			$keywords.=$temprow["keyword"];
		}while($temprow=faqe_db_fetch_array($result));
	}
	$articletext = stripslashes($myrow["article"]);
	$articletext = str_replace("<BR>", "\n", $articletext);
	$articletext = undo_htmlspecialchars($articletext);
	$articletext = bbdecode($articletext);
	$articletext = undo_make_clickable($articletext);
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_keywords?>:<br>
<span class="remark"><?php echo $l_keywordsnote?></span>
</td><td><textarea class="faqeinput" name="keywords" cols="<?php echo $textareascols?>" rows="<?php echo $textareasrows?>"><?php echo $keywords?></textarea></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_articletext?>:
</td><td><textarea class="faqeinput" name="article" cols="<?php echo $textareascols?>" rows="<?php echo $textareasrows?>"><?php echo $articletext?></textarea><br>
<?php display_bbcode_buttons($l_bbbuttons,"article")?>
<?php echo kb_addopts("qref","kbref","article")?>
</td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?><br>
<input type="checkbox" name="disablehtml" value="1"> <?php echo $l_disablehtml?><br>
<input type="checkbox" name="no_br" value="1"><?php echo $l_donttranslatelinebreaks?>
</td></tr>
<?php
if($upload_avail)
{
?>
<tr class="inputrow"><td align="right"><?php echo "$l_or $l_kbfileupload"?>:<br>
<span class="remark"><?php echo $l_offline_editor_file?></span>
</td><td>
<input class="faqeinput" type="file" name="kbfile"></td></tr>
<?php
}
?>
<?php
if($admin_rights<3)
{
?>
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_lastedited?> <i><?php echo $myrow["editor"]?></i>, <i><?php echo $myrow["lastedited"]?></i></td></tr>
<?php
}
else
{
?>
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_lastedited?> <i><?php echo $myrow["editor"]?></i>, <i><?php echo $myrow["lastedited"]?></i></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_seteditdate?></td><td align="left"><input class="faqeinput" type="text" name="input_editdate">
<hr width="90%" align="center">
<input type="checkbox" name="keepeditdate" value="1"><input type="hidden" name="oldeditdate" value="<?php echo $myrow["lastedited"]?>"> <?php echo $l_keepeditdate?></td></tr>
<?php
}
?>
<tr class="inputrow"><td align="center">&nbsp;</td>
<td><input type="checkbox" name="keepviews" value="1"><input type="hidden" name="oldviews" value="<?php echo $myrow["views"]?>"> <?php echo $l_keepviews?></td>
</tr>
<tr class="inputrow"><td align="center">&nbsp;</td>
<td><input type="checkbox" name="keepratings" value="1"><input type="hidden" name="oldrating" value="<?php echo $myrow["rating"]?>">
<input type="hidden" name="oldratingcount" value="<?php echo $myrow["ratingcount"]?>"><?php echo $l_keepratings?></td>
</tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="edit2">
<input class="faqebutton" type="submit" value="<?php echo $l_continue?>">
</td></tr>
</form>
</table></tr></td></table>
<?php
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_articlelist</a></div>";
?>