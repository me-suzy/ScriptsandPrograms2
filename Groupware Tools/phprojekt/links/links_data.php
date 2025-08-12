<?php

// links_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: links_data.php,v 1.10 2005/06/20 14:43:01 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role ... check deactivated since we do not see any security problem
// if (check_role("links") < 2) { die("You are not allowed to do this!"); }

include_once("$lib_path/permission.inc.php");
$include_path3 = $path_pre."lib/access.inc.php";
include_once $include_path3;
$access = assign_acc($acc, 'links');
if (!$parent) $parent = 0;
if($cancel){}
if(isset($ID_s)){
    $ID = $ID_s;
}
if ($delete_b and $ID <> '') {
  manage_delete_records($ID,$module);
}


// delete a file attached to a record
elseif ($delete_file) {delete_attached_file($file_field_name, $ID, 'links'); }

elseif  ($create_b) {
  sqlstrings_create();
  $result = db_query("insert into ".DB_PREFIX."links
           (ID,        gruppe,            von, archiv,  ".$sql_fieldstring." )
    values ($dbIDnull,'$user_group','$user_ID',0, ".$sql_valuestring.")") or db_die();
}

elseif ($modify_b and $ID > 0) {
  // check permission
  $result = db_query("select t_ID, t_author, t_acc
                        from ".DB_PREFIX."db_records
                       where t_ID = '$ID' and
                             (t_acc like 'system' or ((t_author = '$user_ID' or t_acc like 'group' or t_acc like '%\"$user_kurz\"%') ))") or db_die();
  $row = db_fetch_row($result);
  if (!$row[0] or ($row[1] <> $user_ID and $row[2] <> 'w')) { die("You are not allowed to do this"); }


  $sql_string = sqlstrings_modify();
  // update record in db
  $result = db_query("update ".DB_PREFIX."db_records
                         set $sql_string
                             t_author = '$user_ID'
                       where t_ID = '$ID'") or db_die();
}
$ID = 0;

$fields = build_array('links', $ID, 'view');
include_once("./links_view.php");


function delete_record($ID) {
  global $user_ID, $fields;

  // check permission
  $result = db_query("select t_author, t_acc
                        from ".DB_PREFIX."db_records
                       where t_ID = '$ID'") or db_die();
  $row = db_fetch_row($result);
  if ($row[0] == 0) {
    echo "no entry found.";
    $error = 1;
  }
  if ($row[0] <> $user_ID and !$row[1]) {
    echo  "You are not privileged to do this!";
    $error = 1;
  }
  if (!$error) {
    // delete record in db
    $result = db_query("delete from ".DB_PREFIX."db_records
                         where t_ID = '$ID'") or db_die();
  }
}

?>
