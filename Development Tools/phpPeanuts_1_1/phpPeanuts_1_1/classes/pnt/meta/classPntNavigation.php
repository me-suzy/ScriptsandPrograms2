<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntEvaluation', 'pnt/meta');		
includeClass('PntObject', 'pnt');

/** An object of this class represents a navigational step 
* starting from an object or an associative array.  
* PntNavigations can be nested to create a navigational path.
* In many places in the user interface nopt only properties can be 
* specified, but also paths. This makes the user interface more flexible. 
* PntNavigations can execute the navigation, answering the value of the 
* last property or associative key of the path.
* @package pnt/meta
*/
class PntNavigation extends PntEvaluation {

	var $itemType;
	var $key;
	var $next;
	var $getterName; //just a private cache

	/** get an instance of the proper subclass for 
	* navigating from the specified itemType over
	* the specified path
	*
	* @static
	* @param path String the navigation path with 
	*      property names or keys separated by dots
	* @param itemType String 'Array', 'List' or className
	* @return PntNavigation or NntReflectionError
	*/   
	function _getInstance($path, $itemType=null) 
	{
		
		if (is_subclassOr($itemType, 'PntObject')) 
			$result =& new PntObjectNavigation();
			
		elseif ($itemType == 'Array' || $itemType == null || class_exists($itemType)) 		
			$result =& new PntNavigation();

		else 
			return new PntReflectionError(
				'PntNavigation',
				"_getInstance($path, $itemType) Unknown itemType" 
			);
			
		$result->setItemType($itemType);
		return $result->_setPath($path);
	}
	
	function getItemType() {
		return $this->itemType;
	}
	
	function setItemType($value) {
		$this->itemType = $value;
	}

	function getKey() {
		return $this->key;
	}
	
	function setKey($value) {
		$this->key = $value;
		$this->getterName = 'get'.$value;
	}

	function &getNext() {
		return $this->next;
	}
	
	function setNext(&$value) {
		$this->next =& $value;
	}
	
	function &_setPath($path) {

		$i = strpos($path, '.');
		if ($i===false) {
			$this->setKey($path);
			return $this;
		}
		
		$this->setKey(substr($path,0,$i));
		$next =& PntNavigation::_getInstance(substr($path,$i+1,strlen($path) - $i) );
		if (is_ofType($next, 'PntError'))
			return $next;

		$this->setNext($next);
		return $this;
	}
	
	function getPath() 
	{
		$next =& $this->getNext();
		if ($next)
			return $this->getKey().'.'.$next->getPath();
		else
			return $this->getKey();
	}
	
	function getFirstPropertyLabel() 
	{
		// no metadata
		return $this->getKey();
	}
	
	function getPathLabel()
	{
		// no metadata
		return $this->getPath();
	}
	
	function getLabel() {
		return $this->getItemType().'>>'.$this->getPath();
	}


	function toString() 
	{
		return $this->getClass()
			.'('
			. $this->getLabel()
			. ')';
	}
	
	/** Single value navigation from the argument using the key.
	* if the argument is null, return null
	* if the argument is an array, get the next value using the key,
	* else, if the argument is not an object, return an NntReflectionError
	* if the argument has a getter method for the key, get the next value 
	* using the getter, else get the field named like the key.
	* if next, return the result of _evaluat next with the next value.
	* @argument variant $item Array or Object
	* @return variant result of the navigation or PntError
	*/
	function &_evaluate(&$item)
	{
		if ($item === null)
			return null;

		$nextItem =& $this->_step($item);
		if (is_ofType($nextItem, 'PntError')) 
			return $nextItem;
		
		$next =& $this->getNext();
		if ($next) 
			return $next->_evaluate($nextItem);
		else
			return $nextItem;
	}

	/** Multi value navigation from the argument 
	* if the argument is null, return null
	* otherwise return an array with the results of navigating
	* from each element under the same keys as those of the elements in the argument. 
	* If an error occurs, exit returning the error 
	* @argument variant $argument Array or Object
	* @return variant result of the navigation or PntError
	*/
	function &_collect(&$argument)
	{
		if ($argument === null) return null;
		$result = array();
		forEach(array_keys($argument) as $key) {
			$result[$key] =& $this->_evaluate($argument[$key]);
			if (is_ofType($result[$key], 'PntError')) return $result[$key];
		}
		return $result;
	}

