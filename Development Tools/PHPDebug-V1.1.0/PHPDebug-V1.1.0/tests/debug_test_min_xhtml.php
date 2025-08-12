<?php
/**
 * Mini Tutorial
 * 
 * @package PHP_Debug
 * @filesource
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
    </style>
  
<?php
/**
 * Include Debug Class
 */ 
include_once('../sources/debug.php');
//include_once('E:\\Works\\PROJET~2\\01-WWW~1.COM\\wwwroot\\FUNCTI~1\\debug.php');

$Dbg = &new Debug();

/**
 * Generate StyleSheet  
 */
$Dbg->genXHTMLOutput = true;
print($Dbg->generateStyleSheet());

?>

  </head>
  <body>
  <div>
    <a href="http://validator.w3.org/check/referer">
       <img src="../media/vxhtml10.png" alt="Valid XHTML 1.0!" height="31" width="88" />
    </a>     
  </div>
  
<?php

print("<div><h1>PHP_Debug :: Hello World !!</h1></div>");
print("<div><h2>This page is XHTML 1.0 strict compliant.</h2></div>");

$Dbg->addDebug("DEBUG INFO", DBGLINE_STD, __FILE__, __LINE__);

$Dbg->DebugDisplay();

?>

  </body>
</html>

