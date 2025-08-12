<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
includeClass('PntTestCase', 'pnt/unit');

/**
 * @package pnt/test/unit
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntErrorTest extends PntTestCase {

    function testError() {
    	trigger_error('This error was triggered deliberately', E_USER_ERROR);
	}
	
}

return 'PntErrorTest';
?>
