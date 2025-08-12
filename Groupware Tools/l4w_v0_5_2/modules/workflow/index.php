<?php

  /**
    * $Id: index.php,v 1.12 2005/07/31 09:20:36 carsten Exp $
    *
    * Model for supersede. See easy_framework for more information
    * about controlers and models.
    *
    * @package workflow
    */
    
	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");

    // === Logging =====================================================
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("workflow [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/workflow_mdl.php");
	require_once ("controlers/workflow_ctrl.php");
	
    // === Additional Includes =========================================
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid_column.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid2html.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.sqlparser.php");

    // === Application Includes ========================================
    $add_module_path_offset = "../../";

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
	$db_hdl = newADOCOnnection ('mysql');
    $db_hdl->Connect ($db_host, $db_user, $db_passwd, $db_name);
    $db_hdl->SetFetchMode(ADODB_FETCH_BOTH);

	// === Session Default Settings ====================================
	$_SESSION['easy_datagrid']['entries_per_page'] = 20;

    // === transitions definition ======================================
    $transistions = array (
        "add_status"        => array ("success"        => "views/add_status.tpl"),   
        "add_transition"    => array ("success"        => "views/add_transition.tpl"),   
        "create_status"     => array ("success"        => "views/show_states.tpl",
                                      "failure"        => "views/add_status.tpl"),   
        "delete_status"     => array ("success"        => "views/show_states.tpl",
                                      "failure"        => "views/show_states.tpl"),   
        "create_transition" => array ("success"        => "views/show_transitions.tpl",
                                      "failure"        => "views/add_transition.tpl"),   
        "delete_transition" => array ("success"        => "views/show_transitions.tpl",
                                      "failure"        => "views/show_transitions.tpl"),   
        "edit_status"       => array ("success"        => "views/status.tpl"),                                      
        "update_status"     => array ("success"        => "views/show_states.tpl",
                                      "failure"        => "views/status.tpl"),   
        "show_references"   => array ("success"        => "views/show_references.tpl"),   
        "show_states"       => array ("success"        => "views/show_states.tpl"),   
        "show_transitions"  => array ("success"        => "views/show_transitions.tpl"),   
        "copy_from_dg"		=> array ("success"        => "views/show_references.tpl",
                                      "failure"        => "views/show_references.tpl"),
        "set_startpoint"    => array ("success"        => "../common/views/empty.tpl"),   
        "set_endpoint"      => array ("success"        => "../common/views/empty.tpl"),   
        "help"              => array ("add_contact"    => "views/help_contact.tpl",
                                      "show_entries"   => "views/help_show_entries.tpl",
                                      "update_contact" => "views/help_contact.tpl") 
    );

    // === Execute Easy_Script =========================================
	$myScript = new workflow_script($transistions);
	$myScript->run("workflow_model");
    $myScript->clean_up ();

?>

