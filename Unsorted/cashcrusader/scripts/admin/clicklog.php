<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
echo "<title>Click Log For Paid Mail Ad $emailid</title>

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
<script>window.focus()</script>
<body bgcolor=ffffff><font face=arial size=2 class=fsize2>
<center><h3>Click Log For Paid Mail Ad $emailid</h3><hr></center>";
if ($connum){
$rollback=1;
if ($connum!=$md5time){
$rollback=0;
echo "Invalid confirmation ID. Nothing done";
}}
$md5time=substr(md5(time()),0,6);
$report=@mysql_query("select * from ".$mysql_prefix."paid_clicks_$emailid order by value,time desc");
echo "<form method=post><input type=hidden name=emailid value='$emailid'><input type=hidden name=md5time value=$md5time><br><br>To rollback the clicks for this ad and remove all credits received from it<br>enter this confirmation ID: <font size+1><b>$md5time</b></font><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL CANCEL ALL CLICKS BELOW. ONLY DO THIS IF YOU ARE SURE. IF YOU ROLLBACK THIS AD BEFORE THE DAILY CLICKS ARE PROCESSED UPLINES WILL NOT RECEIVE CREDIT EITHER</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Rollback Now'><br></form>";
echo "<table class=fsize2 border=1><tr><td><b>Username</b></td><td><b>Amount</b></td><td><b>Type</b></td><td><b>Date</b></td><td><b>IP/Host</b>";
while($row=@mysql_fetch_array($report)){
$cashfactor=1;
if ($row[vtype]=='cash'){
$cashfactor=$admin_cash_factor;}
if ($rollback){
if ($row[value]){
$P='';
if ($row[vtype]=='points'){
$P='POINT-';}
@mysql_query("delete from ".$mysql_prefix."clicks_to_process where username='$row[username]' and type='$row[vtype]' and amount=$row[value] limit 1");  
$row[value]=0-$row[value];
$update=@mysql_query("UPDATE ".$mysql_prefix."accounting SET amount=amount+$row[value] WHERE type='$row[vtype]' and username = '$row[username]' and description='#SELF-".$P."EARNINGS#' limit 1");
@mysql_query("delete from ".$mysql_prefix."paid_clicks_$emailid where username='$row[username]'");
}
}
$row[time]=substr($row[time],4,2)."/".substr($row[time],6,2)."/".substr($row[time],0,4)." ".substr($row[time],8,2).":".substr($row[time],10,2);
echo "</td></tr><tr $bgcolor><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td align=right>".number_format($row[value]/100000/$cashfactor,5)."</td><td>$row[vtype]</td><td>$row[time]</td><td>$row[ip_host]";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table>";
