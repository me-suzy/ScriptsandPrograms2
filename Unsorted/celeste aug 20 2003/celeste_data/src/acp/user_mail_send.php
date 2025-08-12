<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

$checkallscript = <<< EOF
<script language="JavaScript">
function Checkall(form)
{
var i;
for ( i = 0; i < form.elements.length; i++) form.elements[i].checked = true;
}
function UnCheckall(form)
{
var i;
for ( i = 0; i < form.elements.length; i++) form.elements[i].checked = false;
}
function checkstat(boolean, form) 
{ 
  if(boolean) {
    Checkall(form);
  } else {
    UnCheckall(form);
  }
}
</script>
EOF;

function Celeste_Send_Mail($filename) {
  if(!$filename) return false;
  $mailcontent = readfromfile($filename);
  $mailto      =& substr( $mailcontent, 4 , strpos($mailcontent, "\n")-4);
  $mailcontent =& substr( $mailcontent, strpos($mailcontent, "\n")+1);
  $mailfrom    =& substr( $mailcontent, 0 , strpos($mailcontent, "\n"));
  $mailcontent =& substr( $mailcontent, strpos($mailcontent, "\n")+1);
  $mailsubject =& substr( $mailcontent, 9 , strpos($mailcontent, "\n")-9);
  $mailcontent =& substr( $mailcontent, strpos($mailcontent, "\n")+1, 512);

  return mail($mailto, $mailsubject, $mailcontent, $mailfrom);

}

if (!empty($_POST['acpSubmit']) && $_POST['action']==3) {
/********************************************************
 * delete selected mails
 */
  foreach($_POST['delmails'] as $mail => $tmp) {
    unlink(DATA_PATH.'/mailbox/'.$mail);
  }

  acp_success_redirect('You have deleted the selected mails successfully.', $_SERVER['PHP_SELF'].'?prog=user::mail::send&list=1');

} elseif (!empty($_POST['acpSubmit']) && $_POST['action']==2) {

  $counter = 0;
  $succSend = 0;
  foreach($_POST['delmails'] as $mail => $tmp) {
    if(Celeste_Send_Mail(DATA_PATH.'/mailbox/'.$mail)) {
      unlink(DATA_PATH.'/mailbox/'.$mail);
      $succSend++;
    }
    $counter++;
  }

  if($succSend == $counter)
    acp_success_redirect('You have sent the selected mails successfully.', $_SERVER['PHP_SELF'].'?prog=user::mail::send&list=1');
  else
    acp_success_redirect('You have sent '.$succSend.' mails out of the '.$counter.' selected mails.', $_SERVER['PHP_SELF'].'?prog=user::mail::send&list=1');

} elseif ( (!empty($_POST['acpSubmit']) && $_POST['action']==1) || isset($_GET['continue'])) {
/********************************************************
 * send mails
 */
  $totalMails = intval(getParam('totalMails'));
  if($totalMails < 1) acp_success_redirect('No Mails To Send', $_SERVER['PHP_SELF'].'?prog=user::mail::send');

  isset($_POST['send']) && $beginNo = 0;
  $mailsPS = intval(getParam('mailsPS'));
  if(!$mailsPS) $mailsPS = 100;
  $endNo = $beginNo + $mailsPS;
  @$succSend = intval($_GET['succSend']);

  $d = dir(DATA_PATH.'/mailbox');
  $d->read();

  $counter = 0;
  while($d->read() && $counter<$beginNo) $counter++;
  // begin send mail
  while(($mail = $d->read()) && $counter<$endNo) {
    if(Celeste_Send_Mail(DATA_PATH.'/mailbox/'.$mail)) {
      unlink(DATA_PATH.'/mailbox/'.$mail);
      $succSend++;
    }
    $counter++;
  }
  $d->close();

  if($counter < $totalMails) {
    echo
    '<HTML><TITLE>Please Wait While Continue</TITLE>'.
    '<META HTTP-EQUIV = "Refresh" Content = "3; URL ='.$_SERVER['PHP_SELF'].'?prog=user::mail::send&continue=1&mailsPS='.$mailsPS.'&succSend='.$succSend.'&totalMails='.$totalMails.'&beginNo='.$counter.'">'.
    '<BODY><b>Total Mails To Send</b>: '.$totalMails.'<p>'.
    '<b>Mails Per Session</b>: '.$mailsPS.'<p>'.
    '<b>Processed</b>: '.$counter.'<p>'.
    '<b>Successfully Sent</b>: '.$succSend.'<p>'.
    '</BODY></HTML>';
    exit();

  } else {
    acp_success_redirect('All emails in the outbox have been sent', $_SERVER['PHP_SELF'].'?prog=user::mail::send');

  }


} else {

  $acp->newFrm('Send Mails', $_SERVER['PHP_SELF'].'?prog=user::mail::send&succSend=0&beginNo=0');
  $acp->setFrmBtn();
  $acp->newTbl('Options');
  $acp->newRow('Number of mails sent in a session', $acp->frm->frmText('mailsPS', 100, 10));

  $counter = 0;
  $d = dir(DATA_PATH.'/mailbox');
  $d->read();$d->read(); // '.', '..'

  if(getParam('list')) {
    $mails = array();
    while($m = $d->read()) {
      if(!$m || substr($m, -4)=='.tmp')continue;
      $mails[] = $m;
      $counter++;
    }

    $acp->newRow('Total mails to send', $counter." [ <a href='$_SERVER[PHP_SELF]?prog=user::mail::send'><b>Close List</b></a> ]  <input type=hidden name=totalMails value=".$counter.">");

    $acp->newTbl('Mails To Send');
    $acp->newRow('Action', $acp->frm->frmList('action', 1, '------>Send All Mails', '------>Send Selected Mails', '------>Delete Selected Mails'));
    $acp->newMenuRow($checkallscript.'<input type=checkbox value=1 name=delall onClick="checkstat(acpFrm.delall.value, acpFrm);">To', 'Subject', 'Body Preview');

    foreach($mails as $mail) {
      $mailcontent = readfromfile(DATA_PATH.'/mailbox/'.$mail);
      $mailto      =& substr( $mailcontent, 4 , strpos($mailcontent, "\n")-4);
      $mailcontent =& substr( $mailcontent, strpos($mailcontent, "\n")+1);
      $mailcontent =& substr( $mailcontent, strpos($mailcontent, "\n")+1);
      $mailsubject =& substr( $mailcontent, 0 , strpos($mailcontent, "\n"));
      $mailcontent =& substr( $mailcontent, strpos($mailcontent, "\n")+1, 512);
      $acp->newRow2("<input type='checkbox' name='delmails[".$mail."]'>".$mailto, $mailsubject, $mailcontent);
    }

  }
  else
  {
    while($mail = $d->read()) 
      if(substr($mail, -4)!='.tmp')$counter++;

    $acp->newRow('Total mails to send', $counter." [ <a href='$_SERVER[PHP_SELF]?prog=user::mail::send&list=1'><b>Show List</b></a> ] <input type=hidden name=totalMails value=".$counter."><input type=hidden name=action value=1>");

  }
  $d->close();
  unset($d);

}
