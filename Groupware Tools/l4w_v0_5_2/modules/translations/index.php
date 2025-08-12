<?php

  /**
    * $Id: index.php,v 1.7 2005/07/31 09:20:36 carsten Exp $
    *
    * main page. Each request concerning contacts is build up like
    * modules/translations/index.php?command=XXX
    *
    * @author       Carsten Gräf
    * @copyright    evandor media GmbH
    * @package      translations
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("translations [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/translations_mdl.php");
	require_once ("controlers/translations_ctrl.php");
	
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
        "edit_language"          => array ("success"        => "views/edit_language.tpl"),
        "create_language_file"   => array ("success"        => "views/main_view.tpl",
                                           "failure"        => "views/main_view.tpl"),
        "generate_language"      => array ("success"        => "views/new_lang_view2.tpl",
                                           "failure"        => "views/new_lang_view1.tpl"),
        "edit_text"              => array ("success"        => "views/text.tpl"),
        "load_existing_language" => array ("success"        => "views/main_view.tpl",
                                           "failure"        => "views/load_lang.tpl"),
        "load_lang_view"         => array ("success"        => "views/load_lang.tpl"),
        "main_view"              => array ("success"        => "views/main_view.tpl"),
        "new_lang_view1"         => array ("success"        => "views/new_lang_view1.tpl"),
        "remove_language"        => array ("success"        => "views/main_view.tpl",
                                           "failure"        => "views/main_view.tpl"),
        "set_text"               => array ("success"        => "../common/views/close_window.tpl",
                                           "failure"        => "views/text.tpl"),
        "test_language"          => array ("success"        => "views/main_view.tpl"),                                   
        "help"                   => array ("add_contact"    => "views/help_contact.tpl",
                                           "show_entries"   => "views/help_show_entries.tpl",
                                           "update_contact" => "views/help_contact.tpl"), 
        "update_language"        => array ("success"        => "views/main_view.tpl",
                                           "failure"        => "views/edit_language.tpl"),
    );

    // === Execute Easy_Script =========================================
	$myScript = new translations_script($transistions);
	//$myScript->LANG = $LANG;
	$myScript->run("translations_model");
    $myScript->clean_up ();

?>

