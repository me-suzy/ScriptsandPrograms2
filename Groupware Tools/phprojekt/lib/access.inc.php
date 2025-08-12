<?php

// access.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: access.inc.php,v 1.4 2005/07/04 09:27:15 nina Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) {
    die('Please use index.php!');
}


//  access selection
function assign_acc($acc, $table) {
    global $profil, $persons, $parent, $user_ID;

    // profile
    if ($acc == '4') {
        $result = db_query("SELECT personen
                              FROM ".DB_PREFIX."profile
                             WHERE ID = '$profil'") or db_die();
        $row = db_fetch_row($result);
        $acc = $row[0];
    }

    // option "same access as directory"?
    else if ($acc == 'same_as_parent') {
        $result = db_query("SELECT acc
                              FROM ".DB_PREFIX.$table."
                             WHERE ID = '$parent'") or db_die();
        $row = db_fetch_row($result);
        if ($parent > 0) $acc = $row[0];
        // no parent directory found? -> private
        else             $acc = 'private';
    }

    // manual selection of users in this group
    else if ($acc == '3') {
       	$persons[]=slookup('users','kurz','ID',$user_ID);
       	$acc = serialize($persons);
    }

    // else: personal access or access for all -> leave value
    // -> no action.

    return $acc;
}

?>
