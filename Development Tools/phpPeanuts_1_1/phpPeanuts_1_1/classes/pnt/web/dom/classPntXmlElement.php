<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntXmlPart', 'pnt/web/dom');

class PntXmlElement extends PntXmlPart {

	var $tag;
	var $attributes;

	function PntXmlElement(&$whole, $tag=null)
	{
		$this->attributes = array();
		$this->PntXmlPart($whole);
		$this->setTag($tag);
		
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();

		$this->addFieldProp('tag', 'string');
		$this->addMultiValueProp('attributes', 'string'); //key as attribute name, value may be a PntXmlNavValue too
	}

	function initFrom(&$whole) 
	{
		if ($this->converter===null && $whole->converter !== null)
			$this->setConverter($whole->converter);
	}
	
	function &getParts() {
		return array_merge(
			$this->getElements()
			, $this->getAttributes()
		);
	}

	function getLabel() {
		return $this->getTag();
	}

	function getTag() {
		return $this->tag;
	}
	
	function setTag($value) {
		$this->tag = $value;
	}

	function &getAttributes() {
		return $this->attributes;
	}
	
	function setAttribute($name, $value)
	{
		$atts =& $this->getAttributes();
		$atts[$name] = $value;
	}

	function setAttributes(&$assocArray)
	{
		$this->attributes =& $assocArray;
	}

	function getMarkupStartTag(&$item) { 
		$result = "\n	<"; 
		$result .= $this->getTag();
		$result .= $this->getMarkupAttributes($item);
		$result .= ">";

		return $result;
	}
	
	function getMarkupEndTag() { 
		$result = "</"; 
		$result .= $this->getTag();
		$result .= ">\n";

		return $result;
	}

	function getMarkupAttributes(&$item) { 
		$atts =& $this->getAttributes();
		if (empty($atts)) 
			return '';
			
		$conv =& $this->getConverter();
		$result = '';
		reset($atts);
		while (list($key, ) = each($atts)) {
			
			if (is_object($atts[$key])) {							
				if (($atts[$key]->isAlwaysVisible()) || ($atts[$key]->getContentWith($item)==true)) {
					
					$result .= ' ';
					$result .= $key;
					$result .= '="';
					$result .= $atts[$key]->getMarkupWith($item);
				} 
			}
			else {
				$result .= ' ';
				$result .= $key;
				$result .= '="';
				$result .= $conv->toHtml($atts[$key]);
			}
			$result .= '"';
		}
		return $result;
	}


	function getMarkupWith(&$item) 
	{
		$result = $this->getMarkupStartTag($item); 
		$result .= $this->getMarkupContent($item); 
		$result .= $this->getMarkupEndTag(); 

		return $result;
	}

}
?>