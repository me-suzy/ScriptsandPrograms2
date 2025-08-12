<?php /* ***** Orca Search - Configuration **************************
* Orca Search v2.0b - Copyright (C) 2005 GreyWyvern
*  A robust auto-spidering search engine for single/multiple sites
* 
* See the readme.txt file for installation instructions.
****************************************************************** */

/* ***** MySQL *************************************************** */
$dData['hostname'] = "hostname";
$dData['username'] = "username";
$dData['password'] = "password";
$dData['database'] = "database";
$dData['tablename'] = "orcasearch";


/* ***** Admin *************************************************** */
$dData['adminName'] = "admin";
$dData['adminPass'] = "password";


/* *************************************************************** */
/* ***** Functions *********************************************** */
function pquote($input) {
  if ($input = trim($input)) $input = ($input{0} != "*") ? preg_quote($input, "/") : str_replace('/', '\/', substr($input, 1));
  return $input;
}

function dirname2($path) {
  $path = trim($path);
  return ($path{strlen($path) - 1} == "/") ? $path : dirname($path);
}

function set_vData($field, $input) {
  global $dData, $vData;

  if (!isset($vData[$field]) || $vData[$field] != $input) { 
    mysql_query("UPDATE `{$dData['tablevars']}` SET `$field`='".addslashes($input)."';");
    if (mysql_affected_rows()) {
      $recap = mysql_fetch_assoc(mysql_query("SELECT `$field` FROM `{$dData['tablevars']}`;"));
      $vData[$field] = $recap[$field];
      return true;
    }
  }
  return false;
}

function is_utf8($string) {
  if (preg_match("/^([\x09\x0A\x0D\x20-\x7E]|[\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})*$/", $string)) return true;
  return false;
}

function allEntities($input, $quotes, $reverse = false, $utf8 = false) {
  static $trans = array();

  if (!count($trans)) {
    $trans = array_flip(get_html_translation_table(HTML_ENTITIES));
    while (list($key, $value) = each($trans)) $trans[$key] = array($value, utf8_encode($value));

    // Additional entities
    $trans['&ndash;'] = array("\x96", "–");
    $trans['&trade;'] = array("\x99", "™");
    $trans['&Omega;'] = array(null, "Ω");

    switch ($quotes) {
      case ENT_COMPAT: break;
      case ENT_QUOTES:
        $trans['&#39;'] = array("'", "'");
        break;
      case ENT_NOQUOTES:
        unset($trans['&quot;']);
        break;
    }

    // Move &amp; to the front
    // uksort($trans, create_function('$k1, $k2', 'return ($k1 == "&amp;") ? -1 : 1;'));
  }

  $utf8 = ($utf8) ? 1 : 0;
  $transfil = array_filter($trans, create_function('$v', 'return ($v['.$utf8.']) ? true: false;'));

  if ($reverse) {
    uksort($trans, create_function('$k1, $k2', 'return ($k1 == "&amp;") ? 1 : -1;'));
    if ($utf8) $input = preg_replace("/&#(\d{2,7});/e", "unichr($1);", $input);
    while (list($key, $value) = each($transfil)) $input = str_replace($key, $value[1], $input);
  } else {
    uksort($trans, create_function('$k1, $k2', 'return ($k1 == "&amp;") ? -1 : 1;'));
    while (list($key, $value) = each($transfil)) $input = str_replace($value[1], $key, $input);
  }

  return $input;
}

function unichr($dec) { 
  if ($dec < 128) { 
    $utf = chr($dec); 
  } else if ($dec < 2048) { 
    $utf = chr(192 + (($dec - ($dec % 64)) / 64)); 
    $utf .= chr(128 + ($dec % 64)); 
  } else if ($dec < 65536) { 
    $utf = chr(224 + (($dec - ($dec % 4096)) / 4096)); 
    $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64)); 
    $utf .= chr(128 + ($dec % 64)); 
  } else if ($dec < 2097152) {
    $utf = chr(240 + (($dec - ($dec % 262144)) / 262144));
    $utf .= chr(128 + ((($dec % 262144) - ($dec % 4096)) / 4096));
    $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64)); 
    $utf .= chr(128 + ($dec % 64)); 
  } else return "";
  return $utf; 
}

