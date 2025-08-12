<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntPage', 'pnt/web/pages');
includeClass('PntPoint', 'pnt/graphics');

/** Abstract Dialog superclass. 
* @see http://www.phppeanuts.org/site/index_php/Menu/244
* @package pnt/web/dialogs
*/
class PntDialog extends PntPage {

	// static
	// return the piece of the javascript that will be called by the dialog
	// the piece must deliver the id in variable pId and the label in pLabel
	function getReplyScriptPiece($formKey)
	{
		return "function set$formKey(pId, pIgnoored, pLabel) {
		";
			
	}
	
	// Return a PntPont with minimum width and height
	function &getMinWindowSize() {
			return new PntPoint(600,450);
	}

	// gebruik skinBody zonder menu
	function printBody()
	{
		$this->includeSkin('DialogBody');
	}

	function &getButtonsList() {

		$actButs[] = $this->getOkButton();
		$actButs[] = $this->getButton("Cancel", "window.close();");
		return array($actButs);
	}

	function &getOkButton()
	{
		return $this->getButton("OK", "dialogForm.submit();");
	}

	function printReturnFuncName()
	{
		print 'set'.stripSlashes($this->requestData['pntProperty']);
	}

}	
?>