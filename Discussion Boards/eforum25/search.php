<?php 
include "config.php";
include "incl/head.inc";?>
<title><?php print $lang[32];?>...</title></head><body onload="document.y.src.focus()"><form name="y" action="search.php" method="get">
<table align="center" width="95%"><tr><td><?php include "incl/cust-top.inc";?>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="<?php print $cellpadding;?>">
<tr class="c"><td colspan="3"><table width="100%" cellpadding="1" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><a href="main.php" id="lnk" title="<?php print $lang[0];?>" style="color:#ffffff;text-decoration:none" onmouseover="start_impress();return true" onmouseout="stop_impress();return true" onclick="refresh(<?php print $f;?>);return false"><?php print $forum_name[$f];?></a></span></td>
<td class="c" align="right" width="120"><input type="hidden" name="f" value="<?php print $f;?>" /><input size="20" type="text" name="src" class="ia" style="font-size:9px;width:120px" maxlength="30" value="" /></td><td width="18"><a href="#" onclick="document.y.submit();return false"><img src="pics/find.gif" border="0" width="15" height="15" alt="<?php print $lang[32];?>" title="<?php print $lang[32];?>" /></a></td></tr></table></td></tr>
<?php
if(isset($src)){
$src=strtolower($src);
$src=strip_tags($src);
$src=trim($src);}

if(isset($src)&&strlen($src)>1){
$i=0;$temp_array=array();$found_number=0;
print "<tr class=\"z\"><td class=\"f\" width=\"20%\" nowrap=\"nowrap\">&nbsp;$lang[28]&nbsp;</td><td class=\"f\" width=\"70%\" nowrap=\"nowrap\">&nbsp;$lang[17]&nbsp;</td><td class=\"f\" width=\"10%\" nowrap=\"nowrap\">&nbsp;$lang[15]&nbsp;</td></tr>";

$handle=opendir($data);
while($entry=readdir($handle)){
if(is_file("$data/$entry")&&substr($entry,0,1)=='2'){
$temp_array[$i]=$entry;$i++;}}
closedir($handle);
rsort($temp_array);

for($j=0;$j<count($temp_array);$j++){
if($found_number>9){break;}
$file="$data/$temp_array[$j]";
$fs=open_file($file);
$topic=explode("\n",$fs);
for($i=0;$i<count($topic);$i++){
if($found_number>9){break;}

$row=explode(":|:",$topic[$i]);

if(isset($row[2])&&isset($row[3])){
$pic_number=explode(".gif",$row[2]);
$pic_number=substr($pic_number[0], -1);
if(!strstr($row[0],' ')){$row[0]=time_offset($row[0]);}

$search_in=str_replace("<br />","[br]",$row[3]);
$search_in=strip_tags($search_in);
$search_in=str_replace("[br]","<br />",$search_in);
$search_in=strtolower($search_in);
}else{$search_in='';}

if(stristr($search_in,$src)){$found_number++;
$search_in=str_replace($src,"<span style=\"background-color:yellow;font-weight:bold\">$src</span>",$search_in);
print "\n<tr class=\"$row_bg\"><td><b class=\"y\">$row[2]</b><br clear=\"all\" /><img src=\"pics/b$pic_number.gif\" $size_img[3] alt=\"\" hspace=\"1\" align=\"left\" /><b>$row[1]</b></td>";
print "\n<td class=\"s\"><br />$search_in<a href=\"show.php?f=$f&amp;topic=$temp_array[$j]\"><img src=\"pics/t$pic_number.gif\" border=\"0\" $size_img[2] alt=\"$lang[29]\" align=\"right\" /></a></td><td class=\"s\" nowrap=\"nowrap\">$row[0]</td></tr>";
switch_row_bg();}}}

if($found_number==0){$result=$lang[39];}
elseif($found_number>9){$result=$lang[40];}
else{$result='';}

print "\n<tr class=\"$row_bg\"><td class=\"r\" colspan=\"2\"><span class=\"y\">&nbsp;$result</span></td><td align=\"center\" class=\"r\"><a href=\"#\" onclick=\"self.scrollTo(0,0);return false\">$lang[30]</a></td></tr>";
}?></table></td></tr></table>
<?php $color_changing=0;include "incl/clrs.inc";?><br clear="all" />
<?php include "incl/cust-bot.inc";?></td></tr></table></form></body></html>