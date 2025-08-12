<?php

  /**
    * $Id: index.php,v 1.4 2005/07/31 09:20:36 carsten Exp $
    *
    * main page for datagrid management. Each request concerning mandators is build up like
    * modules/mandators/index.php?command=XXX
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      datagrids
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    
    // === Logging =====================================================
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("datagrids [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/datagrids_mdl.php");
	require_once ("controllers/datagrids_ctrl.php");
	
    // === Additional Includes =========================================
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid_column.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid2html.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.sqlparser.php");

    // === Application Includes ========================================
    include ("../../inc/functions.inc.php");
    include ("../../inc/events.inc.php");

    // === Basic Security Check ========================================
    security_check_core ();
        
    // === Load Translations ===========================================
    loadLanguageFile ("../../");
    
    // === PHPGACL =====================================================
    require_once ('../../extern/phpgacl/gacl.class.php');
    require_once ('../../extern/phpgacl/gacl_api.class.php');
    require_once ('../../inc/acl.inc.php');
    
    $gacl_api = new gacl_api($gacl_options);

	// === Database ====================================================
	$db_hdl = newADOCOnnection ('mysqlt');
    $db_hdl->Connect ($db_host, $db_user, $db_passwd, $db_name);
    $db_hdl->SetFetchMode(ADODB_FETCH_BOTH);

	// === Session Default Settings ====================================
	if (!isset ($_SESSION['easy_datagrid']['entries_per_page']))
    	$_SESSION['easy_datagrid']['entries_per_page'] = 20;

    // === transitions definition ======================================
    $transistions = array (
    	"edit_datagrid"        => array ("success"        => "views/datagrid.tpl"),
    	"edit_column"          => array ("success"        => "views/column.tpl"),
    	"update_datagrid"      => array ("success"        => "views/redirect.tpl",
    	                                 "failure"        => "views/datagrid.tpl"),
        "unset_current_view"   => array ("success"        => "views/empty.tpl")
    );

    // === Execute Easy_Script =========================================
	$myScript = new datagrids_script($transistions);
	$myScript->run("datagrids_model");
    $myScript->clean_up ();

?>

