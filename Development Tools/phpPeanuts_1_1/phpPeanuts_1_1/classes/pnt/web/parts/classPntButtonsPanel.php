<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPagePart', 'pnt/web/parts');

/** Part that outputs html descirbing two rows of buttons.
*
* This abstract superclass provides behavior for the concrete
* subclass ButtonsPanel in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
* @package pnt/web/parts
*/
class PntButtonsPanel extends PntPagePart {

	var $buttonType;
	var $buttonsList;
	
	var $typeSeparator = "</TD></TR><TR><TD>";
	var $buttonSeparator = "&nbsp;";

	function PntButtonsPanel(&$whole, &$requestData)
	{
		$this->PntPage($whole, $requestData);
	}

	function setButtonType($value)
	{
		$this->buttonType = $value;
	}

	function getName() {
		return 'ButtonsPanel';
	}

	function printBody($args)
	{
		if (isSet($args[1]))
			$this->setButtonType($args[1]);
		parent::printBody();
	}

	function printButtonsListPart() {	
		$buttonsList = $this->getButtonsList();

		if ($this->buttonType !== null) {
			if (isSet($buttonsList[$this->buttonType]))
				$this->printButtons($buttonsList[$this->buttonType], $this->buttonType);
		} else {
			while (list($key) = each($buttonsList)) {
				$this->printTypeSeparator($key);
				$this->printButtons($buttonsList[$key], $key);
			}
		}
	}
	
	function printButtons(&$parts, $type)
	{
		for ($i=0; $i<count($parts); $i++) {
			$this->printButtonSeparator($i, $type);
			$this->printButton($parts[$i], $type);
		}
	}
	
	function printTypeSeparator($type)
	{
		if ($type)
			print $this->typeSeparator;
	}
	
	function printButton(&$button, $type)
	{
		print $button->printBody(array(), $type);
	}
	
	function printButtonSeparator($key, $type)
	{
		if ($key)
			print $this->buttonSeparator;
	}

	function getButtonsList()
	{
		if (!isSet($this->buttonsList))
			$this->buttonsList =& $this->whole->getButtonsList();

		return $this->buttonsList;
	}

}
?>