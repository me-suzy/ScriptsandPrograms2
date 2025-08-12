<?php 
include "config.php";
include "incl/head.inc";

include "incl/ban.inc";
include "incl/format2.inc";

$temp_title=$lang[60];$name_exists='';$temp_var=0;

if($name!=''&&$pass!=''&&$mail!=''){

$fs=open_file($members_file);
$fs=explode("\n",$fs);

for($i=1;$i<count($fs);$i++){
if(isset($fs[$i])&&strlen($fs[$i])>9){
$row=explode(":|:",$fs[$i]);
if(strtolower($row[0])==strtolower($name)){
$temp_var=1;break;}}}

if($temp_var==0){
$fs=implode("\n",$fs);
$fs="$fs\n$name:|:$pass:|:$mail:|:$text:|:$image:|:$sex";
save_file($members_file,$fs,0);
include "incl/flood.inc";
die("<title>...</title></head><body onload=\"window.location='memview.php?us=$name'\"></body></html>");}}

if($temp_var==1){$name_exists="<div align=\"center\" class=\"w\">$lang[59]</div>";}

$text=strip_tags($text);
include "incl/mem.inc";
?>