<?php

singletonQueue();

ini_set('allow_url_fopen',1);

require_once('class.variable_domain.php');
//require_once('class.setting.php');

$MAIN_SETTING = variable_domain::singleton();

$MAIN_SETTING->set('mysql_host','localhost');
$MAIN_SETTING->set('mysql_database','ekobudi');
$MAIN_SETTING->set('mysql_user','ekobudi');
$MAIN_SETTING->set('mysql_password','personal');
$MAIN_SETTING->set('dsn','mysql://ekobudi:personal@localhost/ekobudi');

$MAIN_SETTING->set('admin_password','please');
$MAIN_SETTING->set('smtp_password','personal');
$MAIN_SETTING->set('wiki_admin_user','admin');
$MAIN_SETTING->set('wiki_admin_password','personal');
$MAIN_SETTING->set('md5_string','4ah4weafhaweh4548wenz1x1e5s2dnds');
$MAIN_SETTING->set('SECRET_STRING','5we1zs+651a5115af12x531ef5615fa1a5f165af');

$MAIN_SETTING->set('cache_directory',$_SERVER['DOCUMENT_ROOT'].'/../www_cache/');

$MAIN_SETTING->set('session_directory',$_SERVER['DOCUMENT_ROOT'].'/../www_session/');

?>