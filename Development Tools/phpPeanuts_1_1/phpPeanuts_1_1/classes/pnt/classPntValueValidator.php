<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

	includeClass('PntValueValidator', 'pnt');

/**  An object that checks values against its constraint settings and returns error messages.
* @see http://www.phppeanuts.org/site/index_php/Pagina/131
* 
* This abstract superclass provides behavior for the concrete
* subclass StringConverter in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @package pnt
*/
class PntValueValidator {

	var $errorReadOnly='no changes allowed';
	var $errorTooHigh='too high, max: ';
	var $errorTooLow='too low, min: ';
	var $errorTooShort='too short, min: ';
	var $errorTooLong='too long, max: ';

	var $errorInvalidEmail='invalid email address';
	var $errorInvalidType='invalid type: ';
	var $errorNotOfType='value must be a: ';
	
	var $type;
	var $readOnly;
	var $minValue;
	var $maxValue;
	var $minLength;
	var $maxLength;
	
	function PntValidator()
	{
		$this->PntObject();
	}

	//static
	function getInfiniteBig() {
		return 1.79e308;
	}
	//static
	function getInfiniteSmall() {
		return-1.79e308;
	}

	//static - return the internal format used in properties, fields and the database
	function getInternalDateFormat() {
		return "Y-m-d";
	}
	//static - return the internal format used in properties, fields and the database
	function getInternalTimestampFormat() {
		return "Y-m-d H:i:s";
	}
	//static - return the internal format used in properties, fields and the database
	function getInternalTimeFormat() {
		return "H:i:s";
	}
	//static return the internal format used in properties, fields and the database
	//internal representation is created without thousends separator
	function getInternalDecimalSeparator() {
		return '.';
	}

	//Static. only if type = 'number', maxLength may be string like '5,2' 
	function getDecimalPrecision($maxLength) 
	{
		if (!is_string($maxLength) || strlen($maxLength) == 0)
			return null;
		$arr = explode(',', $maxLength);

		if (isSet($arr[1]))
			return $arr[1];
		else
			return 0;
	}

//---------- instance methods ------------------------
	function initFromProp($prop) {
		$this->type = $prop->getType();
		$this->readOnly = $prop->getReadOnly();
		$this->minValue = $prop->getMinValue();
		$this->maxValue = $prop->getMaxValue();
		$this->minLength = $prop->getMinLength();
		$this->maxLength = $prop->getMaxLength();
	}

	function getNumberMaxValue() 
	{
		if ($this->maxValue !== null)
			return $this->maxValue;
			
		if (!is_string($this->maxLength))
			return $this->getInfiniteBig();
		
		return $this->getMaxValueFromMaxLength();
	}
	
	function getNumberMinValue() 
	{
		if ($this->minValue !== null)
			return $this->minValue;
			
		if (!is_string($this->maxLength))
			return $this->getInfiniteSmall();
		
		return -$this->getMaxValueFromMaxLength();
	}

	function getMaxValueFromMaxLength()
	{
		$arr = explode(',', $this->maxLength);
		$max = '';
		for ($i=0;$i<$arr[0];$i++)
			$max .= '9';
		if ($arr[1]) {
			$max .= '.';
			for ($i=0;$i<$arr[1];$i++)
				$max .= '9';
			return (float)$max;
		}
		return (int)$max;
	}

	function getErrorInvalidNumber() {
		$prec = $this->getDecimalPrecision($this->maxLength);
		if ($prec === null) 
			$prec = 2;
		$expected = '-4';
		if ($prec > 0) {
			$expected .= $this->decimal;
			for ($i=1; $i<=$prec; $i++)
				$expected .= $i % 10;
		}
		return $this->errorInvalidNumber . $expected;
	}

// validation methods ----------------------------------------------

	function validate(&$value) 
	{
		if ($this->readOnly) 
			return $this->errorReadOnly;

		switch ($this->type) {
			case "boolean":
				return null;
			case "number":
				return $this->validateNumber($value);
			case "string":
				return $this->validateString($value);
			case "date":
				return $this->validateDate($value);
			case "timestamp":
				return $this->validateTimestamp($value);
			case "time":
				return $this->validateTime($value);
			case "email":
				return $this->validateEmail($value);
			case "currency":
				return $this->validateNumber($value);
			default:
				if (class_exists($type))
					return $this->validateObject($value);
					
				//can not validate values of unknown type
				return $this->errorInvalidType. $type;

		}
			
	}

	function validate_min_max(&$value, $minValue, $maxValue) {
		if ($value < $minValue) 
			return $this->errorTooLow.$minValue;
		
		if ($value > $maxValue)
			return $this->errorTooHigh.$maxValue;
		
		return null;
	}
		
	function validateNumber(&$value) 
	{
		$minValue=$this->getNumberMinValue();
		$maxValue=$this->getNumberMaxValue();
		
		if ($value === null && $this->minLength > 0)
			return $this->errorTooShort.$this->minLength;
		
		if ($value === null) return null;
		
		return $this->validate_min_max($value, $minValue, $maxValue);
	}

	function validateDate(&$value) 
	{
		$minValue = ($this->minValue==null)? "0000-00-00": $this->minValue;
		$maxValue = ($this->maxValue==null)? "9999-12-31": $this->maxValue;

		if ($value === null && $this->minLength > 0)
			return $this->errorTooShort.$this->minLength;
			
		if ($value === null) return null;

		return 	$this->validate_min_max($value, $minValue, $maxValue);
	}
	
	function validateTime(&$value) {
		$minValue = ($this->minValue==null)? "00:00:00": $this->minValue;
		$maxValue = ($this->maxValue==null)? "23:59:59": $this->maxValue;
		
		if ($value === null && $this->minLength > 0)
			return $this->errorTooShort.$this->minLength;
			
		if ($value === null) return null;

		return 	$this->validate_min_max($value, $minValue, $maxValue);
	}

	function validateTimestamp(&$value) {
		$minValue = ($this->minValue==null)? "0000-00-00 00:00:00": $this->minValue;
		$maxValue = ($this->maxValue==null)? "9999-12-31 23:59:59": $this->maxValue;
		
		if ($value === null && $this->minLength > 0)
			return $this->errorTooShort.$this->minLength;
			
		if ($value === null) return null;

		return $this->validate_min_max($value, $minValue, $maxValue);
	}

	function validateEmail(&$value)
	{
		$error = $this->validateString($value);
		if ($error) 
			return $error;
//		if (!isEmailValid($value))  
		if (!eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$", $value)) 
			return $this->errorInvalidEmail;
		
		return null;
	}

	function validateString(&$value)
	{
		if ($this->minLength==null) $this->minLength=0;
		if ($this->maxLength==null) $this->maxLength=$this->getInfiniteBig();
		$len = strlen($value);
		if ($len < $this->minLength) 
			return $this->errorTooShort.$this->minLength;
		if ($len > $this->maxLength)
			return $this->errorTooLong.$this->maxLength;
		return null;
	}

	function validateObject(&$value)
	{
		if (is_ofType($value, $type)) {
			if (method_exists($value, 'validate'))
				return $value->validate($value); //static method
			else
				return $this->validateString(labelFromObject($value));
			}
		//class should be a subclass of the specified type
		//interfaces are not (yet) supported
		return $this->errorNotOfType. $type;
	}
}
?>