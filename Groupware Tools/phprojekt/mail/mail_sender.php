<?php

// mail_sender.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: mail_sender.php,v 1.12 2005/06/20 14:49:08 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("mail") < 1) { die("You are not allowed to do this!"); }

    if(!$make){
//tabs
$tabs = array();

// button bar
if ($aendern){
       $result = db_query("select ID, von, title, sender, signature
                          from ".DB_PREFIX."mail_sender
                         where ID = '$ID'") or db_die();
        $row = db_fetch_row($result);
    $hidden=array('mode'=>'sender','ID'=>$ID,'action'=>'sender','make'=>'aendern');
}
else  $hidden=array('mode'=>'sender','action'=>'sender','make'=>'neu');
if(SID) $hidden=array_merge($hidden,array(session_name()=>session_id()));
$buttons = array();
$buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'onsubmit'=>"return chkForm('sender','title','".__('Please fill in the following field').": ".__('Name')."','sender','".__('Please fill in the following field'));
$output= get_buttons($buttons);
$output .= get_tabs_area($tabs);
$buttons = array();
  $buttons[]=(array('type' => 'submit', 'name' => 'go', 'value' => __('go'), 'active' => false));
  $buttons[]=(array('type' => 'link', 'href' => 'mail.php?mode=options', 'text' => __('back'), 'active' => false));
  $output .= get_buttons_area($buttons);

$output .= '<div class="hline"></div>';
// title
      // title of this record
$form_fields[] = array('type' => 'text', 'name' => 'title', 'label' => __('Name'),'value' =>$row[2] );
 // sender name
$form_fields[] = array('type' => 'text', 'name' => 'sender', 'label' => __('Sender'),'value' =>$row[3]);
    // signature
$form_fields[] = array('type' => 'textarea', 'name' => 'signature', 'label' => __('Signature'),'value' =>$row[4]);

$create_message_fields = get_form_content($form_fields);
$output .= '
<br/>
<div class="inner_content">
<div class="boxHeader">'.__('Sender').' / '.__('Signature').'</div>
<div class="boxContent">'.$create_message_fields;
$output.=get_buttons(array(array('type' => 'submit', 'name' => 'go', 'value' => __('go'), 'active' => false)));
$output.=get_buttons(array(array('type' => 'link', 'href' => 'mail.php?mode=options', 'text' => __('back'), 'active' => false)))."
</div></div></form>";




}
  //insert record into db
  elseif ($make) {
    if (!$error) {
      // insert new record
      if ($make == "neu") {
        $result = db_query("insert into ".DB_PREFIX."mail_sender
                                   (   ID,       von,      title,   sender,   signature )
                            values ($dbIDnull,'$user_ID','$title','$sender','$signature')") or db_die();
        if ($result) {} // send message on success
      }
      // update existing record
      else {
        $result = db_query("update ".DB_PREFIX."mail_sender
                               set title='$title',
                                   sender='$sender',
                                   signature='$signature'
                             where ID='$ID'") or db_die();
        if ($result) {} // send message on success
      }
    }
      include_once('./mail_options.php');
        exit;
  }
  elseif ($loeschen) {
    $result = db_query("delete from ".DB_PREFIX."mail_sender
                         where ID = '$ID'") or db_die();
    if ($result) { $action = ""; }
      include_once('./mail_options.php');
  }

echo $output;

?>
