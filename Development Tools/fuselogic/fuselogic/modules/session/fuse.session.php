<?php
SingletonQueue();
ini_set('session.save_path',$MAIN_SETTING->get('session_directory'));
//ini_set('session.save_path',dirname($_SERVER['DOCUMENT_ROOT']).'/www_session');
ini_set('session.cookie_lifetime',300);
require_once 'function.presistence_object.php';

?>