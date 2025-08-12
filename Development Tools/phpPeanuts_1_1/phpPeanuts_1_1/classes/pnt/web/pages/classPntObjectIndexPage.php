<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPage', 'pnt/web/pages');

/** Page showing a TablePart with all instances of a class. 
* Paging buttons are created by a PntPagerButtonsListBuilder, 
* whose classfolder is pnt/web/helpers.
* Columns of the TablePart can be specified in metadata on the class
* specified by pntType request parameter, 
* @see http://www.phppeanuts.org/site/index_php/Pagina/61
*
* This abstract superclass provides behavior for the concrete
* subclass ObjectIndexPage in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
* @package pnt/web/pages
*/
class PntObjectIndexPage extends PntPage {

	var $items;
	var $itemsAnnouncement = 'Item(s)';
	var $allItemsSizeAnnouncement = 'from';

	function PntObjectIndexPage(&$whole, &$requestData)
	{
		$this->PntPage($whole, $requestData);
	}

	function getName() {
		return 'Index';
	}

	function initForHandleRequest() 
	{
		parent::initForHandleRequest();
		$this->getRequestedObject();
	}
	
	function getInformation() {
		$info = parent::getInformation();
		if ($info)
			$info .= '<BR><BR>';
			
		return $info. $this->getItemsInfo();
	}
	
	function getItemsInfo()
	{
		if (count($this->getRequestedObject()) == 0)
			return '';
			
		return "$this->itemsAnnouncement ". ($this->getPageItemOffset() + 1)
			. ' - '. ($this->getPageItemOffset() + count($this->object))
			. " $this->allItemsSizeAnnouncement ". $this->getAllItemsSize() ;
	}
	
	

	//returns Array of objects
	function &getRequestedObject()
	{
		if (isSet($this->object))
			return $this->object;
			
		$clsDes =& $this->getTypeClassDescriptor();
		$qh =& $clsDes->getSelectQueryHandler();

		$sort = $clsDes->getLabelSort();
		$qh->query .= $sort->getSqlForJoin();
		$qh->query .= $sort->getSql();
		$offset = $this->getPageItemOffset();
		$rowCount = $this->getPageItemCount();
		$qh->query .= " LIMIT $offset, $rowCount";
//print $qh->query;		
		
		$objects =& $clsDes->_getPeanutsRunQueryHandler($qh);
		if (is_ofType($objects, 'PntError')) {
			trigger_error($objects->getLabel(), E_USER_WARNING);
			return array();
		}
		$this->object =& $objects;
		return $objects;
	}

	function &getButtonsList() {
		$type = $this->getType();
		
		$actButs[]=$this->getButton("New", "document.location.href='index.php?pntHandler=EditDetailsPage&pntType=$type';");
		$actButs[]=$this->getButton("Delete", "document.itemTableForm.submit();");
		$actButs[]=$this->getButton('Report', "document.itemTableForm.pntHandler.value='SelectionReportPage'; document.itemTableForm.submit();");
//		$actButs[]=$this->getButton('Copy', "document.itemTableForm.pntHandler.value='CopyMarkedAction'; document.itemTableForm.submit();");

		$navButs = array();
		$builder =& $this->getPagerButtonsListBuilder();
		$builder->addPageButtonsTo($navButs);

		return array($actButs, $navButs);
	}

	function printItemTablePart() {
		$part =& $this->getPart(array('TablePart'));
		$part->printBody();
	}

	function hasFilterForm() {
		return false;
	}

	function getPageButtonScript($pageItemOffset)
	{
		$type = $this->getType();
		return "document.location.href='index.php?pntHandler=IndexPage&pntType=$type&pageItemOffset=$pageItemOffset';"; 
	}
	
	function getAllItemsSize()
	{
		if (isSet($this->allItemsSize))
			return $this->allItemsSize;
			
		$clsDes =& $this->getTypeClassDescriptor();
		$this->allItemsSize = $clsDes->getPeanutsCount();
		return $this->allItemsSize;
	}
			
	function getPageItemOffset()
	{
		return isSet($this->requestData['pageItemOffset'])
			? (integer) $this->requestData['pageItemOffset'] : 0;
	}

	function getPageItemCount()
	{
		return 20;
	}
	
	function &getPagerButtonsListBuilder()
	{
		includeClass('PntPagerButtonsListBuilder', 'pnt/web/helpers');
		$builder =& new PntPagerButtonsListBuilder($this);
		$this->initPagerButtonsListBuilder($builder);
		return $builder;
	}
	
	function initPagerButtonsListBuilder(&$builder)
	{
		$builder->setItemCount($this->getAllItemsSize());
		$builder->setPageItemOffset($this->getPageItemOffset());
		$builder->setPageItemCount($this->getPageItemCount());
	}
	
	/** Return a combiFilter for combing the global filters with 
   * the filter of the searchPart.
	* This method may be overriden for applicable logical combination of filters
	* Default implementation: PntSqlCombiFilter withParts: this getGlobalFilters
	* currently only used by custom subclasses
	* Override getGlobalFIlters on custom subclass to select applicable filters
    * and make sure all applicable filter classes are included.
	*/
	function &getGlobalCombiFilter()
	{
		$filters = $this->getGlobalFilters();
		if (count($filters) == 0) return null;

		includeClass('PntSqlCombiFilter', 'pnt/db/query');
		$combi =& new PntSqlCombiFilter();

		reset($filters);
		while ( list($key) = each($filters) ) 
			$combi->addPart($filters[$key]);
		return $combi;
	}

	function getThisPntContext()
	{
		$type = $this->getType();
		return "$type**";
	}
	
	/** If any global filter's itemType has not been set, set it to the type of this page
   * to prevent influencing other use of the filters, copy them before setting the type  
	*/
	function &getGlobalFilters()
	{
		$result = parent::getGlobalFilters(); //intentionally copies the filters
		while (list($key) = each($result) )
			if (!$result[$key]->get('itemType') )
				$result[$key]->set('itemType', $this->getType());
				
		return $result;
	}
}
?>