<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./includes/constants.inc');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_subscribers;
$page="subscribers";
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if ($myrow = mysql_fetch_array($result))
	$maxconfirmtime=$myrow["maxconfirmtime"];
else
	$maxconfirmtime=0;
$dateformat="Y-m-d H:i:s";
if(!isset($start))
	$start=0;
if(!isset($filtercat))
	$filtercat=-1;
if(!isset($sorting))
	$sorting=52;
if(!isset($filterconfirm))
	$filterconfirm=0;
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
		if(sn_array_key_exists($admcookievals,"sub_sorting"))
			$sorting=$admcookievals["sub_sorting"];
		if(sn_array_key_exists($admcookievals,"sub_filtercat"))
			$filtercat=$admcookievals["sub_filtercat"];
		if(sn_array_key_exists($admcookievals,"sub_filterconfirm"))
			$filterconfirm=$admcookievals["sub_filterconfirm"];
	}
}
if($admin_rights<$sublevel)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="export")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_asciiexport?></b></td></tr>
<?php
		echo "<form method=\"post\" action=\"sub_export.php\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		echo "<input type=\"hidden\" name=\"filtercat\" value=\"$filtercat\">";
		echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\" width=\"30%\">";
		echo $l_listfiletype.":</td><td valign=\"top\" align=\"left\">";
		echo "<input type=\"radio\" name=\"sepmode\" value=\"0\" checked>$l_emailperline<br>";
		echo "<input type=\"radio\" name=\"sepmode\" value=\"1\">$l_charsep: ";
		echo "<input type=\"text\" name=\"sepchar\" value=\",\" size=\"1\" maxlength=\"1\" class=\"sninput\">";
		echo "</td></tr>";
		echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\">";
		echo $l_crlftype.":</td><td valign=\"top\" align=\"left\">";
		for($i=0;$i<count($crlftypes);$i++)
		{
			echo "<input type=\"radio\" name=\"selectedcrlftype\" value=\"$i\"";
			if($crlf==$crlftypes[$i])
				echo " checked";
			echo ">".$crlftype_text[$i]."<br>";
		}
		echo "</td></tr>";
		echo "<tr class=\"actionrow\"><td colspan=\"2\" align=\"center\">";
		echo "<input type=\"submit\" name=\"dosubmit\" value=\"$l_ok\" class=\"snbutton\">";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
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
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editsubscriber?></b></td></tr>
<?php
		$sql = "select * from ".$tableprefix."_subscriptions where (subscriptionnr=$input_subscriptionnr)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		echo "<form method=\"post\" action=\"$act_script_url\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_subscriptionnr" value="<?php echo $input_subscriptionnr?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_email?>:</td><td>
<input type="text" class="sninput" name="email" value="<?php echo $myrow["email"]?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td>
<?php echo language_select($myrow["language"],"sublang","../language")?></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_category?>:</td><td>
<select name="category">
<option value="0" <?php if($myrow["category"]==0) echo "selected"?>><?php echo $l_all?></option>
<?php
		$tmpsql = "select * from ".$tableprefix."_categories";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("Could not connect to the database.");
		while($tmprow = mysql_fetch_array($tmpresult))
		{
			echo "<option value=\"".$tmprow["catnr"]."\"";
			if($tmprow["catnr"]==$myrow["category"])
				echo " selected";
			echo ">".$tmprow["catname"]."</option>";
		}
?>
</select></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_emailtype?>:</td><td>
<input type="radio" name="emailtype" value="0" <?php if($myrow["emailtype"]==0) echo "checked"?>> <?php echo $l_emailtypes[0]?><br>
<input type="radio" name="emailtype" value="1" <?php if($myrow["emailtype"]==1) echo "checked"?>> <?php echo $l_emailtypes[1]?>
</td></tr>
<?php
	if($myrow["confirmed"]==1)
	{
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\">$l_confirmed:</td><td>";
		echo "<img src=\"gfx/checkmark.gif\" border=\"0\">";
	}
	else
	{
		echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
		echo "<input type=\"checkbox\" value=\"1\" name=\"confirmation\"> $l_confirm";
	}
