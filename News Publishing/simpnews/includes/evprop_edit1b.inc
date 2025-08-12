<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
include_once('./includes/sesshandling.inc');
$loggedin=is_loggedin();
include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_edit_proposed_events?></font>
</td></tr>
<?php
if(!$loggedin)
{
	$link="$act_script_url?$langvar=$lang&layout=$layout&mode=edlst";
	if(isset($backurl))
		$link.="&backurl=".urlencode($backurl);
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo "$l_loginfirst"?></font>
</td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<a href="<?php echo $link?>" class="actionlink"><?php echo $l_login?></a></font>
</td></tr>
<?php
}
else
{
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo "$l_loggedinas ".$userdata["email"]?>&nbsp; <a class="actionlink" href="<?php echo $link?>"><?php echo $l_logout?></a></font>
</td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<b><?php echo $l_proposals?></b></font>
</td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>"><b>
<?php
	$sql = "select * from ".$tableprefix."_tmpevents where entrynr='$propnr'";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(!$myrow=mysql_fetch_array($result))
		die("<tr class=\"errorrow\"><td>No such entry");
	$displayheading=do_htmlentities(stripslashes($myrow["heading"]));
	$eventtext = stripslashes($myrow["text"]);
	$eventtext = str_replace("<BR>", "\n", $eventtext);
	$eventtext = undo_htmlspecialchars($eventtext);
	if($myrow["category"]==0)
		echo $l_general;
	else
	{
		$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		$tmprow=mysql_fetch_array($tmpresult);
		$cattext=display_encoded(stripslashes($tmprow["catname"]));
		$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$tmprow["catnr"]." and lang='".$act_lang."'";
		if(!$tmpresult2=mysql_query($tmpsql2,$db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($tmprow2=mysql_fetch_array($tmpresult2))
		{
			if(strlen($tmprow2["catname"])>0)
				$cattext=display_encoded(stripslashes($tmprow2["catname"]));
		}
		echo $cattext;
	}
?>
</b></font>
</td></tr>
<form name="inputform" onsubmit="return checkform_edit()" action="<?php echo $act_script_url?>" method="post">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if(isset($backurl))
		echo "<input type=\"hidden\" name=\"backurl\" value=\"$backurl\">";
	if(isset($actionurl))
		echo "<input type=\"hidden\" name=\"actionurl\" value=\"$actionurl\">";		
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="directpost">
<input type="hidden" name="submode" value="proposal">
<input type="hidden" name="propnr" value="<?php echo $propnr?>">
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
if(bittst($proposereq,BIT_3))
	echo "<b>*";
echo $l_heading.":";
if(bittst($proposereq,BIT_3))
	echo "</b>";
?>
</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="text" name="input_heading" size="40" maxlength="80" value="<?php echo $displayheading?>"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%" valign="top">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<b>*<?php echo $l_date?>:</b></font></td>
<td>
<table width="50%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr>
<td align="center" width="20%"><?php echo $l_day?></td>
<td align="center" width="20%"><?php echo $l_month?></td>
<td align="center" width="20%"><?php echo $l_year?></td>
</tr>
<tr>
<td align="center"><select name="sel_day">
<?php
list($sel_year, $sel_month, $sel_day) = explode("-", $myrow["date"]);
for($i=1;$i<32;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$sel_day)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
<td align="center"><select name="sel_month">
<?php
for($i=1;$i<13;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$sel_month)
		echo " selected";
	echo ">".$l_monthname[$i-1]."</option>";
}
?>
</select></td>
<td align="center"><select name="sel_year">
<?php
for($i=0;$i<$evproposemaxyears;$i++)
{
	echo "<option value=\"".($i+$sel_year)."\"";
	if(($i+$sel_year)==$sel_year)
		echo " selected";
	echo ">".($i+$sel_year)."</option>";
}
?>
</select></td>
</tr>
</table></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%" valign="top">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<b>*<?php echo $l_text?>:</b></font></td>
<td align="left">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<textarea class="snewsinput" name="entrytext" cols="40" rows="5"><?php echo $eventtext?></textarea></font><br>
</td></tr>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="snewsbutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
<?php
	if(isset($actionurl) && $actionurl)
	{
		echo "<tr bgcolor=\"$headingbgcolor\">";
		echo "<td align=\"center\" colspan=\"2\">";
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "<a class=\"actionlink\" href=\"$actionurl";
		if(isset($backurl) && ($backurl))
			echo "&backurl=".urlencode($backurl);
		echo "\">$l_editownproposals</a>";
		echo "</font></td></tr>";
	}
}
?>
