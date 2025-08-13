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
bid 
*/
	checkMemberPage();
/**
input:

sort: $orderby, $orderdir
$keyword
$top

*/


		$sID->assign("memberID",$memberID);
        $tpl->assign("memberID",$memberID);
		
        // check $cmd
        if (!isset($cmd)) $cmd="";

        if ($cmd == "") {
                
        }

/**************************************

**************************************/
	if (!isset($top)) $top=10;
	$tpl->assign("top",$top);
		
	if (!isset($keyword)) $keyword="";
	$tpl->assign("keyword",$keyword);
	
	// urls
	$items=array();
	
	if ($keyword!=""){
    	$dbSet->open("SELECT * FROM links l
	    	INNER JOIN keywords k ON k.keywordID=l.keywordID
	    	WHERE 
	    		status=1 AND adminStatus=1 AND accountStatus=1
	    		AND keywordName LIKE '%".$keyword."%'
	    	ORDER BY bid DESC
	    	LIMIT $top" );
		
		$i=0;
		while ($row=$dbSet->fetchArray()){
			$items[$i]=$row;
			$items[$i]["index"]=$i+1;
			
			$urlID=getUrlOfLink($row["linkID"]);
			//dprint("url=".$urlID);
			$memberID=getMemberOfURL($urlID);
			//dprint("member=".$memberID);
			$member = getMember($memberID);
			//print_r($member);
			$items[$i]["membername"] = $member["info"]["firstName"]." ".$member["info"]["lastName"];
			$i++;
			
		}    	
    }
    else{
    	
    }
    
  	$tpl->assign("items",$items);
  	
  	
    $tpl->display("template.member.bidstats.php");

?>
