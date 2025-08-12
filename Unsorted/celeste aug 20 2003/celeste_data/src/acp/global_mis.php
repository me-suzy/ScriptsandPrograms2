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

import('modify_setting');

if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Miscellaneous Settings in Your Celeste');
  $acp->setFrmBtn();

  $acp->newTbl('Guest', 'guest');
  $acp->newRow('Guest Name', $acp->frm->frmText('SET_GUEST_NAME', SET_GUEST_NAME, 60));
  $acp->newRow('Allow Guest View Forum ?', $acp->frm->frmAnOp('SET_ALLOW_GUEST', SET_ALLOW_GUEST));
  //$acp->newRow('Allow Guest Use Search ?', $acp->frm->frmAnOp('SET_ALLOW_GUEST_SEARCH', SET_ALLOW_GUEST_SEARCH));
  $acp->newRow('Allow Guest View User Profile ?',
                $acp->frm->frmAnOp('SET_ALLOW_GUEST_VIEW_USER', SET_ALLOW_GUEST_VIEW_USER));
  $acp->newRow('Allow Guest View Users List ?',
                $acp->frm->frmAnOp('SET_ALLOW_GUEST_VIEW_USER_LIST', SET_ALLOW_GUEST_VIEW_USER_LIST));


  $acp->newTbl('Online Session Time', 'online');
  $acp->newRow('User with action in this session time will be considered as online.',
                $acp->frm->frmText('SET_ONLINE_DURATION', SET_ONLINE_DURATION/60, 25),
                '* In minutes');

  $acp->newTbl('Post', 'post');
  $acp->newRow('Flood Control',
                $acp->frm->frmText('SET_FLOOD_CONTROL_TIME', SET_FLOOD_CONTROL_TIME, 25),
                '* Min time gap between two posts for one user<br> * In minutes');

  $acp->newRow('Max Length of Post', $acp->frm->frmText('SET_MAX_POST_LENGTH', SET_MAX_POST_LENGTH, 25));
  $acp->newRow('Allow Smile Tag ?', $acp->frm->frmAnOp('SET_ALLOW_SMILE', SET_ALLOW_SMILE));
  $acp->newRow('Allow Flash Tag ?', $acp->frm->frmAnOp('SET_ALLOW_FLASH', SET_ALLOW_FLASH));


  $acp->newTbl('Upload', 'upload');
  $acp->newRow('Max File Size',
                $acp->frm->frmText('SET_ALLOW_UPLOAD_SIZE', SET_ALLOW_UPLOAD_SIZE/1024, 25), '* Measured in KB <br>* Server Allowed: '.(ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'Disabled'));
  $acp->newRow('Permitted File Types',
                $acp->frm->frmText('SET_ALLOW_UPLOAD_TYPE', SET_ALLOW_UPLOAD_TYPE, 60), '* Seperated by space(" ")');
  $acp->newRow('Public Attachments',
                $acp->frm->frmAnOp('SET_DIRECT_ATT', SET_DIRECT_ATT), '* If yes, attachments in public forum will be saved in HTTP accessible dir<br>* This option will greatly reduce the database workload');
  $acp->newRow('Public Attachments : Exceptional Types',
                $acp->frm->frmText('SET_DIRECT_ATT_DISALLOW_TYPE', SET_DIRECT_ATT_DISALLOW_TYPE));
  $acp->newRow('Max number of required rating',
                $acp->frm->frmText('SET_ATTACH_MAX_REQ_RATING', SET_ATTACH_MAX_REQ_RATING));
  $acp->newRow('Pay rating on download ?',
                $acp->frm->frmAnOp('SET_ATTACH_DL_PAY_RATING', SET_ATTACH_DL_PAY_RATING));
  
  $acp->newTbl('Signature', 'sign');
  $acp->newRow('Allow Celeste Tag ?', $acp->frm->frmAnOp('SET_ALLOW_CETAG_SIGN', SET_ALLOW_CETAG_SIGN));
  $acp->newRow('Allow Smile Tag ?', $acp->frm->frmAnOp('SET_ALLOW_SMILE_SIGN', SET_ALLOW_SMILE_SIGN));
  $acp->newRow('Allow HTML Tag ?', $acp->frm->frmAnOp('SET_ALLOW_HTML_SIGN', SET_ALLOW_HTML_SIGN));
  $acp->newRow('Allow Flash Tag ?', $acp->frm->frmAnOp('SET_ALLOW_FLASH_SIGN', SET_ALLOW_FLASH_SIGN));
  $acp->newRow('Allow Image Tag ?', $acp->frm->frmAnOp('SET_ALLOW_IMG_SIGN', SET_ALLOW_IMG_SIGN));
  $acp->newRow('Max number of images ?', $acp->frm->frmAnOp('SET_ALLOW_IMG_SIGN_MAX', SET_ALLOW_IMG_SIGN_MAX));


  $acp->newTbl('P.M.', 'PM');
  $acp->newRow('P.M. Prompting Method', $acp->frm->frmList('SET_PM_AUTO_CHECK', SET_PM_AUTO_CHECK+1, 'Do Not Prompt', 'Only Prompt in Forum Index Page', 'Pormpt In any Page'));
  $acp->newRow('Max number of recievers in one P.M.', $acp->frm->frmText('SET_PM_MAX_RECIEVERS', SET_PM_MAX_RECIEVERS, 25));
  $acp->newRow('Max Length of one P.M.', $acp->frm->frmText('SET_PM_MAX_LENGTH', SET_PM_MAX_LENGTH, 25));
  $acp->newRow('Max number of P.M.s hold by one user ( In All Folders )', 
                $acp->frm->frmText('SET_PM_MAX_PMS', SET_PM_MAX_PMS, 25),
                '');

  $acp->newTbl('Favorites', 'Favorites');
  $acp->newRow('Max Number of Topic in "My Favorites"', $acp->frm->frmText('SET_MAX_FAVORITES', SET_MAX_FAVORITES, 40));


  $acp->newTbl('Avatar', 'avatar');
  $acp->newRow('Allow user to upload their own avatar ?',
                $acp->frm->frmAnOp('SET_ALLOW_USER_UPLOAD_AVATAR', SET_ALLOW_USER_UPLOAD_AVATAR));

  $acp->newRow('Required rating to upload avatar',
                $acp->frm->frmText('SET_MIN_RATING_TO_UPLOAD_AVATAR', SET_MIN_RATING_TO_UPLOAD_AVATAR, 25),
                '* Invalid permission to upload own avatar under this rating');

  $acp->newRow('Required number of posts to upload avatar',
                $acp->frm->frmText('SET_MIN_POST_TO_UPLOAD_AVATAR', SET_MIN_POST_TO_UPLOAD_AVATAR, 25),
                '* Invalid permission to upload own avatar under this number of posts');

  $acp->newRow('Max size of uploaded avatar',
                $acp->frm->frmText('SET_MAX_AVATAR_FILESIZE', SET_MAX_AVATAR_FILESIZE/1024, 25),
                '* Measured in KB <br>* Server Allowed: '.(ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'Disabled'));

  $acp->newRow('Max Height', $acp->frm->frmText('SET_MAX_AVATAR_HEIGHT', SET_MAX_AVATAR_HEIGHT, 25));
  $acp->newRow('Max Width', $acp->frm->frmText('SET_MAX_AVATAR_WIDTH', SET_MAX_AVATAR_WIDTH, 25));


  $acp->newTbl('User Title', 'utitle');
  $acp->newRow('Required number of posts to use self-defined title', $acp->frm->frmText('SET_ALLOW_TITLE_POSTS', SET_ALLOW_TITLE_POSTS, 25));
  $acp->newRow('Required rating to use self-defined title', $acp->frm->frmText('SET_ALLOW_TITLE_RATING', SET_ALLOW_TITLE_RATING, 25));


  $acp->newTbl('Customization', 'custom');
  $acp->newRow('Default number of topics per page',
                $acp->frm->frmText('SET_TOPIC_PP', SET_TOPIC_PP, 25),
                '( in topic list )');

  $acp->newRow('Default number of posts per page',
                $acp->frm->frmText('SET_POST_PP', SET_POST_PP, 25),
                '( reading a topic )');

  $acp->newRow('Default number of users per page',
                $acp->frm->frmText('SET_USER_PP', SET_USER_PP, 25),
                '( in user list )');

  $acp->newRow('Min number of replies of a HOT topic', $acp->frm->frmText('SET_HOT_TOPIC', SET_HOT_TOPIC, 25));

  $acp->newRow('Numbers of latest posts in review when replying', $acp->frm->frmText('SET_POST_REVIEW_NUMBER', SET_POST_REVIEW_NUMBER, 25));

  $acp->newRow('Use Instant Reply Form ?',
                $acp->frm->frmAnOp('SET_FAST_REPLY', SET_FAST_REPLY),
                '* Instant reply form in reading page');

  $readModeList = $acp->frm->frmList('SET_DEFAULT_READMODE',
                                      (SET_DEFAULT_READMODE=='flat' ? 1 : 2), 
                                      'Flat Mode', 'Threaded Mode');
  $acp->newRow('Default Read Mode', $readModeList);



  $acp->newTbl('Email Settings', 'Email');
  $acp->newRow('Enable Email Function In Celeste?', $acp->frm->frmAnOp('SET_ENABLE_EMAIL', SET_ENABLE_EMAIL), '* If set to No, any email event will be ignored.');
  $acp->newRow('Enable Email Cache ?', $acp->frm->frmAnOp('SET_DELAY_SENDMAIL', SET_DELAY_SENDMAIL), '* If set to yes, emails are saved to a temp dir instead of sent immediately. Later admin can use the Send Mail module in ACP to send them.');

  $acp->newRow('Admin Email', $acp->frm->frmText('SET_ADMIN_EMAIL', SET_ADMIN_EMAIL, 60));

  $acp->newRow('Email Sender',
                $acp->frm->frmText('SET_EMAIL_SENDER', SET_EMAIL_SENDER, 60),
                '* Display in emails sent by celeste');

  $acp->newRow('System Email',
                $acp->frm->frmText('SET_BOARD_EMAIL', SET_BOARD_EMAIL, 60),
                '* Display in emails sent by celeste');


  $acp->newTbl('Registration', 'Reg');
  $acp->newRow('Enable Registration',
                $acp->frm->frmAnOp('SET_ENABLE_REG', SET_ENABLE_REG),
                '* Guest cannot register if closed' );

  $acActivat = $acp->frm->frmList('SET_REG_METHOD', SET_REG_METHOD+1,
                                  'Immediately Activated',
                                  'Send Activate Link By Email',
                                  'Send Password By Email',
                                  'Activate By Admin');
  $acp->newRow('Account Activation Mode', $acActivat, '* If set to Send Activate Link By Email or Send Password By Email, the sendmail function MUST be enabled.');

  $acp->newRow('Allow Duplicated Email ?',
                $acp->frm->frmAnOp('SET_ALLOW_DUPE_EMAIL', SET_ALLOW_DUPE_EMAIL),
                '* Duplicated Email in multi accounts is allowed if set to "yes"');

  $acp->newRow('Allow logged in users to register ?',
                $acp->frm->frmAnOp('SET_ALLOW_MULTI_REG', SET_ALLOW_MULTI_REG));


  $acp->newTbl('Date/Time', 'date');
  $acp->newRow('Server Time', '<b>'.date('Y-n-j G:i').'</b>, '.date('T( O )').' ');
  $acp->newRow('Current Forum Time', $acp->frm->frmText('current_time', date('Y-n-j G:i', time()+SET_TIME_ZONE_OFFSET), 25), '* Format: "YYYY-MM-DD Hour:Minute"');

  $acp->newRow('Date Format In Forum',
                $acp->frm->frmText('SET_DATE_FORMAT', SET_DATE_FORMAT, 30),
                '* Refer to <a href="http://www.php.net/manual/en/function.date.php" target="_blank">PHP Manual</a> For Definition');

  $acp->newRow('Time Format In Forum',
                $acp->frm->frmText('SET_TIME_FORMAT', SET_TIME_FORMAT, 30),
                '* Refer to <a href="http://www.php.net/manual/en/function.date.php" target="_blank">PHP Manual</a> For Definition');


  $acp->newTbl('Redirect Page', 'redirect');
  $acp->newRow('Enable Redirect Page',
                $acp->frm->frmAnOp('SET_DISPLAY_REDIRECT_PAGE', SET_DISPLAY_REDIRECT_PAGE));

  $acp->newRow('Forward Time',
                $acp->frm->frmText('SET_FORWARD_TIME', SET_FORWARD_TIME, 25),
                '* Time to wait for auto-redirection');

  /**
   * online list
   */
  $acp->newTbl('Online List', 'onlineList');
  $acp->newRow('Display Online User List',
                $acp->frm->frmAnOp('SET_DISPLAY_INDEX_ONLINELIST', SET_DISPLAY_INDEX_ONLINELIST),
                '( In Forum Index Page )');
  $acp->newRow('Display Online User List',
                $acp->frm->frmAnOp('SET_DISPLAY_FORUM_ONLINELIST', SET_DISPLAY_FORUM_ONLINELIST),
                '( In Topic List Page )');
  $acp->newRow('Number of users each line', $acp->frm->frmText('SET_ONLINE_PER_LINE', SET_ONLINE_PER_LINE, 25));

  $acp->newTbl('Anti-Spam Code', 'ascode');
  $acp->newRow('Enable Anti-Spam Code in Register Page',
                $acp->frm->frmAnOp('SET_REG_ANTI_SPAM', SET_REG_ANTI_SPAM));
  $acp->newRow('Enable Anti-Spam Code in Log In Page',
                $acp->frm->frmAnOp('SET_LOGIN_ANTI_SPAM', SET_LOGIN_ANTI_SPAM));

} else {

  $m = new modify_setting( DATA_PATH.'/settings/config.global.php' );
  
  $m->set('SET_GUEST_NAME', $_POST['SET_GUEST_NAME']);
  $m->set('SET_DEFAULT_READMODE', ($_POST['SET_DEFAULT_READMODE']==1 ? 'flat' : 'threaded'));
  $m->set('SET_ALLOW_UPLOAD_TYPE', $_POST['SET_ALLOW_UPLOAD_TYPE']);
  $m->set('SET_DIRECT_ATT', intval($_POST['SET_DIRECT_ATT']), 0);
  $m->set('SET_DIRECT_ATT_DISALLOW_TYPE', $_POST['SET_DIRECT_ATT_DISALLOW_TYPE']);

  $m->set('SET_ALLOW_GUEST', intval($_POST['SET_ALLOW_GUEST']), 0);
  $m->set('SET_ALLOW_GUEST_VIEW_USER', intval($_POST['SET_ALLOW_GUEST_VIEW_USER']), 0);
  $m->set('SET_ALLOW_GUEST_VIEW_USER_LIST', intval($_POST['SET_ALLOW_GUEST_VIEW_USER_LIST']), 0);

  $m->set('SET_FLOOD_CONTROL_TIME', intval($_POST['SET_FLOOD_CONTROL_TIME']), 0);
  $m->set('SET_MAX_POST_LENGTH', intval($_POST['SET_MAX_POST_LENGTH']), 0);
  $m->set('SET_POST_REVIEW_NUMBER', intval($_POST['SET_POST_REVIEW_NUMBER']), 0);
  $m->set('SET_ALLOW_SMILE', intval($_POST['SET_ALLOW_SMILE']), 0);
  $m->set('SET_ALLOW_FLASH', intval($_POST['SET_ALLOW_FLASH']), 0);

  $m->set('SET_ATTACH_DL_PAY_RATING', intval($_POST['SET_ATTACH_DL_PAY_RATING']), 0);
  $m->set('SET_ATTACH_MAX_REQ_RATING', intval($_POST['SET_ATTACH_MAX_REQ_RATING']), 0);
  $m->set('SET_ALLOW_UPLOAD_SIZE', intval($_POST['SET_ALLOW_UPLOAD_SIZE'])*1024, 0);

  $m->set('SET_ALLOW_CETAG_SIGN', intval($_POST['SET_ALLOW_CETAG_SIGN']), 0);
  $m->set('SET_ALLOW_SMILE_SIGN', intval($_POST['SET_ALLOW_SMILE_SIGN']), 0);
  $m->set('SET_ALLOW_HTML_SIGN', intval($_POST['SET_ALLOW_HTML_SIGN']), 0);
  $m->set('SET_ALLOW_FLASH_SIGN', intval($_POST['SET_ALLOW_FLASH_SIGN']), 0);
  $m->set('SET_ALLOW_IMG_SIGN', intval($_POST['SET_ALLOW_IMG_SIGN']), 0);
  $m->set('SET_ALLOW_IMG_SIGN_MAX', intval($_POST['SET_ALLOW_IMG_SIGN_MAX']), 0);

  $m->set('SET_PM_AUTO_CHECK', intval($_POST['SET_PM_AUTO_CHECK'])-1, 0);
  $m->set('SET_PM_MAX_RECIEVERS', intval($_POST['SET_PM_MAX_RECIEVERS']), 0);
  $m->set('SET_PM_MAX_LENGTH', intval($_POST['SET_PM_MAX_LENGTH']), 0);
  $m->set('SET_PM_MAX_PMS', intval($_POST['SET_PM_MAX_PMS']), 0);

  $m->set('SET_ALLOW_USER_UPLOAD_AVATAR', intval($_POST['SET_ALLOW_USER_UPLOAD_AVATAR']), 0);
  $m->set('SET_MIN_RATING_TO_UPLOAD_AVATAR', intval($_POST['SET_MIN_RATING_TO_UPLOAD_AVATAR']), 0);
  $m->set('SET_MIN_POST_TO_UPLOAD_AVATAR', intval($_POST['SET_MIN_POST_TO_UPLOAD_AVATAR']), 0);
  $m->set('SET_MAX_AVATAR_FILESIZE', intval($_POST['SET_MAX_AVATAR_FILESIZE'])*1024, 0);
  $m->set('SET_MAX_AVATAR_HEIGHT', intval($_POST['SET_MAX_AVATAR_HEIGHT']), 0);
  $m->set('SET_MAX_AVATAR_WIDTH', intval($_POST['SET_MAX_AVATAR_WIDTH']), 0);

  $m->set('SET_ALLOW_TITLE_POSTS', intval($_POST['SET_ALLOW_TITLE_POSTS']), 0);
  $m->set('SET_ALLOW_TITLE_RATING', intval($_POST['SET_ALLOW_TITLE_RATING']), 0);

  $m->set('SET_TOPIC_PP', intval($_POST['SET_TOPIC_PP']), 0);
  $m->set('SET_POST_PP', intval($_POST['SET_POST_PP']), 0);
  $m->set('SET_USER_PP', intval($_POST['SET_USER_PP']), 0);
  $m->set('SET_HOT_TOPIC', intval($_POST['SET_HOT_TOPIC']), 0);
  $m->set('SET_FAST_REPLY', intval($_POST['SET_FAST_REPLY']), 0);
  
  $m->set('SET_ONLINE_DURATION', intval($_POST['SET_ONLINE_DURATION'])*60, 0);

  $m->set('SET_MAX_FAVORITES', intval($_POST['SET_MAX_FAVORITES']), 0);
  $m->set('SET_ENABLE_EMAIL', intval($_POST['SET_ENABLE_EMAIL']), 0);
  $m->set('SET_DELAY_SENDMAIL', intval($_POST['SET_DELAY_SENDMAIL']), 0);

  $m->set('SET_ADMIN_EMAIL', $_POST['SET_ADMIN_EMAIL']);
  $m->set('SET_EMAIL_SENDER', $_POST['SET_EMAIL_SENDER']);
  $m->set('SET_BOARD_EMAIL', $_POST['SET_BOARD_EMAIL']);

  $m->set('SET_ENABLE_REG', intval($_POST['SET_ENABLE_REG']), 0);
  $m->set('SET_REG_METHOD', intval($_POST['SET_REG_METHOD'])-1, 0);
  $m->set('SET_ALLOW_DUPE_EMAIL', intval($_POST['SET_ALLOW_DUPE_EMAIL']), 0);
  $m->set('SET_ALLOW_MULTI_REG', intval($_POST['SET_ALLOW_MULTI_REG']), 0);


  $remove_timestamp = getTs($_POST['current_time']);
  if( -1 == $remove_timestamp ) {
    acp_exception('Please input a valid time');
  }

  /**
   * caculate time zone offset
   */
  $server_timestamp = time();
  $server_timestamp = $server_timestamp - $server_timestamp%60;
  $timezoneoffset   = $remove_timestamp - $server_timestamp;

  $m->set('SET_TIME_ZONE_OFFSET', $timezoneoffset, 0);
  $m->set('SET_DATE_FORMAT', $_POST['SET_DATE_FORMAT']);
  $m->set('SET_TIME_FORMAT', $_POST['SET_TIME_FORMAT']);

  $m->set('SET_DISPLAY_REDIRECT_PAGE', intval($_POST['SET_DISPLAY_REDIRECT_PAGE']), 0);
  $m->set('SET_FORWARD_TIME', intval($_POST['SET_FORWARD_TIME']), 0);

  $m->set('SET_DISPLAY_INDEX_ONLINELIST', intval($_POST['SET_DISPLAY_INDEX_ONLINELIST']), 0);
  $m->set('SET_DISPLAY_FORUM_ONLINELIST', intval($_POST['SET_DISPLAY_FORUM_ONLINELIST']), 0);
  $m->set('SET_ONLINE_PER_LINE', intval($_POST['SET_ONLINE_PER_LINE']), 0);

  $m->set('SET_REG_ANTI_SPAM', intval($_POST['SET_REG_ANTI_SPAM']), 0);
  $m->set('SET_LOGIN_ANTI_SPAM', intval($_POST['SET_LOGIN_ANTI_SPAM']), 0);

  $m->save();
  acp_success_redirect('You have updated the settings successfully', 'prog=global::mis');

}
