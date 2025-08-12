<?php

  /**
    * $Id: index.php,v 1.4 2005/07/31 08:45:16 carsten Exp $
    *
    * main page. Each request concerning contacts is build up like
    * modules/###name###/index.php?command=XXX
    *
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    $logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    $logger->set_ident ("###name### [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/###name###_mdl.php");
	require_once ("controlers/###name###_ctrl.php");
	
    // === Additional Includes =========================================
	//require_once (EASY_FRAMEWORK_DIR."/classes/extern/adodb/adodb-time.inc.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid_column.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid2html.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.sqlparser.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/widgets/class.checkbox.php");

    // === Application Includes ========================================
    $add_module_path_offset = "../../";
    //include ("../../config/config.inc.php");
    include ("../../inc/functions.inc.php");

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
	if (!isset ($_SESSION['easy_datagrid']['entries_per_page']))
    	$_SESSION['easy_datagrid']['entries_per_page'] = 20;

    // === transitions definition ======================================
    $transistions = array (
        "add_folder"         => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "../common/views/folder.tpl"),
        "add_entry"          => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/###name###.tpl",
                                       "close"          => "views/close_window.tpl"),
        "add_###name###_att"       => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/###name###_as_attachment.tpl",
                                       "close"          => "views/close_window.tpl"),
        "add_entry_view"     => array ("success"        => "views/###name###.tpl"),
        "add_folder_view"    => array ("success"        => "../common/views/folder.tpl"),
        "add_###name###_att_view"  => array ("success"        => "views/###name###_as_attachment.tpl"),
        "add_ref_view"       => array ("success"        => "views/show_entries.tpl"),
        "clear_filter"       => array ("success"        => "views/show_entries.tpl"),
		"delete_entry"       => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/show_entries.tpl"),
        "delete_selected"    => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/show_entries.tpl"),
        "del_ref"            => array ("success"        => "views/empty.tpl"),                                       
        "edit_att_###name###"      => array ("success"        => "views/###name###_as_attachment.tpl"),
        "edit_entry"         => array ("success"        => "views/###name###.tpl",
                                       "redirect"       => "../common/views/folder.tpl"),
        "edit_folder"        => array ("success"        => "../common/views/folder.tpl"),
        "move_view"          => array ("success"        => "views/foldertree.tpl"),
        "move"               => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/show_entries.tpl"),
    	"show_entries"       => array ("success"        => "views/show_entries.tpl"),
    	"show_locked"        => array ("success"        => "views/show_entries.tpl"),
        "export_excel"       => array ("success"        => "views/show_entries.tpl"), 
        "update_att_###name###"    => array ("success"        => "views/###name###.tpl",
                                       "failure"        => "views/###name###_as_attachment.tpl"),
        "update_entry"       => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/###name###.tpl",
                                       "apply"          => "views/###name###.tpl"),
        "unset_current_view" => array ("success"        => "views/empty.tpl"),
        "help"               => array ("add_contact"    => "views/help_contact.tpl",
                                      "show_entries"   => "views/help_show_entries.tpl",
                                      "update_contact" => "views/help_contact.tpl") 
    );

    // === Execute Easy_Script =========================================
	$myScript = new ###name###_script($transistions);
	//$myScript->LANG = $LANG;
	$myScript->run("###name###_model");
    $myScript->clean_up ();

?>

