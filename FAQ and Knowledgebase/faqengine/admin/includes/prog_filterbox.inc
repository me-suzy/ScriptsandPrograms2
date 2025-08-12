<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(!isset($filterprog))
	$filterprog=-1;
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr ";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog ";
	if(isset($filterlang) && ($filterlang!="none"))
	{
		if($admin_rights>=3)
			$sql1.="where ";
		else
			$sql1.="and ";
		$sql1.= "prog.language='$filterlang' ";
	}
	$sql1.="order by prog.language, prog.displaypos";
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
	if(isset($addfilters))
		echo "<input type=\"hidden\" name=\"addfilters\" value=\"$addfilters\">";
	if(isset($sorting))
		echo "<input type=\"hidden\" name=\"sorting\" value=\"$sorting\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<tr><td align="right" width="50%" valign="middle"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="filterprog">
<option value="-1" <?php if($filterprog==-1) echo "selected"?>><?php echo $l_nofilter?></option>
<?php
	do {
		$progname=display_encoded($temprow["programmname"]);
		$proglang=$temprow["language"];
		echo "<option value=\"".$temprow["prognr"]."\"";
		if(isset($filterprog))
		{
			if($filterprog==$temprow["prognr"])
				echo " selected";
		}
		echo ">";
		echo "$progname [$proglang]";
		echo "</option>";
	} while($temprow = faqe_db_fetch_array($result1));
?>
</select></td><td align="left"><input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
	}
?>