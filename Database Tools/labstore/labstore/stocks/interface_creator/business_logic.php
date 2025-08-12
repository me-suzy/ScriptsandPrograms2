<?php
function check_login()
{global $simple_login_user;
$simple_login_user->CheckLogin();
$simple_login_user->ManageUsers(); // put this line if you want the admin to see the manager interface
} // end function check_login()
function get_user()
{
global $simple_login_user;
$user = $simple_login_user->userName();
return $user;
} // end function get_ID_user
function current_user_is_owner($where_field, $where_value, $table_name, $fields_labels_ar)
{
global $current_user, $conn, $db_name;
// get the name of the field that has ID_user type
$ID_user_field_name = get_ID_user_field_name($fields_labels_ar);
if ($ID_user_field_name === false) {return true;}
else {// check if the owner of the record is current_user
$sql = "SELECT ".$quote.$ID_user_field_name.$quote." FROM ".$quote.$table_name.$quote." WHERE ".$quote.$where_field.$quote." = '".$where_value."' AND ".$quote.$ID_user_field_name.$quote." = '".add_slashes($current_user)."'";
$res = execute_db($sql, $conn);
$num_rows= get_num_rows_db($res);
if ($num_rows === 1){return true;}
else{return false;}
} // end else
} // end function current_user_is_owner()
function get_ID_user_field_name($fields_labels_ar)
{
$ID_user_field_name = false;
$fields_labels_ar_count = count($fields_labels_ar);
$i = 0;
while ($i < $fields_labels_ar_count && $ID_user_field_name === false) {
		if ($fields_labels_ar[$i]['type_field'] === 'ID_user') {
			$ID_user_field_name = $fields_labels_ar[$i]['name_field'];
		} // end if
		$i++;
} // end while
return $ID_user_field_name;
} // end function get_ID_user_field_name()
function get_user_infos_ar_from_username_password($username_user, $password_user, $md5_or_not)
{
global $conn, $users_table_name, $users_table_username_field, $users_table_password_field, $users_table_user_type_field, $quote;
if ($md5_or_not == 'md5'){$password_user = md5($password_user);} // md5 hash before comparison; not needed if from cookie
$sql = "SELECT ".$quote.$users_table_username_field.$quote.",
".$quote.$users_table_password_field.$quote.",
".$quote.$users_table_user_type_field.$quote." FROM ".$quote.$users_table_name.$quote." WHERE ".$quote.$users_table_username_field.$quote." = '".$username_user."' AND ".$quote.$users_table_password_field.$quote." = '".$password_user."'";
$res = execute_db($sql, $conn);
if (get_num_rows_db($res) === 1){
		$row = fetch_row_db($res);
		$user_infos_ar['username_user'] = $row[$users_table_username_field];
		$user_infos_ar['password_user'] = $row[$users_table_password_field];
		$user_infos_ar['user_type_user'] = $row[$users_table_user_type_field];
		return $user_infos_ar;
} // end if
else {return false;}
} // end function get_user_infos_ar_from_username_password()
function build_fields_names_array($table_name)
{global $conn, $db_name;
	// put the name of the table's fields in an array
	$fields = list_fields_db($db_name, $table_name, $conn);
	$fields_number = num_fields_db($fields);
	for ($i = 0; $i < $fields_number; $i++) {
		$fields_names_ar[$i] = field_name_db($fields, $i);
		}
	return $fields_names_ar;
} // end build_fields_names_array function
function build_tables_names_array($exclude_not_allowed = 1, $exclude_not_installed = 1, $inlcude_users_table = 0)
{global $conn, $db_name, $prefix_internal_table, $table_list_name, $quote, $users_table_name, $current_user_is_administrator;
$z = 0;
$tables_names_ar = array();
if ( $exclude_not_installed == 1 ) { // get the list from $table_list_name tab 
$sql = "SELECT name_table FROM ".$quote.$table_list_name.$quote;
		if ( $exclude_not_allowed == 1) { // excluding not allowed if necessary
			$sql .= " WHERE allowed_table = '1'";
		} // end if
$res = execute_db($sql, $conn);
		while ($row = fetch_row_db($res)) {
			if ($current_user_is_administrator === 1 || $row[0] !== $users_table_name || $inlcude_users_table === 1) {
				$tables_names_ar[$z] = $row[0];
				$z++;
			} // end if
		}
	} // end if
	else{ // get the list directly from db
		$tables_res=list_tables_db($db_name);
		$table_number = get_num_rows_db($tables_res);
		for ($i=0; $i<$table_number; $i++){
			$table_name_temp = tablename_db($tables_res, $i);
			// if the table is not internal
			if (substr($table_name_temp, 0, strlen($prefix_internal_table)) != $prefix_internal_table && $table_name_temp != $table_list_name ){
				$tables_names_ar[$z] = tablename_db($tables_res, $i);
				$z++;
			} // end if
		} // end for
	} // end else
	return $tables_names_ar;
} // end build_tables_names_array function
function table_exists($table_name)
{
global $conn, $quote;
if ( mysql_query("DESCRIBE ".$quote.$table_name.$quote, $conn) === false) {
		return false;}
else{return true;}
} // end function table_exists
function build_fields_labels_array($table_internal_name, $order)
// goal: build an array ($fields_labels_ar) containing the fields labels and other information about fields (e.g. the type, display/don't display) of a specified table to use in the form
// input: name of the internal table, $order, 0|1 if shouldn't/should be order by order_form
// output: fields_labels_ar, a 2 dimensions associative array: $fields_labels_ar[field_number]["internal table field (e.g. present_insert_form_field)"]
// global $error_messages_ar, the array containg the error messages
{
	global $conn, $error_messages_ar, $quote;

	$table_alias_suffixes_ar = array();

	// put the labels and other information of the table's fields in an array
	$sql = "SELECT ".$quote."name_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."type_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."content_field".$quote.", ".$quote."label_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.",
".$quote."linked_fields_extra_mysql".$quote.",
".$quote."select_type_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote." FROM ".$quote.$table_internal_name.$quote;
	
	if ($order == "1"){
		$sql .= " ORDER BY ".$quote."order_form_field".$quote;
	} // end if

	$res_field = execute_db($sql, $conn);
	$i = 0;
	if (get_num_rows_db($res_field) > 0) { // at least one record
		while($field_row = fetch_row_db($res_field)){
			$fields_labels_ar[$i]["name_field"] = $field_row["name_field"]; // the name of the field
			$fields_labels_ar[$i]["present_insert_form_field"] = $field_row["present_insert_form_field"]; // 1 if the user want to display it in the insert form
			$fields_labels_ar[$i]["present_ext_update_form_field"] = $field_row["present_ext_update_form_field"]; // 1 if the user want to display it in the external update form
			$fields_labels_ar[$i]["present_search_form_field"] = $field_row["present_search_form_field"]; // 1 if the user want to display it in the search form
			$fields_labels_ar[$i]["required_field"] = $field_row["required_field"]; // 1 if the field is required in the insert (the field must be in the insert form, otherwise this flag hasn't any effect
			$fields_labels_ar[$i]["present_results_search_field"] = $field_row["present_results_search_field"]; // 1 if the user want to display it in the basic results page
			$fields_labels_ar[$i]["present_details_form_field"] = $field_row["present_details_form_field"]; // 1 if the user want to display it in the basic results page
			$fields_labels_ar[$i]["check_duplicated_insert_field"] = $field_row["check_duplicated_insert_field"]; // 1 if the field needs to be checked for duplicated insert
			
			$fields_labels_ar[$i]["label_field"] = $field_row["label_field"]; // the label of the field
			$fields_labels_ar[$i]["type_field"] = $field_row["type_field"]; // the type of the field
			$fields_labels_ar[$i]["other_choices_field"] = $field_row["other_choices_field"]; // 0/1 the possibility to add another choice with select single menu
			$fields_labels_ar[$i]["content_field"] = $field_row["content_field"]; // the control type of the field (eg: numeric, alphabetic, alphanumeric)
			$fields_labels_ar[$i]["select_options_field"] = $field_row["select_options_field"]; // the options, separated by separator, possible in a select field
			$fields_labels_ar[$i]["separator_field"] = $field_row["separator_field"]; // the separator of different possible values for a select field
			$fields_labels_ar[$i]["primary_key_field_field"] = $field_row["primary_key_field_field"]; // the primary key field_name if this field is a foreign key
			$fields_labels_ar[$i]["primary_key_table_field"] = $field_row["primary_key_table_field"]; // the primary key table_name if this field is a foreign key
			$fields_labels_ar[$i]["primary_key_db_field"] = $field_row["primary_key_db_field"]; // the primary key database if this field is a foreign key
			$fields_labels_ar[$i]["linked_fields_field"] = $field_row["linked_fields_field"]; // the fields linked to through the pk
			$fields_labels_ar[$i]["linked_fields_order_by_field"] = $field_row["linked_fields_order_by_field"]; // the fields by which order when retreiving the linked fields
			$fields_labels_ar[$i]["linked_fields_order_type_field"] = $field_row["linked_fields_order_type_field"]; // the order type (ASC|DESC) to use in the order clause when retreiving the linked fields
			$fields_labels_ar[$i]["linked_fields_extra_mysql"] = $field_row["linked_fields_extra_mysql"]; // extra MySQl command to use when retreiving the linked fields
			$fields_labels_ar[$i]["select_type_field"] = $field_row["select_type_field"]; // the type of select, exact match or like
			$fields_labels_ar[$i]["prefix_field"] = $field_row["prefix_field"]; // the prefix of the field (e.g. http:// - only for text, textarea and rich_editor)
			$fields_labels_ar[$i]["default_value_field"] = $field_row["default_value_field"]; // the default value of the field (only for text, textarea and rich_editor)
			$fields_labels_ar[$i]["width_field"] = $field_row["width_field"]; // the width size of the field in case of text, textarea or rich_editor
			$fields_labels_ar[$i]["height_field"] = $field_row["height_field"]; // the height size of the field in case of textarea or rich_editor
			$fields_labels_ar[$i]["maxlength_field"] = $field_row["maxlength_field"]; // the maxlength of the field in case of text
			$fields_labels_ar[$i]["hint_insert_field"] = $field_row["hint_insert_field"]; // the hint to display after the field in the insert form (e.g. use only number here!!)
			$fields_labels_ar[$i]["order_form_field"] = $field_row["order_form_field"]; // the position of the field in the form

			if ($field_row["primary_key_field_field"] !== '') {
				$linked_fields_ar = explode($field_row["separator_field"], $field_row["linked_fields_field"]);

				if ( array_key_exists($field_row["primary_key_table_field"], $table_alias_suffixes_ar) === false){
					$table_alias_suffixes_ar[$field_row["primary_key_table_field"]] = 1;
					$fields_labels_ar[$i]["alias_suffix_field"] = 1;
				} // end if
				else {
					$table_alias_suffixes_ar[$field_row["primary_key_table_field"]]++;
					$fields_labels_ar[$i]["alias_suffix_field"] = $table_alias_suffixes_ar[$field_row["primary_key_table_field"]];
				} // end else

			} // end if

			$i++;
		} // end while
	} // end if
	else { // no records
		echo $error_messages_ar["int_db_empty"];
	} // end else
	return $fields_labels_ar;
} // end build_fields_labels_array function

function build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, $where_field, $where_value)

