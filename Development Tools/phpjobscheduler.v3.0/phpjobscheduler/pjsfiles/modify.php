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
include("functions.php");
include_once($installed_config_file);
db_connect();
$query="select * from phpjobscheduler where id=$id";
$result = mysql_query($query);
if (!$result) js_msg("There has been an error: ".mysql_error() );
else $row = mysql_fetch_array($result);
db_close();
// check if its hours
$interval_array = time_unit($row["time_interval"]);

if (ereg("hours",$interval_array[1])>0) $hours=$interval_array[0];
else $hours=-1;
if (ereg("days",$interval_array[1])>0) $days=$interval_array[0];
else $days=-1;
if (ereg("weeks",$interval_array[1])>0) $weeks=$interval_array[0];
else $weeks=-1;
include("add-modify.html");
?>
<script language="JavaScript"><!--
with (document.I_F)
{
 id.value="<? echo $row["id"]; ?>";
 name.value="<? echo $row["name"]; ?>";
 scriptpath.value="<? echo $row["scriptpath"]; ?>";
 hours.value=<? echo $hours; ?>;
 days.value=<? echo $days; ?>;
 weeks.value=<? echo $weeks; ?>;
 time_last_fired.value=<? echo $row['time_last_fired']; ?>;
 button.value="Modify Job";
}
// --></script>