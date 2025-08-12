<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPage', 'pnt/web/pages');

/** Page that serves as the main page of an application.
* By default includes skinIndexPage.php fromt the application folder. 
*
* This abstract superclass provides behavior for the concrete
* subclass IndexPage in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
* @package pnt/web/pages
*/
class PntIndexPage extends PntPage {

	function PntIndexPage(&$whole, &$requestData)
	{
		$this->PntPage($whole, $requestData);
	}

	function initForHandleRequest() {
		// no PntType, 

		$this->startSession();
	}

	function getName() {
		return 'Index';
	}

	function getTypeLabel() {
		$dir = $this->getDir();
		return subStr($dir, 0, strLen($dir)-1);
	}
	
	function printMainPart() {
		$this->includeSkin($this->getName());
	}
	
}
?>