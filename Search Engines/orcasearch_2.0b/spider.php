<?php /* ***** Orca Search - Spidering Engine ***********************
* Orca Search v2.0b - Copyright (C) 2005 GreyWyvern
*  A robust auto-spidering search engine for single/multiple sites
* 
* See the readme.txt file for installation instructions.
****************************************************************** */

include "config.php";

/* *************************************************************** */
/* ***** Functions *********************************************** */
function add2Queue($uri, $referer = "") {
  global $spData, $vData, $dData;

  $uri = str_replace("/./", "/", trim($uri));
  $uri = preg_replace("/(?<!:)\/{2,}/", "/", $uri);

  $turi = parse_url($uri);
  if ($turi['scheme'] != "http") return "";
  if (isset($turi['port'])) $turi['host'] .= ":".$turi['port'];

  $turi['path'] = preg_replace("/^(\/\.\.)+/", "", $turi['path']);
  while (strpos($turi['path'], "../") !== false) {
    $turi['path'] = preg_replace("/[^\/]+?\/\.\.\//", "", $turi['path']);
    $turi['path'] = preg_replace("/^\/\.\.\//", "/", $turi['path']);
  }

  $uri = "http://{$turi['host']}{$turi['path']}".((isset($turi['query'])) ? "?{$turi['query']}" : "");

  if ($uri == $vData['sp.pathto']) return "";
  if (isset($spData['queue'][$uri])) return "";
  if (in_array($uri, $spData['scanned'])) return "";
  if (!in_array($turi['host'], $spData['allowedDomains'])) return "";

  if (isBlocked($uri)) {
    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `status`='Blocked', `body`='' WHERE `uri`='".addslashes($uri)."';");
    if (mysql_affected_rows()) $spData['stats']['Blocked']++;
    return "";
  }

  $spData['queue'][$uri] = $referer;
  return $uri;
}

function isBlocked($uri) {
  global $spData;

  $foo = (count($spData['onlySpider'])) ? true : false;
  foreach ($spData['noSpider'] as $noSpider) if (preg_match("/{$noSpider}/i", $uri)) return true;
  foreach ($spData['robotsCancel'] as $robotsCancel) if (strpos($uri, $robotsCancel) !== false) return true;
  foreach ($spData['onlySpider'] as $onlySpider) {
    if (preg_match("/{$onlySpider}/i", $uri)) {
      $foo = false;
      break;
    }
  }
  return $foo;
}

function spiderError($errno, $errstr, $errfile, $errline) {
  global $page, $_LOG, $vData, $_LANG;

  set_vData("sp.lock", "false");

  $merror = "";
  if ($merror = mysql_error()) $merror = "<br />\nMySQL error: $merror";

  $errtxt = <<<ERR
<br />
{$_LANG['000q6']} {$page->uri}<br />
{$_LANG['000q7']}<br />
{$_LANG['000q8']}: $errno<br />
{$_LANG['000q9']}: $errstr<br />
{$_LANG['000qa']}: $errline$merror<br />
ERR;

  if ($_SERVER['REQUEST_METHOD'] == "CRON") {
    echo strip_tags($errtxt);
  } else { ?> 
    <style type="text/css">form#canceller input { display:none; }</style>
    <?php echo $errtxt; ?> 
    <hr />
    <a href="<?php echo htmlspecialchars($_POST['linkback']); ?>" id="goback"><?php echo $_LANG['000q1']; ?></a>
    </body></html><?php

    if ($vData['sp.email']) mail($vData['sp.email'], "{$_LANG['000q5']}: {$vData['sp.pathto']}", implode("\n", $_LOG).strip_tags($errtxt));
  }
  exit();
}

function parseHTMLTag($tag, $debug = false) {
  $output = array("closing" => false);
  $loaf = $tag = trim($tag);

  if ($tag{0} == "<" && $tag{strlen($tag) - 1} == ">") {
    $tag = preg_replace(array("/^<\s+\//","/\s*=\s*/"), array("</", "="), $tag);
    if ($tag{1} == "/") $output['closing'] = true;
    str_replace(array("\x05", "\x06"), "", $tag);

    preg_match("/^<\/?([\w\-]+?)(\s+|>)/", $tag, $element);
    if (isset($element[1]) && $element[1]) {
      $output['element'] = $element[1];
      $loaf = preg_replace("/\s*\/?>$/", "", substr($loaf, strlen($element[0])));

      if (strlen($loaf) >= 1) {
        preg_match_all("/=\s*('[^']*')/", $loaf, $qsin);
        $loaf = preg_replace("/=\s*('[^']*')/", "=\x05", $loaf);
        $qsin = $qsin[1];
        array_unshift($qsin, "");
        reset($qsin);

        preg_match_all("/=\s*(\"[^\"]*\")/", $loaf, $qdub);
        $loaf = preg_replace("/=\s*(\"[^\"]*\")/", "=\x06", $loaf);
        $qdub = $qdub[1];
        array_unshift($qdub, "");
        reset($qdub);

        $loaf = preg_replace(array("/\s*=\s*/", "/\s\s+/"), array("=", " "), $loaf);
        $loaf = explode(" ", $loaf);
        foreach ($loaf as $slice) {
          $slice = explode("=", $slice, 2);
          if (isset($slice[0]) && preg_match("/^[\w\-]+$/", $slice[0])) {
            if (count($slice) == 2) {
              $slice[1] = preg_replace(array("/^\x05$/e", "/\x05/e"), array('trim(next($qsin), "\'");', 'next($qsin);'), $slice[1]);
              $slice[1] = preg_replace(array("/^\x06$/e", "/\x06/e"), array('trim(next($qdub), "\"");', 'next($qdub);'), $slice[1]);
              $output[strtolower($slice[0])] = $slice[1];
            } else if (count($slice) == 1) $output[$slice[0]] = true;
          } else if ($debug) trigger_error("parseHTMLTag: Invalid attribute name ".htmlspecialchars($slice[0]));
        }
      }
      return $output;

    } else if ($debug) trigger_error("parseHTMLTag: Invalid element name");
  } else if ($debug) trigger_error("parseHTMLTag: Input does not begin or end with angle brackets");
  return false;
}

