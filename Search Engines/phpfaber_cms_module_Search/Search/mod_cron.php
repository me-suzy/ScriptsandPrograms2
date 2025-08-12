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
// 02:10 AM 08/23/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

global $FILE_EXT, $NO_INDEX_DIR, $NO_INDEX_FILES, $CFN, $KBCOUNT, $fp_FINFO, $fp_SITEWORDS, $fp_WORD_IND, $WORDS, $START_URL, $ALLOW_URL, $NO_INDEX_STRINGS ,$USE_ESC, $DESCR_SIZE, $USE_META;

$USE_META = $_MOD->SETT['EN_USE_META'];
$DESCR_SIZE = $_MOD->SETT['DESCR_SIZE'];

# ----------------------------------------------------------------------
# Translate escape chars (like &Egrave; or &#255;)
$USE_ESC = 1;

# Parts of documents, which should not be indexed
$NO_INDEX_STRINGS = array(
    "<!-- MODULE_SEARCH: NO_INDEX -->" => "<!-- /MODULE_SEARCH: NO_INDEX -->",
);

# File extensions to index
$FILE_EXT = "NONE php html htm";

# Starting URL (used by spider)
$START_URL = array(
  "$_CFG->URL_SITE/",
);

# Spider will index only files from these servers
$ALLOW_URL = array(
  "$_CFG->URL_SITE/",
);

$arr_allowed_files = array($_CFG->PG_IDX,$_CFG->PG_MOD);
$NO_INDEX_DIR = ' ';
$NO_INDEX_FILES = ' ';
$dir = dir($_CFG->PATH_SITE);
$dir->read(); $dir->read();
while (($file = $dir->read())!==false) {
   if (is_file("$_CFG->PATH_SITE/$file") && !in_array($file,$arr_allowed_files)) $NO_INDEX_FILES .= "$file ";
   elseif (is_dir("$_CFG->PATH_SITE/$file")) $NO_INDEX_DIR .= "$file ";
}
$dir->close();
$NO_INDEX_DIR = module_Search_PrepareString($NO_INDEX_DIR);
$NO_INDEX_FILES = module_Search_PrepareString($NO_INDEX_FILES);

if (preg_match("/NONE/",$FILE_EXT)) {
  $FILE_EXT = preg_replace ("/NONE/", '', $FILE_EXT);
  $FILE_EXT = module_Search_PrepareString($FILE_EXT);
  $FILE_EXT = '(\.'.$FILE_EXT.'|^[^.]+|/[^.]*)$';
}
else {
  $FILE_EXT = module_Search_PrepareString($FILE_EXT);
  $FILE_EXT = '\.'.$FILE_EXT.'$';
}

$CFN = 0;
$CWN = 0;
$KBCOUNT = 0;
# ----------------------------------------------------------------------

LogWrite(DoLng('_CRON_START'), $LOGF, 0);

$fp_FINFO = fopen ("$CACHE_FINFO", "wb") or die("<code>Cannot open index file</code>");
fwrite($fp_FINFO, "\x0A");
$fp_SITEWORDS = fopen ("$CACHE_SITEWORDS", "wb") or die("<code>Cannot open index file</code>");
$fp_WORD_IND = fopen ("$CACHE_WORD_IND", "wb") or die("<code>Cannot open index file</code>");

$time1 = GMT();

module_Search_StartSpidering($_SERVER['ALLOW_URL_FOPEN']);

$time2 = GMT();
$time = round($time2 - $time1,2);
LogWrite(DoLng('_CRON_SCAN_TIME').": $time", $LOGF);

