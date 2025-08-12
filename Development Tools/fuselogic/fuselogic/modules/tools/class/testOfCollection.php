<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.collection1.php');
    
    class TestOfCollection extends UnitTestCase{
		
        function TestOfCollection(){
            $this->UnitTestCase();
        }    
		
		    function testNext1(){			
				    $array[] = '1';
						$array[] = '2';
						$array[] = '3';
							    
				    $test = &new collection1($array);
						
						$check = (string)$test->next();
						$this->assertEqual('1',$check);
						
						$check = (string)$test->next();
						$this->assertEqual('2',$check);
						
						$check = (string)$test->next();
						$this->assertEqual('3',$check);
						
						$check = (string)$test->next();
						$this->assertEqual(NULL,$check);
						
						$test = &new collection1($array);
						$i = 0;
						while($check = $test->next()){
						    $check = (string)$check;
						    $this->assertEqual($array[$i],$check);
								$i++;
						}						
														
        }
				
				function testNext2(){			
				    $array['a'] = '1';
						$array['b'] = '2';
						$array['c'] = '3';
							    
				    $test = &new collection1($array);
						
						$check = (string)$test->next();
						$this->assertEqual('1',$check);
						
						$check = (string)$test->next();
						$this->assertEqual('2',$check);
						
						$check = (string)$test->next();
						$this->assertEqual('3',$check);
						
						$check = (string)$test->next();
						$this->assertEqual(NULL,$check);
																			
        }
				
    }
		
				
?>