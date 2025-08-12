<?php

require_once('../path_cnfg.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'func_common.php');

// $includeDir is the path to the directory 
// that holds the admin scripts.
$includeDir = path_cnfg('pathToAdminScriptsDir');

$htPage = $HTTP_GET_VARS['page'];

if (!isset($htPage) )
{   $includePage = $includeDir.'admin.php';
    include_once($includePage);
}
else
{   $myDB = db_connect();
    $includePage = $includeDir.$htPage.'.php';
    if (file_exists($includePage) ) 
    {   include_once($includePage);
    } 
    else
    {   die("Could not get file");
    }
}

db_disconnect($myDB);

?>