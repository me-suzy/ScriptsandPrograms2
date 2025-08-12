<?php
//============================================================+
// File name   : tce_general_constants.php                      
// Begin       : 2002-03-01                                    
// Last Update : 2004-12-30                                    
//                                                             
// Description : Configuration file for general constants.  
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
 * Configuration file for general constants.
 * @author Nicola Asuni
 * @copyright Copyright &copy; 2004, Tecnick.com S.r.l. - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link www.tecnick.com
 * @since 2002-03-01
 */

define ("K_SECONDS_IN_MINUTE", 60);
define ("K_SECONDS_IN_HOUR", 60 * K_SECONDS_IN_MINUTE);
define ("K_SECONDS_IN_DAY", 24 * K_SECONDS_IN_HOUR);
define ("K_SECONDS_IN_WEEK", 7 * K_SECONDS_IN_DAY);
define ("K_SECONDS_IN_MONTH", 30 * K_SECONDS_IN_DAY);
define ("K_SECONDS_IN_YEAR", 365 * K_SECONDS_IN_DAY);

// string used as a seed for some security code generation
// please change this value and keep it secret
define ("K_RANDOM_SECURITY", "aksye2ne"); 

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
