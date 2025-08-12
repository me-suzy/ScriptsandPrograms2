<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObject', 'pnt');
// ValueValidator included by PntSite

/** Object of this class convert strings to values and back according to their format settings.
* All user interface String conversions are and should be delegated to StringConverters
* to make override possible. 
* 
* This abstract superclass provides behavior for the concrete
* subclass StringConverter in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @package pnt/web
*/
class PntStringConverter extends PntObject {

	var $true='true';
	var $false='false';
	var $dateFormat="Y-m-d"; //as to be shown in user interface. 
	// ! Currently not used for parsing date strings from the user interface,
	// promat from locale is used. dateFormat must correspond to locale!
	var $timestampFormat="Y-m-d H:i:s"; //as shown in user interface. Default is same as properties value
	var $timeFormat="H:i:s"; //as shown in user interface. Default is same as properties value
	var $decimal='.'; // decimal separator
	var $thousends=','; //thousends separator
	var $charset = 'ISO-8859-1';  
	var $usKbSupport4Uropean = false; //numeric keypad has only a dot key...
	
	var $errorInvalidNumber='invalid number, expected is like: ';
	var $errorInvalidDate='invalid date, expected is like: ';
	var $errorInvalidTimestamp='invalid timestamp, expected is like: ';
	var $errorInvalidTime='invalid time, expected is like: ';
	var $errorInvalidBoolean='invalid boolean, expected: ';
	var $errorInvalidType='invalid type: ';

	var $type;
	var $decimalPrecision = 2;
	
	var $error; //if not null an error occurred

	function PntStringConverter()
	{
		$this->PntObject();
	}

	// static - return supported separators for date, time and timestamp
	function getTimeStampSeparators() {
		return '- :/.';
	}

	/** works for date, time and timestamp, 
	* only supports numeric date and time elements, 
	* so no monthnames, daynames and no AM/PM
	* @static
	* @param $string String containing date and/or time 
	* @param $format the format $string should be in
	* @param $type String either 'date', 'time' or 'timestamp'.
	* @return String containing date and/or time in the internal format corrensponding to $type
	*/
	function convertDT($string, $format, $type)
	{
		switch ($type) {
			case 'date':
				$internalFormat = ValueValidator::getInternalDateFormat();
				break;
			case 'timestamp':
				$internalFormat = ValueValidator::getInternalTimestampFormat();
				break;
			case 'time':
				$internalFormat = ValueValidator::getInternalTimeFormat();
				break;
			default:
				trigger_error("unsupported type: $type", E_USER_ERROR);
		}

		$inputArray = StringConverter::splitDT($string, $format);
		while (list($key, $value) = each($inputArray)) {
			// check numeric components to be numeric
			if (strPos($key, 'aADFlMST') === false && !is_numeric($value))
				return false; 
			//add leading zero's, but check for numbers too large
			$denominator = $key=='Y' ? 10000: 100;
			if (($value % $denominator) != (int) $value)
				return false;
			$resultArray[$key] = substr($value + $denominator, 1);
		}

		//check resultArray content Y-m-d H:i:s
		if ($type != 'time' && !checkdate($resultArray['m'], $resultArray['d'], $resultArray['Y']))
			return false;
		if ($type != 'date' && !$this->checktime($resultArray['H'], $resultArray['i'], $resultArray['s']))
			return false;

		// create internalformat formatted string
		return StringConverter::formatDT($resultArray, $internalFormat);
	}

	/** Split date, time or timeStamp, answer array with keys from format
	* Limitation: only works for formats using Y, m, d, H, i and/or s 
	*    and separators from getTimeStampSeparators()
	* @static
	* @param String date, time or timestamp String
	* @param String $format
	* @return Array of String
	*/
	function splitDT($value, $format)
	{
		$expr = '['.StringConverter::getTimeStampSeparators().']';
		$arr = split($expr, $value);
		$formatArray = split($expr, $format);
		for ($i=0; $i<count($formatArray); $i++) 
			$result[$formatArray[$i]] = isSet($arr[$i]) ? $arr[$i] : '';
		return $result;
	}	

