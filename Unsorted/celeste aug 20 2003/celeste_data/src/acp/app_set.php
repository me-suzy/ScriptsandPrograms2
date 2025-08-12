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

$APP_TITLE_SET = array();
$APP_TITLE_SET['name'] = array('SET_INDEX_TITLE', 'SET_NEW_TOPIC_TITLE', 'SET_REPLY_TOPIC_TITLE', 'SET_LOGIN_PAGE_TITLE', 'SET_LOGOUT_PAGE_TITLE', 'SET_ATTACHMENT_DOWN_TITLE', 'SET_EDIT_POST_TITLE', 'SET_USER_VIEW_TITLE', 'SET_USER_CP_TITLE', 'SET_POST_DEL_TITLE', 'SET_SEARCH_TITLE', 'SET_REGISTER_TITLE', 'SET_REQ_PWD_TITLE', 'SET_ACTIVATE_ACCOUNT_TITLE', 'SET_EMAIL_TOPIC_TITLE', 'SET_SHOWNEW_TITLE', 'SET_NEW_POLL_TITLE', 'SET_EDIT_POLL_TITLE', 'SET_ANNOUNCEMENT_TITLE', 'SET_MEMBER_LIST_TITLE', 'SET_SENDMAIL_TITLE', 'SET_PM_INBOX_TITLE', 'SET_PM_OUTBOX_TITLE', 'SET_PM_CONTACT_LIST', 'SET_PM_IGNORE_LIST', 'SET_PM_NEW_TITLE');
$APP_TITLE_SET['title'] = array('Forum Index', 'Post New Topic', 'Reply Topic', 'Log In', 'Log Out', 'Download Attachment', 'Edit Post', 'View User Profile', 'User Control Panel', 'Delete Post', 'Search', 'Register', 'Request Password', 'Activate Account', 'Email Topic', 'Show New Post', 'Create New Poll', 'Edit Poll', 'Annoucement', 'User List', 'Send Email', 'P.M. Inbox', 'P.M. Outbox', 'P.M. Contact List', 'P.M. Ignore List', 'Send New P.M.');

$APP_TAG_SET = array();
$APP_TAG_SET['name'] = array('SET_ON', 'SET_OFF', 'SET_ONLINE', 'SET_OFFLINE', 'SET_CAN', 'SET_CANNOT', 'SET_ELITE_STRING', 'SET_USER_VIEW_NONE', 'SET_REPLY_HEADER');
$APP_TAG_SET['title'] = array('On', 'Off', 'Online', 'Offline', 'Can do a action', 'Cannot do a action', 'Prefix of an elite topic', 'Information Not Available', 'Prefix of reply title');

$APP_COLOR_SET = array();
$APP_COLOR_SET['name'] = array('SET_TABLE_WIDTH', 'SET_BG_COLOR', 'SET_BORDER_COLOR', 'SET_TOPICROW_COLOR', 'SET_CATEROW_COLOR', 'SET_MAIN_COLOR1', 'SET_MAIN_COLOR2', 'SET_INNER_COLOR', 'SET_HIGHLIGHT_COLOR', 'SET_QUOTE_BORDER_COLOR', 'SET_QUOTE_INNER_COLOR', 'SET_CODE_BORDER_COLOR', 'SET_CODE_INNER_COLOR');
$APP_COLOR_SET['title'] = array('Main Table Width', 'Background Color', 'Table Border Color', 'Topic Row Color', 'Category Row Color', 'Main Color 1', 'Main Color 2', 'Inner Background Color', 'Highlight Color', 'CeTag : Quote Box Border Color', 'Quote Box Inner Background Color', 'CeTag : Code Box Border Color', 'Code Box Inner Background Color');


if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Template Variables');
  $acp->setFrmBtn();

  /**
   * Title
   */
  $acp->newTbl('Page Titles', 'title');
  foreach($APP_TITLE_SET['name'] as $key => $const) {
    $acp->newRow($APP_TITLE_SET['title'][$key], $acp->frm->frmText($const, _removeHtml(constant($const)), 60));
  }

  /**
   * Tags
   */
  $acp->newTbl('Tags', 'tag');
  foreach($APP_TAG_SET['name'] as $key => $const) {
    $acp->newRow($APP_TAG_SET['title'][$key], $acp->frm->frmText($const, _removeHtml(constant($const)), 60));
  }
  
  /**
   * Color
   */
  $acp->newTbl('Color', 'color');
  foreach($APP_COLOR_SET['name'] as $key => $const) {
    $color = ($const != 'SET_TABLE_WIDTH' ?
              dechex(hexdec(str_replace('#', '0x', constant($const))) ^ 0xffffff) :
              '#000000');
    $acp->newRow($APP_COLOR_SET['title'][$key],
                  $acp->frm->frmText($const, constant($const), 25, 50,
                  'style="background-color:'.constant($const).'; COLOR: '.$color.'" '.
                  'onChange="this.document.acpFrm.'.$APP_COLOR_SET['name'][$key].'.style.backgroundColor=this.document.acpFrm.'.$APP_COLOR_SET['name'][$key].'.value"'));
  }
  

} else {

  $m = new modify_setting( DATA_PATH.'/settings/config.global.php' );

  foreach($APP_TITLE_SET['name'] as $key => $const) {
    $m->set($const, $_POST[$const]);
  }

  foreach($APP_TAG_SET['name'] as $key => $const) {
    $m->set($const, $_POST[$const]);
  }

  foreach($APP_COLOR_SET['name'] as $key => $const) {
    $m->set($const, $_POST[$const]);
  }

  $m->save();
  acp_success_redirect('You have updated the template variables successfully', 'prog=app::set');

}
