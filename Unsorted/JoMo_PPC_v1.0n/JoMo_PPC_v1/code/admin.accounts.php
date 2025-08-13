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
accounts
*/

	checkAdminPage();

/**
input:
$accountType=member|affiliate|all
*/

// cmd

        // check $cmd
        if ($sID->assigned("cmd")) $cmd = $sID->fetch("cmd");
        if (!isset($cmd)) $cmd="";
        
    	if (!isset($page) || empty($page)) $page=1;
    	$mode = $sID->fetch("mode");

		// update balance
        if ($cmd == "balance"){
        	changeAccountBalance($accountType,$accountID, $balanceValue,"admin",1);
        	$msg = "account was updated (".($balanceValue>0?"increased":"decreased")." by \$".abs($balanceValue).")";
        	$cmd="";			
		}

        // del request
        if ($cmd == "delrequest"){
        	delAffiliateRequest($memberID, $accountType);
        	$msg = "request was deleted";
        	$cmd="";			
		}

		
// load page
		$linksPerPage = getOption("LinksPerPage");
		
		$start=($page-1)*$linksPerPage;
        $limit = $linksPerPage;
        if (!isset($accountType)) $accountType="member";
        $tpl->assign("accountType",$accountType);
        
        $table = $accountType."s";
        $tableaccounts = $accountType."accounts";
		//$tableShort = "t"; $tableAccountShort = "ta";
		$itemColumnID = $accountType."ID";
		
		if (!isset($orderby)) $orderby="1";
		if (!isset($orderdir)) $orderdir="ASC";
		$tpl->assign("orderby",$orderby);
		$tpl->assign("orderdir",$orderdir);
		
        // member accounts
        // count
        $dbSet->open("SELECT t.".$itemColumnID." as accountID  FROM ".$table." t         	INNER JOIN ".$tableaccounts." ta ON t.".$itemColumnID."=ta.".$itemColumnID);
        $nItems = $dbSet->numRows();
        
        $dbSet->open("SELECT t.".$itemColumnID." as accountID, 
        	t.".$itemColumnID." as itemID ,t.firstName, t.lastName, CONCAT(firstName,' ',lastName)  as name, t.email,
        	ta.balance as balance, ta.isActive as isActive,
        	'".$accountType."' as accountType 
        	FROM ".$table." t 
        	INNER JOIN ".$tableaccounts." ta ON t.".$itemColumnID."=ta.".$itemColumnID."
        	ORDER BY $orderby $orderdir
        	LIMIT $start, $limit"
        );
        $items=array();
        $i=0;
        while ($row=$dbSet->fetchArray()){
        	$items[$i]=$row;
        	$items[$i]["activity"] = $row["isActive"]==1?"active":"frozen";
            if ($accountType == "affiliate"){
                $req = getAffiliateRequest($items[$i]["accountID"]);
                $items[$i]["isRequest"] = empty($req) ? 0 : 1;
                $items[$i]["lastRequestDate"] = empty($req)? "---" : $req["lastRequestDate"];
            }
        	$i++;	
        }
        
        $tpl->assign("items",$items);
		$tpl->assign("nItems",$nItems);		
		
		// calc pages
		$pages=array(); $prev=$next=0;
		calcPages($nItems,$linksPerPage, $page, &$pages, &$prev,&$next);
		
		$tpl->assign("page",$page);		
		$tpl->assign("pages",$pages);
		$tpl->assign("prev",$prev);
		$tpl->assign("next",$next);

		if (!isset($msg)) $msg="";
		$tpl->assign("msg",$msg);
		$tpl->assign("minBalance",getOption("minAffiliateBalance"));

		$tpl->assign("mode",$mode);

		$sID->assign("cmd",$cmd);		
        
        $tpl->display("admin/template.admin.accounts.php");
?>