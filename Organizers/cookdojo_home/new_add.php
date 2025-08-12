<?php
	include ('useall.php');
	include ('include/header.php');

	if (!isset($catID))
		$catID = "";
	
	echo "<table width=50% align=center class=h4>\n";
	
	$q = "SELECT cat_title FROM catrecipe WHERE catID = '$catID'";
	mysql_first_data ($q, "cat_title");
	
	echo "<tr><td colspan=2 class=h3><b>Category : $cat_title</b></td></tr>\n";
	
	$q = "SELECT recipe_title FROM recipe WHERE catID = '$catID'";
	$result = mysql_query($q);
	while ($row = mysql_fetch_object($result))
	{

		echo "<tr><td height=25>$row->recipe_title</a></td></tr>\n";
		
	}
	echo "</table>\n";
	echo "<form method=post>\n";
	echo "<table width=100% align=center class=h4 bgcolor=#F2F2F2>\n";
	echo "<tr><td height=30 align=center><a href='add_new.php?catID=$catID'>Add new recipe</a></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	include ('include/footer.php');
?>