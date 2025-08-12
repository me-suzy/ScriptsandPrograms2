<?php

// mail_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: mail_data.php,v 1.14.2.1 2005/09/12 10:38:26 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("mail") < 1) die("You are not allowed to do this!");


// sadly enough here comes the third check whether the imap library is installed ;-)
if (!function_exists('imap_open')) die("Sorry but the full functionality of the mail client requires the imap-extension
                                        of php. Please ensure that this extension is active on your system.<br />
                                        In the meantime if you want to use the mail send module, set PHPR_QUICKMAIL=1; in the config.inc.php");

$include_path = $path_pre."lib/email_getpart.inc.php";
include_once $include_path;

// unset all references to attachments and let the script create them
//session_unregister("file_ID");
//unset($file_ID);
unreg_sess_var($file_ID);

// seems that the last function doesn't work properly on some php installations -> additional function to make sure that ...
$file_ID = array();

if ($delete_c <> '' || $delete_b <>'') {
    if(isset($ID_s) && $ID_s != ''){
        $ID = $ID_s;
    }
    manage_delete_records($ID,$module);
}

if ($action == "showhtml") {
  // fetch original data
  if ($ID) {
    $result = db_query("select ID, von, body_html
                          from ".DB_PREFIX."mail_client
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    // check permission
    if ($row[0] == 0) { die("no entry found."); }
    if ($row[1] <> $user_ID) { die("You are not allowed to do this!"); }
  }
  $body = stripslashes($row[2]);
  
  // remove text up to (including) <body> and the end of the HTML-Body
  $body = preg_replace("=(.*<body.*>)=isU", "", $body);
  $body = preg_replace("=(</body>.*)$=isU", "", $body);
    
  // add images into the html code
  $i = 0;
  while ($pos1 = strpos($body,"cid")) {
    $i++;
    $i2 = 0;
    $b1 = explode("cid",$body,2);
    $b2 = explode("\"",$b1[1],2);
    $result3 = db_query("select tempname
                           from ".DB_PREFIX."mail_attach
                          where parent = '$row[0]'") or db_die();
    while ($row3 = db_fetch_row($result3)) {
      $i2++;
      if ($i == $i2) { $imgstring = $path_pre.PHPR_ATT_PATH."/".$row3[0]; }
    }
    $body = $b1[0].$imgstring."\"".$b2[1];
  }
  // look for scripts in the string and try to avoid poisoned code ...
  $body = eregi_replace("<script","&lt;script",$body);
  // look for images which should be loaded from outer space ...
  $body = eregi_replace("<img[^>]*http[^>]*>","", $body);

  // output of the body from message an stop
  //die($body);
  echo $body;
}


// ***********************
// actions for directories

elseif ($modify_b <> '' and $ID > 0) {
  // check for same record name
  $result = db_query("select ID, subject
                        from ".DB_PREFIX."mail_client
                       where typ like 'd'") or db_die();
  while ($row = db_fetch_row($result)) {
    if ($row[0] <> $ID and $row[1] == $subject) die(__('This name already exists')."!<br /><a href='mail.php?mode=view&action=form&page=$page&perpage=$perpage&up=$up&sort=$sort$sid'>".__('back')."</a>");
  }
  // assign category
  if (!$kat) { $kat = $kat2; }

  // copy record with new values
  if ($c_m == "c") {
    $result = db_query("select ID,von,subject,body,sender,recipient,cc,kat,remark,date_received,touched,typ,
                               parent,date_sent,header,replyto,acc,body_html
                          from ".DB_PREFIX."mail_client
                         where ID = '$ID'") or db_die(); // fetch missing values from old record
    $row = quote_runtime(db_fetch_row($result));
    sqlstrings_create();
    $result = db_query("insert into ".DB_PREFIX."mail_client
                               (   ID,        von,   subject,  body,     sender,   recipient, cc,   date_received,touched,typ, date_sent, header,".$sql_fieldstring.",parent )
                        values ($dbIDnull,'$user_ID','$row[2]','$row[3]','$row[4]','$row[5]','$row[6]','$dbTSnull',  1,'m','$row[13]','$row[14]',".$sql_valuestring.",'$parent')") or db_die();
    // copy attachments as well
    copy_attachments($ID);

  }
  // update record or move item
  else {
    // if type = dir, then allow to change the name
    if ($typ == "d") $dirname2 = "subject = '$dirname',";
    else             $dirname2 = '';

    $sql_string=sqlstrings_modify();
/*
    echo "update ".DB_PREFIX."mail_client
                           set   $sql_string
                                 $dirname2
                                  parent='$parent'
                         where ID = '$ID'";
*/
    // update for all types (mail, dir)
    $result = db_query("update ".DB_PREFIX."mail_client
                           set   $sql_string
                                 $dirname2
                                  parent='$parent'
                         where ID = '$ID'") or db_die();
  }
  $action = "";
}

// create new dir
elseif ($create_b <> '') {
  if (!$dirname) {  // filename doesn't exists?
    echo __('Please select a file');
  }
  // check for same record name
  $result = db_query("select subject
                        from ".DB_PREFIX."mail_client
                       where typ like 'd'") or db_die();
  while ($row = db_fetch_row($result)) {
    if ($row[0] == $subject) {
      echo __('This name already exists')."!";
      $error = 1;
    }
  }
  // write record to db
  sqlstrings_create();
  if (!$error) {
    $result = db_query("insert into ".DB_PREFIX."mail_client
                               ( ID,         von,  date_received,typ,subject,parent,".$sql_fieldstring." )
                        values ($dbIDnull,'$user_ID','$dbTSnull', 'd','$dirname','$parent',".$sql_valuestring.")") or db_die();
    $action = "";
  }
}
$fields = build_array($module, $ID, 'view');
include_once("./mail_view.php");


function sendmail_link($mailadress,$m_name) {
  global  $sid;
  if (PHPR_QUICKMAIL > 0) {
    echo "<td><a href='mail.php?mode=send_form&recipient=$mailadress".$sid."')>$m_name</a>&nbsp;</td>\n";
  }
  else { echo "<td><a href='mailto:$mailadress'>$m_name</a>&nbsp;</td>\n"; }
}

function delete_record($delete_ID) {
  global $user_ID, $path_pre;
  $result = db_query("select ID, von, typ
                        from ".DB_PREFIX."mail_client
                       where ID = '$delete_ID'") or db_die();
  $row = db_fetch_row($result);
  // check permission
  if ($row[0] == 0) { die("no entry found."); }
  if ($row[1] <> $user_ID) { die("You are not allowed to do this!"); }
  // delete attachments
  if ($row[2] <> "d") {
    // select files
    $result2 = db_query("select tempname
                           from ".DB_PREFIX."mail_attach
                          where parent = '$delete_ID'") or db_die();
    while ($row2 = db_fetch_row($result2)) {
      $path = $path_pre.PHPR_ATT_PATH."/".$row2[0];
      unlink($path);
    }
    // delete records
    $result2 = db_query("delete from ".DB_PREFIX."mail_attach
                          where parent = '$delete_ID'") or db_die();
  }
  elseif ($row[2] == "d") {
    // free rules
    $result2 = db_query("update ".DB_PREFIX."mail_rules
                            set parent = ''
                          where parent = '$delete_ID'") or db_die();
  }
  // delete record itself
  $result2 = db_query("delete from ".DB_PREFIX."mail_client
                        where ID = '$delete_ID'") or db_die();
  // delete corresponding entry from db_record
  $result = db_query("delete from ".DB_PREFIX."db_records
                            where t_record = '$delete_ID' and t_module = 'mail'") or db_die();

  if ($row[2] == "d") del($row[0]); // look for files in the subdirectory
}


function sub($ID) {
  global $sid, $level, $user_access, $where,
         $user_kurz, $arrdir, $filter, $keyword, $sort, $direction,
         $up, $up2, $page, $perpage, $file_ID, $total_size, $tree_mode,
         $img_path, $sel_all;

  $result = db_query("select ID,von,subject,body,sender,recipient,cc,kat,remark,date_received,touched,typ,
                             parent,date_sent,header,replyto,acc,body_html
                        from ".DB_PREFIX."mail_client
                       where parent = '$ID'
                    order by ".qss($sort)." $direction") or db_die();
  while ($row = db_fetch_row($result)) {
    $ID = $row[0];
    $level++;
    include('./mail_list.php');
    if ($row[11] == "d" and ($arrdir[$ID] or $tree_mode == "open")) { sub($ID); }
    $level--;
  }
}

function del($delete_ID) {

  $result = db_query("select ID, von, typ
                        from ".DB_PREFIX."mail_client
                       where parent = '$delete_ID'") or db_die();
  while ($row = db_fetch_row($result)) {
    // delete attachments if it's not a directory
    if ($row[2] <> "d") {
      // select files
      $result2 = db_query("select tempname
                             from ".DB_PREFIX."mail_attach
                            where parent = '$delete_ID'") or db_die();
      while ($row2 = db_fetch_row($result2)) {
        $path = $path_pre.PHPR_ATT_PATH."/".$row2[0];
        unlink($path);
      }
      // delete records
      $result2 = db_query("delete from ".DB_PREFIX."mail_attach
                            where parent = '$delete_ID'") or db_die();
    }
    // directory? -> free rules
    else { $result2 = db_query("update ".DB_PREFIX."mail_rules
                                   set parent = ''
                                 where parent = '$delete_ID'") or db_die(); }

    // for all types: delete record itself
    $result2 = db_query("delete from ".DB_PREFIX."mail_client
                          where ID = '$row[0]'") or db_die();
    if ($row[2] == "d") del($row[0]); // look for mails etc. in the subdirectory
  }
}

function copy_attachments($ID) {
  global $user_ID, $row, $dbTSnull, $path_pre, $dbIDnull;

  // 1. fetch the ID of the new record
  $result2 = db_query("select ID
                         from ".DB_PREFIX."mail_client
                        where date_received = '$dbTSnull' and
                              von = '$user_ID' and
                              subject = '$row[2]'") or db_die(); // fetch missing values from old record
  $row2 = db_fetch_row($result2);
  // now fetch all attachments from the previous mail
  $result3 = db_query("select filename, filesize, tempname
                         from ".DB_PREFIX."mail_attach
                        where parent = '$ID'") or db_die();
  while ($row3 = db_fetch_row($result3)) {
    // add extension to random name
    $att_tempname = rnd_string().substr($row3[0],-4,4);
    // copy file
    copy($path_pre.PHPR_ATT_PATH."/".$row3[2],$path_pre.PHPR_ATT_PATH."/".$att_tempname);
    // write record to db
    $result4 = db_query("insert into ".DB_PREFIX."mail_attach
                                (   ID,     parent,   filename,        tempname,  filesize )
                         values ($dbIDnull,'$row2[0]','$row3[0]','$att_tempname','$row3[1]')") or db_die();
  }
}

?>
