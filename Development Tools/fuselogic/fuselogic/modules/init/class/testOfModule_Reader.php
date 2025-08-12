<?php
if(!class_exists('TestOfModule_reader')){
    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.module_reader.php');

    class TestOfModule_reader extends UnitTestCase{
		
        function TestOfModule_reader(){
            $this->UnitTestCase();
        }    
		
		    function testStart(){
				    $test = &new module_reader();
						$check = "<?php\n\n";				
						$test->start();		
						$this->assertEqual($check,$test->get_text());
        }
				
				function testEnd(){
				    $test = &new module_reader();
						$check = "\n?>";				
						$test->End();		
						$this->assertEqual($check,$test->get_text());
        }
				
				function testAddArray(){
				    $test = &new module_reader();
						$fl_module = array();
						$fl_module['module1'] = 'path1';
						$fl_module['module2'] = 'path2';
						$fl_module['module3'] = 'path3';
						
						$check = '$fl_module[\'module1\']=\'path1\';'."\n";
						$check .= '$fl_module[\'module2\']=\'path2\';'."\n";
						$check .= '$fl_module[\'module3\']=\'path3\';'."\n";
										
						$test->add_array($fl_module);		
						$this->assertEqual($check,$test->get_text());				
				}			
					
				function testFile(){
				    $test = &new module_reader('test_circuits.php');
						$fl_module = array();
						$fl_module['module1'] = 'path1';
						$fl_module['module2'] = 'path2';
						$fl_module['module3'] = 'path3';
						
						$check = '$fl_module[\'module1\']=\'path1\';'."\n";
						$check .= '$fl_module[\'module2\']=\'path2\';'."\n";
						$check .= '$fl_module[\'module3\']=\'path3\';'."\n";
						
						$test->start();				
						$test->add_array($fl_module);		
						$test->end();
						$test->file_put(dirname(__FILE__).'/'.$test->file_name,$test->get_text());						
				}						
				
				
    }		
}				
?>