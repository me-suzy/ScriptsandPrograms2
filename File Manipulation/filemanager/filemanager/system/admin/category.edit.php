<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<script language=\"JavaScript\" type=\"text/javascript\">
	<!--
	
	function checkCat() {
		
		if(document.createform.category_subof.options[document.createform.category_subof.selectedIndex].value != '0') {
			
			var grplen = document.getElementsByName('category_groups[]').length;
			
			for(var i=0; i<grplen; i++) {
				document.getElementsByName('category_groups[]')[i].checked = false;
				document.getElementsByName('category_groups[]')[i].disabled = true;
			}
		}
		else {
			
			var grplen = document.getElementsByName('category_groups[]').length;
			
			for(var i=0; i<grplen; i++) {
				
				document.getElementsByName('category_groups[]')[i].disabled = false;
			}
		}
	}
	
	//-->
</script>

<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Kategorien - Kategorie editieren</b></td>
	</tr>
</table>
<br>
<a href=\"javascript:window.close();\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Schliessen</a>
<br><br>
";
$sql = "SELECT * FROM `user_category` WHERE `category_id` = '$_REQUEST[category_id]'";
$result = mysql_query($sql, Config::getDbLink());
if($data = mysql_fetch_array($result)) {
	
	echo "
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
		<tr>
			<td valign=\"top\">
				<form name=\"createform\" action=\"process.php?action=category.update\" method=\"post\">
					<input type=\"hidden\" name=\"category_id\" value=\"$data[category_id]\">
					<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
						<tr>
							<td>Position:</td>
							<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
							<td>
							<select name=\"category_subof\" style=\"width:200px;\" onchange=\"checkCat()\">
								<option value=\"0\">Hauptkategorie</option>
								";
								
								$category_tree = array();
								Category::getSubCategorys($data['category_id'],$category_tree);
								
								$sql2 = "SELECT * FROM `user_category` ORDER BY `category_name`";
								$result2 = mysql_query($sql2, Config::getDbLink());
								while($data2 = mysql_fetch_array($result2)) {
									
									if(!in_array($data2['category_id'], $category_tree) && $data2['category_id'] != $data['category_id']) {
										echo "<option value=\"$data2[category_id]\""; if($data2['category_id'] == $data['category_subof']) { echo " selected"; } echo ">Sub von $data2[category_name]</option>";
									}
								}
								echo "
							</select>
							</td>
						</tr>
						<tr>
							<td>Name:</td>
							<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
							<td><input type=\"text\" name=\"category_name\" value=\"$data[category_name]\" style=\"width:200px;\"></td>
						</tr>
						<tr>
							<td valign=\"top\">Sichtbar für:</td>
							<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
							<td>
							";
							
							$category_groups = array();
							
							$sql2 = "SELECT * FROM `relation_group2category` WHERE `category_id` = '$data[category_id]'";
							$result2 = mysql_query($sql2, Config::getDbLink());
							while($data2 = mysql_fetch_array($result2)) {
								
								$category_groups[] = $data2['group_id'];
							}
							
							$sql2 = "SELECT * FROM `user_group` ORDER BY `group_name`";
							$result2 = mysql_query($sql2, Config::getDbLink());
							while($data2 = mysql_fetch_array($result2)) {
								
								echo "<input type=\"checkbox\" name=\"category_groups[]\" value=\"$data2[group_id]\""; if(in_array($data2['group_id'], $category_groups)) { echo " checked"; } echo "> $data2[group_name]<br>";
							}
							echo "
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
							<td><input type=\"submit\" name=\"sent_data\" value=\"Speichern\"></td>
						</tr>
					</table>
				</form>
				<br>
			</td>
		</tr>
	</table>
	
	<script language=\"JavaScript\" type=\"text/javascript\">
		<!--
		
		checkCat();
		
		//-->
	</script>
	";
}

?>