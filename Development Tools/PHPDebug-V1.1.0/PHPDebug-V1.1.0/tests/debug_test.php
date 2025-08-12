<?php
/**
 * Full Tutorial
 * 
 * @package PHP_Debug
 * @filesource
 */ 

// Global var
include_once('../includes/setup.php');

// Include the Debug Class
include_once("$debugClassLocation/debug.php");

// Current file
$file = 'debug_test.php';

// Create the debug object
$Dbg = &new Debug(DBG_MODE_FULL);

// Generate XHTML
$Dbg->genXHTMLOutput = true;


/**
 * Begin output
 */ 
print('<?xml version="1.0" encoding="UTF-8"?>');

?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">     
  <head>  
    <title>PHP_Debug :: Hello World !!</title>
    <style type="text/css">
    body {
        background-color:#FFFFFF;
        padding:1px;
        margin:1px;
    }
    img {
        border-width:0 0 0 0;
    }    
    hr {
        height:             1px;
        border-style:       solid;
        border-color:       #c0c0c0;
        margin-top:         10px;
        margin-bottom:      10px;
    }
    </style>
  
<?php

/**
 * Generate StyleSheet  
 */
print($Dbg->generateStyleSheet());

?>

  </head>
  <body>
  
<?php

// == Change default settings of debug object =================================

// Set url of view source script
$Dbg->ViewSourceScriptPath = "../sources";
$Dbg->ViewSourceScriptName = "source.php";

// Set PHPMyAdmin config
$Dbg->setPhpMyAdminUrl("http://127.0.0.1/mysql");
$Dbg->setDatabaseName("mysql");

// Use _REQUEST array instead of _GET + _POST + _FILES + _COOKIE arrays
$Dbg->UseRequestArr = false;

// Set Max query line length to display big queries on multiple line
$Dbg->maxQueryLineLength = 110;


// Some display
print("<div align=\"center\"><h1>&laquo; PHP_Debug :: Hello World !! &raquo;</h1></div>");
print("<div><hr><h2>Click <a href=\"../sources/source.php?script=". $HTTP_SERVER_VARS['SCRIPT_FILENAME']. "\">here</a> to see the source code of this page.</h2></div>");


// == Then we can add debug infos :) ==========================================

// Add current processed file
$Dbg->addDebug('', DBGLINE_CURRENTFILE, __FILE__, __LINE__);

// Define Action
$action = 'PHP_DEBUG_BASIC';
// Debug current action processed
$Dbg->addDebug($action, DBGLINE_PAGEACTION, __FILE__, __LINE__);


// This the simplest debug info ( see full doc for debugline type constants )
$Dbg->addDebug("This is my first debug info !", DBGLINE_STD, __FILE__, __LINE__);


// Let's debug a query
$field = 'host, user';
$table = 'USERS';
$sql = "SELECT * FROM $table";
$badsql = "SELECT * F ,sdROM USERS;";


// Add the query in the debug infos
$Dbg->addDebug($sql, DBGLINE_QUERY, __FILE__, __LINE__);

// Now we ckeck the process time of the query, start timer
$Dbg->DebugPerf(DBGLINE_QUERY);

// ... Execute your query ... 
for ($i = 0 ; $i < 100000 ; $i++) {$z = $i;}
// ... Execute you query ...

// Now we stop the timer ( same function with same parameter )
$Dbg->DebugPerf(DBGLINE_QUERY);


// This the simplest debug info ( see full doc for debugline type constants )
$Dbg->addDebug("Now test the <b>Pear::SQL_Parser</b> (Pear must be enabled)", DBGLINE_STD, __FILE__, __LINE__);

// This the simplest debug info ( see full doc for debugline type constants )
$Dbg->addDebug("to enable the SQL parser, set <b>DBG_VERSION</b> to <b>DBG_VERSION_PEAR</b> in debug.php (top of the file)", DBGLINE_STD, __FILE__, __LINE__);

// Add a query with a parse error
$Dbg->addDebug($badsql, DBGLINE_QUERY, __FILE__, __LINE__);


// Check CPU Process time of a part of code

// How many iterations ?
$PerfCpt = 300;

// Text of the debug info
$Dbg->addDebug("Analyse the performance of a <b>for</b> 
					statement of $PerfCpt iterations.", DBGLINE_STD,
                __FILE__, __LINE__);

// Start the process time evaluation
$Dbg->DebugPerf(DBGLINE_STD);
$z = 0;
for ($i = 0 ; $i < $PerfCpt ; $i++)
{
	$z = $i;
	// ... your code ...
}
// Stop the process time evaluation
$Dbg->DebugPerf(DBGLINE_STD);


// Let's debug an array...
// We can also call this function with debug object  
//   "Dbg->DumpArr($arr,'Debug Variable $arr (array) Function DumpArr()');"

$arr = array(	array( 	1 => "aaaaaaa" , 
					 	2 => "bbbbb" ) , 

				array(	7 => "ccccccc" , 
						8 => "dddddd" ), 
						
				"The Array Says..." => "I'am an array, you can easly see what 
                    is inside of me with PHP_Debug, This class rox !  :p"
			);

print("<hr>");

Debug::DumpArr($arr,'Debug Variable $arr (array) Function DumpArr()');

print("<hr>");

Debug::DumpObj($arr, 'Debug Variable $arr (array) Function DumpObj() (same as DumpObj() if Pear is disabled)');

print("<hr>");

// ... or include it in the debug infos.
$Dbg->addDebug($arr, DBGLINE_OBJECT, __FILE__, __LINE__,'Debug Variable $arr 
   (array) Function DumpObj(), (same as DumpObj() if Pear is disabled)');


// Now dislay the debug infos ( End of page or wherever you want )
$Dbg->DebugDisplay();
      
// == Check documentation for full details. :) ================================


print("</div></body></html>");
?>
