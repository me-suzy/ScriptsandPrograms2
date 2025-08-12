<?php /* ***** Orca Search - Searching Engine ***********************
* Orca Search v2.0b - Copyright (C) 2005 GreyWyvern
*  A robust auto-spidering search engine for single/multiple sites
* 
* See the readme.txt file for installation instructions.
*********************************************************************
*********************************************************************
* Output Documentation
* 
* Search Query:
*  - Only set if an actual query exists, unset otherwise
* 1) $_QUERY array with hash:
*   -> "original" - String
*        => Original query as typed
*   -> "category" - String
*        => Selected category, empty string if none
*   -> "allterms" - Array
*        => Unfiltered terms in no order (includes ignored terms)
*   -> "terms" - Array
*        => Filtered terms in no order (terms actually searched for)
*   -> "sorted" - String
*        => Filtered terms sorted alphabetically (cache index column)
*   -> "and" - Array
*        => All terms which were marked with +
*   -> "not" - Array
*        => All terms which were marked with - or !
*   -> "or" - Array
*        => All terms which were not marked
*   -> "andor" - Array
*        => Combined "or" and "and" arrays stripped of + marks
*
* Search Info:
* 1) $dData['now'] - Float
*   -> *NIX timestamp with microtime() when script started
*   -> Use array_sum(explode(" ", microtime())) - $dData['now']
*       to find execution-time
* 2) $sData['totalRows'] - Integer
*   -> Number of searchable rows indexed
* 3) $sData['categories'] - Array
*   -> List of all categories available from searchable pages
*
* Results:
* 1) If no database connection:
*   -> $dData['online'] = false
*     -> $dData['error'] = MySQL error message (if exists)
*     -> $dData['errno'] = MySQL error code (if exists)
*
* 2) If the database is locked, and there are no cached results:
*   -> $dData['online'] = true;
*   -> $_RESULTS == NULL
* 
* 3) If no results are found:
*   -> $dData['online'] = true
*   -> Empty $_RESULTS array
* 
* 4) If results were found:
*   -> $dData['online'] = true
*   -> Relevance-ordered array $_RESULTS with each item having hash:
*     -> "title" - String
*          => Title of match (or URI if no title)
*     -> "description" - String
*          => Meta (or table-assigned) description
*     -> "category" - String
*          => Assigned category, pre-filtered by $_GET['c']
*     -> "uri" - String
*          => Full URI of match
*     -> "matchURI" - String
*          => Full URI with matching terms highlighted
*     -> "matchText" - String
*          => Collection of matched text from entry
*     -> "relevance" - Float
*          => Relevance score
****************************************************************** */


/* *************************************************************** */
/* ***** Functions *********************************************** */
function mysqlPrep($input, $neg) {
  $input = ltrim($input, "!+-");
  if (strpos($input, " ") !== false) {
    $type = "REGEXP";
    $input = str_replace(" ", '[,.?!]?[ \-][,.?!]?', preg_quote($input));
  } else {
    $type = "LIKE";
    $input = "%$input%";
  }
  return (($neg) ? "NOT " : "")."$type '".addslashes($input)."'";
}

function heapsort(&$ra, $g = false) {
  if (count($ra) <= 1) return;
  array_unshift($ra, "");
  $ir = count($ra) - 1;
  $l = ($ir >> 1) + 1;

  while (1) {
    if ($l <= 1) {
      $rra = $ra[$ir];
      $ra[$ir] = $ra[1];
      if (--$ir == 1) {
        $ra[1] = $rra;
        return array_shift($ra);
      }
    } else $rra = $ra[--$l];
    $i = $l;
    $j = $l << 1;
    while ($j <= $ir) {
      if ($g === false) {
        if (($j < $ir) && ($ra[$j] < $ra[$j + 1])) $j++;
        if ($rra < $ra[$j]) {
          $ra[$i] = $ra[$j];
          $j += ($i = $j);
        } else $j = $ir + 1;
      } else {
        if (($j < $ir) && ($ra[$j][$g] < $ra[$j + 1][$g])) $j++;
        if ($rra[$g] < $ra[$j][$g]) {
          $ra[$i] = $ra[$j];
          $j += ($i = $j);
        } else $j = $ir + 1;
      }
    }
    $ra[$i] = $rra;
  }
}

