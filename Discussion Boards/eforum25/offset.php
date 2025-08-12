<?php 
include "config.php";

if(isset($settime)){
setcookie('user_time',$settime,time()+86400*100,'/');
include "incl/head.inc";
die("<title>...</title></head><body onload=\"if(window.opener){window.opener.location='index.php'}self.close()\"></body></html>");
}

include "incl/head.inc";?>
<title>...</title></head><body>
<table align="center" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5">
<tr class="c"><td><?php print $lang[72];?></td></tr>
<tr class="b"><td><table cellspacing="6"><tr><td class="s" nowrap="nowrap">
<?php 
for($i=-12;$i<=13;$i++){
$time_entry=gmdate($time_format,time()+$i*3600);

if($user_time==$i){$color=';color:#ffffff';}else{$color='';}
if($i==0){$gmt='&nbsp;GMT';}else{$gmt='';}

print "<a href=\"#\" onclick=\"window.location='offset.php?f=$f'+amp+'settime=$i';return false\" style=\"text-decoration:none$color\">".$time_entry."$gmt</a><br />\n";
}?>
</td></tr></table></td></tr></table></td></tr></table></body></html>