<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
includeClass('PntTestCase', 'pnt/unit');

/**
 * @package pnt/test/unit
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntFatalErrorTest extends PntTestCase {

    function testFatalError() {
    	$null = null;
    	$null->doesNotExist();
	}
	
}

return 'PntFatalErrorTest';
?>
