<?php
/**
* Basic configuration for easy framework
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
* @todo 		check SESSSION_NAME for relevance
*/

// =======================================================================
// Handling Assertions
// see http://www.php.net/manual/de/function.assert-options.php
// =======================================================================
assert_options(ASSERT_ACTIVE,         1);
assert_options(ASSERT_WARNING,        1);
assert_options(ASSERT_CALLBACK,       "easy_assert_callback");

define ("ASSERTION_EXIT_MESSAGE",     "Execution stopped as of assertion");

if (!defined("SESSION_NAME"))
    define ("SESSION_NAME", session_name ());

?>