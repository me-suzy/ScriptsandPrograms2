<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
    require_once(dirname(__FILE__).'/class.printer_friendly.php');

    class TestOfPrinter_friendly extends UnitTestCase{
		
        function TestOfPrinter_friendly(){
            $this->UnitTestCase();
        }  								
		    			
				function testImage_src_strip(){				    
				    $string = '<img src="http://www.haltebis.com/priv_stats/test.gif" alt="test">';	
						$result = '';											
            $test = &new printer_friendly();												
						$this->assertEqual($result,$test->img_src_strip($string));																										
        }						
				
				function testImage_strip(){				    
				    $string = '<img border="3" src="http://www.haltebis.com/priv_stats/test.gif" alt="test">';	
						$result = '';											
            $test = &new printer_friendly();												
						$this->assertEqual($result,$test->img_strip($string));																										
        }		
				
				function testFont_strip(){				    
				    $string = '<font color="#cc9900" size="3">how do you do?</font>';	
						$result = 'how do you do?';											
            $test = &new printer_friendly();												
						$this->assertEqual($result,$test->font_strip($string));																										
        }
				
				function testColor_strip(){				    
				    $string = '<font bgcolor="#cc9900" size="3">how do you do?</font>';	
						$result = '<font size="3">how do you do?</font>';											
            $test = &new printer_friendly();												
						$this->assertEqual($result,$test->color_strip($string));	
						
						$string = '<font bgcolor=\'#cc9900\' size="3">how do you do?</font>';	
						$this->assertEqual($result,$test->color_strip($string));																										
        }
				
				function testTag_strip(){
				    $string = '<font bgcolor="#cc9900" size="3">how do you do?</font>';	
						$result = 'how do you do?';
						$test = &new printer_friendly();		
						$this->assertEqual($result,$test->tag_strip($string,'font'));
						
				}	
				function testTag_inside_strip(){
				    $string = 'test1 <span style="background-color: #6699cc"><font size="2"><b>cari cl Istri</b></font></span>test2';	
						$string .= 'test3 <span style="background-color: #6699cc"><font size="2"><b>cari cl Istri</b></font></span>test4';							
						$result = 'test1 test2test3 test4';
						$test = &new printer_friendly();		
						$this->assertEqual($result,$test->tag_inside_strip($string,'span'));
						
				}
				function testAttribute_strip(){
				    $string = '<span style="background-color: #6699cc"><b>weferwefwef</b></span>';
				    $result = '<span><b>weferwefwef</b></span>';
						$test = &new printer_friendly();		
						//$this->assertEqual($result,$test->attribute_strip($string,'style'));
						
				}	
				
    }		
				
?>