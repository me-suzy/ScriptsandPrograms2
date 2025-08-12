<?php

singletonQueue();
    
    $temp = getLayout();
		if(defined('FL_CACHE_CREATE') and FL_CACHE_CREATE){
		    $gabus_cache->save($temp,NULL,userModule());
		}
		echo $temp;				


?>
