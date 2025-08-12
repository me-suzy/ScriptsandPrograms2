<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.version.php');		

    class TestOfVersion extends UnitTestCase{
		
        function TestOfVersion(){
            $this->UnitTestCase();
        }    
		
		    function test1(){
				    $test = &new php_version('4.4.3');						
						$this->assertTrue($test->less_than('5.0.0'));			
						$this->assertFalse($test->less_than('4.4.2'));						
						$this->assertTrue($test->equal('4.4.3'));			
						
						$test->php_version('5.0.0RC3');
						$this->assertFalse($test->less_than('5.0.0'));								
        }
				
											
    }		
				
?>