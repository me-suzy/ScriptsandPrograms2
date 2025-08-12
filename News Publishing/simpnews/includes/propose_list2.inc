<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$errors=0;
$errmsg="";
$loggedin=false;
include_once('./includes/sesshandling.inc');
if(isset($dologin))
{
	$email=addslashes(strtolower($email));
	$result=do_login($email,$password,$db);
	if($result!=1)
	{
		include_once("./includes/head.inc");
		echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"4\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "$l_erroronlogin</td></tr>";
		echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"4\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "<a class=\"actionlink\" href=\"javascript:history.back()\">$l_back</a></td></tr>";
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;		
	}
	else
	{
		$loggedin=true;
		if(isset($redirurl))
		{
			if(isset($backurl))
				$redirurl.="&backurl=".urlencode($backurl);
			echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$redirurl\">";
			exit;
		}
	}
}
else if(!isset($requestpw))
{
	if(is_loggedin())
	{
		$email=$userdata["email"];
		$loggedin=true;
	}
}
include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="4">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_proposed_news?></font>
</td></tr>
<?php
if($loggedin)
{
	$link="$act_script_url?$langvar=$act_lang&layout=$layout&mode=logout";
	if(isset($backurl))
		$link.="&backurl=".urlencode($backurl);
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="4">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo "$l_loggedinas ".$userdata["email"]?>&nbsp; <a class="actionlink" href="<?php echo $link?>"><?php echo $l_logout?></a></font>
</td></tr>
<?php
}
if(!$loggedin)
{
	$errors=0;
	$errmsg="";
	if(!$email)
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"4\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$errmsg.="$l_noemail</td></tr>";
	}
	else if(!validate_email($email))
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"4\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$errmsg.="$l_novalidemail</td></tr>";
	}
	if(isset($requestpw) && ($errors==0))
	{
		$displaytext=str_replace("{email}",$email,$l_requestpwprelude);
?>
<tr bgcolor="<?php echo $headingbgcolor?>">
<td align="center" colspan="4">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<b><?php echo $displaytext?></b></font></td></tr>
<?php
		$sql="select * from ".$tableprefix."_poster where email='$email'";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if(!$myrow=mysql_fetch_array($result))
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"4\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_noentryforemail</td></tr>";
		}
		else
		{
			if($myrow["password"] && ($myrow["pwconfirmed"]==1))
			{
				$errors=1;
				$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
				$errmsg.="<td align=\"center\" colspan=\"4\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$errmsg.="$l_pwallreadydefined</td></tr>";
			}
			else
				$posternr=$myrow["entrynr"];
		}
		if($errors==0)
		{
?>
<form name="inputform" onsubmit="return checkformpw()" action="<?php echo $act_script_url?>" method="post">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if(isset($backurl))
				echo "<input type=\"hidden\" name=\"backurl\" value=\"$backurl\">";
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="postpw">
<input type="hidden" name="category" value="<?php echo $category?>">
<input type="hidden" name="posternr" value="<?php echo $posternr?>">
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_password?>:</font></td><td align="left">
<input class="snewsinput" type="password" name="password" size="40" maxlength="40"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_passwordconfirm?>:</font></td><td align="left">
<input class="snewsinput" type="password" name="password2" size="40" maxlength="40"></font></td></tr>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="4"><input class="snewsbutton" type="submit" name="submit" value="<?php echo $l_ok?>">
</td></tr></form>
<?php
			if(isset($backurl) && $backurl)
			{
				echo "<tr bgcolor=\"$headingbgcolor\">";
				echo "<td align=\"center\" colspan=\"4\">";
				echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				echo "<a class=\"actionlink\" href=\"$backurl\">$l_back2news</a>";
				echo "</font></td></tr>";
			}
			echo "</td></tr></table></td></tr></table></div>";
			include_once("./includes/footer.inc");
			exit;
		}
	}
	else
	{
		if(!$password)
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"4\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_nopassword</td></tr>";
		}
	}
	if($errors==1)
	{
		echo $errmsg;
		echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"4\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "<a class=\"actionlink\" href=\"javascript:history.back()\">$l_back</a></td></tr>";
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
	}
}
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="4">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<b><?php echo $l_transferrednews?></b></font></td></tr>
<?php
$entrynum=0;
$email=strtolower($email);
$actionurl="$act_script_url?$langvar=$act_lang&mode=edlst&layout=$layout";
$sql="select dat.* from ".$tableprefix."_data dat, ".$tableprefix."_poster p where dat.exposter=p.entrynr and p.email='$email'";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
{
	echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"center\" colspan=\"4\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_noentries;
	echo "</font></td></tr>\n";
}
else
{
	do{
		$entrynum++;
		list($mydate,$mytime)=explode(" ",$myrow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
		{
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
			$displaydate=date($dateformat,$temptime);
		}
		else
			$displaydate="";
		if($myrow["heading"])
			$displayheading=do_htmlentities($myrow["heading"]);
		else
		{
			$displayheading=stripslashes($myrow["text"]);
			$displayheading=undo_htmlspecialchars($displayheading);
			$displayheading=strip_trags($displayheading);
			$displayheading=substr($displayheading,0,20);
		}
		echo "<tr bgcolor=\"$contentbgcolor\">";
		echo "<td align=\"right\" width=\"5%\">";
		echo $entrynum;
		echo "</td>";
		echo "<td align=\"left\" width=\"15%\">";
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo $displaydate;
		echo "</font></td>";
		echo "<td align=\"left\" width=\"50%\">";
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo $displayheading;
		echo "</font></td>";
		echo "<td>";
		echo "<a class=\"listlink\" href=\"$act_script_url?newsnr=".$myrow["newsnr"]."&$langvar=$act_lang&layout=$layout&mode=proped&type=edit";
		if(isset($backurl) && ($backurl))
			echo "&backurl=".urlencode($backurl);
		if(isset($actionurl) && ($actionurl))
			echo "&actionurl=".urlencode($actionurl);
		echo "\">";
		echo "$l_edit</a>";
		echo "&nbsp;&nbsp;";
		$delurl="$act_script_url?newsnr=".$myrow["newsnr"]."&$langvar=$act_lang&layout=$layout&mode=proped&type=delete";
		$delmsg=str_replace("{entrynr}",$entrynum,$l_delmsg);
		$delmsg=undo_htmlentities($delmsg);
		if(isset($backurl) && ($backurl))
			$delurl.="&backurl=".urlencode($backurl);
		echo "<a class=\"listlink\" href=\"javascript:confirmDel('$delmsg','$delurl')\">";
		echo "$l_delete</a></td>";
		echo "</tr>\n";
	}while($myrow=mysql_fetch_array($result));
}
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="4">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<b><?php echo $l_proposals?></b></font></td></tr>
<?php
$sql="select dat.* from ".$tableprefix."_tmpdata dat, ".$tableprefix."_poster p where dat.posterid=p.entrynr and p.email='$email'";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
{
	echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"center\" colspan=\"4\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_noentries;
	echo "</font></td></tr>\n";
}
else
{
	do{
		$entrynum++;
		list($mydate,$mytime)=explode(" ",$myrow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
		{
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
			$displaydate=date($dateformat,$temptime);
		}
		else
			$displaydate="";
		if($myrow["heading"])
			$displayheading=do_htmlentities($myrow["heading"]);
		else
		{
			$displayheading=stripslashes($myrow["text"]);
			$displayheading=undo_htmlspecialchars($displayheading);
			$displayheading=strip_tags($displayheading);
			$displayheading=substr($displayheading,0,20);
		}
		echo "<tr bgcolor=\"$contentbgcolor\">";
		echo "<td align=\"right\" width=\"5%\">";
		echo $entrynum;
		echo "</td>";
		echo "<td align=\"left\" width=\"15%\">";
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo $displaydate;
		echo "</font></td>";
		echo "<td align=\"left\" width=\"50%\">";
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo $displayheading;
		echo "</font></td>";
		echo "<td>";
		echo "<a class=\"listlink\" href=\"$act_script_url?propnr=".$myrow["entrynr"]."&$langvar=$act_lang&layout=$layout&mode=proped&type=edit";
		if(isset($backurl) && ($backurl))
			echo "&backurl=".urlencode($backurl);
		if(isset($actionurl) && ($actionurl))
			echo "&actionurl=".urlencode($actionurl);
		echo "\">";
		echo "$l_edit</a>";
		echo "&nbsp;&nbsp;";
		$delurl="$act_script_url?propnr=".$myrow["entrynr"]."&$langvar=$act_lang&layout=$layout&mode=proped&type=delete";
		$delmsg=str_replace("{entrynr}",$entrynum,$l_delmsg);
		$delmsg=undo_htmlentities($delmsg);
		if(isset($backurl) && ($backurl))
			$delurl.="&backurl=".urlencode($backurl);
		echo "<a class=\"listlink\" href=\"javascript:confirmDel('$delmsg','$delurl')\">";
		echo "$l_delete</a></td>";
		echo "</tr>\n";
	}while($myrow=mysql_fetch_array($result));
}
echo "<tr bgcolor=\"$headingbgcolor\">";
echo "<td align=\"center\" colspan=\"4\">";
echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
$editentry="propose.php?$langvar=$act_lang&layout=$layout&mode=eduser";
if(isset($backurl) && $backurl)
	$editentry.="&backurl=".urlencode($backurl);
echo "<a class=\"actionlink\" href=\"$editentry\">$l_edituserentry</a>&nbsp;&nbsp; ";
$newentry="propose.php?$langvar=$act_lang&layout=$layout&mode=new";
$newentry.="&backtxt=".urlencode($l_editownproposals);
$localbackurl="propose.php?$langvar=$act_lang&layout=$layout&mode=edlst";
$newentry.="&backurl=".urlencode($localbackurl);
echo "<a class=\"actionlink\" href=\"$newentry\">$l_newproposal</a>&nbsp;&nbsp; ";
if(isset($backurl) && $backurl)
{
	echo "<a class=\"actionlink\" href=\"$backurl\">$l_back2news</a>";
}
echo "</font></td></tr>";
?>