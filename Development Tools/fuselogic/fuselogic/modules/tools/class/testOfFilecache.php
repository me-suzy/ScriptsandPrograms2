<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.filecache.php');

    class TestOfFilecache extends UnitTestCase{
		
        function TestOfFilecache(){
            $this->UnitTestCase();
        }    
		
		    function testSaveCache(){
				    $option['directory'] = dirname(__FILE__).'/temp1';
						$option['time'] = 1 ; // one minute
						$option['id'] = 'cache1.html';
            $test = &new fileCache($option);
						$string = 'Time : ';
						$string .= date ("l dS of F Y h:i:s A");
						$test->save($string);
						$this->assertEqual($test->get($option['id']),$string);	
															
        }
				
				function testClearCache(){
				    $option['directory'] = dirname(__FILE__).'/temp1';
						$option['time'] = 60; // one minute
						
            $test = &new fileCache($option);
						$j = 5;
						for($i=1;$i<=$j;$i++){
						    $string = $i.') Time : '.date ("l dS of F Y h:i:s A");
								$option['id'] = 'cache'.$i.'.html';
								$test->setOption($option);
						    $test->save($string);
						    $this->assertEqual($test->get($option['id']),$string);							
            }	
						
						$test->clean();
						
						for($i=1;$i<=$j;$i++){
						    $option['id'] = 'cache'.$i.'.html';
								$test->setOption($option);
								$this->assertEqual($test->get($option['id']),null);	
						}										
        }				
				
				
    }		
				
?>