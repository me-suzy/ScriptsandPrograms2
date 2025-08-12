<?php
	include ('useall.php');
	include ('include/header.php');


	if (!isset($act))
		$act = "";
	else if (isset($category) && $act == "save")
	{
		$category = htmlentities($category);
		$q = "INSERT INTO `catrecipe` (`catID`, `cat_title`) VALUES ('', '$category')"; 
		mysql_query($q);
	}
	else if (isset($catID) && $act == "del")
	{
		$q = "DELETE FROM `catrecipe` WHERE catID='$catID'";
		mysql_query($q);
	
	}
	else if (isset($catID) && $act == "ren" && isset($category))
	{
		$category = htmlentities($category);
		$q = "UPDATE catrecipe SET cat_title = '$category' WHERE catID = '$catID'";
		mysql_query($q);
	}
	
	
	$q = "SELECT * FROM catrecipe ORDER BY cat_title ASC";
	$result = mysql_query($q);
	
	echo "<table width=70% align=center class=h4>\n";
	echo "<tr><td colspan=3 class=h3 align=center><img src='images/recipe_categories.jpg' alt='Recipe Categories'></b><br><br>&nbsp;</td></tr>\n";
	
	$i = 0;
	while ($row = mysql_fetch_object($result))
	{
		$q2 = "SELECT COUNT(*) AS cat_count FROM recipe WHERE catID = '$row->catID'";
		mysql_first_data ($q2, "cat_count");
		
		$txt = "<a href=\"index.php?add=2&catID=$row->catID\"><img border=0 src=\"images/edit.jpg\" align=center alt=\"Rename category $row->cat_title\"></a>&nbsp;";
		$txt .= "<a href=\"index.php?act=del&catID=$row->catID\" onclick=\"return confirmLink('Delete category ". addslashes($row->cat_title) ." ?')\"><img border=0 src=\"images/delete.jpg\" align=center alt=\"Delete category $row->cat_title \"></a>&nbsp;";
		$txt .= "<a href=\"cat_view.php?catID=$row->catID\">$row->cat_title ($cat_count)</a>";
		
		if ($i == 0)
		{
			echo "<tr height=35><td>$txt</td>";
			$i = 1;
		}
		else if ($i == 1)
		{
			echo "<td>$txt</td>\n";
			$i = 2;
		}
		else if ($i == 2)
		{
			echo "<td>$txt</td></tr>\n";
			$i = 0;
		}
	}
	
	if ($i == 0)
		echo "<td colspan=2></td></tr>\n";
	else if ($i == 1)
		echo "<td></td></tr>\n";
	
	echo "</table><br>\n";

	if (!isset($add))
	{
		echo "<form method=post>\n";
		echo "<table width=100% align=center class=h4 bgcolor=#F2F2F2>\n";
		echo "<tr><td height=30 align=center><a href=\"index.php?add=1\">Add new recipe category</a></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
	}
	else if ($add == 1)
	{
		echo "<form method=post>\n";
		echo "<input type=hidden name=act value=save>";
		echo "<table width=100% align=center class=h4 bgcolor=#F2F2F2>\n";
		echo "<tr><td height=30 align=center>Add category : <input type=text name=category class=textfield> <input type=submit class=button value=Add></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
	}
	else if ($add == 2 && isset($catID))
	{
		echo "<form method=post>\n";
		$q = "SELECT cat_title FROM catrecipe WHERE catID=\"$catID\"";
		mysql_first_data ($q, "cat_title");		
		
		echo "<input type=hidden name=act value=ren>";
		echo "<input type=hidden name=catID value=\"$catID\">";
		echo "<table width=100% align=center class=h4 bgcolor=#F2F2F2>\n";
		echo "<tr><td height=30 align=center>Rename <i>$cat_title</i> to : <input type=text name=category class=textfield value=\"$cat_title\"> <input type=submit class=button value=Rename></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
	}
	else if ($add == 3)
	{
		echo "<form method=post action=search.php>\n";
		echo "<input type=hidden name=act value=save>";
		echo "<table width=100% align=center class=h4 bgcolor=#F2F2F2>\n";
		echo "<tr><td height=30 align=center>Search recipe : <input type=text name=keywords class=textfield> in \n";
		echo "<select name='search_in' class=textfield><option>All</option><option>Title</option><option>Ingredients</option><option>Method</option><option>Note</option></select> <input type=submit class=button value=Search></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
	}
	
	
	include ('include/footer.php');
?>