<?php
/* $Id: tbl_alter.php,v 1.7 2000/02/13 20:15:54 tobias Exp $ */

require("header.inc.php");

if(isset($submit))
{
    if(!isset($query)) 
        $query = "";
    $query .= " $field_orig[0] $field_name[0] $field_type[0] ";
    if($field_length[0] != "")
        $query .= "($field_length[0]) ";
    if($field_attribute[0] != "")
        $query .= "$field_attribute[0] ";
    if($field_default[0] != "")
        $query .= "DEFAULT '$field_default[0]' ";

   $query .= "$field_null[0] $field_extra[0]";
   $query = stripslashes($query);
   $sql_query = "ALTER TABLE $table CHANGE $query";
   $result = mysql_db_query($db, "ALTER TABLE $table CHANGE $query") or mysql_die();
   $message = "$strTable $table $strHasBeenAltered";
   include("tbl_properties.php");
   exit;
}
else
{
    $result = mysql_db_query($db, "SHOW FIELDS FROM $table LIKE '$field'") or mysql_die();
    $num_fields = mysql_num_rows($result);
    $action = "tbl_alter.php";
    include("tbl_properties.inc.php");
}

require ("footer.inc.php");
?>
