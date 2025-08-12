<?php
// include config, functions, common and header
include ("../config.php");
include ("functions.php");
include ("common_start.php");
include ("header_admin.php");

// variables:
// GET
// table_name form admin.php
if (isset($_GET["table_name"])){
	$table_name = urldecode($_GET["table_name"]);
} // end if
else{
	$table_name = "";
} // end else

// POST
// $install from install.php (set to 1 when user click on install)

if (isset($_POST["install"])){
	$install = $_POST["install"];
} // end if
else{
	$install = "";
} // end else


if ($install == "1"){
	if ($table_name != ""){
		$tables_names_ar[0] = $table_name;
		
		if (!table_exists($table_list_name)){
			// drop (if present) the old table list table and create the new one
			create_table_list_table();
		} // end if
	} // end if
	else{
		// drop (if present) the old users table and create the new one
		create_users_table();

		// get the array containing the names of the tables (excluding "dadabik_" ones)
		$tables_names_ar = build_tables_names_array(0, 0, 1);

		// drop (if present) the old table list table and create the new one
		create_table_list_table();
	} // end else

	for ($i=0; $i<count($tables_names_ar); $i++){
		$table_name_temp = $tables_names_ar[$i];
		$table_internal_name_temp = $prefix_internal_table.$table_name_temp;

		$unique_field_name = get_unique_field($table_name_temp);

		// get the array containing the names of the fields
		$fields_names_ar = build_fields_names_array($table_name_temp);

		// drop (if present) the old internal table and create the new one.
		create_internal_table ($table_internal_name_temp);

		// delete the previous record about the table
		$sql = "delete from ".$quote.$table_list_name.$quote." where ".$quote."name_table".$quote." = '".$table_name_temp."'";			
		$res_delete = execute_db($sql, $conn);

		// add the table to the table list table and set allowed to 1
		$sql = "insert into ".$quote.$table_list_name.$quote." (".$quote."name_table".$quote.", ".$quote."allowed_table".$quote.", ".$quote."enable_insert_table".$quote.", ".$quote."enable_edit_table".$quote.", ".$quote."enable_delete_table".$quote.", ".$quote."enable_details_table".$quote.") values ('".$table_name_temp."', '1', '1', '1', '1', '1')";
		$res_insert = execute_db($sql, $conn);

		if ($table_name_temp === $users_table_name) {
			$sql = "INSERT INTO ".$quote.$table_internal_name_temp.$quote." VALUES ('ID_user', 'ID_user', 'text', 'alphanumeric', '0', '0', '0', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 1, '~')";
			$res_insert = execute_db($sql, $conn);
			
			$sql = "INSERT INTO ".$quote.$table_internal_name_temp.$quote." VALUES ('user_type_user', 'User type', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '0', '~admin~normal~', '', '', '', '', '', '',  '','is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 2, '~')";
			$res_insert = execute_db($sql, $conn);

			$sql = "INSERT INTO ".$quote.$table_internal_name_temp.$quote." VALUES ('username_user', 'Username', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '0', '', '', '', '', '', '', '',  '','is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 3, '~')";
			$res_insert = execute_db($sql, $conn);

			$sql = "INSERT INTO ".$quote.$table_internal_name_temp.$quote." VALUES ('password_user', 'Password (md5 hash)', 'text', 'alphanumeric', '0', '0', '1', '1', '1', '1', '0', '0', '', '', '', '', '', '', '',  '','is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 4, '~')";
			$res_insert = execute_db($sql, $conn);
		} // end if
		else {
			for ($j=0; $j<count($fields_names_ar); $j++){
				// insert a new record in the internal table with the name of the field as name and label
				$sql = "insert into ".$quote.$table_internal_name_temp.$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."order_form_field".$quote.") values ('".$fields_names_ar[$j]."', '".$fields_names_ar[$j]."', '".($j+1)."')";
				
				$res_insert = execute_db($sql, $conn);
			} // end for
		} // end else
		
		if (table_exists($table_internal_name_temp)){ // just a check if always is fine
			echo "<p>Internal table <b>".$table_internal_name_temp."</b> correctly created...</p>";
		} // end if
		else{
			echo "<p>An error occurred during installation!</p>";
			exit;
		} // end else

		if ($unique_field_name == ""){
			echo "<p><b>Warning:</b> the table <b>".$table_name_temp."</b> does not have a primary key set. If you do not set a primary key, the interface creator won't show the edit/delete/details buttons.</p>";
		} // end if
	} // end for
	echo "<p>... the interface creator is properly installed.</p>";
	echo "<p>You can now manage information in the MySQL tables, starting from <a href=\"".$dadabik_main_file."\">the data browser front page</a></p>";
	echo "<p>In order to configure the appearance of records from this table and of the forms used to manipulate data in the table, go to the <a href=\"admin.php\">administration</a> area.</p>";
} // end if ($install == "1")
else{
	echo "<form name=\"install_form\" id=\"install_form\" action=\"install.php?table_name=".urlencode($table_name)."\" method=\"post\">";
	echo "<p><input type=\"hidden\" name=\"install\" id=\"install\" value=\"1\" />";
	if ( $table_name != "") {
		echo "<input type=\"submit\" value=\"Click this button to install ".$table_name." table\" />";
	} // end if
	if ( $table_name == "") {
		echo "<input type=\"submit\" value=\"Click this button to install the interface creator\" />";
		echo "<br /><br />Please note that if the interface creator is already installed in the ".$db_name." database, the installation will overwrite the previous configuration. The installation will also overwrite the users table - ".$users_table_name.". If you want to keep the pre-existent one, just back it up  and import it after the installation (for how to backup and import MySQL tables, please check elsewhere; you may want to try the free web application phpmyadmin - www.phpmyadmin.net).</p>";
	}
	echo "</form>";
} // end else

// include footer
include ("footer_admin.php");
?>