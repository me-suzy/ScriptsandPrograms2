<?php
if(!function_exists('str_ireplace')){
function str_ireplace($needle,$rep,$haystack,$pos=0){
    $b=explode(strtolower($needle),strtolower($haystack));
    foreach($b AS $bK => $bV) {
        $b[$bK]=substr($haystack,$pos,strlen($bV));
        $pos+=strlen($bV)+strlen($needle);
    }
		return implode($rep,$b);
}
}
?>