?>
</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_subscriptiondate?>:</td><td>
<?php
list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
list($year, $month, $day) = explode("-", $mydate);
list($hour, $min, $sec) = explode(":",$mytime);
$temptime=mktime($hour,$min,$sec,$month,$day,$year);
$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
$displaydate=date($l_admdateformat,$temptime);
echo $displaydate;
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="snbutton" type="submit" name="submit" value="<?php echo $l_submit?>"></td></tr>
</form>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
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
		$errors=0;
		if(!isset($emailtype))
			$emailtype=1;
		if(!isset($email) || !$email)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noemail</td></tr>";
			$errors=1;
		}
		else if(!validate_email($email))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noemail</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "update ".$tableprefix."_subscriptions set email='$email', emailtype=$emailtype, language='$sublang', category=$category ";
			if(isset($confirmation))
				$sql.=", confirmed=1 ";
			$sql.= "where subscriptionnr=$input_subscriptionnr";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_subscriberupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="massdel")
	{
		if(!isset($subscriptionnrs))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noentriesselected</td></tr>";
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
		else
		{
    		while(list($null, $input_subscriptionnr) = each($_POST["subscriptionnrs"]))
    		{
				$deleteSQL = "delete from ".$tableprefix."_subscriptions where (subscriptionnr=$input_subscriptionnr)";
				$success = mysql_query($deleteSQL);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_deleted<br>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
		}
	}
	if($mode=="impfile")
	{
		if(($admin_rights < 2) || (!$upload_avail))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_importemaillist?></b></td></tr>
<?php
		$errors=0;
		if($new_global_handling)
			$tmp_file=$HTTP_POST_FILES['maillist']['tmp_name'];
		else
			$tmp_file=$_FILES['maillist']['tmp_name'];
		if(is_uploaded_file($tmp_file))
		{
			if($new_global_handling)
			{
				$filename=$_FILES['maillist']['name'];
				$filesize=$_FILES['maillist']['size'];
			}
			else
			{
				$filename=$HTTP_POST_FILES['maillist']['name'];
				$filesize=$HTTP_POST_FILES['maillist']['size'];
			}
			$filedata="";
			if($filesize>0)
			{
				if(isset($path_tempdir) && $path_tempdir)
				{
					if(!move_uploaded_file ($tmp_file, $path_tempdir."/".$filename))
					{
						echo "<tr class=\"errorrow\"><td align=\"center\">";
						printf($l_cantmovefile,$path_attach."/".$physfile);
						echo "</td></tr>";
						die();
					}
					$orgfile=$path_tempdir."/".$filename;
				}
				else
					$orgfile=$tmp_file;
			}
		}
		if(!isset($orgfile) || ($filesize<1))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nolistfile</td></tr>";
			$errors=1;
		}
		if(($listtype==1) && (!$sepchar))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nosepchar</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if($listtype==1)
			{
				$imported=import_seplist($orgfile, $newslang, $emailtype, $sepchar, $newscat);
			}
			else
			{
				$imported=import_single_line($orgfile, $newslang, $emailtype, $newscat);
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo str_replace("{imported}",$imported,$l_listimported);
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="import")
	{
		if(($admin_rights < 2) || (!$upload_avail))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_importemaillist?></b></td></tr>
<form <?php if($upload_avail) echo "enctype=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="impfile">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_emaillistfile?>:</td>
<td><input class="sninput" type="file" name="maillist"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_listfiletype?>:</td>
<td><input type="radio" name="listtype" value="0" checked> <?php echo $l_emailperline?><hr>
<input type="radio" name="listtype" value="1"> <?php echo $l_charsep?>:
<input class="sninput" type="text" size="1" maxlength="1" name="sepchar" value=","></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td>
<td><?php echo language_select("","newslang","../language")?></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_category?>:</td>
<td><select name="newscat">
<option value="0"><?php echo $l_all?></option>
<?php
$tmpsql="select * from ".$tableprefix."_categories";
if(!$tmpresult = mysql_query($tmpsql, $db))
    die("Could not connect to the database.");
while($tmprow = mysql_fetch_array($tmpresult))
{
	echo "<option value=\"".$tmprow["catnr"]."\">".$tmprow["catname"]."</option>";
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
<input class="snbutton" type="submit" value="<?php echo $l_import?>"></td></tr>
<?php
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
	}
	if($mode=="display")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_subscriptions where (subscriptionnr=$input_subscriptionnr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_email?>:</td><td><?php echo $myrow["email"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_confirmed?>:</td><td>
<?php if($myrow["confirmed"]==1) echo "<img src=\"gfx/checkmark.gif\" border=\"0\">"?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_category?>:</td><td>
<?php
if($myrow["category"]==0)
	echo $l_all;
else
{
	$tmpsql = "select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("Could not connect to the database.");
	$tmprow = mysql_fetch_array($tmpresult);
	echo $tmprow["catname"];
}
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_emailtype?>:</td><td>
<?php echo $l_emailtypes[$myrow["emailtype"]]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_subscriptiondate?>:</td><td>
<?php
list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
list($year, $month, $day) = explode("-", $mydate);
list($hour, $min, $sec) = explode(":",$mytime);
$temptime=mktime($hour,$min,$sec,$month,$day,$year);
$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
$displaydate=date($l_admdateformat,$temptime);
echo $displaydate;
?>
</td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
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
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newsubscriber?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_email?>:</td>
<td><input class="sninput" type="text" name="email" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td>
<td><?php echo language_select("","newslang","../language")?></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_category?>:</td>
<td><select name="newscat">
<option value="0"><?php echo $l_all?></option>
<?php
$tmpsql="select * from ".$tableprefix."_categories";
if(!$tmpresult = mysql_query($tmpsql, $db))
    die("Could not connect to the database.");
while($tmprow = mysql_fetch_array($tmpresult))
{
	echo "<option value=\"".$tmprow["catnr"]."\">".$tmprow["catname"]."</option>";
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
<input class="snbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
<?php
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
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
		if(!isset($email) || !$email)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noemail</td></tr>";
			$errors=1;
		}
		else if(!validate_email($email))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noemail</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql="select * from ".$tableprefix."_subscriptions where email='$email' and (category=$newscat or category=0)";
			if(!$result = mysql_query($sql, $db))
				die("<tr bgcolor=\"#cccccc\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			if($myrow=mysql_fetch_array($result))
			{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
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
				$sql = "select * from ".$tableprefix."_subscriptions where unsubscribeid=$unsubscribeid";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			}while(mysql_num_rows($result)>0);
			$sql = "insert into ".$tableprefix."_subscriptions (email, confirmed, subscribeid, enterdate, emailtype, language, unsubscribeid, category, lastsent) ";
			$sql.= "values ('$email', $confirmed, $subscribeid, '$actdate', $emailtype, '$newslang', $unsubscribeid, $newscat, '$actdate')";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_subscriberadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_newsubscriber</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="fix")
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
		$confirmtime=($maxconfirmtime*24)+1;
		$sql = "select * from ".$tableprefix."_subscriptions where category!=0";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
		while($myrow=mysql_fetch_array($result))
		{
			$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			if(mysql_num_rows($tmpresult)<1)
			{
				$delsql="delete from ".$tableprefix."_subscriptions where subscriptionnr=".$myrow["subscriptionnr"];
				if(!$delresult = mysql_query($delsql, $db))
					die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			}
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_cleanedup<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
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
		$confirmtime=($maxconfirmtime*24)+1;
		$sql = "delete from ".$tableprefix."_subscriptions where confirmed=0 and enterdate<=DATE_SUB('$actdate', INTERVAL $confirmtime HOUR)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_cleanedup<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
	}
	if($mode=="delete")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(($admdelconfirm==1) && !isset($confirmed))
		{
			$tmpsql="select * from ".$tableprefix."_subscriptions where subscriptionnr=$input_subscriptionnr";
			if(!$tmpresult=mysql_query($tmpsql))
				die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			$subemail=$tmprow["email"];
?>
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="mode" value="delete">
<input type="hidden" name="input_subscriptionnr" value="<?php echo $input_subscriptionnr?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel: $l_subscriber #$input_subscriptionnr ($subemail)";
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_subscriptions where (subscriptionnr=$input_subscriptionnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
	}
	if($mode=="setmanual")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$actdate=date("Y-m-d H:i:s");
		$deleteSQL = "update ".$tableprefix."_subscriptions set lastmanual='$actdate' where (subscriptionnr=$input_subscriptionnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantupdate.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_updated<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($admin_rights < 2)
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		die("$l_functionnotallowed");
	}
$allowactions=false;
if($admin_rights >= 3)
	$allowactions=true;
if(($admin_rights == 2) && bittst($secsettings,BIT_5))
	$allowactions=true;
?>
<tr class="actionrow">
<td colspan="6" align="center">
<table width=\"100%\" align=\"center\"><tr>
<?php
if($allowactions)
{
?>
<td class="submenu"><a class="submenu" href="<?php echo do_url_session("$act_script_url?mode=cleanup&$langvar=$act_lang")?>"><?php echo $l_cleanupoverdue?></a></td>
<td class="submenu"><a class="submenu" href="<?php echo do_url_session("$act_script_url?mode=fix&$langvar=$act_lang")?>"><?php echo $l_cleanupnocat?></a></td>
<td class="submenu"><a class="submenu" href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newsubscriber?></a></td>
<?php
if($upload_avail)
{
	echo "<td class=\"submenu\"><a class=\"submenu\" href=\"".do_url_session("$act_script_url?mode=import&$langvar=$act_lang")."\">$l_importemaillist</a></td>";
}
}
if(($filtercat>=0) || ($userdata["rights"]>2))
{
?>
<td class="submenu"><a class="submenu" href="<?php echo do_url_session("$act_script_url?mode=export&filtercat=$filtercat&$langvar=$act_lang")?>"><?php echo $l_asciiexport?></a></td>
<?php
}
?>
<td class="submenu"><a class="submenu" href="<?php echo do_url_session("sub_stats.php?mode=new&$langvar=$act_lang")?>"><?php echo $l_stats?></a></td>
</tr></table></td></tr></table></td></tr></table>
<?php
include("./includes/sub_catfilter.inc");
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
// Display list of actual subscriptions
$sql = "select * from ".$tableprefix."_subscriptions ";
$firstarg=true;
if(isset($filterconfirm))
{
	switch($filterconfirm)
	{
		case 0:
			break;
		case 1:
			$sql.="where confirmed=0 ";
			$firstarg=false;
			break;
		case 2:
			$sql.="where confirmed=1 ";
			$firstarg=false;
			break;
	}
}
if(isset($filtercat) && ($filtercat>=0))
{
	if($firstarg)
	{
		$sql.="where category=$filtercat ";
		$firstarg=false;
	}
	else
		$sql.="and category=$filtercat ";
}
else if($userdata["rights"]==2)
{
	if(bittst($secsettings,BIT_10))
	{
		$sql.="where category=0 ";
		$firstarg=false;
	}
	$tmpsql="select * from ".$tableprefix."_cat_adm where usernr=".$userdata["usernr"];
	if(!$tmpresult = mysql_query($tmpsql, $db))
    	die("Could not connect to the database.");
    while($tmprow=mysql_fetch_array($tmpresult))
    {
    	if($firstarg)
    	{
    		$firstarg=false;
    		$sql.="where ";
    	}
    	else
    		$sql.="or ";
    	$sql.="category=".$tmprow["catnr"]." ";
    }
}
switch($sorting)
{
	case 11:
		$sql.="order by email asc";
		break;
	case 12:
		$sql.="order by email desc";
		break;
	case 21:
		$sql.="order by language asc";
		break;
	case 22:
		$sql.="order by language desc";
		break;
	case 31:
		$sql.="order by category asc";
		break;
	case 32:
		$sql.="order by category desc";
		break;
	case 41:
		$sql.="order by enterdate asc";
		break;
	case 51:
		$sql.="order by lastsent asc";
		break;
	case 52:
		$sql.="order by lastsent desc";
		break;
	case 61:
		$sql.="order by confirmed asc";
		break;
	case 62:
		$sql.="order by confirmed desc";
		break;
	case 71:
		$sql.="order by subscriptionnr asc";
		break;
	case 72:
		$sql.="order by subscriptionnr desc";
		break;
	default:
		$sql.="order by enterdate desc";
		break;
}
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.".mysql_error());
if($allowactions)
{
?>
<form name="subscriberlist" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="massdel">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
}
$numentries=mysql_num_rows($result);
if($admepp>0)
{
	echo "<tr><td colspan=\"9\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	if(($start>0) && ($numentries>$admepp))
	{
		$sql .=" limit $start,$admepp";
	}
	else
	{
		$sql .=" limit $admepp";
	}
	if(!$result = mysql_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		if(($admepp+$start)>$numentries)
			$displayresults=$numentries;
		else
			$displayresults=($admepp+$start);
		$displaystart=$start+1;
		$displayend=$displayresults;
		echo "<tr class=\"pagenav\"><td align=\"center\">";
		echo "<a id=\"list\"><b>$l_page ".ceil(($start/$admepp)+1)."/".ceil(($numentries/$admepp))."</b><br><b>($l_entries $displaystart - $displayend $l_of $numentries)</b></a>";
		echo "</td></tr>";
	}
	if($numentries>$admepp)
	{
		$baselink="$act_script_url?$langvar=$act_lang";
		echo "<tr class=\"pagenav\"><td align=\"center\">";
		echo "<b>$l_page</b> ";
		if(floor(($start+$admepp)/$admepp)>1)
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=0")."#list\">";
			echo "<img src=\"../gfx/first.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
			echo "</a> ";
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start-$admepp))."#list\">";
			echo "<img src=\"../gfx/prev.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
			echo "</a> ";
		}
		for($i=1;$i<($numentries/$admepp)+1;$i++)
		{
			if(floor(($start+$admepp)/$admepp)!=$i)
			{
				echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-1)*$admepp));
				echo "#list\"><b>[$i]</b></a> ";
			}
			else
				echo "<b>($i)</b> ";
		}
		if($start < (($i-2)*$admepp))
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start+$admepp))."#list\">";
			echo "<img src=\"../gfx/next.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
			echo "</a> ";
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-2)*$admepp))."#list\">";
			echo "<img src=\"../gfx/last.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
			echo "</a> ";
		}
		echo "</font></td></tr>";
	}
	echo "</table></td></tr>";
}
?>
<tr class="rowheadings">
<?php
$baseurl=$act_script_url."?".$langvar."=".$act_lang;
if($admstorefilter==1)
	$baseurl.="&dostorefilter=1";
