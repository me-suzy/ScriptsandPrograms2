<?PHP
if($_userlevel == 3){
echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/ads_title.gif\" border=0 alt=\"\"><p>";
echo "<div align=right><a href=index.php?page=ads&d=new><img src=images/new_ad.gif border=0></a></div>";

if($_GET[d] == "new"){
	if($_POST['submit']){
			$result=MYSQL_QUERY("INSERT INTO `ads` (`ad_type`, `ad_text_name` , `ad_text_href` , `ad_text_description`)".
			"VALUES ('text', '$_POST[ad_name]', '$_POST[ad_link]', '$_POST[ad_desc]')"); 

		header("Location: index.php?page=ads");
	} else {
					echo "
					<table width=100% class=category_table cellpadding=0 cellspacing=0>
						<tr>
							<td class=table_1_header>			
								<b>Add a new AD</b>			
							</td>
						</tr>
						<tr>
							<td align=center><form action=index.php?page=ads&d=new method=post>
									<input type=hidden name=id value='$id'>

							<table border=0 cellpadding=2 cellspacing=2>
								<tr>
									<td width=35% valign=top>
										<b>Ad Title:</b>
									</td>
									<td>
										<input type='text' name='ad_name' value=\"$row[ad_text_name]\" size='50' class='textbox'>
									</td>
								</tr>
								<tr>
									<td width=35% valign=top>
										<b>Ad Link:</b>
									</td>
									<td>
										<input type='text' name='ad_link' size='50' value=\"$row[ad_text_href]\" class='textbox'>
									</td>
								</tr>
								<tr>
									<td width=35% valign=top>
										<b>Description:</b>
									</td>
									<td>
										<textarea name='ad_desc' rows='10' cols='50' class='textbox'>$row[ad_text_description]</textarea>
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
	$get_ad_info = mysql_query("SELECT * FROM ads WHERE id='$_GET[id]'");
	$row = mysql_fetch_array($get_ad_info);
	if($_GET['sure'] == "yes"){
		$delete_category = mysql_query("DELETE FROM ads WHERE id='$_GET[id]'");

		header("Location: index.php?page=ads");
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
										Are you sure you would like to delete this ad?
									</td>
								</tr>
								<tr>
									<td class=contentmain align=center>
										<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
											<tr>
												<td class=\"redtable\" align=center width=100>
													<a href=\"index.php?page=ads&d=delete&id=$_GET[id]&sure=yes\" class=link2>Yes</a>
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
		$update_ad = mysql_query("UPDATE ads SET ad_text_name='$_POST[ad_name]', ad_text_href='$_POST[ad_link]', ad_text_description='$_POST[ad_desc]' WHERE id='$_GET[id]'");

		header("Location: index.php?page=ads");
	} else {
	$get_ad_info = mysql_query("SELECT * FROM ads WHERE id='$_GET[id]'");
	$row = mysql_fetch_array($get_ad_info);
					echo "
					<table width=100% class=category_table cellpadding=0 cellspacing=0>
						<tr>
							<td class=table_1_header>			
								<b>Manage Current AD</b>			
							</td>
						</tr>
						<tr>
							<td align=center><form action=\"index.php?page=ads&d=edit&id=$_GET[id]\" method=\"post\">
									<input type=hidden name=id value='$id'>

							<table border=0 cellpadding=2 cellspacing=2>
								<tr>
									<td width=35% valign=top>
										<b>Ad Title:</b>
									</td>
									<td>
										<input type='text' name='ad_name' value=\"$row[ad_text_name]\" size='50' class='textbox'>
									</td>
								</tr>
								<tr>
									<td width=35% valign=top>
										<b>Ad Link:</b>
									</td>
									<td>
										<input type='text' name='ad_link' size='50' value=\"$row[ad_text_href]\" class='textbox'>
									</td>
								</tr>
								<tr>
									<td width=35% valign=top>
										<b>Description:</b>
									</td>
									<td>
										<textarea name='ad_desc' rows='10' cols='50' class='textbox'>$row[ad_text_description]</textarea>
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
					$ad_sql = mysql_query("SELECT * FROM ads ORDER BY ad_text_name ASC");

					echo "<table width=100% class=category_table cellpadding=0 cellspacing=0>
							<tr>
								<td class=table_1_header colspan=4>			
									<b>Manage Ads</b>			
								</td>
							</tr>
							<tr class=table_subheader>
								<td width=75 align=left class=table_subheader></td>
								<td align=left class=table_subheader>Site</td>
								<td width=40 align=left class=table_subheader></td>
								<td width=40 align=left class=table_subheader></td>
							</tr>";
					while($row = mysql_fetch_array($ad_sql)){
						$ad_id = $row['id'];
						$ad_name = $row['ad_text_name'];
						$ad_desc = $row['ad_text_description'];
						$ad_link = $row['ad_text_href'];

						if(!($even_number_count % 2) == TRUE){
							$even_number = 1;
						} else {
							$even_number = 2;
						}
					
						$even_number_count = $even_number_count + 1;

						echo "<tr>
								<td align=center><img src=\"images/ads_img.gif\" border=0></td>
								<td class=\"row$even_number\"  onmouseover=\"this.className='row1_on';\" onmouseout=\"this.className='row$even_number';\">
									<a href='$ad_link' target=_blank>$ad_name</a><br>
									<span style=\"font-size: 7pt;\">$ad_desc</span>
								</td>
								<td class='row2' align=center><a href=\"index.php?page=ads&d=edit&id=$row[id]\"><img src=\"images/ad_edit_btn.gif\" border=0></a></td>
								<td class='row2' align=center><a href=\"index.php?page=ads&d=delete&id=$row[id]\"><img src=\"images/ad_delete_btn.gif\" border=0></a></td>
							  </tr>";


					}

					echo "</table>";
}

} else {
	echo "<center><span class=red>You need to be an admin to access this page!</span></center>";
}
?>