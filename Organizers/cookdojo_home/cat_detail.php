<?php
	include ('useall.php');
	include ('include/header.php');

	if (!isset($catID))
		$catID = "";
	
	if (!isset($recID))
		$recID = "";
	
	if (!isset($act))
		$act = "";
	
	if ($act == "save" && !empty($recID))
	{
		$title = htmlspecialchars ($title);
		$ingredients = htmlspecialchars ($ingredients);
		$method = htmlspecialchars ($method);
		$note = htmlspecialchars ($note);
		$q = "UPDATE recipe SET catID = '$catID', recipe_title = '$title', recipe_ingredients = '$ingredients', recipe_method = '$method', recipe_note = '$note' WHERE recipeID = '$recID'";
		mysql_query ($q);	
	}
	
	echo "<table width=50% align=center class=h4>\n";
	
	$cat_title = "";
	$q = "SELECT cat_title FROM catrecipe WHERE catID = '$catID'";
	mysql_first_data ($q, "cat_title");
	
	echo "<tr><td colspan=2 class=h3><b><a href='index.php'>Recipe Categories</a> &gt;&gt; <a href='cat_view.php?catID=$catID'>$cat_title</a></b></td></tr>\n";
	
	$recipe_title = "";
	$recipe_ingredients  = "";
	$recipe_method = "";
	$recipe_note = "";
	$q = "SELECT * FROM recipe WHERE catID = '$catID' AND recipeID = '$recID'";
	mysql_first_data ($q, "recipe_title|recipe_ingredients|recipe_method|recipe_note");
	
	echo "<tr><td colspan=2 class=h2><br><b>$recipe_title</b></td></tr>\n";
	echo "<tr><td colspan=2 class=h4_brown><br><b>INGREDIENTS</b></td></tr>\n";
	echo "<tr><td colspan=2 class=h4>". nl2br($recipe_ingredients) ."</td></tr>\n";
	echo "<tr><td colspan=2 class=h4_brown><br><b>METHOD</b></td></tr>\n";
	echo "<tr><td colspan=2 class=h4>". nl2br($recipe_method) ."</td></tr>\n";
	
	if (!empty($recipe_note))
	{
		echo "<tr><td colspan=2 class=h4_brown><br><b>NOTE</b></td></tr>\n";
		echo "<tr><td colspan=2 class=h4>". nl2br($recipe_note) ."</td></tr>\n";
	}
	
	
	$txt = "<script  type='text/javascript' language='javascript'>\n";
	$txt .=  "function pop(url, nama)\n {";
	
	$txt .= "window.open(url, nama, 'toolbars=no,scrollbars=yes,location=no,directories=no,status=no,menubar=yes,top=10,left=10,width=560,height=450');";
	

	$txt .= "}\n</script>\n";
	
	$txt .= "<a href=\"make_indeks.php?recID=$recID&size=4x6\" target=_blank><img border=0 src='images/4x6.gif' align=center alt='Make US Index Card 4x6'></a>&nbsp;\n";
	$txt .= "<a href=\"make_indeks.php?recID=$recID&size=5x8\" target=_blank><img border=0 src='images/5x8.gif' align=center alt='Make US Index Card 5x8'></a>&nbsp;\n";
	$txt .= "<a href=\"javascript:pop('printer_friendly.php?recID=$recID', 'printer');\"><img border=0 src='images/print.jpg' align=center alt='Printer Friendly'></a>&nbsp;\n";
	$txt .= "<a href='rec_edit.php?catID=$catID&recID=$recID'><img border=0 src='images/edit.jpg' align=center alt='Edit this recipe'></a>&nbsp;\n";
	$txt .= "<a href='cat_view.php?act=del&catID=$catID&recID=$recID' onclick=\"return confirmLink('Delete this recipe ?')\"><img border=0 src='images/delete.jpg' align=center alt='Delete this recipe'></a>&nbsp;\n";

	echo "<tr><td colspan=2><br>$txt</td></tr>\n";
	echo "</table>\n";
	echo "<form method=post>\n";
	echo "<table width=100% align=center class=h4 bgcolor=#F2F2F2>\n";
	echo "<tr><td height=30 align=center><a href='add_new.php?catID=$catID'>Add new recipe</a></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	include ('include/footer.php');
?>