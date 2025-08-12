<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

require_once('../classes/generalFunctions.php');
includeClass('PntAssert', 'pnt/unit');

/** Class on which static functions are called by tests
* to generate test notifications if specified conditions
* are not met. If called when testing in pnt/unit the 
* notifications will be forwarded to the user interface
* where they will usually show up as assertion failures.
* All failures will be ignoored if global pntTestNotifier is not set.
*
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in superclass.
*/
class Assert extends PntAssert {
	
}
?>