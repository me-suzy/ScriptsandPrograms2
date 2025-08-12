<?php

//                         _______________
//------------------------< SOME INCLUDES >-------------------------------------
//                         ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
include_once 'config.php';
include_once 'includes/functions.php';
require_once 'includes/configmagik.php';
//                        __________________
//-----------------------< GLOBAL VARIABLES >-----------------------------------
//                        ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
// txt/db file path
$txtPath = $scriptPath."txt/txtdb.ini.php";
$imagesPath = $scriptPath."images/";




/******************************************************************************/
//                      	MAIN SCRIPT                                   //

poll($txtPath, $imagesPath);





?>
