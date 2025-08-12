<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

/** Collection of general purpose functions that may be used by all phpPeanuts classes 
*/

/** Returns phpPeanuts version identifier 
@return String */
function getPntVersion() {
	return "1.1";
}

/** Tries to include a file once. Returns wheater the file 
* was included or included before.  Does not trigger warnings.
*/
function tryIncludeOnce($filePath)
{
	if ( isSet($GLOBALS['PntIncluded'][$filePath]) )
		//inclusion has been tried before, return cached result
		return $GLOBALS['PntIncluded'][$filePath];

	$found = file_exists($filePath);
	$GLOBALS['PntIncluded'][$filePath] = $found;
	if ($found) 
		include_once($filePath);

	return $found;
}

/** Tries to include a class whose file name follows the pnt class file name rule:
* $fileName = "class$className.php" relative to the ../classes directory
* Registers included classes so that their names in correct case
* can be retrieved by classname in lowercase (=result of get_class() )
* Each class file is only (try)included once, so if you dynamically generate class files
* during the same request that should include them, only use this service if you understand its implementation
* @param String $className The name of the class in correct case
* @param String $dirPath the pathName of the directory relative to the current one
* @return boolean Wheater the include file was found and included
*/
function tryIncludeClass($className, $dirPath='') 
{
	if ($dirPath && substr($dirPath, -1) != '/')
		$dirPath .= '/';

	$result = tryIncludeOnce("../classes/$dirPath"."class$className.php");

	if ($result) {
		$GLOBALS['PntIncludedClasses'][strtolower($className)] = $className;
	}

	return $result;
}

/** @see tryIncludeClass(), same function, but triggers a warning if class file is not found.
* each class file is only (try)included once 
* We do use include instead of require because the use of a function like this one will
* unlike require, not tell the line number where it was called from when a file is not found.
* letting the execution continue may cause an error that reveals more about 
* the caller that tried to include the class that was not found.
*/
function includeClass($className, $dirPath='', $debug=false) 
{
	if ($dirPath && substr($dirPath, -1) != '/')
		$dirPath .= '/';

	$result = include_once("../classes/$dirPath"."class$className.php");
	if ($debug) {
		print ("Included: ../classes/$dirPath"."class$className.php<BR>");	
	}
	
	if ($result) {
		$GLOBALS['PntIncludedClasses'][strtolower($className)] = $className;
	}

	return $result;
}

/** Returns class name in the same case as was used to include its file
* through (try)includeClass(). If the class was not successfully
* included through (try)includeClass() the argument value is returned.
* @param string $class the class name in lower case, like the result of get_class() 
* @param boolean $warnIfMissing trigger a warning if the class name is missing
* @result The class name as it was included
*/
function getOriginalClassName($class, $warnIfMissing=true)
{
	if (isSet($GLOBALS['PntIncludedClasses'][$class]))
		return $GLOBALS['PntIncludedClasses'][$class];
	
	if ($warnIfMissing)
		trigger_error("class name not found for: $class", E_USER_WARNING);
	return $class;
}

function printDebug(&$obj) {
	print "<pre>";
	print "\n\n##################################\n";		
	print_r($obj);
	print "\n##################################\n";
	print "</pre>\n";
	
}

/** Returns a copy of the object
* The object's class file must be included by includeClass
* and the object must support constructing throuhg a zero argument constructor
*/
function &objectCopy(&$obj)
{
	$className = getOriginalClassName(get_class($obj));
	$copy =& new $className();
	reset($obj);
	while(list($field) = each($obj))
		$copy->$field = $obj->$field;
	return $copy;
}