// goal: build a tabled form by using the info specified in the array $fields_labels_ar
// input: $table_name, array containing labels and other info about fields, $action (the action of the form), $form_type, $res_details, $where_field, $where_value (the last three useful just for update forms)
// global: $submit_buttons_ar, the array containing the values of the submit buttons, $normal_messages_ar, the array containig the normal messages, $select_operator_feature, wheter activate or not displaying "and/or" in the search form, $default_operator, the default operator if $select_operator_feature is not activated, $db_name, $size_multiple_select, the size (number of row) of the select_multiple fields, $table_name
// output: $form, the html tabled form
{
global $conn, $submit_buttons_ar, $normal_messages_ar, $select_operator_feature, $default_operator, $db_name, $size_multiple_select, $upload_relative_url, $show_top_buttons, $quote, $enable_authentication, $enable_browse_authorization, $current_user;
switch ($form_type) {
		case 'insert':
			$function = 'insert';
			break;
		case 'update':
			$function = 'update';
			break;
		case 'ext_update':
			$function = 'ext_update';
			break;
		case 'search':
			$function = 'search';
			break;
} // end switch
$form = "";
$form .= "<form id=\"contacts_form\" method=\"post\" action=\"".$action."?table_name=".urlencode($table_name)."&amp;page=0&amp;function=".$function;
if ( $form_type == "update" or $form_type == "ext_update") {
		$form .= "&amp;where_field=".urlencode($where_field)."&amp;where_value=".urlencode($where_value);
	}

	if ( $form_type == "search") {
		$form .= "&amp;execute_search=1";
	}
	
	$form .= "\" enctype=\"multipart/form-data\"><table>";

	switch($form_type){
		case "insert":
			$number_cols = 4;
			$field_to_ceck = "present_insert_form_field";
			break;
		case "update":
			$number_cols = 4;
			$field_to_ceck = "present_insert_form_field";
			$details_row = fetch_row_db($res_details); // get the values of the details
			if ( $show_top_buttons == 1) {
				$form .= "<tr class=\"tr_button_form\"><td colspan=\"".$number_cols."\" class=\"td_button_form\"><input class=\"button_form\" type=\"submit\" value=\"".$submit_buttons_ar[$form_type]."\" /></td></tr>";
			}
			break;
		case "ext_update":
			$number_cols = 4;
			$field_to_ceck = "present_ext_update_form_field";
			$details_row = fetch_row_db($res_details); // get the values of the details
			if ( $show_top_buttons == 1) {
				$form .= "<tr class=\"tr_button_form\"><td colspan=\"".$number_cols."\" class=\"td_button_form\"><input class=\"button_form\" type=\"submit\" value=\"".$submit_buttons_ar[$form_type]."\" /></td></tr>";
			}
			break;
		case "search":
			$number_cols = 3;
			$field_to_ceck = "present_search_form_field";
			if ($select_operator_feature == "1"){
				$form .= "<tr class=\"tr_operator_form\"><td colspan=\"".$number_cols."\" class=\"td_button_form\"><select single=\"single\" name=\"operator\" id=\"operator\"><option value=\"and\">".$normal_messages_ar["all_conditions_required"]."</option><option value=\"or\">".$normal_messages_ar["any_conditions_required"]."</option></select></td></tr>";
			} // end if
			else{
				$form .= "<input type=\"hidden\" id=\"operator\" name=\"operator\" value=\"".$default_operator."\" />";
			} // end else
			if ( $show_top_buttons == 1) {
				$form .= "<tr class=\"tr_button_form\"><td colspan=\"".$number_cols."\"><input  class=\"button_form\" type=\"submit\" value=\"".$submit_buttons_ar[$form_type]."\" /></td></tr>";
			}
			break;
	} // end switch
	
	for ($i=0; $i<count($fields_labels_ar); $i++){
		if ($fields_labels_ar[$i][$field_to_ceck] == "1") { // the user want to display the field in the form
			
			// build the first coloumn (label)
			//////////////////////////////////
			// I put a table inside the cell to get the same margin of the second coloumn
			$form .= "<tr><td align=\"right\" valign=\"top\"><table summary=\"none\"><tr><td class=\"td_label_form\" style=\"white-space:nowrap;\">";
			if ($fields_labels_ar[$i]["required_field"] == "1" and $form_type != "search"){
				$form .= "<span class=\"required_field_labels\">";
			} // end if 
			$form .= $fields_labels_ar[$i]["label_field"]." ";
			if ($fields_labels_ar[$i]["required_field"] == "1"){
				$form .= "</span>";
			} // end if
			$form .= "</td></tr></table></td><td>";
			//////////////////////////////////
			// end build the first coloumn (label)

			// build the second coloumn (input field)
			/////////////////////////////////////////
			$field_name_temp = $fields_labels_ar[$i]["name_field"];
			$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
			if ($primary_key_field_field != ""){
					$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
					$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
					$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
					$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
					$linked_fields_order_by_field = $fields_labels_ar[$i]["linked_fields_order_by_field"];
					if ($linked_fields_order_by_field !== '') {
						$linked_fields_order_by_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_order_by_field);
					} // end if
					else {
						unset($linked_fields_order_by_ar);
					} // end else

					$linked_fields_order_type_field = $fields_labels_ar[$i]["linked_fields_order_type_field"];
					$linked_fields_extra_mysql = $fields_labels_ar[$i]["linked_fields_extra_mysql"];

					$sql = "SELECT ".$quote.$primary_key_field_field.$quote;

					$count_temp = count($linked_fields_ar);
					for ($j=0; $j<$count_temp; $j++) {
						$sql .= ", ".$quote.$linked_fields_ar[$j].$quote;
					}
					$sql .= " FROM ".$quote.$primary_key_table_field.$quote;
                   // extra mysql clauses
                   if ($linked_fields_extra_mysql != "")
                   {$sql .= $linked_fields_extra_mysql;}
					if (isset($linked_fields_order_by_ar)) {
						$sql .= " ORDER BY ";
						$count_temp = count($linked_fields_order_by_ar);
						for ($j=0; $j<$count_temp; $j++) {
							$sql .= $quote.$linked_fields_order_by_ar[$j].$quote.", ";
						}
						$sql = substr($sql, 0, -2); // delete the last ","
						$sql .= " ".$linked_fields_order_type_field;
					} // end if

				//} // end else
				// select the primary key database
				if ($fields_labels_ar[$i]["primary_key_db_field"] != ""){
					select_db($fields_labels_ar[$i]["primary_key_db_field"], $conn);
				} // end if

				$res_primary_key = execute_db($sql, $conn);

				// re-select the main database
				select_db($db_name, $conn);
			} // end if

			if ($form_type == "search"){
				$select_type_select = build_select_type_select($field_name_temp."_select_type", $fields_labels_ar[$i]["select_type_field"], 0); // build the select type select form (is_equal....)
				$select_type_date_select = build_select_type_select($field_name_temp."_select_type", $fields_labels_ar[$i]["select_type_field"], 1); // build the select type select form (is_equal....) for date fields, with the first option blank
			} // end if
			else{
				$select_type_select = "";
				$select_type_date_select = "";
			} // end else
			$form .= "<table summary=\"none\" border=\"0\"><tr>";
			switch ($fields_labels_ar[$i]["type_field"]){
case "text":
case "ID_user":
case "unique_ID":
$form .= "<td class=\"td_input_form\">".$select_type_select."<input type=\"text\" name=\"".$field_name_temp."\" id=\"".$field_name_temp."\"";
if ($fields_labels_ar[$i]["width_field"] != "")
{$form .= " size=\"".$fields_labels_ar[$i]["width_field"]."\"";}
else
{$form .= " size=\"25\"";}
if ($fields_labels_ar[$i]["maxlength_field"] != "")
{$form .= " maxlength=\"".$fields_labels_ar[$i]["maxlength_field"]."\"";}
if ($form_type == "update" or $form_type == "ext_update")
{$form .= " value=\"".htmlspecialchars($details_row[$field_name_temp])."\"";}
if ($form_type == "insert")
{$form .= " value=\"".$fields_labels_ar[$i]["prefix_field"].$fields_labels_ar[$i]["default_value_field"]."\"";}
$form .= " /></td>";
break;
case "generic_file":
case "image_file":
if ($form_type == "search") { // build a textbox instead of a file input
$form .= "<td class=\"td_input_form\">".$select_type_select."<input type=\"text\" name=\"".$field_name_temp."\" id=\"".$field_name_temp."\" size=\"".$fields_labels_ar[$i]["width_field"]."\" />";
}
else{
$form .= "<td class=\"td_input_form\"><input type=\"file\" name=\"".
						  $field_name_temp."\" id=\"".
						  $field_name_temp."\" size=\"".$fields_labels_ar[$i]["width_field"]."\" />";
if ($form_type == "update" or $form_type == "ext_update"){
							$file_name_temp = $details_row[$field_name_temp];
							if ($file_name_temp != "")
							{
								$form .= "<br />".$normal_messages_ar["current_upload"].": <p><a href=\"".$upload_relative_url;
								$form .= str_replace("%2F", "/", rawurlencode(substr($file_name_temp, 18)));
								$form .= "\">";
								$form .= htmlspecialchars(substr($file_name_temp, 18));
								$form .= "</a> <input type=\"checkbox\" value=\"".htmlspecialchars($file_name_temp)."\" name=\"".$field_name_temp."_file_uploaded_delete\" id=\"".$field_name_temp."_file_uploaded_delete\" /> (".$normal_messages_ar['delete'].") <input type=\"hidden\" value=\"".htmlspecialchars($file_name_temp)."\" name=\"".$field_name_temp."_file_uploaded_delete2\" id=\"".$field_name_temp."_file_uploaded_delete2\" />";
							} // end if
						} // end if
}
$form .= "</td>"; // add the second coloumn to the form
break;
case "textarea":
$form .= "<td class=\"td_input_form\">".$select_type_select."</td>";
$form .= "<td class=\"td_input_form\"><textarea cols=\"".$fields_labels_ar[$i]["width_field"]."\" rows=\"".$fields_labels_ar[$i]["height_field"]."\" name=\"".$field_name_temp."\" id=\"".$field_name_temp."\">";
if ($form_type == "update" or $form_type == "ext_update"){
						$form .= htmlspecialchars($details_row[$field_name_temp]);
					} // end if
					if ($form_type == "insert"){
						$form .= $fields_labels_ar[$i]["prefix_field"].$fields_labels_ar[$i]["default_value_field"];
					} // end if
$form .= "</textarea></td>"; // add the second coloumn to the form
break;
case "rich_editor":
$form .= "<td class=\"td_input_form\">".$select_type_select."</td>";
$form .= "<td class=\"td_input_form\"><textarea cols=\"".$fields_labels_ar[$i]["width_field"]."\" rows=\"".$fields_labels_ar[$i]["height_field"]."\" name=\"".$field_name_temp."\" name=\"".$field_name_temp."\">";
if ($form_type == "update" or $form_type == "ext_update"){
						$form .= htmlspecialchars($details_row[$field_name_temp]);
					} // end if
if ($form_type == "insert"){
						$form .= $fields_labels_ar[$i]["prefix_field"].$fields_labels_ar[$i]["default_value_field"];
					} // end if
					
$form .= "</textarea></td>"; // add the second coloumn to the form
$form .= "<script type=\"text/javascript\" language=\"javascript\">
				<![CDATA[
				editor_generate('".$field_name_temp."');
				]]>
				</script>";
break;
case "password":
$form .= "<td class=\"td_input_form\">".$select_type_select."<input type=\"password\" name=\"".$field_name_temp."\" id=\"".$field_name_temp."\" size=\"".$fields_labels_ar[$i]["width_field"]."\" maxlength=\"".$fields_labels_ar[$i]["maxlength_field"]."\"";
if ($form_type == "update" or $form_type == "ext_update"){
$form .= " value=\"".htmlspecialchars($details_row[$field_name_temp])."\"";
					} // end if
$form .= " /></td>"; // add the second coloumn to the form
break;

case "date":
switch($form_type){
						case "update":
							split_date($details_row[$field_name_temp], $day, $month, $year);
							$date_select = build_date_select($field_name_temp, $day, $month, $year);
							break;
						case "insert":
							$date_select = build_date_select($field_name_temp,"","","");
							break;
						case "search":
							//$operator_select = build_date_select_type_select($field_name_temp."_select_type");
							$date_select = build_date_select($field_name_temp,"","","");
							break;
					} // end switch
					$form .= "<td class=\"td_input_form\">".$select_type_date_select."</td>".$date_select; // add the second coloumn to the form
					break;
				case "insert_date":
				case "update_date":
					//$operator_select = "";
					$date_select = "";
					switch($form_type){
						case "search":
							//$operator_select = build_date_select_type_select($field_name_temp."_select_type");
							$date_select = build_date_select($field_name_temp,"","","");
							break;
					} // end switch
					$form .= "<td class=\"td_input_form\">".$select_type_date_select."</td>".$date_select."</td>"; // add the second coloumn to the form
					break;
////////////////////////////////////////////////////////////////////////////////
case "select_multiple":
case "select_multiple_checkbox":
$what_selected_check = array();
$form .= "<td class=\"td_input_form\">";
if ($fields_labels_ar[$i]["other_choices_field"] == "1")
{$form .= "Choose, and/or type ...<br />";}
else
{$form .= "Choose ...<br />";}
if ($fields_labels_ar[$i]["type_field"] == "select_multiple")
{$form .= "<select name=\"".$field_name_temp."[]\" id=\"".$field_name_temp."[]\" size=\"".$size_multiple_select."\" multiple=\"multiple\">";}
// if options provided
if ( $fields_labels_ar[$i]["select_options_field"] != "")
{$options_labels_temp = substr($fields_labels_ar[$i]["select_options_field"], 1, -1);
$select_labels_ar = explode($fields_labels_ar[$i]["separator_field"],$options_labels_temp);
$select_labels_ar_number = count($select_labels_ar);
$any_selected_check = array();
for ($j=0; $j<$select_labels_ar_number; $j++){
 if ($fields_labels_ar[$i]["type_field"] == "select_multiple")
 {$form .= "<option value=\"".htmlspecialchars($select_labels_ar[$j])."\"";}
 elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox")
 {$form .= "<input type=\"checkbox\" name=\"".$field_name_temp."[]\" id=\"".$field_name_temp."[]\" value=\"".htmlspecialchars($select_labels_ar[$j])."\"";}
 if ($form_type == "update" or $form_type == "ext_update")
  {
  $options_values_temp = $details_row[$field_name_temp];
  // in case no separators flank - if was select_single before
  $separator_first_check = stripos($options_values_temp, $fields_labels_ar[$i]["separator_field"]);
  $separator_last_check = strripos($options_values_temp, $fields_labels_ar[$i]["separator_field"]);
  if ($separator_first_check == "0" and $separator_last_check == (strlen($options_values_temp)-1))
  {
  $options_values_temp = substr($options_values_temp, 1, -1);
  }
  $select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
  if ( in_array($select_labels_ar[$j],$select_values_ar ))
   {
    if ($fields_labels_ar[$i]["type_field"] == "select_multiple")
    {
    $form .= " selected=\"selected\"";
    }
    elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox")
    {
    $form .= " checked=\"checked\"";
    }
   $what_selected_check[] = $select_labels_ar[$j];
   }
  }
 if ($fields_labels_ar[$i]["type_field"] == "select_multiple")
 {$form .= "> ".$select_labels_ar[$j]."</option>";}
 elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox")
 {$form .= " /> ".$select_labels_ar[$j]."<br />";}
} // end for 
} // end if options provided
// if from foreign table
if ($fields_labels_ar[$i]["primary_key_field_field"] != "")
{
// if any value in foreign able that can be used as option
if (get_num_rows_db($res_primary_key) > 0)
{
// get options to build menu
// get number of fields to work with; if only main field, is 1. If one linked field, 2, and so on
$fields_number = num_fields_db($res_primary_key);
// get from all the rows - main and any linked fields
$options_array=array();
while ($primary_key_row = fetch_row_db($res_primary_key))
{
  $linked_fields_value = "";
  for ($z=1; $z<$fields_number; $z++)
   {
   $linked_fields_value .= $primary_key_row[$z];
   $linked_fields_value .= " - ";
   } // end for
  $linked_fields_value = substr($linked_fields_value, 0, -3); // delete last " -
  // put into array
  $options_array[] = $linked_fields_value;
} // end getting options to build menu
// if form is to edit, get current filled values
if ($form_type == "update" or $form_type == "ext_update")
{
  // getting current filled in values
  $options_values_temp = $details_row[$field_name_temp];
   // in case no separators flank - if was select_single type before
  $separator_first_check = stripos($options_values_temp, $fields_labels_ar[$i]["separator_field"]);
  $separator_last_check = strripos($options_values_temp, $fields_labels_ar[$i]["separator_field"]);
  if ($separator_first_check == "0" and $separator_last_check == (strlen($options_values_temp)-1))
   {
   $options_values_temp = substr($options_values_temp, 1, -1);
   }
  // put into array; as is something like ~abc~def~ghi~
  $select_values_ar =  explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
  // to keep track of matches
  $what_selected_check = array();
}
// start building menu
foreach ($options_array as $key=>$option_value)
{
if ($fields_labels_ar[$i]["type_field"] == "select_multiple")
{$form .= "<option value=\"".htmlspecialchars($option_value)."\"";}
elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox")
{$form .= "<input type=\"checkbox\" name=\"".$field_name_temp."[]\" id=\"".$field_name_temp."[]\" value=\"".htmlspecialchars($option_value)."\"";}
// show filled in in if in edit form
if ($form_type == "update" or $form_type == "ext_update")
{
  if (in_array($option_value, $select_values_ar))
    {
	if ($fields_labels_ar[$i]["type_field"] == "select_multiple")
	 {
	 $form .= " selected=\"selected\"";									
     }
	elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox")
	 {
	 $form .= " checked=\"checked\"";
	 }
	$what_selected_check[] = $option_value; 
    }
} // end showing filled in if form type is edit
if ($fields_labels_ar[$i]["type_field"] == "select_multiple")
{$form .= "> ".$option_value."</option>";}
elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox")
{$form .= " /> ".$option_value."<br />";}
} // end foreach for building menu
} // end if any value in foreign table
} // end if from foreign table
// finish the menu
if ($fields_labels_ar[$i]["type_field"] == "select_multiple")
{$form .= "</select>";}
// start input box if allowed ('other choice')
if ($fields_labels_ar[$i]["other_choices_field"] == "1" and ($form_type == "insert" or $form_type == "update"))
{
$form .= "<br /><input type=\"text\" name=\"".$field_name_temp."[]\" id=\"".$field_name_temp."[]\" maxlength=\"".$fields_labels_ar[$i]["maxlength_field"]."\"";
if ($fields_labels_ar[$i]["width_field"] != "")
 {$form .= " size=\"".$fields_labels_ar[$i]["width_field"]."\"";}
else {$form .= " size=\"25\"";}
// start if form is to edit - again; to fill in a value in box
if (($form_type == "update" or $form_type == "ext_update") and (isset($select_values_ar) and isset($what_selected_check)))
{
$for_box_array = array_diff($select_values_ar,$what_selected_check);
if (count($for_box_array)>0)
 {
 $for_box_string = implode($fields_labels_ar[$i]["separator_field"],$for_box_array);
 $form .= " value=\"";
 $form .= $for_box_string;
 $form .= "\"";
 }
}
// end if form is to edit - again; to fill in a value in textbox 
$form .= " />";
}
$form .= "</td>";
break;
////////////////////////////////////////////////////////////////////////////////
case "select_single":
// current value (for use with editing but not for new insert)
if (!isset($details_row[$field_name_temp])){$details_row[$field_name_temp]="";}
$to_fill = $details_row[$field_name_temp];
// in case field was select_multiple type before, remove separators
$separator_first_check = stripos($to_fill, $fields_labels_ar[$i]["separator_field"]);
$separator_last_check = strripos($to_fill, $fields_labels_ar[$i]["separator_field"]);
if ($separator_first_check == "0" and $separator_last_check == (strlen($to_fill)-1))
{
$to_fill = substr($to_fill, 1, -1);
}
// build
$form .= "<td class=\"td_input_form\">".$select_type_select."<select single=\"single\" name=\"".$field_name_temp."\" id=\"".$field_name_temp."\">";
if ($fields_labels_ar[$i]["other_choices_field"] == "1")
{$form .= "<option value=\"\">Choose one, or type ...</option>";}
else
{$form .= "<option value=\"\">Choose one ...</option>";}
// generate options for pull down menu
$field_temp = substr($fields_labels_ar[$i]["select_options_field"], 1, -1); // delete the first and the last separator: ~Paris~London~Delhi~
if (trim($field_temp) !== '')
{
$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$field_temp);
$count_temp = count($select_values_ar);
$any_selected_check = array();
for ($j=0; $j<$count_temp; $j++)
 {
 $form .= "<option value=\"".htmlspecialchars($select_values_ar[$j])."\"";
 // if form to edit
 if (($form_type == "update" or $form_type == "ext_update") and ($select_values_ar[$j] == $to_fill))
  {
  $form .= " selected =\"selected\"";
  $any_selected_check[] = "yes";
  }
 else
  {
  $any_selected_check[] = "no";
  }
 $form .= ">".$select_values_ar[$j]."</option>";
 } // end if form to edit
} // end generating options
if ($fields_labels_ar[$i]["primary_key_field_field"] != "") // if from foreign table
{
 $any_selected_check = array();
 if (get_num_rows_db($res_primary_key) > 0)
 {
 // get values from foreign table
 $fields_number = num_fields_db($res_primary_key);
 while ($primary_key_row = fetch_row_db($res_primary_key))
  {
  $primary_key_value = $primary_key_row[0];
  $linked_fields_value = "";
  for ($z=1; $z<$fields_number; $z++)
   {$linked_fields_value .= $primary_key_row[$z];
   $linked_fields_value .= " - ";
   } // end for
  $linked_fields_value = substr($linked_fields_value, 0, -3); // delete last " - 
  $form .= "<option value=\"".htmlspecialchars($primary_key_value)."\"";
  if (($form_type == "update" or $form_type == "ext_update") and ($primary_key_value == $to_fill))
   {
   $form .= " selected =\"selected\"";
   $any_selected_check[] = "yes";
   }
  else
  {
  $any_selected_check[] = "no";
  }
  $form .= ">".$linked_fields_value."</option>";
  } // end while
 } // end if
} // end if from foreign table
$form .= "</select>";
// input box - if user allowed to type in other choice
if ($fields_labels_ar[$i]["other_choices_field"] == "1" and ($form_type == "insert" or $form_type == "update")) 
{$form .= "<br /><input type=\"text\" name=\"".$field_name_temp."_other____"."\" id=\"".$field_name_temp."_other____"."\" maxlength=\"".$fields_labels_ar[$i]["maxlength_field"]."\"";
if ($fields_labels_ar[$i]["width_field"] != "")
 {$form .= " size=\"".$fields_labels_ar[$i]["width_field"]."\"";}
if (!in_array("yes", $any_selected_check))
 {$form .= " value=\"".htmlspecialchars($to_fill)."\"";}
$form .= " />";
} // end box for other choice
$form .= "</td>";
break;
} // end switch
/////////////////////////////////////////	
			// end build the second coloumn (input field)

			if ($form_type == "insert" or $form_type == "update" or $form_type == "ext_update"){
				$form .= "<td class=\"td_hint_form\">".$fields_labels_ar[$i]["hint_insert_field"]."</td>"; // display the insert hint if it's the insert form
			} // end if
			$form .= "</tr></table></td></tr>";
		} // end if ($fields_labels_ar[$i]["$field_to_ceck"] == "1")
	} // enf for loop for each field in the label array
	

	$form .= "<tr><td class=\"tr_button_form\" colspan=\"".$number_cols."\"><p><input type=\"submit\" class=\"button_form\" value=\"".$submit_buttons_ar[$form_type]."\" /></p></td></tr></table></form>";
	return $form;
} // end build_form function

