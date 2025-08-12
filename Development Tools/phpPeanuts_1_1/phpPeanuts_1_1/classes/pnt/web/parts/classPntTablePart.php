<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPagePart', 'pnt/web/parts');
includeClass('PntXmlNavText', 'pnt/web/dom');

/** Part that outputs html descirbing a table with rows for object
* and columns for their properties. 
* As a default columns can be specified in metadata on the class
* specified by pntType request parameter, 
* @see http://www.phppeanuts.org/site/index_php/Pagina/61
*
* This abstract superclass provides behavior for the concrete
* subclass TablePart in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
* @package pnt/web/parts
*/
class PntTablePart extends PntPagePart {

	var $noItemsMessage = 'No Items';
	var $itemSelectWidgets = true;
	var $tableWidth='';
	var $selectedId=-1;
	var $showPropHeaders = true;
	var $itemBgColor='white';  
	var $itemHlColor='#ffffa0';
	var $bgColor;

	//legecay support - old PntTable protocol from before the intro of PntHorizontalTablePart
	var $showTableHeaders; 
	var $rowBgColor;  
	var $rowHlColor;
	var $handler_printItemRows;
	var $pntTableEqField;

	function PntTablePart(&$whole, &$requestData, $itemType=null, $propPaths=null)
	{
		$this->PntPagePart($whole, $requestData);
		$this->initialize($itemType, $propPaths);
	
	}

	function getName() {
		return 'TablePart';
	}

	function setItems(&$items)
	{
		$this->items =& $items;
	}
	
	function setSelectedId($value)
	{
		$this->selectedId = $value;
	}
	
	function setItemSelectWidgets($value)
	{
		$this->itemSelectWidgets = $value;
	}
	
	function setShowPropHeaders($value)
	{
		$this->showPropHeaders = $value;
		$this->showTableHeaders = $value; //depricated support
	}

	function setTableWidth($value)
	{
		$this->tableWidth = $value;
	}

	function setBgColor($value)
	{
		$this->bgColor = $value;
	}
	
	function setItemBgColor($value)
	{
		$this->itemBgColor = $value;
		$this->rowBgColor = $value; //depricated support
	}

	function setItemHlColor($value)
	{
		$this->itemHlColor = $value;
		$this->rowHlColor = $value;
	}

	function setHandler_printTableHeaders(&$handler)
	{
		$this->handler_printTableHeaders =& $handler;
	}
	
	function setHandler_printRows(&$handler)
	{
		$this->handler_printRows =& $handler;
	}

	function setHandler_printItemCells(&$handler)
	{
		$this->handler_printItemCells =& $handler;
	}
	
	function setHandler_printTableFooter(&$handler) 
	{
		$this->handler_printTableFooter =& $handler;
	}
	
	function setHandler_printItemBgColor(&$handler) 
	{
		$this->handler_printItemBgColor =& $handler;
	}
	
	function initialize($itemType, $propPaths)
	{
//		$this->pntTableEqField = microtime();
		// depricated support - if values are set to depricated fields, set them in the new corresponding fields
		if (isSet($this->showTableHeaders)) 
			$this->showPropHeaders = $this->showTableHeaders;
		else
			$this->showTableHeaders = $this->showPropHeaders;
		if (isSet($this->rowBgColor)) 
			$this->itemBgColor = $this->rowBgColor;
		else
			$this->rowBgColor = $this->itemBgColor;
		if (isSet($this->rowHlColor)) 
			$this->itemHlColor = $this->rowHlColor;
		else
			$this->rowHlColor = $this->itemHlColor;
			
		$this->headers = array();  // prop headers
		$this->cells = array();
		$this->items = null;
		$this->setHandler_printRows($this);
		$this->setHandler_printItemCells($this);
		$this->setHandler_printTableHeaders($this);
		$this->setHandler_printTableFooter($this);
		$this->setHandler_printItemBgColor($this);
		$this->itemType = $itemType;
		$this->peanutItems = is_subclassOr($this->getItemType(), 'PntObject');
		if (!$this->peanutItems)
			$this->itemSelectWidgets = false;
		$this->addPropPaths($propPaths);
	}