if(isset($filtercat))
	$baseurl.="&filtercat=$filtercat";
if($allowactions)
	echo "<td width=\"2%\">&nbsp;</td>";
$maxsortcol=7;
$sorturl=getSortURL($sorting, 7, $maxsortcol, $baseurl);
echo "<td align=\"center\" width=\"2%\"><b>";
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "#</a>";
echo getSortMarker($sorting, 7, $maxsortcol);
echo "</b></td>";
$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
echo "<td align=\"center\" width=\"20%\"><b>";
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "$l_email</a>";
echo getSortMarker($sorting, 1, $maxsortcol);
echo "</b></td>";
$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
echo "<td align=\"center\" width=\"10%\"><b>";
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "$l_language</a>";
echo getSortMarker($sorting, 2, $maxsortcol);
echo "</b></td>";
$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
echo "<td align=\"center\" width=\"10%\"><b>";
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "$l_category</a>";
echo getSortMarker($sorting, 3, $maxsortcol);
echo "</b></td>";
echo "<td align=\"center\" width=\"5%\">";
$sorturl=getSortURL($sorting, 6, $maxsortcol, $baseurl);
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "<b>$l_confirmed</a>";
echo getSortMarker($sorting, 6, $maxsortcol);
echo "</b></td>";
$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl);
echo "<td align=\"center\" width=\"10%\"><b>";
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "$l_date</a>";
echo getSortMarker($sorting, 4, $maxsortcol);
echo "</b></td>";
$sorturl=getSortURL($sorting, 5, $maxsortcol, $baseurl);
echo "<td align=\"center\" width=\"10%\"><b>";
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "$l_lastmanual</a>";
echo getSortMarker($sorting, 5, $maxsortcol);
echo "</b></td>";
echo "<td width=\"10%\">&nbsp;</td></tr>";
	if (!$myrow = mysql_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"8\">";
		echo $l_noentries;
		echo "</td></tr></form></table></td></tr></table>";
	}
	else
	{
		do {
			$act_id=$myrow["subscriptionnr"];
			echo "<tr class=\"displayrow\">";
			if($allowactions)
			{
				echo "<td align=\"center\">";
				echo "<input type=\"checkbox\" name=\"subscriptionnrs[]\" value=\"$act_id\">";
				echo "</td>";
			}
			echo "<td align=\"right\">".$myrow["subscriptionnr"]."</td>";
			echo "<td align=\"center\">".$myrow["email"]."</td>";
			echo "<td align=\"center\">";
			echo $myrow["language"];
			echo "</td>";
			echo "<td align=\"center\">";
			if($myrow["category"]==0)
				echo $l_all;
			else
			{
				$tmpsql = "select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("Could not connect to the database.");
				if($tmprow = mysql_fetch_array($tmpresult))
					echo $tmprow["catname"];
				else
					echo $l_undefined." (".$myrow["category"].")";
			}
			echo "</td>";
			echo "<td align=\"center\">";
			if($myrow["confirmed"]==1)
				echo "<img src=\"gfx/checkmark.gif\" border=\"0\">";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td align=\"center\">";
			list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
			$displaydate=date($l_admdateformat,$temptime);
			echo $displaydate;
			echo "</td>";
			echo "<td align=\"center\">";
			if($myrow["lastmanual"]!="0000-00-00 00:00:00")
			{
				list($mydate,$mytime)=explode(" ",$myrow["lastmanual"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				$temptime=mktime($hour,$min,$sec,$month,$day,$year);
				$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
				$displaydate=date($l_admdateformat,$temptime);
				echo $displaydate;
			}
			else if ($admin_rights >= $nllevel)
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=setmanual&input_subscriptionnr=$act_id&$langvar=$act_lang")."\">$l_set</a>";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td>";
			if($allowactions)
			{
				$dellink=do_url_session("$act_script_url?mode=delete&input_subscriptionnr=$act_id&$langvar=$act_lang");
				if($admdelconfirm==2)
					echo "<a class=\"listlink2\" href=\"javascript:confirmDel('$l_subscriber #$act_id','$dellink')\">";
				else
					echo "<a class=\"listlink2\" href=\"$dellink\" valign=\"top\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&input_subscriptionnr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a> ";
			}
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_subscriptionnr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
			if(($myrow["confirmed"]==1) && ($admin_rights >= $nllevel))
			{
				echo " <a class=\"listlink\" href=\"".do_url_session("newsmailer.php?input_subscriptionnr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/sendmail.gif\" border=\"0\" title=\"$l_sendnews\" alt=\"$l_sendnews\"></a>";
			}
		} while($myrow = mysql_fetch_array($result));
		if($allowactions)
		{
			echo "<tr class=\"actionrow\"><td colspan=\"10\" align=\"left\"><input class=\"snbutton\" type=\"submit\" value=\"$l_delselected\">";
			echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"checkAll(document.subscriberlist)\" value=\"$l_checkall\">";
			echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"uncheckAll(document.subscriberlist)\" value=\"$l_uncheckall\">";
			echo "</td></tr>";
		}
		echo "</form></table></tr></td></table>";
	}
if(($admepp>0) && ($numentries>$admepp))
{
	$baselink="$act_script_url?$langvar=$act_lang";
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"pagenav\"><td align=\"center\">";
	echo "<b>$l_page</b> ";
	if(floor(($start+$admepp)/$admepp)>1)
	{
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=0")."#list\">";
		echo "<img src=\"../gfx/first.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start-$admepp))."#list\">";
		echo "<img src=\"../gfx/prev.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
		echo "</a> ";
	}
	for($i=1;$i<($numentries/$admepp)+1;$i++)
	{
		if(floor(($start+$admepp)/$admepp)!=$i)
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-1)*$admepp));
			echo "#list\"><b>[$i]</b></a> ";
		}
		else
			echo "<b>($i)</b> ";
	}
	if($start < (($i-2)*$admepp))
	{
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start+$admepp))."#list\">";
		echo "<img src=\"../gfx/next.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-2)*$admepp))."#list\">";
		echo "<img src=\"../gfx/last.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
		echo "</a> ";
	}
	echo "</font></td></tr></table></td></tr></table>";
}
include("./includes/sub_catfilter.inc");
?>
<table class="actionbox" width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0"><tr><td>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="actionrow">
<td colspan="6" align="center">
<table width=\"100%\" align=\"center\"><tr>
<?php
if($allowactions)
{
?>
<td class="submenu"><a class="submenu" href="<?php echo do_url_session("$act_script_url?mode=cleanup&$langvar=$act_lang")?>"><?php echo $l_cleanupoverdue?></a></td>
<td class="submenu"><a class="submenu" href="<?php echo do_url_session("$act_script_url?mode=fix&$langvar=$act_lang")?>"><?php echo $l_cleanupnocat?></a></td>
<td class="submenu"><a class="submenu" href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newsubscriber?></a></td>
<?php
if($upload_avail)
{
	echo "<td class=\"submenu\"><a class=\"submenu\" href=\"".do_url_session("$act_script_url?mode=import&$langvar=$act_lang")."\">$l_importemaillist</a></td>";
}
}
if(($filtercat>=0) || ($userdata["rights"]>2))
{
?>
<td class="submenu"><a class="submenu" href="<?php echo do_url_session("$act_script_url?mode=export&filtercat=$filtercat&$langvar=$act_lang")?>"><?php echo $l_asciiexport?></a></td>
<?php
}
?>
<td class="submenu"><a class="submenu" href="<?php echo do_url_session("sub_stats.php?mode=new&$langvar=$act_lang")?>"><?php echo $l_stats?></a></td>
</tr></table></td></tr></table></td></tr></table>
<?php
}
include_once('./trailer.php');
function import_single_line($inputfile, $newslang, $emailtype, $newscat)
{
	global $tableprefix, $db;

	$emailfile=fopen($inputfile,"r");
	$maxsize=filesize($inputfile);
	$imported=0;
	while($fileLine=fgets($emailfile,$maxsize))
	{
		$fileLine=str_replace("\n","",$fileLine);
		$fileLine=str_replace("\r","",$fileLine);
		$fileLine=trim($fileLine);
		if(strlen($fileLine)<1)
			continue;
		$sql="select * from ".$tableprefix."_subscriptions where email='$fileLine' and (category=$newscat or category=0)";
		if(!$result = mysql_query($sql, $db))
			die("Could not connect to the database.");
		if(!$myrow=mysql_fetch_array($result))
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
				$sql = "select * from ".$tableprefix."_subscriptions where unsubscribeid=$unsubscribeid";
				if(!$result = mysql_query($sql, $db))
					die("Could not connect to the database.");
			}while(mysql_num_rows($result)>0);
			$sql = "insert into ".$tableprefix."_subscriptions (email, confirmed, subscribeid, enterdate, emailtype, language, unsubscribeid, category, lastsent) ";
			$sql.= "values ('$fileLine', $confirmed, $subscribeid, '$actdate', $emailtype, '$newslang', $unsubscribeid, $newscat, '$actdate')";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database.".mysql_error());
			$imported++;
		}
	}
	return $imported;
}