function build_select_type_select($field_name, $select_type, $first_option_blank)
// goal: build a select with the select type of the field (e.g. is_equal, contains....)
// input: $field_name, $select_type (e.g. is_equal/contains), $first_option_blank(0|1)
// output: $select_type_select
// global: $normal_messages_ar, the array containing the normal messages
{
	global $normal_messages_ar;

	$select_type_select = "";

	$operators_ar = explode("/",$select_type);

	if (count($operators_ar) > 1){ // more than on operator, need a select
		$select_type_select .= "<select single=\"single\" name=\"".$field_name."\" id=\"".$field_name."\">";
		$count_temp = count($operators_ar);
		if ($first_option_blank === 1) {
			$select_type_select .= "<option value=\"\"></option>";
		} // end if
		for ($i=0; $i<$count_temp; $i++){
			$select_type_select .= "<option value=\"".$operators_ar[$i]."\">".$normal_messages_ar[$operators_ar[$i]]."</option>";
		} // end for
		$select_type_select .= "</select>";
	} // end if
	else{ // just an hidden
		$select_type_select .= "<input type=\"hidden\" name=\"".$field_name."\" id=\"".$field_name."\" value=\"".$operators_ar[0]."\" />";
	}

	return $select_type_select;
} // end function build_select_type_select

function check_required_fields($_POST, $_FILES, $fields_labels_ar)
// goal: check if the user has filled all the required fields
// input: all the fields values ($_POST), $_FILES (for uploaded files) and the array containing infos about fields ($fields_labels_ar)
// output: $check, set to 1 if the check is ok, otherwise 0
{
	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp and $check == 1){
		if ($fields_labels_ar[$i]["required_field"] == "1" and $fields_labels_ar[$i]["present_insert_form_field"] == "1"){
			$field_name_temp = $fields_labels_ar[$i]["name_field"];
			switch($fields_labels_ar[$i]["type_field"]){
				case "date":
					break; // date is always filled
				case "select_single":
					if ($fields_labels_ar[$i]["other_choices_field"] == "1")
					{
						$field_name_other_temp = $field_name_temp."_other____";
						if ($_POST["$field_name_other_temp"] == "" and $_POST[$field_name_temp] == "")
						{
							$check = 0;
						} // end if
					} // end if
					else{
						if ($_POST[$field_name_temp] == ""){
							$check = 0;
						} // end if
					} // end else
					break;
				case "select_multiple":
				case "select_multiple_checkbox":
				   $stringed = implode ("",$_POST[$field_name_temp]);
				   if (strlen($stringed)<1){$check = 0;}
				   break;
				case "generic_file":
				case "image_file":
                   if ($_FILES[$field_name_temp]['name'] != '' OR $_POST[$field_name_temp] != '' OR ($_POST[$field_name_temp.'_file_uploaded_delete2'] != '' and !isset($_POST[$field_name_temp.'_file_uploaded_delete']))){$check = 1;}
                   else
                   {$check = 0;}
				   break;
				default:
					if ($_POST[$field_name_temp] == $fields_labels_ar[$i]["prefix_field"]){
						$_POST[$field_name_temp] = "";
					} // end if
					if ($_POST[$field_name_temp] == ""){
						$check = 0;
					} // end if
					break;
			} // end switch
		} // end if
		$i++;
	} // end while
	return $check;
} // end function check_required_fields

function check_length_fields($_POST, $fields_labels_ar)
// goal: check if the text, password, textarea, rich_editor, select_single, select_multiple_checkbox, select_multiple fields contains too much text
// input: all the fields values ($_POST) and the array containing infos about fields ($fields_labels_ar)
// output: $check, set to 1 if the check is ok, otherwise 0
{
	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp and $check == 1){
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		// I use isset for select_multiple because could be unset
		if ($fields_labels_ar[$i]["maxlength_field"] != "" && isset($_POST[$field_name_temp])){
			switch($fields_labels_ar[$i]["type_field"]){
				case "text":
				case "password":
				case "textarea":
				case "rich_editor":
					if (strlen($_POST[$field_name_temp]) > $fields_labels_ar[$i]["maxlength_field"]){
						$check = 0;
					} // end if
					break;
				case "select_multiple_checkbox":
				case "select_multiple":
					$count_temp_2 = count($_POST[$field_name_temp]);
					$value_temp = "";
					for ($j=0; $j<$count_temp_2; $j++) {
						$value_temp .= $fields_labels_ar[$i]["separator_field"].$_POST[$field_name_temp][$j];
					}
					$value_temp .= $fields_labels_ar[$i]["separator_field"]; // add the last separator
					if (strlen($value_temp) > $fields_labels_ar[$i]["maxlength_field"]){
						$check = 0;
					} // end if
                    break;
				case "select_single":
					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "......"){
						$field_name_other_temp = $field_name_temp."_other____";
						if (strlen($_POST[$field_name_other_temp]) > $fields_labels_ar[$i]["maxlength_field"]){
							$check = 0;
						} // end if
					} // end if
					else{
						if (strlen($_POST[$field_name_temp]) > $fields_labels_ar[$i]["maxlength_field"]){
							$check = 0;
						} // end if
					} // end else
					break;
			} // end switch
		} // end if
		$i++;
	} // end while
	return $check;
} // end function check_length_fields

function write_temp_uploaded_files($_FILES, $fields_labels_ar)
// goal: write an upload file with a temporary name, checking the size and the file extension, the correct name will be assigned later in insert_record or update_record
// input: all the uploaded files ($_FILES) and the array containing infos about fields ($fields_labels_ar)
// output: $check, set to 1 everyhings is fine (also the write procedure), otherwise 0
{
	global $upload_directory, $max_upload_file_size, $allowed_file_exts_ar, $allowed_all_files, $table_name;
// START make table-specific upload directory
	$upload_directory2 = $upload_directory.$table_name.'/';
	if (!file_exists($upload_directory2)){mkdir($upload_directory2);}
	if (file_exists($upload_directory2) and !is_dir($upload_directory2)){unlink($upload_directory2); mkdir($upload_directory2);}
	$uploaded_file_names_count = 0;
// END make table-specific upload directory

	$uploaded_file_names_ar = array();
	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp and $check == 1){
		switch($fields_labels_ar[$i]["type_field"]){
			case "generic_file":
			case "image_file":
				$field_name_temp = $fields_labels_ar[$i]["name_field"];

				if ($_FILES[$field_name_temp]['name'] != ''){
					
					$file_name_temp = $_FILES[$field_name_temp]['tmp_name'];
					$file_name = strip_slashes($_FILES[$field_name_temp]['name']);
					$file_name = get_valid_name_uploaded_file($file_name, 1);

					$file_size = $_FILES[$field_name_temp]['size'];

					$file_suffix_temp = strrchr($file_name, ".");
					$file_suffix_temp = substr($file_suffix_temp, 1); // remove the first dot
					
					if ( !in_array(strtolower($file_suffix_temp), $allowed_file_exts_ar) && $allowed_all_files != 1){
						$check = 0;
					}
					else {
						if ($file_size > $max_upload_file_size) {
							$check = 0;
						}
						else { //go ahead and copy the file into the upload directory
							if (!(move_uploaded_file($file_name_temp, $upload_directory2.'dadabik_tmp_file_'.$file_name))) {
								$check = 0;
							}
						} // end else
					} // end else
				} // end if
				break;
		} // end switch
		$i++;
	} // end while
	return $check;
} // end function write_temp_uploaded_files

