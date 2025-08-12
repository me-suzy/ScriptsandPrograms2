<?php

    if(!defined(SIMPLE_TEST)){
        define(SIMPLE_TEST,$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.state.php');
		

    class TestOfState extends UnitTestCase{
		
        function TestOfState(){
            $this->UnitTestCase();
        }    
		
		    function testState1(){				    
				    $test = &new state();
				    $this->assertEqual(0,$test->getState());
						$this->assertEqual(1,$test->changeState());
						$this->assertEqual(1,$test->getState());
						$this->assertEqual(0,$test->changeState());																		
        }
				function testState2(){			
				    $setting['number_of_state'] = 5;	    
				    $test = &new state($setting);
				    $this->assertEqual(0,$test->getState());
						$this->assertEqual(1,$test->changeState());
						$this->assertEqual(2,$test->changeState());					
						$this->assertEqual(3,$test->changeState());		
						$this->assertEqual(4,$test->changeState());					
						$this->assertEqual(0,$test->changeState());																						
        }			
    }
		
				
?>