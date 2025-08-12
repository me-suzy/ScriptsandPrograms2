<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/../class.q.php');

    class TestOfQ extends UnitTestCase{
		
        function TestOfQ(){
            $this->UnitTestCase();
				}    
				
				function test1(){
				    $test = &new Q('moduleX/subModuleY','noname','parentFuse');
						
						$this->assertEqual('moduleX/subModuleY',$test->fuse);
						$this->assertEqual('noname',$test->layoutName);
						$this->assertEqual('parentFuse',$test->parentFuse);
						$this->assertEqual(__FILE__,$test->FILE);
						$this->assertEqual(17,$test->LINE);
						$this->assertEqual('moduleX',$test->module);
						$this->assertEqual('subModuleY',$test->subModule);
						
				}
							
    }
	
				
?>