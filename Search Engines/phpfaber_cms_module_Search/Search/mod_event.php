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

function module_Search_Event($event,&$args)
{
  global $_CFG, $MOD_NAME, $LOGF;

  switch ($event) {
    case 'onCronExec':
      $_MOD = new Modules($MOD_NAME);
      $LOGF = "$_MOD->TPL_ADM/spider.log";
      LoadModLng($_MOD->LNG);
      include_once "$_CFG->PATH_MOD/$MOD_NAME/includes/lib.module.php";
      include_once "$_CFG->PATH_MOD/$MOD_NAME/mod_cron.php";
      break;
  }

}

?>