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

	checkMemberPage();

	if (isset($is_success))
	{
		$msg = $is_success ? "You have done payment." : "You canceled payment.";
	}
	else{
		$msg = "";
	}
	
	$tpl->assign("msg", $msg);
		
	Header("Location: index.php?mode=products&msg=$msg");
	exit;
	//$tpl->display("template.member.account.php");
?>