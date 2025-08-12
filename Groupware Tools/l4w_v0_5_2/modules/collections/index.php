<?php

  /**
    * $Id: index.php,v 1.12 2005/07/31 09:20:36 carsten Exp $
    *
    * main page for categories management. Each request concerning categories is build up like
    * modules/categories/index.php?command=XXX
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      categories
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("categories [User #".$_SESSION['user_id']."]");
 
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/collections_mdl.php");
	require_once ("controlers/collections_ctrl.php");
	
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
        "copy_from_dg"		 => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/show_entries.tpl"),
        "add_category" => array ("success"       => "views/new_category.tpl"),
        "add_folder"    => array ("success"        => "views/new_folder.tpl"),         // no group and access rights needed
        "create_folder"         => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/new_folder.tpl"),
        "delete_entry"       => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/show_entries.tpl"),
        "edit_category"      => array ("success"        => "views/category.tpl"),
        "edit_folder"        => array ("success"        => "views/folder.tpl"),
    	"show_entries"       => array ("success"        => "views/show_entries.tpl"),
        "create_category"       => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/new_category.tpl"),
        "update_category"    => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/category.tpl"),
        "update_folder"      => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/folder.tpl"),
        "help"               => array ("add_contact"    => "views/help_contact.tpl",
                                       "show_entries"   => "views/help_show_entries.tpl",
                                       "update_contact" => "views/help_contact.tpl") 
    );

    // === Execute Easy_Script =========================================
	$myScript = new collections_script($transistions);
	$myScript->run("collections_model");
    $myScript->clean_up ();

?>

