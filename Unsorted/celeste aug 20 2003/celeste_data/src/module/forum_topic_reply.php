<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.4 Build 0820
 * Aug 20, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

if (!is_object($forum) || !is_object($topic)) {
  celeste_exception_handle('invalid_id');
}

if ($topic->getProperty('locked') && !$celeste->isSU() && !$forum->permission['edittopic']) {
  celeste_exception_handle('topic_locked');
}

//if (!$celeste->usergroup['allowreply'] || !$forum->permission['allowreply']) {
if (!$forum->permission['allowreply']) {
  if($usergroupid==5) {
    import('login');
    celeste_login('prog=topic::reply&tid='.$topicid.'&pid='.$postid.'&postMode='.(isset($_GET['postMode']) ? $_GET['postMode'] : ''));
  }
  if(!$celeste->isSU()) celeste_exception_handle('permission_denied');
}

if (isset($_POST['title'])) {
  // submit here
  if (SET_FLOOD_CONTROL_TIME && $celeste->login && !$celeste->isSU() && !$forum->permission['edittopic'] && SET_FLOOD_CONTROL_TIME>($celeste->timestamp - $user->getProperty('lastpost')))
    celeste_exception_handle('flood_prohibited');

  if (isset($_POST['title'])) $_POST['title'] =& trim($_POST['title']);
  if (isset($_POST['content'])) $_POST['content'] =& trim($_POST['content']);
  
  if (empty($_POST['title'])) celeste_exception_handle('invalid_title');
  if (empty($_POST['content'])) celeste_exception_handle('invalid_content');
  $_POST['title'] =& nl2br( _removeHTML( _replaceCensored(slashesEncode( $_POST['title']))));
  //$_POST['content'] =& nl2br( _removeHTML( slashesEncode( $_POST['content'])));
  //$_POST['content'] =& nl2br( _removeHTML( slashesDecode( $_POST['content'])));
  $_POST['content'] =& _removeHTML( slashesDecode( $_POST['content']));

  if (strlen($_POST['content']) > SET_MAX_POST_LENGTH) celeste_exception_handle('content_too_long');
  if (!empty($_POST['requirerating']) && ($usergroupid==5 || $_POST['requirerating'] > $user->getProperty('totalrating'))) 
    celeste_exception_handle('invalid_rating');
  //if (!empty($_FILES['attachment']['name'])  && $celeste->usergroup['allowupload']==1 && $forum->permission['allowupload']==1) 
  if (!empty($_FILES['attachment']['name'])  && $forum->permission['allowupload']==1) 
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
  }	else {
  	$attachmentid = 0;
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


  
  $pt = new post();
  $pt->setProperty('topicid', $topicid);

  $pt->setProperty('iconid', (empty($_POST['iconid']) ? 1 : $_POST['iconid']));
  $pt->setProperty('parentid', (empty($_POST['parentid']) ? 0 : $_POST['parentid']));
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
  $topic->addPost($pt);
  if (is_object($user)) $user->updateLastPost($pt->postid);
  $postid = $pt->postid;
  unset($pt);

	/**
	 * send email notice
	 */
  if(SET_ENABLE_EMAIL) {
    if (empty($_POST['parentid']))
    {
      $lastP =& $DB->result("select username,posttime,title,emailnotice,userid from celeste_post where topicid='".$topicid."' and postid<'".$postid."' order by postid ASC");

      if ($lastP['emailnotice'] && $lastP['userid']!=$userid)
        $lastP['email'] = $DB->result('select email from celeste_user where userid=\''.$lastP['userid'].'\'');
      else unset($lastP);
    }
    elseif (isInt($_POST['parentid']))
    {
      $lastP =& $DB->result('select topicid,username,posttime,title,emailnotice,userid from celeste_post where postid=\''.$_POST['parentid'].'\'');

      if ($lastP['topicid']==$topicid && $lastP['emailnotice'] && $lastP['userid']!=$userid) 
        $lastP['email'] =  $DB->result('select email from celeste_user where userid=\''.$lastP['userid'].'\'');
      else unset($lastP);
    }
    else
    {
      unset($lastP);
    }
    
    if (isset($lastP))
    {
      $parentpost =& $lastP;

        $em = new templateElement(readfromfile(DATA_PATH.'/email/reply_notice.tpl'));
        $em->set('username', $parentpost['username']);
        $em->set('posturl', SET_FORUM_URL.'redirect.php?prog=viewpost&pid='.$postid);
        $em->set('posttime', getTime($parentpost['posttime']));
        $em->set('posttitle', $parentpost['title']);
        $em->set('poster',($usergroupid==5 ? SET_GUEST_NAME : $user->getProperty('username') ) );
        $em->set('boardtitle', SET_TITLE);
        $em->parse();
        $celeste->sendmail($parentpost['email'] , SET_BOARD_EMAIL, substr($em->final,0, strpos($em->final,"\n")), substr($em->final, strpos($em->final,"\n")), SET_BOARD_EMAIL);
        unset($em);
    }

  }


  celeste_success_redirect('post_saved', 'prog=topic::'.$t->varvals['_readMode'].'&page=end&tid='.$topicid);
}
else
{

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
  
  //if ($forum->permission['allowupload'] && $celeste->usergroup['allowupload']) {
  if ($forum->permission['allowupload'])
  {
    $t->preload('upload_form');
    $up = true;
  } else $up = false;
  $t->retrieve();
  $root =& $t->get('post_form');
  $t->setRoot($root);
  $root->set('icon_list', $t->getString('icon_list'));
  
  if ($up)
  {
  	$up =& $t->get('upload_form');
  	$up->set('size', SET_ALLOW_UPLOAD_SIZE);
  	$up->set('type', SET_ALLOW_UPLOAD_TYPE);
    $up->set('maxrating', min(SET_ATTACH_MAX_REQ_RATING, is_object($user) ? $user->getProperty('totalrating') : 0) );
  	
    $root->set('upload_form', $up->parse()); 
  }
  $header = $t->get('header');

  // get nav
  $header->set('nav', getCache('tr_F'.$forumid.'_'.$forum->getProperty('path')).'&#187; '.SET_REPLY_TOPIC_TITLE);
  $header->set('pagetitle', SET_REPLY_TOPIC_TITLE);

  $root->set('thisprog' ,$thisprog);
  $root->set('forumid', $forumid);
  $root->set('topicid', $topicid);
  $root->set('postid', $postid);
  
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
    for ($i=rand(0, $c); $j<min(8, $c); $j++,$i++)
    {
      if ($i==$c) $i=0;
      $smiles->set('image', $smileImgs[$i]);
      $smiles->set('code', $smileTags[$i]);
      $smiles->parse(true);
    }
    $root->set('post_adv_showsmiles', $smiles->getContent());
  }
  else
  {
    $root->set('opp_mode_name', 'Advanced Mode');
    $root->set('opp_mode', 'adv');
  }
  
  $root->set('pagetitle', SET_REPLY_TOPIC_TITLE);

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
  
  if (isset($post) && is_object($post) && (!isset($_GET['quote']) || $_GET['quote']!=='0'))
  {
  	if (!$post->getProperty('requirerating') || ($celeste->login && $user->getProperty('totalrating')>=$post->getProperty('requirerating')) || $celeste->isSU())
  	{
      $contemp =& $post->getProperty('content');
      /*******************************
       * reverse string
       */
      import('stringreverse');
      $conReverse = new celesteStringReverse(0, 1, 1);
      $conReverse->setString($contemp);
      $contemp =& $conReverse->parse();
      
      $contemp =& preg_replace('/\\[(quote|code)\\].+\\[\\/(quote|code)\\]/isU', '', $contemp);
      $tmp =& explode('<br />', $contemp, 5);
      unset($tmp[4]);
      $contemp =& trim(implode("\n", $tmp));
      
    }
    else
    {
      $contemp = ' Hidden Post ';
    }

	$root->set('content', '[quote][b]'.$post->getProperty('username').': '.$post->getProperty('title')."[/b]\n".$contemp."\n[/quote]\n");
    $root->set('parentid', $post->postid);
  }
  
  $root->set('title', SET_REPLY_HEADER.( empty($post) ? $topic->properties['topic'] : $post->properties['title']));

  if (SET_POST_REVIEW_NUMBER && ($userid==-1 || $user->getProperty('showpostonreply')))
  {
  	import('string');
    $pv =& $t->get('post_review');
    //$useCeTag =& $forum->getProperty('allowcetag');
    //$sf = new celesteStringFactory($useCeTag, 0, 0, 0, $forum->getProperty('allowhtml'), 0 , SET_ALLOW_SMILE);
    if (!empty($_GET['pid']))
    {
      $pv->set('title', $post->getProperty('title'));
      $pv->set('username', $post->getProperty('username'));
      $pv->set('time', getTime($post->getProperty('posttime')));
      //$sf->setString($post->getProperty('content'));
      //$pv->set('content', $sf->parse());
      /**********************
       * need to check require rating
       */
      $pv->set('content', $post->getProperty('content'));
      $pv->parse();
    }
    else
    {
      $rs = $DB->query('SELECT title,userid,username,posttime,content,requirerating,cetag,smiles FROM celeste_post where topicid=\''.$topicid.'\' order by posttime DESC', 0, SET_POST_REVIEW_NUMBER);
      while($dataRow =& $rs->fetch())
      {
        $pv->set('title', $dataRow['title']);
        $pv->set('username', $dataRow['username']);
        $pv->set('time', getTime($dataRow['posttime']));
      
        if (!$dataRow['requirerating'] || ($celeste->login && $user->getProperty('totalrating')>=$dataRow['requirerating']) || $celeste->isSU())
        {
          //$sf->setString($dataRow['content']);
          //$sf->setceTag( $dataRow['cetag'] && $useCeTag);
          //$sf->setSmile( SET_ALLOW_SMILE && $dataRow['smiles']);
          //$pv->set('content', $sf->parse());
          $pv->set('content', $dataRow['content']);
        } else 
          $pv->set('content', ' Hidden Post ');
        $pv->parse(true);
      }
      $rs->free();
    }
    $root->set('post_review', $pv->final);
  }
}