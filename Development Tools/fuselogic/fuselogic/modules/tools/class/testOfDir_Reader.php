<?php
if(!class_exists('TestOfDir_reader')){
    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.dir_reader.php');

    class TestOfDir_reader extends UnitTestCase{
		
        function TestOfDir_reader(){
            $this->UnitTestCase();
        }    
		
		    function testRead_Directory(){
				    $root = dirname(__FILE__).'/modules';
				    $test = &new dir_reader();
						$check = array();
						$check[] = 'module_1';
						$check[] = 'module_2';
						$check[] = 'module_3';
						$check[] = '_module_a';
						$check[] = '_module_b';
						
						$check2 = array();
						$check2[] = 'a.php';
						$check2[] = 'b.php';
						$check2[] = 'c.php';
						
						$test->read_directory($root);
						$this->assertEqual($check,$test->get_directory(),'1');
						
						$result2 = $test->get_files();
						sort($result2);
						$this->assertEqual($check2[0],$result2[0]);
						$this->assertEqual($check2[1],$result2[1]);
						$this->assertEqual($check2[2],$result2[2]);						
						    
        }
				
    }

}		
				
?>