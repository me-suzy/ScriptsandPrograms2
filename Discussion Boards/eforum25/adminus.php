<?php 
include "config.php";
include "incl/pss.inc";
include "incl/head.inc";

if(!isset($go)||$go==0){$go=1;}else{$go=(int)$go;}

$go_forward=$go+$topics_per_page;
if($go>($topics_per_page+1)){$go_back=$go-$topics_per_page;}
else{$go_back=1;}

$fs=open_file($members_file);
$fs=explode("\n",$fs);

if(isset($delete)){
$delete=round($delete);}
else{$delete=0;}

if($delete<count($fs)&&$delete!=0){
for($i=0;$i<count($fs);$i++){
if($delete==$i){$fs[$i]='';break;}}

$fs=implode("\n",$fs);
save_file($members_file,$fs,0);
die("<title>...</title></head><body onload=\"pp=Math.round(999999*Math.random());window.location='adminus.php?go=$go'+amp+'n='+pp\"></body></html>");
}
?><title><?php $temp_var=$go_forward-1;print "ADMIN: $lang[63] ($go-$temp_var)";?></title></head><body>
<table align="center" width="500" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5"><tr class="c"><td colspan="4"><table width="100%" cellpadding="0" cellspacing="0"><tr><td><b class="w"><?php print $lang[63];?></b></td><td align="right">
<?php
print "<a href=\"adminus.php?go=$go_back\" title=\"&lt;&lt;\"><img src=\"pics/la.png\" width=\"10\" height=\"9\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"&lt;&lt;\" onmouseover=\"this.src='pics/lb.png'\" onmouseout=\"this.src='pics/la.png'\" /></a>";

for($i=0;$i<5;$i++){
if(is_integer($i/2)){$hspace=1;}else{$hspace=0;}
if($go!=($i*$topics_per_page+1)){set_navbar($i+1,0);

print "<a href=\"adminus.php?go=$nav2temp\" title=\"$lang[63]: $nav3temp-$nav1temp\"><img src=\"pics/of.png\" width=\"10\" height=\"9\" hspace=\"$hspace\" vspace=\"0\" border=\"0\" alt=\"$lang[63]: $nav3temp-$nav1temp\" onmouseover=\"this.src='pics/ou.png'\" onmouseout=\"this.src='pics/of.png'\" /></a>";}
else{print "<img src=\"pics/on.png\" width=\"10\" height=\"9\" hspace=\"$hspace\" vspace=\"0\" alt=\"\" />";}}

print "<a href=\"adminus.php?go=$go_forward\" title=\"&gt;&gt;\"><img src=\"pics/ra.png\" width=\"10\" height=\"9\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"&gt;&gt;\" onmouseover=\"this.src='pics/rb.png'\" onmouseout=\"this.src='pics/ra.png'\" /></a>";
?>
</td></tr></table></td></tr><tr class="z"><td class="f" width="25%" nowrap="nowrap">&nbsp;<?php print $lang[62];?>&nbsp;</td><td class="f" width="20%" nowrap="nowrap">&nbsp;<?php print $lang[57];?></td><td class="f" width="50%" nowrap="nowrap">&nbsp;<?php print $lang[58];?></td><td class="f"  width="5%" nowrap="nowrap">&nbsp;<?php print $lang[33];?></td></tr>
<?php 
for($i=$go;$i<$go_forward;$i++){
if(isset($fs[$i])&&strlen($fs[$i])>9){
$row=explode(":|:",$fs[$i]);

$temp_var=rand(1,7);
if(isset($row[5])&&$row[5]!='m'){$sex='s';}else{$sex='w';}

print "<tr class=\"$row_bg\"><td class=\"s\" nowrap=\"nowrap\"><a href=\"#\" style=\"text-decoration:none\" onclick=\"usr('$row[0]');return false\"><img src=\"pics/$sex$temp_var.gif\" $size_img[1] alt=\"\" hspace=\"2\" border=\"0\" /><b>$row[0]</b></a></td><td class=\"s\"><a href=\"mailto:$row[2]\">$row[2]</a></td><td class=\"s\"><img src=\"pics/t$temp_var.gif\" $size_img[2] alt=\"\" border=\"0\" align=\"left\" vspace=\"0\" />$row[3]</td><td class=\"s\"><a href=\"adminus.php?f=$f&amp;delete=$i&amp;go=$go\" onclick=\"return no_undo('$lang[35]')\">$lang[34]</a></td></tr>\n";
switch_row_bg();
}}
?></table></td></tr></table></body></html>