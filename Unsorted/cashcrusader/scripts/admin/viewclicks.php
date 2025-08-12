<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if (!isset($to)){$to=1000000;}
if (!isset($from)){$from=0;}
echo "<title>Click Report</title><script>window.focus()</script>
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
<center><h2>Click Report</h2><hr></center><form method=post>List all members with total clicks ranging<br>from: <input type=text name=from value=$from> to: <input type=text name=to value=$to><input type=submit name=report value=Report></form>";
$f=$from;
$t=$to;
if ($connum){
if ($connum!=$md5time){
echo "Invalid confirmation ID. Counters not reset";
}
else { @mysql_query("update ".$mysql_prefix."click_counter set counter=0");}}
echo "<form method=post>";
$md5time=substr(md5(time()),0,6);
echo "<form method=post><input type=hidden name=md5time value=$md5time><br><br>To reset click counters for all accounts<br>enter this confirmation ID: <font size+1><b>$md5time</b></font><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL RESET ALL COUNTERS FOR ALL ACCOUNTS</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Reset Now'></form>";
if ($report){
$report=@mysql_query("select username,counter from ".$mysql_prefix."click_counter where counter>=$f and counter<=$t order by counter desc");
echo "<br><table class=fsize2 border=1><tr><td><b>Username</b></td><td><b>Total Clicks</b>";
while($row=@mysql_fetch_array($report)){
echo "</td></tr><tr $bgcolor><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td align=right>$row[counter]";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table>";}
