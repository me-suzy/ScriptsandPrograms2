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
<?
/**
keywordbox.php
*/
	 include("config.php");
     include(__CFG_PATH_CODE . "loader.php");

     /** Page title */
     $tpl->assign("pageTitle", "keyword");

/**
session:
    loginMode 
$keyword,     
memberID
listingType = "link"|"banner"|"all"
*/
	                                 
    // check $loginMode
    if (!$sID->assigned("loginMode")){
        die("you must login first");
    }
    else{                           
        $loginMode = $sID->fetch("loginMode");
        if ($loginMode==""){
            die("you must login first");
        }
    }
    $loginMode = $sID->fetch("loginMode");
    $tpl->assign("loginMode",$loginMode);


    // keyword    
	if (!isset($keyword)) $keyword="";
	$tpl->assign("keyword",$keyword);
	
	if (!isset($top)) $top=3;
	if ($top>10) $top=10;

	if (!isset($memberID)) $memberID=0;
    if ($loginMode=="admin") $memberID=0;
                     
    if (!isset($listingType)) $listingType="link";
    $tpl->assign("listingType",$listingType);
//    dprint($listingType);
//    dprint($memberID);

	// items
	$items=array();
    $i=0;
	
	if ($keyword!=""){ 
        if ($listingType=="link"){
        	$dbSet->open("SELECT * FROM links l
        		INNER JOIN urls u ON u.urlID=l.urlID
    	    	INNER JOIN keywords k ON k.keywordID=l.keywordID
    	    	WHERE 
    	    		status=1 AND adminStatus=1 AND accountStatus=1
    	    		AND u.memberID<>$memberID
    	    		AND keywordName LIKE '%".$keyword."%'
    	    	ORDER BY bid DESC
    	    	LIMIT $top" );
    		
    		while ($row=$dbSet->fetchArray()){
    			if ($i>=$top) break;
                                                        
                //print_r($row);
                $items[$i]=$row;
    			$items[$i]["index"]=$i+1;
    			
    			$urlID=getUrlOfLink($row["linkID"]);
    			$mID=getMemberOfURL($urlID);
    			$member = getMember($mID);
    			$items[$i]["membername"] = $member["info"]["firstName"]." ".$member["info"]["lastName"];
                $items[$i]["listingType"] = "link";                 
    			$i++;
    		}    	
        }

        if ($listingType=="banner"){
        	$dbSet->open("SELECT *, keywords as keywordName FROM banners b
    	    	WHERE 
    	    		status=1 AND adminStatus=1 AND accountStatus=1
    	    		AND b.memberID<>$memberID
    	    		AND keywords LIKE '%".$keyword."%'
    	    	ORDER BY bid DESC
    	    	LIMIT $top" );
    		
    		while ($row=$dbSet->fetchArray()){
    			if ($i>=$top) break;
                
                $items[$i]=$row;
    			$items[$i]["index"]=$i+1;
    			
    			$member = getMember($row["memberID"]);
    			$items[$i]["membername"] = $member["info"]["firstName"]." ".$member["info"]["lastName"];
                $items[$i]["listingType"] = "banner";
                
    			$i++;
    		}    	
        }
        
    }
    else{
    	
    }
    
  	$tpl->assign("items",$items);		
	$tpl->assign("top",$top);		
	$tpl->assign("memberID",$memberID);			

	$tpl->display("template.keywordbox.php");
	
	include(__CFG_PATH_CODE . "unloader.php");
?>