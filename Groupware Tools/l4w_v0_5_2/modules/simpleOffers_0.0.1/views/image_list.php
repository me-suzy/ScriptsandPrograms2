<?php

// This list may be created by a server logic page PHP/ASP/ASPX/JSP in some backend system.
// There images will be displayed as a dropdown in all image dialogs if the "external_link_image_url"
// option is defined in TinyMCE init.


	// === Base Configuration ==========================================
    include_once ("../../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    $logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    $logger->set_ident ("simpleOffers [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../../common/leads4web_model.php");
	require_once ("../models/offers_mdl.php");
	require_once ("../controlers/offers_ctrl.php");
	
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
    include ("../../../inc/functions.inc.php");
    include ("../../../inc/events.inc.php");

    $query = "SELECT * FROM ".TABLE_PREFIX."pics WHERE 1=1"; // todo!!! where clause
    $res   = mysql_query ($query);

    $pics  = array ();
    
    while ($row = mysql_fetch_array ($res)) {
        $pics[] = "[\"".$row['headline']."\", \"../pics_0.0.1/".$row['pic']."\"]";    
    }        

/*var tinyMCEImageList = new Array(
	// Name, URL
	["Logo 1", "logo.jpg"],
	["Logo 2 Over", "logo_over.jpg"]
);*/

    echo "var tinyMCEImageList = new Array(\n";
    for ($i=0; $i < count ($pics); $i++) {
        if ($i > 0) echo ",\n";
        echo $pics[$i];       
    }    
    echo ");";
?>
