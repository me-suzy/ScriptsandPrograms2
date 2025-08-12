<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');

$myDB = db_connect();
  
$content = array();

setcookie("log_in_cookie[user]");
setcookie("log_in_cookie[id]");

$content[] = "echo cnfg('logOutMessage');";

// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_log_out'));

db_disconnect($myDB);

?>
