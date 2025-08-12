<?php

/**
* This is the file you have to include into your scripts to use easy_framework.
* Simply add a line like "require_once (PATH_TO_EASY.'/easy_framework.inc.php')"
* to the beginning of your script.
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

    // --- Configure constant EASY_FRAMEWORK_DIR --------------------
	if (!defined ("EASY_FRAMEWORK_DIR"))
		define ("EASY_FRAMEWORK_DIR", dirname(__FILE__));

    // --- Configuration of Easy Framework --------------------------
    require_once (EASY_FRAMEWORK_DIR."/config/config.inc.php");
     
    // --- Include Core Classes -------------------------------------
	include ("classes/easy.class.php");
	include ("classes/easy_assertion.class.php");
	//include ("classes/abstract_logger_wrapper.class.php");
	//include ("classes/easy_logger_wrapper.class.php");
	include ("classes/datatype.class.php");
    include ("classes/datatypes/easy_select.class.php");
    include ("classes/datatypes/resource.class.php");
    include ("classes/datatypes/easy_string.class.php");
	include ("classes/datatypes/string_restrict.class.php");
    include ("classes/datatypes/query_string.class.php");
    include ("classes/datatypes/email_string.class.php");
	include ("classes/datatypes/escaped_string.class.php");
    //include ("classes/datatypes/link.class.php");
    //include ("classes/datatypes/collection.class.php");
    include ("classes/datatypes/easy_array.class.php");
    include ("classes/datatypes/easy_int.class.php");
    include ("classes/datatypes/date.class.php");

    // --- fundamental inclusions -----------------------------------
    require_once (EASY_FRAMEWORK_DIR."/classes/easy_model.class.php");
    require_once (EASY_FRAMEWORK_DIR."/classes/easy_controller.class.php");

	// --- Extern Classes -------------------------------------------
	// if you don't one or all of these classes, you can comment out
	// the appropriate lines without problems to increase performance
	// @todo: get rid of Log-related classes in easy framework (logging
	// should not be part of the framework)
	//require_once ("classes/extern/pear/Log/Log.php");
	//require_once (ADODB_INC_FILE);
	//require_once (SMARTY_INC_FILE);

    // --- initiate -------------------------------------------------
    @session_name (SESSION_NAME);
    session_start();
    $easy = new easy();
    
    /**
    * Calls easys assert function
    *
    * You don't have to call this function yourself.
    * If you use assertions as in testapps/assertion_example,
    * this function is called automatically and works as a wrapper for
    * the class $easy->assertion (which defaults to classes/standard_assertion.class.php)
    *
    * See configuration file config/config.inc.php
    *
    * @param string  $file      name of file throwing the assertion
    * @param int     $line      number of line of file throwing the assertion
    * @param string  $code      information about the code throwing the assertion
    *
    **/
    function easy_assert_callback ($file, $line, $code) {
        global $easy;
        
        $tmp = array ($code);
        if (function_exists ('debug_backtrace')) { // since php version >= 4.3.0
        	$tmp = debug_backtrace();
        	$tmp[] = $code;
		}
    	$easy->assertion->throw_assertion($file, $line, $tmp);
    }
?>
