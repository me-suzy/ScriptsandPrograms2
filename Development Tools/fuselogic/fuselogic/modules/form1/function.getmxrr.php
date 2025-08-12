<?php
if(!function_exists('getmxrr')){
    function getmxrr($hostname, &$mxhosts){
        $mxhosts = array();
        exec('nslookup -type=mx '.$hostname, $result_arr);
        foreach($result_arr as $line){
            if(preg_match("/.*mail exchanger = (.*)/", $line, $matches))
             $mxhosts[] = $matches[1];
        }
        return(count($mxhosts)>0);
    }
}

?>
