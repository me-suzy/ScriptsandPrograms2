#!/usr/local/bin/php
<?
set_time_limit(0);
include("conf.inc.php");
require_once("functions.inc.php");
echo "<pre>\n";
mysqlstart();
$getusers=mysql_query("select * from CAC_import_users_tmp");
while($ufile=mysql_fetch_array($getusers)){
   $result = $ufile[joindate]; 
   $result = str_replace( "  ", " ", $result );
   $result = str_replace( ",", "", $result );
   list( $aMonth, $aDay, $aYear, $theRest ) =  explode( " ", $result, 4 );
   $ufile[joindate] = date( "YmdHis",
                   strtotime("$aDay $aMonth $aYear $therest"));
echo $ufile[u]."\n";
if (!$ufile[email]){
$ufile[email]=$ufile[u];}
mysql_query("insert into users set username='$ufile[u]',password='$ufile[password]',email='$ufile[email]',upline='$ufile[upline]',referrer='$ufile[upline]',signup_ip_host='$ufile[ip]',first_name='$ufile[first_name]',last_name='$ufile[last_name]',address='$ufile[address]',city='$ufile[city]',state='$ufile[state]',zipcode='$ufile[zipcode]',country='$ufile[country]',signup_date='$ufile[joindate]'");
}
$users=mysql_query("select username from users");
while($row=mysql_fetch_row($users)){
$upline=$row[0];
mysql_query("delete from ".$mysql_prefix."levels where username='$username'");
for ($idx=1;$idx<=count($awardpoints);$idx++){
list($upline)=mysql_fetch_row(mysql_query("select upline from ".$mysql_prefix."users where username='$upline' limit 1"));
if (!$upline){
break;
}
$tier=$idx-1;
echo "$row[0] - $upline $tier";
mysql_query("insert into ".$mysql_prefix."levels set upline='$upline',username='$row[0]',level=$tier");
}}
mysql_query("delete from ".$mysql_prefix."users where username='' or email=''");
mysql_query("delete from ".$mysql_prefix."accounting where username='' or username='\$referer'");
