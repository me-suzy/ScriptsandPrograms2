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
$page_title=$l_kbcategory_title;
$page="kb_cats";
require_once('./heading.php');
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
			if(faqe_array_key_exists($admcookievals,"kbc_filterprog"))
				$filterprog=$admcookievals["kbc_filterprog"];
			if(faqe_array_key_exists($admcookievals,"kbc_filterlang"))
				$filterlang=$admcookievals["kbc_filterlang"];
			if(faqe_array_key_exists($admcookievals,"kbc_sorting"))
				$sorting=$admcookievals["kbc_sorting"];
	}
}
if(!isset($sorting))
	$sorting=11;
if(!isset($filterprog))
	$filterprog=-1;
if(!isset($filterlang))
	$filterlang="none";
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
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_kb_cat where (catnr=$input_catnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$displayheading = display_encoded($myrow["heading"]);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displaycats?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_catname?>:</td><td><?php echo display_encoded($myrow["catname"])?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_heading?>:</td><td><?php echo $displayheading?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_programm?>:</td>
<td>
<?php
	$sql = "select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database (3).");
	}
	if ($temprow = faqe_db_fetch_array($result))
		echo display_encoded($temprow["programmname"])." [".$temprow["language"]."]";
?>
</td></tr>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
	}
	// Page called with some special mode
	if($mode=="new")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		// Display empty form for entering category
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newcategory?></b></td></tr>
<form name="inputform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_catname?>:</td><td><input class="faqeinput" type="text" name="category" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_heading?>:</td><td><input class="faqeinput" type="text" name="heading" size="40" maxlength="250"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_programm?>:</td>
<td>
<?php
	$firstarg=true;
	if($admin_rights<3)
	{
		$sql = "select pr.* from ".$tableprefix."_programm pr, ".$tableprefix."_programm_admins pa where pr.prognr = pa.prognr and pa.usernr=$act_usernr ";
		$firstarg=false;
	}
	else
		$sql = "select pr.* from ".$tableprefix."_programm pr ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterlang) && ($filterlang!="none"))
		{
			if($firstarg)
			{
				$firstarg=false;
				$sql.="where ";
			}
			else
				$sql.="and ";
			$sql.="pr.language='$filterlang' ";
		}
	}
	$sql.="order by pr.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql.=", pr.programmname asc";
	else
		$sql.=", pr.displaypos asc";
	if(!$result = faqe_db_query($sql, $db)) {
		die("Could not connect to the database (3).");
	}
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<a href=\"".do_url_session("program.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="programm">
<option value="-1">???</option>
<?php
	do {
		echo "<option value=\"".$temprow["prognr"]."\"";
		if(bittst($admedoptions,BIT_1) && isset($filterprog) && ($filterprog>=0) && ($filterprog==$temprow["prognr"]))
			echo " selected";
		echo ">";
		echo display_encoded($temprow["programmname"]);
		echo " | ";
		echo stripslashes($temprow["language"]);
		echo "</option>";
	} while($temprow = faqe_db_fetch_array($result));
?>
</select>
<?php
	}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="add">
