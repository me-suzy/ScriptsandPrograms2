<?php
	/*
		This is the only search system that does not use a get results page, it links directly do the view ticket page;	
		
		One specical note here, all queries for this section are stored as contants located in a seperate file.
		In this way we can send query numbers rather then whole queries and this keep to data hiding principles
	*/
	include_once './includes/cat_queries.php';
?>
<table cellpadding="0" cellspacing="0" border="1" width="100%">
	<tr><td colspan="3">
		<hr/>
	</td></tr>
	<tr>
		<td valign="top" style="padding-left:10px" width="33%">
			<img src="./images/arrow<?php echo isset($_GET['stype']) && $_GET['stype'] == 'cate' ? 2 : 1; ?>.png" border="0" class="arrow" />&nbsp;<a href="?type=cat&stype=cate" class="link">Browse by Category</a><br/>
			<?php
				if (isset($_GET['stype']) && $_GET['stype'] == 'cate') {
					echo '<div class="innerbox">';
					$s = mysql_query(QUERY_1) or die(mysql_error());
					
					while ($r = mysql_fetch_assoc($s))
					{
						$cat = new Category($r['category']);
						echo '<img src="./images/arrow' . (isset($_GET['item']) && $_GET['item'] == $cat->get('id', 'intval') && $_GET['q'] == 1 ? 2 : 1) . '.png" border="0" class="arrow" />&nbsp;<a href="?type=cat&stype=cate&q=1&item=' . $cat->get('id', 'intval') . '" class="link">' . ($cat->get('name', 'stripslashes') == '' ? 'Unknown' : $cat->get('name', 'stripslashes')) . '(' . $r['c'] . ')</a><br/>' . chr(10);	
						if (isset($_GET['item']) && $_GET['item'] == $cat->get('id', 'intval')) include_once './includes/cat_cate_show.php';
					}
						
					echo '</div>' . chr(10);
				}
			?>
		</td>
		<td valign="top" style="padding-left:10px" width="34%">
			<img src="./images/arrow<?php echo isset($_GET['stype']) && $_GET['stype'] == 'pri' ? 2 : 1; ?>.png" border="0" class="arrow" />&nbsp;<a href="?type=cat&stype=pri"  class="link">Browse by Priority</a><br/>
			<?php
				if (isset($_GET['stype']) && $_GET['stype'] == 'pri') {
					echo '<div class="innerbox">' . chr(10);
					$s = mysql_query(QUERY_2) or die(mysql_error());
					
					while ($r = mysql_fetch_assoc($s))
					{
						$priority = new Priority($r['priority']);
						echo '<img src="./images/arrow' . (isset($_GET['item']) && $_GET['item'] == $priority->get('name', 'stripslashes') && $_GET['q'] == 2 ? 2 : 1) . '.png" border="0" class="arrow" />&nbsp;<a href="?type=cat&stype=pri&q=2&item=' . $priority->get('pid', 'intval') . '" class="link">' . ($priority->get('name', 'stripslashes') == '' ? 'Unknown' : $priority->get('name', 'stripslashes')) . '(' . $r['c'] . ')</a><br/>' . chr(10);
						if (isset($_GET['item']) && $_GET['item'] == $r['priority']) include_once './includes/cat_cate_show.php';
					}
					
					echo '</div>' . chr(10);
				}
			?>
		</td>
		<td valign="top" style="padding-left:10px" width="33%">
			<img src="./images/arrow<?php echo isset($_GET['stype']) && $_GET['stype'] == 'stat' ? 2 : 1; ?>.png" border="0" class="arrow" />&nbsp;<a href="?type=cat&stype=stat" class="link">Browse by Status</a><br/>
			<?php
				if (isset($_GET['stype']) && $_GET['stype'] == 'stat') {
					echo '<div class="innerbox">' . chr(10);
					$s = mysql_query(QUERY_3) or die(mysql_error());
					
					while ($r = mysql_fetch_assoc($s))
					{
						$stat = new Status($r['status']);
						echo '<img src="./images/arrow' . (isset($_GET['item']) && $_GET['item'] == $stat->get('name', 'stripslashes') && $_GET['q'] == 3 ? 2 : 1) . '.png" border="0" class="arrow" />&nbsp;<a href="?type=cat&stype=stat&q=3&item=' . $stat->get('id', 'intval') . '" class="link">' . ($stat->get('name', 'stripslashes') == '' ? 'Unknown' : $stat->get('name', 'stripslashes')) . '(' . $r['c'] . ')</a><br/>' . chr(10);
						if (isset($_GET['item']) && $_GET['item'] == $r['status']) include_once './includes/cat_cate_show.php';
					}
						
					echo '</div>' . chr(10);
				}
			?>
		</td>
	</tr>
</table>