<?php 
$die_string1='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><link rel="stylesheet" type="text/css" href="css/0.css"><script type="text/javascript" src="incl/all.js"></script><title>...</title></head><body style="background-color:#eeeeee" onload="document.y.apss.focus()"><form name="y" action="admin.php?f='.$f.'" method="post"><table align="center"><tr><td><img src="pics/adm.gif" width="14" height="12" alt="" /></td><td><input type="'.$pass_field.'" name="apss" class="ib" value="" /> <input type="submit" class="ib" value="OK" /></td></tr></table><div align="center" class="hu">';
$die_string2='</div></form></body></html>';

if(is_file("$forum_data[0]/psss.php")){
$temp_var=0;$file="$forum_data[0]/psss.php";

$pass_stored=open_file($file);
$pass_stored=str_replace('<?php die();?>','',$pass_stored);

if(isset($cpss)){$cpss=md5($cpss);
if($cpss==$pass_stored){$temp_var=1;}}

elseif(isset($apss)){$gpss=md5($apss);
if($gpss==$pass_stored){setcookie('cpss',$apss);$temp_var=1;}}
if($temp_var!=1){die($die_string1.$die_string2);}}

else{
if(isset($apss)&&strlen($apss)>2){
$file="$forum_data[0]/psss.php";
$gpss='<?php die();?>';
$gpss.=md5($apss);
save_file($file,$gpss,0);
setcookie('cpss',$apss);}
else{die($die_string1.$lang[71].$die_string2);}}
?>