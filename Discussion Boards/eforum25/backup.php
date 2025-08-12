<?php 
include "config.php";
include "incl/pss.inc";
include "incl/head.inc";

$latest_backup="$data/when";
$backup_dir=$forum_back[$f];

if(!is_writeable($latest_backup)){
save_file($latest_backup,'',0);}

$task_done="<title>...</title></head><body onload=\"pp=Math.round(99999*Math.random());window.location='backup.php?f=$f'+amp+'n='+pp\"></body></html>";

if(isset($backup_all)&&is_dir($backup_dir)){
$handle=opendir($backup_dir);
while($entry=readdir($handle)){
if(is_file("$backup_dir/$entry")){
unlink("$backup_dir/$entry");}
}closedir($handle);
$handle=opendir($data);
while($entry=readdir($handle)){
if(is_file("$data/$entry")&&(substr($entry,0,1)=='2'||$entry=='gshow')){
$test_f=copy("$data/$entry","$backup_dir/$entry");}
}closedir($handle);
$test_m=copy($members_file,"$backup_dir/$members_file");
if(isset($test_m)&&$test_m==true&&isset($test_f)&&$test_f==true){
save_file($latest_backup,$current_time,0);}
die($task_done);}

elseif(isset($restore_topics)&&is_dir($backup_dir)){
$handle=opendir($data);
while($entry=readdir($handle)){
if(is_file("$data/$entry")&&(substr($entry,0,1)=='2'||$entry=='gshow')){
unlink("$data/$entry");}
}closedir($handle);
$handle=opendir($backup_dir);
while($entry=readdir($handle)){
if(is_file("$backup_dir/$entry")&&$entry!=$members_file){
copy("$backup_dir/$entry","$data/$entry");}
}closedir($handle);
die($task_done);}

elseif(isset($restore_members)&&is_dir($backup_dir)){
copy("$backup_dir/$members_file",$members_file);
die($task_done);}
?>
<title><?php print $lang[51];?></title></head><body>
<table align="center" width="230" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5"><tr class="c">
<td colspan="2"><a href="backup.php?f=<?php print $f;?>&amp;backup_all=1" style="color:#ffffff;text-decoration:none"><b><?php print $lang[52];?></b></a> (<?php print $forum_name[$f];?>)</td></tr>
<tr class="z"><td class="f" nowrap="nowrap">&nbsp;<?php print $lang[15];?></td><td class="f" nowrap="nowrap">&nbsp;<?php print $lang[53];?></td></tr>
<?php
$when=open_file($latest_backup);
if(strlen($when)>5){if(!strstr($when,' ')){$when=time_offset($when);}
print "<tr class=\"a\"><td width=\"50%\" class=\"s\" nowrap=\"nowrap\">$when</td><td width=\"50%\" class=\"s\" nowrap=\"nowrap\"><a href=\"backup.php?f=$f&amp;restore_topics=1\">$lang[21]</a> <a href=\"backup.php?f=$f&amp;restore_members=1\" onclick=\"qzz=confirm('$lang[73]');if(qzz){return true}else{return false}\">$lang[63]</a></td></tr>";
}?></table></td></tr></table></body></html>