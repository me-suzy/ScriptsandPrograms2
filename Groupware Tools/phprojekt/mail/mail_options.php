<?php

// mail_options.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: mail_options.php,v 1.12 2005/06/20 14:49:08 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("mail") < 1) { die("You are not allowed to do this!"); }
//tabs
$tabs = array();
$output = get_tabs_area($tabs);

// button bar
$buttons = array();
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=send_form&amp;form=email'.$sid, 'text' => __('Write'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;action=fetch_new_mail&amp;sort='.$sort.'&amp;up='.$up.'&amp;view_only=1'.$sid, 'text' => __('view mail list'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;action=fetch_new_mail&amp;sort='.$sort.'&amp;up='.$up.$sid, 'text' => __('Receive'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;action=fetch_new_mail&amp;sort='.$sort.'&amp;up='.$up.'&amp;no_del=1'.$sid, 'text' => __('and leave on server'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=forms&amp;form=d'.$sid, 'text' => __('Directory').' '.__('Create'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=options'.$sid, 'text' => __('Options'), 'active' => false);
$output .= get_buttons_area($buttons);

$output .= '<div class="hline"></div><a name="content"></a>';

  // mail accounts

    $disabl = "disabled='disabled'";
     // copy project branches
 $output.='
 <div class="header">'.__('Accounts').'</div>
 <div class="formbody_mailops">';
 $output.= "<fieldset>";
 $output.= "<form action='mail.php' method='post' >\n";
 $output.= "<input type='hidden' name='mode' value='accounts'/>\n";
 $output.= "<h5>".__('Accounts')."</h5>\n";
 $output.= "<input type='hidden' name='action' value='accounts' />\n";
 $output.= "<input type='submit' class='button2' name='neu' value='".__('Create')."' /> &nbsp;&nbsp;".__('or')." \n";
 $output.= "<select name='ID' ><option value=''></option>";
  $result = db_query("select ID,von,accountname,hostname,type,username,password
                        from ".DB_PREFIX."mail_account
                       where von ='$user_ID'") or db_die();
  while ($row = db_fetch_row($result)) {$output.= "<option value='$row[0]'>$row[2]</option>\n";$disabl ="";}
 $output.= "</select>&nbsp;&nbsp;";
  if(SID)$output.= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
 $output.= "<input type='submit' name='aendern' class='button2' value='".__('Modify')."' $disabl />&nbsp;&nbsp;";
 $output.= "<input type='submit' name='loeschen' class='button2' value='".__('Delete')."' $disabl onclick=\"return confirm('".__('Are you sure?')."')\" />&nbsp;&nbsp;\n";
 $output.= "</form></fieldset></div>\n";

  // query one single account
    $disabl = "disabled='disabled'";
 $output.=" <div class='header'>".__('Accounts').": ".__('Single account query')."</div>
            <div class='formbody_mailops'>";
 $output.= "<fieldset>";
 $output.= "<form action='mail.php' method='post'>\n";
 $output.= "<h5>".__('Accounts').": ".__('Single account query')."</h5>\n";
 $output.= "<input type='hidden' name='mode' value='view' />\n";
 $output.= "<input type='hidden' name='action' value='fetch_new_mail' />\n";
 $output.= "<select name='account_ID'><option value=''></option>";
  $result = db_query("select ID,von,accountname,hostname,type,username,password
                        from ".DB_PREFIX."mail_account
                       where von ='$user_ID'") or db_die();
  while ($row = db_fetch_row($result)) {$output.= "<option value='$row[0]'>$row[2]</option>\n"; $disabl ="";}
 $output.= "</select>&nbsp;&nbsp;";
  if(SID)$output.= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
 $output.= "<input class='button2' type='submit' value='".__('go')."' $disabl />\n";
 $output.= "</form></fieldset></div>\n";



  if (check_role("mail") > 1) {
    // senders and signatures
      $disabl = "disabled='disabled'";
   $output.=" <div class='header'>".__('Sender')." / ".__('Signature')."</div>
              <div class='formbody_mailops'>";
   $output.= "<fieldset>";
   $output.= "<form action='mail.php' method='post'>\n";
   $output.= "<input type='hidden' name='mode' value='sender' />\n";
    // title: sender /signature
   $output.= "<h5>".__('Sender')." / ".__('Signature')."</h5>\n";
   $output.= "<input type='hidden' name='action' value='sender' />\n";
   $output.= "<input class='button2' type='submit' name='neu' value='".__('Create')."' />&nbsp;&nbsp; ".__('or')." \n";
   $output.= "<select name='ID'><option value=''></option>";
    $result = db_query("select ID,von,title,sender,signature
                          from ".DB_PREFIX."mail_sender
                         where von ='$user_ID'") or db_die();
    while ($row = db_fetch_row($result)) {$output.= "<option value='$row[0]'>$row[2]</option>\n"; $disabl ="";}
   $output.= "</select>&nbsp;&nbsp;";
    if(SID)$output.= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
   $output.= "<input type='submit' class='button2' name='aendern' value='".__('Modify')."' $disabl />&nbsp;&nbsp;";
   $output.= "<input type='submit' class='button2'  name='loeschen' value='".__('Delete')."' $disabl onclick=\"return confirm('".__('Are you sure?')."')\" />&nbsp;&nbsp;\n";
   $output.= "</form></fieldset></div>\n";
  }

  // rules
    $disabl = "disabled='disabled'";
 $output.=" <div class='header'>".__('Rules')."</div>
            <div class='formbody_mailops'>";
 $output.= "<fieldset>";
 $output.= "<form action='mail.php' method='post'>";
 $output.= "<h5>".__('Rules')."</h5>\n";
 $output.= "<input type='hidden' name='mode' value='rules' />\n";
 $output.= "<input type='hidden' name='action' value='rules' />\n";
 $output.= "<input type='submit' class='button2' name='neu' value='".__('Create')."' />&nbsp;&nbsp; ".__('or')." \n";
 $output.= "<select name='ID'><option value=''></option>";
  $result = db_query("select ID,von,title,phrase,type,is_not,parent,action
                        from ".DB_PREFIX."mail_rules
                       where von ='$user_ID'") or db_die();
  while ($row = db_fetch_row($result)) {$output.= "<option value='$row[0]'>$row[2]</option>\n"; $disabl ="";}
 $output.= "</select>&nbsp;&nbsp;";
  if(SID)$output.= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
 $output.= "<input type='submit' class='button2' name='aendern' value='".__('Modify')."' $disabl />&nbsp;&nbsp;";
 $output.= "<input type='submit' class='button2' name='loeschen' value='".__('Delete')."' $disabl onclick=\"return confirm('".__('Are you sure?')."')\" />&nbsp;&nbsp;\n";
 $output.= "</form></fieldset></div>\n";

  // default dir for incoming mails
    $disabl = "disabled='disabled'";
  // first fetch the current parent
  $result = db_query("select parent
                        from ".DB_PREFIX."mail_rules
                       where von ='$user_ID' and
                             type like 'incoming'") or db_die();
  $row = db_fetch_row($result);
 $output.=" <div class='header'>".__('All')." ".__('imcoming Mails')." ".__('in').":</div>
            <div class='formbody_mailops'>";
 $output.= "<fieldset>";
 $output.= "<form action='mail.php' method='post'>";
 $output.= "<input type='hidden' name='mode' value='rules' />\n";
  if(SID)$output.= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
 $output.= "<input type='hidden' name='action' value='rules' />\n";
 $output.= "<input type='hidden' name='mode2' value='incoming' />\n";
  // text 'all incoming mail'
 $output.= __('All')." ".__('imcoming Mails')." ".__('in').":\n";
   $output.= "<select name='dir'><option value=''></option>";
    $result2 = db_query("select ID, subject
                           from ".DB_PREFIX."mail_client
                          where von = '$user_ID' and
                                typ like 'd'") or db_die();
    while ($row2 = db_fetch_row($result2)) {
     $output.= "<option value='$row2[0]'";
            $disabl ="";
      if ($row2[0] == $row[0]) {$output.= " selected"; }
     $output.= ">$row2[1]</option>\n";
    }
   $output.= "</select>";
 $output.= " <input type='submit' class='button2' name='make' value='".__('Modify')."' $disabl />&nbsp;";
 $output.= "</form></fieldset></div>\n";

  // default dir for outgoing mails
    $disabl = "disabled='disabled'";
  // first fetch the current parent
  $result = db_query("select parent
                        from ".DB_PREFIX."mail_rules
                       where von ='$user_ID' and
                             type like 'outgoing'") or db_die();
  $row = db_fetch_row($result);
 $output.=" <div class='header'>".__('All')." ".__('sent Mails')." ".__('in')."</div>
            <div class='formbody_mailops'>";
 $output.= "<fieldset>";
 $output.= "<form action='mail.php' method='post'>";
 $output.= "<input type='hidden' name='mode' value='rules' />\n";
  if(SID)$output.= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
 $output.= "<input type='hidden' name='action' value='rules' />\n";
 $output.= "<input type='hidden' name='mode2' value='outgoing' />\n";
  // text 'all outgoing mail'
 $output.= __('All')." ".__('sent Mails')." ".__('in').":\n";
   $output.= "<select name='dir'> <option value=''></option>";
    $result2 = db_query("select ID, subject
                           from ".DB_PREFIX."mail_client
                          where von = '$user_ID' and
                                typ like 'd'") or db_die();
    while ($row2 = db_fetch_row($result2)) {
     $output.= "<option value='$row2[0]'";
            $disabl ="";
      if ($row2[0] == $row[0]) {$output.= " selected"; }
     $output.= ">$row2[1]</option>\n";
    }
   $output.= "</select>";
 $output.= " <input type='submit' class='button2' name='make' value='".__('Modify')."' $disabl />&nbsp;";
 $output.= "</form></fieldset></div>\n";

  // checkbox: search for email in contact list and assign mail to this contact automatically
 $output.=" <div class='header'>".__('Assign to contact according to address')."</div>
            <div class='formbody_mailops'>";
 $output.= "<fieldset>";
 $output.= "<form action='mail.php' method='post'>";
 $output.= "<input type='hidden' name='mode' value='rules' />\n";
  if(SID)$output.= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
 $output.= "<input type='hidden' name='action' value='rules' />\n";
 $output.= "<input type='hidden' name='mode2' value='mail_to_contact' />\n";
  $result = db_query("select ID
                        from ".DB_PREFIX."mail_rules
                       where von ='$user_ID' and
                             type like 'mail2con'") or db_die();
  $row = db_fetch_row($result);
 $output.= "<input type='checkbox' name='mail2con'";
  if ($row[0] > 0) {$output.= ' checked'; }
 $output.= " /> ".__('Assign to contact according to address')." <input type='submit' class='button2' name='make' value='".__('go')."' /></form></fieldset></div>\n";

 echo $output;
?>