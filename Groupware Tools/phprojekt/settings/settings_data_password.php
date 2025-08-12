<?php

// settings_data_password.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: settings_data_password.php,v 1.4 2005/06/15 16:47:27 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use settings.php!');


// fetch password from this user from database
$result = db_query("SELECT pw
                      FROM ".DB_PREFIX."users
                     WHERE ID = '$user_ID'") or db_die();
$row = db_fetch_row($result);

// encrypt password if this option is set in config
if (PHPR_PW_CRYPT) $password2 = encrypt($password, $row[0]);
// ...or leave like it is
else               $password2 = $password;

//  if the password doesn't fit, display error message
if ( $password2 <> $row[0]) {
    $pout .= '<h5>'.__('Wrong password').'!</h5>';
    $action = 0;
}

// dialog for random gen. pw's
if ($action == '1') {
    // create a new random generated password
    $pw = rnd_string();
    $pout .= '<br /><h5>'.__('Suggestion').': '.$pw.'</h5>';
    $newpw1 = $pw;
    $_SESSION['newpw1'] =& $newpw1;
    // link to new string
    $pout .= '<a href="./settings.php?password='.$password.'&amp;action=1&amp;mode=password'.$sid.
             '" title="'.__('Generate a new password').'">'.__('Generate a new password').'</a><br />';
    // link to save this string with confirmation
    $pout .= '<a href="./settings.php?password='.$password.'&amp;action=2&amp;mode=password'.$sid.
             '" title="'.__('Are you sure?').'" onclick="return confirm(\''.__('Are you sure?').'\');">'.
             __('Save password').'</a><hr />';
}
// proof pw and write it to db
else if ($action == '2') {
    // check whether the new password has more than 4 chars
    if (strlen($newpw1) < 5) {
        $pout .= '<h5>'.__('The new password must have 5 letters at least').'!</h5>';
    }
    // check here whether the user retyped the same word
    else if (PHPR_PW_CHANGE == 2 and $newpw1 <> $newpw2) {
        $pout .= '<h5>'.__('You didnt repeat the new password correctly').'!</h5>';
    }
    // everythings fine? -> store new password in db and session
    else {
        if (PHPR_PW_CRYPT) {
            $newpasswd = encrypt($newpw1, $newpw1);
        }
        else {
            $newpasswd = $newpw1;
        }
        // update record in database
        $result = db_query("UPDATE ".DB_PREFIX."users
                               SET pw = '$newpasswd'
                             WHERE ID = '$user_ID'") or db_die();
        $pout = __('Your new password has been stored');

        // store new password in session
        $user_pw = $newpw1;
        unset($newpw1);
        $_SESSION['user_pw'] =& $user_pw;
    }
}

?>
