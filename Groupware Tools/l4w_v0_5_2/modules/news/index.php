<?php

  /**
    * $Id: index.php,v 1.7 2005/07/31 09:20:36 carsten Exp $
    *
    * Model for supersede. See easy_framework for more information
    * about controlers and models.
    * @package news
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("news [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/news_mdl.php");
	require_once ("controlers/news_ctrl.php");
	
    // === Additional Includes =========================================
	//require_once (EASY_FRAMEWORK_DIR."/classes/extern/adodb/adodb-time.inc.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid_column.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid2html.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.sqlparser.php");
	//require_once (EASY_FRAMEWORK_DIR."/classes/widgets/class.checkbox.php");

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
    	"show_all_news"      => array ("success"        => "views/show_all_news.tpl"),
    	"show_current_news"  => array ("success"        => "views/show_current_news.tpl"),
        "help"               => array ("add_contact"    => "views/help_contact.tpl",
                                      "show_entries"   => "views/help_show_entries.tpl",
                                      "update_contact" => "views/help_contact.tpl") 
    );

    // === Execute Easy_Script =========================================
	$myScript = new news_script($transistions);
	$myScript->run("news_model");
    $myScript->clean_up ();

?>

