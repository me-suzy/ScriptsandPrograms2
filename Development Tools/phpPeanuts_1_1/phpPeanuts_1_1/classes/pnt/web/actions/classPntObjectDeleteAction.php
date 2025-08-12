<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntAction', 'pnt/web/actions');

/** Action that deletes one object. Requires id and pntType request parameters.
* Used by form from EditDetailsPage when Delete button is pressed.
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
class PntObjectDeleteAction extends PntAction {

	function PntObjectDeleteAction(&$whole, &$requestData)
	{
		$this->PntAction($whole, $requestData);
	}


	function handleRequest() {
		$this->useClass($this->getType(), $this->getDomainDir());
		$obj =& $this->getRequestedObject();
		if (!$obj)
			return trigger_error('Item not found: id='.$this->requestData['id'], E_USER_ERROR);

		$errors =& $obj->getDeleteErrorMessages();
		if (empty($errors)) {
			$obj->delete();
		
			$context = $this->getRequestParam('pntContext');
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
				// forward to same type listpage
				$newReq['pntType'] = $this->getType();
			}			
			
			return $this->redirectRequest($newReq, $this->getOKMessage($obj));
		}
		
		//error(s), forward to detailsPage
		$newReq = $this->requestData; //makes a copy
		$newReq['pntHandler'] = 'EditDetailsPage';
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
		$typeLabel = $this->getTypeLabel();
		return "<B>This $typeLabel can not be deleted because:</B> ";
	}
	
	function getOKMessage($obj) {
		$typeLabel = $this->getTypeLabel();
		$label = $obj->getLabel();
		return "$typeLabel '$label' has been deleted";
	}
}
?>