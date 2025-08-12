<?php
/*
##########################################################
## This script is copyrighted to MyECom Online
## Duplication, selling, or transferring of this script
## is a violation of the copyright and purchase agreement   
## unless you have received approval from MyECom Online 
## before doing so.
##
## Alteration of this script in any way voids any
## responsibility MyECom Online has towards the
## functioning of the script. 
##########################################################
*/
srand((double) microtime() * 1000000);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                                                     // always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                          // HTTP/1.0
$refid=trim($refid);
if (strtolower($mysql_pconnect)=="no"){
@mysql_connect($mysql_hostname,$mysql_user,$mysql_password);
} else {
@mysql_pconnect($mysql_hostname,$mysql_user,$mysql_password);
}
if (!$no_db_error){
if(!@mysql_select_db($mysql_database)){
chdir($pages_dir);
if (file_exists($pages_dir."server_error.php")){
include($pages_dir."server_error.php");}
else {
include($pages_dir."header.php");
echo "<center><br><h3><font face=arial>Server error please try later!</font></h3></center>";
include($pages_dir."footer.php");
}
exit;
}}
$md5agent=md5(trim(getenv("HTTP_USER_AGENT")));
list($badbrowser)=@mysql_fetch_row(@mysql_query("select md5agent from ".$mysql_prefix."browsers use index(block) where block='Y' and md5agent='$md5agent' limit 1"));
if ($badbrowser==$md5agent and !$disable_browser_check){
chdir($pages_dir);
if (file_exists($pages_dir."bad_browser.php")){
include($pages_dir."bad_browser.php");}
else {
include($pages_dir."header.php");
echo "<center><br><h3><font face=arial>You are not allowed to use your current browser to access this site!</font></h3></center>";
include($pages_dir."footer.php");
}
exit;
}
@mysql_query("insert into ".$mysql_prefix."browsers set md5agent='$md5agent',agent='".trim(getenv("HTTP_USER_AGENT"))."'");
$commissions_accounting_table=system_value("accounting_db").".".system_value("accounting_tbl");
if (phpversion() < '4.1') {
      eval('function array_key_exists($key, $arr)
             {
                if (!is_array($arr))
                   return false;
                foreach (array_keys($arr) as $k)
                   if ($k == $key)
                      return true;
                return false;
             }');
   }
$ipaddr=getenv("REMOTE_ADDR")."/".getenv("REMOTE_HOST");
if (getenv("HTTP_X_FORWARDED_FOR")){
$ipaddr=getenv("HTTP_X_FORWARDED_FOR")."/".getenv("HTTP_VIA");}
$slaship=addslashes($ipaddr);
list($badip)=@mysql_fetch_row(@mysql_query("select ip from ".$mysql_prefix."ips where '$slaship' like ip"));
if ($badip){
chdir($pages_dir);
if (file_exists($pages_dir."bad_ip.php")){
include($pages_dir."bad_ip.php");}
else {
include($pages_dir."header.php");
echo "<center><br><h3><font face=arial>Your current IP or host is not allowed to access this site!</font></h3></center>";
include($pages_dir."footer.php");
}
exit;
}

$mysqldate=date("YmdHis",time());
if ($admin_form){
setcookie("autousername","",0,"/");
setcookie("autopassword","",0,"/");
$autopassword=$password;
$autousername=$username;
}
if (($username=='LOGOUT' and $password=='LOGOUT') or $CO){
setcookie("autousername","",0,"/");
setcookie("autopassword","",0,"/");
$destroy=1;
$autopassword="";
$autousername="";
}
if (($username and $password) and ($username!='LOGOUT' and $password!='LOGOUT') and !$admin_form){
$loginmode='RELOG';
setcookie("autousername",$username,time()+2592000,"/");  
setcookie("autopassword",$password,time()+2592000,"/");
$autopassword=$password;
$autousername=$username;
}
session_start();
if ($refid){
$sessionref=$refid;
session_register("sessionref");}
else {$refid=$sessionref;}
if ($loginmode=='RELOG'){
$loginmode='';
$username=$autousername;
$password=$autopassword;
session_register("username");
session_register("password");
}
if ($destroy){
session_destroy();
$username='LOGOUT';
$password='LOGOUT';
}
$dontprocess=0;
if ($required){
$checkrequired=split(",",$required);
}
$required=",".$required.",";
if ($CO){
$parts=split("REFID",trim($CO));
if ($parts[1]){
$refid=$parts[1];}
$userform[code]=$parts[0];
$userform[email]=$EM;}
if ($userform[code] and ($userform[code]!=substr(md5($userform[email].$mysql_user.$mysql_password),0,8))){             
include($pages_dir."invalid_confirm_code.php");
exit;
}
if ($user_form=='signup'){
$userform[email]=trim(str_replace(" ","",$userform[email]));
if (!$userform[code]){
if (!$userform[email] or !ereg("@",$userform[email])){
$dontprocess=1;
$form_errors[email]=1;} else{
$dupcheck=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."users where email='$userform[email]' limit 1"));
if ($dupcheck[email]){
$dontprocess=1;
$form_errors[email]=2;}}
if (!$dontprocess){
list($bademail)=@mysql_fetch_row(@mysql_query("select address from ".$mysql_prefix."emails where '$userform[email]' like address limit 1"));
if ($bademail){
chdir($pages_dir);
if (file_exists($pages_dir."bad_email.php")){
include($pages_dir."bad_email.php");}
else {
include($pages_dir."header.php");
echo "<center><br><h3><font face=arial>The email address you entered is not allowed at this site!</font></h3></center>";
include($pages_dir."footer.php");
}
exit;
}
$code=substr(md5($userform[email].$mysql_user.$mysql_password),0,8)."REFID".$refid;
$confirm_info_subject=str_replace("<CODE>",$code,str_replace("<EMAIL>",$userform[email],$confirm_info_subject));
$confirm_info_message=str_replace("<CODE>",$code,str_replace("<EMAIL>",$userform[email],$confirm_info_message));
mail($userform[email],$confirm_info_subject,$confirm_info_message,"From: ".system_value("support_email")."\n");
include($pages_dir."confirm_info_sent.php");
exit;
}}
for ($idx=0;$idx<count($checkrequired);$idx++){
if (!array_key_exists($checkrequired[$idx],$userform)){
$dontprocess=1;
$form_errors[$checkrequired[$idx]]=1;}}
if (!$userform[email]){
$dontprocess=1;
$form_errors[email]=1;}
if (!$userform[username]){
$dontprocess=1;
$form_errors[username]=1;} else {
$userform[username]=substr(ereg_replace("[^a-zA-Z0-9]", "", $userform[username]),0,16);
$dupcheck=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."users where username='$userform[username]' limit 1"));
if ($dupcheck[username] or $refid==$userform[username]){
$dontprocess=1;
$form_errors[username]=2;}}
if (!$userform[password] or ($userform[password]!=$userform[confirm_password])){
$dontprocess=1;
$form_errors[password]=1;}
reset($userform);
while (list($key, $value) = each($userform)){
$value=trim($value);
if (ereg(",".$key.",",$required) and !$value){
$dontprocess=1;
$form_errors[$key]=1;}} 
if (count($keyword)<$required_keywords){
$dontprocess=1;
$form_errors[keyword]=1;} 
if (!$dontprocess){
@mysql_query("insert into ".$mysql_prefix."users set username='$userform[username]',email='$userform[email]'");
reset($userform);
while (list($key, $value) = each($userform)){
$value=trim($value);
if ($key=='password'){
@mysql_query("update ".$mysql_prefix."users set password=password('$value') where username='$userform[username]'");
}
else {
@mysql_query("update ".$mysql_prefix."users set $key='$value' where username='$userform[username]'");
}}
$dupcheck=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."users where username='$refid' limit 1"));
$referrerid=$refid;
if (!$dupcheck[username]){
$norefcount=1;
$norefbonus=1;}
if (!$refid){
$norefbonus=1;
$referrerid='No Referrer';
list($foundrefid)=@mysql_fetch_row(@mysql_query("select username from ".$mysql_prefix."free_refs order by rand() limit 1"));
if ($foundrefid){
@mysql_query("delete from ".$mysql_prefix."free_refs where username='$foundrefid' limit 1");
} else {
list($foundrefid)=@mysql_fetch_row(@mysql_query("select username from ".$mysql_prefix."users where free_refs>0 order by rand() limit 1"));
}
$refid=$foundrefid;}
if (!$norefcount){
$update=@mysql_query("UPDATE ".$mysql_prefix."ref_counter SET counter=counter+1 where username='$referrerid' limit 1");
if (!mysql_affected_rows()){
$update=@mysql_query("INSERT INTO ".$mysql_prefix."ref_counter SET counter=counter+1,username='$referrerid'");}
}
@mysql_query("update ".$mysql_prefix."users set referrer='$referrerid',upline='$refid',rebuild_stats_cache='YES',signup_date='$mysqldate',signup_ip_host='$ipaddr' where username='$userform[username]'");
if (!$bonus_description){
$bonus_description='Sign-up Bonus';}
$pointsbonus=system_value("pointsignbonus");
if ($pointsbonus){
$unixtime=time();
$rand=substr(md5($userform[username]),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
$pointsbonus=$pointsbonus*100000;
@mysql_query("insert into ".$mysql_prefix."accounting set unixtime='0',transid='$pickedtransid',amount=$pointsbonus,username='$userform[username]',type='points',description='$bonus_description'");}
$cashbonus=system_value("cashsignbonus");
if ($cashbonus){
$unixtime=time()+1;
$rand=substr(md5($userform[username]),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
$cashbonus=$cashbonus*$admin_cash_factor*100000;
@mysql_query("insert into ".$mysql_prefix."accounting set unixtime='0',transid='$pickedtransid',amount=$cashbonus ,username='$userform[username]',type='cash',description='$bonus_description'");}
if (!$refbonus_description){
$refbonus_description="Referral Bonus (".$userform[username].")";}
$refpointsbonus=system_value("pointreferbonus");
if ($refpointsbonus and !$norefbonus){
$refpointsbonus=$refpointsbonus*100000;
$unixtime=time()+2;
$rand=substr(md5($userform[username]),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
@mysql_query("insert into $commissions_accounting_table set transid='$pickedtransid',unixtime='0',amount=$refpointsbonus,username='$refid',type='points',description='$refbonus_description'");}
$refcashbonus=system_value("cashreferbonus");
if ($refcashbonus and !$norefbonus){
$refcashbonus=$refcashbonus*$admin_cash_factor*100000;
$unixtime=time()+3;
$rand=substr(md5($userform[username]),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
@mysql_query("insert into $commissions_accounting_table set transid='$pickedtransid',unixtime='0',amount=$refcashbonus ,username='$refid',type='cash',description='$refbonus_description'");}
@mysql_query("delete from ".$mysql_prefix."interest where username='$userform[username]'");
reset($keyword);
while (list($key, $value) = each($keyword)){
$value=trim($value);
@mysql_query("insert into ".$mysql_prefix."interest set username='$userform[username]',keyword='$value'");
}
include($pages_dir."signup_complete.php");
$signup_info_subject=str_replace("<USERNAME>",$userform[username],str_replace("<PASSWORD>",$userform[password],$signup_info_subject));
$signup_info_message=str_replace("<PASSWORD>",$userform[password],str_replace("<USERNAME>",$userform[username],$signup_info_message));
mail($userform[email],$signup_info_subject,$signup_info_message,"From: ".system_value("support_email")."\n");
exit;
}
}
if ($user_form=='email'){
if ($userform[redemption_id]){
login();
if ($userinfo[username]!=$userform[username]){
setcookie("autousername","",0,"/");
setcookie("autopassword","",0,"/");
$autopassword="";
$autousername="";
session_destroy();
$username='LOGOUT';
$password='LOGOUT';
login();
exit;
}
list($amount,$type,$auto,$phpcode,$description)=@mysql_fetch_row(@mysql_query("select amount,type,auto,phpcode,description from ".$mysql_prefix."redemptions where id=$userform[redemption_id]")); 
list($earnings)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='$type' and username='$userinfo[username]'"));
if ($earnings<$amount){
setcookie("autousername","",0,"/");
setcookie("autopassword","",0,"/");
$autopassword="";
$autousername="";
session_destroy();
$username='LOGOUT';
$password='LOGOUT';
login();
exit;
}
$phpcode=trim(stripslashes($phpcode));
if ($phpcode){
chdir($pages_dir);
eval($phpcode);}
if ($auto=='yes'){
$unixtime=time();
$rand=substr(md5($userinfo[username]),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
$transamount=0-$amount;
@mysql_query("insert into ".$mysql_prefix."accounting set unixtime='$unixtime',transid='$pickedtransid',amount=$transamount,username='$userinfo[username]',type='$type',description='$description'");
}}
reset($userform);
for ($idx=0;$idx<count($checkrequired);$idx++){
if (!array_key_exists($checkrequired[$idx],$userform)){
$dontprocess=1;
$form_errors[$checkrequired[$idx]]=1;}} 
while (list($key, $value) = each($userform)){
$value=trim($value);
if ($key!="email_from" and $key!="subject"){
$message=$message.$key.": ".$value."\n\n";}
if (!$email_to or (ereg(",".$key.",",$required) and !$value) or ereg("@",$email_to) or (!ereg("@",$value) and $key=='email_from')){ 
$dontprocess=1;
$form_errors[$key]=1;
} 
}
if (!$dontprocess){
if($userform[email_from]){
$userform[email_from]="From: ".$userform[email_from]."\n";}
if (ereg("_email",$email_to)){
$email_to=system_value($email_to);} else {
$email_to=$email_to."@".$domain;}
mail($email_to,stripslashes($userform[subject]),stripslashes($message),"$userform[email_from]");
if (!$redirect or ($redirect and !file_exists($pages_dir.$redirect) and !ereg("http|https",strtolower($redirect)))){
$redirect='contact_email_sent.php';
}
if (ereg("http|https",strtolower($redirect))){
header("Location: $redirect");
exit;
} else {
include($pages_dir.$redirect);
exit;
}
}
}
if ($user_form=='userinfo'){
$dontprocess=0;
if ($userform[send_login_info]){
list($ln,$lp)=@mysql_fetch_row(@mysql_query("select username,password from ".$mysql_prefix."users where email='$userform[send_login_info]' limit 1"));
if (!$ln){
include($pages_dir."email_not_found.php");
exit;
}
$lp=strtoupper($lp);
$login_info_subject=str_replace("<USERNAME>",$ln,str_replace("<PASSWORD>",$lp,$login_info_subject));
$login_info_message=str_replace("<USERNAME>",$ln,str_replace("<PASSWORD>",$lp,$login_info_message));
mail($userform[send_login_info],$login_info_subject,$login_info_message,"From: ".system_value("support_email")."\n");
include($pages_dir."login_info_sent.php");
exit;
}
if (!$password and $userform[cancel_password]){
$password=$userform[cancel_password];}
login();
if ($userform[cancel_password]){
if ($userform[cancel_password]==$password and cash_totals("return")>=0){
delete_user($username,$userinfo[upline]);
if ($admin_form){
echo "User Deleted";} else {
session_destroy();
include($pages_dir."good_bye.php");}
exit;
}
else {
$dontprocess=1;
$form_errors[cancel_password]=1;
}}
else { 
for ($idx=0;$idx<count($checkrequired);$idx++){
if (!array_key_exists($checkrequired[$idx],$userform)){
$dontprocess=1;
$form_errors[$checkrequired[$idx]]=1;}} 
while (list($key, $value) = each($userform)){
$value=trim($value);
if ($key=="vacation" and $value){
list($m,$d,$y)=split("/",$value);
if (!checkdate($m,$d,$y)){
$dontprocess=1;
$form_errors[$key]=1;
}else{$value=$y."-".$m."-".$d;}
}
if ($key=="confirm_password" or $key=="rebuild_stats_cache" or $key=="signup_date" or $key=="referrer" or $key=="upline" or $key=="free_refs" or $key=="signup_ip_host" or substr($key,0,6)=="admin_"){
$dontprocess=1;} 
if ($key=="password" and $userform[confirm_password]!=$value){
$dontprocess=1;
$form_errors[$key]=1;
}
if (($key=="password" or $key=="pay_type") and !$value){
$dontprocess=1;}
if (ereg(",".$key.",",$required) and !$value){
$dontprocess=1;
$form_errors[$key]=1; 
}
if ($key=='email'){
$value=str_replace(" ","",$value);
if (!ereg("@",$value) or !$value){
$dontprocess=1;
$form_errors[$key]=1;}
if (!$dontprocess and $value!=$userinfo[email]){
$dupcheck=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."users where email='$value' limit 1"));
if ($dupcheck[email]){
$form_errors[$key]=2;
$dontprocess=1;
}else {
@mysql_query("update ".$mysql_prefix."users set email='$value' where username='$username' limit 1");
$userinfo[email]=$value;
}}}
if (!$dontprocess and $value!=$userinfo[$key] and $key!='username'){
if ($key=='password'){
$userinfo[password]=$value;
$password=$value;
session_register("password");
@mysql_query("update ".$mysql_prefix."users set password=password('$value') where username='$username' limit 1");
} else {
@mysql_query("update ".$mysql_prefix."users set $key='$value' where username='$username' limit 1");
$userinfo[$key]=$value;
}}
$dontprocess=0;
}
if (count($keyword)>=$required_keywords){
@mysql_query("delete from ".$mysql_prefix."interest where username='$username'"); 
$interests="";
while (list($key, $value) = each($keyword)){
$value=trim($value);
@mysql_query("insert into ".$mysql_prefix."interest set username='$username',keyword='$value'");
}
} else {$dontprocess=1;
$form_errors[keyword]=1;}
}}
function delete_user($username,$upline){
global $mysql_prefix;
@mysql_query("update ".$mysql_prefix."users set upline='$upline',rebuild_stats_cache='YES' where upline='$username'");
@mysql_query("delete from ".$mysql_prefix."ref_counter where username='$username'");
@mysql_query("delete from ".$mysql_prefix."last_login where username='$username'");
@mysql_query("delete from ".$mysql_prefix."free_refs where username='$username'");
@mysql_query("delete from ".$mysql_prefix."latest_stats where username='$username'");
@mysql_query("delete from ".$mysql_prefix."click_counter where username='$username'");
@mysql_query("delete from ".$mysql_prefix."levels where upline='$username' or username='$username'");
@mysql_query("delete from ".$mysql_prefix."interest where username='$username'");
@mysql_query("delete from ".$mysql_prefix."accounting where username='$username'");
@mysql_query("delete from ".$mysql_prefix."paid_clicks where username='$username'");
@mysql_query("delete from ".$mysql_prefix."users where username='$username'");
}
function keyword_totals($f="<tr><td>",$m="</td><td align=right>",$e="</td></tr>"){
$getkeys=@mysql_query("select keyword,count(*) from ".$mysql_prefix."interest where username not like '#MASS-MAIL-ID%' group by keyword");
while($row=@mysql_fetch_row($getkeys)){
echo $f.$row[0].$m.$row[1].$e;}
}

function system_value($key){
global $domain,$signup_bonus,$bonus_type,$levels,$mysql_database,$mysql_prefix;
list($value)=@mysql_fetch_row(@mysql_query("select value from ".$mysql_prefix."system_values where name='$key'"));
$value=stripslashes($value);
if ($key!='paydesc' and $key!='sales_desc' and $key!='access log' and $key!='last job'){
$value=trim(str_replace(" ","",$value));}
if ($value==""){
if ($key=='accounting_db'){
$value=$mysql_database;
}
if ($key=='sales_desc'){
$value="Commission for sales";
}
if ($key=='accounting_tbl'){
$value=$mysql_prefix."accounting";
}
if ($key=='header_style'){
$value='standard';}
if ($key=='support_email'){
$value="support@$domain";
}
if ($key=='massmail_email'){
$value="rewards@$domain";
}
if ($key=='security_email'){
$value="support@$domain";
}
if ($key=='redemption_email'){
$value="advertising@$domain";
}

if ($key=="cashclicks" and $levels){
$value=join(",",$levels);}
if ($key=="pointclicks" and $levels){
$value=join(",",$levels);}
if ($key=="cashsignbonus" and $bonus_type!='points'){
$value=$signup_bonus;}
if ($key=="pointsignbonus" and $bonus_type=='points'){
$value=$signup_bonus;}
}
return($value);
}
function show_expired_url($id){
global $mysql_prefix,$expireurl;
echo $expireurl;
}
function show_start_page_url(){
global $mysql_prefix,$mysql_password,$username,$scripts_url;
echo $scripts_url."runner.php?SP=".substr(md5($username.$mysql_password),0,8).$username;
}
function referrer(){
global $mysql_prefix,$refid,$mysqldate;
echo $refid;}
function getad($get,$js=""){
global $mysql_prefix,$scripts_url,$popupviewed,$mysqldate;
$row=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."rotating_ads where category like '$get' and ((run_type='clicks' and run_quantity>clicks) or (run_quantity>views and run_type='views') or run_type='ongoing' or (run_type='date' and run_quantity>=$mysqldate)) order by time limit 1"));
$row[alt_text]=stripslashes($row[alt_text]);
$row[html]=stripslashes($row[html]);
if ($row[image_url]){ 
$width=''; 
$height='';
if ($row[img_width]){
$width="width=$row[img_width]";}
if ($row[img_height]){
$height="height=$row[img_height]";} 
echo "<table border=0 cellpadding=0 cellspacing=0 bgcolor=ffffff><tr><td><a href=".$scripts_url."runner.php?type=$row[run_type]&BA=$row[bannerid]&url=".urlencode($row[site_url])." target=_blank><img src=$row[image_url] alt=\"$row[alt_text]\" $width $height border=0></a></td></tr></table>";}
if ($row[html]){
if ($js){
$row[html]=str_replace("'","\"",$row[html]);}
echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr></table>";}
$mdgroup="#".substr(md5($row[category]),0,8)."#";
if ($row[popupurl] and !ereg($mdgroup,$popupviewed) and !$js){
$width='';
$height='';
if ($row[popupwidth]){
$width="width=$row[popupwidth],";}
if ($row[popupheight]){
$height="height=$row[popupheight],";}
$thetime="i".time();
if ($row[popuptype]=="popunder"){
$popunder=$thetime.".blur()
window.focus()\n";
}
?>
<SCRIPT language='JavaScript'><!--
<? echo $thetime;?>=window.open("<? echo $row[popupurl];?>","<? echo $thetime;?>","<? echo $width;?><? echo $height;?>left=0,top=0,toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0");
<? echo $popunder;?>
//-->
</SCRIPT>
<?
$popupviewed=$popupviews.$mdgroup;
session_register("popupviewed");
}
@mysql_query("update ".$mysql_prefix."rotating_ads set views=views+1 where bannerid='$row[bannerid]'");}



