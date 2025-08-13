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
info about account
*/

	checkAdminPage();
	
/**
input:
$accountType=member|affiliate
$itemID
*/

// cmd
        // check $cmd
        if (!isset($cmd)) $cmd="";
        
    	if (!isset($page) || empty($page)) $page=1;
    	$mode = $sID->fetch("mode");

		$msg="";

		// update balance
        if ($cmd == "balance"){
        	changeAccountBalance($accountType,$itemID, $balanceValue,"admin",1);
        	$msg = "account was updated (".($balanceValue>0?"increase":"decrease")." by \$".abs($balanceValue).")";
        	$cmd="";			
		}
		
		if ($cmd=="update"){
		 	$error="";
	 		if (updateMember($itemID,$result,$error,$accountType)>0){
		 		$msg = "info was changed.";
		 		Header("Location: admin.php?mode=accounts&msg=$msg");
		 		exit();
		 	}
		 	else{
		 		$msg = $error;
		 	}
		 	
		 }
		 
 

// countries
        $dbSet->open("SELECT DISTINCT country_id, code as country_code, name as country_name FROM country");
        $country_ids=array();
        $country_names=array(); 
        while ($row=$dbSet->fetchArray()){
                $country_ids[]=$row["country_id"];
                $country_names[]=$row["country_name"];          
        }
        $tpl->assign("countryListValues", $country_ids);
        $tpl->assign("countryListOutput", $country_names);

		
// load page
		$table = $accountType."s";
        $tableaccounts = $accountType."accounts";
		$itemColumnID = $accountType."ID";
		
        // member account
        $dbSet->open("SELECT t.*, 
        	t.".$itemColumnID." as itemID , CONCAT(firstName,' ',lastName)  as name, 
        	ta.balance as balance, ta.isActive as isActive,
        	'".$accountType."' as accountType 
        	FROM ".$table." t 
        	INNER JOIN ".$tableaccounts." ta ON t.".$itemColumnID."=ta.".$itemColumnID."
        	WHERE t.".$itemColumnID."=".$itemID
        );
        $item=$dbSet->fetchArray();
        $tpl->assign("result",$item);
		
		$tpl->assign("minBalance",getOption("minBalance"));

		$tpl->assign("accountType",$accountType);	
		$tpl->assign("msg",$msg);
		$tpl->assign("mode",$mode);
		$tpl->assign("cmd",$cmd);
		
        // session
		$sID->assign("cmd",$cmd);		
		
        $tpl->display("admin/template.admin.accountinfo.php");
?>