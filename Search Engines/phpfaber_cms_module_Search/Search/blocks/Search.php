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
// 06:16 PM 08/10/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

$_BLOCK['Search'] = array(
  'descr' => 'Search',
);

function block_Search(&$tpl)
{
  $MOD_NAME = 'Search';
  if (!isModuleInstalled($MOD_NAME)) return false;
  $tpl->Add('search_query', $_GET['query']);
  $tpl->Add('search_type', $_GET['stype']);
}

?>