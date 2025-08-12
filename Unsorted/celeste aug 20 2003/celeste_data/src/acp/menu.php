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
include(DATA_PATH.'/src/acp/acp_modules.php');
import('acp_menu_header');
import('acp_menu');

$groupid = !empty($_GET['groupid']) ? $_GET['groupid'] : 'global';


if ($groupid == 'logout') {
  header('Location: '.$_SERVER['PHP_SELF'].'?prog=logout');
  exit;

} elseif ($groupid == 'viewForum') {
  define('POPUP_FORUM', 1);
  $groupid = $_GET['oldGroupid'];
}


$header = new ACP_MENU_HEADER($groupid);
foreach($acp_group as $dgroupid=>$group) {
  $header->addGroup($group, $dgroupid);
}
$header->plot();
unset($header);


$menu = new ACP_MENU;

foreach($acp_category[$groupid] as $index=>$category) {
  $menu->addCat($category);

  foreach($acp_module[$groupid][$index] as $prog => $module) {
    $menu->addItem($module, $_SERVER['PHP_SELF'].'?prog='.$prog);

  }


}


$menu->plot();
unset($menu);

// goto forum
if(defined('POPUP_FORUM'))
  echo "<script>window.open('index.php');</script>\n";