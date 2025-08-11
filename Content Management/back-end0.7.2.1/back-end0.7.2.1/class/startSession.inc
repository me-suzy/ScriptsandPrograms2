<?php
    
   /***
    * This file will be run for every new session that is initialized
    * by slashSess.class. It is specified by the $auto_init class variable
    * defined in slashSess.class
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: startSession.inc,v 1.3 2005/03/17 15:36:58 mgifford Exp $
    */
    
   global $sess, $BE_currentLanguage;
   $sess->register('BE_currentLanguage');
    
   // for now, just switch to the default language at the start of the session
   // TO DO: make this smarter about checking for browser language setting
   //        or looking in a cookie
   //        - the code should ONLY change $BE_currentLanguage
   // PAC Removed the switch_language call here because it is made later on through
   //     setCurrentLanguage()
   #switchLanguage($_BE['Default_language']);
    
?>
