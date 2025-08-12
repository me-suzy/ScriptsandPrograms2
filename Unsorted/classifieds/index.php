<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

/**************************************
 * File Name: index.php               *
 * ---------                          *
 *                                    *
 **************************************/


require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');

$cookie = $HTTP_COOKIE_VARS['log_in_cookie'];

$myDB = db_connect();

checkUser('', ''); 

$content = array();
$content[] = 'doMain();';

// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_index'));

db_disconnect($myDB);


# --- START FUNCTIONS ---

// *********** START FUNCTION doMain() *************

function doMain(){

  ?>

  <!-- Put your html content BELOW here -->

  Welcome to the home page.  Welcome to the home page.  
  Welcome to the home page.  Welcome to the home page.  
  Welcome to the home page.  Welcome to the home page.  
  Welcome to the home page.  Welcome to the home page.  
  Welcome to the home page.  Welcome to the home page.  
  Welcome to the home page.  Welcome to the home page.  
  Welcome to the home page.  Welcome to the home page.  
  Welcome to the home page.  Welcome to the home page.  

 <!-- Put your html content ABOVE here -->


  <?php

} // end function doMain()

// *********** END FUNCTION doMain() *************




?>
