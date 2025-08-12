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
$page_title=$l_subscribers;
$page="subscribers";
require_once('./heading.php');
if(!isset($start))
	$start=0;
if(!isset($filterprog))
	$filterprog=-1;
if(!isset($filterlang))
	$filterlang="none";
if(!isset($sorting))
	$sorting=42;
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
		if(faqe_array_key_exists($admcookievals,"sub_filterprog"))
			$filterprog=$admcookievals["sub_filterprog"];
		if(faqe_array_key_exists($admcookievals,"sub_filterlang"))
			$filterlang=$admcookievals["sub_filterlang"];
		if(faqe_array_key_exists($admcookievals,"sub_sorting"))
			$sorting=$admcookievals["sub_sorting"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
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
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
?>
<form method="post" action="<?php echo $act_script_url?>">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_subscriptionnr" value="<?php echo $input_subscriptionnr?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_email?>:</td><td>
<input type="text" class="faqeinput" name="email" value="<?php echo $myrow["email"]?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td><td>
<?php echo language_select($myrow["language"],"sublang","../language")?></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td><td>
<select name="progid">
<?php
		$tmpsql = "select * from ".$tableprefix."_programm where subscriptionavail=1 group by progid";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("Could not connect to the database.");
		while($tmprow = faqe_db_fetch_array($tmpresult))
		{
			echo "<option value=\"".$tmprow["progid"]."\"";
			if($tmprow["progid"]==$myrow["progid"])
				echo " selected";
			echo ">".display_encoded($tmprow["programmname"])."</option>";
		}
?>
</select></td></tr>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_emailtype?>:</td><td>
<input type="radio" name="emailtype" value="0" <?php if($myrow["emailtype"]==0) echo "checked"?>> <?php echo $l_emailtypes[0]?><br>
<input type="radio" name="emailtype" value="1" <?php if($myrow["emailtype"]==1) echo "checked"?>> <?php echo $l_emailtypes[1]?>
</td></tr>
<?php
		if($zlibavail==1)
		{
			echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
			echo "<input type=\"checkbox\" name=\"compress\" value=\"1\"";
			if($myrow["compression"]==1)
				echo " checked";
			echo "> $l_sendcompressed</td></tr>";
		}
		else
			if($myrow["compression"]==1)
				echo "<input type=\"hidden\" name=\"compress\" value=\"1\">";
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_confirmed?>:</td><td>
<?php if($myrow["confirmed"]==1) echo "<img src=\"gfx/checkmark.gif\" border=\"0\">"?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_subscriptiondate?>:</td><td><?php echo $myrow["enterdate"]?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="faqebutton" type="submit" name="submit" value="<?php echo $l_submit?>"></td></tr>
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
		$tmpsql = "select * from ".$tableprefix."_programm where progid='$progid' and language='$sublang'";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
		    die("Could not connect to the database.");
		if(faqe_db_num_rows($tmpresult)<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noproglang</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($compress))
				$compression=1;
			else
				$compression=0;
			$sql = "update ".$tableprefix."_subscriptions set email='$email', emailtype=$emailtype, language='$sublang', progid='$progid', compression=$compression where subscriptionnr=$input_subscriptionnr";
			if(!$result = faqe_db_query($sql, $db))
				die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
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
				$success = faqe_db_query($deleteSQL,$db);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_deleted<br>";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
			}
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
			$tmp_file=$_FILES['maillist']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['maillist']['tmp_name'];
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
						$errmsg="<tr class=\"errorrow\"><td align=\"center\">";
						$errmsg.= sprintf($l_cantmovefile,$path_tempdir."/".$filename);
						$errmsg.="</td></tr>";
						die($errmsg);
					}
					$orgfile=$path_tempdir."/".$filename;
				}
				else
					$orgfile=$tmp_file;
			}
		}
		if(!isset($filename) || ($filesize<1))
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
		$tmpsql = "select * from ".$tableprefix."_programm where progid='$progid' and language='$sublang'";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
		    die("Could not connect to the database.");
		if(faqe_db_num_rows($tmpresult)<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noproglang</td></tr>";
			$errors=1;
		}
		if(isset($compress))
			$compression=1;
		else
			$compression=0;
		if($errors==0)
		{
			if($listtype==1)
			{
				$imported=import_seplist($orgfile, $sublang, $emailtype, $sepchar, $progid, $compression);
			}
			else
			{
				$imported=import_single_line($orgfile, $sublang, $emailtype, $progid, $compression);
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
<td><input class="faqefile" type="file" name="maillist"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_listfiletype?>:</td>
<td><input type="radio" name="listtype" value="0" checked> <?php echo $l_emailperline?><hr>
<input type="radio" name="listtype" value="1"> <?php echo $l_charsep?>:
<input class="faqeinput" type="text" size="1" maxlength="1" name="sepchar" value=","></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td>
<td><?php echo language_select("","sublang","../language")?></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><select name="progid">
<?php
$tmpsql="select * from ".$tableprefix."_programm where subscriptionavail=1 group by progid";
if(!$tmpresult = faqe_db_query($tmpsql, $db))
    die("Could not connect to the database.");
while($tmprow = faqe_db_fetch_array($tmpresult))
{
	echo "<option value=\"".$tmprow["progid"]."\">".display_encoded($tmprow["programmname"])."</option>";
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
<?php
		if($zlibavail==1)
		{
			echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
			echo "<input type=\"checkbox\" name=\"compress\" value=\"1\" checked>";
			echo "$l_sendcompressed</td></tr>";
		}
?>
<tr class="actionrow"><td align="center" colspan="2">
<input class="faqebutton" type="submit" value="<?php echo $l_import?>"></td></tr>
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
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_email?>:</td><td><?php echo $myrow["email"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_confirmed?>:</td><td>
<?php if($myrow["confirmed"]==1) echo "<img src=\"gfx/checkmark.gif\" border=\"0\">"?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td><td>
<?php
$tmpsql = "select * from ".$tableprefix."_programm where progid='".$myrow["progid"]."' and language='".$myrow["language"]."'";
if(!$tmpresult = faqe_db_query($tmpsql, $db))
	die("Could not connect to the database.");
$tmprow = faqe_db_fetch_array($tmpresult);
echo $tmprow["programmname"];
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_emailtype?>:</td><td>
<?php echo $l_emailtypes[$myrow["emailtype"]]?></td></tr>
<?php
if($zlibavail==1)
{
	echo "<tr class=\"displayrow\"><td align=\"right\">$l_sendcompressed:</td><td>";
	if($myrow["compression"]==1)
		echo "<img src=\"gfx/checkmark.gif\" border=\"0\">";
	echo "</td></tr>";
}
?>
<tr class="displayrow"><td align="right"><?php echo $l_subscriptiondate?>:</td><td><?php echo $myrow["enterdate"]?></td></tr>
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
<td><input class="faqeinput" type="text" name="email" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td>
<td><?php echo language_select("","subscriptionlang","../language")?></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><select name="progid">
<?php
$tmpsql="select * from ".$tableprefix."_programm where subscriptionavail=1 group by progid";
if(!$tmpresult = faqe_db_query($tmpsql, $db))
    die("Could not connect to the database.");
while($tmprow = faqe_db_fetch_array($tmpresult))
{
	echo "<option value=\"".$tmprow["progid"]."\">".display_encoded($tmprow["programmname"])."</option>";
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
<?php
		if($zlibavail==1)
		{
			echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
			echo "<input type=\"checkbox\" name=\"compress\" value=\"1\" checked>";
			echo "$l_sendcompressed</td></tr>";
		}
?>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="add">
<input class="faqebutton" type="submit" value="<?php echo $l_add?>"></td></tr>
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
		$tmpsql = "select * from ".$tableprefix."_programm where progid='$progid' and language='$subscriptionlang'";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
		    die("Could not connect to the database.");
		if(faqe_db_num_rows($tmpresult)<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noproglang</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql="select * from ".$tableprefix."_subscriptions where email='$email' and 'progid=$progid' and language='$subscriptionlang'";
			if(!$result = faqe_db_query($sql, $db))
				die("<tr bgcolor=\"#cccccc\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			if($myrow=faqe_db_fetch_array($result))
			{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
					echo "$l_subscriptionexists</td></tr>";
					$errors=1;
			}
		}
		if($errors==0)
		{
			if(isset($compress))
				$compression=1;
			else
				$compression=0;
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
				if(!$result = faqe_db_query($sql, $db))
					die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			}while($myrow=faqe_db_fetch_array($result));
			$sql = "insert into ".$tableprefix."_subscriptions (email, confirmed, subscribeid, enterdate, emailtype, language, unsubscribeid, progid, compression) ";
			$sql.= "values ('$email', $confirmed, $subscribeid, '$actdate', $emailtype, '$subscriptionlang', $unsubscribeid, '$progid', $compression)";
			if(!$result = faqe_db_query($sql, $db))
				die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
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
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_cleanedup<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subscribers</a></div>";
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
		$deleteSQL = "delete from ".$tableprefix."_subscriptions where (subscriptionnr=$input_subscriptionnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
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
?>
<tr class="actionrow">
<td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=cleanup&$langvar=$act_lang")?>"><?php echo $l_cleanupoverdue?></a>&nbsp;&nbsp;
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newsubscriber?></a>&nbsp;&nbsp;
<a href="<?php echo do_url_session("sub_stats.php?mode=new&$langvar=$act_lang")?>"><?php echo $l_stats?></a>
<?php
if($upload_avail)
{
	echo "&nbsp;&nbsp;";
	echo "<a href=\"".do_url_session("$act_script_url?mode=import&$langvar=$act_lang")."\">$l_importemaillist</a>";
}
?>
</td></tr>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
// Display list of actual subscriptions
	$sql = "select * from ".$tableprefix."_subscriptions ";
	$firstarg=true;
	if(isset($filterprog) && ($filterprog!=-1))
	{
		$tmpsql = "select * from ".$tableprefix."_programm where prognr=$filterprog";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$tmprow = faqe_db_fetch_array($tmpresult))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		$filterprogid=$tmprow["progid"];
		if($firstarg)
		{
			$firstarg=false;
			$sql.="where progid='$filterprogid' ";
		}
	}
	if(isset($filterlang) && ($filterlang!="none"))
	{
		if($firstarg)
		{
			$firstarg=false;
			$sql.="where ";
		}
		else
			$sql.="and ";
		$sql.="language='$filterlang' ";
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
			$sql.="order by progid asc";
			break;
		case 32:
			$sql.="order by progid desc";
			break;
		case 41:
			$sql.="order by enterdate asc";
			break;
		case 42:
			$sql.="order by enterdate desc";
			break;
	}
	if(!$result = faqe_db_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if($admin_rights > 1)
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
$numentries=faqe_db_num_rows($result);
?>
<tr class="rowheadings">
<?php
if($admin_rights > 1)
	echo "<td>&nbsp;</td>";
$maxsortcol=4;
$baseurl=$act_script_url."?".$langvar."=".$act_lang;
if(isset($filterlang))
	$baseurl.="&filterlang=$filterlang";
if(isset($filterprog))
	$baseurl.="&filterprog=$filterprog";
if($admstorefaqfilters==1)
	$baseurl.="&storefaqfilter=1";
echo "<td align=\"center\" width=\"40%\">";
$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
echo "<b>$l_email</b></a>";
echo getSortMarker($sorting, 1, $maxsortcol);
echo "</td>";
echo "<td align=\"center\" width=\"5%\">";
$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
echo "<b>$l_language</b></a>";
echo getSortMarker($sorting, 2, $maxsortcol);
echo "</td>";
echo "<td align=\"center\" width=\"10%\">";
$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
echo "<b>$l_programm</b></a>";
echo getSortMarker($sorting, 3, $maxsortcol);
echo "</td>";
echo "<td align=\"center\" width=\"5%\"><b>$l_confirmed</b></td>";
echo "<td align=\"center\" width=\"20%\">";
$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl);
echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
echo "<b>$l_date</b></a>";
echo getSortMarker($sorting, 4, $maxsortcol);
echo "</td>";
echo "<td width=\"10%\">&nbsp;</td></tr>";
	if (!$myrow = faqe_db_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"7\">";
		echo $l_noentries;
		echo "</td></tr></form></table></td></tr></table>";
	}
	else
	{
		do {
			$act_id=$myrow["subscriptionnr"];
			echo "<tr class=\"displayrow\">";
			if($admin_rights > 1)
			{
				echo "<td align=\"center\" width=\"2%\">";
				echo "<input type=\"checkbox\" name=\"subscriptionnrs[]\" value=\"$act_id\">";
				echo "</td>";
			}
			echo "<td width=\"40%\">".$myrow["email"]."</td>";
			echo "<td width=\"5%\" align=\"center\">";
			echo $myrow["language"];
			echo "</td>";
			echo "<td width=\"10%\" align=\"center\">";
			$tmpsql = "select * from ".$tableprefix."_programm where progid='".$myrow["progid"]."' and language='".$myrow["language"]."'";
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				die("Could not connect to the database.");
			$tmprow = faqe_db_fetch_array($tmpresult);
			echo $tmprow["programmname"];
			echo "</td>";
			echo "<td width=\"5%\" align=\"center\">";
			if($myrow["confirmed"]==1)
				echo "<img src=\"gfx/checkmark.gif\" border=\"0\">";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td width=\"20%\" align=\"center\">";
			echo $myrow["enterdate"];
			echo "</td>";
			echo "<td>";
			if($admin_rights > 1)
			{
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=delete&input_subscriptionnr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>";
				echo "&nbsp; ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&input_subscriptionnr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a>";
				echo "&nbsp; ";
			}
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_subscriptionnr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
		} while($myrow = faqe_db_fetch_array($result));
		if($admin_rights > 1)
		{
			echo "<tr class=\"actionrow\"><td colspan=\"10\" align=\"left\"><input class=\"faqebutton\" type=\"submit\" value=\"$l_delselected\">";
			echo "&nbsp; <input class=\"faqebutton\" type=\"button\" onclick=\"checkAll(document.subscriberlist)\" value=\"$l_checkall\">";
			echo "&nbsp; <input class=\"faqebutton\" type=\"button\" onclick=\"uncheckAll(document.subscriberlist)\" value=\"$l_uncheckall\">";
			echo "</td></tr></form>";
		}
		echo "</form></table></tr></td></table>";
	}
if(($admin_rights > 1) && ($maxconfirmtime>0))
{
?>
<div class="bottombox" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=cleanup&$langvar=$act_lang")?>"><?php echo $l_cleanupoverdue?></a>&nbsp;&nbsp;
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newsubscriber?></a>&nbsp;&nbsp;
<a href="<?php echo do_url_session("sub_stats.php?mode=new&$langvar=$act_lang")?>"><?php echo $l_stats?></a>
<?php
if($upload_avail)
{
	echo "&nbsp;&nbsp;";
	echo "<a href=\"".do_url_session("$act_script_url?mode=import&$langvar=$act_lang")."\">$l_importemaillist</a>";
}
?>
</div>
<?php
}
include_once('./includes/language_filterbox.inc');
include_once('./includes/prog_filterbox.inc');
}
include_once('./trailer.php');
function import_single_line($inputfile, $sublang, $emailtype, $progid, $compression)
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
		$sql="select * from ".$tableprefix."_subscriptions where email='$fileLine'";
		if(!$result = faqe_db_query($sql, $db))
			die("Could not connect to the database.");
		if(!$myrow=faqe_db_fetch_array($result))
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
				if(!$result = faqe_db_query($sql, $db))
					die("Could not connect to the database.");
			}while($myrow=faqe_db_fetch_array($result));
			$sql = "insert into ".$tableprefix."_subscriptions (email, confirmed, subscribeid, enterdate, emailtype, language, unsubscribeid, progid, compression) ";
			$sql.= "values ('$fileLine', $confirmed, $subscribeid, '$actdate', $emailtype, '$sublang', $unsubscribeid, '$progid', $compression)";
			if(!$result = faqe_db_query($sql, $db))
				die("Could not connect to the database.");
			$imported++;
		}
	}
	return $imported;
}

function import_seplist($inputfile, $newslang, $emailtype, $sepchar, $progid, $compression)
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
		$sql="select * from ".$tableprefix."_subscriptions where email='$actmail'";
		if(!$result = faqe_db_query($sql, $db))
			die("Could not connect to the database.");
		if(!$myrow=faqe_db_fetch_array($result))
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
				if(!$result = faqe_db_query($sql, $db))
					die("Could not connect to the database.");
			}while($myrow=faqe_db_fetch_array($result));
			$sql = "insert into ".$tableprefix."_subscriptions (email, confirmed, subscribeid, enterdate, emailtype, language, unsubscribeid, progid, compression) ";
			$sql.= "values ('$actmail', $confirmed, $subscribeid, '$actdate', $emailtype, '$sublang', $unsubscribeid, '$progid', $compression)";
			if(!$result = faqe_db_query($sql, $db))
				die("Could not connect to the database.");
			$imported++;
		}
	}
	return $imported;
}
?>