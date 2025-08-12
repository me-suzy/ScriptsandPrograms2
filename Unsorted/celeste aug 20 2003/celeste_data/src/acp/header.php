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

import('acp_menu_header');

$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : 'global';
$header = new ACP_MENU_HEADER($groupid);

$header->addGroup('Global Settings', 'global');
$header->addGroup('Forums', 'forum');
$header->addGroup('Topics & Posts', 'post');
$header->addGroup('Users & Groups', 'user');
$header->addGroup('Database', 'data');

$header->plot();
