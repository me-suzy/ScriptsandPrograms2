<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPage', 'pnt/web/pages');
includeClass('PntXmlTotalText', 'pnt/web/dom');

class PntObjectSelectionReportPage extends PntPage {

	function getName() {
		return 'SelectionReport';
	}

	function initForHandleRequest() 
	{
		// initializations
		parent::initForHandleRequest();
		$this->getRequestedObject();
	}
//section copied from PntDeleteMarkedAction
	//returns Array of objects
	function &getRequestedObject()
	{
		$markedObjects = array(); // php may crash if reference to unitialized var is returned
		$dir = $this->getDomainDir();
		$markedOids =& $this->getMarkedOids();
		while (list($key, $oid) = each($markedOids) ) {
			$oidArray = explode('*', $oid);
			$this->useClass($oidArray[0], $dir);
			$clsDes =& PntClassDescriptor::getInstance($oidArray[0]);
			$obj =& $clsDes->_getPeanutWithId($oidArray[1]);
			if (is_ofType($obj, 'PntError'))
				trigger_error($obj, E_USER_ERROR);
			$markedObjects[] =& $obj;
		}
		return $markedObjects;
	}
	// return the oids of the marked objects from the request
	function &getMarkedOids()
	{
		$result = array(); // php may crash if reference to unitialized var is returned
		reset($this->requestData);
		while (list($key) = each($this->requestData))
			if (strPos($key, '*!@') !== false)
				$result[] = substr($key, 3, strLen($key) - 3);
		return $result;
	}
//end section
	// no menu, info and buttons, 
	// call this method printMainPart to get menu, info and buttons
	// and adapt printBodyTagIeExtraPiece and skinReportPart.php
	function printMainPart() { 
		$this->printPart('SelectionReportPart');
	}

	function printBodyTagIeExtraPiece()
	{
		// 	Switch this on when menu, info and buttons
		//	if (getBrowser()!="Netscape 4.7") 
		//		print 'scroll=no onResize="scaleContent()"';
	}


/*	function insertCheckboxInItemTable(&$table)
	{
		// no checkboxes
	}
*/
	function getButtonsList() 
	{
		// only used if menu, info and buttons
		$actButs = array();
		$actButs[]=$this->getButton('Report', "document.itemTableForm.submit();");

		return array($actButs);
	}
	
	function getDetailsHref($dir, $pntType)
	{			
		return "../$dir/index.php?pntType=$pntType&pntHandler=ReportPage&id=";
	}

	/** Returns the paths for the columns to show in the table for the 
	* specified type
	* default is getReportColumnPaths from the type 
	* If null is returned, the columns will default to the uiColumnPaths
	* @param $propName String The name of type
	* $return String holding paths seperated by space, or Array. 
	*   If Array, String keys are used as labels, for numeric keys the paths will be used
	*/
	function getTableColumnPaths($type)
	{
		return eval("return $type::getReportColumnPaths();");
	}
	
	function printItemTablePart()
	{
		$table =& $this->getInitItemTable();
		$table->printBody();
	}
	
	function &getInitItemTable()
	{
		$table =& $this->getPart(array(
			'TablePart'
			, $this->getType()
			, $this->getTableColumnPaths($this->getType())
		));
		$table->extraCells =& $this->getTotalCells($table);
		$table->setHandler_printItemCells($this); // to calculate the totals
		$table->setHandler_printTableFooter($this); // to print the totals row
		return $table;
	}

	function &getTotalCells(&$table)
	{
		$totalCells = array();
		reset($table->cells);
		while (list($key) = each($table->cells)) {
			$cellText =& $table->cells[$key];
			if ($cellText->getContentType() == 'number') {
				$nav =& $cellText->getNavigation();
				if ($nav) {
					$itemClsDes =& PntClassDescriptor::getInstance($nav->getItemType());
					$prop =& $itemClsDes->getPropertyDescriptor($nav->getKey());
					$decimalPrecision = ValueValidator::getDecimalPrecision($prop->getMaxLength());
				}
				$totalCells[$key] =& new PntXmlTotalText($null, null, 0, 'number', $decimalPrecision);
				$totalCells[$key]->setConverter($this->getConverter());
			} 
		}
		return $totalCells;
	}

	//nothing different from the original handler, except that we calculate the totals
	function printItemCells(&$table, &$item)
	{
		reset($table->cells);
		while (list($key) = each($table->cells)) {
			$cell =& $table->cells[$key];
			$itemId = $item->get('id');
			print "<TD onClick=\"tdl(this,'$itemId');\">";
			print $cell->getMarkupWith($item);
			print "</TD>";
			
			if (isSet($table->extraCells[$key]))
				$table->extraCells[$key]->totalize($cell->content);
		}
	}

	function printTableFooter(&$table)
	{
?> 
	<TFOOT>
		<TR class="pntItf">
			<TD>&nbsp;</TD> <!-- for itemSelect column -->
			<?php $this->printTotalCells($table) ?> 
		</TR>
	</TFOOT>
<?php
	}
	
	function printTotalCells(&$table)
	{
		$zero = 0;
		$labelSet = false;
		reset($table->cells);
		while (list($key) = each($table->cells)) {
			print "
			<TD>";
			$cell =& $table->extraCells[$key];
			if ($cell) {
				print $cell->getMarkupWith($zero); //for some unknown reason 0 is replaced by the argument value
			} else {
				print ($labelSet ? '&nbsp;' : $this->getTotalsRowLabel());
				$labelSet = true;
			}
			print "</TD>";
		}
	}
	
	function getTotalsRowLabel()
	{
		return 'total';
	}

}
?>