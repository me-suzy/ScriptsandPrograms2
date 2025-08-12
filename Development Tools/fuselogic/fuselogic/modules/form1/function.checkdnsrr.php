<?php
if(!function_exists('checkdnsrr')){

    function checkdnsrr($hostName='',$recType=''){
		    if(!empty($hostName)){
            if($recType == '') $recType = "MX";
                exec("nslookup -type=$recType $hostName", $result);
                foreach ($result as $line) {
                    if(eregi("^$hostName",$line)) {
                    return true;
                }
            }
            return false;
        }
        return false; 
		}
		
}
?>