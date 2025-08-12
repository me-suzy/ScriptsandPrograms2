<?php

// admin.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: admin.php,v 1.56.2.1 2005/07/29 13:35:48 nina Exp $


$file = 'admin';
$path_pre = '../';
include_once($path_pre.'lib/lib.inc.php');
include_once($path_pre.'lib/languages.inc.php');
require_once('./admin.inc.php');

$_SESSION['common']['module'] = 'admin';

// include the ldap library
if (PHPR_LDAP) {
    $include_path2 = $path_pre.'lib/ldapconf.inc.php';
    include_once($include_path2);
}

// only root can select a group
if ($group_select) {
    $group_ID = $group_select;
    $_SESSION['group_ID'] =& $group_ID;
    $sql_group = "(gruppe = '$group_ID')";
}
// a group admin is assigned to his group
else if ($user_group <> '') {
    $group_ID = $user_group;
    $_SESSION['group_ID'] =& $group_ID;
    $sql_group = "(gruppe = '$group_ID')";
}
else {
    $sql_group = "(gruppe = '$group_ID')";
}


// array with the different user modes: u = normal user, c = user with chief status, a = administrator
$acc         = array( "u" => __('Normal user'), "c" => __('User w/Chief Rights'), "a" => __('Administrator') );
$acc_level   = array( "0" => __('No access'), "1" => __('Read access'), "2" => __('Write access') );
$user_types  = array( '0' => __('User'), '1' => __('Resource') );
$user_status = array( '0' => __('Active'), '1' => __('Inactive') );
// TODO: obsolete now, remove this next time
//$vis         = array( "y" => __('schedule readable to others'), "v" => __('schedule visible but not readable'), "n" => __('schedule invisible to others') );

// Menu und content
echo set_page_header();
include_once($path_pre.'lib/navigation.inc.php');


$output .= "<div class='outer_content'>\n";
$output .= "<div class='content'>\n";

