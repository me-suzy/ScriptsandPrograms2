<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntAction', 'pnt/web/actions');

/** Action that deletes multiple objects. 
* Used by form from ObjectIndexPage, ObjectSearchPage and ObjectPropertyPage 
* when Delete button is pressed and items are marked.
* Redirects to pntContext or if none, to ObjectIndexPage 
* @see http://www.phppeanuts.org/site/index_php/Pagina/158
*
* This abstract superclass provides behavior for the concrete
* subclass DeleteAction in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @package pnt/web/actions
*/
class PntObjectDeleteMarkedAction extends PntAction {

	function PntObjectDeleteMarkedAction(&$whole, &$requestData)
	{
		$this->PntAction($whole, $requestData);
	}

	function handleRequest() {

		$marked =& $this->getRequestedObject();
		$errors = array();
		while (list($key, ) = each($marked) ) {
			$errors = array_merge($errors, $marked[$key]->getDeleteErrorMessages());
		}
		// we COULD delete the objects that had no errors and redirect, 
		// if we could clean up the copy of the request and make sure 
		// the error messages + markedOids fit into an url 
		// but that is a not yet implemented, 
		// so for now we do not delete any object if 
		// there are errors
		
		if (empty($errors)) {
			reset($marked);
			while (list($key) = each($marked) ) 
				$marked[$key]->delete();
		} else 
			$newReq = $this->requestData; //makes a copy
			
		$context =  $this->getRequestParam('pntContext');
		if ($context) {
			$arr = explode('*', $context);
			//forward to page of type from context
			$newReq['pntType'] = $arr[0];
			if (isSet($arr[2]) && $arr[2]) {
				// forward to context propertypage
				$newReq['id'] = $arr[1];
				$newReq['pntProperty'] = $arr[2];
				$newReq['pntHandler'] = "PropertyPage";
			}
		} else {
			// forward to indexpage
			$newReq['pntType'] = $this->getType();
			$newReq['pntHandler'] = 'IndexPage';
		}			
			
		if (empty($errors))
			return $this->redirectRequest($newReq, $this->getOKMessage($marked));

		
		//error(s), forward to page
		$handler =& $this->getRequestHandler($newReq);
		$handler->setInformation(
			$this->getDeleteErrorInformation($errors)
		);
		$handler->setInfoStyle($handler->getInfoStyleError());
		$handler->handleRequest();
	}

	function getDeleteErrorInformation($errors)
	{
		$result = $this->getDeleteErrorMessage();
		$result .= "\n<lu>";
		forEach($errors as $message)
			$result .= "\n<li>$message</li>";
		$result .= "\n</lu>";
		return $result;
	}
	
	function getDeleteErrorMessage() {
		return "<B>Delete canceled because:</B> ";
	}
	
	function getOKMessage($marked) {
		$typeLabel = $this->getTypeLabel();
		$count = count($marked);
		return "$count $typeLabel(s) have been deleted";
	}
	
	// return the oids of the marked objects from the request
	function &getMarkedOids()
	{
		$result = array(); // php may crash if reference to unitialized var is returned
		reset($this->requestData);
		while (list($key, ) = each($this->requestData))
			if (strPos($key, '*!@') !== false)
				$result[] = substr($key, 3, strLen($key) - 3);
		return $result;
	}
	
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
}
?>