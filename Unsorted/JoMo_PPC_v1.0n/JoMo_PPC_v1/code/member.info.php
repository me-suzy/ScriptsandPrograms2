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
members. info
*/

/**
input:
memberID
*/

	checkMemberPage();

 if (!isset($cmd)) $cmd="";
 
 if ($cmd=="update"){
 	$error="";
 	if (updateMember($memberID,$result,$error)>0){
 		$msg = "info was changed.";
 	}
 	else{
 		$msg = $error;
 	}
 }
 
 // member info
 $member = getMember($memberID);
 
 //$tpl->assign("member",$member["info"]);
 $tpl->assign("result",$member["info"]);
 $tpl->assign("memberAccount",$member["account"]);
 
 if (!isset($msg)) $msg="";
 $tpl->assign("msg",$msg);
 
 $tpl->display("template.member.info.php");

?>