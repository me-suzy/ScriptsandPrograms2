<?php

// email_notification.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: email_notification.inc.php,v 1.13 2005/06/20 14:59:56 paolo Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) die('Please use index.php!');


/**
* Send emails to a bunch of people
*
* @param string $module     Name of Module (used as subject and first string in body)
* @param string $selection  "all": mail to all users (restricted to group if $user_group is set),
*       <serialized array of shortnames> identifying the recipients
* @param string $title      the body! NOT the title
* @param array  $module_data optional array with a key "last_insert_id" with an ID as value
*/
function email_notification($module, $selection, $title, $module_data=array()) {
    global $user_group, $user_name, $user_firstname, $user_email, $mail;
/*
    error_log(__FILE__);
    error_log(__LINE__.": ".$user_group);
    error_log(__LINE__.": ".$user_name);
    error_log(__LINE__.": ".$user_firstname);
    error_log(__LINE__.": ".$user_email);
    error_log(__LINE__.": ".gettype($mail));
*/
    // select members - but not to the uploader him - or herself
    // 1. case: all members of the group
    if ($selection == 'all') {
        // group system
        if ($user_group) {
            $result2 = db_query("SELECT ".DB_PREFIX."users.email, ".DB_PREFIX."users.ID
                                   FROM ".DB_PREFIX."grup_user, ".DB_PREFIX."users
                                  WHERE grup_ID = '$user_group'
                                    AND user_ID = ".DB_PREFIX."users.ID
                                    AND ".DB_PREFIX."users.email <> '$user_email'") or db_die();
        }
        // or from all users if the group system is not enabled
        else {
            $result2 = db_query("SELECT email
                                   FROM ".DB_PREFIX."users
                                  WHERE email <> '$user_email'") or db_die();
        }

        // create array with email adresses
        while ($row2 = db_fetch_row($result2)) {
            if ($row2[0] <> '') $list[] = array($row2[0], $row2[1]);
        }
    }

    // 2. case: only selected members
    else {
        // unpack the string with the participants
        $acc = unserialize($selection);
        if ($acc) {
            foreach ($acc as $shortname) {
                $result2 = db_query("SELECT email, ID
                                       FROM ".DB_PREFIX."users
                                      WHERE kurz = '$shortname'") or db_die();
                $row2 = db_fetch_row($result2);
                if ($row2[0] <> '') $list[] = array($row2[0], $row2[1]);
            }
        }
    }
    $notify_title .= $module;
    $notify_body  .= "$module ".__('Created by')." $user_firstname $user_name:".PHPR_MAIL_EOL." $title".PHPR_MAIL_EOL;

    use_mail('1');
    // loop over all users with an email adress and send out the notification
    if ($list) {
        foreach ($list as $data) {
            // add notification link
            $module_data['recipient_ID'] = $data[1];
            // commented as long as there's no table "logintoken"
            // add_notification_link(&$notify_body, $module, $module_data);
            $success = $mail->go($data[0], $notify_title, $notify_body, $user_email);
        }
    }
}


/**
* add notification link to email
* @author Alex Haslberger
* @param string $notify_body body text of email
* @return void
*/
function add_notification_link(&$notify_body, $module, $module_data) {
    global $dbIDnull, $dbTSnull, $user_ID;

    // generate login token
    $tokenmail = md5 (uniqid (rand()));
    $token = md5(encrypt($tokenmail, $tokenmail));
    switch($module) {
        case 'Forum':
            $link = get_host_path().substr($_SERVER['PHP_SELF'], 1).'?mode=forms&ID=';
            if (isset($module_data['last_insert_id'])) {
                $link .= $module_data['last_insert_id'];
            }
            else {
                $link .= $_REQUEST['ID'];
            }
            $link .= '&fID='.$_REQUEST['fID'].'&logintoken='.$token;
            break;
    }
    $notify_body .= $link;
    // insert token data into database
    $days = 7;
    $valid= date('YmdHis', time() + PHPR_TIMEZONE*3600 + 60*60*24*$days);
    $result = db_query("INSERT INTO ".DB_PREFIX."logintoken
                                    ( ID, von, token, user_ID, url, datum, valid )
                             VALUES ( $dbIDnull, '$user_ID', '$token', '".$module_data['recipient_ID']."',
                                      '$link', '$dbTSnull', '$valid' )") or db_die();
}

?>
