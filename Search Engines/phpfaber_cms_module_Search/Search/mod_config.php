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
// 10:51 PM 09/04/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

$_MOD = array(
  'name'  => 'Search',
  'descr' => 'Search module for your site. This module works as crawler and indexes your site daily using cron. Thanks to it your visitors are able to search what they want to find on your site!',
);

$_MOD_SUBPG = array(
  'setup'     => '_HEADER_MODULE_SETTINGS',
  'spiderlog' => '_PAGE_TITLE_SL',
);

$_MOD_CFG = array(
  'SITE_SIZE'       => 3,
  'RES_NUM'         => 10,
  'MIN_LENGTH'      => 3,
  'INDEXING_SCHEME' => 2,
  'NUMBERS'         => '0-9',
  'STOP_WORDS'      => 'and any are but can had has have her here him his
how its not our out per she some than that the their them then there
these they was were what you',
  'EN_USE_META'     => 0,
  'DESCR_SIZE'      => 256,
);

?>