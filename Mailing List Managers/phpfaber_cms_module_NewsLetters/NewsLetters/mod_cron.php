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
// 12:21 AM 09/23/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

function module_NewsLetters_Cron(&$args)
{
  global $db, $_CFG, $MOD_NAME;

  $MAIL_PER_TIME = 100;
  $TIME_WAIT = 60; // seconds

  $_MOD = new Modules($MOD_NAME);
  $letters = $db->GetData("SELECT id, list_id, subj, content, is_html, status FROM {$_MOD->TBL['letter']} WHERE date<=NOW() AND status<2");
  if (!$letters) return; // no matching mailing lists

  foreach ($letters as $v) {
    if (!$v['status']) {
      $db->Query("UPDATE {$_MOD->TBL['letter']} SET status=1, amount=0 WHERE id='{$v['id']}'");
    }
    $unsub_url_tpl = "$_CFG->URL_SITE/$_CFG->PG_MOD?mod=$MOD_NAME&action=unsubscribe&mlist_id={$v['list_id']}&email=";

    $total = 0;
    $cur = 0;

    $subs = $db->GetData("SELECT name, email FROM {$_MOD->TBL['subscriber']} WHERE list_id='{$v['list_id']}'");

    foreach ($subs as $sub) {
      if (IsEmail($sub['email'])) {
        $sub['unsub_url'] = $unsub_url_tpl.$sub['email'];
        SendMail($sub['email'],$_CFG->ADMIN_EMAIL,$v['subj'],TplLoadBuf($v['content'],$sub,0));
        $total++;
      }
      $db->query("UPDATE {$_MOD->TBL['letter']} SET amount=amount+1 WHERE id='{$v['id']}'");
      $cur++;
      if($cur>=$MAIL_PER_TIME){
        $cur = 0;
        sleep($TIME_WAIT);
      }
    }
    $db->Query("UPDATE {$_MOD->TBL['letter']} SET status=2 WHERE id='{$v['id']}'");
    SendSysMsg($v['subj'],sprintf(DoLng('_CRON_NL_TOTSENT'),$v['id'],$total,$v['subj']));
    if ($_MOD->SETT['EN_M_CRON']) SendMail($_CFG->ADMIN_EMAIL,$_CFG->ADMIN_EMAIL,$v['subj'],sprintf(DoLng('_CRON_NL_TOTSENT'),$v['id'],$total,$v['subj']));
    $args['A_CRON'][$MOD_NAME][sprintf(DoLng('_CRON_NL_TOTSENT_'),$v['id'])] = $total;
  }
  return $args;
}

?>
