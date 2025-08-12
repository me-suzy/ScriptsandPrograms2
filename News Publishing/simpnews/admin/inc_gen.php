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
$page_title=$l_include_generator;
$page="inc_gen";
require_once('./heading.php');
include_once('./includes/inc_gen.inc');
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($mode=="sngen")
	{
		$headerinclude="";
		$headerinclude2="";
		$includestatement="";
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_snincgen</td></tr>";
		if(isset($usevisitcookie))
		{
			$headerinclude.="<?php\n";
			if(isset($usevisitcookie))
			{
				$headerinclude.="\$cookiename=\"$cookiename\";\n";
				$headerinclude.="\$cookiedomain=\"$cookiedomain\";\n";
				$headerinclude.="include_once('".$path_simpnews."/newscookie.php');\n";
			}
			$headerinclude.="?>\n";
		}
		if(isset($usestyles))
		{
			$headerinclude2.="<?php\n";
			if(isset($usestyles))
				$headerinclude2.="include_once('".$path_simpnews."/snstyles.php');\n";
			$headerinclude2.="?>\n";
		}
		$includestatement.="<?php\n";
		if(!$headerinclude)
		{
			if($new_global_handling)
				$tmpval="true";
			else
				$tmpval="false";
			$includestatement.="\$new_global_handling=$tmpval;\n";
		}
		$includestatement.="include('".$path_simpnews."/snews.php');\n";
		$includestatement.="?>\n";
		echo "<form name=\"inputform\">";
		if($headerinclude)
		{
			echo "<tr class=\"optionrow\"><td align=\"left\" colspan=\"2\"><b>snews.php/an_inc.php/ev_inc.php</b></td></tr>";
			$headerinclude=do_htmlentities($headerinclude);
			echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_beforeanyoutput:";
			echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.headercode.focus();document.inputform.headercode.select();\"></td>";
			echo "<td><textarea name=\"headercode\" wrap=\"off\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$headerinclude</textarea>";
		}
		if($headerinclude2)
		{
			echo "<tr class=\"optionrow\"><td align=\"left\" colspan=\"2\"><b>snews.php/an_inc.php/ev_inc.php</b></td></tr>";
			$headerinclude2=do_htmlentities($headerinclude2);
			echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_codeforheader:";
			echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.headercode.focus();document.inputform.headercode2.select();\"></td>";
			echo "<td><textarea name=\"headercode2\" wrap=\"off\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$headerinclude2</textarea>";
		}
		echo "<tr class=\"optionrow\"><td align=\"left\" colspan=\"2\"><b>snews.php</b></td></tr>";
		$includestatement=do_htmlentities($includestatement);
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_codeforbody:";
		echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.bodycode.focus();document.inputform.bodycode.select();\"></td>";
		echo "<td><textarea name=\"bodycode\" wrap=\"off\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$includestatement</textarea>";
		echo "<tr class=\"optionrow\"><td align=\"left\" colspan=\"2\"><b>an_inc.php</b></td></tr>";
		$includestatement="";
		$includestatement.="<?php\n";
		$includestatement.="include('".$path_simpnews."/an_inc.php');\n";
		$includestatement.="?>\n";
		$includestatement=do_htmlentities($includestatement);
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_codeforbody:";
		echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.bodycode2.focus();document.inputform.bodycode2.select();\"></td>";
		echo "<td><textarea name=\"bodycode2\" wrap=\"off\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$includestatement</textarea>";
		echo "<tr class=\"optionrow\"><td align=\"left\" colspan=\"2\"><b>ev_inc.php</b></td></tr>";
		$includestatement="";
		$includestatement.="<?php\n";
		$includestatement.="include('".$path_simpnews."/ev_inc.php');\n";
		$includestatement.="?>\n";
		$includestatement=do_htmlentities($includestatement);
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_codeforbody:";
		echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.bodycode3.focus();document.inputform.bodycode3.select();\"></td>";
		echo "<td><textarea name=\"bodycode3\" wrap=\"off\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$includestatement</textarea>";
		echo "</form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_startover</a></div>";
		include('./trailer.php');
		exit;
	}
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_normalincludes</td></tr>";
	if($layoutid)
	{
		$sql="select * from ".$tableprefix."_layout where id='$layoutid' and lang='$ilang'";
		if(!$result = mysql_query($sql, $db))
			die("Could not connect to the database.");
		if(mysql_num_rows($result)<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_layoutnotdefined: $layoutid ($ilang)</td></tr>";
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
	}
	if($mode=="selnews")
	{
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"geninclude\">";
		echo "<input type=\"hidden\" name=\"inc_script\" value=\"$inc_script\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(isset($usevisitcookie))
			echo "<input type=\"hidden\" name=\"usevisitcookie\" value=\"1\">";
		if(isset($usestyles))
			echo "<input type=\"hidden\" name=\"usestyles\" value=\"1\">";
		if(isset($limitentries) && $limitentries)
			echo "<input type=\"hidden\" name=\"limitentries\" value=\"$limitentries\">";
		if(isset($limitdays) && $limitdays)
			echo "<input type=\"hidden\" name=\"limitdays\" value=\"$limitdays\">";
		if(isset($maxannounce) && $maxannounce)
			echo "<input type=\"hidden\" name=\"maxannounce\" value=\"$maxannounce\">";
		echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
		echo "<input type=\"hidden\" name=\"ilang\" value=\"$ilang\">";
		echo "<input type=\"hidden\" name=\"layoutid\" value=\"$layoutid\">";
		echo "<input type=\"hidden\" name=\"inc_type\" value=\"$inc_type\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\">";
		echo $l_script2include.":</td>";
		echo "<td>$inc_script</td>";
		echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_newsentry#:</td>";
		echo "<td><select name=\"newsnr\">";
		echo "<option value=\"0\">0</option>";
		$sql="select * from ".$tableprefix."_data where category=$catnr and lang='$ilang' and linknewsnr=0 order by newsnr desc";
		if(!$result = mysql_query($sql, $db))
			die("Could not connect to the database.");
		while($myrow=mysql_fetch_array($result))
		{
			echo "<option value=\"".$myrow["newsnr"]."\">".$myrow["newsnr"]."</option>";
		}
		echo "</select></td></tr>";
		echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_ok\"></td></tr></form>";
		echo "</table></td></tr></table>";
		include('./trailer.php');
		exit;
	}
	if($mode=="selannounce")
	{
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"geninclude\">";
		echo "<input type=\"hidden\" name=\"inc_script\" value=\"$inc_script\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(isset($usevisitcookie))
			echo "<input type=\"hidden\" name=\"usevisitcookie\" value=\"1\">";
		if(isset($usestyles))
			echo "<input type=\"hidden\" name=\"usestyles\" value=\"1\">";
		if(isset($limitentries) && $limitentries)
			echo "<input type=\"hidden\" name=\"limitentries\" value=\"$limitentries\">";
		if(isset($limitdays) && $limitdays)
			echo "<input type=\"hidden\" name=\"limitdays\" value=\"$limitdays\">";
		if(isset($maxannounce) && $maxannounce)
			echo "<input type=\"hidden\" name=\"maxannounce\" value=\"$maxannounce\">";
		echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
		echo "<input type=\"hidden\" name=\"ilang\" value=\"$ilang\">";
		echo "<input type=\"hidden\" name=\"layoutid\" value=\"$layoutid\">";
		echo "<input type=\"hidden\" name=\"inc_type\" value=\"$inc_type\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\">";
		echo $l_script2include.":</td>";
		echo "<td>$inc_script</td>";
		echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_announce#:</td>";
		echo "<td><select name=\"announcenr\">";
		echo "<option value=\"0\">0</option>";
		$sql="select * from ".$tableprefix."_announce where category=$catnr and lang='$ilang' order by entrynr desc";
		if(!$result = mysql_query($sql, $db))
			die("Could not connect to the database.");
		while($myrow=mysql_fetch_array($result))
		{
			echo "<option value=\"".$myrow["entrynr"]."\">".$myrow["entrynr"]."</option>";
		}
		echo "</select></td></tr>";
		echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_ok\"></td></tr></form>";
		echo "</table></td></tr></table>";
		include('./trailer.php');
		exit;
	}
	$includestatement="";
	$headerinclude="";
	$headerinclude2="";
	if($inc_type==0)
	{
		if(isset($usevisitcookie))
		{
			$headerinclude.="<?php\n";
			$headerinclude.="\$$langvar=\"$ilang\";\n";
			if(isset($usevisitcookie))
			{
				$headerinclude.="\$cookiename=\"$cookiename\";\n";
				if(isset($cookiedomain))
					$headerinclude.="\$cookiedomain=\"$cookiedomain\";\n";
				$headerinclude.="include_once('".$path_simpnews."/newscookie.php');\n";
			}
			$headerinclude.="?>\n";
		}
		if(isset($usestyles))
		{
			$headerinclude2.="<?php\n";
			$headerinclude2.="\$$langvar=\"$ilang\";\n";
			if(isset($usestyles))
				$headerinclude2.="include_once('".$path_simpnews."/snstyles.php');\n";
			$headerinclude2.="?>\n";
		}
		$includestatement.="<?php\n";
		if(!in_array($inc_script,$nocatsneeded))
			$includestatement.="\$category=$catnr;\n";
		if($layoutid)
			$includestatement.="\$layout=\"$layoutid\";\n";
		if(isset($limitentries) && $limitentries)
			$includestatement.="\$limitentries=$limitentries;\n";
		if(isset($limitdays) && $limitdays)
			$includestatement.="\$limitdays=$limitdays;\n";
		if(isset($maxannounce) && $maxannounce)
			$includestatement.="\$maxannounce=$maxannounce;\n";
		if(in_array($inc_script,$selnews_scripts))
			$includestatement.="\$newsnr=$newsnr;\n";
		if(in_array($inc_script,$selan_scripts))
			$includestatement.="\$announcenr=$announcenr;\n";
		if(isset($incsortorder))
			$includestatement.="\$sortorder=$incsortorder;\n";
		$includestatement.="include('".$path_simpnews."/".$inc_script."');\n";
		$includestatement.="?>\n";
	}
	else
	{
		if(isset($usevisitcookie) || isset($usestyles))
		{
			if(isset($usevisitcookie))
			{
				$headerinclude.="<!--#include virtual=\"";
				$headerinclude.=$url_simpnews;
				$headerinclude.="/newscookie.php";
				$headerinclude.="\" -->\n";
			}
			if(isset($usestyles))
			{
				$headerinclude.="<!--#include virtual=\"";
				$headerinclude.=$url_simpnews;
				$headerinclude.="/snstyles.php";
				$headerinclude.="\" -->";
			}
		}
		$includestatement.="<!-- #include virtual=\"";
		$includestatement.=$url_simpnews;
		$includestatement.="/".$inc_script;
		$includestatement.="?$langvar=$ilang";
		if(!in_array($inc_script,$nocatsneeded))
			$includestatement.="&category=$catnr";
		if($layoutid)
			$includestatement.="&layout=$layoutid";
		if(isset($limitentries) && $limitentries)
			$includestatement.="&limitentries=$limitentries";
		if(isset($limitdays) && $limitdays)
			$includestatement.="&limitdays=$limitdays";
		if(in_array($inc_script,$selnews_scripts))
			$includestatement.="&newsnr=$newsnr";
		if(in_array($inc_script,$selan_scripts))
			$includestatement.="&announcenr=$announcenr";
		if(isset($maxannounce) && $maxannounce)
			$includestatement.="&maxannounce=$maxannounce";
		if(isset($incsortorder))
			$includestatement.="&sortorder=$incsortorder";
		$includestatement.="\" -->";
	}
	echo "<form name=\"inputform\">";
	if($headerinclude)
	{
		$headerinclude=do_htmlentities($headerinclude);
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_beforeanyoutput:";
		echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.headercode.focus();document.inputform.headercode.select();\"></td>";
		echo "<td><textarea name=\"headercode\" wrap=\"off\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$headerinclude</textarea>";
	}
	if($headerinclude2)
	{
		$headerinclude2=do_htmlentities($headerinclude2);
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_codeforheader:";
		echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.headercode2.focus();document.inputform.headercode2.select();\"></td>";
		echo "<td><textarea name=\"headercode2\" wrap=\"off\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$headerinclude2</textarea>";
	}
	$includestatement=do_htmlentities($includestatement);
	echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_codeforbody:";
	echo "<br><input type=\"button\" value=\"$l_highlight_all\" class=\"snbutton\" onClick=\"javascript:document.inputform.bodycode.focus();document.inputform.bodycode.select();\"></td>";
	echo "<td><textarea name=\"bodycode\" wrap=\"virtual\" class=\"codesnippet\" cols=\"60\" rows=\"10\">$includestatement</textarea>";
	echo "</form></table></td></tr></table>";
	echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_startover</a></div>";
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if(!isset($inc_script))
	{
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_normalincludes</td></tr>";
		echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">";
		echo $l_script2include.":</td>";
		echo "<td><select name=\"inc_script\">";
		for($i=0;$i<count($includeable_scripts);$i++)
			echo "<option value=\"".$includeable_scripts[$i]."\">".$includeable_scripts[$i]."</option>";
		echo "</select></td></tr>";
		echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"submit\" class=\"snbutton\" value=\"$l_ok\"></td></tr>";
		echo "</form>";
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"sngen\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_snincgen</td></tr>";
		echo "<tr class=\"inputrow\"><td></td><td><input type=\"checkbox\" name=\"usevisitcookie\" value=\"1\"> $l_uselastvisitcookie</td></tr>";
		echo "<tr class=\"inputrow\"><td></td><td><input type=\"checkbox\" name=\"usestyles\" value=\"1\"> $l_usestyles</td></tr>";
		echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
		echo "<input type=\"submit\" name=\"sngen\" value=\"$l_generate\" class=\"snbutton\">";
		echo "</td></tr></form>";
		echo "</table></td></tr></table>";
		include('./trailer.php');
		exit;
	}
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_normalincludes</td></tr>";
	echo "<form method=\"post\" action=\"$act_script_url\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	if(in_array($inc_script,$selnews_scripts))
		echo "<input type=\"hidden\" name=\"mode\" value=\"selnews\">";
	else if(in_array($inc_script,$selan_scripts))
		echo "<input type=\"hidden\" name=\"mode\" value=\"selannounce\">";
	else
		echo "<input type=\"hidden\" name=\"mode\" value=\"geninclude\">";
	echo "<input type=\"hidden\" name=\"inc_script\" value=\"$inc_script\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\">";
	echo $l_script2include.":</td>";
	echo "<td>$inc_script</td>";
	echo "<tr class=\"inputrow\"><td align=\"right\">$l_include_type:</td><td>";
	echo "<input type=\"radio\" name=\"inc_type\" value=\"0\" checked> PHP<br>";
	echo "<input type=\"radio\" name=\"inc_type\" value=\"1\"> SHTML";
	echo "</td></tr>";
	echo "<tr class=\"inputrow\"><td align=\"right\">$l_language:</td><td>";
	echo language_select("","ilang","../language/");
	echo "</td></tr>";
	echo "<tr class=\"inputrow\"><td align=\"right\">$l_layout:</td>";
	echo "<td><select name=\"layoutid\"><option value=\"\">$l_deflayout</option>";
	$sql="select * from ".$tableprefix."_layout group by id";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
		echo "<option value=\"".$myrow["id"]."\">".$myrow["id"]."</option>";
	if(!in_array($inc_script,$nocatsneeded))
	{
		echo "<tr class=\"inputrow\"><td align=\"right\">$l_category:</td>";
		echo "<td><select name=\"catnr\">";
		echo "<option value=\"-1\">$l_all</option>";
		echo "<option value=\"0\" selected>$l_general</option>";
		$sql="select * from ".$tableprefix."_categories";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		while($myrow=mysql_fetch_array($result))
			echo "<option value=\"".$myrow["catnr"]."\">".$myrow["catname"]."</option>";
		echo "</td></tr>";
	}
	if(in_array($inc_script,$applet_scripts))
	{
		echo "<tr class=\"inputrow\"><td align=\"right\">$l_limitentries:</td>";
		echo "<td><input type=\"text\" class=\"sninput\" name=\"limitentries\" size=\"4\" maxlength=\"10\"></td></tr>";
		echo "<tr class=\"inputrow\"><td align=\"right\">$l_limitdays:</td>";
		echo "<td><input type=\"text\" class=\"sninput\" name=\"limitdays\" size=\"4\" maxlength=\"10\"></td></tr>";
	}
	if(in_array($inc_script,$limitentries_scripts))
	{
		echo "<tr class=\"inputrow\"><td align=\"right\">$l_limitentries:</td>";
		echo "<td><input type=\"text\" class=\"sninput\" name=\"limitentries\" size=\"4\" maxlength=\"10\"></td></tr>";
	}
	if(!in_array($inc_script,$selnews_scripts) && !in_array($inc_script,$selan_scripts) && !in_array($inc_script,$noanneeded))
	{
		echo "<tr class=\"inputrow\"><td align=\"right\">$l_maxannounce:</td>";
		echo "<td><input type=\"text\" class=\"sninput\" name=\"maxannounce\" size=\"4\" maxlength=\"10\" value=\"0\"></td></tr>";
	}
	if(in_array($inc_script,$sortorder_scripts))
	{
		echo "<tr class=\"inputrow\"><td align=\"right\">$l_sortorder:</td><td><select name=\"incsortorder\">";
		for($i=0;$i<count($l_incgen_sortorders);$i++)
			echo "<option value=\"$i\">".$l_incgen_sortorders[$i]."</option>";
		echo "</select></td></tr>";
	}
	echo "<tr class=\"inputrow\"><td></td><td><input type=\"checkbox\" name=\"usevisitcookie\" value=\"1\"> $l_uselastvisitcookie</td></tr>";
	echo "<tr class=\"inputrow\"><td></td><td><input type=\"checkbox\" name=\"usestyles\" value=\"1\"> $l_usestyles</td></tr>";
	echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_ok\"></td></tr></form>";
	echo "</table></td></tr></table>";
}
include('./trailer.php');
?>