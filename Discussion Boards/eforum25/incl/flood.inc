<?php 
$file="$forum_data[0]/flood";
$level_exceeded=0;
$flood[0]=$flood[0]-1;
if(!is_file($file)){save_file($file,'',0);}

if(isset($wap)&&$wap==1){$die_string="<card id=\"error\" title=\"Error\"><p>you've been banned</p></card></wml>";}
else{$die_string="<title>...</title></head><body onload=\"self.close()\"></body></html>";}

$fs=open_file($file);
$fs=explode("\n",$fs);
for($i=0;$i<count($fs);$i++){
if(isset($fs[$i])&&strlen($fs[$i])>8){
if($fs[$i]==$REMOTE_ADDR){
$level_exceeded=$level_exceeded+1;}}}

if($level_exceeded>$flood[0]){
$file="$forum_data[0]/banned";
if(!is_file($file)){save_file($file,'',0);}
$fs=open_file($file);
$fs="$REMOTE_ADDR\n$fs";
save_file($file,$fs,0);
die($die_string);}

else{if(count($fs)<10){
$fs=implode("\n",$fs);
$fs="$REMOTE_ADDR\n$fs";
save_file($file,$fs,0);}
else{$fs="$REMOTE_ADDR\n$fs[0]\n$fs[1]\n$fs[2]\n$fs[3]\n$fs[4]\n$fs[5]\n$fs[6]\n$fs[7]\n$fs[8]";
save_file($file,$fs,0);}}
?>