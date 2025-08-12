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
$page_title=$l_searchlogs;
require_once('./heading.php');

?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights < $searchlogaccess)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="cleanlog")
	{
		$logname=$path_logfiles."/search.log";
		if(file_exists($logname))
			if(!@unlink($logname))
			{
				echo "<tr class=\"errorrow\"><td colspan=\"2\">";
				printf($l_cant_delete,$logname);
				echo "</td></tr>";
			}
	}
}
$logcontent="";
$logname=$path_logfiles."/search.log";
$logfile=@fopen($logname,"r");
if($logfile)
{
	$logcontent=fread($logfile,filesize($logname));
	$logcontent=str_replace("\r","",$logcontent);
}
echo "<tr class=\"displayrow\"><td align=\"right\" valign=\"top\" width=\"30%\">";
echo "$l_log_entries:<br><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=cleanlog")."\"><img src=\"gfx/clear.gif\" border=\"0\" title=\"$l_cleanup_log\" alt=\"$l_cleanup_log\"></a></td>";
echo "<td><textarea readonly=\"readonly\" wrap=\"off\" cols=\"60\" rows=\"10\" class=\"sninput\">$logcontent</textarea></td></tr>";
echo "</table></td></tr></table>";
include('./trailer.php');
?>