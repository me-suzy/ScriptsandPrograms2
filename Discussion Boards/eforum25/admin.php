<?php 

include "config.php";
include "incl/pss.inc";
include "incl/head.inc";

if(!isset($go)){$go=0;}else{$go=(int)$go;}
$go_forward=$go+$topics_per_page;
if($go>$topics_per_page){$go_back=$go-$topics_per_page;}
else{$go_back=0;} 

?><title>ADMIN: <?php $temp_var=$go+1;print "$lang[21] ($temp_var-$go_forward)";?></title>
</head><body><?php include "incl/cust-top.inc";?>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="<?php print $cellpadding;?>">
<tr class="c"><td colspan="6"><table width="100%" cellpadding="2" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><a href="admin.php" id="lnk" title="<?php print $lang[0];?>" style="color:#ffffff;text-decoration:none" onmouseover="start_impress();return true" onmouseout="stop_impress();return true" onclick="pp=Math.round(999999*Math.random());window.location='admin.php?f=<?php print $f;?>'+amp+'n='+pp;return false"><?php print $forum_name[$f];?></a></span></td><td align="right" nowrap="nowrap"><b style="color:white">ADMIN</b></td></tr></table></td></tr>
<tr class="z"><td class="f" width="4"></td><td class="f" width="60%" nowrap="nowrap">&nbsp;<?php print $lang[21];?>&nbsp;</td><td class="f" width="10%" nowrap="nowrap">&nbsp;<?php print $lang[23];?>&nbsp;</td><td class="f" width="10%" nowrap="nowrap">&nbsp;<?php print $lang[24];?>&nbsp;</td><td class="f" width="4%" nowrap="nowrap">&nbsp;<?php print $lang[25];?>&nbsp;</td><td class="f" width="12%" nowrap="nowrap">&nbsp;<?php print $lang[33];?>&nbsp;</td></tr>
<?php 

$temp_var=0;
$fs=open_file($log);
$fs=explode("\n",$fs);

if(isset($delete)){
$temp_var=1;
file_allowed($delete);
unlink("$data/$delete");
for($i=0;$i<count($fs);$i++){
$row=explode(":|:",$fs[$i]);
if($row[0]==$delete){$fs[$i]='';break;}
}}

elseif(count($fs)>$topics_max){
$temp_var=1;
for($i=$topics_max;$i<count($fs);$i++){
$row=explode(":|:",$fs[$i]);
$old="$data/$row[0]";
if(is_file($old)){unlink($old);$fs[$i]='';}
}}

if($temp_var==1){
$fr=implode("\n",$fs);
save_file($log,$fr,0);}

for($i=$go;$i<$go_forward;$i++){
if(isset($fs[$i])&&strlen($fs[$i])>5){
$row=explode(":|:",$fs[$i]);

$pic_number=explode(".gif",$row[4]);
$pic_number=substr($pic_number[0],-1);
if(!strstr($row[1],' ')){$row[1]=time_offset($row[1]);}

print "<tr class=\"$row_bg\"><td><img src=\"pics/t$pic_number.gif\" $size_img[2] alt=\"\" hspace=\"2\" vspace=\"2\" /></td>";
print "<td><a href=\"adminsh.php?f=$f&amp;topic=$row[0]&amp;u=$row[5]\" class=\"v\"><b>$row[2]</b></a><div class=\"s\">$row[3]</div></td>";
print "\n<td nowrap=\"nowrap\"><b>$row[4]</b></td>";
print "<td class=\"s\" nowrap=\"nowrap\">$row[1]</td>";
print "<td class=\"s\">$row[5]</td>";
print "<td class=\"s\" nowrap=\"nowrap\"><a href=\"#\" onclick=\"window.open('move.php?f=$f'+amp+'topic=$row[0]','mov','height=250,width=250,resizable=1,scrollbars=1');return false\">$lang[76]</a> <a href=\"admin.php?f=$f&amp;go=$go&amp;delete=$row[0]\" onclick=\"return no_undo('$lang[35]')\">$lang[34]</a></td></tr>\n";
switch_row_bg();}}

print "<tr class=\"$row_bg\"><td colspan=\"3\" class=\"r\"><img src=\"pics/rpr.gif\" width=\"15\" height=\"9\" alt=\"$lang[55]\" title=\"$lang[55]\" onclick=\"qw=confirm('$lang[54]');if(qw){window.open('repair.php?f=$f')}\" /></td>";
print "<td class=\"r\" align=\"center\">";
print "<a href=\"admin.php?f=$f&amp;go=$go_back\" title=\"&lt;&lt;\"><img src=\"pics/la.png\" width=\"10\" height=\"9\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"&lt;&lt;\" onmouseover=\"this.src='pics/lb.png'\" onmouseout=\"this.src='pics/la.png'\" /></a>";

for($i=0;$i<5;$i++){
if(is_integer($i/2)){$hspace=1;}else{$hspace=0;}
if($go!=($i*$topics_per_page)){set_navbar($i+1,0);

print "<a href=\"admin.php?f=$f&amp;go=$nav2temp\" title=\"$lang[21]: $nav3temp-$nav1temp\"><img src=\"pics/of.png\" width=\"10\" height=\"9\" hspace=\"$hspace\" vspace=\"0\" border=\"0\" alt=\"$lang[21]: $nav3temp-$nav1temp\" onmouseover=\"this.src='pics/ou.png'\" onmouseout=\"this.src='pics/of.png'\" /></a>";}
else{print "<img src=\"pics/on.png\" width=\"10\" height=\"9\" hspace=\"$hspace\" vspace=\"0\" alt=\"\" />";}}

print "<a href=\"admin.php?f=$f&amp;go=$go_forward\" title=\"&gt;&gt;\"><img src=\"pics/ra.png\" width=\"10\" height=\"9\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"&gt;&gt;\" onmouseover=\"this.src='pics/rb.png'\" onmouseout=\"this.src='pics/ra.png'\" /></a>";
?></td>
<td class="r" align="center" colspan="2">
<a href="#" onclick="yu=window.open('backup.php?f=<?php print $f;?>','bjp','height=120,width=250,resizable=1,scrollbars=1');yu.focus();return false" title="<?php print $lang[51];?>"><img src="pics/backup.gif" border="0" hspace="2" vspace="2" width="13" height="14" alt="<?php print $lang[51];?>" /></a>
<a href="#" onclick="zz=window.open('adminus.php','dlu','width=600,height=620,resizable=1,scrollbars=1,status=1');zz.focus();return false" title="<?php print $lang[63];?>"><img src="pics/log.gif" width="24" border="0" hspace="2" vspace="2" height="14" alt="<?php print $lang[63];?>" /></a>
<a href="#" onclick="pp=Math.round(99999*Math.random());ban(<?php print $f;?>,pp);return false" title="<?php print $lang[47];?>"><img src="pics/banned.gif" border="0" hspace="2" vspace="2" width="17" height="14" alt="<?php print $lang[47];?>" /></a>
</td></tr></table></td></tr></table><table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr><td class="f"><?php $end_time=time_to_run();$total_time=substr(($end_time-$start_time),0,5);print "$total_time $lang[31]";?></td>
<td><?php include "incl/rate.inc";?>
</td></tr></table><?php include "incl/cust-bot.inc";?>
</body></html>