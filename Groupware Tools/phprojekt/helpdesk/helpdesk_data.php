<?php

// helpdesk_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: helpdesk_data.php,v 1.23.2.2 2005/09/07 09:19:18 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("helpdesk") < 2) { die("You are not allowed to do this!"); }

use_mail('1');

// fetch permission routine
include_once("$lib_path/permission.inc.php");
$include_path3 = $path_pre."lib/access.inc.php";
include_once $include_path3;
$acc_read = assign_acc($acc_read, 'helpdesk');

// delete request
if ($delete_b) {
  if ($ID > 0) manage_delete_records($ID,$module);
  elseif( $ID_s <> '') manage_delete_records($ID_s,$module);
}


// delete a file attached to a record
elseif ($delete_file) {delete_attached_file($file_field_name, $ID, 'helpdesk'); }

// insert new request
elseif  (!$ID && isset($_REQUEST['name'])) {
  if ($acc_write <> '') { $acc_write = 'w'; }
  sqlstrings_create();
  $status = $helpdesk_states[0]['key'];
  $result = db_query(xss("insert into ".DB_PREFIX."rts
           (ID,        gruppe,        parent,  von,       acc,  acc_read,   acc_write ,status,   ".$sql_fieldstring." )
    values ($dbIDnull,'$user_group','$parent','$user_ID','$acc','$acc_read','$acc_write','$status',      ".$sql_valuestring.")")) or db_die();
}

// update request
elseif ($ID > 0 && isset($_REQUEST['name'])) {
  // check permission
  $result = db_query("select ID, assigned, acc_write, von, status
                        from ".DB_PREFIX."rts
                       where ID = '$ID' and
                             (acc_read like 'system' or ((von = '$user_ID' or assigned = '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))") or db_die();
  $row = db_fetch_row($result);
  if (!$row[0] or ($row[1] <> $user_ID and $row[3] <> $user_ID and $row[2] <> 'w')) { die("You are not allowed to do this"); }

  // check whether this record is assigned to this user - if yes, allow him to change the permission status
  // otherwise don't change this field
  if ($row[1] == $user_ID ) {
    $accessstring = "acc_read = '$acc_read',";
    if ($acc_write <> '') $accesswritestring = "acc_write = 'w',";
    else $accesswritestring = "acc_write = '',";
  }
  else {
    $accessstring = '';
    $accesswritestring = '';
  }
  // end check permission

  //keep history
  if (PHPR_HISTORY_LOG) {
    sqlstrings_create();
    history_keep('rts','acc_read,acc_write,'.$sql_fieldstring,$ID);
  }
  //These options aren't available anymore!
  /**
  if ($action == 'solve') { $rts_normal ? $status = 2 : $status = 4; }
  if ($action == 'stall') $status = 20;
  if ($action == 'moveto') $status = 21;
  */	
  // include the library from lib
  include_once("$lib_path/email_notification.inc.php");
  $change_user= slookup('users','nachname,vorname','ID',$user_ID);

  // notify if the assigned user has been changed
  if ($assigned <> $row[1]) { email_notification('rts', $acc_read,$change_user." ".__("has reassigned the following request").": ".$name, $add_mail); }

  // notify if the status has been changed
  if ($status <> $row[4]) { email_notification('rts', $acc_read, $name.": ".__("Ticket status changed")."(".$helpdesk_states[$status-1]["label"].")", $add_mail); }
   //isn't needed!
  //$status = $helpdesk_states[$status-1]['key'];

  $sql_string = sqlstrings_modify();
  // update record in db
  $result = db_query(xss("update ".DB_PREFIX."rts
                         set $sql_string
                             $accessstring
                             $accesswritestring
                             parent = '$parent',
                             status = '$status',
                             acc = '$acc'	
                       where ID = '$ID'")) or db_die();
  // ********
  // solve request, mail to customer, set access
  if(($status=='solved' or $status==5)&&$status <> $row[4]){
  //if ($action == 'solve') {
    $result = db_query(xss("update ".DB_PREFIX."rts
                            set solved = '$user_ID',
                                solve_time = '$dbTSnull'
                            where ID = '$ID'")) or db_die();

    // fetch original question
    $result = db_query("select name, note
                          from ".DB_PREFIX."rts
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);

    // body of the mail consists of: "has been answered by NN, question: xyz, answer: xyz ...
    $body = __('Your request was solved by')." $user_firstname $user_name\n ".__('Request').": $row[0]\n $row[1]\n ".__('Solution').": $solution";
    // fetch mail adress

    if (PHPR_RTS_CUST_ACC and PHPR_CONTACTS) {
      $result2 = db_query("select email
                             from ".DB_PREFIX."contacts
                            where ID = '$row[0]'") or db_die();
      $row2 = db_fetch_row($result2);
      $cust_mail = $row2[0];
    }
    if(empty($cust_mail)) {
      $result = db_query("select contact, email
                            from ".DB_PREFIX."rts
                           where ID = '$ID'") or db_die();
      $row = db_fetch_row($result);
      $cust_mail = $row[1];
    }
    // mail to the customer with the solution
    $success = $mail->go($cust_mail, __('Answer to your request Nr.')." $ID", $body, $user_email);
    // confirmation screen for the author
    message_stack_in(__('Your solution was mailed to the customer and taken into the database'),"helpdesk","notice");
  }

  // ********
  // move request
  elseif ($action == 'moveto') {
    $result = db_query("select ID, name, note, remark
                          from ".DB_PREFIX."rts
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    $result2 = db_query("select ID, name, note, remark
                           from ".DB_PREFIX."rts
                          where ID = '$moveto'") or db_die();
    $row2 = db_fetch_row($result2);
    $name = quote_runtime($row2[1]."\n+ Nr. $row[0]:\n ".$row[1]);
    $note = quote_runtime($row2[2]."\n+ Nr. $row[0]:\n ".$row[2]);
    $remark = quote_runtime($row2[3]."\n+ Nr. $row[0]:\n ".$row[3]);
    // update new record
    $result = db_query(xss("update ".DB_PREFIX."rts
                           set remark = '$remark',
                               note='$note',
                               name = '$name'
                         where ID = '$moveto'")) or db_die();
    // put a remark into the old record that it has moved
    $result = db_query(xss("update ".DB_PREFIX."rts
                           set remark = 'moved to $moveto'
                         where ID = '$ID'")) or db_die();
  }
}

// show the helpdesk list :-)
$fields = build_array('helpdesk', $ID, 'view');
include_once("./helpdesk_view.php");

function delete_record($ID) {
  global $fields, $user_ID;
    // check permission
  $result = db_query("select assigned, acc_write,ID,von
                        from ".DB_PREFIX."rts
                       where ID = '$ID'") or db_die();
  $row = db_fetch_row($result);
  if ($row[2] == 0) { die("no entry found."); }
  if ($row[0] <> $user_ID and !$row[1] and (!(($row[0]==0 or $row[0]=='')and $row[3]==$user_ID ))) { die("You are not privileged to do this!"); }

  // delete all files associated with this record
  foreach($fields as $field_name => $field) {
    if ($field['form_type'] == 'upload' ) {
      $sql_value = upload_file_delete($field_name, $ID, 'helpdesk');
    }
  }
  // delete record in db
  $result = db_query("delete from ".DB_PREFIX."rts
                       where ID = '$ID'") or db_die();
  // delete corresponding entry from db_record
  $result = db_query("delete from ".DB_PREFIX."db_records
                            where t_record = '$ID' and t_module = 'rts'") or db_die();

  // delete history for this db entry
  if (PHPR_HISTORY_LOG) { history_delete('rts',$ID); }
}

?>

</body>
</html>
