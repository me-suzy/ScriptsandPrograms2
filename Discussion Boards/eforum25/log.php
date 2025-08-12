<?php 

include "config.php";

$temp_var=0;

if(isset($memname)&&isset($mempass)){
setcookie("memname","",time(),'/');
setcookie("mempass","",time(),'/');
include "incl/head.inc";
die("<title>$lang[68]</title></head><body onload=\"if(window.opener){window.opener.location='index.php'}setTimeout('self.close()',3000)\"><div class=\"w\">$lang[70] <b>$memname </b></div></body></html>");
}

if(isset($enter_name)&&strlen($enter_name)<=$flood[1]){$enter_name=clean_entry($enter_name);}else{$enter_name='';}
if(isset($enter_pass)&&strlen($enter_pass)<=$flood[1]){$enter_pass=clean_entry($enter_pass);}else{$enter_pass='';}

if($enter_name!=''&&$enter_pass!=''){
$enter_pass=md5(strtolower($enter_pass));

$fs=open_file($members_file);
$fs=explode("\n",$fs);

for($i=1;$i<count($fs);$i++){
if(isset($fs[$i])&&strlen($fs[$i])>9){
$row=explode(":|:",$fs[$i]);

if(strtolower($enter_name)==strtolower($row[0])&&$enter_pass==$row[1]){
$temp_var=1;break;}}}

if($temp_var==0){
die("<title>...</title></head><body onload=\"window.location='log.php'\"></body></html>");
}}

if($temp_var==1){
setcookie("memname",$enter_name,time()+86400*100,'/');
setcookie("mempass",$enter_pass,time()+86400*100,'/');
include "incl/head.inc";
die("<title>$lang[67]</title></head><body onload=\"if(window.opener){window.opener.location='index.php'}setTimeout('self.close()',3000)\"><div class=\"w\">$lang[69] <b>$enter_name </b></div></body></html>");
}

else{
include "incl/head.inc";
include "incl/log.inc";}

?>