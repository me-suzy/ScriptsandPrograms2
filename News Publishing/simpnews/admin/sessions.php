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
$page_title=$l_cleansession;
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($submit))
{
		$sql = "DELETE FROM ".$tableprefix."_session";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.");
		$sql = "DELETE FROM ".$tableprefix."_session2";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo $l_sessionsdeleted?></td></tr>
</table></td></tr></table>
<?php
	include('trailer.php');
	exit;
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow">
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<td align="center"><?php echo $l_sessionwarning?></td></tr>
<tr class="actionrow">
<td align="center"><input class="snbutton" type="submit" name="submit" value="<?php echo $l_yes?>"></td></tr>
</table></td></tr></table>
<?php
include('./trailer.php');
?>