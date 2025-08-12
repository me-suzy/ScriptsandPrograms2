<?php

  /**
    * $Id: index.php,v 1.9 2005/07/31 09:20:36 carsten Exp $
    *
    * main page for mandator management. Each request concerning mandators is build up like
    * modules/mandators/index.php?command=XXX
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      mandators
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    //if (!defined('EASY_FRAMEWORK_DIR'))
    //    define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    
    // === Logging =====================================================
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("mandators [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/mandators_mdl.php");
	require_once ("controllers/mandators_ctrl.php");
	
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
        "add_mandator"         => array ("success"        => "views/new_mandator.tpl"),
        "create_mandator"      => array ("success"        => "views/show_entries.tpl",
                                         "failure"        => "views/new_mandator.tpl"),
        "add_entry_view"       => array ("success"        => "views/mandator.tpl"),
		"delete_entry"         => array ("success"        => "views/show_entries.tpl",
                                         "failure"        => "views/show_entries.tpl"),
        "edit_mandator"        => array ("success"        => "views/mandator.tpl"),
        "edit_users"           => array ("success"        => "views/users.tpl"),
		"update_mandator"      => array ("success"        => "views/show_entries.tpl",
                                         "failure"        => "views/mandator.tpl"),
		"updateMandatorUsers"  => array ("success"        => "views/show_entries.tpl",
                                         "failure"        => "views/users.tpl"),
        "help"                 => array ("mandators"      => "views/help_mandators.tpl"), 
    	"show_entries"         => array ("success"        => "views/show_entries.tpl"),
    	//"edit_datagrid"        => array ("success"        => "views/edit_datagrid.tpl"),
    	//"edit_datagrid_column" => array ("success"        => "../common/views/datagrid_column.tpl"),
    	"switch_to_mandator"   => array ("success"        => "../common/views/reload.tpl",
    	                                 "failure"        => "views/show_entries.tpl"),
        "copy_from_dg"		   => array ("success"        => "views/show_entries.tpl",
                                         "failure"        => "views/show_entries.tpl"),
		"unset_current_view"   => array ("success"        => "views/empty.tpl")
    );

    // === Execute Easy_Script =========================================
	$myScript = new mandators_script($transistions);
	$myScript->run("mandators_model");
    $myScript->clean_up ();

?>

