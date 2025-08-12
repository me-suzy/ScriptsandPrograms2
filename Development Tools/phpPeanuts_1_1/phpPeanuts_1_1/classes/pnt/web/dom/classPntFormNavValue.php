<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntXmlNavValue', 'pnt/web/dom');

/** 
 * FormNavValue is used to store a form value in the dom. 
 * The form value is stored as markUp text.
 * If no form value the navigation is used to retrieve content
 * from the supplied item. 
 *
 * FormNavValue does not merge its content with its markUp.
 * If you need to merge, use a NavValue or NavText.
 *
 * Currently it is not clear how to handle paths longer then 1.
 * So for now, it works only with single step paths.
 * As a consequence some funcion will later be delegated to the Navigation
 * and the interface may change
 */
class PntFormNavValue extends PntXmlNavValue {

	var $item;
	var $prop; // PntPropertyDescriptor
	// $html is used for storing form values, no merge.

	function PntFormNavValue(&$whole, $itemType='Array', $path=null)
	{
		$this->PntXmlNavValue($whole, $itemType, $path);
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	*/
	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();
	}

	function &getInstances(&$stringConverter, $itemType, $fieldPaths=null) 
	{	
		if ($fieldPaths === null && is_subclassOr($itemType, 'PntObject')) {
			$clsDes = PntClassDescriptor::getInstance($itemType);
			$fieldPaths = $clsDes->getUiFieldPaths();
		} 
		$result = array(); 
		if (!empty($fieldPaths))
			forEach($fieldPaths as $path) {
				$nav =& PntNavigation::_getInstance($path, $itemType);
				if (is_ofType($nav, 'PntError'))
					trigger_error($nav->getLabel(), E_USER_ERROR);
				if ($nav->isSettedReadOnly())
					$inst =& new PntXmlNavValue($null, $itemType, $path);
				else
					$inst =& new PntFormNavValue($null, $itemType, $path);
				$inst->setConverter($stringConverter);
				$result[$inst->getFormKey()] =& $inst;
			} 
			
		return $result;
	}

	function getMarkupWith(&$item) 
	{
		if ($this->markup !== null) 
			return $this->markup;
			
		$content =& $this->getContentWith($item);

		$conv =& $this->getConverter();
		$contentLabel = $conv->toLabel($content, $this->getContentType());
		return $conv->toHtml($contentLabel);
	}
	
	function &getContentWith(&$item)
	{
		if ($this->markup !== null) 
			return $this->content;
			
		$this->initProp(get_class($item));
		$this->converter = $this->getConverter();  //copy converter!!!
		$this->converter->initFromProp($this->prop);

		if (!$this->usesIdProperty())
			return parent::getContentWith($item);
			
		$result =& $this->prop->_getValueFor($item);
		if (is_ofType($result, 'PntError')) {
			trigger_error($result->getLabel(), E_USER_WARNING);
			return '';
		}
		return $result;
	}

	/** sets the value from the form, converts it, 
	* but does not set the value on the item.
	*/
	function setConvertMarkup($value) 
	{
		//magic_quotes_gpc must be ON, see PntStringConverter::fromHtml comment
		$this->setMarkup($value);

		$this->error = null;

		$this->converter = $this->getConverter();  //copy converter
		$this->converter->initFromProp($this->prop);
		$this->content =& $this->converter->fromLabel($value);
// print "<BR>!".$this->getPath()." $this->content";

		if ($this->converter->error)
			return false;
			
		$this->error = $this->item->validateGetErrorString($this->prop, $this->content);
		return $this->error === null;
	}
	
	/** sets the already converted value on the item
	*/
	function commit() {
		$result =& $this->prop->_setValue_for($this->content, $this->item);
		if (is_ofType($result, 'PntError')) {
			$this->error = $result->getLabel();
			return false;
		}
		return true;
	}

	function getError() {
		if (isSet($this->error))
			return $this->error;
		else
			return $this->converter->error;
	}
	
	function setItem(&$item) {
		$this->item =& $item;
		$this->initProp(get_class($item));
	}
	
	function initProp($itemType) 
	{
		$nav =& $this->getNavigation();
		$clsDes =& PntClassDescriptor::getInstance($itemType);
		$prop =& $clsDes->getPropertyDescriptor($nav->getKey());

		$idProp =& $prop->getIdPropertyDescriptor();
		if ($idProp)
			$this->prop =& $idProp;
		else
			$this->prop =& $prop;
	}

	function getFormKey() 
	{
		if ($this->prop === null) {
			$nav =& $this->getNavigation();
			$this->initProp($nav->getItemType());
		}
		return $this->prop->getName();
	}
	
	function getNavKey() {
		$nav =& $this->getNavigation();
		return $nav->getKey();
	}
	
	function usesIdProperty()
	{
		$nav =& $this->getNavigation();
		return $this->getFormKey() != $nav->getKey();
	}
	
	function isReadOnly() {
		return false;
	}
}
?>