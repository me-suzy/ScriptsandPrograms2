<?php
includeClass('PntDialog', 'pnt/web/dialogs');
includeClass('PntPoint', 'pnt/graphics');

class PntObjectDialog extends PntDialog {

	function getName() {
		return 'SearchDialog';
	}
	
	function getSearchButtonLabel()
	{
		return 'Search';
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

	//returns Array of objects
	function &getRequestedObject()
	{
		if ($this->object !== null)
			return $this->object;

		$filterFormPart =& $this->getPart(array($this->getFilterFormPartName()));

		$filter =& $filterFormPart->getRequestedObject();
		if (!$filter) 
			return $this->getRequestedObjectDefault();
		
		$this->objects =& $filterFormPart->getFilterResult();
		return $this->objects;
	}

	//default is the object currently selected
	function &getRequestedObjectDefault()
	{
		$clsDes =& $this->getTypeClassDescriptor();
		$found =& $clsDes->_getPeanutsWith('id', $this->requestData['id']);
		if (is_ofType($found, 'PntError')) {
			trigger_error($found->getLabel(), E_USER_WARNING);
			$found = array();
		}
		
		$this->object = $found;
		return $this->object;
	}

	function printItemTablePart() 
	{
		$table =& $this->getInitItemTable();
		$table->printBody();
	}

	function printSelectScriptPart()
	{
		if ($this->isMultiSelect())
			$this->printMultiSelectScript(); //notYetImplemented
		else
			$this->printSingleSelectScript();
	}
	
	function printSingleSelectScript()
	{
		?>
			<script>
				function tdl(itemId, itemLabel) {
					window.opener.<?php $this->printReturnFuncName() ?>(itemId, null, itemLabel);
					window.close();
				}
			</script>
		<?php
	}

	function getInitItemTable() {
	
		$table =  $this->getPart(array('TablePart'));
		$table->setHandler_printItemCells($this); //for special onClick script
		$table->setItemSelectWidgets($this->isMultiSelect());
		return $table;
	}

	function isMultiSelect()
	{
		return false; 
		//multiselect not yet supported, plan is to use request parameter tot activate multiselect
		// implement printMultiSelectScript first
	}

	function printItemCells(&$table, &$item)
	{
		reset($table->cells);
		while (list($key, ) = each($table->cells)) {
			$itemId = $item->get('id');
			$itemLabel = $item->getLabel();
			print "<TD onClick=\"tdl('$itemId', '$itemLabel');\">";
			print $table->cells[$key]->getMarkupWith($item);
			print "</TD>";
		}
	}

	function &getOkButton()
	{
		return $this->getButton("OK", "alert('click on an item in the table')");
	}


}	
?>