function clearCache() {
  global $dData;

  $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `cache`='';");
  $optimize = mysql_query("OPTIMIZE TABLE `{$dData['tablestat']}`;");
}


/* *************************************************************** */
/* ***** Begin Program ******************************************* */
error_reporting(E_ALL);
$dData['now'] = array_sum(explode(" ", microtime()));

/* ***** Magic Quotes Fix **************************************** */
if (get_magic_quotes_gpc()) {
  $fsmq = create_function('&$mData, $fnSelf', 'if (is_array($mData)) foreach ($mData as $mKey=>$mValue) $fnSelf($mData[$mKey], $fnSelf); else $mData = stripslashes($mData);');
  $fsmq($_POST, $fsmq);
  $fsmq($_GET, $fsmq);
  $fsmq($_ENV, $fsmq);
  $fsmq($_SERVER, $fsmq);
  $fsmq($_COOKIE, $fsmq);
}
set_magic_quotes_runtime(0);

/* ***** MySQL *************************************************** */
$dData['online'] = false;
$dData['tablevars'] = $dData['tablename']."_v";
$dData['tablestat'] = $dData['tablename']."_s";

$db = @mysql_connect($dData['hostname'], $dData['username'], $dData['password']) or $dData['error'] = mysql_error();
if (!isset($dData['error'])) @mysql_select_db($dData['database'], $db) or $dData['error'] = mysql_error();

