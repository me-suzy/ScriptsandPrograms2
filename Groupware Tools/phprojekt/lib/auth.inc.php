<?php

// auth.inc.php - PHProjekt Version 5.0
// copyright    2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: auth.inc.php,v 1.30.2.3 2005/09/21 08:29:20 fgraf Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) die('Please use index.php!');


include($path_pre.'lib/languages.inc.php');

$fetch_uservalues = 0;
// fetch language ...
$lang_found = 0;

// no language in settings -> choose browser language
if (!isset($langua)) {
    $langua = getenv('HTTP_ACCEPT_LANGUAGE');
    foreach ($languages as $langua1 => $langua2) {
        if (eregi($langua1,$langua)) {
            $langua = $langua1;
            $lang_found = 1;
        }
    }
    if ($lang_found) {
        include($path_pre.'lang/'.$langua.'.inc.php');
    }
    else {
        $langua = 'en';
        include($path_pre.'lang/en.inc.php');
    }
}

// set default skin
if (!isset($skin)) {
    $skin = PHPR_SKIN;
}

// check for the appropiate login field ...
if (!PHPR_LOGIN_SHORT) {
    $label = __('Last name');
    $field_name = 'nachname';
}
else if (PHPR_LOGIN_SHORT == '1') {
    $label = __('Short name');
    $field_name = 'kurz';
}
else if ((PHPR_LOGIN_SHORT == '2') || (PHPR_LDAP == '1')) {
    $label=__('Login name');
    $field_name = 'loginname';
}

