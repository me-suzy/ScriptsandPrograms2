<?php

// mail_fetch.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: mail_fetch.php,v 1.10.2.4 2005/09/15 07:58:10 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("mail") < 1) die("You are not allowed to do this!");

// New function for fetching mails form more than one account!
function get_mails($row, $path_pre){
    global $port, $user_ID, $dbTSnull, $dbIDnull, $view_only, $no_del;
    // compose connect string
    $acc_t = $row[4];
    $acc_string = "\{$row[3]:$port[$acc_t]}INBOX";
    if(ereg('imap',$row[4]))$imap="on";
    else $imap="off";
    // open  gate to mailserver
    $link2 = imap_open($acc_string,$row[5],$row[6]);
    // access failed? -> give message
    if (!$link2) {
        echo "<b>".__('Access error for mailbox')." $row[2]!</b>:<br />".imap_last_error()."<br />";
        $error = 1;
    }

    // ****************************
    // access ok? -> fetch messages
    if (!$error) {
        $n_deleted = 0;

        // display information & status, close and reopen again for fetching mails.
        $check = imap_mailboxmsginfo($link2);
        $list_mails = "<br />$user_name / $row[2]: $check->Nmsgs ".__('records')." ($check->Size Bytes) ";

        // headers = number of mails
        $headers=imap_headers($link2);
        for($x=1; $x <= count($headers); $x++) {
            // fetch all header entries
            $head = imap_header($link2,$x);

            // message ID
            $message_ID = substr($head->message_id,1,-1);
            // check whether this message is already in the database
            $skip_mail = 0;
            if ($no_del == 1 or $imap=="on") {
                $result3 = db_query("select ID
                             from ".DB_PREFIX."mail_client
                            where message_ID = '$message_ID'") or db_die();
                $row3 = db_fetch_row($result3);
                if ($row3[0] > 0) { $skip_mail = 1; }
            }
            // if there is already an entry in
            if (!$skip_mail) {
                // header information
                $date = $head->date;
                $email["date"] = strftime("%Y%m%d%H%M%S",$head->udate);
                // save header in field for later db storage
                if ($save_mail_header) { $email[header] = imap_fetchheader($link2,$x); }
                else { $email[header] = ""; }

                // create 'from' string
                $from_head  = $head->from[0];
                // in case the name variable is empty, take the value from the mailbox

                $sender=imap_mime_header_decode($from_head->personal);
                $from_head->personal = addslashes($sender[0]->text);

                if ($from_head->personal == "") { $from_head->personal = $from_head->mailbox."@".$from_head->host; }
                $email[sender] = addslashes($from_head->personal."<".$from_head->mailbox."@".$from_head->host.">");

                // create 'to' string
                $i = 0;
                $email[recipient] = "";
                while ($head->to[$i] <> "") {
                    $to_head = $head->to[$i];
                    $to_head_pers = imap_mime_header_decode($to_head->personal);
                    $to_head_personal = addslashes($to_head_pers[0]->text);
                    if ($to_head_personal == "") { $to_head_personal = $to_head->mailbox."@".$to_head->host; }
                    if ($i > 0) $email[recipient] .= ", ";
                    $email[recipient] .= addslashes($to_head_personal."<".$to_head->mailbox."@".$to_head->host.">");
                    $i++;
                }

                // create 'cc' string
                $i = 0;
                $email[cc] = "";
                while ($head->cc[$i] <> "") {
                    $cc_head = $head->cc[$i];
                    $cc_head_pers = imap_mime_header_decode($cc_head->personal);
                    $cc_head_personal = addslashes($cc_head_pers[0]->text);
                    if ($cc_head_personal == "") { $cc_head_personal = $cc_head->mailbox."@".$cc_head->host; }
                    if ($i > 0) $email[cc] .= ", ";
                    $email[cc] .= addslashes($cc_head_personal."<".$cc_head->mailbox."@".$cc_head->host.">");
                    $i++;
                }

                // reply to
                $reply_to_head = $head->reply_to[$i];
                $reply_to_head_pers = imap_mime_header_decode($reply_to_head->personal);
                $reply_to_head_personal = addslashes($reply_to_head_pers[0]->text);
                if ($reply_to_head_personal == "") { $reply_to_head_personal = $reply_to_head->mailbox."@".$reply_to_head->host; }
                $email[reply_to] = addslashes($replyto_head_personal."<".$replyto_head_mailbox."@".$replyto_head_host.">");

                // subject
                $subject=imap_mime_header_decode($head->subject);
                $email[subject] = chop(addslashes($subject[0]->text));
                // no text in subject? -> default text
                if ($email[subject] == "") { $email[subject] = "[".__('Subject')."]"; }

                // if the user just want to get the maillist displayed
                if ($view_only) {
                    $mail_arr[$email[subject]] = $email[sender];
                }
                // in all other cases store the mails in the database
                else {

                    // body
                    // first get the  plain part ...
                    $body_tmp = get_part($link2, $x, "TEXT/PLAIN");
                    $body_tmp1 =imap_mime_header_decode($body_tmp);
                    $email[body_text] = addslashes(ereg_replace("\r","",$body_tmp1[0]->text));
                    // ... and then the html part
                    $body_tmp = get_part($link2, $x, "TEXT/HTML");
                    $email[body_html] = addslashes($body_tmp);

                    $parent_contact = 0;
                    $parent_project = 0;
                    $parents = apply_rules($email);

                    // try to assign the mail2contact - overrules some special rules if exist
                    if ($mail2contact == 1) {
                        $result3 = db_query("select ID
                               from ".DB_PREFIX."contacts
                              where (acc like 'system' or ((von = '$user_ID' or acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group)) and
                                    email like '$email[sender]'") or db_die();
                        $row3 = db_fetch_row($result3);
                        if ($row3[0] > 0) { $parent_contact = $row3[0]; }
                    } // end assign mail2contact

                    $i = 0;
                    while (isset($parents[$i])) {
                        // save mail to db
                        $result3 = db_query("insert into ".DB_PREFIX."mail_client
                                      (   ID,        von,           subject,          body,               sender,          recipient,          cc,date_received,touched,typ,parent, date_sent,            header,          replyto,          body_html,           contact,          projekt ,  message_ID   )
                               values ($dbIDnull,'$user_ID','$email[subject]','$email[body_text]','$email[sender]','$email[recipient]','$email[cc]','$dbTSnull',0,'m',$parents[$i],'$email[date]','$email[header]','$email[replyto]','$email[body_html]','$parent_contact','$parent_project','$message_ID' )") or db_die();
                        // fetch ID as reference for attachment storage
                        $result3 = db_query("select max(ID)
                               from ".DB_PREFIX."mail_client
                              where von = '$user_ID' and
                                    date_received like '$dbTSnull'") or db_die();
                        $row3 = db_fetch_row($result3);
                        $mail_ID = $row3[0];

                        // attachments?
                        $attach = imap_fetchstructure($link2,$x);
                        $attachments = $attach->parts;

                        // loop over all attachments
                        for ($a=0; $a < count($attachments); $a++) {

                            // check whether the info is in parameters or dparameters
                            if ($attachments[$a]->ifparameters) $count = count($attachments[$a]->parameters);
                            else $count = count($attachments[$a]->dparameters);

                            for ($c = 0; $c < $count; $c++) {
                                // same thing here: infos could be in parameters or dparameters
                                if ($attachments[$a]->ifparameters) $param = $attachments[$a]->parameters[$c];
                                else $param = $attachments[$a]->dparameters[$c];

                                // fetch encoding
                                $att_enc = $attachments[$a]->encoding;

                                // fetch filesize
                                $att_size = $attachments[$a]->bytes;

                                // fetch attachment name
                                if (eregi("name",$param->attribute))  {
                                    $att_name1 = $param->value;

                                    // decode name
                                    $att_name2 =imap_mime_header_decode($att_name1);
                                    $att_name = addslashes($att_name2[0]->text);
                                    // here is a workaround for a real strange problem - admins report that sometimes
                                    // the file name to store begins with a " ' " causing a sql error -> workaround: delete the ' :-))
                                    while (substr($att_name,0,1) == "'") { $att_name = substr($att_name,1); }
                                    // end bloody workaround :-()

                                    // fetch content of attachment
                                    $a1 = $a + 1;
                                    $att_content = imap_fetchbody($link2, $x, $a1);

                                    // decode
                                    if ($att_enc == 2) { $att_content = imap_binary($att_content); }
                                    if ($att_enc == 3) { $att_content = imap_base64($att_content); }
                                    if ($att_enc == 4) { $att_content = imap_qprint($att_content); }
                                    // save file, first create random name
                                    $att_tempname = "";
                                    srand((double)microtime()*1000000);
                                    $char = "123456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMANOPQRSTUVWXYZ";
                                    while (strlen($att_tempname) < 12) { $att_tempname .= substr($char,(rand()%(strlen($char))),1);}
                                    // add extension to random name
                                    $att_tempname .= strstr($att_name,'.');
                                    // write file

                                    $att_file = $path_pre.PHPR_ATT_PATH."/".$att_tempname;
                                    if ($fp = @fopen($att_file,'w+') ) {
                                        $fw = fwrite($fp,$att_content);
                                        fclose($fp);
                                    }                                    // write record to db
                                    // if the attachment hasn't any size, just tell it so :)
                                    if (!$att_size) $att_size = "0";
                                    $result3 = db_query("insert into ".DB_PREFIX."mail_attach
                                          (   ID,      parent,  filename,       tempname,   filesize )
                                   values ($dbIDnull,'$mail_ID','$att_name','$att_tempname','$att_size')") or db_die();

                                }
                            }
                        }  // end loop over attachments
                        $i++;
                    } // end loop over different rules

                    // mark mails for later deletion if $no_del is not set.
                    if (!$no_del and $imap=="off") { imap_delete($link2, $x); }

                } // end bracket store mail
                if ($n_deleted > 0 and !$no_del) { echo " / $n_deleted ".__(' is deleted.'); }
            } // end bracket to skip mail because it's already in the db
        } // end for each mail
        // delete marked mails in the mailbox
        imap_expunge($link2);
        // close access to mailbox
        imap_close($link2);
    }
    return $list_mails;
}

// RULES
function apply_rules($email) {
  global $user_ID, $n_deleted, $parent_contact, $parent_project, $mail2contact;

  // fetch rules, write to array
  $i = 0;
  $result2 = db_query("select ID,von,title,phrase,type,is_not,parent,action, projekt, contact
                         from ".DB_PREFIX."mail_rules
                        where von = '$user_ID'") or db_die();
  while ($row2 = db_fetch_row($result2)) {

    // check for general rules: incoming dir and assign to contact
    if ($row2[4] == 'incoming') {$incoming = $row2[6]; }
    if ($row2[4] == 'mail2con') $mail2contact = 1;

      // write field and phrase in array
      $rules[$i] =  array("$row2[4]" => "$row2[3]");
      // create another array with the same counter, place target dir.
      $rules_action[$i] = $row2[7];
      $dir_ID[$i] = $row2[6];
    $projekt_ID[$i] = $row2[8];
    $contact_ID[$i] = $row2[9];
      $i++;
    }

  // check for applicable rule
  unset($action);
  unset($parents);
  // only one rule applicable at the moment, sorry.
  for ($i = 0; $i < count($rules); $i++) {
    // loop over all
    list($field, $keyword2) = each($rules[$i]);
    // special rule for body since we have to search through 2 fields
    if ($field  == 'body') {
      if(eregi($keyword2,$email[body_text]) or eregi($keyword2,$email[body_html])) {
        $parent = $dir_ID[$i];
        $action = $rules_action[$i];
        if( slookup('projekte','ID','ID',$row[9]) > 0) $parent_project = $row[9];
        if( slookup('contacts','ID','ID',$row[10]) > 0) $parent_contact = $row[10];
      }
    }
    else { // applicable for all other fields
      if ($email[$field]) {
        if(eregi($keyword2,$email[$field])) {
          $parent = $dir_ID[$i];
          $action = $rules_action[$i];
          if( slookup('projekte','ID','ID',$projekt_ID[$i]) > 0) $parent_project = $projekt_ID[$i];
          if( slookup('contacts','ID','ID',$contact_ID[$i]) > 0) $parent_contact = $contact_ID[$i];
        }
      }
    }
  }

  // now for the actions
  if ($action == 'delete') {
    unset($parents);
    $n_deleted++; // raise number of deleted mails
  }
  // move -> sort into given dir
  elseif($action == 'move') {
    $parents[0] = $parent;
  }
  // copy -> mail goes into given dir and in the root
  elseif($action == 'copy') {
    $parents[0] = $parent;
    $parents[1] = 0;
  }
  // check for default rule
  elseif ($incoming > 0) {
    // any other rule already given? -> add the rule
    if ($parents[0] > 0) { $parents[1] = $incoming; }
    // not other rule? -> move into this folder
    else { $parents[0] = $incoming; }
  }
  // no rules -> normal save into root dir
  else {
    $parents[0] = 0;
  }
  return $parents;
}

?>
