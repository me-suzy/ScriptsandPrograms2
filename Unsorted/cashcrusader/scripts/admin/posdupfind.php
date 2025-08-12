<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
echo "<title>List accounts that MAY belong to cheaters</title><script>window.focus()</script>
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
<center><h2>List accounts that MAY belong to cheaters</h2><hr></center>The list of accounts below match very closely. The only difference is the full name. More then likely they belong to cheaters. But of course use your own judgement<br>";
if ($connum){
echo "<br><br>";
if ($connum!=$md5time){
echo "Invalid confirmation ID. Nothing deleted";
}
elseif ($uid){
while (list($key, $value) = each($uid)){
list($upline)=@mysql_fetch_row(@mysql_query("select upline from  ".$mysql_prefix."users where username='$key'"));
delete_user($key,$username);
echo "Deleted: $key<br>";
}
exit;
}
}
$report=@mysql_query("select password,pay_account,referrer,signup_ip_host,count(*) as count from ".$mysql_prefix."users group by signup_ip_host,password,pay_account,referrer order by count desc");
$md5time=substr(md5(time()),0,6);
echo "<form method=post><input type=hidden name=md5time value=$md5time>To delete all checked accounts below<br>enter this confirmation ID: <font size+1><b>$md5time
</b></font><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL DELETE ALL CHECKED ACCOUNTS BELOW. ONLY DO THIS IF YOU ARE SURE</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Delete Now'><br>"; 
echo "<table border=1 class=fsize2><tr><td><b>Delete</b></td><td><b>Username</b></td><td><b>Encrypted Password</b></td><td><b>Referrer</b></td><td><b>Full Name</b></td><td><b>Payment Account</b></td><td><b>Signup IP/HOST and Date</b>";
while($getlist=@mysql_fetch_array($report)){
if ($getlist[count]<2){
break;
}
$userreport=@mysql_query("select * from ".$mysql_prefix."users where password='$getlist[password]' and pay_account='$getlist[pay_account]' and referrer='$getlist[referrer]' and signup_ip_host='$getlist[signup_ip_host]'");
while($row=@mysql_fetch_array($userreport)){
echo "</td></tr><tr $bgcolor><td><input type=checkbox name=\"uid[$row[username]]\" value=1 checked></td><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td>$row[password]</td><td>$row[referrer]</td><td>$row[first_name] $row[last_name]</td><td>$row[pay_account]</td><td>$row[signup_ip_host]<br>$row[signup_date]";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
}
echo "</td></tr></table></form>";
echo "</body>";