function getBrowser() {

$b = $_SERVER['HTTP_USER_AGENT'];
$ie40 = preg_match("/MSIE 4.0/i", $b);
$ie50 = preg_match("/MSIE 5.0/i", $b);
$ie55 = preg_match("/MSIE 5.5/i", $b);
$ie60 = preg_match("/MSIE 6.0/i", $b);
$opera = preg_match("/opera/i", $b);
$ns47 = preg_match("/Mozilla\/4/i", $b);
$ns6  = preg_match("/Netscape6/i", $b);
$mz5  = preg_match("/Mozilla\/5/i", $b);

if ($ie40 == 1) {
$browser = "Internet Explorer 4.0";
} else if ($ie50 == 1) {
$browser = "Internet Explorer 5.0";
} else if ($ie55 == 1) {
$browser = "Internet Explorer 5.5";
} else if ($ie60 == 1) {
$browser = "Internet Explorer 6.0";
} else if ($opera == 1) {
$browser = "Opera";
} else if ($ns47 == 1) {
$browser = "Netscape 4.7";
} else if ($ns6 == 1) {
$browser = "Netscape 6";
} else if ($mz5 == 1) {
	$revPos = strPos($b, 'rv:');
	$rev = $revPos !== false ? subStr($b, $revPos + 3, 3) : '';
	$browser = "Mozilla $rev";
} else {
$browser = "Not identified";
}

return($browser);
}

/** Returns wheather specified class is 
* a subclass or is the specified parentClass
*
* @param String childClassName
* @param String parentClassName
* @return Boolean
*/
function is_subclassOr($childClassName, $parentClassName) 
{ 
		$parentClassName = strtolower($parentClassName);
		if (strtolower($childClassName) == $parentClassName)
			return true;

		if(!class_exists($childClassName))
			return false;
	
   		do { 
     		$childClassName = get_parent_class($childClassName);
     		if ($childClassName==$parentClassName) return true; 
   		} while (!empty($childClassName)); 

   		return false; 
}
	
/** Returns wheather the supllied value is within the specified type
* for objects is_a is used, for $type = number is_numeric.
* otherwise type == get_type 
* PS: is_numeric is locale dependent,
* this is consistent with implicit type conversion
*	
* The value is passed by reference, so passing a literal will cause an error,
* but who wants to check the type of a literal anyway?
*
* @param variant &$value
* @param String $type
* @return Boolean
*/
function is_ofType(&$value, $type)
{
	if (is_object($value)) {
		return is_a($value, $type);
	}
	
	$typeOfValue = gettype($value);
	return $typeOfValue == $type
		|| ($type == 'number'
			&& is_numeric($value)
		);
	
}

function class_hasMethod($className, $methodName) 
{
	// or check superclasses too?
	return in_array(
		strtolower($methodName)
		, get_class_methods($className)
	);
}

//for debugging purposes, for user interface string use StringConverter
function pntToString($value, $max=4) {
	
	if (!is_array($value))
		return pntValueToString($value);

	//array
	$result = "array(";
	$result .= implode(", ", assocsToStrings($value) );
	if (count($value) > $max)
		$result .= " ..";
	$result .= ")";
	return $result;
}

function pntValueToString($value) {
	
	if ($value===null) 
		return 'NULL';
	if ( is_bool($value) ) 
		return ($value ? 'true' : 'false');
	if ( is_string($value) )
		return "'$value'";
	if ( is_object($value) )
		if (method_exists($value, 'toString') )
			return $value->toString();
		else
			return 'a '.getOriginalClassName(get_class($value), false);

	return "$value";
}

/** 
* @param Array $array an associative array
* $return An array with strings representing the associations
*/
function &assocsToStrings(&$array, $max=null) 
{
	$result = array();
	$count = 0;
	reset($array);
	while ( ($max==null || $count <= $max) && (list($key) = each($array)) ) {
		$result[] = pntValueToString($key).'=>'.pntValueToString($array[$key]);
		$count++;
	}
	return $result;
}

function labelFromObject(&$value) {
	if (method_exists($value, 'getLabel') )
		return $value->getLabel();
	else
		if (method_exists($value, 'toString') )
			return $value->toString();
		else
			return 'a '.ucFirst(getClass($value));
}

function labelFrom(&$value)
{
	if (is_object($value))
		return labelFromObject($value);
	else
		return (string) $value;
}

