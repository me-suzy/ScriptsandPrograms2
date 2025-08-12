<?php

  /**
    * $Id: index.php,v 1.2 2005/08/04 07:28:11 carsten Exp $
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
	require_once ("../../modules/common/leads4web_model.php");
	/*require_once ("models/collections_mdl.php");
	require_once ("controlers/collections_ctrl.php");*/
	
    // === Additional Includes =========================================
 
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

    // === fire events accoring to due dates ===========================
    
    // --- tickets -----------------------------------------------------
    $query = "
        SELECT * FROM ".TABLE_PREFIX."tickets 
        WHERE 
            due < now() AND
            reminded='0' 
    ";
    echo $query;
    $res = mysql_query ($query);
    echo mysql_error();
    while ($row = mysql_fetch_array ($res)) {

    	$logger->log ('Calling file '.__FILE__, 7);
    	
        // 1. Fire event
            //function fireEvent (&$model, $reference, $event, $type, $object_id) {
        $dummyModel = null;
        fireEvent ($dummyModel, 'ticket', 'ticket reminder', 'cron', $row['ticket_id']);
        
        // 2. Set reminded to '1'
           
        // 3. Log event
        $logger->log ('fire event was called in '.__FILE__.' for ticket reminder with id '.$row['ticket_id'], 4);
            
    }    
    

?>

