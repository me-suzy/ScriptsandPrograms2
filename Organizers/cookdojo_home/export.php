<?php
	include ('useall.php');
	include ('include/header.php');
	
	echo "<table width=70% align=center class=h4>\n";
	echo "<tr><td colspan=3 class=h2><br><b>Export Recipes to E-book (format PDF)</b><br>&nbsp;</td></tr>\n";
	echo "<form method=post action='export_pdf.php' target=_blank>\n";
	echo "<tr><td width=25%>Title</td><td>:</td><td width=75%><input type=text name=ebook_title class=textfield></td></tr>\n";
	echo "<tr><td>Compiled by</td><td>:</td><td><input type=text name=ebook_by class=textfield></td></tr>\n";
	echo "<tr><td valign=top>Recipe Categories</td><td valign=top>:</td><td>\n";
	
	$q = "SELECT * FROM catrecipe ORDER by cat_title ASC";
	$result = mysql_query($q);
	while ($row = mysql_fetch_object($result))
	{
		echo "<input type=checkbox value='$row->catID' name='ebook_category[]'>$row->cat_title<br>\n";	
	}	
	echo "<tr><td></td><td></td><td><br><input type=submit value='Make E-book' class=button><br><br>&nbsp;</td></tr>\n";
	echo "</td></tr>\n";
	echo "</form>";
	echo "</table>";
	
	
	include ('include/footer.php');
?>