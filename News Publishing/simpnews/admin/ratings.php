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
$page_title=$l_defratings;
$page="ratings";
require_once('./heading.php');
if(!isset($filterlang))
	$filterlang="all";
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
		if(sn_array_key_exists($admcookievals,"ratings_filterlang"))
			$filterlang=$admcookievals["ratings_filterlang"];	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="new")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_newrating?></b></td></tr>
<form name="inputform" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_value?>:</td><td width="80%">
<select name="rating">
<?php
	for($i=0;$i<11;$i++)
		echo "<option value=\"$i\">$i</option>";
?>
</select></td></tr>
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_language?>:</td><td width="80%">
<?php echo language_select($act_lang, "rating_language", "../language/")?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_text?>:<br></td>
<td><input type="text" class="sninput" name="ratingtext" size="40" maxlength="40">
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="add">
<input class="snbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_defratings</a></div>";
	}
	if($mode=="edit")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_ratings where rating=$input_rating and lang='$ratinglang'";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editrating?></b></td></tr>
<form name="inputform" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		echo "<input type=\"hidden\" name=\"input_rating\" value=\"$input_rating\">";
		echo "<input type=\"hidden\" name=\"ratinglang\" value=\"$ratinglang\">";
?>
<tr class="displayrow"><td align="right" width="20%"><?php echo $l_value?>:</td><td width="80%">
<?php echo $myrow["rating"]?>
</td></tr>
<tr class="displayrow"><td align="right" width="20%"><?php echo $l_language?>:</td><td width="80%">
<?php echo $myrow["lang"]?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_text?>:</td>
<td><input type="text" name="ratingtext" size="40" maxlength="40" class="sninput" value="<?php echo display_encoded($myrow["text"])?>">
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="update">
<input class="snbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_defratings</a></div>";
	}
	if($mode=="update")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(!$ratingtext)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_notext</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "update ".$tableprefix."_ratings set text='$ratingtext' where rating=$input_rating and lang='$ratinglang'";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to update database.");
			echo "<tr class=\"displayrow\"><td align=\"center\">";
			echo "$l_ratingupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_defratings</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="add")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(!$ratingtext)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_notext</td></tr>";
			$errors=1;
		}
		$checksql="select * from ".$tableprefix."_ratings where rating='$rating' and lang='$rating_language'";
		if(!$result = mysql_query($checksql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if(mysql_numrows($result)>0)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_ratingexists</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "insert into ".$tableprefix."_ratings (rating, lang, text) values ('$rating', '$rating_language', '$ratingtext')";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to add rating to database.");
			echo "<tr class=\"displayrow\"><td align=\"center\">";
			echo "$l_ratingadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_defratings</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="delete")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_ratings where rating=$input_rating and lang='$ratinglang'";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_deleted<br>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_defratings</a></div>";
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($admin_rights>2)
	{
?>
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newrating?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
$sql = "select * from ".$tableprefix."_ratings ";
if(isset($filterlang) && ($filterlang!="all"))
	$sql.="where lang='$filterlang' ";
$sql.="order by lang asc, rating asc";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database. (".mysql_error().")");
}
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"20%\"><b>";
	echo "$l_value";
	echo "</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>";
	echo "$l_language";
	echo "</b></td>";
	echo "<td align=\"center\" width=\"50%\"><b>";
	echo "$l_text";
	echo "</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\">".$myrow["rating"]."</td>";
		echo "<td align=\"center\">".$myrow["lang"]."</td>";
		echo "<td align=\"center\">".display_encoded($myrow["text"])."</td>";
		echo "<td>";
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_rating=".$myrow["rating"]."&ratinglang=".$myrow["lang"])."\">";
		echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a> ";
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=delete&$langvar=$act_lang&input_rating=".$myrow["rating"]."&ratinglang=".$myrow["lang"])."\">";
		echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
   } while($myrow = mysql_fetch_array($result));
}
echo "</table></tr></td></table>";
include('./includes/language_filter.inc');
if($admin_rights > 2)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newrating?></a></div>
<?php
}
}
include('./trailer.php');
?>