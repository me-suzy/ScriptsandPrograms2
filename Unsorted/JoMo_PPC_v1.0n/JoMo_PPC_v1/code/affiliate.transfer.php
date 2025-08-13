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
transfer money 
*/
	
	checkAffiliatePage();
	
/**
input parameters:
$affiliateID
$cmd
*/
	
        if (!isset($cmd) || empty($cmd))   $cmd="";
                               
        if ($cmd=="transfer"){
            // check member's account
            if (!isset($login)) $login="";
            if (!isset($password)) $password="";
            $memberID = isMemberExist($login, $password, "member");
            if ($memberID==0){
                $cmd = "";
                $msg = "member's account not exists";
            }
            else{
            
            // transfer money
                                                       
            $error = "";
            if (transferAffiliateMoney($affiliateID, $memberID, $amount, $error)){
                $cmd="";
                $msg = "you have transfered money";
                Header("Location: index.php?mode=affiliates&affiliateID=$affiliateID&affMode=account&cmd=&msg=$msg");
                exit();

            }
            else{
                $cmd="";
                $msg = $error;
            }
            }
        }   
        
// load page

   // member
   $member = getMember($affiliateID, $memberType);
   $tpl->assign("member",$member["info"]);
   $tpl->assign("account",$member["account"]);
        
    //                      
    $minAffBalance = getOption("minAffiliateBalance");
    $tpl->assign("minAffBalance", $minAffBalance);
    
    // check balance    
    if ($member["account"]["balance"]<$minAffBalance){                                     
        $msg = "You haven't enough money for request";
        Header("Location: index.php?mode=affiliates&affiliateID=$affiliateID&affMode=account&cmd=&msg=$msg");
        exit;
    }
    
    $tpl->assign("cmd",$cmd);
    $tpl->assign("affiliateID",$affiliateID);
            
    if (!isset($msg)) $msg="";
    $tpl->assign("msg",$msg);
            
    $tpl->display("template.affiliate.transfer.php");
?>