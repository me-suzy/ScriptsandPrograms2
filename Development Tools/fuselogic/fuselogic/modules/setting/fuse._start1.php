<?php

singletonQueue();

ini_set('allow_url_fopen',1);

require_once('class.variable_domain.php');

$MAIN_SETTING = variable_domain::singleton();

$MAIN_SETTING->set('mysql_host','localhost');

$MAIN_SETTING->set('cache_directory',$_SERVER['DOCUMENT_ROOT'].'/../www_cache/');

$MAIN_SETTING->set('session_directory',$_SERVER['DOCUMENT_ROOT'].'/../www_session/');

?>