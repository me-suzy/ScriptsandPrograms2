<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntAssertionFailure', 'pnt/unit/notifications');

/** This class implements the assertions. Its interface is more
* extended then the PHPUnit compatible interface of PntTestCase .
* This class also can be used for assertions outside of testcases.
*
* All failures will be ignoored if global pntTestNotifier is not set.
* This alows you to put assettions in application code and only see the
* failures if you are actually testing. 
* TODO: exit assertion methods ASAP if no pntTestNotifier and 
* if continuing takes substantial time (for example to serialize objects and arrays)
*
* With gratitude to Peter van Rooijen, ( http://www.ensu.net/ )
* for lending us his idea of using static methods (originally class methods)
* for assertions, as well as for inpiration and comments
*/
class PntAssert {

	/** Check the equality of $reference and $toCheck.
	* if $precision specified, signalAssertionFailure if 
	*   abs(difference) > $precision
	* otherwise, signalAssertionFailure if not equal.
	* if $precision specified, assertNumeric $reference and $toCheck
	* @static
	* @param mixed $reference value known to be correct
	* @param mixed $toCheck value to check
	* @param string $label To recognise the asstertion from
	* @param float @precision maximum difference for numeric values
	*/
	function equals($reference, $toCheck, $label = null, $precision = null) 
	{
		if ($precision !== null) {
			if ($reference)
				Assert::numeric($reference, $label);
			if ($toCheck)
				Assert::numeric($toCheck, $label);
			
			Assert::that(abs($reference - $toCheck) <= $precision,
				'assertEquals', $reference, $toCheck, $label, $precision);
			return;
		} 
		
		//TODO: make less strict comparision for arrays and objects
		if ((is_array($reference) && is_array($toCheck) ) 
				|| (is_object($reference) && is_object($toCheck))) {
			if (serialize($reference) == serialize($toCheck)) return;
		} else {
			if ($reference == $toCheck) return;
		}
		Assert::fail('assertEquals', $reference, $toCheck, $label, $precision);
	}
	
	/** check $toCheck to be equal and of same type as $reference
	* @static
	*/
	function same($reference, $toCheck, $label = null)
	{
		if ((is_array($reference) && is_array($toCheck) ) 
				|| (is_object($reference) && is_object($toCheck))) {
			if (serialize($reference) == serialize($toCheck)) return;
		} else {
			if ($reference === $toCheck) return;
		}
		Assert::fail('assertSame', $reference, $toCheck, $label);
	}

	/** check $toCheck to reference the same object as $reference, 
	* or both be null.
	* @static
	*/
	function refSameObject(&$reference, &$toCheck, $label = null)
	{
		if ($reference === null && $toCheck === null)
			return;
			
		if (!(is_object($reference) && is_object($toCheck)))
			trigger_error('arguments must be objects', E_USER_WARNING);
    	//the name gbplxzsjj is arbitrary but should be an unlikely name
   		if(isSet($reference->gbplxzsjj) || isSet($toCheck->gbplxzsjj))
      		trigger_error('copy checking field name collision', E_USER_WARNING);
   		$reference->gbplxzsjj = true;
   		$result = isSet($toCheck->gbplxzsjj);
   		unSet($reference->gbplxzsjj);
   		
   		if ($result) return;
		Assert::fail('assertRefSame', $reference, $toCheck, $label);
	}

		
	/** check $toCheck not to be equal and of same type as $reference
	* @static
	*/
	function notSame($reference, $toCheck, $label = null)
	{
		if ((is_array($reference) && is_array($toCheck) ) 
				|| (is_object($reference) && is_object($toCheck))) {
			if (serialize($reference) !== serialize($toCheck)) return;
		} else {
			if ($reference !== $toCheck) return;
		}
		
		Assert::fail('assertNotSame', $reference, $toCheck, $label);
	}
	
	/** check $toCheck to be equal and of same type as null
	* @static
	*/
	function null($toCheck, $label = null)
	{
		Assert::that($toCheck === null, 'assertNull', null, $toCheck, $label);
	}
	
