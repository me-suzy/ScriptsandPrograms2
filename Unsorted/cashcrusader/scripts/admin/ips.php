<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
echo "<title>IP Blocker</title><script>window.focus()</script>
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
<center><h2>IP Blocker</h2><hr></center>";
if ($blockip){
$blockip=addslashes($blockip);
@mysql_query("insert into ".$mysql_prefix."ips set ip='$blockip'");}
if ($uid){
while (list($key, $value) = each($uid)){
@mysql_query("delete from ".$mysql_prefix."ips where ip='$value'");
}
}
$report=@mysql_query("select * from ".$mysql_prefix."ips order by ip");
echo "<form method=post>Use % as a wild card. ex: If you wish to block all IPs starting with 192.168. then put 192.168.% in the field below<br>If you want to block all hosts that are Squid cache servers put %squid% in the field below<br>Add IP or host name to block: <input type=text size=30 maxlength=64 name=blockip><br><input type=submit name=save value='Save Changes'><br>";
echo "<table border=1 class=fsize2><tr><td><b>Delete</b></td><td><b>IP</b>";
while($row=@mysql_fetch_array($report)){
$counter++;
$checked='';
echo "</td></tr><tr $bgcolor><td><input type=checkbox name=\"uid[$counter]\" value=\"$row[ip]\"></td><td>".stripslashes($row[ip]);
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table><input type=submit name=save value='Save Changes'></form>";
echo "</body>";
