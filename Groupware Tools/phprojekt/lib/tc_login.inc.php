<?php

// permission.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: tc_login.inc.php,v 1.6 2005/06/20 15:03:44 paolo Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) {
    die('Please use index.php!');
}


function show_timecard_button() {
    global $user_ID, $sid, $img_path;

    // fetch current date and time
    $datum = date('Y-m-d', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
    $time  = date('H:i',   mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));


    // fetch an entry of this user from today where the record hasn't been completed (means: the user is still in the office)
    $result = db_query("SELECT ID
                          FROM ".DB_PREFIX."timecard
                         WHERE datum = '$datum'
                           AND (ende = '' OR ende IS NULL)
                           AND users = '$user_ID'") or db_die();
    $row = db_fetch_row($result);
    // buttons for 'come' and 'leave', alternate display
    if ($row[0] > 0) {
    // button 'leave' only if one record from today is open
        echo "href='../index.php?module=timecard&mode=data&action=2".$sid."' target='_top'><img src='$img_path/tc_logout.gif' alt='".__('End')."' title='".__('End')."' border=0></a>\n";
    }
    else {
        // 'come' button only if the user is not logged into the timecard today
        echo "href='../index.php?module=timecard&mode=data&action=1".$sid."' target='_top'><img src='$img_path/tc_login.gif' alt='".__('Begin')."' title='".__('Begin')."' border=0></a>\n";
    }
}

?>
