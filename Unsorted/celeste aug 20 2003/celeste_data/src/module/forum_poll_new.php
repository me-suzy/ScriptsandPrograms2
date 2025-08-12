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

if (!is_object($forum)) {
  celeste_exception_handle('invalid_id');
}

//if (!$celeste->usergroup['allowcreatepoll'] || !$forum->permission['allowcreatepoll'])
if (!$forum->permission['allowcreatepoll'])
{
  if($usergroupid==5)
  {
    import('login');
    celeste_login('prog=poll::new&fid='.$forumid.'&postMode='.(isset($_GET['postMode']) ? $_GET['postMode'] : ''));
  }
  if(!$celeste->isSU()) celeste_exception_handle('permission_denied');
}

if (isset($_POST['title']))
{
  
  if (SET_FLOOD_CONTROL_TIME && $celeste->login &&
     !$celeste->isSU() && 
     !$forum->permission['edittopic'] && 
     SET_FLOOD_CONTROL_TIME>($celeste->timestamp - $user->getProperty('lastpost')))
    celeste_exception_handle('flood_prohibited');
  // submit here
  if (isset($_POST['title'])) $_POST['title'] =& trim($_POST['title']);
  if (isset($_POST['content'])) $_POST['content'] =& trim($_POST['content']);
  if (isset($_POST['options'])) $_POST['options'] =& trim($_POST['options']);
  
  if (empty($_POST['title'])) celeste_exception_handle('invalid_title');
  if (empty($_POST['content'])) celeste_exception_handle('invalid_content');
  if (empty($_POST['options'])) celeste_exception_handle('invalid_poll_options');
  $_POST['title'] =& nl2br( _removeHTML( _replaceCensored(slashesEncode( $_POST['title']))));
  //$_POST['content'] =& nl2br( _removeHTML( slashesEncode( $_POST['content'])));
  //$_POST['content'] =& nl2br( _removeHTML( slashesDecode( $_POST['content'])));
  $_POST['content'] =& _removeHTML( slashesDecode( $_POST['content']));
  $_POST['options'] =& _removeHTML( slashesEncode( $_POST['options']));
  
  $optionlist = explode("\n", $_POST['options']);
  $optioncount = count($optionlist);
  if($optioncount<2 || $optioncount>SET_MAX_POLL_OPTIONS) celeste_exception_handle('poll_options_error');


  if (strlen($_POST['content']) > SET_MAX_POST_LENGTH) celeste_exception_handle('content_too_long');
  if (!empty($_POST['requirerating']) && ($usergroupid==5 || $_POST['requirerating'] > $user->getProperty('totalrating'))) 
    celeste_exception_handle('invalid_rating');

  //if (!empty($_FILES['attachment']['name']) && $celeste->usergroup['allowupload']==1 && $forum->permission['allowupload']==1)
  if (!empty($_FILES['attachment']['name']) && $forum->permission['allowupload']==1)
  {
    if ($_POST['attachrating'] && ($usergroupid==5 || $_POST['attachrating'] > $user->getProperty('totalrating') || $_POST['attachrating'] > SET_ATTACH_MAX_REQ_RATING)) 
      celeste_exception_handle('invalid_attach_rating');
    import('attachment');
    $att = new attach('attachment');
    if (!empty($_POST['attachrating']) && isInt($_POST['attachrating']) && !preg_match('/^image/', $_FILES['attachment']['type'])) $att->setProperty('rating', $_POST['attachrating']);

    if(attach::useDirectOutput())
      $att->setProperty('direct_output', 1);
    // save att
    $attachmentid = $att->store();
    // direct output
    if(attach::useDirectOutput()) {
      $att->direct_output();
    }
    unset($att);
  } else {
    $attachmentid = 0;
  }
  //print_r( $_FILES );
  
  import('topic');
  import('post');
  import('string');

  $timeout =& mktime(0,0,0, $_POST['timeout_month'],$_POST['timeout_day'],$_POST['timeout_year']);
  if ($timeout<$celeste->timestamp) $timeout = $celeste->timestamp + 2592000; //60*60*24*30;
  $DB->update(sprintf(
  "insert INTO celeste_poll (pollid,options,voters,votecount,multichoice,timeout) values('%s','%s',0,0,'%s','%s')",
  $DB->nextid('poll'), $optioncount, (empty($_POST['multichoice']) ? 0 : 1), $timeout));

  $pollid =& $DB->lastid();
  
  $query = 'insert INTO celeste_vote (optionid,pollid,optiontitle,votecount) values';
  for($i = 0; $i<$optioncount; $i++) {
    if($optiontitle = trim($optionlist[$i])) {
      $query.='(\''.$DB->nextid('vote').'\','.$pollid.',\''.$optiontitle.'\',0),';
    }
  }
  $DB->update(substr($query, 0, -1));
  $line = '<?exit();?'.'>'."\n";
  writetofile(DATA_PATH.'/poll/'.$pollid.'.poll.php', $line);
  
  //$sf = new celesteStringFactory(0, ( !empty($_POST['autoParseURL']) ? 2:1), ( !empty($_POST['autoParseIMG']) ?2:1), 0, 0, 0, 0);
  $sf = new celesteStringFactory(
    // ce tag
    //($forum->permission['allowcetag'] && $celeste->usergroup['allowcetag'] && !empty($_POST['cetag']) ? 1 : 0),
    ($forum->permission['allowcetag'] && !empty($_POST['cetag']) ? 1 : 0),
    // auto parse url
    ( !empty($_POST['autoParseURL']) ? 1:0), ( !empty($_POST['autoParseIMG']) ?2:1),
    // fla code
    SET_ALLOW_FLASH,
    // html
    //$celeste->usergroup['allowhtml'] && $forum->permission['allowhtml'],
    $forum->permission['allowhtml'],
    // img
    -1,
    // smile
    //((SET_ALLOW_SMILE && $forum->permission['allowsmiles'] && $celeste->usergroup['allowsmiles'] && !empty($_POST['smiles'])) ? 1 : 0)
    ((SET_ALLOW_SMILE && $forum->permission['allowsmiles'] && !empty($_POST['smiles'])) ? 1 : 0)
  );
  $sf->setString($_POST['content']);
  //$_POST['content'] =& $sf->parse();
  //$_POST['content'] =& slashesEncode($sf->parse(), 1);
  $_POST['content'] =& nl2br(slashesEncode($sf->parse(), 1));
  unset($sf);
  
  $tp = new topic();
  $tp->setProperty('poster', ($usergroupid==5 ? SET_GUEST_NAME : $user->getProperty('username')));
  $tp->setProperty('posterid', $userid);
  $tp->setProperty('topic', $_POST['title']);
  $tp->setProperty('forumid', $forum->forumid);
  $tp->setProperty('pollid', $pollid);
  $tid = $tp->store();
  $topic =& $tp;
  
  $pt = new post();
  $pt->setProperty('topicid', $tid);

  $pt->setProperty('iconid', (isset($_POST['iconid']) ? $_POST['iconid'] : 1));
  $pt->setProperty('parentid', ( isset($_POST['parentid']) ? $_POST['parentid'] : 0));
  $pt->setProperty('username', ($usergroupid==5 ? SET_GUEST_NAME : $user->getProperty('username')));
  
  $pt->setProperty('userid', $userid);
  $pt->setProperty('title', $_POST['title']);
  $pt->setProperty('content', $_POST['content']);
  
  //$pt->setProperty('cetag', ($forum->permission['allowcetag'] && $celeste->usergroup['allowcetag'] && !empty($_POST['cetag']) ? 1 : 0));
  $pt->setProperty('cetag', ($forum->permission['allowcetag'] && !empty($_POST['cetag']) ? 1 : 0));
  //$pt->setProperty('smiles', ((SET_ALLOW_SMILE && $forum->permission['allowsmiles'] && $celeste->usergroup['allowsmiles'] && !empty($_POST['smiles'])) ? 1 : 0));
  $pt->setProperty('smiles', ((SET_ALLOW_SMILE && $forum->permission['allowsmiles'] && !empty($_POST['smiles'])) ? 1 : 0));
  $pt->setProperty('showsign', (empty($_POST['showsign']) ? 0 : 1));
  $pt->setProperty('emailnotice', (empty($_POST['emailnotice']) ? 0 : 1));
  if (isset($_POST['requirerating']) && isInt($_POST['requirerating']) && $celeste->login && $_POST['requirerating']<=$user->getProperty('totalrating')) $pt->setProperty('requirerating', $_POST['requirerating']);
  if ($attachmentid) $pt->setProperty('attachmentid', $attachmentid);
  $pid = $pt->store(1);
  
  unset($tp);
  unset($pt);
  if (is_object($user)) $user->updateLastPost($pid);
  celeste_success_redirect('topic_created', 'prog=topic::'.$t->varvals['_readMode'].'&tid='.$tid);
} else {
  // new topic form

  //$t->preload('header');
  //$t->preload('footer');
  $t->preload('post_form');
  $t->preload('icon_list');
  $t->preload('poll_form');
  if (empty($_GET['postMode']) && $celeste->login) $_GET['postMode'] = ( $user->getProperty('postmode') ? 'adv' : 'sim' );
  
  if (isset($_GET['postMode']) && $_GET['postMode']=='adv')
  {
    $t->preload('post_adv_script');
    $t->preload('post_adv_showsmiles');
    $t->preload('post_adv_toolsbar');
    $t->preload('post_adv_font');
  }
  //if ($forum->permission['allowupload'] && $celeste->usergroup['allowupload'])
  if ($forum->permission['allowupload'])
  {
    $t->preload('upload_form');
    $up = true;
  } else $up = false;
  $t->retrieve();
  $root =& $t->get('post_form');
  $t->setRoot($root);
  $root->set('icon_list', $t->getString('icon_list'));
  
  if ($up) {
    $up =& $t->get('upload_form');
    $up->set('size', SET_ALLOW_UPLOAD_SIZE);
    $up->set('type', SET_ALLOW_UPLOAD_TYPE);
    $up->set('maxrating', min(SET_ATTACH_MAX_REQ_RATING, is_object($user) ? $user->getProperty('totalrating') : 0) );
  	
    $root->set('upload_form', $up->parse()); 
  }
  $header = $t->get('header');

  // get nav
  $path =& getCache('tr_F'.$forumid.'_'.$forum->getProperty('path'));

  $header->set('nav', $path.'&#187; '.SET_NEW_POLL_TITLE);
  $header->set('pagetitle', SET_NEW_POLL_TITLE);

  $root->set('thisprog' ,$thisprog);
  $root->set('forumid', $forumid);
  
  if (isset($_GET['postMode']) && $_GET['postMode']=='adv')
  {
    $root->set('post_adv_script', $t->getString('post_adv_script'));
    $root->set('post_adv_toolsbar', $t->getString('post_adv_toolsbar'));
    $root->set('post_adv_font', $t->getString('post_adv_font'));
    $root->set('opp_mode_name', 'Simple Mode');
    $root->set('opp_mode', 'sim');
    
    include_once(DATA_PATH.'/settings/smile.inc.php');

    $c = count($smileTags);
    $smiles=& $t->get('post_adv_showsmiles');
    $j=0;
    for ($i=rand(0, $c); $j<min(8, $c); $j++,$i++) {
      if ($i==$c) $i=0;
      $smiles->set('image', $smileImgs[$i]);
      $smiles->set('code', $smileTags[$i]);
      $smiles->parse(true);
    }
    $root->set('post_adv_showsmiles', $smiles->getContent());
  } else {
    $root->set('opp_mode_name', 'Advanced Mode');
    $root->set('opp_mode', 'adv');
  }
  
  $root->set('pagetitle', SET_NEW_POLL_TITLE);

  // set rules
  //$root->set('cetaginfo', ($celeste->usergroup['allowcetag'] && $forum->permission['allowcetag'] ?  SET_ON : SET_OFF));
  $root->set('cetaginfo', ($forum->permission['allowcetag'] ?  SET_ON : SET_OFF));
  //$root->set('imageinfo', ($celeste->usergroup['allowimage'] && $forum->permission['allowimage'] ?  SET_ON : SET_OFF));
  $root->set('imageinfo', ($forum->permission['allowimage'] ?  SET_ON : SET_OFF));
  //$root->set('htmlinfo', ($celeste->usergroup['allowhtml'] && $forum->permission['allowhtml'] ?  SET_ON : SET_OFF));
  $root->set('htmlinfo', ($forum->permission['allowhtml'] ?  SET_ON : SET_OFF));
  //$root->set('smilesinfo', ($celeste->usergroup['allowsmiles'] && $forum->permission['allowsmiles'] ?  SET_ON : SET_OFF));
  $root->set('smilesinfo', ($forum->permission['allowsmiles'] ?  SET_ON : SET_OFF));

  // set post options
  if ($userid>0)
  {
    $root->set('emailnoticecheck', (empty($user->properties['emailnotice']) ? '' : 'checked' ));
    $root->set('showsigncheck', (empty($user->properties['showsign']) ? '' : 'checked'));

    if($forum->permission['allowcetag'])
      $root->set('cetagcheck', (empty($user->properties['cetag']) ? '' : 'checked'));
    else
      $root->set('cetagcheck', 'disabled');

    if($forum->permission['allowcetag'])
      $root->set('autoparseurlcheck', (empty($user->properties['parseurl']) ? '' : 'checked'));
    else
      $root->set('autoparseurlcheck', 'disabled');

    if($forum->permission['allowimage'])
      $root->set('autoparseimgcheck', (empty($user->properties['parseimg']) ? '' : 'checked'));
    else
      $root->set('autoparseimgcheck', 'disabled');

    if($forum->permission['allowsmiles'])
      $root->set('smilescheck', (empty($user->properties['smiles']) ? '' : 'checked'));
    else
      $root->set('smilescheck', 'disabled');
  }
  else
  {
    $root->set('emailnoticecheck', 'disabled');
    $root->set('showsigncheck', 'disabled');
    $root->set('cetagcheck', ($forum->permission['allowcetag'] ? 'checked' : 'disabled'));
    $root->set('autoparseurlcheck', ($forum->permission['allowcetag'] ? 'checked' : 'disabled'));
    $root->set('autoparseimgcheck', ($forum->permission['allowimage'] ? 'checked' : 'disabled'));
    $root->set('smilescheck', ($forum->permission['allowsmiles'] ? 'checked' : 'disabled'));
  }  	

  $poll_form =& $t->get('poll_form');
  list($d, $m, $y) = explode(',', date("j,n,Y"));
  if ($m==12) { $y++; $m=1; }
  else $m++;

  $poll_form->set('d', $d);
  $poll_form->set('m', $m);
  $poll_form->set('y', $y);
  $poll_form->set('max_option', SET_MAX_POLL_OPTIONS);
  $root->set('poll_form', $poll_form->parse());
}


?>