<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
echo "<title>Convert ALL points to cash</title><script>window.focus()</script><center><h2>Convert ALL points to cash</h2><hr></center>";
$check=@mysql_fetch_row(@mysql_query("select * from ".$mysql_prefix."system_values where name='convert points'"));
if ($check[0]){
echo "<br><br>Points conversion has already been started. You can not process another conversion untill this one is complete";
exit;}

if ($connum){
echo "<br><br>";
if ($connum==$md5time){
@mysql_query("insert into ".$mysql_prefix."system_values set name='convert points',value='$cvalue'");
echo "Point conversion will be processed during the next Cron Job. You may now close this window";
exit;
}
}
echo "<form method=post>To calculate point value, enter the TOTAL cash amount you wish the TOTAL points to be converted to: <br><input type=text name=cash value='$cash'><input type=submit value=Report></form>";
if ($connum){
if ($connum!=$md5time){
echo "Invalid confirmation ID. Nothing done!";
exit;
}
}
if ($cash>0){
list($report)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='points'"));
if ($report){
echo "<br><br>The current total of points: ".number_format($report/100000,5);
echo "<br>Will be converted to a cash value of: ".number_format($cash/($report/100000),5)." per point";
$md5time=substr(md5(time()),0,6);
echo "<form method=post><input type=hidden name=md5time value=$md5time><br><br>To convert all points to cash and reset points to zero<br>enter this confirmation ID: <font size+1><b>$md5time
</b></font><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS CAN NOT BE UNDONE. ONLY DO THIS IF YOU ARE SURE</b><br>
Confirmation ID: <input type text name=connum><input type=hidden name=md5time value=$md5time><input type=hidden name=cvalue value=".$cash/($report/100000)."><input type=submit value='Convert Now'><br>";
} 
else {echo "<br><br>There are not points to convert";} 
}
echo "</form>";
