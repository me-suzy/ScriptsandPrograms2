<?
function RECOMMENDED_DROPDOWN() {
	$recommended_dd_query="SELECT * 
						   FROM guestmap;";	
	$recommended_dd_result = mysql_query($recommended_dd_query);
	if(!$recommended_dd_result) {
		echo "Doh!  Error creating dropdown from database."; exit;
	}
	else {
		$recommended = "<select name='guestmap_recommended'><option value='0'></option>";
		while ($row = mysql_fetch_object($recommended_dd_result)) {
			$recommended .= "<option value='$row->guestmap_ID'>$row->guestmap_name</option>";
			
			/*$i++;
			$recommended['$i']['name'] = $row->guestmap_name;
			$recommended['$i']['ID'] = $row->guestmap_ID;*/
		}
		$recommended .= "</select>";
	}
return $recommended;
}
?>