function addRelevance(&$value, $terms, $multiplier) {
  global $vData;

  $areas = array("title", "body", "keywords", "wtags", "uri");
  $order = array_flip($areas);
  $multi = -1;
  $foundlimit = 3;

  foreach ($terms as $term) {
    $relevance = $value['relevance'];
    $phrase = false;
    $match = array();
    $found = array();

    if (strpos($term, " ")) {
      $term = str_replace(" ", '[,.?!]?[ \-][,.?!]?', preg_quote($term, "/"));
      $phrase = true;
    } else if ($vData['s.latinacc'] == "true") $term = termAccents($term, ($vData['sp.utf8'] == "true") ? true : false);

    foreach ($areas as $area) {
      if ($vData['s.weight'][$order[$area]] > 0) {
        if ($phrase || $vData['s.latinacc'] == "true") {
          preg_match_all("/$term/i", $value[$area], $match[$area]);
          $found[$area] = count($match[$area][0]);
        } else $found[$area] = substr_count(strtolower($value[$area]), strtolower($term));
      } else $found[$area] = 0;
    }

    reset($areas);
    while (list($key, $val) = each($areas))
      $value['relevance'] += $vData['s.weight'][$key] * min($foundlimit, $found[$val]) * $multiplier;
    if ($value['relevance'] > $relevance) $multi++;

    unset($matchtext);
    if ($phrase || $vData['s.latinacc'] == "true") {
      if (isset($match['body'][0][0]) && $firstpos = strpos(strtolower($value['body']), strtolower($match['body'][0][0])))
        $matchtext = substr($value['body'], max(0, $firstpos - 80), 160 + strlen($match['body'][0][0]));
    } else if (($firstpos = strpos(strtolower($value['body']), strtolower($term))) !== false)
      $matchtext = substr($value['body'], max(0, $firstpos - 80), 160 + strlen($term));

    if (isset($matchtext) && ((strlen($value['matchText']) + strlen($matchtext)) < $vData['s.matchingtext']) && !preg_match("/$term/i", $value['matchText'])) {
      if ($vData['sp.utf8'] == "true") {
        $matchtext = preg_replace(array("/^.*?(?=[\xC2-\xDF\xE0-\xF4\s])/s", "/(\xF4[\x80-\x8F]?[\x80-\xBF]?|[\xF1-\xF3][\x80-\xBF]{,2}|\xF0[\x90-\xBF]?[\x80-\xBF]?|\xED[\x80-\x9F]?|[\xE1-\xEC\xEE\xEF][\x80-\xBF]?|\xE0[\xA0-\xBF]?|[\xC2-\xDF]|\w+)$/"), "", $matchtext);
      } else $matchtext = preg_replace(array("/^[^\s]*\s/", "/\s[^\s]*$/"), "", $matchtext);
      $value['matchText'] .= $matchtext." ... ";
    }
  }

  $value['relevance'] *= pow($vData['s.weight'][5], $multi);

  if ($value['matchText']) {
    $value['matchText'] = trim(substr($value['matchText'], 0, strlen($value['matchText']) - 5));
    if (preg_match("/^[^A-Z]/", $value['matchText']{0})) $value['matchText'] = " ... ".$value['matchText'];
    if (preg_match("/[^.?!]/", $value['matchText']{strlen($value['matchText']) - 1})) $value['matchText'] .= " ... ";
    $value['matchText'] = str_replace(array("\n", "\r"), "", $value['matchText']);
  }
}

