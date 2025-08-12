<?php

  /**
    * $Id: addexternallink.php,v 1.4 2005/07/31 08:45:17 carsten Exp $
    *
    * main page for notes management. Each request concerning contacts is build up like
    * modules/notes/index.php?command=XXX
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    $logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    $logger->set_ident ("notes [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("externallinks.class.php");
	
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
	if (!isset ($_SESSION['easy_datagrid']['entries_per_page']))
    	$_SESSION['easy_datagrid']['entries_per_page'] = 20;

    $img_path = "../../".get_skin_img_path ();

    $links_tab = new externallinks_tab (
            $_REQUEST['id'],
            $_REQUEST['type'], 
            false); 
            
    $links_tab->setTabNr   (6);
    $links_tab->setImgPath ($img_path);
    
    // --- inclusions -----------------------------------------------   
    include ("../common/standard_inclusions.inc.php");
   
	// --- pagestats ------------------------------------------------
	set_page_stats(__FILE__);

    // --- Header ---------------------------------------------------
	include ("../common/header.inc.php");

?>    

<script language=''>

    function submitForm () {
        
        var form   = document.formular;
        var orig   = opener.document.formular;
        for (i = 1; i <= 3; i++) {
            eval ("elem = document.formular.link"+i+";");
            alert (elem.value);
            if (elem.value != "") {
                position  = orig.added_links.length;
                alert (position);
                orig.added_links.options[position]     = null;
        	    newEntry  = new Option ('link ('+elem.value+')',elem.value,true, true);
		        orig.added_links.options[position]     = newEntry;
            }    
        }    
        window.close();
    }
       
</script>

<FORM method='post' name='formular'>   

<?php

	$headline  = "<img src='".$img_path."tickets.gif' align=top>&nbsp;";
    $headline .= translate ("external references")."</i>";
	include ("../common/headline.php"); 
    echo $links_tab->showAddLinksForm ();
?>
<br>
<input type=submit name=submit_button onClick='javascript:submitForm()' value='<?=translate('save')?>'>
<form>    
</body>
</html>