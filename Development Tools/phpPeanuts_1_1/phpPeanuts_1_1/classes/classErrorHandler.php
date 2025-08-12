<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntErrorHandler', 'pnt');

/** Objects of this class log and handle errors using php's set_error_handler function
* $see http://www.phppeanuts.org/site/index_php/Pagina/32
*
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in the superclass.
*/
class ErrorHandler extends PntErrorHandler {
	
		var $developmentHost = 'localhost';
}
?>