function nonPrep($_) {
  $_ = preg_replace("/&#(\d{2,5});/e", "chr($1);", $_);
  $_ = str_replace(array("&quot;", "&lt;", "&gt;", "&amp;"), array("\"", "<", ">", "&"), $_);
  return $_;
}


/* *************************************************************** */
/* ***** Classes ************************************************* */
class Resource {
  var $uri         = "";
  var $referer     = "";
  var $gzip        = false;
  var $md5         = "";
  var $html        = false;
  var $title       = "";
  var $body        = "";
  var $links       = array();
  var $keywords    = "";
  var $description = "";
  var $wtags       = "";
  var $changefreq  = "always";
  var $charset     = "-";
  var $mimetype    = "";
  var $metatags    = array();
  var $isnew       = "false";
  var $lastmod     = 0;
  var $status      = "";
  var $nofollow    = false;
  var $noindex     = false;
  var $refresh     = "";
  var $parsed      = array();


  function Resource($uri, $referer) {
    $this->uri = $uri;
    $this->referer = $referer;
    $this->setBase($this->uri);
    $this->lastmod = time();
  }

  function setStatus($status) {
    global $dData, $spData;

    $bodyblow = (in_array($status, array("Blocked", "Not Found", "Unread"))) ? ", `body`=''" : "";
    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `status`='$status'$bodyblow WHERE `uri`='{$this->uri}';");
    if (mysql_affected_rows()) $spData['stats'][$status]++;
  }

  function getMetatags($html) {
    preg_match("/<head.*?\/head>/is", $this->body, $headtag);
    if (isset($headtag[0])) {
      preg_match_all("/<meta\s[^>]+>/i", $headtag[0], $this->metatags);
      if (isset($this->metatags[0])) {
        $this->metatags = $this->metatags[0];

        while (list($key, $value) = each($this->metatags)) {
          $value = $this->metatags[$key] = parseHTMLTag($value);

          if (isset($value['content'])) {
            if (isset($value['http-equiv'])) {
              switch (strtolower($value['http-equiv'])) {
                case "refresh":
                  preg_match("/http:\/\/.+;?/", $value['content'], $refresh);
                  if (isset($refresh[0])) $this->refresh = $refresh[0];
                  break;
                case "content-type":
                  preg_match("/charset=([\w\-]+)/", $value['content'], $charset);
                  if (isset($charset[1])) $this->charset = strtoupper($charset[1]);
                  break;
              }
            } else if (isset($value['name'])) {
              if (strtolower($value['name']) == "robots" || strtolower($value['name']) == "orcaspider") {
                if (strpos($value['content'], "nofollow") !== false) $this->nofollow = true;
                if (strpos($value['content'], "noindex") !== false) $this->noindex = true;
              }
            }
          }
        }
      }
    }
  }

  function setBase($_) {
    global $dData;

    $this->parsed = parse_url($_);
    if (!isset($this->parsed['path'])) {
      $this->parsed['path'] = "/";
      if ($this->uri{strlen($this->uri) - 1} != "/") {
        $update = mysql_query("UPDATE `{$dData['tablename']}` SET `uri`=CONCAT(`uri`,'/') WHERE `uri`='{$this->uri}';");
        $this->uri .= "/";
      }
    }
    $this->parsed['dir'] = dirname2($this->parsed['path']);
    $this->parsed['base'] = basename($this->parsed['path']);
    $this->parsed['full'] = $this->parsed['path'].((isset($this->parsed['query'])) ? "?{$this->parsed['query']}" : "");
    $this->parsed['hostport'] = $this->parsed['host'].((isset($this->parsed['port'])) ? ":".$this->parsed['port'] : "");
    if (!isset($this->parsed['port'])) $this->parsed['port'] = "80";
  }

  function mysqlPrep() {
    $this->title = addslashes(stripslashes(trim($this->title)));
    $this->body = addslashes(stripslashes(trim($this->body)));
    $this->keywords = addslashes(stripslashes(trim($this->keywords)));
    $this->description = addslashes(stripslashes(trim($this->description)));
    $this->wtags = addslashes(stripslashes(trim($this->wtags)));
  }
}


/* *************************************************************** */
/* ***** Import Language File ************************************ */
if ($langfile = @fopen("lang.txt", "r")) {
  while (!feof($langfile)) {
    $line = fgets($langfile);
    if (strpos($line, "=") && $line{0} != "#") {
      $line = explode("=", $line, 2);
      $_LANG[$line[0]] = str_replace("\"", "&quot;", rtrim($line[1]));
    }
  }
  fclose($langfile);
} else die("Unable to load language file");


/* *************************************************************** */
/* ***** Setup *************************************************** */
header("OrcaScript: Search_Spider");

if (isset($_SERVER['REQUEST_METHOD'])) {
  switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
      if (!isset($_POST['key']) || $_POST['key'] != $vData['c.spkey']) {
        echo "{$_LANG['000p0']}<br />\n";
        if (isset($_POST['linkback'])) { ?> 
          <a href="<?php echo htmlspecialchars($_POST['linkback']); ?>"><?php echo $_LANG['000p1']; ?></a>
        <?php }
        exit();
      } else set_vData("c.spkey", "");
      break;
    case "GET":
      if (!isset($_SERVER['QUERY_STRING']) || $_SERVER['QUERY_STRING'] != $vData['s.spkey']) {
        echo $_LANG['000p2'];
        exit();
      }
      break;
    case "HEAD":
    default:
      exit();
  }
} else if ($vData['sp.cron'] == "false") {
  echo "Script: {$dData['userAgent']} - Spider\n";
  echo $_LANG['000p3'];
  exit();
} else $_SERVER['REQUEST_METHOD'] = "CRON";

