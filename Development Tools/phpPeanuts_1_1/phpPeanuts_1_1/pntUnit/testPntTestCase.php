<HTML>
<HEAD>
</HEAD>
<BODY>

<?php
	
require_once('../classes/pnt/unit/classPntTestNotifier.php');
// generalFunctions included by PntTestResult
require_once('../classes/pnt/test/unit/testPntFailTest.php');
require_once('../classes/pnt/test/unit/testPntSucceedTest.php');
$pntTestNotifier =& new PntTestNotifier();

$case =& new PntSucceedTest();
$pntTestNotifier->currentTestCase =& $case;
$pntTestNotifier->runCase($case);

print "================================================================";

$case =& new PntFailTest();
$pntTestNotifier->runCase($case);
$pntTestNotifier->runCase($null);


?>
</BODY>
</HTML>