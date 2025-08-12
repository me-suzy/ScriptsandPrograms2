<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.string.php');

    class TestOfString extends UnitTestCase{
		
        function TestOfString(){
            $this->UnitTestCase();
        }  								
		    			
				function test_first_position(){				    
				    $string = '012.gif.89.gif';	
						$match = 'gif';											
            $test = &new _string();			
						$result = $test->first_position($match,$string);									
						$this->assertEqual(4,$result['left']);		
						$this->assertEqual(6,$result['right']);																									
        }						
				function test_first_position_i(){				    
				    $string = '012.gif.89.gif';	
						$match = 'gIf';											
            $test = &new _string();			
						$result = $test->first_position_i($match,$string);									
						$this->assertEqual(4,$result['left']);		
						$this->assertEqual(6,$result['right']);																									
        }	
				/*
		    function test_last_position(){				    
				    $string = '012.gif.89.gif';	
						$match = 'gif';											
            $test = &new _string();			
						$result = $test->last_position($match,$string);									
						$this->assertEqual(11,$result['left']);		
						$this->assertEqual(13,$result['right']);																									
        }	
				function test_last_position_i(){				    
				    $string = '012.gif.89.gIF';	
						$match = 'gif';											
            $test = &new _string();			
						$result = $test->last_position_i($match,$string);									
						$this->assertEqual(11,$result['left']);		
						$this->assertEqual(13,$result['right']);																									
        }	
				*/
				function test_exists(){				    
				    $string = '012.Gif.89.gIF';	
						$match = 'gif';											
            $test = &new _string();			
						$this->assertFalse($test->exists($match,$string));		
						$this->assertTrue($test->exists_i($match,$string));		
												
						$string = '012.gif.89.gIF';	
						$this->assertTrue($test->exists($match,$string));		
						$match = 'giff';						
						$this->assertFalse($test->exists_i($match,$string));
																													
        }
				
				function test_get(){				    
				    $string = '012.gif.89.gif';	
						$match = 'gif';											
            $test = &new _string();			
						
						$left = 4;
						$right = 6;					
						$this->assertEqual('gif',$test->get($left,$right,$string));		
						
						$left = 11;
						$right = 13;			
						$this->assertEqual('gif',$test->get($left,$right,$string));		
																																																		
        }	
				function test_reverse(){				    
				    $string = '0123456789 0123456789';	
						$result = '9876543210 9876543210';											
            $test = &new _string();						
						$this->assertEqual($result,$test->reverse($string));								
																																																		
        }	
				
    }		
				
?>