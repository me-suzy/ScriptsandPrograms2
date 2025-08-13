<?php
/* $Id: tbl_rename.php,v 1.5 2000/02/13 20:15:57 tobias Exp $ */
$old_name = $table;
$table = $new_name;
require("header.inc.php");

$result = mysql_db_query($db, "ALTER TABLE $old_name RENAME $new_name") or mysql_die();
$table = $old_name;
eval("\$message =  \"$strRenameTableOK\";");
$table = $new_name;
include("tbl_properties.php");
?>
