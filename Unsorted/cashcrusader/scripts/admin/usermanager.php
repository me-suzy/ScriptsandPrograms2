<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if ($free_refs=='YES'){
$free_refs="and free_refs>0";}
if ($free_refs=='NO'){
$free_refs="and free_refs<1";}
?><html><title>User Search</title><script>window.focus();</script><body bgcolor=ffffff><center>
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
<font face=arial size=2 class=fsize2><h2>User Manager</h2></center><hr>
<a href=http://myecom.net/main/thegetpaidsite/usermgr_instructions.htm target=_instructions><font size=4>CLICK HERE FOR INSTRUCTIONS</font></a><br><br>
<form action=usermanager.php method=post><input type=submit name=nouplinesearch value='List users without uplines'><form><br><br>
<form action=usermanager.php method=post>Search Users Database (leave blank to list all users) 
<table border=0><tr><td>
<font face=arial size=2 class=fsize2>Username: </font></td><td><input type=text name=user></td></tr><tr><td><font face=arial size=2 class=fsize2>Encrypted Password:</font></td><td><input type=text name=pass></td></tr><tr><td><font face=arial size=2 class=fsize2>eMail:</font></td><td><input type=text name=email></td></tr><tr><td><font face=arial size=2 class=fsize2>Upline:</font></td><td><input type=text name=upline></td></tr><tr><td><font face=arial size=2 class=fsize2>First Name:</font></td><td><input type=text name=first></td></tr><tr><td><font face=arial size=2 class=fsize2>Last Name:</font></td><td><input type=text name=last></td></tr><tr><td><font face=arial size=2 class=fsize2>Country:</font></td><td><input type=text name=country></td></tr><tr><td><font face=arial size=2 class=fsize2>IP/Host:</font></td><td><input type=text name=host></td></tr></table>List <input type=text name=limit value=100> users <input type=hidden name=get value=search><br>Free Referrals Status: <select name=free_refs><option value=''>Either<option value="YES">Only show accounts receiving free referrals<option value="NO">Only show accounts not receiving free referrals</select><br><input type=submit value='Search'><br></form><br>
<?
if ($get=='search'){$user="%".$user."%";
$upline="%$upline%";
$country="%".$country."%";
$pass="%".$pass."%";
$first="%".$first."%";
$last="%".$last."%";
$host="%".$host."%";
$email="%".$email."%";
}
echo "<table border=1><tr><th><font face=arial size=2 class=fsize2>Username, Encrypted Password, eMail and Upline</font></th><th><font face=arial size=2 class=fsize2>Full Name</font></th><th><font face=arial size=2 class=fsize2>Country</font></th><th><font face=arial size=2 class=fsize2>Signup IP/HOST and Date</font></th></tr>";
if (!$searchphrase){$searchphrase='*****************************';}
if ($limit){$limit="limit $limit";}
if (!$nouplinesearch){
$getads=@mysql_query("select * from ".$mysql_prefix."users where (username like '$user' and upline like '$upline' and password like '$pass' and email like '$email' and first_name like '$first' and last_name like '$last' and country like '$country' and signup_ip_host like '$host') $free_refs order by username $limit"); 
}
else {$getads=@mysql_query("select ".$mysql_prefix."users.* from ".$mysql_prefix."users left join ".$mysql_prefix."users as ".$mysql_prefix."users2 on ".$mysql_prefix."users.upline=".$mysql_prefix."users2.username where ".$mysql_prefix."users2.username is NULL");}
while($row=@mysql_fetch_array($getads)){
echo "<form action=viewuser.php method=post target=_viewuser><input type=hidden name=userid value='$row[username]'><tr $bgcolor><td><table border=0 class=fsize2><tr><th>Username:</th><td>$row[username]</td></tr><tr><th>Encrypted Password:</th><td>$row[password]</td></tr><tr><th>eMail:</th><td>$row[email]</td></tr><tr><th>Upline:</th><td>$row[upline]</td></tr></table><input type=submit name=mode value='View/Move/Edit/Delete'></td><td><font face=arial size=2 class=fsize2>$row[first_name] $row[last_name]</font></td><td><font face=arial size=2 class=fsize2>$row[country]</font></td></td><td><font face=arial size=2 class=fsize2>$row[signup_ip_host]<br>$row[signup_date]</font></td></tr></form>";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</table>";
$count=mysql_num_rows($getads);
if ($searchphrase){echo "<b>".$count." user(s) found</b><br><br>";}
echo "</html>";
