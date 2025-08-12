<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
require('../config.php');
require('./auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
$page_title=$l_featurerequests;
$page="requests";
require('./heading.php');
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
	$dateformat=$myrow["dateformat"];
else
	$dateformat="Y-m-d";
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
		if(psys_array_key_exists($admcookievals,"req_prognr"))
			$prognr=$admcookievals["req_prognr"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="edit")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_feature_requests where (requestnr=$input_requestnr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
		$tempsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$tempresult = mysql_query($tempsql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr bgcolor=\"#cccccc\"><td>Database inconsitency error");
		$displaytext=stripslashes($myrow["request"]);
		$displaytext = undo_htmlspecialchars($displaytext);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editrequest?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="input_requestnr" value="<?php echo $input_requestnr?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="mode" value="update">
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo stripslashes($temprow["programmname"])." [".$temprow["language"]."]"?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_request?>:</td>
<td><textarea class="psysinput" name="requesttext" rows="5" cols="40"><?php echo $displaytext?></textarea></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_publish?>:</td>
<td><input type="checkbox" name="dopublish" value="1" <?php if($myrow["publish"]==1) echo "checked"?>></td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form></table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_featurerequests</a>";
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
<?php
		if(isset($dopublish))
			$publish=1;
		else
			$publish=0;
		$errors=0;
		if(!$requesttext)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_norequest</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$requesttext = htmlentities($requesttext);
			$requesttext = str_replace("\n", "<BR>", $requesttext);
			$requesttext=addslashes($requesttext);
			$sql = "update ".$tableprefix."_feature_requests set publish=$publish, request='$requesttext' where requestnr=$input_requestnr";
			if(!$result = mysql_query($sql, $db))
				die("Unable to update feature request in database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_requestupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_featurerequests</a>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
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
		if(isset($action) && ($action=="publish"))
		{
			if($admin_rights < 2)
			{
				echo "<tr class=\"actionrow\"><td align=\"center\">";
				die("$l_functionnotallowed");
			}
			if(isset($dopublish))
				$publishrequest=1;
			else
				$publishrequest=0;
			$sql = "update ".$tableprefix."_feature_requests set publish=$publishrequest where requestnr=$input_requestnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"actionrow\"><td>Could not connect to the database.");
		    echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_updated</td></tr>";
		}
		$sql = "select * from ".$tableprefix."_feature_requests where (requestnr=$input_requestnr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$tempsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$tempresult = mysql_query($tempsql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr class=\"errorrow\"><td>Database inconsitency error");
		list($year, $month, $day) = explode("-", $myrow["enterdate"]);
		if($month>0)
			$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		$displaytext=stripslashes($myrow["request"]);
		$displaytext = undo_htmlspecialchars($displaytext);
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displayrequest?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo stripslashes($temprow["programmname"])." [".$temprow["language"]."]"?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_request?>:</td>
<td><?php echo $displaytext?></td></tr>
<?php
		echo "<tr class=\"displayrow\"><td align=\"right\">$l_date:</td>";
		echo "<td>$displaydate</td></tr>";
		if($myrow["comment"])
		{
			$displaycomment=stripslashes($myrow["comment"]);
			$displaycomment = undo_htmlspecialchars($displaycomment);
			echo "<tr class=\"displayrow\"><td align=\"right\">$l_commentext:</td>";
			echo "<td>$displaycomment</td></tr>";
		}
		echo "<tr class=\"displayrow\"><td align=\"right\">$l_releasestate:</td>";
		echo "<td>".$l_releasestates[$myrow["releasestate"]]."</td></tr>";
		if($admin_rights>1)
		{
			echo "<tr class=\"displayrow\"><td align=\"right\">$l_email:</td>";
			echo "<td>".$myrow["email"]."</td></tr>";
			echo "<tr class=\"displayrow\"><td align=\"right\">$l_ipadr:</td>";
			echo "<td>".$myrow["ipadr"]."</td></tr>";
			$rating=$myrow["rating"];
			$ratingcount=$myrow["ratingcount"];
			if($ratingcount>0)
			{
				echo "<tr class=\"displayrow\"><td align=\"right\" valign=\"top\">$l_rating:</td><td>";
				echo $l_ratings[round($rating/$ratingcount,2)]."<br>";
				echo round($rating/$ratingcount,2);
				echo " ($ratingcount)";
				echo "</td></tr>";
			}
?>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="mode" value="display">
<input type="hidden" name="action" value="publish">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="input_requestnr" value="<?php echo $input_requestnr?>">
<?php
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right"><?php echo $l_publish?>:</td>
<td><input type="checkbox" name="dopublish" value="1" <?php if($myrow["publish"]==1) echo "checked"?>>
&nbsp;&nbsp;<input class="psysbutton" type="submit" value="<?php echo $l_ok?>">
</td></tr></form>
<?php
		}
?>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_featurerequests</a>";
		if($admin_rights>1)
		{
			echo "<br>";
			$dellink=do_url_session("$act_script_url?lang=$lang&mode=delete&input_requestnr=".$myrow["requestnr"]);
			if($admdelconfirm==1)
				echo "<a class=\"listlink\" href=\"javascript:doconfirm('$l_confirmdel','$dellink')\">";
			else
				echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
			echo "$l_deleteentry</a>";
		}
		echo "</div>";
	}
	if($mode=="comment")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_feature_requests where (requestnr=$input_requestnr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
		$tempsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$tempresult = mysql_query($tempsql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr bgcolor=\"#cccccc\"><td>Database inconsitency error");
		list($year, $month, $day) = explode("-", $myrow["enterdate"]);
		if($month>0)
			$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		$displaytext=stripslashes($myrow["request"]);
		$displaytext = undo_htmlspecialchars($displaytext);
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_requestcomment?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo stripslashes($temprow["programmname"])." [".$temprow["language"]."]"?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_request?>:</td>
<td><?php echo $displaytext?></td></tr>
<?php
		echo "<tr class=\"displayrow\"><td align=\"right\">$l_date:</td>";
		echo "<td>$displaydate</td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"right\">$l_email:</td>";
		echo "<td>".$myrow["email"]."</td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"right\">$l_ipadr:</td>";
		echo "<td>".$myrow["ipadr"]."</td></tr>";
		$rating=$myrow["rating"];
		$ratingcount=$myrow["ratingcount"];
		if($ratingcount>0)
		{
			echo "<tr class=\"displayrow\"><td align=\"right\" valign=\"top\">$l_rating:</td><td>";
			echo $l_ratings[round($rating/$ratingcount,2)]."<br>";
			echo round($rating/$ratingcount,2);
			echo " ($ratingcount)";
			echo "</td></tr>";
		}
		$displaycomment=stripslashes($myrow["comment"]);
		$displaycomment = undo_htmlspecialchars($displaycomment);
?>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="mode" value="postcomment">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="input_requestnr" value="<?php echo $input_requestnr?>">
<?php
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right"><?php echo $l_publish?>:</td>
<td><input type="checkbox" name="dopublish" value="1" <?php if($myrow["publish"]==1) echo "checked"?>>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_releasestate?>:</td>
<td><select name="releasestate">
<?php
for($i=0;$i<count($l_releasestates);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$myrow["releasestate"])
		echo " selected";
	echo ">".$l_releasestates[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_comment?>:</td>
<td><textarea class="psysinput" name="commenttext" rows="5" cols="40"><?php echo $displaycomment?></textarea></td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form></table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_featurerequests</a>";
		echo "</div>";
	}
	if($mode=="postcomment")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$commenttext = htmlentities($commenttext);
		$commenttext = str_replace("\n", "<BR>", $commenttext);
		$commenttext=addslashes($commenttext);
		if(isset($dopublish))
			$publish=1;
		else
			$publish=0;
		$sql = "update ".$tableprefix."_feature_requests set publish=$publish, comment='$commenttext', releasestate=$releasestate where requestnr=$input_requestnr";
		if(!$result = mysql_query($sql, $db))
			die("Unable to update feature request in database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_requestupdated";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_featurerequests</a>";
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
		$deleteSQL = "delete from ".$tableprefix."_feature_requests where (requestnr=$input_requestnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_featurerequests</a>";
		echo "</div>";
	}
}
else
{
if(($admin_rights > 1) && ($topfilter==1))
{
	echo "</td></tr></table>";
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).".mysql_error());
	}
	if ($temprow = mysql_fetch_array($result1))
	{
?>
<table class="filterbox" align="center" width="50%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="prognr">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
	do {
		$progname=htmlentities($temprow["programmname"]);
		$proglang=$temprow["language"];
		echo "<option value=\"".$temprow["prognr"]."\"";
		if(isset($prognr))
		{
			if($prognr==$temprow["prognr"])
				echo " selected";
		}
		echo ">";
		echo "$progname [$proglang]";
		echo "</option>";
	} while($temprow = mysql_fetch_array($result1));
?>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
	}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(isset($prognr) && ($prognr>=0))
{
	$tempsql="select * from ".$tableprefix."_programm where prognr=$prognr";
	if(!$tempresult = mysql_query($tempsql, $db)) {
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	}
	if($temprow=mysql_fetch_array($tempresult))
		echo "<tr class=\"errorrow\"><td colspan=\"5\" align=\"center\">$l_onlyprog: <i>".$temprow["programmname"]."</i></td></tr>";
	$sql = "select * from ".$tableprefix."_feature_requests where programm=$prognr ";
	if($admin_rights<2)
		$sql.="where publish=1 ";
	$sql.= "order by programm asc, enterdate desc";
}
else
{
	$sql = "select * from ".$tableprefix."_feature_requests ";
	if($admin_rights<2)
		$sql.="where publish=1 ";
	$sql.= "order by programm asc, enterdate desc";
}
if(!$result = mysql_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
}
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\"><b>#</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_programm</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_date</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_rating</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_releasestate</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$tempsql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$tempresult = mysql_query($tempsql, $db)) {
		    die("Could not connect to the database.");
		}
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr class=\"errorrow\"><td>Database inconsitency error");
		$act_id=$myrow["requestnr"];
		if($myrow["enterdate"]>$userdata["lastlogin"])
			echo "<tr class=\"displayrownew\">";
		else
			echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\">".$myrow["requestnr"]."</td>";
		list($year, $month, $day) = explode("-", $myrow["enterdate"]);
		if($month>0)
			$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		echo "<td align=\"center\">".$temprow["programmname"]." [".$temprow["language"]."]</td>";
		echo "<td align=\"center\">".$displaydate."</td>";
		echo "<td align=\"center\">";
		$rating=$myrow["rating"];
		$ratingcount=$myrow["ratingcount"];
		if($ratingcount>0)
		{
			echo $l_ratings[round($rating/$ratingcount,2)]."<br>";
			echo round($rating/$ratingcount,2);
			echo " ($ratingcount)";
		}
		else
			echo "--";
		echo "</td><td align=\"center\">";
		echo $l_releasestates[$myrow["releasestate"]];
		echo "</td><td>";
		if($admin_rights > 1)
		{
			$modsql="select * from ".$tableprefix."_programm_admins where (prognr=".$temprow["prognr"].") and (usernr=".$userdata["usernr"].")";
			if(!$modresult = mysql_query($modsql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(($modrow = mysql_fetch_array($modresult)) || ($admin_rights > 2))
			{
				$dellink=do_url_session("$act_script_url?mode=delete&input_requestnr=$act_id&lang=$lang");
				if($admdelconfirm==1)
					echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_request #$act_id','$dellink')\">";
				else
					echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=comment&input_requestnr=$act_id&lang=$lang")."\">";
				echo "<img src=\"gfx/comment.gif\" border=\"0\" title=\"$l_comment\" alt=\"$l_comment\"></a> ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&input_requestnr=$act_id&lang=$lang")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a> ";
			}
		}
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_requestnr=$act_id&lang=$lang")."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
		echo "</td></tr>";
   } while($myrow = mysql_fetch_array($result));
   echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).".mysql_error());
	}
	if ($temprow = mysql_fetch_array($result1))
	{
?>
<br>
<table class="filterbox" align="center" width="50%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="prognr">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
	do {
		$progname=htmlentities($temprow["programmname"]);
		$proglang=$temprow["language"];
		echo "<option value=\"".$temprow["prognr"]."\"";
		if(isset($prognr))
		{
			if($prognr==$temprow["prognr"])
				echo " selected";
		}
		echo ">";
		echo "$progname [$proglang]";
		echo "</option>";
	} while($temprow = mysql_fetch_array($result1));
?>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
	}
}
}
include('trailer.php');
?>