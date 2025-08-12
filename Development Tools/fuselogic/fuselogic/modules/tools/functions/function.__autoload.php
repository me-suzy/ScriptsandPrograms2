<?php
singletonQueue();
define('AUTO_LOAD_DIR_CLASS',dirname(dirname(__FILE__)).'/class/');

function __autoload($className){
    $className = strtolower($className);
    if(file_exists('./class/class.'.$className.'.php')){
		    include_once './class/class.'.$className.'.php';
		}elseif(file_exists('./class.'.$className.'.php')){
		    include_once './class.'.$className.'.php';
		}elseif(file_exists(AUTO_LOAD_DIR_CLASS.'class.'.$className.'.php')){
		    include_once AUTO_LOAD_DIR_CLASS.'class.'.$className.'.php';
		}
}

?>
