<?php

  /**
    * $Id: index.php,v 1.8 2005/07/31 09:20:36 carsten Exp $
    *
    * main page for faqs management. Each request concerning contacts is build up like
    * modules/faqs/index.php?command=XXX
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package faqs
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    //$logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    //$logger->set_ident ("faqs [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");
	require_once ("../notes/models/notes_mdl.php");
	require_once ("models/faqs_mdl.php");
	require_once ("controlers/faqs_ctrl.php");
	
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
	if (!isset ($_SESSION['easy_datagrid']['entries_per_page']))
    	$_SESSION['easy_datagrid']['entries_per_page'] = 20;

    // === transitions definition ======================================
    $transistions = array (
        "add_folder"         => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "../common/views/folder.tpl"),
        "add_entry"          => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/faq.tpl",
                                       "close"          => "views/close_window.tpl",
                                       "guestview"      => "views/suggestfaq.tpl",
                                       "thanks"         => "views/thanks.tpl"),
        "add_note_att"       => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/note_as_attachment.tpl",
                                       "close"          => "views/close_window.tpl"),
        "add_entry_view"     => array ("success"        => "views/faq.tpl",
                                       "guestview"      => "views/suggestfaq.tpl"),
        "add_folder_view"    => array ("success"        => "../common/views/folder.tpl"),
        "add_note_att_view"  => array ("success"        => "views/note_as_attachment.tpl"),
        "add_ref_view"       => array ("success"        => "views/show_entries.tpl"),
    	"browse"             => array ("success"        => "views/list.tpl"),
        "clear_filter"       => array ("success"        => "views/show_entries.tpl"),
		"delete_entry"       => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/show_entries.tpl"),
        "delete_selected"    => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/show_entries.tpl"),
        "del_ref"            => array ("success"        => "views/empty.tpl"),                                       
        "edit_att_note"      => array ("success"        => "views/note_as_attachment.tpl"),
        "edit_entry"         => array ("success"        => "views/faq.tpl",
                                       "redirect"       => "../common/views/folder.tpl"),
        "edit_folder"        => array ("success"        => "../common/views/folder.tpl"),
    	"list"               => array ("success"        => "views/list.tpl"),
        "move_view"          => array ("success"        => "views/foldertree.tpl"),
        "move"               => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/show_entries.tpl"),
    	"search_view"        => array ("success"        => "views/search.tpl"),
    	"show_entries"       => array ("success"        => "views/show_entries.tpl"),
    	"show_locked"        => array ("success"        => "views/show_entries.tpl"),
        "export_excel"       => array ("success"        => "views/show_entries.tpl"), 
        "update_att_note"    => array ("success"        => "views/faq.tpl",
                                       "failure"        => "views/note_as_attachment.tpl"),
        "update_entry"       => array ("success"        => "views/show_entries.tpl",
                                       "failure"        => "views/faq.tpl",
                                       "apply"          => "views/faq.tpl"),
        "unset_current_view" => array ("success"        => "views/empty.tpl"),
        "help"               => array ("add_contact"    => "views/help_contact.tpl",
                                      "show_entries"   => "views/help_show_entries.tpl",
                                      "update_contact" => "views/help_contact.tpl") 
    );

    // === Execute Easy_Script =========================================
	$myScript = new faqs_script($transistions);
	//$myScript->LANG = $LANG;
	$myScript->run("faqs_model");
    $myScript->clean_up ();

?>