function import_seplist($inputfile, $newslang, $emailtype, $sepchar, $newscat)
{
	global $tableprefix, $db;

	$filedata=get_file($inputfile);
	$filedata=str_replace("\n","",$filedata);
	$filedata=str_replace("\r","",$filedata);
	$emails=explode($sepchar,$filedata);
	$imported=0;
	for($i=0;$i<count($emails);$i++)
	{
		$actmail=trim($emails[$i]);
		if(strlen($actmail)<1)
			continue;
		$sql="select * from ".$tableprefix."_subscriptions where email='$actmail' and (category=$newscat or category=0)";
		if(!$result = mysql_query($sql, $db))
			die("Could not connect to the database.");
		if(!$myrow=mysql_fetch_array($result))
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
				$sql = "select * from ".$tableprefix."_subscriptions where unsubscribeid=$unsubscribeid";
				if(!$result = mysql_query($sql, $db))
					die("Could not connect to the database.");
			}while(mysql_num_rows($result)>0);
			$sql = "insert into ".$tableprefix."_subscriptions (email, confirmed, subscribeid, enterdate, emailtype, language, unsubscribeid, category, lastsent) ";
			$sql.= "values ('$actmail', $confirmed, $subscribeid, '$actdate', $emailtype, '$newslang', $unsubscribeid, $newscat, '$actdate')";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database.".mysql_error());
			$imported++;
		}
	}
	return $imported;
}
?>