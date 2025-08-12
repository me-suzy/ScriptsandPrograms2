<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$page_title=$l_purgenews;
$page="purgenews";
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("SimpNews not set up.");
$maxage=$myrow["maxage"];
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < $pnlevel)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode) && ($mode=="massdel"))
{
	$errors=0;
	if(!isset($newsnr))
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		echo "$l_noentriesselected</td></tr>";
		$errors=1;
	}
	if($errors==0)
	{
		while(list($null, $input_newsnr) = each($_POST["newsnr"]))
		{
			if(isset($transfernews))
			{
				$sql = "update ".$tableprefix."_data set category=$destcat where newsnr=$input_newsnr";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
			else
			{
				$sql = "delete from ".$tableprefix."_data where newsnr=$input_newsnr";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\">><td>Unable to delete entry from database.");
				$sql = "delete from ".$tableprefix."_data where linknewsnr=$input_newsnr";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Unable to delete linked entries from database.");
				$sql2 = "delete from ".$tableprefix."_search where newsnr=$input_newsnr";
				if(!$result2 = mysql_query($sql2, $db))
					die("<tr class=\"errorrow\"><td>Unable to delete search entries from database.");
				$sql = "delete from ".$tableprefix."_news_attachs where newsnr=$input_newsnr";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Unable to delete attachments database.");
			}
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo $l_newspurged?></td></tr>
</table></td></tr></table>
<?php
	}
	else
	{
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
	}
	include('trailer.php');
	exit;
}
if(isset($preview))
{
	$errors=0;
	if(!$purgedays)
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		echo "$l_nopurgedays</td></tr>";
		$errors=1;
	}
	if($errors==0)
	{
		if($purgecat>0)
		{
			$sql="select * from ".$tableprefix."_categories where catnr=$purgecat";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			if(!$myrow=mysql_fetch_array($result))
				die("<tr class=\"errorrow\"><td>No such category ($purgecat).");
			$catname=display_encoded($myrow["catname"]);
		}
		else
			$catname=$l_general;
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		echo "<tr class=\"headingrow\"><td align=\"right\" width=\"50%\"><b>";
		echo "$l_purgecat:</b></td><td align=\"left\"><b>$catname</b></td></tr>";
		if(isset($transfernews))
		{
			if($destcat>0)
			{
				$sql="select * from ".$tableprefix."_categories where catnr=$destcat";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Unable to connect to database.");
				if(!$myrow=mysql_fetch_array($result))
					die("<tr class=\"errorrow\"><td>No such category ($destcat).");
				$catname=display_encoded($myrow["catname"]);
			}
			else
				$catname=$l_general;
			echo "<tr class=\"headingrow\"><td align=\"right\" width=\"50%\"><b>";
			echo "$l_move2cat:</b></td><td><b>$catname</b></td></tr>";
		}
		$actdate = date("Y-m-d H:i:s");
		if($admin_rights<3)
		{
			$sql="select dat.* from ".$tableprefix."_data dat, ".$tableprefix."_cat_adm ca where dat.date<date_sub('$actdate', INTERVAL $purgedays DAY) and (dat.category=ca.catnr and ca.usernr=".$userdata["usernr"];
			if($purgecat>=0)
				$sql.=" and dat.category=$purgecat";
			else
			{
				if(!bittst($secsettings,BIT_1))
					$sql.=" and dat.category != 0";
				else
					$sql.=" or dat.category=0";
			}
			$sql.=")";
			if(isset($transfernews))
				$sql.=" and dat.category != $destcat";
			$sql.=" group by dat.newsnr";
		}
		else
		{
			$sql = "SELECT * FROM ".$tableprefix."_data dat where date<date_sub('$actdate', INTERVAL $purgedays DAY) and dontpurge=0";
			if(isset($transfernews))
				$sql.=" and dat.category != $destcat";
			if($purgecat>=0)
				$sql.=" and dat.category=$purgecat";
		}
		if(($purgecat<0) && isset($catexclude))
		{
			echo "<tr class=\"headingrow\" valign=\"top\"><td align=\"right\" width=\"50%\"><b>$l_dontpurgecat:</b></td><td><b>";
			while(list($null, $excat) = each($_POST["excludecat"]))
			{
				$sql.=" and dat.category!=".$excat;
				if($excat>0)
				{
					$tmpsql="select * from ".$tableprefix."_categories where catnr=$excat";
					if(!$tmpresult = mysql_query($tmpsql, $db))
						die("<tr class=\"errorrow\"><td>Unable to connect to database. ".mysql_error());
					if(!$tmprow=mysql_fetch_array($tmpresult))
						die("<tr class=\"errorrow\"><td>No such category ($excat).");
					$catname=display_encoded($tmprow["catname"]);
				}
				else
					$catname=$l_general;
				echo "$catname<br>";
			}
			echo "</b></td></tr>";
		}
		if(!isset($transfernews))
			$sql.=" and dat.dontpurge=0";
		$sql.= " order by dat.date desc";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error()."<br>".$sql);
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		if(mysql_num_rows($result)>0)
		{
			echo "<form name=\"inputform\" action=\"$act_script_url\" method=\"post\">";
			if(isset($transfernews))
			{
				echo "<input type=\"hidden\" name=\"transfernews\" value=\"1\">";
				echo "<input type=\"hidden\" name=\"destcat\" value=\"$destcat\">";
			}
			while($myrow=mysql_fetch_array($result))
			{
				$act_id=$myrow["newsnr"];
				$newstext=stripslashes($myrow["text"]);
				$newstext=undo_htmlspecialchars($newstext);
				if($admentrychars>0)
				{
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
						$text=strip_tags(undo_htmlspecialchars(stripslashes($myrow["text"])));
						if($admentrychars>0)
							$displaytext=substr($text,0,$admentrychars);
						else
							$displaytext=substr($text,0,20);
						$displaytext.="[...]";
					}
				}
				echo "<tr>";
				echo "<td class=\"inputrow\" align=\"center\" valign=\"top\" width=\"1%\">";
				echo "<input type=\"checkbox\" name=\"newsnr[]\" value=\"$act_id\">";
				echo "</td>";
				echo "<td class=\"displayrow\" align=\"center\" valign=\"top\" width=\"8%\">";
				if($myrow["linknewsnr"]==0)
					$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$myrow["newsnr"]);
				else
					$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$myrow["linknewsnr"]);
				echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
				echo $myrow["newsnr"]."</a></td>";
				echo "<td class=\"newsentry\" align=\"left\">";
				echo "$displaytext</td>";
				echo "<td class=\"newscat\" align=\"center\" valign=\"top\" width=\"20%\">";
				if($myrow["category"]>0)
				{
					$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
					if(!$tmpresult = mysql_query($tmpsql, $db))
						die("<tr class=\"errorrow\"><td>Unable to connect to database.");
					if($tmprow=mysql_fetch_array($tmpresult))
						echo display_encoded($tmprow["catname"]);
				}
				else
					echo $l_general;
				echo "</td>";
				echo "<td class=\"newsdate\" align=\"center\" valign=\"top\" width=\"15%\">";
				echo $myrow["date"]."</td>";
				echo "</tr>";
			}
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"massdel\">";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"actionrow\"><td colspan=\"5\" align=\"left\">";
			if(isset($transfernews))
				echo "<input class=\"snbutton\" type=\"submit\" name=\"del\" value=\"$l_moveselected\">";
			else
				echo "<input class=\"snbutton\" type=\"submit\" name=\"del\" value=\"$l_delselected\">";
			echo "&nbsp;&nbsp;";
			echo "<input class=\"snbutton\" type=\"button\" value=\"$l_checkall\" onclick=\"checkAll(inputform)\">";
			echo "&nbsp;&nbsp;";
			echo "<input class=\"snbutton\" type=\"button\" value=\"$l_uncheckall\" onclick=\"uncheckAll(inputform)\">";
			echo "</td></tr>";
			echo "</form>";
		}
		else
		{
			echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"5\">";
			echo $l_nomatchingentries;
			echo "</td></tr>";
		}
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_purgenews</a></div>";
	}
	else
	{
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
	}
	include('trailer.php');
	exit;
}
if(isset($submit))
{
	$errors=0;
	if(!$purgedays)
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		echo "$l_nopurgedays</td></tr>";
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
		include('trailer.php');
		exit;
	}
	if($purgecat>0)
	{
		$sql="select * from ".$tableprefix."_categories where catnr=$purgecat";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.");
		if(!$myrow=mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>No such category ($purgecat).");
		$catname=display_encoded($myrow["catname"]);
	}
	else
		$catname=$l_general;
	if(isset($transfernews))
	{
		if($destcat>0)
		{
			$sql="select * from ".$tableprefix."_categories where catnr=$destcat";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			if(!$myrow=mysql_fetch_array($result))
				die("<tr class=\"errorrow\"><td>No such category ($destcat).");
			$catname=display_encoded($myrow["catname"]);
		}
		else
			$catname=$l_general;
		echo "<tr class=\"headingrow\"><td align=\"right\" width=\"50%\"><b>";
		echo "$l_move2cat:</b></td><td><b>$catname</b></td></tr>";
	}
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"headingrow\"><td align=\"right\" width=\"50%\"><b>";
	echo "$l_purgecat:</b></td><td align=\"left\"><b>$catname</b></td></tr>";
	$actdate = date("Y-m-d H:i:s");
	if($admin_rights<3)
	{
		$sql="select dat.* from ".$tableprefix."_data dat, ".$tableprefix."_cat_adm ca where dat.date<date_sub('$actdate', INTERVAL $purgedays DAY) and (dat.category=ca.catnr and ca.usernr=".$userdata["usernr"];
		if($purgecat>=0)
			$sql.=" and dat.category=$purgecat";
		else if(!bittst($secsettings,BIT_1))
			$sql.=" and dat.category != 0";
		else
			$sql.=" or dat.category=0";
		$sql.=")";
		if(isset($transfernews))
			$sql.=" and dat.category != $destcat";
	}
	else
	{
		$sql = "SELECT * FROM ".$tableprefix."_data dat where date<date_sub('$actdate', INTERVAL $purgedays DAY)";
		if(isset($transfernews))
			$sql.=" and dat.category != $destcat";
		if($purgecat>=0)
			$sql.=" and dat.category=$purgecat";
	}
	if(!isset($transfernews))
		$sql.=" and dat.dontpurge=0";
	if(($purgecat<0) && isset($catexclude))
	{
		echo "<tr class=\"headingrow\" valign=\"top\"><td align=\"right\" width=\"50%\"><b>$l_dontpurgecat:</b></td><td><b>";
		while(list($null, $excat) = each($_POST["excludecat"]))
		{
			$sql.=" and dat.category!=".$excat;
			if($excat>0)
			{
				$tmpsql="select * from ".$tableprefix."_categories where catnr=$excat";
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Unable to connect to database. ".mysql_error());
				if(!$tmprow=mysql_fetch_array($tmpresult))
					die("<tr class=\"errorrow\"><td>No such category ($excat).");
				$catname=display_encoded($tmprow["catname"]);
			}
			else
				$catname=$l_general;
			echo "$catname<br>";
		}
		echo "</b></td></tr>";
	}
	$sql.=" group by dat.newsnr";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.");
	while($myrow=mysql_fetch_array($result))
	{
		if(isset($transfernews))
		{
			$sql2 = "update ".$tableprefix."_data set category=$destcat where newsnr=".$myrow["newsnr"];
			if(!$result2 = mysql_query($sql2, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		}
		else
		{
			$sql2 = "delete from ".$tableprefix."_data where linknewsnr=".$myrow["newsnr"];
			if(!$result2 = mysql_query($sql2, $db))
				die("<tr class=\"errorrow\"><td>Unable to delete linked entries from database.");
			$sql2 = "delete from ".$tableprefix."_search where newsnr=".$myrow["newsnr"];
			if(!$result2 = mysql_query($sql2, $db))
				die("<tr class=\"errorrow\"><td>Unable to delete search entries from database.");
			$sql2 = "delete from ".$tableprefix."_news_attachs where newsnr=".$myrow["newsnr"];
			if(!$result2 = mysql_query($sql2, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			$sql2 = "delete from ".$tableprefix."_data where newsnr=".$myrow["newsnr"];
			if(!$result2 = mysql_query($sql2, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		}
	}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo $l_newspurged?></td></tr>
</table></td></tr></table>
<?php
	include('trailer.php');
	exit;
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow">
<form name="selectionform" method="post" action="<?php echo $act_script_url?>" onsubmit="return checkselform()">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<td align="center" colspan="2"><?php echo $l_purgenews2a?> <input class="sninput" type="text" name="purgedays" size="3" maxlength="5" <?php if($maxage>0) echo "value=\"$maxage\""?>> <?php echo "$l_days $l_purgenews2b"?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_workoncat?>:</td><td align="left">
<select name="purgecat" onchange="catchanged()">
<option value="-1"><?php echo $l_all?></option>
<?php
if(bittst($secsettings,BIT_1) || ($admin_rights>2))
	echo "<option value=\"0\">$l_general</option>";
if($admin_rights==2)
	$sql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"]." group by cat.catnr";
else
	$sql="select cat.* from ".$tableprefix."_categories cat";
$sql.=" order by cat.displaypos asc";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to conntect to database.");
while($myrow=mysql_fetch_array($result))
{
	echo "<option value=\"".$myrow["catnr"]."\">";
	echo display_encoded($myrow["catname"])."</option>";
}
?>
</select>
</td></tr>
<tr class="inputrow" valign="top"><td align="right"><input type="checkbox" name="catexclude" value="1"> <?php echo $l_dontworkoncat?>:</td><td align="left">
<select name="excludecat[]" size="5" multiple id="excludecat">
<?php
if(bittst($secsettings,BIT_1) || ($admin_rights>2))
	echo "<option value=\"0\">$l_general</option>";
if($admin_rights==2)
	$sql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"]." group by cat.catnr";
else
	$sql="select cat.* from ".$tableprefix."_categories cat";
$sql.=" order by cat.displaypos asc";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to conntect to database.");
while($myrow=mysql_fetch_array($result))
{
	echo "<option value=\"".$myrow["catnr"]."\">";
	echo display_encoded($myrow["catname"])."</option>";
}
?>
</select>
</td></tr>
<tr class="inputrow"><td align="right" width="60%"><input type="checkbox" name="transfernews" value="1"> <?php echo $l_nodelbuttrans?>:</td><td align="left">
<select name="destcat">
<?php
if(bittst($secsettings,BIT_1) || ($admin_rights>2))
	echo "<option value=\"0\">$l_general</option>";
if($admin_rights==2)
	$sql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"]." group by cat.catnr";
else
	$sql="select cat.* from ".$tableprefix."_categories cat";
$sql.=" order by cat.displaypos asc";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to conntect to database.");
while($myrow=mysql_fetch_array($result))
{
	echo "<option value=\"".$myrow["catnr"]."\">";
	echo display_encoded($myrow["catname"])."</option>";
}
?>
</select>
</td></tr>
<tr class="optionrow"><td align="center" colspan="2"><input type="checkbox" name="preview" value="1"> <?php echo $l_previewlistnews?></td></tr>
<tr class="actionrow">
<td align="center" colspan="2"><input class="snbutton" type="submit" name="submit" value="<?php echo $l_submit?>"></td></tr>
</table></td></tr></table>
<?php
include('./trailer.php');
?>