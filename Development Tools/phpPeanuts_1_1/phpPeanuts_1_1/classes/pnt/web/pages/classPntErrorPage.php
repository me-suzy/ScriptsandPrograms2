<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPage', 'pnt/web/pages');

/** page used by ErrorHandler to show error message to end user.
* @see http://www.phppeanuts.org/site/index_php/Pagina/32
*
* This abstract superclass provides behavior for the concrete
* subclass ErrorPage in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
 * @package pnt/web/pages
*/
class PntErrorPage extends PntPage {

	function PntErrorPage(&$whole, &$requestData)
	{
		$this->PntPage($whole, $requestData);
	}

	function getName() {
		return 'Error';
	}

	function initForHandleRequest()
	{
		// do not try to useClass
	}

	function printMainPart() {
		print $this->getBody();
	}

	function getBody() {
		if (isSet($this->whole->errorMessage))
			return $this->whole->errorMessage;
			
		$errorMessage = isSet($this->requestData['errorMessage'])
			? $this->requestData['errorMessage'] 
			: 'An error occurred';
		return "$errorMessage<BR>
				". $this->getRequestParam('errorCause');
	}
	
}
?>