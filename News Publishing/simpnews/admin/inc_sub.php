<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_subscription_generator;
require_once('./heading.php');
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(isset($mode))
{
	if($mode=="masssub")
	{
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_masssub</td></tr>";
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"massgeninc\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_language:</td><td>";
		echo language_select("","ilang","language/");
		echo "</td></tr>";
		echo "<tr class=\"inputrow\"><td align=\"right\">$l_layout:</td>";
		echo "<td><select name=\"layoutid\"><option value=\"\">$l_deflayout</option>";
		$sql="select * from ".$tableprefix."_layout group by id";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		while($myrow=mysql_fetch_array($result))
			echo "<option value=\"".$myrow["id"]."\">".$myrow["id"]."</option>";
		echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\" valign=\"$l_top\">$l_categories:</td>";
		$sql="select * from ".$tableprefix."_categories where excludefromnewsletter=0 order by catname asc";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		echo "<td>";
		while($myrow=mysql_fetch_array($result))
			echo "<input type=\"checkbox\" name=\"subcat[]\" value=\"".$myrow["catnr"]."\">".$myrow["catname"]."<br>";
		echo "</td></tr>";
		echo "<tr class=\"inputrow\"><td align=\"right\">$l_tablewidth:</td>";
		echo "<td><input class=\"sninput\" name=\"tablewidth\" size=\"5\" maxlength=\"5\" value=\"80%\"></td></tr>";
		echo "<tr class=\"inputrow\"><td align=\"right\">$l_tablealign:</td>";
		echo "<td><select name=\"tablealign\">";
		for($i=0;$i<count($l_alignments);$i++)
			echo "<option value=\"".$l_alignments[$i]."\">".$l_alignments[$i]."</option>";
		echo "</select></td></tr>";
		echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_ok\">";
		echo "</td></tr></form>";
		echo "</table></td></tr></table>";
	}
	if($mode=="geninclude")
	{
		include_once("language/subbox_".$ilang.".php");
		$substatement="<!-- --------- Start of subscription code --------- -->\n";
		$substatement.="<table width=\"".$tablewidth."\" align=\"".$tablealign."\">\n";
		$substatement.="<form method=\"post\" action=\"".$url_simpnews."/subscription.php\">\n";
		$substatement.="<input type=\"hidden\" name=\"".$langvar."\" value=\"".$ilang."\">\n";
		$substatement.="<input type=\"hidden\" name=\"mode\" value=\"subscribe\">\n";
		if(isset($layoutid) && $layoutid)
			$substatement.="<input type=\"hidden\" name=\"layout\" value=\"".$layoutid."\">\n";
		$substatement.="<tr><td align=\"right\" width=\"30%\">".$l_sub_email.":</td>\n";
		$substatement.="<td><input type=\"text\" name=\"email\" size=\"40\" maxlength=\"240\"></td></tr>\n";
		if($nlformat==0)
		{
			$substatement.="<tr><td align=\"right\" valign=\"top\">".$l_sub_mailtype.":</td><td>\n";
			for($i=0;$i<count($l_sub_mailtypes);$i++)
			{
				$substatement.="<input type=\"radio\" name=\"emailtype\" value=\"$i\"";
				if($i==0)
					$substatement.=" checked";
				$substatement.="> ".$l_sub_mailtypes[$i];
				if($i<count($l_sub_mailtypes)-1)
					$substatement.="<br>";
				$substatement.="\n";
			}
			$substatement.="</td></tr>\n";
		}
		else
			$substatement.="<input type=\"hidden\" name=\"emailtype\" value=\"".($nlformat-1)."\">\n";
		$substatement.="<input type=\"hidden\" name=\"newscat\" value=\"".$catnr."\">\n";
		$substatement.="<td align=\"center\" colspan=\"2\"><input type=\"submit\" value=\"".$l_sub_subscribe."\">\n";
		$substatement.="</td></tr></form></table>\n";
		$substatement.="<!-- --------- End of subscription code --------- -->\n";
		$unsubstatement="<!-- --------- Start of unsubscription code --------- -->\n";
		$unsubstatement.="<table width=\"".$tablewidth."\" align=\"".$tablealign."\">\n";
		$unsubstatement.="<form method=\"post\" action=\"".$url_simpnews."/subscription.php\">\n";
		$unsubstatement.="<input type=\"hidden\" name=\"".$langvar."\" value=\"".$ilang."\">\n";
		$unsubstatement.="<input type=\"hidden\" name=\"mode\" value=\"unsubscribe\">\n";
		$unsubstatement.="<tr><td align=\"right\" width=\"30%\">".$l_sub_email.":</td>\n";
		$unsubstatement.="<td><input type=\"text\" name=\"email\" size=\"40\" maxlength=\"240\"></td></tr>\n";
		if(isset($layoutid) && $layoutid)
			$unsubstatement.="<input type=\"hidden\" name=\"layout\" value=\"".$layoutid."\">\n";
		$unsubstatement.="<input type=\"hidden\" name=\"newscat\" value=\"".$catnr."\">\n";
		$unsubstatement.="<td align=\"center\" colspan=\"2\"><input type=\"submit\" value=\"".$l_sub_unsubscribe."\">\n";
		$unsubstatement.="</td></tr></form></table>\n";
		$unsubstatement.="<!-- --------- End of unsubscription code --------- -->\n";
		echo "<form name=\"inputform\">";
		$substatement=do_htmlentities($substatement);
		$unsubstatement=do_htmlentities($unsubstatement);
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_codeforsubbox:";
		echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.subcode.focus();document.inputform.subcode.select();\"></td>";
		echo "<td><textarea name=\"subcode\" wrap=\"off\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$substatement</textarea>";
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_codeforunsubbox:";
		echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.unsubcode.focus();document.inputform.unsubcode.select();\"></td>";
		echo "<td><textarea name=\"unsubcode\" wrap=\"off\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$unsubstatement</textarea>";
		echo "</form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_startover</a></div>";
	}
	if($mode=="massgeninc")
	{
		include_once("language/subbox_".$ilang.".php");
		$substatement="<!-- --------- Start of subscription code --------- -->\n";
		$substatement.="<table width=\"".$tablewidth."\" align=\"".$tablealign."\">\n";
		$substatement.="<form method=\"post\" action=\"".$url_simpnews."/masssub.php\">\n";
		$substatement.="<input type=\"hidden\" name=\"".$langvar."\" value=\"".$ilang."\">\n";
		$substatement.="<input type=\"hidden\" name=\"mode\" value=\"subscribe\">\n";
		if(isset($layoutid) && $layoutid)
			$substatement.="<input type=\"hidden\" name=\"layout\" value=\"".$layoutid."\">\n";
		$substatement.="<tr><td align=\"right\" width=\"30%\">".$l_sub_email.":</td>\n";
		$substatement.="<td><input type=\"text\" name=\"email\" size=\"40\" maxlength=\"240\"></td></tr>\n";
		$substatement.="<tr><td align=\"right\" valign=\"top\">".$l_sub_mailtype.":</td><td>\n";
		for($i=0;$i<count($l_sub_mailtypes);$i++)
		{
			$substatement.="<input type=\"radio\" name=\"emailtype\" value=\"$i\"";
			if($i==0)
				$substatement.=" checked";
			$substatement.="> ".$l_sub_mailtypes[$i];
			if($i<count($l_sub_mailtypes)-1)
				$substatement.="<br>";
			$substatement.="\n";
		}
		$substatement.="</td></tr>\n";
		$substatement.="<tr><td align=\"right\" width=\"30%\">".$l_categories.":</td><td>\n";
		for($i=0;$i<count($subcat);$i++)
		{
			$sql="select * from ".$tableprefix."_categories where catnr=".$subcat[$i];
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			if($myrow=mysql_fetch_array($result))
				$catname=$myrow["catname"];
			else
				$catname=$l_unknown;
			$substatement.="<input type=\"checkbox\" name=\"newscat[]\" value=\"".$subcat[$i]."\"> $catname<br>\n";
		}
		$substatement.="</td></tr>";
		$substatement.="<td align=\"center\" colspan=\"2\"><input type=\"submit\" value=\"".$l_sub_subscribe."\">\n";
		$substatement.="</td></tr></form></table>\n";
		$substatement.="<!-- --------- End of subscription code --------- -->\n";
		echo "<form name=\"inputform\">";
		$substatement=do_htmlentities($substatement);
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_codeforsubbox:";
		echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.subcode.focus();document.inputform.subcode.select();\"></td>";
		echo "<td><textarea name=\"subcode\" wrap=\"off\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$substatement</textarea>";
		echo "</form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_startover</a></div>";
	}
}
else
{
	echo "<form method=\"post\" action=\"$act_script_url\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"geninclude\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_language:</td><td>";
	echo language_select("","ilang","language/");
	echo "</td></tr>";
	echo "<tr class=\"inputrow\"><td align=\"right\">$l_layout:</td>";
	echo "<td><select name=\"layoutid\"><option value=\"\">$l_deflayout</option>";
	$sql="select * from ".$tableprefix."_layout group by id";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
		echo "<option value=\"".$myrow["id"]."\">".$myrow["id"]."</option>";
	echo "<tr class=\"inputrow\"><td align=\"right\">$l_category:</td>";
	echo "<td><select name=\"catnr\">";
	echo "<option value=\"0\" selected>$l_all</option>";
	$sql="select * from ".$tableprefix."_categories where excludefromnewsletter=0 order by catname asc";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
		echo "<option value=\"".$myrow["catnr"]."\">".$myrow["catname"]."</option>";
	echo "</td></tr>";
	echo "<tr class=\"inputrow\"><td align=\"right\">$l_newsletterformat:</td><td>";
	echo "<select name=\"nlformat\">";
	for($i=0;$i<count($l_newsletterformats);$i++)
		echo "<option value=\"$i\">".$l_newsletterformats[$i]."</option>";
	echo "</select></td></tr>";
	echo "<tr class=\"inputrow\"><td align=\"right\">$l_tablewidth:</td>";
	echo "<td><input class=\"sninput\" name=\"tablewidth\" size=\"5\" maxlength=\"5\" value=\"80%\"></td></tr>";
	echo "<tr class=\"inputrow\"><td align=\"right\">$l_tablealign:</td>";
	echo "<td><select name=\"tablealign\">";
	for($i=0;$i<count($l_alignments);$i++)
		echo "<option value=\"".$l_alignments[$i]."\">".$l_alignments[$i]."</option>";
	echo "</select></td></tr>";
	echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_ok\">";
	echo "</td></tr></form>";
	echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
	echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=masssub")."\" class=\"actionlink\">$l_masssub</a></td></tr>";
	echo "</table></td></tr></table>";
}
include('./trailer.php');
?>