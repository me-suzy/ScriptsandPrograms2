<?php

// contacts_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, Norbert Ku:ck
// $Id: contacts_data.php,v 1.24 2005/07/05 11:27:01 nina Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role('contacts') < 2) die('You are not allowed to do this!');
$include_path3 = $path_pre."lib/access.inc.php";
include_once $include_path3;

// prepare array for doublet check
if ($doublet_action <> '' and count($doublet_fields) > 0) { $ex_records = fetch_contacts(); }
// check whether the name field is empty
$nachname = check_lastname($nachname,$firma);

// *************
// start actions


// work on a user entry

if ($members) {
    $result = db_query(xss("update ".DB_PREFIX."users
                         set firma='".xss($firma)."',
                             email='".xss($email)."',
                             tel1='".xss($tel1)."',
                             tel2='".xss($tel2)."',
                             fax='".xss($fax)."',
                             strasse='".xss($strasse)."',
                             stadt='".xss($stadt)."',
                             plz='".xss($plz)."',
                             land='".xss($land)."',
                             mobil='".xss($mobil)."',
                             anrede='".xss($anrede)."'
                       where ID = '$ID'")) or db_die();
    $action='members';
    message_stack_in(__('the data set is now modified.')." :-)","contacts","notice");
}
// ***************
// import routines
else if ($imp_approve) {
    if ($ID > 0) {
        $result = db_query("update ".DB_PREFIX."contacts
                           set import='',
                               sync2 = '$dbTSnull'
                         where ID = '$ID'") or db_die();
    }
    // approve the whole list
    else {
        $result = db_query("update ".DB_PREFIX."contacts
                           set import='',
                               sync2 = '$dbTSnull'
                       where von = '$user_ID' and
                             $sql_user_group and
                             import='1'") or db_die();
        message_stack_in(__('The list has been imported.'),"contacts","notice");
        $approve_contacts = '';
        $_SESSION['approve_contacts'] =& $approve_contacts;
    }
}
else if ($imp_undo) {
    // remove a single entry from the import list
    if ($ID > 0) {
        $result = db_query("delete from ".DB_PREFIX."contacts
                        where ID = '$ID'") or db_die();
        // delete corresponding entry from db_record
        $result = db_query("delete from ".DB_PREFIX."db_records
                                  where t_record = '$ID' and t_module = 'contacts'") or db_die();
    }
    // remove the complete import list
    else {
        $result = db_query("delete from ".DB_PREFIX."contacts
                        where von = '$user_ID' and
                                $sql_user_group and
                                import='1'") or db_die();
        message_stack_in(__('The list has been rejected.'),"contacts","notice");
        $approve_contacts = '';
        $_SESSION['approve_contacts'] =& $approve_contacts;
    }
}
else if ($import_contacts) {
    include_once('./contacts_import_data.php');
}
// end import
// **********

// separate check: for insert or modify, update the profiles
if (PHPR_CONTACTS_PROFILES and !$cancel) {

  // case update first delete all existing records connected with this contact (very dangerous if the system crashes! :-()
  // case delete: you can delete the entries anyway :-)
 if ($modify or $modify_contact) {
    $result = db_query("SELECT ID
                          FROM ".DB_PREFIX."contacts_profiles
                         WHERE von = '$user_ID'");
    $profile_ids = "0";
    while($row=db_fetch_row($result)) {
      $profile_ids .= ",$row[0]";
    }
    $result = db_query("DELETE FROM ".DB_PREFIX."contacts_prof_rel
                              WHERE contact_ID = '$ID'
                                AND contacts_profiles_ID IN ($profile_ids)") or db_die();
  }
 elseif ($create OR $create_contact) {
     // find the ID of the last created user and assign it to ID
     $result = db_query("SELECT MAX(ID)
                           FROM ".DB_PREFIX."contacts
                          WHERE von = '$user_ID'") or db_die();
    $row = db_fetch_row($result);
    $ID = $row[0];
  }
  //now insert all selected profiles, but only if the contacts shouldn't be deleted
  if (!$delete and $profile_lists[0] > 0) {
    foreach($profile_lists as $profile) {
      $result = db_query(xss("INSERT INTO ".DB_PREFIX."contacts_prof_rel
                                          ( ID,     contact_ID, contacts_profiles_ID)
                                   VALUES ($dbIDnull, '$ID', '$profile')")) or db_die();
    }
  }
}


// store the selection as a result of the current filter list
if ($action == 'store_selection') {
  $sql_user_group = "(".DB_PREFIX."contacts.gruppe = '$user_group')";

  // 1. step: create a new profile with the timestamp as the name
  foreach($flist['contacts'] as $key => $p_filter) {
    foreach ($fields as $field_name => $field) {
      if ($field_name == $p_filter[0]) {
        $filtername = enable_vars($field['form_name']);
      }
    }
    $filters .= $filtername." ".$p_filter[1]." ".$p_filter[2]." | ";
  }
  $result = db_query(xss("insert into ".DB_PREFIX."contacts_profiles
                             (ID,        von,     name,      remark)
                      values ($dbIDnull,'$user_ID','".show_iso_date1($dbTSnull)."','$filters')")) or db_die();
  // fetch the ID number for later reference
  $result = db_query("select max(ID) from ".DB_PREFIX."contacts_profiles
                       where von = '$user_ID'") or db_die();
  $row = db_fetch_row($result);

  // build the where clause
  foreach ($flist['contacts'] as $key => $p_filter) {
    $where .= ' and (';
    // if the field string is 'all', it has to belloped over all applicable fields
    if ($p_filter[0] == 'all') { $where .= apply_full_filter($p_filter[1],$p_filter[2]); }
    else { $where .= apply_filter($p_filter[0],$p_filter[1],$p_filter[2]); }
    $where .=')';
  }

  // do the query
  $result2 = db_query("select ID
                         from ".DB_PREFIX."contacts
                        where (acc_read like 'system' or ((von = '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))
                              $where") or db_die();
  while ($row2 = db_fetch_row($result2)) {
    $result3 = db_query(xss("insert into ".DB_PREFIX."contacts_prof_rel
                                (ID,contact_ID,contacts_profiles_ID)
                         values ($dbIDnull,'$row2[0]','$row[0]')")) or db_die();
  }
}
elseif ($action == 'store_filter') {
  save_filter('contacts', date('Y-m-d'));
}
elseif ($action == 'load_filter') {

}


// ****************************
// external contacts operations

if($cancel_b){}

// delete a file attached to a record
elseif ($delete_file) { delete_attached_file($file_field_name, $ID, 'contacts'); }

elseif ($delete_b) {
  if ($ID > 0) manage_delete_records($ID,$module);
  elseif ($ID_s <> '')  manage_delete_records($ID_s,$module);
}

elseif ($modify_b) {
  // check permission
  $result = db_query("select ID, von, acc_write
                        from ".DB_PREFIX."contacts
                       where ID = '$ID' and
                             (acc_read like 'system' or ((von = '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))") or db_die();
  $row = db_fetch_row($result);
  if (!$row[0] or ($row[1] <> $user_ID and $row[2] <> 'w')) {dier ("You are not allowed to do this"); }
  // check whether this user is author of this record - if yes, allow him to change the permission status
  // otherwise don't change this field
  $accessstring = update_access($module,'acc_read',$row[1]);

  //keep history
  if (PHPR_HISTORY_LOG) {
    sqlstrings_create('1');
    history_keep('contacts','acc_read,acc_write,'.$sql_fieldstring,$ID);
  }
  $sql_string = sqlstrings_modify();
  // update record in db
  $result = db_query("update ".DB_PREFIX."contacts
                         set $sql_string
                             import ='',
                             parent = '$parent',
                             $accessstring
                             sync2 = '$dbTSnull'
                       where ID = '$ID'") or db_die();
  message_stack_in(__('The date of the contact was modified')." :-)","contacts","notice");
}

elseif ($create_b) {
  $access = assign_acc($acc, 'contacts');
  if ($acc_write <> '') { $acc_write = 'w'; }
  sqlstrings_create();
  $result = db_query(xss("insert into ".DB_PREFIX."contacts
           (ID,        gruppe,            von,   parent,    sync1, sync2,     acc_read, acc_write, ".$sql_fieldstring." )
    values ($dbIDnull,'$user_group','$user_ID','$parent','$dbTSnull','$dbTSnull','$access','$acc_write',     ".$sql_valuestring.")")) or db_die();
      message_stack_in(__('The new contact has been added')." :-)","contacts","notice");
}

// annoying but it has to be done once again - building the array of fields, but this time in the order of the list view
$fields = build_array('contacts', $ID, 'view');
include_once("./contacts_view.php");


// *************
// function area

// fetch contacts of this user into array for later doublet check
function fetch_contacts() {
  global $doublet_fields, $user_ID;

  settype($doublet_fields, "array");
  $new_val = array();
  foreach($doublet_fields as $a_val) {
    $new_val[] = qss($a_val);
  }

  $result2 = db_query("select ID,".implode(',', $new_val)."
                         from ".DB_PREFIX."contacts
                        where von = '$user_ID'") or db_die();
  while ($row2 = db_fetch_row($result2)) {
    $ex_records[$row2[0]]['ID'] = $row2[0];
    $i = 1;
    foreach ($doublet_fields as $doublet_field) {
      $ex_records[$row2[0]][$doublet_field] = $row2[$i];
      $i++;
    }
  }
  return $ex_records;
}

function check_for_doublettes($imp_records, $ex_records) {
  global $doublet_fields;
  if ($ex_records) {
    foreach ($ex_records as $ex_record) {
      $failed = 0;
      if ($doublet_fields) {
        foreach ($doublet_fields as $doublet_field) {
          if ($imp_records[$doublet_field] <> $ex_record[$doublet_field]) $failed = 1;
        }
        if (!$failed) return $ex_record['ID'];
      }
    }
  }
  return 0;
}

// if no last name is given - e.g. during import - use the company name
function check_lastname($nachname,$firma) {
  $nachname ? $nachname : $nachname = $firma;
  return $nachname;
}

function delete_record($ID) {
  global $fields, $user_ID;

  // check permission
    $result = db_query("select von, acc_write, nachname
                        from ".DB_PREFIX."contacts
                       where ID = '$ID'") or db_die();
  $row = db_fetch_row($result);
  if ($row[0] == 0) {
     message_stack_in("no entry found","contacts","error");
    $error = 1;
  }
  if ($row[0] <> $user_ID and !$row[1]) {
      message_stack_in("No privilege to delete contact!","contacts","error");
    $error = 1;
  }

  if (!$error) {
    // delete the entry from table contacts_profiles
    if (PHPR_CONTACTS_PROFILES) {
      $result = db_query("delete from ".DB_PREFIX."contacts_prof_rel
                           where contact_ID = '$ID'") or db_die();
    }
    // delete all files associated with this record
    foreach($fields as $field_name => $field) {
      if ($field['form_type'] == 'upload' ) {
        $sql_value = upload_file_delete($field_name, $ID, 'contacts');
      }
    }
    // delete record in db
    $result = db_query("delete from ".DB_PREFIX."contacts
                         where ID = '$ID'") or db_die();
    // delete corresponding entry from db_record
    $result = db_query("delete from ".DB_PREFIX."db_records
                              where t_record = '$ID' and t_module = 'contacts'") or db_die();
         message_stack_in( $row[2].": ".__('The contact has been deleted').".  ","contacts","notice");

     // finally delete history
     if (PHPR_HISTORY_LOG) { history_delete('contacts',$ID); }
  }
}

?>
