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
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");

		if($admin_rights <3)
		{
			$sql2="select * from ".$tableprefix."_category_admins where (catnr=".$myrow["category"].") and (usernr=".$userdata["usernr"].")";
			if(!$result2 = faqe_db_query($sql2, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(!$tmprow = faqe_db_fetch_array($result2))
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				die("$l_functionnotallowed");
			}
		}
		$tmpsql = "select * from ".$tableprefix."_category where catnr=".$myrow["category"];
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$tmprow = faqe_db_fetch_array($tmpresult))
			$prognr=0;
		else
			$prognr=$tmprow["programm"];
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newkbarticle?> (1/2)</b></td></tr>
<form onSubmit="return checkform1();" name="inputform" method="post" action="kb.php">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="page_title" value="<?php echo do_htmlentities($l_transfer2kb2)?>">
<input type="hidden" name="backurl" value="<?php echo do_htmlentities("faq.php?$langvar=$act_lang")?>">
<input type="hidden" name="backurl_txt" value="<?php echo do_htmlentities($l_faqlist)?>">
<?php
		echo "<input type=\"hidden\" name=\"selprog\" value=\"".$prognr."\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
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
		die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
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
		if($prognr==$temprow["prognr"])
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
	$articletext = $questiontext."\r\n\r\n".$answertext;
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_keywords?>:<br>
<span class="remark"><?php echo $l_keywordsnote?></span>
</td><td><textarea class="faqeinput" name="keywords" cols="<?php echo $textareascols?>" rows="<?php echo $textareasrows?>"></textarea></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_articletext?>:
</td><td><textarea class="faqeinput" name="article" cols="<?php echo $textareascols?>" rows="<?php echo $textareascols?>"><?php echo $articletext?></textarea><br>
<?php display_bbcode_buttons($l_bbbuttons,"article")?>
<?php echo kb_addopts("qref","kbref","article")?>
</td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?><br>
<input type="checkbox" name="disablehtml" value="1"> <?php echo $l_disablehtml?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="new2">
<input class="faqebutton" type="submit" value="<?php echo $l_continue?>">
</td></tr>
</form>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("faq.php?$langvar=$act_lang")."\">$l_faqlist</a></div>";
?>