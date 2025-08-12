<?php

    die ("deprecated since 0.4.4, to be deleted");

  /**
    * $Id: content.php,v 1.6 2005/07/31 08:45:16 carsten Exp $
    *
    * Model for supersede. See easy_framework for more information
    * about controlers and models.
    * @package contacts
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    $logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    $logger->set_ident ("contacts [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/contacts_mdl.php");
	require_once ("controlers/contacts_ctrl.php");
	
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
	$_SESSION['easy_datagrid']['entries_per_page'] = 20;

    // === transitions definition ======================================
    $transistions = array (
        "assign_view"       => array ("success"        => "views/assign.tpl"),
    	"show_entries"      => array ("success"        => "views/show_entries.tpl"),
        "add_contact_view"  => array ("success"        => "views/contact.tpl"), 
        "add_contact"       => array ("success"        => "views/show_entries.tpl",
                                      "failure"        => "views/contact.tpl"),
        "delete_entry"      => array ("success"        => "views/show_entries.tpl",
                                      "failure"        => "views/show_entries.tpl"),
        "update_contact"    => array ("success"        => "views/show_entries.tpl",
                                      "failure"        => "views/contact.tpl"),
        "show_contact"      => array ("success"        => "views/contact.tpl"),
        "help"              => array ("add_contact"    => "views/help_contact.tpl",
                                      "show_entries"   => "views/help_show_entries.tpl",
                                      "update_contact" => "views/help_contact.tpl") 
    );

    // === Execute Easy_Script =========================================
	$myScript = new contacts_script($transistions);
	//$myScript->LANG = $LANG;
	$myScript->run("contacts_model");
    $myScript->clean_up ();

?>

