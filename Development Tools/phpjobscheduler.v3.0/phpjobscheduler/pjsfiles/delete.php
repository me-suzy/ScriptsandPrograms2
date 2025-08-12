<?
/**********************************************************
 *                phpJobScheduler                         *
 *           Author:  DWalker.co.uk                        *
 *    phpJobScheduler © Copyright 2003 DWalker.co.uk      *
 *              All rights reserved.                      *
 **********************************************************
 *        Launch Date:  Oct 2003                          *
 *     3.0       Nov 2005       Released under GPL/GNU    *
 *     Version    Date              Comment               *
 *     1.0       14th Oct 2003      Original release      *
 *     2.0       Oct 2004         Improved functions      *
 *     3.0       Nov 2005       Released under GPL/GNU    *
 *  NOTES:                                                *
 *        Requires:  PHP 4.2.3 (or greater)               *
 *                   and MySQL                            *
 **********************************************************/
 $app_name = "phpJobScheduler";
 $phpJobScheduler_version = "3.0";
// ---------------------------------------------------------
include_once("functions.php");
include_once($installed_config_file);
db_connect();
$query="delete from phpjobscheduler where id=$id";
$result = mysql_query($query);
if (!$result) js_msg("There has been an error: ".mysql_error());
else js_msg("Job deleted");
db_close();
?>
<script language="JavaScript"><!--
function moveit()
{
 url2open="index.php";
 document.location=url2open;
}
moveit();
// --></script>