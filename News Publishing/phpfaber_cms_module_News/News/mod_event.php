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

function module_News_Event($event,&$args)
{
  global $_CFG, $MOD_NAME, $db;

  switch ($event) {
    case 'onAdmViewSummary':
      $_MOD = new Modules($MOD_NAME);
      LoadModLng($_MOD->LNG);
      $args['A_SUMMARY']['content']["<a href='$_MOD->URL_ADM'>".DoLng('_TBL_TOTALNEWS')."</a>"] = $db->GetVar("SELECT count(*) FROM {$_MOD->TBL['list']}");
      $d = $db->GetVar("SELECT count(*) FROM {$_MOD->TBL['list']} WHERE is_visible<>1 OR exp_date<NOW()");
      if ($d) $args['A_SUMMARY']['expired']["<a href='$_MOD->URL_ADM&amp;active=expired'>".DoLng('_TBL_TOTALNEWS')."</a>"] = $d;
      $p = $db->GetVar("SELECT count(*) FROM {$_MOD->TBL['list']} WHERE is_visible=1 AND date_added>NOW()");
      if ($p) $args['A_SUMMARY']['pending']["<a href='$_MOD->URL_ADM&amp;active=pending'>".DoLng('_TBL_TOTALNEWS')."</a>"] = $p;
      break;
    case 'onAdditHeadsBuild':
      if (file_exists("$_CFG->PATH_SITE/$_CFG->FLD_CONTENT/files/news_feeds.rdf")) $_CFG->ADDIT_HEADS .= "\n".'<link rel="alternate" type="application/rdf+xml" title="'.$_MOD->SETT['RSS_TITLE'].'" href="'.$_CFG->URL_SITE.'/'.$_CFG->FLD_CONTENT.'/files/news_feeds.rdf" />';
      break;
    case 'onGoogleSitemapBuild':
      $_MOD = new Modules($MOD_NAME);
      $news = $db->GetData("SELECT * FROM {$_MOD->TBL['list']} WHERE is_visible=1 AND date_added<=NOW() AND exp_date>=NOW() ORDER BY id");
      foreach ($news as $n) $args['google_urls'][] = "$_CFG->URL_SITE/$_CFG->PG_MOD/$MOD_NAME/".StrToUrl($n['title']).'-'.$n['id'].'.html';
      break;
  }

}

?>