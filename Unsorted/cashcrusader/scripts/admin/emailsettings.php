<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if ($sysval){
while (list($key, $value) = each($sysval)){
@mysql_query("replace into ".$mysql_prefix."system_values set name='$key',value='".addslashes(trim($value))."'");
}}?>
<html><title>eMail Settings</title><script>window.focus();</script>
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
<center><h2>eMail Settings</h2>
</center><hr>
<br><br><form method=post><table class=fsize2 border=0><tr><td align=right>Redemption notices are sent to: </td><td><input type=text size=30 name=sysval[redemption_email] value='<? print system_value("redemption_email");?>'></td></tr>

<tr><td align=right>Mass emails are sent as being from: </td><td><input type=text size=30 name=sysval[massmail_email]  value='<? print system_value("massmail_email");?>'></td></tr>
</table>
<input type=submit value='Save Changes'></form>

