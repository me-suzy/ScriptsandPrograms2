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
$page_title=$l_proposednews;
$page="proposes";
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.".mysql_error());
}
if ($myrow = mysql_fetch_array($result))
	$enablehostresolve=$myrow["enablehostresolve"];
else
	$enablehostresolve=1;
$dateformat=$l_admdateformat;
if(!isset($filtercat))
	$filtercat=-1;
if(!isset($sorting))
	$sorting=12;
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
		if(sn_array_key_exists($admcookievals,"prop_sorting"))
			$sorting=$admcookievals["prop_sorting"];
		if(sn_array_key_exists($admcookievals,"prop_filtercat"))
			$filtercat=$admcookievals["prop_filtercat"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < $nproplevel)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(isset($mode))
{
	if($mode=="delete")
	{
		$deleteSQL="delete from ".$tableprefix."_tmpdata where (entrynr=".$input_entrynr.")";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL="delete from ".$tableprefix."_tmpnews_attachs where (newsnr=".$input_entrynr.")";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"5\" >";
		echo "$l_entries $l_deleted</td></tr>";
	}
	if($mode=="display")
	{
		$sql="select tmp.* from ".$tableprefix."_tmpdata tmp where tmp.entrynr=".$input_entrynr;
		if(!$result = mysql_query($sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("Could not connect to the database.".mysql_error());
		}
		if (!$myrow = mysql_fetch_array($result))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("<tr class=\"errorrow\"><td>No such entry.");
		}
		$catname=$l_undefined;
		if($myrow["category"]==0)
			$catname=$l_general;
		else
		{
			$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
			if(!$tmpresult = mysql_query($tmpsql, $db)) {
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				die("Could not connect to the database.".mysql_error());
			}
			if ($tmprow = mysql_fetch_array($tmpresult))
				$catname=$tmprow["catname"];
		}
		$postername=$l_unknown;
		$postermail=$l_unknown;
		if($myrow["posterid"]!=0)
		{
			$postersql="select * from ".$tableprefix."_poster where entrynr=".$myrow["posterid"];
			if(!$posterresult = mysql_query($postersql, $db)) {
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				die("Could not connect to the database.".mysql_error());
			}
			if($posterrow=mysql_fetch_array($posterresult))
			{
				$postername=$posterrow["name"];
				$postermail=$posterrow["email"];
			}
		}
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_category?>:</td>
<td><?php echo $catname?> (<?php echo $myrow["category"]?>)</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_poster?>:</td><td>
<?php echo $postername?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_postermail?>:</td><td>
<?php echo $postermail?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_ipadr?>:</td><td>
<?php
if($admin_rights>2)
	echo $myrow["posterip"];
else
	echo $l_logged;
?>
</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_language?>:</td><td>
<?php echo $myrow["lang"]?></td></tr>
<?php
		list($mydate,$mytime)=explode(" ",$myrow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
?>
<tr class="displayrow"><td align="right"><?php echo $l_date?>:</td><td>
<?php echo $displaydate?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_heading?>:</td><td>
<?php echo $myrow["heading"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_text?>:</td><td>
<?php echo $myrow["text"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_attachement?>:</td><td>
<?php
$tmpsql="select f.* from ".$tableprefix."_tmpnews_attachs tna, ".$tableprefix."_files f where f.entrynr=tna.attachnr and tna.newsnr=".$myrow["entrynr"];
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.".mysql_error());
if(!$tmprow=mysql_fetch_array($tmpresult))
	echo $l_none;
else
	echo "<a href=\"../sndownload.php?$langvar=$act_lang&nodlcount=1&entrynr=".$tmprow["entrynr"]."\">".$tmprow["filename"]." (".$tmprow["filesize"]." Bytes)</a>";
echo "</td></tr>";
if($myrow["chgnews"]!=0)
{
	$displaymsg=str_replace("{entrynr}",$myrow["chgnews"],$l_replace_entry);
	$displaymsg=str_replace("{entrytype}",$l_newsentry,$displaymsg);
	echo "<tr class=\"displayrow\"><td>&nbsp;</td><td>$displaymsg</td></tr>";
}
?>
<form method="post" action="news.php">
<?php
if($sessid_url)
	echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr class="inforow"><td colspan="2" align="left">
<b><?php echo $l_transfertonews?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="delpropose" value="1"> <?php echo $l_delpropose?></td></tr>
<?php
if($myrow["chgnews"]!=0)
{
	echo "<input type=\"hidden\" name=\"chgnewsnr\" value=\"".$myrow["chgnews"]."\">";
	echo "<tr class=\"inforow2\"><td>&nbsp;</td><td align=\"left\">";
	echo "<input type=\"radio\" name=\"asnewentry\" value=\"1\" checked> $l_asnewentry</td></tr>";
}
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_category?>:</td>
<td><select name="catnr">
<?php
echo "<option value=\"0\"";
if($myrow["category"]==0)
	echo " selected";
echo ">$l_general</option>";
$tmpsql = "select * from ".$tableprefix."_categories";
if(!$tmpresult = mysql_query($tmpsql, $db)) {
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("Could not connect to the database.".mysql_error());
}
while($tmprow=mysql_fetch_array($tmpresult))
{
	echo "<option value=\"".$tmprow["catnr"]."\"";
	if($myrow["category"]==$tmprow["catnr"])
		echo " selected";
	echo ">".$tmprow["catname"]."</option>";
}
$transfertext=do_htmlentities($myrow["text"]);
?>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td>
<td><?php echo language_select($myrow["lang"],"newslang")?></td></tr>
<input type="hidden" name="newstext" value="<?php echo $transfertext?>">
<input type="hidden" name="transfer" value="1">
<input type="hidden" name="tmpdata" value="<?php echo $myrow["entrynr"]?>">
<input type="hidden" name="headingtext" value="<?php echo do_htmlentities($myrow["heading"])?>">
<?php
if($myrow["chgnews"]!=0)
{
	echo "<input type=\"hidden\" name=\"chgnewsnr\" value=\"".$myrow["chgnews"]."\">";
	echo "<tr class=\"inforow2\"><td>&nbsp;</td><td align=\"left\">";
	echo "<input type=\"radio\" name=\"asnewentry\" value=\"0\"> $l_asrefferedentry</td></tr>";
	echo "<tr class=\"inputrow\"><td>&nbsp;</td><td align=\"left\">";
	echo "<input type=\"radio\" name=\"replacemode\" value=\"0\" checked> $l_replaceold<br>";
	echo "<input type=\"radio\" name=\"replacemode\" value=\"1\"> $l_append2old";
	echo "</td></tr>";
}
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="snbutton" type="submit" name="submit" value="<?php echo $l_transfer?>"></td></tr></form>
<?php
		echo "<tr class=\"actionrow\"><td colspan=\"2\" align=\"center\">";
		echo "<a href=\"".do_url_session("$act_script_url?input_entrynr=$input_entrynr&$langvar=$act_lang&mode=delete")."\">";
		echo "$l_delete</a></td></tr>";
		echo "</td></tr></table>";
		echo "</td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_proposednews</a></div>";
		include('./trailer.php');
		exit;
	}
}
$doquery=true;
if($admin_rights>2)
{
	$sql = "select * from ".$tableprefix."_tmpdata dat";
	if(isset($filtercat) && ($filtercat>=0))
		$sql.=" where dat.category=$filtercat ";
}
else
{
	$possiblecats=array();
	$sql="select * from ".$tableprefix."_cat_adm ca where ca.usernr=".$userdata["usernr"];
	if(isset($filtercat) && ($filtercat>=0))
		$sql.=" and ca.catnr=$filtercat ";
	if(!$result = mysql_query($sql, $db))
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		die("Could not connect to the database.".mysql_error());
	}
	while($myrow=mysql_fetch_array($result))
		array_push($possiblecats,$myrow["catnr"]);
	if(bittst($secsettings,BIT_1))
	{
		if(isset($filtercat) && ($filtercat>=0))
		{
			if($filtercat==0)
				array_push($possiblecats,0);
		}
		else
			array_push($possiblecats,0);
	}
	$firstarg=true;
	$sql="select * from ".$tableprefix."_tmpdata dat";
	for($i=0;$i<count($possiblecats);$i++)
	{
		if($firstarg)
		{
			$firstarg=false;
			$sql.=" where ( ";
		}
		else
			$sql.=" or ";
		$sql.="dat.category=".$possiblecats[$i];
	}
	if(count($possiblecats)<1)
		$doquery=false;
	else
		$sql.=")";
}
$sql.=" group by dat.entrynr";
switch($sorting)
{
	case 11:
		$sql.=" order by dat.date asc";
		break;
	case 21:
		$sql.=" order by dat.category asc";
		break;
	case 22:
		$sql.=" order by dat.category desc";
		break;
	case 31:
		$sql.=" order by dat.lang asc";
		break;
	case 32:
		$sql.=" order by dat.lang desc";
		break;
	default:
		$sql.=" order by dat.date desc";
		break;
}
if(!$doquery)
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo "$l_noentries";
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$baseurl=$act_script_url."?".$langvar."=".$act_lang;
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
	if(isset($filtercat))
		$baseurl.="&filtercat=$filtercat";
	if(!$result = mysql_query($sql, $db))
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		die("Could not connect to the database.".mysql_error());
	}
	if (!$myrow = mysql_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		echo "$l_noentries";
		echo "</td></tr></table></td></tr></table>";
	}
	else
	{
		echo "<tr class=\"rowheadings\">";
		echo "<td align=\"center\" width=\"25%\"><b>";
		$maxsortcol=3;
		$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
		echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
		echo "$l_date</a>";
		echo getSortMarker($sorting, 1, $maxsortcol);
		echo "</b></td>";
		echo "<td align=\"center\" width=\"20%\"><b>";
		$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
		echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
		echo "$l_category</a>";
		echo getSortMarker($sorting, 2, $maxsortcol);
		echo "</b></td>";
		echo "<td align=\"center\" width=\"20%\"><b>";
		$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
		echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
		echo "$l_language</a>";
		echo getSortMarker($sorting, 3, $maxsortcol);
		echo "</b></td>";
		echo "<td align=\"center\" width=\"25%\"><b>";
		echo "$l_poster";
		echo "</b></td>";
		echo "<td>&nbsp;</td>";
		echo "</tr>";
		do {
			$actid=$myrow["entrynr"];
			list($mydate,$mytime)=explode(" ",$myrow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
				$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
			else
				$displaydate="";
			if($myrow["date"]>$userdata["lastlogin"])
				echo "<tr class=\"displayrownew\">";
			else
				echo "<tr class=\"displayrow\">";
			if($myrow["category"]>0)
			{
				$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
				if(!$tmpresult = mysql_query($tmpsql, $db))
				{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
					die("Could not connect to the database.".mysql_error());
				}
				if ($tmprow = mysql_fetch_array($tmpresult))
					$catname=$tmprow["catname"];
				else
					$catname=$l_undefined;
			}
			else
				$catname=$l_general;
			echo "<td align=\"center\">".$displaydate."</td>";
			echo "<td align=\"center\">".$catname."</td>";
			echo "<td align=\"center\">".$myrow["lang"]."</td>";
			echo "<td align=\"center\">";
			if($myrow["posterid"]!=0)
			{
				$tmpsql="select * from ".$tableprefix."_poster where entrynr=".$myrow["posterid"];
				if(!$tmpresult=mysql_query($tmpsql,$db))
				{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
					die("Could not connect to the database. ".mysql_error());
				}
				if($tmprow=mysql_fetch_array($tmpresult))
					echo $tmprow["email"];
				else
					echo $l_unknown;
			}
			else
				echo $l_unknown;
			echo "</td>";
			echo "<td>";
			echo "<a href=\"".do_url_session("$act_script_url?input_entrynr=$actid&$langvar=$act_lang&mode=delete")."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
			echo "<a href=\"".do_url_session("$act_script_url?input_entrynr=$actid&$langvar=$act_lang&mode=display")."\">";
			echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
			echo "</td></tr>";
		} while($myrow = mysql_fetch_array($result));
		echo "</table></tr></td></table>";
	}
}
if($userdata["rights"]==2)
	$tmpsql="select cat.* from ".$tableprefix."_cat_adm ca, ".$tableprefix."_categories cat where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"];
else
	$tmpsql="select * from ".$tableprefix."_categories";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.".mysql_error());
if(mysql_num_rows($tmpresult)>0)
{
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
	echo "<tr class=\"optionrow\"><td valign=\"middle\" align=\"center\">$l_filterbycat:";
	echo "<select name=\"filtercat\">";
	echo "<option value=\"-1\"";
	if(!isset($filtercat) || ($filtercat==-1))
		echo " selected";
	echo ">$l_nofilter</option>";
	if(($userdata["rights"]>2) || bittst($secsettings,BIT_1))
	{
		echo "<option value=\"0\"";
		if(isset($filtercat) && ($filtercat==0))
			echo " selected";
		echo ">$l_general</option>";
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
}
include('./trailer.php');
?>