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
links of member
*/

	checkMemberPage();

/**
input:
$memberID
$urlID
$cmd= delete
$sortby, $sortDirection
$filterURL, $filterKeyword

*/

		$sID->assign("memberID",$memberID);
		
        // check $cmd
        if (!isset($cmd)) $cmd="";

		$ids=array();
		$count=0;
		if (isset($check)){
			foreach ($check as $id){
				$ids[] = $id;
				$count++;
			}
		}

        if ($cmd == "delete") {
                deleteLink($linkID);
        }

		if ($cmd == "activate" || $cmd=="deactivate") {
			$status = $cmd=="activate"?1:0;
			$dbSet->execute("UPDATE links SET status=$status WHERE linkID=$linkID");
			$msg = "link has been ".($status==1?"activated":"deactivated");
			$cmd="";
		}
		
		if ($cmd == "deleteselected") {
			foreach ($HTTP_POST_VARS as $var=>$v){
				//dprint($var."=".$v);
				//echo "<br>";
			}
			
			if (!empty($ids)){
				$inIDs = implode(",",$ids);
				//print_r($ids);
				$dbSet->execute("DELETE FROM links WHERE linkID IN (".$inIDs.")");
				$msg = "$count links have been deleted";
				$cmd="";
			}
		}
		
		if ($cmd == "activateselected" || $cmd=="deactivateselected") {
			$status = $cmd=="activateselected"?1:0;
			
			if (!empty($ids)){
				$inIDs = implode(",",$ids);
				$dbSet->execute("UPDATE links SET status=$status WHERE linkID IN (".$inIDs.")");
				
				$msg = "$count links have been ".($status==1?"activated":"deactivated");
				$cmd="";
			}
			$cmd="";
		}

        // urls
        $dbSet->open("SELECT * FROM urls WHERE memberID=$memberID" );
        $urls = $dbSet->fetchColsAll();
        $tpl->assign("urlIDs",$urls["urlID"]);
        $tpl->assign("urlNames",$urls["url"]);
        $tpl->assign("urlTitles",$urls["title"]);

        // keywords
        $dbSet->open("SELECT k.*
         FROM links l INNER JOIN urls u ON l.urlID=u.urlID
         INNER JOIN keywords k ON l.keywordID=k.keywordID
         WHERE memberID=$memberID" );
        $keywords = $dbSet->fetchColsAll();
        $tpl->assign("keywordIDs",$keywords["keywordID"]);
        $tpl->assign("keywordNames",$keywords["keywordName"]);


// links
        // filter
        $where = "1=1 ";
        // filter url
        if (!isset($urlID)) $urlID=0;
        if ($urlID!=0){
            $where.=" AND l.urlID=$urlID ";
        }
        $tpl->assign("urlID",$urlID);

        // filter url
        /*
        if (!isset($keywordID)) $keywordID=0;
        if ($keywordID!=0){
            $where.=" AND l.keywordID=$keywordID ";
        }
        $tpl->assign("keywordID",$keywordID);
        */
        if (!isset($keywordName)) $keywordName="";
        if ($keywordName!=""){
            $where.=" AND keywordName LIKE '%".$keywordName."%' ";
        }
        $tpl->assign("keywordName",$keywordName);
		
		// autobid
		if ($cmd=="autobidall"){
			if (!isset($maxBid)) $maxBid=0;
			$i=0;			
			foreach ($ids as $id){
				if (autobidLink($id, $memberID, $maxBid))
					$i++;
			}
			
			$msg = "$i bids were changed";			
			$cmd="";		
		}
		
		if ($cmd=="autobid"){
			if (!isset($maxBid)) $maxBid=0;
			$i=0;
			if (autobidLink($linkID, $memberID, $maxBid)){
					$i++;
			 }
			$msg = "$i bids were changed";			
			$cmd="";		
		}

		
		$linksPerPage = getOption("linksPerPage");
		// count
		if (!isset($page) || empty($page)) $page=1;
		$start=($page-1)*$linksPerPage;
        $dbSet->open("SELECT COUNT(*) as n
	         FROM links l INNER JOIN urls u ON l.urlID=u.urlID
	         INNER JOIN keywords k ON l.keywordID=k.keywordID
	         WHERE memberID=$memberID AND ".$where
         );
		$row=$dbSet->fetchArray();
		$nItems = $row["n"];
		
        // orderby
		if (!isset($orderby)) $orderby="l.linkID";
		if (!isset($orderdir)) $orderdir=" ASC ";		
		$tpl->assign("orderby",$orderby);
		$tpl->assign("orderdir",$orderdir);
		
        $links = array();
//        $dbSet->open("SELECT * FROM links WHERE memberID=$memberID" );
        $dbSet->open("SELECT l.linkID, l.title, l.bid,l.status, l.adminStatus, l.urlID,
         DATE_FORMAT(l.creationDate,'%Y-%m-%d') as creationDate,
         DATE_FORMAT(l.modificationDate,'%Y-%m-%d') as modificationDate,
         u.url as url, u.title as urltitle, k.keywordName as keywordName
         FROM links l INNER JOIN urls u ON l.urlID=u.urlID
         INNER JOIN keywords k ON l.keywordID=k.keywordID
         WHERE memberID=$memberID AND ".$where.
         "ORDER BY ".$orderby." ".$orderdir." ".
			 " LIMIT $start, ".$linksPerPage 
		);
        //$links = $dbSet->fetchRowsAll("array");
        $i=0;
        while($row = $dbSet->fetchArray()) {
                $links[$i] = $row;
                $links[$i]["activity"]=getLinkStatus($row["linkID"]);
                $i++;
        }

		// calc pages
		$pages=array(); $prev=$next=0;
		calcPages($nItems,$linksPerPage, $page, &$pages, &$prev,&$next);
		$tpl->assign("page",$page);		
		$tpl->assign("pages",$pages);
		$tpl->assign("prev",$prev);
		$tpl->assign("next",$next);
		/*
		dprint("page=".$page);
		dprint("next=".$next);
		dprint("prev=".$prev);
		*/
		
		//		
        $tpl->assign("links",$links);
        $tpl->assign("nLinks",$nItems);		
        
        $tpl->assign("memberID",$memberID);
        $tpl->assign("minBidValue", getOption("minBidValue"));
		
		if (!isset($msg)) $msg="";
		$tpl->assign("msg",$msg);

        $tpl->display("template.member.links.php");
?>