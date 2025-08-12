<?php

  /**
    * $Id: index.php,v 1.14 2005/07/31 08:45:17 carsten Exp $
    *
    * Model for supersede. See easy_framework for more information
    * about controlers and models.
    * @package tree
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");

    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("tree [User #".$_SESSION['user_id']."]");
   
	// === Inclusion of model and controler ============================
	require_once ("models/tree_mdl.php");
	require_once ("controlers/tree_ctrl.php");
	
    // === Additional Includes =========================================
	//require_once (EASY_FRAMEWORK_DIR."/classes/extern/adodb/adodb-time.inc.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid_column.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid2html.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.sqlparser.php");
	//require_once (EASY_FRAMEWORK_DIR."/classes/widgets/class.checkbox.php");

    // === Application Includes ========================================
    $add_module_path_offset = "../../";
    //include ("../../config/config.inc.php");
    include ("../../inc/functions.inc.php");
    include ("../../inc/events.inc.php");
    
    // === Basic Security Check ========================================
    security_check_core ();
        
    // === Load Translations ===========================================
    loadLanguageFile ("../../");
    addLanguageFile  ("../../", "easyfaq/lang");

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
        "show_entries"      => array ("success"        => "views/show_entries.tpl"),
        "show_tree"         => array ("success"        => "views/tree.tpl"),
        "add_entry"         => array ("success"        => "views/show_entries.tpl",
                                      "failure"        => "views/entry.tpl"),
        "delete_entry"      => array ("success"        => "views/show_entries.tpl",
                                      "failure"        => "views/show_entries.tpl"),
        "edit_entry"        => array ("success"        => "views/entry.tpl"),
        "tree"              => array ("success"        => "views/navigation.tpl"),
        "verticaltabs"      => array ("success"        => "views/verticaltabs.tpl"),
        "order_down"        => array ("success"        => "views/show_entries.tpl"),
        "order_up"          => array ("success"        => "views/show_entries.tpl"),
        "show_auth"         => array ("success"        => "views/auth.tpl"),
        "show_contact"      => array ("success"        => "views/contact.tpl"),
        "update_auth"       => array ("success"        => "views/show_entries.tpl",
                                      "failure"        => "views/auth.tpl"),        
        "update_entry"      => array ("success"        => "views/show_entries.tpl",
                                      "failure"        => "views/entry.tpl"),      
        "use_template"      => array ("success"        => "views/show_entries.tpl",
                                      "failure"        => "views/entry.tpl"),                                              
        "help"              => array ("add_contact"    => "views/help_contact.tpl",
                                      "show_entries"   => "views/help_show_entries.tpl",
                                      "update_contact" => "views/help_contact.tpl") 
    );

    // === Execute Easy_Script =========================================
	$myScript = new tree_script($transistions);
	//$myScript->LANG = $LANG;
	$myScript->run("tree_model");
    $myScript->clean_up ();

?>