function check_fields_types($_POST, $fields_labels_ar, &$content_error_type)
// goal: check if the user has well filled the form, according to the type of the field (e.g. no numbers in alphabetic fields, emails and urls correct)
// input: all the fields values ($_POST) and the array containing infos about fields ($fields_labels_ar), &$content_error_type, a string that change according to the error made (alphabetic, numeric, email, phone, url....)
// output: $check, set to 1 if the check is ok, otherwise 0
{
	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp and $check == 1){
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		if (isset($_POST[$field_name_temp])){ // otherwise it has not been filled
			if ($_POST[$field_name_temp] == $fields_labels_ar[$i]["prefix_field"]){
				$_POST[$field_name_temp] = "";
			} // end if
			if ($fields_labels_ar[$i]["type_field"] == "select_single" && $fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "......"){ // other field filled
				$field_name_temp = $field_name_temp."_other____";
			} // end if
			if (($fields_labels_ar[$i]["type_field"] == "text" || $fields_labels_ar[$i]["type_field"] == "textarea" || $fields_labels_ar[$i]["type_field"] == "rich_editor" || $fields_labels_ar[$i]["type_field"] == "select_single" || $fields_labels_ar[$i]["type_field"] == "select_multiple" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") and $fields_labels_ar[$i]["present_insert_form_field"] == "1" and $_POST[$field_name_temp] != ""){
				
				switch ($fields_labels_ar[$i]["content_field"]){
					case "alphabetic":
						if ( $fields_labels_ar[$i]["type_field"] == "select_multiple" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
							$count_temp_2 = count($_POST[$field_name_temp]);
							$j=0;
							while ($j<$count_temp_2 && $check == 1) {
								if (contains_numerics($_POST[$field_name_temp][$j])){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
								$j++;
							}
						}
						else{
							if (contains_numerics($_POST[$field_name_temp])){
								$check = 0;
								$content_error_type = $fields_labels_ar[$i]["content_field"];
							} // end if
						}
						break;
					case "numeric":
						if ( $fields_labels_ar[$i]["type_field"] == "select_multiple" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
							$count_temp_2 = count($_POST[$field_name_temp]);
							$j=0;
							while ($j<$count_temp_2 && $check == 1) {
								if (!is_numeric($_POST[$field_name_temp][$j])){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
								$j++;
							}
						}
						else{
							if (!is_numeric($_POST[$field_name_temp])){
								$check = 0;
								$content_error_type = $fields_labels_ar[$i]["content_field"];
							} // end if
						}
						break;
					case "phone":
						if ( $fields_labels_ar[$i]["type_field"] == "select_multiple" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
							$count_temp_2 = count($_POST[$field_name_temp]);
							$j=0;
							while ($j<$count_temp_2 && $check == 1) {
								if (!is_valid_phone($_POST[$field_name_temp][$j])){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
								$j++;
							}
						}
						else{
							if (!is_valid_phone($_POST[$field_name_temp])){
								$check = 0;
								$content_error_type = $fields_labels_ar[$i]["content_field"];
							} // end if
						}
						break;
					case "email":
						if ( $fields_labels_ar[$i]["type_field"] == "select_multiple" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
							$count_temp_2 = count($_POST[$field_name_temp]);
							$j=0;
							while ($j<$count_temp_2 && $check == 1) {
								if (!is_valid_email($_POST[$field_name_temp][$j])){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
								$j++;
							}
						}
						else{
							if (!is_valid_email($_POST[$field_name_temp])){
								$check = 0;
								$content_error_type = $fields_labels_ar[$i]["content_field"];
							} // end if
						}
						break;
					case "url":
						if ( $fields_labels_ar[$i]["type_field"] == "select_multiple" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
							$count_temp_2 = count($_POST[$field_name_temp]);
							$j=0;
							while ($j<$count_temp_2 && $check == 1) {
								if (!is_valid_url($_POST[$field_name_temp][$j])){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
								$j++;
							}
						}
						else{
							if (!is_valid_url($_POST[$field_name_temp])){
								$check = 0;
								$content_error_type = $fields_labels_ar[$i]["content_field"];
							} // end if
						}
						break;
				} // end switch
			} // end if
		} // end if
		elseif( $fields_labels_ar[$i]["type_field"] == "date" ){
			$day = $_POST[$field_name_temp."_day"];
			$month = $_POST[$field_name_temp."_month"];
			$year = $_POST[$field_name_temp."_year"];
			if (!is_numeric($day) || !is_numeric($month) || !is_numeric($year) || !checkdate( $month, $day, $year )){
				$check = 0;
				$content_error_type = "date";
			} // end if
		}
		$i++;
	} // end while
	return $check;
} // end function check_fields_types

function build_select_duplicated_query($_POST, $table_name, $fields_labels_ar, &$string1_similar_ar, &$string2_similar_ar)
// goal: build the select query to select the record that can be similar to the record inserted
// input: all the field values ($_POST), $table_name, $fields_labels_ar, &$string1_similar_ar, &$string2_similar_ar (the two array that will contain the similar string found)
// output: $sql, the sql query
// global $percentage_similarity, the percentage after that two strings are considered similar, $number_duplicated_records, the maximum number of records to be displayed as duplicated
{
	global $percentage_similarity, $number_duplicated_records, $conn, $quote;
	
	// get the unique key of the table
	$unique_field_name = get_unique_field($table_name);

	if ($unique_field_name != ""){ // a unique key exists, ok, otherwise I'm not able to select the similar record, which field should I use to indicate it?

		/*
		reset ($_POST);
		while (list($key, $value) = each ($_POST)){
			$$key = $value;
		} // end while
		*/

		$sql = "";
		$sql_select_all = "";
		$sql_select_all = "SELECT ".$quote.$unique_field_name.$quote.", "; // this is used to select the records to check similiarity
		$select = "SELECT * FROM ".$quote.$table_name.$quote."";
		$where_clause = "";	

		// build the sql_select_all clause
		$j = 0;
		// build the $fields_to_check_ar array, containing the field to check for similiarity
		$fields_to_check_ar = array();
		$count_temp = count($fields_labels_ar);
		for ($i=0; $i<$count_temp; $i++){
			if ($fields_labels_ar[$i]["check_duplicated_insert_field"] == "1"){
				//if (${$fields_labels_ar[$i]["name_field"]} != ""){
					$fields_to_check_ar[$j] = $fields_labels_ar[$i]["name_field"]; // I put in the array only if the field is non empty, otherwise I'll check it even if I don't need it
				//} // end if
				$sql_select_all .= $quote.$fields_labels_ar[$i]["name_field"].$quote.", ";
				$j++;
			} // end if
		} // end for
		$sql_select_all = substr ($sql_select_all, 0, -2); // delete the last ", "
		$sql_select_all .= " FROM ".$quote.$table_name.$quote;
		// end build the sql_select_all clause

		// at the end of the above procedure I'll have, for example, "select ID, name, email from table" if ID is the unique key, name and email are field to check

		// execute the select query
		$res_contacts = execute_db($sql_select_all, $conn);	

		if (get_num_rows_db($res_contacts) > 0){
			while ($contacts_row = fetch_row_db($res_contacts)){ // *A* for each record in the table
				$count_temp = count($fields_to_check_ar);
				for ($i=0; $i<$count_temp; $i++){ // *B* and for each field the user has inserted
					$z=0;
					$found_similarity =0; // set to 1 when a similarity is found, so that it's possible to exit the loop (if I found that a record is similar it doesn't make sense to procede with other fields of the same record)
					
					// *C* check if the field inserted are similiar to the other fields to be checked in this record (*A*)
					$count_temp_2 = count($fields_to_check_ar);
					while ($z<$count_temp_2 and $found_similarity == 0){
						$string1_temp = $_POST[$fields_to_check_ar[$i]]; // the field the user has inserted
						$string2_temp = $contacts_row[$z+1]; // the field of this record (*A*); I start with 1 because 0 is alwais the unique field (e.g. ID, name, email)
						
						similar_text(strtolower($string1_temp), strtolower($string2_temp), $percentage);
						if ($percentage >= $percentage_similarity){ // the two strings are similar
							$where_clause .= $quote.$unique_field_name.$quote." = \"".$contacts_row[$unique_field_name]."\" or ";
							$found_similarity = 1;
							$string1_similar_ar[]=$string1_temp;
							$string2_similar_ar[]=$string2_temp;
						} // end if the two strings are similar
						$z++;
					} // end while

				} // end for loop for each field to check
			} // end while loop for each record
		} // end if (get_num_rows_db($res_contacts) > 0)

		$where_clause = substr($where_clause, 0, -4); // delete the last " or "
		if ($where_clause != ""){
			$sql = $select." where ".$where_clause." limit 0,".$number_duplicated_records;
		} // end if
		else{ // no duplication
			$sql = "";
		} // end else*
	} // end if if ($unique_field_name != "")
	else{ // no unique keys
		$sql = "";
	} // end else
	return $sql;	
} // end function build_select_duplicated_query

function build_insert_duplication_form($_POST, $fields_labels_ar, $table_name, $table_internal_name)
// goal: build a tabled form composed by two buttons: "Insert anyway" and "Go back"
// input: all the field values ($_POST), $fields_labels_ar, $conn, $table_name, $table_internal_name
// output: $form, the form
// global $submit_buttons_ar, the array containing the caption on submit buttons
{
	global $submit_buttons_ar, $dadabik_main_file;

	$form = "";

	$form .= "<table summary=\"none\"><tr><td>";

	$form .= "<form action=\"".$dadabik_main_file."?table_name=".urlencode($table_name)."&amp;function=insert&amp;insert_duplication=1\" method=\"post\"><p>";

	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){

		$field_name_temp = $fields_labels_ar[$i]["name_field"];

		if ($fields_labels_ar[$i]["present_insert_form_field"] == "1"){
			switch ($fields_labels_ar[$i]["type_field"]){
				case "select_multiple":
				case "select_multiple_checkbox":
					if (isset($_POST[$field_name_temp])){
						$count_temp_2 = count($$field_name_temp);
						for ($j=0; $j<$count_temp_2; $j++){
							$form .= "<input type=\"hidden\" name=\"".$field_name_temp."[".$j."]"."\" id=\"".$field_name_temp."[".$j."]"."\" value=\"".htmlspecialchars(strip_slashes($_POST[$field_name_temp][$j]))."\" />";// add the field value to the sql statement
						} // end for
					} // end if
					break;
				case "date":
					$year_field = $field_name_temp."_year";
					$month_field = $field_name_temp."_month";
					$day_field = $field_name_temp."_day";

					$form .= "<input type=\"hidden\" name=\"".$year_field."\" idname=\"".$year_field."\" value=\"".$_POST[$year_field]."\" />";
					$form .= "<input type=\"hidden\" name=\"".$month_field."\" id=\"".$month_field."\" value=\"".$_POST[$month_field]."\" />";
					$form .= "<input type=\"hidden\" name=\"".$day_field."\" id=\"".$day_field."\" value=\"".$_POST[$day_field]."\" />";
					break;
				case "select_single":
					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "......"){ // other choice filled
						$field_name_other_temp = $field_name_temp."_other____";
						$form .= "<input type=\"hidden\" name=\"".$field_name_temp."\" id=\"".$field_name_temp."\" value=\"".htmlspecialchars(strip_slashes($_POST[$field_name_temp]))."\" />";
						$form .= "<input type=\"hidden\" name=\"".$field_name_other_temp."\" id=\"".$field_name_other_temp."\" value=\"".htmlspecialchars(strip_slashes($_POST[$field_name_other_temp]))."\" />";
					} // end if
					else{
						$form .= "<input type=\"hidden\" name=\"".$field_name_temp."\" id=\"".$field_name_temp."\" value=\"".htmlspecialchars(strip_slashes($_POST[$field_name_temp]))."\" />";
					} // end else
					break;
				default: // textual field
					if ($_POST[$fields_labels_ar[$i]["name_field"]] == $fields_labels_ar[$i]["prefix_field"]){ // the field contain just the prefix
						$_POST[$fields_labels_ar[$i]["name_field"]] = "";
					} // end if
						
					$form .= "<input type=\"hidden\" name=\"".$field_name_temp."\" id=\"".$field_name_temp."\" value=\"".htmlspecialchars(strip_slashes($_POST[$fields_labels_ar[$i]["name_field"]]))."\" />";
					break;
			} // end switch
		} // end if
	} // end for
	$form .= "<input type=\"submit\" value=\"".$submit_buttons_ar["insert_anyway"]."\"></p></form>";

	$form .= "</td><td>";

	$form .= "<form><p><input type=\"button\" value=\"".$submit_buttons_ar["go_back"]."\" onclick=\"javascript:history.back(-1)\" /></p></form>";

	$form .= "</td></tr></table>";

	return $form;
} // end function build_insert_duplication_form
function build_change_table_select($exclude_not_allowed=1, $inlcude_users_table = 0)
// goal: build a select to choose the table
// input: $exclude_not_allowed, $inlcude_users_table (1 if it is necessary to include the users table, even if the user is not admin (useful in admin.php)
// output: $select, the html select
{
	global $conn, $table_name, $autosumbit_change_table_control;
	$change_table_select = "";
	$change_table_select .= "<select name=\"table_name\" class=\"select_change_table\"";
	if ( $autosumbit_change_table_control == 1) {
		$change_table_select .= " onchange=\"javascript:document.change_table_form.submit();\"";
	}
	$change_table_select .= ">";

	if ($exclude_not_allowed == 1){
		if ($inlcude_users_table == 0) {
			// get the array containing the names of the tables installed(excluding not allowed)
			$tables_names_ar = build_tables_names_array(1, 1, 0);
		} // end if
		else {
			// get the array containing the names of the tables installed(excluding not allowed)
			$tables_names_ar = build_tables_names_array(1, 1, 1);
		} // end else
	} // end if
	else{
		if ($inlcude_users_table == 0) {
			// get the array containing the names of the tables installed
			$tables_names_ar = build_tables_names_array(0, 1, 0);
		} // end if
		else {
			// get the array containing the names of the tables installed
			$tables_names_ar = build_tables_names_array(0, 1, 1);
		} // end else
	} // end else

	$count_temp = count($tables_names_ar);
	for($i=0; $i<$count_temp; $i++){
		$change_table_select .= "<option value=\"".htmlspecialchars($tables_names_ar[$i])."\"";
		if ($table_name == $tables_names_ar[$i]){
			$change_table_select .= " selected=\"selected\"";
		}
		$change_table_select .= ">".$tables_names_ar[$i]."</option>";
	} // end for
	$change_table_select .= "</select>";
	if ($count_temp == 1){
		return "";
	} // end if
	else{
		return $change_table_select;
	} // end else
} // end function build_change_table_select
function table_contains($db_name_2, $table_name, $field_name, $value)
// goal: check if a table contains a record which has a field set to a specified value
// input: $db_name, $table_name, $field_name, $value
// output: true or false
{
	global $conn, $quote, $db_name;
	if ( $db_name_2 != "") {
		select_db($db_name_2, $conn);
	}
	$sql = "SELECT COUNT(".$quote.$field_name.$quote.") FROM ".$quote.$table_name.$quote." WHERE ".$quote.$field_name.$quote." = '".$value."'";
	$res_count = execute_db($sql, $conn);
	$count_row = fetch_row_db($res_count);
	if ($count_row[0] > 0){
		return true;
	} // end if
	// re-select the old db
	if ( $db_name_2 != "") {
		select_db($db_name, $conn);
	}
	return false;
} // end function table_contains

function insert_record($_FILES, $_POST, $fields_labels_ar, $table_name, $table_internal_name)
// goal: insert a new record in the table
// input $_FILES (needed for the name of the files), $_POST (the array containing all the values inserted in the form), $fields_labels_ar, $table_name, $table_internal_name
// output: nothing
{
	global $conn, $db_name, $quote, $upload_directory, $current_user;
	// START make table-specific upload directory
	$upload_directory2 = $upload_directory.$table_name.'/';
	if (!file_exists($upload_directory2)){mkdir($upload_directory2);}
	if (file_exists($upload_directory2) and !is_dir($upload_directory2)){unlink($upload_directory2); mkdir($upload_directory2);}
	$uploaded_file_names_count = 0;
    // END make table-specific upload directory

	/*
	// get the post variables of the form
	reset ($_POST);
	while (list($key, $value) = each ($_POST)){
		$$key = $value;
	} // end while
	*/

	$uploaded_file_names_count = 0;

	// build the insert statement
	/////////////////////////////
	$sql = "";
	$sql .= "INSERT INTO ".$quote.$table_name.$quote." (";

	$count_temp=count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){
		if ($fields_labels_ar[$i]["present_insert_form_field"] == "1" || $fields_labels_ar[$i]["type_field"] == "insert_date" || $fields_labels_ar[$i]["type_field"] == "update_date" || $fields_labels_ar[$i]["type_field"] == "ID_user" || $fields_labels_ar[$i]["type_field"] == "unique_ID"){ // if the field is in the form or need to be inserted because it's an insert data, an update data, an ID_user or a unique_ID
			$sql .= $quote.$fields_labels_ar[$i]["name_field"].$quote.", "; // add the field name to the sql statement
		} // end if
	} // end for

	$sql = substr($sql, 0, (strlen($sql)-2));

	$sql .= ") VALUES (";

	for ($i=0; $i<$count_temp; $i++){
		if ($fields_labels_ar[$i]["present_insert_form_field"] == "1"){ // if the field is in the form
			switch ($fields_labels_ar[$i]["type_field"]){
				case "generic_file":
				case "image_file":
					
					$name_field_temp = $fields_labels_ar[$i]["name_field"];
					$file_name = strip_slashes($_FILES[$name_field_temp]['name']);
					
					if ($file_name != '') {
						$file_name = get_valid_name_uploaded_file($file_name, 0);
					}
                    $file_name2 = $table_name."/".$file_name;
					
					//$sql .= "'".add_slashes($uploaded_file_names_ar[$uploaded_file_names_count])."', "; // add the field value to the sql statement
					$uploaded_file_names_count++;
					
					if ($file_name != '') {
						// rename the temp name of the uploaded file
						copy ($upload_directory2.'dadabik_tmp_file_'.$file_name, $upload_directory2.$file_name);
						unlink($upload_directory2.'dadabik_tmp_file_'.$file_name);
						$sql .= "'The filepath is - ".add_slashes($file_name2)."', "; // add the field value to the sql statement
					} // end if
					else {$sql .= "'', ";} // add the field value to the sql statement
					break;
				case "select_multiple":
				case "select_multiple_checkbox":
					$field_name_temp = $fields_labels_ar[$i]["name_field"];
					$sql .= "'";
					if (isset($_POST[$fields_labels_ar[$i]["name_field"]])){ // otherwise the user hasn't checked any options
						
						$count_temp_2 = count($_POST[$fields_labels_ar[$i]["name_field"]]);
						for ($j=0; $j<$count_temp_2; $j++){
                        if (!(empty($_POST[$field_name_temp][$j])))
                        {
                        $sql .= $fields_labels_ar[$i]["separator_field"].$_POST[$field_name_temp][$j];
                        $add_last = 'yes';
                        } // end if
                        } // end for

						if(isset($add_last) and $add_last == 'yes'){$sql .= $fields_labels_ar[$i]["separator_field"];} // add the last separator
					} // end if
					$sql .= "', ";
					break;
				case "date":
					$field_name_temp = $fields_labels_ar[$i]["name_field"];
					$year_field = $field_name_temp."_year";
					$month_field = $field_name_temp."_month";
					$day_field = $field_name_temp."_day";

					$mysql_date_value = $_POST[$year_field]."-".$_POST[$month_field]."-".$_POST[$day_field];
					$sql .= "'".$mysql_date_value."', "; // add the field value to the sql statement

					break;
				case "select_single":
					$field_name_temp = $fields_labels_ar[$i]["name_field"];
					$field_name_other_temp = $fields_labels_ar[$i]["name_field"]."_other____";

					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "......" and $_POST[$field_name_other_temp] != ""){ // insert the "other...." choice
						$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
						if ($primary_key_field_field != ""){
							
							$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $fields_labels_ar[$i]["linked_fields_field"]);

							$primary_key_field_field = insert_other_field($fields_labels_ar[$i]["primary_key_db_field"], $fields_labels_ar[$i]["primary_key_table_field"], $linked_fields_ar[0], $_POST[$field_name_other_temp]);

							$sql .= "'".$primary_key_field_field."', "; // add the last ID inserted to the sql statement
						} // end if ($foreign_key_temp != "")
						else{ // no foreign key field
							$sql .= "'".$_POST[$field_name_other_temp]."', "; // add the field value to the sql statement
							if ( strpos($fields_labels_ar[$i]["select_options_field"], $fields_labels_ar[$i]["separator_field"].$_POST[$field_name_other_temp].$fields_labels_ar[$i]["separator_field"] === false) ){ // the other field inserted is not already present in the $fields_labels_ar[$i]["select_options_field"] so we have to add it

								udpate_options($fields_labels_ar[$i], $field_name_temp, $_POST[$field_name_other_temp]);

								// re-get the array containg label ant other information about the fields changed with the above instruction
								$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
							} // end if
						} // end else
					} // end if
					else{
						$sql .= "'".$_POST[$field_name_temp]."', "; // add the field value to the sql statement
					} // end else
					break;
				default: // textual field and select single
					if ($_POST[$fields_labels_ar[$i]["name_field"]] == $fields_labels_ar[$i]["prefix_field"]){ // the field contain just the prefix
						$_POST[$fields_labels_ar[$i]["name_field"]] = "";
					} // end if
					$sql .= "'".$_POST[$fields_labels_ar[$i]["name_field"]]."', "; // add the field value to the sql statement
					break;
			} // end switch
			
		} // end if
		elseif ($fields_labels_ar[$i]["type_field"] == "insert_date" or $fields_labels_ar[$i]["type_field"] == "update_date"){ // if the field is not in the form but need to be inserted because it's an insert data or an update data
			$sql .= "'".date("Y-m-d H:i:s")."', "; // add the field name to the sql statement
		} // end elseif
		elseif ($fields_labels_ar[$i]["type_field"] == "ID_user"){ // if the field is not in the form but need to be inserted because it's an ID_user
			$sql .= "'".$current_user."', "; // add the field name to the sql statement
		} // end elseif
		elseif ($fields_labels_ar[$i]["type_field"] == "unique_ID"){ // if the field is not in the form but need to be inserted because it's a password record
			$pass = md5(uniqid(microtime(), 1)).getmypid();
			$sql .= "'".$pass."', "; // add the field name to the sql statement
		} // end elseif
	} // end for

	$sql = substr($sql, 0, (strlen($sql)-2));

	$sql .= ")";
	/////////////////////////////
	// end build the insert statement
	
	display_sql($sql);
	
	// insert the record
	$res_insert = execute_db($sql, $conn);
} // end function insert_record

