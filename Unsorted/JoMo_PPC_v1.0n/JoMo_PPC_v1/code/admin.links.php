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

	checkAdminPage();
	
/**
input:
filter: $memberID, $urlID, $keywordName
$page
*/

// cmd
        // check $cmd
        if (!isset($cmd)) $cmd="";
		if (!isset($page) || empty($page)) $page=1;
		$msg="";
		
        if ($cmd == "activate" || $cmd=="deactivate") {
			$adminStatus = $cmd=="activate"?1:0;
			$dbSet->execute("UPDATE links SET adminStatus=$adminStatus WHERE linkID=$linkID");
		}
		
		if ($cmd == "delete") {
                deleteLink($linkID);
                $msg = "link has been deleted";
                $cmd="";
        }

	
// load page
		
		// members
		//firstName+' '+lastName 
		$dbSet->open("SELECT memberID, CONCAT(firstName,' ',lastName)  as name FROM members" );
        $members = $dbSet->fetchColsAll();
        $tpl->assign("memberIDs",$members["memberID"]);
        $tpl->assign("memberNames",$members["name"]);
        
        // urls
        $dbSet->open("SELECT * FROM urls" );
        $urls = $dbSet->fetchColsAll();
        $tpl->assign("urlIDs",$urls["urlID"]);
        $tpl->assign("urlNames",$urls["url"]);

        // links
           // filter
        $where = "1=1 ";
        
        // filter member
        if (!isset($memberID)) $memberID=0;
        if ($memberID!=0){
            $where.=" AND u.memberID=$memberID ";
        }
        $tpl->assign("memberID",$memberID);
        

        // filter url
        if (!isset($urlID)) $urlID=0;
        if ($urlID!=0){
            $where.=" AND l.urlID=$urlID ";
        }
        $tpl->assign("urlID",$urlID);

        // filter url
        if (!isset($keywordName)) $keywordName="";
        if ($keywordName!=""){
            $where.=" AND k.keywordName LIKE '%".$keywordName."%' ";
        }
        $tpl->assign("keywordName",$keywordName);


		if ($cmd == "deactivateall" || $cmd=="activateall") {
			$adminStatus = $cmd=="activateall"?1:0;
			
			$dbSet->open("SELECT l.linkID
	         FROM links l 
	         INNER JOIN urls u ON l.urlID=u.urlID
	         INNER JOIN keywords k ON l.keywordID=k.keywordID
	         WHERE ".$where);
			 
			$n = $dbSet->numRows();			 
			
			$linkIDs=" 0";
	        while($row = $dbSet->fetchArray()) {
				$linkIDs.= ", ".$row["linkID"];
            }
			
			$dbSet->execute("UPDATE links SET adminStatus=$adminStatus WHERE linkID IN (".$linkIDs.")");
			$msg = $n." links were updated";
		}
		
		// count
		$start=($page-1)*$linksPerPage;
        $dbSet->open("SELECT COUNT(*) as n
	         FROM links l 
	         INNER JOIN urls u ON l.urlID=u.urlID
	         INNER JOIN keywords k ON l.keywordID=k.keywordID
	         WHERE ".$where);
		$row=$dbSet->fetchArray();
		$nItems = $row["n"];

		// orderby
		if (!isset($orderby)) $orderby="linkID";
		if (!isset($orderdir)) $orderdir=" ASC ";		
		$tpl->assign("orderby",$orderby);
		$tpl->assign("orderdir",$orderdir);
		
		// links
        $links = array();
        $dbSet->open("SELECT l.linkID, l.title, l.bid,l.status, l.adminStatus, l.urlID, m.*,
	         DATE_FORMAT(l.creationDate,'%Y-%m-%d') as creationDate,
	         DATE_FORMAT(l.modificationDate,'%Y-%m-%d') as modificationDate,
	         u.url as url, u.title as urltitle, k.keywordName as keywordName
	         FROM links l 
	         INNER JOIN urls u ON l.urlID=u.urlID
	         INNER JOIN members m ON u.memberID=m.memberID
	         INNER JOIN keywords k ON l.keywordID=k.keywordID
	         WHERE ".$where.
			 "ORDER BY ".$orderby." ".$orderdir." ".
			 " LIMIT $start, ".$linksPerPage );
			 
        //$links = $dbSet->fetchRowsAll("array");
		$linkIDs=" 0";
        $i=0;

        while($row = $dbSet->fetchArray()) {
				$linkIDs.= ", ".$row["linkID"];
                $links[$i] = $row;

				$adminStatus=$row["adminStatus"];
				
                $links[$i]["activity"]=$adminStatus=="1"?"enabled":"disabled";
                $links[$i]["memberName"]= $row["firstName"]." ".$row["lastName"];
                $i++;
        }
		
        $tpl->assign("links",$links);
		$tpl->assign("nLinks",$nItems);		
		
		// calc pages
		$pages=array(); $prev=$next=0;
		calcPages($nItems,$linksPerPage, $page, &$pages, &$prev,&$next);
		
		$tpl->assign("page",$page);		
		$tpl->assign("pages",$pages);
		$tpl->assign("prev",$prev);
		$tpl->assign("next",$next);
		
		$tpl->assign("msg",$msg);
		
        // display template
        $tpl->display("admin/template.admin.links.php");
?>