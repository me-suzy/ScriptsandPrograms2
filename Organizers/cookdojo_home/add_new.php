<?php
	include ('useall.php');
	include ('include/header.php');

	if (!isset($catID))
		$catID = "";
		
	echo "<table width=50% align=center class=h4>\n";
	
	$cat_title = "";
	$q = "SELECT cat_title FROM catrecipe WHERE catID = '$catID'";
	mysql_first_data ($q, "cat_title");
	
	echo "<tr><td colspan=3 class=h3><b><a href='index.php'>Recipe Categories</a> &gt;&gt; Add New Recipe</b><br>&nbsp;</td></tr>\n";
	
	
	echo "<form method=post action='cat_view.php'>\n";
	echo "<input type=hidden name=act value=save>\n";
	
	$select_category = "<select name=catID class=textfield>\n";
	$q = "SELECT * FROM catrecipe ORDER BY cat_title ASC";
	$result = mysql_query($q);
	while ($row = mysql_fetch_object($result))
	{
		if ($catID == $row->catID)
			$SELECTED = "SELECTED";
		else
			$SELECTED = "";
		
		$select_category .= "<option value='$row->catID' $SELECTED>$row->cat_title</option>\n";
	}
	$select_category .= "</select>\n";
	echo "<tr><td>Category </td><td>:</td><td>$select_category</td></tr>\n";
	echo "<tr><td>Title </td><td>:</td><td><input type=text name=title class=textfield></td></tr>\n";
	echo "<tr valign=top><td>Ingredients </td><td>:</td><td><textarea name=ingredients cols=40 rows=6></textarea></td></tr>\n";
	echo "<tr valign=top><td>Method </td><td>:</td><td><textarea name=method cols=40 rows=6></textarea></td></tr>\n";
	echo "<tr valign=top><td>Note </td><td>:</td><td><textarea name=note cols=40 rows=6></textarea></td></tr>\n";
	echo "<tr valign=top><td> </td><td></td><td><input type=submit value=Save class=button></td></tr>\n";
	echo "</table>";
	echo "</form>\n";
	echo "<form method=post>\n";
	echo "<table width=100% align=center class=h4 bgcolor=#F2F2F2>\n";
	echo "<tr><td height=30 align=center><a href='javascript:history.back();'>&lt;&lt; Back</a></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	include ('include/footer.php');
?>