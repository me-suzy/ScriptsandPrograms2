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
faq
*/

	checkAdminPage();
	
/**
input:
*/

// cmd
        // check $cmd
        if (!isset($cmd)) $cmd="";
		if (!isset($page) || empty($page)) $page=1;
		$msg="";
		
		
		if ($cmd == "delete") {
                $dbSet->execute("DELETE FROM faq WHERE questionID=$questionID");
                $msg = "question has been deleted";
                $cmd="";
        }

	
// load page
		
		// categories
        $dbSet->open("SELECT DISTINCT category FROM faq");
        $categories = array();
        while ($row=$dbSet->fetchArray()){
            $categories[]=$row["category"];
        }                      
        $tpl->assign("categoryIDs",$categories);
        $tpl->assign("categoryNames",$categories);
        

        // q
           // filter
        $where = "1=1 ";
        
        // filter category
        if (!isset($category)) $category="";
        if ($category!="") $where.=" AND category='".$category."'";
        
        $tpl->assign("category",$category);
        

		// count
		$start=($page-1)*$linksPerPage;
        $dbSet->open("SELECT COUNT(*) as n
	         FROM faq f 
	         WHERE ".$where);
		$row=$dbSet->fetchArray();
		$nItems = $row["n"];

		// orderby
		if (!isset($orderby)) $orderby="questionID";
		if (!isset($orderdir)) $orderdir=" ASC ";		
		$tpl->assign("orderby",$orderby);
		$tpl->assign("orderdir",$orderdir);
		
		// links
        $questions = array();
        $dbSet->open("SELECT questionID, question, category, CONCAT(SUBSTRING(answer, 1,100),'...') as answer
	         FROM faq f 
	         WHERE ".$where.
			 "ORDER BY ".$orderby." ".$orderdir." ".
			 " LIMIT $start, ".$linksPerPage );
			 
        while($row = $dbSet->fetchArray()) {
                $questions[] = $row;
        }
		
        $tpl->assign("questions",$questions);
		$tpl->assign("nQuestions",$nItems);		
		
		// calc pages
		$pages=array(); $prev=$next=0;
		calcPages($nItems,$linksPerPage, $page, &$pages, &$prev,&$next);
		
		$tpl->assign("page",$page);		
		$tpl->assign("pages",$pages);
		$tpl->assign("prev",$prev);
		$tpl->assign("next",$next);
		
		$tpl->assign("msg",$msg);
		
        // display template
        $tpl->display("admin/template.admin.faq.php");
?>