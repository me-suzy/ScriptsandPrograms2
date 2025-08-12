<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$usermail="";
$username="";
include_once('./includes/sesshandling.inc');
$haspw=false;
$loggedin=is_loggedin();
$usebbcode=true;
if(!$loggedin)
{
	if($new_global_handling)
	{
		if(isset($_COOKIE[$cookiename]))
			$cookiedata=$_COOKIE[$cookiename];
	}
	else
	{
		if(isset($_COOKIE[$cookiename]))
			$cookiedata=$_COOKIE[$cookiename];
	}
	if(isset($cookiedata))
	{
		if(sn_array_key_exists($cookiedata,"usermail"))
			$usermail=$cookiedata["usermail"];
		if(sn_array_key_exists($cookiedata,"username"))
			$username=$cookiedata["username"];
		if($usermail)
		{
			$tmpsql="select * from ".$tableprefix."_poster where email='$usermail'";
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if($tmprow=mysql_fetch_array($tmpresult))
			{
				if($tmprow["pwconfirmed"]==1)
					$haspw=true;
			}
		}
	}
	if(bittst($proposepermissions,BIT_5))
		$usebbcode=false;
}
else
{
	$username=$userdata["name"];
	$usermail=$userdata["email"];
	if(bittst($proposepermissions,BIT_4) || ($userdata["disablebbcode"]==1))
		$usebbcode=false;
}
include_once("./includes/head.inc");
if($usebbcode)
	include_once("./includes/bbcode_buttons.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_propose_news?></font>
</td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<b>
<?php
if($category==0)
	echo $l_general;
else
{
	$tmpsql="select * from ".$tableprefix."_categories where catnr='$category'";
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
<form <?php if($upload_avail) echo "enctype=\"multipart/form-data\""?> name="inputform" onsubmit="return checkform()" action="<?php echo $act_script_url?>" method="post">
<?php
echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$maxuserupload\">\n";
if(is_konqueror())
	echo "<tr><td></td></tr>";
if(isset($backurl))
	echo "<input type=\"hidden\" name=\"backurl\" value=\"$backurl\">";
if(isset($backtxt))
	echo "<input type=\"hidden\" name=\"backtxt\" value=\"$backtxt\">";
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="start" value="<?php echo $start?>">
<input type="hidden" name="mode" value="add">
<input type="hidden" name="category" value="<?php echo $category?>">
<input type="hidden" name="postingid" value="<?php echo microtime()?>">
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
if(bittst($proposereq,BIT_1))
	echo "<b>*";
echo $l_name.":";
if(bittst($proposereq,BIT_1))
	echo "</b>";
?>
</font></td>
<td align="left">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="text" name="poster" size="40" maxlength="80" value="<?php echo $username?>"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
if(bittst($proposereq,BIT_2))
	echo "<b>*";
echo $l_email.":";
if(bittst($proposereq,BIT_2))
	echo "</b>";
?>
</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="text" name="email" size="40" maxlength="80" value="<?php echo $usermail?>"></font></td></tr>
<?php
if(!$loggedin && bittst($proposepermissions,BIT_1) && !$haspw)
{
?>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
echo $l_password.":";
?>
</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="password" name="password2" size="40" maxlength="20"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
echo $l_passwordconfirm.":";
?>
</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="password" name="password" size="40" maxlength="20"></font></td></tr>

<?php
}
?>
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
<input class="snewsinput" type="text" name="input_heading" size="40" maxlength="80"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%" valign="top">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<b>*<?php echo $l_text?>:</b></font></td>
<td align="left">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<textarea class="snewsinput" name="entrytxt" cols="40" rows="5"></textarea></font>
<?php
if($usebbcode)
{
	display_smiliebox("entrytxt");
	display_bbcode_buttons($l_bbbuttons, "entrytxt", true, true);
?>
</td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="urlautoencode" value="1" checked> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="enablespcode" value="1" checked> <?php echo $l_enablespcode?><br>
<input type="checkbox" name="disableemoticons" value="1"> <?php echo $l_disableemoticons?><br>
<?php
}
else
	echo "<input type=\"hidden\" name=\"disableemoticons\" value=\"1\">";
echo "</td></tr>";
if($loggedin)
{
	if($userdata["disablefileupload"]==1)
		$allow_attachs=false;
	else
		$allow_attachs=true;
}
else
{
	if(bittst($proposepermissions,BIT_7))
		$allow_attachs=false;
	else
		$allow_attachs=true;
}
if(bittst($proposepermissions,BIT_6) && $allow_attachs && $upload_avail)
{
?>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_attachfile?>:</font></td><td align="left"><table width="100%" cellspacing="0" cellpadding="1">
<tr><td align="right" width="20%"><?php echo $l_file?>:</td><td align="left"><input size="40" type="file" name="userfile" class="snewsinput"></td></tr>
<tr><td align="right" width="20%"><?php echo $l_description?>:</td><td align="left"><input size="40" maxlength="255" name="filedescription" class="snewsinput" type="text"></td></tr>
</table></td></tr>
<?php
}
if(!$loggedin)
{
?>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center"><td>&nbsp;</td><td align="left">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input type="checkbox" name="storeuserdata" value="1"> <?php echo $l_storeuserdata?>
</td></tr>
<?php
}
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="snewsbutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
<?php
if((isset($backurl) && $backurl) || bittst($proposepermissions,BIT_1))
{
	echo "<tr bgcolor=\"$headingbgcolor\">";
	echo "<td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	if(isset($backurl) && $backurl)
		echo "<a class=\"actionlink\" href=\"$backurl\">$l_back</a>";
	if(bittst($proposepermissions,BIT_1))
	{
		if(isset($backurl) && $backurl)
			echo "&nbsp;&nbsp;";
		echo "<a class=\"actionlink\" href=\"propose.php?$langvar=$act_lang&mode=edlst&layout=$layout";
		if(isset($backurl) && $backurl)
			echo "&backurl=".urlencode($backurl);
		echo "\">$l_editownproposals</a>";
		if(!$loggedin)
		{
			echo "&nbsp;&nbsp;";
			$redirecturl="propose.php?$langvar=$act_lang&layout=$layout&category=$category&mode=new&start=$start";
			echo "<a class=\"actionlink\" href=\"propose.php?$langvar=$act_lang&mode=login&layout=$layout";
			echo "&redirecturl=".urlencode($redirecturl);
			if(isset($backurl) && $backurl)
				echo "&backurl=".urlencode($backurl);
			echo "\">$l_login</a>";
		}
		else
		{
			echo "&nbsp;&nbsp;";
			echo "<a class=\"actionlink\" href=\"$act_script_url?$langvar=$act_lang&layout=$layout&mode=logout";
			$backurl2="propose.php?$langvar=$act_lang&layout=$layout&category=$category&mode=new&start=$start";
			if(isset($backurl))
				$backurl2.="&backurl=".urlencode($backurl);
			if(isset($backurl2) && $backurl2)
				echo "&backurl=".urlencode($backurl2);
			echo "\">$l_logout</a>";
		}
	}
	echo "</font></td></tr>";
}
?>
