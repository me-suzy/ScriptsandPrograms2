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
		$sql = "select * from ".$tableprefix."_data where (faqnr=$input_faqnr)";
		if(!$result = faqe_db_query($sql, $db))
			db_die("<tr class=\"errorrow\"><td>Unable to get FAQ from DB.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		if($admin_rights <3)
		{
			$sql2="select * from ".$tableprefix."_category_admins where (catnr=".$myrow["category"].") and (usernr=".$userdata["usernr"].")";
			if(!$result2 = faqe_db_query($sql2, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to determine category admins.");
			if(!$tmprow = faqe_db_fetch_array($result2))
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				die("$l_functionnotallowed");
			}
		}
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editfaq?> (1/2)</b></td></tr>
<form onSubmit="return checkform1();" name="inputform" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		$tempsql = "select * from ".$tableprefix."_category where catnr=".$myrow["category"];
		if(!$tempresult = faqe_db_query($tempsql, $db))
			db_die("<tr class=\"errorrow\"><td>Unable to get category from DB.");
		if($temprow = faqe_db_fetch_array($tempresult))
			echo "<input type=\"hidden\" name=\"selprog\" value=\"0|".$temprow["programm"]."\">";
		else
			echo "<input type=\"hidden\" name=\"selprog\" value=\"\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_faqnr" value="<?php echo $myrow["faqnr"]?>">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right"><?php echo $l_category?>:</td>
<td><input type="hidden" name="oldcat" value="<?php echo $myrow["category"]?>">
<?php
	if($admin_rights<3)
		$sql = "select cat.*, prog.language, prog.programmname from ".$tableprefix."_category cat, ".$tableprefix."_category_admins ca, ".$tableprefix."_programm prog where cat.catnr = ca.catnr and ca.usernr=$act_usernr and prog.prognr=cat.programm ";
	else
		$sql = "select cat.*, prog.language, prog.programmname from ".$tableprefix."_category cat, ".$tableprefix."_programm prog where prog.prognr=cat.programm ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterprog) && ($filterprog>=0))
			$sql.="and prog.prognr=$filterprog ";
		else if(isset($filterlang) && ($filterlang!="none"))
			$sql.="and prog.language='$filterlang' ";
	}
	$sql.="order by prog.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql.=", prog.programmname asc, cat.categoryname asc";
	else
		$sql.=", prog.displaypos asc, cat.displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Unable to get category list from DB.");
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<a href=\"".do_url_session("categories.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="category" onchange="document.inputform.selprog.value=this.options[this.selectedIndex].value">
<option value="-1|-1">???</option>
<?php
		do {
			$prognr=$temprow["programm"];
			echo "<option value=\"".$temprow["catnr"]."|$prognr\"";
			if($myrow["category"]==$temprow["catnr"])
				echo " selected";
			echo ">";
			$catname=display_encoded($temprow["categoryname"]);
			$progname=display_encoded($temprow["programmname"]);
			$proglang=$temprow["language"];
			echo "$catname ($progname [$proglang])";
			echo "</option>";
		} while($temprow = faqe_db_fetch_array($result));
		echo "</select>";
	}
