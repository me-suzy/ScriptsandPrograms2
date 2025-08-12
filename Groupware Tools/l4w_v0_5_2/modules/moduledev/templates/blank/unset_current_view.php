<?php

  /**
    * $Id: unset_current_view.php,v 1.2 2005/07/31 08:45:16 carsten Exp $
    *
    * unset current view
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package notes
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    $logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    $logger->set_ident ("notes [User #".$_SESSION['user_id']."]");
    
	// === Inclusion of model and controler ============================
	require_once ("../common/leads4web_model.php");

    // === Application Includes ========================================
    include ("../../inc/functions.inc.php");

    $affected = l4w_model::unlockEntry ('note', $_REQUEST['entry_id']);
	
    
?>
unset note<br><?=$_REQUEST['entry_id']?> (<?=$affected?>)
</body>
</html>
