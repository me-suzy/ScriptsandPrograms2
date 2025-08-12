<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/../class.env.php');

    class TestOfEnv extends UnitTestCase{
		
        function TestOfEnv(){
            $this->UnitTestCase();
	}    
				
	function test1(){
	        $test = &new env();
		
		$wanted = str_replace('\\','/',getcwd());		
	        $this->assertEqual($wanted,$test->door_path);
		
		$wanted = str_replace('\\','/',dirname(dirname(__FILE__)));
		$this->assertEqual($wanted,$test->core_path);
		
		$wanted = dirname(dirname(dirname(dirname(__FILE__))));
		$wanted = str_replace('\\','/',$wanted);
		$this->assertEqual($wanted,$test->root_path);
		
		$wanted = $_SERVER['DOCUMENT_ROOT'].'///';
		$wanted = str_replace('////','',$wanted);
		$wanted = str_replace('///','',$wanted);
		$this->assertEqual($wanted,$test->document_root);
		
		
		$wanted = '/fuselogic_modules/modules/core/test/index.php/';		
		$input = '/fuselogic_modules/modules/core/test/index.php/init/home/ab/c';
		$this->assertEqual($wanted,$test->_index($input));
		
		$wanted = 'init/home';		
		$input = '/fuselogic_modules/modules/core/test/';
		$this->assertEqual($wanted,$test->_user_fuse($input));
		
		$wanted = 'init/home';		
		$input = '/fuselogic_modules/modules/core/test/index.php/';
		$this->assertEqual($wanted,$test->_user_fuse($input));
		
		$wanted = 'init/info';		
		$input = '/fuselogic_modules/modules/core/test/index.php/init/info/a/b/c';
		$this->assertEqual($wanted,$test->_user_fuse($input));
				
		$wanted = array('a','b','c');		
		$input = '/fuselogic_modules/modules/core/test/index.php/init/info/a/b/c';
		$result = $test->_uri($input);		
		$this->assertEqual(count($wanted),count($result));
		$i=0;
		foreach($wanted as $value){
		   $this->assertEqual($value,$result[$i]);
		   $i++;
		}
						
		$wanted = array();		
		$input = '/fuselogic_modules/modules/core/test/index.php/init/info';
		$result = $test->_uri($input);		
		$this->assertEqual(count($wanted),count($result));
		$i=0;
		foreach($wanted as $value){
		   $this->assertEqual($value,$result[$i]);
		   $i++;
		}
		
		$wanted = array();		
		$input = '/fuselogic_modules/modules/core/test/';
		$result = $test->_uri($input);		
		$this->assertEqual(count($wanted),count($result));
		$i=0;
		foreach($wanted as $value){
		   $this->assertEqual($value,$result[$i]);
		   $i++;
		}
				
	}
	
	function test_data(){
	   $test = &new env();
	   
	   $this->assertEqual(false,file_exists($test->root_path.'/data/temp_test'));
	   $this->assertEqual($test->root_path.'/data/temp_test',$test->data('temp_test'));
	   $this->assertEqual(true,file_exists($test->root_path.'/data/temp_test'));
	   
	   $this->assertEqual(false,file_exists($test->root_path.'/data/temp_test/test'));
	   $this->assertEqual($test->root_path.'/data/temp_test/test',$test->data('temp_test/test'));
	   $this->assertEqual(true,file_exists($test->root_path.'/data/temp_test/test'));
	   
	   $test->rmdirr($test->root_path.'/data/temp_test');	
	}
	
    }
	
				
?>