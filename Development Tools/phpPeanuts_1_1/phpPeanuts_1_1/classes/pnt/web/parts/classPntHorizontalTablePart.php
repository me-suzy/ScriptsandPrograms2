<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntTablePart', 'pnt/web/parts');

/* Part that works like a normal TablePart, except for the colums 
 * and rows to be shown exchanged. In this table the properties 
 * are shown in the rows, while each item has a column.
 * Yet still the 'columnPaths' are used to specify the properties. 
 * So in fact they are they rowPaths here. */ 
class PntHorizontalTablePart extends PntTablePart {

	function setHandler_printPropHead(&$handler)
	{
		$this->handler_printPropHead =& $handler;
	}
	
	function setHandler_printPropFinish(&$handler)
	{
		$this->handler_printPropFinish =& $handler;
	}
	
	function setHandler_printHeaderPropFinish(&$handler)
	{
		$this->handler_printHeaderPropFinish=& $handler;
	}

	function initialize($itemType, $propPaths)
	{
		parent::initialize($itemType, $propPaths);
		$this->setHandler_printPropHead($this);
		$this->setHandler_printHeaderPropFinish($this);
		$this->setHandler_printPropFinish($this);
	}		
	
	function printThead()
	{
		if (!$this->itemSelectWidgets) return;
?>
	<THEAD>
		<TR class="pntIth">
			<?php $this->handler_printTableHeaders->printTableHeaders($this) ?> 
		</TR>
	</THEAD>
<?php	
	}

	/** Prints TD's for the header row, after an eventual ItemSelectHeader has been printed
	* Eventhandler 
	* @argument PntTablePart $table === $this, made explicit for copy&paste as event handler
	*/
	function printTableHeaders(&$table)
	{
		if ($this->showPropHeaders) print "<TD>&nbsp;</TD>"; 
		
		$items =& $table->getItems();
		reset($items);
		while (list($key) = each($items)) {
			$item =& $items[$key];
			$table->printItemSelectCell($item);
		}
		
		$table->handler_printHeaderPropFinish->printHeaderPropFinish($table);
	}
	
	function printRows(&$table)
	{
		reset($table->cells);
		while (list($key) = each($table->cells)) {
			$cell =& $table->cells[$key];
?> 
		<TR>
			<?php
			$table->handler_printPropHead->printPropHead($table, $cell);
			$table->handler_printItemCells->printItemCells($table, $cell);
 			$table->handler_printPropFinish->printPropFinish($table, $cell) ?>
		</TR>
<?php
		}
	}
	
	function printPropHead(&$table, $cell)
	{
		$label = $table->headers[$cell->pntTableIndex];
			print "
			<TD class=pntIth>$label</TD>";
	}	
	
	/** Prints TD's for the supplied item, after an eventual ItemSelectCell has been printed
	* Eventhandler 
	* @argument PntObject $item the item this row displays
	* @argument PntTablePart $table $this, made explicit for copy&paste as event handler
	*/
	function printItemCells(&$table, &$cell)
	{
		$items =& $table->getItems();
		reset($items);
		while (list($key) = each($items)) {
			$item =& $items[$key];

			$onClick = $table->getCellOnClickParam($table, $item);
			print "
			<TD $onClick "; 
?>			bgcolor="<?php $table->handler_printItemBgColor->printItemBgColor($table, $item) 
?>" onMouseOver="this.style.background='<?php print $table->rowHlColor 
?>';" onMouseOut="this.style.background='<?php $table->handler_printItemBgColor->printItemBgColor($table, $item) 
?>';" style="cursor:hand;"> <?php
			print $cell->getMarkupWith($item);
			print "</TD>";
		}
	}
	
	function printHeaderPropFinish($table)
	{
		//ignore
	}
	
	/** Prints eventual finishing cells
	* Eventhandler. Default implementation is do nothing
	* @argument PntTablePart $table $this, made explicit for copy&paste as event handler
	*/
	function printPropFinish(&$table, &$cell)
	{
		
	} 
}
?>