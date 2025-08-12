<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if ($sysval){
while (list($key, $value) = each($sysval)){
@mysql_query("replace into ".$mysql_prefix."system_values set name='$key',value='".trim($value)."'");
}}?>
<html><title>Signup Bonus Settings</title><script>window.focus();</script>
<STYLE TYPE="text/css">
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
<center><h2>Signup Bonus Settings</h2>
<hr>
<br><br><form method=post><table class=fsize2 border=1><tr><td>You can have both points and cash signup bonuses active at the same time. Place 0 in the value to disable signup bonuses<table class=fsize2 border=0><tr><td>Cash Signup Bonus</td><td><input type=text name=sysval[cashsignbonus] value=<? print system_value("cashsignbonus");?>></td></tr><tr><td>
Points Signup Bonus</td><td><input type=text name=sysval[pointsignbonus] value=<? print system_value("pointsignbonus");?>></td></tr></table></td></tr></table>
<input type=submit value='Save Changes'></form>

