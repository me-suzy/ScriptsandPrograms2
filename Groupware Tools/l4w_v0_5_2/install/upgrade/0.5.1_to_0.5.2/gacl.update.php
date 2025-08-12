<?php

  /**
    * $Id: gacl.update.php,v 1.5 2005/08/04 19:57:31 carsten Exp $
    *
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    */

	// === Base Configuration ==========================================
    include_once ("../config/config.inc.php");
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
        
    // === Additional Includes =========================================

    // === Application Includes ========================================
    //include ("../inc/functions.inc.php");
    //include ("../inc/events.inc.php");
    
    // === PHPGACL =====================================================
    require_once ('../extern/phpgacl/gacl.class.php');
    require_once ('../extern/phpgacl/gacl_api.class.php');
    require_once ('../inc/acl.inc.php');
    
    $gacl_api = new gacl_api($gacl_options);

	// === Database ====================================================
	$db_hdl = newADOCOnnection ('mysqlt');
    $db_hdl->Connect ($db_host, $db_user, $db_passwd, $db_name);
    $db_hdl->SetFetchMode(ADODB_FETCH_BOTH);

    // === StatsManager ===
    echo "GACL: Updating / Creating StatsManager<br>";
    
    // --- new acl section ---
    $aco_section_id = $gacl_api->add_object_section ('StatsManager','StatsManager',10, false, 'aco');

    // --- add acos ---
    $acos = array ();
    
    $gacl_api->add_object ('StatsManager', 'Show BasicPageStats', 'Show BasicPageStats', 1, false, 'aco');
    $gacl_api->add_object ('StatsManager', 'Show GroupPageStats', 'Show GroupPageStats', 2, false, 'aco');
    $gacl_api->add_object ('StatsManager', 'Show UserPageStats',  'Show UserPageStats',  3, false, 'aco');
    $gacl_api->add_object ('StatsManager', 'Edit Permissions',    'Edit Permissions',    4, false, 'aco');
    
    $acos[] = 'Show BasicPageStats';
    $acos[] = 'Show GroupPageStats';
    $acos[] = 'Show UserPageStats';
    $acos[] = 'Edit Permissions';
    
    // --- add rights for superadmin ---
    echo "<br>====================================================<br>";
    	//function add_acl($aco_array, $aro_array, 
    	//$aro_group_ids=NULL, $axo_array=NULL, $axo_group_ids=NULL, $allow=1, $enabled=1, $return_value=NULL, $note=NULL, $section_value=NULL, $acl_id=FALSE ) {

    $gacl_api->add_acl (
        array ('StatsManager' => $acos),
        array ('Person' => array (2)),
        null, 
        null, 
        null, 
        true, 
        true,
        null,
        null,
        'user');

   
    // === CategoryManager ===
    echo "GACL: Updating / Creating CategoryManager<br>";
    
    // --- new acl section ---
    $aco_section_id = $gacl_api->add_object_section ('CategoryManager','CategoryManager',11, false, 'aco');

    // --- add acos ---
    $acos = array ();
    
    $gacl_api->add_object ('CategoryManager', 'Add Category',         'Add Category',        1, false, 'aco');
    $gacl_api->add_object ('CategoryManager', 'Edit Category',        'Edit Category',       2, false, 'aco');
    $gacl_api->add_object ('CategoryManager', 'Delete Category',      'Delete Category',     3, false, 'aco');
    $gacl_api->add_object ('CategoryManager', 'Edit Permissions',     'Edit Permissions',    4, false, 'aco');
    $gacl_api->add_object ('CategoryManager', 'Show CategoryManager', 'Show CategoryManager',5, false, 'aco');
    $gacl_api->add_object ('CategoryManager', 'Edit Datagrid',        'Edit Datagrid',       6, false, 'aco');
    
    $acos[] = 'Add Category';
    $acos[] = 'Edit Category';
    $acos[] = 'Delete Category';
    $acos[] = 'Edit Permissions';
    $acos[] = 'Show CategoryManager';
    $acos[] = 'Edit Datagrid';
    
    // --- add rights for superadmin ---
    echo "<br>====================================================<br>";
    
    // --- delete existing acls first ---
    $query = "
        SELECT distinct(aco.acl_id) from ".TABLE_PREFIX."gacl_aco_map aco 
        LEFT JOIN ".TABLE_PREFIX."gacl_aro_map aro ON aco.acl_id = aro.acl_id 
        WHERE
            aco.section_value='CategoryManager' AND
            aro.value=2
        ";
    $res = mysql_query ($query);
    echo mysql_error();
    $row = mysql_fetch_array($res);
    //echo ":".$row[0];
    $gacl_api->del_acl ($row[0]);
    
    $gacl_api->add_acl (
        array ('CategoryManager' => $acos),
        array ('Person' => array (2)),
        null, 
        null, 
        null, 
        true, 
        true,
        null,
        null,
        'user');

    // === Mandatormanager ===
    echo "GACL: Updating / Creating Mandatormanager<br>";
    
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
    
    // --- delete existing acls first ---
    $query = "
        SELECT distinct(aco.acl_id) from ".TABLE_PREFIX."gacl_aco_map aco 
        LEFT JOIN ".TABLE_PREFIX."gacl_aro_map aro ON aco.acl_id = aro.acl_id 
        WHERE
            aco.section_value='Mandatormanager' AND
            aro.value=2
        ";
    $res = mysql_query ($query);
    echo mysql_error();
    $row = mysql_fetch_array($res);
    //echo ":".$row[0];
    $gacl_api->del_acl ($row[0]);
    
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

    // === Languages ===
    echo "GACL: Updating / Creating Languages<br>";
    
    // --- new acl section ---
    $aco_section_id = $gacl_api->add_object_section ('Languages','Languages',20, false, 'aco');

    // --- add acos ---
    $acos = array ();
    
    $gacl_api->add_object ('Languages', 'Manage',            'Manage',           1, false, 'aco');
    $gacl_api->add_object ('Languages', 'Edit Permissions',  'Edit Permissions', 2, false, 'aco');
    
    $acos[] = 'Manage';
    $acos[] = 'Edit Permissions';
        
    // --- add rights for superadmin ---
    echo "<br>====================================================<br>";
    
    $gacl_api->add_acl (
        array ('Languages' => $acos),
        array ('Person' => array (2)),
        null, 
        null, 
        null, 
        true, 
        true,
        null,
        null,
        'user');


?>
Updating gacl: done
<br><br>
