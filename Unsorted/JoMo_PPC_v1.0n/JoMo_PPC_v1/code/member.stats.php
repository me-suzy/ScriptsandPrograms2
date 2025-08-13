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
statistics 
*/

	checkMemberPage();
	
/**
input:
$memberID
$cmd= 
sort: $orderby, $orderdir
filter:

$groupby=date|link
$detailsLevel=0,1,2
*/

  		
		$sID->assign("memberID",$memberID);
        $tpl->assign("memberID",$memberID);
		
        // check $cmd
        if (!isset($cmd)) $cmd="";

        if ($cmd == "") {
                
        }

/**************************************

**************************************/
		$tpl->assign("months",$months);
		$tpl->assign("monthIDs",$monthIDs);
		$tpl->assign("days",$days);
		$tpl->assign("years",$years);
		$tpl->assign("yearIDs",$yearIDs);
	
		
        // urls
        $dbSet->open("SELECT * FROM urls WHERE memberID=$memberID" );
        $urls = $dbSet->fetchColsAll();
        $tpl->assign("urlIDs",$urls["urlID"]);
        $tpl->assign("urlNames",$urls["title"]);
        //$tpl->assign("urlTitles",$urls["title"]);
		
        // banners
        $dbSet->open("SELECT * FROM banners WHERE memberID=$memberID" );
        $banners = $dbSet->fetchColsAll();
        $tpl->assign("bannerIDs",$banners["bannerID"]);
		$i=0;
		foreach ($banners["path"] as $path){
			$banners["path"][$i]=pathToName($path);
			$i++;
		}
        $tpl->assign("bannerNames",$banners["path"]);



       // filter
        $where = "1=1 AND affiliateID=0 ";
        
        // filter log type
        if (!isset($logType)) $logType="link";
        if ($logType=="banner"){    $where.=" AND (lc.logType='banner' OR lc.logType='impression')";       }
        else if ($logType!="all"){    $where.=" AND lc.logType='".$logType."' ";       }
        $tpl->assign("logType",$logType);      

		if ($logType=="banner"){        
			$urlID=$linkID=0;
		}
        // filter url
        if (!isset($urlID)) $urlID=0;
        if ($urlID!=0){    $where.=" AND l.urlID=$urlID ";       }
        $tpl->assign("urlID",$urlID);
	
	    // filter banner
        if (!isset($bannerID)) $bannerID=0;
        if ($bannerID!=0){    $where.=" AND b.bannerID=$bannerID ";       }
        $tpl->assign("bannerID",$bannerID);


        // filter keyword
        if (!isset($keywordID)) $keywordID=0;
        if ($keywordID!=0){       $where.=" AND l.keywordID=$keywordID ";        }
        $tpl->assign("keywordID",$keywordID);
        
		// filter date
		
        // get current time
		$curtimestamp=time(); $curtime=getdate($curtimestamp);
		
        // year
		if (!isset($year)) $year=$curtime["year"];        
		if ($year!=0){       $where.=" AND YEAR(lc.logDate)=$year ";        }
        
        // month
		if (!isset($month)) $month=0;        
		if ($month!=0){       $where.=" AND MONTH(lc.logDate)=$month ";        }
        
        // filter day
		if (!isset($day)) $day=-1;
		if ($day!=0 && $day!=-1){       $where.=" AND DAYOFMONTH(lc.logDate)=$day ";        }
		else if ($day==-1){       $where.=" AND YEAR(lc.logDate)=YEAR(NOW()) AND DAYOFMONTH(lc.logDate)=DAYOFMONTH(NOW())";        }
        
		if ($day==-1){
			$month = $curtime["mon"];
			$year = $curtime["year"];
			//$detailsDate=2; //?
		}

        $tpl->assign("year",$year);
        $tpl->assign("month",$month);
        $tpl->assign("day",$day);


	// orderby
	if (!isset($orderby)) $orderby="l.linkID";
	if (!isset($orderdir)) $orderdir=" ";		
	$tpl->assign("orderby",$orderby);
	$tpl->assign("orderdir",$orderdir);
	
	if (!isset($groupby)) $groupby="link";
	
	if (!isset($detailsDate)) $detailsDate=1;
	if (!isset($detailsLink)) $detailsLink=1;

	if ($logType=="banner"){
		$detailsLink=1;
	}
	
	//if (!isset($detailsLevel)) $detailsLevel=1;
	$detailsLevel = $detailsDate + $detailsLink;
	
	/*
	$startYear = 2002; $startMonth=8; $startDay=1;
	$endYear = 2002; $endMonth=8; $endDay=31;		
	*/
	
	/*
	$groupColumns = array("l.urlID","l.linkID", "MONTH(lc.logDate)","DAYOFMONTH(lc.logDate)");
	$nameColumns = array("urlID","linkID", "m","d");
	$columns = array("urlID", "linkID", "MONTH(lc.logDate)","DAYOFMONTH(lc.logDate)");
	l.urlID,l.linkID,l.keywordID,lc.logDate, YEAR(lc.logDate) as y, MONTH(lc.logDate) as m, DAYOFMONTH(lc.logDate) as d
	*/	
	
	$aggColumns = array("logCount", "cost", "avgpos", "maxpos");
	
	$allDateColumns = array(
		0=>array("name"=>"YEAR(lc.logDate)", "alias"=>"y", "title"=>"year", "select"=>"YEAR(lc.logDate) as y"),
		1=>array("name"=>"MONTH(lc.logDate)", "alias"=>"m", "title"=>"month", "select"=>"MONTH(lc.logDate) as m"),
		2=>array("name"=>"DAYOFMONTH(lc.logDate)", "alias"=>"d", "title"=>"day","select"=>"DAYOFMONTH(lc.logDate) as d")
	);
	$allLinkColumns = array(
		0=>array("name"=>"l.urlID", "alias"=>"urlID", "title"=>"url","select"=>"l.urlID as urlID",),
		1=>array("name"=>"l.linkID", "alias"=>"linkID", "title"=>"link","select"=>"l.linkID as linkID"),
		2=>array("name"=>"l.keywordID", "alias"=>"keywordID", "title"=>"keyword","select"=>"l.keywordID as keywordID"),
	);
	$allBannerColumns = array(
		0=>array("name"=>"b.bannerID", "alias"=>"bannerID", "title"=>"banner","select"=>"b.bannerID as bannerID",),
	);

	if ($logType=="link")
		$allListingColumns = $allLinkColumns;
	else if ($logType=="banner")
		$allListingColumns = $allBannerColumns;	
	else
		$allListingColumns = $allLinkColumns;	
	
	$allColumns = array_merge($allListingColumns, $allDateColumns);
	
	// make columns
	$dateColumns = array();
	for ($i=0;$i<$detailsDate;$i++){
		$dateColumns[]=$allDateColumns[$i];
	}

	$listingColumns = array();
	for ($i=0;$i<$detailsLink;$i++){
		$listingColumns[]=$allListingColumns[$i];
	}

	if ($groupby=="link")
		$columns = array_merge($listingColumns, $dateColumns);
	else
		$columns = array_merge($dateColumns,$listingColumns);
	
	$cur = array();
	foreach ($columns as $column){
		$cur[$column["alias"]]=0;
	}
	
	$orderColumns = array_kv($columns,"name");
	$orderby = implode(",",$orderColumns);

	// from
	if ($logType=="link"){
		$from = "	FROM logclicks lc 
			INNER JOIN  links l ON lc.listingID=l.linkID AND lc.logType='link'
			INNER JOIN urls u ON u.urlID=l.urlID 
			INNER JOIN keywords k ON l.keywordID=k.keywordID";
	}
	else {//if ($logType=="banner"){
		$from = "	FROM logclicks lc 
			INNER JOIN  banners b ON lc.listingID=b.bannerID AND (lc.logType='banner' OR lc.logType='impression')";
	}

	
