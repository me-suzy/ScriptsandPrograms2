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

error_reporting(0);

switch($_GET['prog']) {

  case 'activate': $url = 'user::register&activate=1&key='.$_GET['key']; break;
  case 'viewtopic': $url = 'topic::flat&tid='.$_GET['tid']; break;
  case 'viewpost': $url = 'topic::threaded&pid='.$_GET['pid']; break;

  default: break;
}

header('Location: index.php?prog='.$url);