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
// 11:46 PM 08/25/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

function module_GoogleSitemap_Cron(&$args)
{
  global $db, $_CFG, $MOD_NAME;

  $_MOD = new Modules($MOD_NAME);

  include_once "$_CFG->PATH_MOD/$MOD_NAME/includes/lib.module.php";
  $created = module_GoogleSitemap_Build();
  
  $MOD_NAME = 'GoogleSitemap';

  if ($created) {
    include_once "$_CFG->PATH_INC/HTTPGet.php";
    $http = new HTTPGet('google.com');
    $http->open();
    $n = 0;
    while (0) {
      $n++;
      $http->get("http://www.google.com/webmasters/sitemaps/ping?sitemap={$_CFG->URL_SITE}/".$_MOD->SETT['MAP_FNAME']);
      if (strpos($http->_header, '200 OK')) { $submited = 1; break; }
      elseif ($n>20) { $submited = 0; break; }
      sleep(2);
    }
    $http->close();
    $args['A_CRON'][$MOD_NAME][sprintf(DoLng($submited?'_MSG_SITEMAP_POSTED':'_MSG_ER_SITEMAP_POST'),$_MOD->SETT['MAP_FNAME'])] = '';
  }
  else {
    $args['A_CRON'][$MOD_NAME][sprintf(DoLng('_MSG_ER_SITEMAP_GENERATE'),$_MOD->SETT['MAP_FNAME'])] = '';
  }
  return $args;
  
}

?>