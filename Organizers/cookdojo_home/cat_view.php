<?php
	include ('useall.php');
	include ('include/header.php');

	if (!isset($catID))
		$catID = "";
	
	if (!isset($act))
		$act = "";
	
	if (!isset($recID))
		$recID = "";
		
	if ($act == "save" && !empty($catID))
	{
		$title = htmlspecialchars ($title);
		$ingredients = htmlspecialchars ($ingredients);
		$method = htmlspecialchars ($method);
		$note = htmlspecialchars ($note);
		$q = "INSERT INTO `recipe` (`recipeID`, `recipe_title`, `recipe_ingredients`, `recipe_method`, `recipe_note`, `catID`) VALUES ('', '$title', '$ingredients', '$method', '$note', '$catID')";
		mysql_query ($q);	
	}
	else if ($act == "del" && !empty($recID))
	{
		$q = "DELETE FROM recipe WHERE catID = '$catID' AND recipeID = '$recID'";
		mysql_query ($q);	
	}
	
	echo "<table width=50% align=center class=h4>\n";
	
	$cat_title = "";
	$q = "SELECT cat_title FROM catrecipe WHERE catID = '$catID'";
	mysql_first_data ($q, "cat_title");
	
	echo "<tr><td colspan=2 class=h3><b><a href='index.php'>Recipe Categories</a> &gt;&gt; $cat_title</b></td></tr>\n";
	
	if (!isset($limit)) 
		$limit = 0;

	$alltotal = 0;
	$q = "SELECT COUNT(*) AS alltotal FROM recipe WHERE catID = '$catID'";
	mysql_first_data ($q, "alltotal");

	$numberperpage = 20;
	$q = "SELECT * FROM recipe WHERE catID = '$catID' ORDER BY recipeID DESC LIMIT $limit, $numberperpage";
	$result = mysql_query ($q);
	$currenttotal = mysql_num_rows ($result);
		
	echo "<tr><td colspan=2 class=h5>". ($limit + $currenttotal) ." from $alltotal total data<br>&nbsp;</td></tr>\n";
	

	while ($row = mysql_fetch_object($result))
	{

		echo "<tr><td height=20>- <a href='cat_detail.php?recID=$row->recipeID&catID=$catID'>$row->recipe_title</a></a></td></tr>\n";
		
	}
	
	echo "<tr><td colspan=2><br>";
	show_number($numberperpage, $limit, $alltotal, $currenttotal, "cat_view.php?catID=$catID");
	echo "</td></tr>\n";
	
	echo "</table>\n";
	echo "<form method=post>\n";
	echo "<table width=100% align=center class=h4 bgcolor=#F2F2F2>\n";
	echo "<tr><td height=30 align=center><a href='add_new.php?catID=$catID'>Add new recipe</a></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	include ('include/footer.php');
?>