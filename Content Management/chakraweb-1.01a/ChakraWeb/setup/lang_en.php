<?php 
// ----------------------------------------------------------------------
// ModName: lang_id.php
// Purpose: Setup Definitiion for English Language
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------


// ----------------------------------------------------------------------
// Setup Stages Titles
// ----------------------------------------------------------------------

define('SETUP_STAGE_START_TITLE', 'ChakraWeb Setup');
define('SETUP_STAGE_CHMOD_TITLE', 'ChakraWeb Setup: CHMOD Check');
define('SETUP_STAGE_WEBINFO_TITLE', 'ChakraWeb Setup: Website Information');
define('SETUP_STAGE_DBINFO_TITLE', 'ChakraWeb Setup: Database Information');
define('SETUP_STAGE_DBCREATE_TITLE', 'ChakraWeb Setup: Create Database And Tables');
define('SETUP_STAGE_ADMIN_TITLE', 'ChakraWeb Setup: Administrative Account');
define('SETUP_STAGE_FINISH_TITLE', 'ChakraWeb Setup: Finish');

// ----------------------------------------------------------------------
// Setup Stage Navigation
// ----------------------------------------------------------------------

define('PREVIOUS_STAGE', 'Back to Previous Stage');
define('NEXT_STAGE', 'Next Stage');

// ----------------------------------------------------------------------
// Setup Status
// ----------------------------------------------------------------------

define('STATUS_SUCCESS', 'Succeeded');
define('STATUS_FAILED', 'Failed');
define('STATUS_ALREADY_CCREATED', 'Already Created');
define('STATUS_UNKNOWN', 'Unknown');
define('STATUS_OK', 'OK');

// ----------------------------------------------------------------------
// Setup Error Messages
// ----------------------------------------------------------------------

define('SETUP_ERROR_HPINFO', 'Please enter all field of your homepage information.');
define('SETUP_ERROR_ADMIN_INFO', 'Please enter the correct name, fullname, and email of administrator');
define('SETUP_ERROR_ADMIN_PASSWORD', 'The password is invalid, or the two password are not match.');


// ----------------------------------------------------------------------
// Other Definitions
// ----------------------------------------------------------------------

define ('CHMOD_SUCCESS_FMT', 'File permissions for <b>%s</b> are 666 -- correct, this script can write to the file');
define ('CHMOD_FAILED_FMT', 'Unable to change file permissions for <b>%s</b> to 666 -- this script cannot write to the file');

define('DB_INFO_FMT', '%s at %s');

define('GUEST_ACCOUNT', 'guest');
define('GUEST_ACCOUNT_FULLNAME', 'Website Guest');

?>
