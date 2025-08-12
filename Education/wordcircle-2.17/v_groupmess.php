<?php

$result = $GLOBALS['db']->execQuery("select group_message from group_message where group_id = ".$_GET['gid']);
	$group_message = "no overview saved yet";
	while ($row = mysql_fetch_assoc($result)) { 
	   $group_message = html_entity_decode($row['group_message']);
   }
	$linkArray[0] = "index.php?a=editgm&gid=".$_GET['gid'];
	$GLOBALS['page']->tableStart("","99%","TAB","Overview ",$linkArray);
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo($group_message);
	$GLOBALS['page']->tableEnd("TEXT");
	$GLOBALS['page']->tableEnd("TAB");
	echo("<br>");
	
?>

