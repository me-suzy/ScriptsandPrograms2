<?php
// include business logic, db_functions and general_functions
// DO NOT CHANGE ANYTHING BELOW - magic quotes issues
function add_slashes($value) {
    if (is_array($value)) {
        foreach ($value as $index => $val) {
            $value[$index] = add_slashes($val);
        }
        return $value;
    } else {
        return addslashes($value);
    }
}
function strip_slashes($value) {
    if (is_array($value)) {
        foreach ($value as $index => $val) {
            $value[$index] = strip_slashes($val);
        }
        return $value;
    } else {
        return stripslashes($value);
    }
}
if (!get_magic_quotes_gpc()) {
    $_GET = add_slashes($_GET);
    $_POST = add_slashes($_POST);
    $_COOKIE = add_slashes($_COOKIE);
    $_REQUEST = add_slashes($_REQUEST);
}
// for function stripos - undefined in PHP versions before 5
if(!function_exists('stripos'))
{
   function stripos($haystack,$needle,$offset = 0)
   {
    
return(strpos(strtolower($haystack),strtolower($needle),$offset));
   }
}
// end function stripos

// fix for lack of strripos function in PHP older than version 5
if (!function_exists('strripos')) {
function strripos($haystack, $needle, $offset = null)
{
if (!is_scalar($haystack)) {
user_error('strripos() expects parameter 1 to be scalar, ' .
gettype($haystack) . ' given', E_USER_WARNING);
return false;
}

if (!is_scalar($needle)) {
user_error('strripos() expects parameter 2 to be scalar, ' .
gettype($needle) . ' given', E_USER_WARNING);
return false;
}

if (!is_int($offset) && !is_bool($offset) && !is_null($offset)) {
user_error('strripos() expects parameter 3 to be long, ' .
gettype($offset) . ' given', E_USER_WARNING);
return false;
}

// Manipulate the string if there is an offset
$fix = 0;
if (!is_null($offset)) {
// If the offset is larger than the haystack, return
if (abs($offset) >= strlen($haystack)) {
return false;
}

// Check whether offset is negative or positive
if ($offset > 0) {
$haystack = substr($haystack, $offset, strlen($haystack) - $offset);
// We need to add this to the position of the needle
$fix = $offset;
} else {
$haystack = substr($haystack, 0, strlen($haystack) + $offset);
}
}

$segments = explode(strtolower($needle), strtolower($haystack));

$last_seg = count($segments) - 1;
$position = strlen($haystack) + $fix - strlen($segments[$last_seg]) - strlen($needle);

return $position;
}
}
// end fix for strripos lack

include ("business_logic.php");
function format_date($date)
// from "2000-12-15" to "15 Dec 2000"
{
	global $date_format, $date_separator;
	$temp_ar=explode("-",$date);
	$temp_ar[2] = substr($temp_ar[2], 0, 2); // e.g. from 11 00:00:00 to 11 if the field is datetime
	switch ($date_format){
		case "literal_english":
			$date=@date("j M Y",mktime(0,0,0,$temp_ar[1],$temp_ar[2],$temp_ar[0]));
			break;
		case "latin":
			$date = $temp_ar[2].$date_separator.$temp_ar[1].$date_separator.$temp_ar[0];
			break;
		case "numeric_english":
			$date = $temp_ar[1].$date_separator.$temp_ar[2].$date_separator.$temp_ar[0];
			break;
	} // end switch
	return $date;
}

function split_date($date, &$day, &$month, &$year)
// goal: split a mysql date returning $day, $mont, $year
// input: $date, a MySQL date, &$day, &$month, &$year
// output: &$day, &$month, &$year
{
	$temp=explode("-",$date); 
	$day=$temp[2];
	$month=$temp[1];
	$year=$temp[0];
} // end function split_date

