<?php

// contacts_profiles_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: contacts_profiles_data.php,v 1.11 2005/07/22 13:56:38 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");


if ($loeschen) {
    if ($ID == "proxy") update_proxy('');
    else                delete_profile();
}
else if ($db_neu) {
    insert_profile();
}
else if ($db_aendern) {
    if ($ID == "proxy") update_proxy($s);
    else                update_profile();
}
else if ($use_profile) {
    use_profile($ID);
}

include_once("./contacts_profiles_forms.php");


function delete_profile() {
    global $ID, $lib_path, $action;

    if ($ID <> '') {
        // check permission
        include_once($lib_path."/permission.inc.php");
        if ($action == "contacts") {
            check_permission("contacts_profiles", "von", $ID);
            // delete record in db
            $result = db_query("DELETE FROM ".DB_PREFIX."contacts_profiles
                                      WHERE ID = '$ID'") or db_die();
            // delete all entries in the other table
            $result = db_query("DELETE FROM ".DB_PREFIX."contacts_prof_rel
                                      WHERE contacts_profiles_ID = '$ID'") or db_die();
        }
// TODO: this should be removed from here. profiles are administrated in the settings.
        else {
            check_permission("profile", "von", $ID);
            // delete record in db
            $result = db_query("DELETE FROM ".DB_PREFIX."profile
                                      WHERE ID = '$ID'") or db_die();
        }
// END TODO
        message_stack_in(__('The profile has been deleted.'), "profiles", "notice");
    }
}


function check_values() {
    global $error, $name, $s, $user_ID, $ID, $action;

    // forgot to give a name?
    if (!$name) {
        $error = 1;
        message_stack_in(__('Please specify a description! '), "profiles", "error");
    }
    //forgot to select at least one record?
    if (!$s) {
        $error = 1;
        message_stack_in(__('Please select at least one name! '), "profiles", "error");
    }
    // check whether this name already exists
    if ($action == "contacts") {
        $result = db_query("SELECT name
                              FROM ".DB_PREFIX."contacts_profiles
                             WHERE von = '$user_ID'
                               AND ID <> '$ID'") or db_die();
    }
// TODO: this should be removed from here. profiles are administrated in the settings.
    else {
        $result = db_query("SELECT bezeichnung
                              FROM ".DB_PREFIX."profile
                             WHERE von = '$user_ID'
                               AND ID <> '$ID'") or db_die();
    }
// END TODO
    while ($row = db_fetch_row($result)) {
        if ($row[0] == $name) {
            message_stack_in(__('This name already exists'),"profiles","error");
            $error = 1;
        }
    }
}


function insert_profile() {
    global $error, $dbIDnull, $user_ID, $name, $remark;
    global $kategorie, $acc, $s, $ID, $user_ID, $action;

    check_values();

    if (!$error) {
        // insert profile for contacts
        if ($action == "contacts") {
            // insert record itself
            $result = db_query(xss("INSERT INTO ".DB_PREFIX."contacts_profiles
                                                (   ID,        von,       name,    remark )
                                         VALUES ($dbIDnull, '$user_ID', '$name', '$remark')")) or db_die();
            if ($result) {
                message_stack_in($name . __(' is created as a profile.<br>'),"profiles","notice");
            }
            // fetch ID from last insert
            $result = db_query("SELECT ID
                                  FROM ".DB_PREFIX."contacts_profiles
                                 WHERE von  = '$user_ID'
                                   AND name = '$name'") or db_die();
            $row = db_fetch_row($result);
            // insert the new values
            foreach($s as $s1) {
                $result = db_query(xss("INSERT INTO ".DB_PREFIX."contacts_prof_rel
                                                    (ID, contact_ID, contacts_profiles_ID)
                                             VALUES ($dbIDnull, '$s1', '$row[0]')")) or db_die();
            }
        }
// TODO: this should be removed from here. profiles are administrated in the settings.
        // insert profile for users
        else {
            $personen = serialize_it($s);
            $result = db_query(xss("INSERT INTO ".DB_PREFIX."profile
                                                (   ID,       von, bezeichnung, personen )
                                         VALUES ($dbIDnull,'$user_ID','$name','$personen')")) or db_die();
            if ($result) {
                message_stack_in($name . __(' is created as a profile.<br>'),"profiles","notice");
            }
        }
// END TODO
    }
}


function update_profile() {
    global $error, $dbIDnull, $user_ID, $name, $remark, $kategorie, $acc, $s, $ID, $action;

    check_values();

    if (!$error) {
        if ($action == "contacts") {
            // update relatd entries:
            // 1. delete all old entries
            $result = db_query("DELETE FROM ".DB_PREFIX."contacts_prof_rel
                                      WHERE contacts_profiles_ID = '$ID'") or db_die();
            // 2. insert the new values
            foreach($s as $s1) {
                $result = db_query(xss("INSERT INTO ".DB_PREFIX."contacts_prof_rel
                                                    (ID, contact_ID, contacts_profiles_ID)
                                             VALUES ($dbIDnull, '$s1', '$ID')")) or db_die();
            }
            // update record itself
            $result = db_query(xss("UPDATE ".DB_PREFIX."contacts_profiles
                                       SET name='$name',
                                           remark='$remark'
                                     WHERE ID='$ID'")) or db_die();
            if ($result) {
                message_stack_in($name . __('is changed.<br>'),"profiles","notice");
            }
        }
// TODO: this should be removed from here. profiles are administrated in the settings.
        else {
            $personen = serialize_it($s);
            $result = db_query(xss("UPDATE ".DB_PREFIX."profile
                                       SET bezeichnung='$name',
                                           personen='$personen'
                                     WHERE ID='$ID'")) or db_die();
            if ($result) {
                message_stack_in($name . __('is changed.<br>'),"profiles","notice");
            }
        }
// END TODO
    }
}


function serialize_it($s) {
    $personen = serialize($s);
    return $personen;
}


function update_proxy($s){
    global $user_ID, $name;

    if ($s) $pers = serialize($s);
    $result = db_query(xss("UPDATE ".DB_PREFIX."users
                               SET proxy = '$pers'
                             WHERE ID = '$user_ID'")) or db_die();
    if ($result) {
        message_stack_in($name . __('is changed.<br>'),"profiles","notice");
    }
}


function use_profile($ID) {
    global $flist, $remark;

    unset($flist['contacts']);
    $filters = explode('|', $remark);
    foreach ($filters as $filter) {
        if ($filter <> '') {
            $flist['contacts'][] = explode(' ', $filter);
        }
    }
    $_SESSION['flist'] =& $flist;
}

?>
