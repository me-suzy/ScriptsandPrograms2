<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_pposter;
$page="poster";
require_once('./heading.php');
if(!isset($sorting))
	$sorting=11;
if(!isset($dostorefilter) && ($admstorefilter==1))
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
		if(sn_array_key_exists($admcookievals,"poster_sorting"))
			$sorting=$admcookievals["poster_sorting"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < $posterlevel)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="propdisplay")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql="select * from ".$tableprefix."_poster where entrynr=$input_usernr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$postermail=$myrow["email"];
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="3"><b><?php echo $l_proposedentries?></b></td></tr>
<tr><td class="inforow" align="center" colspan="3"><?php echo "$l_poster: $postermail"?></td></tr>
<tr class="listheading0"><td align="center" colspan="3"><b><?php echo $l_news?><b></td></tr>
<tr class="listheading1"><td align="center" colspan="3"><b><?php echo $l_transferrednews?><b></td></tr>
<?php
		$sql="select * from ".$tableprefix."_data where exposter=$input_usernr order by category";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">$l_noentries</td></tr>";
		else
		{
			echo "<tr class=\"rowheadings\">";
			echo "<td align=\"center\"><b>#</b></td>";
			echo "<td align=\"center\"><b>$l_entry</b></td>";
			echo "<td align=\"center\"><b>$l_category</b></td>";
			echo "</tr>";
			do{
				echo "<tr class=\"displayrow\">";
				echo "<td align=\"right\" width=\"5%\">";
				echo $myrow["newsnr"];
				echo "</td>";
				echo "<td align=\"left\" width=\"50%\">";
				$newstext = stripslashes($myrow["text"]);
				$newstext = undo_htmlspecialchars($newstext);
				if($admentrychars>0)
				{
					$newstext=undo_htmlentities($newstext);
					$newstext=strip_tags($newstext);
					$newstext=substr($newstext,0,$admentrychars);
					$newstext.="[...]";
				}
				if($admonlyentryheadings==0)
				{
					if($myrow["heading"])
						$displaytext="<b>".$myrow["heading"]."</b><br>".$newstext;
					else
						$displaytext=$newstext;
				}
				else
				{
					if($myrow["heading"])
						$displaytext="<b>".$myrow["heading"]."</b>";
					else
					{
						$displaytext=strip_tags($myrow["text"]);
						if($admentrychars>0)
							$displaytext=substr($displaytext,0,$admentrychars);
						else
							$displaytext=substr($displaytext,0,20);
						$displaytext.="[...]";
					}
				}
				echo $displaytext;
				echo "</td>";
				echo "<td align=\"center\">";
				if($myrow["category"]==0)
					echo $l_general;
				else
				{
					$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
					if(!$tmpresult = mysql_query($tmpsql, $db))
					    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
					if($tmprow=mysql_fetch_array($tmpresult))
						echo $tmprow["catname"];
				}
				echo "</td></tr>";
			}while($myrow=mysql_fetch_array($result));
		}
?>
<tr class="listheading1"><td align="center" colspan="3"><b><?php echo $l_proposals?><b></td></tr>
<?php
		$sql="select * from ".$tableprefix."_tmpdata where posterid=$input_usernr order by category";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">$l_noentries</td></tr>";
		else
		{
			echo "<tr class=\"rowheadings\">";
			echo "<td align=\"center\"><b>#</b></td>";
			echo "<td align=\"center\"><b>$l_entry</b></td>";
			echo "<td align=\"center\"><b>$l_category</b></td>";
			echo "</tr>";
			do{
				echo "<tr class=\"displayrow\">";
				echo "<td align=\"right\" width=\"5%\">";
				echo $myrow["entrynr"];
				echo "</td>";
				echo "<td align=\"left\" width=\"50%\">";
				$newstext = stripslashes($myrow["text"]);
				$newstext = undo_htmlspecialchars($newstext);
				if($admentrychars>0)
				{
					$newstext=undo_htmlentities($newstext);
					$newstext=strip_tags($newstext);
					$newstext=substr($newstext,0,$admentrychars);
					$newstext.="[...]";
				}
				if($admonlyentryheadings==0)
				{
					if($myrow["heading"])
						$displaytext="<b>".$myrow["heading"]."</b><br>".$newstext;
					else
						$displaytext=$newstext;
				}
				else
				{
					if($myrow["heading"])
						$displaytext="<b>".$myrow["heading"]."</b>";
					else
					{
						$displaytext=strip_tags($myrow["text"]);
						if($admentrychars>0)
							$displaytext=substr($displaytext,0,$admentrychars);
						else
							$displaytext=substr($displaytext,0,20);
						$displaytext.="[...]";
					}
				}
				echo $displaytext;
				echo "</td>";
				echo "<td align=\"center\">";
				if($myrow["category"]==0)
					echo $l_general;
				else
				{
					$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
					if(!$tmpresult = mysql_query($tmpsql, $db))
					    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
					if($tmprow=mysql_fetch_array($tmpresult))
						echo $tmprow["catname"];
				}
				echo "</td></tr>";
			}while($myrow=mysql_fetch_array($result));
		}
