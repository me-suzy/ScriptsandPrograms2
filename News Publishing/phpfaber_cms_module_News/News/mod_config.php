<?php

// -----------------------------------------------------------------------------
//
// phpFaber CMS v.1.0
// Copyright(C), phpFaber LLC, 2004-2005, All Rights Reserved.
// E-mail: products@phpfaber.com
//
// All forms of reproduction, including, but not limited to, internet posting,
// printing, e-mailing, faxing and recording are strictly prohibited.
// One license required per site running phpFaber CMS.
// To obtain a license for using phpFaber CMS, please register at
// http://www.phpfaber.com/i/products/cms/
//
// 08:54 PM 09/25/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

$_MOD = array(
  'name'  => 'News',
  'descr' => 'Simple news system. You are abble to post your news feeds on the site using this module. RSS feeds will be prepared as well!',
);

$_MOD_SUBPG = array(
  'setup' => '_HEADER_MODULE_SETTINGS',
  'list'  => '_HEADER_NEWSLIST_PAGE',
  'add'   => '_HEADER_ADDNEW_PAGE',
);

$_MOD_CFG = array(
  'RES_NUM' => 10,
  'RSS_TITLE' => '',
  'RSS_RES_NUM' => 25,
  'HIT_UNBAN' => 1440,
);

$_MOD_DEL_REL_FILES = array(
  'news_feeds.rdf',
);

$_MOD_TBL = array(
  'list' => "CREATE TABLE list (
             id mediumint(8) NOT NULL auto_increment,
             title varchar(255) NOT NULL default '',
             text_short text NOT NULL,
             text_full mediumtext NOT NULL,
             is_visible int(3) NOT NULL default '1',
             date_added datetime NOT NULL default '0000-00-00 00:00:00',
             exp_date datetime NOT NULL default '0000-00-00 00:00:00',
             hits int(11) NOT NULL default '0',
             PRIMARY KEY (id),
             KEY (is_visible))",
);

?>