function update_record($_FILES, $_POST, $fields_labels_ar, $table_name, $table_internal_name, $where_field, $where_value, $update_type)
// goal: insert a new record in the main database
// input $_FILES (needed for the name of the files), $_POST (the array containing all the values inserted in the form, $fields_labels_ar, $table_name, $table_internal_name, $where_field, $where_value, $update_type (internal or external)
// output: nothing
// global: $ext_updated_field, the field in which we set if a field has been updated
{
	global $conn, $ext_updated_field, $quote, $use_limit_in_update, $upload_directory;
	//START make table-specific upload directory
	$upload_directory2 = $upload_directory.$table_name.'/';
	if (!file_exists($upload_directory2)){mkdir($upload_directory2);}
	if (file_exists($upload_directory2) and !is_dir($upload_directory2)){unlink($upload_directory2); mkdir($upload_directory2);}
	$uploaded_file_names_count = 0;
	
	switch($update_type){
		case "internal":
			$field_to_check = "present_insert_form_field";
		break;
		case "external":
			$field_to_check = "present_ext_update_form_field";
		break;
	} // end switch

	// build the update statement
	/////////////////////////////
	$sql = "";
	$sql .= "UPDATE ".$quote.$table_name.$quote." SET ";
	
	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		if ($fields_labels_ar[$i][$field_to_check] == "1" or $fields_labels_ar[$i]["type_field"] == "update_date"){ // if the field is in the form or need to be inserted because it's an update data
			switch ($fields_labels_ar[$i]["type_field"]){
				case "generic_file":
				case "image_file":
					$file_name = strip_slashes($_FILES[$field_name_temp]['name']);
					if ( $file_name != '') 
					{ // the user has selected a new file to upload
						
						$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement

						$file_name = get_valid_name_uploaded_file($file_name, 0);
                        $file_name2 = $table_name."/".$file_name;
						$sql .= "'The filepath is - ".add_slashes($file_name2)."', "; // add the field value to the sql statement
						$uploaded_file_names_count++;

						// rename the temp name of the uploaded file
						copy ($upload_directory2.'dadabik_tmp_file_'.$file_name, $upload_directory2.$file_name);
						unlink($upload_directory2.'dadabik_tmp_file_'.$file_name);

						if (isset($_POST[$field_name_temp.'_file_uploaded_delete'])) 
						{ // the user want to delete a file previoulsy uploaded
							unlink( $upload_directory.substr(strip_slashes($_POST[$field_name_temp.'_file_uploaded_delete']), 18) );
						} // end if
						
						if (isset($_POST[$field_name_temp.'_file_uploaded_delete2'])) 
						{ // delete a file previously uploaded
							unlink( $upload_directory.substr(strip_slashes($_POST[$field_name_temp.'_file_uploaded_delete2']), 18) );
						} // end if
					}
					elseif (isset($_POST[$field_name_temp.'_file_uploaded_delete'])) 
					{ // the user want to delete a file previoulsy uploaded
						$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
						$sql .= "'', "; // add the field value to the sql statement
						unlink( $upload_directory.substr(strip_slashes($_POST[$field_name_temp.'_file_uploaded_delete']), 18) );
					}
					break;
				case "select_multiple":
				case "select_multiple_checkbox":
					$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
					$sql .= "'";
					$count_temp_2 = count($_POST[$field_name_temp]);
					for ($j=0; $j<$count_temp_2; $j++){
						if (!(empty($_POST[$field_name_temp][$j])))
                        {
 $sql .= $fields_labels_ar[$i]["separator_field"].$_POST[$field_name_temp][$j];
                        $add_last = 'yes';
                        } // end if
					} // end for
					if (isset($add_last) and $add_last == 'yes'){$sql .= $fields_labels_ar[$i]["separator_field"];} // add the last separator
					$sql .= "', ";
					break;
				case "update_date":
					$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
					$sql .= "'".date("Y-m-d H:i:s")."', "; // add the field name to the sql statement
					break;
				case "date":
					$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
					$field_name_temp = $field_name_temp;
					$year_field = $field_name_temp."_year";
					$month_field = $field_name_temp."_month";
					$day_field = $field_name_temp."_day";

					$mysql_date_value = $_POST[$year_field]."-".$_POST[$month_field]."-".$_POST[$day_field];
					$sql .= "'".$mysql_date_value."', "; // add the field value to the sql statement

					break;
				case 'select_single':
					$field_name_other_temp = $field_name_temp."_other____";

					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "" and $_POST[$field_name_other_temp] != ""){ // insert the "other...." choice
						
						$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
						if ($primary_key_field_field != ""){
							$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $fields_labels_ar[$i]["linked_fields_field"]);

							$primary_key_field_field = insert_other_field($fields_labels_ar[$i]["primary_key_db_field"], $fields_labels_ar[$i]["primary_key_table_field"], $linked_fields_ar[0], $_POST[$field_name_other_temp]);

							$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
							$sql .= "'".$primary_key_field_field."', "; // add the field value to the sql statement
						} // end if ($foreign_key_temp != "")
						else{ // no foreign key field
							$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
							$sql .= "'".$_POST[$field_name_other_temp]."', "; // add the field value to the sql statement
							if (strpos($fields_labels_ar[$i]["select_options_field"], $fields_labels_ar[$i]["separator_field"].$_POST[$field_name_other_temp].$fields_labels_ar[$i]["separator_field"]) === false){ // the other field inserted is not already present in the $fields_labels_ar[$i]["select_options_field"] so we have to add it

							if (isset($autoupdate_options) and $autoupdate_options == '1'){udpate_options($fields_labels_ar[$i], $field_name_temp, $_POST[$field_name_other_temp]);}

								// re-get the array containg label ant other information about the fields changed with the above instruction
								$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
							} // end if
						} // end else
					} // end if
					else{
						$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
						$sql .= "'".$_POST[$field_name_temp]."', "; // add the field value to the sql statement
					} // end else
					
					break;
				default: // textual field
					$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
					$sql .= "'".$_POST[$field_name_temp]."', "; // add the field value to the sql statement
					break;
			} // end switch
		} // end if
	} // end for
	$sql = substr($sql, 0, -2); // delete the last two characters: ", "
	$sql .= " where ".$quote.$where_field.$quote." = '".$where_value."'";
	if ($use_limit_in_update === 1){
		$sql .= " LIMIT 1";
	} // end if
	/////////////////////////////
	// end build the update statement
	
	display_sql($sql);
	
	// update the record
	$res_update = execute_db($sql, $conn);
	
	if ($update_type == "external"){
		
		$sql = "UPDATE ".$quote.$table_name.$quote." SET ".$quote.$ext_updated_field.$quote." = '1' WHERE ".$quote.$where_field.$quote." = '".$where_value."'";
		if ($use_limit_in_update === 1){
			$sql .= " LIMIT 1";
		} // end if

		display_sql($sql);
		
		// update the record
		$res_update = execute_db($sql, $conn);
	} // end if
} // end function update_record

function build_where_clause($_POST, $fields_labels_ar, $table_name)
// goal: build the where clause of a select sql statement e.g. "field_1 = 'value' AND field_2 LIKE '%value'"
// input: $_POST, $fields_labels_ar, $table_name
{
	global $quote;

	$where_clause = "";

	$count_temp = count($fields_labels_ar);
	// build the where clause
	for ($i=0; $i<$count_temp; $i++){
		$field_type_temp = $fields_labels_ar[$i]["type_field"];
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		$field_separator_temp = $fields_labels_ar[$i]["separator_field"];
		$field_select_type_temp = $fields_labels_ar[$i]["select_type_field"];

		if ($fields_labels_ar[$i]["present_search_form_field"] == "1"){
			switch ($field_type_temp){
				case "select_multiple":
				case "select_multiple_checkbox":
					if (isset($_POST[$field_name_temp])){
						$count_temp_2 = count($_POST[$field_name_temp]);
						for ($j=0; $j<$count_temp_2; $j++){ // for each possible check
							if ($_POST[$field_name_temp][$j] != ""){
								$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." LIKE '%".$field_separator_temp.$_POST[$field_name_temp][$j].$field_separator_temp."%'";
								$where_clause .= " AND ";
							} // end if
						} // end for
					} // end if
					break;
				case "date":
				case "insert_date":
				case "update_date":
					$select_type_field_name_temp = $field_name_temp."_select_type";
					if ($_POST[$select_type_field_name_temp] != ""){
						$year_field = $field_name_temp."_year";
						$month_field = $field_name_temp."_month";
						$day_field = $field_name_temp."_day";

						$mysql_date_value = $_POST[$year_field]."-".$_POST[$month_field]."-".$_POST[$day_field];

						switch ($_POST[$select_type_field_name_temp]){
							case "is_equal":
								$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." = '".$mysql_date_value."'";
								break;
							case "greater_than":
								$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." > '".$mysql_date_value."'";
								break;
							case "less_then":
								$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." < '".$mysql_date_value."'";
								break;
						} // end switch

						$where_clause .= " ".$_POST["operator"]." ";
					} // end if
					break;
				default:
					if ($_POST[$field_name_temp] != ""){ // if the user has filled the field
						$select_type_field_name_temp = $field_name_temp."_select_type";
						switch ($_POST[$select_type_field_name_temp]){
							case "is_equal":
								$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." = '".$_POST[$field_name_temp]."'";
								break;
							case "contains":
								$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." LIKE '%".$_POST[$field_name_temp]."%'";
								break;
							case "starts_with":
								$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." LIKE '".$_POST[$field_name_temp]."%'";
								break;
							case "ends_with":
								$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." LIKE '%".$_POST[$field_name_temp]."'";
								break;
							case "greater_than":
								$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." > '".$_POST[$field_name_temp]."'";
								break;
							case "less_then":
								$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." < '".$_POST[$field_name_temp]."'";
								break;
						} // end switch
						//} // end else
						$where_clause .= " ".$_POST["operator"]." ";
					} // end if
					break;
			} //end switch
		} // end if
	} // end for ($i=0; $i<count($fields_labels_ar); $i++)

	if ($where_clause !== '') {
		$where_clause = substr($where_clause, 0, -(strlen($_POST["operator" ])+2)); // delete the last " and " or " or "
	} // end if

	return $where_clause;
} // end function build_select_query

