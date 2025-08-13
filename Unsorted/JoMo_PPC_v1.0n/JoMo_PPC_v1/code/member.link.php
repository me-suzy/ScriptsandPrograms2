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
	
        $sID->assign("memberID",$memberID);
        
/**
input parameters:
$linkID
*/

        if (!isset($linkID) || empty($linkID)) $linkID = 0;
        if (!isset($cmd) || empty($cmd))   $cmd="";

//dprint($cmd);

// if user choose "cancel" in form
        if ($cmd=="cancel"){
                Header("Location: "."index.php?mode=members&memberMode=links&cmd=");
                include(__CFG_PATH_CODE . "unloader.php");
                exit;
        }

// signup
        if ($cmd=="signupedit") {
                editLink($result["linkID"], $result);
                $cmd="";
                Header("Location: "."index.php?mode=members&memberMode=links&cmd=");
                include(__CFG_PATH_CODE . "unloader.php");
                exit;
        }

        if ($cmd == "signupcreate"){
        	$error="";
        	if (!isset($result["urlID"]) || $result["urlID"]==0){
        		$error="URL is not specified";
        		$res=0;
        	}
        	else
                $res=createLink($result, $error);
                
            // error creating link
            if ($res==0){
            	$msg = $error;
            	$cmd="create";
            	$linkID=0;
            }
            else{
	            $cmd="";
	            Header("Location: "."index.php?mode=members&memberMode=links&cmd=");
	            include(__CFG_PATH_CODE . "unloader.php");
	            exit;
	        }
        }

	if (!isset($viewBidKeyword)) $viewBidKeyword="";
	$tpl->assign("viewBidKeyword",$viewBidKeyword);
	
	if (!isset($cmdView)) $cmdView="";
	if ($cmdView=="viewBid"){
		$maxBidKeyword = getMaxBidOfKeyword($viewBidKeyword);
		$tpl->assign("maxBidKeyword", $maxBidKeyword);
	}
	
// load page

   // urls
        $dbSet->open("SELECT * FROM urls WHERE memberID=$memberID" );
        $urls = $dbSet->fetchColsAll();
        $tpl->assign("urlIDs",$urls["urlID"]);
        $tpl->assign("urlNames",$urls["url"]);
        $tpl->assign("urlTitles",$urls["title"]);

   // link
   		if ($linkID!=0){
	        $dbSet->open("SELECT l.linkID,l.urlID,l.keywordID,l.bid,l.status, l.adminStatus,  l.accountStatus,  l.title,  l.description,
	            DATE_FORMAT(l.creationDate,'%Y-%m-%d') as creationDate,
	            DATE_FORMAT(l.modificationDate,'%Y-%m-%d') as modificationDate,
	            k.keywordName as keywordName 
	            FROM links l
			    INNER JOIN urls u ON l.urlID=u.urlID
			    INNER JOIN keywords k ON l.keywordID=k.keywordID
			    WHERE linkID=$linkID");
			
			$link = $dbSet->fetchArray();
		}
		else{
			$link = emptyLink();
			if (isset($urlID))
				$link["urlID"]=$urlID;
			if (isset($result))
				foreach ($result as $key=>$val){
             		$link[$key]=$val;
             	}
		}
		
        $tpl->assign("link",$link);
        
	    //status
	    $accountStatus = isAccountActive($memberID)?1:0;
	    if ($cmd=="create")   	$adminStatus = getOption("approveListing")==1?0:1;
	    else $adminStatus = $link["adminStatus"];
		if ($accountStatus==0)    	$statusMsg = "you don't have enough money. Your links will be NOT active.";
	    else if ($adminStatus==0)    	$statusMsg = "Link is disabled by admin. Link will be active after admin approves it.";
		else $statusMsg = "";
		
	    $tpl->assign("statusMsg",getLinkStatus($linkID));
	    //$tpl->assign("statusMsg",$statusMsg);

        $tpl->assign("cmd",$cmd);
        $tpl->assign("memberID",$memberID);

			
	$minBidValue = getOption("minBidValue");
	$tpl->assign("minBidValue",$minBidValue);

	if (!isset($msg)) $msg="";
	$tpl->assign("msg",$msg);
		
    $tpl->display("template.member.link.php");
?>


