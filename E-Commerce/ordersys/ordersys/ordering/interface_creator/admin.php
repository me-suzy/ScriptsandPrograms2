<?php
include ("../config.php");
include ("functions.php");
include ("common_start.php");
include ("check_installation.php");
include ("header_admin.php");
if (isset($_POST["allow_table_ar"])){
	$allow_table_ar = $_POST["allow_table_ar"];
} // end if
if (isset($_POST["deleted_fields_ar"])){
	$deleted_fields_ar = $_POST["deleted_fields_ar"];
} // end if
if (isset($_POST["field_to_change_name"])){
	$field_to_change_name = $_POST["field_to_change_name"];
} // end if
if (isset($_POST["field_to_change_name"])){
	$field_to_change_name = $_POST["field_to_change_name"];
} // end if
if (isset($_POST["field_to_change_new_position"])){
$field_to_change_new_position = $_POST["field_to_change_new_position"];
} // end if
if (isset($_POST["old_field_name"])){
	$old_field_name = $_POST["old_field_name"];
} // end if
if (isset($_POST["new_field_name"])){
	$new_field_name = $_POST["new_field_name"];
} // end if
if (isset($_POST["new_field_name"])){
	$new_field_name = $_POST["new_field_name"];
} // end if
if (isset($_POST["function"])){
	$function = $_POST["function"];
} // end if
elseif (isset($_GET["function"])){ // for uninstall function
	$function = $_GET["function"];
} // end if
else{
	$function = "";
} // end else
if (isset($_POST["enable_insert"])){
	$enable_insert = $_POST["enable_insert"];
} // end if
if (isset($_POST["enable_edit"])){
	$enable_edit = $_POST["enable_edit"];
} // end if
if (isset($_POST["enable_delete"])){
	$enable_delete = $_POST["enable_delete"];
} // end if
if (isset($_POST["enable_details"])){
	$enable_details = $_POST["enable_details"];
} // end if

$confirmation_message = "";

// get the array containing the names of the tables installed
$installed_tables_ar = build_tables_names_array(0, 1, 1);

// get the table name to use in the second part of the administration
if (isset($_GET["table_name"])){
	$table_name = $_GET["table_name"];
} // end if
else{
	if (count($installed_tables_ar)>0){
		// get the first table
		$table_name = $installed_tables_ar[0];
	} // end if
} // end else

if (isset($table_name)){
	// build the select with all installed table
	$change_table_select = build_change_table_select(0, 1);
	$table_internal_name = $prefix_internal_table.$table_name;
} // end if

// this is useful to display the tables that could be installed
$complete_tables_names_ar = build_tables_names_array(0, 0, 1);