	/** Single value navigation step from the argument using the key.
	* if the argument is null, return null
	* if the argument is an array, get the next value using the key,
	* else, if the argument is not an object, return an NntReflectionError
	* if the argument has a getter method for the key, get the next value 
	* using the getter, else get the field named like the key.
	* @argument variant $item Array or Object
	* @return variant result of the navigation or PntError
	*/
	function &_step(&$item) 
	{
		if ($item === null)
			return null;

		if (is_array($item))
			$nextItem =& $item[$this->getKey()];
		elseif (is_object($item))
			if (method_exists($item, $this->getterName)) {
				$getter = $this->getterName;
				$nextItem =& $item->$getter();
			} else {
				$field = $this->getKey();
				$nextItem =& $item->$field;
			}
		else
			return new PntReflectionError(
				$this
				, " can not navigate from item of unsupported type: $item"
			);
		return $nextItem;
	}

	/** Return an array with the elements from the supplied one 
	* sorted by the results of the supplied navigation.
	* and under-sorted by the keys from the supplied array ( this allows sub-sorting
	*  by first applying the second criterium and then again calling this function with the first)
	* Keys are not retained in the result array
	* Current implementation is case sentitive, this may change in future
	* @static
	* @param array array the array holding the elements to be sorted
	* @param $nav PntNavigation the navigation to sort by. ResultType must be primitive datatype
	* @param $ascending boolean wheather the sort order is ascending
	* @result Associative Array with keys for sorting and values from array param
	*/
	function &_nav1Sort(&$array, &$nav, $ascending=true)
	{
		$result =& PntNavigation::_byNav1SortKey($array, $nav);
		if (is_ofType($result, 'PntError'))
			return $result;
		
		//sort the result array by key
		if ($ascending)
			kSort($result);
		else
			krSort($result);
			
		return $result;
	}
	
	/** Return an array with the elements from the supplied one 
	* indexed by keys for sorting, i.e. from concatenating the results of the supplied navigation
	* with the original keys, both padded to the length of the longest. 
	* @static
	* @param array $array the array holding the elements to be sorted
	* @param $nav PntNavigation the navigation to use. ResultType must be primitive datatype
	* @result Associative Array with keys for sorting and values from array param
	*/
	function &_byNav1SortKey(&$array, &$nav)
	{
		reset($array);
		//Calculate the maximal lenght of keys and navigation results.
		//Cache the navigation results (retrieving them can take considerable resources)
		$maxLength = 0;
		$maxKeyLength = 0;
		$navResults = array();
		while (list($key) = each($array)) {
			$navResult =& $nav->_evaluate($array[$key]);
			
			if (is_object($navResult))
				if (is_ofType($navResult, 'PntError'))
					return new PntError('nav1Sort', 'can not retrieve sortKey', $navResult);
				else
					return new PntError('nav1Sort', 'navigation result not a primitive datatype: '. pntToString($navResult));
			
			$maxLength = max($maxLength, strLen($navResult));
			$maxKeyLength = max($maxKeyLength, strLen($key));
			$navResults[$key] =& $navResult;
		}
		// build array with keys by concatenating navigation results and original keys,
		// both paddes up to the length of the longest with spaces. Padd strings right and numbers left.
		$result = array();
		while (list($key) = each($navResults)) {
			$navResult =& $navResults[$key];
			if (is_string($navResult))
				$just = STR_PAD_RIGHT;
			else
				$just = STR_PAD_LEFT;
			$sortKey = str_pad($navResult, $maxLength, ' ', $just);	
			
			if (is_string($key))
				$keyJust = STR_PAD_RIGHT; 
			else
				$keyJust = STR_PAD_LEFT;
			$sortKey .= str_pad($key, $maxKeyLength, '!', $keyJust);
			
			$result[$sortKey] =& $array[$key];
		}
		return $result;	
	}

	/** Multi value navigation from the argument using a path string
	* if the argument is null, return null
	* otherwise return an array with the results of navigating
	* from each element under the same keys as those of the elements in the argument. 
	* If an error occurs, trigger E_USER_WARNING and return false.
	* @static
	* @argument Array $array  
	* @path String $path the path to navigate 
	* @return variant result of the navigation or PntError
	*/
	function &collect_path(&$array, $path, $itemType=null) 
	{
		$nav =& PntNavigation::_getInstance($path, $itemType);
		$result =& $nav->_collect($array);
		if (is_ofType($result, 'PntError')) {
			trigger_error($result->getLabel(), E_USER_WARNING);
			return false;
		}
		return $result;
	}
}

includeClass('PntObjectNavigation', 'pnt/meta');
?>