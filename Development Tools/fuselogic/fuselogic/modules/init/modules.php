<?php
SingletonQueue();
//$auto = False; //faster but not relaiable for development
$auto = True; //slow but relaiable

//*************************************************************
//define module manually
$fl_module = array();
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

if(file_exists('circuits.php') and !$auto and userSubModule() !== 'update_modules' and userSubModule() !== 'modules_update'){
    require_once('circuits.php');			
}else{
//autodetect module
require_once('class/class.dir_reader.php');
$dir_reader = &new dir_reader();

$temp1 = array();
$root = dirname(dirname(__FILE__));
$dir_reader->read_directory($root);
$temp1 = $dir_reader->get_directory();

$count = count($temp1);
$temp2 = array();
for($i=0;$i<$count;$i++){
    if(substr($temp1[$i],0,1) !== '_' and $temp1[$i] !== 'init'){
		    $temp2[] = $temp1[$i];
		}
}

$count = count($temp2);
for($i=0;$i<$count;$i++){
    $file = str_replace('\\','/',$root.'/'.$temp2[$i].'/module_setting.php');		
    if(file_exists($file)){	
		    $FL_MODULE_SETTING = array();
				include $file;
				if(isset($FL_MODULE_SETTING['module_name'])){
				   $count1 = count($FL_MODULE_SETTING['module_name']);
					 if($count1 > 1){
					     for($j=0;$j<$count1;$j++){
							    if(isset($FL_MODULE_SETTING['module_name'][$j]) and !isset($fl_module[$FL_MODULE_SETTING['module_name'][$j]])){
					            $fl_module[$FL_MODULE_SETTING['module_name'][$j]] = $temp2[$i];
							    }
							 }					     
					 }else{
					     if(!isset($fl_module[$FL_MODULE_SETTING['module_name']])){
					         $fl_module[$FL_MODULE_SETTING['module_name']] = $temp2[$i];
							 }				       
					 }
				}		 				
		}		
}

}

foreach($fl_module as $module_name => $directory_name){
    $FuseLogic->setModule($module_name,'modules/'.$directory_name);
}
unset($fl_module);

?>