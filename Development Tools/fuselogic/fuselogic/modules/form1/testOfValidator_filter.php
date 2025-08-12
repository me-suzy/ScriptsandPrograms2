<?php

    if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',$_SERVER['DOCUMENT_ROOT'].'/unittest/simpletest/1.0.b1/');
    }
    require_once(SIMPLE_TEST.'unit_tester.php');
    require_once(SIMPLE_TEST.'reporter.php');
		require_once(dirname(__FILE__).'/class.validator.php');    
    require_once(dirname(__FILE__).'/class.validator_filter.php');

    class TestOfValidateFilter extends UnitTestCase{
		
        function TestOfValidateFilter(){
            $this->UnitTestCase();
        }    
		
		    function testCheck1(){
				
				    $string1 = 'abcd ijaid Sex idis sidis';
						$badwords = array();
						$badwords[] = 'seX';
						
					  $field_name = 'description';
						
			      $test = new ValidateFilter($string1,$field_name,$badwords);														
						$this->assertTrue($test->isBadWords1($test->string,$test->message,$test->badwords));
						
						$string2 = 'abcd ijaid ex idis sidis';
						$this->assertFalse($test->isBadWords1($string2,$test->message,$test->badwords));								
														
        }
				
				function testCheck2(){
				
				    $string1 = 'abcd-ijaid_Sex-idis*sidis';
						$badwords = array();
						$badwords[] = 'seX';
						
					  $field_name = 'description';
						
			      $test = new ValidateFilter($string1,$field_name,$badwords);														
						$this->assertTrue($test->isBadWords2($test->string,$test->message,$test->badwords));
						
						$string2 = 'abcd ijaid ex idis sidis';
						$this->assertFalse($test->isBadWords2($string2,$test->message,$test->badwords));								
														
        }					
				
				function testCheck3(){
				
				    $string1 = 'abcd-ijaid_Sex-idis*sidis';
						$badwords = array();
						$badwords[] = 'seX';
						
					  $field_name = 'description';
						
			      $test = new ValidateFilter($string1,$field_name,$badwords);														
						$this->assertTrue($test->isBadWords3($test->string,$test->message,$test->badwords));
						
						$string2 = 'abcd ijaid ex idis sidis';
						$this->assertFalse($test->isBadWords3($string2,$test->message,$test->badwords));								
														
        }		
				
				function testCheck4(){
				
				    $string1 = 'abcd-ijaid_ Sex -idis*sidis';
						$badwords = array();
						$badwords[] = 'seX';
						
					  $field_name = 'description';
						
			      $test = new ValidateFilter($string1,$field_name,$badwords);														
						$this->assertTrue($test->isBadWords4($test->string,$test->message,$test->badwords));
						
						$string2 = 'abcd ijaid ex idis sidis';
						$this->assertFalse($test->isBadWords4($string2,$test->message,$test->badwords));								
														
        }		
				
				function testCheck5(){
				
				    $string1 = 'abcd-ijaid_ Sex -idis*sidis';
						$string1 .= ' kosong satu dua tiga empat lima enam tujuh delapan sembilan abcd-ijaid_ Sex -idis*sidis';
						
						$badwords = array();
						$badwords[] = '0123456789';
						
					  $field_name = 'description';
						
			      $test = new ValidateFilter($string1,$field_name,$badwords);														
						$this->assertTrue($test->isBadWords5($test->string,$test->message,$test->badwords));
						
						$string2 = 'abcd ijaid ex idis sidis';
						$this->assertFalse($test->isBadWords5($string2,$test->message,$test->badwords));								
														
        }				
				
				
				function testCheck(){
				
				    $string1 = 'abcd-ijaid_ Sex -idis*sidis';
						$string1 .= ' kosong satu dua tiga empat lima enam tujuh delapan sembilan abcd-ijaid_ Sex -idis*sidis';
						
						$badwords = array();
						$badwords[] = '0123456789';
						
					  $field_name = 'description';
						
			      $test = new ValidateFilter($string1,$field_name,$badwords);														
						$this->assertTrue($test->isBadWords($test->string,$test->message,$test->badwords));
						
						$string2 = 'abcd ijaid ex idis sidis';
						$this->assertFalse($test->isBadWords($string2,$test->message,$test->badwords));								
														
        }				
				
    }		
				
?>