<?php

    if(!defined(SIMPLE_TEST)){
        define(SIMPLE_TEST,$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/../class.layout.php');

    class TestOfLayout extends UnitTestCase{
		
        function TestOfLayout(){
            $this->UnitTestCase();
        }    
		
		    function testSetLayoutAndGetLayout(){
            $test = &new FLLayout();
						$string = 'I am just string';
						$test->setLayout('html',$string);
            $this->assertEqual($test->getLayout('html'),$string);												
        }
												
    }
		
				
?>