function get_field_correct_displaying($field_value, $field_type, $field_content, $field_separator, $display_mode)
// get the correct mode to display a field, according to its content (e.g. format data, display select multiple in different rows without separator and so on
// input: $field_value, $field_type, $field_content, $field_separator, $display_mode (results_table|details_page)
// output: $field_to_display, the field value ready to be displayed
// global: $word_wrap_col, the coloumn at which a string will be wrapped in the results
{
global $word_wrap_col, $enable_word_wrap_cut, $upload_relative_url;
$field_to_display = "";
switch ($field_type){
case "generic_file":
			if ( $field_value != '') {
				$field_to_display = "<a href=\"".$upload_relative_url.str_replace("%2F", "/", rawurlencode(substr($field_value, 18)))."\">".htmlspecialchars($field_value)."</a>";
			}
break;
case "image_file":
			if ( $field_value != '') 
			{

             $field_to_display = "<img alt=\"none\" src=\"".$upload_relative_url.str_replace("%2F", "/", rawurlencode(substr($field_value, 18)))."\" />";

			}
break;
case "select_multiple":
case "select_multiple_checkbox":
$field_value = htmlspecialchars($field_value);
$separator_first_check = stripos($field_value, $field_separator);
$separator_last_check = strripos($field_value, $field_separator);
if ($separator_first_check == "0" and $separator_last_check == (strlen($field_value)-1))
{$field_value = substr($field_value, 1, -1);} // delete the first and the last separator
$select_values_ar = explode($field_separator,$field_value);
$count_temp = count($select_values_ar);
for ($i=0; $i<$count_temp; $i++)
{
if ( $display_mode == "results_table") 
{
$field_to_display .= nl2br(wordwrap($select_values_ar[$i], $word_wrap_col))."<br />";
}
else
{
$field_to_display .= nl2br($select_values_ar[$i])."<br />";
}
} // end for
break;
case "select_single":
		    $field_value = htmlspecialchars($field_value);
			$separator_first_check = stripos($field_value, $field_separator);
			$separator_last_check = strripos($field_value, $field_separator);
			if ($separator_first_check == 0 and $separator_last_check == (strlen($field_value)-1))
			{$field_value = substr($field_value, 1, -1);} // delete the first and the last separator
			$field_to_display = $field_value;
			break;
		case "insert_date":
		case "update_date":
		case "date":
			$field_value = htmlspecialchars($field_value);
			if ($field_value != '0000-00-00 00:00:00' && $field_value != '0000-00-00'){
				$field_to_display = format_date($field_value);
			} // end if
			else{
				$field_to_display = "";
			} // end else
			break;
		case "rich_editor":
			$field_to_display = $field_value;
			break;
		default: // e.g. text, textarea and select sinlge
			if ($field_content !== 'html') {
				$field_value = htmlspecialchars($field_value);

				if ( $display_mode == "results_table") {
					$displayed_part = wordwrap($field_value, $word_wrap_col, "\n", $enable_word_wrap_cut);
				} // end if
				else{
					$displayed_part = $field_value;
				} // end else

			} // end if
			else {
				$displayed_part = $field_value;
			} // end else

			if ($field_content == "email"){
				$field_to_display = "<a href=\"mailto:".$field_value."\">".$displayed_part."</a>";
			} // end if
			elseif ($field_content == "url"){
				$field_to_display = "<a href=\"".$field_value."\">".$displayed_part."</a>";
			} // end elseif
			else {
				$field_to_display = nl2br($displayed_part);
			} // end else
			break;
	} // end switch
	return $field_to_display;
} // function get_field_correct_displaying

function get_field_correct_csv_displaying($field_value, $field_type, $field_content, $field_separator)
// get the correct mode to display a field in a csv, according to its content (e.g. format data, display select multiple in different rows without separator and so on
// input: $field_value, $field_type, $field_content, $field_separator
// output: $field_to_display, the field value ready to be displayed
{
	$field_to_display = "";
	switch ($field_type){
		case "select_multiple":
		case "select_multiple_checkbox":
			$separator_first_check = stripos($field_value, $field_separator);
            $separator_last_check = strripos($field_value, $field_separator);
            if ($separator_first_check == "0" and $separator_last_check == (strlen($field_value)-1))
            {$field_value = substr($field_value, 1, -1);} // delete the first and the last separator
			$select_values_ar = explode($field_separator,$field_value);
			$count_temp = count($select_values_ar);
			for ($i=0; $i<$count_temp; $i++){
				$field_to_display .= $select_values_ar[$i]."\n";
			} // end for
			break;
	    case "select_single":
	        $separator_first_check = stripos($field_value, $field_separator);
            $separator_last_check = strripos($field_value, $field_separator);
            if ($separator_first_check == "0" and $separator_last_check == (strlen($field_value)-1))
            {$field_value = substr($field_value, 1, -1);}
            $field_to_display = $field_value;
            break;
		case "insert_date":
		case "update_date":
		case "date":
			if ($field_value != '0000-00-00 00:00:00' && $field_value != '0000-00-00'){
				$field_to_display = format_date($field_value);
			} // end if
			else{
				$field_to_display = "";
			} // end else
			break;
		default:
			$field_to_display = str_replace("\r", '', $field_value);
	} // end switch
	return $field_to_display;
} // function get_field_correct_csv_displaying

function build_results_table($fields_labels_ar, $table_name, $res_records, $results_type, $name_mailing, $page, $action, $where_clause, $page, $order, $order_type)
{
global $submit_buttons_ar, $normal_messages_ar, $edit_target_window, $delete_icon, $edit_icon, $details_icon, $enable_edit, $enable_delete, $enable_details, $conn, $quote, $ask_confirmation_delete, $word_wrap_col, $word_wrap_fix_width, $alias_prefix, $dadabik_main_file, $dadabik_short_file, $popup_parameters;
if ($action == "mail.php"){
$function = "check_form";
} // end if
else{
$function = "search";
} // end elseif
$unique_field_name = get_unique_field($table_name);

	// build the results HTML table
	///////////////////////////////
$results_table = "";
$results_table .= "<table summary=\"none\" class=\"results\">";
$results_table .= "<tr>";
$results_table .= "<th class=\"results\">&nbsp;</td>"; // skip the first column for edit, delete and details
$count_temp = count($fields_labels_ar);
for ($i=0; $i<$count_temp; $i++){

if ($fields_labels_ar[$i]["present_results_search_field"] == "1"){ // the user want to display the field in the basic search results page
$label_to_display = $fields_labels_ar[$i]["label_field"];
if ($word_wrap_fix_width === 1){
$spaces_to_add = $word_wrap_col-strlen($label_to_display);
if ( $spaces_to_add > 0) {
for ($j=0; $j<$spaces_to_add; $j++) {
$label_to_display .= '&nbsp;';
}
}
} // end if
			
$results_table .= "<th class=\"results\">";

if ( $results_type == "search") {
if ($order != $fields_labels_ar[$i]["name_field"]){ // the results are not ordered by this field at the moment
$link_class="order_link";
$new_order_type = "ASC";
}
else{
$link_class="order_link_selected";
if ( $order_type == "DESC") {
$new_order_type = "ASC";
}
else{
$new_order_type = "DESC";
}
} // end else if
$results_table .= "<a class=\"".$link_class."\" href=\"".$action."?table_name=". urlencode($table_name)."&amp;function=".$function."&amp;where_clause=".urlencode($where_clause)."&amp;page=".$page."&amp;order=".urlencode($fields_labels_ar[$i]["name_field"])."&amp;order_type=".$new_order_type."\">".$label_to_display."</a></th>"; // insert the linked name of the field in the <th>
}
else{
$results_table .= $label_to_display."</th>"; // insert the  name of the field in the <th>
} // end if
} // end if
} // end for

$results_table .= "</tr>";
$td_results_class = 'results_1';
$td_controls_class = 'controls_1';
// build the table body
while ($records_row = fetch_row_db($res_records)){

		if ($td_results_class === 'results_1') {
			$td_results_class = 'results_2';
			$td_controls_class = 'controls_2';
		} // end if
		else {
			$td_results_class = 'results_1';
			$td_controls_class = 'controls_1';
		} // end else

		// set where clause for delete and update
		///////////////////////////////////////////
		if ($unique_field_name != ""){ // exists a unique number
			$where_field = $unique_field_name;
			$where_value = $records_row[$unique_field_name];
		} // end if
		///////////////////////////////////////////
		// end build where clause for delete and update

$results_table .= "<tr>";
$results_table .= "<td class=\"".$td_controls_class."\">";
if ($unique_field_name != "" and ($results_type == "search" or $results_type == "possible_duplication")){ // exists a unique number: edit, delete, details make sense

			if ($enable_edit == "1"){ // display the edit icon 
				$results_table .= "<a class=\"onlyscreen\" href=\"";
				if ($edit_target_window == 'blank') // popup
				{
				$results_table .= $dadabik_short_file;
				}
				else
				{
				$results_table .= $dadabik_main_file;
				}
				$results_table .= "?table_name=".urlencode($table_name)."&amp;function=edit&amp;where_field=".urlencode($where_field)."&amp;where_value=".urlencode($where_value)."\"";
				if ($edit_target_window == 'blank') // popup
				{
				$results_table .= " onclick=\"javascript:window.open('".$dadabik_short_file."?table_name=".urlencode($table_name)."&amp;function=edit&amp;where_field=".urlencode($where_field)."&amp;where_value=".urlencode($where_value)."', 'new','".$popup_parameters."'); return false;\"";
				}
				$results_table .= "><img style=\"border:0;\" src=\"".$edit_icon."\" alt=\"".$submit_buttons_ar["edit"]."\" title=\"".$submit_buttons_ar["edit"]."\" /></a>";
			} // end if

			if ($enable_delete == "1"){ // display the delete icon
				$results_table .= "<a class=\"onlyscreen\"";
				if ( $ask_confirmation_delete == 1 and $edit_target_window == 'self') {
					$results_table .= " onclick=\"if (!confirm('".str_replace('\'', '\\\'', $normal_messages_ar['confirm_delete?'])."')){ return false;}\"";
				}
				$results_table .= " href=\"";
				if ($edit_target_window == 'blank') // popup
				{
				$results_table .= $dadabik_short_file;
				}
				else
				{
				$results_table .= $dadabik_main_file;
				}
				$results_table .=
				"?table_name=".urlencode($table_name)."&amp;function=delete";

				if($results_type == "search") {
				$results_table .= "&amp;where_clause=".urlencode($where_clause)."&amp;page=".$page."&amp;order=".urlencode($order)."&amp;order_type=".$order_type;
				}
				$results_table .= "&amp;where_field=".urlencode($where_field)."&amp;where_value=".urlencode($where_value)."\"";
				
				if ($edit_target_window == 'blank') // popup
				{
				$results_table .= " onclick=\"javascript:return confipop('".$dadabik_short_file."?table_name=".urlencode($table_name)."&amp;function=delete";
					if($results_type == "search") {
					$results_table .= "&amp;where_clause=".urlencode($where_clause)."&amp;page=".$page."&amp;order=".urlencode($order)."&amp;order_type=".$order_type;
					}
				$results_table .= "&amp;where_field=".urlencode($where_field)."&amp;where_value=".urlencode($where_value)."')\"";
				}
				
				$results_table .="><img style=\"border:0;\" src=\"".$delete_icon."\" alt=\"".$submit_buttons_ar["delete"]."\" title=\"".$submit_buttons_ar["delete"]."\" /></a>";
			} // end if

			if ($enable_details == "1"){ // display the details icon
				$results_table .= "<a class=\"onlyscreen\" href=\"";
				if ($edit_target_window == 'blank') // popup
				{
				$results_table .= $dadabik_short_file;
				}
				else
				{
				$results_table .= $dadabik_main_file;
				}
				$results_table .= "?table_name=".urlencode($table_name)."&amp;function=details&amp;where_field=".urlencode($where_field)."&amp;where_value=".urlencode($where_value)."\"";
				if ($edit_target_window == 'blank') // popup
				{
				$results_table .= " onclick=\"javascript:window.open('".$dadabik_short_file."?table_name=".urlencode($table_name)."&amp;function=details&amp;where_field=".urlencode($where_field)."&amp;where_value=".urlencode($where_value)."', 'new','".$popup_parameters."'); return false;\"";
				}
				$results_table .= "><img style=\"border:0;\" src=\"".$details_icon."\" alt=\"".$submit_buttons_ar["details"]."\" title=\"".$submit_buttons_ar["details"]."\" /></a>";
			} // end if

		} // end if
$results_table .= "</td>";

// start cells (2nd cell onwards) showing values
for ($i=0; $i<$count_temp; $i++)
{
// the user want to display the field in the search results page
if ($fields_labels_ar[$i]["present_results_search_field"] == "1")
{
 $results_table .= "<td class=\"".$td_results_class."\">"; // start the cell
 $field_name_temp = $fields_labels_ar[$i]["name_field"];
 $field_type = $fields_labels_ar[$i]["type_field"];
 $field_content = $fields_labels_ar[$i]["content_field"];
 $field_separator = $fields_labels_ar[$i]["separator_field"];
 $field_value = $records_row[$field_name_temp];
 $field_to_display = get_field_correct_displaying($field_value, $field_type, $field_content, $field_separator, "results_table");
 if ( $field_to_display == "")
  {$field_to_display .= "&nbsp;";}
 $results_table .= $field_to_display."&nbsp;";
 $results_table = substr($results_table, 0, -6); // delete the last &nbsp;
 $results_table .= "</td>"; // end the cell
} // end if the user want to display the field
} // end for - start cells (2nd cell onwards) showing values
$results_table .= "</tr>";
} // end while
$results_table .= "</table>";
return $results_table;
} // end function build_results_table

