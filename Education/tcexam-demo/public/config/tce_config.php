<?php
//============================================================+
// File name   : tce_config.php                                 
// Begin       : 2001-10-23                                    
// Last Update : 2004-12-22                                    
//                                                             
// Description : Configuration file for public section.
//                                                             
//                                                             
// Author: Nicola Asuni                                        
//                                                             
// (c) Copyright:                                              
//               Tecnick.com S.r.l.                            
//               Via Ugo Foscolo n.19                          
//               09045 Quartu Sant'Elena (CA)                  
//               ITALY                                         
//               www.tecnick.com                               
//               info@tecnick.com                              
//============================================================+

/**
 * Configuration file for public section.
 * @author Nicola Asuni
 * @copyright Copyright &copy; 2004, Tecnick.com S.r.l. - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link www.tecnick.com
 * @since 2001-10-23
 */

// -- INCLUDE files -- 
require_once('../../shared/config/tce_extension.inc');
require_once('../../shared/config/tce_config.'.CP_EXT);

// -- DEFAULT META Tags --
define ("K_SITE_TITLE", "TCExam"); //default site name
define ("K_SITE_DESCRIPTION", "TCExam by Tecnick.com"); //default site description
define ("K_SITE_AUTHOR", "Nicola Asuni - Tecnick.com S.r.l."); //default site author
define ("K_SITE_REPLY", "info@tecnick.com"); //default page reply email
define ("K_SITE_KEYWORDS", "TCExam, eExam, e-exam, web, exam"); //default keywords
define ("K_SITE_ICON", "../../favicon.ico"); //default icon
define ("K_SITE_STYLE", K_PATH_STYLE_SHEETS."default.css"); //default stylesheet

// -- Options / COSTANTS --
define ("K_MAX_ROWS_PER_PAGE", 50); //max number of rows to display in tables
define ("K_MAX_UPLOAD_SIZE", 1000000); //max size to be uploaded in bytes
define ("K_MAX_EXECUTION_TIME", 3*K_SECONDS_IN_MINUTE); // [seconds] Limits the maximum execution time for a script

define ("K_MAX_MEMORY_LIMIT", "8M"); // max memory limit for a PHP script

define ("K_MAIN_PAGE", "index.".CP_EXT.""); // main page (homepage)

// -- INCLUDE files -- 
require_once('../../shared/config/tce_db_config.'.CP_EXT);
require_once('../../shared/code/tce_db_connect.'.CP_EXT);
require_once('../../shared/code/tce_functions_general.'.CP_EXT);

set_time_limit(K_MAX_EXECUTION_TIME); //Limit the maximum execution time
ini_set("memory_limit", K_MAX_MEMORY_LIMIT); //set memory limit
ini_set("session.use_trans_sid", 0); //if =1 use PHPSESSID (for clients that do not support cookies)

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
