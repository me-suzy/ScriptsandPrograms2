<?php
/****************************************************************************
   openbiz core path
 ****************************************************************************/
//define('OPENBIZ_HOME', 'absolute_dir/Openbiz');
define('OPENBIZ_HOME',$_SERVER['DOCUMENT_ROOT']."/openbiz");

/****************************************************************************
   application related path
 ****************************************************************************/
define('APP_HOME',dirname(dirname(__FILE__)));

define('META_PATH',APP_HOME."/metadata");

/* Log file path */
define("LOG_PATH", APP_HOME."/log");

/* path to save session files */
define("SESSION_PATH", APP_HOME."/session");

/* smarty template path */
define('SMARTY_TPL_PATH', APP_HOME."/templates");
define('SMARTY_CPL_PATH', APP_HOME."/templates/cpl");
define('SMARTY_CFG_PATH', APP_HOME."/templates/cfg");

/****************************************************************************
   application system level constances
 ****************************************************************************/
/* whether print debug infomation or not */
define("DEBUG", 0);

/* check whether user logged in */
define("CHECKUSER", "Y");
/* session timeout seconds */
define("TIMEOUT", -1);  // -1 means never timeout.

include_once(OPENBIZ_HOME."/bin/sysheader.inc");