function build_csv($res_records, $fields_labels_ar)
// build a csv, starting from a recordset
// input: $res_record, the recordset, $fields_labels_ar
{
	global $csv_separator, $alias_prefix;
	$csv = "";
	$count_temp = count($fields_labels_ar);

	// write heading
	for ($i=0; $i<$count_temp; $i++) {
		if ( $fields_labels_ar[$i]["present_results_search_field"] == "1") {
			$csv .= "\"".str_replace("\"", "\"\"", $fields_labels_ar[$i]["label_field"])."\"".$csv_separator;
		}
	}
	$csv = substr($csv, 0, -1); // delete the last ","
	$csv .= "\n";

	// write other rows
	while ($records_row = fetch_row_db($res_records)) {
		for ($i=0; $i<$count_temp; $i++) {
			if ( $fields_labels_ar[$i]["present_results_search_field"] == "1") {

				$field_name_temp = $fields_labels_ar[$i]["name_field"];
				$field_type = $fields_labels_ar[$i]["type_field"];
				$field_content = $fields_labels_ar[$i]["content_field"];
				$field_separator = $fields_labels_ar[$i]["separator_field"];
	
				$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop I have the previous values

				$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
				if ($primary_key_field_field != ""){
					
					$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
					$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
					$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
					$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
					$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];

					for ($j=0;$j<count($linked_fields_ar);$j++) {
						$field_values_ar[$j] .= $records_row[$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
					} // end for


					//$field_values_ar = $linked_field_values_ar;
				}
				else{
					$field_values_ar[0] = $records_row[$field_name_temp];
				}
				$csv .= "\"";

				$count_temp_2 = count($field_values_ar);
				for ($j=0; $j<$count_temp_2; $j++) {
					
					$field_to_display = get_field_correct_csv_displaying($field_values_ar[$j], $field_type, $field_content, $field_separator);

					$csv .= str_replace("\"", "\"\"", $field_to_display)." ";
				}
				$csv = substr($csv, 0, -1); // delete the last space
			$csv .= "\"".$csv_separator;
			}
		} // end for
		$csv = substr($csv, 0, -1); // delete the last ","
		$csv .= "\n";
	}
	return $csv;
} // end function build_csv

function build_details_table($fields_labels_ar, $res_details)
// goal: build an html table with details of a record
// input: $fields_labels_ar $res_details (the result of the query)
// ouptut: $details_table, the html table
{
	global $conn, $quote, $alias_prefix;

	// build the table
	$details_table = "";

	$details_table .= "<table summary=\"none\">";

	while ($details_row = fetch_row_db($res_details)){ // should be just one

		$count_temp = count($fields_labels_ar);
		for ($i=0; $i<$count_temp; $i++){
			if ($fields_labels_ar[$i]["present_details_form_field"] == "1"){
				$field_name_temp = $fields_labels_ar[$i]["name_field"];
                $field_value = $details_row[$field_name_temp];
				$details_table .= "<tr><td class=\"td_label_details\"><b>".$fields_labels_ar[$i]["label_field"]."</b></td><td class=\"td_value_details\">";
				$field_to_display = get_field_correct_displaying($field_value, $fields_labels_ar[$i]["type_field"], $fields_labels_ar[$i]["content_field"], $fields_labels_ar[$i]["separator_field"], "details_table"); // get the correct display mode for the field
                $details_table .= $field_to_display."&nbsp;"; 
				$details_table = substr($details_table, 0, -6); // delete the last &nbsp;
				$details_table .= "</td></tr>";

			} // end if
		} // end for
	} // end while

	$details_table .= "</table>";

	return $details_table;
} // end function build_details_table

function build_navigation_tool($where_clause, $pages_number, $page, $action, $name_mailing, $order, $order_type)
// goal: build a set of link to go forward and back in the result pages
// input: $where_clause, $pages_number (total number of pages), $page (the current page 0....n), $action, the action page (e.g. index.php or mail.php), $name_mailing, the name of the current mailing, $order, the field used to order the results
// output: $navigation_tool, the html navigation tool
{
	global $table_name, $quote;

	if ($action == "mail.php"){
		$function = "check_form";
	} // end if
	else{
		$function = "search";
	} // end elseif
	$navigation_tool = "";

	$page_group = (int)($page/10); // which group? (from 0......n) e.g. page 12 is in the page_group 1 
	$total_groups = ((int)(($pages_number-1)/10))+1; // how many groups? e.g. with 32 pages 4 groups
	$start_page = $page_group*10; // the navigation tool start with $start_page, end with $end_page
	if ($start_page+10 > $pages_number){
		$end_page = $pages_number;
	} // end if
	else{
		$end_page = $start_page+10;
	} // end else
	
	if ($page_group > 1){
		$navigation_tool .= "<a class=\"navig\" href=\"".$action."?&amp;table_name=". urlencode($table_name)."&amp;function=".$function."&amp;where_clause=".urlencode($where_clause)."&amp;page=0&amp;order=".urlencode($order)."&amp;order_type=".urlencode($order_type)."\" title=\"1\">&lt;&lt;</a> ";
	} // end if
	if ($page_group > 0){
		$navigation_tool .= "<a class=\"navig\" href=\"".$action."?&amp;table_name=". urlencode($table_name)."&amp;function=".$function."&amp;where_clause=".urlencode($where_clause)."&amp;page=".((($page_group-1)*10)+9)."&amp;order=".urlencode($order)."&amp;order_type=".urlencode($order_type)."\" title=\"".((($page_group-1)*10)+10)."\">&lt;</a> ";
	} // end if

	for($i=$start_page; $i<$end_page; $i++){
		if ($i != $page){
			$navigation_tool .= "<a class=\"navig\" href=\"".$action."?&amp;table_name=". urlencode($table_name)."&amp;function=".$function."&amp;where_clause=".urlencode($where_clause)."&amp;page=".$i."&amp;order=".urlencode($order)."&amp;order_type=".urlencode($order_type)."\">".($i+1)."</a> ";
		} // end if
		else{
			$navigation_tool .= "<span class=\"navig\">".($i+1)."</span> ";
		} //end else
	} // end for

	if(($page_group+1) < ($total_groups)){
		$navigation_tool .= "<a class=\"navig\" href=\"".$action."?&amp;table_name=". urlencode($table_name)."&amp;function=".$function."&amp;where_clause=".urlencode($where_clause)."&amp;page=".(($page_group+1)*10)."&amp;order=".urlencode($order)."&amp;order_type=".urlencode($order_type)."\" title=\"".((($page_group+1)*10)+1)."\">&gt;</a> ";
	} // end elseif
	if (($page_group+1) < ($total_groups-1)){
		$navigation_tool .= "<a class=\"navig\" href=\"".$action."?&amp;table_name=". urlencode($table_name)."&amp;function=".$function."&amp;where_clause=".urlencode($where_clause)."&amp;page=".($pages_number-1)."&amp;order=".urlencode($order)."&amp;order_type=".urlencode($order_type)."\" title=\"".$pages_number."\">&gt;&gt;</a> ";
	} // end if
	return $navigation_tool;
} // end function build_navigation_tool

function delete_files_with_record ($table_name, $where_field, $where_value)
// goal: delete files uploaded with the one record being deleted
{

    global $conn, $quote, $upload_directory;
	$sql = "SELECT * FROM ".$quote.$table_name.$quote." WHERE ".$quote.$where_field.$quote." = '".$where_value."' LIMIT 1";
	display_sql($sql);
	$row = mysql_fetch_row (execute_db($sql, $conn));
	foreach ($row as $key => $value)
	{
	 if ($value !== '' and strlen($value) > 19)
	 {
	 // the 18 is because of the text tag - The filepath is - - that acts as an identifier that a file has been uploaded
	 $tag = substr($value,0,18);
	 $file_location = strip_slashes(substr($value,18));
	 if ($tag === 'The filepath is - ')
	  {
	  $files_to_delete[] = $file_location;
	  }
	 }
	}
	if (isset($files_to_delete) and count($files_to_delete) > 0){
	foreach ($files_to_delete as $key => $value)
	{
	if (file_exists($upload_directory.$value))
	 {
	 unlink ($upload_directory.$value);
	 }
	}}

} // end function delete_files_with_record

function delete_record ($table_name, $where_field, $where_value)
// goal: delete one record
{
	global $conn, $quote;
	

	$sql = "DELETE FROM ".$quote.$table_name.$quote." WHERE ".$quote.$where_field.$quote." = '".$where_value."' LIMIT 1";
	display_sql($sql);

	// execute the select query
	$res_contacts = execute_db($sql, $conn);

} // end function delete_record


function delete_multiple_records ($table_name, $where_clause, $ID_user_field_name)
// goal: delete a group of record according to a where clause
// input: $table_name, $where_clause, $ID_user_field_name (if it is not false, delete only the records that the current user owns
// how - scan for files to delete first, then delete mysql
{
global $conn, $quote, $current_user, $enable_authentication, $enable_delete_authorization, $upload_directory;
if ($enable_authentication === 1 && $enable_delete_authorization === 1 && $ID_user_field_name !== false) { // check also the user
		if ($where_clause !== '') {
			$where_clause .= ' AND ';
		} // end if
		$where_clause .= $quote.$ID_user_field_name.$quote." = '".$current_user."'";
} // end if
// get files to delete
$sql = '';
$sql = "SELECT * FROM ".$quote.$table_name.$quote;
if ($where_clause !== ''){ 
$sql .= " WHERE ".$where_clause;} // end if
display_sql($sql);
$files_to_delete_with_records = array();
$results = execute_db($sql, $conn);
$numofrows = mysql_num_rows ($results);
for ($i = 0; $i < $numofrows; $i++)
{
$row = mysql_fetch_array($results);
foreach ($row as $key => $value)
 {
 if (strlen($value) > 19)
  {$tag = substr($value,0,18);
   $file_location = strip_slashes(substr($value,18));
   if ($tag == "The filepath is - ")
   {$files_to_delete_with_records[] = $file_location;}
  } // end if
 } // end foreach
} // end for
// delete mysql

	$sql = '';
	$sql .= "DELETE FROM ".$quote.$table_name.$quote;
	if ($where_clause !== '') {
		$sql .= " WHERE ".$where_clause;
	} // end if
	display_sql($sql);

	// execute the select query
	$res_contacts = execute_db($sql, $conn);
// delete the files
foreach ($files_to_delete_with_records as $key => $value)
{
	if (file_exists($upload_directory.$value))
	 {
	 unlink ($upload_directory.$value);
	 }
}
} // end function delete_multiple_records

function required_field_present($fields_labels_ar)
// goal: check if there are at least one required field
// input: $fields_labels_ar
// output: true or false
{
	
	$i=0;
	$found = 0;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp && $found == 0) {
		if ( $fields_labels_ar[$i]["required_field"] == "1") {
			$found = 1;
		}
		$i++;
	}
	if ( $found == 1 ){
		return true;
	}
	else{
		return false;
	}
} // end function required_field_present

function create_internal_table($table_internal_name)
// goal: drop (if present) the old internal table and create the new one.
// input: $table_internal_name
{
	global $conn, $quote;

	// drop the old table
	$sql = "DROP TABLE IF EXISTS ".$quote.$table_internal_name.$quote;
	$res_table = execute_db($sql, $conn);

	// create the new one
	$sql ="CREATE TABLE ".$quote.$table_internal_name.$quote." (
	name_field varchar(50) NOT NULL default '',
	label_field varchar(255) NOT NULL default '',
	type_field ENUM('text','textarea','rich_editor','password','insert_date','update_date','date','select_single','select_multiple','select_multiple_checkbox','generic_file','image_file','ID_user','unique_ID') NOT NULL default 'text',
	content_field ENUM('alphabetic','alphanumeric','numeric','url','email','html','phone') NOT NULL DEFAULT 'alphanumeric',
	present_search_form_field ENUM('0','1') DEFAULT '1' NOT NULL,
	present_results_search_field ENUM('0','1') DEFAULT '1' NOT NULL,
	present_details_form_field ENUM('0','1') DEFAULT '1' NOT NULL,
	present_insert_form_field ENUM('0','1') DEFAULT '1' NOT NULL,
	present_ext_update_form_field ENUM('0','1') DEFAULT '1' NOT NULL,
	required_field ENUM('0','1') DEFAULT '0' NOT NULL,
	check_duplicated_insert_field ENUM('0','1') DEFAULT '0' NOT NULL,
	other_choices_field ENUM ('0','1') DEFAULT '0' NOT NULL,
	select_options_field text NOT NULL default '',
	primary_key_field_field VARCHAR(255) NOT NULL,
	primary_key_table_field VARCHAR(255) NOT NULL,
	primary_key_db_field VARCHAR(50) NOT NULL,
	linked_fields_field TEXT NOT NULL,
	linked_fields_order_by_field TEXT NOT NULL,
	linked_fields_order_type_field VARCHAR(255) NOT NULL,
	linked_fields_extra_mysql VARCHAR(255) NOT NULL,
	select_type_field varchar(100) NOT NULL default 'is_equal/contains/starts_with/ends_with/greater_than/less_then',
	prefix_field TEXT NOT NULL default '',
	default_value_field TEXT NOT NULL default '',
	width_field VARCHAR(5) NOT NULL,
	height_field VARCHAR(5) NOT NULL,
	maxlength_field VARCHAR(5) NOT NULL default '100',
	hint_insert_field VARCHAR(255) NOT NULL,
	order_form_field smallint(6) NOT NULL,
	separator_field varchar(2) NOT NULL default '~',
	PRIMARY KEY  (name_field)
	) TYPE=MyISAM
	";
	$res_table = execute_db($sql, $conn);
} // end function create_internal_table

function create_table_list_table()
// goal: drop (if present) the old table list and create the new one.
{
	global $conn, $table_list_name, $users_table_name, $quote;

	// drop the old table
	$sql = "DROP TABLE IF EXISTS ".$quote.$table_list_name.$quote;
	$res_table = execute_db($sql, $conn);

	// create the new one
	$sql ="CREATE TABLE ".$quote.$table_list_name.$quote." (
	name_table varchar(255) NOT NULL default '',
	allowed_table tinyint(4) NOT NULL default '0',
	enable_insert_table varchar(5) NOT NULL default '',
	enable_edit_table varchar(5) NOT NULL default '',
	enable_delete_table varchar(5) NOT NULL default '',
	enable_details_table varchar(5) NOT NULL default '',
	PRIMARY KEY  (name_table)
	) TYPE=MyISAM
	";
	$res_table = execute_db($sql, $conn);

} // end function create_table_list_table