	/** Format date, time or timestamp String from Array
	* Limitation: only works for formats that require no conversion of
	*   elements, in practice these are only using Y, m, d, H, i and/or s
	* @static
	* @param Array of String $dtArray with keys from format
	* @param String $format
	* @return String
	*/
	function formatDT($dtArray, $format)
	{
		$result = $format;
		while (list($key, $value) = each($dtArray)) 
			$result = str_replace($key, $value, $result);
				
		return $result;
	}

	// static
	function checkTime($hours, $minutes, $seconds) {
		return $hours >= 0 
			&& $hours < 24
			&& $minutes >= 0
			&& $minutes < 60
			&& $seconds >= 0
			&& $seconds < 60;
	}

	function getErrorInvalidNumber() {
		$prec = $this->decimalPrecision;
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

	function initFromProp($prop) {		
		$this->type = $prop->getType();
		$this->decimalPrecision = ValueValidator::getDecimalPrecision($prop->getMaxLength());
	}

// methods converting to user interface string ---------------------------
	
	function toHtml($string)
	{					
		return htmlentities($string, ENT_QUOTES, $this->charset);
	}
	
	function toLabel(&$value, $type) 
	{	
		if ($value === null)
			return '';
		switch ($type) {
			case "boolean":
				return $this->labelFromBoolean($value);
			case "number":
				return $this->labelFromNumber($value);
			case "string":
				return $this->labelFromString($value);
			case "date":
				return $this->labelFromDate($value);
			case "timestamp":
				return $this->labelFromTimestamp($value);
			case "email":
				return $this->labelFromString($value);
			case "currency":
				return $this->labelFromNumber($value);
			case "time":
				return $this->labelFromTime($value);
			default:
				if ( is_object($value) ) 
					return $this->labelFromObject($value);
				else
					return "$value";
		}
	}
	
	function labelFromBoolean(&$value) {
		if ($value)
			return $this->true;
		else
			return $this->false;
	}

	function labelFromNumber(&$value) 
	{
		// bring in line with decimal precision
		$prec = $this->decimalPrecision;

		if ($prec!==null) {
			$value = round($value, $prec); //otherwise it gets trucated
			return number_format($value, $prec, $this->decimal, $this->thousends);
		}
		
		$arr =  explode(ValueValidator::getInternalDecimalSeparator(), "$value");
		$string = number_format($arr[0], 0, $this->decimal, $this->thousends);
		if (isSet($arr[1])) 
			$string .= $this->decimal. $arr[1];
			
		return $string;
	}

	function labelFromDate(&$value) 
	{
		$arr =& $this->splitDT($value, ValueValidator::getInternalDateFormat());
		if ($arr['Y'] == 0)
			return '';
		if ($arr['Y'] > 1972)  // ;-(((
			return date($this->dateFormat, strtotime($value)); //MC: changing this may break Aurora lastUpdated column (uses internalDormat 'Ymd')
		else
			return $this->formatDT($arr, $this->dateFormat); //has its limitations too, but not the year limit
	}

	function labelFromTime(&$value) 
	{
		$arr =& $this->splitDT($value, ValueValidator::getInternalTimeFormat());
		return date($this->timeFormat, strtotime($value));
	}

	function labelFromTimestamp(&$value) 
	{
		$arr =& $this->splitDT($value, ValueValidator::getInternalTimestampFormat());
		if ($arr['Y'] > 1973)
			return date($this->timestampFormat, strtotime($value));
		else
			return '';
	}

	function labelFromString(&$value) {
		return "$value";
	}

	function labelFromObject(&$value) {
		// pntGeneralFunc tions
		return labelFromObject($value);
	}

// methods for conversion from user interface String ----------------------

	function fromHtml($htmlString) 
	{
		// problem: request data contains slashes but html output should not
		// therefore slashes are currently allways stripped by UI code and this
		// method is not used, becuase it is not clear wheather slashes should be stripped 
		// if no magic_quotes_gpc - and therefore if there should be a fromRequestData method instead
		// IOW, the framework currently only supports that magic_quotes_gpc is true!

		if (get_magic_quotes_gpc())
			return stripSlashes($htmlString); 
		else
			return html_entity_decode($htmlString); 
			//problem: requires PHP 4 >= 4.3.0
			
	}
	
	// converts from user interface string representation to
	// validated property value. 
	// returns null if conversion fails (may return invalid value though)
	// $this->error will hold an error message if some error occurs
	function fromLabel($string)
	{
		$this->error = null;

		$value = $this->convert($string); //checks format too
		return $value;
	}

	function convert(&$string) 
	{
		if (strlen($string) == 0)
			return null;
			
		$this->error = null;
		switch ($this->type) {
			case "boolean":
				return $this->convertToBoolean($string);
			case "number":
				return $this->convertToNumber($string);
			case "string":
				return $this->convertToString($string);
			case "date":
				return $this->convertToDate($string);
			case "timestamp":
				return $this->convertToTimestamp($string);
			case "time":
				return $this->convertToTime($string);
			case "email":
				return $this->convertToString($string);
			case "currency":
				return $this->convertToNumber($string);
			default:
				if (class_exists($this->type) && class_hasMethod($this->type, 'fromLabel'))
					return $value->fromLabel($value); //static method
				
				return $this->errorInvalidType . $this->type;
		}
	}
	
	function convertToBoolean($string)
	{
		$lower = strtolower($string); 
		if (($lower != $this->true) && ($lower != $this->false))
			$this->error = $this->errorInvalidBoolean . "$this->true/$this->false";
		return ($lower && $lower != $this->false);
	}
	
	function convertToNumber($string)
	{
		if ($this->usKbSupport4Uropean)
			$string = $this->usKbConvert4Uropean($string);
		
		$value = str_replace($this->thousends, '', $string);
//		$valid = preg_match("|^[+-]?[0-9]+([$$this->decimal][0-9]+)?\$|", $value);
		$valid = preg_match("|^[+-]?\\d+([$this->decimal]\\d+)?|", $value);

		if (!$valid) 
			$this->error = $this->getErrorInvalidNumber();
		
		// replace the decimal separator	
		$value = str_replace(
			$this->decimal
			,ValueValidator::getInternalDecimalSeparator()
			, $value
		);

		return (float)$value;
	}

	function convertToDate($string)
	{
		$result = $this->convertDT(
			$string, 
			$this->dateFormat, 
			'date'
		);
		
		if ($result === false) {
			$this->error = $this->errorInvalidDate.date($this->dateFormat);
			return $string;
		}
		return $result;
	}

	function convertToTime($string)
	{
		$result = $this->convertDT(
			$string, 
			$this->timeFormat, 
			'time'
		);
		
		if ($result === false) {
			$this->error = $this->errorInvalidTime.date($this->timeFormat);
			return $string;
		}
		return $result;
	}

	function convertToTimestamp($string)
	{
		$result = $this->convertDT(
			$string, 
			$this->timestampFormat, 
			'timestamp'
		);
		
		if ($result === false) {
			$this->error = $this->errorInvalidTimestamp.date($this->timestampFormat);
			return $string;
		}
		return $result;
	}
	
	function convertToString($string)
	{
		return (string)$string;
	}

	function usKbConvert4Uropean($string)
	{
		//if decimal separator present, return untouched
		if (strpos($string, $this->decimal) !== false) {
			return $string;
		}
		//convert last dot to decimal separator except when it could be a thousends separator
		$lastDotPos = strrpos($string, '.');
		if ($lastDotPos === false ||
				($this->thousends == '.' && strLen($string) - $lastDotPos == 4))
			return $string;
			
		return substr_replace ($string, $this->decimal, $lastDotPos, 1);
	}
}
?>