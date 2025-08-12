<?php

$scan = True;
$update = False;
$setting_file_name = $FuseLogic->FuseLogicRootDirectory.'/setting/setting.fuse.php';
//$setting_file_name = FL_PATH_CORE.'/setting.fuse.php';

$check = userModule();
if($check !== 'init'){
    if(include_once($setting_file_name)){		
        if(isset($modules['init']) & file_exists($modules['init'].'/module_setting.php')){
            $FuseLogic->modules = $modules;
            $FuseLogic->fuse = $fuse;
						$scan = False;							
        }
    }
}

$check = userModule().'/'.userSubModule();
if($check == 'init/update'){
    $update = True;
}

if($scan){
//autodetect module
$root = $FuseLogic->FuseLogicRootDirectory.'/modules';
$dir_reader = &new dir_reader($root);

$module_directory = $dir_reader->get_directory();

foreach($module_directory as $i => $value){
    if(substr($value,0,1) == '_'){		    
				unset($module_directory[$i]);		
		}
}

foreach($module_directory as $i => $value){

    $file = str_replace('\\','/',$root.'/'.$value.'/module_setting.php');		
    if(file_exists($file)){	
				$FL_MODULE_SETTING = array();    
				include $file;
				$setting = array();
				$setting = $FL_MODULE_SETTING;
				if(isset($setting['module_name'])){
				   $count1 = count($setting['module_name']);
					 if($count1 > 1){
					     for($j=0;$j<$count1;$j++){
							    if(isset($setting['module_name'][$j]) and !isset($fl_module[$setting['module_name'][$j]])){
									    $temp = $setting['module_name'][$j];
					            $fl_module[$temp] = $value;
									    $FuseLogic->setModule($temp,'modules/'.$value);
									    foreach($setting['sub_module'] as $subModule => $file_name){
									        $FuseLogic->fuse[$temp][$subModule] = $FuseLogic->modules[$temp].'/'.$file_name;									 
									    }
							    }
							 }					     
					 }else{
					     $temp = $setting['module_name'];
					     $fl_module[$temp] = $value;
							 $FuseLogic->setModule($temp,'modules/'.$value);
							 foreach($setting['sub_module'] as $subModule => $file_name){
							     $FuseLogic->fuse[$temp][$subModule] = $FuseLogic->modules[$temp].'/'.$file_name;									 
							 }
							  
					 }
				}		 				
		}		
}

}

if($update){
    require_once(FL_PATH_CORE.'class.module_reader.php');
    $module_writer = &new module_reader($setting_file_name);
    @$module_writer->save($FuseLogic->modules,$FuseLogic->fuse);
}

//auto load sub module with name '_autoload'
foreach($FuseLogic->modules as $module_name => $directory_name){   
		if(isset($FuseLogic->fuse[$module_name]['_autoload'])){
		    if(file_exists($FuseLogic->fuse[$module_name]['_autoload'])){
				    FirstQueue($module_name.'/_autoload',$module_name.'_autoload');						
				}
		}		
}

?>