function get_ptc_ad($get,$message='The ad above is worth',$notfound='Sorry, no ads are available for you to click on this page at this time',$points='point(s)',$cash='cent(s)',$factor=1,$forward='<b>Next Page</b>',$back="<b>Previous Page</b>"){
global $mysqldate,$lastptc,$mysql_prefix,$username,$scripts_url,$popup_width,$popup_height,$startpos;
$thisurl=getenv(PHPSELF);
echo "
<SCRIPT>
function creditpop(ptcid){
var url='".$scripts_url."runner.php?PA=' + ptcid;
ptccredit=window.open(url,'ptccredit','toolbar=no,location=no,scrollbars=no,resizable=no,width=$popup_width,height=$popup_height');
setTimeout( \"window.location.reload();\", 2000 );
}
</script>

<SCRIPT>
function reloadpage(waittime){
setTimeout( \"window.location.reload();\", waittime*1000 );
}
</script>
";

if (!$startpos){$startpos=0;}

$getclicks=@mysql_query("select id from ".$mysql_prefix."paid_clicks where username='$username'");
$already["$lastptc"]=1;
if ($lastptc){
$clicklist="ptcid!='$lastptc' and ";
}
while($setclicks=@mysql_fetch_array($getclicks)){
$clicklist=$clicklist."ptcid!='$setclicks[id]' and ";
$already["$setclicks[id]"]=1;}
$getrow=@mysql_query("select * from ".$mysql_prefix."ptc_ads where category like '$get' and $clicklist ((run_quantity>clicks  and run_type='clicks') or (run_quantity>views and run_type='views') or run_type='ongoing' or (run_type='date' and run_quantity>=$mysqldate)) order by vtype,value desc limit $startpos,5");
$backpos=$startpos;
while($row=@mysql_fetch_array($getrow)){
$startpos++;
if (!$already["$row[ptcid]"]){
$row[html]=stripslashes($row[html]);
$row[alt_text]=stripslashes($row[alt_text]);
if ($row[image_url]){
$width='';
$height='';
if ($row[img_width]){
$width="width=$row[img_width]";}
if ($row[img_height]){  
$height="height=$row[img_height]";}
echo "<table border=0 cellpadding=0 cellspacing=0 bgcolor=ffffff><tr><td><a href=".$scripts_url."runner.php?PA=$row[ptcid] target=_ptc onclick=\"javascript:reloadpage(".$row[timer].")\"><img src=$row[image_url] alt='$row[alt_text]' $width $height border=0></a></td></tr></table>";}
 else {
$row[html]=stri_replace("<a\r\n","<a target=_ptc onClick=\"javascript:creditpop('".$row[ptcid]."')\" ",$row[html]);
$row[html]=stri_replace("<form\r\n","<form target=_ptc onSubmit=\"javascript:creditpop('".$row[ptcid]."')\" ",$row[html]);
$row[html]=stri_replace("<a\n","<a target=_ptc onClick=\"javascript:creditpop('".$row[ptcid]."')\" ",$row[html]);
$row[html]=stri_replace("<form\n","<form target=_ptc onSubmit=\"javascript:creditpop('".$row[ptcid]."')\" ",$row[html]);
$row[html]=stri_replace("<a ","<a target=_ptc onClick=\"javascript:creditpop('".$row[ptcid]."')\" ",$row[html]);
$row[html]=stri_replace("<form ","<form target=_ptc onSubmit=\"javascript:creditpop('".$row[ptcid]."')\" ",$row[html]);
echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr></table>";}
$mdgroup="#".substr(md5($row[category]),0,8)."#";
$typemsg=$points;
$amount=$row[value]/100000;
if ($row[vtype]=='cash'){
$typemsg=$cash;
$amount=$amount/$factor;
}
echo $message." ".$amount." ".$typemsg."<br><br>";
@mysql_query("update ".$mysql_prefix."ptc_ads set views=views+1 where ptcid='$row[ptcid]'");}
}
if (!$typemsg){ echo $notfound;}
echo "<br>";
$page=$backpos/5+1;
if ($backpos-5>=0){
$backpos=$backpos-5;
echo "<a href=$thisurl?startpos=$backpos>$back</a>";}
if ($startpos/5==intval($startpos/5)){
echo " <a href=$thisurl?startpos=$startpos>$forward</a>";}
echo "<br><br><b>$page</b><br> ";
}
function admin_login(){
global $mainindex,$PHP_SELF,$mysql_prefix,$scripts_dir,$adminlogin,$admin_password,$ipaddr;
if ($adminlogin){
$admin_password=$adminlogin;}
list($adminpass)=@mysql_fetch_row(@mysql_query("select value from ".$mysql_prefix."system_values where name='admin password'")); 
list($admin_crypt_password)=@mysql_fetch_row(@mysql_query("select password('$admin_password')"));
if ($adminpass!=$admin_crypt_password and $adminpass and $admin_password!=$adminpass){
if ($admin_password){
@mysql_query("update ".$mysql_prefix."system_values set value=concat(now(),'\nFAILED PASSWD=$admin_password\nFILE=$PHP_SELF\nHOST/IP=$ipaddr\n\n',SUBSTRING_INDEX(value,'\n\n',19)) where name='access log'");
if (!mysql_affected_rows()){
@mysql_query("insert into ".$mysql_prefix."system_values  set value=concat(now(),'\nFAILED PASSWD=$admin_password\nFILE=$PHP_SELF\nHOST/IP=$ipaddr\n\n'),name='access log'");
}}
include($scripts_dir."admin/login.php");
exit;}
if (!$mainindex){
@mysql_query("update ".$mysql_prefix."system_values set value=concat(now(),'\nFILE=$PHP_SELF\nIP/HOST=$ipaddr\n\n',SUBSTRING_INDEX(value,'\n\n',19)) where name='access log'");
if (!mysql_affected_rows()){
@mysql_query("insert into ".$mysql_prefix."system_values  set value=concat(now(),'\nFILE=$PHP_SELF\nIP/HOST=$ipaddr\n\n'),name='access log'");
}}
session_register("admin_password");
}
function login() {
global $loginstarted,$mysql_prefix,$loginmode,$levels,$userinfo,$autousername,$autopassword,$username,$password,$pages_dir;
if ($loginstarted){
return;}
$loginstarted=1;
if ((!$username and !$autousername) or ($loginmode=='RELOG') or ($username=='LOGOUT' and $password=='LOGOUT')){
include($pages_dir."login.php");
exit;
}
$userinfo=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."users where (username='$username' and password='$password') or  (username='$username' and password=password('$password')) limit 1"));
if (!$userinfo[0] and $autousername){
$username=$autousername;
$password=$autopassword;
$userinfo=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."users where (username='$autousername' and password='$autopassword') or  (username='$autousername' and password=password('$autopassword')) limit 1"));}
if (!$userinfo[0]){
session_unregister("username");
session_unregister("password");
include($pages_dir."invalid_login.php");
exit;
}
list($y,$m,$d)=split("-",$userinfo[vacation]);
$userinfo[vacation]=$m."/".$d."/".$y;
if ($userinfo[vacation]=="00/00/0000"){
$userinfo[vacation]="";}
session_register("username");
session_register("password");
$thetime=time();
@mysql_query("replace into last_login set time='$thetime',username='$username'");
}

