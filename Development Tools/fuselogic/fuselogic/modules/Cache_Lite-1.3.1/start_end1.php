<?php

singletonQueue();

$_FL_CACHE_ENABLE = True;

if(subModule() == '_start'){
    
		$ruleFile = Real_Path(userModule()).'/setting.cache.php';			
		@include($ruleFile);
		$array = &$setting_array;
		if(is_finite(@$array[userSubModule()]) and (@$array[userSubModule()] > 0)){
		
		    $option['lifeTime'] = (int)$array[userSubModule()];			
				define('FL_CACHE_TIME',$option['lifeTime']);
					
				require_once('Lite.php');			
				$option['cacheDir'] = $MAIN_SETTING->get('cache_directory');
		
		    $string_position = strpos($_SERVER['REQUEST_URI'],'#');
		    if($string_position > 0){
            $temp = userModule().'_'.userSubModule().'_'.md5(substr($_SERVER['REQUEST_URI'],0,$string_position)).'.html';
        }else{
            $temp = userModule().'_'.userSubModule().'_'.md5($_SERVER['REQUEST_URI']).'.html';
        }		
		    define('FL_CACHE_ID',$temp);
				
		    $gabus_cache = &new  Cache_Lite($option);     
		
        if($cache = $gabus_cache->get(FL_CACHE_ID,userModule())){
				    $MAIN_SETTING->set('cache_info','Cache');
						require_once('function.header_setexpires.php');
						header_setExpires($gabus_cache->timeToLife());
						if(isset($header[userSubModule()])){
						    header($header[userSubModule()]);
						}
						header('Cache-Control: max-age='.(string)$gabus_cache->timeToLife().', must-revalidate');
            echo $cache;    
            $FLQueue->clear();		        
		        QueueIf('printer_friendly/_print');
		        QueueIf('display/_replace');		
						$FLQueue->close();
        }else{
				    $MAIN_SETTING->set('cache_info','New Cache');
						define('FL_CACHE_CREATE',True);
				}    
    }			
   
}elseif(subModule() == '_end'){       
    
    $temp = getLayout();
		if(defined('FL_CACHE_CREATE') and FL_CACHE_CREATE){
		    $gabus_cache->save($temp,NULL,userModule());
		}
		echo $temp;				

}elseif(subModule() == 'clear'){
    $option = array();
		$option['lifeTime'] = 2360;
    $option['cacheDir'] = $MAIN_SETTING->get('cache_directory');	
    require_once('Lite.php');			
		$gabus_cache = &new Cache_Lite($option);
		$gabus_cache->clean();		
		echo '<div align="center"><h3>Cache Clear</h3></div>';
		
}

?>
