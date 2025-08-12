<?php
//============================================================+
// File name   : tce_config.php                                 
// Begin       : 2001-09-02                                    
// Last Update : 2004-12-30                                    
//                                                             
// Description : Configuration file for administration section.
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
 * Configuration file for administration section.
 * @author Nicola Asuni
 * @copyright Copyright &copy; 2004, Tecnick.com S.r.l. - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link www.tecnick.com
 * @since 2001-09-02
 */

// -- INCLUDE files -- 
require_once('../../shared/config/tce_extension.inc');
require_once('../config/tce_auth.'.CP_EXT);
require_once('../../shared/config/tce_config.'.CP_EXT);

// -- Options / COSTANTS --
define ("K_MAX_EXECUTION_TIME", K_SECONDS_IN_HOUR); // [seconds] Limits the maximum execution time for a script
define ("K_MAX_MEMORY_LIMIT", "128M"); // max memory limit

define ("K_MAX_ROWS_PER_PAGE", 50); //max number of rows to display in tables
define ("K_MAX_UPLOAD_SIZE", 10000000); //max size to be uploaded in bytes

// -- DEFAULT META and BODY Tags --
define ("K_TCEXAM_TITLE", "TCExam");
define ("K_TCEXAM_DESCRIPTION", "TCExam by Tecnick.com");
define ("K_TCEXAM_AUTHOR", "Nicola Asuni - Tecnick.com S.r.l.");
define ("K_TCEXAM_REPLY_TO", "info@tecnick.com");
define ("K_TCEXAM_KEYWORDS", "TCExam, eExam, e-exam, web, exam");
define ("K_TCEXAM_ICON", "../../favicon.ico");
define ("K_TCEXAM_STYLE", K_PATH_STYLE_SHEETS."default.css");
define ("K_TCEXAM_HELP_STYLE", K_PATH_STYLE_SHEETS."help.css");

define ("K_CLOCK_IN_UTC", false); //if true display admin clock in UTC (GMT)
define ("K_SELECT_SUBSTRING", 40); // max number of chars to display on a selection box
define ("K_MENU_SCROLLING", false); //if true enable menu scrolling

// -- INCLUDE files -- 
require_once('../../shared/config/tce_db_config.'.CP_EXT);
require_once('../../shared/code/tce_db_connect.'.CP_EXT);
require_once('../../shared/code/tce_functions_general.'.CP_EXT);

ini_set("memory_limit", K_MAX_MEMORY_LIMIT); //set memory limit
ini_set("upload_max_filesize", K_MAX_UPLOAD_SIZE); //set max upload size
ini_set("post_max_size", K_MAX_UPLOAD_SIZE); //set max post size
ini_set("session.use_trans_sid", 0); //if =1 use PHPSESSID 
set_time_limit(K_MAX_EXECUTION_TIME); //Limit the maximum execution time

//============================================================+
// END OF FILE                                                 
//============================================================+
?>