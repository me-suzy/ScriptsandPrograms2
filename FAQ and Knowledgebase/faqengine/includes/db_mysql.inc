<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
// special dataconversion for mySQL
$db_dateformat_full="Y-m-d H:i:s";
$db_dateformat_short="Y-m-d";
// DB functions for mySQL
function faqe_db_connect($dbhost,$dbuser,$dbpasswd,$dbname,$err_query)
{
	if(!$db = @mysql_connect($dbhost,$dbuser,$dbpasswd))
		die($err_query);
//	for debugging: comment line above, uncomment line below
//	die($err_query."<br>".mysql_error());
	if(!@mysql_select_db($dbname,$db))
		die($err_query);
//	for debugging: comment line above, uncomment line below
//		die($err_query."<br>".mysql_error());
	return $db;
}

function faqe_db_query($sql,$db)
{
	return (mysql_query($sql,$db));
}

function faqe_db_fetch_array($result)
{
	return mysql_fetch_array($result);
}

function faqe_db_error()
{
	return mysql_error();
}

function faqe_db_num_rows($result)
{
	return mysql_num_rows($result);
}

function faqe_db_list_tables($dbname)
{
	return mysql_list_tables($dbname);
}

function faqe_db_tablename($table, $index)
{
	return mysql_tablename($table, $index);
}

function faqe_db_num_fields($result)
{
	return mysql_num_fields($result);
}

function faqe_db_field_name($result, $index)
{
	return mysql_field_name($result, $index);
}

function faqe_db_field_type($result, $index)
{
	return mysql_field_type($result, $index);
}

function faqe_db_fetch_row($result)
{
	return mysql_fetch_row($result);
}

function faqe_db_insert_id($result)
{
	return mysql_insert_id($result);
}

function faqe_db_free_result($result)
{
	return mysql_free_result($result);
}
?>