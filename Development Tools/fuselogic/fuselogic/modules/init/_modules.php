<?php
SingletonQueue();
$auto_detect = True;

$fl_module = array();

if(file_exists('circuits.php') and $auto_detect !== TRUE and userSubModule() !== 'update_modules'){
    require_once('circuits.php');			
}else{
    require_once('auto_detect.php');
}
/*
foreach($fl_module as $module_name => $directory_name){
    //setModule($module_name,'modules/'.$directory_name);		
		$FL_MODULE_SETTING = array();
		$temp = Real_Path($module_name);
		require($temp.'/module_setting.php');
		if(isset($FL_MODULE_SETTING['sub_module']['_autoload'])){
		    if(file_exists($temp.'/'.$FL_MODULE_SETTING['sub_module']['_autoload'])){
				    chdir($temp);
		        require_once($temp.'/'.$FL_MODULE_SETTING['sub_module']['_autoload']);								    
				}
		}		
}
*/
foreach($FuseLogic->modules as $module_name => $directory_name){    
    if(isset($FuseLogic->fuse[$module_name]['_autoload'])){
		    if(file_exists($directory_name.'/'.$FuseLogic->fuse[$module_name]['_autoload'])){
				    FirstQueue($module_name.'/_autoload');
						//echo $module_name.'/_autoload';
				}
		}
}
//exit;
//unset($fl_module);

?>