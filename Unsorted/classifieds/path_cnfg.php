<?php


/* D.E. Classifieds v1.04  
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

// ************* START FUNCTION path_cnfg *************

function path_cnfg($the_key){

 
  $path_cnfg = array();

  
  /*****************************************
   *
   * DO NOT CHANGE ANYTHING 
   * ABOVE THIS POINT, 
   * UNLESS YOU KNOW WHAT YOU'RE
   * DOING.
   *

   *****************************************
   * Values *SHOULD* have a trailing slash.
   * The paths should be absolute, not relative.
   * Windows users should be able to use 
   * forward slashes(/) also, since php should 
   * take care of the difference.  
   *
   */

    /* Path to directory named "lib".
	 * You can put this directory above your 
	 * web root if you wish.
	 */
    $path_cnfg['pathToLibDir'] = '/path/to/declassifieds/lib/'; 

    /* You probably either want to make 
	 * the cnfg directory password protected 
	 * or put it above your web root.
	 * This directory holds the file "cnfg_vars.php" 
	 * which holds information like your db password, etc.. 
	 */
    $path_cnfg['pathToCnfgDir'] = '/path/to/declassifieds/cnfg/'; 


    /* This is not the path to the "admin" 
	 * directory.
	 * The directory named "admin" should stay 
	 * where it is, and the file named "index.php" 
	 * that is in the "admin" directory should stay there.
	 * This is the path to the directory 
	 * named "admin_scripts".  
	 * You can move this above your web root or 
	 * just leave it where it is and password protect it.
	 * The directory named "admin" that contains the "index.php" 
	 * file should also be password protected. 
	 */
	$path_cnfg['pathToAdminScriptsDir'] = '/path/to/declassifieds/admin/admin_scripts/';

    /* Path to directory named "templates".
	 * You can put this directory above your 
	 * web root if you wish.
	 */	
	$path_cnfg['pathToTemplatesDir'] = '/path/to/declassifieds/templates/';
    
   

  /* DO NOT CHANGE ANYTHING 
   * BELOW THIS POINT, 
   * UNLESS YOU KNOW WHAT YOU'RE
   * DOING.
   *
   ******************************************/


  return $path_cnfg[$the_key] ;


} // end function path_cnfg

// ************* END FUNCTION path_cnfg *************

?>