<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObject', 'pnt');

class PntXmlPart extends PntObject {

	var $elements;
	var $converter;

	function PntXmlPart(&$whole)
	{
		$this->PntObject();
		$this->elements = array();
		if ($whole!==null)
			$whole->addElement($this);
		
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();

		$this->addFieldProp('converter', 'PntStringConverter');
		$this->addMultiValueProp('parts', 'PntXmlPart'); // or string
		$this->addMultiValueProp('elements', 'PntXmlPart'); // or string
		$this->addDerivedProp('markup', 'string');
	}

	function initFrom(&$whole) 
	{
		if ($this->converter===null && $whole->converter !== null)
			$this->setConverter($whole->converter);
	}
	
	function &getConverter() {
		
		if ($this->converter===null) 
			return $this->getConverterDefault();
		else
			return $this->converter;
	}

	function getConverterDefault() {
		return new PntStringConverter();
	}

	function setConverter($value) {
		$this->converter = $value;
		$this->initParts();
	}

	function addElement(&$value) 
	{
		$elements =& $this->getElements();
		if (is_object($value))
			$value->initFrom($this);
		$elements[] =& $value;
	}

	function addElements(&$arr) 
	{
		reset($arr);
		while (list($key, ) = each($arr))
			$this->addElement($arr[$key]);
	}


	function &getElements() {
		return $this->elements;
	}


	function &getParts() {
		return $this->getElements();
	}
	
	function getAttributes() {
		
		return array();
	}
	
	function initParts() 
	{
		$parts =& $this->getParts();
		if (!empty($parts)) {
			while (list($key, ) = each($parts)) {
				$part =& $parts[$key];
				if (is_object($part))
					$part->initFrom($this);
			}
		}
	}

	function getMarkupWith(&$item) 
	{
		return $this->getMarkupContent($item);
	}


	function getMarkupContent(&$item) 
	{
		$result = '';
		if (!empty($this->elements)) {
			$elements =& $this->getElements();
			reset($elements);
			while (list($key, ) = each($elements)) {
				$element =& $elements[$key];
				if (is_object($element))
					$result .= $element->getMarkupWith($item);
				else
					$result .= $element;
			}
		}
		return $result;
	}

	function getMarkup() {
		$null = null;
		return $this->getMarkupWith($null);
	}
}
?>