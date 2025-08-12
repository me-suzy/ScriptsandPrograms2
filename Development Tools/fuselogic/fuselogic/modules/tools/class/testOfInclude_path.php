<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.include_path.php');		

    class TestOfInit extends UnitTestCase{
		
        function TestOfInit(){
            $this->UnitTestCase();
        }    
		
		    function testGet_setting(){
				    $test = &new include_path();
						$check = ini_get('include_path');
						$check = str_replace('\\','/',$check);
						$this->assertEqual($check,$test->get_setting());						
        }
				
				function testLink(){
				    $test = &new include_path();
						$directory = getcwd().'/tools';
						$check = ini_get('include_path').$test->_separator.$directory;
						$check = str_replace('\\','/',$check);
						$test->link($directory);
						$this->assertEqual($check,$test->get_setting());						
        }
				
				function testUnLink(){
				    $test = &new include_path();
						$directory = getcwd().'/tools';
						$directory1 = str_replace('\\','/',$directory);
						$check = ini_get('include_path');
						$check = str_replace('\\','/',$check);
						$check = str_replace($test->_separator.$directory1,'',$check);
						
						$test->unlink($directory);
						$this->assertEqual($check,$test->get_setting());							
										
        }
				
				function testClear(){
				    $test = &new include_path();
						$directory = getcwd().'/tools';
						$directory1 = str_replace('\\','/',$directory);
						$check = ini_get('include_path');
						$check = str_replace('\\','/',$check);
						$check = str_replace($test->_separator.$directory1,'',$check);
						
						$test->clear();
						$this->assertEqual('.',$test->get_setting());							
										
        }
							
    }
		
				
?>