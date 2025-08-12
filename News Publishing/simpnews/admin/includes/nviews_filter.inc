<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(isset($sorting))
		echo "<input type=\"hidden\" name=\"sorting\" value=\"$sorting\">";
	if(isset($filtercat))
		echo "<input type=\"hidden\" name=\"filtercat\" value=\"$filtercat\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
	echo "<tr class=\"optionrow\"><td valign=\"middle\" align=\"center\">$l_filterbylang:";
	echo language_select2($filterlang,"filterlang","../language/");
	echo "&nbsp;&nbsp;";
	echo "<input type=\"submit\" class=\"snbutton\" name=\"submit\" value=\"$l_ok\">";
	echo "</td></tr></form></table>";
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="<?php echo filterlang?>" value="<?php echo $filterlang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(isset($sorting))
		echo "<input type=\"hidden\" name=\"sorting\" value=\"$sorting\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
	if(isset($filterlang))
		echo "<input type=\"hidden\" name=\"filterlang\" value=\"$filterlang\">";
	echo "<tr class=\"optionrow\"><td valign=\"middle\" align=\"center\">$l_filterbycat:";
	if($userdata["rights"]==2)
		$tmpsql="select cat.* from ".$tableprefix."_cat_adm ca, ".$tableprefix."_categories cat where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"];
	else
		$tmpsql="select * from ".$tableprefix."_categories";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("Could not connect to the database.".mysql_error());
	echo "<select name=\"filtercat\">";
	echo "<option value=\"-1\"";
	if(!isset($filtercat) || ($filtercat==-1))
		echo " selected";
	echo ">$l_nofilter</option>";
	if(($userdata["rights"]>2) || bittst($secsettings,BIT_10))
	{
		echo "<option value=\"0\"";
		if(isset($filtercat) && ($filtercat==0))
			echo " selected";
		echo ">$l_all</option>";
	}
	while($tmprow=mysql_fetch_array($tmpresult))
	{
		echo "<option value=\"".$tmprow["catnr"]."\"";
		if(isset($filtercat) && ($filtercat==$tmprow["catnr"]))
			echo " selected";
		echo ">".$tmprow["catname"]."</option>";
	}
	echo "</select>&nbsp;&nbsp;";
	echo "<input type=\"submit\" class=\"snbutton\" name=\"submit\" value=\"$l_ok\">";
	echo "</td></tr></form></table>";
?>
