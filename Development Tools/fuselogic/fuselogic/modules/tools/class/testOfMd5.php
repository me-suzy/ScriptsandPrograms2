<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.md5.php');
		

    class TestOfMd5 extends UnitTestCase{
		
        function TestOfMd5(){
            $this->UnitTestCase();
        }    
		
		    function testMD5(){
				    $secret = 'dfjsdjs'.rand();
				    $test = md5::factory($secret);						
						$this->assertEqual($secret,$test->getSecret());		
						
						$value = 'asdfjik;ksdjosod;'.rand();
						$encode = $test->encode($value);						
						$this->assertEqual($value,$test->decode($encode));
						
						$encode1 = '3'.$encode;
						$this->assertEqual(NULL,$test->decode($encode1));
						
						$value = md5('asdfjik;ksdjosod;'.rand());
						$encode = $test->encode($value);						
						$this->assertEqual($value,$test->decode($encode));			
							
        }
				function testMD5_2(){
				    $secret = 'dfjsdjs'.rand();
				    $test = md5::factory();
						$test->setSecret($secret);												
						$this->assertEqual($secret,$test->getSecret());		
				}		
						
				function testSHA1(){
				    $secret = 'dfjsdjs'.rand();
				    $test = md5::factory($secret,'sha1');			
						$this->assertEqual($secret,$test->getSecret());		
						
						$value = 'asdfjik;ksdjosod;'.rand();
						$encode = $test->encode($value);						
						$this->assertEqual($value,$test->decode($encode));
						
						$encode1 = '3'.$encode;
						$this->assertEqual(NULL,$test->decode($encode1));
						
						$value = md5('asdfjik;ksdjosod;'.rand());
						$encode = $test->encode($value);						
						$this->assertEqual($value,$test->decode($encode));												
							
        }
							
    }
		
				
?>