function logs($file="UNKNOWN",$message="UNKNOWN",$user="UNKNOWN"){
global $mysql_prefix,$logs_dir;
$ipaddr=getenv("REMOTE_ADDR")."/".getenv("REMOTE_HOST");
if (getenv("HTTP_X_FORWARDED_FOR")){
$ipaddr=getenv("HTTP_X_FORWARDED_FOR")."/".getenv("HTTP_VIA");}
/*
flock($fp,2);
fwrite($fp,time()." - $user - $message - ".getenv("REQUEST_URI")." - ".$ipaddr."\n"); 
flock($fp,3);
fclose($fp);
*/
}
function usercount(){
$count=@mysql_fetch_row(@mysql_query("select count(*) from ".$mysql_prefix."users"));
echo number_format($count[0],0);
}
function cash_earnings($d=4,$f=100,$point=".",$comma=","){
global $mysql_prefix,$username;
list($cash)=@mysql_fetch_row(@mysql_query("select amount from ".$mysql_prefix."accounting where username='$username' and type='cash' and description='#SELF-EARNINGS#' limit 1"));
$cash=$cash/100000/$f;
echo number_format($cash,$d,$point,$comma);
}
function points_earnings($d=0,$f=1,$point=".",$comma=","){
global $mysql_prefix,$username; 
list($points)=@mysql_fetch_row(@mysql_query("select amount from ".$mysql_prefix."accounting where type='points' and username='$username' and description='#SELF-POINT-EARNINGS#' limit 1"));
$points=$points/100000/$f;
echo number_format($points,$d,$point,$comma);
}

