<?php

// mail_accounts.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: mail_accounts.php,v 1.18.2.2 2005/09/12 09:26:32 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) {
    die('Please use index.php!');
}

// check role
if (check_role('mail') < 1) {
    die('You are not allowed to do this!');
}

// convert checkbox flag
$collect ? $collect = 1 : $collect = 0;

if ($make <> '') {
    // insert new record
  if ($make == "neu") {
        $result = db_query("insert into ".DB_PREFIX."mail_account
                                        (   ID,      von,       accountname,   hostname,   type,   username,   password,   mail_auth,   pop_hostname,   pop_account,   pop_password,   smtp_hostname,   smtp_account,   smtp_password, collect)
                                 values ($dbIDnull,'$user_ID','$accountname','$hostname','$type','$username','$password','$cmail_auth','$cpop_hostname','$cpop_account','$cpop_password','$csmtp_hostname','$csmtp_account','$csmtp_password','$collect')") or db_die();
        if ($result) {
            // send message on success
            $output.= "$hostname ".__('has been created')."<hr>";
            $action = '';
        }
    }
    // update existing record
    else {
        if ($collect) $collect = 1;
        else $collect = 0;
        $result = db_query("update ".DB_PREFIX."mail_account
                               set accountname='$accountname',
                                   hostname='$hostname',
                                   type='$type',
                                   username='$username',
                                   password='$password',
                                   mail_auth = '$cmail_auth',
                                   pop_account = '$cpop_account',
                                   pop_password = '$cpop_password',
                                   pop_hostname = '$cpop_hostname',
                                   smtp_hostname = '$csmtp_hostname',
                                   smtp_account = '$csmtp_account',
                                   smtp_password = '$csmtp_password',
                                   collect = '$collect'
                             where ID='$ID'") or db_die();
        if ($result) {
            // send message on success
            $output.= "$hostname ".__('has been changed')."<hr>";
            $action = '';
        }
    }
    include_once('./mail_options.php');
} else if ($loeschen) {
    $result = db_query("delete from ".DB_PREFIX."mail_account
                              where ID = '$ID'") or db_die();
    include_once('./mail_options.php');
}

// form
if (!$make) {
    //tabs
    $tabs = array();
    $buttons = array();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'enctype' => 'multipart/form-data');
    $output = get_buttons($buttons);
    $output .= get_tabs_area($tabs);
    // button bar
    $buttons = array();
    $buttons[]=(array('type' => 'submit', 'name' => 'go', 'value' => __('go'), 'active' => false));
    $buttons[]=(array('type' => 'link', 'href' => 'mail.php?mode=options', 'text' => __('back'), 'active' => false));
    $output .= get_buttons_area($buttons);

    $output .= '<div class="hline"></div>';

    if ($aendern <> '') {
        // fetch values
        $result = db_query("select ID, von, accountname, hostname, type, username, password,
                                   mail_auth, pop_hostname, pop_account, pop_password,
                                   smtp_hostname, smtp_account, smtp_password, collect
                              from ".DB_PREFIX."mail_account
                             where ID = '$ID'") or db_die();
        $row = db_fetch_row($result);
        // check permission - the hacker doesn't deserve any message!
        if ($row[1] <> $user_ID) exit;
    }

    // title

    $form_fields[] = array('type' => 'text', 'name' => 'accountname', 'label' => __('Mail account')." ".__('Name').":",'value' =>$row[2] );
    $form_fields[] = array('type' => 'text', 'name' => 'hostname', 'label' => __('host name'),'value' =>$row[3]);
    $options = array();
    $options[] = array('value' => '', 'text' => '');
    foreach ($port as $port1=>$port2) {
        $options[]= array('value' => $port1, 'text' => $port1, 'selected' => $row[4] == $port1);
        //  if ($port1 == $row[4])
    }
    $form_fields[] = array('type' => 'select', 'name' => 'type', 'label' => __('Type'), 'options' => $options,'value' =>$row[4]);

    $form_fields[] = array('type' => 'checkbox', 'name' => 'collect', 'label' => __('Include account for default receipt'),'value' =>$row[14], 'checked' => $row[14] == 1);
    $form_fields[] = array('type' => 'text', 'name' => 'username', 'label' => __('Username'),'value' =>$row[5]);
    $form_fields[] = array('type' => 'password', 'name' => 'password', 'label' => __('Password'),'value' =>$row[6]);
    $create_message_fields = get_form_content($form_fields);
    // send configuration

    $output .= '
<br/>
<div class="inner_content">
<div class="boxHeader">'.__('Accounts').'</div>
<div class="boxContent">'.$create_message_fields;
    if (PHPR_MAIL_MODE) {
        $output.='</div> <br style="clear:left"/><br/><div class="boxHeader">'.__('send mails').'</div>
<div class="boxContent">';
        $send_fields[] = array('type' => 'text', 'name' => 'csmtp_password', 'label' => __('the real address of the SMTP mail server, you have access to (maybe localhost)'),'value' =>$row[13]);
        $send_fields[] = array('type' => 'text', 'name' => 'csmtp_hostname', 'label' => __('the real address of the SMTP mail server, you have access to (maybe localhost)'),'value' =>$row[11]);
        $mail_auth_values = array('0' => __('No Authentication'), '1' => __('with POP before SMTP'), '2' => __('SMTP auth (via socket only!)') );
        $options = array();
        $options[] = array('value' => '', 'text' => '');
        foreach ($mail_auth_values as $auth_mail_value=>$auth_mail_text) {
            $options[]= array('value' => $auth_mail_value, 'text' => $auth_mail_text);

        }
        $send_fields[] = array('type' => 'select', 'name' => 'cmail_auth', 'label' => __('Authentication'),'options'=>$options,'value' =>$row[7]);
        $create_send_fields = get_form_content($send_fields);
        $output .= $create_send_fields;

        $output.='</div> <br style="clear:left"/><br/><div class="boxHeader">'.__('fill out in case of authentication via POP before SMTP').'</div>
<div class="boxContent">';
        $pop_fields[] = array('type' => 'text', 'name' => 'cpop_hostname', 'label' => __('the POP server'),'value' =>$row[8]);
        $pop_fields[] = array('type' => 'text', 'name' => 'cpop_account', 'label' => __('real username for POP before SMTP'),'value' =>$row[9]);
        $pop_fields[] = array('type' => 'text', 'name' => 'cpop_password', 'label' => __('password for this pop account'),'value' =>$row[10]);
        $create_pop_fields = get_form_content($pop_fields);
        $output .= $create_pop_fields;
        //fill out in case of SMTP authentication
        $output.='</div> <br style="clear:left"/><br/><div class="boxHeader">'.__('fill out in case of SMTP authentication').'</div>
<div class="boxContent">';
        $smtp_fields[] = array('type' => 'text', 'name' => 'csmtp_account', 'label' => __('real username for SMTP auth'),'value' =>$row[9]);
        $smtp_fields[] = array('type' => 'text', 'name' => 'csmtp_password', 'label' => __('password for this account'),'value' =>$row[10]);
        $create_smtp_fields = get_form_content($smtp_fields);
        $output .= $create_smtp_fields;
    }
    $output.= "<input type='hidden' name='ID' value='$ID' />\n";
    if (SID) $output.= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
    if ($aendern <> '') {
        $output.= "<input type='hidden' name='make' value='aendern' />\n";
    } else {
        $output.= "<input type='hidden' name='make' value='neu' />\n";
    }
    $output.= "<input type='hidden' name='mode' value='accounts' />\n";
    $output.=get_buttons(array(array('type' => 'submit', 'name' => 'go', 'value' => __('go'), 'active' => false)));
    $output.=get_buttons(array(array('type' => 'link', 'href' => 'mail.php?mode=options', 'text' => __('back'), 'active' => false)))."
    </div></div></form>";


    echo $output;
}

?>
