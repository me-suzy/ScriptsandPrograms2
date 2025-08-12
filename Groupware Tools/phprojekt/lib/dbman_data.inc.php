<?php

// dbman_data.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: dbman_data.inc.php,v 1.26 2005/07/20 16:02:26 nina Exp $


// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use index.php!');


// *************
// db operations
// *************

// prepare values for storage in database
function sqlstrings_create() {
    global $fields, $sql_fieldstring, $sql_valuestring, $dbTSnull, $user_ID;
    foreach($fields as $field_name => $field) {
        // if the field is an upload form and the user uploaded a file - store the file and return the tempname
        if ($field['form_type'] == 'upload' ) {
            if ($_FILES[$field_name]['tmp_name'] <> '' and $_FILES[$field_name]['tmp_name'] <> 'none') $sql_value = upload_file_create($field_name);
            else $sql_value = '';
        }
        else if ($field['form_type'] == 'select_multiple') {
            $sql_value = store_select_multiple($field_name);
        }
        else if ($field['form_type'] == 'select_category') {
            if ($_POST['new_category'] <> '') $sql_value = $_POST['new_category'];
            else $sql_value = $_POST[$field_name];
        }
        else if ($field['form_type'] == 'timestamp_modify') {
            $sql_value = $dbTSnull;
        }
        else if ($field['form_type'] == 'timestamp_create') {
            $sql_value = $dbTSnull;
        }
        else if ($field['form_type'] == 'authorID') {
            $sql_value = $user_ID;
        }
        else if ($field['form_type'] == 'time') {
            $hour = $field_name.'_hour';
            $minute = $field_name.'_minute';
            if (strlen($_POST["$hour"]) == 1) $_POST["$hour"] = '0'.$_POST["$hour"];
            if (strlen($_POST["$minute"]) == 1) $_POST["$minute"] = '0'.$_POST["$minute"];
            $sql_value = $_POST["$hour"].':'.$_POST["$minute"];
        }
        // in all other cases simply store the value of the field
        else{
       		 $_POST[$field_name]= addslashes($_POST[$field_name]);
        	 $sql_value = $_POST[$field_name];
        }

        // store the name and the value - except for the simple 'display' function
        if ($field['form_type'] <> 'display') {
            $sql_fields[] = $field_name;
            $sql_values[] = $sql_value;
        }
    }

    $sql_fieldstring = implode(',', $sql_fields);
    $sql_valuestring = "'".implode("','", $sql_values)."'";
}


function sqlstrings_modify() {
    global $fields, $dbTSnull, $user_ID, $ID, $module;
    foreach($fields as $field_name => $field) {
        // if the field is an upload form and the user uploaded a file - store the file and return the tempname
        if ($field['form_type'] == 'upload') {
            if ($_FILES[$field_name]['tmp_name'] <> '' and $_FILES[$field_name]['tmp_name'] <> 'none') {
                $sql_string .= $field_name." = '".upload_file_modify($field_name, $ID, $module)."',";
            }
        }
        // fields with value 'display' will not be filled
        else if (in_array($field['form_type'], array('display', 'user_show', 'contact_create', 'authorID'))) {}
        else if ($field['form_type'] == 'select_multiple') {
            $sql_string .= $field_name." = '".store_select_multiple($field_name)."',";
        }
        else if ($field['form_type'] == 'select_category') {
            if ($_POST['new_category'] <> '') $sql_string .= $field_name." = '".$_POST['new_category']."',";
            else $sql_string .= $field_name." = '".$_POST[$field_name]."',";
        }
        // skip any field with type = create since this value shouldn't be touched anymore
        else if (eregi('create',$field['form_type'])) {}

        else if (eregi('userID_access',$field['form_type'])) {
            if (slookup($field['tablename'],$field['form_select'],'ID',$ID) == $user_ID) {
                $sql_string .= $field_name." = '".$_POST[$field_name]."',";
            }
            else {}
        }
        // timestamp ...
        else if ($field['form_type'] == 'timestamp_modify') {
            $sql_string .= $field_name." = '".$dbTSnull."',";
        }
        else if ($field['form_type'] == 'time') {
            $hour = $field_name.'_hour';
            $minute = $field_name.'_minute';
            if (strlen($_POST["$hour"]) == 1) $_POST["$hour"] = '0'.$_POST["$hour"];
            if (strlen($_POST["$minute"]) == 1) $_POST["$minute"] = '0'.$_POST["$minute"];
            $sql_string .= $field_name." = '".$_POST["$hour"].":".$_POST["$minute"]."',";
        }
        // in all other cases simply store the value of the field
        else {
        	$_POST[$field_name]= addslashes($_POST[$field_name]);
        	$sql_string .= $field_name." = '".$_POST[$field_name]."',";
        }
    }

    return $sql_string;
}


// return string with results for 'select multiple'
function store_select_multiple($field) {
    if ($_POST[$field] <> '') return implode('|',$_POST[$field]);
}


