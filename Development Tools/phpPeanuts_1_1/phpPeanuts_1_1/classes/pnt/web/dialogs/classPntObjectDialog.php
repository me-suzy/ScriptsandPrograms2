<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObjectSearchPage', 'pnt/web/pages');
includeClass('PntPoint', 'pnt/graphics');

/** Dialog with FilterFormPart for searching and selecting an object.
* Paging buttons are created by a PntPagerButtonsListBuilder, 
* whose classfolder is pnt/web/helpers.
*
* This abstract superclass provides behavior for the concrete
* subclass ObjectDialog in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
* @package pnt/web/dialogs
*/
class PntObjectDialog extends PntObjectSearchPage {

// *****************  methods from PntDialog (may be adapted) *******************

	// static
	// return the piece of the javascript that will be called by the dialog
	// the piece must deliver the id in variable pId and the label in pLabel
	function getReplyScriptPiece($formKey)
	{
		return "func"."tion set$formKey(pId, pIgnoored, pLabel) {
		";
			
	}
	
	// Return a PntPont with minimum width and height
	function &getMinWindowSize() {
			return new PntPoint(640,450);
	}

	function getName() {
		return 'SearchDialog';
	}

	// use skinBody without menu
	function printBody()
	{
		$this->includeSkin('DialogBody');
	}

	function &getButtonsList() {

		$actButs[] = $this->getOkButton();
		$actButs[] = $this->getButton("Cancel", "window.close();");

		$navButs=array();
		$builder =& $this->getPagerButtonsListBuilder();
		$builder->addPageButtonsTo($navButs);
		
		return array($actButs, $navButs);
	}

	function &getOkButton()
	{
		return $this->getButton("OK", "alert('click on an item in the table')");
	}

	function printReturnFuncName()
	{
		print 'set'.stripSlashes($this->requestData['pntProperty']);
	}

//  *********** specific methods ************************

	//override PntObjectSearchPage method to get dialog skin
	function printMainPart() {
		$this->printPart($this->getName().'Part');
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

	function getLabel() 
	{
		return $this->getTypeLabel()
			." - "
			.$this->getItemsInfo();
	}

	function getItemsInfo()
	{
		$filterFormPart =& $this->getFilterFormPart();
		$filter =& $filterFormPart->getRequestedObject();
		if ($filter)
			return parent::getItemsInfo();
			
		return 'Current Value';
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
		while (list($key) = each($table->cells)) {
			$itemId = $item->get('id');
			$itemLabel = $this->getConvert($item, 'label');
			print "<TD onClick=\"tdl('$itemId', '$itemLabel');\">";
			print $table->cells[$key]->getMarkupWith($item);
			print "</TD>";
		}
	}


}	
?>