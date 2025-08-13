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
link of member
*/

	checkMemberPage();
	
/**
input parameters:
$memberID
*/

        if (!isset($cmd) || empty($cmd))   $cmd="";

        if ($cmd=="deposit"){
        	changeAccountBalance("member", $memberID,$deposit,"deposit",1);
        	$cmd="";
        	$msg = "you account has been updated";                
        }

		$tpl->assign("num_2checkout",getOption ('UnigueNumber'));
		$tpl->assign("demoMode",getOption('DemoMode'));
		
// load page

   // member
        $dbSet->open("SELECT * FROM members WHERE memberID=$memberID" );
        $member = $dbSet->fetchArray();
        $tpl->assign("member",$member);
        
        //$tpl -> clear_compiled_tpl();
   // account
        $dbSet->open("SELECT * FROM memberaccounts WHERE memberID=$memberID");
        $account = $dbSet->fetchArray();
        $tpl->assign("account",$account);
        
        $tpl->assign("minBalance",getOption("minBalance"));
        
        $tpl->assign("cmd",$cmd);
        $tpl->assign("memberID",$memberID);
        
        $tpl->assign('paypal_account', __CFG_PAYPAL_ACCOUNT);
        $tpl->assign('paypalFee', getOption("paypalFee"));
		
		if (!isset($msg)) $msg="";
		$tpl->assign("msg",$msg);
		
        $tpl->display("template.member.account.php");
?>