if ($CFN==0) {
  LogWrite(DoLng('_CRON_INDEXED_NO'), $LOGF);
}
else {
  LogWrite(DoLng('_CRON_SITEWORDS'), $LOGF);
  $POS_SITEWORDS = ftell($fp_SITEWORDS);
  $POS_WORD_IND  = ftell($fp_WORD_IND);
  $TO_PRINT_SITEWORDS = '';
  $TO_PRINT_WORD_IND  = '';
  foreach ($WORDS as $word=>$value) {
    $CWN++;
    $words_word_dum = pack("NN",$POS_SITEWORDS+strlen($TO_PRINT_SITEWORDS),$POS_WORD_IND+strlen($TO_PRINT_WORD_IND));
    $TO_PRINT_SITEWORDS .= "$word\x0A";
    $TO_PRINT_WORD_IND .= pack("N",strlen($value)/4).$value;
    $WORDS[$word] = $words_word_dum;
    if (strlen($TO_PRINT_WORD_IND)>32000) {
      fwrite($fp_SITEWORDS, $TO_PRINT_SITEWORDS);
      fwrite($fp_WORD_IND, $TO_PRINT_WORD_IND);
      $TO_PRINT_SITEWORDS = '';
      $TO_PRINT_WORD_IND  = '';
      $POS_SITEWORDS = ftell($fp_SITEWORDS);
      $POS_WORD_IND  = ftell($fp_WORD_IND);
    }
  }
  fwrite($fp_SITEWORDS, $TO_PRINT_SITEWORDS);
  fwrite($fp_WORD_IND, $TO_PRINT_WORD_IND);
  fclose($fp_SITEWORDS);
  fclose($fp_WORD_IND);

  LogWrite(DoLng('_CRON_BUILDHASH'), $LOGF);
  module_Search_BuildHash();

  LogWrite(DoLng('_CRON_INDEXED_NUM').": $CFN", $LOGF);
}
$args['A_CRON'][$MOD_NAME][DoLng('_CRON_SCAN_TIME')] = $time;
$args['A_CRON'][$MOD_NAME][DoLng('_CRON_INDEXED_NUM')] = $CFN;

# ----------------------------------------------------------------------
# <SPIDER FUNCTIONS>
function module_Search_StartSpidering($allow_furl=true)
{
  global $START_URL, $ALLOW_URL, $LOGF, $_CFG;

  $visited = array();
  $to_visit = array();
  foreach ($START_URL as $v) $to_visit[$v] = 1;

  if (!$allow_furl) {
    include_once "$_CFG->PATH_INC/HTTPGet.php";
  }

  do {
    if (!count($to_visit)) break;
    else list($url,) = each($to_visit);
    
    if ($allow_furl) $fp = fopen($url,"r");
    else {
      $u = parse_url($url);
      $http = new HTTPGet($u['host'],$u['port']?$u['port']:'80',$u['user']?$u['user']:'',$u['pass']?$u['pass']:'');
      $hres = $http->open();
      $text = $http->get($url);
    }

    $visited[$url] = 1;

    if ($fp==FALSE && $hres==FALSE) {
      LogWrite("Error in opening file: $url", $LOGF);
      unset($to_visit[$url]);
    }
    else {
      if ($fp) {
        $text = '';
        while (!feof($fp)) $text .= fgets($fp, 4096);
      }
      LogWrite("URL: $url - ".strlen($text)." bytes", $LOGF);
      //LogWrite("<hr>$text<hr>", $LOGF);

      $base = $url;
      if (preg_match_all("/<base\\s+href=([\"']?)([^\\s\"'>]+)\\1/is", $text, $matches,PREG_SET_ORDER)){
        $base = $matches[0][2];
      }

      $links = module_Search_GetLink($text);
      foreach ($links as $k=>$v) {
        $new_link = module_Search_GetAbsoluteURL($base,$k);
        $new_link = preg_replace("/#.*/",'',$new_link);
        $new_link_stripped = preg_replace("/\?.*/",'',$new_link);
        if (module_Search_CheckURL($new_link_stripped)) {
          if (!array_key_exists($new_link_stripped,$visited)) {
            $to_visit[$new_link_stripped] = 1;
          }
        }
      }

      module_Search_IndexFile($text,$url);
      unset($to_visit[$url]);
      if (!$allow_furl) $http->close();
    }

  } while(1);

}

