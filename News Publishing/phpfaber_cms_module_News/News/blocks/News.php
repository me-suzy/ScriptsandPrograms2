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
// 05:19 PM 10/03/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

$_BLOCK['News'] = array(
  'descr' => 'News',
);

function block_News(&$tpl)
{
  global $db,$_CFG;
  
  $MOD_NAME = 'News';
  
  if (!isModuleInstalled($MOD_NAME)) return false;

  $_MOD = new Modules($MOD_NAME);
  $ql = "SELECT *, UNIX_TIMESTAMP(date_added) as date_added FROM {$_MOD->TBL['list']} WHERE is_visible=1 AND date_added<=NOW() AND exp_date>=NOW() ORDER BY date_added DESC LIMIT 5";
  $news = $db->GetData($ql);
  foreach ($news as $n) {
    $n['date_added'] = strftime($_CFG->DATEF_SHORT, $n['date_added']);
    $n['url'] = StrToUrl($n['title']).'-'.$n['id'].'.html';
    $tpl->Add('news', $n);
  }
}

?>