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

$mail_addr = trim(getParam('mail_addr'));
$conditions =& buildUserSearchConditions();

if( getParam('send') ) {
/********************************************************
 * send mail
 */
  import('template');

  if(!empty($mail_addr))
  {

    $_POST['body'] = $_POST['subject']."\n\n".$_POST['body'];
    $em = new templateElement($_POST['body']);
    $em->set('boardtitle', SET_TITLE);
    $em->set('boardurl', SET_FORUM_URL);
    $em->parse();
    $mailcontent =& $em->getContent();
    $mailsubject =& substr($mailcontent,0, strpos($mailcontent,"\n"));
    $mailcontent =& substr($mailcontent, strpos($mailcontent,"\n"));
    $mails = explode("\n", $mail_addr);

    foreach($mails as $email) {
      if(($email = trim($email)) && isEmail($email)) {
        mail($email, $mailsubject, $mailcontent, "From: \"".SET_BOARD_EMAIL."\" <".SET_BOARD_EMAIL.">\n");
      }
    }

    acp_success_redirect('All e-mails have been sent successfully.', $_SERVER['PHP_SELF'].'?prog=user::mail');

  }
  else
  {

    // send to users    
    if(empty($_GET['actionid']))
    {
      // generate actionid / action file
      $actionid = md5(uniqid('cemail'));
      $actionfile = DATA_PATH.'/mailbox/'.$actionid.'.tmp';
      writetofile($actionfile, serialize($_POST) );

      $_GET['s'] = $_POST['s'];

      $_POST = unserialize($actiondata['form_conditions']);
      $conditions =& buildUserSearchConditions();

      acp_success_redirect('Action Generated, Continue to Send', $_SERVER['PHP_SELF'].'?prog=user::mail&send=1&actionid='.$actionid.'&s='.$_GET['s']);

    }
    else
    {
      $actionid =& $_GET['actionid'];
      $b = empty($_GET['b']) ? 1 : intval($_GET['b']);
      $actiondata =& unserialize(readfromfile( DATA_PATH.'/mailbox/'.$actionid.'.tmp' ));
      $_POST = unserialize($actiondata['form_conditions']);
      $conditions =& buildUserSearchConditions();

      $rs = $DB->query("SELECT u.userid, u.username, u.password, u.email FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) LEFT JOIN celeste_usergroup g ON(u.usergroupid = g.usergroupid)  ".buildUserSearchQueryConditions($conditions), ($b-1)*SET_PANEL_MAIL_PER_BATCH, SET_PANEL_MAIL_PER_BATCH);

      $actiondata['body'] = $actiondata['subject']."\n\n".$actiondata['body'];
      $em = new templateElement($actiondata['body']);
      $em->set('boardtitle', SET_TITLE);
      $em->set('boardurl', SET_FORUM_URL);

      while($ut = $rs->fetch()) {
        $em->set('userid', $ut['userid']);
        $em->set('username', $ut['username']);
        $em->set('password', $ut['password']);
        $em->parse();

        mail($ut['email'] , substr($em->final,0, strpos($em->final,"\n")), substr($em->final, strpos($em->final,"\n")), "From: \"".SET_BOARD_EMAIL."\" <".SET_BOARD_EMAIL.">\n");
      }

      $rs->free();
      unset($rs);
      unset($em);

      if($b*SET_PANEL_MAIL_PER_BATCH >= $s) {

        unlink(DATA_PATH.'/mailbox/'.$actionid.'.tmp');
        acp_success_redirect('All e-mails have been sent successfully.', $_SERVER['PHP_SELF'].'?prog=user::mail');

      } else {

        acp_success_redirect('Sending mails '.(($b-1)*SET_PANEL_MAIL_PER_BATCH+1).' / '.$s, $_SERVER['PHP_SELF'].'?prog=user::mail&send=1&actionid='.$actionid.'&s='.$_GET['s'].'&b='.($b+1) );

      }
    }

  }


} elseif(!empty($_POST['acpSubmit']) || !empty($_GET['uid']) || !empty($mail_addr)) {
/********************************************************
 * show mail form
 */
  $acp->newFrm('Bulk Mail Step 2');
  $acp->setFrmBtn('SEND MAIL', 'send');
  $acp->newTbl('Send To');

  ///////////////////////////////////////
  // confirm recipient
  if(!empty($mail_addr)) {
    $acp->newRow('To Email', $acp->frm->frmTextarea('mail_addr', $mail_addr));

  } else {
    $matched_users = $DB->result("SELECT count(*) FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) ".buildUserSearchQueryConditions($conditions));

    if($matched_users < 1)
      acp_exception('No Users Matched!');

    $acp->newRow('Matched Users', $matched_users.'<input type=hidden name=s value='.$matched_users.'>');

    if($_POST['showusers'])
    {
      $rs = $DB->query("SELECT u.username, u.email FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) LEFT JOIN celeste_usergroup g ON(u.usergroupid = g.usergroupid) ".buildUserSearchQueryConditions($conditions));
      $users_string = '';
      while($ut = $rs->fetch()) {
        $users_string .= $ut['username'].'<'.$ut['email'].">\n";
      }
      $acp->newRow('Matched Users', $acp->frm->frmTextarea('users_string', $users_string));
      $rs->free();
      unset($rs);
      unset($users_string);
    }
    else
    {
      if($conditions['username'])$acp->newRow('User Name contains', $conditions['username']);

      if($conditions['usergroupid'])
        $acp->newRow('In', $DB->result("SELECT title FROM celeste_usergroup WHERE usergroupid = '".$conditions['usergroupid']."'"));

      if($conditions['email'])$acp->newRow('Email contains', $conditions['email']);
      if($conditions['homepage'])$acp->newRow('Homepage contains', $conditions['homepage']);
      if($conditions['icq'])$acp->newRow('ICQ number contains', $conditions['icq']);
      if($conditions['msn'])$acp->newRow('MSN ID contains', $conditions['msn']);
      if($conditions['aim'])$acp->newRow('AIM name contains', $conditions['aim']);
      if($conditions['yahoo'])$acp->newRow('Yahoo! ID contains', $conditions['yahoo']);
      if($conditions['location'])$acp->newRow('Location contains', $conditions['location']);
      if($conditions['signature'])$acp->newRow('Signature contains', $conditions['signature']);
      if($conditions['ipaddress'])$acp->newRow('IP address contains', $conditions['ipaddress']);

      if($conditions['posts_min'] || $conditions['posts_max'])
        $acp->newRow('Posts From', $acp->frm->frmSpan('posts', '', $conditions['posts_min'], $conditions['posts_max']));

      if($conditions['rating_min'] || $conditions['rating_max'])
        $acp->newRow('Rating From', $acp->frm->frmSpan('rating', '', $conditions['rating_min'], $conditions['rating_max']));

      if($conditions['lastvisit_min'] || $conditions['lastvisit_max'])
        $acp->newRow('Last visit from', $acp->frm->frmDateSpan('lastvisit', $conditions['lastvisit_min'], $conditions['lastvisit_max']));

      if($conditions['lastpost_min'] || $conditions['lastpost_max'])
        $acp->newRow('Last post from', $acp->frm->frmDateSpan('lastpost', $conditions['lastpost_min'], $conditions['lastpost_max']));

      if($conditions['online']>1)
        $acp->newRow('Online Status', $conditions['online']==2 ? 'Online' : 'Offline');
    }

  } // end of 'if(!empty($mail_addr)) {'...'} else {'
  // end of 'confirm recipient'
  ///////////////////////////////////////
  $mailtemplate = $mail_addr ? 'acp_mail' : 'acp_mail_to_users';
  $mailcontent =& readfromfile(DATA_PATH.'/email/'.$mailtemplate.'.tpl');
  $mailsubject =& substr( $mailcontent, 0 , strpos($mailcontent, "\n"));
  $mailcontent =& substr( $mailcontent, strpos($mailcontent, "\n")+1);

  $form_conditions = '<input type=hidden name=form_conditions value=\''.
                      str_replace('\'', '"', serialize($conditions)).'\'>';
  $form_conditions.= $acp->frm->get($acp->frm->frmText('subject', $mailsubject));

  $acp->newTbl('Mail Form');
  $acp->newRow('Mail Subject', $form_conditions);
  $acp->newRow('Mail Body', $acp->frm->frmTextarea('body', $mailcontent), '* Built-in Tag:<br> {username}, {password},<br> {userid}, {boardtitle}, {boardurl}');

} else {
/********************************************************
 * show user search form
 */
  $acp->newFrm('Bulk Mail Step 1');
  $acp->setFrmBtn('Continue ...');

  $acp->newTbl('Options');
  $acp->newRow('Send Mail To', $acp->frm->frmList('mailto', 1, 'Forum Users', 'Specific Emails'));
  $acp->newRow('Show Matched Users', $acp->frm->frmAnOp('showusers', 1), '* If there are a lot of users, we sugguest you turn off the option');

  $acp->newTbl('Specific Emails');
  $acp->newRow('Send Mail To', $acp->frm->frmTextarea('mail_addr'), '* Seperated By New Line');

  buildUserSearchForm($conditions);

}