	function addPropPaths($arrayOrString) {

		$paths =& $arrayOrString;
		if ($paths === null && $this->peanutItems) {
			$clsDes = PntClassDescriptor::getInstance($this->getItemType());
			$paths = $clsDes->getUiColumnPaths();
		} 
		if (!is_array($paths))
			$paths = explode(' ', $paths);

		if (!empty($paths))
			while (list($label, $path) = each($paths) ) 
				$this->addPropPath($path, $label);
	}

	function addPropPath($path, $label) {
		if (empty($path)) return;

		$cell =& new PntXmlNavText($null, $this->getItemType(), $path);
		$cell->setConverter($this->getConverter()); //copies the converter
		$nav =& $cell->getNavigation(); 
		$cell->pntTableIndex = count($this->cells);
		$this->cells[] =& $cell;

		if (is_int($label))
			$label = $nav->getFirstPropertyLabel();
		$this->headers[] = $label;
	}

	function getItemType()
	{
		if ($this->itemType)
			return $this->itemType;
		
		return $this->whole->getType();
	}

	function &getItems()
	{
		if ($this->items !== null)
			return $this->items;
			
		return $this->whole->getRequestedObject();
	}
	
	function printAnchorFor(&$item) {
		//allows user to find items in a table by pressing the first letter of the label
		if ($this->peanutItems)
			print ("<a name='".substr($item->getLabel(),0,1)."'></a>\n");
	}

	function printCheckboxCheckedFor(&$item)
	{
		if (isSet($this->requestData[$item->getOid()]) )
			return 'CHECKED';
		else
			return '';
	}

	function printTableId()
	{
			$type = $this->getItemType(); 
			$dir = $this->getDir();
			$context = $this->whole->getThisPntContext();

			print "$dir*$type*$context";
	}

	function getDir()
	{
		if (!$this->peanutItems) 
			return parent::getDir();

		//HACK: should do ui dir lookup on topRequestHandler using the type as key
		//this may be the default if no dir is specified for the type
		if (parent::getDir() != $this->getDomainDir()) 
			return parent::getDir();  
				
		$clsDes =& PntClassDescriptor::getInstance($this->getItemType());
		return $clsDes->getClassDir().'/';
	}

//the rest of the methods contains table layout

	function printBody()
	{
		if (!$this->getItems())
			return $this->printNoItemMessage();

?>   
	<TABLE onkeypress="window.location.href='#'+String.fromCharCode(event.keyCode).toLowerCase();" class="pntItemTable" id="<?php $this->printTableId() ?>" width="<?php print $this->tableWidth ?>" bgcolor=<?php print $this->bgColor ?>>
	<?php $this->printThead() ?>
	<TBODY>
		<?php $this->handler_printRows->printRows($this) ?>
	</TBODY>
		<?php $this->handler_printTableFooter->printTableFooter($this) ?> 
	</TABLE>
<?php	
	}
	
	function printThead()
	{
		if (!$this->showPropHeaders) return;
?>
	<THEAD>
		<TR class="pntIth">
			<?php $this->printItemSelectHeader() ?>
			<?php $this->handler_printTableHeaders->printTableHeaders($this) ?> 
		</TR>
	</THEAD>
<?php	
	}
	
	function printNoItemMessage()
	{
		print "<font class=pntNormal>$this->noItemsMessage</font><BR>";
	}

	function printItemSelectHeader()
	{
		if (!$this->itemSelectWidgets) return;

		print "<TD class=pntIth>
				&nbsp;<image src='../images/invert.gif' ALT='invert selection' onclick='invertTableCheckboxes(this); return false;'>
			</TD>";
	}
	
	/** Prints TD's for the header row, after an eventual ItemSelectHeader has been printed
	* Eventhandler 
	* @argument PntTablePart $table === $this, made explicit for copy&paste as event handler
	*/
	function printTableHeaders(&$table)
	{
		reset($table->headers);
		while (list($key, $label) = each($table->headers)) 
			print "
			<TD class=pntIth>$label</TD>";
	}

