<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObjectEditDetailsPage', 'pnt/web/pages');

/** Example of EditDetailsPage with polymorhism support */
class ObjectEditDetailsPage extends  PntObjectEditDetailsPage {


// TO BE REMOVED from polymorphic sites

	/** Polymorpism support: forward to proper page if requestedObject is of 
	* type different of pntType
	*/
	function handleRequest()
	{
		$this->useClass($this->getType(), $this->getDomainDir());
		$obj =& $this->getRequestedObject();
		if ($this->getType() == $obj->getClass()) 
			return parent::handleRequest();
	
		$requestData = $this->requestData;
		 $requestData['pntType'] = $obj->getClass();
		 $handler =& $this->getRequestHandler($requestData);
	
		 $handler->setRequestedObject($obj);
		 $handler->setInformation($this->information);
		 $handler->handleRequest();
	}
	
	
}
?>