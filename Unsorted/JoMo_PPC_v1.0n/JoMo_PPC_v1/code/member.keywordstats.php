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
keyword searches statistics 
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
        $where = "1=1 ";
		
		// filter keyword
		if (!isset($keyword)) $keyword="";        
		if ($keyword!=""){       $where.=" AND lk.keyword LIKE '%".$keyword."%'";        }
        $tpl->assign("keyword",$keyword);
		
        // filter month
		$curtimestamp=time(); $curtime=getdate($curtimestamp);
		if (!isset($month)) $month=0;        
		/*
		if ($month==0) $month=$curtime["mon"];
		*/
		if ($month!=0){       $where.=" AND MONTH(lk.searchDate)=$month ";        }
        $tpl->assign("month",$month);
		if ($month==0) $detailsDate=1;
        
        // filter day
		if (!isset($day)) $day=-1;        
		if ($day!=0 && $day!=-1){       $where.=" AND DAYOFMONTH(lk.searchDate)=$day ";        }
		else if ($day==-1){       $where.=" AND MONTH(lk.searchDate)=MONTH(NOW()) AND DAYOFMONTH(lk.searchDate)=DAYOFMONTH(NOW())";        }
        $tpl->assign("day",$day);
        
		if ($day==0)
			$detailsDate=1;
		else
			$detailsDate=2;
		
	// top
	if (!isset($top) || empty($top)) $top=3;
	$tpl->assign("top",$top);
	
	// orderby
	if (!isset($orderby)) $orderby="l.linkID";
	if (!isset($orderdir)) $orderdir=" ";		
	$tpl->assign("orderby",$orderby);
	$tpl->assign("orderdir",$orderdir);
	
	if (!isset($detailsDate)) $detailsDate=1;

	$detailsLevel = $detailsDate+1;
	
	$aggColumns = array("nSearches");
	
	$allDateColumns = array(
		0=>array("name"=>"MONTH(lk.searchDate)", "alias"=>"m", "title"=>"month", "select"=>"MONTH(lk.searchDate) as m"),
		1=>array("name"=>"DAYOFMONTH(lk.searchDate)", "alias"=>"d", "title"=>"day","select"=>"DAYOFMONTH(lk.searchDate) as d")
		);
	$allKeywordColumns = array(
		0=>array("name"=>"keyword", "alias"=>"keyword", "title"=>"keyword","select"=>"lk.keyword as keyword",),
	);

	$allColumns = array_merge($allKeywordColumns, $allDateColumns);
	
	// make columns
	$dateColumns = array();
	for ($i=0;$i<$detailsDate;$i++){
		$dateColumns[]=$allDateColumns[$i];
	}

	$columns = array_merge($dateColumns, $allKeywordColumns);
	
	$cur = array();
	$cur = array("m"=>0,"d"=>0, "keyword"=>"");
	/*
	foreach ($columns as $column){
		$cur[$column["alias"]]="";
	}
	*/
	
	$orderColumns = array_kv($dateColumns,"name");
	$orderby = implode(",",$orderColumns);
	if ($orderby!="") $orderby.=",";
	$orderby .=" nSearches DESC";

	// from
	$from = "	FROM logKeywords lk		";

	
// do the work	
	// get all items
	// select ...
	$selectColumns = array_kv($columns, "select");
	$selectStr = implode(",", $selectColumns);
	// group ...
	$groupColumns = array_kv($columns, "name");
	$groupStr = implode(",", $groupColumns);

		
	$dbSet->open("SELECT ".$selectStr."
		, 
		SUM(lk.nSearches) as nSearches ".
		$from."
		WHERE 		
			".$where."
		GROUP BY ".$groupStr."
		ORDER BY $orderby $orderdir
		");

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
	
//	print_r($items);
	
	// add aggregate values
	$nStats=0;
	$stats = array();

	$nKeywords=0;
	
	foreach ($items as $item){
		$row = $item;
		
		$groupStr = "";
		
		// insert aggregate values for ...
		$changed=0;
		if ($row["keyword"]=="") continue;
		
		for ($level=0;$level<$detailsLevel;$level++){
			$column = $columns[$level];
			$alias = $column["alias"];
			
			if ($level>0)		$groupStr .= ", ".$column["name"];
			else				$groupStr .= $column["name"];
			
			if ($alias=="keyword"){
				$nKeywords++;
			}
			
			
			// if changed 
			if ($changed || $cur[$alias]!=(string)$row[$alias]){
				$changed=1;
				$cur[$alias]=(string)$row[$alias];
				
				if ($alias!="keyword"){
					$nKeywords=0;
				}
				if ($nKeywords>$top){
					continue;
				}

				
				// where
				$w="1=1";
				for ($i=0;$i<=$level;$i++){
					$w.=" AND ".($columns[$i]["name"])."='".$row[$columns[$i]["alias"]]."'";
				}
				
				$dbSet->open("SELECT ".$groupStr."
					, 
					SUM(lk.nSearches) as nSearches
					".$from."
					WHERE 		
						".$w."
					GROUP BY ".$groupStr." 
					ORDER BY $orderby $orderdir
					");
					
				$stat = $dbSet->fetchArray();
				
				// check
				/*
				if ($level<=1 && ($cur[$columns[0]["alias"]]==0)){
					continue;
				}
				*/
				/*
				if ($level>1 && $stat["nSearches"]==0){
					continue;
				}
				*/
				
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


// prepare $stats for displaying
$ss=array();

	for ($i=0;$i<sizeof($stats);$i++){
		$stat=$stats[$i];
		
		// map values
		
		$stat["m"] = $months[$stat["m"]];
		$stat["d"] = $stat["d"]==0?"all days":$stat["d"];
		
		$stat["keyword"] = $stat["keyword"]==""?"all keywords":$stat["keyword"];
		
		$j=0;
		$ss[$i]=array();
		foreach ($columns as $column){
			$ss[$i][$j] = $stat[$column["alias"]];
			$j++;
		}

		$stats[$i]=$stat;
	}

	$tpl->assign("columns",array_kv($columns,"title"));
    $tpl->assign("items",$stats);
    $tpl->assign("ss",$ss);
  
  	//$tpl->assign("levels",$levels);
  	$tpl->assign("detailsDate",$detailsDate);
		       

    $tpl->display("template.member.keywordstats.php");

?>

