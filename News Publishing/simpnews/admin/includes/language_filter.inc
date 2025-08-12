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
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
	echo "<tr class=\"optionrow\"><td valign=\"middle\" align=\"center\">$l_filterbylang:";
	echo language_select2($filterlang,"filterlang","../language/");
	echo "&nbsp;&nbsp;";
	echo "<input type=\"submit\" class=\"snbutton\" name=\"submit\" value=\"$l_ok\">";
	echo "</td></tr></form></table>";
?>
