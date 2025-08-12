<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if (!isset($days)){$days=15;}
echo "<title>Browser Blocker</title><script>window.focus()</script>
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
<center><h2>Browser Blocker</h2><hr></center>";
if ($save){
@mysql_query("update ".$mysql_prefix."browsers set block='N'");
if ($uid){
while (list($key, $value) = each($uid)){
@mysql_query("update ".$mysql_prefix."browsers set block='Y' where md5agent='$value'");
}
}}
$report=@mysql_query("select * from ".$mysql_prefix."browsers order by agent");
echo "<form method=post><input type=submit name=save value='Save Changes'><br>";
echo "<table border=1 class=fsize2><tr><td><b>Block</b></td><td><b>Browser</b>";
while($row=@mysql_fetch_array($report)){
$counter++;
$checked='';
if ($row[block]=="Y"){$checked='checked';}
if (!$row[agent]){
$row[agent]='Browsers that do not identify themselves. (blocking is recommended)';
}
echo "</td></tr><tr $bgcolor><td><input type=checkbox name=\"uid[$counter]\" value=\"$row[md5agent]\" $checked></td><td>$row[agent]";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table><input type=submit name=save value='Save Changes'></form>";
echo "</body>";
