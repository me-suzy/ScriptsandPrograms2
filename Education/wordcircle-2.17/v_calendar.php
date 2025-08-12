<?php



//start calendar

	$linkArray[0] = "index.php?a=edits&gid=".$_GET['gid'];
	
	
	
   	$GLOBALS['page']->tableStart("","100%","TAB","<a class='tabanchor'  href='index.php?a=calendar&gid=".$_GET['gid']."'>Calendar</a>",$linkArray);

	
	$cal = new calendar();
	
	$cal->show();
	
if($_GET['a'] == 'view'){	
echo("<br>
	<table width='250' align='center'><tr><td>To view calendar items, place your mouse over colored dates<br><br>
	
	 <img src='icon_cal.gif' width='14' height='15'><a href='index.php?a=calendar&gid=".$_GET['gid']."'> or click here for full news list</a></td></tr></table><br>
");
}

if($_GET['a'] != 'view'){
	
	if(!isset($_REQUEST['date'])){ 
   $date = mktime(0,0,0,date('m'), date('d'), date('Y')); 
} else { 
   $date = $_REQUEST['date']; 
} 

$day = date('d', $date); 
$month = date('m', $date); 
$year = date('Y', $date); 

	$doNotGetList = "0";
	
	$result2 = $GLOBALS['db']->execQuery("select calendar_id,lcase(date_format(dateOf,'%m.%d.%y %h:%i%p')) as dateOf,calendar from calendar where group_id = ".$_GET['gid']." and dateOf like '".$year."-".$month."-".$day."%' and calendar_id not in (".$doNotGetList.") order by dateOf desc");
	$i = 0;
	$syll2 = array();
	while ($row = mysql_fetch_assoc($result2)) { 
		$doNotGetList .= "," . $row['calendar_id'];
	   $syll2[$i]['selected date\'s events'] = '<img src="icon_cal.gif" width="15" height="16" align="top">'.$row['dateOf'];
	   $syll2[$i]['details'] = $row["calendar"];
	   $i++;
   }	
	
	
	$result1 = $GLOBALS['db']->execQuery("select calendar_id,lcase(date_format(dateOf,'%m.%d.%y %h:%i%p')) as dateOf,calendar from calendar where group_id = ".$_GET['gid']." and dateOf like '".$year."-".$month."%' and calendar_id not in (".$doNotGetList.") order by dateOf desc");
	$i = 0;
	$syll1 = array();
	while ($row = mysql_fetch_assoc($result1)) { 
		$doNotGetList .= "," . $row['calendar_id'];
	   $syll1[$i]['selected month\'s events'] = '<img src="icon_cal.gif" width="15" height="16" align="top">'.$row['dateOf'];
	   $syll1[$i]['details'] = $row["calendar"];
	   $i++;
   }
   




	$result3 = $GLOBALS['db']->execQuery("select calendar_id,lcase(date_format(dateOf,'%m.%d.%y %h:%i%p')) as dateOf,calendar from calendar where group_id = ".$_GET['gid']." and calendar_id not in (".$doNotGetList.") order by dateOf desc");
	$i = 0;
	$syll3 = array();
	while ($row = mysql_fetch_assoc($result3)) { 
		$doNotGetList .= "," . $row['calendar_id'];
	   $syll3[$i]['other events'] = '<img src="icon_cal.gif" width="15" height="16" align="top">'.$row['dateOf'];
	   $syll3[$i]['details'] = $row["calendar"];
	   $i++;
   }
	
	
	if (count($syll2)>0){
	
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($syll2,"odd","even","25%",$GLOBALS['db']->numberOfRows,"calendar");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There is no news for the selected date");
	$GLOBALS['page']->tableEnd("TEXT");
	}
	
	echo("<br>");
	
		if (count($syll1)>0){

	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($syll1,"odd","even","25%",$GLOBALS['db']->numberOfRows,"calendar");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There is no other news for the selected month");
	$GLOBALS['page']->tableEnd("TEXT");
	}
	
	echo("<br>");
	
		if (count($syll3)>0){
	;	
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($syll3,"odd","even","25%",$GLOBALS['db']->numberOfRows,"calendar");
	$GLOBALS['page']->tableEnd("GRID");
	}
	}
	
	$GLOBALS['page']->tableEnd("TAB");
	echo("<br>");
	
?>