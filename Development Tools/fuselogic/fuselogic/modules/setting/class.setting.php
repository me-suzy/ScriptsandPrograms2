<?php

require_once(dirname(__FILE__).'/class.version.php');
$php_version = &new php_version();
if($php_version->less_than('5.0.0')){
    require_once 'class.4_setting.php';
}else{
    require_once 'class.5_setting.php';
}		

?> 