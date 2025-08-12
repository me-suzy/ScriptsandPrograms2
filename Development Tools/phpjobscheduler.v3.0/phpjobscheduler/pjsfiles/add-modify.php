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
if ($hours>0) $time_interval=$hours * 3600;
elseif ($days>0) $time_interval=$days * 86400;
else $time_interval=$weeks * 604800;

if ($id>0)
{
 $fire_time = $time_last_fired + $time_interval;
 $query="UPDATE phpjobscheduler
         SET
          name='$name',
          scriptpath='$scriptpath',
          time_interval='$time_interval',
          fire_time='$fire_time',
          time_last_fired='$time_last_fired'
         WHERE id='$id'";
}
else
{
 $time_last_fired = time();
 $fire_time = $time_last_fired + $time_interval;
 $query="INSERT INTO phpjobscheduler VALUES
   ('NULL',
    '$scriptpath',
    '$name',
    '$time_interval',
    '$fire_time',
    '$time_last_fired')";
}

db_connect();
$result = mysql_query($query);
if (!$result) js_msg("There has been an error: ".mysql_error() );
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