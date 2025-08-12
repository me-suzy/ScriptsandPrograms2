<?php
//============================================================+
// File name   : tce_db_config.php                              
// Begin       : 2001-09-02                                    
// Last Update : 2004-12-26                                    
//                                                             
// Description : Database congiguration file.                  
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
 * Database congiguration file.
 * @author Nicola Asuni
 * @copyright Copyright &copy; 2004, Tecnick.com S.r.l. - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link www.tecnick.com
 * @since 2001-09-02
 */

define ("K_DATABASE_TYPE", ""); // database type (for Database Abstraction Layer)
define ("K_DATABASE_HOST", ""); // database Host name (eg: localhost)
define ("K_DATABASE_PORT", ""); // database port (eg: 3306 or 5432)
define ("K_DATABASE_NAME", ""); // database name (TCExam)
define ("K_DATABASE_USER_NAME", ""); // database user name
define ("K_DATABASE_USER_PASSWORD", ""); // database user password

// prefix for database tables names
define ("K_TABLE_PREFIX", "tce_");

// --- database tables names (do not change)
define ("K_TABLE_SESSIONS", K_TABLE_PREFIX."sessions");
define ("K_TABLE_USERS", K_TABLE_PREFIX."users");
define ("K_TABLE_SUBJECTS", K_TABLE_PREFIX."subjects");
define ("K_TABLE_QUESTIONS", K_TABLE_PREFIX."questions");
define ("K_TABLE_ANSWERS", K_TABLE_PREFIX."answers");
define ("K_TABLE_TESTS", K_TABLE_PREFIX."tests");
define ("K_TABLE_TEST_USER", K_TABLE_PREFIX."tests_users");
define ("K_TABLE_TEST_SUBJECTS", K_TABLE_PREFIX."test_subjects");
define ("K_TABLE_TESTS_LOGS", K_TABLE_PREFIX."tests_logs");
define ("K_TABLE_LOG_ANSWER", K_TABLE_PREFIX."tests_logs_answers");

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
