<?php

if(isset($name)&&strlen($name)<=$flood[1]){
$name=str_replace("'","",$name);
$name=str_replace("\"","",$name);
$name=str_replace("\\","",$name);
$name=trim($name);
$name=clean_entry($name);
$name=remove_bad_words($name);
}else{$name='';}

if(isset($pass)&&strlen($pass)<=$flood[1]){
$pass=trim($pass);
$pass=clean_entry($pass);
$pass=strtolower($pass);
$pass=md5($pass);}
else{$pass='';}

if(isset($mail)&&strlen($mail)<120){
$mail=trim($mail);
$mail=clean_entry($mail);}
else{$mail='';}

if(isset($sex)&&$sex!='m'){
$sex='f';}else{$sex='m';}

if(!isset($image)||$image==''||$image=='http://'){$image='';}
else{$image=clean_entry($image);}
$image=str_replace('"','',$image);
$image=str_replace("'",'',$image);

if(isset($text)&&$text!=''&&strlen($text)<=$flood[5]){
$text=trim($text);
$text=remove_bad_words($text);
$text=str_replace("\r\n","[br]",$text);
$text=clean_entry($text);
$text=str_replace("[br]"," <br />",$text);
$text=str_replace("[b]","<b>",$text);
$text=str_replace("[i]","<i>",$text);
$text=str_replace("[c]","<span class=\"y\">",$text);
$text=str_replace("[/i]","</i>",$text);
$text=str_replace("[/b]","</b>",$text);
$text=str_replace("[/c]","</span>",$text);
$text=eregi_replace('[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]','<a href="\\0" target="_blank">\\0</a>',$text);
}else{$text='-';}
?>