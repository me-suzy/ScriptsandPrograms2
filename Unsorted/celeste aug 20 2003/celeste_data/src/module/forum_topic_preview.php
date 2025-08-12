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

if (!$forumid || empty($_POST['content'])) celeste_exception_handle('invalid_data');

import('string');

$t->preload('topic_preview');
$t->retrieve();

$root =& $t->get('topic_preview');
$t->setRoot($root);

$header = $t->get('header');

$useCeTag =& $forum->getProperty('allowcetag');

$ContentProcessor = new celesteStringFactory(
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

$_POST['title'] =& _removeHTML($_POST['title']);
$_POST['content'] =& _removeHTML($_POST['content']);
$ContentProcessor->setString($_POST['content']);

$root->set('title', $_POST['title']);
$root->set('content', $ContentProcessor->parse());


?>