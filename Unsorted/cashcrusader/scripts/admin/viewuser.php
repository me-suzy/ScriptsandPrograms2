<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
$unixtime=time();
$rand=substr(md5($formusername),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
session_destroy();
session_start();
session_register("admin_password");
$levelcache='';
if ($user_form=='userinfo' and isset($free_refs)){
@mysql_query("update ".$mysql_prefix."users set free_refs='$free_refs' where username='$username'");}
if ($username and $upline){
$newupline=$upline;
while($upline){
list($upline,$chkusername)=@mysql_fetch_row(@mysql_query("select upline,username from ".$mysql_prefix."users where username='$upline' limit 1"));
if (!$uplinecheck[$upline]){
$uplinecheck[$upline]=1;}
else {
@mysql_query("update ".$mysql_prefix."users set upline='' where username='$upline'");
break;}
if ($chkusername){$uplineexists=1;}
if ($chkusername==$username or $upline==$newupline){   
$cantdo=1;
break;
}
}
if (!$cantdo and $uplineexists){
@mysql_query("update ".$mysql_prefix."users set referrer='$newupline',upline='$newupline' where username='$username' limit 1");
@mysql_query("update ".$mysql_prefix."users set rebuild_stats_cache='YES' where username='$username' limit 1");} 
}
if ($username and $description){
$newamount=$newamount*100000;
$type='cash';
if (ereg("POINT",$description)){
$type='points';}else {$newamount=$newamount*$admin_cash_factor;}
@mysql_query("insert into ".$mysql_prefix."accounting set transid='$pickedtransid',unixtime=0,username='$username',description='$description',type='$type',amount='$newamount'");
@mysql_query("update ".$mysql_prefix."accounting set amount='$newamount' where username='$username' and description='$description'");}
if (!$userid){
$userid=$username;}
$userinfo=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."users where username='$userid'"));
list($y,$d,$m)=split("-",$userinfo[vacation]);
$userinfo[vacation]=$m."/".$d."/".$y;
if ($userinfo[vacation]=="00/00/0000"){
$userinfo[vacation]="";}
$username=$userinfo[username];
$password=$userinfo[password];
?>
<html><title>View/Move/Edit/Delete User</title><script>window.focus()</script>                                                        
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
<table class=fsize2><tr><td valign=top><center><form method=post><? if (!$uplineexists and $newupline){ echo "<table class=fsize2 width=100% bgcolor=red><tr><td><font color=ffffff><center>Error! Can not place under $newupline. Member does not exists</td></tr></table>";} elseif ($cantdo){ echo "<table class=fsize2 width=100% bgcolor=red><tr><td><font color=ffffff><center>Error! Can not place under $newupline. An endless loop would be created.</td></tr></table>";}?><input type=hidden name=username value=<? user("username");?>><table border=0 class=fsize2><tr><td>Referred By:</td><td><? user("referrer");?></td></tr><tr><td>Upline:</td><td><input type=text name=upline value='<? user("upline");?>'></td></tr></table><input type=submit value='Move'></form>
<form method=post  action=<? echo $pages_url;?>enter.php target=_membersarea><input type=hidden name=username value="<? user("username");?>"><input type=hidden name=password value="<? user("password");?>"><input type=submit value="Log in to the member area"></form>
<form method=post><br>Signup Date: <? user("signup_date");?><br>Signup IP/Host: <? user("signup_ip_host");?><table class=fsize2>
<input type=hidden name=user_form value=userinfo>
<input type=hidden name=required_keywords value=3>
<input type=hidden name=required value='email,first_name,last_name,address,city,state,zipcode,country'>
<tr><td>Username:</td><td> <? user("username");?></td></tr>
<? form_errors("email","You must place an email address in the email address field","The email address you select is already in use please try another","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?> 
<tr><td><a href=mailto:<? user("email");?>>E-Mail:</a></td><td> <input type="text" name="userform[email]" value="<? user("email");?>"></td></tr>
<? form_errors("first_name","You must place your first name in the first name field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr><td>First Name:</td><td> <input type="text" name="userform[first_name]" value="<? user("first_name");?>"></td></tr>
<? form_errors("last_name","You must place your last name in the last name field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?> 
<tr><td>Last Name:</td><td> <input type="text" name="userform[last_name]" value="<? user("last_name");?>"></td></tr>
<? form_errors("address","You must place your street address in the address field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr><td>Address:</td><td> <input type="text" name="userform[address]" value="<? user("address");?>"></td></tr>
<? form_errors("city","You must place your city in the city field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>   
<tr><td>City:</td><td> <input type="text" name="userform[city]" value="<? user("city");?>"></td></tr>
<? form_errors("state","You must place your state in the state field or type N/A if you do not have a state","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr><td>State:</td><td> <input type="text" name="userform[state]" value="<? user("state");?>"></td></tr>            
<? form_errors("zipcode","You must place your zip or postal code in the zip code field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr><td>Zip Code:</td><td> <input type="text" name="userform[zipcode]" value="<? user("zipcode");?>"></td></tr>
<? form_errors("country","You must place your county in the country field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr><Td>Country:</td><td> <input type="text" name="userform[country]" value="<? user("country");?>"></td></tr>
<? form_errors("vacation","You have entered an invalid date. Please use the format MM/DD/YYYY","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr><td>
Vacation</td><td><input type=date name=userform[vacation] value=<? user("vacation");?>></td></tr>
<tr><td>Free Referrals?</td><td><input type=text name=free_refs value=<? echo $userinfo[free_refs];?>></td></tr><tr><td colspan=2><font size=-2>Enter 0 to disable. Enter 1 or higher to enable. Accounts with higher numbers will receive free referrals more often then accounts with lower numbers<br><br></font>
</td></tr><tr><td>Payment method:</td><td><select name=userform[pay_type]>
<? $getkeys=@mysql_query("select pay_type from ".$mysql_prefix."users group by pay_type");
while($row=@mysql_fetch_row($getkeys)){
echo "<option value='$row[0]' ";
if ($row[0]==$userinfo[pay_type]){echo "selected";}
echo ">$row[0]";}
echo "</select>";?>
</td></tr>
<tr><td>Payment account ID:</td><td><input type=text value="<? user("pay_account");?>" name=userform[pay_account]></td></tr>
<tr><td colspan=2><hr></td></tr>
<? form_errors("password","The password you entered did not match what you put in the confirmation field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr><td>New Password:</td><td><input type=password name=userform[password] value=""></td></tr>
<tr><td>Confirm New Password:</td><td><input type=password name=userform[confirm_password] value=""></td></tr>
<tr><td colspan=2><hr></td></tr>
<tr><td colspan=2>
Select categories of interests:<br>
<? $getkeys=@mysql_query("select keyword,count(*) from ".$mysql_prefix."interest where keyword not like 'c:%' 
group by keyword");
$idx=0;
while($row=@mysql_fetch_row($getkeys)){
echo "<input type=checkbox name=keyword[$idx] value=$row[0] ";
interests(strtolower($row[0]),"checked");
echo ">$row[0]<br>";$idx++;}?>
</td></tr>
<tr><td colspan=2><hr></td></tr>
<tr><td colspan=2 align=center><input type="submit" value="Save Changes"></td></tr>         
<input type=hidden value=1 name=admin_form>
<input type=hidden value="<? user("username");?>" name=username>
<input type=hidden value="<? user("password");?>" name=password>
</form>
</table><hr><table class=fsize2>
<form method=post>
<input type=hidden name=user_form value=userinfo>
<input type=hidden value=1 name=admin_form>
<? form_errors("cancel_password","Your account was not cancelled because you entered your password incorrectly","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr><td align=center><font face=arial>To cancel this account type <b><?=$password;?></b> in the field below and click on the cancel button<br><input type=hidden name=username value="<?=$username;?>"><input type=hidden name=password value="<?=$password;?>"><input type=text name=userform[cancel_password]><br><input type=submit value=Cancel></form></td></tr></table></td><td valign=top>
<table class=fsize2 border=0><tr><td valign=top><table class=fsize2 width=100% cellpadding=4 border=1>
<tr><td align=right>Direct cash earnings:</td><form method=post><td align=right><input type=hidden name=description value='#SELF-EARNINGS#'><input type=text name='newamount' value=<? cash_earnings(5,$admin_cash_factor,".","");?>><input type=submit value=Update><input type=hidden name=username value=<? user("username");?>></td></form></tr>
<tr><td align=right>
Downline cash earnings:</td>
<form method=post><td align=right>
<input type=hidden name=description value='#DOWNLINE-EARNINGS#'><input type=text name='newamount' value=<? dlcash_earnings(5,$admin_cash_factor,".","");?>><input type=submit value=Update><input type=hidden name=username value=<? user("username");?>></td></form></tr>
<tr><td align=right>
Cash account balance after all transactions:<td align=right>
<? cash_totals('all',5,$admin_cash_factor);?></td></tr>
</table><br><table class=fsize2 width=100% cellpadding=4 border=1>
<tr><td align=right>
Direct point earnings:</td><form method=post><td align=right>
<input type=hidden name=description value='#SELF-POINT-EARNINGS#'><input type=text name='newamount' value=<? points_earnings(5,1,".","");?>><input type=submit value=Update><input type=hidden name=username value=<? user("username");?>></td></form></tr>
<tr><td align=right>
Downline point earnings:</td>
<form method=post><td align=right>
<input type=hidden name=description value='#DOWNLINE-POINT-EARNINGS#'><input type=text name='newamount' value=<? dlpoints_earnings(5,1,".","");?>><input type=submit value=Update><input type=hidden name=username value=<? user("username");?>></td></form></tr>
<tr><td align=right>
Point account balance after all transations:</td><td align=right>
<? points_totals('all',5);?></td></tr>
</table>
<br>
<table class=fsize2 border=1 width=100%><tr><td>
<center>Downline Count: <? level_total();?></center>
<?
$pointclicks=split(",",system_value("pointclicks"));
$cashclicks=split(",",system_value("cashclicks"));
$levelcount=count($pointclicks);
if ($pointclicks[$levelcount-1]=="*"){
list($levelcount)=@mysql_fetch_row(@mysql_query("select max(level) from ".$mysql_prefix."levels"));}
if (count($cashclicks)>$levelcount){
$levelcount=count($cashclicks);
if ($cashclicks[$levelcount-1]=="*"){
list($levelcount)=@mysql_fetch_row(@mysql_query("select max(level) from ".$mysql_prefix."levels"));}
}
?>
<form><? for ($idx=0;$idx<$levelcount;$idx++){
$level=$idx+1;
echo "<select><option>Level: $level";
$dowlinegrab=@mysql_query("select username from ".$mysql_prefix."levels where upline='$userid' and level=$idx");
while ($row=@mysql_fetch_array($dowlinegrab)){
echo "<option>$row[username]";}
echo "</select><br>";}
?></form>
</td></tr>
</table>
<table class=fsize2 border=0 cellpadding=4><tr><td>
<center>Recent advertiser that you have received credit for visiting</center><hr>
<? latest_visits();?>
</td></tr></table>
</td><td valign=top>
<table class=fsize2 width=350 border=1><tr><td align=center colspan=2>
Account Transactions</td></tr><tr>
<td colspan=3 align=center>Cash Credits<br><a href=transactions.php?usersearch=<? user("username");?>&transtype=cash target=_transactions><font size=-1>(edit cash transations)</font></a></td></tr>
<? cash_transactions("credits","<tr><td align=right>","</td><td align=right>","</td></tr>","desc","yes","</td><td>",5,$admin_cash_factor);?>
<tr><td align=right>Total Cash Credits:</td><td colspan=2 align=right><? cash_totals("credits",5,$admin_cash_factor);?></td></tr>
<tr><td align=center colspan=3> Cash Debits<br><a href=transactions.php?usersearch=<? user("username");?>&transtype=cash target=_transactions><font size=-1>(edit cash transations)</font></a></td></tr>
<? cash_transactions("debits","<tr><td align=right>","</td><td align=right>","</td></tr>","desc","yes","</td><td>",5,$admin_cash_factor);?>
<tr><td align=right>Total Cash Debits:</td><td colspan=2 align=right><? cash_totals("debits",5,$admin_cash_factor);?></td></tr>
</table>
<br><table class=fsize2 width=350 border=1>
<td colspan=3 align=center>Point Credits<br><a href=transactions.php?usersearch=<? user("username");?>&transtype=points target=_transactions><font size=-1>(edit point transations)</font></a></td></tr>
<? point_transactions("credits","<tr><td align=right>","</td><td align=right>","</td></tr>","desc","yes","</td><td>",5);?>          
<tr><td align=right>Total Point Credits:</td><td align=right colspan=2><? points_totals("credits",5);?></td></tr>             
<tr><td align=center colspan=3> Point Debits<br><a href=transactions.php?usersearch=<? user("username");?>&transtype=points target=_transactions><font size=-1>(edit point transations)</font></a></td></tr>                        
<? point_transactions("debits","<tr><td align=right>","</td><td align=right>","</td></tr>","desc","yes","</td><td>",5);?>                                       
<tr><td align=right>Total Point Debits:</td><td align=right colspan=2><? points_totals("debits",5);?></td></tr>               
</table>       
</td></tr></table>
<? session_unregister(levelcache);?>
