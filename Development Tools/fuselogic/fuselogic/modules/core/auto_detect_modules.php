<?php
require_once('class.dir_reader.php');

$check = userModule().'/'.userSubModule();
if($check == 'init/update'){
  $update = True;
}else{
  $update = False;
}

$scan = True;
$setting_file_name = $FuseLogic->env->data('core').'/setting.fuse.php';
if(!file_exists($setting_file_name)){
   copy($FL_ENV->core_path.'/data/setting.fuse.php',$setting_file_name);
	 chmod($setting_file_name,766);	 
	 copy($FL_ENV->core_path.'/data/.htaccess',dirname($setting_file_name).'/.htaccess');
   $update = True;
}

$check = userModule();
if($check !== 'init'){
  if(include($setting_file_name)){		
    if(isset($modules['init'])){
      $FuseLogic->modules = $modules;
      $FuseLogic->fuse = $fuse;
	    $scan = False;							
    }
  }
}

//development mode -> $scan = true;
if(file_exists($FL_ENV->core_path.'/setting.scan_mode.php')) $scan = true;

if($scan){
  //autodetect module
  $root = $FuseLogic->env->root_path.'/modules';
  $dir_reader = &new dir_reader($root);

  foreach($dir_reader->directory as $i => $value){    
		if($value{0} == '_'){		    
				unset($dir_reader->directory[$i]);		
		}
  }

  reset($dir_reader->directory);
  foreach($dir_reader->directory as $i => $value){

    $dir = str_replace('\\','/',$root.'/'.$value);		
		$dir_reader->read($dir);		
		foreach($dir_reader->files as $j => $file){
		  if(preg_match('/^(module_name\.)\w+(\.php)$/',strtolower($file))){
			  $temp = str_replace('module_name.','',$file);
				$temp = str_replace('.php','',$temp); 		
		    $FuseLogic->setModule($temp,'modules/'.$value);
			  reset($dir_reader->files);
				foreach($dir_reader->files as $k => $file){
			    if(preg_match('/^(fuse\.)\w+(\.php)$/',strtolower($file))){								    
			      $subModule = str_replace('fuse.','',$file);
						$subModule = str_replace('.php','',$subModule);				    
						$FuseLogic->fuse[$temp][$subModule] = str_replace($FuseLogic->env->root_path,'',$FuseLogic->modules[$temp].'/'.$file);	
					}
				}							
				break;		
			}
		}    				
  }
}

if($update){
  require_once($FL_ENV->core_path.'/class.module_reader.php');
  $module_writer = &new module_reader($setting_file_name);	
  @$module_writer->save($FuseLogic->modules,$FuseLogic->fuse);
}

?>
