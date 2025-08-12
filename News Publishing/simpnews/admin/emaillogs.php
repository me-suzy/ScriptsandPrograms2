<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_emaillogs;
require_once('./heading.php');

?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights < $emaillogaccess)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="successclean")
	{
		$logname=$path_logfiles."/email_ok.log";
		if(file_exists($logname))
			if(!@unlink($logname))
			{
				echo "<tr class=\"errorrow\"><td colspan=\"2\">";
				printf($l_cant_delete,$logname);
				echo "</td></tr>";
			}
	}
	if($mode=="failedclean")
	{
		$logname=$path_logfiles."/email_error.log";
		if(file_exists($logname))
			if(!@unlink($logname))
			{
				echo "<tr class=\"errorrow\"><td colspan=\"2\">";
				printf($l_cant_delete,$logname);
				echo "</td></tr>";
			}
	}
}
$successcontent="";
$failedcontent="";
$successlogname=$path_logfiles."/email_ok.log";
$successlog=@fopen($successlogname,"r");
if($successlog)
{
	$successcontent=fread($successlog,filesize($successlogname));
	$successcontent=str_replace("\r","",$successcontent);
}
$failedlogname=$path_logfiles."/email_error.log";
$failedlog=@fopen($failedlogname,"r");
if($failedlog)
{
	$failedcontent=fread($failedlog,filesize($failedlogname));
	$failedcontent=str_replace("\r","",$failedcontent);
}
echo "<tr class=\"displayrow\"><td align=\"right\" valign=\"top\" width=\"30%\">";
echo "$l_ok_attempts:<br><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=successclean")."\"><img src=\"gfx/clear.gif\" border=\"0\" title=\"$l_cleanup_log\" alt=\"$l_cleanup_log\"></a></td>";
echo "<td><textarea readonly=\"readonly\" wrap=\"off\" cols=\"60\" rows=\"10\" class=\"sninput\">$successcontent</textarea></td></tr>";
echo "<tr class=\"displayrow\"><td align=\"right\" valign=\"top\" width=\"30%\">";
echo "$l_failed_attempts:<br><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=failedclean")."\"><img src=\"gfx/clear.gif\" border=\"0\" title=\"$l_cleanup_log\" alt=\"$l_cleanup_log\"></a></td>";
echo "<td><textarea readonly=\"readonly\" wrap=\"off\" cols=\"60\" rows=\"10\" class=\"sninput\">$failedcontent</textarea></td></tr>";
echo "</table></td></tr></table>";
include('./trailer.php');
?>