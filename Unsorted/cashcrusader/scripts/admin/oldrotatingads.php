<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if (!isset($days)){$days=15;}
echo "<title>Old Rotating Ads Report</title><script>window.focus()</script>
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
<center><h2>Old Rotating Ads Report</h2><hr></center><form method=post>List ads that have that have not been shown in <input type=text name=days value=$days> days. <br><input type=submit name=report value=Report></form>";
if ($connum){
echo "<br><br>";
if ($connum!=$md5time){
echo "Invalid confirmation ID. Nothing deleted";
exit;
}
if ($uid){
while (list($key, $value) = each($uid)){
@mysql_query("delete from ".$mysql_prefix."rotating_ads where bannerid='$key'");
echo "Deleted: $key<br>";
}
@mysql_query("optimize table ".$mysql_prefix."email_ads");
}
}
if ($report){
$time=date("YmdHis",time()-($days*60*60*24));
$report=@mysql_query("select bannerid,description,time,run_type,run_quantity,views,clicks from ".$mysql_prefix."rotating_ads where time<'$time' order by time desc");
$md5time=substr(md5(time()),0,6);
echo "<form method=post><input type=hidden name=md5time value=$md5time><br><br>To delete all checked ads below<br>enter this confirmation ID: <font size+1><b>$md5time
</b></font><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL DELETE ALL CHECKED ADS BELOW. ONLY DO THIS IF YOU ARE SURE</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Delete Now'><br>"; 
echo "<table border=1 class=fsize2><tr><td><b>Delete</b></td><td><b>Ad ID</b></td><td><b>Description</b></td><td><b>Type</b></td><td><b>Expires at</b></td><th>Views</th><th>Clicks</th><td><b>Last Shown</b>";
while($row=@mysql_fetch_array($report)){
if ($row[run_type]=='ongoing' or ($row[run_quantity]>$row[clicks] and $row[run_type]=='clicks') or ($row[run_quantity]>$row[views] and $row[run_type]=='views')){
$checked='';} else {$checked='checked';}
echo "</td></tr><tr $bgcolor><td><input type=checkbox name=\"uid[$row[bannerid]]\" value=1 $checked></td><td>$row[bannerid]</a></td><td>$row[description]</td><td>$row[run_type]</td><td>$row[run_quantity]</td><td>$row[views]</td><td>$row[clicks]</td><td>".mytimeread($row[time]);
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table></form>";}
echo "</body>";
