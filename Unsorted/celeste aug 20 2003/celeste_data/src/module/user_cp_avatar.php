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

if (!$celeste->login) {
  import('login');
  celeste_login('prog=ucp::avatar');
}

if(empty($_POST['step'])) {

  $t->preload('user_cp_menu');
  $t->preload('user_cp_avatar');
  $t->preload('user_cp_avatar_pic');
  $t->preload('user_cp_avatar_pic_nl');
  $t->preload('only_one_page');
  $t->preload('page');
  $t->preload('multi_page');
  $t->preload('current_page');
  $t->retrieve();

  $header =& $t->get('header');
  $header->set('pagetitle', SET_USER_CP_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_USER_CP_TITLE);
  $root =& $t->get('user_cp_avatar');
  $t->setRoot($root);
  $root->set('user_cp_menu', $t->getString('user_cp_menu'));
  $root->set( 'username', $user->getProperty('username'));
  $root->set( 'currentavatar', $user->getAvatar( $user->properties ));
  
  // form here
  if (empty($_GET['page'])) {
  	$page = 1;
  } else {
  	$page = $_GET['page'];
  }
   
  if (cacheExists('avatarlist')) {
    $avatarlist =& explode("\n", getCache('avatarlist'));
  } else {
    $dh=opendir('./images/avatars');
    while ($imagefile=readdir($dh))
    {
      if (($imagefile!='.') && ($imagefile!='..') && ($imagefile!='upload'))
      $avatarlist[] = $imagefile;
    }
    closedir($dh);
    storeCache('avatarlist', implode("\n", $avatarlist));
  }
  
  $maxpage = ceil(count($avatarlist) / 40);
  if ($page=='end') $page = $maxpage;
  $end = $page*40;
  $pic =& $t->get('user_cp_avatar_pic');
  $nl =& $t->getString('user_cp_avatar_pic_nl');

  $root->set('totalavatar', count($avatarlist));
  $root->set('start', ($page-1) * 40);
  $root->set('end', $end = min($end, count($avatarlist)));
  $root->set('maxheight', SET_MAX_AVATAR_HEIGHT);
  $root->set('maxwidth', SET_MAX_AVATAR_WIDTH);

  for ($i=($page-1) * 40; $i<$end; $i++) {
  //for ($i=($page-1) * 40; $i<$end; $i++) {
  	if (empty($avatarlist[$i])) {
  	  $pic->set('name', '');
  	  $pic->set('img', '&nbsp;');
  	} else {
  	  $pic->set('name', $avatarlist[$i]);
  	  $pic->set('img', '<img src=\'images/avatars/'.$avatarlist[$i].'\'>');
  	}
  	if ( ($i+1) % 5 == 0 && $i!=$end-1) $pic->set('nl', $nl);
  	else $pic->set('nl', '');
    $pic->parse(true);
  }

  $root->set('piclist', $pic->getContent());
  if ($maxpage<=1) {
    $root->set('page', $t->getString('only_one_page'));
  } else {
    $pageTemp =& $t->get('page');
    for ($i=1; $i<=$maxpage; $i++) {
      $pageTemp->set('url', 'prog=ucp::avatar&page='.$i);
      $pageTemp->set('page', ($i==$page ? '<b>'.$i.'</b>' : $i ));
  	  $pageTemp->parse(true);
    }
  
  $multipage = $t->get('multi_page');
  $multipage->set('url', 'prog=ucp::avatar');
  $multipage->set('pages', $pageTemp->final);
  
  $root->set('page', $multipage->parse());
  }
  
 /* if (preg_match('/^images\/avatars/', $user->getProperty('avatar')))
  {
    // using provided avatar
    $root->set('use_provided', 'checked');
  }
  else */
  if (preg_match('/^(http:\/\/|images\/upload)/', $user->getProperty('avatar'))) {

  	$root->set('use_url', 'checked');
  	$root->set('url', $user->getProperty('avatar'));
  	$root->set('url_width', $user->getProperty('avatarwidth'));
  	$root->set('url_height', $user->getProperty('avatarheight'));
  }
  elseif (empty($user->properties['avatar'])) {
  	$root->set('use_none', 'checked');
  }
}
else
{
  // update
  //print $_POST['name'];
  
  if (isset($_POST['use']) && $_POST['use'] == 'upload' && isset($_FILES['upload_avatar']))
  {
    list($nouse, $ext) = explode('.', $_FILES['upload_avatar']['name'], 2);
    $ext = strtolower($ext);

    if (!SET_ALLOW_USER_UPLOAD_AVATAR || $user->getProperty('posts')<SET_MIN_POST_TO_UPLOAD_AVATAR || $user->getProperty('totalrating')<SET_MIN_RATING_TO_UPLOAD_AVATAR) {
      celeste_exception_handle('upload_avatar_forbidden');
    }
    
    if (!(($ext=='gif' || $ext=='jpg' || $ext=='jpeg' || $ext=='png') || !preg_match('/^image/', $_FILES['upload_avatar']['type'])))
    {
      celeste_exception_handle('Invalid_upload_type');
    }
    elseif ($HTTP_POST_FILES['upload_avatar']['size']>SET_MAX_AVATAR_FILESIZE)
    {
      celeste_exception_handle('Invalid_upload_size');
    }
    $_POST['upload_height'] = max(min(SET_MAX_AVATAR_HEIGHT, $_POST['upload_height']) , 1);
    $_POST['upload_width'] = max(min(SET_MAX_AVATAR_WIDTH, $_POST['upload_width']) , 1);

    $filename = 'images/upload/'.(substr(md5($user->username), 0, 10)).'.'.$ext;
    if (file_exists($filename)) unlink($filename);
    
    move_uploaded_file($_FILES['upload_avatar']['tmp_name'], $filename);
    
    chmod($filename, 0777);

    $user->setProperty('avatar', $filename);
    $user->setProperty('avatarwidth', $_POST['upload_width']);
    $user->setProperty('avatarheight', $_POST['upload_height']);
    
    $user->flushProperty();
    
    celeste_success_redirect('avatar_updated', 'prog=ucp::profile');
    
  }
  elseif (isset($_POST['use']) && $_POST['use'] == 'url' && isset($_POST['url']))
  {
    // update URL address
    if (!preg_match('/^http:\/\//', $_POST['url']) && !preg_match('/^images\/upload\/[a-zA-Z0-9]{6,32}\.[a-z]{3,4}$/',$_POST['url']) )
    {
      celeste_exception_handle('url_error');
    }

    $_POST['url_height'] = max(min(SET_MAX_AVATAR_HEIGHT, $_POST['url_height']) , 1);
    $_POST['url_width'] = max(min(SET_MAX_AVATAR_WIDTH, $_POST['url_width']) , 1);
     
    $user->setProperty('avatar', $_POST['url']);
    $user->setProperty('avatarwidth', $_POST['url_width']);
    $user->setProperty('avatarheight', $_POST['url_height']);
    
    $user->flushProperty();
    celeste_success_redirect('avatar_updated', 'prog=ucp::profile');
  }
  elseif (isset($_POST['use']) && $_POST['use'] == 'provided' && preg_match('/^[a-zA-Z0-9_-]{1,32}\.(gif|jpg)$/', $_POST['name']))
  {
  	// use provided image

    $user->setProperty('avatar', 'images/avatars/'.$_POST['name']);
    $user->setProperty('avatarwidth', '0');
    $user->setProperty('avatarheight', '0');
    
    $user->flushProperty();
    celeste_success_redirect('avatar_updated', 'prog=ucp::profile');
  }elseif  ($_POST['use'] == 'none') {

  	$user->setProperty('avatar', '');
    $user->flushProperty();
    celeste_success_redirect('avatar_updated', 'prog=ucp::profile');
  }
  
  celeste_exception_handle('invalid_data');
}
?>