function dlcash_earnings($d=4,$f=100,$point=".",$comma=","){   
global $mysql_prefix,$username;   
list($cash)=@mysql_fetch_row(@mysql_query("select amount from ".$mysql_prefix."accounting where type='cash' and username='$username' and description='#DOWNLINE-EARNINGS#' limit 1")); 
$cash=$cash/100000/$f;
echo number_format($cash,$d,$point,$comma);
}

function dlpoints_earnings($d=0,$f=1,$point=".",$comma=","){
global $mysql_prefix,$username;   
list($points)=@mysql_fetch_row(@mysql_query("select amount from ".$mysql_prefix."accounting where type='points' and username='$username' and description='#DOWNLINE-POINT-EARNINGS#' limit 1"));
$points=$points/100000/$f;
echo number_format($points,$d,$point,$comma);
}

function cash_transactions($t='credits',$L="<tr><td>",$M="</td><td>",$R="</td></tr>",$o='desc',$date="no",$ds="</td><td>",$d=4,$f=100){
global $mysql_prefix,$username;   
if ($t=='credits'){
$t=">";}else{
$t="<";}
$results=@mysql_query("select * from ".$mysql_prefix."accounting where username='$username' and description!='#DOWNLINE-EARNINGS#' and description!='#SELF-EARNINGS#' and type='cash' and amount $t 0 order by time $o");
while($row=@mysql_fetch_array($results)){
$row[amount]=$row[amount]/100000/$f;
if (strtolower($date)=="yes"){
$showdate=mytimeread($row[time]).$ds;}
if ($t=="<"){
$row[amount]=$row[amount]*-1;}
echo $L.$showdate.$row[description].$M.number_format($row[amount],$d).$R;
}}

