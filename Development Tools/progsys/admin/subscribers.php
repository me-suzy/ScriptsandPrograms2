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
$page_title=$l_subscribers;
$page="subscribers";
require('./heading.php');
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if ($myrow = mysql_fetch_array($result))
{
	$dateformat=$myrow["dateformat"];
	$dateformat.=" H:i:s";
	$enablehostresolve=$myrow["enablehostresolve"];
	$automscheck=$myrow["automscheck"];
}
else
{
	$dateformat="Y-m-d H:i:s";
	$enablehostresolve=1;
}
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
		if(psys_array_key_exists($admcookievals,"sub_prognr"))
			$filterprog=$admcookievals["sub_prognr"];
		if(psys_array_key_exists($admcookievals,"sub_state"))
			$filterstate=$admcookievals["sub_state"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="display")
	{
		if($admin_rights < 2)
		{
			echo "<tr bgcolor=\"#cccccc\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select nl.*, prog.programmname, prog.language as proglang from ".$tableprefix."_newsletter nl, ".$tableprefix."_programm prog where nl.entrynr=$input_entrynr and prog.prognr=nl.programm";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_name?>:</td><td><?php echo $myrow["subscribername"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_email?>:</td><td><?php echo $myrow["email"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo $myrow["programmname"]." [".$myrow["proglang"]."]"?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_confirmed?>:</td><td>
<?php if($myrow["confirmed"]==1) echo "<img src=\"gfx/checkmark.gif\" border=\"0\">"?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_emailtype?>:</td><td>
<?php echo $l_emailtypes[$myrow["emailtype"]]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_subscriptiondate?>:</td><td><?php echo $myrow["enterdate"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_subscriptionip?>:</td><td><?php echo $myrow["userip"]?>
<?php
if($enablehostresolve==1)
{
	$hostname=gethostname($myrow["userip"],$db,false);
	if(strlen($hostname)<1)
	{
		echo " <a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=resolve&input_entrynr=$input_entrynr&lang=$lang&from=display")."\">";
		echo "$l_determine_hostname</a>";
	}
	else
		echo " ($hostname)";
}
?>
</td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_subscribers</a></div>";
	}
	if($mode=="new")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_newsubscriber?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_name?>:</td>
<td><input class="psysinput" type="text" name="subscribername" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_email?>:</td>
<td><input class="psysinput" type="text" name="email" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td>
<?php
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr and prog.enablenewsletter=1 order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog  where prog.enablenewsletter=1 order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).".mysql_error());
	}
	if (!$temprow = mysql_fetch_array($result1))
	{
		echo "<a href=\"".do_url_session("program.php?mode=new&lang=$lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="programm">
<option value="-1">???</option>
<?php
		do {
			$progname=htmlentities($temprow["programmname"]);
			$proglang=$temprow["language"];
			echo "<option value=\"".$temprow["prognr"]."\">";
			echo "$progname [$proglang]";
			echo "</option>";
		} while($temprow = mysql_fetch_array($result1));
	}
?>
</select></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_emailtype?>:</td><td>
<?php
for($i=0;$i<count($l_emailtypes);$i++)
{
	echo "<input type=\"radio\" name=\"emailtype\" value=\"$i\"";
	if($i==0)
		echo " checked";
	echo "> ".$l_emailtypes[$i]."<br>";
}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="add">
<input class="psysbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
<?php
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_subscribers</a></div>";
	}
	if($mode=="resolve")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if($enablehostresolve!=1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_newsletter where entrynr=$input_entrynr";
		if(!$result = mysql_query($sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
		if ($myrow = mysql_fetch_array($result))
		{
			$acthostname=gethostname($myrow["userip"],$db,true);
		}
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_hostnames_resolved";
		echo "</td></tr></table></td></tr></table>";
		if(isset($from) && ($from=="display"))
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang&mode=display&input_entrynr=".$input_entrynr)."\">$l_displaysubscriber</a></div>";
		else
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_subscribers</a></div>";
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
		$errors=0;
		if(!isset($emailtype))
			$emailtype=1;
		if($programm<0)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
			$newsletterfreemailer=1;
		}
		else
		{
			$sql = "select * from ".$tableprefix."_programm where prognr=$programm";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database (3).".mysql_error());
			if ($myrow = mysql_fetch_array($result))
			{
				$newsletterfreemailer=$myrow["newsletterfreemailer"];
			}
		}
		if(!isset($email) || !$email)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noemail</td></tr>";
			$errors=1;
		}
		else if(!validate_email($email))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noemail</td></tr>";
			$errors=1;
		}
		else
		{
			if($newsletterfreemailer==0)
			{
				if (forbidden_freemailer($email, $db))
				{
					echo "<tr class=\"errorrow\" align=\"center\"><td>";
					echo "$l_forbidden_freemailer</td></tr>";
					$errors=1;
				}
			}
		}
		if($errors==0)
		{
			$sql="select * from ".$tableprefix."_newsletter where email='$email' and programm=$programm";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			if($myrow=mysql_fetch_array($result))
			{
					echo "<tr class=\"errorrow\" align=\"center\"><td>";
					echo "$l_subscriptionexists</td></tr>";
					$errors=1;
			}
		}
		if($errors==0)
		{
			$actdate = date("Y-m-d H:i:s");
			$confirmed=1;
			$subscribeid=0;
			do{
				$maximum=9999999999;
				if($maximum>mt_getrandmax())
					$maximum=mt_getrandmax();
				mt_srand((double)microtime()*1000000);
				$unsubscribeid=mt_rand(10000,$maximum);
				$sql = "select * from ".$tableprefix."_newsletter where unsubscribeid=$unsubscribeid";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			}while($myrow=mysql_fetch_array($result));
			$sql = "insert into ".$tableprefix."_newsletter (email, confirmed, subscribeid, enterdate, emailtype, programm, unsubscribeid, subscribername) ";
			$sql.= "values ('$email', $confirmed, $subscribeid, '$actdate', $emailtype, $programm, $unsubscribeid, '$subscribername')";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_subscriberadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&lang=$lang")."\">$l_newsubscriber</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_subscribers</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="cleanup")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$actdate = date("Y-m-d H:i:s");
		if(isset($filterprog) && ($filterprog>0))
			$sql = "select * from ".$tableprefix."_programm where prognr=$filterprog";
		else
			$sql = "select * from ".$tableprefix."_programm where maxconfirmtime>0";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			do{
				$confirmtime=($myrow["maxconfirmtime"]*24)+1;
				$sql2 = "delete from ".$tableprefix."_newsletter where confirmed=0 and enterdate<=DATE_SUB('$actdate', INTERVAL $confirmtime HOUR) and programm=".$myrow["prognr"];
				if(!$result2 = mysql_query($sql2, $db))
					die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			}while($myrow=mysql_fetch_array($result));
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_cleanedup<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_subscribers</a></div>";
	}
	if($mode=="delete")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_newsletter where (entrynr=$input_entrynr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_subscribers</a></div>";
	}
}
else
{
	if($admin_rights < 2)
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		die("$l_functionnotallowed");
	}
	if(isset($smode))
	{
		if($smode=="mscheck")
		{
			$sql="select * from ".$tableprefix."_newsletter where entrynr=$input_entrynr";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database (3).".mysql_error());
			if($myrow=mysql_fetch_array($result))
			{
				$act_id=$myrow["entrynr"];
				if(mailserver_exists($myrow["email"]))
					$tmpsql="update ".$tableprefix."_newsletter set mscheck=2 where entrynr=$act_id";
				else
					$tmpsql="update ".$tableprefix."_newsletter set mscheck=1 where entrynr=$act_id";
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			}
		}
	}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($admin_rights>1)
	{
		if(isset($filterprog) && ($filterprog>0))
			$cleanupurl="$act_script_url?mode=cleanup&lang=$lang&filterprog=$filterprog";
		else
			$cleanupurl="$act_script_url?mode=cleanup&lang=$lang";
?>
<tr class="actionrow">
<td colspan="6" align="center">
<a href="<?php echo do_url_session($cleanupurl)?>"><?php echo $l_cleanup?></a><br>
<a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newsubscriber?></a>
</td></tr>
</table></td></tr></table>
<?php
		if($topfilter==1)
		{
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
			if($admstorefilter==1)
				echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			if($admin_rights<3)
				$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
			else
				$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
			if(!$result1 = mysql_query($sql1, $db))
				die("Could not connect to the database (3).".mysql_error());
			if ($temprow = mysql_fetch_array($result1))
			{
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="filterprog">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
				do {
					$progname=htmlentities($temprow["programmname"]);
					$proglang=$temprow["language"];
					echo "<option value=\"".$temprow["prognr"]."\"";
					if(isset($filterprog))
					{
						if($filterprog==$temprow["prognr"])
							echo " selected";
					}
					echo ">";
					echo "$progname [$proglang]";
					echo "</option>";
				} while($temprow = mysql_fetch_array($result1));
			}
?>
</select></td><td>&nbsp;</td></tr>
<tr><td align="right" width="50%" valign="top"><?php echo $l_statefilter?>:</td>
<td align="left"><select name="filterstate">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
			echo "<option value=\"0\"";
			if(isset($filterstate) && ($filterstate==0))
				echo " selected";
			echo ">$l_unconfirmed</option>";
			echo "<option value=\"1\"";
			if(isset($filterstate) && ($filterstate==1))
				echo " selected";
			echo ">$l_confirmed</option>";
?>
</select></td><td align="left" width="10%"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
		}
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
			if($admstorefilter==1)
				echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
			if(isset($filterstate))
				echo "<input type=\"hidden\" name=\"filterstate\" value=\"$filterstate\">";
			if(isset($filterprog))
				echo "<input type=\"hidden\" name=\"filterprog\" value=\"$filterprog\">";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>	
<tr><td align="right" width="30%"><?php echo $l_searchemail?>:</td><td>
<input type="text" class="psysinput" size="40" maxlength="240" name="srchmail"></td>
<td align="left" width="10%"><input type="submit" value="<?php echo $l_search?>" class="psysbutton"</td></tr>
<?php
		if(isset($srchmail) && (strlen($srchmail)>0))
		{
			echo "<tr class=\"infrow\"><td colspan=\"3\" align=\"center\">";
			echo "<b>".$l_searchedemail.": ".$srchmail."</td></tr>";
		}
?>
</form></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
	$issearch=false;
	if(isset($srchmail) && (strlen($srchmail)>0))
		$issearch=true;
	if(isset($filterprog) && ($filterprog>0) && !$issearch)
		$sql = "select nl.*, prog.programmname, prog.language as proglang from ".$tableprefix."_newsletter nl, ".$tableprefix."_programm prog where nl.programm=$filterprog and prog.prognr=nl.programm ";
	else if($admin_rights < 3)
		$sql = "select nl.*, prog.programmname, prog.language as proglang from ".$tableprefix."_newsletter nl, ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where pa.usernr=".$userdata["usernr"]." and prog.prognr=pa.prognr and prog.prognr=nl.programm ";
	else
		$sql = "select nl.*, prog.programmname, prog.language as proglang from ".$tableprefix."_newsletter nl, ".$tableprefix."_programm prog where prog.prognr=nl.programm ";
	if(isset($filterstate) && ($filterstate>=0) && !$issearch)
	{
		if($filterstate==0)
			$sql.="and nl.confirmed=0 ";
		else
			$sql.="and nl.confirmed=1 ";
	}
	if(isset($srchmail) && (strlen($srchmail)>0))
		$sql.="and nl.email like '%".$srchmail."%' ";
	$sql.="order by nl.enterdate, nl.listtype desc";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
?>
<tr class="rowheadings">
<td align="center" width="5%"><b>#</b></td>
<td align="center" width="30%"><b><?php echo $l_email?></b></td>
<td align="center" width="5%"><b><?php echo $l_confirmed?></b></td>
<td align="center" width="25%"><b><?php echo $l_programm?></b></td>
<td align="center" width="25%"><b><?php echo $l_date?></b></td>
<td width="10%">&nbsp;</td></tr>
<?php
	if (!$myrow = mysql_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"6\">";
		echo $l_noentries;
		echo "</td></tr></table></td></tr></table>";
	}
	else
	{
		do {
			$act_id=$myrow["entrynr"];
			echo "<tr class=\"displayrow\">";
			echo "<td align=\"right\">".$myrow["entrynr"]."</td>";
			echo "<td align=\"left\">";
			if($myrow["mscheck"]==0)
			{
				if($automscheck==1)
				{
					if(mailserver_exists($myrow["email"]))
					{
						$tmpsql="update ".$tableprefix."_newsletter set mscheck=2 where entrynr=$act_id";
						echo "<img src=\"gfx/msok.gif\" border=\"0\" title=\"$l_msok\"> ";
					}
					else
					{
						$tmpsql="update ".$tableprefix."_newsletter set mscheck=1 where entrynr=$act_id";
						echo "<img src=\"gfx/msbad.gif\" border=\"0\" title=\"$l_msbad\"> ";
					}						
					if(!$tmpresult = mysql_query($tmpsql, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				}
				else if($admin_rights>1)
					echo "<a class=\"listlink\" title=\"$l_checkmailserver\" href=\"".do_url_session("$act_script_url?smode=mscheck&lang=$lang&input_entrynr=$act_id")."\">";
			}
			else if($myrow["mscheck"]==2)
				echo "<img src=\"gfx/msok.gif\" border=\"0\" title=\"$l_msok\"> ";
			else
				echo "<img src=\"gfx/msbad.gif\" border=\"0\" title=\"$l_msbad\"> ";
			echo $myrow["email"];
			if(($myrow["mscheck"]==0) && ($automscheck==0) && ($admin_rights>1))
				echo "</a>";
			echo "</td>";
			echo "<td align=\"center\">";
			if($myrow["confirmed"]==1)
				echo "<img src=\"gfx/checkmark.gif\" border=\"0\">";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td align=\"center\">";
			echo $myrow["programmname"]." [".$myrow["proglang"]."]";
			if($myrow["listtype"]==1)
				echo " (Beta)";
			echo "</td>";
			echo "<td align=\"center\">";
			echo $myrow["enterdate"];
			echo "</td>";
			echo "<td>";
			$dellink=do_url_session("$act_script_url?mode=delete&input_entrynr=$act_id&lang=$lang");
			if($admdelconfirm==1)
				echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_subscription #$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_entrynr=$act_id&lang=$lang")."\">";
			echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
	   } while($myrow = mysql_fetch_array($result));
	   echo "</table></tr></td></table>";
	}
if($admin_rights > 1)
{
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db))
		die("Could not connect to the database (3).".mysql_error());
	if ($temprow = mysql_fetch_array($result1))
	{
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="filterprog">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
		do {
			$progname=htmlentities($temprow["programmname"]);
			$proglang=$temprow["language"];
			echo "<option value=\"".$temprow["prognr"]."\"";
			if(isset($filterprog))
			{
				if($filterprog==$temprow["prognr"])
					echo " selected";
			}
			echo ">";
			echo "$progname [$proglang]";
			echo "</option>";
		} while($temprow = mysql_fetch_array($result1));
?>
</select></td><td>&nbsp;</td></tr>
<tr><td align="right" width="50%" valign="top"><?php echo $l_statefilter?>:</td>
<td align="left" width="30%"><select name="filterstate">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
	echo "<option value=\"0\"";
	if(isset($filterstate) && ($filterstate==0))
		echo " selected";
	echo ">$l_unconfirmed</option>";
	echo "<option value=\"1\"";
	if(isset($filterstate) && ($filterstate==1))
		echo " selected";
	echo ">$l_confirmed</option>";
?>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
	}
	if(isset($filterprog) && ($filterprog>0))
		$cleanupurl="$act_script_url?mode=cleanup&lang=$lang&filterprog=$filterprog";
	else
		$cleanupurl="$act_script_url?mode=cleanup&lang=$lang";
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session($cleanupurl)?>"><?php echo $l_cleanup?></a></div>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newsubscriber?></a></div>
<?php
}
}
include('trailer.php');
?>