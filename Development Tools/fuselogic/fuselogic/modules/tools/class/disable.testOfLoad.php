<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.load.php');		
    				
    class TestOfLoad extends UnitTestCase{
		
        function TestOfLoad(){
            $this->UnitTestCase();
        }    
		
		    function testFunction(){		
						$this->assertFalse(LOAD::_Function('test1'));		    
						$this->assertTrue(LOAD::_Function('test'));																				
        }
				function testClass(){	
				    $this->assertFalse(LOAD::_Class('test1'));					    
						//$this->assertTrue(LOAD::_Class('test'));				
						//$this->assertFalse(LOAD::_Class('test'));													
        }
									
    }
	
				
?>