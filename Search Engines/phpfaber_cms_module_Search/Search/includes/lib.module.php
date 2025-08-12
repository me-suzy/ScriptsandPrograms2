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
// 06:10 PM 08/10/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

global $SITE_SIZE, $RES_NUM, $MIN_LENGTH, $INDEXING_SCHEME, $NUMBERS, $STOP_WORDS, $STOP_WORDS_ARRAY, $HASHSIZE, $CACHE_HASH, $CACHE_HASHWORDS, $CACHE_FINFO, $CACHE_SITEWORDS, $CACHE_WORD_IND;

$SITE_SIZE = $_MOD->SETT['SITE_SIZE'];
$RES_NUM = $_MOD->SETT['RES_NUM'];
$MIN_LENGTH = $_MOD->SETT['MIN_LENGTH'];
$INDEXING_SCHEME = $_MOD->SETT['INDEXING_SCHEME'];
$NUMBERS = $_MOD->SETT['NUMBERS'];
$STOP_WORDS = $_MOD->SETT['STOP_WORDS'];

# Path to index database files
$CACHE_HASH      = "$_MOD->TPL/spider_Hash";
$CACHE_HASHWORDS = "$_MOD->TPL/spider_HashWords";
$CACHE_FINFO     = "$_MOD->TPL/spider_FInfo";
$CACHE_SITEWORDS = "$_MOD->TPL/spider_SiteWords";
$CACHE_WORD_IND  = "$_MOD->TPL/spider_WordInd";

if ($SITE_SIZE==1) $HASHSIZE = 20001;
elseif ($SITE_SIZE==3) $HASHSIZE = 100001;
elseif ($SITE_SIZE==4) $HASHSIZE = 300001;
else $HASHSIZE = 50001;

$STOP_WORDS = preg_replace("/\s+/s"," ",$STOP_WORDS);
$pos = 0;
do {
  $new_pos = strpos($STOP_WORDS," ",$pos);
  if ($new_pos === FALSE) {
    $word = substr($STOP_WORDS,$pos);
    $STOP_WORDS_ARRAY[$word] = 1;
    break;
  };
  $word = substr($STOP_WORDS,$pos,$new_pos-$pos);
  $STOP_WORDS_ARRAY[$word] = 1;
  $pos = $new_pos+1;
} while(1>0);

# ----------------------------------------------------------------------
# <FUNCTIONS>

function module_Search_PrepareString($str)
{
  $str = preg_replace ("/^\s+|\s+$/", '', $str);
  $str = preg_replace ("/\s+/", "|", $str);
  $str = preg_replace ("/\./", "\\\.", $str);
  $str = "(".$str.")";
  return $str;
}

function module_Search_Hash($key)
{
  $chars = preg_split("//",$key);
  for ($i=1;$i<count($chars)-1;$i++) {
    $chars2[$i] = ord($chars[$i]);
  }
  $h = hexdec("00000000");
  $f = hexdec("F0000000");
  for ($i=1;$i<count($chars)-1;$i++) {
    $h = ($h << 4) + $chars2[$i];
    if($g = $h & $f) { $h ^= $g >> 24; };
    $h &= ~$g;
  }
  return $h;
}

# </FUNCTIONS>
# ----------------------------------------------------------------------

?>