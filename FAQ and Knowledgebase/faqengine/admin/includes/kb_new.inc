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
		// Display empty form for entering kb article
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newkbarticle?> (1/2)</b></td></tr>
<form onSubmit="return checkform1();" <?php if($upload_avail) echo "ENCTYPE=\"multipart/form-data\""?> name="inputform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="selprog" value="">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_heading?>:</td><td><input class="faqeinput" type="text" name="heading" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_programm?>:</td>
<td>
<?php
	$firstarg=true;
	if($admin_rights<3)
	{
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr ";
		$firstarg=false;
	}
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterprog) && ($filterprog>=0))
		{
			if($firstarg)
			{
				$sql1.="where ";
				$firstarg=false;
			}
			else
				$sql1.="and ";
			$sql1.="prog.prognr=$filterprog ";
		}
		else if(isset($filterlang) && ($filterlang!="none"))
		{
			if($firstarg)
			{
				$sql1.="where ";
				$firstarg=false;
			}
			else
				$sql1.="and ";
			$sql1.="prog.language='$filterlang' ";
		}
	}
	$sql1.="order by prog.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql1.=", prog.programmname asc";
	else
		$sql1.=", prog.displaypos asc";
	if(!$result1 = faqe_db_query($sql1, $db)) {
		db_die("<tr class=\"errorrow\"><td>Unable to get programs from DB.");
	}
	if (!$temprow = faqe_db_fetch_array($result1))
	{
		echo "<a href=\"".do_url_session("program.php?mode=new?$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="programm" onchange="document.inputform.selprog.value=this.options[this.selectedIndex].value">
<option value="-1">???</option>
<?php
		do {
			$progname=display_encoded($temprow["programmname"]);
			$proglang=$temprow["language"];
			echo "<option value=\"".$temprow["prognr"]."\"";
			if(bittst($admedoptions,BIT_1) && isset($filterprog) && ($filterprog>=0) && ($filterprog=$temprow["prognr"]))
				echo " selected";
			echo ">";
			echo "$progname [$proglang]";
			echo "</option>";
		} while($temprow = faqe_db_fetch_array($result1));
		echo "</select>";
	}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_keywords?>:<br><span class="remark"><?php echo $l_keywordsnote?></span>
</td><td><textarea class="faqeinput" name="keywords" cols="<?php echo $textareascols?>" rows="<?php echo $textareasrows?>"></textarea></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_articletext?>:
</td><td><textarea class="faqeinput" name="article" cols="<?php echo $textareascols?>" rows="<?php echo $textareasrows?>"></textarea><br>
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
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="new2">
<input class="faqebutton" type="submit" value="<?php echo $l_continue?>"></td></tr>
</form>
</table></td></tr></table>
<?php
	echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_articlelist</a></div>";
?>