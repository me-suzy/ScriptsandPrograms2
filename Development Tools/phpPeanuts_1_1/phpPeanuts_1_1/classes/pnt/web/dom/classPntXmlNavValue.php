<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntXmlNavText', 'pnt/web/dom');

class PntXmlNavValue extends PntXmlNavText {

	var $navigation;
	var $isAlwaysVisible = true;

	function PntXmlNavValue(&$whole, $itemType='Array', $path=null)
	{
		// do not add as part of the whole
		$this->PntXmlNavText($null, $itemType, $path);
		// compensate for not being initialized from the whole
		$this->initFrom($whole);
	}

	// if changed, method on PntFormNavValue must be changed too
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
				$inst =& new PntXmlNavValue($null, $itemType, $path);
				$inst->setConverter($stringConverter);
				$result[$inst->getFormKey()] =& $inst;
			} 
			
		return $result;
	}

	function getFormKey() 
	{
		$nav =& $this->getNavigation();
		return $nav->getKey();
	}
	
	function isAlwaysVisible() {
		return $this->isAlwaysVisible;	
	}
	
	function setAlwaysVisible($value) {
		$this->isAlwaysVisible=$value;
	}

	function getError() {
		return null;
	}

	function isReadOnly() {
		$nav =& $this->getNavigation();
		return $nav->isSettedReadOnly();
	}

}
?>