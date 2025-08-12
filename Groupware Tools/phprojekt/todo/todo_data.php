<?php

// todo_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: todo_data.php,v 1.24.2.2 2005/08/26 06:03:12 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use todo.php!");

// check role
if (check_role("todo") < 2) die("You are not allowed to do this!");


// fetch permission routines
include_once($lib_path."/permission.inc.php");
$include_path3 = $path_pre."lib/access.inc.php";
include_once($include_path3);
$access = assign_acc($acc, 'todo');

switch (true) {

    case ($cancel):
        break;

    // undertake todo
    case ($undertake==1):
        //check whether this todo is still free ..
        $result = db_query("SELECT ext
                              FROM ".DB_PREFIX."todo
                             WHERE ID = '$ID'") or db_die();
        $row = db_fetch_row($result);
        if ($row[0] > 0) {}
        else {
            // assign this todo to the current user
            $result = db_query("UPDATE ".DB_PREFIX."todo
                                   SET ext = '$user_ID',
                                       sync2 = '$dbTSnull',
                                       status = '3'
                                 WHERE ID = '$ID'") or db_die();
        }
        break;

    case ($delete_b):
        if ($ID > 0)          manage_delete_records($ID, $module);
        else if ($ID_s <> '') manage_delete_records($ID_s, $module);
        break;

    case ($delete <> ''):
        $result = db_query("SELECT von, ext
                              FROM ".DB_PREFIX."todo
                             WHERE ID = '$ID'") or db_die();
        $row = db_fetch_row($result);
        // recipients with chef status can delete todos
        if ($row[1] == $user_ID and ereg("c", $user_access)) {}
        // 2. check permission if the user is the author of this todo
        else if ($row[0] == $user_ID) {}
        // 3. deny deletion if the suer is not the recipient.
        else die("You are not allowed to do this");

        // delete request
        $result = db_query("DELETE FROM ".DB_PREFIX."todo
                                  WHERE ID = '$ID'") or db_die();
        // delete corresponding entry from db_record
        $result = db_query("delete from ".DB_PREFIX."db_records
                                  where t_record = '$ID' and t_module = 'todo'") or db_die();
        break;

    case ($step == "create"):
        // Status - if it an own todo, put it to accepted, otherwise to open
        if ($ext == $user_ID) $status = 3;
        // at the moment of creation the progress must be 0
        $progress = 0;

        // assign access
        if ($acc_write <> '') $acc_write = 'w';

        // create record in db
        $_POST['von'] = $user_ID; // userID should not be set from outside
        sqlstrings_create();
        $result = db_query(xss("INSERT INTO ".DB_PREFIX."todo
                                            (   ID,     status,   sync1,      sync2,      gruppe,       acc,       acc_write,  ".$sql_fieldstring.")
                                     VALUES ($dbIDnull,'$status','$dbTSnull','$dbTSnull','$user_group','$access','$acc_write', ".$sql_valuestring.")")) or db_die();
        unset($von);


        // notify recipient about the new todo
        if ($notify_recipient <> '' and $ext > 0) {
            // include the library from lib
            include_once("$lib_path/email_notification.inc.php");
            $recipient = array(slookup('users', 'kurz', 'ID', $ext));
            email_notification(__('New todo'), serialize($recipient),
                               __('New todo').": ".$remark."\n".__('From').": ".
                               slookup('users', 'nachname,vorname', 'ID', $user_ID)."\n".$note);
        }
        break;

    case ($step == "update_progress"):
        // check whether this user is allowed to update the prgress
        $result = db_query("SELECT ext
                              FROM ".DB_PREFIX."todo
                             WHERE ID = '$ID'") or db_die();
        $row = db_fetch_row($result);
        if ($row[0] <> $user_ID) die("You are not allowed to do this!");
        // otherwise update the progress :-)
        $result = db_query("UPDATE ".DB_PREFIX."todo
                               SET progress = '$progress',
                                   sync2 = '$dbTSnull'
                             WHERE ID = '$ID'") or db_die();
        break;

    case ($ID > 0):
        // check permission
        $result = db_query("SELECT von, ext, status
                              FROM ".DB_PREFIX."todo
                             WHERE ID = '$ID'") or db_die();
        $row = db_fetch_row($result);
        // 1. update of the whole record - only the author
        if ($row[0] == $user_ID)  {
            // workaround if $status is not set
            if (!$status) $status = $row[2];
            if ($ext == $user_ID && $row[1] != $user_ID) $status = 3;
            // an owner of a todo can stop the todo without deleting it:
            if ($todo_done <> "") $status = 5;

            $accessstring = "acc = '$access',";
            if ($acc_write <> '') $accesswritestring = "acc_write = 'w',";
            else                  $accesswritestring = "";

            // update record in db
            $sql_string = sqlstrings_modify();
            $result = db_query(xss("UPDATE ".DB_PREFIX."todo
                                       SET $accessstring
                                           $accesswritestring
                                           $sql_string 
                                           sync2 = '$dbTSnull',
                                           status = '$status',
                                           comment1 = '$comment1'
                                     WHERE ID = '$ID'")) or db_die();
        }
        // 2. case - only update of the status
        if ($row[1] == $user_ID) {
            if ($status <> '' or $todo_done <> '') {
                // security check: make sure the assigned user hasn't hacked the form
                if ($status < 3) $status = $row[2];
                if ($progress == '100') $status = 5;
                // an owner of a todo can stop the todo without deleting it:
                if ($todo_done <> "") $status = 5;
                // if the user has clicked 'done', set the status to value 5
                if ($todo_done <> "") {
                    $status   = 5;
                    $progress = 100;
                }
                $result = db_query("UPDATE ".DB_PREFIX."todo
                                       SET status = '$status',
                                           sync2 = '$dbTSnull'
                                     WHERE ID = '$ID'") or db_die();
            }
            if ($progress <> "") {
                $result = db_query("UPDATE ".DB_PREFIX."todo
                                       SET progress= '$progress',
                                           sync2 = '$dbTSnull'
                                     WHERE ID = '$ID'") or db_die();
            }
            // update comment and flag for sync1 in any case
            $result = db_query(xss("UPDATE ".DB_PREFIX."todo
                                       SET comment2 = '$comment2',
                                           sync2 = '$dbTSnull'
                                     WHERE ID = '$ID'")) or db_die();
        }
        break;
}


// show the todo list :-)
$status = 0;


if (!$justform) {
    $fields = build_array($module, $ID, 'view');
    include_once("./todo_view.php");
}
else {
    echo '<script type="text/javascript">self.opener.location.reload();self.close()</script>';
}


#if (!$justform) {
#    $fields = build_array($module, $ID, 'view');
#    include_once("./todo_view.php");
#}
#else include_once("./todo_forms.php");


function delete_record($ID) {
    global $fields, $user_ID;

    $result = db_query("SELECT von, ext
                          FROM ".DB_PREFIX."todo
                         WHERE ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    // recipients with chef status can delete todos
    if ($row[1] == $user_ID and ereg("c",$user_access)) {}
    // 2. check permission if the user is the author of this todo
    else if ($row[0] == $user_ID) {}
    // 3. deny deletion if the suer is not the recipient.
    else die("You are not allowed to do this");

    // delete request
    $result = db_query("DELETE FROM ".DB_PREFIX."todo
                              WHERE ID = '$ID'") or db_die();
    // delete corresponding entry from db_record
    $result = db_query("delete from ".DB_PREFIX."db_records
                              where t_record = '$ID' and t_module = 'todo'") or db_die();
}

?>
