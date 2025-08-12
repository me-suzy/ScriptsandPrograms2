
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<table>
<tr>
	<td>Keywords:</td>
	<td><input type="text" name="keyword" value="<?php echo $_REQUEST['keyword']; ?>"></td>
</tr>
<tr>
	<td>Category: </td>
	<td>
	<?php

	$sql ="select id, name from snipe_gallery_cat where cat_parent='0' ";	
	$sql .=" order by name asc";
	$get_options = mysql_query($sql);    
	$num_options = mysql_num_rows($get_options);   
	echo '<select name="search_cat">';
	echo '<option value="">All Galleries</option>';

	// our category is apparently valid, so go ahead...        
	if ($num_options > 0) {  		
		while (list($cat_id, $cat_name) = mysql_fetch_row($get_options)) {
			$sql ="select id, name from snipe_gallery_cat where cat_parent='".$cat_id."' ";	
			$sql .=" order by name asc";
			$get_suboptions = mysql_query($sql);  
			while (list($subcat_id, $subcat_name) = mysql_fetch_row($get_suboptions)) {
				echo "<option value=\"".$subcat_id."\"";
				if ($_REQUEST['search_cat']==$subcat_id) {
					echo " selected=\"selected\"";
				}

				echo ">".stripslashes($cat_name).":: ".stripslashes($subcat_name)."</option>\n";
			}

		}		
	}
	?>
	
	</td>
</tr>
<tr>
	<td colspan="2"><input type="radio" name="search_type" value="and"<?php if (($_REQUEST['search_type']=="and") || (empty($_REQUEST['search_type']))) { echo ' checked="checked"'; } ?>>Find all words</td>
</tr>
<tr>
	<td colspan="2"><input type="radio" name="search_type" value="or"<?php if ($_REQUEST['search_type']=="or") { echo ' checked="checked"'; } ?>>Find any words</td>
</tr>
<tr>
	<td colspan="2"><input type="checkbox" name="txtonly" value="1"<?php if ($_REQUEST['txtonly']==1) { echo ' checked="checked"'; } ?>>Show text only results</td>
</tr>
<tr>
	<td colspan="2"><input type="submit" value="Search"></td>
</tr>
</table>
</form>