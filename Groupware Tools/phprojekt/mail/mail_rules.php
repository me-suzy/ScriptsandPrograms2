<?php

// mail_rules.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: mail_rules.php,v 1.14 2005/07/01 11:50:22 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("mail") < 1) { die("You are not allowed to do this!"); }

// fields for rules
$rules_fields = array("subject" => __('Title'), "body" => __('Body'), "sender" => __('Sender'),
                      "recipient" => __('Receiver'), "cc" => "Cc");
// action for rules
$rules_action  = array("copy" => __('Copy'), "move" => __('Move'), "delete" => __('Delete'));
//insert record into db
if ($make) {

  switch ($mode2) {
    case 'mail_to_contact':
      update_mail_to_contact($mail2con);
      include_once('./mail_options.php');
      break;
    case 'incoming':
      update_general_rule('incoming', $dir);
      break;
    case 'outgoing':
      update_general_rule('outgoing', $dir);
      break;
    default:
      // only assign to contact/project if the respective checkbox is checked
      if (!$parent_contact) $contact = '';
      if (!$parent_projekt) $projekt = '';
      // insert new record
      if (!$ID) {
        $result = db_query("insert into ".DB_PREFIX."mail_rules
                                   (   ID,       von,      title,   phrase,   type,  parent,       action ,   projekt,   contact)
                            values ($dbIDnull,'$user_ID','$title','$phrase','$type','$dir','$rules_action1','$projekt','$contact')") or db_die();
        if ($result) { $output.="$title ".__('has been created')."<hr>"; }
      }
      // update existing record
      else {
        $result = db_query("update ".DB_PREFIX."mail_rules
                               set title='$title',
                                   phrase='$phrase',
                                   type='$type',
                                   parent='$dir',
                                   action='$rules_action1',
                                   projekt='$projekt',
                                   contact='$contact'
                             where ID='$ID'") or db_die();
        if ($result) { $output.="$title ".__('has been changed')."<hr>"; }
      }
      break;
  }
  include_once('./mail_options.php');
}
elseif ($loeschen) {
  $result = db_query("delete from ".DB_PREFIX."mail_rules
                       where ID = '$ID'") or db_die();
  include_once('./mail_options.php');
}


// *******
// form
else {

// tabs
$tabs    = array();
$buttons = array();
$hidden  = array('mode'=>'rules','ID'=>$ID,'action'=>'rules','make'=>'make');
if (SID) $hidden = array_merge($hidden, array(session_name()=>session_id()));
$buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
$output = get_buttons($buttons);
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();
$buttons[]=(array('type' => 'submit', 'name' => 'go', 'value' => __('go'), 'active' => false));
$buttons[]=(array('type' => 'link', 'href' => 'mail.php?mode=options', 'text' => __('back'), 'active' => false));

$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';

  // fetch values
  if ($ID > 0) {
    $result = db_query("select ID, von, title, phrase, type, is_not, parent, action, projekt, contact
                          from ".DB_PREFIX."mail_rules
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if ($row[1] <> $user_ID) { die("you are not allowed to do this!"); }
  }


 $form_fields[] = array('type' => 'text', 'name' => 'title', 'label' => __('name of the rule'),'value' =>$row[2] );
 $form_fields[] = array('type' => 'text', 'name' => 'phrase', 'label' => __('part of the word'),'value' =>$row[3]);
 $options = array();
 $options[] = array('value' => '', 'text' => '');
 foreach ($rules_fields as $type1 => $type2){
       $options[]= array('value' => $type1, 'text' => $type2);
      //  if ($port1 == $row[4])
 }
 $form_fields[] = array('type' => 'select', 'name' => 'type', 'label' => __('is in field'), 'options' => $options,'value' =>$row[4]);
 $create_message_fields = get_form_content($form_fields);

 //Duplicate messages
 $create_duplicate_fields='<br style="clear: both;" />';

 // delete, copy or move into a directory
 $create_duplicate_fields.="
 <label for='parent' class='formbody'>".__('Mail')."</label><input type='checkbox' id='parent' name='parent'";
 if ($row[7] <> '') $create_duplicate_fields.=' checked';
 $create_duplicate_fields.=" /> &nbsp;&nbsp;<select name='rules_action1' >\n";
 foreach ($rules_action as $action1 => $action2) {
    $create_duplicate_fields.="<option value='$action1'";
    if ($action1 == $row[7]) {  $create_duplicate_fields.=" selected"; }
    $create_duplicate_fields.=">$action2</option>\n";
  }
 $create_duplicate_fields.="</select>\n";
  // select directory
  $create_duplicate_fields.="&nbsp;&nbsp;<label for='dir'>".__('in')." ".__('Directory')."</label>\n";
  $create_duplicate_fields.="<select name='dir' id='dir'><option value=''></option>\n";
  $result2 = db_query("select ID, subject
                         from ".DB_PREFIX."mail_client
                        where von = '$user_ID' and
                              typ like 'd'") or db_die();
  while ($row2 = db_fetch_row($result2)) {
    $create_duplicate_fields.="<option value='$row2[0]'";
    if ($row2[0] == $row[6]) { $create_duplicate_fields.=" selected"; }
    $create_duplicate_fields.=">$row2[1]</option>\n";
  }
  $create_duplicate_fields.="</select><br style='clear:both;' />";

  // assign to a project
  $create_duplicate_fields.="<label for='parent_projekt' class='formbody'>".__('Assign to project').":</label>
  <input type='checkbox' id='parent_projekt' name='parent_projekt'";
  if ($row[8] > 0) $create_duplicate_fields.=' checked';
  $create_duplicate_fields.=" /> &nbsp;&nbsp;<select name='projekt' id='projekt'><option value='0'></option>\n";
  //prepare values for function
  $where = "where $sql_user_group";
  // call function to show all required elemts in a tree structure in the select box
  $create_duplicate_fields.=show_elements_of_tree("projekte","name",$where,"personen"," order by name",$row[8],'parent',0);
  $create_duplicate_fields.="</select><br style='clear:both;' />";

  // assign to a contact
  $create_duplicate_fields.="<label for='parent_contact' class='formbody'>".__('Assign to contact').":</label>
  <input type='checkbox' name='parent_contact' id='parent_contact'";
  if ($row[9] > 0) $create_duplicate_fields.=' checked';
  $create_duplicate_fields.=" /> &nbsp;&nbsp; <select name='contact' id='contact'><option value='0'></option>\n";
  $create_duplicate_fields.=show_elements_of_tree("contacts",
                        "nachname,vorname,firma",
                        "where (acc_read like 'system' or ((von = $user_ID or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))",
                        "acc"," order by nachname",$row[9],"parent",0);
   $create_duplicate_fields.="</select><br style='clear:both;' />";

 $output .= '
<br/>
<div class="inner_content">
<div class="boxHeader">'.__('Rules').'</div>
<div class="boxContent">'.$create_message_fields.
'</div><br style="clear:left"/><br/><div class="boxHeader">'.__('Action for duplicates').'</div>
<div class="boxContent">'.$create_duplicate_fields;
 // hidden values and go button

}
    $output.=get_buttons(array(array('type' => 'submit', 'name' => 'go', 'value' => __('go'), 'active' => false)));
    $output.=get_buttons(array(array('type' => 'link', 'href' => 'mail.php?mode=options', 'text' => __('back'), 'active' => false)))."</div></div></form>";


echo $output;
// creates, updates or deletes a rule for incoming/outgoing messages
function update_general_rule($mode, $dir) {
  global $user_ID, $dbIDnull;
  // first of all - delete any rule for incoming/outgoing mail for this user :-))
  $result = db_query("delete from ".DB_PREFIX."mail_rules
                         where von = '$user_ID' and
                               type like '$mode'") or db_die();
  if ($dir > 0) {
    $result = db_query("insert into ".DB_PREFIX."mail_rules
                               (    ID,       von,       type,  parent)
                       values  ($dbIDnull, '$user_ID', '$mode', '$dir')") or db_die();
  }
}


function update_mail_to_contact($mail2con) {
  global $user_ID, $dbIDnull;
  // first of all - delete any rule for incoming/outgoing mail for this user :-))
  $result = db_query("delete from ".DB_PREFIX."mail_rules
                         where von = '$user_ID' and
                               type like 'mail2con'") or db_die();
  if ($mail2con <> '') {
    $result = db_query("insert into ".DB_PREFIX."mail_rules
                               (    ID,       von,       type    )
                       values  ($dbIDnull, '$user_ID', 'mail2con')") or db_die();
  }
}

?>
