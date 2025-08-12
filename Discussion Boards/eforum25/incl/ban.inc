<?php 
$file="$forum_data[0]/banned";

if(isset($wap)&&$wap==1){$die_string="<card id=\"error\" title=\"Error\"><p>you've been banned</p></card></wml>";}
else{$die_string="<title>...</title></head><body style=\"color:#ffffff\">$lang[50]</body></html>";}

if(isset($wap)&&$wap==1){$die_proxy="<card id=\"error\" title=\"Error\"><p>proxies not allowed</p></card></wml>";}
else{$die_proxy="<title>...</title></head><body style=\"color:#ffffff\">$lang[74]</body></html>";}

if(isset($allow_proxies)&&$allow_proxies==0&&(isset($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])||isset($HTTP_SERVER_VARS["HTTP_VIA"]))){
die($die_proxy);}

if(is_file($file)){

$fs=open_file($file);
$fs=explode("\n",$fs);

for($i=0;$i<count($fs);$i++){
if(strlen($fs[$i])>6&&strstr($REMOTE_ADDR,$fs[$i])){
die($die_string);}}} 
?>