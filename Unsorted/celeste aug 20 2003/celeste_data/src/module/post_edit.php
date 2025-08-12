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

if (!is_object($forum) || !is_object($topic) || !is_object($post)) {
  celeste_exception_handle('invalid_id');
}

//if (!$forum->permission['editpost'] && !$celeste->usergroup['editpost'])
if (!$forum->permission['editpost'])
{
  if($usergroupid==5) 
  {
    import('login');
    celeste_login('prog=post::edit&pid='.$_GET['pid'].'&postMode='.$_GET['postMode']);
  }
  if(!$celeste->isSU() && $post->getProperty('userid')!=$userid) celeste_exception_handle('permission_denied');
}


if (isset($_POST['title']))
{
  // submit here
  $_POST['title'] =& nl2br( _removeHTML( _replaceCensored(slashesEncode( $_POST['title']))));
  //$_POST['content'] =& nl2br( _removeHTML( slashesEncode( ($_POST['content']))));
  //$_POST['content'] =& nl2br( _removeHTML( slashesDecode( ($_POST['content']))));
  $_POST['content'] =& _removeHTML( slashesDecode( ($_POST['content'])));
  
  if (empty($_POST['title'])) celeste_exception_handle('invalid_title');
  if (empty($_POST['content'])) celeste_exception_handle('invalid_content');
  if (strlen($_POST['content']) > SET_MAX_POST_LENGTH) celeste_exception_handle('content_too_long');
  if ($_POST['requirerating'] && ($usergroupid==5 || $_POST['requirerating'] > $user->getProperty('totalrating') || $_POST['attachrating'] > SET_ATTACH_MAX_REQ_RATING)) 
    celeste_exception_handle('invalid_rating');
    
  $attachmentid=0;
  if (!empty($_FILES['attachment']['name']) 
     && $forum->permission['allowupload']==1) {
    if ($_POST['attachrating'] && ($usergroupid==5 || $_POST['attachrating'] > $user->getProperty('totalrating'))) 
    celeste_exception_handle('invalid_attach_rating');
   	import('attachment');
   	if ($post->getProperty('attachmentid')>0) {
      $att = new attach($post->getProperty('attachmentid'));
      $att->remove_direct_output();
   	  $att->destroy();
   	
      $att->attach('attachment');
      if (!empty($_POST['attachrating']) && isInt($_POST['attachrating']) && !preg_match('/^image/', $_FILES['attachment']['type'])) $att->setProperty('rating', $_POST['attachrating']);
      else $att->setProperty('rating', 0);
      $att->setProperty('counter', 0);
      $att->addfile();
      
      if(attach::useDirectOutput())
        $att->setProperty('direct_output', 1);
      // save att
      $att->flushProperty();
      // direct output
      if(attach::useDirectOutput()) {
        $att->direct_output();
      }
      unset($att);
   	} else {
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
    }
  }
  
  import('post');
  import('string');
  
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
  

  $post->setProperty('iconid', $_POST['iconid']);
  $post->setProperty('parentid', $_POST['parentid']);

  $post->setProperty('edituser', ($usergroupid==5 ? SET_GUEST_NAME : $user->getProperty('username')));
  $post->setProperty('edituserid', $userid);
  $post->setProperty('edittime', $celeste->timestamp);

  $post->setProperty('attachmentid', (!empty($post->properties['attachmentid']) ? $post->properties['attachmentid']  : $attachmentid));
  $post->setProperty('title', $_POST['title']);
  $post->setProperty('content', $_POST['content']);

  //$pt->setProperty('cetag', ($forum->permission['allowcetag'] && $celeste->usergroup['allowcetag'] && !empty($_POST['cetag']) ? 1 : 0));
  $post->setProperty('cetag', ($forum->permission['allowcetag'] && !empty($_POST['cetag']) ? 1 : 0));
  //$pt->setProperty('smiles', ((SET_ALLOW_SMILE && $forum->permission['allowsmiles'] && $celeste->usergroup['allowsmiles'] && !empty($_POST['smiles'])) ? 1 : 0));
  $post->setProperty('smiles', ((SET_ALLOW_SMILE && $forum->permission['allowsmiles'] && !empty($_POST['smiles'])) ? 1 : 0));

  $post->setProperty('showsign', (empty($_POST['showsign']) ? 0 : 1));
  $post->setProperty('emailnotice', (empty($_POST['emailnotice']) ? 0 : 1));
  if (isset($_POST['requirerating']) && isInt($_POST['requirerating']) && $celeste->login && $_POST['requirerating']<=$user->getProperty('totalrating')) $post->setProperty('requirerating', $_POST['requirerating']);
  
  $post->flushProperty();

  /**
   * update topic title
   */
  if($pid == $DB->result("SELECT postid FROM celeste_post WHERE topicid = '".$post->getProperty('topicid')."' ORDER BY postid ASC")) {
    $topic->setProperty('topic', $_POST['title']);
    $topic->flushProperty();
  }

  celeste_success_redirect('post_saved', 'prog=topic::'.$t->varvals['_readMode'].'&page=end&tid='.$topicid);
} else {

  import('stringreverse');

  //$t->preload('header');
  //$t->preload('footer');
  $t->preload('post_form');
  $t->preload('icon_list');
  
  if (empty($_GET['postMode']) && $celeste->login) $_GET['postMode'] = ( $user->getProperty('postmode') ? 'adv' : 'sim' );
  
  if (isset($_GET['postMode']) && $_GET['postMode']=='adv')
  {
  	$t->preload('post_adv_script');
  	$t->preload('post_adv_showsmiles');
  	$t->preload('post_adv_toolsbar');
  	$t->preload('post_adv_font');
  }
  if (SET_POST_REVIEW_NUMBER && ($userid==-1 || $user->getProperty('showpostonreply'))) $t->preload('post_review');
  
  if ($forum->permission['allowupload']==1)
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
    $up->set('maxrating', min(SET_ATTACH_MAX_REQ_RATING, $user->getProperty('totalrating')) );
  	
    $root->set('upload_form', $up->parse()); 
  }
  $header = $t->get('header');

  // get nav
  $header->set('nav', getCache('tr_F'.$forumid.'_'.$forum->getProperty('path')).'&#187; '.SET_EDIT_POST_TITLE);
  $header->set('pagetitle', SET_EDIT_POST_TITLE);

  $root->set('thisprog' ,$thisprog);
  $root->set('forumid', $forumid);
  
  if (isset($_GET['postMode']) && $_GET['postMode']=='adv') {
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
  
  $root->set('pagetitle', SET_EDIT_POST_TITLE);

  // set rules
  // set rules
  //$root->set('cetaginfo', ($celeste->usergroup['allowcetag'] && $forum->permission['allowcetag'] ?  SET_ON : SET_OFF));
  $root->set('cetaginfo', ($forum->permission['allowcetag'] ?  SET_ON : SET_OFF));
  //$root->set('imageinfo', ($celeste->usergroup['allowimage'] && $forum->permission['allowimage'] ?  SET_ON : SET_OFF));
  $root->set('imageinfo', ($forum->permission['allowimage'] ?  SET_ON : SET_OFF));
  //$root->set('htmlinfo', ($celeste->usergroup['allowhtml'] && $forum->permission['allowhtml'] ?  SET_ON : SET_OFF));
  $root->set('htmlinfo', ($forum->permission['allowhtml'] ?  SET_ON : SET_OFF));
  //$root->set('smilesinfo', ($celeste->usergroup['allowsmiles'] && $forum->permission['allowsmiles'] ?  SET_ON : SET_OFF));
  $root->set('smilesinfo', ($forum->permission['allowsmiles'] ?  SET_ON : SET_OFF));

  $root->set('emailnoticecheck', (empty($post->properties['emailnotice']) ? '' : 'checked' ));
  $root->set('cetagcheck', (empty($post->properties['cetag']) ? '' : 'checked'));
  $root->set('smilescheck', (empty($post->properties['smiles']) ? '' : 'checked'));
  $root->set('showsigncheck', (empty($post->properties['showsign']) ? '' : 'checked'));

  // set post options
  if ($userid>0)
  {
    if($forum->permission['allowcetag'])
      $root->set('autoparseurlcheck', (empty($user->properties['parseurl']) ? '' : 'checked'));
    else
      $root->set('autoparseurlcheck', 'disabled');

    if($forum->permission['allowimage'])
      $root->set('autoparseimgcheck', (empty($user->properties['parseimg']) ? '' : 'checked'));
    else
      $root->set('autoparseimgcheck', 'disabled');
  }
  else
  {
    $root->set('autoparseurlcheck', ($forum->permission['allowcetag'] ? 'checked' : 'disabled'));
    $root->set('autoparseimgcheck', ($forum->permission['allowimage'] ? 'checked' : 'disabled'));
  }  	

  if (!$post->getProperty('requirerating') || ($celeste->login && $user->getProperty('totalrating')>=$post->getProperty('requirerating')) || $celeste->isSU()) {
    $contemp =& str_replace('<br />', '', $post->getProperty('content'));

    /***************
     * reverse string
     */
    $conReverse = new celesteStringReverse(0, 1, 1);
    $conReverse->setString($contemp);
    $contemp =& $conReverse->parse();
      
  } else {
    $contemp = ' Hidden Post ';
  }

  $root->set('requirerating', $post->getProperty('requirerating'));
  $root->set('title', $post->getProperty('title'));
  $root->set('content', $contemp);
  $root->set('parentid', $post->properties['parentid']);
  

}