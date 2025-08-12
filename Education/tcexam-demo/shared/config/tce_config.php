<?php
//============================================================+
// File name   : tce_config.php                                
// Begin       : 2002-02-24                                    
// Last Update : 2005-04-30                                    
//                                                             
// Description : Shared configuration file.                    
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
 * Shared configuration file.
 * @author Nicola Asuni
 * @copyright Copyright &copy; 2004, Tecnick.com S.r.l. - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link www.tecnick.com
 * @since 2002-02-24
 */

 // -- Options / COSTANTS --
define ("K_TCEXAM_VERSION", "2.0.019"); // DO NOT CHANGE : program version
define ("K_LANGUAGE", "en"); // 2-letters language code

ini_set("zend.ze1_compatibility_mode", false); // disable PHP4 compatibility mode

// -- INCLUDE files -- 
require_once('../../shared/config/tce_extension.inc');
require_once('../../shared/config/tce_paths.'.CP_EXT);
require_once('../../shared/config/tce_general_constants.'.CP_EXT);

define ("K_ENABLE_NO_ASWER", true); // enable "no answer" as alternative answer
define ("K_ENABLE_RESULTS_TO_USERS", true); // enable users to view their test results
define ("K_TEST_INFO_HEIGHT", 400); // popup window height for test info
define ("K_TEST_INFO_WIDTH", 700); // popup window width for test info
define ("K_ANSWER_TEXTAREA_COLS", 70); // number of columns for answer textarea
define ("K_ANSWER_TEXTAREA_ROWS", 15); // number of rows for answer textarea
define ("K_SESSION_LIFE", K_SECONDS_IN_HOUR); // user's session life time in seconds
define ("K_TIMESTAMP_FORMAT", "Y-m-d H:i:s"); // define timestamp format

// Client Cookie settings
define ("K_COOKIE_DOMAIN", "");
define ("K_COOKIE_PATH", K_PATH_TCEXAM);
define ("K_COOKIE_SECURE", FALSE); 
define ("K_COOKIE_EXPIRE", K_SECONDS_IN_DAY); //cookie expire time (one year)

define ("K_REDIRECT_LOGIN_MODE", 3); //1,2,3 various pages redirection modes after login

// Error settings
//define ("K_ERROR_TYPES", E_ERROR | E_WARNING | E_PARSE); // define error reporting types
define ("K_ERROR_TYPES", E_ALL | E_STRICT); // define error reporting types for debug
define ("K_USE_ERROR_LOG", FALSE); //enable error logs (../log/tce_errors.log)
define ("K_ENABLE_JSERRORS", false); // if true display messages and errors on Javascript popup window
require_once('../../shared/code/tce_functions_errmsg.'.CP_EXT); // error handlers

// load language resources
require_once('../../shared/code/tce_tmx.'.CP_EXT); // TMX class
$lang_resources = new TMXResourceBundle(K_PATH_TMX_FILE, K_LANGUAGE); // istantiate new TMXResourceBundle object
$l = $lang_resources->getResource(); // language array

set_magic_quotes_runtime(0); //disable magic quotes
ini_set("arg_separator.output", "&amp;");
ini_set("magic_quotes_gpc", "On");
ini_set("register_long_arrays", "On");

// --- get posted variables (to be compatible with register_globals off)
foreach ($_POST as $postkey => $postvalue) {
	$$postkey = $postvalue;
}
 
//============================================================+
// END OF FILE                                                 
//============================================================+
?>
