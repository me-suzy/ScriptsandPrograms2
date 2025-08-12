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

  $acp->newFrm('General System Settings in Your Celeste');
  $acp->setFrmBtn();

  /**
   * Main
   */
  $acp->newTbl('Main Settings', 'main');
  $acp->newRow('Celeste Title', $acp->frm->frmText('SET_TITLE', SET_TITLE, 60));
  $acp->newRow('Celeste URL', $acp->frm->frmText('SET_FORUM_URL', SET_FORUM_URL, 60));
  $acp->newRow('Default Charset', $acp->frm->frmText('SET_DEFAULT_CHARSET', SET_DEFAULT_CHARSET, 60));


  $acp->newTbl('Cache & Output Settings', 'output');
  //$acp->newRow('Topic Cache',
  //              $acp->frm->frmList('SET_TOPIC_CACHE_LEVEL', SET_TOPIC_CACHE_LEVEL+1, 'Disabled', 'Cache In Public Forum', 'Cache In Public Forum & Private Forum', 'Cache All Topics'), '* Cache In Public Forum & Private Forum will save topics in private forum into HTTP inaccessible dir whereas Cache All Topics will save all topics into HTTP accessible dir, no matter whether the topics in private forum or public forum, which means the forum\'s permission settings will be disabled');
  $acp->newRow('Use Template Cache',
                $acp->frm->frmAnOp('SET_USE_TEMPLATE_CACHE', SET_USE_TEMPLATE_CACHE),
                '( Recommend )');
  $acp->newRow('GZIP Compress Level',
                $acp->frm->frmText('SET_GZIP_LEVEL', SET_GZIP_LEVEL, 25),
                '* Integer from 0 to 9<br> * Recommend set to "2"<br> * 0 to close GZIP Compress Output');
  $acp->newRow('Template Table',
                $acp->frm->frmText('SET_TEMPLATE_TABLE', SET_TEMPLATE_TABLE, 25),
                '( In MySQL Database )');


  $acp->newTbl('Cookie Settings', 'cookie');
  $acp->newRow('Cookie Header',
                $acp->frm->frmText('SET_COOKIE_HEADER', SET_COOKIE_HEADER, 25),
                '* A unique string for identification.<br>* try to change this if you met cookie problem' );
  $acp->newRow('Cookie Life Time',
                $acp->frm->frmText('SET_COOKIE_LIFETIME', SET_COOKIE_LIFETIME/(60*60*24), 25),
                '* Cookie\' Max Life Span<br> * Measured in days');


  $acp->newTbl('Executive Time', 'exectime');
  $acp->newRow('Display execution time',
                $acp->frm->frmAnOp('SET_BENCH_TIME', SET_BENCH_TIME),
                '');
  $acp->newRow('Max execution time',
                $acp->frm->frmText('SET_MAX_EXECUTION_TIME', SET_MAX_EXECUTION_TIME, 25),
                '* In seconds');

  $acp->newTbl('Database Connection', 'dbcon');
  $acp->newRow('Use Persistent Connection?', $acp->frm->frmAnOp('SET_USE_PCONNECT', SET_USE_PCONNECT));

} else {

  $m = new modify_setting( DATA_PATH.'/settings/config.global.php' );

  $m->set('SET_TITLE', $_POST['SET_TITLE']);
  $m->set('SET_FORUM_URL', $_POST['SET_FORUM_URL']);
  $m->set('SET_DEFAULT_CHARSET', $_POST['SET_DEFAULT_CHARSET']);

  $m->set('SET_TEMPLATE_TABLE', $_POST['SET_TEMPLATE_TABLE']);
  $m->set('SET_TOPIC_CACHE_LEVEL', intval($_POST['SET_TOPIC_CACHE_LEVEL'])-1, 0);
  $m->set('SET_USE_TEMPLATE_CACHE', intval($_POST['SET_USE_TEMPLATE_CACHE']), 0);
  $m->set('SET_GZIP_LEVEL', intval($_POST['SET_GZIP_LEVEL']), 0);

  $m->set('SET_BENCH_TIME', intval($_POST['SET_BENCH_TIME']), 0);
  $m->set('SET_MAX_EXECUTION_TIME', intval($_POST['SET_MAX_EXECUTION_TIME']), 0);

  $m->set('SET_COOKIE_HEADER', $_POST['SET_COOKIE_HEADER'], 0);
  $m->set('SET_COOKIE_LIFETIME', intval($_POST['SET_COOKIE_LIFETIME'])*60*60*24, 0);

  $m->set('SET_USE_PCONNECT', intval($_POST['SET_USE_PCONNECT']), 0);

  $m->save();

  acp_success_redirect('You have updated the settings successfully', 'prog=global::general');

}
