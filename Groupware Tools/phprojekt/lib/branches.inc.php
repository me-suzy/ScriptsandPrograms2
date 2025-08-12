<?php

// branches.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: branches.inc.php,v 1.8 2005/06/20 15:07:45 paolo Exp $


$project_fields = array( 'name',
                         'ende',
                         'personen',
                         'wichtung',
                         'status',
                         'statuseintrag',
                         'anfang',
                         'gruppe',
                         'chef',
                         'typ',
                         'parent',
                         'ziel',
                         'note',
                         'kategorie',
                         'contact',
                         'stundensatz',
                         'budget',
                         'div1',
                         'div2',
                         'acc',
                         'acc_write',
                         'von' );


function copy_branch($ID, $new_parent_rootID) {
    // first check whether the target isn't a subrecord of the source
    check_subrecord($ID,$new_parent_rootID);
    // first copy the root record
    $new_ID = copy_record($ID,$new_parent_rootID);
    // now fetch all children and walk thorugh the whole branch
    fetch_children($ID, $new_ID);
}


function fetch_children($old_parent_ID, $new_parent_ID) {
    $result = db_query("SELECT ID
                          FROM ".DB_PREFIX."projekte
                         WHERE parent = '$old_parent_ID'") or db_die();
    while ($row = db_fetch_row($result)) {
        $old_ID = $row[0];
        $new_ID = copy_record($row[0], $new_parent_ID);
        fetch_children($old_ID, $new_ID);
    }
}


function copy_record($ID, $parent_ID) {
    global $project_fields, $dbIDnull;

    $result = db_query("SELECT ".implode(',', $project_fields)."
                          FROM ".DB_PREFIX."projekte
                         WHERE ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    // and insert this as a new record
    $result = db_query(xss("INSERT INTO ".DB_PREFIX."projekte
                                    (ID, ".implode(',', $project_fields).")
                             VALUES ($dbIDnull, '".implode("','", $row)."')")) or db_die();
    // fetch the ID of this new record
    $result = db_query("SELECT max(ID)
                          FROM ".DB_PREFIX."projekte") or db_die();
    $row = db_fetch_row($result);
    // don't forget to assign the new record to the current parent!
    $result = db_query(xss("UPDATE ".DB_PREFIX."projekte
                           SET parent = '$parent_ID'
                         WHERE ID = '$row[0]'")) or db_die();
    return $row[0];
}


function check_subrecord($ID, $new_parent_rootID) {
    $proj_err_mes  = 'Sorry but the target record is a subrecord of the source! ';
    $proj_err_mes .= 'This would lead to a vanishing project branch. Please try it again';

    // check whether the current record is the target
    if ($ID == $new_parent_rootID) {
        die("$proj_err_mes");
    }
    else {
        // loop over children
        $result = db_query("SELECT ID
                              FROM ".DB_PREFIX."projekte
                             WHERE parent = '$ID'") or db_die();
        while ($row = db_fetch_row($result)) {
            check_subrecord($row[0], $new_parent_rootID);
        }
    }
}


function move_branch($ID, $field, $days) {
    // get the value
    $result = db_query("SELECT ".qss($field)."
                          FROM ".DB_PREFIX."projekte
                         WHERE ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    $olddate = explode('-',$row[0]);
    $newdate = date('Y-m-d', mktime(0, 0, 0, $olddate[1], $olddate[2]+$days, $olddate[0]));
    $result = db_query(xss("UPDATE ".DB_PREFIX."projekte
                           SET ".qss($field)." = '$newdate'
                         WHERE ID = '$ID'")) or db_die();
    // loop over children
    $result = db_query("SELECT ID
                          FROM ".DB_PREFIX."projekte
                         WHERE parent = '$ID'") or db_die();
    while ($row = db_fetch_row($result)) {
        move_branch($row[0], $field, $days);
    }
}

?>