function create_users_table()
// goal: drop (if present) the old users table and create the new one.
{
	global $conn, $users_table_name, $quote;

	// drop the old table
	$sql = "DROP TABLE IF EXISTS ".$quote.$users_table_name.$quote;
	$res_table = execute_db($sql, $conn);

	// create the new one
	$sql ="CREATE TABLE ".$quote.$users_table_name.$quote." (
	ID_user int(10) unsigned NOT NULL auto_increment,
	user_type_user varchar(50) NOT NULL,
	username_user varchar(50) NOT NULL,
	password_user varchar(32) NOT NULL,
	PRIMARY KEY  (ID_user),
	UNIQUE (username_user)
	) TYPE=InnoDB
	";
	$res_table = execute_db($sql, $conn);

	$sql = "INSERT INTO ".$quote.$users_table_name.$quote." (user_type_user, username_user, password_user) VALUES ('admin', 'root', '".md5('letizia')."')";

	$res_table = execute_db($sql, $conn);


} // end function create_users_table

function table_allowed($table_name)
// goal: check if a table is allowed to be managed by DaDaBIK
// input: $table_name
// output: true or false
{
	global $conn, $table_list_name, $quote;
	if (table_exists($table_list_name)){
		$sql = "SELECT ".$quote."allowed_table".$quote." FROM ".$quote.$table_list_name.$quote." WHERE ".$quote."name_table".$quote." = '".$table_name."'";
		$res_allowed = execute_db($sql, $conn);
		if (get_num_rows_db($res_allowed) == 1){
			$row_allowed = fetch_row_db($res_allowed);
			$allowed_table = $row_allowed[0];
			if ($allowed_table == "0"){
				return false;
			} // end if
			else{
				return true;
			} // end else
		} // end if
		elseif (get_num_rows_db($res_allowed) == 0){ // e.g. I have an empty table or the table is not installed
			return false;
		} // end elseif
		else{
			exit;
		} // end else
	} // end if
	else{
		return false;
	} // end else
} // end function table_allowed()

function build_enabled_features_ar($table_name)
// goal: build an array containing "0" or "1" according to the fact that a feature (insert, edit, delete, details) is enabled or not
// input: $table_name
// output: $enabled_features_ar, the array
{
	global $conn, $table_list_name, $quote;
	$sql = "SELECT ".$quote."enable_insert_table".$quote.", ".$quote."enable_edit_table".$quote.", ".$quote."enable_delete_table".$quote.", ".$quote."enable_details_table".$quote." FROM ".$quote.$table_list_name.$quote." WHERE ".$quote."name_table".$quote." = '".$table_name."'";
	$res_enable = execute_db($sql, $conn);
	if (get_num_rows_db($res_enable) == 1){
		$row_enable = fetch_row_db($res_enable);
		$enabled_features_ar["insert"] = $row_enable["enable_insert_table"];
		$enabled_features_ar["edit"] = $row_enable["enable_edit_table"];
		$enabled_features_ar["delete"] = $row_enable["enable_delete_table"];
		$enabled_features_ar["details"] = $row_enable["enable_details_table"];

		return $enabled_features_ar;
	} // end if
	else{
		db_error($sql);
	} // end else
} // end function build_enabled_features_ar($table_name)

function build_enable_features_checkboxes($table_name)
// goal: build the form that enable features
// input: name of the current table
// output: the html for the checkboxes
{
	$enabled_features_ar = build_enabled_features_ar($table_name);

	$enable_features_checkboxes = "";
	$enable_features_checkboxes .= "<input type=\"checkbox\" name=\"enable_insert\" id=\"enable_insert\" value=\"1\"";
	$enable_features_checkboxes .= "";
	if ($enabled_features_ar["insert"] == "1"){
		$enable_features_checkboxes .= "checked=\"checked\"";
	} // end if
	$enable_features_checkboxes .= ">Insert ";
	$enable_features_checkboxes .= "<input type=\"checkbox\" name=\"enable_edit\" id=\"enable_edit\" value=\"1\"";
	if ($enabled_features_ar["edit"] == "1"){
		$enable_features_checkboxes .= "checked=\"checked\"";
	} // end if
	$enable_features_checkboxes .= ">Edit ";
	$enable_features_checkboxes .= "<input type=\"checkbox\" name=\"enable_delete\" id=\"enable_delete\" value=\"1\"";
	if ($enabled_features_ar["delete"] == "1"){
		$enable_features_checkboxes .= "checked=\"checked\"";
	} // end if
	$enable_features_checkboxes .= ">Delete ";
	$enable_features_checkboxes .= "<input type=\"checkbox\" name=\"enable_details\" id=\"enable_details\" value=\"1\"";
	if ($enabled_features_ar["details"] == "1"){
		$enable_features_checkboxes .= "checked=\"checked\"";
	} // end if
	$enable_features_checkboxes .= " />Details ";

	return $enable_features_checkboxes;
} // end function build_enable_features_checkboxes

function build_change_field_select($fields_labels_ar, $field_position)
// goal: build an html select with all the field names
// input: $fields_labels_ar, $field_position (the current selected option)
// output: the select
{
	global $conn, $table_name;

	$change_field_select = "";
	$change_field_select .= "<select name=\"field_position\" id=\"field_position\">";
	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){
		$change_field_select .= "<option value=\"".$i."\"";
		if ($i == $field_position){
			$change_field_select .= " selected=\"selected\"";
		} // end if
		$change_field_select .= ">".$fields_labels_ar[$i]["name_field"]."</option>";
	} // end for
	$change_field_select .= "</select>";

	return $change_field_select;
} // end function build_change_field_select

function build_int_table_field_form($field_position, $int_fields_ar, $fields_labels_ar)
// goal: build a part of the internal table manager form relative to one field
// input: $field_position, the position of the field in the internal form, $int_field_ar, the array of the field of the internal table (with labels and properties), $fields_labels_ar, the array containing the fields labels and other information about fields
// output: the html form part
{
	$int_table_form = "";
	$int_table_form .= "<table summary=\"none\" border=\"0\" cellpadding=\"6\"><tr style=\"background-color:#F0F0F0;\"><td><table>";
	$count_temp = count($int_fields_ar);
	for ($i=0; $i<$count_temp; $i++){
		$int_table_form .= "<tr>";
		$int_field_name_temp = $int_fields_ar[$i][1];
		$int_table_form .= "<td>".$int_fields_ar[$i][0]."</td><td>";
		if ($i==0){ // it's the name of the field, no edit needed, just show the name
			$int_table_form .= $fields_labels_ar[$field_position][$int_field_name_temp];
		} // end if
		else{
			switch ($int_fields_ar[$i][2]){
				case "text":
					$int_table_form .= "<input type=\"text\" name=\"".$int_field_name_temp."_".$field_position."\" id=\"".$int_field_name_temp."_".$field_position."\" value=\"".$fields_labels_ar[$field_position][$int_field_name_temp]."\" size=\"".$int_fields_ar[$i][3]."\" />";
					break;
				case "select_yn":
					$int_table_form .= "<select name=\"".$int_field_name_temp."_".$field_position."\" id=\"".$int_field_name_temp."_".$field_position."\">";
					$int_table_form .= "<option value=\"1\"";
					if ($fields_labels_ar[$field_position][$int_field_name_temp] == "1"){
						$int_table_form .= " selected=\"selected\"";
					} // end if	
					$int_table_form .= ">Y</option>";
					$int_table_form .= "<option value=\"0\"";
					if ($fields_labels_ar[$field_position][$int_field_name_temp] == "0"){
						$int_table_form .= " selected=\"selected\"";
					} // end if	
					$int_table_form .= ">N</option>";
					$int_table_form .= "</select>";
					break;
				case "select_custom":
					$int_table_form .= "<select name=\"".$int_field_name_temp."_".$field_position."\" id=\"".$int_field_name_temp."_".$field_position."\">";
					$temp_ar = explode("/", $int_fields_ar[$i][3]);
					$count_temp_2 = count($temp_ar);
					for ($j=0; $j<$count_temp_2; $j++){
						$int_table_form .= "<option value=\"".$temp_ar[$j]."\"";
						if ($fields_labels_ar[$field_position][$int_field_name_temp] == $temp_ar[$j]){
							$int_table_form .= " selected=\"selected\"";
						} // end if
						$int_table_form .= ">".$temp_ar[$j]."</option>";
					} // end for
					$int_table_form .= "</select>";
					break;
			} // end switch
		} // end else
		$int_table_form .= "</td>";
		$int_table_form .= "</tr>"; // end of the row
	} // end for
	$int_table_form .= "</table></td></tr></table>"; // end of the row

	return $int_table_form;
} // end function build_int_table_field_form($field_position, $int_fields_ar, $fields_labels_ar)

function get_valid_name_uploaded_file ($file_name, $check_temp_files)
{
// goal: get a valid name (not already existant) for an uploaded file, e.g. if I upload a file with the name file.txt, and a file with the same name already exists, return file_2.txt, or file_3.txt.....; if .$check_temp_files is 1 check also the dadabik_tmp_file_ corresponding file names
// input: $file_name, $check_temp_files
// a valid name

	global $upload_directory, $table_name;
// START make table-specific upload directory
	$upload_directory2 = $upload_directory.$table_name.'/';
	if (!file_exists($upload_directory2)){mkdir($upload_directory2);}
	if (file_exists($upload_directory2) and !is_dir($upload_directory2)){unlink($upload_directory2); mkdir($upload_directory2);}
	$uploaded_file_names_count = 0;
// END make table-specific upload directory
	$valid_file_name = $file_name;
	$valid_name_found = 0;

	$dot_position = strpos($file_name, '.');

	$i = 2;
	do{
		if ( file_exists($upload_directory2.$valid_file_name) || file_exists($upload_directory2.'dadabik_tmp_file_'.$valid_file_name) && $check_temp_files === 1) { // a file with the same name is already present or a temporary file that will get the same name when the insert/update function will be executed is already present (and I need to check temp files)
			if ($dot_position === false) { // the file doesn't have an exension
				$valid_file_name = $file_name.'_'.$i; // from pluto to pluto_2
			}
			else{
				$valid_file_name = substr($file_name, 0, $dot_position).'_'.$i.substr($file_name, $dot_position); // from pluto.txt to pluto_2.txt
			}
			$i++;
		} // end if
		else{
			$valid_name_found = 1;
		}
	} while ( $valid_name_found==0 );

	return $valid_file_name;

} // end function get_valid_name_uploaded_file ($file_name)

function insert_other_field($primary_key_db, $primary_key_table, $field_name, $field_value_other)
// goal: insert in the primary key table the other.... field
// input: $primary_key_table, $primary_key_db, $linked_fields, $field_value_other
// outpu: the ID of the record inserted
{
	global $conn, $quote;

	if (!table_contains($primary_key_db, $primary_key_table, $field_name, $field_value_other)){ // check if the table doesn't contains the value inserted as other

		$sql_insert_other = "INSERT INTO ".$quote.$primary_key_table.$quote." (".$quote.$field_name.$quote.") VALUES ('".$field_value_other."')";

		display_sql($sql_insert_other);

		if ($primary_key_db!="") {
			select_db($primary_key_db, $conn);
		}
		
		// insert into the table of other
		$res_insert = execute_db($sql_insert_other, $conn);

		// reselect the old db
		if ($primary_key_db!="") {
			select_db($primary_key_db, $conn);
		}

		return get_last_ID_db();
	} // end if
} // end function insert_other_field($foreign_key, $field_value_other)

function udpate_options($fields_labels_ar_i, $field_name, $field_value_other)
// goal: upate the options of a field when a user select other....
// input: $fields_labels_ar_i (fields_labels_ar specific for a field), $field_name, $field_value_other
{
	global $conn, $quote, $table_internal_name;
	$select_options_field_updated = add_slashes($fields_labels_ar_i["select_options_field"].strip_slashes($field_value_other).$fields_labels_ar_i["separator_field"]);

	$sql_update_other = "UPDATE ".$quote.$table_internal_name.$quote." SET ".$quote."select_options_field".$quote." = '".$select_options_field_updated."' WHERE ".$quote."name_field".$quote." = '".$field_name."'";
	display_sql($sql_update_other);

	// update the internal table
	$res_update = execute_db($sql_update_other, $conn);
} // end function udpate_options($fields_labels_ar_i, $field_name, $field_value_other)

////////////////////////////////////////////////////////////////////////////////
function build_linked_field_values_ar($field_value, $primary_key_field_field, $primary_key_table_field, $primary_key_db_field, $linked_fields_ar)
// goal: build the array containing the linked field values starting from a field value
// input: $primary_key_field_field, $primary_key_table_field, $primary_key_db_field, $linked_fields_ar
// output: linked_field_values_ar
{
	global $conn, $quote;

	$sql = "SELECT ";

	$count_temp = count($linked_fields_ar);
	for ($i=0; $i<$count_temp; $i++) {
		$sql .= $quote.$linked_fields_ar[$i].$quote.", ";
	} // end for
	$sql = substr($sql, 0, -2); // delete the last ", "
	$sql .= " FROM ".$quote.$primary_key_table_field.$quote." WHERE ".$quote.$primary_key_field_field.$quote." = '".$field_value."'";

	// execute the select query
	$res_linked_fields = execute_db($sql, $conn);
	
	$row_linked_fields = fetch_row_db($res_linked_fields);

	$count_temp = num_fields_db($res_linked_fields);
	for ($i=0; $i<$count_temp; $i++){
		$linked_field_values_ar[] = $row_linked_fields[$i];
	} // end for

	return $linked_field_values_ar;
} // end function build_linked_field_values_ar()


function build_select_part($fields_labels_ar, $table_name)
// goal: build the select part of a search query e.g.SELECT table_1.field_1, table_2.field2 from table_1 LEFT JOIN table_2 ON table_1.field_3 = table2.field_3
// input: $fields_labels_ar, $table_name
// output: the query
{
	global $quote, $alias_prefix;

	// get the primary key
	$unique_field_name = get_unique_field($table_name);

	$sql_fields_part = '';
	$sql_from_part = '';

	foreach($fields_labels_ar as $field){
		if ($field['present_results_search_field'] === '1' || $field['present_details_form_field'] === '1' || $field['name_field'] === $unique_field_name) { // include in the select stataments just the fields present in results or the primary key (useful to pass to the edit form)
         $sql_fields_part .= $quote.$table_name.$quote.'.'.$quote.$field['name_field'].$quote.', ';
		} // end if
	} // end foreach

	$sql_fields_part = substr($sql_fields_part, 0, -2); // delete the last ', '

	// compose the final statement
	$sql = "SELECT ".$sql_fields_part." FROM ".$quote.$table_name.$quote.$sql_from_part ;
	
	return $sql;
} // end function build_select_part()
?>