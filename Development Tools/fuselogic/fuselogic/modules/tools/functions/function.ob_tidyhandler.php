<?php
if(!function_exists('ob_tidyhandler') and function_exists('tidy_repair_string'))
{    
    function ob_tidyhandler($buffer){
		    return tidy_repair_string($buffer);		
		}		
}
?>