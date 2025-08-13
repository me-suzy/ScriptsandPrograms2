<?
/*
###############################
#
# JoMo Easy Pay-Per-Click Search Engine v1.0
#
#
###############################
#
# Date                 : September 16, 2002
# supplied by          : CyKuH [WTN]
# nullified by         : CyKuH [WTN]
#
#################
#
# This script is copyright L 2002-2012 by Rodney Hobart (JoMo Media Group),
All Rights Reserved.
#
# The use of this script constitutes acceptance of any terms or conditions,
#
# Conditions:
#  -> Do NOT remove any of the copyright notices in the script.
#  -> This script can not be distributed or resold by anyone else than the
author, unless special permisson is given.
#
# The author is not responsible if this script causes any damage to your
server or computers.
#
#################################

*/
?>
<?PHP
/**
*/
        include("config.php");
        include(__CFG_PATH_CODE . "loader.php");

        /** Page title */
        $tpl->assign("pageTitle", __SITE_TITLE."- admin area");

/**
input:
	[login], [password]
	tryLogin (=0|1)
	
session:
	isAdminLogin
	loginMode="admin"
	mode
	
tpl:	
	mode
	loginMode
	msg
	
*/

	if (!isset($mode)) $mode="";
	
    if ($mode=="gotosite"){
    	$sID->unassign("mode");
    	$sID->unassign("isAdminLogin");
    	Header("Location: index.php");
    	exit;
    }
        
    /** Checkout system login */
    if(!$sID->assigned("isAdminLogin")) {
            if(!isset($login)) $login="";
            if(!isset($password)) $password="";
            if ($login==__CFG_ADMIN_USERNAME && $password==__CFG_ADMIN_PASSWORD) {
                    $sID->assign("isAdminLogin", true);
            		$sID->assign("loginMode", "admin");
					$sID->assign("demoAdmin", false);
                    $mode="main";
					$tpl->assign("demoAdmin", false);
			} else if ( __CFG_GUEST_ADMIN_ENABLE == 1 && $login==__CFG_GUEST_ADMIN_USERNAME && $password==__CFG_GUEST_ADMIN_PASSWORD) {
			  	    $sID->assign("isAdminLogin", true);
            		$sID->assign("loginMode", "admin");
					$sID->assign("demoAdmin", true);
                    $mode="main";
					$tpl->assign("demoAdmin", true);
					
            } else{
            	  if (isset($tryLogin) && $tryLogin==1){
                        $msg="incorrect login or password";
                  }
                  unset ($mode);
            }
    }
    else {
            if(empty($mode))
                    $mode=$sID->fetch("mode");
            $sID->assign("loginMode", "admin");						
    }

        /** Check current admin mode */
        if(!isset($mode) || empty($mode) || $mode=="logout") {
                $sID->unassign("isAdminLogin");
                $sID->unassign("mode");
                $sID->unassign("loginMode");
				$sID->unassign("demoAdmin");				
                $mode="login";
        }

    /** Assign admin mode to template*/
    $tpl->assign("mode", $mode);
    $sID->assign("mode", $mode);

    $tpl->assign("loginMode", "admin");
    
    $tpl->assign("isDemoAdmin", __CFG_GUEST_ADMIN_ENABLE );
    $tpl->assign("demoAdminName", __CFG_GUEST_ADMIN_USERNAME);
    $tpl->assign("demoAdminPassword", __CFG_GUEST_ADMIN_PASSWORD);

    $tpl->assign("msg",isset($msg)?$msg:"");

    /** Get work module */
    if($mode!="login" && $mode!="logout") {
            include_once(__CFG_PATH_CODE . "admin.$mode.php");
    } else  {
            $tpl->display("admin/template.admin.login.php");
    }

    include(__CFG_PATH_CODE . "unloader.php");
?>