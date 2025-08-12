<?php 
include "config.php";
include "incl/wml.inc";

if(!isset($t)){$t=0;}
is_topic($t);

$fs=open_file("$data/$t");
$fs=explode("\n",$fs);
$posts=count($fs);

if(isset($l)){$l=(int)$l;}else{$l=0;}
if($l>=$posts){$l=0;}

if(isset($n)){$n=(int)$n;}else{$n=0;}$p=$n*220;
if($n!=0){$er2=$n+1;$jj=' p.'.$er2;}else{$jj='';}
$er1=$l+1;$mg='Post '.$er1."($posts)".$jj;

$row=abc_only($fs[$l],1);if($row==0){$mg='Error';$row=':|::|::|:The post contains non-latin letters and cannot be shown:|::|:';}

print "<card id=\"show\" title=\"$mg\">";
$row=explode(":|:",$row);$fq=substr($row[3],$p,220);
print "<p><a href=\"wadd.php?f=$f&amp;t=$t&amp;u=$random\">Add...</a> <a href=\"wnd.php?f=$f&amp;u=$random\">Topics</a></p>";

if($l!=0){$x=$l-1;print "<p><a href=\"wshow.php?f=$f&amp;t=$t&amp;l=0&amp;u=$random\">*</a>&nbsp;<a href=\"wshow.php?f=$f&amp;t=$t&amp;l=$x&amp;u=$random\">&lt;&lt;</a>&nbsp;&nbsp;";}else{print "<p>";}
if($l<$posts-1){$y=$l+1;$g=$posts-1;print "<a href=\"wshow.php?f=$f&amp;t=$t&amp;l=$y&amp;u=$random\">&gt;&gt;</a>&nbsp;<a href=\"wshow.php?f=$f&amp;t=$t&amp;l=$g&amp;u=$random\">*</a></p>\n";}else{print "</p>\n";}

if($n!=0){$d=$n-1;print "<p><a href=\"wshow.php?f=$f&amp;t=$t&amp;l=$l&amp;n=$d&amp;u=$random\">...</a></p>";}
if(!strstr($row[0],' ')){$row[0]=time_offset($row[0]);}
print "<p>$row[2] $row[0]<br /> <small>$fq</small></p>";
if(strlen($fq)>=220){$d=$n+1;print "<p><a href=\"wshow.php?f=$f&amp;t=$t&amp;l=$l&amp;n=$d&amp;u=$random\">...</a></p>";}

if($l!=0){$x=$l-1;print "<p><a href=\"wshow.php?f=$f&amp;t=$t&amp;l=0&amp;u=$random\">*</a>&nbsp;<a href=\"wshow.php?f=$f&amp;t=$t&amp;l=$x&amp;u=$random\">&lt;&lt;</a>&nbsp;&nbsp;";}else{print "<p>";}
if($l<$posts-1){$y=$l+1;$g=$posts-1;print "<a href=\"wshow.php?f=$f&amp;t=$t&amp;l=$y&amp;u=$random\">&gt;&gt;</a>&nbsp;<a href=\"wshow.php?f=$f&amp;t=$t&amp;l=$g&amp;u=$random\">*</a></p>\n";}else{print "</p>\n";}

?></card></wml>