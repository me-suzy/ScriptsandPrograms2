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
$time_and_window =  time() + TIME_WINDOW;
$query="select * from phpjobscheduler
        WHERE fire_time <= $time_and_window";
$result = mysql_query($query);
$scripts_to_run = array();
if (mysql_num_rows($result))  // check has got some
{
 $i = 0;
 while ($i < mysql_num_rows($result))
 {
  $id=mysql_result($result,$i, 'id');
  $scriptpath=mysql_result($result,$i, 'scriptpath');
  $time_interval=mysql_result($result,$i, 'time_interval');
  $fire_time=mysql_result($result,$i, 'fire_time');
  $time_last_fired=mysql_result($result,$i, 'time_last_fired');
  $fire_time_new = $fire_time + $time_interval;
  $scripts_to_run[$i]="$scriptpath";
  $query="UPDATE phpjobscheduler
          SET
           fire_time='$fire_time_new',
           time_last_fired='$fire_time'
          WHERE id='$id'";
  mysql_query($query);
  $i++;
 }
}
db_close();

// run the scheduled scripts
for ($i = 0; $i < count($scripts_to_run); $i++) include($scripts_to_run[$i]);

// return image - used for html pages img tag
if (isset($return_image)) include("clearpixel.gif");
?>