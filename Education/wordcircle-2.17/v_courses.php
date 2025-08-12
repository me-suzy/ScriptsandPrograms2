<?php



//start public courses view
	$result = $GLOBALS['db']->execQuery("select groups.group_id,code, group_name from groups where public = 1 order by group_name");
	$i = 0;
	$cour = array();
	while ($row = mysql_fetch_assoc($result)) { 
	   $cour[$i]['Name'] = $row['group_name'];
	   $cour[$i]['key'] = "<a href='index.php?key=".$row["code"]."'>".$row["code"]."</a>";
	   $i++;
   }
   
   $GLOBALS['page']->head("wordcircle","","Only some courses are public - if a course is private it will not appear in this list",0);
	
	echo("<a href='index.php'>Go back to adding someone else's course</a>
	<br>
	<a href='index.php'>Go back to my course list</a><br>
	<br>
	");
	
	$GLOBALS['page']->tableStart("","100%","TAB","Public Course List","index.php");
	if (count($cour)>0){
	;
	echo("Click on a course key to add it to your course list<br><br>
	
	<strong>Note:</strong> Private courses are not in this list - you must get private course keys<br>
 from the person who created the course<br>
 <br>
 ");
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($cour,"odd","even","60%");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There are no public courses at this time");
	$GLOBALS['page']->tableEnd("TEXT");
	}
	$GLOBALS['page']->tableEnd("TAB");
	echo("<br>");	
?>