function build_date_select_type_select($field_name)
// goal: build a select with operators: nothing = > <
// input: $field_name
// output: $operator_select
{
	$operator_select = "";
	$operator_select .= "<select name=\"".$field_name."\" id=\"".$field_name."\">";
	$operator_select .= "<option value=\"\"></option>";
	$operator_select .= "<option value=\"=\">=</option>";
	$operator_select .= "<option value=\">\">></option>";
	$operator_select .= "<option value=\"<\"><</option>";
	$operator_select .= "</select>";

	return $operator_select;
} // end function build_date_select_type_select

function display_sql($sql)
// goal: display a sql query
// input: $sql
// output: nothing
// global: $display_sql
{
	global $display_sql;
	if ($display_sql == "1"){
		echo "<p><i><b>Your MySQL query (for debugging purpose): </b></i>".$sql."</p>";
	} // end if
} // end function display_sql

function txt_out($message, $class="")
{
	if ( $class != "") {
		$message = "<span class=\"".$class."\">".$message."</span>";
	}

	echo $message;
} // end function txt_out

function get_pages_number($results_number, $records_per_page)
// goal: calculate the total number of pages necessary to display results
// input: $results_number, $records_per_page
// ouptut: $pages_number
{
	$pages_number = $results_number / $records_per_page;
	$pages_number = (int)($pages_number);
	if (($results_number % $records_per_page) != 0) $pages_number++; // if the reminder is greater than 0 I have to add a page because I have to round to excess

	return $pages_number;
} // end function get_pages_number

function build_date_select ($field_name, $day, $month, $year)
// goal: build three select to select a data (day, mont, year), if are set $day, $month and $year select them
// input: $field_name, the name of the date field, $day, $month, $year (or "", "", "" if not set)
// output: $date_select, the HTML date select
// global $start_year, $end_year
{
	global $start_year, $end_year;

	$date_select = "";
	$day_select = "";
	$month_select = "";
	$year_select = "";
	
	$day_select .= "<select name=\"".$field_name."_day\" id=\"".$field_name."_day\">";
	$month_select .= "<select name=\"".$field_name."_month\" id=\"".$field_name."_month\">";
	$year_select .= "<select name=\"".$field_name."_year\" id=\"".$field_name."_year\">";

	for ($i=1; $i<=31; $i++){
		$day_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($day != "" and $day == $i){
			$day_select .= " selected=\"selected\"";
		} // end if
		$day_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=1; $i<=12; $i++){
		$month_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($month != "" and $month == $i){
			$month_select .= " selected=\"selected\"";
		} // end if
		$month_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=$start_year; $i<=$end_year; $i++){
		$year_select .= "<option value=\"$i\"";
		if($year != "" and $year == $i){
			$year_select .= " selected=\"selected\"";
		} // end if
		$year_select .= ">".$i."</option>";
	} // end for

	$day_select .= "</select>";
	$month_select .= "</select>";
	$year_select .= "</select>";

	$date_select = "<td valign=\"top\">".$day_select."</td><td valign=\"top\">".$month_select."</td><td valign=\"top\">".$year_select."</td>";

	return $date_select;

} // end function build_date_select

function contains_numerics($string)
// goal: verify if a string contains numbers
// input: $string
// output: true if the string contains numbers, false otherwise
{
	$count_temp = strlen($string);
	if(ereg("[0-9]+", $string)) {
		return true;
		
	}
	return false;
} // end function contains_numerics

function is_valid_email($email)
// goal: chek if an email address is valid, according to its syntax
// input: $email
// output: true if it's valid, false otherwise
{
    return (preg_match( 
        '/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+'.   // the user name 
        '@'.                                     // the ubiquitous at-sign 
        '([-0-9A-Z]+\.)+' .                      // host, sub-, and domain names 
        '([0-9A-Z]){2,4}$/i',                    // top-level domain (TLD) 
        trim($email))); 
} // end function is_valid_email

function is_valid_url($url)
// goal: chek if an url address is valid, according to its syntax, supports 4 letters domaains (e.g. .info), http https ftp protcols and also port numbers
// input: $url
// output: true if it's valid, false otherwise
{
	return eregi("^((ht|f)tps*://)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))(:[0-9]{1,4})*((/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)$", $url); 
} // end function is_valid_url


