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
logs, statistics
*/

/**
logType = "link"|"banner"|"impression"
*/	
  function addToLog($listingID,$affiliateID, $ppc_user_id, $bid, $logType="link",$position=0){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
	
		$table = "logclicks";
		$columnID = "listingID";

	   $dbSet->open("SELECT * FROM ".$table." WHERE ".$columnID."=$listingID
	   AND logType='".$logType."'
	   AND affiliateID=$affiliateID
	   AND userID='".$ppc_user_id."'
	           AND DAYOFYEAR(logDate) = DAYOFYEAR(NOW()) AND YEAR(logDate) = YEAR(NOW())
	        ORDER BY logDate DESC");
	   $n=$dbSet->numRows();
	   if ($n==0){
	   	       $dbSet->execute("INSERT INTO ".$table." (".$columnID.",
	           affiliateID, logType, logDate, userID, cost, logCount, lastPosition)
	            VALUES($listingID, $affiliateID,'".$logType."' ,NOW(),'".$ppc_user_id. "', '".$bid."', 1, $position)");
	   }
	   else{
		    $row = $dbSet->fetchArray();
		    $logID = $row["logID"];
		    $dbSet->execute("UPDATE ".$table." SET logCount=logCount+1, 
				cost='".$bid."',
				lastPosition=$position, logDate=NOW()
		     WHERE logID=$logID");
	   }

  }

  function isClickUnique($listingID,$affiliateID, $ppc_user_id, $clickType="link"){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
		
		$table = "logclicks";
		$columnID = "listingID";
		$logType = $clickType;
		
	   $dbSet->open("SELECT * FROM ".$table." WHERE ".$columnID."=$listingID
	   AND logType='".$logType."' AND affiliateID=$affiliateID
	   AND userID='".$ppc_user_id."'
	           AND DAYOFYEAR(logDate) = DAYOFYEAR(NOW()) AND YEAR(logDate) = YEAR(NOW())
	           AND logCount>0
	        ORDER BY logDate DESC");
	        
	   $n=$dbSet->numRows();
	   if ($n==0){
	   	 return true;
	  }
		
	   return false;
  }

	function addImpressionBannerToLog($bannerID, $affiliateID,$ppc_user_id, $bid){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
	
		$table = "logclicks";
		$columnID = "listingID";
		
		addToLog($bannerID,$affiliateID, $ppc_user_id, $bid, "impression",0);
		
	}

  function addKeywordToLog($keyword){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
	

		$keyword = trim($keyword);
		$keyword = preg_replace("/ +/"," ",$keyword);
		if ($keyword=="") return false;
		$table = "logKeywords";
		$columnID = "logID";

		//			   AND HOUR(NOW())=HOUR(searchDate) AND MINUTE(NOW())=MINUTE(searchDate)

	   $dbSet->open("SELECT * FROM ".$table." WHERE keyword='".$keyword."' 
	           AND DAYOFYEAR(NOW())=DAYOFYEAR(searchDate) AND YEAR(NOW())=YEAR(searchDate)
			   AND searchDate IS NOT NULL
	        ORDER BY searchDate DESC");
	   $n=$dbSet->numRows();
	   if ($n==0){
	           $dbSet->execute("INSERT INTO ".$table." (keyword, searchDate, nSearches)
	            VALUES('".$keyword."', NOW(), 1)");
	   }
	   else{
	    $row = $dbSet->fetchArray();
	    $logID = $row["logID"];
	    $dbSet->execute("UPDATE ".$table." SET nSearches=nSearches+1
	     	WHERE logID=$logID");
	   }
	   
	   // pack older searches
	   packOldKeywords();
	   
  }

   // õðàíèòü ïîäðîáíî òîëüêî çà ...
   // îñòàëüíûå - çàïàêîâàòü - äëÿ êàæäîãî ñëîâà-îäíà ñòðîêà (îçíà÷àåò îáùåå êîë-âî ñòàðûõ ïîèñêîâ 	ýòîãî ñëîâà)
	
	function packOldKeywords(){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
   	   $set2=new xxDataset($dbObj);
		
		$table = "logKeywords";
		$period = 60*60*24*30*365; //year
		
		$dbSet->open("SELECT * FROM ".$table." 
			WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(searchDate) > $period 
	        ORDER BY searchDate DESC");
			
		// nothing to do
		if ($dbSet->numRows()<1) return;
		
		while ($row = $dbSet->fetchArray()){
			$keyword = $row["keyword"];
			$count = $row["nSearches"];
			
			$set2->open("SELECT * FROM ".$table." 
				WHERE searchDate IS NULL AND keyword='".$keyword."'
	        ");
			if ($set2->numRows()==0){
		  		$set2->execute("INSERT INTO $table (keyword, searchDate, nSearches) 
					VALUES ('".$keyword."', NULL, $count)");
		  	}
		  	else{
				$row2 = $dbSet->fetchArray();
			    $logID = $row2["logID"];
		    	$set2->execute("UPDATE ".$table." SET nSearches=nSearches+$count
	    	 		WHERE logID=$logID AND searchDate IS NULL");
		  	}
			
		}
		$dbSet->open("DELETE FROM ".$table." 
			WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(searchDate) > $period ");

	}
	  
  function logVisitor(){
  	global $sID;
  	global $dbObj;
  	
	$dbSet=new xxDataset($dbObj);
  	$id = $sID->getSessionId();
  	
  	// del old visitors
  	
  	// log
  	$dbSet->open("SELECT * FROM visitors WHERE sessionID='".$id."'");
  	$n=$dbSet->numRows();
  	if ($n==0){
  		$dbSet->execute("INSERT INTO visitors (sessionID, lastVisit) VALUES ('".$id."', NOW())");
  	}
  	else{
  		$dbSet->execute("UPDATE visitors SET lastVisit=NOW()
  			WHERE sessionID='".$id."'");
  	}

  }
  
  // return visitors currently online
  function getOnlineVisitors(){
  	global $sID;
  	global $dbObj;
  	
	$dbSet=new xxDataset($dbObj);
  	
  	$dbSet->open("SELECT * FROM visitors 
  		WHERE 
  			UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(lastVisit) < 60*5");
  	
  	return $dbSet->numRows();
  }  
  
  function getMemberCount(){
  	global $sID;
  	global $dbObj;
  	
	$dbSet=new xxDataset($dbObj);
  	
  	$dbSet->open("SELECT * FROM members");
  	
  	return $dbSet->numRows();
  }
  
  function getActiveLinks(){
  	global $sID;
  	global $dbObj;
  	
	$dbSet=new xxDataset($dbObj);
  	
  	$minBidValue = getOption("minBidValue");
  	$dbSet->open("SELECT * FROM links
  		WHERE status=1 AND adminStatus=1 AND accountStatus=1 AND bid>=$minBidValue");
  	
  	return $dbSet->numRows();
  }
  

  function getTopSearchedKeywords($count=10){
	  	global $sID;
	  	global $dbObj;
	  	
		$dbSet=new xxDataset($dbObj);
	  	
	  	$dbSet->open("SELECT DISTINCT keyword FROM logKeywords
	  		WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(searchDate) < 60*60*24*30
	  		ORDER BY keyword
	  		LIMIT $count");
	  	$keywords=array();
	  	while ($row=$dbSet->fetchArray()){
	  		$keywords[]=$row;
	  	}
	  	
	  	return $keywords;
  }
  
  
  function getTodaySearchCount(){
  	global $sID;
  	global $dbObj;
  	
	$dbSet=new xxDataset($dbObj);
  	$period = 60*60*24;
	
  	$dbSet->open("SELECT SUM(nSearches) as s FROM logKeywords
  		WHERE 
  			UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(searchDate) < $period");
  	$row = $dbSet->fetchArray();
	if (empty($row["s"])) return 0;
  	return $row["s"];
  
  }
  
  function getAverageDaySearchCount(){
  	global $sID;
  	global $dbObj;
  	
	$dbSet=new xxDataset($dbObj);
  	$period = 60*60*24*30; // month
	$p = 60*60*24; // day
	//$period = 60*60; // debug
	
  	$dbSet->open("SELECT SUM(nSearches) as s FROM logKeywords
  		WHERE 
  			searchDate IS NOT NULL
			AND UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(searchDate) < $period
		GROUP BY CEILING(UNIX_TIMESTAMP(searchDate)/$p)");
	
	if ($dbSet->numRows()==0) return 0;
	$sum=0; $n=0;
  	while ($row = $dbSet->fetchArray())
	{
		$sum+=$row["s"];
		$n++;
	}
  	return floor($sum/$n*100)/100;
  
  }
  
  function getLastMonthSearchCount(){
  	global $sID;
  	global $dbObj;
  	
	$dbSet=new xxDataset($dbObj);
  	$period = 60*60*24*30; // month
	
  	$dbSet->open("SELECT SUM(nSearches) as s, COUNT(*) as c FROM logKeywords
  		WHERE 
			searchDate IS NOT NULL AND
  			UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(searchDate) < $period");
  	$n = $dbSet->numRows();
  	if ($n==0) return 0;
  	$row = $dbSet->fetchArray();
  	if (!isset($row["s"])) return 0;
  	return $row["s"];
  
  }
  
  function getMaxBidOfKeyword($keyword){
  	global $sID;
  	global $dbObj;
  	
	$dbSet=new xxDataset($dbObj);
	
  	$dbSet->open("SELECT MAX(l.bid) as m 
  		FROM keywords k
  		INNER JOIN links l ON l.keywordID=k.keywordID
  		WHERE keywordName LIKE '%".$keyword."%' 
  			AND l.status=1 and l.adminStatus=1 AND l.accountStatus=1
		ORDER BY bid DESC");
  	$row = $dbSet->fetchArray();
  	if (!isset($row["m"]) || empty($row["m"])) return 0;
  	return $row["m"];
  
  }
  
  
?>