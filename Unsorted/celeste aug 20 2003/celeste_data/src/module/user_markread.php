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

if(!$celeste->login)
  redirect('index.php');

$DB->update("UPDATE celeste_useronline SET lastvisit = '".$celeste->timestamp."' WHERE userid = '".$userid."'");
$celeste->setCookie('lastvisit_'.$userid, $celeste->timestamp);
redirect('index.php');
