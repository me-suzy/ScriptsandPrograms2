<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
		require_once(dirname(__FILE__).'/class.validator.php');
    require_once(dirname(__FILE__).'/class.validator_email.php');

    class TestOfValidator_Email extends UnitTestCase{
		
        function TestOfValidator_Email(){
            $this->UnitTestCase();
        }    
		
		    function testCheck_Name(){
			      $test = &new ValidateEmail('webmaster@haltebis.com');						
						$this->assertTrue($test->check_name());						
						
        }
				
				function testCheck_Exists(){
				    $test = &new ValidateEmail('webmaster@haltebis.com');						
						
						$result = $test->check_exists('webmaster@haltebis.com');
						$this->assertTrue($result[0]);
						
						$result = $test->check_exists('blablablabla@google.com');
						$this->assertFalse($result[0]);
				
				}
				
								
    }
		
				
?>