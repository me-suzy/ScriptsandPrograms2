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
<?php

/**
affiliates. info
*/

/**
input:
affiliateID
*/
	
	checkAffiliatePage();
	
 if (!isset($cmd)) $cmd="";
 
 if ($cmd=="update"){
 	$error="";
 	if (updateMember($affiliateID,$result,$error,$memberType)>0){
 		$msg = "info was changed.";
 	}
 	else{
 		$msg = $error;
 	}
 }
 
 // member info
 $affiliate = getMember($affiliateID,$memberType);
 
 //$tpl->assign("member",$member["info"]);
 $tpl->assign("result",$affiliate["info"]);
 $tpl->assign("account",$affiliate["account"]);
 
 if (!isset($msg)) $msg="";
 $tpl->assign("msg",$msg);
 
 $tpl->assign("affMode",$affMode);
 
 $tpl->display("template.affiliate.info.php");

?>