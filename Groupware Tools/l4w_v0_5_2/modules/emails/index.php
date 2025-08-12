<?php

  /**
    * $Id: index.php,v 1.18 2005/07/31 09:20:36 carsten Exp $
    *
    * main page. Each request concerning contacts is build up like
    * modules/mail/index.php?command=XXX
    *
    * @author       
    * @copyright    
    * @package      
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("mail [User #".$_SESSION['user_id']."]");
    
	ini_set ("memory_limit", "64M");
	set_time_limit(18000);
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("models/mail_mdl.php");
	require_once ("controlers/mail_ctrl.php");
	
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
        "add_account_view"       => array ("success"        => "views/account.tpl"),
        "add_account"            => array ("success"        => "views/show_accounts.tpl",
                                           "failure"        => "views/account.tpl"),
        "create_mail"            => array ("success"        => "views/sendmail.tpl"),
        "send_mail"              => array ("success"        => "views/confirmation.tpl",
                                           "failure"        => "views/sendmail.tpl"),
		"delete_from_trash"      => array ("success"        => "views/mails.tpl",
                                           "failure"        => "views/mails.tpl"),
        "edit_account"           => array ("success"        => "views/account.tpl"),
        "get_mails"              => array ("success"        => "views/execute.tpl"),
        "move2trash"             => array ("success"        => "views/mails.tpl",
                                           "failure"        => "views/mails.tpl"),
		"show_accounts"          => array ("success"        => "views/show_accounts.tpl"),
		"show_attachments"       => array ("success"        => "views/attachments.tpl"),
        "show_content"           => array ("success"        => "views/mail_iframe.tpl"),
		"show_header"            => array ("success"        => "views/header.tpl"),
		"show_log"               => array ("success"        => "views/log.tpl"),
		"show_mail"              => array ("success"        => "views/mail.tpl"),
		"show_mails"             => array ("success"        => "views/mails.tpl"),
		"show_mails_for_contact" => array ("success"        => "views/mails_for_contact.tpl"),
		"show_pic"               => array ("success"        => "views/pic.tpl"),
		"update_account"         => array ("success"        => "views/show_accounts.tpl",
                                           "failure"        => "views/account.tpl"),
        "unset_current_view"     => array ("success"        => "views/empty.tpl"),
        "help"                   => array ("add_contact"    => "views/help_contact.tpl",
                                           "show_entries"   => "views/help_show_entries.tpl",
                                           "update_contact" => "views/help_contact.tpl") 
    );

    // === Execute Easy_Script =========================================
	$myScript = new mail_script($transistions);
	//$myScript->LANG = $LANG;
	$myScript->run("mail_model");
    $myScript->clean_up ();

?>