function is_valid_phone($phone)
// goal: chek if a phone numbers is valid, according to its syntax (should be: "+390523599314")
// input: $phone
// output: true if it's valid, false otherwise
{
	if ($phone[0] != "+"){
		return false;
	} // end if
	else{
		$phone = substr($phone, 1); // delete the "+"
		if (!is_numeric($phone)){
			return false;
		} // end if
	} // end else
	return true;
} // end function is_valid_phone

function get_unique_field($table_name)
// goal: get the name of the first uniqe field in a table
// input: $table_name
// output: $unique_field_name, the name of the first unique field in the table
{
	global $conn, $db_name;
	$unique_field_name = "";
	$fields = list_fields_db($db_name, $table_name, $conn);
	$columns = num_fields_db($fields);

	for ($i = 0; $i < $columns; $i++) {
		if (strpos(field_flags_db($fields, $i), "primary_key")){ // if the flag contain the word "primary_key"
			$unique_field_name = field_name_db($fields, $i);
			break;
		} // end if
	}
	return $unique_field_name;
} // end function get_unique_field

function db_error($sql)
// goal: exit the script
// input: $sql
// output: nothing
{
	exit;
} // end function db_error

// db functions
function connect_db($server, $user, $password)
{
	global $debug_mode;
	if ($debug_mode == 1){
		$conn = @mysql_connect($server, $user, $password) or die ('<p><b>[06] Error:</b> during database connection.<br />MySQL server said: '.mysql_error().'</p>');
	} // end if
	else{
		$conn = mysql_connect($server, $user, $password) or die ('<p><b>[06] Error:</b> during database connection. Most likely the settings in config.php file are incorrect, or the MySQL server is down. For MySQL debugging, you should turn on the appropriate options in the file.</p>');
	} // end else
	return $conn;
}

function list_tables_db($dbase)
{
	global $debug_mode;
	return mysql_list_tables($dbase);
}

function tablename_db($res, $i)
{
	global $debug_mode;
	return mysql_tablename($res, $i);
}

function select_db($dbase, $conn)
{
	global $debug_mode;
	if ($debug_mode == 1){
		mysql_select_db($dbase, $conn) or die ('<p><b>[07] Error:</b> during database selection.<br />MySQL server said: '.mysql_error().'</p>');
	} // end if
	else{
		mysql_select_db($dbase, $conn) or die ('<p><b>[07] Error:</b> during database selection. Most likely the database name set in config.php file is incorrect, or there is no such database is down. For MySQL debugging, you should turn on the appropriate options in the file.</p>');
	} // end else

}

function execute_db($sql, $conn)
{
	global $debug_mode;
	if ($debug_mode == 0){
		$results = mysql_query($sql, $conn) or die ('<p><b>[08] Error:</b> during query execution. The query statement was:<br /><br />'.$sql.'<br /><br />The MySQL server responded: '.mysql_error().'</p>');
	} // end if
	else{
		$results = mysql_query($sql, $conn) or die ('<p><b>[08] Error:</b> during query execution. Perhaps there is something wrong with the PHP code. For MySQL debugging, you should turn on the appropriate options in the file.</p>');
	} // end else
	return $results;
}

function fetch_row_db($rs)
{
	return mysql_fetch_array ($rs);
}

function get_num_rows_db($rs)
{
	return mysql_num_rows($rs);
}

function get_last_ID_db()
{
	return mysql_insert_id();
}
 
function list_fields_db($db_name, $table_name, $conn)
{
	return mysql_list_fields ($db_name, $table_name, $conn);
}
 
function num_fields_db($fields)
{
	return mysql_num_fields ($fields);
}
 
function field_name_db($fields, $i)
{
	return mysql_field_name ($fields, $i);
}

function field_flags_db($fields, $i)
{
	return mysql_field_flags($fields, $i);
}

function fetch_field_db($res)
{
	return mysql_fetch_field($res); // return the field type
}
?>