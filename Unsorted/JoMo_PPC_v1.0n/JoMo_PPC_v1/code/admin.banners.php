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
banners
*/

    checkAdminPage();
    
/**
input:
filter: $memberID, $urlID, $keywordName
$page
$itemID
*/

// cmd
$table = "banners";
$tableShort = "b";
$itemColumnID = "bannerID";

    // check $cmd
    if (!isset($cmd)) $cmd="";
    if (!isset($page) || empty($page)) $page=1;
    $msg="";
    
    if ($cmd == "delete") {
    	delBanner($itemID);
    	$msg = "banner has been deleted";
    	$cmd="";
    }
    
    if ($cmd == "activate" || $cmd=="deactivate") {
        $adminStatus = $cmd=="activate"?1:0;
        $dbSet->execute("UPDATE ".$table." 
        SET adminStatus=$adminStatus 
        WHERE ".$itemColumnID."=$itemID");
    }


	if ($cmd=="upload"){
		if (!isset($newurl)) $newurl="";
		
         $error = "";
         $res = insertBanner(array("memberID"=>0, "url"=>$newurl), $error);
         if ($res==0){
         	$msg = $error;
         }
         else{
         	$msg = "new banner was added to banner's pool";
         }
         $cmd="";
         unset($bannerID);
    }

	
// load page
		
		// members
		$dbSet->open("SELECT memberID, CONCAT(firstName,' ',lastName)  as name FROM members" );
        $members = $dbSet->fetchColsAll();
        $tpl->assign("memberIDs",$members["memberID"]);
        $tpl->assign("memberNames",$members["name"]);
        
        // urls
        $dbSet->open("SELECT DISTINCT url FROM banners" );
		
        //$urls = $dbSet->fetchColsAll();
		$i=0;
		$urls=$urlValues=$urlNames=array();
		while($row = $dbSet->fetchArray()) {
                $urls[$i] = $row;
                $urlNames[]=preg_replace("/http:\/\//","",$row["url"]);
				$urlValues[]=$row["url"];
                $i++;
        }

        $tpl->assign("urlValues",$urlValues);
        $tpl->assign("urlNames",$urlNames);

        // banners
           // filter
        $where = "1=1 ";
        
        // filter member
        if (!isset($memberID)) $memberID=0;
		$tpl->assign("memberID",$memberID);
		
        if ($memberID!=0){
			if ($memberID==-1) $memberID=0;
            $where.=" AND b.memberID=$memberID ";
        }
        
        // filter url
        if (!isset($url)) $url="";
        if ($url!=""){
            $where.=" AND b.url='".$url."' ";
        }
        $tpl->assign("url",$url);

        // filter url
        if (!isset($keywordName)) $keywordName="";
        if ($keywordName!=""){
            $where.=" AND (b.keywords LIKE '%".$keywordName."%' OR b.isCatchAll=1)";
        }
        $tpl->assign("keywordName",$keywordName);

		if ($cmd == "deactivateall" || $cmd=="activateall") {
			$adminStatus = $cmd=="activateall"?1:0;
			
			$dbSet->open("SELECT b.bannerID
    	         FROM ".$table." b "."
    	         WHERE ".$where);
			 
			$n = $dbSet->numRows();			 
			
			$itemIDs=" 0";
	        while($row = $dbSet->fetchArray()) {
				$itemIDs.= ", ".$row[$itemColumnID];
            }
			
			$dbSet->execute("UPDATE ".$table." SET adminStatus=$adminStatus 
				WHERE ".$itemColumnID." IN (".$itemIDs.")");
			$msg = $n." banners were updated";
		}
		
		// count
		$start=($page-1)*$linksPerPage;
        $dbSet->open("SELECT COUNT(*) as n
	         FROM ".$table." b "."
	         WHERE ".$where);
		$row=$dbSet->fetchArray();
		$nItems = $row["n"];

		// orderby
		if (!isset($orderby)) $orderby="b.bannerID";
		if (!isset($orderdir)) $orderdir=" ASC ";		
		$tpl->assign("orderby",$orderby);
		$tpl->assign("orderdir",$orderdir);

		// banners
        $items = array();
        $dbSet->open("SELECT b.*,
	         DATE_FORMAT(b.creationDate,'%Y-%m-%d') as creationDate,
	         DATE_FORMAT(b.modificationDate,'%Y-%m-%d') as modificationDate,
	         url as url, keywords as keywords,
			 CONCAT(m.firstName,' ',m.lastName) as membername
	         FROM ".$table." b "."
			 LEFT OUTER JOIN members m ON b.memberID=m.memberID
	         WHERE ".$where.                  
			 "ORDER BY ".$orderby." ".$orderdir." ".
			 " LIMIT $start, ".$linksPerPage );
			 
        //$links = $dbSet->fetchRowsAll("array");
        $i=0;
        while($row = $dbSet->fetchArray()) {
                $items[$i] = $row;
				$adminStatus=$row["adminStatus"];
                $items[$i]["activity"]=$adminStatus=="1"?"enabled":"disabled";
				//catch all
                if ($items[$i]["isCatchAll"]==1) $items[$i]["keywords"]="[catch all]";
				//path
				$items[$i]["name"]=pathToName($items[$i]["path"]);
				//member
				if ($items[$i]["memberID"]==0) $items[$i]["membername"]="POOL";
				
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
		
		$tpl->assign("msg",$msg);

		// per impression
		$tpl->assign("bannerPerImpression",getOption("bannerPerImpression"));
		$tpl->assign("bannerImpressionBid",getOption("bannerImpressionBid"));
		
        // display template
        $tpl->display("admin/template.admin.banners.php");
?>