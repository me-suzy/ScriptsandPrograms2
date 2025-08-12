<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
if(!$insafemode)
	@set_time_limit($longrunner);
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('../functions.php');
require_once('./functions.php');
require_once('./auth.php');
$page_title=$l_repairtables;
$page="tblrepair";
$infotext="";
require_once('./heading.php');
require_once('./includes/dbtables.inc');
if(isset($mode))
{
	$tablesrepaired=0;
	for($i=0;$i<count($dbtables);$i++)
	{
		$sql="REPAIR TABLE ".$tableprefix."_".$dbtables[$i];
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.".faqe_db_error());
		$tablesrepaired++;
	}
	if(isset($doban))
	{
		$sql="REPAIR TABLE ".$banprefix."_banlist";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.".faqe_db_error());
		$tablesrepaired++;
	}
	if(isset($dohostcache))
	{
		$sql="REPAIR TABLE ".$hcprefix."_hostcache";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.".faqe_db_error());
		$tablesrepaired++;
	}
	if(isset($doleacher))
	{
		$sql="REPAIR TABLE ".$leacherprefix."_leachers";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.".faqe_db_error());
		$tablesrepaired++;
	}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo "$l_tablesrepaired ($tablesrepaired)"?></td></tr>
</table></td></tr></table>
<?php
	include('trailer.php');
	exit;
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="repair">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_repairtables_note?></td></tr>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_alsoworkon?>:</td>
<td valign="top" align="left">
<input type="checkbox" name="doban" value="1"> <?php echo $l_ipbanlist?><br>
<input type="checkbox" name="dohostcache" value="1"> <?php echo $l_hostcache?><br>
<input type="checkbox" name="doleacher" value="1"> <?php echo $l_leacherlist?></td></tr>
<tr class="actionrow">
<td align="center" colspan="2"><input class="faqebutton" type="submit" name="submit" value="<?php echo $l_yes?>"></td></tr>
</table></td></tr></table>
<?php
include('./trailer.php');
?>