// tabs
$tabs = array();
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();
if (ereg('a', $user_access) and !$user_group) {
    $result = db_query("SELECT name
                          FROM ".DB_PREFIX."gruppen
                         WHERE ID = '$group_ID'") or db_die();
    $row = db_fetch_row($result);
    // form start
    $hidden = array();
    if (SID) $hidden[session_name()] = session_id();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'name' => 'form1');
    $buttons[] = array('type' => 'text', 'text' => __('Choose group').__(':'));
    // group select
    $tmp = "<select name='group_select' onchange='document.form1.submit();'>\n";
    // no groups selected? show him a blank entry
    //if (!$group_ID) $output .= "<option value='0'></option>\n";
    $tmp .= "<option value='0'></option>\n";
    // fetch ID and name for db
    $result = db_query("SELECT ID, name
                          FROM ".DB_PREFIX."gruppen
                      ORDER BY name") or db_die();
    while ($row = db_fetch_row($result)) {
        $tmp .= "<option value='$row[0]'";
        if ($row[0] == $group_ID) $tmp .= ' selected="selected"';
        $tmp .= ">$row[1]</option>\n";
    }
    $tmp .= "</select>\n";
    $buttons[] = array('type' => 'text', 'text' => $tmp);
    // form end
    $buttons[] = array('type' => 'form_end');
    $buttons[] = array('type' => 'separator');
    $buttons[] = array('type' => 'text', 'text' => ' (<a href="./module_designer.php?'.SID.'" target="_blank">'.__('Module Designer').'</a>)');
}
$output .= get_buttons_area($buttons);

$output .= "<div class='hline'></div>\n";

// Actions after the dialog
// ******************
// right frame
// ******************
//$output .= set_page_header();
$output .= "<div class='admin_right'>\n";
$output .= "<div class='admin_header'></div>\n";
$output .= "<div class='admin_fields'>\n";
//***************************
// output messages of actions
$output .= "<br class='clear' />\n";


/**
*
*   group management
*
*/
if ($action1 == "groups") {
    // db action
    if ($mode == "anlegen") {
        if (!$name) {
            $output .= __('Please insert a name')."!<br class='clear' />\n";
        }
        else {
            $result = db_query("SELECT name, kurz
                                  FROM ".DB_PREFIX."gruppen
                                 WHERE ID <> '$group_nr'
                              ORDER BY name") or db_die();
            while ($row = db_fetch_row($result)) {
                if ($row[0] == $name) {
                    $output .= __('Name or short form already exists')."!";
                    $error   = 1;
                    $anlegen = '';
                }
                // don't check for existing short name when you want to modify the record
                if ($neu and $row[1] == $kurz) {
                    $output .= __('Name or short form already exists')."!";
                    $error   = 1;
                    $anlegen = '';
                }
            }
        }

        if (!$error) {
            if ($neu) {
                // write record to db
                $result = db_query("INSERT INTO ".DB_PREFIX."gruppen
                                                (   ID,      name,   kurz,   kategorie,   bemerkung )
                                         VALUES ($dbIDnull,'$name','$kurz','$kategorie','$bemerkung')") or db_die();
                $output .= "$name: ".__('Group created').".<br class='clear' />\n";
            }
            // modify record
            else if ($aendern) {
                $result = db_query("UPDATE ".DB_PREFIX."gruppen
                                       SET name = '$name',
                                           kategorie = '$kategorie',
                                           bemerkung = '$bemerkung',
                                           chef = '$chef'
                                     WHERE ID = '$group_nr'") or db_die();
            }
        }
    }
    $group_ID = $group_nr;
    $_SESSION['group_ID'] =& $group_ID;

    // merge group
    if ($loeschen1) {
        // fetch name of group to delete
        $result = db_query("SELECT name
                              FROM ".DB_PREFIX."gruppen
                             WHERE ID = '$group_nr'") or db_die();
        $row = db_fetch_row($result);
        $name = $row[0];

        // update tables
        if (PHPR_CONTACTS) {
            $result = db_query("UPDATE ".DB_PREFIX."contacts
                                   SET gruppe = '$merge_target'
                                 WHERE gruppe = '$group_nr'") or db_die();
        }
        if (PHPR_FILEMANAGER) {
            $result = db_query("UPDATE ".DB_PREFIX."dateien
                                   SET gruppe = '$merge_target'
                                 WHERE gruppe = '$group_nr'") or db_die();
        }
        if (PHPR_FORUM) {
            $result = db_query("UPDATE ".DB_PREFIX."forum
                                   SET gruppe = '$merge_target'
                                 WHERE gruppe = '$group_nr'") or db_die();
        }
        if (PHPR_BOOKMARKS) {
            $result = db_query("UPDATE ".DB_PREFIX."lesezeichen
                                   SET gruppe = '$merge_target'
                                 WHERE gruppe = '$group_nr'") or db_die();
        }
        if (PHPR_PROJECTS) {
            $result = db_query("UPDATE ".DB_PREFIX."projekte
                                   SET gruppe = '$merge_target'
                                 WHERE gruppe = '$group_nr'") or db_die();
        }
        if (PHPR_RTS) {
            $result = db_query("UPDATE ".DB_PREFIX."rts_cat
                                   SET gruppe = '$merge_target'
                                 WHERE gruppe = '$group_nr'") or db_die();
        }

        // update table for assign user to groups, avoid double entries
        // select members of group to be deleted
        $result = db_query("SELECT user_ID
                              FROM ".DB_PREFIX."grup_user
                             WHERE grup_ID = '$group_nr'") or db_die();
        while ($row = db_fetch_row($result)) {
            // look if this user is already member of the target group
            $result2 = db_query("SELECT ID
                                   FROM ".DB_PREFIX."grup_user
                                  WHERE user_ID = '$row[0]' AND
                                        grup_ID = '$merge_target'") or db_die();
            $row2 = db_fetch_row($result2);
            // no entry? then move user to the new group
            if (!$row2[0]) {
                $result3 = db_query("UPDATE ".DB_PREFIX."grup_user
                                        SET grup_ID = '$merge_target'
                                      WHERE grup_ID = '$group_nr'
                                        AND user_ID = '$row[0]'") or db_die();
            }
            // look whether the old group is the primary group of this user and change it
            $result2 = db_query("SELECT gruppe
                                   FROM ".DB_PREFIX."users
                                  WHERE ID = '$row[0]'") or db_die();
            $row2 = db_fetch_row($result2);
            if ($row2[0] == $group_nr) {
                $result3 = db_query("UPDATE ".DB_PREFIX."users
                                        SET gruppe = '$merge_target'
                                      WHERE ID = '$row[0]'") or db_die();
            }
        }
        // last action: delete record in table gruppen and grup_user
        $result4 = db_query("DELETE FROM ".DB_PREFIX."gruppen
                                   WHERE ID = '$group_nr'") or db_die();
        $result5 = db_query("DELETE FROM ".DB_PREFIX."grup_user
                                   WHERE grup_ID = '$group_nr'") or db_die();
        $output .= "<div class='admin_fields'>$name ".__(' is deleted.')."\n<br class='clear' />\n</div>\n";
    }
}


/**
*
*   user management
*
*/
if ($action1 == "user") {

    if ($anlegen == "aendern" && isset($_REQUEST['remove_settings'])) {
        // remove users settings
        $query = "UPDATE ".DB_PREFIX."users
                     SET settings = ''
                   WHERE ID = '$pers_ID'";
        $result = db_query($query) or db_die();
        $output .= "$nachname, $vorname: ".__('Settings removed.').".<br class='clear' />\n";
    }

    else if ($anlegen) {
        // crypt password
        if (PHPR_PW_CRYPT and $pw <> "") {
            $pw = encrypt($pw, $pw);
        }
        $result = db_query("SELECT ldap_name
                              FROM ".DB_PREFIX."users
                             WHERE ID = '$pers_ID'");
        $row = db_fetch_row($result);
        if (PHPR_LDAP == 0) {
            $user_ldap_conf = 0;
        }
        else if (!isset($row[0]) || (strlen($row[0]) < 1)) {
            $user_ldap_conf = "1";
        }
        else {
            $user_ldap_conf = $row[0];
        }

        //*** checks***
        // no family name at all or pw or short name at creating? -> error!
        // ldap_sync == 2 means everything comes from LDAP
        // ldap_sync == 1 means everything comes from db therefore update
        if ((!PHPR_LDAP or (PHPR_LDAP and $ldap_conf[$user_ldap_conf]["ldap_sync"] == "1")) and
            (!$nachname or !$kurz or !$pw) and $anlegen == "neu_anlegen") {
            $output .= __('You have to fill in the following fields: family name, short name and password.');
            $error   = 1;
            $anlegen = '';
        }
        // in case ldap is activated a loginname must exist
        else if (PHPR_LDAP and (strlen($loginname) < 1) and $anlegen == "neu_anlegen") {
            $output .= __('You have to fill in the following fields: family name, short name and password.');
            $error   = 1;
            $anlegen = '';
        }

        // if admin is limited to his group, no group is specified and the new user must be in his group
        if (!$gruppe) $gruppe = $group_ID;
        // check whether default group is in group list
        $found = 0;
        for ($i = 0; $i < count($grup_user); $i++) {
            if ($gruppe == $grup_user[$i]) $found = 1;
        }
        // not selected? -> add it
        if (!$found) $grup_user[] = $gruppe;
        $where = "WHERE gruppe = '$gruppe'";

        // check for double entries
        if (!PHPR_LDAP or ($ldap_conf[$user_ldap_conf]["ldap_sync"] == "1")) {
            if ($anlegen == "aendern") {
                if ($where) $where .= " AND ";
                else        $where = "WHERE ";
                $where .= "ID <> '$pers_ID'";
            }

            $result = db_query("SELECT ID, vorname, nachname, kurz, gruppe, pw, loginname
                                  FROM ".DB_PREFIX."users
                                  $where") or db_die();
            while ($row = db_fetch_row($result)) {
/* taken out of the script since if is not used anymore!
                // password must be unique
                if ($row[5] == $pw) {
                    $output .= "$admin_text34";
                    $error = 1;
                }
end taken out ... */
                // same group can't have 2 users with same first AND last name
                if ($nachname == $row[2] and $vorname == $row[1]) {
                    $output .= __('This combination first name/family name already exists.');
                    $error = 1;
                }
                if ($loginname == $row[6]) {
                    $output .= __('This login name already exists! Please chosse another one.');
                    $error = 1;
                }
                if ($kurz == $row[3]) {
                    $output .= __('This short name already exists!');
                    $error = 1;
                }
                if ($ldap_conf[$user_ldap_conf]["ldap_sync"] == "1") {
                    if (strlen($ldap_name) < 1) {
                        $output .= __('ldap name');
                        $error = 1;
                    }
                }
            }
        }
        else {
            // still check loginname
            if (strlen($loginname) < 1) {
                $output .= __('You have to fill in the following fields: family name, short name and password.');
                $error = 1;
            }
            if (strlen($ldap_name) < 1) {
                $output .= __('ldap name');
                $error = 1;
            }
        } // *** end checks

        // *** no errors? -> db action
        if (!$error) {
            // status and calendar visibility tied together
            $access = $access1.$access2;

            // *** new record
            if ($anlegen == "neu_anlegen") {
                // insert new record in db
                $result = db_query("INSERT INTO ".DB_PREFIX."users
                                                (  ID,      vorname,  nachname,   kurz,   pw,   firma,
                                                 gruppe,   email,   acc,      tel1,   tel2,   fax,
                                                 strasse,   stadt,   plz,   land,   sprache,   mobil,
                                                 loginname,   ldap_name,      anrede,   sms,   role,
                                                 hrate, remark, usertype, status )
                                         VALUES ($dbIDnull, '$vorname', '$nachname', '$kurz', '$pw', '$firma',
                                                 '$gruppe', '$email', '$access', '$tel1', '$tel2', '$fax',
                                                 '$strasse', '$stadt', '$plz', '$land', '$sprache', '$mobil',
                                                 '$loginname', '$ldap_profile', '$anrede', '$sms', '$role',
                                                 '$hrate', '".addslashes($remark)."', '$usertype', '$status')") or db_die();

                // fetch user ID from just created record
                $result = db_query("SELECT ID
                                      FROM ".DB_PREFIX."users
                                  ORDER BY ID DESC") or db_die();
                $row = db_fetch_row($result);
                // insert into grup_user
                for ($i = 0; $i < count($grup_user); $i++) {
                    $result = db_query("INSERT INTO ".DB_PREFIX."grup_user
                                                    (   ID,       grup_ID,         user_ID )
                                             VALUES ($dbIDnull, '$grup_user[$i]', '$row[0]')") or db_die();
                }

                $output .= "$nachname, $vorname: ".__('the user is now in the list.')."<br class='clear' />\n";
                //$output .= "<img src='$img_path/s.gif' width=390 height=1 vspace=2 border=0 />";
            }

            // **** modify
            else if ($anlegen == "aendern") {

                // encrypt pw
                if (!$pw) $pw_string = "";
                else      $pw_string = "pw='$pw',";

                // create query string for groups, but only if admin = root
                if (!$user_group and $pers_ID > 1) $group_string = "gruppe='$gruppe',";
                else $group_string = '';

                if ((PHPR_LDAP == 0) or ($ldap_conf[$user_ldap_conf]["ldap_sync"] == 1)) {
                    $query = "UPDATE ".DB_PREFIX."users
                                 SET vorname = '$vorname',
                                     nachname = '$nachname',
                                     firma = '$firma',
                                     $pw_string
                                     $group_string
                                     email = '$email',
                                     acc = '$access',
                                     tel1 = '$tel1',
                                     tel2 = '$tel2',
                                     fax = '$fax',
                                     strasse = '$strasse',
                                     stadt = '$stadt',
                                     plz = '$plz',
                                     land = '$land',
                                     sprache = '$sprache',
                                     mobil = '$mobil',
                                     loginname = '$loginname',
                                     ldap_name = '$ldap_name',
                                     anrede = '$anrede',
                                     sms = '$sms',
                                     role = '$role',
                                     hrate = '$hrate',
                                     remark = '".addslashes($remark)."',
                                     usertype = '$usertype',
                                     status = '$status'
                               WHERE ID = '$pers_ID'";
                }
                else {
                    $query = "UPDATE ".DB_PREFIX."users
                                 SET $group_string
                                     acc = '$access',
                                     loginname = '$loginname',
                                     sms = '$sms',
                                     role = '$role',
                                     ldap_name = '$ldap_name'
                               WHERE ID = '$pers_ID'";
                }

                // update db record in users table
                $result = db_query($query) or db_die();

                // update group status, but only if admin = root
                if (!$user_group) {
                    $result = db_query("DELETE FROM ".DB_PREFIX."grup_user
                                              WHERE user_ID = '$pers_ID'") or db_die();
                    if (!$grup_user[0]) $grup_user[0] = $group_ID;
                    for ($i = 0; $i < count($grup_user); $i++) {
                        $result = db_query("INSERT INTO ".DB_PREFIX."grup_user
                                                        (   ID,      grup_ID,         user_ID )
                                                 VALUES ($dbIDnull,'$grup_user[$i]','$pers_ID')") or db_die();
                    }
                }
                $output .= "$nachname, $vorname: ".__('the data set is now modified.').".<br class='clear' />\n";
                //$output .= "<img src='$img_path/s.gif' width='390' height='1' vspace='2' border='0' />\n";
            }
        }
    }

    //**************
    // user: delete record
    if ($loeschen1) {

        // checks
        // 1. check: no user chosen
        if (!$pers_ID) {
            $output .= __('Please choose a user')."!";
            $error = 1;
        }
        // 2. check: don't delete the root!
        if ($pers_ID == 1) {
            $output .= __('Deletion of super admin root not possible')."!";
            $error = 1;
        }

        // no error? -> begin to delete ...
        if (!$error) {
            // fetch his name ..
            $result = db_query("SELECT nachname, kurz
                                  FROM ".DB_PREFIX."users
                                 WHERE ID = '$pers_ID'") or db_die();
            while ($row = db_fetch_row($result)) {
                $name = $row[0];
                $kurz = $row[1];
            }

            // warn, if he's a member of a project team
            if (PHPR_PROJECTS) {
                $result = db_query("SELECT name
                                      FROM ".DB_PREFIX."projekte
                                     WHERE personen LIKE '%$kurz%'") or db_die();
                while ($row = db_fetch_row($result)) {
                    $project_names[] = $row[0];
                }
                if ($project_names[0] <> '') {
                    $output .= "<b>$name ".__('is still listed in some projects. Please remove it.')."! (".implode(',',$project_names).")</b><br class='clear' />\n";
                }
            }

            // delete membership in groups
            $result = db_query("DELETE FROM ".DB_PREFIX."grup_user
                                      WHERE user_ID = '$pers_ID'") or db_die();

            // delete profiles
            // 1. his own
            $result = db_query("DELETE FROM ".DB_PREFIX."profile
                                      WHERE von = '$pers_ID'") or db_die();
            $output .= "$name: ".__('All profiles are deleted').". <br class='clear' />\n";
            // 2. as a participant
            $result = db_query("SELECT ID, von, bezeichnung, personen
                                  FROM ".DB_PREFIX."profile
                                 WHERE personen LIKE '%$kurz%'") or db_die();
            while ($row = db_fetch_row($result)) {
                $an = unserialize($row[3]);
                for ($i=0; $i<count($an); $i++) {
                    if ($an[$i] == $kurz) $a = $i;
                }
                unset($an[$a]);
                $an2 = serialize($an);
                $return2 = db_query("UPDATE ".DB_PREFIX."profile
                                        SET personen = '$an2'
                                      WHERE ID = '$row[0]'") or db_die();
            }
            $output .= "$name ".__('is taken out of all user profiles').".<br class='clear' />\n";

            // delete todos
            if (PHPR_TODO) {
                $result = db_query("DELETE FROM ".DB_PREFIX."todo
                                          WHERE von = '$pers_ID'") or db_die();
                $output .= "$name: ".__('All todo lists of the user are deleted').". <br class='clear' />\n";
            }

            // delete his files, links and dirs set to private
            if (PHPR_FILEMANAGER) {
                $result = db_query("SELECT ID, filename, tempname, typ, filesize, acc, remark
                                      FROM ".DB_PREFIX."dateien
                                     WHERE von = '$pers_ID'") or db_die();
                while ($row = db_fetch_row($result)) {
                    // delete files if they are set top private
                    if ($row[5] =="private") {
                        // only delete file when it is not a link
                        if ($row[4] > 0) {
                            $path = PHPR_FILE_PATH."/$row[2]";
                            unlink($path);
                        }
                        $result2 = db_query("DELETE FROM ".DB_PREFIX."dateien
                                                   WHERE ID = '$row[0]'") or db_die();
                        // look for files in the subdirectory or if it si a file with versioning
                        if ($row[3] == "d" or $row[3] == "fv") del($row[0]);
                    }
                    // if set to non-private, add a remark in the remark :)
                    else {
                        $remark = "[ $name ]".$row[6];
                        $result2 = db_query("UPDATE ".DB_PREFIX."dateien
                                                SET remark = '$remark'
                                              WHERE ID = '$row[0]'") or db_die();
                    }
                }
            }

            // delete notes set to private
            if (PHPR_NOTES) {
            $result = db_query("DELETE FROM ".DB_PREFIX."notes
                                WHERE von = '$pers_ID' AND
                                        (ext IS NULL OR ext = '0')") or db_die();
            }

            // update polls
            if (PHPR_VOTUM) {
                $result = db_query("SELECT ID, an
                                        FROM ".DB_PREFIX."votum
                                    WHERE an LIKE '%$kurz%' AND
                                            fertig NOT LIKE '%$kurz%'") or db_die();
                while ($row = db_fetch_row($result)) {
                    $ID = $row[0];
                    $an = unserialize($row[1]);
                    for ($i=0; $i<count($an); $i++) {
                        if ($an[$i] == $kurz) $a = $i;
                    }
                    unset($an[$a]);
                    $an2 = serialize($an);
                    $return2 = db_query("UPDATE ".DB_PREFIX."votum
                                            SET an = '$an2'
                                          WHERE ID = '$row[0]'") or db_die();
                }
                $output .= "$name ".__('is taken out of these votes where he/she has not yet participated').".<br class='clear' />\n";
            }

            // delete schedule
            $result = db_query("DELETE FROM ".DB_PREFIX."termine
                                      WHERE an = '$pers_ID'") or db_die();
            $output .= "$name: ".__('All events are deleted').". <br class='clear' />\n";
            // delte user itself
            $result = db_query("DELETE FROM ".DB_PREFIX."users
                                      WHERE ID = '$pers_ID'") or db_die();
            $output .= "$name: ".__('user file deleted').". <br class='clear' />\n";
            $output .= "<i>$name: ".__('bank account deleted')." ;-))</i><br class='clear' /><br class='clear' />\n";
            $output .= __('finished').".\n";
        }
    }
}


/**
*
*   role management
*
*/
if ($action1 == "roles" && PHPR_ROLES) {
    //delete
    if ($loeschen1) {
        // remove the assignment to users
        $result = db_query("UPDATE ".DB_PREFIX."users
                               SET role = ''
                             WHERE role = '$roles_ID'") or db_die();
        // delete record itself
        $result = db_query("DELETE FROM ".DB_PREFIX."roles
                                  WHERE ID = '$roles_ID'") or db_die();
        // show message
        $output .= __('Role deleted, assignment to users for this role removed').".<br class='clear' />\n";
    }

    if ($anlegen) {
        if (!$roles_ID) $roles_ID = 0;
        // check for double entries
        $result = db_query("SELECT ID
                              FROM ".DB_PREFIX."roles
                             WHERE title = '$title'
                               AND ID <> '$roles_ID'") or db_die();
        $row = db_fetch_row($result);
        if ($row[0] > 0) {
            if (($anlegen == "aendern" and $row[0] <> $roles_ID) or $anlegen == "neu_anlegen") {
                $output .= __('This name already exists');
                $error = 1;
            }
        }

        if (!$error) {
            // new
            if ($anlegen == "neu_anlegen") {
                if (!$title) die(__('Please insert a name')."!");

                $db_cols = array('ID', 'von', 'title', 'remark');
                $db_vals = array($dbIDnull, $user_ID, "'$title'", "'$remark'");
                if (PHPR_TODO) {
                    $db_cols[] = 'todo';
                    $db_vals[] = $todo_m;
                }
                if (PHPR_CALENDAR) {
                    $db_cols[] = 'calendar';
                    $db_vals[] = $calendar_m;
                }
                if (PHPR_CONTACTS) {
                    $db_cols[] = 'contacts';
                    $db_vals[] = $contacts_m;
                }
                if (PHPR_FORUM) {
                    $db_cols[] = 'forum';
                    $db_vals[] = $forum_m;
                }
                if (PHPR_CHAT) {
                    $db_cols[] = 'chat';
                    $db_vals[] = $chat_m;
                }
                if (PHPR_FILEMANAGER) {
                    $db_cols[] = 'filemanager';
                    $db_vals[] = $filemanager_m;
                }
                if (PHPR_BOOKMARKS) {
                    $db_cols[] = 'bookmarks';
                    $db_vals[] = $bookmarks_m;
                }
                if (PHPR_VOTUM) {
                    $db_cols[] = 'votum';
                    $db_vals[] = $votum_m;
                }
                if (PHPR_QUICKMAIL) {
                    $db_cols[] = 'mail';
                    $db_vals[] = $mail_m;
                }
                if (PHPR_NOTES) {
                    $db_cols[] = 'notes';
                    $db_vals[] = $notes_m;
                }
                if (PHPR_RTS) {
                    $db_cols[] = 'helpdesk';
                    $db_vals[] = $helpdesk_m;
                }
                if (PHPR_PROJECTS) {
                    $db_cols[] = 'projects';
                    $db_vals[] = $projects_m;
                }
                if (PHPR_TIMECARD) {
                    $db_cols[] = 'timecard';
                    $db_vals[] = $timecard_m;
                }
                $query = 'INSERT INTO '.DB_PREFIX.'roles
                                      ('.implode(',', $db_cols).')
                               VALUES ('.implode(',', $db_vals).')';
                $result = db_query($query) or db_die();
                $output .= " $title: ".__('The role has been created').".<br class='clear' />\n";
            }

            // modify
            if ($anlegen == "aendern") {
                if (!$title) die(__('Please insert a name')."!");

                $update_cols = '';
                if (PHPR_TODO) {
                    $update_cols .= ", todo='$todo_m'";
                }
                if (PHPR_CALENDAR) {
                    $update_cols .= ", calendar='$calendar_m'";
                }
                if (PHPR_CONTACTS) {
                    $update_cols .= ", contacts='$contacts_m'";
                }
                if (PHPR_FORUM) {
                    $update_cols .= ", forum='$forum_m'";
                }
                if (PHPR_CHAT) {
                    $update_cols .= ", chat='$chat_m'";
                }
                if (PHPR_FILEMANAGER) {
                    $update_cols .= ", filemanager='$filemanager_m'";
                }
                if (PHPR_BOOKMARKS) {
                    $update_cols .= ", bookmarks='$bookmarks_m'";
                }
                if (PHPR_VOTUM) {
                    $update_cols .= ", votum='$votum_m'";
                }
                if (PHPR_QUICKMAIL) {
                    $update_cols .= ", mail='$mail_m'";
                }
                if (PHPR_NOTES) {
                    $update_cols .= ", notes='$notes_m'";
                }
                if (PHPR_RTS) {
                    $update_cols .= ", helpdesk='$helpdesk_m'";
                }
                if (PHPR_PROJECTS) {
                    $update_cols .= ", projects='$projects_m'";
                }
                if (PHPR_TIMECARD) {
                    $update_cols .= ", timecard='$timecard_m'";
                }
                $query = "UPDATE ".DB_PREFIX."roles
                             SET title = '$title',
                                 remark = '$remark'".$update_cols."
                           WHERE ID = '$roles_ID'";
                $result = db_query($query) or db_die();
                $output .= " $title: ".__('The role has been modified').".<br class='clear' />\n";
            }
        }
    }
}


/**
*
*   actions on helpdesk categories
*
*/
if ($action1 == "rts_categories") {
    // delete
    if ($loeschen1) {
        $result = db_query("DELETE FROM ".DB_PREFIX."rts_cat
                                  WHERE ID = '$rts_cat_ID'") or db_die();
        $output .= __('Category deleted').". <br class='clear' />\n";
    }

    if ($anlegen) {
        // check for double entries
        if ($anlegen == "aendern") $mod_strg = "WHERE ID <> '$rts_cat_ID'";
        else                       $mod_strg = '';
        $result = db_query("SELECT name
                              FROM ".DB_PREFIX."rts_cat
                              $mod_strg") or db_die();
        while ($row = db_fetch_row($result)) {
            if ($name == $row[0]) {
                $output .= __('This name already exists');
                $error = 1;
            }
        }

        if (!$error) {
            // new
            if ($anlegen == "neu_anlegen") {
                if (!$name) die(__('Please insert a name')."!");
                $result = db_query("INSERT INTO ".DB_PREFIX."rts_cat
                                                (   ID,      name,   users,  gruppe )
                                         VALUES ($dbIDnull,'$name','$user','$gruppe')") or db_die();
                $output .= " $name: ".__('The category has been created').".<br class='clear' />";
            }
            // modify
            if ($anlegen == "aendern") {
                if (!$name) die(__('Please insert a name')."!");
                $result = db_query("UPDATE ".DB_PREFIX."rts_cat
                                       SET name = '$name',
                                           gruppe = '$gruppe',
                                           users = '$user'
                                     WHERE ID = '$rts_cat_ID'") or db_die();
                $output .= " $name: ".__('The category has been modified').".<br class='clear' />";
            }
        }
    }
}


/**
*
*   delete bookmarks
*
*/
if ($action == "lesezeichen") {
    if ($loeschen) {
        if (!$lesezeichen_ID) {
            $output .= __('Please select at least one bookmark')."!\n<br class='clear' />\n<a href='admin.php?".SID."'>".__('back')."</a>\n";
        }
        else {
            for ($i=0; $i < count($lesezeichen_ID); $i++) {
                $result = db_query("SELECT bezeichnung
                                      FROM ".DB_PREFIX."lesezeichen
                                     WHERE ID = '$lesezeichen_ID[$i]'") or db_die();
                while ($row = db_fetch_row($result)) {
                    $output .= "$row[0]: ".__('The bookmark is deleted').". <br class='clear' />\n";
                }
                $result = db_query("DELETE FROM ".DB_PREFIX."lesezeichen
                                          WHERE ID = '$lesezeichen_ID[$i]'") or db_die();
            }
            $output .= __('finished').".<br class='clear' />\n";
        }
    }
}


/**
*
*   groups, second dialog: create, modify, delete group
*
*/
if ($action == "groups") {
    // create a new group
    if ($neu and $mode <> "anlegen") {
        // extended value for the input field of the short name
        $output .= "<form action='admin.php' method='post' name='frm'>\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='hidden' name='action1' value='groups' />\n";
        $output .= "<input type='hidden' name='mode' value='anlegen' />\n";
        // input fields for groupname, short name and remark
        $output .= "<label class='admin_label' for='name'>".__('Group name').":</label> <input type='text' name='name' size='30' /><br class='clear' />\n";
        $output .= "<label class='admin_label' for='kurz'>".__('Short form').":</label> <input type='text' name='kurz' size='10' maxlength='10' /><br class='clear' />\n";
        $output .= "<label class='admin_label' for='bemerkung'>".__('Remark').":</label> <input type='text' name='bemerkung' size='30' /><br class='clear' />\n";
        // if you create a new group, you can't assign a chief right now: yet no users in the group!
        $output.="&nbsp;".get_go_button('button2','button','neu',__('Create'));
       // $output .= "&nbsp;<input type='submit'  class='admin_button 'name='neu' value='".__('Create')."' />\n";
        $output .= "</form>\n<br class='clear' />\n";
    }

    // modify existing group
    else if ($aendern and $mode <> "anlegen") {
        $result = db_query("SELECT ID,name,kurz,kategorie,bemerkung,chef,div1,div2
                              FROM ".DB_PREFIX."gruppen
                             WHERE ID = '$group_nr'") or db_die();
        $row = db_fetch_row($result);
        $output .= "<form action='admin.php' method='post'>\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='hidden' name='action1' value='groups' />\n";
        $output .= "<input type='hidden' name='mode' value='anlegen' />\n";
        $output .= "<input type='hidden' name='group_nr' value='$group_nr' />\n";
        // name of the group
        $output .= "<label class='admin_label' for='name'>".__('Group name').":</label> <input type='text' name='name' value='$row[1]' size='30' /><br class='clear' />\n";
        // short name can't be changed after creation -> only display
        $output .= "<label class='admin_label' for='kurz'>".__('Short form').":</label> $row[2]&nbsp;<br class='clear' />\n";
        //$output .= "$admin_text70: <input type='text' name='kategorie' value='$row[3]' size='20'><br class='clear' />\n";
        $output .= "<label class='admin_label' for='bemerkung'>".__('Remark').":</label> <input type='text' name='bemerkung' value='$row[4]' size='30' /><br class='clear' />\n";
        // select chef
        $output .= "<label class='admin_label' for='chef'>".__('Leader').":</label><select name='chef'><option value='0'></option>\n";
        $result2 = db_query("SELECT user_ID
                               FROM ".DB_PREFIX."grup_user
                              WHERE grup_ID = '$group_nr'") or db_die();
        while ($row2 = db_fetch_row($result2)) {
            // fetch name from table users
            $result3 = db_query("SELECT vorname, nachname
                                   FROM ".DB_PREFIX."users
                                  WHERE ID = '$row2[0]'") or db_die();
            $row3 = db_fetch_row($result3);
            $output .= "<option value='$row2[0]'";
            if ($row2[0] == $row[5]) $output .= ' selected="selected"';
            $output .= "> $row3[1], $row3[0]</option>\n";
        }
        $output .= "</select><br class='clear' />\n";
        $output .= "&nbsp;".get_go_button('admin_button','button','aendern',__('Modify'))."\n";
        $output .= "</form>\n<br />\n";
    }

    // confirm delete record, choose group to merge
    else if ($loeschen) {
        // delete default group forbidden
        $output .= "<form action='admin.php' method='post'>\n";
        $output .= "<br class='clear' /><br class='clear' />\n";
        $output .= "<label class='' for='merge_target'>".__('Delete group and merge contents with group').":</label><br class='clear' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='hidden' name='action1' value='groups' />\n";
        $output .= "<input type='hidden' name='group_nr' value='$group_nr' />\n";
        $output .= "<select name='merge_target'>\n";
        // show all groups except the chosen
        $result = db_query("SELECT ID, name
                              FROM ".DB_PREFIX."gruppen
                             WHERE ID <> '$group_nr'
                          ORDER BY name") or db_die();
        while ($row = db_fetch_row($result)) {
            $output .= "<option value='$row[0]'>$row[1]</option>\n";
        }
        $output .= "</select>&nbsp;\n";
        $output .=get_go_button('admin_button','button','loeschen1')."\n";
        $output .= "</form>\n<br />\n";
    }
}


/**
*
*   timecard
*
*/
if ($action == "timecard") {
    // modification
    if ($mode == "change") {
        if (!ereg("(^[0-9]*$)",$day) or !ereg("(^[0-9]*$)",$time)) {
            die("<br class='clear' /><b>".__('Please check your date and time format! ')."<br class='clear' /><a href='admin.php?".SID."'>".__('back')."</a> ...</b>");
        }
        if (strlen($time) == 3) {
            $time = "0".$time;   // Zeitangabe nur 3-stellig?
        }
        if (!checkdate($month,$day,$year)) {
            die("<br class='clear' /><b>".__('Please check the date!')." <br class='clear' /><a href='admin.php?".SID."'>".__('back')."</a> ...</b>");
        }
        if (strlen($month) == 1) {
            $month = "0".$month;   // Zeitangabe nur 3-stellig?
        }
        $datum = $year."-".$month."-".$day;
        $result = db_query("SELECT anfang,ende
                              FROM ".DB_PREFIX."timecard
                             WHERE users = '$pers' AND
                                   datum = '$datum'") or db_die();
        $row = db_fetch_row($result);
        if ($type == "ende" and $time <= $row[0]) {
            $output .= __('There is no open record with a begin time on this day!');
            $error = 1;
        }
        if ($type == "anfang" and $row[1]<>'' and $time >= $row[1]) {
            $output .= __('There is no open record with a begin time on this day!');
            $error = 1;
        }
        if (!$error) {
            $result = db_query("SELECT ID
                                  FROM ".DB_PREFIX."timecard
                                 WHERE users = '$pers' AND
                                       datum = '$datum'") or db_die();
            $row = db_fetch_row($result);
            if (!$row[0]) {
                $result = db_query("INSERT INTO ".DB_PREFIX."timecard
                                                (ID,        users,   datum )
                                         VALUES ($dbIDnull,'$pers','$datum')") or db_die();
            }
            $result = db_query("UPDATE ".DB_PREFIX."timecard
                                   SET ".qss($type)." = '$time'
                                 WHERE datum = '$datum'
                                   AND users = '$pers'") or db_die();
        }
    }

    // form
    $result = db_query("SELECT nachname, vorname
                          FROM ".DB_PREFIX."users
                         WHERE ID = '$pers'") or db_die();
    $row = db_fetch_row($result);
    $output .= "<h5>".__('Timecard').":$row[0], $row[1]</h5>\n";
    $output .= "<form action='admin.php' method='post'>\n";
    $output .= "<input type='hidden' name='action1' value='timecard' />\n";
    $output .= "<input type='hidden' name='action' value='timecard' />\n";
    $output .= "<input type='hidden' name='pers' value='$pers' />\n";
    if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />";
    $output .= "<label class='admin_label' for='month'>".__('Month').":</label> <select name='month'>\n";
    // box for month and year
    for ($a=1; $a<13; $a++) {
        $mo = date("n", mktime(0,0,0, $a, 1, $year));
        $name_of_month = $name_month[$mo];
        if ($mo == $month) {
            $output .= "<option value='$a' selected='selected'>$name_of_month</option>\n";
        }
        else {
            $output .= "<option value='$a'>$name_of_month</option>\n";
        }
    }
    $output .= "</select>&nbsp;\n";
    $y = date("Y");
    $output .= "<select name='year'>&nbsp;\n";
    for ($i=$y-2; $i<=$y+5; $i++) {
        if ( $i == $year) {
            $output .= "<option selected='selected'>$i\n";
        }
        else {
            $output .="<option>$i</option>\n";
        }
    }
    $output .= "</select>&nbsp;&nbsp;".get_go_button()."\n";
    $output .= "</form>\n<br />\n";

    // Modification form
    $output .= "<form action='admin.php' method='post'>\n";
    $output .= "<input type='hidden' name='action1' value='timecard' />\n";
    $output .= "<input type='hidden' name='action' value='timecard' />\n";
    if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
    $output .= "<input type='hidden' name='mode'  value='change' />";
    $output .= "<input type='hidden' name='pers'  value='$pers' />\n";
    $output .= "<input type='hidden' name='month' value='$month' />\n";
    $output .= "<input type='hidden' name='year'  value='$year' />\n";
    $output .= "<label class='admin_label' for='day'>".__('Day').":</label><input type='text' name='day' maxlength='2' size='2' value='01' /><br class='clear' />\n";
    $output .= "<label class='admin_label' for='time'>".__('Time').":</label><input type='text' name='time' size='4' maxlength='4' value='0800' />&nbsp;\n";
    $output .= "<select name='type'><br />\n";
    $output .= "<option value='anfang'>".__('Begin')."</option>\n<option value='ende'>".__('End')."</option>\n</select><br /><br />\n";
    $output .= get_go_button()."</form><br />\n";
    // list of records
    $output .='<table><thead><tr>';
    $output .= "<th width='60'><b>".__('Day')."</b></th><th width='60'><b>".__('Begin')."</b></th><th width='60'><b>".__('End')."</b></th>\n";
    $output .= "</tr></thead><tbody>\n";

    if (strlen($month) == 1) $month = "0".$month;
    $result = db_query("SELECT ID, users, datum, projekt, anfang, ende
                          FROM ".DB_PREFIX."timecard
                         WHERE users = '$pers' AND
                               datum LIKE '%-$month-%' AND
                               datum LIKE '$year-%'
                      ORDER BY datum DESC") or db_die();
    while ($row = db_fetch_row($result)) {
        if (($i/2) == round($i/2)) {
            $color = PHPR_BGCOLOR1;$i++;
        }
        else {
            $color = PHPR_BGCOLOR2;$i++;
        }   //Farben abwechselnd
        $datum2 = explode("-", $row[2]);
        $output .= "<tr bgcolor='$color'><td>$datum2[2].$datum2[1].$datum2[0]&nbsp;</td>\n";
        $output .= "<td>$row[4]</td><td>&nbsp;$row[5]</td></tr>\n";
    }
    $output .= "</tbody></table>\n<br class='clear' />\n";
    // csv export
    $output .= "<a href='../misc/export.php?medium=csv&file=timecard_admin&amp;pers_ID=$pers&amp;month=$month&amp;year=$year".$sid."'>".__('export')."</a>\n";
    $output .= "</form>\n<br />\n";
}


/**
*
*   logging
*
*/
if ($action == "logs") {
    if (strlen($month) == 1) $month = "0".$month;
    $timestring = $year.$month;
    $output .= "<center><h5> ".__('Logging')." </h5> ".__('Month').": $month/$year<br class='clear' /><br class='clear' /><br />\n";
    $output .= "<table cellspacing=1 cellpadding=3 border=1><tr bgcolor='".PHPR_BGCOLOR2."'><td>";
    // column titles: login, logout, duration h:m
    $output .= "<b>".__('Login')."</b></td><td><b>".__('Logout')."</b></td><td><b>h:m</b></td></tr>\n";
    $result = db_query("SELECT login, logout
                          FROM ".DB_PREFIX."logs
                         WHERE von = '$pers' AND
                               login LIKE '$timestring%'
                      ORDER BY login DESC") or db_die();
    while ($row = db_fetch_row($result)) {
        $diff   = 0;
        $diff_h = 0;
        $diff_m = 0;
        $day0   = substr($row[0],6,2).".".$month;
        $h0     = substr($row[0],8,2);
        $m0     = substr($row[0],10,2);
        // only show values if the user has logged out -> means: a value exists
        if ($row[1]) {
            $day1 = substr($row[1],6,2).".".$month;
            $h1   = substr($row[1],8,2);
            $m1   = substr($row[1],10,2);
            $diff =($h1-$h0)*60 + $m1 - $m0;
            // look whether logout is on the next day . if yes, add theminutes of a day
            if (substr($row[1],0,8) > substr($row[0],0,8)) $diff += 1440;
            $diff_h = floor($diff/60);
            $diff_m = $diff - $diff_h*60;
            if (strlen($diff_h) == 1) $diff_h = "0".$diff_h;
            if (strlen($diff_m) == 1) $diff_m = "0".$diff_m;
        }
        else {
            $day1   = "--";
            $h1     = "--";
            $m1     = "--";
            $diff_h = "--";
            $diff_m = "--";
        }
        if (($i/2) == round($i/2)) {
            $color = PHPR_BGCOLOR1;
            $i++;
        }
        else {
            $color = PHPR_BGCOLOR2;
            $i++;
        }   // Farben abwechselnd
        $output .= "<tr bgcolor=$color><td>$day0 $h0.$m0 </td><td>$day1 $h1.$m1 </td><td>$diff_h : $diff_m</td> </tr>\n";
    }
    $output .= "</table>\n</center>\n<br />\n";
}


/**
*
*   user admistration
*
*/
if ($action == "user") {
    //
    // new user
    //
    if ($neu) {
        if (!PHPR_LDAP or
            (PHPR_LDAP and $ldap_profile != "off" and $ldap_profile and
             $ldap_conf[$ldap_profile]["ldap_sync"] == 1) or
            (PHPR_LDAP and $ldap_profile == "off")) {
            // extended value for the input field of the short name

            // begin form and check whether the short name has a blank in the string
            $output .= "<form action='admin.php' method='post' name='frm'>\n";
            if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />";
            $output .= "<input type='hidden' name='neu' value='$neu' />";
            $output .= "<label class='admin_label' for='anrede'>".__('Salutation').":</label> <input type='text' name='anrede' size='15' maxlength='15' />\n<br class='clear' />\n";
            $output .= "<label class='admin_label' for='vorname'>".__('First Name').": </label><input type='text' name='vorname' size='20' maxlength='40' /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='nachname'>".__('Family Name')."(*):</label> <input type='text' name='nachname' size='20' maxlength='40' /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='kurz'>".__('Short Form')."(*): </label><input type='text' name='kurz' size='10' onBlur=\"chkChrs('frm','kurz','Alphanumerics only, please!',/[a-zA-Z0-9_]+/,1)\" /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='loginname'>".__('Login name')."(*):</label> <input type='text' name='loginname' size='20' /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='password'>".__('Password')."(*):</label> <input type='password' name='pw' size='20' maxlength='40' /><br class='clear' />\n";

            // user type
            $output .= "<label class='admin_label' for='usertype'>".__('Type').": </label>\n";
            $output .= "<select name='usertype'>\n";
            foreach ($user_types as $user_types1 => $user_types2) {
                $output .= "<option value='$user_types1'>$user_types2</option>\n";
            }
            $output .= "</select><br class='clear' />\n";

            // user status
            $output .= "<label class='admin_label' for='status'>".__('Status').": </label>\n";
            $output .= "<select name='status'>\n";
            foreach ($user_status as $user_status1 => $user_status2) {
                $output .= "<option value='$user_status1'>$user_status2</option>\n";
            }
            $output .= "</select>\n<br class='clear' />\n";

            // assign to groups, only allowed to root
            if (!$user_group) {
                // default group
                $output .= "<label class='admin_label' for='gruppe'>".__('Default Group<br> (must be selected below as well)').": </label><select name='gruppe'>\n";
                $result = db_query("SELECT ID, name
                                      FROM ".DB_PREFIX."gruppen
                                  ORDER BY name") or db_die();
                while ($row = db_fetch_row($result)) {
                    $output .= "<option value='$row[0]'>$row[1]</option>\n";
                }
                $output .= "</select><br class='clear' />\n";

                // member in the following groups:
                $output .= "<label class='admin_label' for='grup_user'>".__('Member of following groups').":</label><select name='grup_user[]' multiple='multiple' size='4'>\n";
                $result2 = db_query("SELECT ID, name
                                       FROM ".DB_PREFIX."gruppen
                                   ORDER BY name") or db_die();
                while ($row2 = db_fetch_row($result2)) {
                    $output .= "<option value='$row2[0]'>$row2[1]</option>\n";
                }
                $output .= "</select><br class='clear' />\n";
            }

            $output .= "<label class='admin_label' for='password'>".__('Company').":</label> <input type='text' name='firma' size='20' maxlength='30' /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='email'>Email:</label><input type='text' name='email' size='20' maxlength='50' /><br class='clear' />\n";

            // access rights
            $output .= "<label class='admin_label' for='access1'>".__('Access rights').": </label>\n";
            $output .= "<select name='access1'>\n";
            foreach ($acc as $acc1 => $acc2) {
                $output .= "<option value='$acc1'>$acc2</option>\n";
            }
            $output .= "</select><br class='clear' />\n";

/* TODO: obsolete now, remove this next time
            // calendar visible?
            $output .= "<label class='admin_label' for='access2'>&nbsp;</label>\n";
            $output .= "<select name='access2'>\n";
            foreach ($vis as $vis1 => $vis2) {
                $output .= "<option value='$vis1'>$vis2</option>\n";
            }
            $output .= "</select><br class='clear' />\n";
*/

            // various fields, see field name
            $output .= "<label class='admin_label' for='tel1'>".__('Phone')." 1:</label> <input type=text name=tel1 size=20 maxlength=20 /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='tel2'>".__('Phone')." 2:</label> <input type=text name=tel2 size=20 maxlength=20 /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='mobil'>".__('Phone')." ".__('mobile').":</label> <input type=text name=mobil size=20 maxlength=30 /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='fax'>".__('Fax').": </label><input type=text name=fax size=20 maxlength=20 /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='sms'>SMS:</label> <input type=text name=sms size=20 maxlength=60 /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='strasse'>".__('Street').":</label> <input type=text name=strasse size=20 maxlength=30 /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='stadt'>".__('City').":</label> <input type=text name=stadt size=20 maxlength=30 /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='plz'>".__('Zip code').": </label><input type=text name=plz size=10 maxlength=10 /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='land'>".__('Country').": </label><input type=text name=land size=20 maxlength=20 /><br class='clear' />\n";
            $output .= "<label class='admin_label' for='hrate'>".__('Hourly rate').":</label> <input type=text name=hrate size=20 maxlength=20 /><br class='clear' />\n";

            // ldap name
            if (PHPR_LDAP) {
                $output .= "<input type='hidden' name='ldap_profile' value='".(($ldap_profile == "off") ? "" : $ldap_profile) ."' />\n";
            }

            // language, list of available languages in the header of this script as an array!
            $output .= "<label class='admin_label' for='sprache'>".__('Language').":</label> <select name='sprache'>\n";
            $output .= "<option value=''></option>\n";
            foreach ($languages as $l_short => $l_long) {
                $output .= "<option value='$l_short'>$l_long</option>\n";
            }
            $output .= "</select><br class='clear' />\n";

            // Role
            $output .= "<label class='admin_label' for='role'>".__('Role').":  </label><select name='role'><option value='0'></option>\n";
            $result2 = db_query("SELECT ID, title
                                   FROM ".DB_PREFIX."roles
                               ORDER BY title") or db_die();
            while ($row2 = db_fetch_row($result2)) {
                $output .= "<option value='$row2[0]'>$row2[1]</option>\n";
            }
            $output .= "</select><br class='clear' />\n";
            $output .= "<label class='admin_label' for='remark'>".__('Remark').":</label> <textarea name=remark cols=30 rows=5></textarea><br class='clear' />\n";
            $output .= "<br />\n";
            $output .= "<input type='hidden' name='action1' value='user' />\n";
            $output .= "<input type='hidden' name='anlegen' value='neu_anlegen' />\n";
            $output .= get_go_button('admin_button','button','',__('Create'))."\n";
            $output .= "</form>\n<br class='clear' />* ".__('these fields have to be filled in.')."\n";
        }
        else {
            if (!$ldap_profile) {
                $output .= "\n";
                $output .= "<form action='admin.php' method='post'>\n";
                $output .= "<label class='admin_label' for='ldap_profile'>LDAP-Profile<select name='ldap_profile'>\n";
                for ($i = 0; $ldap_conf[++$i]["conf_name"] != ""; ) {
                    $output .= "<option value='$i'>".$ldap_conf[$i]['conf_name']."</option>\n";
                }
                $output .= "<option value='off'>LDAP off</option>\n";
                $output .= "</select>\n<br class='clear' />'";
                $output .= "<input type='hidden' name='action1' value='user' />\n";
                if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
                $output .=get_go_button('admin_button','button','neu',__('Proceed'))."\n";
                $output .= "</form>\n";
            }
            else {
                // extended value for the input field of the short name
                $output .= "<form action='admin.php' method='post'>\n";
                if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />";
                if (!$ldap_conf[$ldap_profile][1]) $output .= "<label class='admin_label' for='vorname'>".__('First Name').": <input type='text' name='vorname' size='20' maxlength='40' /><br class='clear' />\n";
                if (!$ldap_conf[$ldap_profile][2]) $output .= "<label class='admin_label' for='nachname'>".__('Family Name')."(*): <input type='text' name='nachname' size='20' maxlength='40' /><br class='clear' />\n";
                if (!$ldap_conf[$ldap_profile][3]) $output .= "<label class='admin_label' for='kurz'>".__('Short Form')."(*): <input type='text' name='kurz' size=20 /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='loginname'>".__('Login name')."(*):</label> <input type=text name='loginname' size=20 />\n<br class='clear' />\n";

                // only allowed to root
                if (!$user_group) {
                    // default group
                    $output .= "<label class='admin_label' for='gruppe'>".__('Default Group (must be selected below as well)').": <select name='gruppe'>\n";
                    $result = db_query("SELECT ID, name
                                          FROM ".DB_PREFIX."gruppen
                                      ORDER BY name") or db_die();
                    while ($row = db_fetch_row($result)) {
                        $output .= "<option value='$row[0]'>$row[1]</option>\n";
                    }
                    $output .= "</select><br class='clear' />\n";

                    // member in the following groups:
                    $output .= "<label class='admin_label' for='grup_user'>".__('Member of following groups').":</label><select name='grup_user[]' multiple='multiple' size='4'><br class='clear' />\n";
                    $result2 = db_query("SELECT ID, name
                                           FROM ".DB_PREFIX."gruppen
                                       ORDER BY name") or db_die();
                    while ($row2 = db_fetch_row($result2)) {
                        $output .= "<option value='$row2[0]'>$row2[1]</option>\n";
                    }
                    $output .= "</select>\n<br class='clear' />";
                }

                if (!$ldap_conf[$ldap_profile][5]) $output .= "<label class='admin_label' for='bemerkung'>".__('Company').":</label><input type=text name=firma size=20 maxlength=30 /><br class='clear' /><br class='clear' />\n";
                if (!$ldap_conf[$ldap_profile][7]) $output .= "<label class='admin_label' for='email'>Email:</label><input type=text name=email size=20 maxlength=50 /><br class='clear'/>\n";
                $output .= "<label class='admin_label' for='access1'>".__('Access rights').": <select name=access1>\n";
                foreach ($acc as $acc1 => $acc2) {
                    $output .= "<option value='$acc1'>$acc2</option>\n";
                }
                $output .= "</select>\n<br class='clear' />\n";

/* TODO: obsolete now, remove this next time
                $output .= "&nbsp;<select name='access2'>\n";
                foreach ($vis as $vis1 => $vis2) {
                    $output .= "<option value='$vis1'>$vis2</option>\n";
                }
                $output .= "</select><br class='clear' />\n";
*/

                if (!$ldap_conf[$ldap_profile][9])  $output .= "<label class='admin_label' for=''>".__('Phone')." 1: <input type=text name=tel1 size=20 maxlength=20 /><br class='clear' />\n";
                if (!$ldap_conf[$ldap_profile][10]) $output .= "<label class='admin_label' for=''>".__('Phone')." 2: <input type=text name=tel2 size=20 maxlength=20 /><br class='clear' />\n";
                if (!$ldap_conf[$ldap_profile][17]) $output .= "<label class='admin_label' for=''>".__('Phone')." ".__('mobile').": <input type=text name=mobil size=20 maxlength=30 /><br class='clear' />\n";
                if (!$ldap_conf[$ldap_profile][11]) $output .= "<label class='admin_label' for=''>".__('Fax').": <input type=text name=fax size=20 maxlength=20 /><br class='clear' />\n";
                if (!$ldap_conf[$ldap_profile][12]) $output .= "<label class='admin_label' for=''>".__('Street').": <input type=text name=strasse size=20 maxlength=30 /><br class='clear' />\n";
                if (!$ldap_conf[$ldap_profile][13]) $output .= "<label class='admin_label' for=''>".__('City').": <input type=text name=stadt size=20 maxlength=30 /><br class='clear' />\n";
                if (!$ldap_conf[$ldap_profile][14]) $output .= "<label class='admin_label' for=''>".__('Zip code').": <input type=text name=plz size=10 maxlength=10 /><br class='clear' />\n";
                if (!$ldap_conf[$ldap_profile][15]) $output .= "<label class='admin_label' for=''>".__('Country').": <input type=text name=land size=20 maxlength=20 /><br class='clear' />\n";
                $output .= "<input type='hidden' name='ldap_profile' value='$ldap_profile' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for=''>".__('Language').": <select name=sprache>\n";
                $output .= "<option value=''></option>\n";
                foreach ($languages as $l_short => $l_long) {
                    $output .= "<option value='$l_short'>$l_long</option>\n";
                }
                $output .= "</select><br class='clear' />\n";
                $output .= "<input type='hidden' name=action1 value=user />\n";
                $output .= "<input type='hidden' name='anlegen' value='neu_anlegen' />\n";
                $output .= get_go_button('admin_button','button','',__('Create'))."</form>\n";
                $output .= "<br class='clear' />* ".__('these fields have to be filled in.')."\n";
            }
        }
    }

    //
    // modify
    //
    if ($aendern) {
        if (!$pers_ID) {
            $output .=__('Please choose a user')." <a href='admin.php?".SID."'>".__('back')."</a>\n";
        }
        else {
            $result = db_query("SELECT ID, ldap_name, kurz, anrede, vorname, nachname, loginname,
                                       pw, gruppe, email, firma, acc, tel1, tel2, mobil, fax, sms,
                                       strasse, stadt, plz, land, sprache, role, hrate, remark,
                                       usertype, status
                                  FROM ".DB_PREFIX."users
                                 WHERE ID = '$pers_ID'") or db_die();
            $row = db_fetch_row($result);
            $row = explode("·", html_out(implode("·", $row)));

            // for LDAP we depend on row 19, if it is either "" or NULL we will set it to a default value of 1
            if (PHPR_LDAP== 0) $user_ldap_conf = 0;
            else if (!isset($row[1]) or (strlen($row[1]) < 1)) $user_ldap_conf = "1";
            else $user_ldap_conf = $row[19];
            // end ldap mod

            $output .= "<form action='admin.php' method='post'>\n";
            if (SID) $output .= "<input type='hidden' name=".session_name()." value='".session_id()."' />\n";
            $output .= "<input type='hidden' name='pers_ID' value='$pers_ID' />\n";
            $output .= "<input type='hidden' name='aendern' value='$aendern' />\n";
            $output .= "<input type='hidden' name='kurz' value='$row[2]' />\n";
            if ((PHPR_LDAP == 0) or ($user_ldap_conf == "off") or ($ldap_conf[$user_ldap_conf]["ldap_sync"] != "2")) {
                $output .= "<label class='admin_label' for='anrede'>".__('Salutation').":</label> <input type=text name=anrede size='15' maxlength=15 value='$row[3]' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='vorname'>".__('First Name').":</label> <input type=text name=vorname size=20 maxlength=40 value='$row[4]' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='nachname'>".__('Family Name')."(*):</label> <input type=text name=nachname size=20 maxlength=40 value='$row[5]' /><br class='clear' />\n";
                $output .= "<label class='admin_label'>".__('Short Form').":</label> $row[2]\n<br class='clear />'";
                $output .= "<label class='admin_label' for='loginname'>".__('Login name')."(*):</label> <input type='text' name='loginname' size='20' value='$row[6]' /><br class='clear' />\n";
                // password field (with remark: insert a value only if you want to have a new password)
                if (PHPR_LDAP == 0) {
                    $output .= "<label class='admin_label' for='password'>".__('New password').":</label><input type='password' name='pw' size='20' maxlength='40' value='' /><br class='clear' />".__('(keep old password: leave empty)')."<br class='clear' />\n";
                }
                else {
                    $output .= "<input type='hidden' name='pw' value='' />\n";
                }
            }
            else {
                $output .= "<label class='admin_label' for='anrede'>".__('Salutation').":</label><input type=text name=anrede size='15' maxlength=15 value='$row[3]'".read_o('1')." /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='vorname'>".__('First Name').":</label><input type=text name=vorname size=20 maxlength=40 value='$row[4]'".read_o('1')." /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='nachname'>".__('Family Name')."(*):</label><input type=text name=nachname size=20 maxlength=40 value='$row[5]'".read_o('1')." /><br class='clear' />\n";
                $output .= "<label class='admin_label'>".__('Short Form').":</label> $row[7]\n";
                $output .= "<label class='admin_label' for='loginname'>".__('Login name')."(*):</label><input type=text name='loginname' size=20 value='$row[6]' /><br class='clear' />\n";
                $output .= "<input type='hidden' name='pw' value='' />\n";
            }

            // user type
            $output .= "<label class='admin_label' for='usertype'>".__('Type').":</label>\n";
            $output .= "<select name='usertype'>\n";
            foreach ($user_types as $user_types1 => $user_types2) {
                $output .= "<option value='$user_types1'";
                if ($user_types1 == $row[25]) $output .= ' selected="selected"';
                $output .= ">$user_types2</option>\n";
            }
            $output .= "</select>\n<br class='clear' />\n";

            // user status
            $output .= "<label class='admin_label' for='status'>".__('Status').":</label>\n";
            $output .= "<select name='status'>\n";
            foreach ($user_status as $user_status1 => $user_status2) {
                $output .= "<option value='$user_status1'";
                if ($user_status1 == $row[26]) $output .= ' selected="selected"';
                $output .= ">$user_status2</option>\n";
            }
            $output .= "</select>\n<br class='clear' />\n";

            // define group membership
            // only allowed to root and if chosen user is not root
            if (!$user_group and $pers_ID > 1) {
                // default group
                $output .= "<label class='admin_label' for='gruppe'>".__('Default Group (must be selected below as well)').":</label>\n";
                $output .= "<select name='gruppe'>\n<option value='0'></option>\n";
                $result2 = db_query("SELECT ID, name
                                       FROM ".DB_PREFIX."gruppen
                                   ORDER BY name") or db_die();
                while ($row2 = db_fetch_row($result2)) {
                    $output .= "<option value='$row2[0]'";
                    if ($row[8] == "$row2[0]") $output .= ' selected="selected"';
                    $output .= ">$row2[1]</option>\n";
                }
                $output .= "</select>\n<br class='clear' />\n";

                // member in the following groups:
                $output .= "<label class='admin_label' for='grup_user'>".__('Member of following groups').":</label>\n";
                $output .= "<select name='grup_user[]' multiple='multiple' size='4'>\n";
                $result2 = db_query("SELECT ID, name
                                        FROM ".DB_PREFIX."gruppen
                                    ORDER BY name") or db_die();
                while ($row2 = db_fetch_row($result2)) {
                    $result3 = db_query("SELECT ID
                                           FROM ".DB_PREFIX."grup_user
                                          WHERE grup_ID = '$row2[0]' AND
                                                user_ID = '$pers_ID'") or db_die();
                    $row3 = db_fetch_row($result3);
                    $output .= "<option value='$row2[0]'";
                    if ($row3[0] > 0) $output .= ' selected="selected"';
                    $output .= ">$row2[1]</option>\n";
                }
                $output .= "</select>\n<br class='clear' />\n";
            }
            else {
                // default group
                $output .= "<label class='admin_label' for='gruppe'>".__('Default Group (must be selected below as well)').":</label>\n";
                $output .= "<select name='gruppe'>\n<option value='0'></option>\n";
                $result2 = db_query("SELECT DISTINCT ".DB_PREFIX."gruppen.ID, name, kurz
                                                FROM ".DB_PREFIX."gruppen, ".DB_PREFIX."grup_user
                                               WHERE grup_ID = ".DB_PREFIX."gruppen.ID AND
                                                     user_ID = '$user_ID'") or db_die();
                while ($row2 = db_fetch_row($result2)) {
                    $output .= "<option value='$row2[0]'";
                    if ($row[8] == "$row2[0]") $output .= ' selected="selected"';
                    $output .= ">$row2[1]</option>\n";
                }
                $output .= "</select>\n<br class='clear' />\n";

                // member in the follwing groups:
                $output .= "<label class='admin_label' for='grup_user'>".__('Member of following groups').":</label>\n";
                $output .= "<select name='grup_user[]' multiple='multiple' size='4'>\n";
                $result2 = db_query("SELECT DISTINCT ".DB_PREFIX."gruppen.ID, name, kurz
                                                FROM ".DB_PREFIX."gruppen, ".DB_PREFIX."grup_user
                                               WHERE grup_ID = ".DB_PREFIX."gruppen.ID AND
                                                     user_ID = '$user_ID'") or db_die();
                while ($row2 = db_fetch_row($result2)) {
                    $result3 = db_query("SELECT ID
                                           FROM ".DB_PREFIX."grup_user
                                          WHERE grup_ID = '$row2[0]' AND
                                                user_ID = '$pers_ID'") or db_die();
                    $row3 = db_fetch_row($result3);
                    $output .= "<option value='$row2[0]'";
                    if ($row3[0] > 0) $output .= ' selected="selected"';
                    $output .= ">$row2[1]</option>\n";
                }
                $output .= "</select>\n<br class='clear' />\n";
            }

            if ((PHPR_LDAP != 0) and (strcmp($user_ldap_conf, "off") != 0) and ($ldap_conf[$user_ldap_conf]["ldap_sync"] == "2")) {
                $output .= "<label class='admin_label' for='email'>Email:</label> <input type=text name=email size=20 maxlength=50 value='$row[9]' $read_o /><br class='clear' />\n";
            }
            else {
                $output .= "<label class='admin_label' for='firma'>".__('Company').":</label> <input type=text name=firma size=20 maxlength=30 value='$row[10]' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='label'>Email:</label> <input type=text name=email size=20 maxlength=50 value='$row[9]' /><br class='clear' />\n";
            }

            $access1 = substr($row[11],0,1);
            $access2 = substr($row[11],1,1);
            $output .= "<label class='admin_label' for='access1'>".__('Access rights').":</label>\n";
            $output .= "<select name='access1'><br class='clear' />\n";
            foreach ($acc as $acc1 => $acc2) {
                $output .= "<option value='$acc1'";
                if ($access1 == $acc1) $output .= ' selected="selected"';
                $output .= ">$acc2</option>\n";
            }
            $output .= "</select>\n<br class='clear' />\n";

/* TODO: obsolete now, remove this next time
            $output .= "<label class='admin_label' for='access2'></label>\n";
            $output .= "<select name='access2'>\n";
            foreach ($vis as $vis1 => $vis2) {
                $output .= "<option value='$vis1'";
                if ($access2 == $vis1) $output .= ' selected="selected"';
                $output .= ">$vis2</option>\n";
            }
            $output .= "</select>\n<br class='clear' />\n";
*/

            if ((PHPR_LDAP == 0) or ($user_ldap_conf == "off") or ($ldap_conf[$user_ldap_conf]["ldap_sync"] != "2")) {
                $output .= "<label class='admin_label' for='tel1'>".__('Phone')." 1:</label>\n";
                $output .= "<input type=text name=tel1 size=20 maxlength=20 value='$row[12]' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='tel2'>".__('Phone')." 2:</label>\n";
                $output .= "<input type=text name=tel2 size=20 maxlength=20 value='$row[13]' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='mobil'>".__('Phone')." ".__('mobile').":</label>\n";
                $output .= "<input type=text name=mobil size=20 maxlength=30 value='$row[14]' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='fax'>".__('Fax').": </label>\n";
                $output .= "<input type=text name=fax size=20 maxlength=20 value='$row[15]' /><br class='clear' />\n";
            }
            $output .= "<label class='admin_label' for='sms'>SMS:</label>\n";
            $output .= "<input type='text' name='sms' size='20' maxlength='60' value='$row[16]' /><br class='clear' />\n";
            if ((PHPR_LDAP == 0) or ($user_ldap_conf == "off") or ($ldap_conf[$user_ldap_conf]["ldap_sync"] != "2")) {
                $output .= "<label class='admin_label' for='strasse'>".__('Street').": </label>\n";
                $output .= "<input type=text name=strasse size=20 maxlength=30 value='$row[17]' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='stadt'>".__('City').":</label>\n";
                $output .= "<input type=text name=stadt size=20 maxlength=30 value='$row[18]' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='plz'>".__('Zip code').": </label>\n";
                $output .= "<input type=text name=plz size=10 maxlength=10 value='$row[19]' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='land'>".__('Country').":</label>\n";
                $output .= "<input type=text name=land size=20 maxlength=20 value='$row[20]' /><br class='clear' />\n";
                $output .= "<label class='admin_label' for='hrate'>".__('Hourly rate').":</label>\n";
                $output .= "<input type=text name=hrate size=8 maxlength=20 value='$row[23]' /><br class='clear' />\n";
            }

            // language
            $output .= "<label class='admin_label' for='sprache'>".__('Language').":</label> \n";
            $output .= "<select name='sprache'><option value=''></option>\n";
            foreach ($languages as $l_short => $l_long) {
                $output .= "<option value='$l_short'";
                if ($row[21] == $l_short) $output .= ' selected="selected"';
                $output .= ">$l_long</option>\n";
            }
            $output .= "</select>\n<br class='clear' />\n";

            // Role
            $output .= "<label class='admin_label' for='role'>".__('Role').":</label>\n";
            $output .= "<select name='role'>\n<option value='0'></option>\n";
            $result2 = db_query("SELECT ID, title
                                   FROM ".DB_PREFIX."roles
                               ORDER BY title") or db_die();
            while ($row2 = db_fetch_row($result2)) {
                $output .= "<option value='$row2[0]'";
                if ($row2[0] == $row[22]) $output .= ' selected="selected"';
                $output .= ">$row2[1]</option>\n";
            }
            $output .= "</select>\n<br class='clear' />\n";

            // remark
            $output .= "<label class='admin_label' for='remark'>".__('Remark').":</label>\n";
            $output .= "<textarea name='remark' cols='30' rows='5'>".stripslashes($row[24])."</textarea><br class='clear' />\n";

            // ldap name
            if (PHPR_LDAP) {
                $output .= "<label class='admin_label' for='ldap_name'>".__('ldap name').": </label>\n";
                $output .= "<select name='ldap_name'>\n";
                $result2 = db_query("SELECT DISTINCT ldap_name
                                                FROM ".DB_PREFIX."users") or db_die();
                while ($row2 = db_fetch_row($result2)) {
                    if ((strcasecmp($row2[0], "off") != 0) && (strcmp($row2[0], "1") != 0)) {
                        $output .= "<option value='$row2[0]'";
                        if ($row2[0] == $row[1]) $output .= ' selected="selected"';
                        $output .= ">$row2[0]</option>\n";
                    }
                }
                $output .= "<option value='1'";
                if (strcasecmp($row[1], "1") == 0) $output .= ' selected="selected"';
                $output .= ">Default (1)</option>\n"; /* XXX Need to nationalize this */

                $output .= "<option value='off'";
                if (strcasecmp($row[1], "off") == 0) $output .= ' selected="selected"';
                $output .= ">Off</option>\n"; /* XXX Need to nationalize this */
                $output .= "</select>\n<br class='clear' />\n";
            }
            $output .= "<br />\n";
            $output .= "<input type='hidden' name='action1' value='user' />\n";
            $output .= "<input type='hidden' name='anlegen' value='aendern' />\n";
            $output .= get_go_button('admin_button','button','modify_user',__('Modify'))."\n";
            $output .= "<br class='clear' /><br class='clear' />\n";
            $output .= "* ".__('these fields have to be filled in.')."\n";
            $output .= "</div>\n<div class='admin_header'></div>\n<div class='admin_fields'>\n";
            $output .= get_go_button('admin_button','button','remove_settings',__('Remove settings only'))."\n";
            $output .= "</form>\n";
            $output .= "<br class='clear' /><br class='clear' />\n";
        }
    }
    // db actions modify and create record are moved to the other frame -> see below

    // confirm delete record
    else if ($loeschen) {
        $output .= "<h5>".__('Are you sure?')."</h5>\n";
        $output .= "<form action='admin.php' method='post'>\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='hidden' name='action1' value='user' />\n";
        // $output .= "<input type='hidden' name='loeschen' value='$loeschen' />\n";
        $output .= "<input type='hidden' name='pers_ID' value='$pers_ID' />\n";
        $output .= "<input type='submit' class='admin_button' name='loeschen1' value='".__('go')."' />\n";
        $output .= "</form>\n";
    }
}

// *********************
// file management, orphan files
else if ($action == "files") {
    // fetch all users from this group, build array
    $result = db_query("SELECT user_ID
                          FROM ".DB_PREFIX."grup_user
                         WHERE grup_ID = '$group_ID'") or db_die();

    while ($row = db_fetch_row($result)) {
        $user_group_ID[] = $row[0];
    }
    // end fetch all users from the group

    // loop over all files in this group
    $result = db_query("SELECT ID, von, filename, tempname
                          FROM ".DB_PREFIX."dateien
                         WHERE gruppe = '$group_ID'") or db_die();
    while ($row = db_fetch_row($result)) {
        // if 1. owner not listed in array or 2. array is empty (means: no member in group) -> orphan!
        if (($user_group_ID and !in_array($row[1],$user_group_ID)) or !$user_group_ID) {
            if ($delete) {
                $output .= "<label class='admin_label'>".__('Delete').":</label> $row[2]<br class='clear' />\n";
                // unlink the file itself
                unlink(PHPR_FILE_PATH."/".$row[3]);

                // remove the record from the database
                $result2 = db_query("DELETE FROM ".DB_PREFIX."dateien
                                           WHERE ID = '$row[0]'") or db_die();
            }
            else if ($move) {
                $output .="<label class='admin_label'>". __('Move').":</label> $row[2]<br class='clear' />\n";
                $result2 = db_query("UPDATE ".DB_PREFIX."dateien
                                        SET von = '$pers_ID'
                                      WHERE ID = '$row[0]'") or db_die();
            }
        }
    }
    $output .= __('finished')."<br class='clear' />\n";
}

// roles
else if ($action == "roles" && PHPR_ROLES) {
    // new record
    if ($neu) {
        $output .= "<form action='admin.php' method='post'>\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />";
        $output .= "<input type='hidden' name='action1' value='roles' />\n";
        // title of the role
        $output .= "<label class='admin_label' for='title'>".__('Name').":</label><input type=text name=title size=40 maxlength=30 /><br class='clear' />\n";
        // remark
        $output .= "<label class='admin_label' for='remark'>".__('Comment').":</label><textarea name=remark rows=10 cols=30></textarea><br class='clear' />\n";

        // loop over all modules
        if ($summary)         $output .= "<label class='admin_label' for='summary'>".__('Summary')."</label>".role1("summary")."<br class='clear' />\n";
        if (PHPR_CALENDAR)    $output .= "<label class='admin_label' for='calendar'>".__('Calendar')."</label>".role1("calendar")."<br class='clear' />\n";
        if (PHPR_CONTACTS)    $output .= "<label class='admin_label' for='contacts'>".__('Contacts')."</label>".role1("contacts")."<br class='clear' />\n";
        if (PHPR_CHAT)        $output .= "<label class='admin_label' for='chat'>".__('Chat')."</label>".role1("chat")."<br class='clear' />\n";
        if (PHPR_FORUM)       $output .= "<label class='admin_label' for='forum'>".__('Forum')."</label>".role1("forum")."<br class='clear' />\n";
        if (PHPR_FILEMANAGER) $output .= "<label class='admin_label' for='filemanager'>".__('Files')."</label>".role1("filemanager")."<br class='clear' />\n";
        if (PHPR_PROJECTS)    $output .= "<label class='admin_label' for='projects'>".__('Projects')."</label>".role1("projects")."<br class='clear' />\n";
        if (PHPR_TIMECARD)    $output .= "<label class='admin_label' for='timecard'>".__('Timecard')."</label>".role1("timecard")."<br class='clear' />\n";
        if (PHPR_NOTES)       $output .= "<label class='admin_label' for='notes'>".__('Notes')."</label>".role1("notes")."<br class='clear' />\n";
        if (PHPR_RTS)         $output .= "<label class='admin_label' for='helpdesk'>".__('Helpdesk')."</label>".role1("helpdesk")."<br class='clear' />\n";
        if (PHPR_QUICKMAIL)   $output .= "<label class='admin_label' for='mail'>".__('Mail')."</label>".role1("mail")."<br class='clear' />\n";
        if (PHPR_TODO)        $output .= "<label class='admin_label' for='todo'>".__('Todo')."</label>".role1("todo")."<br class='clear' />\n";
        if ($news)            $output .= "<label class='admin_label' for='news'>".__('News')."</label>".role1("news")."<br class='clear' />\n";
        if (PHPR_VOTUM)       $output .= "<label class='admin_label' for='votum'>".__('Voting system')."</label>".role1("votum")."<br class='clear' />\n";
        if (PHPR_BOOKMARKS)   $output .= "<label class='admin_label' for='bookmarks'>".__('Bookmarks')."</label>".role1("bookmarks")."<br class='clear' />\n";
        //if (PHPR_LINKS)       $output .= "<label class='admin_label' for='links'>Links</label>".role1("links")."<br class='clear' />\n";

        $output .= "<input type='hidden' name='anlegen' value='neu_anlegen' />\n";
        $output .= "<input type='submit' class='admin_button 'value='".__('Create')."' />\n";
        $output .= "</form>\n";
    }
    // modify
    if ($aendern) {
        $result = db_query("SELECT ID, von, title, remark, summary, calendar, contacts,
                                   forum, chat, filemanager, bookmarks, votum, mail,
                                   notes, helpdesk, projects, timecard, todo, news
                              FROM ".DB_PREFIX."roles
                             WHERE ID = '$roles_ID'") or db_die();
        $row = db_fetch_row($result);
        $output .= "<form action='admin.php' method='post'>\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='hidden' name='anlegen' value='aendern' />\n";
        $output .= "<input type='hidden' name='action1' value='roles' />\n";
        $output .= "<input type='hidden' name='roles_ID' value='$roles_ID' />\n";
        $output .= "<label class='admin_label' for='title'>".__('Name').":</label><input type=text name='title' value='".html_out($row[2])."' size=40 maxlength=40 /><br class='clear' />\n";
        // remark
        $output .= "<label class='admin_label' for='remark'>".__('Comment').":</label><textarea name=remark rows=10 cols=40>".html_out($row[3])."</textarea><br class='clear' />\n";

        // loop over all modules
        if ($summary)         $output .= "<label class='admin_label' for='summary'>".__('Summary')."</label>".role1("summary")."<br class='clear' />\n";
        if (PHPR_CALENDAR)    $output .= "<label class='admin_label' for='calendar'>".__('Calendar')."</label>".role1("calendar")."<br class='clear' />\n";
        if (PHPR_CONTACTS)    $output .= "<label class='admin_label' for='contacts'>".__('Contacts')."</label>".role1("contacts")."<br class='clear' />\n";
        if (PHPR_CHAT)        $output .= "<label class='admin_label' for='chat'>".__('Chat')."</label>".role1("chat")."<br class='clear' />\n";
        if (PHPR_FORUM)       $output .= "<label class='admin_label' for='forum'>".__('Forum')."</label>".role1("forum")."<br class='clear' />\n";
        if (PHPR_FILEMANAGER) $output .= "<label class='admin_label' for=''>".__('Files')."</label>".role1("filemanager")."<br class='clear' />\n";
        if (PHPR_PROJECTS)    $output .= "<label class='admin_label' for='projects'>".__('Projects')."</label>".role1("projects")."<br class='clear' />\n";
        if (PHPR_TIMECARD)    $output .= "<label class='admin_label' for='timecard'>".__('Timecard')."</label>".role1("timecard")."<br class='clear' />\n";
        if (PHPR_NOTES)       $output .= "<label class='admin_label' for='notes'>".__('Notes')."</label>".role1("notes")."<br class='clear' />\n";
        if (PHPR_RTS)         $output .= "<label class='admin_label' for='helpdesk'>".__('Helpdesk')."</label>".role1("helpdesk")."<br class='clear' />\n";
        if (PHPR_QUICKMAIL)   $output .= "<label class='admin_label' for='mail'>".__('Mail')."</label>".role1("mail")."<br class='clear' />\n";
        if (PHPR_TODO)        $output .= "<label class='admin_label' for='todo'>".__('Todo')."</label>".role1("todo")."<br class='clear' />\n";
        if ($news)            $output .= "<label class='admin_label' for='news'>".__('News')."</label>".role1("news")."<br class='clear' />\n";
        if (PHPR_VOTUM)       $output .= "<label class='admin_label' for='votum'>".__('Voting system')."</label>".role1("votum")."<br class='clear' />\n";
        if (PHPR_BOOKMARKS)   $output .= "<label class='admin_label' for='bookmarks'>".__('Bookmarks')."</label>".role1("bookmarks")."<br class='clear' />\n";
        //if (PHPR_LINKS)       $output .= "<label class='admin_label' for='links'>Links</label>".role1("links")."<br class='clear' />\n";

        $output .= "</select>\n<br class='clear' />\n";
        $output .= "<input type='submit' class='admin_button 'value='".__('Modify')."' />\n";
        $output .= "</form>\n";
    }
    // confirm delete record
    else if ($loeschen) {
        $output .= "<h5>".__('Are you sure?')."</h5><br class='clear' />\n";
        $output .= "<form action='admin.php' method='post'>\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='hidden' name='action1' value='roles' /><br class='clear' />\n";
        $output .= "<input type='hidden' name='roles_ID' value='$roles_ID' /><br class='clear' />\n";
        $output .= "<input type='submit' class='admin_button 'name='loeschen1' value='".__('go')."' />\n";
        $output .= "</form>\n<br class='clear' />\n";
    }
}

// *********************
// helpdesk create categories
else if ($action == "rts_categories") {
    // new record
    if ($neu) {
        $output .= "<form action='admin.php' method='post'>\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='hidden' name='action1' value='rts_categories' />\n";
        $output .= "<label class='admin_label' for='name'>".__('Name').":</label><input type=text name=name size=30 maxlength=30 /><br class='clear' />\n";

        // assign the category to a group
        $output .= "<br /><label class='admin_label' for='gruppe'>".__('Automatic assign to group:')."</label>\n";
        $output .= "<select name='gruppe'><option value=''></option>\n";
        $result = db_query("SELECT ID, name
                              FROM ".DB_PREFIX."gruppen
                          ORDER BY name") or db_die();
        while ($row = db_fetch_row($result)) {
            $output .= "<option value='$row[0]'>$row[1]</option>\n";
        }
        $output .= "</select>\n<br class='clear' /><br />\n";

        // assign the category to an user
        $output .= "<label class='admin_label' for=''>".__('Automatic assign to user:')."</label><select name='user'><option value=''></option>\n";
        $result = db_query("SELECT ".DB_PREFIX."users.ID, nachname, vorname
                              FROM ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                             WHERE ".DB_PREFIX."users.ID = user_ID
                               AND grup_ID = '$group_ID'") or db_die();
        while ($row = db_fetch_row($result)) {
            $output .= "<option value='$row[0]'>$row[1], $row[2]</option>\n";
        }
        $output .= "</select><br class='clear' /><br />\n";
        $output .= "<input type='hidden' name='anlegen' value='neu_anlegen' />\n";
        $output .= "<input type='submit' class='admin_button 'value='".__('Create')."' />\n";
        $output .= "</form>\n";
    }
    // modify
    if ($aendern) {
        $result = db_query("SELECT ID,name, users, gruppe
                              FROM ".DB_PREFIX."rts_cat
                             WHERE ID = '$rts_cat_ID'") or db_die();
        $row = db_fetch_row($result);
        $output .= "<form action='admin.php' method='post'>\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='hidden' name='anlegen' value='aendern' />\n";
        $output .= "<input type='hidden' name='action1' value='rts_categories' />\n";
        $output .= "<input type='hidden' name='rts_cat_ID' value='$rts_cat_ID' />\n";
        $row[1] = html_out($row[1]);
        $output .= "<label class='admin_label' for='name'>".__('Name').":</label><input type=text name='name' value='$row[1]' size=30 maxlength=30 /><br class='clear' />\n";

        // assign the category to a group
        $output .= "<br /><label class='admin_label' for='gruppe'>".__('Automatic assign to group:')."</label><select name='gruppe'><option value=''></option>\n";
        $result2 = db_query("SELECT ID, name
                               FROM ".DB_PREFIX."gruppen
                           ORDER BY name") or db_die();
        while ($row2 = db_fetch_row($result2)) {
            $output .= "<option value='$row2[0]'";
            if ($row2[0] == $row[3]) $output .= ' selected="selected"';
            $output .= ">$row2[1]</option>\n";
        }
        $output .= "</select><br class='clear' />\n";

        // assign the category to an user
        $output .= "<br /><label class='admin_label' for='user'>".__('Automatic assign to user:')."</label>\n";
        $output .= "<select name='user'>\n<option value=''></option>\n";
        $result2 = db_query("SELECT ".DB_PREFIX."users.ID, nachname, vorname
                               FROM ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                              WHERE ".DB_PREFIX."users.ID = user_ID AND
                                    grup_ID = '$group_ID'") or db_die();
        while ($row2 = db_fetch_row($result2)) {
            $output .= "<option value='$row2[0]'";
            if ($row2[0] == $row[2]) $output .= ' selected="selected"';
            $output .= ">$row2[1], $row2[2]</option>\n";
        }
        $output .= "</select><br class='clear' /><br />\n";
        $output .= "<input type='submit' class='admin_button 'value='".__('Modify')."' />\n";
        $output .= "</form><br class='clear' />\n";
    }
    // confirm delete record
    else if ($loeschen) {
        $output .= "<h5>".__('Are you sure?')."</h5><br class='clear' />\n";
        $output .= "<form action='admin.php' method='post'>\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='hidden' name='action1' value='rts_categories' />\n";
        $output .= "<input type='hidden' name='action' value='rts_categories' />\n";
        $output .= "<input type='hidden' name='rts_cat_ID' value='$rts_cat_ID' />\n";
        $output .= "<input type='submit' class='admin_button 'name='loeschen1' value='".__('go')."' />\n";
        $output .= "</form>\n<br class='clear' />\n";
    }
}

//****************************
// Lesezeichen/bookmarks check for invalid links and delete them
else if ($action == "lesezeichen") {
    if ($proof) {
        $output .= "<form action='admin.php' method='post'>\n";
        $output .= "<input type='hidden' name='action' value='lesezeichen' />\n";
        $output .= "<input type='hidden' name='loeschen' value='loeschen' />\n";
        $error = 0;
        $result = db_query("SELECT ID, datum, von, url, bezeichnung, bemerkung, gruppe
                              FROM ".DB_PREFIX."lesezeichen
                             WHERE $sql_group") or db_die();
        while ($row = db_fetch_row($result)) {
            $msg = '';
            // $url = eregi_replace("http://","",$row[3]);
            $url = parse_url($row[3]);
            if (!$url[port]) $url[port] = '80';
            $ok = fsockopen($url[host], $url[port]);
            if (!$ok) {
                $msg = 'No response';
            }
            else {
                fputs($ok, "GET / HTTP/1.0\r\n\r\n");
                $a =  fgets($ok,128);
                fclose($ok);
                if (substr($a,9,1) == 4 or substr($a,9,1) == 5) $msg = substr($a, 0, 50);
            }
            if ($msg) {
                $output .= "$row[4]: ".__('The server sent an error message.')."&nbsp;($msg)&nbsp;";
                $output .= "<input type='Checkbox' name='lesezeichen_ID[]' value='$row[0]' /> ".__('Delete')."<br class='clear' />\n";
                $error = 1;
            }
        }
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        if ($error) $output .= "<input type='image' src='$img_path/los.gif' onclick=\"return confirm('".__('Are you sure?')."')\" />\n<br class='clear' />\n";
        else        $output .= __('All Links are valid.').".\n<br class='clear' /><br class='clear' />\n";
        $output .= "</form>\n<br class='clear' />\n";
    }
}

//****************************
// delete forum threads
else if ($action == "forum") {
    // first case - only old threads
    if ($tage) {
        $treffer = 0;
        $zeit = mktime(0, 0, 0, date("m"), date("d")-$tage, date("Y"));
        $zeit = date("YmdHis", $zeit);
        $result = db_query("SELECT ID
                              FROM ".DB_PREFIX."forum
                             WHERE datum < '$zeit' AND
                                   $sql_group") or db_die();
        while ($row = db_fetch_row($result)) {
            $treffer++;
        }
        $result = db_query("DELETE
                              FROM ".DB_PREFIX."forum
                             WHERE datum < '$zeit' AND
                                   $sql_group") or db_die();
        $output .= "$treffer ".__('threads older than x days are deleted.').". (x=$tage)<br />\n";
    }
    // second case - specific threads
    else {
        $result = db_query("SELECT ID, titel, gruppe
                              FROM ".DB_PREFIX."forum
                             WHERE ID = '$ID'") or db_die();
        $row = db_fetch_row($result);
        // check permission (except the root who has access to all groups)
        if ($user_group > 0 and $row[2] <> $user_group) die("you are not allowed to do this");

        // check whether such a posting exists
        if (!$row[0]) {
            $output .= "such a posting does not exist!";
            $error = 1;
        }
        // o.k.? -> begin to delete, first the comments
        if (!$error) {
            delete_comments($ID);
            // now delete the posting itself
            $result = db_query("DELETE
                                  FROM ".DB_PREFIX."forum
                                 WHERE parent = '$ID'") or db_die();
            $result = db_query("DELETE
                                  FROM ".DB_PREFIX."forum
                                 WHERE ID = '$ID'") or db_die();
            $output .= "$row[1] ".__(' is deleted.')." \n";
        }
    }
}

// *****************************
// delete Chat files
else if ($action == "chat") {
    if ($mode == "kill") {
        if(!$user_group)$user_group=0;
        $alivepath = "../chat/".$user_group."_".PHPR_ALIVEFILE;
        $chatpath  = "../chat/".$user_group."_".PHPR_CHATFILE;
        if (file_exists($alivepath)) unlink($alivepath);
        if (file_exists($chatpath))  unlink($chatpath);
        $output .= __('All chat scripts are removed').". <br class='clear' />\n";
    }
}


$output .= "</div>\n</div>\n";

// end code for right frame.


//****************************************
// LEFT FRAME ****************************
//****************************************
$output .= "<div class='admin_left'>\n";
//****************************************
// DIALOG
//****************************************

// no groupID? -> You are superadmin! first dialog: choose a group or work on groups


/**
*
*   start main table
*   check for root
*
*/
if (!$user_group) {
    // work on groups
    // form new group
    $output .= "<div class='admin_header'>".__('Group management')."</div>\n";
    $output .= "<div class='admin_fields'><form action='admin.php' method='post' class='inline'>\n";
    if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
    $output .= "<input type='hidden' name='action' value='groups' />\n";
    // submit button for a new entry ...
    $output .= "<input class='admin_button' type='submit' name='neu' value='".__('Create')."' />&nbsp; &nbsp; ".__('or')."\n";
    $output .= "<input class='admin_button'type='submit' name='aendern' value='".__('Modify')."' />&nbsp;\n";
    $output .= "<select name='group_nr'>\n";
    $result = db_query("SELECT ID, name
                          FROM ".DB_PREFIX."gruppen
                      ORDER BY name") or db_die();
    while ($row = db_fetch_row($result)) {
        $output .= "<option value='$row[0]'";
        if ($row[0] == $group_ID) $output .= ' selected="selected"';
        $output .= ">$row[1]</option>\n";
    }
    $output .= "</select>&nbsp;\n";
    // delete button
    $output .= "<input type='submit' class='admin_button' name='loeschen' value='".__('Delete')."' />\n";
    $output .= "</form>\n\n";
}


//**************************
// groupID set -> main dialog

// user management
if ($group_ID) {
    // set query string for the user list
    $groupstring = " WHERE gruppe = $group_ID";

    $output .= "<div class='admin_header'>".__('User management')."</div>\n";
    $output .= "<div class='admin_fields'>\n";
    $output .= "<form action='admin.php' method='post' class='inline'>\n";
    $output .= "<input type='hidden' name='action' value='user' />\n";
    if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
    $output .= "<input type='submit' class='admin_button' name='neu' value='".__('Create')."' />&nbsp; &nbsp; ".__('or')."\n";
    $output .= "</form>\n";

    // new form: modify or delete user
    $output .= "<form action='admin.php' method='post' class='inline' name='frm1' onsubmit=\"return chkForm('frm1','pers_ID','".__('Please choose an element')."!');\">\n";
    $output .= "<input type='hidden' name='action' value='user' />\n";
    if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
    $output .= "<input type='submit' class='admin_button' name='aendern' value='".__('Modify')."' />&nbsp;\n";
    $output .= "<select name='pers_ID'><option value='0'></option>\n";
    // list users
    $result2 = db_query("SELECT ".DB_PREFIX."users.ID, nachname, vorname
                           FROM ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                          WHERE ".DB_PREFIX."users.ID = user_ID AND
                                grup_ID = '$group_ID'
                       ORDER BY nachname") or db_die();
    // loop over all entries
    while ($row2 = db_fetch_row($result2)) {
        $output .= "<option value='$row2[0]'>$row2[1], $row2[2]</option>\n";
    }
    $output .= "</select>&nbsp;\n";
    // end list users
    $output .= "<input type='submit' class='admin_button' name='loeschen' value='".__('Delete')."' />\n";
    $output .= "</form>\n</div>\n";

    // *****
    // roles
    // *****
    if (PHPR_ROLES) {
        $output .= "<div class='admin_header'>".__('Roles')."</div>\n";
        $output .= "<div class='admin_fields'>\n";
        $output .= "<form action='admin.php' method='post' class='inline'>\n";
        $output .= "<input type='hidden' name='action' value='roles' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='submit'  class='admin_button'  name='neu' value='".__('Create')."' />&nbsp; &nbsp; ".__('or')."</form>\n";
        // new form: modify rts category
        $output .= "<form action='admin.php' method='post' class='inline' name='frm2' onsubmit=\"return chkForm('frm2','roles_ID','".__('Please choose an element')."!')\">\n";
        $output .= "<input type='hidden' name='action' value='roles' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='submit' class='admin_button' name='aendern' value='".__('Modify')."' />&nbsp;\n";
        $output .= "<select name='roles_ID'><option value='0'></option>\n";
        $result = db_query("SELECT ID, title
                              FROM ".DB_PREFIX."roles
                          ORDER BY title") or db_die();
        while ($row = db_fetch_row($result)) {
            $row[1] = html_out($row[1]);
            $output .= "<option value='$row[0]'>$row[1]</option>\n";
        }
        $output .= "</select>&nbsp;\n";
        $output .= "<input type='submit' name='loeschen' class='admin_button' value='".__('Delete')."' />\n";
        $output .= "</form></div>\n";
    }

    // ****************************
    // helpdesk category management
    if (PHPR_RTS) {
        // form: new category
        $output .= "<div class='admin_header'>".__('Help Desk Category Management')."</div>\n";
        $output .= "<div class='admin_fields'>\n";
        $output .= "<form action='admin.php' method='post' class='inline'>\n";
        $output .= "<input type='hidden' name='action' value='rts_categories' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='submit' class='admin_button' name='neu' value='".__('Create')."' />&nbsp; &nbsp; ".__('or')."</form>\n";
        // new form: modify rts category
        $output .= "<form action='admin.php' class='inline' method='post' name='frm3' onsubmit=\"return chkForm('frm3','rts_cat_ID','".__('Please choose an element')."!')\">\n";
        $output .= "<input type='hidden' name='action' value='rts_categories' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='submit' class='admin_button' name='aendern' value='".__('Modify')."' />&nbsp;\n";
        $output .= "<select name='rts_cat_ID'><option value='0'></option>\n";
        $result = db_query("SELECT ID, name
                              FROM ".DB_PREFIX."rts_cat
                          ORDER BY name") or db_die();
        while ($row = db_fetch_row($result)) {
            $row[1] = html_out($row[1]);
            $output .= "<option value='$row[0]'>$row[1]</option>\n";
        }
        $output .= "</select>&nbsp;\n";
        $output .= "<input type='submit' class='admin_button' name='loeschen' value='".__('Delete')."' />\n";
        $output .= "</form>\n</div>\n";
    }

    // timecard
    if (PHPR_TIMECARD) {
        $output .= "<div class='admin_header'>".__('Timecard Management')."</div>\n";
        $output .= "<div class='admin_fields'>\n";
        $output .= "<form action='admin.php' method='post'>\n";
        $output .= "<input type='hidden' name='action' value='timecard' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='submit' class='admin_button' name='pers' value='".__('View')."' />\n";
        $output .= "<select name='pers'>\n";
        $result = db_query("SELECT ID, nachname, vorname
                              FROM ".DB_PREFIX."users
                             WHERE $sql_group
                          ORDER BY nachname") or db_die();
        while ($row = db_fetch_row($result)) {
            $output .= "<option value='$row[0]'>$row[1], $row[2]</option>\n";
        }
        $output .= "</select>\n";
        $output .= "<select name='month'>\n";
        // Monatsbox
        if (!$month) $month = date("m");
        if (!$year)  $year  = date("Y");
        for ($a=1; $a<13; $a++) {
            $mo = date("n", mktime(0,0,0, $a, 1, $year));
            $name_of_month = $name_month[$mo];
            if ($mo == $month) $output .= "<option value='$a' selected='selected'>$name_of_month</option>\n";
            else               $output .= "<option value='$a'>$name_of_month</option>\n";
        }
        $output .= "</select>\n";
        $y = date("Y");
        $output .= "<select name='year'>\n";
        for ($i=$y-2; $i<=$y+5; $i++) {
            if ($i == $year) $output .= "<option value='$i' selected='selected'>$i</option>\n";
            else             $output .= "<option value='$i'>$i</option>\n";
        }
        $output .= "</select>\n</form>\n</div>\n";
    }

    // Logging
    if (PHPR_LOGS) {
        $output .= "<div class='admin_header'>".__('Logging')."</div>\n";
        $output .= "<div class='admin_fields'>\n";
        $output .= "<form action='admin.php' class='inline' method='post'>\n";
        $output .= "<input type='hidden' name='action' value='logs' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='submit' class='admin_button' name='pers' value='".__('View')."' />\n";
        $output .= "<select name='pers'>\n";
        $result = db_query("SELECT ".DB_PREFIX."users.ID, nachname, vorname
                              FROM ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                             WHERE ".DB_PREFIX."users.ID = user_ID AND
                                   grup_ID = '$group_ID'
                          ORDER BY nachname") or db_die();
        while ($row = db_fetch_row($result)) {
            $output .= "<option value='$row[0]'>$row[1], $row[2]</option>\n";
        }
        $output .= "</select>\n";
        $output .= "<select name='month'>\n";
        // Monatsbox
        if (!$month) $month = date("m");
        if (!$year)  $year  = date("Y");
        for ($a=1; $a<13; $a++) {
            $mo = date("n", mktime(0,0,0, $a, 1, $year));
            $name_of_month = $name_month[$mo];
            if ($mo == $month) $output .= "<option value='$a' selected='selected'>$name_of_month</option>\n";
            else               $output .= "<option value='$a'>$name_of_month</option>\n";
        }
        $output .= "</select>\n";
        $y = date("Y");
        $output .= "<select name='year'>\n";
        for ($i=$y-2; $i<=$y+5; $i++) {
            if ( $i == $year) $output .= "<option value='$i' selected='selected'>$i</option>\n";
            else              $output .= "<option value='$i'>$i</option>\n";
        }
        $output .= "</select>\n</form>\n</div>\n";
    }

    // orphan files
    if (PHPR_FILEMANAGER) {
        $output .= "<div class='admin_header'>".__('File management')."</div>\n";
        $output .= "<div class='admin_fields'>\n";
        $output .= "<form action='admin.php' class='inline' method='post'>\n";
        $output .= "<input type='hidden' name='action' value='files' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= __('Orphan files').":\n";
        $output .= "<input type='submit' class='admin_button' name='delete' value='".__('Delete')."' />\n";
        $output .= " &nbsp; ".__('or')." <input type='submit' class='admin_button' name='move' value='".__('Move')."' />\n";
        $output .= "<select name='pers_ID'>\n";
        $result = db_query("SELECT ".DB_PREFIX."users.ID, nachname, vorname
                              FROM ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                             WHERE ".DB_PREFIX."users.ID = user_ID AND
                                   grup_ID = '$group_ID'
                          ORDER BY nachname") or db_die();
        while ($row = db_fetch_row($result)) {
            $output .= "<option value='$row[0]'>$row[1], $row[2]</option>\n";
        }
        $output .= "</select></form></div>\n";
    }

    // bookmark management
    if (PHPR_BOOKMARKS) {
        // check bookmarks
        // delete bookmarks
        $output .= "<div class='admin_header'>".__('Bookmarks')."</div>\n";
        $output .= "<div class='admin_fields'>\n";
        $output .= "<form action='admin.php' class='inline' method='post'>\n";
        $output .= "<input type='hidden' name='action' value='lesezeichen' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<select name='lesezeichen_ID[]' multiple='multiple'>\n";
        $result = db_query("SELECT ID, bezeichnung
                              FROM ".DB_PREFIX."lesezeichen
                             WHERE $sql_group
                          ORDER BY bezeichnung") or db_die();
        while ($row = db_fetch_row($result)) {
            $row[1] = html_out($row[1]);
            $output .= "<option value='$row[0]'>$row[1]</option>\n";
        }
        $output .= "</select>\n";
        $output .= "<input type='submit' class='admin_button' name='loeschen' value='".__('Delete')."' /></form>\n";
        $output .= "<form action='admin.php' method='post' class='inline'>\n";
        $output .= "<input type='hidden' name='action' value='lesezeichen' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "&nbsp;&nbsp;&nbsp;<input type='submit' class='admin_button' name='proof' value='".__('Check')."' />\n";
        $output .= __('for invalid links')."</form>\n</div>\n";
    }

    // Forum
    if (PHPR_FORUM) {
        // first form: delete threads in forum which are older than x days
        $output .= "<div class='admin_header'>".__('Forum')."</div>\n";
        $output .= "<div class='admin_fields'>\n";
        $output .= "<form action='admin.php' method='post'>\n";
        $output .= "<input type='hidden' name='action' value='forum' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='submit' class='admin_button' name='loeschen' value='".__('Delete')."' onclick=\"return confirm('".__('Are you sure?')."')\" />\n";
        $output .= __('Threads older than')." &nbsp;\n";
        $output .= "<select name='tage'><option value='15'>15</option><option value='30'>30</option><option value='45'>45</option><option value='60'>60</option></select>\n";
        $output .= "&nbsp;".__(' days ')."\n";
        $output .= "</form>\n";

        // second form: admin can delete a specified thread (including the comments
        $output .= "<form action='admin.php' method='post' class='inline'>\n";
        $output .= "<input type='hidden' name='action' value='forum' />\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='submit' class='admin_button' name='loeschen' value='".__('Delete')."' onclick=\"return confirm('".__('Are you sure?')."')\" />\n";
        $output .= __('posting (and all comments) with an ID').": <input type='text' name='ID' size='4' />";
        $output .= "</form>\n</div>\n";
    }

    // chat
    if (PHPR_CHAT) {
        $chatpath = "../chat/".$user_group."_".PHPR_CHATFILE;
        $output .= "<div class='admin_header'>".__('Chat')."</div>\n";
        $output .= "<div class='admin_fields'>\n";
        $output .= "<form action='admin.php' method='post'>\n";
        if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output .= "<input type='submit' class='admin_button' value='".__('Delete')."' />\n";
        $output .= __('Chat script')."\n";
        $output .= "<input type='hidden' name='action' value='chat' />\n";
        $output .= "<input type='hidden' name='mode' value='kill' />\n";
        // if the file with the chat text exists, offer a link to save this file
        if (file_exists($chatpath)) {
            $output .= "<a href='$chatpath' target='_blank'>".__('save script of current Chat')."</a>\n";
        }
        $output .= "</form>\n";
    }
} // close $group_ID

$output .= '
        </div>
        <br /><br />
    </div>
</div></div>

</body>
</html>
';

echo $output;

?>