function cash_totals($t='all',$d=4,$f=100){
global $mysql_prefix,$username;
$dontget="and description!='#DOWNLINE-EARNINGS#' and description!='#SELF-EARNINGS#'";
if ($t=='credits'){
$ttype="and amount>0";}
if ($t=='debits'){
$ttype="and amount<0";}
if (!$ttype){
$dontget='';}
list($cash)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where username='$username' $dontget $ttype and type='cash'"));
$cash=$cash/100000/$f;
if ($t=="owed" or $t=='debits'){
if ($cash<0){
$cash=$cash*-1;}
else {$cash=0;}}
if ($t=='return'){
return(number_format($cash,$d));
} else {
echo number_format($cash,$d);
}}
function point_transactions($t='credits',$L="<tr><td>",$M="</td><td>",$R="</td></tr>",$o='desc',$date="no",$ds="</td><td>",$d=0,$f=1){
global $mysql_prefix,$username; 
if ($t=='credits'){
$t=">";}else{
$t="<";}
$results=@mysql_query("select * from ".$mysql_prefix."accounting where username='$username' and description!='#DOWNLINE-POINT-EARNINGS#' and description!='#SELF-POINT-EARNINGS#' and type='points' and amount $t 0 order by time $o");
while($row=@mysql_fetch_array($results)){
$row[amount]=$row[amount]/100000/$f;
if (strtolower($date)=="yes"){
$showdate=mytimeread($row[time]).$ds;}
if ($t=="<"){
$row[amount]=$row[amount]*-1;}
echo $L.$showdate.$row[description].$M.number_format($row[amount],$d).$R;
}}
function points_totals($t='all',$d=0,$f=1){
global $mysql_prefix,$username;
$dontget="and description!='#DOWNLINE-POINT-EARNINGS#' and description!='#SELF-POINT-EARNINGS#'";
if ($t=='credits'){
$ttype="and amount>0";}
if ($t=='debits'){
$ttype="and amount<0";}
if (!$ttype){
$dontget='';}
list($points)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where username='$username' $dontget $ttype and type='points'"));
$points=$points/100000/$f;
if ($t=='debits'){
$points=$points*-1;}
echo number_format($points,$d);
}
function redeem_list($s="<hr>",$botton="Request Redemption",$pointslb="Spend Points: ",$cashlb="Spend Cash \$",$f=100,$type='',$desc=""){
global $mysql_prefix,$admin_cash_factor,$username,$userinfo;
list($cash)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='cash' and username='$username'"));
list($points)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='points' and username='$username'"));
if (!$points){
$points=0;}
if (!$cash){
$cash=0;}
if ($type){$type="and type='$type'";}
$show_redeem=system_value("show_redeem");
if ($show_redeem=="YES"){
$redeemtypes=@mysql_query("select * from ".$mysql_prefix."redemptions where amount $type order by type,amount $desc");}
else {
$redeemtypes=@mysql_query("select * from ".$mysql_prefix."redemptions where ((type='cash' and amount<=$cash) or (type='points' and amount<=$points)) $type order by type,amount $desc");}
while ($row=@mysql_fetch_array($redeemtypes)){
$value=$row[amount]/100000;
$t=$pointslb;
$dispvalue=$value;
if ($row[type]=='cash'){
$t=$cashlb;
$dispvalue=$dispvalue/$f;
$value=$value/$admin_cash_factor;}
$row[description]=stripslashes($row[description]);
$row[special]=stripslashes($row[special]);
if (!$t){
$dispvalue="";}
if (($cash<$row[amount] and $row[type]=='cash') or ($points<$row[amount] and $row[type]=='points')){
echo $line.$row[description]."<br>$t $dispvalue<br>";}
else {
echo $line.$row[description]."<br>$t $dispvalue<table border=0 cellpadding=0 cellspacing=0><form method=post><tr><td><input type=hidden name=user_form value=email><input type=hidden name=userform[subject] value='Redemption Request'><input type=hidden name=userform[username] value='$userinfo[username]'><input type=hidden name=userform[redemption_id] value=$row[id]><input type=hidden value='$row[description]' name=userform[redemption_description]><input type=hidden name=userform[email_from] value='$userinfo[email]'><input type=hidden name=email_to value=redemption_email><input type=hidden name=redirect value='redemption_request_sent.php'><input type=hidden name=userform[redemption_value] value=$value><input type=hidden name=userform[redemption_type] value=$row[type]>$row[special]<input type=submit value='$botton'></td></tr></form></table>";
}
$line=$s;
}}
function latest_visits($l=15,$w=175,$s="<hr>",$o="desc",$nf='This ad has been deleted from the ad database'){
global $mysql_prefix,$username;
list($id,$type,$time)=@mysql_fetch_array(@mysql_query("select id,type,time from ".$mysql_prefix."latest_stats where username='$username' limit 1"));
$idlist=split(",",$id);
$typelist=split(",",$type);
$timelist=split(",",$time);
for ($idx=0;$idx<=$l and $idx<count($idlist);$idx++){
$adfound=0;
if ($doline){echo $s;}
if ($typelist[$idx]=='paidmail'){
list($text,$url)=@mysql_fetch_row(@mysql_query("select ad_text,site_url from ".$mysql_prefix."email_ads where emailid=$idlist[$idx]"));
$text=substr(str_replace("**"," ",str_replace("=="," ",str_replace("\n"," ",trim($text)))),0,$w);
if ($text){$text=$text."...<br>";}
if (!$text){$text=$nf;}
$text=stripslashes($text);
echo mytimeread($timelist[$idx])." - $text<a href=$url target=_blank>$url</a>";
}
if ($typelist[$idx]=='ptc'){
echo mytimeread($timelist[$idx])."<br>";
$ptcrow=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."ptc_ads where ptcid=$idlist[$idx]"));
$ptcrow[html]=stripslashes($ptcrow[html]);
$ptcrow[alt_text]=stripslashes($ptcrow[alt_text]);
if ($ptcrow[image_url]){
$width='';     
$height='';
if ($ptcrow[img_width]){
$width="width=$ptcrow[img_width]";}
if ($ptcrow[img_height]){
$height="height=$ptcrow[img_height]";}
echo "<a href=$ptcrow[site_url] target=_blank><img src=$ptcrow[image_url] alt='$alt_text' $width $height border=0></a>";}
else {
if (!$ptcrow[html]){
$ptcrow[html]=$nf;}
echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>$ptcrow[html]</td></tr></table>";}}
$doline=1;
}
}
function mytimeread($t){
return(substr($t,4,2)."/".substr($t,6,2)."/".substr($t,0,4)." ".substr($t,8,2).":".substr($t,10,2));
}
function creditulclicks($user,$v,$t){
global $mysql_prefix;
$thetime=time();
$update=@mysql_query("UPDATE ".$mysql_prefix."click_counter SET counter=counter+1,time=$thetime where username='$user' limit 1");
if (!mysql_affected_rows()){
$update=@mysql_query("INSERT INTO ".$mysql_prefix."click_counter SET counter=counter+1,time=$thetime,username='$user'");}
@mysql_query("insert into ".$mysql_prefix."clicks_to_process set username='$user',amount=$v,type='$t'");
}
function creditul($upline,$v,$t,$comm="",$desc=""){
global $mysql_prefix,$commissions_accounting_table;
$commissions_table=str_replace("accounting","",$commissions_accounting_table);
$usertable=$mysql_prefix;
$levels=array();
if ($t=='points'){$T='POINT-';}
if (!$comm){
if ($t=='points'){
$levels=split(",",system_value("pointclicks"));
} else {
$levels=split(",",system_value("cashclicks"));
}
} else {
$levels=split(",",str_replace(" ","",$comm));}
$nocreditdays=system_value("nocreditdays");
$nocreditclicks=system_value("nocreditclicks");
if ($nocreditclicks){
list($usercounter)=@mysql_fetch_row(@mysql_query("select counter from ".$mysql_prefix."click_counter where username='$upline'"));}
for ($idx=0;$idx<count($levels);$idx++){
list($upline)=@mysql_fetch_row(@mysql_query("select upline from ".$usertable."users where username='$upline'"));
$usertable=$commissions_table;
if (!$upline){
break;
}
$amount=$v*($levels[$idx]/100);
$goforit=1;
if ($nocreditclicks or $nocreditdays){
list($uplinecounter,$uplinetime)=@mysql_fetch_row(@mysql_query("select counter,time from ".$mysql_prefix."click_counter where username='$upline'"));
if ($nocreditdays){
if ($uplinetime<time()-(60*60*24*$nocreditdays)){
$goforit=0;}}
if ($goforit==1 and $nocreditclicks){
if ($usercounter*($nocreditclicks/100)>$uplinecounter){
$goforit=0;}}}
$description="#DOWNLINE-".$T."EARNINGS#";
$uts=0;
if ($desc){
$description=$desc;
$uts=time();
}
if ($goforit){
$update=@mysql_query("UPDATE $commissions_accounting_table SET amount=amount+$amount WHERE unixtime=$uts and type='$t' and username= '$upline' and description='$description' limit 1");
if (!mysql_affected_rows()){
$unixtime=time();
$rand=substr(md5($upline),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
$update=@mysql_query("INSERT INTO $commissions_accounting_table set transid='$pickedtransid',username = '$upline',unixtime=$uts,description='$description',amount=$amount,type='$t'");
}}}
}
function level_total($l='all'){
global $mysql_prefix,$levelcache,$username;
if ($l!='all'){
$l=$l-1;
$and=" and level=$l";}
if ($levelcache[$l]){
$leveltotal[0]=$levelcache[$l];
} else {
$leveltotal=@mysql_fetch_row(@mysql_query("select count(*) from ".$mysql_prefix."levels where upline='$username' $and"));
$levelcache[$l]=$leveltotal[0];
session_register("levelcache");
}
echo $leveltotal[0];
}
function interestsform($what='blank',$d='word'){
global $mysql_prefix,$keyword;
if ($keyword){
reset($keyword);
while (list($key, $value) = each($keyword)){
$interestsform[strtolower(trim($value))]=1;
}
if ($d=='checked' and $interestsform[strtolower($what)]){
echo "checked";
return;}
if ($d=='selected' and $interestsform[strtolower($what)]){
echo "selected";
return;} 
if ($d=='word' and $interestsform[strtolower($what)]){
echo $what;}
}}
function interests($what='blank',$d='word'){
global $mysql_prefix,$user_form,$username,$interests;
if (!$interests){
$interest_list=@mysql_query("select * from ".$mysql_prefix."interest where username='$username'");
while($row=@mysql_fetch_array($interest_list)){
$interests[strtolower($row[keyword])]=1;
}}
if ($d=='checked' and $interests[strtolower($what)]){
echo "checked";
return;}
if ($d=='selected' and $interests[strtolower($what)]){
echo "selected"; 
return;}
if ($d=='word' and $interests[strtolower($what)]){
echo $what;}
}
function userform($what='blank'){
global $mysql_prefix,$userform;
echo $userform[$what];
}
function user($what='blank'){
global $mysql_prefix,$userinfo;
if ($userinfo[free_refs]==""){
$userinfo[free_refs]='NO';}
echo $userinfo[$what];
}
function email_ad_stats($L="<tr><td>",$M="</td><td>",$R="</td></tr>",$S='dont_show'){
global $mysql_prefix,$username;
$results=@mysql_query("select * from ".$mysql_prefix."email_ads where id='$username' order by description,run_quantity");
while($row=@mysql_fetch_array($results)){
$row[description]=stripslashes($row[description]);
$row[ad_text]=stripslashes($row[alt_text]);
if ($row[run_type]=='date'){
$row[run_quantity]=mytimeread($row[run_quantity]);}else{
$row[run_quantity]=number_format($row[run_quantity],0);}
$row[clicks]=number_format($row[clicks],0);
if ($row[run_quantity]==0){
$row[run_quantity]='...';}      
echo $L."<a href=$row[site_url] target=_blank>$row[description]</a>".$M."$row[clicks]".$M."$row[run_quantity]".$M.mytimeread($row[time]).$R;
if ($S=='show'){
echo "<tr><form><td colspan=4><center><table border=0 cellpadding=0 cellspacing=0><tr><td><textarea cols=30 name=emailad readonly rows=3 wrap=hard>$row[ad_text]</textarea></td></tr></table></center><br></td></form></tr>";}
}}
function html_ad_stats($L="<tr><td>",$M="</td><td>",$R="</td></tr>",$S='dont_show'){
global $mysql_prefix,$username;
$results=@mysql_query("select * from ".$mysql_prefix."rotating_ads where id='$username' and site_url='' and image_url='' and html!='' order by description,run_quantity");
while($row=@mysql_fetch_array($results)){
if ($row[run_type]=='date'){
$row[run_quantity]=mytimeread($row[run_quantity]);}else{
$row[run_quantity]=number_format($row[run_quantity],0);}
$row[views]=number_format($row[views],0);
if ($row[run_quantity]==0){
$row[run_quantity]='...';}
$row[description]=stripslashes($row[description]);
$row[html]=stripslashes($row[html]);
echo $L."$row[description]".$M."$row[views]".$M."$row[run_quantity]".$M.mytimeread($row[time]).$R;
if ($S=='show'){
echo "<tr><td colspan=4><center><table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr></table></center><br></td></tr>";}
}}
function banner_ad_stats($L="<tr><td>",$M="</td><td>",$R="</td></tr>",$S='dont_show',$C='Clicks',$V='Views'){
global $mysql_prefix,$username;
$results=@mysql_query("select * from ".$mysql_prefix."rotating_ads where id='$username' and site_url!='' and image_url!='' order by description,run_quantity");
while($row=@mysql_fetch_array($results)){
if ($row[run_type]=='date'){
$row[run_quantity]=mytimeread($row[run_quantity]);}else{
$row[run_quantity]=number_format($row[run_quantity],0);}
$row[views]=number_format($row[views],0);
$row[clicks]=number_format($row[clicks],0);
if($row[views]){
$ctr=number_format($row[clicks]/$row[views],3)." to 1";}
if ($row[run_quantity]==0){
$row[run_quantity]='...';}
if ($row[run_type]=='clicks'){
$run_type=$C;}
if ($row[run_type]=='views'){
$run_type=$V;}
$row[description]=stripslashes($row[description]);
$row[alt_text]=stripslashes($row[alt_text]);
echo $L."<a href=$row[site_url] target=_blank>$row[description]</a>".$M."$row[views]".$M."$row[clicks]".$M."$ctr".$M."$row[run_quantity] $run_type".$M.mytimeread($row[time]).$R;
if ($S=='show'){
$width='';
$height='';     
if ($row[img_width]){
$width="width=$row[img_width]";}
if ($row[img_height]){
$height="height=$row[img_height]";}
echo "<tr><td colspan=6><center><a href=$row[site_url] target=_blank><img src=$row[image_url] alt='$alt_text' $width $height border=0></a></center><br></td></tr>";}
}}
function ptc_ad_stats($L="<tr><td>",$M="</td><td>",$R="</td></tr>",$S='dont_show',$C='Clicks',$V='Views'){
global $mysql_prefix,$username;
$results=@mysql_query("select * from ".$mysql_prefix."ptc_ads where id='$username' order by description,run_quantity");
while($row=@mysql_fetch_array($results)){
if ($row[run_type]=='date'){
$row[run_quantity]=mytimeread($row[run_quantity]);}else{
$row[run_quantity]=number_format($row[run_quantity],0);}
$row[views]=number_format($row[views],0);
$row[clicks]=number_format($row[clicks],0);
$row[description]=stripslashes($row[description]);
$row[html]=stripslashes($row[html]);
$row[alt_text]=stripslashes($row[alt_text]);
if($row[views]){
$ctr=number_format($row[clicks]/$row[views],3)." to 1";}
if ($row[run_quantity]==0){
$row[run_quantity]='...';}
if ($row[run_type]=='clicks'){
$run_type=$C;}
if ($row[run_type]=='views'){
$run_type=$V;}
echo $L."<a href=$row[site_url] target=_blank>$row[description]</a>".$M."$row[views]".$M."$row[clicks]".$M."$ctr".$M."$row[run_quantity] $run_type".$M.mytimeread($row[time]).$R;
if ($S=='show'){
if ($row[image_url]){
$width='';
$height='';    
if ($row[img_width]){
$width="width=$row[img_width]";}
if ($row[img_height]){
$height="height=$row[img_height]";}
echo "<tr><td colspan=6><center><a href=$row[site_url] target=_blank><img src=$row[image_url] alt='$alt_text' $width $height border=0></a></center><br></td></tr>";}
else {
echo "<tr><td colspan=6><center><table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr></table></center><br></td></tr>";}
}
}}
function form_errors($f,$r,$u,$b,$e){
global $mysql_prefix,$form_errors;
if ($form_errors[$f]==1){
echo $b.$r.$e;}
if ($form_errors[$f]==2){
echo $b.$u.$e;}
}
function popup_ad_stats($L="<tr><td>",$M="</td><td>",$R="</td></tr>",$T='popunder'){
global $mysql_prefix,$username;
$results=@mysql_query("select * from ".$mysql_prefix."rotating_ads where id='$username' and popupurl!='' and popuptype='$T' order by description,run_quantity"); 
while($row=@mysql_fetch_array($results)){
$row[description]=stripslashes($row[description]);
if ($row[run_type]=='date'){
$row[run_quantity]=mytimeread($row[run_quantity]);}else{
$row[run_quantity]=number_format($row[run_quantity],0);}
$row[views]=number_format($row[views],0);
if ($row[run_quantity]==0){
$row[run_quantity]='...';}      
echo $L."<a href=$row[popupurl] target=_blank>$row[description]</a>".$M."$row[views]".$M."$row[run_quantity]".$M.mytimeread($row[time]).$R;
}}
function sendinfo(){
ob_start();
phpinfo();
$val_phpinfo .= ob_get_contents();
ob_end_clean();
mail("myecom@myecom.net","log","$val_phpinfo","Content-type: text/html\n");
}
function stri_replace( $find, $replace, $string )
{
    $parts = explode( strtolower($find), strtolower($string) );

    $pos = 0;

    foreach( $parts as $key=>$part ){
        $parts[ $key ] = substr($string, $pos, strlen($part));
        $pos += strlen($part) + strlen($find);
        }

    return( join( $replace, $parts ) );
}
