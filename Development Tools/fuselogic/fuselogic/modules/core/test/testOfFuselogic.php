<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/../class.fuselogic.php');
    require_once(dirname(__FILE__).'/../class.q.php');
		require_once(dirname(__FILE__).'/../class.env.php');

    class TestOfFuselogic extends UnitTestCase{
		
        function TestOfFuselogic(){
            $this->UnitTestCase();
        }    
		
	  function testErrorMessage(){
	      $test = &new FuseLogic();
	      $test->setErrorMessage('Error please');
              $this->assertEqual($test->errormessage,'Error please');						
				}

	
				
				function testSetCircuit(){
				    $env = new env();
				    $setting = array();
            $setting['fl_root'] = 'D:\www\haltebis.com2';
            $setting['document_root'] = 'D:/www/';
            $setting['user_command'] = '/index.php/module/submodule/var1/var2';
            $setting['door'] = 'D:\www\haltebis.com2';
            $setting['index_name'] = 'index.php';
						
						$env->core_path = 'D:/www/';
						$env->root_path = 'D:\www\haltebis.com2';
						
            $test = &new FuseLogic($env);
						$relativePath = 'moduleLocation/path1/path2';
						$test->setModule('moduleName',$relativePath);
						
						$queue['fuseaction'] = 'moduleName/main/hai/eko';
						$queue['layoutName'] = 'noname';
						$queue['ParentFuseaction'] = '';						
						$q = new q($queue['fuseaction'],$queue['layoutName'],$queue['ParentFuseaction']);
						$test->initFuse($q);	
											
            $this->assertTrue($test->isModuleExists(),'module not exists');	
						
						$realPath = $setting['fl_root'].'/'.$relativePath;
						$realPath = str_replace('\\','/',$realPath);		
																
						$this->assertEqual($test->getModulePath(),$realPath);			
						$this->assertEqual($test->getModulePath('moduleName'),$realPath);										
						$this->assertEqual($test->module,'moduleName');
						$this->assertEqual($test->getSubModule(),'main');						
						$this->assertEqual($test->LayoutName,'noname');		
						$this->assertEqual(false,$test->isHomeCircuit());		
																			
        }
				
				function testHomeCircuit(){
				    $env = new env();
				    $setting = array();
            $setting['fl_root'] = 'D:\www\haltebis.com2';
						$env->root_path = 'D:/www/haltebis.com2';            
						$env->user_fuse = 'module3/submodule';
            
				
				    $test = &new FuseLogic($env);
						$relativePath = 'moduleLocation/path1/path2';
						$test->setModule('module3',$relativePath);
						$queue['fuseaction'] = 'module3/submodule/hai/eko';
						$queue['layoutName'] = 'noname';
						$queue['ParentFuseaction'] = '';
						$q = new q($queue['fuseaction'],$queue['layoutName'],$queue['ParentFuseaction']);
						$test->initFuse($q);	          
						$this->assertEqual(true,$test->isHomeCircuit(),'1');	
						
						$relativePath = 'moduleLocation/path1/path2';
						$test->setModule('module3',$relativePath);
						$queue['fuseaction'] = 'module3/submodule3/1/2/3';
						$queue['layoutName'] = 'noname';
						$queue['ParentFuseaction'] = '';
						$q = new q($queue['fuseaction'],$queue['layoutName'],$queue['ParentFuseaction']);
						$test->initFuse($q);	          
						$this->assertEqual(false,$test->isHomeCircuit(),'2');																					
        }				
				
				
    }		
				
?>