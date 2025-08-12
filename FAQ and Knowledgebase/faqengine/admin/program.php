<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_programm_title;
$page="program";
$uses_bbcode=true;
require_once('./heading.php');
include_once("./includes/bbcode_buttons.inc");
if(!isset($storefaqfilter) && ($admstorefaqfilters==1))
{
	$admcookievals="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	else
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	if($admcookievals)
	{
			if(faqe_array_key_exists($admcookievals,"prog_filterlang"))
				$filterlang=$admcookievals["prog_filterlang"];
			if(faqe_array_key_exists($admcookievals,"prog_sorting"))
				$sorting=$admcookievals["prog_sorting"];
	}
}
if(!isset($sorting))
	$sorting=11;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="display")
	{
		if($admin_rights < 1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_programm where (prognr=$input_prognr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
		$descriptiontext = stripslashes($myrow["description"]);
		$descriptiontext = undo_htmlspecialchars($descriptiontext);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displayprogs?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_progname?>:</td>
<td><?php echo undo_html_ampersand(do_htmlentities(stripslashes($myrow["programmname"])))?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_id?>:</td>
<td><?php echo $myrow["progid"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td>
<?php echo $myrow["language"]?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_description?>:</td>
<td align="left"><?php echo $descriptiontext?></td>
<tr class="displayrow"><td align="right"><?php echo $l_supportedos?>:</td>
<td valign="top">
<?php
	$sql = "SELECT o.osname, o.osnr FROM ".$tableprefix."_os o, ".$tableprefix."_prog_os po WHERE po.prognr = '$input_prognr' AND o.osnr = po.osnr order by o.osnr";
	if(!$r = faqe_db_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
	if ($row = faqe_db_fetch_array($r))
	{
		 do {
		    echo display_encoded($row["osname"])."<BR>";
		 } while($row = faqe_db_fetch_array($r));
	}
	else
		echo "$l_noos<br>";
?>
</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_admins?>:</td>
<td>
<?php
	$sql = "SELECT u.username, u.usernr FROM ".$tableprefix."_admins u, ".$tableprefix."_programm_admins f WHERE f.prognr = '$input_prognr' AND u.usernr = f.usernr order by u.username";
	if(!$r = faqe_db_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
	if ($row = faqe_db_fetch_array($r))
	{
		 do {
		    echo $row["username"]."<BR>";
		 } while($row = faqe_db_fetch_array($r));
	}
	else
		echo "$l_noadmins<br>";
?>
</td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_news_settings?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_nntpserver?>:</td>
<td><?php echo $myrow["nntpserver"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_domain?>:</td>
<td><?php echo $myrow["newsdomain"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_newsgroup?>:</td>
<td><?php echo $myrow["newsgroup"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_newssubject?>:</td>
<td><?php echo $myrow["newssubject"]?></td></tr>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_proglist</a></div>";
	}
	// Page called with some special mode
	if($mode=="new")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		// Display empty form for entering programm
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newprogramm?></b></td></tr>
<form name="inputform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_progname?>:</td><td><input class="faqeinput" type="text" name="programmname" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_id?>:</td><td><input class="faqeinput" type="text" name="progid" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td>
<?php print language_select($default_lang, "proglang", "../language");?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_description?>:</td>
<td align="left"><textarea class="faqeinput" name="description" cols="40" rows="6"></textarea>
<br>
<?php display_bbcode_buttons($l_bbbuttons,"description",false,false,false)?>
</td>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_supportedos?>:</td>
<td>
<SELECT NAME="input_os[]" size="5" multiple>
<?php
		$sql = "SELECT osnr, osname FROM ".$tableprefix."_os ORDER BY osnr";
		if(!$r = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	    	if($row = faqe_db_fetch_array($r))
	    	{
			do {
				echo "<OPTION VALUE=\"".$row["osnr"]."\" >".display_encoded($row["osname"])."</OPTION>\n";
			} while($row = faqe_db_fetch_array($r));
		}
		else {
			echo "<OPTION VALUE=\"0\">$l_none</OPTION>\n";
		}
		echo "</select>";
		echo "</td></tr>";
		if($admin_rights>2)
		{
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_admins?>:</td>
<td>
<SELECT NAME="mods[]" size="5" multiple>
<?php
			$sql = "SELECT usernr, username FROM ".$tableprefix."_admins WHERE rights > 1 ORDER BY username";
			if(!$r = faqe_db_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($row = faqe_db_fetch_array($r))
			{
				do {
					echo "<OPTION VALUE=\"$row[usernr]\" >$row[username]</OPTION>\n";
				} while($row = faqe_db_fetch_array($r));
			}
			else {
				echo "<OPTION VALUE=\"0\">$l_none</OPTION>\n";
			}
			echo "</select>";
			echo "</td></tr>";
		}
		else
			echo "<input type=\"hidden\" name=\"mods[]\" value=\"$act_usernr\">";
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_htmlmailtype?>:</td><td>
<input type="radio" name="htmlmailtype" value="0" checked>#1<br>
<input type="radio" name="htmlmailtype" value="1">#2</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="cansubscribe" value="1" checked>
<?php echo $l_subscriptionavail?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_news_settings?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_nntpserver?>:</td><td><input class="faqeinput" type="text" name="nntpserver" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_domain?>:</td><td><input class="faqeinput" type="text" name="newsdomain" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_newsgroup?>:</td><td><input class="faqeinput" type="text" name="newsgroup" size="40" maxlength="250"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_newssubject?>:</td><td><input class="faqeinput" type="text" name="newssubject" size="40" maxlength="80"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add">
<input class="faqebutton" type="submit" value="<?php echo $l_add?>">
&nbsp;&nbsp;<input class="faqebutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_proglist?></a></div>
<?php
	}
	if($mode=="reindex")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_proglist</a></div>";
			include('./trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_programm";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if($myrow=faqe_db_fetch_array($result))
		{
			do{
				$tempsql="select * from ".$tableprefix."_category where programm=".$myrow["prognr"];
				if(!$tempresult = faqe_db_query($tempsql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				$catcount=faqe_db_num_rows($tempresult);
				$updatesql="update ".$tableprefix."_programm set numcats=$catcount where prognr=".$myrow["prognr"];
				if(!$updateresult = faqe_db_query($updatesql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			}while($myrow=faqe_db_fetch_array($result));
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_progreindexed";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_proglist</a></div>";
	}
	if($mode=="add")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		// Add new programm to database
		$errors=0;
		if(!$proglang)
			$proglang="";
		if(!$programmname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogname</td></tr>";
			$errors=1;
		}
		if(!$progid)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noid</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(!isset($local_urlautoencode))
				$urlautoencode=0;
			else
				$urlautoencode=1;
			if(!isset($local_enablespcode))
				$enablespcode=0;
			else
				$enablespcode=1;
			if(isset($preview))
			{
				$displaydescription="";
				if($description)
				{
					$displaydescription=stripslashes($description);
					if($urlautoencode==1)
						$dispalydescription = make_clickable($displaydescription);
					if($enablespcode==1)
						$displaydescription = bbencode($displaydescription);
					$displaydescription = do_htmlentities($displaydescription);
					$displaydescription = str_replace("\n", "<BR>", $displaydescription);
					$displaydescription = undo_htmlspecialchars($displaydescription);
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newprogramm?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_progname?>:</td><td><?php echo $programmname?><input type="hidden" name="programmname" value="<?php echo $programmname?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_id?>:</td><td><?php echo $progid?><input type="hidden" name="progid" value="<?php echo $progid?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td><?php echo $proglang?><input type="hidden" name="proglang" value="<?php echo $proglang?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_description?>:</td><td><?php echo $displaydescription?><input type="hidden" name="description" value="<?php echo $description?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_supportedos?>:</td><td>
<?php
				if(isset($input_os))
				{
					while(list($null, $local_os) = each($_POST["input_os"]))
					{
						$os_query = "SELECT * from ".$tableprefix."_os where osnr=$local_os";
						if(!$os_result=faqe_db_query($os_query, $db))
							die("<tr class=\"errorrow\"><td>Unable to connect to database.");
						if($os_row=faqe_db_fetch_array($os_result))
						{
							echo $os_row["osname"];
							echo "<input type=\"hidden\" name=\"input_os[]\" value=\"$local_os\"><br>";
						}
					}
				}
?>
</td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_admins?>:</td><td>
<?php
				if(isset($mods))
				{
					while(list($null, $local_mod) = each($_POST["mods"]))
					{
						$mod_query = "SELECT * from ".$tableprefix."_admins where usernr=$local_mod";
	    			   		if(!$mod_result=faqe_db_query($mod_query, $db))
							die("<tr class=\"errorrow\"><td>Unable to connect to database.");
						if($mod_row=faqe_db_fetch_array($mod_result))
						{
							echo $mod_row["username"];
							echo "<input type=\"hidden\" name=\"mods[]\" value=\"$local_mod\"><br>";
						}

					}
				}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_htmlmailtype?>:</td><td>
#<?php echo $htmlmailtype+1?></td></tr>
<input type="hidden" name="htmlmailtype" value="<?php echo $htmlmailtype?>">
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_news_settings?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_nntpserver?>:</td>
<td><?php echo $nntpserver?><input type="hidden" name="nntpserver" value="<?php echo $nntpserver?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_domain?>:</td>
<td><?php echo $newsdomain?><input type="hidden" name="newsdomain" value="<?php echo $newsdomain?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_newsgroup?>:</td>
<td><?php echo $newsgroup?><input type="hidden" name="newsgroup" value="<?php echo $newsgroup?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_newssubject?>:</td>
<td><?php echo $newssubject?><input type="hidden" name="newssubject" value="<?php echo $newssubject?>"></td></tr>
<?php
if(isset($cansubscribe))
	echo "<input type=\"hidden\" name=\"cansubscribe\" value=\"1\">";
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="faqebutton" type="submit" value="<?php echo $l_enter?>">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="add">
</td></tr></form></table></td></tr></table>
<?php
			}
			else
			{
				if($description)
				{
					$description=stripslashes($description);
					if($urlautoencode==1)
						$description = make_clickable($description);
					if($enablespcode==1)
						$description = bbencode($description);
					$description = do_htmlentities($description);
					$description = str_replace("\n", "<BR>", $description);
					$description=addslashes($description);
				}
				$sql = "select max(displaypos) as newdisplaypos from ".$tableprefix."_programm where language='$proglang'";
				if(!$result = faqe_db_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to add programm to database.");
				if($myrow=faqe_db_fetch_array($result))
					$displaypos=$myrow["newdisplaypos"]+1;
				else
					$displaypos=1;
				if(isset($cansubscribe))
					$subscriptionavail=1;
				else
					$subscriptionavail=0;
				$programmname=addslashes($programmname);
				$sql = "INSERT INTO ".$tableprefix."_programm (programmname, numcats, progid, language, newsgroup, newssubject, nntpserver, newsdomain, description, displaypos, htmlmailtype, subscriptionavail) ";
				$sql .="VALUES ('$programmname', 0, '$progid', '$proglang', '$newsgroup', '$newssubject', '$nntpserver', '$newsdomain', '$description', $displaypos, $htmlmailtype, $subscriptionavail)";
				if(!$result = faqe_db_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to add programm to database.");
				$prognr = faqe_db_insert_id($db);
				if(isset($mods))
				{
	    				while(list($null, $local_mod) = each($_POST["mods"]))
	    				{
						$mod_query = "INSERT INTO ".$tableprefix."_programm_admins (prognr, usernr) VALUES ('$prognr', '$local_mod')";
	    				   	if(!faqe_db_query($mod_query, $db))
							die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				if(isset($input_os))
				{
					while(list($null, $local_os) = each($_POST["input_os"]))
					{
						$os_query = "INSERT INTO ".$tableprefix."_prog_os (prognr, osnr) VALUES ('$prognr', '$local_os')";
	    			   		if(!faqe_db_query($os_query, $db))
							die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_progadded";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_newprogramm</a></div>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_proglist</a></div>";
			}
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="delete")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(isset($cat_action))
		{
			$countsql = "select count(catnr), sum(numfaqs) from ".$tableprefix."_category where (programm=$input_prognr)";
			if(!$result = faqe_db_query($countsql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			if ($temprow = faqe_db_fetch_array($result))
			{
				$catcount=$temprow["count(catnr)"];
				$faqcount=$temprow["sum(numfaqs)"];
			}
			else
			{
				$catcount=0;
				$faqcount=0;
			}
			if($cat_action=="del")
			{
				$tempsql = "select * from ".$tableprefix."_category where (programm=$input_prognr)";
				if(!$result = faqe_db_query($tempsql, $db))
					die("<tr class=\"errorrow\"><td>Unable to connect to database.");
				if ($temprow = faqe_db_fetch_array($result))
				{
					do{
						$act_cat=$temprow["catnr"];
						$deletesql = "delete from ".$tableprefix."_subcategory where (category=$act_cat)";
						$success = faqe_db_query($deletesql,$db);
						if (!$success)
							die("<tr class=\"errorrow\"><td>$l_cantdelete $l_subcategory");
						$deletesql = "delete from ".$tableprefix."_data where (category=$act_cat)";
						$success = faqe_db_query($deletesql,$db);
						if (!$success)
							die("<tr class=\"errorrow\"><td>$l_cantdelete $l_faq.");
						$deletesql = "delete from ".$tableprefix."_category_admins where (catnr=$act_cat)";
						$success = faqe_db_query($deletesql,$db);
						if (!$success)
							die("<tr class=\"errorrow\"><td>$l_cantdelete.");
					}while($temprow = faqe_db_fetch_array($result));
				}
				$deletesql = "delete from ".$tableprefix."_category where (programm=$input_prognr)";
				$success = faqe_db_query($deletesql,$db);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete $l_category.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "<i>$faqcount</i> $l_faq $l_in <i>$catcount</i> $l_categories $l_deleted<br></td></tr>";
			}
			if($cat_action=="move")
			{
				if($new_prog>0)
				{
					$movesql = "update ".$tableprefix."_category set programm=$new_prog where (programm=$input_prognr)";
					$success = faqe_db_query($movesql,$db);
					if (!$success)
						die("<tr class=\"errorrow\"><td>$l_cantmove.");
					$sql = "UPDATE ".$tableprefix."_programm SET numcats = numcats + $catcount WHERE (prognr = $new_prog)";
					@faqe_db_query($sql, $db);
					echo "<tr class=\"displayrow\" align=\"center\"><td>";
					echo "<i>$faqcount</i> $l_faq $l_in <i>$catcount</i> $l_categories $l_moved<br></td></tr>";
				}
			}
		}
		if(isset($kbcat_action))
		{
			$kbcount=0;
			$catcount=0;
			$countsql = "select count(catnr) from ".$tableprefix."_kb_cat where (programm=$input_prognr)";
			if(!$result = faqe_db_query($countsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			if ($countrow = faqe_db_fetch_array($result))
			{
				$catcount=$countrow["count(catnr)"];
				$tmpsql="select count(articlenr) from ".$tableprefix."_kb_cat kbc, ".$tableprefix."_kb_articles kba where (kbc.programm=$input_prognr) and (kba.category=kbc.catnr)";
				if(!$tmpresult = faqe_db_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Unable to connect to database.");
				if($tmprow=faqe_db_fetch_array($tmpresult))
					$kbcount+=$tmprow["count(articlenr)"];
			}
			$tmpsql="select count(articlenr) from ".$tableprefix."_kb_articles where programm=$input_prognr and category=0";
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			if($tmprow=faqe_db_fetch_array($tmpresult))
				$kbcount+=$tmprow["count(articlenr)"];
			if($kbcat_action=="del")
			{
				$tempsql = "select * from ".$tableprefix."_kb_cat where (programm=$input_prognr)";
				if(!$result = faqe_db_query($tempsql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
				if ($temprow = faqe_db_fetch_array($result))
				{
					do{
						$act_cat=$temprow["catnr"];
						$deletesql = "delete from ".$tableprefix."_kb_subcat where (category=$act_cat)";
						$success = faqe_db_query($deletesql,$db);
						if (!$success)
							die("<tr class=\"errorrow\"><td>$l_cantdelete $l_subcategory");
						$deletesql = "delete from ".$tableprefix."_kb_articles where (category=$act_cat)";
						$success = faqe_db_query($deletesql,$db);
						if (!$success)
							die("<tr class=\"errorrow\"><td>$l_cantdelete $l_faq.");
					}while($temprow = faqe_db_fetch_array($result));
				}
				$deletesql = "delete from ".$tableprefix."_kb_cat where (programm=$input_prognr)";
				$success = faqe_db_query($deletesql,$db);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete $l_category.");
				$deletesql = "delete from ".$tableprefix."_kb_articles where (programm=$input_prognr)";
				$success = faqe_db_query($deletesql,$db);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "<i>$kbcount</i> $l_articles";
				if($catcount>0)
					echo " $l_in <i>$catcount</i> $l_categories";
				echo " $l_deleted<br></td></tr>";
			}
			if($kbcat_action=="move")
			{
				if($new_prog>0)
				{
					$movesql = "update ".$tableprefix."_kb_cat set programm=$kb_new_prog where (programm=$input_prognr)";
					$success = faqe_db_query($movesql,$db);
					if (!$success)
						die("<tr class=\"errorrow\"><td>$l_cantmove.");
					$movesql = "update ".$tableprefix."_kb_articles set programm=$kb_new_prog where (programm=$input_prognr)";
					$success = faqe_db_query($movesql,$db);
					if (!$success)
						die("<tr class=\"errorrow\"><td>$l_cantmove.");
					echo "<tr class=\"displayrow\" align=\"center\"><td>";
					echo "<i>$kbcount</i> $l_articles";
					if($catcount>0)
						echo "$l_in <i>$catcount</i> $l_categories";
					echo " $l_moved<br></td></tr>";
				}
			}
		}
		$catcount=0;
		$kbcatcount=0;
		$faqcount=0;
		$kbcount=0;
		$countsql = "select count(catnr), sum(numfaqs) from ".$tableprefix."_category where (programm=$input_prognr)";
		if(!$result = faqe_db_query($countsql, $db))
			db_die("<tr class=\"errorrow\"><td>Unable to connect to database.");
		if ($temprow = faqe_db_fetch_array($result))
		{
			$catcount=$temprow["count(catnr)"];
			if($catcount>0)
				$faqcount=$temprow["sum(numfaqs)"];
		}
		$countsql = "select count(catnr) from ".$tableprefix."_kb_cat where (programm=$input_prognr)";
		if(!$result = faqe_db_query($countsql, $db))
			db_die("<tr class=\"errorrow\"><td>Unable to connect to database.");
		if ($countrow = faqe_db_fetch_array($result))
		{
			$kbcatcount=$countrow["count(catnr)"];
			$tmpsql="select count(articlenr) from ".$tableprefix."_kb_cat kbc, ".$tableprefix."_kb_articles kba where (kbc.programm=$input_prognr) and (kba.category=kbc.catnr)";
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			if($tmprow=faqe_db_fetch_array($tmpresult))
				$kbcount+=$tmprow["count(articlenr)"];
		}
		$tmpsql="select count(articlenr) from ".$tableprefix."_kb_articles where programm=$input_prognr and category=0";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			db_die("<tr class=\"errorrow\"><td>Unable to connect to database.");
		if($tmprow=faqe_db_fetch_array($tmpresult))
			$kbcount+=$tmprow["count(articlenr)"];
		if(($catcount > 0) || ($kbcatcount > 0) || ($kbcount > 0))
		{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo "$l_delprog ($delprogname)"?></b></td></tr>
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="delete">
<input type="hidden" name="input_prognr" value="<?php echo $input_prognr?>">
<input type="hidden" name="delprogname" value="<?php echo $delprogname?>">
<?php
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		}
		if($catcount > 0)
		{
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo "$l_catinprog ($catcount)"?>
<?php
			if ($faqcount>0)
				echo "<br>$l_withfaq ($faqcount)";
			echo "</td></tr>";
?>
<tr><td class="inputrow"><input type="radio" name="cat_action" value="del"><?php echo "$l_delcats"?></td></tr>
<tr><td class="inputrow"><input type="radio" name="cat_action" value="move"><?php echo "$l_movecats"?> <?php echo $l_to?>:
<?php
			$sql1 = "select * from ".$tableprefix."_programm where (prognr != $input_prognr)";
			if(!$result1 = faqe_db_query($sql1, $db))
				db_die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
			if (!$temprow = faqe_db_fetch_array($result1))
			{
				echo "$l_noentries";
			}
			else
			{
?>
<select name="new_prog">
<option value="-1">???</option>
<?php
				do {
					$progname=do_htmlentities($temprow["programmname"]);
					$prognr=$temprow["prognr"];
					$proglang=$temprow["language"];
					echo "<option value=\"".$temprow["prognr"]."\">";
					echo "$progname [$proglang]";
					echo "</option>";
				} while($temprow = faqe_db_fetch_array($result1));
?>
</select>
<?php
			}
?>
</td></tr>
<?php
		}
		if(($kbcatcount > 0) || ($kbcount > 0))
		{
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo "$l_kbarticlesinprog ($kbcount)"?>
<?php
			if ($kbcatcount>0)
				echo "<br>$l_in $l_categories ($kbcatcount)";
?>
</td></tr>
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="mode" value="delete">
<input type="hidden" name="input_prognr" value="<?php echo $input_prognr?>">
<input type="hidden" name="progname" value="<?php echo $progname?>">
<tr><td class="inputrow"><input type="radio" name="kbcat_action" value="del"><?php echo "$l_delcats"?></td></tr>
<tr><td class="inputrow"><input type="radio" name="kbcat_action" value="move"><?php echo "$l_movecatsandarticles"?> <?php echo $l_to?>:
<?php
			$sql1 = "select * from ".$tableprefix."_programm where (prognr != $input_prognr)";
			if(!$result1 = faqe_db_query($sql1, $db))
				db_die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
			if (!$temprow = faqe_db_fetch_array($result1))
			{
				echo "$l_noentries";
			}
			else
			{
?>
<select name="kb_new_prog">
<option value="-1">???</option>
<?php
				do {
					$progname=do_htmlentities($temprow["programmname"]);
					$prognr=$temprow["prognr"];
					$proglang=$temprow["language"];
					echo "<option value=\"".$temprow["prognr"]."\">";
					echo "$progname [$proglang]";
					echo "</option>";
				} while($temprow = faqe_db_fetch_array($result1));
?>
</select>
<?php
			}
			echo "</td></tr>";
		}
		if(($catcount > 0) || ($kbcatcount > 0) || ($kbcount > 0))
		{
?>
<tr class="actionrow"><td align="center" colspan="2">
<input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form>
</table></tr></td></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_proglist?></a></div>
<?php
		}
		else
		{
			$tmpsql = "select * from ".$tableprefix."_programm where prognr=$input_prognr";
			if(!$tmpresult = faqe_db_query($tmpsql,$db))
				db_die("<tr class=\"errorrow\"><td>could not connect to database.");
			$tmprow=faqe_db_fetch_array($tmpresult);
			$progid=$tmprow["progid"];
			$proglang=$tmprow["language"];
			$deleteSQL = "delete from ".$tableprefix."_programm_admins where (prognr=$input_prognr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				db_die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$deleteSQL = "delete from ".$tableprefix."_prog_os where (prognr=$input_prognr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$tmpsql2="select * from ".$tableprefix."_programm_version where programm=$input_prognr";
			if(!$tmpresult2 = faqe_db_query($tmpsql2,$db))
				db_die("<tr class=\"errorrow\"><td>could not connect to database.");
			while($tmprow2=faqe_db_fetch_array($tmpresult2))
			{
				$deleteSQL = "delete from ".$tableprefix."_kb_prog_version where progversion=".$tmprow2["entrynr"];
				$success = faqe_db_query($deleteSQL,$db);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete.");
				$deleteSQL = "delete from ".$tableprefix."_faq_prog_version where progversion=".$tmprow2["entrynr"];
				$success = faqe_db_query($deleteSQL,$db);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			}
			$deleteSQL = "delete from ".$tableprefix."_prog_dirs where prognr=$input_prognr";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$deleteSQL = "delete from ".$tableprefix."_programm_version where programm=$input_prognr";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$deleteSQL = "delete from ".$tableprefix."_subscriptions where progid='$progid' and language='$proglang'";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$deleteSQL = "update ".$tableprefix."_kb_articles set programm=0 where programm=$input_prognr";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$deleteSQL = "delete from ".$tableprefix."_programm where (prognr=$input_prognr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "<i>$delprogname</i> $l_deleted<br>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_proglist</a></div>";
		}
	}
	if($mode=="edit")
	{
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=$input_prognr and usernr=$act_usernr";
		if(!$modresult = faqe_db_query($modsql, $db))
		    db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($modrow=faqe_db_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights < 2) || (($admin_rights < 3) && ($ismod==0)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_proglist</a></div>";
			include('./trailer.php');
			exit;
		}
		$sql = "select * from ".$tableprefix."_programm where (prognr=$input_prognr)";
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$descriptiontext=stripslashes($myrow["description"]);
		$descriptiontext = str_replace("<BR>", "\n", $descriptiontext);
		$descriptiontext = undo_htmlspecialchars($descriptiontext);
		$descriptiontext = bbdecode($descriptiontext);
		$descriptiontext = undo_make_clickable($descriptiontext);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editprogs?></b></td></tr>
<form name="inputform" onsubmit="return checkform();" name="inputform" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="input_prognr" value="<?php echo $myrow["prognr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_progname?>:</td><td><input class="faqeinput" type="text" name="programmname" size="40" maxlength="80"
value="<?php echo undo_html_ampersand(do_htmlentities(stripslashes($myrow["programmname"])))?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_id?>:</td><td><input class="faqeinput" type="text" name="progid" size="10" maxlength="10" value="<?php echo $myrow["progid"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td>
<?php print language_select($myrow["language"], "proglang", "../language");?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_description?>:</td>
<td align="left"><textarea class="faqeinput" name="description" cols="40" rows="6"><?php echo $descriptiontext?></textarea>
<hr noshade color="#000000" size="1">
<?php display_bbcode_buttons($l_bbbuttons,"description")?>
</td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_supportedos?>:</td>
<td>
<?php
	$sql = "SELECT o.osname, o.osnr FROM ".$tableprefix."_os o, ".$tableprefix."_prog_os po WHERE po.prognr = '$input_prognr' AND o.osnr = po.osnr order by o.osnr";
	if(!$r = faqe_db_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if ($row = faqe_db_fetch_array($r))
	{
		 do {
		    echo display_encoded($row["osname"])." (<input type=\"checkbox\" name=\"rem_os[]\" value=\"".$row["osnr"]."\"> $l_remove)<BR>";
		    $current_os[] = $row["osnr"];
		 } while($row = faqe_db_fetch_array($r));
		 echo "<br>";
	}
	else
		echo "$l_noos<br><br>";
?>
<?php
	$sql = "SELECT osnr, osname FROM ".$tableprefix."_os ";
	$first=1;
	if(isset($current_os))
	{
    	while(list($null, $curros) = each($current_os)) {
    		if($first==1)
    		{
				$sql .= "WHERE osnr != $curros ";
				$first=0;
			}
			else
				$sql .= "AND osnr != $curros ";
    	}
    }
    $sql .= "ORDER BY osnr";
    if(!$r = faqe_db_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.");
    if($row = faqe_db_fetch_array($r)) {
		echo "<span class=\"inlineheading1\">$l_add:</span><br>";
		echo "<SELECT NAME=\"os[]\" size=\"5\" multiple>";
		do {
			echo "<OPTION VALUE=\"$row[osnr]\" >".display_encoded($row[osname])."</OPTION>\n";
		} while($row = faqe_db_fetch_array($r));
		echo"</select>";
	}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_admins?>:</td>
<td>
<?php
	$sql = "SELECT u.username, u.usernr FROM ".$tableprefix."_admins u, ".$tableprefix."_programm_admins f WHERE f.prognr = '$input_prognr' AND u.usernr = f.usernr order by u.username";
	if(!$r = faqe_db_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if ($row = faqe_db_fetch_array($r))
	{
		 do {
		    echo $row["username"]." (<input type=\"checkbox\" name=\"rem_mods[]\" value=\"".$row["usernr"]."\"> $l_remove)<BR>";
		    $current_mods[] = $row["usernr"];
		 } while($row = faqe_db_fetch_array($r));
		 echo "<br>";
	}
	else
		echo "$l_noadmins<br><br>";
	$sql = "SELECT usernr, username FROM ".$tableprefix."_admins WHERE rights > 1 ";
	if(isset($current_mods))
	{
    	while(list($null, $currMod) = each($current_mods)) {
			$sql .= "AND usernr != $currMod ";
    	}
    }
    $sql .= "ORDER BY username";
    if(!$r = faqe_db_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.");
    if($row = faqe_db_fetch_array($r)) {
		echo "<span class=\"inlineheading1\">$l_add:</span><br>";
		echo"<SELECT NAME=\"mods[]\" size=\"5\" multiple>";
		do {
			echo "<OPTION VALUE=\"$row[usernr]\" >$row[username]</OPTION>\n";
		} while($row = faqe_db_fetch_array($r));
		echo"</select>";
		echo "<br><input type=\"checkbox\" name=\"catadd\" value=\"1\"> $l_addtocats";
	}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_htmlmailtype?>:</td><td>
<input type="radio" name="htmlmailtype" value="0" <?php if($myrow["htmlmailtype"]==0) echo "checked"?>>#1<br>
<input type="radio" name="htmlmailtype" value="1" <?php if($myrow["htmlmailtype"]==1) echo "checked"?>>#2</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="cansubscribe" value="1" <?php if($myrow["subscriptionavail"]==1) echo "checked"?>>
<?php echo $l_subscriptionavail?></td></tr>
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_news_settings?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_nntpserver?>:</td><td><input class="faqeinput" type="text" name="nntpserver" size="40" maxlength="80" value="<?php echo $myrow["nntpserver"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_domain?>:</td><td><input class="faqeinput" type="text" name="newsdomain" size="40" maxlength="80" value="<?php echo $myrow["newsdomain"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_newsgroup?>:</td><td><input class="faqeinput" type="text" name="newsgroup" size="40" maxlength="250" value="<?php echo $myrow["newsgroup"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_newssubject?>:</td><td><input class="faqeinput" type="text" name="newssubject" size="40" maxlength="80" value="<?php echo $myrow["newssubject"]?>"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update">
<input class="faqebutton" type="submit" value="<?php echo $l_update?>">
&nbsp;&nbsp;<input class="faqebutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></tr></td></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_proglist?></a></div>
<?php
	}
	if($mode=="update")
	{
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=$input_prognr and usernr=$act_usernr";
		if(!$modresult = faqe_db_query($modsql, $db)) {
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
		if($modrow=faqe_db_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights < 2) || (($admin_rights < 3) && ($ismod==0)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_proglist</a></div>";
			include('./trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(!$programmname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogname</td></tr>";
			$errors=1;
		}
		if(!$progid)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noid</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(!isset($local_urlautoencode))
				$urlautoencode=0;
			else
				$urlautoencode=1;
			if(!isset($local_enablespcode))
				$enablespcode=0;
			else
				$enablespcode=1;
			if(isset($preview))
			{
				$displaydescription="";
				if($description)
				{
					$displaydescription=stripslashes($description);
					if($urlautoencode==1)
						$dispalydescription = make_clickable($displaydescription);
					if($enablespcode==1)
						$displaydescription = bbencode($displaydescription);
					$displaydescription = do_htmlentities($displaydescription);
					$displaydescription = str_replace("\n", "<BR>", $displaydescription);
					$displaydescription = undo_htmlspecialchars($displaydescription);
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newprogramm?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(isset($catadd))
			echo "<input type=\"hidden\" name=\"catadd\" value=\"1\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_progname?>:</td><td><?php echo $programmname?><input type="hidden" name="programmname" value="<?php echo $programmname?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_id?>:</td><td><?php echo $progid?><input type="hidden" name="progid" value="<?php echo $progid?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td><?php echo $proglang?><input type="hidden" name="proglang" value="<?php echo $proglang?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_description?>:</td><td><?php echo $displaydescription?><input type="hidden" name="description" value="<?php echo $description?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_supportedos?>:</td><td>
<?php
				$os_query="select os.* from ".$tableprefix."_os os, ".$tableprefix."_prog_os po where po.prognr=$input_prognr and os.osnr=po.osnr";
				if(isset($rem_os))
				{
					while(list($null, $os) = each($_POST["rem_os"]))
					{
						echo "<input type=\"hidden\" name=\"rem_os[]\" value=\"$os\">";
						$os_query.=" and os.osnr!=$os";
					}
				}
   			   	if(!$os_result=faqe_db_query($os_query, $db))
					die("<tr class=\"errorrow\"><td>Unable to connect to database.");
				if($os_row=faqe_db_fetch_array($os_result))
				{
					do{
						echo $os_row["osname"];
						echo "<br>";
					}while($os_row=faqe_db_fetch_array($os_result));
				}
				if(isset($os))
				{
					while(list($null, $local_os) = each($_POST["os"]))
					{
						$os_query = "SELECT * from ".$tableprefix."_os where osnr=$local_os";
	    			   		if(!$os_result=faqe_db_query($os_query, $db))
							die("<tr class=\"errorrow\"><td>Unable to connect to database.");
						if($os_row=faqe_db_fetch_array($os_result))
						{
							echo $os_row["osname"];
							echo "<input type=\"hidden\" name=\"os[]\" value=\"$os\"><br>";
						}

					}
				}
?>
</td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_admins?>:</td><td>
<?php
				$mod_query="select mod.* from ".$tableprefix."_admins mod, ".$tableprefix."_programm_admins pa where pa.prognr=$input_prognr and mod.usernr=pa.usernr";
				if(isset($rem_mods))
				{
					while(list($null, $mod) = each($_POST["rem_mods"]))
					{
						echo "<input type=\"hidden\" name=\"rem_mods[]\" value=\"$mod\">";
						$mod_query.=" and mod.usernr!=$mod";
					}
				}
   			   	if(!$mod_result=faqe_db_query($mod_query, $db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
				if($mod_row=faqe_db_fetch_array($mod_result))
				{
					do{
						echo $mod_row["username"];
						echo "<br>";
					}while($mod_row=faqe_db_fetch_array($mod_result));
				}
				if(isset($mods))
				{
					while(list($null, $mod) = each($_POST["mods"]))
					{
						$mod_query = "SELECT * from ".$tableprefix."_admins where usernr=$mod";
	    			   	if(!$mod_result=faqe_db_query($mod_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
						if($mod_row=faqe_db_fetch_array($mod_result))
						{
							echo $mod_row["username"];
							echo "<input type=\"hidden\" name=\"mods[]\" value=\"$mod\"><br>";
						}

					}
				}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_htmlmailtype?>:</td><td>
#<?php echo $htmlmailtype+1?></td></tr>
<input type="hidden" name="htmlmailtype" value="<?php echo $htmlmailtype?>">
<tr class="grouprow1"><td align="left" colspan="2"><b><?php echo $l_news_settings?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_nntpserver?>:</td>
<td><?php echo $nntpserver?><input type="hidden" name="nntpserver" value="<?php echo $nntpserver?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_domain?>:</td>
<td><?php echo $newsdomain?><input type="hidden" name="newsdomain" value="<?php echo $newsdomain?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_newsgroup?>:</td>
<td><?php echo $newsgroup?><input type="hidden" name="newsgroup" value="<?php echo $newsgroup?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_newssubject?>:</td>
<td><?php echo $newssubject?><input type="hidden" name="newssubject" value="<?php echo $newssubject?>"></td></tr>
<?php
if(isset($cansubscribe))
	echo "<input type=\"hidden\" name=\"cansubscribe\" value=\"1\">";
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input type="hidden" name="input_prognr" value="<?php echo $input_prognr?>">
<input class="faqebutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="update">
</td></tr></form></table></td></tr></table>
<?php
			}
			else
			{
				if($description)
				{
					$description=stripslashes($description);
					if($urlautoencode==1)
						$description = make_clickable($description);
					if($enablespcode==1)
						$description = bbencode($description);
					$description = do_htmlentities($description);
					$description = str_replace("\n", "<BR>", $description);
					$description=addslashes($description);
				}
				if(isset($cansubscribe))
					$subscriptionavail=1;
				else
					$subscriptionavail=0;
				$programmname=addslashes($programmname);
				$sql = "UPDATE ".$tableprefix."_programm SET programmname='$programmname', progid='$progid', language='$proglang', newsgroup='$newsgroup', newssubject='$newssubject', nntpserver='$nntpserver', newsdomain='$newsdomain', description='$description', htmlmailtype=$htmlmailtype, subscriptionavail=$subscriptionavail ";
				$sql .=" WHERE (prognr = $input_prognr)";
				if(!$result = faqe_db_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				if(isset($mods))
				{
		    		while(list($null, $mod) = each($_POST["mods"]))
		    		{
						$mod_query = "INSERT INTO ".$tableprefix."_programm_admins (prognr, usernr) VALUES ('$input_prognr', '$mod')";
		    		   	if(!faqe_db_query($mod_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to update the database.");
						if(isset($catadd))
						{
							// FAQ categories
							$catsql="select * from ".$tableprefix."_category where programm=$input_prognr";
			    		   	if(!$catresult=faqe_db_query($catsql, $db))
							    die("<tr class=\"errorrow\"><td>Unable to update the database.");
							while($catrow=faqe_db_fetch_array($catresult))
							{
								$cat2sql="delete from ".$tableprefix."_category_admins where catnr=".$catrow["catnr"]." and usernr=$mod";
				    		   	if(!faqe_db_query($cat2sql, $db))
								    die("<tr class=\"errorrow\"><td>Unable to update the database.");
								$cat2sql="insert into ".$tableprefix."_category_admins (catnr, usernr) values (".$catrow["catnr"].",$mod)";
				    		   	if(!faqe_db_query($cat2sql, $db))
								    die("<tr class=\"errorrow\"><td>Unable to update the database.");
							}
						}
					}
				}
				if(isset($rem_mods))
				{
					while(list($null, $mod) = each($_POST["rem_mods"]))
					{
						$rem_query = "DELETE FROM ".$tableprefix."_programm_admins WHERE prognr = '$input_prognr' AND usernr = '$mod'";
		       			if(!faqe_db_query($rem_query,$db))
						    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				if(isset($os))
				{
					while(list($null, $os) = each ($_POST["os"]))
					{
						$os_query = "INSERT INTO ".$tableprefix."_prog_os (osnr, prognr) VALUES ('$os', '$input_prognr')";
		    		   	if(!faqe_db_query($os_query, $db))
						    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				if(isset($rem_os))
				{
					while(list($null, $os) = each($_POST["rem_os"]))
					{
						$rem_query = "DELETE FROM ".$tableprefix."_prog_os WHERE prognr = '$input_prognr' AND osnr='$os'";
		       			if(!faqe_db_query($rem_query,$db))
						    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					}
				}
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_progupdated";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_proglist</a></div>";
			}
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($admin_rights>1)
	{
?>
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newprogramm?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
// Display list of actual categories
$sql = "select * from ".$tableprefix."_programm ";
if(isset($filterlang) && ($filterlang!="none"))
	$sql.="where language='$filterlang' ";
switch($sorting)
{
	case 12:
		$sql.=" order by prognr desc";
		break;
	case 21:
		$sql.=" order by progid asc";
		break;
	case 22:
		$sql.=" order by progid desc";
		break;
	case 31:
		$sql.=" order by programmname asc";
		break;
	case 32:
		$sql.=" order by programmname desc";
		break;
	case 41:
		$sql.=" order by numcats asc";
		break;
	case 42:
		$sql.=" order by numcats desc";
		break;
	case 99:
		$sql.=" order by language, displaypos";
		break;
	default:
		$sql.=" order by prognr asc";
		break;
}
if(!$result = faqe_db_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$maxsortcol=4;
	$baseurl="$act_script_url?$langvar=$act_lang";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"10%\">";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>#</b></a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"10%\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_id</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"50%\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_progname</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td class=\"rowheadings\" align=\"center\" width=\"20%\"><b>$l_language</b></td>";
	echo "<td align=\"center\" widht=\"10%\">";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_categories</b></a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</td>";
	echo "<td class=\"rowheadings\">";
	if($sorting!=99)
	{
		$sorturl=$baseurl."&sorting=99";
		echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\"><b>";
		echo "$l_sortbydisplaypos</b></a>";
	}
	else
		echo "<span class=\"activesorting\">$l_sortedbydisplaypos</span>";
	echo "</td></tr>";
	do {
		$act_id=$myrow["prognr"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\">".$myrow["prognr"]."</td>";
		echo "<td align=\"center\">".$myrow["progid"]."</td>";
		echo "<td>";
		echo display_encoded($myrow["programmname"]);
		echo "</td>";
		echo "<td align=\"center\">".$myrow["language"]."</td>";
		echo "<td align=\"right\">".$myrow["numcats"]."</td>";
		echo "<td>";
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=$act_id and usernr=$act_usernr";
		if(!$modresult = faqe_db_query($modsql, $db)) {
		    die("Could not connect to the database.");
		}
		if($modrow=faqe_db_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights>2) || ($ismod==1))
		{
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_prognr=$act_id&$langvar=$act_lang&delprogname=".urlencode($myrow["programmname"]))."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>";
			echo "&nbsp;&nbsp;";
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_prognr=$act_id")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a>";
			if((strlen($myrow["newsgroup"])>0) && (strlen($myrow["nntpserver"])>0) && (strlen($myrow["newsdomain"])>0))
			{
				echo "&nbsp; ";
				echo "<a class=\"listlink2\" href=\"".do_url_session("newspost.php?$langvar=$act_lang&input_prognr=$act_id")."\">";
				echo "$l_newspost</a>";
			}
			echo "&nbsp; ";
			echo "<a class=\"listlink2\" href=\"".do_url_session("programversion.php?input_prognr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/list.gif\" border=\"0\" alt=\"$l_versions\" title=\"$l_versions\"></a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink2\" href=\"".do_url_session("reorder_cat.php?input_prognr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/reorder.gif\" border=\"0\" alt=\"$l_reorder_cat\" title=\"$l_reorder_cat\"></a>&nbsp; ";
		}
		echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&input_prognr=$act_id&$langvar=$act_lang&progname=".urlencode($myrow["programmname"]))."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" alt=\"$l_display\" title=\"$l_display\"></a>";
		echo "</td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
	if($admin_rights>2)
	{
		echo "<tr class=\"actionrow\"><td colspan=\"6\" align=\"center\">";
		echo "<a href=\"".do_url_session("$act_script_url?mode=reindex&$langvar=$act_lang")."\">";
		echo "$l_reindex_prog</a></td></tr>";
	}
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
	include('./includes/language_filterbox.inc');
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newprogramm?></a></div>
<?php
}
}
include('./trailer.php');
?>