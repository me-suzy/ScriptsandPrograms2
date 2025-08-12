<?php

  /**
    * $Id: index.php,v 1.10 2005/08/04 19:56:32 carsten Exp $
    *
    * main page for document management. Each request concerning contacts is build up like
    * modules/docs/index.php?command=XXX
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      docs
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("docs [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/docs_mdl.php");
	require_once ("controllers/docs_ctrl.php");
	
    // === Additional Includes =========================================
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid_column.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid2html.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.sqlparser.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/widgets/class.checkbox.php");

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
	if (!isset ($_SESSION['easy_datagrid']['entries_per_page']))
    	$_SESSION['easy_datagrid']['entries_per_page'] = 20;

    // === transitions definition ======================================
    $transistions = array (
        "add_folder_view"    => array ("success"        => "views/folder.tpl"),
        "add_doc"       => array ("success"        => "views/doc.tpl"),
        "edit_entry"         => array ("folder"         => "views/folder.tpl",
                                       "doc"            => "views/doc.tpl"),
    	"show_entries"       => array ("success"        => "views/show_entries.tpl"),
    	"show_locked"        => array ("success"        => "views/show_entries.tpl"),
        "add_contact_view"   => array ("success"        => "views/contact.tpl"), 
        "create_doc"            => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/doc.tpl"),
        "add_folder"         => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/folder.tpl"),
        "delete_entry"       => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/show_entries.tpl"),
        "export_excel"       => array ("success"        => "views/show_entries.tpl"), 
        "update_contact"     => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/contact.tpl"),
        "show_doc"           => array ("success"        => "views/showdoc.tpl",
                                       "failure"        => "views/showdoc.tpl"),
        "unset_current_view" => array ("success"        => "views/empty.tpl"),
        "copy_from_dg"		 => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/show_entries.tpl"),
		"help"               => array ("add_contact"    => "views/help_contact.tpl",
                                      "show_entries"    => "views/help_show_entries.tpl",
                                      "update_contact"  => "views/help_contact.tpl") 
    );

    // === Execute Easy_Script =========================================
	$myScript = new docs_script($transistions);
	$myScript->run("docs_model");
    $myScript->clean_up ();

?>

