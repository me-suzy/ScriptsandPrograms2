<?php
//============================================================+
// File name   : tce_auth.php                                   
// Begin       : 2002-09-02                                    
// Last Update : 2004-06-15                                    
//                                                             
// Description : Define access levels for each admin page      
//               Note:                                         
//                0 = Anonymous user (uregistered user)        
//                1 = registered user                          
//               10 = System Administrator                     
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
 * congiguration file: define access levels for each admin page
 * @author Nicola Asuni
 * @copyright Copyright &copy; 2004, Tecnick.com S.r.l. - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link www.tecnick.com
 * @since 2002-09-02
 */

// ************************************************************
// SECURITY WARNING :
// SET THIS FILE AS READ ONLY AFTER MODIFICATIONS   
// ************************************************************

define ("K_AUTH_ADMIN_USERS", 10); // users administration
define ("K_AUTH_ADMIN_TCECODE", 10); // tcecode editor
define ("K_AUTH_ADMIN_SUBJECTS", 10); // Subjects administration
define ("K_AUTH_ADMIN_QUESTIONS", 10); // questions administration
define ("K_AUTH_ADMIN_ANSWERS", 10); // answers administration
define ("K_AUTH_ADMIN_TESTS", 10); // tests administration
define ("K_AUTH_ADMIN_INFO", 0); // TCExam information
define ("K_AUTH_ADMIN_ONLINE_USERS", 10); // display online users
define ("K_AUTH_ADMIN_UPLOAD_IMAGES", 10); // upload images
define ("K_AUTH_ADMIN_RATING", 10); // manually rate free text answers
define ("K_AUTH_ADMIN_RESULTS", 10); // display results

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