if ($vData['sp.cron'] == "true") {
  $_SERVER['REQUEST_METHOD'] = "CRON";
  ob_start();
}

/* ***** Spider Data ********************************************* */
$spData['acceptTypes'] = array_filter(array_map("trim", explode("\n", $vData['sp.mimetypes'])));
$spData['allowedDomains'] = array_filter(array_map("trim", explode("\n", $vData['sp.domains'])));
$spData['removeTags'] = array_filter(array_map("trim", explode(" ", $vData['sp.remtags'])));
$spData['weightedTags'] = array_filter(array_map("trim", explode(" ", $vData['s.weightedtags'])));
$spData['onlySpider'] = array_filter(array_map("pquote", explode("\n", $vData['sp.require'])));
$spData['noSpider'] = array_filter(array_map("pquote", explode("\n", $vData['sp.ignore'])));
$spData['titleStrip'] = array_filter(array_map("pquote", explode("\n", $vData['sp.remtitle'])));
$spData['autoCat'] = array_filter(array_map("trim", explode("\n", $vData['sp.autocat'])));
$spData['ignoreExtensions'] = array_map("trim", explode(" ", $vData['sp.extensions']));
$spData['robotsCancel'] = array();
$spData['allDomains'] = $spData['allowedDomains'];
$spData['starters'] = array_map("trim", array_values(explode("\n", $vData['sp.start'])));
foreach ($spData['starters'] as $starter) {
  if ($starter != "http://") {
    $getInit = parse_url($starter);
    if (isset($getInit['host'])) {
      if (isset($getInit['port'])) $getInit['host'] .= ":".$getInit['port'];
      if (!in_array($getInit['host'], $spData['allDomains'])) $spData['allDomains'][] = $getInit['host'];
    }
  }
}
foreach($spData['ignoreExtensions'] as $ignoreExtensions) $spData['noSpider'][] = "\.".preg_quote($ignoreExtensions)."(\?|$)";
$spData['acceptHeader'] = implode(", ", $spData['acceptTypes']);
$spData['scanned'] = array();
$spData['linkRegexp'] = array(
  "/<a[^>]*?\shref=[\"']?([^\"'>\s]+)/si",
  "/<link[^>]*?\shref=[\"']?([^\"'>\s]+)/si",
  "/<area[^>]*?\shref=[\"']?([^\"'>\s]+)/si",
  "/<frame[^>]*?\ssrc=[\"']?([^\"'>\s]+)/si",
  "/<iframe[^>]*?\ssrc=[\"']?([^\"'>\s]+)/si"
);
$spData['stats'] = array("New" => 0, "Updated" => 0, "Not Found" => 0, "Orphan" => 0, "Blocked" => 0, "Unread" => 0);
$spData['cleanup'] = false;
$spData['alldata'] = 0;
$spData['timelimit'] = time();


/* ***** Execution Timer ***************************************** */
$_TIMER = array(
  "__log" => $dData['now'],  // Time script began
  "Initx" => 0,  // Script setup and initiation
  "Robot" => 0,  // Robots.txt loading and parsing
  "MySQL" => 0,  // MySQL transactions
  "HTTPx" => 0,  // HTTP transactions
  "GZIPx" => 0,  // GZIP Unpacking
  "Links" => 0,  // Link grabbing from HTML
  "WTags" => 0,  // Extract weighted tag text
  "Cntnt" => 0,  // Parse content for archiving
  "Catty" => 0,  // Automatic Categorisation
  "HTMLx" => 0,  // Spider HTML output
  "StMap" => 0   // Sitemap Output
);

function addTime($wish) { /* ***** Record time split ************* */
  global $_TIMER;

  $_TIMER[$wish] += array_sum(explode(" ", microtime())) - $_TIMER['__log'];
  $_TIMER['__log'] = array_sum(explode(" ", microtime()));
}


/* ***** Log Construction **************************************** */
$_LOG = array();
$_LOG[] = "Script: {$dData['userAgent']} - Spider";
$_LOG[] = sprintf($_LANG['000p4'], date("r"), $_SERVER['REQUEST_METHOD']);
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) $_LOG[] = sprintf($_LANG['000p5'], $_SERVER['HTTP_REFERER']);


/* ***** Begin Output ******************************************** */
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title><?php echo $_LANG['000p6']; ?></title>
  <meta http-equiv="Content-type" content="text/html; charset=<?php echo $vData['c.charset']; ?>;" />
  <style type="text/css">