if (!isset($dData['error'])) {
  $dData['online'] = true;

  $create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tablename']}` (
    `uri` TEXT,
    `md5` TEXT,
    `title` TEXT,
    `category` TEXT,
    `description` TEXT,
    `keywords` TEXT,
    `wtags` TEXT,
    `body` TEXT,
    `links` TEXT,
    `encoding` TINYTEXT,
    `status` ENUM('OK','Not Found','Orphan','Blocked','Unread') DEFAULT 'OK',
    `unlist` ENUM('true','false') DEFAULT 'false',
    `new` ENUM('true','false') DEFAULT 'true',
    `sm.list` ENUM('true','false') DEFAULT 'true',
    `sm.lastmod` INT(11),
    `sm.changefreq` ENUM('always','hourly','daily','weekly','monthly','yearly','never') DEFAULT 'weekly',
    `sm.priority` FLOAT DEFAULT '0.5'
  ) TYPE=MyISAM;") or die("Could not create table: {$dData['tablename']}");

  $create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tablevars']}` (
    `sp.start` TINYTEXT,
    `sp.domains` TEXT,
    `sp.extensions` TEXT,
    `sp.require` TEXT,
    `sp.ignore` TEXT,
    `sp.mimetypes` TEXT,
    `sp.remtags` TEXT,
    `sp.remtitle` TEXT,
    `sp.defcat` TINYTEXT,
    `sp.autocat` TEXT,
    `sp.utf8` ENUM('true','false') DEFAULT 'false',
    `sp.time` INT(11),
    `sp.progress` INT(11),
    `sp.interval` MEDIUMINT(9),
    `sp.pagelimit` MEDIUMINT(9),
    `sp.lock` ENUM('true','false') DEFAULT 'false',
    `sp.cancel` ENUM('true','false') DEFAULT 'false',
    `sp.pathto` TEXT,
    `sp.lasttime` FLOAT,
    `sp.alldata` BIGINT(20),
    `sp.cron` ENUM('true','false') DEFAULT 'false',
    `sp.email` TINYTEXT,

    `s.termlimit` TINYINT(4),
    `s.termlength` TINYINT(4),
    `s.weight` TINYTEXT,
    `s.latinacc` ENUM('true','false') DEFAULT 'false',
    `s.weightedtags` TEXT,
    `s.resultlimit` INT(11),
    `s.pagination` INT(11),
    `s.matchingtext` INT(11),
    `s.ignore` TEXT,
    `s.orphans` ENUM('show','hide') DEFAULT 'hide',
    `s.cachetime` INT(11),
    `s.cachereset` TINYINT(4),
    `s.cachelimit` INT(11),
    `s.cachegzip` ENUM('disabled', 'off', 'on') DEFAULT 'disabled',
    `s.spkey` TINYTEXT,

    `sm.enable` ENUM('true','false') DEFAULT 'false',
    `sm.pathto` TEXT,
    `sm.domain` TINYTEXT,
    `sm.unlisted` ENUM('true','false') DEFAULT 'false',
    `sm.changefreq` ENUM('true','false') DEFAULT 'false',
    `sm.gzip` ENUM('true','false') DEFAULT 'false',

    `c.location` ENUM('List','Search','Spider','Stats','Tools') DEFAULT 'Spider',
    `c.column` ENUM('title','uri') DEFAULT 'uri',
    `c.sortby` ENUM('col1','col2') DEFAULT 'col1',
    `c.pagination` MEDIUMINT(9),
    `c.charset` TINYTEXT,
    `c.logkey` TINYTEXT,
    `c.logtime` INT(11),
    `c.spkey` TINYTEXT,

    `cf.textexclude` TINYTEXT,
    `cf.textmatch` TINYTEXT,
    `cf.category` TINYTEXT,
    `cf.status` ENUM('All', 'OK','Orphan','Blocked','Unread','Unlisted','Not Found') DEFAULT 'All',
    `cf.new` ENUM('true','false') DEFAULT 'false',

    `jw.hide` ENUM('true','false') DEFAULT 'true',
    `jw.key` TINYTEXT,
    `jw.progress` TINYINT(4),
    `jw.memory` INT(11),
    `jw.egg` TEXT,
    `jw.writer` TEXT,
    `jw.remuri` TEXT,
    `jw.extfrom` TEXT,
    `jw.extto` TINYTEXT,
    `jw.index` TEXT,
    `jw.template` TEXT,
    `jw.pagination` TINYINT(4)
  ) TYPE=MyISAM;") or die("Could not create table: {$dData['tablevars']}");

  list($count) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablevars']}`;"));
  if (!$count) {
    $insert = mysql_query("INSERT INTO `{$dData['tablevars']}` VALUES (

      /* ***** Spider ******************************************** */
      'http://{$_SERVER["HTTP_HOST"]}/',
      '{$_SERVER["HTTP_HOST"]}',
      '7z au aiff avi bin bz bz2 cab cda cdr class com css csv doc dll dtd dwg dxf eps exe gif hqx ico image jar jav jfif jpeg jpg js kbd mid moov mov movie mp3 mpeg mpg ocx ogg pdf png pps ppt ps psd qt ra ram rar rm rpm rtf scr sea sit svg swf sys tar.gz tga tgz tif tiff ttf uu uue vob wav xls z zip',
      '',
      '',
      'text/html\ntext/plain\napplication/xhtml+xml\napplication/xml',
      'head noscript style textarea select form',
      '',
      'Main',
      '',
      'false',
      -1,
      -1,
      24,
      2000,
      'false',
      'false',
      'http://{$_SERVER["HTTP_HOST"]}/os2/spider.php',
      -1,
      0,
      'false',
      '',

      /* ***** Search ******************************************** */
      7,
      3,
      '1.3%0.5%2.1%1.9%0%2.5%1.5',
      'false',
      'h1 h2 h3 dt',
      0,
      10,
      300,
      '',
      'hide',
      ".time().",
      15,
      250,
      'disabled',
      '',

      /* ***** Sitemap ******************************************* */
      'false',
      '".rtrim($_SERVER['DOCUMENT_ROOT'], "/")."/sitemap.xml',
      '{$_SERVER["HTTP_HOST"]}',
      'false',
      'false',
      'false',

      /* ***** Control Panel - Misc ****************************** */
      'Spider',
      'uri',
      'col1',
      100,
      'ISO-8859-1',
      '',
      ".(time() - 180).",
      '',

      /* ***** Control Panel - Filters *************************** */
      '',
      '',
      '-',
      'All',
      'false',

      /* ***** JWriter ******************************************* */
      'true',
      '',
      0,
      0,
      '".rtrim($_SERVER['DOCUMENT_ROOT'], "/")."/os2/egg.js',
      'http://{$_SERVER['HTTP_HOST']}/os2/jwriter.php',
      'http://{$_SERVER['HTTP_HOST']}/',
      'asp cfm cgi jhtml jsp php php3 php4 php5 phtml pl shtml',
      'html',
      'index.html',
      '<h3><a href=\"{R_URI}\" title=\"{R_DESCRIPTION}\">{R_TITLE}</a> - <small>{R_CATEGORY}</small></h3>\n<div>\n  <blockquote>\n     <p>\n      {R_MATCH}<br />\n      <cite>{R_URI}</cite> <small>({R_RELEVANCE})</small>\n    </p>\n  </blockquote>\n</div>',
      10
    );");
  }

  $create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tablestat']}` (
    `query` TEXT,
    `hits` INT(11),
    `astyped` TEXT,
    `lasthit` INT(11),
    `cache` LONGBLOB,
    KEY `qk` (`query`(127))
  ) TYPE=MyISAM;") or die("Could not create table: {$dData['tablestat']}");

} else $dData['errno'] = mysql_errno();


