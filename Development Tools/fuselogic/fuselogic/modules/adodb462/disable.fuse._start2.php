<?php
singletonQueue();
//$session_type = 'data_base';

if(@$session_type == 'data_base'){
if(!function_exists('sid')){   
	 
   ini_set('session.save_handler','user');		 
	  
   $ADODB_SESSION_DRIVER = 'mysql';
   $ADODB_SESSION_CONNECT = $MAIN_SETTING->get('MYSQL_HOST');	 
   $ADODB_SESSION_USER = $MAIN_SETTING->get('MYSQL_USER');
   $ADODB_SESSION_PWD = $MAIN_SETTING->get('MYSQL_PASSWORD');
   $ADODB_SESSION_DB = $MAIN_SETTING->get('MYSQL_DATABASE');
   $ADODB_SESSION_TBL = 'sessions';   
	 
   require_once('./session/adodb-session.php');
	    
	 function sid(){	
	     session_start();
	     return session_name().'='.session_id();
	 }	
}
}else{
    ini_set('session.save_path',$MAIN_SETTING->get('session_directory'));
}
?>