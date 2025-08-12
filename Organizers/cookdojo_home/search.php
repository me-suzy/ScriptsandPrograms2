<?php
	include ('useall.php');
	include ('include/header.php');
	echo "<table width=70% align=center class=h4><tr><td>\n";
	
	if (strlen($keywords) < 3)
	{
		echo "ERROR : Keywords minimum 3 character. <br><br> <a href='javascript:history.back()'>&lt;&lt; Back</a>";
	}
	else
	{
		if (!isset($limit)) 
			$limit = 0;
			
		$numberperpage = 10;
		
		echo "<b class=h2>Search result for keywords <i>$keywords</i> in <i>$search_in</i></b><br>";
		
		//Match in title
		if ($search_in == "All" || $search_in == "Title")
		{
			$q = "SELECT * FROM recipe WHERE recipe_title LIKE '%$keywords%'";
			$result = mysql_query($q);
			$alltotal = mysql_num_rows($result);
			
			
			$q = "SELECT * FROM recipe WHERE recipe_title LIKE '%$keywords%' LIMIT $limit, $numberperpage";
			$result = mysql_query($q);
			$currenttotal = mysql_num_rows ($result);
			
			if (mysql_num_rows($result))
				echo "<br><b>Result, ". ($limit + $currenttotal) ." from $alltotal recipes match in <i>Title</i> :</b><br>\n<ul>";
			
			while ($row = mysql_fetch_object($result))
			{
				$recipe_title = eregi_replace ($keywords, "<i>$keywords</i>", $row->recipe_title);
				echo "<li><a href='cat_detail.php?recID=$row->recipeID&catID=$row->catID'>$recipe_title</a><br>\n";
			}
			if (mysql_num_rows($result))
				echo "</ul>";
			
			if ($alltotal > $numberperpage)
			{
				echo "More results match in <i>Title</i> : ";
				show_number($numberperpage, $limit, $alltotal, $currenttotal, "search.php?keywords=". urlencode($keywords) ."&search_in=Title");
			}
		}
		
		//Match in ingredients
		if ($search_in == "All" || $search_in == "Ingredients")
		{
			$q = "SELECT * FROM recipe WHERE recipe_ingredients LIKE '%$keywords%'";
			$result = mysql_query($q);
			$alltotal = mysql_num_rows($result);
			
			$q = "SELECT * FROM recipe WHERE recipe_ingredients LIKE '%$keywords%' LIMIT $limit, $numberperpage";
			$result = mysql_query($q);
			$currenttotal = mysql_num_rows ($result);
			
			if (mysql_num_rows($result))
				echo "<br><b>Result, ". ($limit + $currenttotal) ." from $alltotal recipes match in <i>Ingredients</i> :</b><br>";
			
			while ($row = mysql_fetch_object($result))
			{
				echo "<ul><li><a href='cat_detail.php?recID=$row->recipeID&catID=$row->catID'>$row->recipe_title</a><br>\n";
				
				$pos_str = strpos(strtolower($row->recipe_ingredients), strtolower($keywords));
				$str = substr ($row->recipe_ingredients, $pos_str, strlen($keywords) + 100);
				echo "..." . eregi_replace ($keywords, "<i>$keywords</i>", $str) ."...</ul>";
				
			}
			
			if ($alltotal > $numberperpage)
			{
				echo "More results match in <i>Ingredients</i> : ";
				show_number($numberperpage, $limit, $alltotal, $currenttotal, "search.php?keywords=". urlencode($keywords) ."&search_in=Ingredients");
			}
			
		}
		
		//Match in method
		if ($search_in == "All" || $search_in == "Method")
		{
			$q = "SELECT * FROM recipe WHERE recipe_method LIKE '%$keywords%'";
			$result = mysql_query($q);
			$alltotal = mysql_num_rows($result);
			
			$q = "SELECT * FROM recipe WHERE recipe_method LIKE '%$keywords%' LIMIT $limit, $numberperpage";
			$result = mysql_query($q);
			$currenttotal = mysql_num_rows ($result);
			
			if (mysql_num_rows($result))
				echo "<br><b>Result, ". ($limit + $currenttotal) ." from $alltotal recipes match in <i>Method</i> :</b><br>";
			
			while ($row = mysql_fetch_object($result))
			{
				echo "<ul><li><a href='cat_detail.php?recID=$row->recipeID&catID=$row->catID'>$row->recipe_title</a><br>\n";
				
				$pos_str = strpos(strtolower($row->recipe_method), strtolower($keywords));
				$str = substr ($row->recipe_method, $pos_str, strlen($keywords) + 100);
				echo "..." . eregi_replace ($keywords, "<i>$keywords</i>", $str) ."...</ul>";
			}
			
			if ($alltotal > $numberperpage)
			{
				echo "More results match in <i>Method</i> : ";
				show_number($numberperpage, $limit, $alltotal, $currenttotal, "search.php?keywords=". urlencode($keywords) ."&search_in=Method");
			}
			
		}
		
		//Match in note
		if ($search_in == "All" || $search_in == "Note")
		{
			$q = "SELECT * FROM recipe WHERE recipe_note LIKE '%$keywords%'";
			$result = mysql_query($q);
			$alltotal = mysql_num_rows($result);
			
			$q = "SELECT * FROM recipe WHERE recipe_note LIKE '%$keywords%' LIMIT $limit, $numberperpage";
			$result = mysql_query($q);
			$currenttotal = mysql_num_rows ($result);
			
			if (mysql_num_rows($result))
				echo "<br><b>Result, ". ($limit + $currenttotal) ." from $alltotal recipes match in <i>Note</i> :</b><br>";
			
			while ($row = mysql_fetch_object($result))
			{
				echo "<ul><li><a href='cat_detail.php?recID=$row->recipeID&catID=$row->catID'>$row->recipe_title</a><br>\n";
				
				$pos_str = strpos(strtolower($row->recipe_note), strtolower($keywords));
				$str = substr ($row->recipe_note, $pos_str, strlen($keywords) + 100);
				echo "..." . eregi_replace ($keywords, "<i>$keywords</i>", $str) ."...</ul>";
			}
			
			if ($alltotal > $numberperpage)
			{
				echo "More results match in <i>Note</i> : ";
				show_number($numberperpage, $limit, $alltotal, $currenttotal, "search.php?keywords=". urlencode($keywords) ."&search_in=Note");
			}
		}
	}
	
	
	echo "</td></tr></table><br><br>\n";
	include ('include/footer.php');
?>