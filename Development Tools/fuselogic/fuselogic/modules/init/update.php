<?php

require_once('class/class.module_reader.php');
$module_reader = &new module_reader('circuits.php');

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

$module_reader->update($fl_module);

echo '<div align="center"><h2>Done!</h2></div>';

?>