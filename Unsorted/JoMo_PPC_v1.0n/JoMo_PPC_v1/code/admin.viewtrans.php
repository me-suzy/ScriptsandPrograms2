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

	checkAdminPage();

/**
input: 
$memberID
[$tr_Type], [$year],...
*/	
	
    if (!isset($cmd) || empty($cmd))   $cmd="";
    if (!isset($accountType))
    	die("error: no account");
    
	$tpl->assign("months",$months);
	$tpl->assign("monthIDs",$monthIDs);
	$tpl->assign("days",$days);
	$tpl->assign("years",$years);
	$tpl->assign("yearIDs",$yearIDs);

	// filter date
    $where = "1=1 AND transactionType<>'impression' AND accountType='$accountType' AND accountID=$memberID";
    
    // get current time
	$curtimestamp=time(); $curtime=getdate($curtimestamp);
	
    // year
	if (!isset($year)) $year=$curtime["year"];        
	if ($year!=0){       $where.=" AND YEAR(transactionDate)=$year ";        }
    // month
	if (!isset($month)) $month=0;        
	if ($month!=0){       $where.=" AND MONTH(transactionDate)=$month ";        }
    // filter day
	if (!isset($day)) $day=-1;
	if ($day!=0 && $day!=-1){       $where.=" AND DAYOFMONTH(transactionDate)=$day ";        }
	else if ($day==-1){       $where.=" AND YEAR(transactionDate)=YEAR(NOW()) AND DAYOFMONTH(transactionDate)=DAYOFMONTH(NOW())";        }
    
	if ($day==-1){
		$month = $curtime["mon"];
		$year = $curtime["year"];
	}

    $tpl->assign("year",$year);
    $tpl->assign("month",$month);
    $tpl->assign("day",$day);
    
    // filter tr_Type
	if(!isset($tr_Type)) $tr_Type="all";
	if ($tr_Type!="all") $where.=" AND transactionType = '$tr_Type'";
	
	// sort
    //if (!isset($sortby))  $sortby="transactionDate";
    
    $query = "SELECT * FROM transactions WHERE $where";
   
// load page

   // member
    $dbSet->open($query);
    $data_tr = $ammount_tr = $type_tr = array();
	while ($r = $dbSet -> fetchObject())	{
		$data_tr[]= $r -> transactionDate;
		$ammount_tr[]=$r -> value;
		$type_tr[]=$r -> transactionType;
	}
        
        //$tpl -> clear_compiled_tpl();
 		$tpl->assign("cmd",$cmd);
        $tpl->assign("memberID",$memberID);
		
		$member = getMember($memberID, $accountType);
		$member["info"]["accountType"] = $accountType;
		$member["info"]["memberID"] = $memberID;
        $tpl->assign("member",$member["info"]);
        
        $member["account"]["memberID"] = $memberID;
        $tpl->assign("account",$member["account"]);
        
        
        $tpl->assign("minBalance",getOption("minBalance"));
		
		if (!isset($msg)) $msg="";
		$tpl->assign("msg",$msg);
		$tpl->assign("data_tr",$data_tr);
		$tpl->assign("ammount_tr",$ammount_tr);
		$tpl->assign("type_tr",$type_tr);
		
		$tpl->assign("type",$tr_Type);

		$tpl->assign("accountType",$accountType);		
		
		$msg = "";
		if ($tr_Type == "all") $msg = "view all transactions";
		else if ($tr_Type == "admin") $msg = "view transactions made by admin";
		else if ($tr_Type == "deposit") $msg = "view transactions deposited by you";
		$tpl->assign("msg",$msg);
		
        $tpl->assign('paypal_account', __CFG_PAYPAL_ACCOUNT);
				
        $tpl->display("admin/template.admin.viewtrans.php");
?>
