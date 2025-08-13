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
click statistics for affiliate
*/

	checkAffiliatePage();

/**
input:
$affiliateID
$cmd= 
sort: $orderby, $orderdir
filter:

$groupby=date
$detailsDate = 1|2
*/

 $memberType = "affiliate";
 $tpl->assign("memberType",$memberType);
 
 // member info
 $affiliate = getMember($affiliateID, $memberType);
 
 $tpl->assign("info",$affiliate["info"]);
 $tpl->assign("account",$affiliate["account"]);
 

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
	
		
       // filter
        $where = "1=1 ";
		
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

			
	// top
	if (!isset($top) || empty($top)) $top=3;
	$tpl->assign("top",$top);
	
	// orderby
	if (!isset($orderby)) $orderby="l.linkID";
	if (!isset($orderdir)) $orderdir=" ";		
	$tpl->assign("orderby",$orderby);
	$tpl->assign("orderdir",$orderdir);
	
	if (!isset($detailsDate)) $detailsDate=1;

	$detailsLevel = $detailsDate;
	
	$aggColumns = array("logCount", "cost");
	
	$allDateColumns = array(
		0=>array("name"=>"YEAR(lc.logDate)", "alias"=>"y", "title"=>"year", "select"=>"YEAR(lc.logDate) as y"),
		1=>array("name"=>"MONTH(lc.logDate)", "alias"=>"m", "title"=>"month", "select"=>"MONTH(lc.logDate) as m"),
		2=>array("name"=>"DAYOFMONTH(lc.logDate)", "alias"=>"d", "title"=>"day","select"=>"DAYOFMONTH(lc.logDate) as d")
	);

	$allColumns = $allDateColumns;
	
	// make columns
	$dateColumns = array();
	for ($i=0;$i<$detailsDate;$i++){
		$dateColumns[]=$allDateColumns[$i];
	}

	$columns = $dateColumns;
	
	$cur = array();
	foreach ($columns as $column){
		$cur[$column["alias"]]=0;
	}	
	
	
	$orderColumns = array_kv($dateColumns,"name");
	$orderby = implode(",",$orderColumns);
	if ($orderby!="") $orderby.=",";
	$orderby .=" logCount DESC";

	// from
	$from = "	FROM logclicks lc		";

	
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
		COUNT(lc.logCount) as logCount, SUM(lc.cost) as cost ".
		$from."
		WHERE 		
			affiliateID=$affiliateID AND ".$where."
		GROUP BY ".$groupStr."
		ORDER BY $orderby $orderdir
		");

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
	
	// add aggregate values
	$nStats=0;
	$stats = array();

	$nKeywords=0;
	
	foreach ($items as $item){
		$row = $item;
		
		$groupStr = "";
		
		// insert aggregate values for ...
		$changed=0;
		
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
					COUNT(lc.logCount) as logCount , SUM(lc.cost) as cost
					".$from."
					WHERE 		
						affiliateID=$affiliateID AND ".$w."
					GROUP BY ".$groupStr." 
					ORDER BY $orderby $orderdir
					");
					
				$stat = $dbSet->fetchArray();
				
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

  	$tpl->assign("affpercent",getOption("affiliatePercent"));
		       

    $tpl->display("template.affiliate.clickstats.php");

?>

