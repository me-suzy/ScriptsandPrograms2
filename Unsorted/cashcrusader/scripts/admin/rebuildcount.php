<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if (!isset($days)){$days=30;}
echo "<title>Rebuild downline count stats</title><script>window.focus()</script>
<STYLE TYPE=\"text/css\">
  <!--
    A {text-decoration:none;}
    A:hover {text-decoration:underline;}
    .fsize1 {font-family: Arial, Helvetica, sans-serif; font-size: 11px;}
    .fsize2 {font-family: Arial, Helvetica, sans-serif; font-size: 13px;}
    .fsize3 {font-family: Arial, Helvetica, sans-serif; font-size: 14px;}
    .fsizebig {font-family: Arial, Helvetica, sans-serif; font-size: 18px;}
 -->
  </STYLE>
<body bgcolor=ffffff><font face=arial size=2 class=fsize2>
<center><h2>Rebuild downline count stats</h2><hr></center>";
if ($connum){
echo "<br><br>";
if ($connum!=$md5time){
echo "Invalid confirmation ID. Nothing done";
}
else {@mysql_query("update ".$mysql_prefix."users set rebuild_stats_cache='YES'");
echo "Downline count stats cache will be completely rebuilt. You may now leave this page<br>";
exit;
}}
echo "<form method=post>";
$md5time=substr(md5(time()),0,6);
echo "<form method=post><input type=hidden name=md5time value=$md5time>To completely rebuild the downline count stats cache<br>enter this confirmation ID: <font size+1><b>$md5time
</b></font><br><br><b>This utility is completely safe so there is no need to make a backup. however it is very demanding on the server so only do it if you know that the downline count stats are messed up. Downline count stats do not affect the crediting of the upline they are only used to display the downline counts on the members stats page</b><br><br>Confirmation ID: <input type text name=connum><input type=submit value='Start rebuild'><br>"; 
echo "</form>";
echo "</body>";
