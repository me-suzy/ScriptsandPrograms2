<?php
ini_set('session.save_path',$_SERVER['DOCUMENT_ROOT'].'/../www_session');
ini_set('session.cookie_lifetime',300);
require_once 'function.presistence_object.php';

presistence_object('test1_object','counter');
echo '<br>test1_object->count() = '.$test1_object->count();

echo '<br>';

presistence_object('test2_object','gender');
echo '<br>test2_object->gender() = '.$test2_object->check();

echo '<h2>Presistence Object Info</h2>';
foreach($presistence_copy->list as $key=>$value){
   echo 'Instance Name = '.$key.'<br>';
	 echo 'Object Name = '.$value->object_name.'<br>';	
	 echo 'Object File = '.$value->object_file.'<br>';	
	 echo '<br>';
}

?>