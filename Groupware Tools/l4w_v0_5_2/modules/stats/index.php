<?php

  /**
    * $Id: index.php,v 1.9 2005/07/31 09:20:36 carsten Exp $
    *
    * Model for supersede. See easy_framework for more information
    * about controlers and models.
    * @package stats
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("stats [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/stats_mdl.php");
	require_once ("controlers/stats_ctrl.php");
	
    // === Additional Includes =========================================
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid_column.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid2html.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.sqlparser.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/widgets/class.checkbox.php");

    include (JPGRAPH_PATH."/jpgraph.php");
	include (JPGRAPH_PATH."/jpgraph_line.php");
	include (JPGRAPH_PATH."/jpgraph_error.php");
	include (JPGRAPH_PATH."/jpgraph_bar.php");
	include (JPGRAPH_PATH."/jpgraph_gantt.php");


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
	$db_hdl = newADOCOnnection ('mysql');
    $db_hdl->Connect ($db_host, $db_user, $db_passwd, $db_name);
    $db_hdl->SetFetchMode(ADODB_FETCH_BOTH);

	// === Session Default Settings ====================================
	$_SESSION['easy_datagrid']['entries_per_page'] = 20;

    // === transitions definition ======================================
    $transistions = array (
        "show_requests"         => array ("success"      => "views/requests.tpl"),
        "show_workflow_history" => array ("success"      => "views/workflow.tpl"),
        "help"                  => array ("show_users"   => "views/help_show_users.tpl",
                                          "users_groups" => "views/help_users_groups.tpl",
                                          "summary"      => "views/help_summary.tpl") 
    );    
    
    // === Execute Easy_Script =========================================
	$myScript = new stats_script($transistions);
	$myScript->run("stats_model");
    $myScript->clean_up ();

?>

