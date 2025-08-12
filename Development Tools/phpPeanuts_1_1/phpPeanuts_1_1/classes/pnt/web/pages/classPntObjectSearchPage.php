<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObjectIndexPage', 'pnt/web/pages');

/** Kind of IndexPage with FilterFormPart for searching for objects. 
* Results are shown in a TablePart. Paging buttons are created by 
* a PntPagerButtonsListBuilder, whose classfolder is pnt/web/helpers.
* Columns of the TablePart can be specified in metadata on the class
* specified by pntType request parameter, 
* @see http://www.phppeanuts.org/site/index_php/Pagina/61
*
* This abstract superclass provides behavior for the concrete
* subclass ObjectSearchPage in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
* @package pnt/web/pages
*/
class PntObjectSearchPage extends PntObjectIndexPage {

	function getName() {
		return $this->getSearchButtonLabel();
	}
	
	function getSearchButtonLabel()
	{
		return 'Search';
	}

	//we only need to override the filterPart printing, so we\
	//do not need a skin of our own. This gets the skin of the superclass
	function printMainPart() {
		$this->printPart('IndexPart');
	}

	function printFilterPart()
	{
		$this->printPart($this->getFilterFormPartName());
		parent::printFilterPart();
	}

	function getFilterFormPartName()
	{
		return 'FilterFormPart';
	}

	function hasFilterForm() {
		return true;
	}

	/* Get the filterFormPart
    * To activate global filters on custom subclass, override with:
	*	$part =& parent::getFilterFormPart();
	*	$part->setImplicitCombiFilter($this->getGlobalCombiFilter());
	*	return $part;
	*/
	function &getFilterFormPart()
	{
		return $this->getPart(array($this->getFilterFormPartName()));
	}
	
	//returns Array of objects
	function &getRequestedObject()
	{
		if (isSet($this->object))
			return $this->object;

		$filterFormPart =& $this->getFilterFormPart();

		$filter =& $filterFormPart->getRequestedObject();
		if (!$filter) 
			return $this->getRequestedObjectDefault();
		
		$this->object =& $filterFormPart->getFilterResult($this->getPageItemCount());
		return $this->object;
	}

	function &getRequestedObjectDefault()
	{
		$this->object = array();
		return $this->object;
	}

	function getPageButtonScript($pageItemOffset)
	{
		$allItemsSize = $this->getAllItemsSize();
		$formName = $this->getFormName();
		$script = "document.$formName.allItemsSize.value='$allItemsSize';";
		$script = " document.$formName.pageItemOffset.value='$pageItemOffset';";
		$script .= " document.$formName.submit();";
		return $script;
	}
	
	function getFormName()
	{
		$filterFormPart =& $this->getFilterFormPart();
		return $filterFormPart->getFormName();
	}
	
	function getAllItemsSize()
	{
		$filterFormPart =& $this->getFilterFormPart();
		return $filterFormPart->getAllItemsSize();
	}
}
?>