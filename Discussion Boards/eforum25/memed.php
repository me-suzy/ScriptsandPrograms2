<?php 
include "config.php";
include "incl/head.inc";
include "incl/ban.inc";

include "incl/format2.inc";

$temp_title=$lang[22];$name_exists='';$temp_var=0;

$fs=open_file($members_file);
$fs=explode("\n",$fs);;

if($name!=''&&$pass!=''&&$mail!=''){
$name=strtolower($name);
for($i=1;$i<count($fs);$i++){
if(strlen($fs[$i])>9){
$row=explode(":|:",$fs[$i]);

if(strtolower($row[0])==$name&&strtolower($row[1])==$pass){
$fs[$i]="$name:|:$pass:|:$mail:|:$text:|:$image:|:$sex";$temp_var=1;}
}}

if($temp_var!=0){
$fs=implode("\n",$fs);
save_file($members_file,$fs,0);
die("<title>...</title></head><body onload=\"window.location='memview.php?us=$name'\"></body></html>");}}

elseif(isset($name)){

for($i=1;$i<count($fs);$i++){
if(isset($fs[$i])&&strlen($fs[$i])>9){
$row=explode(":|:",$fs[$i]);
if(strtolower($row[0])==strtolower($name)){
$text=$row[3];
if(isset($row[4])){$image=$row[4];}
}}}}

else{die('<title>...</title></head><body></body></html>');}

$text=strip_tags($text);
include "incl/mem.inc";
?>