<input class="faqebutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_catlist?></a></div>
<?php
	}
	if($mode=="add")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		// Add new category to database
		$errors=0;
		if(!$category)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocatname</td></tr>";
			$errors=1;
		}
		if(!$heading)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noheading</td></tr>";
			$errors=1;
		}
		if($programm<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$heading=addslashes($heading);
			$sql = "select max(displaypos) as newdisplaypos from ".$tableprefix."_kb_cat where programm=$programm";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add category to database.");
			if($myrow=faqe_db_fetch_array($result))
				$displaypos=$myrow["newdisplaypos"]+1;
			else
				$displaypos=1;
			$heading=addslashes($heading);
			$heading=do_htmlentities($heading);
			$sql = "INSERT INTO ".$tableprefix."_kb_cat (catname, heading, programm, displaypos) ";
			$sql .="VALUES ('$category', '$heading', '$programm', $displaypos)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add category to database.");
			$catnr = faqe_db_insert_id($db);
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_catadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_newcategory</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
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
		$deleteSQL = "delete from ".$tableprefix."_kb_cat where (catnr=$input_catnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "<i>$catname</i> $l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
	}
	if($mode=="edit")
	{
		$modsql="select pa.* from ".$tableprefix."_programm_admins pa, ".$tableprefix."_kb_cat cat where cat.catnr=$input_catnr and pa.prognr=cat.programm and pa.usernr=$act_usernr";
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
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
			include('./trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_kb_cat where (catnr=$input_catnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$displayheading = display_encoded($myrow["heading"]);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editcats?></b></td></tr>
<form name="inputform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="input_catnr" value="<?php echo $myrow["catnr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_catname?>:</td><td><input class="faqeinput" type="text" name="category" size="40" maxlength="80" value="<?php echo display_encoded($myrow["catname"])?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_heading?>:</td><td><input class="faqeinput" type="text" name="heading" size="40" maxlength="250" value="<?php echo $displayheading?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_programm?>:</td>
<td><input type="hidden" name="oldprog" value="<?php echo $myrow["programm"]?>">
<?php
	$firstarg=true;
	if($admin_rights<3)
	{
		$sql = "select pr.* from ".$tableprefix."_programm pr, ".$tableprefix."_programm_admins pa where pr.prognr = pa.prognr and pa.usernr=$act_usernr ";
		$firstarg=false;
	}
	else
		$sql = "select pr.* from ".$tableprefix."_programm pr ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterlang) && ($filterlang!="none"))
		{
			if($firstarg)
			{
				$firstarg=false;
				$sql.="where ";
			}
			else
				$sql.="and ";
			$sql.="pr.language='$filterlang' ";
		}
	}
	$sql.="order by pr.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql.=", pr.programmname asc";
	else
		$sql.=", pr.displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
		die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database (3).");
	if (!$temprow = faqe_db_fetch_array($result))
		echo "<a href=\"".do_url_session("program.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	else
	{
?>
<select name="programm">
<option value="-1">???</option>
<?php
	do {
		echo "<option value=\"".$temprow["prognr"]."\"";
		if($myrow["programm"]==$temprow["prognr"])
			echo " selected";
		echo ">";
		echo display_encoded($temprow["programmname"]);
		echo " | ";
		echo stripslashes($temprow["language"]);
		echo "</option>";
	} while($temprow = faqe_db_fetch_array($result));
?>
</select>
<?php
	}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update">
<input class="faqebutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></tr></td></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_catlist?></a></div>
<?php
	}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($mode=="update")
	{
		$modsql="select pa.* from ".$tableprefix."_programm_admins pa, ".$tableprefix."_kb_cat cat where cat.catnr=$input_catnr and pa.prognr=cat.programm and pa.usernr=$act_usernr";
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
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
			include('./trailer.php');
			exit;
		}
		$errors=0;
		if(!$category)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocatname</td></tr>";
			$errors=1;
		}
		if($programm<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if(!$heading)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noheading</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "UPDATE ".$tableprefix."_kb_cat SET catname='$category', heading='$heading', programm='$programm' ";
			$sql .=" WHERE (catnr = $input_catnr)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_catupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
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
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newcategory?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
// Display list of actual kb categories
$sql = "select * from ".$tableprefix."_kb_cat cat, ".$tableprefix."_programm prog where cat.programm=prog.prognr ";
if(isset($filterprog) && ($filterprog>0))
	$sql.="and prog.prognr=$filterprog ";
if(isset($filterlang) && ($filterlang!="none"))
	$sql.="and prog.language='$filterlang' ";
switch($sorting)
{
	case 12:
		$sql.= "order by cat.catnr desc";
		break;
	case 21:
		$sql.="order by cat.catname asc";
		break;
	case 22:
		$sql.="order by cat.catname desc";
		break;
	case 31:
		$sql.="order by prog.programmname asc";
		break;
	case 32:
		$sql.="order by prog.programmname desc";
		break;
	default:
		$sql.= "order by cat.catnr asc";
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
	$maxsortcol=3;
	$baseurl="$act_script_url?$langvar=$act_lang";
	if(isset($filterprog))
		$baseurl.="&filterprog=$filterprog";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\">";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>#</b></a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"40%\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_catname</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"40%\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_progname</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td class=\"rowheadings\" align=\"center\" width=\"10%\"><b>$l_language</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$act_id=$myrow["catnr"];
		$act_prog=$myrow["programm"];
		$displayheading = display_encoded($myrow["heading"]);
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"right\">".$myrow["catnr"]."</td>";
		echo "<td>".display_encoded($myrow["catname"])."<br>";
		echo "<font size=\"2\"><i>$displayheading</i></font></td>";
		$tempsql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$tempresult = faqe_db_query($tempsql, $db)) {
		    die("Could not connect to the database.");
		}
		if (!$temprow = faqe_db_fetch_array($tempresult))
		{
			$progame=$l_undefined;
			$proglang=$l_none;
		}
		else
		{
			$progname=display_encoded($temprow["programmname"]);
			$proglang=$temprow["language"];
		}
		echo "<td>$progname</td>";
		echo "<td align=\"center\">$proglang</td>";
		echo "<td>";
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=".$myrow["programm"]." and usernr=$act_usernr";
		if(!$modresult = faqe_db_query($modsql, $db)) {
		    die("Could not connect to the database.");
		}
		if($modrow=faqe_db_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights>2) || ($ismod==1))
		{
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_catnr=$act_id&$langvar=$act_lang&catname=".urlencode($myrow["catname"]))."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&input_catnr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
			echo "&nbsp; ";
		}
		echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&input_catnr=$act_id&$langvar=$act_lang&catname=".urlencode($myrow["catname"]))."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" alt=\"$l_display\" title=\"$l_display\"></a>";
		echo "</td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
	include('./includes/prog_filterbox.inc');
	include('./includes/language_filterbox.inc');
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newcategory?></a></div>
<?php
}
}
include('./trailer.php');
?>