?>
<tr class="listheading0"><td align="center" colspan="3"><b><?php echo $l_events?><b></td></tr>
<tr class="listheading1"><td align="center" colspan="3"><b><?php echo $l_transferredevents?><b></td></tr>
<?php
		$sql="select * from ".$tableprefix."_events where exposter=$input_usernr order by category";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">$l_noentries</td></tr>";
		else
		{
			echo "<tr class=\"rowheadings\">";
			echo "<td align=\"center\"><b>#</b></td>";
			echo "<td align=\"center\"><b>$l_entry</b></td>";
			echo "<td align=\"center\"><b>$l_category</b></td>";
			echo "</tr>";
			do{
				echo "<tr class=\"displayrow\">";
				echo "<td align=\"right\" width=\"5%\">";
				echo $myrow["eventnr"];
				echo "</td>";
				echo "<td align=\"left\" width=\"50%\">";
				$newstext = stripslashes($myrow["text"]);
				$newstext = undo_htmlspecialchars($newstext);
				if($admentrychars>0)
				{
					$newstext=undo_htmlentities($newstext);
					$newstext=strip_tags($newstext);
					$newstext=substr($newstext,0,$admentrychars);
					$newstext.="[...]";
				}
				if($admonlyentryheadings==0)
				{
					if($myrow["heading"])
						$displaytext="<b>".$myrow["heading"]."</b><br>".$newstext;
					else
						$displaytext=$newstext;
				}
				else
				{
					if($myrow["heading"])
						$displaytext="<b>".$myrow["heading"]."</b>";
					else
					{
						$displaytext=strip_tags($myrow["text"]);
						if($admentrychars>0)
							$displaytext=substr($displaytext,0,$admentrychars);
						else
							$displaytext=substr($displaytext,0,20);
						$displaytext.="[...]";
					}
				}
				echo $displaytext;
				echo "</td>";
				echo "<td align=\"center\">";
				if($myrow["category"]==0)
					echo $l_general;
				else
				{
					$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
					if(!$tmpresult = mysql_query($tmpsql, $db))
					    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
					if($tmprow=mysql_fetch_array($tmpresult))
						echo $tmprow["catname"];
				}
				echo "</td></tr>";
			}while($myrow=mysql_fetch_array($result));
		}
?>
<tr class="listheading1"><td align="center" colspan="3"><b><?php echo $l_proposals?><b></td></tr>
<?php
		$sql="select * from ".$tableprefix."_tmpevents where posterid=$input_usernr order by category";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">$l_noentries</td></tr>";
		else
		{
			echo "<tr class=\"rowheadings\">";
			echo "<td align=\"center\"><b>#</b></td>";
			echo "<td align=\"center\"><b>$l_entry</b></td>";
			echo "<td align=\"center\"><b>$l_category</b></td>";
			echo "</tr>";
			do{
				echo "<tr class=\"displayrow\">";
				echo "<td align=\"right\" width=\"5%\">";
				echo $myrow["entrynr"];
				echo "</td>";
				echo "<td align=\"left\" width=\"50%\">";
				$newstext = stripslashes($myrow["text"]);
				$newstext = undo_htmlspecialchars($newstext);
				if($admentrychars>0)
				{
					$newstext=undo_htmlentities($newstext);
					$newstext=strip_tags($newstext);
					$newstext=substr($newstext,0,$admentrychars);
					$newstext.="[...]";
				}
				if($admonlyentryheadings==0)
				{
					if($myrow["heading"])
						$displaytext="<b>".$myrow["heading"]."</b><br>".$newstext;
					else
						$displaytext=$newstext;
				}
				else
				{
					if($myrow["heading"])
						$displaytext="<b>".$myrow["heading"]."</b>";
					else
					{
						$displaytext=strip_tags($myrow["text"]);
						if($admentrychars>0)
							$displaytext=substr($displaytext,0,$admentrychars);
						else
							$displaytext=substr($displaytext,0,20);
						$displaytext.="[...]";
					}
				}
				echo $displaytext;
				echo "</td>";
				echo "<td align=\"center\">";
				if($myrow["category"]==0)
					echo $l_general;
				else
				{
					$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
					if(!$tmpresult = mysql_query($tmpsql, $db))
					    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
					if($tmprow=mysql_fetch_array($tmpresult))
						echo $tmprow["catname"];
				}
				echo "</td></tr>";
			}while($myrow=mysql_fetch_array($result));
		}
		echo "</table></tr></td></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_pposter</a></div>";
	}
	if($mode=="edit")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editposter?></b></td></tr>
<?php
		$sql="select * from ".$tableprefix."_poster where entrynr=$input_usernr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="update">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_usernr" value="<?php echo $myrow["entrynr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_name?>:</td>