function module_Search_GetLink($text)
{
  $links = array();
  $count = preg_match_all("/<a[^>]+href=([\"']?)([^\\s\"'>]+)\\1/is", $text, $matches, PREG_SET_ORDER);
  for ($i=0;$i<count($matches);$i++) $links[$matches[$i][2]] = 1;

  $count = preg_match_all("/<frame[^>]+src=([\"']?)([^\\s\"'>]+)\\1/is", $text, $matches, PREG_SET_ORDER);
  for ($i=0;$i<count($matches);$i++) $links[$matches[$i][2]] = 1;

  $count = preg_match_all("/<area[^>]+href=([\"']?)([^\\s\"'>]+)\\1/is", $text, $matches, PREG_SET_ORDER);
  for ($i=0; $i < count($matches); $i++) $links[$matches[$i][2]] = 1;

  return $links;
}

function module_Search_GetAbsoluteURL($base,$url)
{
  $url_arr = @parse_url($url);
  if(isset($url_arr['scheme'])) return($url);

  $base_arr = parse_url($base);
  $base_base = strtolower($base_arr['scheme']).'://';
  if (isset($base_arr['user'])) $base_base .= $base_arr['user'].':'.$base_arr['pass'].'@';
  $base_base .= strtolower($base_arr['host']);
  if (isset($base_arr['port'])) $base_base .= ":".$base_arr['port'];
  $base_path = $base_arr['path'];
  if ($base_path=='') $base_path = '/';
  $base_path = preg_replace("/(.*\/).*/","\\1",$base_path);
  if ($url_arr['path'][0] == '/') return $base_base.$url;

  if (preg_match("'^\./'",$url)) {
    $url = preg_replace("'^\./'",'',$url);
    return $base_base.$base_path.$url;
  }

  while (preg_match("'^\.\./'",$url)) {
    $url = preg_replace("'^\.\./'",'',$url);
    $base_path = preg_replace("/(.*\/).*\//","\\1",$base_path);
  }
  return $base_base.$base_path.$url;
}

function module_Search_CheckURL($url)
{
  global $FILE_EXT, $NO_INDEX_FILES, $NO_INDEX_DIR, $ALLOW_URL;
  if (!preg_match("'^http://'",$url)) return FALSE;
  if (!preg_match("'$FILE_EXT'i", $url)) return FALSE;
  if (preg_match("'$NO_INDEX_FILES'i", $url)) return FALSE;
  if (preg_match("'$NO_INDEX_DIR'i", $url)) return FALSE;

  $allow = 0;
  foreach ($ALLOW_URL as $v) {
    if (preg_match("'$v'i", $url)) {
      $allow = 1;
      break;
    }
  }
  if ($allow==0) return FALSE;
  return TRUE;
}

function module_Search_BuildHash()
{
  global $WORDS;
  global $HASHSIZE, $INDEXING_SCHEME, $CACHE_HASH, $CACHE_HASHWORDS;

  for ($i=0;$i<$HASHSIZE;$i++) $hash_array[$i] = '';

  foreach ($WORDS as $word=>$value) {
    if ($INDEXING_SCHEME==3) $subbound = strlen($word)-3;
    else $subbound = 1;
    if (strlen($word)==3) $subbound = 1;
    $substring_length = 4;
    if ($INDEXING_SCHEME==1) $substring_length = strlen($word);

    for ($i=0;$i<$subbound;$i++) {
      $hash_value = abs(module_Search_Hash(substr($word,$i,$substring_length)) % $HASHSIZE);
      $hash_array[$hash_value] .= $value;
    };
  }

  $fp_HASH = fopen ("$CACHE_HASH", "wb") or die("<code>Cannot open index file</code>");
  $fp_HASHWORDS = fopen ("$CACHE_HASHWORDS", "wb") or die("<code>Cannot open index file</code>");

  $zzz = pack("N", 0);
  fwrite($fp_HASHWORDS, $zzz);
  $pos_hashwords = ftell($fp_HASHWORDS);
  $to_print_hash = '';
  $to_print_hashwords = '';

  for ($i=0;$i<$HASHSIZE;$i++) {
    if ($hash_array[$i]=='') $to_print_hash .= $zzz;
    if ($hash_array[$i]!='') {
      $to_print_hash .= pack("N",$pos_hashwords + strlen($to_print_hashwords));
      $to_print_hashwords .= pack("N", strlen($hash_array[$i])/8).$hash_array[$i];
    };
    if (strlen($to_print_hashwords)>64000) {
      fwrite($fp_HASH,$to_print_hash);
      fwrite($fp_HASHWORDS,$to_print_hashwords);
      $to_print_hash = '';
      $to_print_hashwords = '';
      $pos_hashwords = ftell($fp_HASHWORDS);
    }
  }
  fwrite($fp_HASH,$to_print_hash);
  fwrite($fp_HASHWORDS,$to_print_hashwords);
  fclose($fp_HASH);
  fclose($fp_HASHWORDS);
}

