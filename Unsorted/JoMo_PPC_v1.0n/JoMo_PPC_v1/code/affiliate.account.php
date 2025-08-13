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
account of affiliate
*/
	
	checkAffiliatePage();
	
/**
input parameters:
$affiliateID
*/

        if (!isset($cmd) || empty($cmd))   $cmd="";

        /*
        if ($cmd=="deposit"){
                changeAccountBalance($memberType, $affiliateID,$deposit,"deposit",1);
                $cmd="";
                $msg = "you account has been updated";                
        }   
        */


// load page

   // member
   $member = getMember($affiliateID, $memberType);
   $tpl->assign("member",$member["info"]);
   $tpl->assign("account",$member["account"]);
        
    //
    $tpl->assign("minAffBalance",getOption("minAffiliateBalance"));
    
    $tpl->assign("cmd",$cmd);
    $tpl->assign("affiliateID",$affiliateID);
            
    if (!isset($msg)) $msg="";
    $tpl->assign("msg",$msg);
            
    $tpl->display("template.affiliate.account.php");
?>


