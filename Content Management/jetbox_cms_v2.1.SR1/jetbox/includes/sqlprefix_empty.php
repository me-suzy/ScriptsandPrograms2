<?
// Adds prefixes for every table in a sql query
function mysql_prefix_query($sql_value, $connection=''){
	global $table_prefix, $connect;
	$table_prefix='';
	if ($connection=='') {
		$connection=$connect;
	}

	/*
	 *   THIS MODULE IS NOT FUNCTIONAL
	 */

	if ($table_prefix == '') {
		$result = mysql_query($sql_value, $connection);
		return $result;
	}
}
?>