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
biew request
*/

	checkAdminPage();

/**
input:

*/

// cmd

        // check $cmd
        if ($sID->assigned("cmd")) $cmd = $sID->fetch("cmd");
        if (!isset($cmd)) $cmd="";
        
    	$mode = $sID->fetch("mode");

		// cancel
        if ($cmd == "cancel"){
        	Header();
        	exit();
		}

		
// load page
    $request = getAffiliateRequest($affiliateID);
    $member = getMember($affiliateID, "affiliate");
    $info = $member["info"]; $account = $member["account"];
    
    $tpl->assign("request",$request);
    
    if (!isset($msg)) $msg="";
    $tpl->assign("info",$info);
    $tpl->assign("account",$account);
    
    $tpl->assign("minAffBalance",getOption("minAffiliateBalance"));
    
    $tpl->assign("mode",$mode);
    $tpl->assign("cmd",$cmd);
    
    $tpl->display("admin/template.admin.viewrequest.php");
?>