function lcFirst($string)
{
	$len = strLen($string);
	if ($len < 2)
		return strToLower($string);
		
	return strToLower(subStr($string,0, 1)).subStr($string,1);
}

//Return a reference to an array with a reference to the value as its element.
// Use the key parameter if supplied
function &arrayWith(&$value, $key=0)
{
	$arr[$key] =& $value;
	return $arr;
}
//Like array_seach, but uses case insensitive stringcompare
// mixed needle, array haystack [, bool strict])
function array_searchCaseInsensitive($needle, &$haystack, $strict=false)
{
	$needleLwr = strToLower(labelFrom($needle));
	$keys = array_keys($haystack);
	for ($i=0; $i<count($keys); $i++) {
		$key =& $keys[$i];
		$value =& $haystack[$key];
		if ( $strict && !is_typeEqual($needle, $value) )
			break;
		if ( $needleLwr == strToLower(labelFrom($value)) )
			return $key;
		
	}
	return false;
}

function array_assocAddAll(&$addTo, &$toAdd)
{
	reset($toAdd);
	while ( list($key) = each($toAdd) )
		$addTo[$key] =& $toAdd[$key];
}

function array_addAll(&$addTo, &$toAdd)
{
	reset($toAdd);
	while ( list($key) = each($toAdd) )
		$addTo[] =& $toAdd[$key];
}
	
function is_typeEqual(&$first, &$second)
{
	if (is_object($first) && is_object($second))
		return get_class($first) == get_class($second);
	else
		return getType($first) == getType($second);
}

/** substring between the start marker and the end marker, 
* not including the markers.
* @param String $oString may contain the substring
* @param String $sString marks the start
* @param String $eString marks the end
* @param number $sPos position to start searching
* @return String The substring
*/
function getSubstr($oString, $sString, $eString, $sPos=0) 
{
	if ((strlen($oString)==0) or (strlen($sString)==0) or (strlen($eString)==0)) 
		return "";
	$beginPos=strpos($oString, $sString, $sPos);
	if ($beginPos===false)
		return "";
	$beginPos += strlen($sString);
	$oString=substr($oString, $beginPos, strlen($oString)-$beginPos);
	$eindPos=strpos($oString, $eString);
	if ($eindPos===false)
		return "";
	return substr($oString, 0, $eindPos);
}

if (!function_exists(is_a)) {
	function is_a(&$obj, $class)
	{
		return get_class($obj) == strToLower($class)
			|| is_subclass_of($obj, $class);
	}
}

/* use for array search, which may return false or null depending on php version
*/
function isFalseOrNull($value) {
	return $value === false || $value === null;
}

function toCsvString($array, $separator=';')
{
	$result = '';
	reset($array);
	$firstKey = key($array);
	while (list($key, $value) = each($array)) {
		if ($key !== $firstKey)
			$result .= $separator;
		$result .= '"';
		$result .= str_replace('"', '""', $value);
		$result .= '"';
	}
	return $result;
}

function splitFilePath($filePath)
{
	$posPathEnd = strrpos ($filePath, '/');
	if ($posPathEnd === false) {
		return array('', $filePath);
	} else {
		return array(subStr($filePath, 0, $posPathEnd)
			, subStr($filePath, $posPathEnd + 1));
	}
}

/** Return the sum of the values of the property 
* for the objects in the array
*/
function sum_from( $propertyName, &$arr)
{
	$totaal = 0;
	if (count($arr) == 0)
		return 0;

	//using propDesc takes a little more code but is faster then get() when repeated
		reset($arr);
		$any =& $arr[key($arr)];
		$propDesc =& $any->getPropertyDescriptor($propertyName);
if (!$propDesc) trigger_error($any->toString(). " no propertydescriptor for: $propertyName", E_USER_ERROR);	
	while (list($key) = each($arr)) {
		$value = $propDesc->_getValueFor($arr[$key]);
		if (is_ofType($value, 'PntError')) 
			trigger_error($value->getLabel(), E_USER_ERROR);
		$totaal += $value;
	}
	return $totaal;
}

?>
