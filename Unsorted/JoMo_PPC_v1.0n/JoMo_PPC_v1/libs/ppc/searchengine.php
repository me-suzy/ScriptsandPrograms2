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
<?php
/**
search
*/

// returns matching links from links table
  function findLinks($str,$start, $count)
  {
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
	  
	  $minBidValue = getOption("minBidValue");
	  if (!isset($minBidValue)) $minBidValue=0.01;
	  
	   // search in keywords
	   // count total links
	   $dbSet->open("SELECT COUNT(*) AS totalLinks
	    FROM links l INNER JOIN keywords k ON l.keywordID=k.keywordID
	    WHERE (keywordName LIKE '%".$str."%') AND
	     status=1 and adminStatus=1 and accountStatus=1 AND l.bid>=".$minBidValue."
	    ORDER BY l.bid DESC, l.creationDate ASC
	   ");
	   $r=$dbSet->fetchArray();
	   $n = $r["totalLinks"];
	   $results["totalLinks"]=$n;
   
	   // search
	   $dbSet->open("SELECT l.linkID as linkID, u.url as linkURL, l.bid as bid, l.title as title, SUBSTRING(l.description,1,200) as description
	    FROM links l INNER JOIN keywords k ON l.keywordID=k.keywordID
	    INNER JOIN urls u ON u.urlID=l.urlID
	    WHERE (keywordName LIKE '%".$str."%') AND
	     status=1 and adminStatus=1 and accountStatus=1 AND l.bid>=".$minBidValue."
	    ORDER BY l.bid DESC, l.creationDate ASC
	    LIMIT $start,$count");
	   
	   $results["count"]=$dbSet->numRows();
   
	   $results["links"]=array();
	   
	   while ($row=$dbSet->fetchArray()){
	    $results["links"][] = $row;
	   }
	   $dbSet->Close();

	   /*
	   // search in titles and descriptions
	   $dbSet->open("SELECT linkID, bid, l.title as title, SUBSTRING(l.description,1,200) as description
	    FROM links l INNER JOIN keywords k ON l.keywordID=k.keywordID
	     INNER JOIN urls u ON l.urlID=u.urlID
	    WHERE (l.title LIKE '%".$str."%' OR l.description LIKE '%".$str."%' OR u.title LIKE '%".$str."%' OR u.description LIKE '%".$str."%')
	     AND l.status=1
	    ORDER BY l.bid DESC, l.creationDate ASC");
	
	   while ($row=$dbSet->fetchArray()){
	    $results[] = $row;
	   }
	   */

	   return $results;

  }

  /**
  return $banner["top"][], $banner["bottom"][]
  */
  function findBanners($str)
  {
		global $dbObj;
		$dbSet=new xxDataset($dbObj);
	
	  	if (getOption("showBanners")==0) return array();
	  	
		$minBidValue = getOption("minBidValue");
	  if (!isset($minBidValue)) $minBidValue=0.01;
	  
	   // search
	   $banner = array();
	   
	   // top banner = top bidded keyword, then pool
	   // calc max bid
	   $dbSet->open("SELECT MAX(b.bid) as maxbid
	    FROM banners b
	    WHERE ((keywords LIKE '%".$str."%') OR isCatchAll=1 ) AND
			isPerImpression=0 AND 
	     status=1 and adminStatus=1 and accountStatus=1 and memberID<>0 AND bid>=".$minBidValue."
	    ");
	    
	    $row = $dbSet->fetchArray();
	    $maxbid=$row["maxbid"];
	    if (empty($row["maxbid"])) 
	    	$maxbid=0;
		
		// get top bidded keyword banners	    
	   $dbSet->open("SELECT b.bannerID as bannerID,url, bid, memberID, path,isPerImpression
	    FROM banners b
	    WHERE ((keywords LIKE '%".$str."%') OR isCatchAll=1 ) AND
			isPerImpression=0 AND 
	     status=1 and adminStatus=1 and accountStatus=1 and memberID<>0 AND bid>=".$minBidValue."
	     AND bid=$maxbid
	    ORDER BY b.bid DESC, b.creationDate ASC    ");
	   
	   //$banner["top"]=$dbSet->fetchArray();
	   //$banner["bottom"]=$dbSet->fetchArray();
	   
	   $n=$dbSet->numRows();
	   $banners=array();
	   $i=0;
	   while ($row=$dbSet->fetchArray()){
	    $banners[] = $row;
	    $i++;
	   }
   
   if ($n>0){
   		srand ((double) microtime() * 1000000);
	   	$N = $n;
	    if ($N>0){
		   $topindex = $N<=1 ? 0 : rand(0,$N-1);
		   $banner["top"]=$banners[$topindex];
		}
   }
   // pool 
   else{
	   $nPool=0;
	   //if ($n<=1){
		   	 $dbSet->open("SELECT b.bannerID as bannerID,url, bid, path, isPerImpression
		    FROM banners b
		    WHERE 
				status=1 and adminStatus=1 and accountStatus=1 AND
				memberID=0
		    ORDER BY b.bid DESC, b.creationDate ASC    ");
		   $nPool=$dbSet->numRows();
		   $banners=array();
		   while ($row=$dbSet->fetchArray()){
		    $banners[] = $row;
		    $i++;
		   }
	
	   //}
	   srand ((double) microtime() * 1000000);
	   $N = $nPool;
	   if ($N>0){
		   $topindex = $N<=1 ? 0 : rand(0,$N-1);
		   $banner["top"]=$banners[$topindex];
		}
	   
	   
	}
   
   /*
	   //dprint("banners");
	   if ($n+$nPool==0) return array();
	   srand ((double) microtime() * 1000000);
		
		$N=$n;
		if ($N<=0) $N=$n+$nPool;
	    $topindex = $N<=1 ? 0 : rand(0,$N-1);
	   
	    $banner["top"]=$banners[$topindex];
	 */
	 
	 // bottom banner = random from pool + perimpr
	 $optionPerImpression = getOption("bannerPerImpression");
	 $nPool=0;
   	 $dbSet->open("SELECT b.bannerID as bannerID,url, bid, path, isPerImpression
	    FROM banners b
	    WHERE 
			adminStatus=1  AND
			((isPerImpression=1 AND $optionPerImpression=1 AND accountStatus=1 and status=1 ) OR memberID=0)
	    ORDER BY b.bid DESC, b.creationDate ASC    ");
	   $nPool=$dbSet->numRows();
	   $banners=array();
	   $i=0;
	   while ($row=$dbSet->fetchArray()){
	    $banners[] = $row;
	    $i++;
	   }
		
		//dprint("bottom banners:");
		//print_r($banners);
		
	   srand ((double) microtime() * 1000000);
	   $N = $nPool;
	   if ($N>0){
		   $bottomindex = $N<=1 ? 0 : rand(0,$N-1);
		    $banner["bottom"]=$banners[$bottomindex];
		}
	  
	/*	   
	    if ($N<=1) $N=$n+$nPool;
	    if ($N<=1) return $banner;
	    $bottomindex = $N<=1 ? 0 : rand(0,$N-1);
		if ($bottomindex==$topindex){
			$bottomindex=$topindex-1;
			if ($bottomindex<0) $bottomindex=$topindex+1;
		}
	    $banner["bottom"]=$banners[$bottomindex];
	  */    
  	    $dbSet->Close();
	
	    return $banner;

  }


	function getXMLLinks($affiliateID, $items, $page=0, $count=10, $from=-1){
		$s = "<?xml version=\"1.0\" encoding=\"windows-1251\"?>";
		$s.="<root>"."\n";
		$c=sizeof($items);
		$s.="<count>$c</count>"."\n";
		$s.="\t\t<affiliateID>$affiliateID</affiliateID>"."\n";		

		$s.="<result>"."\n";
		
		if ($count==0) $count=sizeof($items);
		if ($from==-1) $from = ($page-1)*$count;
		
		//for ($i=$from;$i<sizeof($items) && $i<$from+$c;$i++){
		for ($i=0; $i<sizeof($items) && $i<$count; $i++){
			$pos = $from+$i+1;
			$s.="\t<site position=\"$pos\">"."\n";
			//$s.="\t<site >"."\n";
			
			$item=$items[$i];
			$title = $item["title"];
			$url = $item["linkURL"];
			$description = $item["description"];
			$bid = $item["bid"];
			
			$s.="<linktitle> <![CDATA[ $title  ]]>  </linktitle>"."\n";		
			$s.="\t<url> <![CDATA[ ".$url." ]]>  </url>"."\n";
			$s.="\t<description> <![CDATA[ ".$description." ]]> </description>"." \n";		
			$s.="\t<bid>$bid</bid>"."\n";
			
			$s.="\t</site>"."\n";
		}

		$s.="</result>"."\n";	
		$s.="</root>"."\n";	
		
		return $s;
	}

	
?>