function termAccents($_, $utf8 = true) {
  if ($utf8) {
    $_ = str_replace(array("AE", "Ae", "aE", "ae"), "(ae|Ã[¦])", $_);
    $_ = str_replace(array("A", "a"), "(a|Ã[ ¡¢£¤¥])", $_);
    $_ = str_replace(array("C", "c"), "(c|Ã[§])", $_);
    $_ = str_replace(array("E", "e"), "(e|Ã[°¨©ª«])", $_);
    $_ = str_replace(array("I", "i"), "(i|Ã[¬­®¯])", $_);
    $_ = str_replace(array("N", "n"), "(n|Ã[±])", $_);
    $_ = str_replace(array("O", "o"), "(o|Ã[²³´µ¶¸])", $_);
    $_ = str_replace(array("S", "s"), "(s|Ã)", $_);
    $_ = str_replace(array("U", "u"), "(u|Ã[º»¼])", $_);
    $_ = str_replace(array("T", "t"), "(t|Ã)", $_);
    $_ = str_replace(array("Y", "y"), "(y|Ã[½¿])", $_);
  } else {
    $_ = str_replace(array("AE", "Ae", "aE", "ae"), "(ae|Æ|æ)", $_);
    $_ = str_replace(array("A", "a"), "[aÀÁÂÃÄÅàáâãäå]", $_);
    $_ = str_replace(array("C", "c"), "[cÇç]", $_);
    $_ = str_replace(array("E", "e"), "[eÐÈÉÊËðèéêë]", $_);
    $_ = str_replace(array("I", "i"), "[iÌÍÎÏìíîï]", $_);
    $_ = str_replace(array("N", "n"), "[nÑñ]", $_);
    $_ = str_replace(array("O", "o"), "[oÒÓÔÕÖØòóôõöø]", $_);
    $_ = str_replace(array("S", "s"), "[sß]", $_);
    $_ = str_replace(array("U", "u"), "[uÙÚÛÜùúûü]", $_);
    $_ = str_replace(array("T", "t"), "[tÞ]", $_);
    $_ = str_replace(array("Y", "y"), "[yÝýÿ]", $_);
  }
  return $_;
}

function outputText($string, $terms) {
  global $vData;

  $string = str_replace(" ... ", "  %%%%%%%%%...%%%%/%%%%  ", $string);
  foreach ($terms as $term) {
    $term = str_replace(" ", '[,.?!]?[ \-][,.?!]?', preg_quote($term, "/"));
    if ($vData['s.latinacc'] == "true") $term = termAccents($term, ($vData['sp.utf8'] == "true") ? true : false);
    $string = preg_replace("/($term)/i", " %%%%%%%%%$1%%%%/%%%% ", $string);
  }

  if ($vData['sp.utf8'] == "true") $string = htmlspecialchars($string, ENT_NOQUOTES);
  $string = str_replace(array(" %%%%%%%%%", "%%%%/%%%% ", "</strong><strong>"), array("<strong>", "</strong>", ""), $string);
  return $string;
}


/* *************************************************************** */
/* ***** Setup *************************************************** */
header("OrcaScript: Search_Engine");

