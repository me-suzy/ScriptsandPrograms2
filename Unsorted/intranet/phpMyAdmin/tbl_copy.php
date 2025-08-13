<?php
/* $Id: tbl_copy.php,v 1.6 2000/02/13 20:15:55 tobias Exp $ */

require("header.inc.php");

function my_handler($sql_insert)
{
    global $table, $db, $new_name;
    
    $sql_insert = ereg_replace("INSERT INTO $table", "INSERT INTO $new_name", $sql_insert);
    $result = mysql_db_query($db, $sql_insert) or mysql_die();
    $sql_query = $sql_insert;
}

$sql_structure = get_table_def($db, $table, "\n");
$sql_structure = ereg_replace("CREATE TABLE $table", "CREATE TABLE $new_name", $sql_structure);

$result = mysql_db_query($db, $sql_structure) or mysql_die();
$sql_query .= "\n$sql_structure";

if($what == "data")
    get_table_content($db, $table, "my_handler");

eval("\$message = \"$strCopyTableOK\";");
include("db_details.php");
?>