// ***********
// functions to upload, modify and delete a file in the form
function upload_file_create($field_name) {
    global $tablename, $path_pre;
    // add extension to random name
    $tempname = rnd_string().strstr($_FILES[$field_name]['name'],'.');
    // write file
    copy($_FILES[$field_name]['tmp_name'],$path_pre.PHPR_DOC_PATH.'/'.$tempname);
    // since only one field as available we have to store both values together
    return $_FILES[$field_name]['name'].'|'.$tempname;
}


function upload_file_delete($field_name, $ID, $module) {
    global $tablename;
    // fetch tempname from db
    $result = db_query("SELECT ".qss($field_name)."
                          FROM ".qss(DB_PREFIX.$tablename[$module])."
                         WHERE ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    // is there any previous file listed?
    if ($row[0] <> '') {
        list(,$t2) = explode('|',$row[0]);
        if (is_file('../'.PHPR_DOC_PATH.'/'.$t2)) unlink('../'.PHPR_DOC_PATH.'/'.$t2);
    }
}


function upload_file_modify($field_name, $ID, $module) {
    // 1. step: delete the old file
    upload_file_delete($field_name, $ID, $module);
    // 2. step
    return upload_file_create($field_name);
}


// delete a file attached to a record but leave the record as it is
function delete_attached_file ($field_name, $ID, $module) {
    global $tablename;
    // 1. step: unlink the file
    upload_file_delete($field_name, $ID, $module);
    // 2. step: update record
    $result = db_query(xss("UPDATE ".qss(DB_PREFIX.$tablename[$module])."
                           SET ".qss($field_name)." = ''
                         WHERE ID = '$ID'")) or db_die();
}

function manage_delete_records($ID, $module, $delete_tree=false) {
    global $tablename;
   $arr_ID = explode(',', $ID);
    foreach ($arr_ID as $ID) {
        $error = false;
        if (!check_perm_delete($ID, $module)) {
            $err_msg[] = 'No permission for record ID '.$ID;
            message_stack_in(__('No permission for record ID ').$ID, $module,"error");
            $error = true;
        }
        if (check_children($ID, $module) == true and $delete_tree == false) {
            $err_msg[] = 'Record with ID '.$ID.' still has subelements!';
            message_stack_in(__('Record has still subelements ').$ID, $module,"error");
            $error = true;
        }
        if (!$error) delete_record($ID);
    }
}


function check_perm_delete($ID, $module) {
    global $user_access, $user_ID, $tablename;
    if($module == 'links'){
        $id_field = 't_ID';
        $author = 't_author';
    }
    else{
        $id_field = 'ID';
        $author = 'von';
    }
    if (ereg('a',$user_access) or slookup($tablename[$module],$author,$id_field,$ID) == $user_ID) return true;
    else return false;
}


function check_children($ID, $module) {
    global $tablename;
    if($module == 'links'){
        $id_field = 't_ID';
        $parent = 't_parent';
    }
    else{
        $id_field = 'ID';
        $parent = 'parent';
    }
    if (slookup($tablename[$module],$id_field,$parent,$ID) > 0) return true;
    else return false;
}


function check_perm_modify($ID, $module, $acc_fieldname='acc') {
    global $user_ID, $user_kurz, $tablename, $sql_user_group;

    $acc_fieldname = qss($acc_fieldname);

    // check permission
    $result = db_query("SELECT ID, von, acc_write
                          FROM ".qss(DB_PREFIX.$tablename[$module])."
                         WHERE ID = '$ID'
                           AND (".$acc_fieldname." LIKE 'system'
                                OR ((von = '$user_ID'
                                     OR ".$acc_fieldname." LIKE 'group'
                                     OR ".$acc_fieldname." LIKE '%\"$user_kurz\"%')
                                    AND $sql_user_group))") or db_die();
    $row = db_fetch_row($result);
    if (!$row[0]) return 0;
    else if ($row[1] == $user_ID) return 'owner';
    else if ($row[2] == 'w') return 'write';
    else return 'read';
}


function update_access($module, $acc_read_fieldname, $owner_ID) {
    global $user_ID, $path_pre;

    include_once $path_pre.'lib/access.inc.php';

    if ($owner_ID == $user_ID) {
        // set read string
        $accessstring = $acc_read_fieldname." = '".assign_acc($_POST['acc'], $module)."',";
        // set write string
        if ($_POST['acc_write'] <> '') $accessstring .= "acc_write = 'w',";
        else $accessstring .= "acc_write = '',";
        return $accessstring;
    }
    else return '';
}


function insert_access($module, $acc_read_fieldname) {
    global $path_pre;

    include_once $path_pre.'lib/access.inc.php';

    // set read string
    $accessstring = $acc_read_fieldname." = '".assign_acc($_POST['acc'], $module)."',";
    // set write string
    if ($_POST['acc_write'] <> '') $accessstring[0] = "acc_write = 'w',";
    else $accessstring[1] = "acc_write = '',";
    return $accessstring;
}

?>
