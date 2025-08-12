<?php



$result = $GLOBALS['db']->execQuery("select thoughts_id,thoughts,date_format(created_on,'%m.%d.%y') as created_on from thoughts where group_id = ".$_GET['gid']." order by created_on desc",true);
	$i = 0;
	$thoughts = array();
	while ($row = mysql_fetch_assoc($result)) { 
	   $thoughts[$i]['news item'] = '<img src="icon_news.gif" alt="" width="10" height="11" hspace="1" border="0" align="top">'.(($_GET['a']=='view')?"<a href='index.php?a=thoughts&gid=".$_GET['gid']."'>".substr(html_entity_decode($row['thoughts']),0,30)."..."."</a>":html_entity_decode($row['thoughts']));
	   $thoughts[$i]['date posted'] = $row["created_on"];
	   $i++;
   }

	$linkArray[0] = "index.php?a=editn&gid=".$_GET['gid'];
   	$GLOBALS['page']->tableStart("","100%","TAB","<a class='tabanchor'  href='index.php?a=thoughts&gid=".$_GET['gid']."'>News</a>",$linkArray);
	if(count($thoughts)>0){
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($thoughts,"odd","even","70%",$GLOBALS['db']->numberOfRows,"thoughts");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There is no news at this time<br>");
	$GLOBALS['page']->tableEnd("TEXT");
	}
	$GLOBALS['page']->tableEnd("TAB");
	echo("<br>");
	
?>