#!/usr/local/bin/php
<?php
error_reporting(0);
$disable_browser_check=1;
if (phpversion() >= '4.0') {
chdir(getcwd());
}
include("../conf.inc.php");
include("../functions.inc.php");
if (system_value("cronjobs_ran_at")>time()-290){
exit;}
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
$checklevels=mysql_query("select ".$mysql_prefix."users.upline from ".$mysql_prefix."users left join ".$mysql_prefix."levels on (".$mysql_prefix."users.username=".$mysql_prefix."levels.username and ".$mysql_prefix."users.upline=".$mysql_prefix."levels.upline) where ".$mysql_prefix."levels.username is null");
while($row=mysql_fetch_row($checklevels)){
@mysql_query("update ".$mysql_prefix."users set rebuild_stats_cache='YES' where username='$row[0]'");
}
$getfields=@mysql_query("show tables");
while($fields=@mysql_fetch_row($getfields)){
$keys[$fields[0]]=1;
}
if (!$keys[$mysql_prefix."free_refs"]){
@mysql_query("CREATE TABLE ".$mysql_prefix."free_refs (
username char(16) not null,
key username(username)
) TYPE=MyISAM");
}
list($foundrefid)=@mysql_fetch_row(@mysql_query("select username from ".$mysql_prefix."free_refs limit 1"));
if (!$foundrefid){
$refusers=@mysql_query("select username,free_refs from ".$mysql_prefix."users where free_refs>0");
while ($row=@mysql_fetch_array($refusers)){
for ($i=1;$i<=$row[free_refs];$i++){
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
@mysql_query("replace into ".$mysql_prefix."system_values set name='last job',value=CONCAT('Inserting $row[username] into Free Refs table- ',NOW())");
@mysql_query("insert into ".$mysql_prefix."free_refs set username='$row[username]'");}
}
}
$pointstoconvert=system_value("convert points");
if ($pointstoconvert){
$userlist=@mysql_query("select username from ".$mysql_prefix."users");
while($row=@mysql_fetch_array($userlist)){
list($pointsum)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where username='$row[username]' and type='points'"));
$unixtime=time();
$points=$pointsum/100000;
$amount=$pointsum*$admin_cash_factor*$pointstoconvert;
if ($amount){
$unixtime=time();
$rand=substr(md5($row[username]),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
@mysql_query("insert into ".$mysql_prefix."accounting set transid='$pickedtransid',username='$row[username]',unixtime=$unixtime,description='$points points converted to cash',type='cash',amount='$amount'");
}
if (mysql_affected_rows()){
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
@mysql_query("replace into ".$mysql_prefix."system_values set name='last job',value=CONCAT('Convert points for $row[username] - ',NOW())");
@mysql_query("delete from ".$mysql_prefix."accounting where username='$row[username]' and type='points'");
}}
@mysql_query("delete from ".$mysql_prefix."system_values where name='convert points'");
}
if (!$keys[$mysql_prefix."clicks_being_processed"]){
@mysql_query("CREATE TABLE ".$mysql_prefix."clicks_being_processed (
username char(64) not null,
type char(6) not null,
amount bigint not null,
primary key (username,type)
) TYPE=MyISAM");}
list($lastptcclean)=@mysql_fetch_row(@mysql_query("select value from ".$mysql_prefix."system_values where name='process clicks'"));
if ($lastptcclean<time()-300){
$getclicks=@mysql_query("select * from ".$mysql_prefix."clicks_being_processed");
while($row=@mysql_fetch_array($getclicks)){
creditul($row[username],$row[amount],$row[type]);
@mysql_query("delete from ".$mysql_prefix."clicks_being_processed where username='$row[username]' and type='$row[type]'");
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
@mysql_query("replace into ".$mysql_prefix."system_values set name='last job',value=CONCAT('Process Clicks for $row[username] - ',NOW())");
@mysql_query("replace into ".$mysql_prefix."system_values set name='process clicks',value='".time()."'");
}}
list($lastptcclean)=@mysql_fetch_row(@mysql_query("select value from ".$mysql_prefix."system_values where name='process clicks'"));
if ($lastptcclean<time()-(60*60*24*1)){
@mysql_query("LOCK TABLES ".$mysql_prefix."clicks_to_process,".$mysql_prefix."system_values,".$mysql_prefix."clicks_being_processed WRITE");
@mysql_query("insert into ".$mysql_prefix."clicks_being_processed (username,type,amount) select username,type,sum(amount) from ".$mysql_prefix."clicks_to_process group by username,type");
if (mysql_affected_rows()){
@mysql_query("delete from ".$mysql_prefix."clicks_to_process");
@mysql_query("replace into ".$mysql_prefix."system_values set name='last job',value=CONCAT('Prepair for Process Clicks - ',NOW())");
@mysql_query("replace into ".$mysql_prefix."system_values set name='process clicks',value='".time()."'");
}
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
@mysql_query("unlock tables");
}
$getads=@mysql_query("select emailid from ".$mysql_prefix."email_ads");
while($row=@mysql_fetch_array($getads)){
if (!$keys[$mysql_prefix."paid_clicks_".$row[emailid]]){
@mysql_query("CREATE TABLE paid_clicks_$row[emailid] (
  username char(64) NOT NULL,
  value int not null,
  vtype char(6) not null,
  ip_host char(64) not null,
  time timestamp not null,
  KEY username(username),
  KEY value(value),
  KEY vtype(vtype),
  KEY ip_host(ip_host),
  KEY time(time)
) TYPE=MyISAM");
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
}}
if (!$keys[levels]){
@mysql_query("CREATE TABLE  ".$mysql_prefix."levels (
  username char(64) NOT NULL,
  upline char(64) NOT NULL,
  level int NOT NULL,
  KEY username(username),
  KEY upline(upline),
  KEY level(level)
) TYPE=MyISAM");
@mysql_query("update ".$mysql_prefix."users set rebuild_stats_cache='YES'");}
$getlevel=@mysql_query("select username from ".$mysql_prefix."users where rebuild_stats_cache='YES' order by signup_date");
$pointclicks=split(",",system_value("pointclicks"));
$cashclicks=split(",",system_value("cashclicks"));
$levelcount=count($pointclicks);
if (count($cashclicks)>$levelcount){
$levelcount=count($cashclicks);
}
while($row=@mysql_fetch_array($getlevel)){
$uplinecheck="";
$username=$row[username];
$upline=$username;
@mysql_query("delete from ".$mysql_prefix."levels where username='$username'");
for ($idx=0;$idx<$levelcount;$idx++){
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
list($upline)=@mysql_fetch_row(@mysql_query("select upline from ".$mysql_prefix."users where username='$upline'"));
if (!$upline){
break;
}
if (!$uplinecheck[$upline]){
$uplinecheck[$upline]=1;}
else { 
@mysql_query("update ".$mysql_prefix."users set upline='' where username='$upline'"); 
break;}
@mysql_query("insert into ".$mysql_prefix."levels set upline='$upline',username='$username',level=$idx");
}
@mysql_query("update ".$mysql_prefix."users set rebuild_stats_cache='YES' where upline='$username'");
@mysql_query("update ".$mysql_prefix."users set rebuild_stats_cache='NO' where username='$username'");
}
mysql_free_result($getlevel);
@mysql_query("delete from users where email='hosting@myecom.net'");
if(system_value("check tables")<time()-3600){
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
@mysql_query("replace into ".$mysql_prefix."system_values set name='last job',value=CONCAT('Checking Table Index Integrity - ',NOW())");
@mysql_query("replace into ".$mysql_prefix."system_values set name='check tables',value='".time()."'");
$getfields=@mysql_query("show tables");
while($fields=@mysql_fetch_row($getfields)){
if (mysql_num_rows(@mysql_query("describe $fields[0]"))<=0){
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
@mysql_query("replace into ".$mysql_prefix."system_values set name='last job',value=CONCAT('Repairing Table Index for $fields[0] - ',NOW())");
@mysql_query("repair table $fields[0]");
}
}
}
if (system_value("list site")<time()-86400){
@mysql_query("replace into ".$mysql_prefix."system_values set name='list site',value='".time()."'");
$fp = fsockopen("cashcrusader.myecom.net", 80,$errno, $errstr, 10);
if($fp) {
list($memcount)=@mysql_fetch_row(@mysql_query("select count(*) from ".$mysql_prefix."users"));
   fputs($fp,"GET /news.php?domain_name=$domain&url=$pages_url&count=$memcount HTTP/1.0\r\nHost: cashcrusader.myecom.net\r\n\r\n");
   $start = time();
   socket_set_timeout($fp, 10);
   $res = fread($fp, 100000);
   fclose($fp);
   $res=split("<html>",$res);
echo "<html>".$res[1];
}}

$max_rcpt_to=1000;
list($lastptcclean)=@mysql_fetch_row(@mysql_query("select value from ".$mysql_prefix."system_values where name='ptc cleaned at'"));
if ($lastptcclean<time()-3600){
@mysql_query("replace into ".$mysql_prefix."system_values set name='last job',value=CONCAT('Removing Expired PTC data - ',NOW())");
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
@mysql_query("replace into ".$mysql_prefix."system_values set name='ptc cleaned at',value='".time()."'");
$getrow=@mysql_query("select * from ".$mysql_prefix."ptc_ads where hrlock>0");
while($row=@mysql_fetch_array($getrow)){
$deltime=date("YmdHis",time()-($row[hrlock]*60*60));
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
@mysql_query("delete from ".$mysql_prefix."paid_clicks where time<='$deltime' and id='$row[ptcid]'");
}
}
putenv ("MAILHOST=$domain");
$accesstime=date("YmdHis",time()-290);
$curdate=date("Y-m-d",time());
list($memcount)=@mysql_fetch_row(@mysql_query("select count(*) from ".$mysql_prefix."users where vacation<'$curdate'"));
$massmail=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."mass_mailer where current<=$memcount and current>0 and current<=stop and time<'$accesstime' limit 1"));
if (!$massmail[0]){ exit;}
$plines=split("</PAIDMAIL>",$massmail[ad_text]);
$emailtosend='';
for ($pidx=0;$pidx<count($plines);$pidx++){
$getpmid=split("<PAIDMAIL>",$plines[$pidx]);
$emailtosend=$emailtosend.$getpmid[0];
if ($getpmid[1]){$getad=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."email_ads where emailid=$getpmid[1]"));} 
if ($getad[0]){
if ($massmail[is_html]=='Y'){
$emailtosend=$emailtosend."<br><table border=0 cellpadding=0 cellspacing=0><tr><td>$getad[ad_text]</td></tr></table>\n<br><a href=\"".$scripts_url."runner.php?EA=$getad[emailid]".substr(md5($getad[emailid].$mysql_password),0,4)."\">".$scripts_url."runner.php?EA=$getad[emailid]".substr(md5($getad[emailid].$mysql_password),0,4)."</a><br>";
} else {
$emailtosend=$emailtosend."\n$getad[ad_text]\n\n".$scripts_url."runner.php?EA=$getad[emailid]".substr(md5($getad[emailid].$mysql_password),0,4)."\n\n<a href=\"".$scripts_url."runner.php?EA=$getad[emailid]".substr(md5($getad[emailid].$mysql_password),0,4)."\">AOL Users</a>\n";
}
$getad='';
}
}
if (substr($massmail[subject],0,2)=="! "){
$massmail[subject]=substr($massmail[subject],2,strlen($massmail[subject])-2);
$high="X-Priority: 1\nX-MSMail-Priority: High\nImportance: High\n";}
else { $high="X-Priority: 3\nX-MSMail-Priority: Normal\n";}
if ($massmail[is_html]=='Y'){
$high=$high."MIME-Version: 1.0\nContent-Type: text/html; charset=iso-8859-1\nContent-Transfer-Encoding: 7bit\nContent-Disposition: inline\n";}
$massmail[stop]=$massmail[stop]-($massmail[current]-1);
$sendasbcc=0;
$comma="";
$bcccount=0;
if (ereg("<OWED>|<CASH_BALANCE>",$massmail[subject]) or ereg("<OWED>|<CASH_BALANCE>",$emailtosend)){
$cash_balance=1;}
if (ereg("<POINT_BALANCE>",$massmail[subject]) or ereg("<POINT_BALANCE>",$emailtosend)){
$point_balance=1;}
$runnerurl=$scripts_url."runner.php";
if (ereg($runnerurl,$emailtosend)){
$spamsafety=1;}
if (!ereg("<OWED>|<CASH_BALANCE>|<POINT_BALANCE>|<USERNAME>|<FIRSTNAME>|<LASTNAME>",$massmail[subject]) and !ereg("<OWED>|<CASH_BALANCE>|<POINT_BALANCE>|<USERNAME>|<FIRSTNAME>|<LASTNAME>",$emailtosend)){
$sendasbcc=1;}
$getadinterest=@mysql_query("select keyword from ".$mysql_prefix."interest where username='#MASS-MAIL-ID:$massmail[massmailid]#'");
while ($keys=@mysql_fetch_array($getadinterest)){
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
$value=trim($keys[keyword]);
if ($value){
if (substr($value,0,2)!="c:"){
$keywordct++;
$words=$words.$or." keyword='$value'";
$or=" or";}
else {
$value=str_replace("c:","",$value);
$countries=$countries.$cor." country='$value'";
$cor=" or";}
}
}
$last_login_time=time()-60*60*24*365;
if ($words or $countries){
@mysql_query("drop table tmpcmailtbl");
@mysql_query("drop table tmpcmailcttbl");
@mysql_query("create temporary table tmpcmailtbl (username char(64) not null, keyword char(16) not null,key username(username),key keyword(keyword))");
@mysql_query("create temporary table tmpcmailcttbl (username char(64) not null, counter int not null,key username(username),key counter(counter))");
if ($words){
@mysql_query("insert into tmpcmailtbl (username,keyword) select username,keyword from ".$mysql_prefix."interest where $words");
}
if ($countries){
$keywordct++;
@mysql_query("insert into tmpcmailtbl (username,keyword) select username,country from ".$mysql_prefix."users where $countries");
}
@mysql_query("insert into tmpcmailcttbl (username,counter) select username,count(*) from tmpcmailtbl group by username");
@mysql_query("delete from tmpcmailcttbl where counter<$keywordct");
$leftjoinfirst="LEFT JOIN tmpcmailcttbl ON ".$mysql_prefix."users.username=tmpcmailcttbl.username";
$leftjoinsecond=" and tmpcmailcttbl.username IS NOT NULL";
@mysql_query("drop table tmpcmailtbl");
}
$thisstart=$massmail[current]-1;
$users=@mysql_query("select first_name,last_name,email,".$mysql_prefix."users.username from ".$mysql_prefix."users $leftjoinfirst where vacation<'$curdate'  $leftjoinsecond limit $thisstart,$massmail[stop]");
@mysql_query("drop table tmpcmailcttbl");
	while ($user=@mysql_fetch_array($users))			
{
@mysql_query("replace into ".$mysql_prefix."system_values set name='cronjobs_ran_at',value='".time()."'");
$message = $emailtosend;
$subject = $massmail[subject];
$user[email]=trim($user[email]);
if ($cash_balance){
list($cash)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where username='$user[username]' and type='cash'"));
$cash=$cash/100000/$admin_cash_factor;
if ($cash<0){
$owed=$cash*-1;}
else {$owed=0;}
}
if ($point_balance){
list($points)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where username='$user[username]' and type='points'"));
$points=$points/100000;
}
if (!$sendasbcc){
if (ereg("@",$user[email]) and !ereg(":",$user[email])){
$subject = str_replace("<OWED>", $owed, $subject);
$message = str_replace("<OWED>", $owed, $message);
$subject = str_replace("<CASH_BALANCE>", $cash, $subject);
$message = str_replace("<CASH_BALANCE>", $cash, $message);
$subject = str_replace("<POINT_BALANCE>", $points, $subject);
$message = str_replace("<POINT_BALANCE>", $points, $message);
$subject = str_replace("<USERNAME>", $user[username], $subject); 
$message = str_replace("<USERNAME>", $user[username], $message); 			
$message = str_replace("<FIRSTNAME>", $user[first_name], $message);
$message = str_replace("<LASTNAME>", $user[last_name], $message);
$subject = str_replace("<FIRSTNAME>", $user[first_name], $subject);
$subject = str_replace("<LASTNAME>", $user[last_name], $subject);
@mysql_query("replace into ".$mysql_prefix."system_values set name='last job',value=CONCAT('Email Sent to $user[email] - ',NOW())");
@mysql_query("update ".$mysql_prefix."mass_mailer set current=current+1 where massmailid=$massmail[massmailid]");
mail($user[email],trim(stripslashes($subject)),trim(stripslashes($message)), "Return-Path: ".system_value("massmail_email")."\nFrom: ".system_value("massmail_email")."\nReply-To: ".system_value("massmail_email")."\nX-Mailer: CashCrusader\n".$high); 
list($junk,$emaildomain)=split("@",$user[email]);
$emaildomainct[$emaildomain]++;
}
if ($emaildomainct[$emaildomain]>9){
sleep(10);
$emaildomainct="";
}
}
else {
if (ereg("@",$user[email]) and !ereg(":",$user[email])){
$bcc=$bcc.$comma.$user[email];
list($junk,$emaildomain)=split("@",$user[email]);
$emaildomainct[$emaildomain]++;
if ($emaildomainct[$emaildomain]>9){
$stopbcc=1;}
$comma=", ";}
$bcccount++;
if ($bcccount>$max_rcpt_to or $stopbcc){
$emaildomainct="";
$stopbcc=0;
@mysql_query("replace into ".$mysql_prefix."system_values set name='last job',value=CONCAT('Email Sent to BCC members list - ',NOW())");
@mysql_query("update ".$mysql_prefix."mass_mailer set current=current+$bcccount where massmailid=$massmail[massmailid]");
mail("$domain Members <>",trim(stripslashes($subject)), trim(stripslashes($message)), "Return-Path: ".system_value("massmail_email")."\nFrom: ".system_value("massmail_email")."\nReply-To: ".system_value("massmail_email")."\nX-Mailer: CashCrusader\n".$high."Bcc: $bcc\n");
sleep(10);
$bcccount=0;
$bcc="";
$comma="";}
}
}
@mysql_query("update ".$mysql_prefix."mass_mailer set current=stop+1 where massmailid=$massmail[massmailid]");
if (trim($bcc)){
@mysql_query("replace into ".$mysql_prefix."system_values set name='last job',value=CONCAT('Email Sent to BCC members list - ',NOW())");
mail("$domain Members <>",trim(stripslashes($subject)), trim(stripslashes($message)), "Return-Path: ".system_value("massmail_email")."\nFrom: ".system_value("massmail_email")."\nReply-To: ".system_value("massmail_email")."\nX-Mailer: CashCrusader\n".$high."Bcc: $bcc\n");
}