body { background-color:#ffffff; font:normal 100% sans-serif; }
body .warning { color:#ff0000; background-color:transparent; }
body form#canceller { margin:0px; }
body form#canceller input { margin-left:30px; vertical-align:top; }
body h1 { margin:3px 0px; font:bold 130% sans-serif; }
body h2 { margin:2px 0px; font:normal 100% sans-serif; }
body h3 { margin:1px 240px 1px 5px; font:normal 85% monospace; }
body h3.notice { text-indent:-1.2em; padding-left:1em; }
body p { position:absolute; top:0px; left:40%; color:#000000; background-color:#ffffff; font:normal 250% sans-serif; }
body label textarea { margin-bottom:5px; white-space:nowrap; }
body a#goback { display:block; text-align:center; font:bold 125% sans-serif; border:3px outset #dddddd; background-color:#eeeeee; }
body table { position:absolute; top:5px; right:5px; background-color:#ffffff; font-size:80%; }
body table tr th { text-align:right; }
  </style>
</head>
<body>
  <?php set_vData("sp.cancel", "false");
  if (isset($_POST['linkback'])) { ?> 
    <form action="<?php echo htmlspecialchars($_POST['linkback']); ?>" method="post" id="canceller">
      <h1><?php echo $_LANG['000p7']; ?> <input type="submit" name="spider_Cancel" value="<?php echo $_LANG['000p8']; ?>" /></h1>
    </form>
  <?php } else { ?> 
    <h1><?php echo $_LANG['000p7']; ?></h1>
  <?php } ?> 
  <h2><?php echo $_LANG['000p9']; ?><?php
    flush();


    addTime("Initx");  /* ***** Record time split **************** */


    /* ***** Check allDomains robots.txt ************************* */
    foreach ($spData['allDomains'] as $allDomains) {
      $allDomains = explode(":", $allDomains);
      $portIncluded = false;
      if (!isset($allDomains[1])) {
        $allDomains[1] = "80";
      } else $portIncluded = true;
      $rLines = array();
      if ($conn = @fsockopen($allDomains[0], $allDomains[1], $erstr, $errno, 5)) {
        $status = socket_get_status($conn);
        if (!$status['blocked']) socket_set_blocking($conn, true);
        socket_set_timeout($conn, 5);

        fwrite($conn, "GET /robots.txt HTTP/1.0\r\nHost: {$allDomains[0]}\r\nUser-Agent: {$dData['userAgent']}\r\nAccept: text/plain, */*;q=0.1\r\n\r\n");
        $headers = false;
        while (!feof($conn)) {
          if ($headers) {
            $rLines[] = fgets($conn, 1024);
          } else $data = fgets($conn, 1024);

          $status = socket_get_status($conn);
          if ($status['timed_out']) break;

          if (preg_match("/^HTTP\/1\.\d ([^2]\d\d)/i", $data, $code)) break;
          if (preg_match("/^\r?\n$/", $data)) $headers = true;
        }
        fclose($conn);
      }
      $tracker = "off";
      foreach ($rLines as $rline) {
        switch ($tracker) {
          case "on":
            if (preg_match("/^[^#]*?Disallow:(.*?)($|#)/i", $rline, $match)) {
              $match[1] = trim($match[1]);
              if ($match[1] && $match[1]{0} == "/") $spData['robotsCancel'][] = $allDomains[0].(($portIncluded) ? ":{$allDomains[1]}" : "").$match[1];
            }
            if (preg_match("/^[^#]*?User-agent:/i", $rline)) $tracker = "end";
            break;
          case "off":
            if (preg_match("/^[^#]*?User-agent:\s*(\*|orcaspider)[#\s\n\r]/i", $rline)) $tracker = "begin";
            break;
          case "begin":
            if (preg_match("/^[^#]*?Disallow:(.*?)($|#)/i", $rline, $match)) {
              $tracker = "on";
              $match[1] = trim($match[1]);
              if ($match[1] && $match[1]{0} == "/") $spData['robotsCancel'][] = $allDomains[0].(($portIncluded) ? ":{$allDomains[1]}" : "").$match[1];
            }
            break;
          case "end":
        }
      }
    }


    addTime("Robot");  /* ***** Record time split **************** */


  ?> <?php echo $_LANG['000pa']; ?></h2>
  <?php flush();

  if (isset($_POST['spider_Force'])) set_vData("sp.lock", "false");

  if ($vData['sp.lock'] == "true") {
    $_LOG[] = $_LANG['000pb']; 
    $_LOG[] = $_LANG['000pc']; ?> 
    <h2 class="warning"><?php echo $_LANG['000pb']; ?></h2>
    <h2><?php echo $_LANG['000pd']; ?></h2>
    <h2><?php echo $_LANG['000pc']; ?></h2>

  <?php } else {
    ignore_user_abort(true);
    set_error_handler("spiderError");
    set_vData("sp.lock", "true"); ?> 

    <h2><?php echo $_LANG['000pe']; ?></h2>
    <h2><?php echo $_LANG['000pf']; ?></h2>

    <?php /* ***************************************************** */ 
    /* ***** Begin Spider **************************************** */
    flush();

    foreach ($spData['starters'] as $starter) add2Queue($starter);
    if (count($spData['queue'])) {
      $update = mysql_query("UPDATE `{$dData['tablename']}` SET `new`='false';");


      addTime("Initx");  /* ***** Record time split ************** */

      while (1) {
        while (count($spData['queue'])) {

          $select = mysql_fetch_assoc(mysql_query("SELECT `sp.cancel` FROM `{$dData['tablevars']}` LIMIT 1;"));
          if ($select['sp.cancel'] == "true") {
            $_LOG[] = $_LANG['000pg'];
            break;
          }

          if (count($spData['scanned']) >= $vData['sp.pagelimit']) trigger_error("Page limit reached ({$vData['sp.pagelimit']})", E_USER_WARNING);

          reset($spData['queue']);
          list($uri, $referer) = each($spData['queue']);
          $page = new Resource($uri, $referer);
          array_shift($spData['queue']);

          $spData['scanned'][] = $page->uri;


          if ($conn = @fsockopen($page->parsed['host'], $page->parsed['port'], $erstr, $errno, 5)) {
            $status = socket_get_status($conn);
            if (!$status['blocked']) socket_set_blocking($conn, true);
            socket_set_timeout($conn, 5);

            fwrite($conn, "GET {$page->parsed['full']} HTTP/1.0\r\nHost: {$page->parsed['hostport']}\r\nUser-Agent: {$dData['userAgent']}\r\nAccept: {$spData['acceptHeader']}, */*;q=0.1\r\nAccept-Encoding: gzip\r\n".(($page->referer) ? "Referer: {$page->referer}\r\n": "")."\r\n");
            while (!feof($conn)) {
              $data = fgets($conn, 1024);
              $status = socket_get_status($conn);

              if ($status['timed_out']) {
                echo "<h3 class=\"notice warning\">&bull; ", sprintf($_LANG['000qb'], $page->uri), "</h3>\n";
                $_LOG[] = sprintf($_LANG['000qb'], $page->uri);

                $page->setStatus("Unread");
                break;
              }

              $spData['alldata'] += strlen($data);

              if (preg_match("/^OrcaScript: Search/", $data)) {
                $page->setStatus("Blocked");
                break;
              }

              if (preg_match("/^HTTP\/1\.\d ([^23]\d\d)/i", $data, $code)) {
                echo "<h3 class=\"notice warning\">&bull; ", sprintf($_LANG['000ph'], $code[1], $page->uri, $page->referer), "</h3>\n";
                $_LOG[] = sprintf($_LANG['000ph'], $code[1], $page->uri, $page->referer);

                $page->setStatus("Not Found");
                break;
              }

              if (preg_match("/^Location:\s*([^\r\n]*?)[\r\n]/i", $data, $location)) {
                echo "<h3 class=\"notice\">&bull; ", sprintf($_LANG['000pi'], $page->uri, $location[1], $page->referer), "</h3>\n";
                $_LOG[] = sprintf($_LANG['000pi'], $page->uri, $location[1], $page->referer);

                $page->setStatus("Unread");
                list($newbump) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `uri`='{$location[1]}';"));
                if (!$newbump) {
                  if ($newuri = add2Queue($location[1], $page->uri)) {
                    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `uri`='$newuri' WHERE `uri`='{$page->uri}';");
                    if (mysql_affected_rows()) {
                      echo "<h3 class=\"notice\">--&gt; ", sprintf($_LANG['000pj'], $page->uri), "</h3>\n";
                      $_LOG[] = "--> ".sprintf($_LANG['000pj'], $page->uri);
                    }
                  }
                } else {
                  $delete = mysql_query("DELETE FROM `{$dData['tablename']}` WHERE `uri`='{$page->uri}';");
                  if (mysql_affected_rows()) {
                    echo "<h3 class=\"notice\">--&gt; ", sprintf($_LANG['000pk'], $page->uri), "</h3>\n";
                    $_LOG[] = "--> ".sprintf($_LANG['000pk'], $page->uri);
                  }
                }
                break;
              }

              if (preg_match("/^Content-Encoding:\s?gzip/", $data)) $page->gzip = true;

              if (preg_match("/^Content-Type:\s*([^;\r\n]+?)[\s;\r\n]/i", $data, $mime)) {
                if (!in_array($mime[1], $spData['acceptTypes'])) break;
                $page->mimetype = $mime[1];
                if (preg_match("/charset=\s*([^;\r\n]+?)[\s;\r\n]/i", $data, $charset))
                  $page->charset = strtoupper($charset[1]);
              }

              if (preg_match("/^\r?\n$/", $data)) {
                if ($spData['cleanup']) $page->setStatus("Orphan");
                while (!feof($conn)) $page->body .= fgets($conn, 1024);
                $spData['alldata'] += strlen($page->body);
              }

            }
            fclose($conn);

          } else {
            echo "<h3 class=\"notice warning\">&bull; $erstr</h3>\n";
            $_LOG[] = $erstr;
          
          } // else trigger_error($erstr, $errno);


          addTime("HTTPx");  /* ***** Record time split ************ */


          if (time() - $spData['timelimit'] >= 20) {
            set_time_limit(30);
            $spData['timelimit'] = time();
          }
          set_vData("sp.progress", time());
          echo "<p>", count($spData['scanned']), " / ", count($spData['scanned']) + count($spData['queue']), "</p>\n";
          flush();


          addTime("HTMLx");  /* ***** Record time split ************ */


          if ($page->body) {
            $page->md5 = md5($page->body);

            $dbl = mysql_query("SELECT `uri` FROM `{$dData['tablename']}` WHERE `uri`!='".addslashes($page->uri)."' AND `md5`='{$page->md5}';");
            for ($x = 0, $dblskip = false; $x < mysql_num_rows($dbl); $x++) {
              $dbluri = mysql_result($dbl, $x, "uri");
              if (in_array($dbluri, $spData['scanned'])) {
                $dblpsd = parse_url($dbluri);
                $pka = array_search($page->parsed['hostport'], $spData['allowedDomains']);
                $pkb = array_search($dblpsd['host'], $spData['allowedDomains']);

                if ($pka !== false && $pkb !== false) {
                  if ($pka > $pkb || ($pka == $pkb && strlen($page->uri) > strlen($dbluri))) {
                    list($pka, $pkb) = array($page->uri, $dbluri);
                  } else list($pka, $pkb) = array($dbluri, $page->uri);

                  $delete = mysql_query("DELETE FROM `{$dData['tablename']}` WHERE `uri`='".addslashes($pka)."';");
                  if (mysql_affected_rows()) {
                    echo "<h3 class=\"notice\">&bull; ", sprintf($_LANG['000pl'], $pka, $pkb), "</h3>\n";
                    $_LOG[] = sprintf($_LANG['000pl'], $pka, $pkb);
                  } else {
                    echo "<h3 class=\"notice\">&bull; ", sprintf($_LANG['000pm'], $pka, $pkb), "</h3>\n";
                    $_LOG[] = sprintf($_LANG['000pm'], $pka, $pkb);
                  }
                  if ($pka == $page->uri) $dblskip = true;
                }
              }
            }
            if ($dblskip) continue;

            $exist = mysql_query("SELECT `md5`, `status`, `links`, `sm.lastmod`, `sm.changefreq` FROM `{$dData['tablename']}` WHERE `uri`='".addslashes($page->uri)."' LIMIT 1;");
            if (mysql_num_rows($exist)) {
              $_EXISTS = mysql_fetch_assoc($exist);
              $page->changefreq = $_EXISTS['sm.changefreq'];
              $page->isnew = (strlen($_EXISTS['md5']) < 32 && strpos($page->md5, $_EXISTS['md5']) === 0) ? "false" : "true";
              $page->lastmod = ($page->isnew == "false") ? (int)$_EXISTS['sm.lastmod'] : time();
              if ($vData['sm.changefreq'] == "true") {
                $adjmod = time() - (int)$_EXISTS['sm.lastmod'];
                if ($adjmod <= 2700) $page->changefreq = 'always';
                if ($adjmod > 2700 && $adjmod <= 64800) $page->changefreq = 'hourly';
                if ($adjmod > 64800 && $adjmod <= 432000) $page->changefreq = 'daily';
                if ($adjmod > 432000 && $adjmod <= 2160000) $page->changefreq = 'weekly';
                if ($adjmod > 2160000 && $adjmod <= 21600000) $page->changefreq = 'monthly';
                if ($adjmod > 21600000) $page->changefreq = 'yearly'; 
              }
            } else $_EXISTS = array();


            addTime("MySQL");  /* ***** Record time split ********** */


            if (count($_EXISTS) && $_EXISTS['md5'] == $page->md5 && $_EXISTS['status'] == "OK") {
              $page->links = array_filter(explode("\n", $_EXISTS['links']));
              foreach ($page->links as $link) add2Queue($link, $page->uri);

              if ($vData['sm.changefreq'] == "true")
                $update = mysql_query("UPDATE `{$dData['tablename']}` SET `sm.changefreq`='{$page->changefreq}' WHERE `md5`='{$page->md5}' LIMIT 1;");

            } else {
              if ($page->gzip) $page->body = gzinflate(substr($page->body, 10));


              addTime("GZIPx");  /* ***** Record time split ******** */


              if ($page->mimetype == "text/html" || $page->mimetype == "application/xhtml+xml" || $page->mimetype == "application/xml") {
                $page->html = true;
                $page->body = preg_replace(array("/<!--.*?-->/s", "/<script.*?\/script>/is"), "", $page->body);
                $page->getMetatags($page->body);
                if ($page->refresh) add2Queue($page->refresh, $page->uri);

                if (!$page->nofollow) {
                  preg_match("/<base[^>]+?href=[\"']([^\"'>\s]*)?/i", $page->body, $base);
                  if (isset($base[1])) $page->setBase($base[1]);

                  foreach ($spData['linkRegexp'] as $linkRegexp) {
                    preg_match_all($linkRegexp, $page->body, $links);
                    $links = array_unique($links[1]);

                    foreach ($links as $link) {
                      if ($link && $link{0} != "#" && $link != "http://") {
                        $tlink = parse_url($link);
                        if (isset($tlink['scheme'])) {
                          if ($tlink['scheme'] == "http" && isset($tlink['host']) && isset($tlink['path'])) {
                            if (isset($tlink['port'])) $tlink['host'] .= ":".$tlink['port'];
                            $page->links[] = add2Queue("http://{$tlink['host']}/{$tlink['path']}".((isset($tlink['query'])) ? "?{$tlink['query']}" : ""), $page->uri);
                          }
                        } else if ($link{0} == "/") {
                          $page->links[] = add2Queue("http://{$page->parsed['hostport']}$link", $page->uri);
                        } else $page->links[] = add2Queue("http://{$page->parsed['hostport']}/".(($link{0} == "?") ? $page->parsed['path'] : $page->parsed['dir']."/").$link, $page->uri);
                      }
                    }
                  }
                }
              }


              addTime("Links");  /* ***** Record time split ******** */


              if (!$page->noindex) {
                if ($page->html) {
                  foreach ($spData['weightedTags'] as $weightedTags) {
                    preg_match_all("/<{$weightedTags}[^>]*>(.*?)<\/{$weightedTags}>/si", $page->body, $match);
                    foreach ($match[1] as $mat) {
                      foreach ($spData['removeTags'] as $removeTags) $mat = preg_replace("/<{$removeTags}.*?\/{$removeTags}>/is", "", $mat);
                      $page->wtags .= trim(strip_tags(preg_replace("/(\s|&nbsp;){2,}/", " ", $mat)))." ";
                    }
                  }


                  addTime("WTags");  /* ***** Record time split **** */


                  if (isset($page->metatags) && !count($_EXISTS)) {
                    foreach ($page->metatags as $metatags) {
                      if (isset($metatags['name']) && isset($metatags['content'])) {
                        if ($metatags['name'] == "description") $page->description = $metatags['content'];
                        if ($metatags['name'] == "keywords") $page->keywords = $metatags['content'];
                      }
                    }
                  }

                  $page->title = (preg_match("/<title[^>]*?>([^<]+?)<\/title>/i", $page->body, $match)) ? str_replace(array("\r", "\n"), " ", $match[1]) : "";
                  foreach ($spData['titleStrip'] as $titleStrip) $page->title = preg_replace("/{$titleStrip}/", "", $page->title);

                  foreach ($spData['removeTags'] as $removeTags) $page->body = preg_replace("/<{$removeTags}.*?\/{$removeTags}>/is", "", $page->body);

                  $page->body = strip_tags(str_replace(array("<", ">"), array(" <", "> "), $page->body));
                  $page->body = str_replace("&nbsp;", " ", $page->body);
                  $page->body = preg_replace("/(\s|&nbsp;){2,}/", " ", $page->body);

                  if ($vData['sp.utf8'] == "true") {
                    $page->wtags = allEntities($page->wtags, ENT_QUOTES, true, true);
                    $page->title = allEntities($page->title, ENT_QUOTES, true, true);
                    $page->keywords = allEntities($page->keywords, ENT_QUOTES, true, true);
                    $page->description = allEntities($page->description, ENT_QUOTES, true, true);
                    $page->body = allEntities($page->body, ENT_QUOTES, true, true);
                  } else {
                    $page->wtags = nonPrep($page->wtags);
                    $page->title = nonPrep($page->title);
                    $page->keywords = nonPrep($page->keywords);
                    $page->description = nonPrep($page->description);
                    $page->body = nonPrep($page->body);
                  }

                } else $page->body = preg_replace("/\s{2,}/", " ", $page->body);

                $page->links = trim(implode("\n", array_filter($page->links)));
                $page->status = ($spData['cleanup']) ? "Orphan" : "OK";
                $page->mysqlPrep();


                addTime("Cntnt");  /* ***** Record time split ****** */


                if (count($_EXISTS)) {
                  $update = mysql_query("UPDATE `{$dData['tablename']}` SET `md5`='{$page->md5}', `title`='{$page->title}', `wtags`='{$page->wtags}', `body`='{$page->body}', `links`='{$page->links}', `encoding`='{$page->charset}', `status`='{$page->status}', `new`='{$page->isnew}', `sm.lastmod`={$page->lastmod}, `sm.changefreq`='{$page->changefreq}' WHERE `uri`='".addslashes($page->uri)."';");
                  if (mysql_affected_rows()) $spData['stats']['Updated']++;


                  addTime("MySQL");  /* ***** Record time split **** */


                } else {
                  $category = $vData['sp.defcat'];
                  reset($spData['autoCat']);
                  foreach ($spData['autoCat'] as $autoCat) {
                    $against = "";
                    if (strpos($autoCat, $sep = ":::")) {
                      $against = $page->uri;
                    } else if (strpos($autoCat, $sep = ";;;")) $against = $page->title;
                    if ($against) {
                      $autoCat = array_map("trim", explode($sep, $autoCat));
                      $autoCat[1] = ($autoCat[1]{0} != "*") ? preg_quote($autoCat[1], "/") : substr($autoCat[1], 1);
                      if (preg_match("/{$autoCat[1]}/", $against)) {
                        $category = $autoCat[0];
                        break;
                      }
                    }
                  }


                  addTime("Catty");  /* ***** Record time split **** */


                  $insert = mysql_query("INSERT INTO `{$dData['tablename']}` VALUES ('{$page->uri}', '{$page->md5}', '{$page->title}', '$category', '{$page->description}', '{$page->keywords}', '{$page->wtags}', '{$page->body}', '{$page->links}', '{$page->charset}', 'OK', 'false', 'true', 'true', ".time().", '{$page->changefreq}', 0.5);");
                  if (mysql_affected_rows()) $spData['stats']['New']++;


                  addTime("MySQL");  /* ***** Record time split **** */


                }
              } else $page->setStatus("Blocked");
            }
          }
        }

        /* ***** Add Orphans to the queue ************************ */
        if (!$spData['cleanup']) {
          $spData['cleanup'] = true;
          $select = mysql_query("SELECT `uri` FROM `{$dData['tablename']}`;");
          while ($orp = mysql_fetch_assoc($select)) {
            if (!in_array($orp['uri'], $spData['scanned'])) {
              if (isBlocked($orp['uri'])) {
                $update = mysql_query("UPDATE `{$dData['tablename']}` SET `status`='Blocked', `body`='' WHERE `uri`='".addslashes($orp['uri'])."';");
                if (mysql_affected_rows()) $spData['stats']['Blocked']++;
              } else $spData['queue'][$orp['uri']] = "";
            }
          }


          addTime("MySQL");  /* ***** Record time split ********** */


        } else break;
      }

      $optimize = mysql_query("OPTIMIZE TABLE `{$dData['tablename']}`;");
      $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `cache`='';");

      set_vData("sp.time", time());
      set_vData("sp.lock", "false");
      set_vData("sp.lasttime", array_sum(explode(" ", microtime())) - $dData['now']);
      set_vData("sp.alldata", $spData['alldata']);


      addTime("MySQL");  /* ***** Record time split ************** */


      restore_error_handler();

      $_LOG[] = "*** ".sprintf($_LANG['000pn'], count($spData['scanned']))." ***";
      $_LOG[] = "{$_LANG['000po']}: ".sprintf("%01.2f", $vData['sp.lasttime'])."s";
      $_LOG[] = "{$_LANG['000pp']}: ".sprintf("%01.3f", $vData['sp.lasttime'] / count($spData['scanned']))."s";
      $_LOG[] = "{$_LANG['000pq']}: {$spData['stats']['New']}";
      $_LOG[] = "{$_LANG['000pr']}: {$spData['stats']['Updated']}";
      $_LOG[] = "{$_LANG['000ps']}: {$spData['stats']['Not Found']}";
      $_LOG[] = "{$_LANG['000pt']}: {$spData['stats']['Orphan']}";
      $_LOG[] = "{$_LANG['000pu']}: {$spData['stats']['Blocked']}";
      /* ***** End Spider **************************************** */


      /* ***** Begin Sitemap ************************************* */
      if ($vData['sm.enable'] == "true") { ?> 
        <h2><?php echo $_LANG['000pv']; ?></h2>
        <?php flush();

        $cData['smnf'] = true;
        $cData['smnw'] = true;
        if (file_exists($vData['sm.pathto'])) {
          $cData['smnf'] = false;
          if (is_writable($vData['sm.pathto'])) $cData['smnw'] = false;
        }
        if ($cData['smnf'] || $cData['smnw']) { ?> 
          <h2 class="warning"><?php echo $_LANG['000pw']; ?></h2>
          <?php $_LOG[] = $_LANG['000pw'];

        } else {
          ob_start();

          if ($vData['sm.unlisted'] != "true") {
            $lq = ($vData['s.orphans'] == "show") ? " AND (`status`='OK' OR `status`='Orphan')" : " AND `status`='OK'";

            $nq = "";
            $sData['noSearch'] = array_filter(array_map("trim", explode("\n", $vData['s.ignore'])));
            foreach ($sData['noSearch'] as $noSearch)
              $nq .= " AND `uri` NOT ".(($noSearch{0} == "*") ? "REGEXP '".substr($noSearch, 1)."'": " LIKE '%{$noSearch}%'");

            $qadd = " AND `unlist`!='true'{$lq}{$nq}"; 
          }

          $sitemap = mysql_unbuffered_query("SELECT `uri`, `sm.lastmod`, `sm.changefreq`, `sm.priority` FROM `{$dData['tablename']}` WHERE `sm.list`='true' AND `uri` LIKE '%//{$vData['sm.domain']}/%'$qadd;");

          echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
          ?><urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
<?php while ($smrow = mysql_fetch_assoc($sitemap)) { ?>  <url>
    <loc><?php echo $smrow['uri']; ?></loc>
    <lastmod><?php echo date("Y-m-d", $smrow['sm.lastmod']); ?></lastmod>
    <changefreq><?php echo $smrow['sm.changefreq']; ?></changefreq><?php
    if ($smrow['sm.priority'] != 0.5) { ?> 
    <priority><?php echo $smrow['sm.priority']; ?></priority><?php
    } ?> 
  </url>
<?php } ?></urlset><?php

          $sitemap = ob_get_contents();
          ob_end_clean();

          if ($dData['zlib'] && $vData['sm.gzip'] == "true") {
            $shell = gzopen($vData['sm.pathto'], "w");
            gzwrite($shell, $sitemap);
            gzclose($shell);
          } else {
            $shell = fopen($vData['sm.pathto'], "w");
            fwrite($shell, $sitemap);
            fclose($shell);
          }
          $_LOG[] = "*** {$_LANG['000px']} ***";
        }
      }
      /* ***** End Sitemap *************************************** */
      /* ********************************************************* */ 


      addTime("StMap");  /* ***** Record time split ************** */


      ?><h1><?php echo $_LANG['000py']; ?></h1>

      <style type="text/css">form#canceller input { display:none; }</style>

      <label><?php echo $_LANG['000pz']; ?>:<br />
        <textarea rows="15" cols="60" readonly="readonly" ><?php
          foreach ($spData['scanned'] as $scanned) echo "\n", str_replace("http://{$_SERVER['HTTP_HOST']}{$dData['thisLocation']}", "/", $scanned);
        ?></textarea>
      </label>

      <table cellspacing="0" border="1" cellpadding="3">
        <tr>
          <th><?php echo $_LANG['000pq']; ?></th>
          <td><?php echo $spData['stats']['New']; ?></td>
        </tr>
        <tr>
          <th><?php echo $_LANG['000pr']; ?></th>
          <td><?php echo $spData['stats']['Updated']; ?></td>
        </tr>
        <tr>
          <th><?php echo $_LANG['000ps']; ?></th>
          <td><?php echo $spData['stats']['Not Found']; ?></td>
        </tr>
        <tr>
          <th><?php echo $_LANG['000pt']; ?></th>
          <td><?php echo $spData['stats']['Orphan']; ?></td>
        </tr>
        <tr>
          <th><?php echo $_LANG['000pu']; ?></th>
          <td><?php echo $spData['stats']['Blocked']; ?></td>
        </tr>
        <tr>
          <th><?php echo $_LANG['000po']; ?></th>
          <td><?php printf("%01.2f", $vData['sp.lasttime']); ?>s</td>
        </tr>
        <tr>
          <th><?php echo $_LANG['000pp']; ?></th>
          <td><?php printf("%01.3f", $vData['sp.lasttime'] / count($spData['scanned'])); ?>s</td>
        </tr>
      </table><?php
    } else { 

      set_vData("sp.lock", "false"); ?> 
      <h2 class="warning"><?php echo $_LANG['000q0']; ?></h1>
    <?php }
  } ?> 

  <?php if ($_SERVER['REQUEST_METHOD'] != "CRON" && isset($_POST['linkback'])) { ?> 
    <a href="<?php echo htmlspecialchars($_POST['linkback']); ?>" id="goback"><?php echo $_LANG['000q1']; ?></a>
  <?php } ?> 

  <hr />

  <h1><?php echo $_LANG['000q2']; ?></h1>
  <pre><?php


    addTime("HTMLx");  /* ***** Record time split **************** */


    echo "\n{$_LANG['000q3']}: ";
      print_r($spData['robotsCancel']);
    echo "{$_LANG['000q4']}: ";
      print_r($_TIMER);
  ?></pre>

</body>
</html><?php

if ($_SERVER['REQUEST_METHOD'] == "CRON") {
  ob_end_clean();
  echo implode("\n", $_LOG);

} else if ($vData['sp.email']) {
  $headers = "From: Orca Search Spider <{$_SERVER['SERVER_ADMIN']}>\r\n";
  $headers .= "X-Sender: <{$_SERVER['SERVER_ADMIN']}>\r\n";
  $headers .= "Return-Path: <{$_SERVER['SERVER_ADMIN']}>\r\n";
  $headers .= "Errors-To: <{$_SERVER['SERVER_ADMIN']}>\r\n";
  $headers .= "X-Mailer: {$dData['userAgent']}\r\n";
  $headers .= "X-Priority: 3\r\n";
  $headers .= "Date: ".date("r")."\r\n";
  $headers .= "Content-type:text/plain; charset={$vData['c.charset']}";

  @mail($vData['sp.email'], "{$_LANG['000q5']}: {$vData['sp.pathto']}", implode("\n", $_LOG), $headers);
}

?>