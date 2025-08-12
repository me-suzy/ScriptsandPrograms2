<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('ObjectDetailsPage');

/** Kind of DetailsPage showing property labels and values of a single object,
* but also a TablePart with values for each multi value property.
* Navigation leads to other ReportPages. 
* What details are shown can be overridden by overriding getFormTextPaths method.
* What multi value properties are shown can be overriden by overriding
* the getMultiPropNames method. 
* Columns shown in each TablePart can be overridden by creating a 
* getReportColumnPaths method on the type of objects shown in the table.
* Layout can be overridden, see http://www.phppeanuts.org/site/index_php/Pagina/65
*
* This abstract superclass provides behavior for the concrete
* subclass ObjectReportPage in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
* @package pnt/web/pages
*/
class PntObjectReportPage extends PntObjectDetailsPage {

	var $object;
	var $formTexts;

	function getName() {
		return 'Report';
	}

	// no menu, info and buttons, 
	// call this method printMainPart to get menu, info and buttons
	// and adapt printBodyTagIeExtraPiece and skinReportPart.php
	function printBody() { 
		$this->printPart('ReportPart');
	}

	function printBodyTagIeExtraPiece()
	{
		// 	Switch this on when menu, info and buttons
		//	if (getBrowser()!="Netscape 4.7") 
		//		print 'scroll=no onResize="scaleContent()"';
	}


	function insertCheckboxInItemTable(&$table)
	{
		// no checkboxes
	}

	function getButtonsList() 
	{
		// only used if menu, info and buttons
		$actButs = array();
		$type = $this->getType();
		$id = $this->requestData['id'];

		$navButs=array();
		$this->addContextButtonTo($navButs);

		return array($actButs, $navButs);
	}

	/** @return Array of PntNavValue
	*/
	function &getFormTexts()
	{
		if ($this->formTexts === null) {
			includeClass('PntFormNavValue', 'pnt/web/dom');
			$this->formTexts =& PntXmlNavValue::getInstances(
				$this->getConverter()
				, $this->getType()
				, $this->getFormTextPaths()
			);
		}
		return $this->formTexts;
	}

	function includeOrPrintDetailsTable() {

		$object =& $this->getRequestedObject();
		if (!$object) return;

		$type = $this->getType();
		$filePath = "skin$type".'ReportDetailsTable.php';
//print $filePath;
		if (file_exists($filePath))
			include($filePath);
		else
			$this->printPart('DetailsTablePart'); //do not include skin$type".'DetailsTable.php
	}

	function printDetailsLink($formKey)
	{
		// no detailsLinks
	}

	function getDetailsHref($dir, $pntType)
	{			
		return "../$dir"."index.php?pntType=$pntType&pntHandler=ReportPage&id=";
	}

	/** Print the label above the table with the items from a multi value property
	* @param $prop PntMultiValuePropertyDescriptor (not null)
	*/
	function printMultiPropLabel(&$prop)
	{
		print '	<BR>
				<font class=h2>'.ucFirst($prop->getLabel()).'</font>
				<BR>';
	}

	function printMultiPropsPart()
	{
		$obj =& $this->getRequestedObject();
		if ($obj === null) return;
		
		$clsDes =& $obj->getClassDescriptor();
		$names =& $this->getMultiPropNames();
		if (count($names) > 0) {
			while (list($key, $name) = each($names)) {
				$prop =& $clsDes->getPropertyDescriptor($name);
				if ($prop === null)
					trigger_error("Property not found: $name", E_USER_ERROR);
				$this->printMultiPropLabel($prop);
				$this->printPropertyItemTable($name);
			}
		}
	}

	/** Returns the names of the multi value properties to include in the report
	* in the right order. May be overridden by subclasses for specialized reports.
	*/
	function getMultiPropNames()
	{
		$obj =& $this->getRequestedObject();
		$clsDes =& $obj->getClassDescriptor();
		$multiProps =& $clsDes->getMultiValuePropertyDescriptors();
		return array_keys($multiProps);
	}

	/** Returns the paths for the columns to show in the table for the 
	* specified multi value property
	* default is getReportColumnPaths from the type of the property.
	* If null is returned, the columns will default to the uiColumnPaths
	* @param $propName String The name of the multi value property
	* $return String holding paths seperated by space, or Array. 
	*   If Array, String keys are used as labels, for numeric keys the paths will be used
	*/
	function &getItemTableColumnPaths($propName)
	{
		$obj =& $this->getRequestedObject();
		$prop =& $obj->getPropertyDescriptor($propName);
		$type = $prop->getType();
		$columnPaths = eval("return $type::getReportColumnPaths();");

		if (!$columnPaths) {
			$itemClsDes =& PntClassDescriptor::getInstance($prop->getType());
			$columnPaths =& $itemClsDes->getUiColumnPaths();
		}
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
	
	/** Default is to include all uiPropertyDescriptors
	*/
	function getFormTextPaths()
	{
		$clsDes =& $this->getTypeClassDescriptor();
		$props =& $clsDes->getUiPropertyDescriptors();
		return array_keys($props);

	}
	
	function printPropertyItemTable($propName, $columnPaths=null)
	{
		$obj =& $this->getRequestedObject();
		$prop =& $obj->getPropertyDescriptor($propName);
		
		$objects =& $prop->_getValueFor($obj);		
		if (is_ofType($objects, 'PntError')) {
			trigger_error($objects->getLabel(), E_USER_WARNING);
			return null;
		}
		
		if ($columnPaths === null)
			$columnPaths =& $this->getItemTableColumnPaths($propName);
		$table =& $this->getPropertyItemTable($prop, $columnPaths);		
		
		$table->setItems($objects);
		$table->printBody();
	}

	function &getPropertyItemTable(&$prop, $columnPaths=null)
	{
		$partName = 'TableProperty'.$prop->getName().'Part';
		$part =& $this->getPart(array($partName, $prop->getType(), $columnPaths));
		if (!$part) {
			$partName = 'TablePart';
			$part =& $this->getPart(array($partName, $prop->getType(), array()));
			//getPart may retrieve existing part without reinitilizing it, so we initialize it here
			$part->initialize($prop->getType(), $columnPaths);
		}
		
		$part->noItemsMessage = $this->getNoItemsMessage();
		$part->setItemSelectWidgets(false);
		return $part;
	}
	
	function printLabelPart()
	{
		$obj =& $this->getRequestedObject();
		if ($obj !== null)
			print '<H1>'.$obj->getLabel().'</H1>';
		else 
			print '<H1>'.getOriginalClassName(get_class($this)).' Error: Item not found: id='.$this->requestData['id'].'</H1>';
	}
}
?>