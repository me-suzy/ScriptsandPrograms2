<?php 
$temp_arr1=explode(':','[s]:[/s]:[br]:[m1]:[m2]:[m3]:[m4]:[m5]:[m6]:[b]:[i]:[c]:[/b]:[/i]:[/c]');
$temp_arr2=explode(':',"<div class=\"ic\"><hr size=\"1\" class=\"w\" />:<br /><hr size=\"1\" class=\"w\" /></div>: <br />:<img src=\"pics/m1.gif\" $size_img[0] hspace=\"2\" alt=\"\" />:<img src=\"pics/m2.gif\" $size_img[0] hspace=\"2\" alt=\"\" />:<img src=\"pics/m3.gif\" $size_img[0] hspace=\"2\" alt=\"\" />:<img src=\"pics/m4.gif\" $size_img[0] hspace=\"2\" alt=\"\" />:<img src=\"pics/m5.gif\" $size_img[0] hspace=\"2\" alt=\"\" />:<img src=\"pics/m6.gif\" $size_img[0] hspace=\"2\" alt=\"\" />:<b>:<i>:<span class=\"y\">:</b>:</i>:</span>");

$admin_ban=0;
if((strlen($name)>$flood[1])||(strlen($title)>$flood[2])||(strlen($text)>$flood[3])){$admin_ban=1;}
$lines=explode("\n",$text);if(count($lines)>$flood[6]){$admin_ban=1;}

for($i=0;$i<count($lines);$i++){
$words=explode(" ",$lines[$i]);
for($j=0;$j<count($words);$j++){
if(strlen($words[$j])>$flood[4]){$admin_ban=1;break 2;}
}}

if($admin_ban==1){
$desc=1;$name="ADMIN";$title="...";
$text=$lang[77]." [c]".$REMOTE_ADDR."[/c] ... [m2]";}

$name=clean_entry($name);
$title=clean_entry($title);
$text=clean_entry($text);
$image=clean_entry($image);
$image=str_replace('"','',$image);
$image=str_replace("'",'',$image);

$name=remove_bad_words($name);
$title=remove_bad_words($title);
$text=remove_bad_words($text);

for($i=0;$i<count($temp_arr1);$i++){$text=str_replace($temp_arr1[$i],$temp_arr2[$i],$text);}
$text=eregi_replace('[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]','<a href="\\0" target="_blank">\\0</a>',$text);

if(strlen($image)>90){$image='';}
if(isset($mem_pic)){$mem_pic=clean_entry($mem_pic);}else{$mem_pic='w1';}

$name="<img src=\"pics/$mem_pic.gif\" $size_img[1] alt=\"\" border=\"0\" hspace=\"2\" />$name";
$desc=strip_tags($text);$desc=substr($desc,0,90).'...';

$temp_var=0;
if(isset($key)&&$key!=''){
$check_name=strip_tags($name);
$check_name=strtolower($check_name);
$check_pass=strtolower($key);
$md5pass=md5($check_pass);

$mem_file=open_file($members_file);
$mem_file=explode("\n",$mem_file);
for($i=1;$i<count($mem_file);$i++){
if(isset($mem_file[$i])&&strlen($mem_file[$i])>9){
$mem_row=explode(":|:",$mem_file[$i]);

if($check_name==strtolower($mem_row[0])&&($check_pass==strtolower($mem_row[1])||$md5pass==strtolower($mem_row[1]))){
$temp_var=1;break;}}}

if($temp_var==1){$name="<a class=\"j\" href=\"#\" onclick=\"usr('$check_name');return false\">$name</a>";}}
if($temp_var==0&&$members_only==1){die("<title>...</title></head><body><div class=\"w\">$lang[66]</div></body></html>");}
?>