   <?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
   includeClass('PntTestCase', 'pnt/unit');

/**
 * @package pnt/test/unit
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntWarningTest extends PntTestCase {

	function testWarning()
	{
		trigger_error('This warning was triggered deliberately', E_USER_WARNING);
	}	

}

return 'PntWarningTest';
?>
