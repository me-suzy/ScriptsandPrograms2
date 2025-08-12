<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if (!isset($days)){$days=30;}
echo "<title>Inactive Members Report</title><script>window.focus()</script>
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
<center><h2>Inactive Members Report</h2><hr></center><form method=post>List members that have not clicked on a link in <input type=text name=days value=$days> days. <br><input type=submit name=report value=Report></form>";
if ($connum){
if ($connum!=$md5time){
echo "Invalid confirmation ID. Nothing deleted";
exit;
}
if ($uid){
while (list($key, $value) = each($uid)){
list($upline)=@mysql_fetch_row(@mysql_query("select upline from  ".$mysql_prefix."users where username='$key'"));
delete_user($key,$upline);
echo "Deleted: $key<br>";
}
exit;
}
}
if ($report){
$result=@mysql_query("select ".$mysql_prefix."accounting.username,".$mysql_prefix."accounting.time from ".$mysql_prefix."accounting left join ".$mysql_prefix."click_counter on ".$mysql_prefix."accounting.username=".$mysql_prefix."click_counter.username where ".$mysql_prefix."accounting.description like '#SELF-%' and ".$mysql_prefix."click_counter.username is NULL order by ".$mysql_prefix."accounting.time desc");           
while($row=@mysql_fetch_array($result)){
$lastclose[0]=$row[time];
$time=mktime(substr($lastclose[0],-6,2),substr($lastclose[0],-4,2),substr($lastclose[0],-2,2),substr($lastclose[0],-10,2),substr($lastclose[0],-8,2),substr($lastclose[0],0,4));
@mysql_query("insert into ".$mysql_prefix."click_counter set time='$time',username='$row[username]'");
}
mysql_free_result($result);
$result=@mysql_query("select ".$mysql_prefix."users.username,".$mysql_prefix."users.signup_date from ".$mysql_prefix."users left join ".$mysql_prefix."click_counter on ".$mysql_prefix."users.username=".$mysql_prefix."click_counter.username where ".$mysql_prefix."click_counter.username is NULL");
while($row=@mysql_fetch_array($result)){
$lastclose[0]=$row[signup_date];
$time=mktime(substr($lastclose[0],-8,2),substr($lastclose[0],-5,2),substr($lastclose[0],-2,2),substr($lastclose[0],-14,2),substr($lastclose[0],-11,2),substr($lastclose[0],0,4));
@mysql_query("insert into ".$mysql_prefix."click_counter set time='$time',username='$row[username]'");
}
$time=time()-($days*60*60*24);
$report=@mysql_query("select ".$mysql_prefix."click_counter.username,".$mysql_prefix."click_counter.time,".$mysql_prefix."users.free_refs,".$mysql_prefix."users.vacation from ".$mysql_prefix."click_counter,".$mysql_prefix."users where ".$mysql_prefix."click_counter.username=".$mysql_prefix."users.username and ".$mysql_prefix."click_counter.time<=$time order by ".$mysql_prefix."click_counter.time desc ");
$md5time=substr(md5(time()),0,6);
echo "<form method=post><input type=hidden name=md5time value=$md5time><br><br>To delete all checked accounts below<br>enter this confirmation ID: <font size+1><b>$md5time
</b></font><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL DELETE ALL CHECKED ACCOUNTS BELOW. ONLY DO THIS IF YOU ARE SURE</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Delete Now'><br>"; 
echo "<table border=1 class=fsize2><tr><td><b>Delete</b></td><td><b>Username</b></td><td><b>Free Referrals Setting</b></td><td><b>Vacation</b></td><td><b>Last Click</b>";
while($row=@mysql_fetch_array($report)){
list($y,$d,$m)=split("-",$row[vacation]);
$row[vacation]=$m."/".$d."/".$y;
if (intval($m) or intval($d) or intval($y) or $row[free_refs]>0){
$checked='';} else {$checked='checked';}
if ($row[free_refs]<1){$row[free_refs]="";}
if (!intval($m) and !intval($d) and !intval($y)){
$row[vacation]='';}
echo "</td></tr><tr $bgcolor><td><input type=checkbox name=\"uid[$row[username]]\" value=1 $checked></td><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td>$row[free_refs]</td><td>$row[vacation]</td><td align=right>".strftime("%m/%d/%Y %H:%M:%S",$row[time]);
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table></form>";}
echo "</body>";