?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_or?> <?php echo $l_subcategory?>:</td><td>
<?php
	if($admin_rights<3)
		$sql = "select cat.categoryname as catname, subcat.catnr as subcatnr, subcat.categoryname as subcatname, prog.programmname, prog.language, prog.prognr from ".$tableprefix."_category cat, ".$tableprefix."_category_admins ca, ".$tableprefix."_subcategory subcat, ".$tableprefix."_programm prog  where cat.catnr = ca.catnr and ca.usernr=$act_usernr and subcat.category=cat.catnr and prog.prognr=cat.programm ";
	else
		$sql = "select cat.categoryname as catname, subcat.catnr as subcatnr, subcat.categoryname as subcatname, prog.programmname, prog.language, prog.prognr from ".$tableprefix."_category cat, ".$tableprefix."_subcategory subcat, ".$tableprefix."_programm prog  where subcat.category=cat.catnr and prog.prognr=cat.programm ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterprog) && ($filterprog>=0))
			$sql.="and prog.prognr=$filterprog ";
		else if(isset($filterlang) && ($filterlang!="none"))
			$sql.="and prog.language='$filterlang' ";
	}
	$sql.="order by prog.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql.=", prog.programmname asc , cat.categoryname asc, subcat.categoryname asc";
	else
		$sql.=", prog.displaypos asc, cat.displaypos asc, subcat.displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Unable to get seubcategories from DB.");
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<a href=\"".do_url_session("subcategories.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="subcategory" onchange="document.inputform.selprog.value=this.options[this.selectedIndex].value">
<option value="0"><?php echo $l_none?></option>
<?php
		do {
			echo "<option value=\"".$temprow["subcatnr"]."|".$temprow["prognr"]."\"";
			if($myrow["subcategory"]==$temprow["subcatnr"])
				echo " selected";
			echo ">";
			$progname=display_encoded($temprow["programmname"]);
			$proglang=$temprow["language"];
			$catname=display_encoded($temprow["catname"]);
			$subcatname=display_encoded($temprow["subcatname"]);
			echo "$subcatname ($catname : $progname [$proglang])";
			echo "</option>";
		} while($temprow = faqe_db_fetch_array($result));
?>
</select>
<?php
	}
	echo "</td></tr>";
	$questiontext = stripslashes($myrow["questiontext"]);
	$questiontext = str_replace("<BR>", "\n", $questiontext);
	$questiontext = undo_htmlspecialchars($questiontext);
	$questiontext = bbdecode($questiontext);
	$questiontext = undo_make_clickable($questiontext);
	$answertext = stripslashes($myrow["answertext"]);
	$answertext = str_replace("<BR>", "\n", $answertext);
	$answertext = undo_htmlspecialchars($answertext);
	$answertext = bbdecode($answertext);
	$answertext = undo_make_clickable($answertext);
	$sql = "select kw.* from ".$tableprefix."_keywords kw, ".$tableprefix."_faq_keywords fk where fk.faqnr=$input_faqnr and kw.keywordnr=fk.keywordnr";
	if(!$result = faqe_db_query($sql, $db)) {
		db_die("<tr class=\"errorrow\"><td>Unable to get keywords from DB.");
	}
	$displaykeywords="";
	if($temprow=faqe_db_fetch_array($result))
	{
		$first=1;
		do{
			if($first==1)
				$first=0;
			else
				$displaykeywords.="| ";
			$displaykeywords.=$temprow["keyword"];
		}while($temprow=faqe_db_fetch_array($result));
	}
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_heading?>:</td><td><input class="faqeinput" type="text" name="heading" size="40" maxlength="80" value="<?php echo undo_html_ampersand(stripslashes($myrow["heading"]))?>"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_question?>:</td><td><textarea class="faqeinput" name="question" cols="<?php echo $textareascols?>" rows="<?php echo $textareasrows?>"><?php echo $questiontext?></textarea><br>
<?php display_bbcode_buttons($l_bbbuttons,"question")?>
<?php echo faq_addopts("qref","faqref","question")?>
</td></tr>
<tr class="optionrow"><td>&nbsp;</td><td>
<input type="checkbox" name="ques_no_br" value="1"><?php echo $l_donttranslatelinebreaks?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_answer?>:
</td><td><textarea class="faqeinput" name="answer" cols="<?php echo $textareascols?>" rows="<?php echo $textareasrows?>"><?php echo $answertext?></textarea><br>
<?php display_bbcode_buttons($l_bbbuttons,"answer")?>
<?php echo faq_addopts("aref","faqref","answer")?>
</td></tr>
<tr class="optionrow"><td>&nbsp;</td><td>
<input type="checkbox" name="ans_no_br" value="1"><?php echo $l_donttranslatelinebreaks?></td></tr>
<?php
if($upload_avail)
{
?>
<tr class="inputrow"><td align="right"><?php echo "$l_or $l_faqfileupload"?>:<br>
<span class="remark"><?php echo $l_offline_editor_file?></span>
</td><td>
<input class="faqeinput" type="file" name="faqfile"></td></tr>
<?php
}
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_keywords?>:<br><?php echo $l_keywordsnote?></td>
<td><textarea class="faqeinput" name="keywords" cols="<?php echo $textareascols?>" rows="<?php echo $textareasrows?>"><?php echo $displaykeywords?></textarea></td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?><br>
<input type="checkbox" name="disablehtml" value="1"> <?php echo $l_disablehtml?>
</td></tr>
<?php
if($admin_rights<3)
{
?>
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_lastedited?> <i><?php echo $myrow["editor"]?></i>, <i><?php echo $myrow["editdate"]?></i></td></tr>
<?php
}
else
{
?>
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_lastedited?> <i><?php echo $myrow["editor"]?></i>, <i><?php echo $myrow["editdate"]?></i></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_seteditdate?></td><td align="left"><input class="faqeinput" type="text" name="input_editdate">
<hr width="90%" align="center">
<input type="checkbox" name="keepeditdate" value="1"><input type="hidden" name="oldeditdate" value="<?php echo $myrow["editdate"]?>"><?php echo $l_keepeditdate?></td></tr>
<?php
}
?>
<tr class="inputrow"><td align="center">&nbsp;</td>
<td><input type="checkbox" name="keepviews" value="1"><input type="hidden" name="oldviews" value="<?php echo $myrow["views"]?>"><?php echo $l_keepviews?></td>
</tr>
<tr class="inputrow"><td align="center">&nbsp;</td>
<td><input type="checkbox" name="keepratings" value="1"><input type="hidden" name="oldrating" value="<?php echo $myrow["rating"]?>">
<input type="hidden" name="oldratingcount" value="<?php echo $myrow["ratingcount"]?>"><?php echo $l_keepratings?></td>
</tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="edit2">
<input class="faqebutton" type="submit" value="<?php echo $l_continue?>"></td></tr>
</form>
</table></tr></td></table>
<?php
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_faqlist</a></div>";
?>