/* *************************************************************** */
/* ***** Setup *************************************************** */
if (isset($_SERVER['REQUEST_URI'])) {
  $_SERVER['PHP_SELF'] = preg_replace("/\?.*$/", "", $_SERVER['REQUEST_URI']);
  $dData['thisLocation'] = preg_replace("/\/.*$/", "/", $_SERVER['PHP_SELF']);
} else $dData['thisLocation'] = "/";

$dData['version'] = "2.0b";
$dData['userAgent'] = "OrcaSearch/{$dData['version']} (http://www.greywyvern.com/orca#sear)";
$dData['zlib'] = (function_exists("gzopen")) ? true : false;
$dData['4.3.0'] = (function_exists("mysql_real_escape_string")) ? true : false;

if ($dData['online']) {
  $vData = mysql_fetch_assoc(mysql_query("SELECT * FROM `{$dData['tablevars']}`;"));
  $vData['s.weight'] = explode("%", $vData['s.weight']);

  /* ************************************************************* */
  /* ***** Upgrade to 2.0a *************************************** */
  $show = mysql_query("SHOW COLUMNS FROM `{$dData['tablevars']}`;");
  for ($x = 0, $dData['fields'] = array(); $x < mysql_num_rows($show); $x++) $dData['fields'][] = mysql_result($show, $x, "Field");
  if (!in_array("sp.require", $dData['fields']))
    mysql_query("ALTER TABLE `{$dData['tablevars']}` ADD `sp.require` TEXT AFTER `sp.extensions`;");

  /* ***** Upgrade to 2.0b *************************************** */
  if (count($vData['s.weight']) == 6) {
    array_splice($vData['s.weight'], 4, 0, "0.0");
    $weight = implode("%", $vData['s.weight']);
    if (set_vData("s.weight", $weight)) $vData['s.weight'] = explode("%", $vData['s.weight']);
    clearCache();
  }

  /* ***** End of Upgrades *************************************** */
  /* ***** This section can be safely removed after upgrade ****** */
  /* ************************************************************* */


  if ($vData['s.cachegzip'] == "disabled" && $dData['zlib'] && $dData['4.3.0']) {
    set_vData("s.cachegzip", "off");
    clearCache();
  } else if ($vData['s.cachegzip'] != "disabled" && (!$dData['zlib'] || !$dData['4.3.0'])) {
    set_vData("s.cachegzip", "disabled");
    clearCache();
  }
}

?>