switch($function){
	case "uninstall_table":
		// delete the table from table_list_name
		$sql = "DELETE FROM ".$quote.$table_list_name.$quote." WHERE name_table = '".$table_name."'";
		execute_db($sql, $conn);

		// drop the internal table
		$sql = "DROP TABLE ".$quote.$table_internal_name.$quote;
		execute_db($sql, $conn);

		$confirmation_message .= "Table ".$table_name." uninstalled.";

		// re-get the array containing the names of the tables installed
		$installed_tables_ar = build_tables_names_array(0, 1, 1);

		if (count($installed_tables_ar)>0){
			// get the first table
			$table_name = $installed_tables_ar[0];
		} // end if

		if (isset($table_name)){
			// build the select with all installed table
			$change_table_select = build_change_table_select(0, 1);
			$table_internal_name = $prefix_internal_table.$table_name;
		} // end if

		break;
	case "include_tables":
		for ($i=0; $i<count($installed_tables_ar); $i++){
			if (isset($allow_table_ar[$i])){
				if ($allow_table_ar[$i] == "1"){
					$sql = "update ".$quote."$table_list_name".$quote." set ".$quote."allowed_table".$quote." = '1' where ".$quote."name_table".$quote." = '".$installed_tables_ar[$i]."'";
				} // end if
			} // en if
			else{
				$sql = "update ".$quote."$table_list_name".$quote." set ".$quote."allowed_table".$quote." = '0' where ".$quote."name_table".$quote." = '".$installed_tables_ar[$i]."'";
			} // end else
			
			execute_db($sql, $conn);
		} // end for

		//$installed_tables_ar = build_tables_names_array(0, 1); // reload to show the correct values

		$confirmation_message .= "Changes correctly saved.";

		break; // break case "include tables"
	case "change_field_name":
		// change the name of the field
		$sql = "update ".$quote.$table_internal_name.$quote." set ".$quote."name_field".$quote." = '$new_field_name' where ".$quote."name_field".$quote." = '$old_field_name'";

		execute_db($sql, $conn);

		$confirmation_message .= "$old_field_name correctly changed to $new_field_name.";
		
		break;
	case "enable_features":
		if (!isset($enable_insert)){
			$enable_insert = "0";
		} // end if

		if (!isset($enable_edit)){
			$enable_edit = "0";
		} // end if

		if (!isset($enable_delete)){
			$enable_delete = "0";
		} // end if

		if (!isset($enable_details)){
			$enable_details = "0";
		} // end if

		// save the configuration about features enabled
		$sql = "update ".$quote."$table_list_name".$quote." set ".$quote."enable_insert_table".$quote." = '".$enable_insert."', ".$quote."enable_edit_table".$quote." = '".$enable_edit."', ".$quote."enable_delete_table".$quote." = '".$enable_delete."', ".$quote."enable_details_table".$quote." = '".$enable_details."' where ".$quote."name_table".$quote." = '$table_name'";

		// execute the update
		$res_update = execute_db($sql, $conn);

		$confirmation_message .= "Changes correctly saved.";
		break;
	case "delete_records":
		// get the array containg label and other information about the fields
		$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
		
		if (isset($deleted_fields_ar)){
			for ($i=0; $i<count($deleted_fields_ar); $i++){
				// delete the record of the internal table
				$sql = "delete from ".$quote."$table_internal_name".$quote." where ".$quote."name_field".$quote." = '".$deleted_fields_ar[$i]."' limit 1";
				$res_delete = execute_db("$sql", $conn);

				// get the order_form_field of the field
				for ($j=0; $j<count($fields_labels_ar); $j++){
					if ($deleted_fields_ar[$i] == $fields_labels_ar[$j]["name_field"]){
						$order_form_field_temp = $fields_labels_ar[$j]["order_form_field"];
					} // end if
				} // end for

				// re-get the array containg label and other information about the fields
				$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

				if (isset($order_form_field_temp)){ // otherwise I could have done a reload of a delete page
					// decrease the order_form_field of all the following record by one
					for ($j=($order_form_field_temp+1); $j<=(count($fields_labels_ar)+1); $j++){
						$sql ="update ".$quote."$table_internal_name".$quote." set ".$quote."order_form_field".$quote." = order_form_field-1 where ".$quote."order_form_field".$quote." = $j limit 1";
						$res_update = execute_db("$sql", $conn);
					} // end for
				} // end if

				// re-get the array containg label and other information about the fields
				$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
			} // end for

			$confirmation_message .= "$i fields correctly deleted from the internal table $table_internal_name.";
		} // end if
		else{
			$confirmation_message .= "Please select one or more fields to delete.";
		} // end else
		break;
	case "refresh_table":
		// get the array containing the names of the fields
		$fields_names_ar = build_fields_names_array($table_name);

		// get the array containg label ant other information about the fields
		$fields_labels_ar = build_fields_labels_array($table_internal_name, "0");

		// get the max order from the table
		$sql_max = "select max(order_form_field) from ".$quote."$table_internal_name".$quote."";
		$res_max = execute_db("$sql_max", $conn);
		while ($max_row = fetch_row_db($res_max)){
			$max_order_form = $max_row[0];
		} // end while

		// drop (if present) the old internal table and create the new one.
		create_internal_table($table_internal_name);

		$j = 0;  // set to 0 the counter for the $fields_labels_ar
		$new_fields_nr = 0; // set to 0 the counter for the number of new fields inserted

		for ($i=0; $i<count($fields_names_ar); $i++){
			if (isset($fields_labels_ar[$j]["name_field"]) and $fields_names_ar[$i] == $fields_labels_ar[$j]["name_field"]){



				// insert a previous present record in the internal table
				$name_field_temp = add_slashes($fields_labels_ar[$j]["name_field"]);
				$present_insert_form_field_temp = add_slashes($fields_labels_ar[$j]["present_insert_form_field"]);
				$present_search_form_field_temp = add_slashes($fields_labels_ar[$j]["present_search_form_field"]);
				$present_ext_update_form_field_temp = add_slashes($fields_labels_ar[$j]["present_ext_update_form_field"]);
				$required_field_temp = add_slashes($fields_labels_ar[$j]["required_field"]);
				$present_results_search_field_temp = add_slashes($fields_labels_ar[$j]["present_results_search_field"]);
				$check_duplicated_insert_field_temp = add_slashes($fields_labels_ar[$j]["check_duplicated_insert_field"]);
				$type_field_temp = add_slashes($fields_labels_ar[$j]["type_field"]);
				$content_field_temp = add_slashes($fields_labels_ar[$j]["content_field"]);
				$separator_field_temp = add_slashes($fields_labels_ar[$j]["separator_field"]);
				$select_options_field_temp = add_slashes($fields_labels_ar[$j]["select_options_field"]);
				$select_type_field_temp = add_slashes($fields_labels_ar[$j]["select_type_field"]);
				$prefix_field = add_slashes($fields_labels_ar[$j]["prefix_field"]);
				$default_value_field = add_slashes($fields_labels_ar[$j]["default_value_field"]);
				$label_field_temp = add_slashes($fields_labels_ar[$j]["label_field"]);
				$width_field_temp = add_slashes($fields_labels_ar[$j]["width_field"]);
				$height_field_temp = add_slashes($fields_labels_ar[$j]["height_field"]);
				$maxlength_field_temp = add_slashes($fields_labels_ar[$j]["maxlength_field"]);
				$hint_insert_field_temp = add_slashes($fields_labels_ar[$j]["hint_insert_field"]);
				$order_form_field_temp = add_slashes($fields_labels_ar[$j]["order_form_field"]);
				
				$other_choices_field_temp = add_slashes($fields_labels_ar[$j]["other_choices_field"]);

				$primary_key_field_field_temp = add_slashes($fields_labels_ar[$j]["primary_key_field_field"]);
				$primary_key_table_field_temp  = add_slashes($fields_labels_ar[$j]["primary_key_table_field"]);
				$primary_key_db_field_temp = add_slashes($fields_labels_ar[$j]["primary_key_db_field"]);

				$linked_fields_field_temp = add_slashes($fields_labels_ar[$j]["linked_fields_field"]);
				$linked_fields_order_by_field_temp = add_slashes($fields_labels_ar[$j]["linked_fields_order_by_field"]);
				$linked_fields_order_type_field_temp = add_slashes($fields_labels_ar[$j]["linked_fields_order_type_field"]);
				$linked_fields_extra_mysql_temp = add_slashes($fields_labels_ar[$j]["linked_fields_extra_mysql"]);
				
			

				$sql = "insert into ".$quote."$table_internal_name".$quote." (".$quote."name_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."label_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.",
".$quote."linked_fields_order_type_field".$quote.",
".$quote."linked_fields_extra_mysql".$quote.") values ('$name_field_temp', '$present_insert_form_field_temp', '$present_search_form_field_temp', '$required_field_temp', '$present_results_search_field_temp', '$present_ext_update_form_field_temp', '$check_duplicated_insert_field_temp', '$type_field_temp', '$content_field_temp', '$separator_field_temp', '$select_options_field_temp', '$select_type_field_temp', '$prefix_field', '$default_value_field', '$label_field_temp', '$width_field_temp', '$height_field_temp', '$maxlength_field_temp', '$hint_insert_field_temp', '$order_form_field_temp', '$other_choices_field_temp', '$primary_key_field_field_temp', '$primary_key_table_field_temp', '$primary_key_db_field_temp', '$linked_fields_field_temp', '$linked_fields_order_by_field_temp', '$linked_fields_order_type_field_temp', '$linked_fields_extra_mysql_temp')";

				$j++; // go to the next record in the internal table
			} // end if
			else{
				$max_order_form++;
				// insert a new record in the internal table with the name of the field
				$sql = "insert into ".$quote."$table_internal_name".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."order_form_field".$quote.") values ('$fields_names_ar[$i]', '$fields_names_ar[$i]', '$max_order_form')";
				
				$new_fields_ar[$new_fields_nr] = $fields_names_ar[$i]; // insert the name of the new field in the array to display it in the confirmation message
				$new_fields_nr++; // increment the counter of the $new_fields_ar array
			} // end else	
			$res_insert = execute_db($sql, $conn);
		} // end for
		$confirmation_message .= "Internal table correctly refreshed.<br />$new_fields_nr field/s added.";
		if ($new_fields_nr > 0){
			$confirmation_message .= " (";
			for ($i=0; $i<count($new_fields_ar); $i++){
				$confirmation_message .= $new_fields_ar[$i].", ";
			} // end for
			$confirmation_message = substr($confirmation_message, 0, -2); // delete the last ", "
			$confirmation_message .= ")";
		} // end if
		$confirmation_message .= ". <span style=\"color:red;\">You may go </span><a href=\"internal_table_manager.php?table_name=<?php echo urlencode($table_name); ?>\">to the configurator page</a> <span style=\"color:red;\">to specify the nature of form fields to be used with the new table fields.</span>";
		break;
	case "change_position":
		// get the array containg label and other information about the fields
		$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

		// get the order_form_field of the field
		for ($i=0; $i<count($fields_labels_ar); $i++){
			if ($field_to_change_name == $fields_labels_ar[$i]["name_field"]){
				$field_to_change_old_position = $fields_labels_ar[$i]["order_form_field"];
			} // end if
		} // end for

		if ($field_to_change_new_position < $field_to_change_old_position){
			// increase the order_form_field of all the following record by one
			for ($i=$field_to_change_old_position-1; $i>=$field_to_change_new_position; $i--){
				$sql ="update ".$quote.$table_internal_name.$quote." set ".$quote."order_form_field".$quote." = ".$quote."order_form_field".$quote."+1 where ".$quote."order_form_field".$quote." = '".$i."' limit 1";
				$res_update = execute_db($sql, $conn);
			} // end for
		} // end if
		else{
			// decrease the order_form_field of all the previous record by one
			for ($i=$field_to_change_old_position+1; $i<=$field_to_change_new_position; $i++){
				$sql ="update ".$quote.$table_internal_name.$quote." set ".$quote."order_form_field".$quote." = ".$quote."order_form_field".$quote."-1 where ".$quote."order_form_field".$quote." = '".$i."' limit 1";
				$res_update = execute_db($sql, $conn);
			} // end for
		} // end if

		// change the order_form_field of the field selected
		$sql ="update ".$quote.$table_internal_name.$quote." set ".$quote."order_form_field".$quote." = '".$field_to_change_new_position."' where ".$quote."name_field".$quote." = '".$field_to_change_name."' limit 1";
		$res_update = execute_db($sql, $conn);
		$confirmation_message .= "Field $field_to_change_name position correctly changed from $field_to_change_old_position to $field_to_change_new_position.";		
		break;
	default:
		break;
} // end switch
?>
<?php
if ($confirmation_message != ""){
	echo "<p><b>$confirmation_message</b></p>";
} // end if
?>
<?php if (count($installed_tables_ar)>0){ // otherwise it means that no internal tables are installed
// get the array containg label and other information about the fields
$fields_labels_ar = build_fields_labels_array($table_internal_name, "1"); // because I need it for the display of the select in the form
?>
<div style="width:700px; background:#f0f0f0;"><table summary="none" cellspacing="5" border="0"><tr><td><p>Scroll down to add (install) or remove tables for manipulation with the interface creator.</p>
<p>Below, you can modify interface creator settings for the selected table</p>
<?php
if ($change_table_select != ""){
?>
<form name="change_table_form" method="get" action="admin.php"><p><input type="hidden" name="function" id="function" value="change_table" />
<?php echo $change_table_select; ?>
<input type="submit" value="Change table" /></p></form></td></tr></table></div><br /><div style="width:700px; background:#dcdcdc;">
<table summary="none" border="0" cellpadding="5" width="700"><tr><td><b>Table <?php echo $table_name; ?>:</b><br />
<?php
}
$enable_features_checkboxes = build_enable_features_checkboxes($table_name);
?>
<form method="post" action="admin.php?table_name=<?php echo urlencode($table_name); ?>"><p><input type="hidden" id="function" name="function" value="enable_features" />For this table, enable: <?php echo $enable_features_checkboxes ?><input type="submit" value="Enable/disable" /><br />Note that the tables can still be manipulated using scripts from outside the interface creator.</p></form><span style="color:red;">To configure the interface of the table in detail (e.g., to specify options for pull-down menus appearing in the entry insert form, to specify if a table field should not be displayed, etc.), go </span><a href="internal_table_manager.php?table_name=<?php echo urlencode($table_name); ?>">to this page</a>.<br /><br />To update information for interface creator, e.g., when you have modified some fields of your table (i.e., when you have added one or more fields, deleted one or more fields, renamed one or more fields for the table), follow these steps in the correct order:<br /><br />
<table summary="none" border="0" cellpadding="5"><tr><td><b>Step 1:</b><br />If you have renamed some fields of <b><?php echo $table_name; ?></b> you have to specify here the new names.</b>Select the field name you want to change and specify the new name:<br /><form name="change_field_name_form" id="change_field_name_form" method="post" action="admin.php?table_name=<?php echo $table_name; ?>"><p><input type="hidden" name="function" id="function" value="change_field_name" /></p>Old field name: <select name="old_field_name" id="old_field_name">
<?php
for ($i=0; $i<count($fields_labels_ar); $i++){
	echo "<option value=\"".$fields_labels_ar[$i]["name_field"]."\">".$fields_labels_ar[$i]["name_field"]."</option>";	
} // end for
?></select>New field name: <input type="text" name="new_field_name" id="new_field_name" /><input type="submit" value="Change" /></form></td></tr></table><br />
<table summary="none" border="0" cellpadding="5">
<tr><td><b>Step 2:</b><br />If you have deleted some fields of <b><?php echo $table_name; ?></b> you have to specify here which fields you have deleted by selecting it/them and pressing the delete button. Select the field/s you want to delete:<br />(press CTRL or CMD + click for multiple selection)<form name="deleted_fields_form" id="deleted_fields_form" method="post" action="admin.php?table_name=<?php echo $table_name; ?>">
<p><input type="hidden" name="function" id="function" value="delete_records" /></p>
<select multiple="multiple" id="deleted_fields_ar[]" name="deleted_fields_ar[]" size="10">
<?php
for ($i=0; $i<count($fields_labels_ar); $i++){
echo "<option value=\"".$fields_labels_ar[$i]["name_field"]."\">".$fields_labels_ar[$i]["name_field"]."</option>";	
} // end for
?> 
</select><p><input type="submit" value="Delete this/these field/fields" name="submit" id="submit" /></p></form></td></tr></table>
<br />
<table summary="none" border="0" cellpadding="5">
<tr><td><b>Step 3:</b><br />If you have added some fields to <b><?php echo $table_name; ?></b> you have to update interface creator by pressing the refresh installation button. <span style="color:red;">Afterwards, go </span><a href="internal_table_manager.php?table_name=<?php echo urlencode($table_name); ?>">to the configurator page</a> <span style="color:red;">to specify the nature of form fields to be used with the new table fields.</span><form name="refresh_form" id="refresh_form" method="post" action="admin.php?table_name=<?php echo $table_name; ?>"><p><input type="hidden" name="function" id="function" value="refresh_table" /><input type="submit" value="Refresh installation" name="submit" id="submit" /></p></form></td></tr></table>
<br/ >
<table summary="none" border="0" cellpadding="5"><tr><td><b>Step 4:</b><br />If you want to change the displaying order of a field in the interface creator interfaces, you can do it by selecting the field from the following menu and specifying the new position. All the other field positions will be shifted correctly.<form name="change_position_form" id="change_position_form" method="post" action="admin.php?table_name=<?php echo $table_name; ?>">
		<p><input type="hidden" name="function" id="function" value="change_position" /><p>
		Field name (position): 
        <select single="single" name="field_to_change_name" id="field_to_change_name">
         <?php
		for ($i=0; $i<count($fields_labels_ar); $i++){
			echo "<option value=\"".$fields_labels_ar[$i]["name_field"]."\">".$fields_labels_ar[$i]["name_field"]." (".$fields_labels_ar[$i]["order_form_field"].")</option>";	
		} // end for
		?> 
        </select>
		 New position: 
		<select  name="field_to_change_new_position" id="field_to_change_new_position">
         <?php
		for ($i=0; $i<count($fields_labels_ar); $i++){
			echo "<option value=\"".$fields_labels_ar[$i]["order_form_field"]."\">".$fields_labels_ar[$i]["order_form_field"]."</option>";	
		} // end for
		?> 
        </select>
        <p><input type="submit" value="Change position" name="submit" id="submit" /></p>
      </form></td></tr></table>
</td></tr></table></div>
<br /><div style="width:700px; background-color:#f0f0f0;"><table summary="none" border="0" cellspacing="5"><tr><td><b>Manage the list of tables of the "<?php echo $db_name; ?>" database you want to use in interface creator</b>
<table summary="none" border="0" cellpadding="5"><tr><td>
<p><span style="color:red;">Do not alter these unless you understand the system and need to do the alterations.</span></p><p>Here is the list of the tables that are <i>installed</i>, and thus ready for use with the interface creator. (That means that for each of this table, there is a corresponding table named with a dadabik_ prefix). Uncheck and click 'save changes' to remove a table from the interface creator. Click 'Uninstall' if you also want to remove the interface creator-installed table (names prefixed with - <?php echo $prefix_internal_table; ?>) from the database. Unchecking and clicking 'save changes' will not delete interface creator-installed table.</p>
<form name="include_tables_form" id="include_tables_form"  method="post" action="admin.php">
<p><input type="hidden" name="function" id="function" value="include_tables" /></p>
<?php if (count($installed_tables_ar) != 0){ ?>
<table summary="none"><tr><td><p>
<?php
for ($i=0; $i<count($installed_tables_ar); $i++){
echo "<input type=\"checkbox\" name=\"allow_table_ar[$i]\" id=\"allow_table_ar[$i]\" value=\"1\"";
	if (table_allowed($installed_tables_ar[$i])){
		echo " checked=\"checked\"";
	} // end if
	echo " />".$installed_tables_ar[$i]." <a href=\"admin.php?function=uninstall_table&amp;table_name=".urlencode($installed_tables_ar[$i])."\">Uninstall</a><br />";
} // end for
?>
</p></td><td></table>
<p><input type="submit" value="Save changes" /></p>
<?php } // end if
 else{	
	echo "No tables installed.";
 } // end else
 ?>
</form></td></tr></table>
<table summary="none" border="0" cellpadding="5"><tr><td>These are all the tables in your database, except those installed by the interface creator (prefixed - <?php echo $prefix_internal_table; ?>). (Note that the interface creator may also have installed a 'users_tab' users table into your database.) Click 'Install' to install a not-yet-installed table <i>or to re-install</i> an already installed one to overwrite its configuration.<br /><br />
<?php
for ($i=0; $i<count($complete_tables_names_ar); $i++){
echo $complete_tables_names_ar[$i]."&nbsp;<a href=\"install.php?table_name=".urlencode($complete_tables_names_ar[$i])."\">Install</a><br />";
} // end for
?>
</td></tr></table>

</td></tr></table></div>
<?php } // end if?>
<?php
if ($enable_authentication === 1){ // for ID_user transfers
//////
//-- actions
if (isset($_POST['reassign']))
{ // submitted form for reassignment
 if (empty($_POST['table_for_reassign']) or empty($_POST['from_user']) or empty($_POST['to_user']))
 {
 $user_assign_message = '<span style="color:red;">You did not choose one of the options</span>';
 }
 else
 {
 $to_user = $_POST['to_user'];
 if ($_POST['to_user'] == 'no_one')
  {$to_user = '';} // will clear ID_user
 $from_user = $_POST['from_user'];
 if ($_POST['from_user'] == 'no_one')
  {$from_user = '';} // ID_user empty
 $sql = 'UPDATE `'.$_POST['table_for_reassign'].'` SET `ID_user` = \''.$to_user.'\'';
 if ($_POST['from_user'] !== 'any_one')
  {$sql .= 'WHERE `ID_user` = \''.$from_user.'\'';
  if (!empty($_POST['extra_sql']))
   {
   $sql .= ' '.strip_slashes($_POST['extra_sql']);
   }
  }
 else
  {$sql .= 'WHERE `ID_user` LIKE \'%\'';
   if (!empty($_POST['extra_sql']))
   {
   $sql .= ' '.strip_slashes($_POST['extra_sql']);
   }
  }
 mysql_query($sql);
  if (!(mysql_error()))
  {
  $user_assign_message = '<b>The re-assignment was successful - '.mysql_affected_rows().' records(s) needed the modification</b>';
  }
  else
  {
  $user_assign_message = '<span style="color:red;">MySQL error for query - <br /><i>' . $sql . '</i><br />Please check the extra MySQL clauses that you may have passed.</span>';
  }
 }
}
//-- display
echo ('<br /><div style="width:700px; background-color:#f0f0f0;"><table summary="none" border="0" cellpadding="5"><tr><td><a name="id_user" id="id_user"></a><b>Record owner re-assignment</b><p>Use the options below if you want to change the "ID_user" field values to re-assign "ownership" of records. Depending on configurations in the config.php file, the ability to view details of, edit or delete a record for a table may be restricted to the record "owner."</p>');
echo ('<p>'.$user_assign_message.'</p>');
// options for tables for form; only installed tables with ID_user field
$table_options = '';
$table_possible = array();
if (count($installed_tables_ar) > 0)
{
foreach ($installed_tables_ar as $key => $value)
{
if ($value !== $users_table_name)
	{
	$sql = 'SHOW COLUMNS FROM '.$value.' LIKE \'ID_user\'';
	$result = mysql_query($sql);
	if (mysql_fetch_array($result)){$table_possible[] = $value;}
	}
}
}
$table_options .= '<select single="single" id="table_for_reassign" name="table_for_reassign"><option value="">Select table...</option>';
if (count($table_possible) > 0)
{
foreach ($table_possible as $key => $value)
{
$table_options .= '<option value="'.$value.'">'.$value.'</option>';
}
}
$table_options .= '</select>';
// options for users for form
$to_user_options = '<select single="single" id="to_user" name="to_user"><option value="">Select new owner (ID_user)...</option>';
$from_user_options = '<select single="single" id="from_user" name="from_user"><option value="">Select current owner (ID_user)...</option>';
$user_options = '';
$sql = 'SELECT `'.$users_table_username_field.'`, `ID_user` FROM `'.$users_table_name.'`';
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result))
{
$user_options .= '<option value="'.$row[1].'">'.$row[0].' ('.$row[1].')</option>';
}
$to_user_options .= $user_options.'<option value="no_one">* No one * make empty</option></select>';
$from_user_options .= $user_options.'<option value="no_one">* No one * now empty</option><option value="any_one">* Anyone * all</option></select>';
// for extra sql
$for_extra_sql = '<textarea id="extra_sql" name="extra_sql" rows="2" cols="50"></textarea>';
// build form
echo ('<form id="user_reassignment" method="POST" action="admin.php#id_user"><table summary="none" border="0" cellspacing="2" cellpadding="0"><tr><td><b>Table:</b><br />'.$table_options.'</td><td><b>Current owner:</b><br />'.$from_user_options.'</td><td><b>New owner:</b><br />'.$to_user_options.'</td></tr><tr><td colspan="2"><b>Extra for MySQL SELECT statement:</b><br />'.$for_extra_sql.'<br /></td><td><input name="reassign" id="reassign" type="submit" value="Submit" ');
if (count($table_possible) < 1)
{
echo ('disabled="disabled" ');
}
echo ('/></td></tr><tr><td colspan="3"><br />You may use the text-field above to pass extra clauses for the MySQL SELECT statement used for the user re-assignment. Do not use if you are not familiar with MySQL syntax. This is useful if you want to restrict the re-assignment or limit it to certain number. E.g., if filled with <i>AND `artist` LIKE \'%bruce%\' LIMIT 1</i>, note the back-ticks and single quote-marks, only those records will be re-assigned that have <i>bruce</i> for the "artist" field.</td></tr></table></form>');
echo ('</td></tr></table></div>');
//--
/////
} // end for ID_user transfers
else
{echo ('<br /><div style="width:700px; background-color:#f0f0f0;"><table summary="none" border="0" cellpadding="5"><tr><td><a name="id_user" id="id_user"></a><b>Record owner re-assignment</b><p>You do not have authentication enabled (through $enable_authentication in config.php). This functionality is therefore disabled.</p></td></tr></table></div>');
}
// include footer
include ("footer_admin.php");
?>