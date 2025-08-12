<?php

// mail_send_form.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: mail_send_form.php,v 1.32.2.2 2005/09/06 10:00:38 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("mail") < 2) die("You are not allowed to do this!");


// js function to include a string with the name of a db-field for the personalized newsletter
$output = "
<script type='text/javascript'>
<!--
var flag = 0;
function insPlHold() {
    i = ";

$i = '"mem[]"';
$output .= $i.";
    x = document.frm;
    txt = x.body.value + x.placehold.value;
    x.body.value = txt;
    x.placehold.value = '';
    x.body.focus();
    if (flag == 0) {
        flag = 1;
        x.elements[i].value = '';
        x.elements[i].disabled = true;
        x.profil1.value = '';
        x.profil1.disabled = true;
        if (x.action.value == 'fax') {
            x.additional_fax.value = '';
            x.additional_fax.disabled = true;
        }
        if (x.action.value == 'email') {
            x.additional_mail.value = '';
            x.additional_mail.disabled = true;
            x.cc.value = '';
            x.cc.disabled = true;
            x.bcc.value = '';
            x.bcc.disabled = true;
        }
    }
}
//-->
</script>
";

// if no mode is set (email, fax, sms), set default to email
if (!$form) $form = "email";

// only for mail section: check whether mail client sended some data
if ($action2 || $ID) {
    // check permission and fetch data
    $result = db_query("select ID, von, sender, replyto, recipient, cc, subject, body, subject
                          from ".DB_PREFIX."mail_client
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if ($row[0] == 0) { die("no entry found."); }
    if ($row[1] <> $user_ID) { die("You are not allowed to do this!"); }

    // reply? -> give sender as recipient
    if (ereg("&lt;",$row[2])) {
        $sender=explode("&lt;",$row[2]);
        $row[2]=substr($sender[1],0,-4);
    }
    // build email of recipient
    if ($action2 == "reply" or $action2 == "replyall") {
        // take adress from replyto if given
        if ($row[3] <> "")  { $recipient = $row[3]; }
        // otherwise the from adress
        else { $recipient = $row[2]; }
        // add cc and recipients: to recipient if replyall is given
        if ($action2 == "replyall") {
            if ($row[4] <> "") { $recipient .=",".$row[4]; }
            if ($row[5] <> "") { $recipient .=",".$row[5]; }
        }
        // subject
        $subject = "Re: ".$row[6];
    }
    // forwarding ...
    elseif ($action2 == "forward") {
        $subject = "Fw: ".$row[6];
    }
    // format body text.
    $body = ereg_replace("\n","\n>",$row[7]);
    $body = ">".wordwrap($body,50,"\n>");
} // end only for mail section - reply or forward a mail ...


// **************
// begin the form

// tabs
$tabs = array();
$buttons = array();
// form start
$hidden = array('mode' => 'send','html'=>$html);
if (SID) $hidden[session_name()] = session_id();
$buttons[] = array('type' => 'form_start','name'=>'frm', 'hidden' => $hidden, 'enctype' => 'multipart/form-data');
$output .= get_buttons($buttons);
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();
$buttons[] = array('type' => 'link', 'href' => 'mail.php?mode=send_form&amp;form=email'.$sid, 'text' => __('Mail'), 'active' => false);
if (PHPR_FAXPATH) {
    $buttons[] = array('type' => 'link', 'href' => 'mail.php?mode=send_form&amp;form=fax'.$sid, 'text' => __('Fax'), 'active' => false);
}
if ($smspath) {
    $buttons[] = array('type' => 'link', 'href' => 'mail.php?mode=send_form&amp;form=sms'.$sid, 'text' => __('SMS'), 'active' => false);
}
// send mail button
$buttons[] = array('type' => 'submit', 'name' => 'versenden', 'value' => __('Send'), 'active' => false);
// back
if (PHPR_QUICKMAIL == 2) {
    $buttons[] = array('type' => 'link', 'href' => 'mail.php?mode=view&amp;page='.$page.'&amp;perpage='.$perpage.'&amp;up='.$up.'&amp;sort='.$sort.$sid, 'text' => __('back'), 'active' => false);
}
$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';
$output .= get_status_bar();

/*******************************
*    create message fields
*******************************/
// email: account, sender/signature
$form_fields = array();

if ($form == "email") {
    // sender
    // if it is the full mail client, offer the choice of all sender/signatures
    if ($html!='true') $form_fields[] = array('type' => 'string',  'text' => "<a href='mail.php?mode=send_form&amp;ID=$ID&amp;html=true'>".__('send html mail')."</a>");
    if (PHPR_QUICKMAIL == 2) {
        // Using sockets? Offer the choice of accounts
        if (PHPR_MAIL_MODE) {
            $options = array();
            $options[] = array('value' => '', 'text' => '*'.__('Standard').'*');
            $result = db_query("select ID, accountname
                                  from ".DB_PREFIX."mail_account
                                 where von = '$user_ID'and smtp_hostname <>''
                              order by accountname") or db_die();
            while ($row = db_fetch_row($result)) {
                $options[] = array('value' => $row[0], 'text' => $row[1]);
            }
            $form_fields[] = array('type' => 'select', 'name' => 'account_ID', 'label' => __('Standard').__(':'), 'options' => $options);
        }
        // begin dropdown menu
        $options = array();
        $options[] = array('value' => 'default', 'text' => $user_email);
        $result = db_query("select ID, title
                              from ".DB_PREFIX."mail_sender
                             where von = '$user_ID'") or db_die();
        while ($row = db_fetch_row($result)) {
            $options[] = array('value' => $row[0], 'text' => $row[1]);
        }
        $form_fields[] = array('type' => 'select', 'name' => 'sender_ID', 'label' => __('Sender').__(':'), 'options' => $options, 'width' => '500px', 'label_class' => 'small');
    }
    // otherwise just display the normal string
    else {
        $form_fields[] = array('type' => 'string', 'text' => $user_email.'<br/>' );
    }
    // end sender
}

// nothing similar for fax and sms :-(
// subject field not for sms
if ($form <> "sms") {
    $form_fields[] = array('type' => 'text', 'name' => 'subj', 'label' => __('Subject').__(':'), 'value' => html_out($subject), 'width' => '500px', 'label_class' => 'small');
}
// body for all three modes :-)
$textarea_id = 'id=body';
//$js_html_textarea= array();
$js_html_textarea[] = 'body';
if ($html=='true') {
    $output .= "<script type=\"text/javascript\">window.onload = function() {\n";
    foreach ($js_html_textarea as $f) {
        $output .= "var ".$f."=new FCKeditor('".$f."');\n".$f.".BasePath='".$path_pre."lib/';\n".$f.".Width='500px';\n".$f.".Height='200px';\n".$f.".ReplaceTextarea();\n";
    }
    $output .=  '}</script>';
}

$form_fields[] = array('type' => 'textarea', 'name' => 'body', 'label' => __('Text').__(':'), 'value' => $body, 'width' => '500px', 'height' => '200px', 'label_class' => 'small');

// option for customized newsletter
// insert db-field
if ($form == "email") {
    $options = array();
    $options[] = array('value' => '', 'text' => '');
    // fetch all active fields
    // 1. include db_man and fetch fields
    require_once($path_pre.'lib/dbman_lib.inc.php');
    $fields = build_array('contacts', 0);
    // loop over all active fields
    foreach ($fields as $field_name => $field_values)  {
        $options[] = array('value' => '|db-field:'.$field_name.'('.enable_vars($field_values['form_name']).')|', 'text' => enable_vars($field_values['form_name']));
    }
    $form_fields[] = array('type' => 'select', 'name' => 'placehold', 'label' => '&nbsp;', 'options' => $options, 'onchange' => 'insPlHold();', 'text_after' => __('insert db field (only for contacts)').__(':'), 'label_class' => 'small', 'style' => 'float:left');
    // notice of receipt
    $form_fields[] = array('type' => 'checkbox', 'name' => 'receipt', 'label' => '&nbsp;', 'label_right' => __('Notice of receipt'), 'label_class' => 'small');
    // send single mails
    $form_fields[] = array('type' => 'checkbox', 'name' => 'single', 'label' => '&nbsp;', 'label_right' => __('Send single mails'), 'label_class' => 'small');
    // end drop down menu personalized newsletter - only for mail
}

$html = get_buttons(array(array('type' => 'submit', 'name' => 'versenden', 'value' => __('Senden'), 'active' => false)));
// back button to the mail client
if (PHPR_QUICKMAIL == 2) {
    $html .= get_buttons(array(array('type' => 'link', 'href' => 'mail.php?mode=view&amp;page='.$page.'&amp;perpage='.$perpage.'&amp;up='.$up.'&amp;sort='.$sort.$sid, 'text' => __('back'), 'active' => false)));
}
$form_fields[] = array('type' => 'parsed_html', 'html' => $html);
$create_message_fields = get_form_content($form_fields);

/*******************************
*     attachment fields
*******************************/
$form_fields = array();
// Fax form
if ($form == "fax") {
    $form_fields[] = array('type' => 'hidden', 'name' => 'action', 'value' => 'fax');
    $form_fields[] = array('type' => 'text', 'name' => 'additional_fax', 'label' => __('Additional number').__(':'), 'value' => '', 'label_class' => 'small');
}
// SMS form
elseif ($form == "sms") {
    $form_fields[] = array('type' => 'hidden', 'name' => 'action', 'value' => 'sms');
    $form_fields[] = array('type' => 'text', 'name' => 'smsnumber', 'label' => __('Additional number').__(':'), 'value' => '', 'label_class' => 'small');
}
else {
    // 1. attachment
    $form_fields[] = array('type' => 'file', 'name' => 'userfile[]', 'id' => 'userfile1', 'label' => __('Attachment').__(':'), 'label_class' => 'small');
    // 2. attachment
    $form_fields[] = array('type' => 'file', 'name' => 'userfile[]',  'id' => 'userfile2', 'label' => __('Attachment').__(':'), 'label_class' => 'small');
    // 3. attachment
    $form_fields[] = array('type' => 'file', 'name' => 'userfile[]',  'id' => 'userfile3', 'label' => __('Attachment').__(':'), 'label_class' => 'small');
}
$attachment_fields = get_form_content($form_fields);

/*******************************
*     recipient fields
*******************************/
// right content
$form_fields = array();
if ($form == "email") {
    $form_fields[] = array('type' => 'hidden', 'name' => 'action', 'value' => 'email');
    // recipient
    $form_fields[] = array('type' => 'text', 'name' => 'additional_mail', 'label' => __('Additional address').__(':'), 'value' => str_replace("'","",str_replace('"','',$recipient)), 'label_class' => 'small', 'width' => '500px');
    // cc
    $form_fields[] = array('type' => 'text', 'name' => 'cc', 'label' => __('CC').__(':'), 'value' => '', 'label_class' => 'small', 'width' => '500px');
    // bcc
    $form_fields[] = array('type' => 'text', 'name' => 'bcc', 'label' => __('BCC').__(':'), 'value' => '', 'label_class' => 'small', 'width' => '500px');
    // carry a flag so the script can look for attachments
    if ($action2 == "forward") {
        $form_fields[] = array('type' => 'hidden', 'name' => 'forwarded_mail', 'value' => $ID);
    }
}

// select members of your group
if ($user_group and in_array($form, array('email','fax','sms'))) {
    $result2 = db_query("select gu.user_ID, nachname, vorname
                           from ".DB_PREFIX."grup_user AS gu,
                                ".DB_PREFIX."users AS u
                          where grup_ID = '$user_group'
                            and gu.user_ID = u.ID
                            and ".qss($form)." != ''
                       order by nachname") or db_die();
}
elseif (in_array($form, array('email','fax','sms'))) {
    $result2 = db_query("select ID, nachname, vorname
                           from ".DB_PREFIX."users
                          where ".qss($form)." != ''
                       order by nachname") or db_die();
}
else {
    $result2 = false;
}

// list: user selection
$options = array();
while ($row2 = db_fetch_row($result2)) {
    $options[] = array('value' => $row2[0], 'text' => "$row2[1], $row2[2]");
}
$form_fields[] = array('type' => 'select', 'name' => 'mem[]', 'label' => __('Group members').__(':'), 'options' => $options, 'multiple' => true, 'label_class' => 'small');

$options = array();
$result = db_query("select ID, bezeichnung
                      from ".DB_PREFIX."profile
                     where von = '$user_ID'
                  order by bezeichnung") or db_die();
while ($row = db_fetch_row($result)) {
    $options[] = array('value' => $row[0], 'text' => $row[1]);
}
$form_fields[] = array('type' => 'select', 'name' => 'profil1', 'label' => __('Profile').__(':'), 'options' => $options, 'label_class' => 'small');
if (PHPR_CONTACTS and $form <> "sms") {
    $options = array();
    $result = db_query("select ID, vorname, nachname
                          from ".DB_PREFIX."contacts
                         where (acc_read like 'system' or ((von = '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group)) and
                               ".qss($form)." <> ''
                      order by nachname") or db_die();
    while ($row = db_fetch_row($result)) {
        $options[] = array('value' => $row[0], 'text' => $row[2].', '.$row[1]);
    }
    $form_fields[] = array('type' => 'select', 'name' => 'con[]', 'label' => __('External contacts').__(':'), 'options' => $options, 'multiple' => true, 'label_class' => 'small');
    // fetch profiles for contacts
    if (PHPR_CONTACTS_PROFILES and $form <> "sms") {
        $options = array();
        $result = db_query("select ID, name
                              from ".DB_PREFIX."contacts_profiles
                             where von = '$user_ID'
                          order by name") or db_die();
        while ($row = db_fetch_row($result)) {
            $options[] = array('value' => $row[0], 'text' => $row[1]);
        }
        $form_fields[] = array('type' => 'select', 'name' => 'profil2', 'label' => __('Contact Profile').__(':'), 'options' => $options, 'label_class' => 'small');
    }
}

$recipient_fields .= get_form_content($form_fields);

$output .= '
<br />

<div class="inner_content">
    <a name="content"></a>
    <div class="boxHeader">'.__('Create new message').'</div>
    <div class="boxContent">'.$create_message_fields.'</div>
    <br style="clear:both"/><br/>
    <div class="boxHeader">'.__('Recipients').'</div>
    <div class="boxContent">'.$recipient_fields.'</div>
    <br style="clear:both"/><br/>
    <div class="boxHeader">'.__('Attachments').'</div>
    <div class="boxContent">'.$attachment_fields.'</div>
</div>
</form>
';

echo $output;

?>
