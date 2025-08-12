<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
 	$page="masssub";
	include_once('./includes/head.inc');
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td align="center" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_subscriptionprelude?></font></td></tr>
<form name="subscriptionform" onsubmit="return checkform()" action="<?php echo $act_script_url?>" method="post">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" valign="top" width="30%"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>"><?php echo $l_category?>:</td>
<td align="left">
<?php
if(!isset($cats))
{
	$sql="select * from ".$tableprefix."_categories where excludefromnewsletter=0 order by catname asc";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		echo "<input type=\"checkbox\" name=\"newscat[]\" value=\"".$myrow["catnr"]."\">";
		$cattext=display_encoded(stripslashes($myrow["catname"]));
		$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$myrow["catnr"]." and lang='".$act_lang."'";
		if(!$tmpresult2=mysql_query($tmpsql2,$db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($tmprow2=mysql_fetch_array($tmpresult2))
		{
			if(strlen($tmprow2["catname"])>0)
				$cattext=display_encoded(stripslashes($tmprow2["catname"]));
		}
		echo $cattext;
		if(bittst($masssuboptions,BIT_1))
		{
			list($mydate,$mytime)=explode(" ",$myrow["nlsenddate"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
			{
				$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
				$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
				$displaydate=strftime($nlsend_dateformat,$displaytime);
				echo " ($displaydate)";
			}
		}
		echo "<br>";
	}
	echo "</td></tr>";
}
else
{
	$categories=explode(",",$cats);
	for($i=0;$i<count($categories);$i++)
	{
		$sql="select * from ".$tableprefix."_categories where excludefromnewsletter=0 and catnr=".$categories[$i];
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			echo "<input type=\"checkbox\" name=\"newscat[]\" value=\"".$myrow["catnr"]."\">";
			$cattext=display_encoded(stripslashes($myrow["catname"]));
			$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$myrow["catnr"]." and lang='".$act_lang."'";
			if(!$tmpresult2=mysql_query($tmpsql2,$db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if($tmprow2=mysql_fetch_array($tmpresult2))
			{
				if(strlen($tmprow2["catname"])>0)
					$cattext=display_encoded(stripslashes($tmprow2["catname"]));
			}
			echo $cattext;
			if(bittst($masssuboptions,BIT_1))
			{
				list($mydate,$mytime)=explode(" ",$myrow["nlsenddate"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				if($month>0)
				{
					$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
					$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
					$displaydate=strftime($nlsend_dateformat,$displaytime);
					echo " ($displaydate)";
				}
			}
			echo "<br>";
		}
	}
}
?>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>"><?php echo $l_email?>:
<?php if($subscriptionfreemailer==0) echo "<br>$l_nofreemailer"?></font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>"><input class="snewsinput" type="text" name="email" maxlength="240" size="40"></font></td></tr>
<?php
if($subemailtype==0)
{
?>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%" valign="top"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>"><?php echo $l_emailtype?>:</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input type="radio" name="emailtype" value="0" checked> <?php echo $l_htmlmail?><br>
<input type="radio" name="emailtype" value="1"> <?php echo $l_ascmail?></font></td></tr>
<?php
}
else
	echo "<input type=\"hidden\" name=\"emailtype\" value=\"".($subemailtype-1)."\">";
echo "<input type=\"hidden\" name=\"mode\" value=\"subscribe\">";
if(isset($backurl))
	echo "<input type=\"hidden\" name=\"backurl\" value=\"$backurl\">";
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="snewsbutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
<?php
if(isset($backurl) && $backurl)
{
	echo "<tr bgcolor=\"$headingbgcolor\">";
	echo "<td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "<a class=\"actionlink\" href=\"$backurl\">$l_back</a>";
	echo "</font></td></tr>";
}
?>
</table></td></tr></table>
<?php
include_once("./includes/footer.inc");
?>