<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
includeClass('PntTestCase', 'pnt/unit');

/**
 * @package pnt/test/unit
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntNoticeTest extends PntTestCase {

	function testNotice()
	{
		trigger_error('This notice was triggered deliberately', E_USER_NOTICE);
	}
	
}

return 'PntNoticeTest';
?>
