<?php
setcookie('test_path',dirname(__FILE__),time()+3600,'/');
Location(WebPath('unit_test').'/fuselogic_test.php');
?>