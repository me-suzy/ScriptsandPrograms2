<?php
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
				include $file;
				$setting = array();
				$setting = $FL_MODULE_SETTING;
				if(isset($setting['module_name'])){
				   $count1 = count($setting['module_name']);
					 if($count1 > 1){
					     for($j=0;$j<$count1;$j++){
							    if(isset($setting['module_name'][$j]) and !isset($fl_module[$setting['module_name'][$j]])){
									    $temp = $setting['module_name'][$j];
					            $fl_module[$temp] = $temp2[$i];
											$FuseLogic->setModule($temp,'modules/'.$temp2[$i]);
									    foreach($setting['sub_module'] as $subModule => $file_name){
									        $FuseLogic->fuse[$temp][$subModule] = $file_name;									 
									    }
							    }
							 }					     
					 }else{
					     if(!isset($fl_module[$setting['module_name']])){ 
							     $temp = $setting['module_name'];
					         $fl_module[$temp] = $temp2[$i];
									 $FuseLogic->setModule($temp,'modules/'.$temp2[$i]);
									 foreach($setting['sub_module'] as $subModule => $file_name){
									     $FuseLogic->fuse[$temp][$subModule] = $file_name;									 
									 }
							 }				       
					 }
				}		 				
		}		
}

?>