// no values from the session or the login form?-> show login form
if (!$user_pw and !$user_name) {
    set_style();
    if ($logintoken) {
        $token = encrypt($logintoken, $logintoken);
        $query = "SELECT l.user_ID, l.valid, l.used, l.ID, u.status
                    FROM ".DB_PREFIX."logintoken l, ".DB_PREFIX."users u
                   WHERE l.token = '$token'
                     AND l.user_ID = u.ID
                     AND u.status = 0";
        $result = db_query($query);
        $now = time();
        $row = db_fetch_row($result);
        //if ($now > mktime(substr($row[1], 8, 2), substr($row[1], 10, 2), substr($row[1], 12, 2), substr($row[1], 4, 2), substr($row[1], 6, 2), substr($row[1], 0, 4))) {
        if ($row[4] == '1') {
            // append return path to redirect the user to where he wanted to go
            $return_path = urlencode($_REQUEST['return_path'] ? '?return_path='.$_REQUEST['return_path'] : '');
            die(set_page_header().__('Sorry you are not allowed to enter.')."!<br /><a href='index.php$return_path'>".__('back')."</a> ...\n</body>\n</html>\n");
        }
        else if ($now > $row[1]) {
// FIXME: what is the next echo good for?!
            //echo "now: $now und dann: $row[1]";
            die(__('Your token has already been expired.'));
        }
        else if ($row[2] <> '') {
            die(__('Your token has already been used.<br>If it wasnt you, who used the token please contact your administrator.'));
        }
        else {
            $fetch_uservalues = $row[0];
            $query = xss("UPDATE ".DB_PREFIX."logintoken
                         SET used = '".date('YmdHis', $now + PHPR_TIMEZONE*3600)."'
                       WHERE ID = '$row[3]'");
            $result = db_query($query) or db_die();
        }
        // end check for &pw
    }
    if (!$fetch_uservalues) {
        // see whether a welcome screen exists
        if (is_file($path_pre.'img/welcome.jpg')) $background = 'background="'.$img_path.'/welcome.jpg"';
        // if not, put the logo above
        else $logo_img = $path_pre.'img/logo_ng.gif';
        // show form
        include_once($path_pre.'lib/authform.inc.php');
        exit;
    }
}
// values exist -> check authentication
else {
    // add additional condition if logged to the admin section
    if ($file == 'admin') $admin_login = "AND acc LIKE '%a%'";
    else                  $admin_login = '';

    // use ldap?
    if (PHPR_LDAP == '1') {
        $includefile4 = $path_pre.'lib/ldap_auth.inc.php';
        include_once $includefile4;
    }
    // normal authentication system
    else {
        $query = "SELECT ID, pw
                    FROM ".DB_PREFIX."users
                   WHERE ".qss($field_name)." = '$loginstring'
                     AND status = 0
                     $admin_login";
        $result = db_query($query);
        // loop through all names in the table users and check password
        while ($row = db_fetch_row($result)) {
            // check for password encryption and if yes, crypt the value from the form
            if ($user_pwenc) {
                $enc_pw = $user_pwenc;
            }
            else if (PHPR_PW_CRYPT && !isset($_SESSION['user_pw'])) {
                $enc_pw = encrypt($user_pw, $row[1]);
            }
            // just the unencrypted password
            else {
                $enc_pw = $user_pw;
            }
            // great! I found an entry for you!
            if ($row[1] == $enc_pw) {
                // store the found user_ID
                $fetch_uservalues = $row[0];
            } // end check for &pw
        } // end loop over all found users with the same loginstring
    } // end case for non-ldap auth
} // end else bracket for authentication

// no record found? -> display error message
if (!$fetch_uservalues) {
    // destroy the session - on some system the first, on some system the second function doesn't work :-))
    @session_unset();
    @session_destroy();
    // append return path to redirect the user to where he wanted to go
    $return_path = $_REQUEST['return_path'] ? '?return_path='.urlencode($_REQUEST['return_path']) : '';
    if (defined('soap_request')) soapFaultDie(__('Sorry you are not allowed to enter.'), __('Sorry you are not allowed to enter.'));
    die(set_page_header().__('Sorry you are not allowed to enter.')."! <br /><a href='index.php".$return_path.">".__('back')."</a> ...</body></html>\n");
}
// fetch the user values and store them in the session!
else {
    // fetch the data ...
    $result = db_query("SELECT ID, vorname, nachname, kurz, email, loginname,
                               sms, gruppe, settings, acc, sprache, pw
                          FROM ".DB_PREFIX."users
                         WHERE ID = '$fetch_uservalues'") or db_die();
    $row = db_fetch_row($result);
    // fill the user data into variables
    if ($logintoken) {
        $loginstring = $row[5];
        $user_pwenc  = $row[11];
    }
    $user_ID        = $row[0];
    $user_firstname = $row[1];
    $user_name      = $row[2];
    $user_kurz      = $row[3];
    $user_email     = $row[4];
    $user_loginname = $row[5];
    $user_smsnr     = $row[6];  //sms nr
    // overwrite the found language of the browser with the amdin setting
    if ($row[10] <> '') $langua = $row[10];
    // Take the default group from the data set unless the user has chosen another one during the session
    if (!$user_group) $user_group = $row[7] + 0;

    // fetch settings
    $settings = unserialize($row[8]);
    if ($settings) {
        foreach($settings as $key => $value) {
            if ($value <> '') $$key = $value;
        }
    }
/*
    // do the date format stuff
    require_once($path_pre.'lib/date_format.php');
    $user_date_format = new PHProjekt_Date_Format($date_format);
    $date_format      = $user_date_format->get_user_format();
*/
    // fetch access: first character is for the user status, second one for the visibility of his calendar
    $user_access = $row[9];
    // have a look into the group table: maybe he's the leader of the group _-> declare him as chief ;-)
    if ($user_group > 0) {
        $result2 = db_query("SELECT chef
                               FROM ".DB_PREFIX."gruppen
                              WHERE ID = '$user_group'") or db_die();
        $row2 = db_fetch_row($result2);
        if ($row2[0] == $user_ID) $user_access = 'c'.substr($user_access, 0, 1);
    }
    unset ($row);
    include_once($path_pre.'lang/'.$langua.'.inc.php');

    // set time
    $dbTSnull = date('YmdHis', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
    // track the login
    if (PHPR_LOGS and !$logID and $user_ID) {
        $result2 = db_query(xss("INSERT INTO ".DB_PREFIX."logs
                                         (   ID,     von,      login  )
                                  VALUES ($dbIDnull, '$user_ID', '$dbTSnull')")) or db_die();
        // store logID for the logout
        $result2 = db_query("SELECT ID
                               FROM ".DB_PREFIX."logs
                              WHERE von = '$user_ID'
                                AND login = '$dbTSnull'") or db_die();
        $row2 = db_fetch_row($result2);
        $logID = $row2[0];
    }
    // crypt password in session
    $user_pw = $enc_pw;

    // register user variables in session
    $_SESSION['user_ID'] =& $user_ID;
    $_SESSION['user_name'] =& $user_name;
    $_SESSION['user_firstname'] =& $user_firstname;
    $_SESSION['user_pw'] =& $user_pw;
    $_SESSION['user_group'] =& $user_group;
    $_SESSION['user_kurz'] =& $user_kurz;
    $_SESSION['user_access'] =& $user_access;
    $_SESSION['user_loginname'] =& $user_loginname;
    $_SESSION['user_email'] =& $user_email;
    $_SESSION['langua'] =& $langua;
    $_SESSION['loginstring'] =& $loginstring;
    $_SESSION['user_pwenc'] =& $user_pwenc;
    $_SESSION['logID'] =& $logID;
    $_SESSION['user_smsnr'] =& $user_smsnr;

}

?>
