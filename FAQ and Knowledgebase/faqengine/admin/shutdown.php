<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$page_title=$l_shutdownsys;
$page="shutdown";
$uses_bbcode=true;
require_once('./heading.php');
include_once("./includes/bbcode_buttons.inc");
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 4)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($submit) || isset($preview))
{
	if(isset($shutdowntext))
	{
		if(!isset($local_urlautoencode))
			$urlautoencode=0;
		else
			$urlautoencode=1;
		if(!isset($local_enablespcode))
			$enablespcode=0;
		else
			$enablespcode=1;
		if(isset($preview))
		{
			$displaytext=$shutdowntext;
			if(isset($disablehtml))
			{
				$displaytext = htmlspecialchars($displaytext);
				$displaytext = undo_html_ampersand($displaytext);
			}
			if($urlautoencode==1)
				$displaytext = make_clickable($displaytext);
			if($enablespcode==1)
				$displaytext = bbencode($displaytext);
			$displaytext = stripslashes($displaytext);
			$displaytext = do_htmlentities($displaytext);
			$displaytext = str_replace("\n", "<BR>", $displaytext);
			$displaytext = undo_htmlspecialchars($displaytext);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow">
<td align="center"><?php echo $l_shutdownpreview?>:</td></tr>
<tr class="displayrow"><td align="center" VALIGN="MIDDLE"><?php echo $displaytext?></td></tr>
<tr class="actionrow" align="center"><form method="post" action="<?php echo $act_script_url?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<td align="center">
<?php
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
if(isset($disablehtml))
	echo "<input type=\"hidden\" name=\"disablehtml\" value=\"1\">";
?>
<input type="hidden" name="new" value="<?php echo $new?>">
<input class="faqebutton" type="submit" name="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="shutdowntext" value="<?php echo do_htmlentities($shutdowntext)?>">
</td></form></tr></table></td></tr></table>
<?php
			include('./trailer.php');
			exit;
		}
		else
		{
			if(isset($disablehtml))
			{
				$shutdowntext = htmlspecialchars($shutdowntext);
				$shutdowntext = undo_html_ampersand($shutdowntext);
			}
			if($urlautoencode==1)
				$shutdowntext = make_clickable($shutdowntext);
			if($enablespcode==1)
				$shutdowntext = bbencode($shutdowntext);
			$shutdowntext=stripslashes($shutdowntext);
			$shutdowntext = do_htmlentities($shutdowntext);
			$shutdowntext = str_replace("\n", "<BR>", $shutdowntext);
			$shutdowntext=addslashes($shutdowntext);
			if($new==1)
				$sql = "INSERT INTO ".$tableprefix."_misc (shutdowntext) values ('$shutdowntext')";
			else
				$sql = "UPDATE ".$tableprefix."_misc SET shutdowntext='$shutdowntext'";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
		}
	}
	if(isset($newshutdown))
	{
		if($new==1)
			$sql = "INSERT INTO ".$tableprefix."_misc (shutdown) values ($newshutdown)";
		else
			$sql = "UPDATE ".$tableprefix."_misc SET shutdown=$newshutdown";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	}
}
$sql="select * from ".$tableprefix."_misc";
if(!$result = faqe_db_query($sql, $db)) {
    die("Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	$new=1;
	$shutdown=0;
	$shutdowntext="";
}
else
{
	$new=0;
	$shutdown=$myrow["shutdown"];
	$shutdowntext = stripslashes($myrow["shutdowntext"]);
	$shutdowntext = str_replace("<BR>", "\n", $shutdowntext);
	$shutdowntext = undo_htmlspecialchars($shutdowntext);
	$shutdowntext = bbdecode($shutdowntext);
	$shutdowntext = undo_make_clickable($shutdowntext);
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow">
<form name="inputform" method="post" action="<?php echo $act_script_url?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="new" value="<?php echo $new?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
if($shutdown==1)
	$displaytext=$l_isshutdown;
if($shutdown==0)
	$displaytext=$l_isonline;
if($shutdown==2)
	$displaytext=$l_isnoadmin;
?>
<td align="center" colspan="2"><b><?php echo $l_systemstate?>: <?php echo $displaytext?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_shutdowntext?>:</td>
<td><textarea class="faqeinput" name="shutdowntext" cols="50" rows="10"><?php echo $shutdowntext?></textarea>
<br>
<?php display_bbcode_buttons($l_bbbuttons,"shutdowntext",false,false,false)?>
</td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?><br>
<input type="checkbox" name="disablehtml" value="1"> <?php echo $l_disablehtml?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="submit" value="do">
<input class="faqebutton" type="submit" name="update" value="<?php echo $l_update?>">
&nbsp;&nbsp;<input class="faqebutton" type="submit" name="preview" value="<?php echo $l_preview?>">
</td></form></tr>
<tr class="actionrow"><td align="center" colspan="2">
<a class="actionlink" href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&newshutdown=1&submit=1&new=$new")?>"><?php echo $l_shutdown?></a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a class="actionlink" href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&newshutdown=0&submit=1&new=$new")?>"><?php echo $l_restart?></a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a class="actionlink" href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&newshutdown=2&submit=1&new=$new")?>"><?php echo $l_adminshutdown?></a>
</td></tr>
</table></td></tr></table>
<?php
include('./trailer.php');
?>