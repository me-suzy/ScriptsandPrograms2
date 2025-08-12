<?php
////////////////////////////////////////////////////////////////////////
/**
*
* example for debugHelper.php
* shows the different options of the class
*
* For the lastest version go to:
* http://www.phpclasses.org/browse.html/package/879.html
*
* @author	    Lennart Groetzbach <lennartg@web.de>
* @copyright	Lennart Groetzbach <lennartg@web.de> - distributed under the LGPL
*/
////////////////////////////////////////////////////////////////

$dir = dirname(__FILE__);
require_once $dir . "/../debugHelper.php";

///////////////////////////////////////////////////////////////////////
$debug->startTimer();

function a() {
   b();
}

function b() {
   global $debug;
   echo $debug->trace();
}

$a = 112311213;
$b = true;
$c = "peter";
$d = array(2, 25, 42);
$e = array("a" => "1","3" => "c", "b" => $d);

echo $debug->dump($a) . "\n<p>\n";
echo $debug->dump($b) . "\n<p>\n";
echo $debug->dump($c) . "\n<p>\n";
echo $debug->dump($d) . "\n<p>\n";

echo $debug->toTable($d, 'example') . "\n<p>\n";
echo $debug->toTable($d, 'example', false) . "\n<p>\n";

echo $debug->message("Hello World!<p>\n");
echo "<p>";
a();

///////////////////////////////////////////////////////////////////////

echo "<hr>";

echo "Timing: " . $debug->stopTimer();

///////////////////////////////////////////////////////////////////////

echo "<hr>";

echo $debug->highlightFile(__FILE__);

///////////////////////////////////////////////////////////////////////
?>
