<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(!isset($filtercat))
	$filtercat=-1;
if(!isset($filterlang))
	$filterlang="none";
	include('./includes/prog_filterbox.inc');
	if($admin_rights<3)
		$sql1 = "select cat.*, prog.programmname, prog.language from ".$tableprefix."_category cat, ".$tableprefix."_programm prog, ".$tableprefix."_category_admins ca where prog.prognr = cat.programm and ca.usernr=$act_usernr and cat.catnr=ca.catnr ";
	else
		$sql1 = "select cat.*, prog.programmname, prog.language from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm ";
	if(isset($filterlang) && ($filterlang!="none"))
		$sql1.= "and prog.language='$filterlang' ";
	if(isset($filterprog) && ($filterprog>-1))
		$sql1.="and prog.prognr=$filterprog ";
	$sql1.=" order by prog.language, prog.displaypos, cat.displaypos";
	if(!$result1 = faqe_db_query($sql1, $db)) {
		die("Could not connect to the database (3).".faqe_db_error());
	}
	if ($temprow = faqe_db_fetch_array($result1))
	{
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefaqfilters==1)
		echo "<input type=\"hidden\" name=\"storefaqfilter\" value=\"1\">";
	if(isset($filterlang))
		echo "<input type=\"hidden\" name=\"filterlang\" value=\"$filterlang\">";
	if(isset($filterprog))
		echo "<input type=\"hidden\" name=\"filterprog\" value=\"$filterprog\">";
	if(isset($sorting))
		echo "<input type=\"hidden\" name=\"sorting\" value=\"$sorting\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<tr><td align="right" width="50%" valign="middle"><?php echo $l_catfilter?>:</td>
<td align="left" width="30%"><select name="filtercat">
<option value="-1" <?php if($filtercat==-1) echo "selected"?>><?php echo $l_nofilter?></option>
<?php
	do {
		$progname=display_encoded($temprow["programmname"]);
		$proglang=$temprow["language"];
		$catname=display_encoded($temprow["categoryname"]);
		echo "<option value=\"".$temprow["catnr"]."\"";
		if(isset($filtercat))
		{
			if($filtercat==$temprow["catnr"])
				echo " selected";
		}
		echo ">";
		echo "$catname | $progname [$proglang]";
		echo "</option>";
	} while($temprow = faqe_db_fetch_array($result1));
?>
</select></td><td align="left"><input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
	}
	include('./includes/language_filterbox.inc');
	if($admin_rights<3)
	{
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefaqfilters==1)
		echo "<input type=\"hidden\" name=\"storefaqfilter\" value=\"1\">";
	if(isset($filterlang))
		echo "<input type=\"hidden\" name=\"filterlang\" value=\"$filterlang\">";
	if(isset($filterprog))
		echo "<input type=\"hidden\" name=\"filterprog\" value=\"$filterprog\">";
	if(isset($sorting))
		echo "<input type=\"hidden\" name=\"sorting\" value=\"$sorting\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<tr><td align="right" width="50%" valign="middle"><?php echo $l_display?>:</td>
<td align="left" width="30%"><select name="hideunassigned">
<option value="0" <?php if($hideunassigned==0) echo "selected"?>><?php echo $l_all?></option>
<option value="1" <?php if($hideunassigned==1) echo "selected"?>><?php echo $l_onlyeditable?></option>
</select></td><td align="left"><input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
	}
?>
