<?php 
include "config.php";
include "incl/head.inc";

if(!isset($go)||$go==0){$go=1;}else{$go=(int)$go;}

$go_forward=$go+$topics_per_page;
if($go>($topics_per_page+1)){$go_back=$go-$topics_per_page;}
else{$go_back=1;}

$fs=open_file($members_file);
$fs=explode("\n",$fs);?>

<title><?php $temp_var=$go_forward-1;print "$lang[63] ($go-$temp_var)";?></title></head><body>
<table align="center" width="460" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5"><tr class="c"><td colspan="2"><table width="100%" cellpadding="0" cellspacing="0"><tr><td><b class="w"><?php print $lang[63];?></b></td><td align="right">
<a href="join.php" style="color:#ffffff" onclick="zz=window.open('join.php','login','width=310,height=300,resizable=1');zz.focus();return false"><b>New Member</b></a>
</td></tr></table></td></tr><tr class="z"><td class="f" width="25%" nowrap="nowrap">&nbsp;<?php print $lang[62];?>&nbsp;</td><td class="f" width="75%" nowrap="nowrap">&nbsp;<?php print $lang[58];?></td></tr>
<?php 
for($i=$go;$i<$go_forward;$i++){
if(isset($fs[$i])&&strlen($fs[$i])>9){
$row=explode(":|:",$fs[$i]);
$random=rand(1,7);
if(isset($row[5])&&$row[5]!='m'){$sex='s';}else{$sex='w';}

print "<tr class=\"$row_bg\"><td class=\"s\" nowrap=\"nowrap\"><a href=\"#\" style=\"text-decoration:none\" onclick=\"usr('$row[0]');return false\"><img src=\"pics/$sex$random.gif\" $size_img[1] alt=\"\" hspace=\"2\" border=\"0\" /><b>$row[0]</b></a></td><td class=\"s\"><img src=\"pics/t$random.gif\" $size_img[2] alt=\"\" border=\"0\" align=\"left\" vspace=\"0\" />$row[3]</td></tr>\n";
switch_row_bg();}} 

print "<tr class=\"$row_bg\"><td colspan=\"2\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"f\">";
print "<a href=\"join.php\" onclick=\"zz=window.open('join.php','login','width=310,height=300,resizable=1');zz.focus();return false\">$lang[60]</a></td><td align=\"right\">";

print "<a href=\"memlist.php?go=$go_back\" title=\"&lt;&lt;\"><img src=\"pics/la.png\" width=\"10\" height=\"9\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"&lt;&lt;\" onmouseover=\"this.src='pics/lb.png'\" onmouseout=\"this.src='pics/la.png'\" /></a>";

for($i=0;$i<5;$i++){
if(is_integer($i/2)){$hspace=1;}else{$hspace=0;}
if($go!=($i*$topics_per_page+1)){set_navbar($i+1,1);

print "<a href=\"memlist.php?go=$nav2temp\" title=\"$lang[63]: $nav3temp-$nav1temp\"><img src=\"pics/of.png\" width=\"10\" height=\"9\" hspace=\"$hspace\" vspace=\"0\" border=\"0\" alt=\"$lang[63]: $nav3temp-$nav1temp\" onmouseover=\"this.src='pics/ou.png'\" onmouseout=\"this.src='pics/of.png'\" /></a>";}
else{print "<img src=\"pics/on.png\" width=\"10\" height=\"9\" hspace=\"$hspace\" vspace=\"0\" alt=\"\" />";}}

print "<a href=\"memlist.php?go=$go_forward\" title=\"&gt;&gt;\"><img src=\"pics/ra.png\" width=\"10\" height=\"9\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"&gt;&gt;\" onmouseover=\"this.src='pics/rb.png'\" onmouseout=\"this.src='pics/ra.png'\" /></a>";

?></td></tr></table></td></tr></table></td></tr></table></body></html>