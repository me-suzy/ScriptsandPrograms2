<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPage', 'pnt/web/pages');

/** Abstract superclass of PageParts.
* Generates html for a part of a page.
* @see http://www.phppeanuts.org/site/index_php/Menu/242
* @package pnt/web/parts
*/
class PntPagePart extends PntPage {

	function PntPagePart(&$whole, &$requestData)
	{
		$this->PntPage($whole, $requestData);
	}

	function printBody() {
		$this->includeSkin($this->getName());
	}

	// returns an appropiate form value for pntHandler
	function getThisPntHandlerName()
	{
		return $this->whole->getThisPntHandlerName();
	}
	
	function getType()
	{
		return $this->whole->getType($this);
	}
	
	function getRequestedObject()
	{
		return $this->whole->getRequestedObject($this);
	}
	
	function &getFormTexts()
	{
		return  $this->whole->getFormTexts();
	}
}
?>