<td><input class="sninput" type="text" name="postername" value="<?php echo $myrow["name"]?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_email?>:</td>
<td><input class="sninput" type="text" name="postermail" value="<?php echo $myrow["email"]?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="nobbcode" value="1" <?php if($myrow["disablebbcode"]==1) echo "checked"?>>
<?php echo $l_disablebbcode?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="noupload" value="1" <?php if($myrow["disablefileupload"]==1) echo "checked"?>>
<?php echo $l_disablefileupload?></td></tr>
<?php
		if($myrow["pwconfirmed"]==1)
			echo "<tr class=\"inputrow\"><td>&nbsp;</td><td><input type=\"checkbox\" name=\"cleanpw\" value=\"1\"> $l_cleanpw</td></tr>";
		echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"submit\" value=\"$l_update\"></td></tr>";
		echo "</form></table></tr></td></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_pposter</a></div>";
	}
	if($mode=="update")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editposter?></b></td></tr>
<?php
		$errors=0;
		if(!$postername)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noname</td></tr>";
			$errors=1;
		}
		if(!isset($postermail) || !$postermail)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noemail</td></tr>";
			$errors=1;
		}
		else if(!validate_email($postermail))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noemail</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($noupload))
				$disablefileupload=1;
			else
				$disablefileupload=0;
			if(isset($nobbcode))
				$disablebbcode=1;
			else
				$disablebbcode=0;
			$sql = "update ".$tableprefix."_poster set name='$postername', email='$postermail', disablebbcode=$disablebbcode, disablefileupload=$disablefileupload";
			if(isset($cleanpw))
				$sql.=", pwconfirmed=0, pid=0, password=''";
			$sql.=" where entrynr=$input_usernr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_posterupdated";
			echo "</td></tr>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr>";
		}
		echo "</form></table></tr></td></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_pposter</a></div>";
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
		$deleteSQL = "delete from ".$tableprefix."_poster where (entrynr=$input_usernr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_tmpdata where (posterid=$input_usernr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_tmpevents where (posterid=$input_usernr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$sql = "update ".$tableprefix."_events set exposter=0 where (exposter=$input_usernr)";
		$success = mysql_query($sql);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$sql = "update ".$tableprefix."_data set exposter=0 where (exposter=$input_usernr)";
		$success = mysql_query($sql);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_pposter</a></div>";
	}
	if($mode=="cleanpw")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "update ".$tableprefix."_poster set pid=0, pwconfirmed=0, password='' where (entrynr=$input_usernr)";
		$success = mysql_query($sql);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_pwdeleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_pposter</a></div>";
	}
}
else
{
$allowactions=false;
if($admin_rights >= 3)
	$allowactions=true;
if(($admin_rights == 2) && bittst($secsettings,BIT_6))
	$allowactions=true;
echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
$sql = "select * from ".$tableprefix."_poster ";
switch($sorting)
{
	case 11:
		$sql.="order by email asc";
		break;
	case 12:
		$sql.="order by email desc";
		break;
	case 21:
		$sql.="order by name asc";
		break;
	case 22:
		$sql.="order by name desc";
		break;
}
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
$baseurl=$act_script_url."?".$langvar."=".$act_lang;
if($admstorefilter==1)
	$baseurl.="&dostorefilter=1";
$maxsortcol=2;
echo "<tr class=\"rowheadings\">";
echo "<td align=\"center\"><b>";
$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "$l_email</a>";
echo getSortMarker($sorting, 1, $maxsortcol);
echo "</b></td>";
echo "<td align=\"center\"><b>";
$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "$l_name</a>";
echo getSortMarker($sorting, 2, $maxsortcol);
echo "</b></td>";
echo "<td>&nbsp;</td></tr>";
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"4\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	do {
		$act_id=$myrow["entrynr"];
		echo "<tr class=\"displayrow\">";
		echo "<td width=\"40%\" align=\"left\">".$myrow["email"]."</td>";
		echo "<td width=\"40%\" align=\"left\">";
		echo $myrow["name"];
		echo "</td>";
		echo "<td>";
		if($allowactions)
		{
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=delete&input_usernr=$act_id&$langvar=$act_lang")."\">";
			echo "$l_delete</a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&input_usernr=$act_id&$langvar=$act_lang")."\">";
			echo "$l_edit</a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=propdisplay&input_usernr=$act_id&$langvar=$act_lang")."\">";
			echo "$l_displayproposals</a>";
			if($myrow["pwconfirmed"]==1)
			{
				echo "&nbsp; ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=cleanpw&input_usernr=$act_id&$langvar=$act_lang")."\">";
				echo "$l_cleanpw</a>";
			}
		}
		echo "</td></tr>";
   } while($myrow = mysql_fetch_array($result));
   echo "</table></tr></td></table>";
}
}
include('./trailer.php');
?>