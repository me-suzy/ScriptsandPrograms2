<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(!isset($filterlang))
	$filterlang="none";
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefaqfilters==1)
		echo "<input type=\"hidden\" name=\"storefaqfilter\" value=\"1\">";
	if(isset($addfilters))
		echo "<input type=\"hidden\" name=\"addfilters\" value=\"$addfilters\">";
	if(isset($sorting))
		echo "<input type=\"hidden\" name=\"sorting\" value=\"$sorting\">";
?>
<tr><td align="right" width="50%" valign="middle"><?php echo $l_langfilter?>:</td>
<td align="left" width="30%"><select name="filterlang">
<option value="none" <?php if($filterlang=="none") echo "selected"?>><?php echo $l_nofilter?></option>
<?php
	$langs=language_list("../language");
	for($i=0;$i<count($langs);$i++)
	{
		echo "<option value=\"".$langs[$i]."\"";
		if(isset($filterlang))
		{
			if($filterlang==$langs[$i])
				echo " selected";
		}
		echo ">";
		echo $langs[$i];
		echo "</option>";
	}
?>
</select></td><td align="left"><input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
