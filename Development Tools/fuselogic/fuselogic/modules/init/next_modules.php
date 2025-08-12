<?php
SingletonQueue();
$auto_detect = True;

$fl_module = array();

if(file_exists('circuits.php') and $auto_detect !== TRUE and userSubModule() !== 'update_modules'){
    require_once('circuits.php');			
}else{
    require_once('auto_detect.php');
}

foreach($fl_module as $module_name => $directory_name){
    setModule($module_name,'modules/'.$directory_name);
		$FL_MODULE_SETTING['sub_module'] = array();
		require(Real_path($module_name).'/module_setting.php');
		if(isset($FL_MODULE_SETTING['sub_module']['_autoload'])){
		    include_once(Real_path($module_name).$FL_MODULE_SETTING['sub_module']['_autoload']);
		}
}

//echo print_r($fl_module);
unset($fl_module);

?>