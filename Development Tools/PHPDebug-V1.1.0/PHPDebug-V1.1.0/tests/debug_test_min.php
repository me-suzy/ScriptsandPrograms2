<?php
/**
 * Mini Tutorial
 * 
 * @package PHP_Debug
 * @filesource
 */ 

/**
 */ 
include_once('../sources/debug.php');
//include_once('E:\\Works\\PROJET~2\\01-WWW~1.COM\\wwwroot\\FUNCTI~1\\debug.php');

$Dbg = &new Debug();

print("<h1>PHP_Debug :: Hello World !!</h1>");

$Dbg->addDebug("DEBUG INFO", DBGLINE_STD, __FILE__, __LINE__);

$Dbg->DebugDisplay();

?>