if ($dData['online']) {
  $sData['noSearch'] = array_filter(array_map("trim", explode("\n", $vData['s.ignore'])));

  $sData['lq'] = ($vData['s.orphans'] == "show") ? " AND (`status`='OK' OR `status`='Orphan')" : " AND `status`='OK'";

  $sData['nq'] = "";
  foreach ($sData['noSearch'] as $noSearch)
    $sData['nq'] .= " AND `uri` NOT ".(($noSearch{0} == "*") ? "REGEXP '".substr($noSearch, 1)."'": " LIKE '%{$noSearch}%'");

  list($sData['totalRows']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `unlist`='false'{$sData['lq']}{$sData['nq']};"));
  $_RESULTS = array();

  $sData['categories'] = array();
  $select = mysql_query("SELECT DISTINCT `category` FROM `{$dData['tablename']}` WHERE `unlist`='false'{$sData['lq']}{$sData['nq']};");
  while ($row = mysql_fetch_assoc($select)) $sData['categories'][] = $row['category'];

  $_GET['c'] = (isset($_GET['c'])) ? $_GET['c'] : "";
  if (!in_array($_GET['c'], $sData['categories'])) $_GET['c'] = "";

  if ($vData['s.cachetime'] < (time() - $vData['s.cachereset'] * 86400)) {
    $truncate = mysql_query("TRUNCATE TABLE `{$dData['tablestat']}`;");
    set_vData("s.cachetime", time());

  } else {
    $show = 0;
    $select = mysql_query("SELECT LENGTH(`cache`) FROM `{$dData['tablestat']}`;");
    while ($row = mysql_fetch_array($select)) $show += $row[0];
    while ($show > $vData['s.cachelimit'] * 1024) {
      $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `cache`='' WHERE LENGTH(`cache`)>1 ORDER BY `lasthit` LIMIT 1;");
      if (mysql_affected_rows()) {
        $show = 0;
        $select = mysql_query("SELECT LENGTH(`cache`) FROM `{$dData['tablestat']}`;");
        while ($row = mysql_fetch_array($select)) $show += $row[0];
      } else break;
    }
    $optimize = mysql_query("OPTIMIZE TABLE `{$dData['tablestat']}`;");
  }

  /* *************************************************************** */
  /* ***** Search ************************************************** */
  if (isset($_GET['q']) && $_GET['q'] = trim($_GET['q'])) {
    $_QUERY = array();

    $_QUERY['query'] = $_QUERY['original'] = $_GET['q'];
  
    preg_match_all("/[!+\-]?\".*?\"/", $_QUERY['query'], $quotes);
    $_QUERY['terms'] = str_replace('"', "", $quotes[0]);
    $_QUERY['query'] = preg_replace("/[!+\-]?\".*?\"/", "", $_QUERY['query']);
    $_QUERY['query'] = preg_replace("/\"/", "", $_QUERY['query']);
    $_QUERY['query'] = preg_replace("/\s{2,}/", " ", $_QUERY['query']);
    $_QUERY['terms'] = array_merge($_QUERY['terms'], explode(" ", $_QUERY['query']));
    $_QUERY['allterms'] = $_QUERY['terms'];
    $_QUERY['terms'] = array_filter($_QUERY['terms'], create_function('$value', 'return (strlen($value) >= '.$vData['s.termlength'].') ? true : false;'));
    $_QUERY['terms'] = array_slice($_QUERY['terms'], 0, $vData['s.termlimit']);

    // Sort terms and compare to cache
    $_QUERY['sorted'] = $_QUERY['terms'];
    sort($_QUERY['sorted']);
    $_QUERY['sorted'] = addslashes(stripslashes(implode(" ", $_QUERY['sorted'])));
    list($found) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablestat']}` WHERE `query`='{$_QUERY['sorted']}';"));
    list($count) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablestat']}` WHERE `query`='{$_QUERY['sorted']}' AND LENGTH(`cache`)>5;"));

    if (count($_QUERY['terms'])) {
      if (!$count) {
        if ($vData['sp.lock'] == "false") {
          $_QUERY['and'] = preg_grep("/^\+/", $_QUERY['terms']);
          $_QUERY['not'] = preg_grep("/^[!\-]/", $_QUERY['terms']);
          $_QUERY['or'] = array_diff($_QUERY['terms'], $_QUERY['and'], $_QUERY['not']);
          array_walk($_QUERY['and'], create_function('&$v, $k', '$v = substr($v, 1);'));
          array_walk($_QUERY['not'], create_function('&$v, $k', '$v = substr($v, 1);'));
          $_QUERY['andor'] = array_merge($_QUERY['and'], $_QUERY['or']);

          $mq = "";
          foreach ($_QUERY['not'] as $not) {
            $shot = mysqlPrep($not, true);
            $mq .= " AND `body` $shot AND `title` $shot AND `keywords` $shot";
          }
          foreach ($_QUERY['and'] as $and) {
            $shot = mysqlPrep($and, false);
            $mq .= " AND (`body` $shot OR `title` $shot OR `keywords` $shot)";
          }

          $select = mysql_unbuffered_query("SELECT * FROM `{$dData['tablename']}` WHERE `unlist`='false'{$sData['lq']}{$sData['nq']}{$mq};");

          while ($uri = mysql_fetch_assoc($select)) {

            $uri['relevance'] = 0;
            $uri['bonus'] = 1;
            $uri['matchText'] = "";
            addRelevance($uri, $_QUERY['and'], $vData['s.weight'][6]);
            addRelevance($uri, $_QUERY['or'], 1);

            if (!$uri['matchText']) $uri['matchText'] = $uri['description'];
            $uri['matchText'] = outputText($uri['matchText'], $_QUERY['andor']);
            $uri['title'] = outputText($uri['title'], $_QUERY['andor']);
            $uri['matchURI'] = outputText($uri['uri'], $_QUERY['andor']);
            $uri['description'] = outputText($uri['description'], array());

            if ($uri['relevance'])
              $_RESULTS[] = array("title"       => $uri['title'],
                                  "description" => $uri['description'],
                                  "category"    => $uri['category'],
                                  "uri"         => $uri['uri'],
                                  "matchURI"    => $uri['matchURI'],
                                  "matchText"   => $uri['matchText'],
                                  "relevance"   => $uri['relevance']);
          }

          if (count($_RESULTS)) {
            heapsort($_RESULTS, "relevance");
            $_RESULTS = array_slice(array_reverse($_RESULTS), 0, ($vData['s.resultlimit']) ? $vData['s.resultlimit'] : max(5, min(100, ceil($sData['totalRows'] / 6))));
          }

          if ($vData['s.cachelimit']) {
            $rStore = serialize($_RESULTS);
            $rStore = ($vData['s.cachegzip'] == "on") ? mysql_real_escape_string(gzcompress($rStore)) : addslashes(stripslashes($rStore));
          } else $rStore = "";

          if (!$found) {
            $insert = mysql_query("INSERT INTO `{$dData['tablestat']}` VALUES ('{$_QUERY['sorted']}', 1, '".addslashes($_QUERY['original'])."', ".time().", '{$rStore}');");
          } else $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `hits`=`hits`+1, `lasthit`=".time().", `cache`='{$rStore}', `astyped`='".addslashes($_QUERY['original'])."' WHERE `query`='{$_QUERY['sorted']}';");

        } else {
          $_RESULTS = NULL;

          if ($vData['sp.progress'] < time() - 60) set_vData("sp.lock", "false");
        }

      } else {
        if (!isset($_GET['start']))
          $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `hits`=`hits`+1, `lasthit`=".time().", `astyped`='".addslashes($_QUERY['original'])."' WHERE `query`='{$_QUERY['sorted']}';");

        $select = mysql_query("SELECT `cache` FROM `{$dData['tablestat']}` WHERE `query`='{$_QUERY['sorted']}';");

        $_RESULTS = mysql_result($select, 0, "cache");
        $_RESULTS = unserialize(($vData['s.cachegzip'] == "on") ? gzuncompress($_RESULTS) : stripslashes($_RESULTS));
      }

      $_QUERY['category'] = $_GET['c'];
      if ($_QUERY['category'] != "") {
        $_RESULTS = array_filter($_RESULTS, create_function('$v', 'global $_QUERY; return ($v[\'category\'] != $_QUERY[\'category\']) ? false : true;'));
        $_RESULTS = array_values($_RESULTS);
      }
    }
  }

  if ((!isset($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] != $dData['userAgent']) &&
      $vData['sp.lock'] == "false" &&
      $vData['sp.cron'] == "false" &&
      $vData['sp.interval'] &&
      $vData['sp.time'] < (time() - $vData['sp.interval'] * 3600)) {
    if ($vData['sp.pathto'] != "http://") {
      $uri = parse_url($vData['sp.pathto']);

      if (isset($uri['host']) && $conn = fsockopen($uri['host'], 80, $erstr, $errno, 5)) {
        fwrite($conn, "HEAD {$uri['path']} HTTP/1.0\r\nHost: {$uri['host']}\r\nUser-Agent: {$dData['userAgent']}\r\n\r\n");
        while (!feof($conn)) {
          $data = fgets($conn, 1024);

          if (preg_match("/^OrcaScript: Search_Spider/", $data)) {
            fclose($conn);

            set_vData("s.spkey", md5(time()));
            $conn2 = pfsockopen($uri['host'], 80, $erstr, $errno, 5);
            fwrite($conn2, "GET {$uri['path']}?{$vData['s.spkey']} HTTP/1.0\r\nHost: {$_SERVER['HTTP_HOST']}\r\nUser-Agent: {$dData['userAgent']}\r\nReferer: http://{$_SERVER['HTTP_HOST']}{$_SERVER["REQUEST_URI"]}\r\n\r\n");

            break;
          }
        }
      }
    }
  }
}

?>