<?php 
include "config.php";
include "incl/head.inc";

$temp_var=0;$new_pass='';

$alphabet="abcdefghijklmnopqrstuvwxyz1234567890";
for($i=0;$i<6;$i++){$new_pass.=$alphabet[(mt_rand(0,(strlen($alphabet)-1)))];}

if(isset($mail)&&$mail!=''){
$mail=strtolower($mail);

$fs=open_file($members_file);
$fs=explode("\n",$fs);

for($i=1;$i<count($fs);$i++){
if(strlen($fs[$i])>9){
$row=explode(":|:",$fs[$i]);
if(strtolower($row[2])==$mail){
$row[1]=md5($new_pass);
$user=$row[0];
if(!isset($row[5])){$row[5]='m';}

$fs[$i]=implode(":|:",$row);
$temp_var=1;break;}}}}

if($temp_var==1){
$fs=implode("\n",$fs);
save_file($members_file,$fs,0);
mail($mail,"...","user:$user\npassword:$new_pass","From: admin@$SERVER_NAME");
die("<title>...</title></head><body><span class=\"w\">$lang[65]</span></body></html>");}

?><title>...</title></head><body><form name="y" action="fgtnp.php" method="post">
<table width="280" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5">
<tr class="c"><td><table width="100%" cellpadding="1" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><?php print $lang[61];?></span></td></tr></table></td></tr>
<tr class="a"><td><table><tr><td width="50" nowrap="nowrap"><b><?php print $lang[57];?>:</b></td>
<td width="180"><input size="25" type="text" name="mail" class="ia" maxlength="30" value="" /></td>
<td width="50"><input type="submit" value="<?php print $lang[3];?>" class="ib" /></td>
</tr></table></td></tr></table></td></tr></table></form></body></html>