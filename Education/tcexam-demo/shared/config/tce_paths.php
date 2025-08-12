<?php
//============================================================+
// File name   : tce_paths.php                                  
// Begin       : 2002-01-15                                    
// Last Update : 2005-01-02
//                                                             
// Description : Configuration file for files and directories
//               paths.
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
 * Configuration file for files and directories paths.
 * @author Nicola Asuni
 * @copyright Copyright &copy; 2004, Tecnick.com S.r.l. - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link www.tecnick.com
 * @since 2002-01-15
 */

// Normally you must change only the following 4 constant values

define ("K_PATH_HOST", ""); // host URL (e.g.: "http://www.yoursite.com")
define ("K_PATH_TCEXAM", ""); // relative URL where this program is installed (e.g.: "/")
define ("K_PATH_MAIN", ""); //real full path where this program is installed (e.g: "/usr/local/apache/htdocs/TCExam/")
define ("K_STANDARD_PORT", 80); // standard port

// ----------------------------------------
define ("K_PATH_PUBLIC_CODE", K_PATH_HOST.K_PATH_TCEXAM."public/code/"); //public code
define ("K_PATH_PUBLIC_CODE_REAL", K_PATH_MAIN."public/code/"); //server path of public code
define ("K_PATH_CACHE", K_PATH_MAIN."cache/"); //cache directory for temporary files (full path)
define ("K_PATH_URL_CACHE", K_PATH_TCEXAM."cache/"); //cache directory for temporary files (url path)
define ("K_PATH_FONTS", K_PATH_MAIN."fonts/"); //full font path
define ("FPDF_FONTPATH", K_PATH_FONTS); //path for PDF fonts
putenv("GDFONTPATH=".K_PATH_FONTS); //set GD library font path for GD2
define ("K_PATH_STYLE_SHEETS", "../styles/");
define ("K_PATH_JSCRIPTS", "../jscripts/");
define ("K_PATH_SHARED_JSCRIPTS", "../../shared/jscripts/");
define ("K_PATH_IMAGES", "../../images/");
define ("K_PATH_JAVA", "../java/");
define ("K_PATH_SHARED_JAVA", "../../shared/java/");
define ("K_PATH_TMX_FILE", K_PATH_MAIN."shared/config/lang/language_tmx.xml"); // tmx language file
define ("K_BLANK_IMAGE", K_PATH_IMAGES."_blank.png"); // blank image

//============================================================+
// END OF FILE                                                 
//============================================================+
?>