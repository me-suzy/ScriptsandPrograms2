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
        $tpl->assign("pageTitle", "PPC");

/**
mode = home, search, member, admin, affiliate
loginMode= none, admin, member, affiliate
*/

        /** Checkout system login */

        if(!$sID->assigned("isAdminLogin")) {
                if(!isset($username)) $username="";
                if(!isset($password)) $password="";
                if($username==__CFG_ADMIN_USERNAME && $password==__CFG_ADMIN_PASSWORD) {
                        $sID->assign("isAdminLogin", true);
                        $adminMode="users";
                } else {
                        unset ($adminMode);
                }
        }
        else {
                if(empty($adminMode))
                        $adminMode=$sID->fetch("adminMode");
        }

        /** Check current admin mode */
        if(!isset($adminMode) || empty($adminMode) || $adminMode=="logout") {
                $sID->unassign("isAdminLogin");
                $sID->unassign("adminMode");
                $adminMode="login";
                //$adminMode="mail";
        }

        /** Assign admin mode to template*/
        $tpl->assign("adminMode", $adminMode);
        $sID->assign("adminMode", $adminMode);
        /** Get work module */
        if($adminMode!="login" && $adminMode!="logout") {
                include_once(__CFG_PATH_CODE . "$adminMode.php");
        } else  {
                $tpl->display("template.login.php");
        }

        include(__CFG_PATH_CODE . "unloader.php");
?>