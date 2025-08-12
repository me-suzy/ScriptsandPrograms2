<?php

  /**
    * $Id: gacl.update.php,v 1.4 2005/07/08 13:24:32 carsten Exp $
    *
    * main page for mandator management. Each request concerning mandators is build up like
    * modules/mandators/index.php?command=XXX
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    */

	// === Base Configuration ==========================================
    include_once ("../../../config/config.inc.php");
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
        
	
    // === Additional Includes =========================================
	/*require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid_column.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.datagrid2html.php");
	require_once (EASY_FRAMEWORK_DIR."/classes/lib/class.sqlparser.php");*/

    // === Application Includes ========================================
    include ("../../../inc/functions.inc.php");
    include ("../../../inc/events.inc.php");
    
    // === PHPGACL =====================================================
    require_once ('../../../extern/phpgacl/gacl.class.php');
    require_once ('../../../extern/phpgacl/gacl_api.class.php');
    require_once ('../../../inc/acl.inc.php');
    
    $gacl_api = new gacl_api($gacl_options);

	// === Database ====================================================
	$db_hdl = newADOCOnnection ('mysqlt');
    $db_hdl->Connect ($db_host, $db_user, $db_passwd, $db_name);
    $db_hdl->SetFetchMode(ADODB_FETCH_BOTH);

    // === Mandatormanager ===
    // --- new acl section ---
    $aco_section_id = $gacl_api->add_object_section ('Mandatormanager','Mandatormanager',10, false, 'aco');

    // --- add acos ---
    
    $acos = array ();
    
    $gacl_api->add_object ('Mandatormanager', 'Show Mandatormanager', 'Show Mandatormanager', 1, false, 'aco');
    $gacl_api->add_object ('Mandatormanager', 'Add Mandator',         'Add Mandator',         2, false, 'aco');
    $gacl_api->add_object ('Mandatormanager', 'Edit Mandator',        'Edit Mandator',        3, false, 'aco');
    $gacl_api->add_object ('Mandatormanager', 'Delete Mandator',      'Delete Mandator',      4, false, 'aco');
    $gacl_api->add_object ('Mandatormanager', 'Edit Permissions',     'Edit Permissions',     5, false, 'aco');
    $gacl_api->add_object ('Mandatormanager', 'Edit Datagrid',        'Edit Datagrid',        6, false, 'aco');
    $gacl_api->add_object ('Mandatormanager', 'Switch Mandator',      'Switch Mandator',      7, false, 'aco');
    
    $acos[] = 'Show Mandatormanager';
    $acos[] = 'Add Mandator';
    $acos[] = 'Edit Mandator';
    $acos[] = 'Delete Mandator';
    $acos[] = 'Edit Permissions';
    $acos[] = 'Edit Datagrid';
    $acos[] = 'Switch Mandator';
    
    // --- add rights for superadmin ---
    echo "<br>====================================================<br>";
    	//function add_acl($aco_array, $aro_array, 
    	//$aro_group_ids=NULL, $axo_array=NULL, $axo_group_ids=NULL, $allow=1, $enabled=1, $return_value=NULL, $note=NULL, $section_value=NULL, $acl_id=FALSE ) {

    $gacl_api->add_acl (
        array ('Mandatormanager' => $acos),
        array ('Person' => array (2)),
        null, 
        null, 
        null, 
        true, 
        true,
        null,
        null,
        'user');

    // === Usermanager ===
    
    // --- add acos ---
    
    $acos = array ();
    
    $gacl_api->add_object ('Usermanager', 'Switch User', 'Switch User', 60, false, 'aco');
    

?>

Done