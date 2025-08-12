<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.factory.php');		
    				
    class TestOfFactory extends UnitTestCase{
		
        function TestOfFactory(){
            $this->UnitTestCase();
        }    
		
		    function testLoadClass(){		
						$this->assertFalse(FACTORY::loadClass('test1'));		    
						$this->assertTrue(FACTORY::loadClass('test'));																				
        }
				
    }
	
				
?>