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

  $acp->newFrm('Template Editor');

  /**
   * Main
   */
  $acp->newTbl('Main Function Pages', 'main');
  addBlock('Global Elements', array('header','footer','login_box','cp_head'));

  addBlock('Forum Index', array('index', 'indi_cate', 'indi_forum', 'last_topic', 'no_topic', 'forum_header',
    'login_welcome_text', 'login_user_status', 'unlogin_welcome_text',  'unlogin_user_status',
    'online_group1', 'online_group2', 'online_group3', 'online_othergroup',  'online_linebreak', 'onlinelist'));
  addBlock('Forum Display', array('indi_cate', 'indi_forum', 'last_topic', 'no_topic', 'forum_header','display_announcement','indi_topic','topic_header', 'online_group1','online_group2','online_group3','online_othergroup','online_linebreak', 'forum_onlinelist','topiclist', 'header', 'footer', 'only_one_page', 'page', 'multi_page', 'current_page', 'topic_search_forum'));

  addBlock('Read Topic - Flat Mode', array('topic_flat','topic_flat_post', 'rate_form', 'rate_disable', 'edit_status', 'attachment_other', 'attachment_image',  'page', 'multi_page', 'only_one_page', 'current_page','fast_reply','poll_timeout', 'poll_locked', 'poll_not_login', 'poll_available', 'option_multichoice','option_simplechoice', 'display_poll', 'poll_optionresult', 'poll_voted'));

  addBlock('Read Topic - Threaded Mode', array('topic_threaded','thread_post','topic_flat_post', 'rate_form', 'rate_disable', 'edit_status', 'attachment_other', 'attachment_image','fast_reply', 'poll_timeout', 'poll_locked', 'poll_not_login', 'poll_available', 'option_multichoice','option_simplechoice', 'display_poll', 'poll_optionresult', 'poll_voted'));

  addBlock('Read Topic - Print Mode', array('topic_print', 'post_review', 'edit_status', 'attachment_other', 'attachment_image'));

  addBlock('Post New Topic', array('post_form','icon_list','post_adv_script','post_adv_showsmiles','post_adv_toolsbar','post_adv_font','upload_form'));

  addBlock('Reply Topic', array('header','footer','post_form','icon_list','post_adv_script','post_adv_showsmiles','post_adv_toolsbar','post_adv_font','post_review','upload_form'));

  addBlock('Post Preview', array('topic_preview'));

  addBlock('Create New Poll', array('post_form','icon_list','poll_form','post_adv_script','post_adv_showsmiles','post_adv_toolsbar','post_adv_font','upload_form'));
  addBlock('Email Topic', array('topic_email'));

  addBlock('Edit Post', array('post_form','icon_list','post_adv_script','post_adv_showsmiles','post_adv_toolsbar','post_adv_font','post_review','upload_form'));
  addBlock('Delete Post', array('post_del'));
  addBlock('Download Attachment', array('attach_dl'));
  addBlock('Search', array('topic_search','topic_search_forum','topic_search_result','indi_topic_wf','indi_post_wf','search_result_topic_list','search_result_post_list','page','multi_page','current_page','only_one_page'));
  addBlock('Show New Post', array('topic_search_result','indi_post_wf','search_result_post_list','page','multi_page','current_page','only_one_page'));

  addBlock('Announcement', array('forum_announcement', 'forum_announcement_item'));
  addBlock('Help', array('showsmiles', 'indi_smile'));

  $acp->newTbl('User Function Pages', 'user');
  addBlock('Log In / Log Out', array('login', 'logout'));
  addBlock('Register', array('register_forum_rules','forum_rules', 'register_3',  'register','register_password_input'));
  addBlock('Request Password', array('request_password'));
  addBlock('Activate Account', array('activate_account'));
  addBlock('View User Profile', array('user_view'));
  addBlock('Send Email to Forum User', array('sendmail'));
  addBlock('User List', array('user_list','indi_user','only_one_page','current_page','page','multi_page'));
  

  $acp->newTbl('User Control Panel Pages', 'usercp');
  addBlock('User Control Panel', array('user_cp_menu','user_cp'));

  addBlock('Profile Index', array('user_cp_profile'));
  addBlock('Edit Profile', array('user_cp_edit_profile','user_cp_edit_profile_title'));
  addBlock('Edit Avatar', array('user_cp_avatar','user_cp_avatar_pic','user_cp_avatar_pic_nl','only_one_page','page','multi_page','current_page'));
  addBlock('Edit Account Information', array('user_cp_account'));
  addBlock('My Favourites Page', array('user_cp_favorites','favorites_topic'));
  
  addBlock('Note Pad', array('user_cp_note'));
  addBlock('Preference', array('user_cp_preference'));

  addBlock('Private Messages - Edit', array('pm_mass_sent','pm_edit','user_cp_menu','pm_menu'));
  addBlock('Private Messages - In Box', array('pmlist_inbox','indi_pm_inbox','indi_pm_inbox','pmlist_inbox','pm_menu','pm_stat','pm_full_alert'));
  addBlock('Private Messages - Out Box', array('pmlist_outbox','indi_pm_outbox','indi_pm_outbox','pmlist_outbox','pm_menu','pm_stat','pm_full_alert'));
  addBlock('Private Messages - Read', array('pm_read'));
  addBlock('Private Messages - Contact List', array('contact_list','indi_contact_buddy','user_cp_menu','pm_menu'));


  $acp->newTbl('Error Pages', 'err');
  addBlock('Error Page', array('exception'));
  $exc = $DB->query('select name from '.SET_TEMPLATE_TABLE.' where name like "exception_%"');
  $errs = array();
  while($exc->next_record())  $errs[] = $exc->get('name');
  addBlock('Error Messages', $errs);

  $acp->newTbl('Success Pages', 'suc');
  addBlock('Success Page', array('success'));
  $suc = $DB->query('select name from '.SET_TEMPLATE_TABLE.' where name like "success_%"');
  $sucs = array();
  while($suc->next_record())  $sucs[] = $suc->get('name');
  addBlock('success Messages', $sucs);

  $emailtemplates = array();
  $emaildir = dir(DATA_PATH.'/email');$emaildir->read();$emaildir->read();
  while($template = $emaildir->read()) {
    $emailtemplates[] = 'm:'.str_replace('.tpl', '', $template);
  }
  $acp->newTbl('E-Mail Templates', 'email');
  addBlock('E-Mail Templates', $emailtemplates);

  $acp->newTbl('Other Elements', 'oth');
  $oth = $DB->query('select name from '.SET_TEMPLATE_TABLE.' where templateid>180');
  $oths = array();
  while($oth->next_record())  $oths[] = $oth->get('name');
  addBlock('Other Template Elements', $oths);


function addLine( $templatename ) {

//global $acp;

return '<br>&nbsp;&nbsp;&#187;<a href="'.$_SERVER['PHP_SELF'].'?prog=template::edit&tm='.$templatename.'">'.$templatename.
  '</a>';

}

function addBlock( $title, $array ) {
global $acp;
static $id;
  if (!$id) $id=1;
  else $id++;
  $re = '<div id=M'.$id.' style="DISPLAY: none">  ';
  foreach($array as $tmp ) $re.=addLine($tmp);
  $re.='</div>';
 $acp->newRow('<a href="#'.$id.'" style="cursor:hand" onClick="turnit(M'.$id.')">'.$title.'</a>', $re);
}

?>
<script language="javascript"> 
<!-- 
function turnit (dvn){ 
if (dvn.style.display=="none") { 
  dvn.style.display=""; 
}else { 
if (dvn.style.display=="") { 
  dvn.style.display="none"; 
   } 
} 

} 
//--> 
</script> 