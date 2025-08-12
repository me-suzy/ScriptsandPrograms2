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

function module_GoogleSitemap_Build()
{
  global $_CFG,$_MOD,$_EVENT;

  $_EVENT['google_urls'] = array();
  DoEventMod('onGoogleSitemapBuild',$_EVENT);
  $tree = $_EVENT['google_urls'];
  $O_PAGE = PagesTree::getInstance();
  $data = $O_PAGE->GetTree();
  $n = 0;
  $l_arr = array();
  $l_arr[0] = 0;
  $u_arr = array();
  foreach ($data as $d) {
    $l_arr[$d[$O_PAGE->LEV]] = ++$n;
    $u_arr[$d[$O_PAGE->LEV]] = $d[$O_PAGE->URL];
    $url = join('/', array_slice($u_arr, 0, $d[$O_PAGE->LEV]));
    $tree[] = "$_CFG->URL_SITE/$_CFG->PG_IDX/$url/";
  }
  $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">';
  foreach ($tree as $url) {
  $xml .= "
  <url>
    <loc>$url</loc>
    <changefreq>daily</changefreq>
  </url>";
  }
  $xml .= '
</urlset>';
  return FileWrite("$_CFG->PATH_SITE/".$_MOD->SETT['MAP_FNAME'],$xml);
}

?>