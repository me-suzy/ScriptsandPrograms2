<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');

$myDB = db_connect();

$content = array();

if ( logIn($HTTP_POST_VARS["user_name"], $HTTP_POST_VARS["password"]) )
{   header("Location: ".cnfg('deHome') );
    exit;
}
else
{   $gbl["errorMessage"] = 'Wrong username/password combination<BR>';
    $content[]  = 'echo $gbl["errorMessage"] ; ';
}


// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_log_in')); 

db_disconnect($myDB);

?>
