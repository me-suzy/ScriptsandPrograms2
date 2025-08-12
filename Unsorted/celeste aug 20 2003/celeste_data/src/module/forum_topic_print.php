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

if (!is_object($topic)) {
  import('exception');
  new exception('invalid_id');
}

$topic->incHit();
//import('string');

$t->preload('topic_print', true);
$t->preload(array('post_review', 'edit_status', 'attachment_other', 'attachment_image'));

$t->retrieve();

$root =& $t->get('topic_print');
$t->setRoot($root);

$totalPage = ceil($topic->getProperty('posts') /SET_POST_PP);

$topicTitle =& _replaceCensored($topic->getProperty('topic'));
$header=&$t->get('header');
//$header->set('pagetitle', $topicTitle);
$root->set('topic', $topicTitle);
$root->set('pagetitle', $topicTitle);

//$path =& readfromfile(DATA_PATH.'/cache/'.$forumid.'_tr.tmp');
$path =& getCache('tr_F'.$forumid.'_'.$forum->getProperty('path'));
$root->set('nav', $path);


    $pv =& $t->get('post_review');
    $useCeTag =& $forum->getProperty('allowcetag');
    //$sf = new celesteStringFactory($useCeTag, 0, 0, 0, $forum->getProperty('allowhtml'), 0 , SET_ALLOW_SMILE);
    $rs = $DB->query('SELECT title,userid,username,posttime,content,requirerating,cetag,smiles FROM celeste_post where topicid=\''.$topicid.'\' order by postid DESC', 0, SET_POST_REVIEW_NUMBER);
     while($dataRow =& $rs->fetch()){
        $pv->set('title', $dataRow['title']);
        $pv->set('username', $dataRow['username']);
        $pv->set('time', getTime($dataRow['posttime']));
      
        if (!$dataRow['requirerating'] || ($celeste->login && $user->getProperty('totalrating')>=$dataRow['requirerating']) || $celeste->isSU()) {
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
    $root->set('posts', $pv->final);

$root->set('forumid', $forumid);
$root->set('topicid', $topicid);


?>