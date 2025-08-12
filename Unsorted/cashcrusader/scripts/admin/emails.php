<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
echo "<title>Email Blocker</title><script>window.focus()</script>
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
<center><h2>Email Blocker</h2><hr></center>";
if ($blockemail){
$blockemail=addslashes($blockemail);
@mysql_query("insert into ".$mysql_prefix."emails set address='$blockemail'");}
if ($uid){
while (list($key, $value) = each($uid)){
@mysql_query("delete from ".$mysql_prefix."emails where address='$value'");
}
}
$report=@mysql_query("select * from ".$mysql_prefix."emails order by address");
echo "<form method=post>Use % as a wild card. ex: If you wish to block all emails in a domain ending with hotmail.com then put %@hotmail.com in the field below<br>If you want to block just an email address like bob@hotmail.com just enter bob@hotmail.com in the field below<br>Add email to block: <input type=text size=30 maxlength=64 name=blockemail><br><input type=submit name=save value='Save Changes'><br>";
echo "<table border=1 class=fsize2><tr><td><b>Delete</b></td><td><b>Email</b>";
while($row=@mysql_fetch_array($report)){
$counter++;
$checked='';
echo "</td></tr><tr $bgcolor><td><input type=checkbox name=\"uid[$counter]\" value=\"$row[address]\"></td><td>".stripslashes($row[address]);
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table><input type=submit name=save value='Save Changes'></form>";
echo "</body>";
