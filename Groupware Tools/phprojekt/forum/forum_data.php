<?php

// forum_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: forum_data.php,v 1.15.2.1 2005/08/22 09:40:04 nina Exp $

// check lib
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("forum") < 2) { die("You are not allowed to do this!"); }

$include_path3 = $path_pre."lib/access.inc.php";
include_once $include_path3;
$access = assign_acc($acc, 'forum');

function insert($fID="", $ID="") {

  global $user_ID, $titel, $remark, $user_group, $dbIDnull, $dbTSnull,
  $lib_path, $notify_others, $notify_me, $user_email, $mail,$user_name,
  $access, $acc_write;

// if it's a root posting, st the 'lastchanged' field to NOW so it appears on the top of the list
  if ($ID == 0) { $lastchange = $dbTSnull; }
  // otherwiese theis field doesn't matter :-)
  else { $lastchange = "NULL"; }
  // write access is not really necessary at the moment since we do not have an update function :-))
  if ($acc_write <> '') { $acc_write = 'w'; }
  // database action - insert record
  $result = db_query(xss("insert into ".DB_PREFIX."forum
          (ID, von, titel, remark,datum, gruppe,parent,antwort,lastchange,   notify,      acc,      acc_write)
         values ($dbIDnull,'$user_ID','$titel','$remark','$dbTSnull','$user_group','$fID','$ID','$lastchange','$notify_me','$access','$acc_write')")) or db_die();
    // update root posting to the current date
  if ($ID > 0) { update_root($ID); }
  if ($fID > 0) { update_root($fID); }

  // send out notifications to the group members if the flag is set
  if (PHPR_FORUM_NOTIFY and $notify_others) {
     // get last insert id
    $result = db_query("SELECT MAX(ID) FROM ".DB_PREFIX."forum");
    $row = db_fetch_row($result);
    // include the library from lib
    include_once("$lib_path/email_notification.inc.php");
    // call routine to send mails with notification about the new record
    email_notification(__('Forum'), "all",$titel, array('last_insert_id' => $row[0]));
  }
  // next notification: check whether the poster of the parent posting wants to be informed
  if ($fID > 0) {
  	$bei=$fID;
   	if($ID>0)$bei=$ID;
    $result = db_query("select titel, notify, email
                          from ".DB_PREFIX."forum, ".DB_PREFIX."users
                         where ".DB_PREFIX."forum.ID = '$bei' and
                               ".DB_PREFIX."forum.von = ".DB_PREFIX."users.ID") or db_die();
    $row = db_fetch_row($result);
    if ($row[1] == "on" and $row[2] <> "" ) {
    use_mail('1');
    $titel1 = __('You got an answer to your posting').' '.$row[0].' '.__('by').' '.$user_name.', '.__('Title').': '.$titel;
    $success = $mail->go($row[2],__('Answer to your posting in the forum'),$titel1,$user_email);
    }
  }
  // end routines email notification
}

// find the root posting and update the 'lastchanged' value
function update_root($antwort) {
  global $dbTSnull;

  while ($antwort > 0) {
    $result = db_query("select id, antwort
                          from ".DB_PREFIX."forum
                         where ID = '$antwort'") or db_die();
    $row = db_fetch_row($result);
    $antwort = $row[1];
  }
  $result=db_query(xss("update ".DB_PREFIX."forum
                       set lastchange='$dbTSnull'
                     where ID='$row[0]'")) or db_die();
}

if($createfor){
    insert();
    $createfor="";
}

elseif($createbei){
    insert($fID);
    $createbei="";
}
elseif($answer){
    insert($fID,$ID);
    $createbei="";
}
elseif(empty($back)){
// insert record into database ....
insert();
}
// ... and call the list
include_once("./forum_view.php");


?>