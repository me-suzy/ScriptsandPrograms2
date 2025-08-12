<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntXmlTextPart', 'pnt/web/dom');
includeClass('PntNavigation', 'pnt/meta');

class PntXmlNavText extends PntXmlTextPart {

	var $navigation;

	function PntXmlNavText(&$whole, $itemType='Array', $path=null)
	{
		$this->PntXmlTextPart($whole);
		$this->setPath($path, $itemType);
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	*/
	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();

		$this->addFieldProp('navigation', 'PntNavigation');

	}

	function setPath($path, $itemType=null) 
	{
		$nav =& $this->getNavigation();
		if ($nav) {
			if ($itemType)
				$nav->setItemType($itemType);
			$result =& $nav->_setPath($path);
			if (is_ofType($result, 'PntError'))
				trigger_error($result->getLabel(), E_USER_WARNING);
		} else {
			if ($path) {
				$nav =& PntNavigation::_getInstance($path, $itemType);
	
				if (is_ofType($nav, 'PntError'))
					trigger_error($nav->getLabel(), E_USER_ERROR);
				$this->setNavigation($nav);
			}
		}
	}
	
	function getPath() {
		$nav =& $this->getNavigation();
		return $nav->getPath();
	}

	function getContentType()
	{
		$nav =& $this->getNavigation();
		return $nav->getResultType();
	}

	function &getNavigation() {
		return $this->navigation;
	}
	
	function setNavigation(&$value) {
		$this->navigation = $value;
	}
	
	function getLabel() 
	{
		$nav = $this->getNavigation();
		return $nav->getLabel();
	}

	function getPathLabel() 
	{
		$nav = $this->getNavigation();
		return $nav->getPathLabel(); 
	}

	
	//Returns the content that will be merged
	function getContentWith(&$item) 
	{
		$nav = $this->getNavigation();
		$this->converter = $this->getConverter();  //copy the converter!!!
		$prop =&$nav->getLastProp();
		if ($prop)  {
			$this->setDecimalPrecision(ValueValidator::getDecimalPrecision($prop->getMaxLength()));
//			$this->converter->initFromProp($prop);
		} // else $this->converter->type = $nav->getResultType();	

		$value =& $nav->_evaluate($item);
		if (is_ofType($value, 'PntError')) {			
			trigger_error($value->getLabel(), E_USER_WARNING);
			return null;
		}
		
		$this->content =& $value;
		return $value;
	}
}
?>