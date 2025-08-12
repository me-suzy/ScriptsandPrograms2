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
if (!file_exists($installed_config_file))
{
 include("install.html");
 exit;
}
else include_once($installed_config_file);
db_connect();
$query="select * from phpjobscheduler";
$result = mysql_query($query);
if (!$result) js_msg("There has been an error: ".mysql_error() );
else
{
 if (mysql_num_rows($result))  // check has got some
 {
  $i = 0;
  $table_rows="";
  $bg_colour="#FFFFFF";
  while ($i < mysql_num_rows($result))
  {
   $id=mysql_result($result,$i, 'id');
   $scriptpath=mysql_result($result,$i, 'scriptpath');
   $name=mysql_result($result,$i, 'name');
   $time_interval=mysql_result($result,$i, 'time_interval');
   $fire_time=mysql_result($result,$i, 'fire_time');
   $time_last_fired=mysql_result($result,$i, 'time_last_fired');
   $time_interval = time_unit($time_interval);
   $fire_hours = strftime("%H:%M:%S ",$fire_time);
   $fire_date = strftime("%b %d, %Y",$fire_time);
   if ($bg_colour=="#E9E9E9") $bg_colour="#FFFFFF"; else $bg_colour="#E9E9E9";
   $table_rows.="
      <tr align=\"center\">
      <th align=\"left\" bgcolor=\"$bg_colour\"><small><font color=\"#008000\">&quot;$name&quot;</font> - <a
      href=\"javascript:modify($id);\">MODIFY</a> - <a
      href=\"javascript:deletepjs($id,'$name');\">DELETE</a><br>
      <small>Script path: <font color=\"#000000\">$scriptpath</font></small></small></th>
      <th align=\"center\" bgcolor=\"$bg_colour\"><small>$fire_hours on $fire_date</small></th>
      <th align=\"center\" bgcolor=\"$bg_colour\"><small>$time_interval[0] $time_interval[1]</small></th>
      </tr>";
   $i++;
  }
 }
 else $table_rows="<b><font color=\"#FF0000\">NO Jobs saved - to add a NEW scheduled job click the Add NEW schedule link above.</font></b><br><br>";
}

db_close();

include("main.html");
?>