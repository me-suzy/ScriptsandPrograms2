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

	checkAdminPage();
	
/**
input:
$cmd= 
sort: $orderby, $orderdir
filter:

$groupby=date|link
$detailsLevel=0,1,2
*/

		
        // check $cmd
        if (!isset($cmd)) $cmd="";

        if ($cmd == "delete") {
        	$where = "1=1 ";

  			// filter keyword
			if (!isset($keyword)) $k="";  else $k=$keyword;
			if ($k!=""){       $where.=" AND keyword = '".$k."'";        }
			$keyword = "";

			// filter date
			$curtimestamp=time(); $curtime=getdate($curtimestamp);
	        // year
			if (!isset($year)) $y=$curtime["year"];      	else $y=$year;
			if ($y!=0){       $where.=" AND YEAR(searchDate)=$y ";        }
	        
	        // month
			if (!isset($m)) $m=0;  else $m=$month;
			if ($m!=0){       $where.=" AND MONTH(searchDate)=$m ";        }
	        
	        // filter day
			if (!isset($day)) $d=-1;	else $d=$day;
			if ($d!=0 && $d!=-1){       $where.=" AND DAYOFMONTH(searchDate)=$d ";        }
			else if ($d==-1){       $where.=" AND YEAR(searchDate)=YEAR(NOW()) AND DAYOFMONTH(searchDate)=DAYOFMONTH(NOW())";        }
	        
			if ($d==-1){
				$m = $curtime["mon"];			$y = $curtime["year"];
	        }
	        
	        $dbSet->execute("DELETE FROM logKeywords WHERE $where");
	        //dprint("DELETE FROM logKeywords WHERE $where");
	        $cmd="";
	        
        }
        

/**************************************

**************************************/
		$tpl->assign("months",$months);
		$tpl->assign("monthIDs",$monthIDs);
		$tpl->assign("days",$days);
		$tpl->assign("years",$years);
		$tpl->assign("yearIDs",$yearIDs);

        // members
        $dbSet->open("SELECT * FROM members " );
        $members = $memberIDs = $memberNames = array();
        while ($row=$dbSet->fetchArray()){
        	$memberIDs[] = $row["memberID"];
        	$memberNames[] = $row["firstName"]." ".$row["lastName"];
        }
        $tpl->assign("memberIDs",$memberIDs);
        $tpl->assign("memberNames",$memberNames);

		
       // filter
        $where = "1=1 ";
		
		// filter keyword
		if (!isset($keyword)) $keyword="";        
		if ($keyword!=""){       $where.=" AND lk.keyword LIKE '%".$keyword."%'";        }
        $tpl->assign("keyword",$keyword);
		

   		// filter date
		
        // filter month
		$curtimestamp=time(); $curtime=getdate($curtimestamp);

        // year
		if (!isset($year)) $year=$curtime["year"];        
		if ($year!=0){       $where.=" AND YEAR(lk.searchDate)=$year ";        }
        
        // month
		if (!isset($month)) $month=0;        
		if ($month!=0){       $where.=" AND MONTH(lk.searchDate)=$month ";        }
        
        // filter day
		if (!isset($day)) $day=-1;
		if ($day!=0 && $day!=-1){       $where.=" AND DAYOFMONTH(lk.searchDate)=$day ";        }
		else if ($day==-1){       $where.=" AND YEAR(lk.searchDate)=YEAR(NOW()) AND DAYOFMONTH(lk.searchDate)=DAYOFMONTH(NOW())";        }
        
		if ($day==-1){
			$month = $curtime["mon"];
			$year = $curtime["year"];
			$detailsDate=3; //?
		}

        $tpl->assign("year",$year);
        $tpl->assign("month",$month);
        $tpl->assign("day",$day);

		
	// top
	if (!isset($top) || empty($top)) $top=3;
	$tpl->assign("top",$top);
	
	// orderby
	if (!isset($orderby)) $orderby="l.linkID";
	if (!isset($orderdir)) $orderdir=" ";		
	$tpl->assign("orderby",$orderby);
	$tpl->assign("orderdir",$orderdir);
	
	if (!isset($detailsDate)) $detailsDate=2;

	$detailsLevel = $detailsDate+1;
	
	$aggColumns = array("nSearches");
	
	$allDateColumns = array(
		0=>array("name"=>"YEAR(lk.searchDate)", "alias"=>"y", "title"=>"year", "select"=>"YEAR(lk.searchDate) as y"),
		1=>array("name"=>"MONTH(lk.searchDate)", "alias"=>"m", "title"=>"month", "select"=>"MONTH(lk.searchDate) as m"),
		2=>array("name"=>"DAYOFMONTH(lk.searchDate)", "alias"=>"d", "title"=>"day","select"=>"DAYOFMONTH(lk.searchDate) as d")
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
	foreach ($columns as $column){
		$cur[$column["alias"]]=0;
	}
	
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
		
		$stat["dayID"] = $stat["d"];
		$stat["monthID"] = $stat["m"];
		$stat["year"] = $stat["y"];
		$stat["key"] = ($stat["keyword"]=="")?"":$stat["keyword"];
		
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
	//print_r($stats);
    $tpl->assign("items",$stats);
    $tpl->assign("ss",$ss);
  
  	$tpl->assign("detailsDate",$detailsDate);
		       

    $tpl->display("admin/template.admin.keywordstats.php");

?>