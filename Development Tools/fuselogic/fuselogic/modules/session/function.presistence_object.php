<?php
if(!function_exists('presistence_object')){
ob_start();
require_once 'class.include_path.php';
require_once 'class.presistence_list.php';
require_once 'class.presistence.php';

session_start();
if(!isset($_SESSION['presistence'])){	
	$_SESSION['presistence'] = &new presistence();
}
$presistence_copy = $_SESSION['presistence'];

$cwd = getcwd();
foreach($presistence_copy->list as $name=>$value){
   if(!class_exists($name) and file_exists($value->object_file)){
      chdir(dirname($value->object_file));
		require_once basename($value->object_file);
	 }
}
chdir($cwd);

session_write_close();
session_set_cookie_params(7776000);
session_start();
$_SESSION['presistence'] = $presistence_copy;

foreach($presistence_copy->list as $name => $value){
   if(isset($_SESSION[$name])) $GLOBALS[$name] = &$_SESSION[$name];
}

function presistence_object($instance_name,$object_name,$file_name = ''){
   $file_name = empty($file_name)?'class.'.$object_name.'.php':$file_name;
	 
   if(!isset($GLOBALS[$instance_name])){
      if(!class_exists($object_name)){
		require_once $file_name;				 	 
	}
			
	$GLOBALS['presistence_copy'] = $_SESSION['presistence'];				 
	session_write_close();
        session_set_cookie_params(7776000);
        session_start();			
     							 		  
	if(!isset($GLOBALS['presistence_copy']->list[$instance_name])){ 
	    $GLOBALS['presistence_copy']->list[$instance_name] = &new presistence_list($instance_name,$object_name);	 	       
	}
			
	$_SESSION['presistence'] = $GLOBALS['presistence_copy'];
			
	if(isset($_SESSION[$instance_name])){
           $GLOBALS[$instance_name] = $_SESSION[$instance_name];
        }else{	 
	   $GLOBALS[$instance_name] = &new ${object_name};
	   $_SESSION[$instance_name] = $GLOBALS[$instance_name];
        }
		
    }
	 
}

}

?>