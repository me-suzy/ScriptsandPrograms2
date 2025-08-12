<?php header("Content-Type: text/vnd.wap.wml");
print '<?xml version="1.0"?>';

$random=mt_rand(1,99999);$wap=1;

function abc_only($a,$x){$a=strip_tags($a);$a=stripslashes($a);$b=strlen($a);
$a=eregi_replace("[^a-z0-9!@#$%*()_+-={}:'\"?|,./\\ ]","",$a);
$a=eregi_replace("#[0-9]+;","",$a);$c=strlen($a);if($b>$c&&$x==1){$a=0;}
;$a=trim($a);return $a;}

function is_topic($n){global $f,$data;
$ok=0;$handle=opendir($data);while($entry=readdir($handle)){
if($entry==$n&&substr($entry,0,1)=='2'){$ok=1;break;}}closedir($handle);
if($ok==0){die('<card id="go" title="go"><onevent type="onenterforward"><go href="wnd.php?f=$f&amp;u=$random" /></onevent><p><a href="wnd.php?f=$f&amp;u=$random">Forward...</a></p></card></wml>');
}}
?><!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml"><wml>