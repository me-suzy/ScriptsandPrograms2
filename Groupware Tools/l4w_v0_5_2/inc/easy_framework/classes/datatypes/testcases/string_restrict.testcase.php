<?php
/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: string_restrict.testcase.php,v 1.5 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
require_once '../../datatype.class.php';
require_once '../string_restrict.class.php';
require_once '../../extern/pear/PHPUnit/PHPUnit.php';

class String_Restrict_Test extends PHPUnit_TestCase {

    var $datatype;
	var $expectedNullAssertionThrown = false;
		
    function String_Restrict_Test($name) {
       $this->PHPUnit_TestCase($name);
    }

    function setUp() {
		global $easy;
        //$this->datatype = new easy_string_restrict (NULL);
		$easy->set_proceeding (true);
		$easy->set_assertion_function ("quiet_assertion");	
    }
	
    function tearDown() {
		global $expected_assertion;
		$expected_assertion = false;
        unset($this->datatype);
    }

    function testInitWithNull() {
		global $assertion_nr;
		
		$this->datatype = new easy_string_restrict (null, null);
		$this->assertTrue ('is_object($this->datatype)', 'Datatype is not an object');
	}

    function testNullNotAllowed() {
		global $expected_assertion;
		
		$this->datatype = new easy_string_restrict (null, null);
		$this->datatype->set_null_allowed (false);
		@$this->datatype->set (null);
		$this->assertSame (true,$expected_assertion, 'Assertion should have been thrown, but has not');		
	}

    function testNullAllowed() {
		global $expected_assertion;
		
		$this->datatype = new easy_string_restrict (null, null);
		$this->datatype->set_null_allowed (true);
		$this->datatype->set (null);
		$this->assertSame (false,$expected_assertion, 'Assertion should not have been thrown, but has');		
	}

    function testNullAllowed2() {
		global $expected_assertion;
		
		$this->datatype = new easy_string_restrict (null, null);
		$this->datatype->set_null_allowed (true);
		$res = $this->datatype->get ();
		$this->assertSame (false,$expected_assertion, 'Assertion should not have been thrown, but has');		
	}

    function testEmptyParam2() {
		global $expected_assertion;
		
		@$this->datatype = new easy_string_restrict ('abc', '');
		$this->assertSame (true,$expected_assertion, 'Assertion should have been thrown, but has not');					
	}
	
	function testNoString () {
		global $expected_assertion;

		@$this->datatype = new easy_string_restrict (1, null);
		$this->assertSame (true,$expected_assertion, 'Assertion should have been thrown, but has not');					
	}

	function testAllAllowed () {
		$teststr = '^1234567890=?asdfghZUIO,.-;:_<>{[]}';			
		$this->datatype = new easy_string_restrict ($teststr, null);
		assert ('$this->datatype->get() == $teststr');
	}
	
	function testCharRejected () {
		global $expected_assertion;
		$teststr = '^1234567890=?asdfghZUIO,.-;:_<>{[]}';			
		@$this->datatype = new easy_string_restrict ($teststr, array (','));
		$this->assertSame (true,$expected_assertion, 'Assertion should have been thrown, but has not');					
	}

	function testCharRejected2 () {
		global $expected_assertion;
		$teststr = '^1234567890=?asdfghZUIO,.-;:_<>{[]}';			
		@$this->datatype = new easy_string_restrict ($teststr, array ('|','.'));
		$this->assertSame (true,$expected_assertion, 'Assertion should have been thrown, but has not');					
	}

	function testStringRejected () {
		global $expected_assertion;
		$teststr = '^1234567890=?asdfghZUIO,.-;:_<>{[]}';			
		@$this->datatype = new easy_string_restrict ($teststr, array ('0=?a'));
		$this->assertSame (true,$expected_assertion, 'Assertion should have been thrown, but has not');					
	}
	
	function testPassMe () {
		$teststr  = '^234567890=?asdghZUIO,.-:_<>{[]}';
		$rejected = array ('1',';'); 			
		$this->datatype = new easy_string_restrict ($teststr, $rejected);
		$res = $this->datatype->get();
		$this->assertSame ($teststr, $res, $teststr.' expected, got '.$res);		
	}
	
}
?>
