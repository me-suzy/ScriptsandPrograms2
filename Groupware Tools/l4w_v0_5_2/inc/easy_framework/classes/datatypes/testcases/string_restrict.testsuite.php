<br><br>
<?php
/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: string_restrict.testsuite.php,v 1.5 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
	require("../../../easy.inc.php");

    require_once 'string_restrict.testcase.php';
    require_once '../../extern/pear/PHPUnit/PHPUnit.php';

	$expected_assertion = false;
	
	function quiet_assertion ($file, $line, $code) {
		global $expected_assertion;
		
		$expected_assertion = true;
		//echo "*";
		echo "<!-- quite assertion has been called as expected in file $file ($line) -->\n";
	}
		
    $suite = new PHPUnit_TestSuite("String_Restrict_Test");
    $result = PHPUnit::run($suite);

     echo $result -> toHTML();
?>

<a href='../../../index.php'>back</a><br>
_____________________________<br>
<font face=Verdana size=1 color='#000066'>
	<b>Description:</b><br><br>
	<u>testInitWithNull</u> initializes a new easy_string_restrict with null for 
	all parameters. Null is allowed by default, therefore an object easy_string_restrict
	should be created.<br><br>
	<u>testNullNotAllowed</u> null allowed is set to false, so a creation with null should fail.<br><br>
	<u>testNullAllowed</u> null allowed is set to true, so a creation with null should succeed.<br><br>
</font>
