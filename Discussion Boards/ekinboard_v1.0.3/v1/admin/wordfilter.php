<?PHP
if($_userlevel == 3){
echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/wordfilter_title.gif\" border=0 alt=\"\"><p>";
echo "<div align=right><a href=index.php?page=wordfilter&d=new><img src=images/new_wordfilter.gif border=0></a></div>";

if($_GET[d] == "new"){
	if($_POST['submit']){
			$result=MYSQL_QUERY("INSERT INTO `wordfilters` (`word`, `replacement`)".
			"VALUES ('$_POST[word]', '$_POST[replacement]')"); 

		header("Location: index.php?page=wordfilter");
	} else {
					echo "
					<table width=100% class=category_table cellpadding=0 cellspacing=0>
						<tr>
							<td class=table_1_header>			
								<b>Add a new Word Filter</b>			
							</td>
						</tr>
						<tr>
							<td align=center><form action=index.php?page=wordfilter&d=new method=post>
									<input type=hidden name=id value='$id'>

							<table border=0 cellpadding=2 cellspacing=2>
								<tr>
									<td width=35% >
										<b>Word:</b>
									</td>
									<td>
										<input type='text' name='word' value=\"$row[word]\" size='50' class='textbox'>
									</td>
								</tr>
								<tr>
									<td width=35%>
										<b>Replacement:</b>
									</td>
									<td>
										<input type='text' name='replacement' size='50' value=\"$row[replacement]\" class='textbox'>
									</td>
								</tr>

								</table></td></tr>
								<tr>
									<td colspan=2 align=center class=table_bottom>
										<input type=submit name=submit value=\"Add > >\">
									</td>
								</tr>
					</table>";
	}
} else if(($_GET[d] == "delete") && (!empty($_GET[id]))){
	$get_ad_info = mysql_query("SELECT * FROM wordfilters WHERE id='$_GET[id]'");
	$row = mysql_fetch_array($get_ad_info);
	if($_GET['sure'] == "yes"){
		$delete_category = mysql_query("DELETE FROM wordfilters WHERE id='$_GET[id]'");

		header("Location: index.php?page=wordfilter");
	} else {
		echo "				<table width=100% cellspacing=0 cellpadding=0 class=category_table>
								<tr>
									<td colspan=4>
										<table width=100% cellpadding=0 cellspacing=0 border=0>
											<tr>
												<td width=45>
													<img src=\"images/EKINboard_header_left.gif\"></td>
												<td width=100% class=table_1_header>
													<img src=\"images/arrow_up.gif\"> <b>Delete</b></td>
												<td align=right class=table_1_header>
													<img src=\"images/EKINboard_header_right.gif\"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr class=table_subheader>
									<td align=left class=table_subheader>	
										Are you sure you would like to delete this word filter?
									</td>
								</tr>
								<tr>
									<td class=contentmain align=center>
										<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
											<tr>
												<td class=\"redtable\" align=center width=100>
													<a href=\"index.php?page=wordfilter&d=delete&id=$_GET[id]&sure=yes\" class=link2>Yes</a>
												</td>
												<td width=20></td>
												<td class=\"bluetable\" align=center width=100>
													<a href=\"javascript:history.go(-1)\" class=link2>No</a>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>";
}
} else if(($_GET[d] == "edit") && (!empty($_GET[id]))){
	if($_POST['submit']){
		$update_ad = mysql_query("UPDATE wordfilters SET word='$_POST[word]', replacement='$_POST[replacement]' WHERE id='$_GET[id]'");

		header("Location: index.php?page=wordfilter");
	} else {
	$get_ad_info = mysql_query("SELECT * FROM wordfilters WHERE id='$_GET[id]'");
	$row = mysql_fetch_array($get_ad_info);
					echo "
					<table width=100% class=category_table cellpadding=0 cellspacing=0>
						<tr>
							<td class=table_1_header>			
								<b>Manage Current Word Filter</b>			
							</td>
						</tr>
						<tr>
							<td align=center><form action=\"index.php?page=wordfilter&d=edit&id=$_GET[id]\" method=\"post\">
									<input type=hidden name=id value='$id'>

							<table border=0 cellpadding=2 cellspacing=2>
								<tr>
									<td width=35% valign=top>
										<b>Word:</b>
									</td>
									<td>
										<input type='text' name='word' value=\"$row[word]\" size='50' class='textbox'>
									</td>
								</tr>
								<tr>
									<td width=35% valign=top>
										<b>Replacement:</b>
									</td>
									<td>
										<input type='text' name='replacement' size='50' value=\"$row[replacement]\" class='textbox'>
									</td>
								</tr>
								</table></td></tr>
								<tr>
									<td colspan=2 align=center class=table_bottom>
										<input type=submit name=submit value=\"Save > >\">
									</td>
								</tr>
					</table>";
	}
} else {
					$ad_sql = mysql_query("SELECT * FROM wordfilters ORDER BY word DESC");
					$wf_count = mysql_num_rows($ad_sql);

					echo "<table width=100% class=category_table cellpadding=0 cellspacing=0>
							<tr>
								<td class=table_1_header colspan=5>			
									<b>Manage Word Filters</b>			
								</td>
							</tr>
							<tr class=table_subheader>
								<td width=50 align=left class=table_subheader></td>
								<td align=left class=table_subheader>Word</td>
								<td align=left class=table_subheader>Replacement</td>
								<td width=40 align=left class=table_subheader></td>
								<td width=40 align=left class=table_subheader></td>
							</tr>";
					while($row = mysql_fetch_array($ad_sql)){
						$wf_id = $row['id'];
						$wf_word = $row['word'];
						$wf_replacement = $row['replacement'];

						if(!($even_number_count % 2) == TRUE){
							$even_number = 1;
						} else {
							$even_number = 2;
						}
					
						$even_number_count = $even_number_count + 1;

						echo "<tr>
								<td align=center><img src=\"images/wordfilter_sm_img.gif\" border=0></td>
								<td class=\"row$even_number\">
									$wf_word
								</td>
								<td class=\"row$even_number\">
									$wf_replacement
								</td>
								<td class='row2' align=center><a href=\"index.php?page=wordfilter&d=edit&id=$row[id]\"><img src=\"images/wf_edit_btn.gif\" border=0></a></td>
								<td class='row2' align=center><a href=\"index.php?page=wordfilter&d=delete&id=$row[id]\"><img src=\"images/wf_delete_btn.gif\" border=0></a></td>
							  </tr>";


					}

					echo "</table>";
}

} else {
	echo "<center><span class=red>You need to be an admin to access this page!</span></center>";
}
?>