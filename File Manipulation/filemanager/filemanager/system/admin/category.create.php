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
		<td><b>Kategorien - Neue Kategorie erstellen</b></td>
	</tr>
</table>
<br>
<a href=\"javascript:window.close();\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Schliessen</a>
<br><br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<form name=\"createform\" action=\"process.php?action=category.insert\" method=\"post\">
				<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
					<tr>
						<td>Position:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td>
						<select name=\"category_subof\" style=\"width:200px;\" onchange=\"checkCat()\">
							<option value=\"0\">Hauptkategorie</option>
							";
							$sql = "SELECT * FROM `user_category` ORDER BY `category_name`";
							$result = mysql_query($sql, Config::getDbLink());
							while($data = mysql_fetch_array($result)) {
								
								echo "<option value=\"$data[category_id]\">Sub von $data[category_name]</option>";
							}
							echo "
						</select>
						</td>
					</tr>
					<tr>
						<td>Name:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"category_name\" style=\"width:200px;\"></td>
					</tr>
					<tr>
						<td valign=\"top\">Sichtbar für:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td>
						";
						$sql = "SELECT * FROM `user_group` ORDER BY `group_name`";
						$result = mysql_query($sql, Config::getDbLink());
						while($data = mysql_fetch_array($result)) {
							
							echo "<input type=\"checkbox\" name=\"category_groups[]\" value=\"$data[group_id]\"> $data[group_name]<br>";
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
";

?>