<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPage', 'pnt/web/pages');

/** Page showing a TablePart with the values of a multi value property.
* The property is specified by the pntProperty request parameter.
* Columns of the TablePart can be specified in metadata on the class
* specified by pntType request parameter, 
* @see http://www.phppeanuts.org/site/index_php/Pagina/61
*
* This abstract superclass provides behavior for the concrete
* subclass ObjectPropertyPage in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
* @package pnt/web/pages
*/
class PntObjectPropertyPage extends PntPage {

	var $items;

	function getPropertyName() {
		return isSet($this->requestData['pntProperty']) ? $this->requestData['pntProperty'] : null;
	}

	function getName() 
	{
		$prop =& $this->getPropertyDescriptor();
		return ucfirst($prop->getLabel());
	}

	function getLabel() 
	{
		return labelFrom($this->getRequestedObject())
			." - "
			.$this->getName();
	}

	/** Polymorpism support: forward to proper page if requestedObject is of 
	* type different of pntType
	*/
	function handleRequest()
	{
		$this->useClass($this->getType(), $this->getDomainDir());
		$obj =& $this->getRequestedObject();
		if ($this->getType() == $obj->getClass()) 
			return parent::handleRequest(); // normal handling by this object
	
		//forward to another page
		$requestData = $this->requestData;
		 $requestData['pntType'] = $obj->getClass();
		 $handler =& $this->getRequestHandler($requestData);
	
		$handler->setRequestedObject($obj);  //so that the page does not have to retrieve it again
		
		//the following may have been set by another handler
		$handler->setInformation($this->information); 
		$handler->setInfoStyle($this->infoStyle);
		
		$handler->handleRequest();
	}

	function initForHandleRequest() 
	{
		parent::initForHandleRequest();
		$obj =& $this->getRequestedObject();

		$prop =& $this->getPropertyDescriptor();
		if (!$prop) {
			trigger_error('PropertyDescriptor missing', E_USER_WARNING);
			return null;
		}
		if ($obj)
			$this->items =& $this->getPropertyValueFor($obj);
		else 
			$this->items = array();
		
	}

	function printMainPart() 
	{
		$this->printPart('PropertyPart');
	}

	function getInformation() {
		$info = parent::getInformation();
		if ($info)
			return $info;
			
		if ($this->getRequestedObject())
			return count($this->items). ' Item(s)';
			
		return '<B>'.getOriginalClassName(get_class($this)).' Error:</B><BR>Item not found: id='.$this->requestData['id'];
	}

	function printItemTablePart() 
	{
		if (!$this->getRequestedObject()) return;

		$table =& $this->getInitItemTable();
		$table->printBody();
	}

	function &getInitItemTable() {

		$prop =& $this->getPropertyDescriptor();
		$columnPaths =& $this->getItemTableColumnPaths();
		
		$partName = 'TableProperty'.$prop->getName().'Part';
		$part =& $this->getPart(array($partName, $prop->getType(), $columnPaths));
		if (!$part) {
			$partName = 'TablePart';
			$part =& $this->getPart(array($partName, $prop->getType(), $columnPaths));
		}
		
		$part->setItems($this->items);
		return $part;
		
	}

	/** Returns the paths for the columns to show in the itemtable 
	* $return Array, whose String keys are used as labels, for numeric keys the paths will be used
	*/
	function &getItemTableColumnPaths()
	{
		$prop =& $this->getPropertyDescriptor();
		$itemClsDes =& PntClassDescriptor::getInstance($prop->getType());
		$columnPaths =& $itemClsDes->getUiColumnPaths();
		if (!is_array($columnPaths))
			$columnPaths = explode(' ', $columnPaths);

		$twinName = $prop->getTwinName();
		if ($twinName) {
			//remove column of twin from columnPaths
			$twinColumnKey = array_search($twinName, $columnPaths);
			if (!isFalseOrNull($twinColumnKey))
				unset($columnPaths[$twinColumnKey]);
		}
		
		return $columnPaths;
	}


	function &getPropertyValueFor(&$obj) {
		$prop =& $this->getPropertyDescriptor();
		$objects =& $prop->_getValueFor($obj);		
		if (is_ofType($objects, 'PntError')) {
			trigger_error($objects->getLabel(), E_USER_WARNING);
			return null;
		}
		return $objects;
	}
	
	function &getPropertyDescriptor() {
		$clsDes =& $this->getTypeClassDescriptor();
		return $clsDes->getPropertyDescriptor($this->getPropertyName());
	}
	
	function getPropertyType() {
		$prop =& $this->getPropertyDescriptor();
		if (!$prop)
			return null;
		return $prop->getType();
	}

	function getPropertyClassDir() {
		$prop =& $this->getPropertyDescriptor();
		if (!$prop)
			return null;
		return $prop->getClassDir();	
	}
	
	function getButtonsList() 
	{
		$type = $this->getType();
		$id = $this->requestData['id'];
		$propType = $this->getPropertyType();
		$idPropName = $this->getIdPropertyName();
		$propClassDir = $this->getPropertyClassDir();
		// assume if the properties classFolder is the same as the currend domain folder,
		// we do not need to refer to a different application. 
		// Assume if we do the application foder has the same name as the properties class folder. 
		$appDirPart = $propClassDir == $this->getDomainDir() ? '' : "../$propClassDir/";

		$actButs[]=$this->getButton("New",
			"document.location.href='$appDirPart"
			. "index.php?pntHandler=EditDetailsPage&pntType=$propType&pntContext="
			. $this->getThisPntContext()
			. ($idPropName ? "&$idPropName=$id';" : "';") 
		);
		$actButs[]=$this->getButton("Delete", "document.itemTableForm.submit();");
		$actButs[]=$this->getButton('Report', "document.itemTableForm.pntHandler.value='SelectionReportPage'; document.itemTableForm.submit();");

		$navButs[]=$this->getButton('Details', "document.location.href='index.php?pntType=$type&id=$id';");
		$this->addMultiValuePropertyButtons($navButs);

		return array($actButs, $navButs);
	}

	function getThisPntContext()
	{
		$type = $this->getType();
		$id = $this->requestData['id'];
		$propName = $this->getPropertyName();
		return "$type*$id*$propName";
	}

	function getIdPropertyName() 
	{
		$prop =& $this->getPropertyDescriptor();
		if (!$prop)
			return null;
		$idProp =& $prop->getIdPropertyDescriptor();
		if (!$idProp)
			return null;
		return $idProp->getName();
		
	}

	function getDetailsHref($dir, $pntType)
	{
		$type = $this->getType();
		$id = $this->requestData['id'];
		$propName = $this->getPropertyName();
		
		return "../$dir/index.php?pntType=$pntType&pntContext=$type*$id*$propName&id=";
	}	

}
?>