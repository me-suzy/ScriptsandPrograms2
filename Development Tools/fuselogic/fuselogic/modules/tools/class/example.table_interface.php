<?php

require_once('class.table_interface.php');
$setting = array();
$setting['dsn'] = 'mysql://root:please@localhost/ekobudi';
//$setting['table_name'] = 'haltebis_classifad';
$setting['table_name'] = 'autopub';
$setting['table_name'] = 'on_line_users';

$test = &new table_interface($setting);

$test->_getPK();
echo $test->primary_key_name;

?>