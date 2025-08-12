#!/usr/local/bin/php
<?
$counter=0;
$unixtime=0;
set_time_limit(0);
include("conf.inc.php");
require_once("functions.inc.php");
echo "<pre>\n";
mysqlstart();
$getfields=mysql_query("describe ".$mysql_prefix."CAC_import_users_tmp");
while($fields=mysql_fetch_array($getfields)){
$keys[$fields[Field]]=1;
}
exec("ls -1 $userdatadir",$lines);
for ($idx=0;$idx<count($lines);$idx++){
$user=file($userdatadir.$lines[$idx]);
$set="";
echo "$lines[$idx]\n";
for ($idxa=0;$idxa<count($user);$idxa++){
list($key,$value)=split("::",$user[$idxa]);
if ($keys[$key] and $key!='u'){
$value=addslashes(trim($value));
if ($key=='debit'){
$items=split(",",$value);
for ($gi=0;$gi<count($items);$gi++){
list ($iname,$iamt)=split("&&",$items[$gi]);
$iamt=$iamt*100000;
$unixtime++;
if ($iamt){
$counter++;
mysql_query("insert into ".$mysql_prefix."accounting set transid='$counter',unixtime=$unixtime,type='cash', description='$iname',amount=-$iamt,username='$lines[$idx]'");
echo " - $iname:$iamt\n";
}
}
}
if ($key=='credit'){
$items=split(",",$value);
for ($gi=0;$gi<count($items);$gi++){
list ($iname,$iamt)=split("&&",$items[$gi]);
$iamt=$iamt*100000;
$unixtime++;
if ($iamt or ereg('Sign Up Bonus',$iname)){
$counter++;
mysql_query("insert into ".$mysql_prefix."accounting set transid='$counter',unixtime=$unixtime,type='cash',description='$iname',amount=$iamt,username='$lines[$idx]'");
echo " - $iname:$iamt\n";
}}
}
if ($key=='clicks'){
$value=$value*100000;
if ($value){
$counter++;
mysql_query("insert into ".$mysql_prefix."accounting set transid='$counter',type='cash',description='#SELF-EARNINGS#',amount=$value,username='$lines[$idx]'"); 
creditulclicks($lines[$idx],$value,"cash");
}}
$set.="$key='$value',";
}}
mysql_query("insert into ".$mysql_prefix."CAC_import_users_tmp set $set u='$lines[$idx]'"); 
}
