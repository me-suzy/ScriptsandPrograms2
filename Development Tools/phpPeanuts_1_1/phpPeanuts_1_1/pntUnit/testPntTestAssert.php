<HTML>
<HEAD>
</HEAD>
<BODY>

<?php
	
require_once('../classes/pnt/unit/classPntTestNotifier.php');
// generalFunctions included by PntTestResult
require_once('../classes/pnt/test/unit/classObjectToTest.php');

includeClass('Assert');

$pntTestNotifier =& new PntTestNotifier();

	testSucceed();
	
print "================================================================";

	testFail();
	
    function testSucceed() {

		$obj1 =& new ObjectToTest();
    	$obj2 =& new ObjectToTest();
    	$obj2->var1 = 'value of obj2->var1 explicitly set by testSucceed';

     	Assert::false(false, 'false');
		Assert::true(true, 'true');

		Assert::null(null, 'null');
		Assert::notNull(123, '123');

	   	Assert::equals('123.0', 123, 'mixed');
    	Assert::equals(2, 2, 'with numbers');
    	Assert::equals('yes', 'yes', 'with Strings');
    	Assert::equals($obj1, $obj1, 'with objects');

		Assert::notSame('12', 12, '12');
		Assert::same($obj1, $obj1, '$obj1');

		Assert::preg_match('~.php~', 'myFile.php', 'RegExp');
		
		Assert::ofType('integer', 123);
		Assert::ofType('string', '123');
		Assert::ofType('number', 123);
		Assert::ofType('number', '123');
		Assert::ofType('NULL', null);
		Assert::ofType('ObjectToTest', $obj1);
		
		Assert::ofAnyType(array('integer', 'boolean'), false);
    }

    function testFail() {

		$obj1 =& new ObjectToTest();
    	$obj2 =& new ObjectToTest();
    	$obj2->var1 = 'value of obj2->var1 explicitly set by testFail';

    	Assert::equals('yes', 123, 'mixed');
    	Assert::equals(2, 4, 'with numbers');
    	Assert::equals('yes', 'no', 'with Strings');
    	Assert::equals($obj1, $obj2, 'with objects');

		Assert::notNull(null, 'null');
		Assert::null(123, '123');

		Assert::same('12', 12, '12');
		Assert::notSame($obj1, $obj1, '$obj1');

     	Assert::true(false, 'false');
		Assert::false(true, 'true');

		Assert::preg_match('~.php~', 'myFile.txt', 'RegExp');
     	
		Assert::ofType('boolean', 1);
		Assert::ofType('string', 123);
		Assert::ofType('number', '123,45');
		Assert::ofType('integer', null);
		Assert::ofType('object', $obj1);
		Assert::ofType('ObjectToTest', $this);
		
		Assert::ofAnyType(array('integer', 'boolean'), '12');
    }

?>
</BODY>
</HTML>