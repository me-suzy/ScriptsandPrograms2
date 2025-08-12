<?php

singletonQueue();


    $option = array();
		$option['lifeTime'] = 2360;
    $option['cacheDir'] = $MAIN_SETTING->get('cache_directory');	
    require_once('Lite.php');			
		$gabus_cache = &new Cache_Lite($option);
		$gabus_cache->clean();		
		echo '<div align="center"><h3>Cache Clear</h3></div>';
		


?>