	function printRows(&$table)
	{
		//depricated support - remove after copying this eventhandler
		if (isSet($this->handler_printItemRows) && $this->handler_printRows === self) 
			return $this->handler_printItemRows->printItemRows();
		
		$items =& $table->getItems();
		//reference anomaly woraround, maar misschien is dit ook wel efficienter
		forEach(array_keys($items) as $key) {
			$item =& $items[$key];

?> 
		<TR bgcolor="<?php $table->handler_printItemBgColor->printItemBgColor($table, $item) ?>" onMouseOver="this.style.background='<?php print $table->rowHlColor ?>';" onMouseOut="this.style.background='<?php $table->handler_printItemBgColor->printItemBgColor($table, $item) ?>';" style="cursor:hand;">
			<?php $table->printItemSelectCell($item) ?>
			<?php $table->handler_printItemCells->printItemCells($table, $item) ?> 
		</TR>
<?php
		}
	}
	
	function printItemBgColor(&$table, &$item)
	{
		//depricated support - remove after copying this eventhandler
		if (isSet($this->handler_printRowBgColor) ) 
			return $this->handler_printRowBgColor->printRowBgColor();
		
		if ($table->peanutItems && $item->get('id') == $table->selectedId)
			print $table->itemHlColor;
		else
			print $table->itemBgColor;
	}

	function printItemSelectCell(&$item) 
	{		
		if (!$this->itemSelectWidgets) return;
?> 
			<TD>
				<?php $this->printAnchorFor($item)?>
				<INPUT TYPE='CHECKBOX' <?php $this->printCheckboxCheckedFor($item) ?> VALUE='true' NAME='*!@<?php print $item->getOid() ?>'></INPUT>
			</TD>
<?php
	}

	/** Prints TD's for the supplied item, after an eventual ItemSelectCell has been printed
	* Eventhandler 
	* @argument PntObject $item the item this row displays
	* @argument PntTablePart $table $this, made explicit for copy&paste as event handler
	*/
	function printItemCells(&$table, &$item)
	{
		reset($table->cells);
		while (list($key) = each($table->cells)) {
			$cell =& $table->cells[$key];
			$onClick = $table->getCellOnClickParam($table, $item);
			print "
			<TD $onClick>";
			print $cell->getMarkupWith($item);
			print "</TD>";
		}
	}

	function getCellOnClickParam(&$table, &$item)
	{
			if (!$table->peanutItems) return ''; 
			
			$itemKey = $item->get('id');
			return "onClick=\"tdl(this,'$itemKey');\"";
	}

	/** Prints eventual footer
	* Eventhandler. Default implementation is do nothing
	* @argument PntTablePart $table $this, made explicit for copy&paste as event handler
	*/
	function printTableFooter(&$table)
	{
		//there can only be extra rows if an external eventhandler is set		
	}

	// ------------------------------  DEPRICATED SUPPORT ------------------------------------

	/** @depricated */
	function setShowTableHeaders($value)
	{
		$this->setShowPropHeaders($value);
	}

	/** @depricated */
	function setRowBgColor($value)
	{
		$this->setItemBgColor($value);
	}
	
	/** @depricated */
	function setRowHlColor($value)
	{
		$this->setItemHlColor($value);
	}

	/** @depricated */
	function addColumnPaths($arrayOrString) {
		$this->addPropPaths($arrayOrString);
	}
			
	/** @depricated */
	function addColumnPath($path, $label) {
		$this->addPropPath($path, $label);
	}

		/** @depricated */
	function setHandler_printItemRows(&$handler)
	{
		$this->handler_printItemRows($handler);
	}

	/** @depricated */
	function printItemRows(&$table)
	{
		$this->printRows($table);
	}
	
	/** @depricated */
	function setHandler_printRowBgColor(&$handler) 
	{
		$this->handler_printRowBgColor =& $handler;
	}
	
}