// do the work	

	// get all items
	// select ...
	$selectColumns = array_kv($columns, "select");
	$selectStr = implode(",", $selectColumns);
	// group ...
	$groupColumns = array_kv($columns, "name");
	$groupStr = implode(",", $groupColumns);

//	dprint($where);
		
	$dbSet->open("SELECT ".$selectStr."
		, 
		COUNT(lc.logCount) as logCount, SUM(lc.cost) as cost, AVG(lc.lastPosition) as avgpos, MIN(lc.lastPosition) as maxpos ".
		$from."
		WHERE 		
			memberID=$memberID AND ".$where."
		GROUP BY ".$groupStr."
		ORDER BY $orderby $orderdir");

	// url, link, keyword, date, logCount, cost,...
	$items=array();
	$i=0;
	while ($row=$dbSet->fetchArray()){
		
		$items[$i]=$row;
		// zero columns
		foreach ($columns as $column){
			$alias = $column["alias"];
			if (!isset($row[$alias]) || empty($row[$alias])){
				$items[$i][$alias]=0;
			}
		}
		foreach ($aggColumns as $column){
			if (!isset($row[$column]) || empty($row[$column]))
				$items[$i][$column]=0;
		}
		
		//print_r($items[$i]);
		//echo "<br>";
				
		$i++;
	}
	
	//print_r($items);
	
	// add aggregate values
	$nStats=0;
	$stats = array();

	foreach ($items as $item){
		$row = $item;
		
		$groupStr = "";
		
		// insert aggregate values for ...
		$changed=0;
		
		for ($level=0;$level<$detailsLevel;$level++){
			
			$column = $columns[$level];
			$alias = $column["alias"];
			
			//dprint("test ".$alias);
			
			if ($level>0)		$groupStr .= ", ".$column["name"];
			else				$groupStr .= $column["name"];
			
			// if changed 
			if ($changed || $cur[$alias]!=$row[$alias]){
				//dprint("changed ".$alias);
				$changed=1;
				$cur[$alias]=$row[$alias];
				
				// where
				$where="1=1";
				for ($i=0;$i<=$level;$i++){
					$where.=" AND ".($columns[$i]["name"])."=".$row[$columns[$i]["alias"]];
				}
				
				$dbSet->open("SELECT ".$groupStr."
					, 
					COUNT(lc.logCount) as logCount, SUM(lc.cost) as cost, AVG(lc.lastPosition) as avgpos, MIN(lc.lastPosition) as maxpos
					".$from."
					WHERE 		
						memberID=$memberID AND affiliateID=0 AND ".$where."
					GROUP BY ".$groupStr." 
					ORDER BY $orderby $orderdir");
					
				$stat = $dbSet->fetchArray();
				/*
				$logCount = $stat["logCount"];
				$cost = $stat["cost"];
				$maxpos = $stat["maxpos"];
				$avgpos = $stat["avgpos"];	
				*/
				
				// check
				if ($level<=1 && ($cur[$columns[0]["alias"]]==0)){
					continue;
				}
				
				if ($level>1 && $stat["logCount"]==0){
					continue;
				}
				
				$stats[$nStats]=array("level"=>$level);
				
				// put aggregate values
				foreach ($aggColumns as $v){
					if (isset($stat[$v]) && !empty($stat[$v]))
						$stats[$nStats][$v]=$stat[$v];
					else 
						$stats[$nStats][$v]=0;
				}
				
				// zero
				for ($i=0;$i<sizeof($allColumns);$i++){
					$stats[$nStats][$allColumns[$i]["alias"]] = 0;
				}
				
				for ($i=0;$i<=$level;$i++){
					$stats[$nStats][$columns[$i]["alias"]] = $cur[$columns[$i]["alias"]];
				}

				$nStats++;
				
			}
			
		//$i++;
		}
	
	}

//print_r($stats);	


// prepare $stats for displaying
$ss=array();

	for ($i=0;$i<sizeof($stats);$i++){
		$stat=$stats[$i];
		
		// map values
		// link
		if ($logType=="link"){
			$url = getUrl($stat["urlID"]);
			$link = getLink($stat["linkID"]);
			if ($stat["linkID"]!=0){
				$keyword = getKeyword($link["keywordID"]);
				$keywordName = $keyword["keywordName"];
			}
			else
				$keywordName = "";
			
			$stat["urlID"] = $stat["urlID"]==0?"all urls":$url["url"];
			$stat["linkID"] = $stat["linkID"]==0?"all links":$link["title"];
			$stat["keywordID"] = $stat["keywordID"]==0?"--":$keyword["keywordName"];

		}
		//banner
		else{
			$banner = getBanner($stat["bannerID"]);
			$stat["bannerID"] = $stat["bannerID"]==0?"all banners":(pathToName($banner["path"].($banner["isPerImpression"]==1?" (impression)":"") ));
			
		}

		$stat["m"] = $months[$stat["m"]];
		$stat["d"] = $stat["d"]==0?"all days":$stat["d"];
		$stat["y"] = $stat["y"]==0?"all":$stat["y"];
		
		$ss[$i]["avgpos"]=$stat["avgpos"] = floor($stat["avgpos"]*10)/10;
		
		$j=0;
		$ss[$i]=array();
		foreach ($columns as $column){
			//$stat[$column["title"]] = $stat[];
			//$stat[$j] = $stat[$column["alias"]];
			$ss[$i][$j] = $stat[$column["alias"]];
			
			$j++;
		}

		$stats[$i]=$stat;
	}

	$tpl->assign("columns",array_kv($columns,"title"));
//    $tpl->assign("items",$items);
    $tpl->assign("items",$stats);
    $tpl->assign("ss",$ss);
  
  	$tpl->assign("groupby",$groupby);
  	
  	//$tpl->assign("levels",$levels);
  	$tpl->assign("levelNumbers",array(1,2,3,4));
  	$tpl->assign("detailsLevel",$detailsLevel);
  	$tpl->assign("detailsDate",$detailsDate);
  	$tpl->assign("detailsLink",$detailsLink);
		       

    $tpl->display("template.member.stats.php");

?>

