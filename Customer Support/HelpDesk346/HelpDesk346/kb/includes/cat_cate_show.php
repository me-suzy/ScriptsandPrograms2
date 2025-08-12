<div style="padding-left:30px; font-family:Arial; font-size:12px; color:blue; width:300px">
<?php
	switch ($_GET['q'])
	{
		case 1:
			$set = mysql_query(QUERY_4) or die(mysql_error());
			break;
		case 2:
			$set = mysql_query(QUERY_5) or die(mysql_error());
			break;
		case 3:
			$set = mysql_query(QUERY_6) or die(mysql_error());
			break;
		default:
			echo "Bad Query Number Specified<br/>\n";
			exit;
	}
	
	while ($row = mysql_fetch_assoc($set))
	{
		$t = new Ticket($row['id']);
		echo '<a href=' . ( (isset($_SESSION['enduser']) && $_SESSION['userLevel'] > ENDUSER_SECURITY_LEVEL) ? '../viewDetails.php' : 'viewTicket.php') . '?id=' . $t->get('id', 'intval') . ' class="_link">' . substr($t->get('descrip', 'stripslashes'), 0, 150) . '...</a>';
		echo '<hr align="left" width="25%" />' . chr(10);
	}
	
	if (!isset($_GET['limit'])) {
		echo '<a href="?type=cat&stype=' . $_GET['stype'] . '&q=' . intval($_GET['q']) . '&item=' . $_GET['item'] . '&limit=0" class="_link">Show All</a>' . chr(10);
	}
?>
</div>