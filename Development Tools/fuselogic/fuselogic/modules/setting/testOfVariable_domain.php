<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.variable_domain.php');		

    class TestOfVariable_domain extends UnitTestCase{
		
        function TestOfVariable_domain(){
            $this->UnitTestCase();
        }    
		
		    function testSet_and_Get1(){
				  	$test = new variable_domain();						
						$test->set('mysql_host','localhost');
						$this->assertEqual('localhost',$test->get('mysql_host'));										
        }
				
				function testSet_and_Get2(){
				    $test = new variable_domain();										
						$test->set('MYSQL_host','localhost');
						$this->assertEqual('localhost',$test->get('MYsql_host'));						
        }		
					
				function testSet_and_Get3(){
				    $test = variable_domain::singleton();									
						$test->set('mysql_username','eko');
						$this->assertEqual('eko',$test->get('mysql_username'));										
        }	
						
				function testSet_and_Get4(){
				    $test1 = variable_domain::singleton();								
						$this->assertEqual('eko',$test1->get('mysql_username'));										
        }
				
				function testSet_and_Get5(){
				    $test1 = variable_domain::singleton();								
						$this->assertTrue($test1->defined('mysql_username'));										
        }
				function testDomain(){
				    $test1 = variable_domain::singleton('test');								
						$this->assertTrue('test',$test1->domain);										
        }
    }		
				
?>