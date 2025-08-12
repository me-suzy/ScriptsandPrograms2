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

if (!is_object($topic))
{
  import('exception');
  new exception('invalid_id');
}

$topic->incHit();
import('string');

if ($forum->permission['setpermission'] || $celeste->isSU())
{ 
  $showIp = true;
} else {
  $showIp = false;
}

$t->preload(array('topic_threaded','thread_post','topic_flat_post', 'rate_form', 'rate_disable', 'edit_status', 'attachment_other', 'attachment_image'));
!SET_FAST_REPLY || $t->preload('fast_reply');

$posts = $DB->query("SELECT postid, parentid, title, username, posttime FROM celeste_post WHERE topicid='$topicid' order by postid ASC");
$fdRow =& $posts->fetch();
$rootid = $fdRow['postid'];
if (empty($postid)) $postid=$rootid;

if ($rootid==$postid && $pollid = $topic->properties['pollid'])
{
  $t->preload(array('poll_timeout', 'poll_locked', 'poll_not_login', 'poll_available', 'option_multichoice',
    'option_simplechoice', 'display_poll', 'poll_optionresult', 'poll_voted'));
}
$t->retrieve();

$root =& $t->get('topic_threaded');
$t->setRoot($root);

!empty($pollid) && include(DATA_PATH.'/src/module/forum_topic_readpoll.php');

$header = $t->get('header');

//$topicTitle =& _replaceCensored($topic->getProperty('topic'));
$topicTitle =& $topic->getProperty('topic');
$header->set('pagetitle', $topicTitle);
$root->set('topic', $topicTitle);

$path =& getCache('tr_F'.$forumid.'_'.$forum->getProperty('path'));
$header->set('nav', $path);

$dataRow =& $DB->result('SELECT p.postid, p.iconid, p.attachmentid,p.html,p.image, p.posttime, p.username, u.userid, p.title, p.content, p.smiles, p.cetag, p.showsign,'.($showIp ? ' p.ipaddress,' : '').' p.edituser, p.edittime, p.rating, p.requirerating, 
u.email, u.homepage, u.title usertitle,'.
(!$user || $user->getProperty('showothersign') ? 'u.signature,' : '').'u.avatar, u.avatarwidth, u.avatarheight, u.location, u.joindate,u.posts,u.totalrating,u.publicemail,u.usergroupid,u.title usertitle,
ug.title gptitle, a.filename, a.counter, a.filetype, a.rating arating, a.direct_output FROM celeste_post p left join celeste_attachment a USING(attachmentid) left join celeste_user u ON(u.userid=p.userid) left join celeste_usergroup ug USING(usergroupid) WHERE p.topicid=\''.$topicid.'\' AND p.postid='.$postid);

$p = $t->get('topic_flat_post');

$useCeTag =& $forum->getProperty('allowcetag');

