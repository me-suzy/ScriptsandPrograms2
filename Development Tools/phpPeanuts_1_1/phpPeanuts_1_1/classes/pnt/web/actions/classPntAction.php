<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntRequestHandler', 'pnt/web');

/** Abstract Action superclass.
* @see http://www.phppeanuts.org/site/index_php/Pagina/158
* @package pnt/web/actions
*/
class PntAction extends PntRequestHandler {

	function PntAction(&$whole, &$requestData)
	{
		$this->PntRequestHandler($whole, $requestData);
	}


	
}
?>