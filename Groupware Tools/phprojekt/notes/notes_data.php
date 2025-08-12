<?php

// notes_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: notes_data.php,v 1.18.2.1 2005/09/12 13:59:29 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("notes") < 2) die("You are not allowed to do this!");


include_once($lib_path."/permission.inc.php");
$include_path3 = $path_pre."lib/access.inc.php";
include_once($include_path3);
$access = assign_acc($acc, 'notes');

if (!$parent) $parent = 0;
if ($cancel) {}

if ($delete_b) {
    manage_delete_records($ID, $module);
}
else if ($delete_c) {
  if      ($ID > 0)     manage_delete_records($ID, $module);
  else if ($ID_s <> '') manage_delete_records($ID_s, $module);
}
// delete a file attached to a record
else if ($delete_file) {
    delete_attached_file($file_field_name, $ID, 'notes');
}
else if (!$ID) {
    if ($acc_write <> '') $acc_write = 'w';
    sqlstrings_create();
    $result = db_query(xss("INSERT INTO ".DB_PREFIX."notes
                                        (ID,        gruppe,            von,   parent,    sync1,     sync2,      acc,      acc_write ,".$sql_fieldstring." )
                                 VALUES ($dbIDnull,'$user_group','$user_ID','$parent','$dbTSnull','$dbTSnull','$access','$acc_write',".$sql_valuestring.")")) or db_die();
}
else if ($ID > 0) {
    $perm_modify = check_perm_modify($ID, $module, 'acc');
    if ($perm_modify <> 'write' && $perm_modify <> 'owner') {
        $err_msg[] = 'You cannot modify the records with the ID '.$ID;
        $error = true;
    }
    if (!$error) {
        // keep history
        if (PHPR_HISTORY_LOG) {
            sqlstrings_create();
            history_keep('notes', 'acc,acc_write,'.$sql_fieldstring, $ID);
        }
        // check whether this user is author of this record - if yes, allow him to change the permission status
        // otherwise don't change this field
        if ($perm_modify == 'owner') {
            $accessstring = "acc = '$access',";
            if ($acc_write <> '') $accesswritestring = "acc_write = 'w',";
            else                  $accesswritestring = "acc_write = '',";
        }
        else {
            $accessstring      = '';
            $accesswritestring = '';
        }

        $sql_string = sqlstrings_modify();
        // update record in db
        $result = db_query(xss("UPDATE ".DB_PREFIX."notes
                                   SET $sql_string
                                       parent = '$parent',
                                       $accessstring
                                       $accesswritestring
                                       sync2 = '$dbTSnull'
                                 WHERE ID = '$ID'")) or db_die();
    }
}

$ID = 0;

if (!$justform) {
  $mode = 'view';
  include_once("./notes_view.php");
}
else {
    echo '<script type="text/javascript">self.opener.location.reload();self.close();</script>';
}

#if (!$justform) include_once("./notes_view.php");
#else include_once("./notes_forms.php");


function delete_record($ID) {
    global $fields, $user_ID;

    // check permission
    $result = db_query("SELECT von, acc_write
                          FROM ".DB_PREFIX."notes
                         WHERE ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if ($row[0] == 0) die("no entry found.");
    if ($row[0] <> $user_ID and !$row[1]) die("You are not privileged to do this!");

    // check whether there are subelements below this record ..
    $result = db_query("SELECT ID
                          FROM ".DB_PREFIX."notes
                         WHERE parent = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if ($row[0] > 0) {
        message_stack_in(__('Please delete all subelements first')."!", "notes", "error");
    }
    // no sub element? -> delete!
    else {
        // delete all files associated with this record
        foreach ($fields as $field_name=>$field) {
            if ($field['form_type'] == 'upload') {
                $sql_value = upload_file_delete($field_name, $ID, 'notes');
            }
        }
        // delete record in db
        $result = db_query("DELETE FROM ".DB_PREFIX."notes
                                  WHERE ID = '$ID'") or db_die();
        // delete corresponding entry from db_record
        $result = db_query("DELETE FROM ".DB_PREFIX."db_records
                                  WHERE t_record = '$ID'
                                    AND t_module = 'notes'") or db_die();
    }
}

?>