function module_Search_GetMetaInfo($html)
{

 preg_match("/<\s*[Mm][Ee][Tt][Aa]\s*[Nn][Aa][Mm][Ee]=\"?[Kk][Ee][Yy][Ww][Oo][Rr][Dd][Ss]\"?\s*[Cc][Oo][Nn][Tt][Ee][Nn][Tt]=\"?([^\"]*)\"?\s*>/s",$html,$matches);
  $res[0] = $matches[1];
  preg_match("/<\s*[Mm][Ee][Tt][Aa]\s*[Nn][Aa][Mm][Ee]=\"?[Dd][Ee][Ss][Cc][Rr][Ii][Pp][Tt][Ii][Oo][Nn]\"?\s*[Cc][Oo][Nn][Tt][Ee][Nn][Tt]=\"?([^\"]*)\"?\s*>/s",$html,$matches);
  $res[1] = $matches[1];
  return $res;
}

function module_Search_IndexFile($html_text,$url)
{
  global $CFN, $KBCOUNT, $DESCR_SIZE, $MIN_LENGTH, $STOP_WORDS_ARRAY, $USE_ESC;
  global $NO_INDEX_STRINGS;
  global $USE_META;
  global $fp_FINFO;
  global $WORDS;
  global $NUMBERS;
  global $LOGF;

  $CFN++;
  $size = strlen($html_text);
  $KBCOUNT += intval($size/1024);
  LogWrite("$CFN -> $url; totalsize -> $KBCOUNT kb", $LOGF);

  # Delete parts of document, which should not be indexed
  foreach ($NO_INDEX_STRINGS as $k => $v) {
    $html_text = preg_replace('/'.preg_quote($k,'/').'.*?'.preg_quote($v,'/').'/s',' ',$html_text);
  }

  if (preg_match("/<title>\s*(.*?)\s*<\/title>/is",$html_text,$matches)) {
    $title = $matches[1];
  }
  else {
    $title = "No title";
  }

  preg_replace("/\s+/"," ",$title);
  if ($title=='') $title = "No title";

  $keywords = '';
  $description = '';
  if ($USE_META) {
    $res = module_Search_GetMetaInfo($html_text);
    $keywords = $res[0];
    $description = $res[1];
  }

  $html_text = preg_replace("/<!--.*?-->/s"," ",$html_text);
  $html_text = preg_replace("/<[Ss][Cc][Rr][Ii][Pp][Tt].*?<\/[Ss][Cc][Rr][Ii][Pp][Tt]>/s"," ",$html_text);
  $html_text = preg_replace("/<[Ss][Tt][Yy][Ll][Ee].*?<\/[Ss][Tt][Yy][Ll][Ee]>/s"," ",$html_text);
  $html_text = preg_replace("/<[^>]*>/s"," ",$html_text);
  if ($USE_ESC){ $html_text = preg_replace_callback("/&[a-zA-Z0-9#]*?;/", 'module_Search_EscToChar', $html_text); }

  if ($USE_META && $description) $descript = substr($description,0,$DESCR_SIZE);
  else {
    $html_text = preg_replace("/\s+/s"," ",$html_text);
    $descript = substr($html_text,0,$DESCR_SIZE);
  }

  $html_text = $html_text." ".$keywords." ".$description;
  $html_text = preg_replace("/[^a-zA-Z$NUMBERS -]/"," ",$html_text);
  $html_text = preg_replace("/\s+/s"," ",$html_text);
  $html_text = strtolower($html_text);

  $words_temp = array();

  $pos = 0;
  do {
    $new_pos = strpos($html_text," ",$pos);
    if ($new_pos===FALSE) {
      $word = substr($html_text,$pos);
      $words_temp[$word] = 1;
      break;
    };
    $word = substr($html_text,$pos,$new_pos-$pos);
    $words_temp[$word] = 1;
    $pos = $new_pos+1;
  } while(1>0);

  $pos = ftell($fp_FINFO);
  $pos = pack("N",$pos);
  $url = preg_replace("/\?.*/",'',$url);
  fwrite($fp_FINFO, "$url|::|$size|::|$title|::|$descript\x0A");

  foreach ($words_temp as $word => $val) {
    if (strlen($word)<$MIN_LENGTH) continue;
    if (array_key_exists($word,$STOP_WORDS_ARRAY)) continue;
    $WORDS[$word] .= $pos;
  }

  unset($words_temp);
}

