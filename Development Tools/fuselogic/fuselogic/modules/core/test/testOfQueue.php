<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/../class.queue.php');

    class TestOfQueue extends UnitTestCase{
		
        function TestOfQueue(){
            $this->UnitTestCase();
        }    
		
		    function testQueue(){
            $test = &new FLQueue();
						$test->queue('ads/category/book','noname','root/test');
						$result = $test->service();
						$check = array();
						$check['fuseaction']= 'ads/category/book';
						$check['layoutName'] = 'noname';
						$check['ParentFuseaction'] = 'root/test';
            $this->assertEqual($test->activeQueue->fuse,$check['fuseaction']);	
						$this->assertEqual($test->activeQueue->layoutName,$check['layoutName']);	
						$this->assertEqual($test->activeQueue->parentFuse,null);	
																							
        }
				
				
				function testCount(){
            $test = &new FLQueue();
						
						$test->queue('ads/category/book','noname','root/test');
						$test->queue('ads/category/book','noname','root/test');
						$test->queue('ads/category/book','noname','root/test');		
						$test->queue('ads/category/book','noname','root/test');		
						
						$result = $test->service();
						$this->assertEqual($test->count(),3);																		
        }
				
				function testFirstQueue1(){
            $test = &new FLQueue();
						
						$test->queue('ads/category/book','noname','root/test');
						$test->queue('ads/category/book','noname','root/test');
						$test->queue('ads/category/book','noname','root/test');		
						$test->FirstQueue('ads1/category1/book1','noname','root1/test1');
						
						$result = $test->service();
						$check = array();
						$check['fuseaction']= 'ads1/category1/book1';
						$check['layoutName'] = 'noname';
						$check['ParentFuseaction'] = 'root1/test1';
            $this->assertEqual($test->activeQueue->fuse,$check['fuseaction']);	
						$this->assertEqual($test->activeQueue->layoutName,$check['layoutName']);	
						$this->assertEqual($test->activeQueue->parentFuse,null);																		
        }
				
				function testSingleton(){
            $test = &new FLQueue();
						
						$test->queue('ads/category/book','noname','root/test');
						
						$test->singleton('ads/category/book');
						
						$test->queue('ads/category/book','noname','root/test');
						$test->queue('ads/category/book','noname','root/test');		
						$test->queue('ads/category/book','noname','root/test');								
						
						$this->assertEqual($test->count(),1);																		
        }
				
				function testClose(){
            $test = &new FLQueue();
						
						$test->queue('ads/category/book','noname','root/test');
						$test->queue('ads/category/book','noname','root/test');
						
						$test->close();
						
						$test->queue('ads/category/book','noname','root/test');		
						$test->queue('ads/category/book','noname','root/test');								
						
						$this->assertEqual($test->count(),2);																		
        }
				
				function testForceQueue(){
            $test = &new FLQueue();
						
						$test->queue('ads/category/book','noname','root/test');
						$test->queue('ads/category/book','noname','root/test');
						
						$test->close();
						
						$test->queue('ads/category/book','noname','root/test');		
						$test->queue('ads/category/book','noname','root/test');								
						
						$test->ForceQueue('ads/category/book','noname','root/test');		
						$test->ForceQueue('ads/category/book','noname','root/test');
						
						$this->assertEqual($test->count(),4);																		
        }
				
				function testForceFirstQueue(){
            $test = &new FLQueue();
						
						$test->queue('ads/category/book','noname','root/test');
						$test->queue('ads/category/book','noname','root/test');
						
						$test->close();
						
						$test->queue('ads/category/book','noname','root/test');		
						$test->queue('ads/category/book','noname','root/test');								
						
						$test->ForceFirstQueue('ads/category/book','noname','root/test');		
						$test->ForceFirstQueue('ads/category/book','noname','root/test');
						
						$this->assertEqual($test->count(),4);																		
        }
				
				function testOpen(){
            $test = &new FLQueue();		
													
						$test->queue('ads/category/book','noname','root/test');						
						$test->close();						
						$test->queue('ads/category/book','noname','root/test');	
																
						$this->assertEqual($test->count(),1);																		
        }			
			
    }		
				
?>