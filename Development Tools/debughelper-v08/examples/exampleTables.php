<html>
<head>
</head>

<body>
<?php
////////////////////////////////////////////////////////////////////////
/**
*
* example for debugHelper.php
* shows the different view for tables
*
* For the lastest version go to:
* http://www.phpclasses.org/browse.html/package/879.html
*
* @author	    Lennart Groetzbach <lennartg@web.de>
* @copyright	Lennart Groetzbach <lennartg@web.de> - distributed under the LGPL
* @package      debughelper
*/
////////////////////////////////////////////////////////////////

$dir = dirname(__FILE__);
require_once $dir . "/../debugHelper.php";

///////////////////////////////////////////////////////////////////////

$oneToFive = array(1,2,3,4,5);
$fiveToTen = array(6,7,8,9,10);
$aToE = array('A', 'B', 'C', 'D', 'E');

echo $debug->toTable($oneToFive, 'horizontal');
echo "<br>";
echo $debug->toTable($fiveToTen, 'vertical', false);
echo "<br>";
echo $debug->toTable(array($oneToFive,$fiveToTen), $aToE);
echo "<br>";
echo $debug->toTable(array($oneToFive,$fiveToTen), $aToE, false);

///////////////////////////////////////////////////////////////////////

echo "<hr>";
echo $debug->highlightFile(__FILE__);

///////////////////////////////////////////////////////////////////////

?>
</body>
</html>