function module_Search_EscToChar($str)
{

  $html_esc = array(
    "&Agrave;" => chr(192),
    "&Aacute;" => chr(193),
    "&Acirc;" => chr(194),
    "&Atilde;" => chr(195),
    "&Auml;" => chr(196),
    "&Aring;" => chr(197),
    "&AElig;" => chr(198),
    "&Ccedil;" => chr(199),
    "&Egrave;" => chr(200),
    "&Eacute;" => chr(201),
    "&Eirc;" => chr(202),
    "&Euml;" => chr(203),
    "&Igrave;" => chr(204),
    "&Iacute;" => chr(205),
    "&Icirc;" => chr(206),
    "&Iuml;" => chr(207),
    "&ETH;" => chr(208),
    "&Ntilde;" => chr(209),
    "&Ograve;" => chr(210),
    "&Oacute;" => chr(211),
    "&Ocirc;" => chr(212),
    "&Otilde;" => chr(213),
    "&Ouml;" => chr(214),
    "&times;" => chr(215),
    "&Oslash;" => chr(216),
    "&Ugrave;" => chr(217),
    "&Uacute;" => chr(218),
    "&Ucirc;" => chr(219),
    "&Uuml;" => chr(220),
    "&Yacute;" => chr(221),
    "&THORN;" => chr(222),
    "&szlig;" => chr(223),
    "&agrave;" => chr(224),
    "&aacute;" => chr(225),
    "&acirc;" => chr(226),
    "&atilde;" => chr(227),
    "&auml;" => chr(228),
    "&aring;" => chr(229),
    "&aelig;" => chr(230),
    "&ccedil;" => chr(231),
    "&egrave;" => chr(232),
    "&eacute;" => chr(233),
    "&ecirc;" => chr(234),
    "&euml;" => chr(235),
    "&igrave;" => chr(236),
    "&iacute;" => chr(237),
    "&icirc;" => chr(238),
    "&iuml;" => chr(239),
    "&eth;" => chr(240),
    "&ntilde;" => chr(241),
    "&ograve;" => chr(242),
    "&oacute;" => chr(243),
    "&ocirc;" => chr(244),
    "&otilde;" => chr(245),
    "&ouml;" => chr(246),
    "&divide;" => chr(247),
    "&oslash;" => chr(248),
    "&ugrave;" => chr(249),
    "&uacute;" => chr(250),
    "&ucirc;" => chr(251),
    "&uuml;" => chr(252),
    "&yacute;" => chr(253),
    "&thorn;" => chr(254),
    "&yuml;" => chr(255),
    "&nbsp;" => " ",
    "&amp;" => " ",
    "&quote;" => " ",
  );

  $esc = $str[0];
  $char = '';

  if (preg_match ("/&[a-zA-Z]*;/", $esc)){
    if (isset($html_esc[$esc])) $char = $html_esc[$esc];
    else $char = " ";
  }
  elseif (preg_match ("/&#([0-9]*);/", $esc, $matches)) $char = chr($matches[1]);
  elseif (preg_match ("/&#x([0-9a-fA-F]*);/", $esc, $matches)) $char = chr(hexdec($matches[1]));
  return $char;
}

# </SPIDER FUNCTIONS>
# ----------------------------------------------------------------------

?>