	/** check $toCheck not to be equal and of same type as null
	* @static
	*/
	function notNull($toCheck, $label = null)
	{
		Assert::that($toCheck !== null, 'assertNotNull', null, $toCheck, $label);
	}
	
	/** check $toCheck to be equal or converted to true
	* @static
	*/
	function true($toCheck, $label = null)
	{
		Assert::that($toCheck, 'assertTrue', true, $toCheck, $label);
	}
	
	/** check $toCheck to be equal or converted to false
	* @static
	*/
	function false($toCheck, $label = null)
	{
		Assert::that(!$toCheck, 'assertFalse', false, $toCheck, $label);
	}

// End of PHPUnit_Assert compatible interface 

	/** check $expression to preg_match for $toCheck 
	* @static
	*/
	function preg_match($expression, $toCheck, $label = null)
	{
		Assert::that(preg_match($expression, $toCheck), 
			'assertPreg_match', $expression, $toCheck, $label);
	}
	
	/** check the $toCheck to be within $type 
	* for objects is_a is used, for $type = number is_numeric.
	* otherwise type == get_type 
	* PS: is_numeric is locale dependent,
	* this is consistent with implicit type conversion
	* @static
	*/
	function ofType($type, $toCheck, $label = null) 
	{
		Assert::that(is_ofType($toCheck, $type),
			'assertOfType', $type, $toCheck, $label);
	}
	
	/** check the $toCheck to be within one of the $allowedTypes
	* further @see assertOfType
	* @static
	*/
	function ofAnyType($alowedTypes, $toCheck, $label = null) 
	{
		reset($alowedTypes);
		while (list($key, $type) = each($alowedTypes))
			if (is_ofType($toCheck, $type)) return;
			
		Assert::fail('assertOfAnyType', $alowedTypes, $toCheck, $label);
	}
	
	/** check $toCheck to be numeric - what is 'numeric' depends on
	* the setLocale stetings, but so does the automatic type conversion,
	* so this method will assert that $toCheck will properly convert to a number.
	* @static
	*/
	function numeric($toCheck, $label = null) 
	{
		Assert::that(is_numeric($toCheck),
			'assertNumeric', 'is_numeric', $toCheck, $label);
	}

	/** check $toCheck to be or be converted to true.
	* This is a low level method that can be used for assertions that are not 
	* available in this class. For example if you write:
	* Assert:: that($toCheck > $reference, 'assertLarger', $reference, $toCheck)
	* an eventuial failure will show up in pntUnit signaling that 'assertLarger' has failed
	* @static
	*/
	function that($toCheck, $asserion, $reference, $wasChecked, $label = null, $precision = null)
	{
		if ($toCheck) return;
		
		Assert::fail($asserion, $reference, $wasChecked, $label, $precision);
	}

	/** Signal that the assertion specified by the paramters has failed
	* All failures will be ignoored if global pntTestNotifier is not set.
	* This alows you to put assettions in application code and only see the
	* failures if you are actually testing. 
	* @static
	*/
	function fail($assertion, $reference, $toCheck, $label, $precision = null) 
	{
		// should be done first thing in assertions
		global $pntTestNotifier;
		if (!isSet($pntTestNotifier) ) return;
		
		$event =& new PntAssertionFailure(
			$assertion, $reference, $toCheck, $label, $precision);
			
		$pntTestNotifier->event($event);
	}
	
	/** Requires PhpPeanuts
	* assert each property value of both objects to be equal
	* @param PntObject $refObj object with properties whose values are known to be correct
	* @param &$obj, object to check
	* @param $label To recognise the asstertion from
	*/
	function propertiesEqual(&$refObj, &$obj, $label = '')
	{
		$clsDes =& $refObj->getClassDescriptor();

		$this->assertNotNull($obj, $label);
		$this->assertEquals($refObj->getClass(), $obj->getClass(), "className $label");
		
		$props =& $clsDes->getPropertyDescriptors(); 
		$count=0;
		while (list($propName) = each($props)) {
			$this->assertEquals(
				$refObj->get($propName)
				, $obj->get($propName)
				, "$propName $label"
			);
			$count ++;
			if ($count>100) 
				die("<BR>$label to many properties? $propName");
		}
	}		
}
?>