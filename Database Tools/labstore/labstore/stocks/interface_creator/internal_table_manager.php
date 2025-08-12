<?php
// include config, functions, common, check_installation and header
include ("../config.php");
include ("functions.php");
include ("common_start.php");
include ("check_installation.php");
include ("header_admin.php");

if (!isset($_GET["table_name"])){
	exit;
} // end if
else{
	$table_name = urldecode($_GET["table_name"]);
} // end else

$table_internal_name = $prefix_internal_table.$table_name;


if (!isset($_POST["show_all_fields"])){
	$show_all_fields = "";
} // end if
else{
	$show_all_fields = $_POST["show_all_fields"];
} // end else

// the position of the field the user wants to manage
if (!isset($_POST["field_position"])){
	$field_position = "";
} // end if
else{
	$field_position = $_POST["field_position"];
} // end else

// I need this the first time I load the page, $save is unset
if (isset($_POST["save"])){
	$save = $_POST["save"];
} // end if
else{
	$save = "0";
} // end if

/*
reset ($_POST);
while (list($key, $value) = each ($_POST)){
	$$key = $value;
} // end while
*/
// include internal table fields definition
include ("internal_table.php");

// get the array containg label ant other information about the fields
$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

if ($field_position == "" and $show_all_fields != "1"){
	$field_position = 0; // set the $field_name to the first field
} // end if

if ($save == "1"){
	// save the configuration of the internal table
	for ($i=0; $i<count($fields_labels_ar); $i++){
		if (isset($_POST[$int_fields_ar[1][1]."_".$i])){ // if isset the variable (it means that this field was in the form){

			$sql = "";
			$sql .= "update ".$quote."$table_internal_name".$quote." set ";

			for ($j=1; $j<count($int_fields_ar); $j++){ // from 1 because the first is the name of the field ".${$int_fields_ar[$j][1]."_".$i};
				$sql .= $quote.$int_fields_ar[$j][1]."".$quote." = '".$_POST[$int_fields_ar[$j][1]."_".$i]."', ";
			} // end for
			$sql = substr($sql, 0, strlen($sql)-2);

			$sql .= " where name_field = '".$fields_labels_ar[$i]["name_field"]."'";

			// execute the update select
			$res_update = execute_db($sql, $conn);
		} // end if
	} // end for

	"<p>Configuration correctly saved.</p>";
} // end if

// re-get the array containg label ant other information about the fields
$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

$change_field_select = build_change_field_select($fields_labels_ar, $field_position);

$int_table_form = "";

$int_table_form .= "<p>This form allows you to configure the way the fields for this table - <b>".$table_name."</b> - appear on the web forms. To configure for fields of a different table, choose that table on the interface creator administration home page. (<a href=\"help.htm\" onclick=\"return popitup('help.htm')\">Help</a>)</p><form method=\"post\" action=\"internal_table_manager.php?table_name=".urlencode($table_name)."\">".$change_field_select."<p><input type=\"submit\" value=\"Select a field above and click to configure it\" /></p></form>

<form method=\"post\" action=\"internal_table_manager.php?table_name=".urlencode($table_name)."\"><p><input type=\"hidden\" name=\"show_all_fields\" id=\"show_all_fields\" value=\"1\" /><input type=\"submit\" value=\"Or click to configure all fields\" /></p></form>

<form method=\"post\" action=\"internal_table_manager.php?table_name=".urlencode($table_name)."\">";


if ($show_all_fields == "1"){
	// main loop through each record of the internal table
	for ($i=0; $i<count($fields_labels_ar); $i++){
		$int_table_form .= build_int_table_field_form($i, $int_fields_ar, $fields_labels_ar);
	} // end for
} // end if
else{
	$int_table_form .= build_int_table_field_form($field_position, $int_fields_ar, $fields_labels_ar);
} // end else

$int_table_form .= "<p><input type=\"hidden\" name=\"field_position\" id=\"field_position\" value=\"".$field_position."\" />";
$int_table_form .= "<input type=\"hidden\" name=\"show_all_fields\" id=\"show_all_fields\" value=\"".$show_all_fields."\" />";
$int_table_form .= "<input type=\"submit\" value=\"Save configuration\" />";
$int_table_form .= "<input type=\"hidden\" name=\"save\" id=\"save\" value=\"1\" />";
$int_table_form .= "</p></form>";

// display the tabled form
echo $int_table_form;
?>
<?php
// include footer
include ("footer_admin.php");
?>