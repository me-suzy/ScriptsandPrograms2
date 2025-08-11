<?php
// ----------------------------------------------------------------------
// ModName: _config.php
// Purpose: Definition and include all other library files
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

$PhpSelf = $_SERVER['PHP_SELF'];
$PhpReferer = $_SERVER['HTTP_REFERER'];
$PhpRemoteAddr = $_SERVER['REMOTE_ADDR'];
$PhpMagicQuote = get_magic_quotes_gpc();

if (eregi("_config.php", $PhpSelf))
    die ("Access Denied");

//this file and all includes file loaded as library
define(LOADED_AS_LIBRARY, 1);


// ----------------------------------------------------------------------
// FILE INCLUDES
// ----------------------------------------------------------------------
require_once('_defgenerate.php');
require_once('_defgeneral.php');
require_once('fun_utils.php');
require_once('cls_dbase.php');
require_once('fun_session.php');
require_once('fun_dbvars.php');
require_once('fun_dbutils.php');
require_once('fun_system.php');
require_once('fun_string.php');
require_once('fun_user.php');
require_once('fun_member.php');
require_once('fun_template.php');
require_once('fun_content.php');
require_once('fun_web.php');
require_once('fun_stats.php');


InitSystemVars();

//CONNECTION TO DATABASE
$db = new DBase();
if (!$db->Connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME))
	die();

// Start session
if (!SessionBegin())
	SystemFatalError('Session initialisation failed');

CheckSystemMainenanceTime();
InitWebPage();

$lang_module = "../_lang/".UserGetLID().'/global.php';
require_once($lang_module);



?>
