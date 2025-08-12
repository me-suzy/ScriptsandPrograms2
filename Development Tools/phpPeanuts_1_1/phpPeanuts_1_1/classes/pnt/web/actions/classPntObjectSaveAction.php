<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntAction', 'pnt/web/actions');

/** Action that saves an object to the database. 
* Used by form from ObjectEditDetailsPage 
* when Insert or Update button is pressed. 
* Calls save method on the object.
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
class PntObjectSaveAction extends PntAction {

	function PntObjectSaveAction(&$whole, &$requestData)
	{
		$this->PntAction($whole, $requestData);
	}

	function handleRequest() {
		// initializations
		$this->useClass($this->getType(), $this->getDomainDir());
		$obj =& $this->getRequestedObject();
		$formTexts =& $this->getFormTexts();
		$this->saveErrors = array();

		// convert and validate form values
		$success = true;
		reset($formTexts);
		while (list($formKey) = each($formTexts)) {
			$current =& $formTexts[$formKey];
			if ($this->shouldProcess($current)) {
				$current->setItem($obj);
				$success = $current->setConvertMarkup(
					stripSlashes($this->requestData[$formKey])
				) && $success;
			} else {
				if (!$current->isReadOnly())
					print "<BR>RequestData not set for $formKey";
			}
		}
		if ($success) {
			//commit changes
			reset($formTexts);
			while (list($formKey) = each($formTexts)) {
				$current =& $formTexts[$formKey];
				if ($this->shouldProcess($current)) {
					$success = $success && $current->commit();
				}
			}
		}
		if ($success) {
			$this->saveErrors =& $obj->getSaveErrorMessages();
			$success = $success && empty($this->saveErrors); 
		}
		if ($success) {
			$obj->save();
			$this->finishSuccess($obj);
			return;
		}

		//error(s), forward to detailsPage
		$this->finishFailure();
	}


	function finishFailure() {
		//error(s), forward to detailsPage
		$newReq = $this->requestData; //makes a copy

		$handlerOrigin = $this->getRequestParam('pntHandlerOrigin');
		$newReq['pntHandler'] = $handlerOrigin ? $handlerOrigin : 'EditDetailsPage';

		$handler =& $this->getRequestHandler($newReq);
		$handler->setFormTexts($this->getFormTexts());
		$handler->setRequestedObject($this->getRequestedObject());
		$handler->setInformation($this->getErrorInformation());
		$handler->setInfoStyle($handler->getInfoStyleError());
		$handler->handleRequest();
	}


	function shouldProcess(&$formNavValue)
	{
		if ($formNavValue->isReadOnly())
			return false;

		if (isSet($this->requestData[$formNavValue->getFormKey()]))
			return true;

		$nav =&  $formNavValue->getNavigation();
		if ($nav->getResultType() != 'boolean')
			return false;

		//checkboxes do not send their key&value if not checked, so we add that here
		$cnv =& $formNavValue->getConverter();
		$false = false;
		$false = $cnv->toLabel($false, 'boolean');
		$this->requestData[$formNavValue->getFormKey()] = $false;
		return true;
	}

	function finishSuccess(&$obj)
	{
		$newReq['pntType'] = $this->getType();
		$newReq['id'] = $obj->get('id');
		$newReq['pntHandler'] = $this->getRequestParam('pntHandlerOrigin');

		$context = $this->getRequestParam('pntContext');
		if ($context)
			$newReq['pntContext'] = $context;

		$this->redirectRequest($newReq, $this->getOKMessage($obj));
		//return $this->forwardRequest($newReq, $this->getOKMessage($obj));
	}

	function getErrorMessage() {
		return "<B>Errors in value of:</B><BR>";
	}

	function getErrorInformation()
	{
		if (empty($this->saveErrors) )
			return $this->getErrorMessage();
			
		//Save errors
		$result = $this->getSaveErrorMessage();
		$result .= "\n<lu>";
		forEach($this->saveErrors as $message)
			$result .= "\n<li>$message</li>";
		$result .= "\n</lu>";
		return $result;
	}
	
	function getSaveErrorMessage() {
		$typeLabel = $this->getTypeLabel();
		return "<B>This $typeLabel can not be saved because:</B> ";
	}

	function getOKMessage($obj) {

		$done = $this->requestData['id'] ? 'updated' : 'created';

		return "OK<BR>"
			.$this->getTypeLabel()
			." '"
			.$obj->getLabel()
			."' has been $done.";
		}
}
?>