//$ContentProcessor = new celesteStringFactory( $useCeTag, 0, $forum->getProperty('allowimage'), SET_ALLOW_FLASH,  $forum->getProperty('allowhtml'), -1 , SET_ALLOW_SMILE);
//if (!$user || $user->getProperty('showothersign')) $SignProcessor = new celesteStringFactory( SET_ALLOW_CETAG_SIGN, 0, SET_ALLOW_IMG_SIGN, SET_ALLOW_FLASH_SIGN, SET_ALLOW_HTML_SIGN, SET_ALLOW_IMG_SIGN_MAX, SET_ALLOW_SMILE_SIGN);
$showsignature = (!$user || $user->getProperty('showothersign'));

  include_once(DATA_PATH.'/settings/title.inc.php');
  import('user');

  //$p->setArray( $dataRow );
  $p->set('username', $dataRow['username']);
  $p->set('userid', $dataRow['userid']);
  $p->set('postid', $dataRow['postid']);
  $p->set('title', $dataRow['title']);
  $p->set('posts', $dataRow['posts']);
  $p->set('totalrating', $dataRow['totalrating']);
  $p->set('joindate', $dataRow['joindate']);
  $p->set('iconid', $dataRow['iconid']);
  $p->set('avatar', user::getAvatar($dataRow));
  $p->set('location', $dataRow['location']);
  $p->set('gptitle', $dataRow['gptitle']);
  if ($showIp) $p->set('ipaddress', $dataRow['ipaddress']);
  
  if ($dataRow['userid'])
  {
    list($temptitle, $tempimage) = getTitle( $dataRow['usertitle'], $dataRow['posts'], $dataRow['usergroupid'] );
  }
  else 
  {
  	$temptitle=''; $tempimage='';
  }
  $p->set('usertitle', $temptitle);
  $p->set('usertitleimage', $tempimage);
  $p->set('posttime' , getTime($dataRow['posttime']));

  if($dataRow['edituser']) 
  {
    if (empty($e)) $e =& $t->get('edit_status');
    $e->set('edittime', getTime($dataRow['edittime']));
    $e->set('edituser', $dataRow['edituser']);
    $p->set('edit_status', $e->parse());
  }
  else
  {
  	$p->set('edit_status', '');
  }
  
  if($dataRow['rating'])
  {
  	if (empty($rate_disable)) $rate_disable =& $t->get('rate_disable');
    $rate_disable->set('rating', $dataRow['rating']);
    $p->set('rating', $rate_disable->parse());
  }
  else
  {
    $p->set('rating', $t->getString('rate_form'));
  }

  if($dataRow['attachmentid'])
  {
  	if (substr($dataRow['filetype'], 0, 5)=='image') 
  	{
  		// image
  		if (empty($imgattach)) $imgattach =& $t->get('attachment_image');
  		$attachment =& $imgattach;
  	}
  	else
  	{
  		if (empty($nonimgattach)) $nonimgattach =& $t->get('attachment_other');
  		$attachment =& $nonimgattach;
  	}
    $attachment->set('download_url',
       $dataRow['direct_output'] ? './direct_output/attachments/ATT'.$dataRow['attachmentid'].'_'.$dataRow['filename']:
                                   'index.php?prog=attach::dl&pid='.$dataRow['postid']);
    $attachment->set('postid', $dataRow['postid']);
    $attachment->set('filename', $dataRow['filename']);
    $attachment->set('counter', $dataRow['counter']);
    $attachment->set('rating', $dataRow['arating']);
    $p->set('attachment', $attachment->parse());
  }
  else 
  {
  	$p->set('attachment', '');
  }

  if (!$dataRow['requirerating'] || ($celeste->login && ($user->properties['totalrating']>=$dataRow['requirerating'] || $celeste->isSU()))) 
  {
  	//$ContentProcessor->setceTag($dataRow['cetag']);
    //$ContentProcessor->setSmile(SET_ALLOW_SMILE && $dataRow['smiles']);
    //$ContentProcessor->setImgcode($dataRow['image']);
    //$ContentProcessor->setHTML($dataRow['html']);
    //$ContentProcessor->setString($dataRow['content']);
  	//$p->set('content', $ContentProcessor->parse());
    $p->set('content', $dataRow['content']);
  
  }
  else
  {
    $p->set('content', '<font color=red><b>&gt;&gt;&gt; Hidden Post: Credits '.$dataRow['requirerating'].' &lt;&lt;&lt;</b></font>');
  }
  
  //if (!empty($dataRow['showsign']) && !empty($dataRow['signature']))
  //{
  //  $SignProcessor->setString($dataRow['signature']);
  //  $p->set('signature', $SignProcessor->parse());
  //} else {
  //  $p->set('signature', '');
  //}
  if($showsignature && $dataRow['showsign']) {
    $p->set('signature', $dataRow['signature']);
  }

$root->set('posts', $p->parse());
$root->set('forumid', $forumid);
$root->set('topicid', $topicid);

$list = array();
$list[(string)$rootid] = & $fdRow;

/**
 * build the post tree
 */

while($dataRow =& $posts->fetch())
{
  $list[(string)$dataRow['postid']] =& $dataRow;

  if (isset($list[(string)$dataRow['parentid']])) $p =& $list[(string)$dataRow['parentid']];
    else $p =& $list[(string)$rootid];

  if (!isset($p['child'])) $p['child']=array();
  $p['child'][] =& $list[(string)$dataRow['postid']];

  /**
   * avoid infinite loop due to DB clash
   */

  if ($dataRow['postid'] == $dataRow['parentid'] && $dataRow['parentid']!= $rootid)
  {
    //print $dataRow['postid']; print $rootid; 
    celeste_exception_handle('invalid_data');
  }
}
$posts->free();
/**
 * display the tree recursively
 */
$tp = $t->get('thread_post');

function viewTree ( &$root , $depth)
{
  global $tp, $postid, $list, $celeste;
  if ($depth >= 30) $depth --;
  $tp->set('title', ($root['postid'] == $postid ? '<b>'.$root['title'].'</b>': $root['title']));
  $tp->set('username', $root['username']);
  $tp->set('posttime', getTime( $root['posttime']));
  $tp->set('postid', $root['postid']);
  $tp->set('depth', str_repeat( '&nbsp; &nbsp;', $depth) );
  if ($celeste->login && $root['posttime']>$celeste->lastvisit) {
    $tp->set('bgcolor', SET_MAIN_COLOR1);
    $tp->set('status', 'new');
  }
  else
  {
  	$tp->set('bgcolor', SET_MAIN_COLOR2);
    $tp->set('status', 'old');
  }
  $tp->parse(true);
  if (isset($root['child'])) foreach ($root['child'] as $child) viewTree( $child, $depth + 1);
}

viewTree( $list[$rootid], 0 );
$root->parseBlock('thread_post', $tp);


if (SET_FAST_REPLY && ($celeste->login || $forum->permission['allowreply']) )
{
 $fr =& $t->get('fast_reply');
 $fr->set('parentid', $postid);
 $fr->set('topicid', $topicid);
 $fr->set('title', SET_REPLY_HEADER. $topic->properties['topic'] );
 $root->set('fast_reply', $fr->parse());
}

?>