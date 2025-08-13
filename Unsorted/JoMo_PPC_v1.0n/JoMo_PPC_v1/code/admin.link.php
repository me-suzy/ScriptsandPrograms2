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
admin.link
*/

	checkAdminPage();
/**
input:
$linkID
*/

// cmd
        // check $cmd
        if (!isset($cmd)) $cmd="";

        if ($cmd=="cancel") {
        	Header("Location: "."admin.php?mode=links&cmd=");
            include(__CFG_PATH_CODE . "unloader.php");
            exit;
        }

// signup
        if ($cmd=="signupedit") {
                editLink($result["linkID"], $result);
                $cmd="";
                Header("Location: "."admin.php?mode=links&cmd=");
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
	            Header("Location: "."admin.php?mode=links&cmd=");
	            include(__CFG_PATH_CODE . "unloader.php");
	            exit;
	        }
        }
	
// load page
// TODO: getLink(...)
	if (!isset($linkID)) $linkID=0;
        $dbSet->open("SELECT l.linkID, l.title, l.bid,l.status, l.adminStatus, l.accountStatus,
			l.description,
			CONCAT(m.firstName,' ',m.lastName) as membername,
			l.urlID,
	         DATE_FORMAT(l.creationDate,'%Y-%m-%d') as creationDate,
	         DATE_FORMAT(l.modificationDate,'%Y-%m-%d') as modificationDate,
	         u.url as url, u.title as urltitle, k.keywordName as keywordName
	         FROM links l 
	         INNER JOIN urls u ON l.urlID=u.urlID
			 INNER JOIN members m ON u.memberID=m.memberID
	         INNER JOIN keywords k ON l.keywordID=k.keywordID
			 WHERE l.linkID=$linkID
		");
		
		$link = $dbSet->fetchArray();
		$tpl->assign("link",$link);

		$tpl->assign("cmd",$cmd);
		$tpl->assign("memberID",getMemberOfLink($linkID));		
		
		$tpl->assign("statusMsg",getLinkStatus($linkID));
        $minBidValue = getOption("minBidValue");
        $tpl->assign("minBidValue",$minBidValue);
        
		
        // display template
        $tpl->display("admin/template.admin.link.php");
?>