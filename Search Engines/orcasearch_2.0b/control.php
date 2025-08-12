<?php /* ***** Orca Search - Control Panel **************************
* Orca Search v2.0b - Copyright (C) 2005 GreyWyvern
*  A robust auto-spidering search engine for single/multiple sites
* 
* See the readme.txt file for installation instructions.
****************************************************************** */


if (!ini_get("zlib.output_compression")) ob_start("ob_gzhandler");
include "config.php";


/* *************************************************************** */
/* ***** Functions *********************************************** */
function countUp($time) {
  global $_LANG;
  static $ctr = 0;
  $ctr++;

  $since = time() - $time;
  $days = floor($since / 86400); $since %= 86400;
  $hours = floor($since / 3600); $since %= 3600;
  $minutes = floor($since / 60);
  $seconds = $since % 60; ?> 
  <span id="days<?php echo $ctr; ?>"><?php printf("%02s", $days); ?></span> <?php echo $_LANG['00014']; ?>,
  <span id="hours<?php echo $ctr; ?>"><?php printf("%02s", $hours); ?></span> <?php echo $_LANG['00015']; ?>,
  <span id="minutes<?php echo $ctr; ?>"><?php printf("%02s", $minutes); ?></span> <?php echo $_LANG['00016']; ?>,
  <span id="seconds<?php echo $ctr; ?>"><?php printf("%02s", $seconds); ?></span> <?php echo $_LANG['00017'], " ", $_LANG['00018']; ?>
  <script type="text/javascript"><!--
    <?php if ($ctr == 1) { ?> 
      function incrTime(y) {
        setTimeout("incrTime(" + y + ");", 990);
        if (++atime[y][3] > 59) {
          atime[y][3] = 0;
          atime[y][2]++;
        }
        if (atime[y][2] > 59) {
          atime[y][2] = 0;
          atime[y][1]++;
        }
        if (atime[y][1] > 23) {
          atime[y][1] = 0;
          atime[y][0]++;
        }
        for (var x = 0; x < atype.length; x++)
          document.getElementById(atype[x] + y).firstChild.nodeValue = ((atime[y][x] < 10) ? "0" : "") + atime[y][x];
      }
      var atime = new Array();
      var atype = ["days", "hours", "minutes", "seconds"];
    <?php } ?>
    atime[<?php echo $ctr; ?>] = ["<?php echo $days; ?>", "<?php echo $hours; ?>", "<?php echo $minutes; ?>", "<?php echo $seconds; ?>"];
    setTimeout("incrTime(<?php echo $ctr; ?>);", 0);
  // --></script>
<?php }

function maul_md5s() {
  global $dData;

  $update = mysql_query("UPDATE `{$dData['tablename']}` SET `md5`=LEFT(`md5`,30);");
}


/* *************************************************************** */
/* ***** Setup *************************************************** */
header("OrcaScript: Search_ControlPanel");

if ($dData['online']) {
  $sData['noSearchSQL'] = array_filter(array_map("trim", explode("\n", $vData['s.ignore'])));
  $sData['noSearch'] = array_filter(array_map("pquote", explode("\n", $vData['s.ignore'])));
}

$cData['loggedIn'] = false;
$cData['command'] = "";


/* *************************************************************** */
/* ***** Import Language File ************************************ */
if ($langfile = @fopen("lang.txt", "r")) {
  while (!feof($langfile)) {
    $line = fgets($langfile);
    if (strpos($line, "=") && $line{0} != "#") {
      $line = explode("=", $line, 2);
      $_LANG[$line[0]] = rtrim($line[1]);
    }
  }
  fclose($langfile);
} else die("Unable to load language file");

$cData['langcf'] = array(
  "always"  => $_LANG['00019'],
  "hourly"  => $_LANG['0001a'],
  "daily"   => $_LANG['0001b'],
  "weekly"  => $_LANG['0001c'],
  "monthly" => $_LANG['0001d'],
  "yearly"  => $_LANG['0001e'],
  "never"   => $_LANG['0001f']
);
$cData['langst'] = array(
  "Not Found" => $_LANG['0001g'],
  "Blocked"   => $_LANG['0001h'],
  "Unlisted"  => $_LANG['0001i'],
  "OK"        => $_LANG['0001j'],
  "Orphan"    => $_LANG['0001k'],
  "Unread"    => $_LANG['0001l']
);


/* *************************************************************** */
/* ***** Login & Verification ************************************ */
if (isset($_COOKIE['osc_cp'])) {
  $login = explode("::", base64_decode($_COOKIE['osc_cp']));
  if ($login[0] == $dData['adminName'] && $login[1] == $dData['adminPass']) {
    if ($login[2] == $vData['c.logkey']) {
      setcookie("osc_cp", base64_encode(implode("::", $login)), time() + 18600);
      set_vData("c.logtime", time());
      $cData['loggedIn'] = true;
    } else {
      setcookie("osc_cp", "", time() - 18600);
      $eData['error'][] = $_LANG['00031'];
    }
  } else $eData['error'][] = $_LANG['00032'];
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['loginName']) && isset($_POST['loginPass'])) {
    if ($_POST['loginName'] == $dData['adminName'] && $_POST['loginPass'] == $dData['adminPass']) {
      if ($vData['c.logtime'] < time() - 180) {
        set_vData("c.logkey", $key = md5(time()));
        setcookie("osc_cp", base64_encode(implode("::", array($dData['adminName'], $dData['adminPass'], $key))), time() + 18600);
        set_vData("c.logtime", time());
        $cData['loggedIn'] = true;
      } else $eData['error'][] = sprintf($_LANG['00033'], time() - $vData['c.logtime']);
    }
  }
}
if ($cData['loggedIn']) {
  if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SERVER['QUERY_STRING'])) {
    if (strpos($_SERVER['QUERY_STRING'], "=") === false) {
      $cData['command'] = "location";
      $cData['location'] = $_SERVER['QUERY_STRING'];
    } else {
    }    
  } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    reset($_POST);
    while (list($key, $value) = each($_POST)) {
      if (strpos($key, "_")) {
        $command = explode("_", $key);
        $cData['command'] = $command[0];
        $cData[$command[0]] = $command[1];
        break;
      }
    }
  }
}


/* *************************************************************** */
/* ***** Command Execution *************************************** */
if ($dData['online'] && $cData['loggedIn'] && $cData['command']) {

  switch ($cData['command']) {
    /* *********************************************************** */
    case "location":
      switch ($cData['location']) {
        case "Logout": /* **************************************** */
          setcookie("osc_cp", "", time() - 84600);
          set_vData("c.logtime", time() - 84600);
          $cData['loggedIn'] = false;
          break;

        case "Javabuild": /* ************************************* */
          if (isset($_POST['key']) && $_POST['key'] == $vData['jw.key']) {
            if ($vData['jw.writer'] != "http://") {
              $uri = parse_url($vData['jw.writer']);

              if (isset($uri['host']) && $conn = pfsockopen($uri['host'], ((isset($uri['port'])) ? $uri['port'] : 80), $erstr, $errno, 5)) {
                fwrite($conn, "GET {$uri['path']}?{$vData['jw.key']} HTTP/1.0\r\nHost: {$uri['host']}\r\nUser-Agent: {$dData['userAgent']}\r\n\r\n");
                set_vData("jw.progress", 0);
                set_vData("jw.memory", 0);
              } else {
                set_vData("jw.progress", 100);
                $eData['error'][] = $_LANG['00034'];
              }
            } else $eData['error'][] = $_LANG['00034'];
          }
          if (isset($eData['error'])) break 2;
      ?><html>
<head>
  <meta http-equiv="refresh" content="3; URL=<?php echo $_SERVER['PHP_SELF'], (($vData['jw.progress'] < 100) ? "?Javabuild" : ""); ?>" />
  <title><?php echo $_LANG['000o0']; ?></title>
</head>
<body>
  <?php if ($vData['jw.progress'] < 100) { ?> 
    <h1><?php echo $_LANG['000o0']; ?></h1>
    <h2><?php printf($_LANG['000o1'], $vData['jw.progress']); ?></h2>
    <h3>
      <?php echo $_LANG['000o2']; ?> 
    </h3>
    <p><?php printf($_LANG['000o3'], sprintf("%01.2f", $vData['jw.memory'] / 1048576)); ?></p>
    <p><?php printf($_LANG['000o4'], $_SERVER['PHP_SELF'].(($vData['jw.progress'] < 100) ? "?Javabuild" : "")); ?></p>
  <?php } else { 
    set_vData("c.location", "Tools"); ?> 
    <h1><?php echo $_LANG['000o5']; ?></h1>
    <h2><?php echo $_LANG['000o6']; ?></h2>
    <h3><?php echo $_LANG['000o7']; ?></h3>
    <p><?php printf($_LANG['000o8'], $_SERVER['PHP_SELF']); ?></p>
  <?php } ?> 
</body>
</html><?php
          exit();

        case "Search": /* **************************************** */
        case "List":
        case "Spider":
        case "Stats":
        case "Tools":
          set_vData("c.location", $cData['location']);
          break;
      }
      break;


    /* *********************************************************** */
    case "filter":
      set_vData("c.location", "List");
      $_GET['start'] = 0;
      switch ($cData['filter']) {
        case "Clear": /* ***************************************** */
          set_vData("cf.textexclude", "");
          set_vData("cf.textmatch", "");
          set_vData("cf.category", "-");
          set_vData("cf.status", "All");
          set_vData("cf.new", "false");
          break;

        case "Set": /* ******************************************* */
          set_vData("cf.textexclude", $_POST['textexclude']);
          set_vData("cf.textmatch", $_POST['textmatch']);
          set_vData("cf.category", (isset($_POST['category'])) ? $_POST['category'] : "-");
          set_vData("cf.status", $_POST['status']);
          set_vData("cf.new", (isset($_POST['new'])) ? "true" : "false");
          break;
      }
      break;


    /* *********************************************************** */
    case "action":
      set_vData("c.location", "List");
      $cData['input'] = $_POST[$cData['action']];
      if (!isset($_POST['action'])) $_POST['action'] = array();
      if (isset($_POST['actionIDs'])) $_POST['action'] = explode("::", $_POST['actionIDs']);

      if (count($_POST['action'])) {
        switch ($cData['input']) {
          case "delete": /* **************************************** */
            foreach ($_POST['action'] as $action)
              $delete = mysql_query("DELETE FROM `{$dData['tablename']}` WHERE `md5`='{$action}';");
            clearCache();
            $cData['command'] = $cData['input'] = "";
            break;

          case "unlist": /* **************************************** */
            foreach ($_POST['action'] as $action)
              $update = mysql_query("UPDATE `{$dData['tablename']}` SET `unlist`='true' WHERE `md5`='{$action}';");
            clearCache();
            $cData['command'] = $cData['input'] = "";
            break;

          case "relist": /* **************************************** */
            foreach ($_POST['action'] as $action)
              $update = mysql_query("UPDATE `{$dData['tablename']}` SET `unlist`='false' WHERE `md5`='{$action}';");
            clearCache();
            $cData['command'] = $cData['input'] = "";
            break;

          case "category": /* ************************************** */
            break;

          case "sm.unlist": /* ************************************* */
            if ($vData['sm.enable'] == "true") {
              foreach ($_POST['action'] as $action)
                $update = mysql_query("UPDATE `{$dData['tablename']}` SET `sm.list`='false' WHERE `md5`='{$action}';");
              clearCache();
            } else $cData['command'] = $cData['input'] = "";
            break;

          case "sm.relist": /* ************************************* */
            if ($vData['sm.enable'] == "true") {
              foreach ($_POST['action'] as $action)
                $update = mysql_query("UPDATE `{$dData['tablename']}` SET `sm.list`='true' WHERE `md5`='{$action}';");
              clearCache();
            } else $cData['command'] = $cData['input'] = "";
            break;

          case "sm.changefreq": /* ********************************* */
            if ($vData['sm.enable'] != "true" || $vData['sm.changefreq'] == "true") $cData['command'] = $cData['input'] = "";
            break;

          case "sm.priority": /* *********************************** */
            if ($vData['sm.enable'] != "true") $cData['command'] = $cData['input'] = "";
            break;

          case "smcConfirm": /* ************************************ */
            if ($vData['sm.enable'] == "true" && $vData['sm.changefreq'] != "true" && array_key_exists($_POST['changefreq'], $cData['changefreq']))
              foreach ($_POST['action'] as $action)
                $update = mysql_query("UPDATE `{$dData['tablename']}` SET `sm.changefreq`='{$_POST['changefreq']}' WHERE `md5`='$action';");
            $cData['command'] = $cData['input'] = "";
            break;

          case "smpConfirm": /* ************************************ */
            if ($vData['sm.enable'] == "true") {
              $_POST['priority'] = (float)$_POST['priority'];
              foreach ($_POST['action'] as $action)
                $update = mysql_query("UPDATE `{$dData['tablename']}` SET `sm.priority`='{$_POST['priority']}' WHERE `md5`='$action';");
            }
            $cData['command'] = $cData['input'] = "";
            break;

          case "catConfirm": /* ************************************ */
            $cData['row'] = array();
            $cData['categories'] = array();
            $select = mysql_query("SELECT DISTINCT `category` FROM `{$dData['tablename']}`;");
            while ($row = mysql_fetch_assoc($select)) $cData['categories'][] = $row['category'];

            if ($_POST['categoryExist'] == "-") {
              if (!trim($_POST['categoryNew'])) {
                $cData['row']['category'] = "-";
                $eData['error'][] = $_LANG['00035'];
              } else $cData['row']['category'] = trim($_POST['categoryNew']);
            } else if (in_array(trim($_POST['categoryExist']), $cData['categories'])) {
              $cData['row']['category'] = trim($_POST['categoryExist']);
            } else $eData['error'] = $_LANG['00036'];

            if (!isset($eData)) {
              foreach ($_POST['action'] as $action)
                $update = mysql_query("UPDATE `{$dData['tablename']}` SET `category`='{$cData['row']['category']}' WHERE `md5`='$action';");
              clearCache();
              $cData['command'] = $cData['input'] = "";
            } else $cData['input'] = "category";
            break;

          default: /* ********************************************** */
            $cData['command'] = $cData['input'] = "";

        }
      } else $cData['command'] = $cData['input'] = "";
      break;


    /* *********************************************************** */
    case "show":
      set_vData("c.location", "List");
      if (set_vData("c.pagination", max(10, min(999, (int)trim($_POST['show'.$cData['show']]))))) $_GET['start'] = 0;
      break;


    /* *********************************************************** */
    case "add":
      set_vData("c.location", "List");
      switch ($cData['add']) {
        case "Confirm": /* *************************************** */
          $cData['row'] = array();

          $cData['categories'] = array();
          $select = mysql_query("SELECT DISTINCT `category` FROM `{$dData['tablename']}`;");
          while ($row = mysql_fetch_assoc($select)) $cData['categories'][] = $row['category'];

          $cData['row']['uri'] = trim($_POST['uri']);
          if ($cData['row']['uri'] != "http://") {
            $uri = parse_url($cData['row']['uri']);
            if (!isset($uri['path'])) {
              $uri['path'] = "/";
              if ($cData['row']['uri']{strlen($cData['row']['uri']) - 1} != "/") {
                $update = mysql_query("UPDATE `{$dData['tablename']}` SET `uri`=CONCAT(`uri`,'/') WHERE `uri`='{$cData['row']['uri']}';");
                $cData['row']['uri'] .= "/";
              }
            }
            $uri['full'] = $uri['path'].((isset($uri['query'])) ? "?{$uri['query']}" : "");

            if ($conn = fsockopen($uri['host'], ((isset($uri['port'])) ? $uri['port'] : 80), $erstr, $errno, 5)) {
              fwrite($conn, "HEAD {$uri['full']} HTTP/1.0\r\nHost: {$uri['host']}\r\nUser-Agent: {$dData['userAgent']}\r\n\r\n");
              while (!feof($conn)) {
                $data = fgets($conn, 1024);

                if (preg_match("/^HTTP\/1\.\d ([^23]\d\d)/i", $data, $code)) {
                  $eData['error'][] = sprintf($_LANG['00037'], $code[1], $cData['row']['uri']);
                  break;
                }

                if (preg_match("/^Location:\s*([^\r\n]*?)[\r\n]/i", $data, $location)) {
                  $eData['error'][] = sprintf($_LANG['00038'], $uri, $location[1]);
                  $cData['row']['uri'] = $location[1];
                  break;
                }
              }
            } else $eData['error'][] = sprintf($_LANG['00039'], $uri['host']);
          } else $eData['error'][] = $_LANG['0003a'];

          $cData['row']['title'] = trim($_POST['title']);
          if ($_POST['categoryExist'] == "-") {
            if (trim($_POST['categoryNew'])) {
              $cData['row']['category'] = trim($_POST['categoryNew']);
            } else {
              $cData['row']['category'] = "-";
              $eData['error'][] = $_LANG['00035'];
            }
          } else if (in_array(trim($_POST['categoryExist']), $cData['categories'])) {
            $cData['row']['category'] = trim($_POST['categoryExist']);
          } else $eData['error'] = $_LANG['00036'];
          $cData['row']['description'] = str_replace(array("\n", "\r"), " ", trim($_POST['description']));
          $cData['row']['keywords'] = str_replace(array("\n", "\r"), " ", trim($_POST['keywords']));
          $cData['row']['unlist'] = ($_POST['unlist'] == "true") ? "true" : "false";

          $cData['row']['sm.list'] = (isset($_POST['list']) && $_POST['list'] == "true") ? "true" : "false";
          $cData['row']['sm.changefreq'] = (isset($_POST['changefreq']) && array_key_exists($_POST['changefreq'], $cData['changefreq'])) ? "true" : "false";
          $cData['row']['sm.priority'] = (isset($_POST['priority'])) ? max(0, min(1, (float)$_POST['priority'])) : "0.5";

          $select = mysql_query("SELECT `uri`, `title`, `category`, `description`, `keywords`, `unlist`, `md5`, `sm.list`, `sm.changefreq`, `sm.priority` FROM `{$dData['tablename']}` WHERE `uri`='{$_POST['uri']}';");

          if (mysql_num_rows($select)) {
            $cData['add'] = "Again";
            $eData['error'][] = $_LANG['0003b'];
          } else {
            $cData['row'] = array_map("stripslashes", $cData['row']);
            $cData['row'] = array_map("addslashes", $cData['row']);

            $insert = mysql_query("INSERT INTO `{$dData['tablename']}` VALUES ('{$cData['row']['uri']}', '".md5($cData['row']['uri'])."', '{$cData['row']['title']}', '{$cData['row']['category']}', '{$cData['row']['description']}', '{$cData['row']['keywords']}', '', '', '', '-', 'Unread', '{$cData['row']['unlist']}', 'true', '{$cData['row']['sm.list']}', '".time()."','{$cData['row']['sm.changefreq']}', '{$cData['row']['sm.priority']}');");
            if (mysql_affected_rows()) {
              clearCache();
              $eData['success'][] = sprintf($_LANG['00060'], $cData['row']['uri']);
            } else $eData['error'][] = sprintf($_LANG['0003c'], $cData['row']['uri']);

            unset($cData['add']);
          }

          if (isset($eData)) $cData['add'] = "Again";

          break;
      }
      break;


    /* *********************************************************** */
    case "edit":
      set_vData("c.location", "List");
      switch ($cData['edit']) {
        case "Confirm": /* *************************************** */
          $cData['row'] = array();

          $cData['categories'] = array();
          $select = mysql_query("SELECT DISTINCT `category` FROM `{$dData['tablename']}`;");
          while ($row = mysql_fetch_assoc($select)) $cData['categories'][] = $row['category'];

          $select = mysql_query("SELECT `uri`, `title`, `encoding`, `category`, `description`, `keywords`, `unlist`, `md5`, `sm.list`, `sm.changefreq`, `sm.priority` FROM `{$dData['tablename']}` WHERE `md5`='{$_POST['md5']}';");

          if (mysql_num_rows($select)) {
            $row = mysql_fetch_assoc($select);
            $cData['row']['uri'] = trim($_POST['uri']);
            $cData['row']['title'] = trim($_POST['title']);
            if ($_POST['categoryExist'] == "-") {
              if (trim($_POST['categoryNew'])) {
                $cData['row']['category'] = trim($_POST['categoryNew']);
              } else {
                $cData['row']['category'] = trim($_POST['categoryNow']);
                $eData['error'][] = $_LANG['00035'];
              }
            } else if (in_array(trim($_POST['categoryExist']), $cData['categories'])) {
              $cData['row']['category'] = trim($_POST['categoryExist']);
            } else $eData['error'] = $_LANG['00036'];
            $cData['row']['description'] = str_replace(array("\n", "\r"), " ", trim($_POST['description']));
            $cData['row']['keywords'] = str_replace(array("\n", "\r"), " ", trim($_POST['keywords']));
            $cData['row']['unlist'] = ($_POST['unlist'] == "true") ? "true" : "false";
            $cData['row']['md5'] = $_POST['md5'];

            $cData['row']['sm.list'] = ($_POST['list'] == "true") ? "true" : "false";
            $cData['row']['sm.changefreq'] = (isset($_POST['changefreq']) && array_key_exists($_POST['changefreq'], $cData['changefreq'])) ? $_POST['changefreq'] : $row['sm.changefreq'];
            $cData['row']['sm.priority'] = max(0, min(1, (float)$_POST['priority']));

            if (!isset($eData)) {
              $cData['row'] = array_map("stripslashes", $cData['row']);
              $cData['row'] = array_map("addslashes", $cData['row']);

              $update = mysql_query("UPDATE `{$dData['tablename']}` SET `title`='{$cData['row']['title']}', `category`='{$cData['row']['category']}', `description`='{$cData['row']['description']}', `keywords`='{$cData['row']['keywords']}', `unlist`='{$cData['row']['unlist']}', `sm.list`='{$cData['row']['sm.list']}', `sm.changefreq`='{$cData['row']['sm.changefreq']}', `sm.priority`='{$cData['row']['sm.priority']}' WHERE `md5`='{$cData['row']['md5']}';");
              clearCache();

              $cData['command'] = "";
            } else $cData['edit'] = $cData['row']['md5'];
          } else $eData['error'][] = $_LANG['0003d'];

          break;

        default: /* ********************************************** */
          $select = mysql_query("SELECT `uri`, `title`, `encoding`, `category`, `description`, `keywords`, `unlist`, `md5`, `sm.list`, `sm.changefreq`, `sm.priority` FROM `{$dData['tablename']}` WHERE `md5`='{$cData['edit']}';");
          if (mysql_num_rows($select)) {
            $cData['row'] = mysql_fetch_assoc($select);
          } else {
            unset($cData['edit']);
            $eData['error'][] = $_LANG['0003d'];
          }
      }
      break;


    /* *********************************************************** */
    case "spider":
      set_vData("c.location", "Spider");
      switch ($cData['spider']) {
        case "Edit": /* ****************************************** */
          $_POST = array_map(create_function('$v', 'return str_replace("\r", "", $v);'), $_POST);

          if ($_POST['pathto'] = trim($_POST['pathto'])) {
            if (!preg_match("/^http:\/\//", $_POST['pathto'])) $_POST['pathto'] = "http://".$_POST['pathto'];
            set_vData("sp.pathto", $_POST['pathto']);
          }

          if ($_POST['start'] = trim($_POST['start'])) {
            $_POST['start'] = preg_grep("/^http:\/\/\w/", array_map("trim", explode("\n", $_POST['start'])));
            while (list($key, $value) = each($_POST['start'])) {
              $uri = parse_url($value);
              if (isset($uri['host']) && !isset($uri['path'])) $_POST['start'][$key] .= "/";
            }
            set_vData("sp.start", implode("\n", $_POST['start']));
          }

          set_vData("sp.cron", (isset($_POST['cron'])) ? "true" : "false");

          set_vData("sp.pagelimit", max(1, abs((int)trim($_POST['pagelimit']))));

          if (isset($_POST['interval']))
            set_vData("sp.interval", min(1536, abs((int)trim($_POST['interval']))));

          if (isset($_POST['email']))
            set_vData("sp.email", trim($_POST['email']));

          $_POST['defcat'] = trim($_POST['defcat']);
          set_vData("sp.defcat", $_POST['defcat']);

          $_POST['autocat'] = preg_replace("/\n{2,}/", "\n", trim($_POST['autocat']));
          set_vData("sp.autocat", $_POST['autocat']);

          $mauler = false;

          if (set_vData("sp.utf8", (isset($_POST['utf8'])) ? "true" : "false")) $mauler = true;

          $_POST['mimetypes'] = trim($_POST['mimetypes']);
          if (preg_match("/[^\w+\/\n]/", $_POST['mimetypes'])) {
            $eData['error'][] = $_LANG['0003e'];
          } else if (set_vData("sp.mimetypes", $_POST['mimetypes'])) $mauler = true;

          $_POST['domains'] = preg_replace("/\n{2,}/", "\n", trim($_POST['domains']));
          if (set_vData("sp.domains", $_POST['domains'])) $mauler = true;

          $_POST['require'] = preg_replace("/\n{2,}/", "\n", trim($_POST['require']));
          if (set_vData("sp.require", $_POST['require'])) $mauler = true;

          $_POST['ignore'] = preg_replace("/\n{2,}/", "\n", trim($_POST['ignore']));
          if (set_vData("sp.ignore", $_POST['ignore'])) $mauler = true;

          $_POST['extensions'] = trim($_POST['extensions']);
          if (preg_match("/[^\w\d\s.]/", $_POST['extensions'])) {
            $eData['error'][] = $_LANG['0003f'];
          } else {
            $_POST['extensions'] = preg_replace(array("/\s/", "/\s{2,}/", "/(^|\s)\.+/", "/\.(\s|$)/"), array(" ", " ", "$1", "$1"), $_POST['extensions']);
            $_POST['extensions'] = explode(" ", $_POST['extensions']);
            sort($_POST['extensions']);
            $_POST['extensions'] = implode(" ", $_POST['extensions']);
            if (set_vData("sp.extensions", $_POST['extensions'])) $mauler = true;
          }

          $_POST['remtags'] = trim($_POST['remtags']);
          if (preg_match("/[^\w\s]/", $_POST['remtags'])) {
            $eData['error'][] = $_LANG['0003g'];
          } else {
            $_POST['remtags'] = preg_replace(array("/\s/", "/\s{2,}/"), " ", $_POST['remtags']);
            $_POST['remtags'] = explode(" ", $_POST['remtags']);
            sort($_POST['remtags']);
            $_POST['remtags'] = implode(" ", $_POST['remtags']);
            if (set_vData("sp.remtags", $_POST['remtags'])) $mauler = true;
          }

          if (set_vData("sp.remtitle", $_POST['remtitle'])) $mauler = true;

          if ($mauler) maul_md5s();

          break;

        case "Go": /* ******************************************** */
          break;

        case "Cancel": /* **************************************** */
          set_vData("sp.cancel", "true");
          set_vData("sp.lock", "false");
          $eData['success'][] = $_LANG['00061'];
          break;

      }
      break;


    /* *********************************************************** */
    case "search":
      set_vData("c.location", "Search");
      switch ($cData['search']) {
        case "Cache": /* ************************************ */
          if ($_POST['cachelimit'] = abs((int)trim($_POST['cachelimit'])))
            set_vData("s.cachelimit", $_POST['cachelimit']);

          if ($vData['s.cachegzip'] != "disabled") {
            $_POST['cachegzip'] = (isset($_POST['cachegzip'])) ? "on" : "off";
            if ($_POST['cachegzip'] != $vData['s.cachegzip']) {
              set_vData("s.cachegzip", $_POST['cachegzip']);
              clearCache();
            }
          }
          break;

        case "Purge": /* ***************************************** */
          clearCache();
          break;

        case "Edit": /* ****************************************** */
          $_POST = array_map(create_function('$v', 'return str_replace("\r", "", $v);'), $_POST);

          $_POST['ignore'] = preg_replace("/\n{2,}/", "\n", trim($_POST['ignore']));
          set_vData("s.ignore", $_POST['ignore']);

          if ($_POST['termlimit'] = abs((int)trim($_POST['termlimit'])))
            set_vData("s.termlimit", $_POST['termlimit']);

          if ($_POST['termlength'] = abs((int)trim($_POST['termlength'])))
            set_vData("s.termlength", $_POST['termlength']);

          $weight = (string)abs((float)trim($_POST['weight0']));
          $weight .= "%".(string)abs((float)trim($_POST['weight1']));
          $weight .= "%".(string)abs((float)trim($_POST['weight2']));
          $weight .= "%".(string)abs((float)trim($_POST['weight3']));
          $weight .= "%".(string)abs((float)trim($_POST['weight4']));

          $weight .= "%".(string)abs((float)trim($_POST['weight5']));
          $weight .= "%".(string)abs((float)trim($_POST['weight6']));
          if (set_vData("s.weight", $weight))
            $vData['s.weight'] = explode("%", $vData['s.weight']);

          set_vData("s.latinacc", (isset($_POST['latinacc'])) ? "true" : "false");

          $_POST['weightedtags'] = trim($_POST['weightedtags']);
          if (preg_match("/[^\w\s]/", $_POST['weightedtags'])) {
            $eData['error'][] = $_LANG['0003h'];
          } else {
            $_POST['weightedtags'] = preg_replace(array("/\s/", "/\s{2,}/"), " ", $_POST['weightedtags']);
            $_POST['weightedtags'] = explode(" ", $_POST['weightedtags']);
            sort($_POST['weightedtags']);
            $_POST['weightedtags'] = implode(" ", $_POST['weightedtags']);
            if (set_vData("s.weightedtags", $_POST['weightedtags'])) maul_md5s();
          }

          set_vData("s.resultlimit", abs((int)trim($_POST['resultlimit'])));

          if ($_POST['matchingtext'] = abs((int)trim($_POST['matchingtext'])))
            set_vData("s.matchingtext", $_POST['matchingtext']);

          set_vData("s.orphans", (isset($_POST['orphans'])) ? "show" : "hide");

          clearCache();

          break;
      }
      break;


    /* *********************************************************** */
    case "stats":
      set_vData("c.location", "Stats");
      switch ($cData['stats']) {
        case "Interval": /* ************************************** */
          if ($_POST['cachereset'] = abs((int)trim($_POST['cachereset'])))
            set_vData("s.cachereset", $_POST['cachereset']);
          break;

        case "Reset": /* ***************************************** */
          $truncate = mysql_query("TRUNCATE TABLE `{$dData['tablestat']}`;");
          set_vData("s.cachetime", time());
          break;

      }
      break;


    /* *********************************************************** */
    case "control":
      set_vData("c.location", "Tools");
      switch ($cData['control']) {
        case "Control": /* *************************************** */
          if (preg_match("/[^\w\d\-]/", trim($_POST['charset']))) {
            $eData['error'][] = $_LANG['0003i'];
          } else set_vData("c.charset", trim($_POST['charset']));
          break;
      }
      break;


    /* *********************************************************** */
    case "sitemap":
      set_vData("c.location", "Tools");
      switch ($cData['sitemap']) {
        case "Control": /* *************************************** */
          $cData['domains'] = array();
          $domains = mysql_unbuffered_query("SELECT `uri` FROM `{$dData['tablename']}` WHERE `sm.list`='true';");
          while ($domrow = mysql_fetch_assoc($domains)) {
            $parsed = parse_url($domrow['uri']);
            if (!array_key_exists($parsed['host'], $cData['domains'])) {
              $cData['domains'][$parsed['host']] = 1;
            } else $cData['domains'][$parsed['host']]++;
          }
          arsort($cData['domains']);
          reset($cData['domains']);

          if (!array_key_exists($vData['sm.domain'], $cData['domains']))
            set_vData("sm.domain", key($cData['domains']));

          set_vData("sm.enable", (isset($_POST['enable'])) ? "true" : "false");

          if (isset($_POST['pathto'])) {
            if ($_POST['pathto'] = trim($_POST['pathto']))
              set_vData("sm.pathto", $_POST['pathto']);

            set_vData("sm.gzip", (isset($_POST['gzip'])) ? "true" : "false");
            if ($vData['sm.gzip'] == "true") {
              if (substr($vData['sm.pathto'], -3, 3) != ".gz") set_vData("sm.pathto", $vData['sm.pathto'].".gz");
            } else if (substr($vData['sm.pathto'], -3, 3) == ".gz") set_vData("sm.pathto", substr($vData['sm.pathto'], 0, strlen($vData['sm.pathto']) - 3));

            if (isset($_POST['domain']) && $_POST['domain'] = trim($_POST['domain'])) {
              if (!array_key_exists($_POST['domain'], $cData['domains'])) {
                $eData['error'][] = $_LANG['0003j'];
              } else set_vData("sm.domain", $_POST['domain']);
            }

            set_vData("sm.unlisted", (isset($_POST['unlisted'])) ? "true" : "false");

            set_vData("sm.changefreq", (isset($_POST['changefreq'])) ? "true" : "false");
          }
          break;

        case "Commit": /* **************************************** */
          $cData['smnf'] = true;
          $cData['smnw'] = true;
          if (file_exists($vData['sm.pathto'])) {
            $cData['smnf'] = false;
            if (is_writable($vData['sm.pathto'])) $cData['smnw'] = false;
          }

          if (!$cData['smnf'] && !$cData['smnw']) {  
            if ($vData['sm.unlisted'] != "true") {
              $lq = ($vData['s.orphans'] == "show") ? " AND (`status`='OK' OR `status`='Orphan')" : " AND `status`='OK'";

              $nq = "";
              $sData['noSearch'] = array_filter(array_map("trim", explode("\n", $vData['s.ignore'])));
              foreach ($sData['noSearch'] as $noSearch)
                $nq .= " AND `uri` NOT ".(($noSearch{0} == "*") ? "REGEXP '".substr($noSearch, 1)."'": " LIKE '%{$noSearch}%'");

              $qadd = " AND `unlist`!='true'{$lq}{$nq}"; 
            }

            $sitemap = mysql_unbuffered_query("SELECT `uri`, `sm.lastmod`, `sm.changefreq`, `sm.priority` FROM `{$dData['tablename']}` WHERE `sm.list`='true' AND `uri` LIKE '%//{$vData['sm.domain']}/%'$qadd;");

            ob_start();
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
            $eData['success'][] = $_LANG['00062'];
          } else $eData['error'][] =  $_LANG['0003k'];
          break;

      }
      break;


    /* *********************************************************** */
    case "jwriter";
      set_vData("c.location", "Tools");
      $_POST = array_map(create_function('$v', 'return str_replace("\r", "", $v);'), $_POST);
      switch ($cData['jwriter']) {
        case "Options":
          set_vData("jw.hide", (isset($_POST['hide'])) ? "false" : "true");

          if (isset($_POST['writer'])) {
            if ($_POST['writer'] = trim($_POST['writer']))
              set_vData("jw.writer", $_POST['writer']);

            if ($_POST['egg'] = trim($_POST['egg']))
              set_vData("jw.egg", $_POST['egg']);

            $_POST['remuri'] = preg_replace("/\n{2,}/", "\n", trim($_POST['remuri']));
            set_vData("jw.remuri", $_POST['remuri']);

            $_POST['index'] = trim($_POST['index']);
            set_vData("jw.index", $_POST['index']);

            $_POST['extfrom'] = trim($_POST['extfrom']);
            if (preg_match("/[^\w\s.]/", $_POST['extfrom'])) {
              $eData['error'][] = $_LANG['0003l'];
            } else {
              $_POST['extfrom'] = preg_replace(array("/\s/", "/\s{2,}/", "/(^|\s)\.+/", "/\.(\s|$)/"), array(" ", " ", "$1", "$1"), $_POST['extfrom']);
              $_POST['extfrom'] = explode(" ", $_POST['extfrom']);
              sort($_POST['extfrom']);
              $_POST['extfrom'] = implode(" ", $_POST['extfrom']);
              set_vData("jw.extfrom", $_POST['extfrom']);
            }

            $_POST['extto'] = trim($_POST['extto'], "\n\r\t. ");
            set_vData("jw.extto", $_POST['extto']);

            set_vData("jw.pagination", max(1, abs((int)trim($_POST['pagination']))));

            if ($_POST['template'] = trim($_POST['template'])) {
              set_vData("jw.template", $_POST['template']);
            } else set_vData("jw.template", "<h3><a href=\"{R_URI}\" title=\"{R_DESCRIPTION}\">{R_TITLE}</a> - <small>{R_CATEGORY}</small></h3>\n<div>\n  <blockquote>\n     <p>\n      {R_MATCH}<br />\n      <cite>{R_URI}</cite> <small>({R_RELEVANCE})</small>\n    </p>\n  </blockquote>\n</div>");
          }
          break;
      }
      break;

  }
}


/* *************************************************************** */
/* ***** Display Setup ******************************************* */
if ($dData['online'] && $cData['loggedIn']) {
  $cData['lq'] = ($vData['s.orphans'] == "show") ? " AND (`status`='OK' OR `status`='Orphan')" : " AND `status`='OK'";

  $cData['nq'] = "";
  $igl = array_filter(array_map("trim", explode("\n", $vData['s.ignore'])));
  foreach ($igl as $ig) $cData['nq'] .= " AND `uri` NOT ".(($ig{0} == "*") ? "REGEXP '".substr($ig, 1)."'": " LIKE '%{$ig}%'");

  switch ($vData['c.location']) {
    case "Tools": /* ********************************************* */
      if ($vData['sm.enable'] == "true") {
        $cData['smnf'] = true;
        $cData['smnw'] = true;
        if (file_exists($vData['sm.pathto'])) {
          $cData['smnf'] = false;
          if (is_writable($vData['sm.pathto'])) $cData['smnw'] = false;
        }

        if (!isset($cData['domains'])) {
          $cData['domains'] = array();
          $domains = mysql_unbuffered_query("SELECT `uri` FROM `{$dData['tablename']}` WHERE `sm.list`='true';");
          while ($domrow = mysql_fetch_assoc($domains)) {
            $parsed = parse_url($domrow['uri']);
            if (!array_key_exists($parsed['host'], $cData['domains'])) {
              $cData['domains'][$parsed['host']] = 1;
            } else $cData['domains'][$parsed['host']]++;
          }
          arsort($cData['domains']);
        }
      }

      if ($vData['jw.hide'] == "false") {
        if ((int)ini_get("memory_limit")) {
          $dData['memlimit'] = (ini_set("memory_limit", ((int)ini_get("memory_limit") + 1)."M")) ? true : false;
        } else $dData['memlimit'] = NULL;

        list($count) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `unlist`!='true'{$cData['lq']}{$cData['nq']};"));

        $cData['jnf'] = true;
        $cData['jnw'] = true;
        if (file_exists($vData['jw.egg'])) {
          $cData['jnf'] = false;
          if (is_writable($vData['jw.egg'])) $cData['jnw'] = false;
        }

        $cData['wnf'] = true;
        if ($vData['jw.writer'] != "http://") {
          $uri = parse_url($vData['jw.writer']);
          $uri['full'] = $uri['path'].((isset($uri['query'])) ? "?{$uri['query']}" : "");

          if (isset($uri['host']) && $conn = fsockopen($uri['host'], ((isset($uri['port'])) ? $uri['port'] : 80), $erstr, $errno, 5)) {
            fwrite($conn, "HEAD {$uri['full']} HTTP/1.0\r\nHost: {$uri['host']}\r\nUser-Agent: {$dData['userAgent']}\r\n\r\n");
            while (!feof($conn)) {
              $data = fgets($conn, 1024);

              if (preg_match("/^OrcaScript: Search_JWriter/", $data)) {
                $cData['wnf'] = false;
                break;
              }
            }
          }
        }

        $cData['indextable'] = mysql_fetch_assoc(mysql_query("SHOW TABLE STATUS LIKE '%{$dData['tablename']}%';"));
        $cData['indexmem'] = $cData['indextable']['Data_length'];
      }
      break;

    case "Search": /* ******************************************** */
      $optimize = mysql_query("OPTIMIZE TABLE `{$dData['tablestat']}`;");
      list($cData['cachedno']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablestat']}` WHERE LENGTH(`cache`)>5;"));

      $cData['cachekb'] = 0;
      $select = mysql_query("SELECT LENGTH(`cache`) FROM `{$dData['tablestat']}`;");
      while ($row = mysql_fetch_array($select)) $cData['cachekb'] += $row[0];
      $cData['cachekb'] /= 1024;
      break;

    case "Stats": /* ********************************************* */
      if ($vData['sp.lasttime'] != -1) {
        $cData['indextable'] = mysql_fetch_assoc(mysql_query("SHOW TABLE STATUS LIKE '%{$dData['tablename']}%';"));
        $cData['indexmem'] = $cData['indextable']['Data_length'];

        list($cData['indexpages']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}`;"));
        list($cData['indexsrchd']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `unlist`!='true'{$cData['lq']}{$cData['nq']};"));

        $cData['indexcats'] = mysql_num_rows(mysql_query("SELECT DISTINCT `category` FROM `{$dData['tablename']}`;"));

        $cData['encodings'] = array();
        $encodings = mysql_query("SELECT `encoding`, COUNT(*) as `num` FROM `{$dData['tablename']}` GROUP BY `encoding` ORDER BY `num` DESC;");
        while($row = mysql_fetch_assoc($encodings)) $cData['encodings'][] = $row;
      }
      break;

    case "Spider": /* ******************************************** */
      set_vData("c.spkey", md5(time()));

      $cData['snf'] = false;
      $cData['ser'] = true;
      if ($vData['sp.pathto'] != "http://") {
        $uri = parse_url($vData['sp.pathto']);
        $uri['full'] = $uri['path'].((isset($uri['query'])) ? "?{$uri['query']}" : "");

        if (isset($uri['host']) && $conn = fsockopen($uri['host'], ((isset($uri['port'])) ? $uri['port'] : 80), $erstr, $errno, 5)) {
          fwrite($conn, "HEAD {$uri['full']} HTTP/1.0\r\nHost: {$uri['host']}\r\nUser-Agent: {$dData['userAgent']}\r\n\r\n");
          while (!feof($conn)) {
            $data = fgets($conn, 1024);

            if (preg_match("/^HTTP\/1\.\d [^2]\d\d/i", $data)) {
              $cData['snf'] = true;
              break;
            }

            if (preg_match("/^OrcaScript: Search_Spider/", $data)) {
              $cData['ser'] = false;
              break;
            }
          }
        }
      }

      $cData['cronsp'] = parse_url($vData['sp.pathto']);

      list($cData['indexpages']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}`;"));
      list($cData['utf8pages']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `encoding`='UTF-8';"));
      break;

    default: /* ************************************************** */
      $_GET['start'] = (isset($_GET['start'])) ? $_GET['start'] : ((isset($_POST['start'])) ? $_POST['start'] : 0);

      $cData['categories'] = array();
      $select = mysql_query("SELECT DISTINCT `category` FROM `{$dData['tablename']}`;");
      while ($row = mysql_fetch_assoc($select)) $cData['categories'][] = $row['category'];

      if ($cData['command'] == "add" ||
          $cData['command'] == "edit" ||
          ($cData['command'] == "action" && $cData['input'] == "category") ||
          ($cData['command'] == "action" && $cData['input'] == "sm.priority")) {

        // Load nothing

      } else {

        /* ***** Sorting ***************************************** */
        if (isset($_GET['column']) && in_array($_GET['column'], array("title", "uri"))) {
          set_vData("c.column", $_GET['column']);
          set_vData("c.sortby", "col1");
          set_vData("cf.textexclude", "");
          set_vData("cf.textmatch", "");
        }
        if (isset($_GET['sortby']) && in_array($_GET['sortby'], array("col1", "col2")))
          set_vData("c.sortby", $_GET['sortby']);

        $sqlData['orderby'] = " ORDER BY ".(($vData['c.sortby'] == "col2") ? "`category`, " : "")."`{$vData['c.column']}`";

        $sData['orderby1'] = "<em>%1\$s</em>";
        $sData['orderby2'] = "<a href=\"?sortby=%2\$s\">%1\$s</a>";
        $sData['orderby3'] = "<a href=\"?column=%2\$s\"><small>%1\$s</small></a>";

        /* ***** Filters ***************************************** */
        list($cData['new']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `new`='true';"));

        $sqlData['filters'] = "";
        if ($vData['cf.textexclude']) $sqlData['filters'] .= " `{$vData['c.column']}` NOT LIKE '%".addslashes(stripslashes($vData['cf.textexclude']))."%' AND";
        if ($vData['cf.textmatch']) $sqlData['filters'] .= " `{$vData['c.column']}` LIKE '%".addslashes(stripslashes($vData['cf.textmatch']))."%' AND";
        if ($vData['cf.category'] != "-") $sqlData['filters'] .= " `category`='{$vData['cf.category']}' AND";
        if ($vData['cf.new'] == "true") $sqlData['filters'] .= " `new`='true' AND";

        switch ($vData['cf.status']) {
          case "OK":
          case "Orphan":
          case "Unread":
            foreach ($sData['noSearchSQL'] as $noSearch)
              $sqlData['filters'] .= " `uri` NOT ".(($noSearch{0} == "*") ? "REGEXP '".substr($noSearch, 1)."'": " LIKE '%{$noSearch}%'")." AND";
            $sqlData['filters'] .= " `status`='{$vData['cf.status']}' AND `unlist`='false' AND";
            break;
          case "Blocked":
            $sqlData['filters'] .= " `status`='Blocked' AND";
            break;
          case "Unlisted":
            $build = "";
            foreach ($sData['noSearchSQL'] as $noSearch)
              $build .= "`uri` ".(($noSearch{0} == "*") ? "REGEXP '".substr($noSearch, 1)."'": " LIKE '%{$noSearch}%'")." OR ";
            $sqlData['filters'] .= " ({$build}`unlist`='true') AND `status`!='Not Found' AND";
            break;
          case "Not Found":
            $sqlData['filters'] .= " `status`='Not Found' AND";
            break;
        }

        $cData['nofilters'] = ($vData['cf.textexclude'] === "" && $vData['cf.textmatch'] === "" && $vData['cf.category'] === "-" && $vData['cf.status'] === "All" && $vData['cf.new'] === "false") ? true : false;
        if ($sqlData['filters']) $sqlData['filters'] = " WHERE".preg_replace("/ AND$/", "", $sqlData['filters']);

        list($cData['count']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}`{$sqlData['filters']}{$sqlData['orderby']};"));

        $cData['start'] = ($cData['count'] <= $vData['c.pagination']) ? 0 : $_GET['start'];
        $cData['end'] = min($cData['start'] + $vData['c.pagination'], $cData['count']);

        $cData['list'] = mysql_query("SELECT `title`, `uri`, `category`, `status`, `md5`, `new`, `unlist`, `sm.list`, `sm.changefreq`, `sm.priority` FROM `{$dData['tablename']}`{$sqlData['filters']}{$sqlData['orderby']} LIMIT {$cData['start']}, {$vData['c.pagination']};");
        $cData['rows'] = mysql_num_rows($cData['list']);
      }
  }
}


/* *************************************************************** */
/* ***** Do not cache this page ********************************** */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


/* *************************************************************** */
/* ***** Start HTML ********************************************** */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>Orca Search - <?php echo $_LANG['00026']; ?></title>
  <meta http-equiv="Content-type" content="text/html; charset=<?php echo $vData['c.charset']; ?>;" />
  <link rel="stylesheet" type="text/css" href="control.css" />
</head>
<body id="osc_body">
  <?php if (!$dData['online']) { ?> 
    <h3 class="warning"><?php echo $_LANG['0003m']; ?></h3>
    <pre class="warning">
      <?php echo $dData['errno'], " - ", $dData['error']; ?> 
    </pre>

  <?php } else if (!$cData['loggedIn']) { ?> 
    <?php if (isset($eData['error'])) { ?> 
      <ul id="error">
        <?php foreach ($eData['error'] as $error) { ?> 
          <li>Error: <?php echo $error; ?></li>
        <?php } ?> 
      </ul>
    <?php } ?> 

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="login">
      <h3><?php echo $_LANG['00000']; ?></h3>
      <div>
        <label><?php echo $_LANG['00001']; ?>: <input type="text" name="loginName" size="10" /></label>
        <label><?php echo $_LANG['00002']; ?>: <input type="password" name="loginPass" size="10" /></label>
        <input type="submit" value="<?php echo $_LANG['00003']; ?>" />
      </div>
    </form>
  <?php } else { ?> 
    <ul id="menu">
      <li class="first">Orca Search v<?php echo $dData['version']; ?></li>
      <li><a href="?Logout"><?php echo $_LANG['00020']; ?></a></li>
      <li><a href="?Stats"<?php if ($vData['c.location'] == "Stats") echo " class=\"selected\""; ?>><?php echo $_LANG['00021']; ?></a></li>
      <li><a href="?Spider"<?php if ($vData['c.location'] == "Spider") echo " class=\"selected\""; ?>><?php echo $_LANG['00022']; ?></a></li>
      <li><a href="?List"<?php if ($vData['c.location'] == "List") echo " class=\"selected\""; ?>><?php echo $_LANG['00023']; ?></a></li>
      <li><a href="?Search"<?php if ($vData['c.location'] == "Search") echo " class=\"selected\""; ?>><?php echo $_LANG['00024']; ?></a></li>
      <li><a href="?Tools"<?php if ($vData['c.location'] == "Tools") echo " class=\"selected\""; ?>><?php echo $_LANG['00025']; ?></a></li>
    </ul>

    <?php if (isset($eData['error'])) { ?> 
      <ul id="error">
        <?php foreach ($eData['error'] as $error) { ?> 
          <li><?php echo $_LANG['00030']; ?>: <?php echo $error; ?></li>
        <?php } ?> 
      </ul>
    <?php }
    if (isset($eData['success'])) { ?> 
      <ul id="success">
        <?php foreach ($eData['success'] as $success) { ?> 
          <li><?php echo $success; ?></li>
        <?php } ?> 
      </ul>
    <?php } ?> 


    <?php switch ($vData['c.location']) {
      case "Tools": /* ***** Tools **************************** */ ?> 
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="optionform">
          <h3><?php echo $_LANG['00090']; ?></h3>
          <ul>
            <li>
              <var><input type="text" size="15" maxlength="20" name="charset" value="<?php echo htmlspecialchars($vData['c.charset']); ?>" /></var>
              <h4 title="<?php echo $_LANG['00092']; ?>"><?php echo $_LANG['00091']; ?></h4>
              <div></div>
            </li>
            <li class="drow">
              <var><input type="submit" name="control_Control" value="<?php echo $_LANG['00010']; ?>" /></var>
              <h4><?php echo $_LANG['00011']; ?></h4>
              <div></div>
            </li>
          </ul>
        </form>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="optionform">
          <h3><?php echo $_LANG['00093']; ?></h3>
          <ul>
            <li>
              <var><input type="checkbox" name="enable" value="true"<?php if ($vData['sm.enable'] == "true") echo " checked=\"checked\""; ?>" /></var>
              <h4><?php echo $_LANG['00094']; ?></h4>
              <div></div>
            </li>
            <?php if ($vData['sm.enable'] == "true") { ?> 
              <li class="drow">
                <var><input type="text" size="70" name="pathto" value="<?php echo htmlspecialchars($vData['sm.pathto']); ?>" /></var>
                <h4><?php echo $_LANG['00095']; ?></h4>
                <?php if ($cData['smnf']) {
                  ?><span class="warning"><?php echo $_LANG['00096']; ?></span><?php
                } else if ($cData['smnw']) {
                  ?><span class="warning"><?php echo $_LANG['00097']; ?></span><?php
                } ?> 
                <div></div>
              </li>
              <li>
                <var><input type="checkbox" name="gzip" value="true"<?php if ($dData['zlib'] && $vData['sm.gzip'] == "true") echo " checked=\"checked\""; ?>" <?php if (!$dData['zlib']) echo " disabled=\"disabled\""; ?> /></var>
                <h4><?php echo $_LANG['00098']; ?></h4>
                <?php if (!$dData['zlib']) { ?><span class="warning"><?php echo $_LANG['00099']; ?></span><?php } ?> 
                <div></div>
              </li>
              <li class="drow">
                <var><select name="domain" size="1"<?php if (!count($cData['domains'])) echo " disabled=\"disabled\""; ?>>
                  <?php if (count($cData['domains'])) {
                    reset ($cData['domains']);
                    while (list($key,) = each($cData['domains'])) { ?> 
                      <option value="<?php echo $key; ?>"<?php if ($key == $vData['sm.domain']) echo " selected=\"selected\""; ?>><?php echo $key; ?></option><?php
                    } 
                  } else { ?> 
                    <option><?php echo $_LANG['0009c']; ?></option><?php
                  } ?>
                </select></var>
                <h4 title="<?php echo $_LANG['0009b']; ?>"><?php echo $_LANG['0009a']; ?></h4>
                <div></div>
              </li>
              <li>
                <var><input type="checkbox" name="unlisted" value="true"<?php if ($vData['sm.unlisted'] == "true") echo " checked=\"checked\""; ?>" /></var>
                <h4 title="<?php echo $_LANG['0009e']; ?>"><?php echo $_LANG['0009d']; ?></h4>
                <div></div>
              </li>
              <li class="drow">
                <var><input type="checkbox" name="changefreq" value="true"<?php if ($vData['sm.changefreq'] == "true") echo " checked=\"checked\""; ?>" /></var>
                <h4><?php echo $_LANG['0009f']; ?></h4>
                <?php echo $_LANG['0009g']; ?> 
                <div></div>
              </li>
            <?php } ?>
            <li<?php if ($vData['sm.enable'] != "true") echo " class=\"drow\""; ?>>
              <var><input type="submit" name="sitemap_Control" value="<?php echo $_LANG['00010']; ?>" /></var>
              <h4><?php echo $_LANG['00011']; ?></h4>
              <div></div>
            </li>
          </ul>
          <?php if ($vData['sm.enable'] == "true") { ?> 
            <h3><?php echo $_LANG['0009h']; ?></h3>
            <ul>
              <li>
                <var><input type="submit" name="sitemap_Commit" value="<?php echo $_LANG['0009k']; ?>"<?php if ($cData['smnf'] || $cData['smnw'] || !count($cData['domains'])) echo " disabled=\"disabled\""; ?> /></var>
                <h4 title="<?php echo $_LANG['0009i']; ?>"><?php echo $_LANG['0009h']; ?></h4>
                <?php echo $_LANG['0009j']; ?> 
                <div></div>
              </li>
            </ul>
          <?php } ?> 
        </form>


        <?php if ($count) { ?> 
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="optionform">
            <h3><?php echo $_LANG['0009l']; ?></h3>
            <ul>
              <li>
                <var><input type="checkbox" name="hide" value="true"<?php if ($vData['jw.hide'] != "true") echo " checked=\"checked\""; ?>" /></var>
                <h4><?php echo $_LANG['0009m']; ?></h4>
                <div></div>
              </li>
              <?php if ($vData['jw.hide'] == "false") { ?> 
                <li class="drow">
                  <var><input type="text" size="70" name="writer" value="<?php echo htmlspecialchars($vData['jw.writer']); ?>" /></var>
                  <h4><?php echo $_LANG['0009n']; ?></h4>
                  <?php if ($cData['wnf']) { ?><span class="warning"><?php echo $_LANG['0009o']; ?></span><?php } ?> 
                  <div></div>
                </li>
                <li>
                  <var><input type="text" size="70" name="egg" value="<?php echo htmlspecialchars($vData['jw.egg']); ?>" /></var>
                  <h4><?php echo $_LANG['0009p']; ?></h4>
                  <?php if ($cData['jnf']) {
                    ?><span class="warning"><?php echo $_LANG['00096']; ?></span><?php
                  } else if ($cData['jnw']) {
                    ?><span class="warning"><?php echo $_LANG['00097']; ?></span><?php
                  } ?> 
                  <div></div>
                </li>
                <li class="drow">
                  <var><textarea rows="3" cols="40" name="remuri"><?php echo htmlspecialchars($vData['jw.remuri']); ?></textarea></var>
                  <h4><?php echo $_LANG['0009q']; ?></h4>
                  <?php echo $_LANG['0009r']; ?> 
                  <div></div>
                </li>
                <li>
                  <var><input type="text" size="20" name="index" value="<?php echo htmlspecialchars($vData['jw.index']); ?>" /></var>
                  <h4><?php echo $_LANG['0009s']; ?></h4>
                  <?php echo $_LANG['0009t']; ?> 
                  <div></div>
                </li>
                <li class="drow">
                  <var><textarea rows="4" cols="30" name="extfrom"><?php echo htmlspecialchars($vData['jw.extfrom']); ?></textarea></var>
                  <h4><?php echo $_LANG['0009u']; ?></h4>
                  <?php echo $_LANG['0009v']; ?> 
                  <div></div>
                </li>
                <li>
                  <var><input type="text" size="7" name="extto" value="<?php echo htmlspecialchars($vData['jw.extto']); ?>" /></var>
                  <h4><?php echo $_LANG['0009w']; ?></h4>
                  <?php echo $_LANG['0009x']; ?> 
                  <div></div>
                </li>
                <li class="drow">
                  <var><input type="text" size="4" name="pagination" value="<?php echo $vData['jw.pagination']; ?>" /></var>
                  <h4 title="<?php echo $_LANG['0009z']; ?>"><?php echo $_LANG['0009y']; ?></h4>
                  <div></div>
                </li>
                <li>
                  <h4><?php echo $_LANG['000a0']; ?></h4>
                  <?php echo $_LANG['000a1']; ?>
                  <var><textarea rows="10" cols="78" name="template"><?php echo htmlspecialchars($vData['jw.template']); ?></textarea></var>
                  <div></div>
                </li>
              <?php } ?>
              <li class="drow">
                <var><input type="submit" name="jwriter_Options" value="<?php echo $_LANG['00010']; ?>" /></var>
                <h4><?php echo $_LANG['00011']; ?></h4>
                <div></div>
              </li>
            </ul>
            <?php if ($vData['jw.hide'] == "false") { ?> 
              <h3><?php echo $_LANG['000a2']; ?></h3>
              <ul>
                <li>
                  <var><input type="submit" name="location_Javabuild" value="<?php echo $_LANG['000a4']; ?>"<?php if ($cData['wnf'] || $cData['jnf'] || $cData['jnw']) echo " disabled=\"disabled\""; ?> /></var>
                  <h4><?php echo $_LANG['000a3']; ?></h4>
                  <?php printf($_LANG['000a5'], sprintf("%01.2f", $cData['indexmem'] / 1048576), sprintf("%01.2f", $cData['indexmem'] * 2 / 1048576)); ?><br />
                  <?php if (!$cData['wnf'] && !$cData['jnf'] && !$cData['jnw']) {
                    set_vData("jw.key", md5(time())); ?> 
                    <input type="hidden" name="key" value="<?php echo $vData['jw.key']; ?>" />
                    <?php if (!$dData['memlimit']) {
                      ?><span class="warning"><?php echo $_LANG['000a6']; ?></span><br />
                      <?php if ($dData['memlimit'] === NULL) {
                        ?><?php echo $_LANG['000a7']; ?><br />
                      <?php } else {
                        ?><?php printf($_LANG['000a8'], ini_get("memory_limit")); ?><br />
                      <?php } ?> 
                    <?php } else {
                      ?><strong><?php echo $_LANG['000a9']; ?></strong><br />
                    <?php }
                  } ?> 
                  <div></div>
                </li>
              </ul>
            <?php } ?> 
          </form>
        <?php }
        break;


      case "Search": /* ***** Search ************************** */ ?> 
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="optionform">
          <h3><?php echo $_LANG['000c0']; ?></h3>
          <ul>
            <li>
              <var><input type="submit" name="search_Purge" value="<?php echo $_LANG['000d2']; ?>" /></var>
              <h4 title="<?php echo $_LANG['000c2']; ?>"><?php echo $_LANG['000c1']; ?></h4>
              <div></div>
            </li>
            <li class="drow">
              <var><strong title="<?php echo $_LANG['000d3']; ?>"><?php echo ceil($cData['cachekb']); ?></strong> kB <big>/</big>
                <input type="text" size="5" name="cachelimit" value="<?php echo $vData['s.cachelimit']; ?>" /> kB</var>
              <h4 title="<?php echo $_LANG['000c4']; ?>"><?php echo $_LANG['000c3']; ?></h4>
              <div></div>
            </li>
            <li>
              <var><input type="checkbox" name="cachegzip" value="on"<?php if ($vData['s.cachegzip'] == "on") echo " checked=\"checked\""; if ($vData['s.cachegzip'] == "disabled") echo " disabled=\"disabled\""; ?> /></var>
              <h4><?php echo $_LANG['000c5']; ?></h4><?php
              if ($cData['cachedno']) { ?> 
                <?php printf($_LANG['000c6'], $cData['cachedno'], sprintf("%01.2f", $cData['cachekb'] / $cData['cachedno'])); 
              } else { ?> 
                <?php echo $_LANG['000c7'];
              }
              if ($vData['s.cachegzip'] == "disabled") { ?><br />
                <span class="warning"><?php echo $_LANG['000c8']; ?></span><?php
              } ?> 
              <div></div>
            </li>
            <li class="drow">
              <var><input type="submit" name="search_Cache" value="<?php echo $_LANG['00010']; ?>" /></var>
              <h4><?php echo $_LANG['00011']; ?></h4>
              <div></div>
            </li>
          </ul>
        </form>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="optionform">
          <h3><?php echo $_LANG['000c9']; ?></h3>
          <ul>
            <li>
              <var><textarea rows="6" cols="40" name="ignore"><?php echo htmlspecialchars($vData['s.ignore']); ?></textarea></var>
              <h4><?php echo $_LANG['000ca']; ?></h4>
              <?php echo $_LANG['000cb']; ?>
              <div></div>
            </li>
            <li class="drow">
              <var><input type="text" size="5" maxlength="2" name="termlimit" value="<?php echo $vData['s.termlimit']; ?>" /> <?php echo $_LANG['000ce']; ?></var>
              <h4 title="<?php echo $_LANG['000cd']; ?>"><?php echo $_LANG['000cc']; ?></h4>
              <div></div>
            </li>
            <li>
              <var><input type="text" size="5" maxlength="2" name="termlength" value="<?php echo $vData['s.termlength']; ?>" /> <?php echo $_LANG['000ch']; ?></var>
              <h4 title="<?php echo $_LANG['000cg']; ?>"><?php echo $_LANG['000cf']; ?></h4>
              <div></div>
            </li>
            <li class="drow">
              <var><?php echo $_LANG['000ck']; ?>: <input type="text" size="5" maxlength="5" name="weight0" value="<?php echo $vData['s.weight'][0]; ?>" /><br />
                   <?php echo $_LANG['000cl']; ?>: <input type="text" size="5" maxlength="5" name="weight1" value="<?php echo $vData['s.weight'][1]; ?>" /><br />
                   <?php echo $_LANG['000cm']; ?>: <input type="text" size="5" maxlength="5" name="weight2" value="<?php echo $vData['s.weight'][2]; ?>" /><br />
                   <span title="<?php echo $_LANG['000co']; ?>"><?php echo $_LANG['000cn']; ?></span>: <input type="text" size="5" maxlength="5" name="weight3" value="<?php echo $vData['s.weight'][3]; ?>" /><br />
                   <?php echo $_LANG['00027']; ?>: <input type="text" size="5" maxlength="5" name="weight4" value="<?php echo $vData['s.weight'][4]; ?>" /></var>
              <h4 title="<?php echo $_LANG['000cj']; ?>"><?php echo $_LANG['000ci']; ?></h4>
              <div></div>
            </li>
            <li>
              <var><?php echo $_LANG['000cq']; ?>: <input type="text" size="5" maxlength="5" name="weight5" value="<?php echo $vData['s.weight'][5]; ?>" /><br />
                   <?php echo $_LANG['000cr']; ?>: <input type="text" size="5" maxlength="5" name="weight6" value="<?php echo $vData['s.weight'][6]; ?>" /></var>
              <h4><?php echo $_LANG['000cp']; ?></h4>
              <div></div>
            </li>
            <li class="drow">
              <var><input type="checkbox" name="latinacc" value="true"<?php if ($vData['s.latinacc'] == "true") echo " checked=\"checked\""; ?> /></var>
              <h4><?php echo $_LANG['000cs']; ?></h4>
              <?php echo $_LANG['000ct']; ?>
              <div></div>
            </li>
            <li>
              <var><textarea rows="3" cols="20" name="weightedtags"><?php echo htmlspecialchars($vData['s.weightedtags']); ?></textarea></var>
              <h4><?php echo $_LANG['000cu']; ?></h4>
              <?php echo $_LANG['000cv']; ?> 
              <span class="warning"><?php echo $_LANG['00012']; ?></span>: <?php echo $_LANG['000cw']; ?>
              <div></div>
            </li>
            <li class="drow">
              <var><input type="text" size="5" maxlength="4" name="resultlimit" value="<?php echo $vData['s.resultlimit']; ?>" /> <?php echo $_LANG['000cy']; ?></var>
              <h4><?php echo $_LANG['000cx']; ?></h4>
              <div></div>
            </li>
            <li>
              <var><input type="text" size="5" maxlength="4" name="matchingtext" value="<?php echo $vData['s.matchingtext']; ?>" /> <?php echo $_LANG['000ch']; ?></var>
              <h4><?php echo $_LANG['000cz']; ?></h4>
              <div></div>
            </li>
            <li class="drow">
              <var><input type="checkbox" name="orphans" value="show"<?php if ($vData['s.orphans'] == "show") echo " checked=\"checked\""; ?> /></var>
              <h4 title="<?php echo $_LANG['000d1']; ?>"><?php echo $_LANG['000d0']; ?></h4>
              <div></div>
            </li>
            <li>
              <var><input type="submit" name="search_Edit" value="<?php echo $_LANG['00010']; ?>" /></var>
              <h4><?php echo $_LANG['00011']; ?></h4>
              <div></div>
            </li>
          </ul>
        </form>
        <?php break;


      case "Spider": /* ***** Spider ************************** */ ?> 
        <form action="<?php echo htmlspecialchars($vData['sp.pathto']); ?>" method="post" class="optionform">
          <h3><?php echo $_LANG['000i0']; ?></h3>
          <ul>
            <?php if ($vData['sp.lock'] == "true") {
              $spoo = time() - $vData['sp.progress'];
              $swat = 30; ?> 
              <li>
                <var><input type="submit" name="spider_Force" value="<?php echo $_LANG['000i2']; ?>"<?php if ($cData['snf'] || $vData['sp.lock'] == "false" || $spoo <= $swat) echo " disabled=\"disabled\""; ?> /></var>
                <h4><?php echo $_LANG['000i1']; ?></h4>
                <span class="warning"><?php echo $_LANG['000i3']; ?></span>
                <?php if ($spoo <= $swat) { ?> 
                  <?php echo $_LANG['000i4']; ?> 
                <?php } else { ?> 
                  <?php printf($_LANG['000i5'], $swat); ?> 
                <?php } ?> 
                <div></div>
              </li>
            <?php } else { ?> 
              <li>
                <var><input type="submit" name="spider_Go" value="<?php echo $_LANG['000i8']; ?>"<?php if ($cData['snf'] || $vData['sp.lock'] == "true") echo " disabled=\"disabled\""; ?> /></var>
                <h4><?php echo $_LANG['000i6']; ?></h4>
                <?php if ($vData['sp.time'] == -1) { ?> 
                  <span class="warning"><?php echo $_LANG['00013']; ?></span>: <?php echo $_LANG['000i7']; ?> 
                <?php } ?> 
                <div></div>
              </li>
            <?php } ?> 
            <li class="drow">
              <var><?php
                if ($vData['sp.time'] == -1) {
                  echo $_LANG['000ia'];
                } else countUp($vData['sp.time']);
              ?></var>
              <input type="hidden" name="key" value="<?php echo $vData['c.spkey']; ?>" />
              <input type="hidden" name="linkback" value="http://<?php echo $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF']; ?>" />
              <h4><?php echo $_LANG['000i9']; ?></h4>
              <div></div>
            </li>
          </ul>
        </form>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="optionform">
          <h3><?php echo $_LANG['000ib']; ?></h3>
          <ul>
            <li>
              <var><input type="text" size="70" name="pathto" value="<?php echo htmlspecialchars($vData['sp.pathto']); ?>" /></var>
              <h4><?php echo $_LANG['000ic']; ?></h4>
              <?php if ($cData['snf']) {
                ?><span class="warning"><?php echo $_LANG['000id']; ?></span><?php
              } else if ($cData['ser']) {
                ?><span class="warning"><?php echo $_LANG['000ja']; ?></span><br />
                <?php printf($_LANG['000jb'], htmlspecialchars($vData['sp.pathto'])); 
              } ?> 
              <div></div>
            </li>
            <li class="drow">
              <var><textarea rows="3" cols="70" name="start" class="smooth"><?php echo htmlspecialchars($vData['sp.start']); ?></textarea></var>
              <h4><?php echo $_LANG['000ie']; ?></h4>
              <div></div>
            </li>
            <li>
              <var><input type="checkbox" name="cron" value="true"<?php if ($vData['sp.cron'] == "true") echo " checked=\"checked\""; ?>" /></var>
              <h4><?php echo $_LANG['000if']; ?></h4>
              <span class="warning"><?php echo $_LANG['00013']; ?></span>: <?php echo $_LANG['000ig']; ?> 
              <div></div>
            </li>
            <li class="drow">
              <var><input type="text" size="5" name="interval" value="<?php echo $vData['sp.interval']; ?>"<?php if ($vData['sp.cron'] == "true") echo " disabled=\"disabled\""; ?> /> Hours</var>
              <h4 title="<?php echo $_LANG['000ii']; ?>"><?php echo $_LANG['000ih']; ?></h4>
              <div></div>
            </li>
            <li>
              <var><input type="text" size="7" name="pagelimit" value="<?php echo $vData['sp.pagelimit']; ?>" /></var>
              <h4><?php echo $_LANG['000ij']; ?></h4>
              <div></div>
            </li>
            <li class="drow">
              <var><input type="text" size="20" name="defcat" value="<?php echo $vData['sp.defcat']; ?>" /></var>
              <h4 title="<?php echo $_LANG['000il']; ?>"><?php echo $_LANG['000ik']; ?></h4>
              <div></div>
            </li>
            <li>
              <var><textarea rows="4" cols="40" name="autocat"><?php echo htmlspecialchars($vData['sp.autocat']); ?></textarea></var>
              <h4><?php echo $_LANG['000im']; ?></h4>
              <?php echo $_LANG['000in']; ?> 
              <div></div>
            </li>
            <li class="drow">
              <var><input type="text" size="60" name="email" value="<?php echo htmlspecialchars($vData['sp.email']); ?>"<?php if ($vData['sp.cron'] == "true") echo " disabled=\"disabled\""; ?> /></var>
              <h4 title="<?php echo $_LANG['000ip']; ?>"><?php echo $_LANG['000io']; ?></h4>
              <div></div>
            </li>
            <li>
              <h4 class="warning"><?php echo $_LANG['000iq']; ?></h4>
              <?php echo $_LANG['000ir']; ?> 
              <div></div>
            </li>
            <li class="drow">
              <var><input type="checkbox" name="utf8" value="true"<?php if ($vData['sp.utf8'] == "true") echo " checked=\"checked\""; ?> /></var>
              <h4><?php echo $_LANG['000is']; ?></h4>
              <?php echo $_LANG['000it']; ?> 
              <?php if ($cData['indexpages']) { ?> 
                <em><?php printf($_LANG['000iu'], sprintf("%01.1f", $cData['utf8pages'] * 100 / $cData['indexpages'])); ?></em>
              <?php } ?> 
              <div></div>
            </li>
            <li>
              <var><textarea rows="4" cols="30" name="mimetypes"><?php echo htmlspecialchars($vData['sp.mimetypes']); ?></textarea></var>
              <h4><?php echo $_LANG['000iv']; ?></h4>
              <div></div>
            </li>
            <li class="drow">
              <var><textarea rows="4" cols="30" name="domains"><?php echo htmlspecialchars($vData['sp.domains']); ?></textarea></var>
              <h4><?php echo $_LANG['000iw']; ?></h4>
              <?php echo $_LANG['000ix']; ?> 
              <div></div>
            </li>
            <li>
              <var><textarea rows="4" cols="40" name="require"><?php echo htmlspecialchars($vData['sp.require']); ?></textarea></var>
              <h4 title="<?php echo $_LANG['000j8']; ?>"><?php echo $_LANG['000j7']; ?></h4>
              <?php echo $_LANG['000j9']; ?> 
              <?php echo $_LANG['000iz']; ?> 
              <div></div>
            </li>
            <li class="drow">
              <var><textarea rows="6" cols="40" name="ignore"><?php echo htmlspecialchars($vData['sp.ignore']); ?></textarea></var>
              <h4><?php echo $_LANG['000iy']; ?></h4>
              <?php echo $_LANG['000iz']; ?> 
              <div></div>
            </li>
            <li>
              <var><textarea rows="4" cols="40" name="extensions"><?php echo htmlspecialchars($vData['sp.extensions']); ?></textarea></var>
              <h4><?php echo $_LANG['000j0']; ?></h4>
              <?php echo $_LANG['000j1']; ?> 
              <div></div>
            </li>
            <li class="drow">
              <var><textarea rows="4" cols="40" name="remtitle"><?php echo htmlspecialchars($vData['sp.remtitle']); ?></textarea></var>
              <h4><?php echo $_LANG['000j2']; ?></h4>
              <?php echo $_LANG['000j3']; ?> 
              <?php echo $_LANG['000iz']; ?> 
              <div></div>
            </li>
            <li>
              <var><textarea rows="3" cols="40" name="remtags"><?php echo htmlspecialchars($vData['sp.remtags']); ?></textarea></var>
              <h4><?php echo $_LANG['000j4']; ?></h4>
              <?php echo $_LANG['000j5']; ?> 
              <?php echo $_LANG['000j6']; ?> 
              <div></div>
            </li>
            <li class="drow">
              <var><input type="submit" name="spider_Edit" value="<?php echo $_LANG['00010']; ?>" /></var>
              <h4><?php echo $_LANG['00011']; ?></h4>
              <div></div>
            </li>
          </ul>
        </form>
        <?php break;


      case "Stats": /* ***** Statistics *********************** */ ?> 
        <?php if ($vData['sp.lasttime'] != -1) { ?> 
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="optionform">
            <h3><?php echo $_LANG['000l0']; ?></h3>
            <ul>
              <li>
                <var><?php countUp($vData['sp.time']); ?></var>
                <h4><?php echo $_LANG['000l1']; ?></h4>
                <div></div>
              </li>
              <li class="drow">
                <var><strong><?php printf("%01.2f", $vData['sp.lasttime']); ?></strong> seconds</var>
                <h4 title="<?php echo $_LANG['000l3']; ?>"><?php echo $_LANG['000l2']; ?></h4>
                <div></div>
              </li>
              <li>
                <var><strong><?php printf("%01.2f", $vData['sp.alldata'] / 1048576); ?></strong>MB</var>
                <h4><?php echo $_LANG['000l4']; ?></h4>
                <div></div>
              </li>
              <li class="drow">
                <var><strong><?php printf("%01.2f", $cData['indexmem'] / 1048576); ?></strong>MB</var>
                <h4><?php echo $_LANG['000l5']; ?></h4>
                <div></div>
              </li>
              <li>
                <var><strong><?php echo ($vData['sp.alldata']) ? sprintf("%01.1f", $cData['indexmem'] * 100 / $vData['sp.alldata']) : "--.-"; ?></strong>%</var>
                <h4><?php echo $_LANG['000l6']; ?></h4>
                <div></div>
              </li>
              <li class="drow">
                <var><strong><?php echo $cData['indexpages']; ?></strong></var>
                <h4><?php echo $_LANG['000l7']; ?></h4>
                <div></div>
              </li>
              <li>
                <var><strong><?php echo $cData['indexsrchd']; ?></strong></var>
                <h4 title="<?php echo ($vData['s.orphans'] == "show") ? $_LANG['000l9'] : $_LANG['000la']; ?>"><?php echo $_LANG['000l8']; ?></h4>
                <div></div>
              </li>
              <li class="drow">
                <var><strong><?php echo $cData['indexcats']; ?></strong></var>
                <h4><?php echo $_LANG['000lb']; ?></h4>
                <div></div>
              </li>
              <?php if ($cData['indexpages']) { ?> 
                <li>
                  <var><?php foreach ($cData['encodings'] as $encodings) {
                    if ($encodings['encoding'] == "-") $encodings['encoding'] = $_LANG['000le'];
                    echo "{$encodings['encoding']}: <strong>".sprintf("%01.1f", $encodings['num'] * 100 / $cData['indexpages'])."%</strong><br />\n";
                  } ?></var>
                  <h4 title="<?php echo $_LANG['000ld']; ?>"><?php echo $_LANG['000lc']; ?></h4>
                  <div></div>
                </li>
              <?php } ?> 
            </ul>
          </form>
        <?php } ?> 

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="optionform">
          <h3><?php echo $_LANG['000lf']; ?></h3>
          <ul>
            <li>
              <var><input type="submit" name="stats_Reset" value="<?php echo $_LANG['000li']; ?>" onclick="return confirm('<?php echo $_LANG['000lj']; ?>');" /></var>
              <h4><?php echo $_LANG['000lg']; ?></h4>
              <?php echo $_LANG['000lh']; ?> 
              <div></div>
            </li>
            <li class="drow">
              <var><input type="text" size="5" maxlength="3" name="cachereset" value="<?php echo $vData['s.cachereset']; ?>" /> Days
                <input type="submit" name="stats_Interval" value="<?php echo $_LANG['000lm']; ?>" /></var>
              <h4 title="<?php echo $_LANG['000ll']; ?>"><?php echo $_LANG['000lk']; ?></h4>
              <div></div>
            </li>
            <li>
              <var><?php countUp($vData['s.cachetime']); ?></var>
              <h4><?php echo $_LANG['000ln']; ?></h4>
              <div></div>
            </li>
          </ul>
        </form>

        <?php $select = mysql_query("SELECT `astyped`, `hits`, `lasthit` FROM `{$dData['tablestat']}` ORDER BY `hits` DESC;");
        if (mysql_num_rows($select)) { ?> 
          <table cellspacing="0" border="0" id="querylog">
            <thead>
              <tr>
                <td colspan="3"><?php echo $_LANG['000lo']; ?></td>
              </tr>
              <tr>
                <th title="<?php echo $_LANG['000lq']; ?>"><?php echo $_LANG['000lp']; ?></th>
                <th title="<?php echo $_LANG['000ls']; ?>"><?php echo $_LANG['000lr']; ?></th>
                <th title="<?php echo $_LANG['000lu']; ?>"><?php echo $_LANG['000lt']; ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $y = 1; $timeColl = array();            
              while ($row = mysql_fetch_assoc($select)) { ?> 
                <tr<?php echo ($y++ % 2) ? " class=\"drow\"" : ""; ?>>
                  <th><?php echo htmlspecialchars($row['astyped']); ?></th>
                  <td><?php echo $row['hits']; ?></td>
                  <td><?php 
                    $timeColl[] = $row['lasthit'];
                    $diff = time() - $row['lasthit'];
                    $days = floor($diff / 86400);
                    if (!($days = floor($diff / 86400))) {
                      if (!($hours = floor($diff / 3600))) {
                        if (!($minutes = floor($diff / 60))) {
                          $final = $diff." {$_LANG['00017']}";
                        } else $final = $minutes." {$_LANG['00016']}";
                      } else $final = $hours." {$_LANG['00015']}";
                    } else $final = $days." {$_LANG['00014']}";
                    echo $final, " {$_LANG['00018']}";
                  ?></td>
                </tr>
              <?php } ?> 
            </tbody>
          </table>
          <script type="text/javascript"><!--
            var qlnow = 1;
            var headers = document.getElementById("querylog").tHead.getElementsByTagName("th");
            for (var x = 0; x < headers.length; x++) headers[x].className = (x == qlnow) ? "on" : "off";
            headers[0].onclick = new Function("qlsort('asc', 0);");
            headers[1].onclick = new Function("qlsort('dsc', 1);");
            headers[2].onclick = new Function("qlsort('dsc', 2);");
            var times = [<?php foreach($timeColl as $tc) echo "$tc, "; ?>0];
            var qlrows = document.getElementById("querylog").tBodies[0].rows;
            var qldata = new Array();
            for (var x = 0; x < qlrows.length; x++) {
              qldata[x] = new Array();
              for (var y = 0; y < qlrows[x].cells.length; y++) {
                if (y != 2) {
                  qldata[x][y] = qlrows[x].cells[y].firstChild.nodeValue;
                  if (y == 1) qldata[x][y] = parseInt(qldata[x][y]);
                } else qldata[x][y] = times[x];
              }
            }
            function qlsort(direc, column) {
              if (column != qlnow) {
                headers[qlnow].className = "off";
                qldata.sort(new Function("a, b", "var col = " + column + ", dir = " + ((direc == "asc") ? 1 : -1) + "; var acol = (!col) ? a[col].toLowerCase() : a[col]; var bcol = (!col) ? b[col].toLowerCase() : b[col]; if (acol == bcol) return 0; return (acol > bcol) ? dir : -(dir);"));
                for (var x = 0; x < qlrows.length; x++) {
                  qlrows[x].cells[0].firstChild.nodeValue = qldata[x][0];
                  qlrows[x].cells[1].firstChild.nodeValue = qldata[x][1];
                  var now = new Date(qldata[x][2] * 1000);
                  var sfinal = "";
                  var diff = <?php echo time(); ?> - now.getTime() / 1000;
                  var days = Math.floor(diff / 86400);
                  if (!days) {
                    var hours = Math.floor(diff / 3600);
                    if (!hours) {
                      var minutes = Math.floor(diff / 60);
                      if (!minutes) {
                        sfinal = diff + " <?php echo $_LANG['00017']; ?>";
                      } else sfinal = minutes + " <?php echo $_LANG['00016']; ?>";
                    } else sfinal = hours + " <?php echo $_LANG['00015']; ?>";
                  } else sfinal = days + " <?php echo $_LANG['00014']; ?>";
                  qlrows[x].cells[2].firstChild.nodeValue = sfinal + " <?php echo $_LANG['00018']; ?>";
                }
                qlnow = column;
                headers[qlnow].className = "on";
              }
            }
          // --></script>
        <?php }
        break;


      default: /* ***** Entry List ******************************* */
        if (!count($cData['categories']) && ($cData['command'] != "add" || !isset($cData['add']) || $cData['add'] == "AddConfirm")) { ?> 
          <h2><?php echo $_LANG['000f0']; ?></h2>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="editform">
            <input type="submit" name="add_Add" value="<?php echo $_LANG['000f1']; ?>" title="<?php echo $_LANG['000f2']; ?>" />
          </form>

        <?php } else {
          if ($cData['command'] == "action") { 
            switch ($cData['input']) {
              case "category": /* ***************************** */ ?> 
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="editform">
                  <h3><?php echo $_LANG['000f3']; ?></h3>
                  <ul>
                    <li><h4><?php echo $_LANG['000f4']; ?></h4>
                      <select name="categoryExist" size="1" title="<?php echo $_LANG['000f5']; ?>" onchange="document.getElementById('editform').categoryNew.disabled=(this.value!='-')?'disabled':'';">
                        <option value="-" selected="selected"><?php echo $_LANG['000f6']; ?> &gt;&gt;</option>
                        <?php foreach($cData['categories'] as $category) { ?> 
                          <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                        <?php } ?> 
                      </select>
                      <input type="text" name="categoryNew" />
                      <?php if ($_GET['start']) { ?> 
                        <input type="hidden" name="start" value="<?php echo $_GET['start']; ?>" />
                      <?php } ?> 
                      <input type="hidden" name="actionIDs" value="<?php echo implode("::", $_POST['action']); ?>" />
                      <input type="hidden" name="Confirm" value="catConfirm" />
                    </li>
                    <li><h4><a href="<?php echo $_SERVER['PHP_SELF'], (($_GET['start']) ? "?start={$_GET['start']}" : ""); ?>" title="<?php echo $_LANG['000f7']; ?>"><?php echo $_LANG['000f8']; ?></a></h4>
                      <input type="submit" name="action_Confirm" value="<?php echo $_LANG['00010']; ?>" />
                    </li>
                  </ul>
                </form>
                <?php break;

              case "sm.changefreq": /* ************************ */ ?> 
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="editform">
                  <h3><?php echo $_LANG['000f9']; ?></h3>
                  <ul>
                    <li><h4><?php echo $_LANG['000fa']; ?></h4>
                      <select name="changefreq" size="1">
                        <?php while (list($key, $value) = each($cData['langcf'])) {
                          ?><option value="<?php echo $key; ?>"<?php if ($key == "weekly") echo " selected=\"selected\""; ?>><?php echo $value; ?></option>
                          <?php
                        } ?> 
                      </select>
                      <input type="hidden" name="actionIDs" value="<?php echo implode("::", $_POST['action']); ?>" />
                      <input type="hidden" name="Confirm" value="smcConfirm" />
                    </li>
                    <li><h4><a href="<?php echo $_SERVER['PHP_SELF'], (($_GET['start']) ? "?start={$_GET['start']}" : ""); ?>" title="<?php echo $_LANG['000f7']; ?>"><?php echo $_LANG['000f8']; ?></a></h4>
                      <input type="submit" name="action_Confirm" value="<?php echo $_LANG['00010']; ?>" />
                    </li>
                  </ul>
                </form>
                <?php break;

              case "sm.priority": /* ************************** */ ?> 
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="editform">
                  <h3><?php echo $_LANG['000fb']; ?></h3>
                  <ul>
                    <li><h4><?php echo $_LANG['000fc']; ?></h4>
                      <input type="text" name="priority" size="5" value="0.5" />
                      <input type="hidden" name="actionIDs" value="<?php echo implode("::", $_POST['action']); ?>" />
                      <input type="hidden" name="Confirm" value="smpConfirm" />
                    </li>
                    <li><h4><a href="<?php echo $_SERVER['PHP_SELF'], (($_GET['start']) ? "?start={$_GET['start']}" : ""); ?>" title="<?php echo $_LANG['000f7']; ?>"><?php echo $_LANG['000f8']; ?></a></h4>
                      <input type="submit" name="action_Confirm" value="<?php echo $_LANG['00010']; ?>" />
                    </li>
                  </ul>
                </form>
                <?php break;
            }

          } else if (($cData['command'] == "edit" && isset($cData['edit']) && $cData['edit'] != "Confirm") ||
                     ($cData['command'] == "add"  && isset($cData['add'])  && $cData['add']  != "AddConfirm")) {
            $repeat = ($cData['command'] == "edit" || $cData['add'] == "Again") ? true : false; ?> 
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="editform">
              <h3><?php echo ($cData['command'] == "edit") ? $_LANG['000fd'] : $_LANG['000fe']; ?></h3>
              <ul>
                <li><h4><?php echo $_LANG['00027']; ?></h4>
                  <?php if ($cData['command'] == "edit") { ?> 
                    <a href="<?php echo $cData['row']['uri']; ?>"><?php echo $cData['row']['uri']; ?></a>
                    <input type="hidden" name="uri" value="<?php echo $cData['row']['uri']; ?>" />
                  <?php } else { ?> 
                    <input type="text" name="uri" value="<?php echo ($cData['add'] == "Again") ? $cData['row']['uri'] : "http://"; ?>" size="54" />
                  <?php } ?> 
                </li>
                <?php if ($cData['command'] == "edit") { ?> 
                  <li><h4><?php echo $_LANG['000fg']; ?></h4>
                    <?php echo ($cData['row']['encoding'] != "-") ? $cData['row']['encoding'] : $_LANG['000fh']; ?> 
                  </li>
                <?php } ?> 
                <li><h4><?php echo $_LANG['000fi']; ?></h4>
                  <input type="text" name="title" value="<?php if ($repeat) echo htmlspecialchars($cData['row']['title']); ?>" size="54" />
                </li>
                <li><h4><?php echo $_LANG['000f4']; ?></h4>
                  <select name="categoryExist" size="1" title="<?php echo $_LANG['000f5']; ?>" onchange="document.getElementById('editform').categoryNew.disabled=(this.value!='-')?'disabled':'';">
                    <option value="-"<?php if ($cData['command'] == "add") echo " selected=\"selected\""; ?>><?php echo $_LANG['000f6']; ?> &gt;&gt;</option>
                    <?php foreach($cData['categories'] as $category) { ?> 
                      <option value="<?php echo htmlspecialchars($category); ?>"<?php echo ($repeat && $cData['row']['category'] == $category) ? " selected=\"selected\"" : ""; ?>><?php echo htmlspecialchars($category); ?></option>
                    <?php } ?> 
                  </select>
                  <input type="text" name="categoryNew" />
                  <?php if ($cData['command'] == "edit") { ?> 
                    <input type="hidden" name="categoryNow" value="<?php echo $cData['row']['category']; ?>" />
                    <script type="text/javascript"><!--
                      document.getElementById('editform').categoryNew.disabled = "disabled";
                    // --></script>
                  <?php } ?> 
                </li>
                <li><h4><?php echo $_LANG['000fj']; ?></h4>
                  <textarea rows="4" cols="40" name="description"><?php if ($repeat) echo $cData['row']['description']; ?></textarea>
                </li>
                <li><h4><?php echo $_LANG['000fk']; ?></h4>
                  <textarea rows="3" cols="40" name="keywords"><?php if ($repeat) echo $cData['row']['keywords']; ?></textarea>
                </li>
                <li><h4><?php echo $_LANG['000fl']; ?></h4>
                  <label><?php echo $_LANG['000fm']; ?> <input type="radio" name="unlist" value="false"<?php echo ($cData['command'] != "edit" || $cData['row']['unlist'] == "false") ? " checked=\"checked\"" : ""; ?> /></label>
                  <label><?php echo $_LANG['000fn']; ?> <input type="radio" name="unlist" value="true"<?php echo ($repeat && $cData['row']['unlist'] == "true") ? " checked=\"checked\"" : ""; ?> /></label>
                </li>
                <?php if ($vData['sm.enable'] == "true") { ?> 
                  <li class="title"><h3><?php echo $_LANG['000fo']; ?></h3></li>
                  <li><h4><?php echo $_LANG['000fp']; ?></h4>
                    <label><?php echo $_LANG['000fq']; ?> <input type="radio" name="list" value="true"<?php echo ($cData['command'] != "edit" || $cData['row']['sm.list'] == "true") ? " checked=\"checked\"" : ""; ?> /></label>
                    <label><?php echo $_LANG['000fr']; ?> <input type="radio" name="list" value="false"<?php echo ($repeat && $cData['row']['sm.list'] == "false") ? " checked=\"checked\"" : ""; ?> /></label>
                  </li>
                  <li><h4><?php echo $_LANG['000fs']; ?></h4>
                    <select name="changefreq" size="1"<?php if ($vData['sm.changefreq'] == "true") echo " disabled=\"disabled\""; ?>>
                      <?php while (list($key, $value) = each($cData['langcf'])) {
                        $selected = (($repeat && $cData['row']['sm.changefreq'] == $key) || (!$repeat && $key == 'weekly')) ? " selected=\"selected\"" : "";
                        ?><option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $value; ?></option>
                        <?php
                      } ?> 
                    </select>
                  </li>
                  <li><h4><?php echo $_LANG['000ft']; ?> (0.0 ~ 1.0)</h4>
                    <input type="text" name="priority" value="<?php echo ($repeat) ? $cData['row']['sm.priority'] : "0.5"; ?>" size="5" />
                  </li>
                <?php } ?> 
                <li><h4><a href="<?php echo $_SERVER['PHP_SELF'], (($_GET['start']) ? "?start={$_GET['start']}" : ""); ?>" title="<?php echo $_LANG['000f7']; ?>"><?php echo $_LANG['000f8']; ?></a></h4>
                  <?php if ($_GET['start']) { ?> 
                    <input type="hidden" name="start" value="<?php echo $_GET['start']; ?>" />
                  <?php } ?> 
                  <?php if ($cData['command'] == "edit") { ?> 
                    <input type="hidden" name="md5" value="<?php echo $cData['row']['md5']; ?>" />
                    <input type="submit" name="edit_Confirm" value="<?php echo $_LANG['00010']; ?>" />
                  <?php } else { ?> 
                    <input type="submit" name="add_Confirm" value="<?php echo $_LANG['00010']; ?>" />
                  <?php } ?> 
                </li>
                <?php if ($cData['command'] != "edit") { ?> 
                  <li>
                    <p><small>
                      <?php echo $_LANG['00012']; ?>: <?php echo $_LANG['000fu']; ?>
                    </small></p>
                  </li>
                <?php } ?> 
              </ul>
            </form>

          <?php } else { ?> 
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="mainlist">
              <table cellspacing="0" border="0">
                <thead>
                  <tr>
                    <th colspan="4">
                      <?php if ($vData['c.column'] == "uri") {
                        if (count($cData['categories']) > 1 && $vData['cf.category'] == "-" ) {
                          printf(($vData['c.sortby'] == "col1") ? $sData['orderby1'] :  $sData['orderby2'], $_LANG['00027'], "col1");
                        } else echo ($vData['c.sortby'] == "col1") ? sprintf($sData['orderby1'], $_LANG['00027']) : $_LANG['00027'];
                        echo " / ", sprintf($sData['orderby3'], $_LANG['000fi'], "title");
                      } else {
                        echo sprintf($sData['orderby3'], $_LANG['00027'], "uri"), " / ";
                        if (count($cData['categories']) > 1 && $vData['cf.category'] == "-") {
                          printf(($vData['c.sortby'] != "col1") ? $sData['orderby2'] : $sData['orderby1'], $_LANG['000fi'], "col1");
                        } else echo ($vData['c.sortby'] == "col1") ? sprintf($sData['orderby1'], $_LANG['000fi']) : $_LANG['000fi'];
                      } ?> 
                    </th>
                    <th class="min">
                      <?php if (count($cData['categories']) > 1 && $vData['cf.category'] == "-") {
                        printf(($vData['c.sortby'] == "col2") ? $sData['orderby1'] : $sData['orderby2'], $_LANG['000f4'], "col2");
                      } else echo ($vData['c.sortby'] == "col2") ? sprintf($sData['orderby1'], $_LANG['000f4']) : $_LANG['000f4']; ?> 
                    </th>
                    <th class="min"><?php echo $_LANG['000fv']; ?></th>
                    <th class="min" colspan="3"><?php echo ($vData['sm.enable'] == "true") ? $_LANG['000fo'] : $_LANG['000fw']; ?></th>
                  </tr>
                  <tr id="filters">
                    <th>
                      <?php echo $_LANG['000fx']; ?> 
                    </th>
                    <td class="min">
                      <input type="checkbox" name="new" value="true"<?php echo ($vData['cf.new'] == "true") ? " checked=\"checked\"" : ""; ?><?php echo ($cData['new']) ? " title=\"{$_LANG['000fy']}\"" : " title=\"{$_LANG['000fz']}\" disabled=\"disabled\""; ?>
                    /></td>
                    <td>
                      <label><?php echo $_LANG['000g0']; ?> 
                        <input type="text" name="textmatch" value="<?php echo htmlspecialchars($vData['cf.textmatch']); ?>" title="<?php echo $_LANG['000g1']; ?>" />
                      </label>
                    </td>
                    <td>
                      <label><?php echo $_LANG['000g2']; ?> 
                        <input type="text" name="textexclude" value="<?php echo htmlspecialchars($vData['cf.textexclude']); ?>" title="<?php echo $_LANG['000g3']; ?>" />
                      </label>
                    </td>
                    <td>
                      <?php if (count($cData['categories']) > 1) { ?> 
                        <select name="category" size="1" title="<?php echo $_LANG['000g4']; ?>">
                          <option value="-"<?php echo ($vData['cf.category'] == "-") ? " selected=\"selected\"" : ""; ?>><?php echo $_LANG['000g6']; ?></option>
                          <?php foreach($cData['categories'] as $category) { ?> 
                            <option value="<?php echo htmlspecialchars($category); ?>"<?php if ($vData['cf.category'] == $category) echo " selected=\"selected\""; ?>><?php echo htmlspecialchars($category); ?></option>
                          <?php } ?> 
                        </select>
                      <?php } else echo "&ndash;"; ?> 
                    </td>
                    <td>
                      <select name="status" size="1" title="<?php echo $_LANG['000g5']; ?>">
                        <option value="All"<?php echo ($vData['cf.status'] == "All") ? " selected=\"selected\"" : ""; ?>><?php echo $_LANG['000g6']; ?></option>
                        <option value="OK"<?php echo ($vData['cf.status'] == "OK") ? " selected=\"selected\"" : ""; ?>><?php echo $_LANG['0001j']; ?></option>
                        <option value="Orphan"<?php echo ($vData['cf.status'] == "Orphan") ? " selected=\"selected\"" : ""; ?>><?php echo $_LANG['0001k']; ?></option>
                        <option value="Blocked"<?php echo ($vData['cf.status'] == "Blocked") ? " selected=\"selected\"" : ""; ?>><?php echo $_LANG['0001h']; ?></option>
                        <option value="Unlisted"<?php echo ($vData['cf.status'] == "Unlisted") ? " selected=\"selected\"" : ""; ?>><?php echo $_LANG['0001i']; ?></option>
                        <option value="Not Found"<?php echo ($vData['cf.status'] == "Not Found") ? " selected=\"selected\"" : ""; ?>><?php echo $_LANG['0001g']; ?></option>
                        <option value="Unread"<?php echo ($vData['cf.status'] == "Unread") ? " selected=\"selected\"" : ""; ?>><?php echo $_LANG['0001l']; ?></option>
                      </select>
                    </td>
                    <td colspan="3">
                      <input type="submit" name="filter_Set" value="<?php echo $_LANG['000gd']; ?>" title="<?php echo $_LANG['000ge']; ?>" />
                      <input type="submit" name="filter_Clear" value="<?php echo $_LANG['000gf']; ?>" title="<?php echo $_LANG['000gg']; ?>"<?php if ($cData['nofilters']) echo " disabled=\"disabled\""; ?>
                    /></td>
                  </tr>
                  <?php ob_start();
                    ?>  <tr>
                      <td class="actions" colspan="3">
                        <input type="checkbox" name="selectall" id="allTypeA" title="<?php echo $_LANG['000gh']; ?>" disabled="disabled" onclick="checkAll((this.checked)?'checked':'');" />
                        <select name="TypeA" id="TypeA" size="1" title="<?php echo $_LANG['000gi']; ?>"<?php if (!$cData['rows']) echo " disabled=\"disabled\""; ?>>
                          <option value="null"><?php echo $_LANG['000gj']; ?></option>
                          <option value="delete"><?php echo $_LANG['000gk']; ?></option>
                          <option value="unlist"><?php echo $_LANG['000fl']; ?></option>
                          <option value="relist"><?php echo $_LANG['000gm']; ?></option>
                          <option value="category"><?php echo $_LANG['000gn']; ?></option><?php
                          if ($vData['sm.enable'] == "true") { ?> 
                            <option value="sm.unlist"><?php echo $_LANG['000go']; ?></option>
                            <option value="sm.relist"><?php echo $_LANG['000gp']; ?></option><?php 
                            if ($vData['sm.changefreq'] != "true") { ?> 
                              <option value="sm.changefreq"><?php echo $_LANG['000gq']; ?></option><?php
                            } ?> 
                            <option value="sm.priority"><?php echo $_LANG['000gr']; ?></option><?php
                          } ?> 
                        </select>
                        <input type="submit" name="action_TypeA" value="<?php echo $_LANG['000gs']; ?>" title="<?php echo $_LANG['000gt']; ?>" onclick="return actionGo('TypeA');"<?php if (!$cData['rows']) echo " disabled=\"disabled\""; ?> />
                        <?php echo $_LANG['000gu']; ?> <input type="submit" name="add_Add" value="<?php echo $_LANG['000f1']; ?>" title="<?php echo $_LANG['000f2']; ?>" />
                      </td>
                      <td colspan="3">
                        <?php if ($cData['count'] > $vData['c.pagination']) { ?> 
                          <?php echo $_LANG['000gv']; ?>:
                          <?php for ($z = 0; $z < $cData['count']; $z += $vData['c.pagination']) {
                            if ($cData['start'] != $z) { ?> 
                              <a href="<?php echo $_SERVER['PHP_SELF'], (($z) ? "?start=$z" : ""); ?>"><?php echo ($z / $vData['c.pagination'] + 1); ?></a>
                            <?php } else { ?> 
                              <strong><?php echo ($z / $vData['c.pagination'] + 1); ?></strong>
                            <?php }
                          }
                        } else echo "&ndash;"; ?> 
                      </td>
                      <td colspan="3">
                        <input type="text" name="showTypeA" size="3" maxlength="3" value="<?php echo $vData['c.pagination']; ?>" title="<?php echo $_LANG['000gw']; ?> (10-999)" />
                        <input type="submit" name="show_TypeA" value="<?php echo $_LANG['000gs']; ?>" onclick="if(document.getElementById('mainlist').showTypeA.value==pagination)return false;" />
                      </td>
                    </tr><?php
                  $cData['paginateHTML'] = ob_get_contents();
                  ob_end_flush(); ?> 
                </thead>
                <tfoot>
                  <?php echo str_replace("TypeA", "TypeB", $cData['paginateHTML']); ?> 
                </tfoot>
                <tbody>
                  <?php $y = 0;
                  while ($row = mysql_fetch_assoc($cData['list'])) { ?> 
                    <tr<?php echo ($y++ % 2) ? "" : " class=\"drow\""; ?>>
                      <th colspan="4"><?php
                        $row['front'] = ($vData['c.column'] == "uri") ? $row['uri'] : htmlspecialchars($row['title']);
                        $row['back'] = ($vData['c.column'] == "uri") ? htmlspecialchars($row['title']) : $row['uri']; ?> 
                        <input type="checkbox" name="action[]" value="<?php echo $row['md5']; ?>" />
                        <<?php echo ($row['new'] == "true") ? "strong" : "span"; ?> title="<?php echo $row['back']; ?>"><?php echo ($row['front']) ? $row['front'] : "&ndash;"; ?></<?php echo ($row['new'] == "true") ? "strong" : "span"; ?>>
                      </th>
                      <td><?php echo htmlspecialchars($row['category']); ?></td>
                      <td><?php
                        switch ($row['status']) {
                          case "Not Found":
                          case "Blocked":
                            echo $cData['langst'][$row['rstat'] = $row['status']];
                            break;
                          case "Orphan":
                          case "Unread":
                          case "OK":
                            if ($row['unlist'] != "true") {
                              if ($row['status'] == "OK") {
                                foreach ($sData['noSearch'] as $noSearch) {
                                  if (preg_match("/{$noSearch}/i", $row['uri'])) {
                                    echo $cData['langst'][$row['rstat'] = "Unlisted"];
                                    break 2;
                                  }
                                }
                              }
                              echo $cData['langst'][$row['rstat'] = $row['status']];
                            } else echo "<strong>", $cData['langst'][$row['rstat'] = "Unlisted"], "</strong>";
                            break;
                        }
                      ?></td>
                      <?php if ($vData['sm.enable'] == "true" && ($row['rstat'] == "OK" || ($row['rstat'] == "Orphan" && $vData['s.orphans'] == "show") || ($row['rstat'] == "Unlisted" && $vData['sm.unlisted'] == "true"))) {
                        if ($row['sm.list'] == "true") { ?> 
                          <td><?php
                            $height = 16 - round((float)$row['sm.priority'] * 16); ?> 
                            <div class="priority" title="Priority - <?php echo $row['sm.priority']; ?>">
                              <div style="height:<?php echo $height; ?>px;" title="<?php echo $_LANG['000ft']; ?> - <?php echo $row['sm.priority']; ?>">&nbsp;</div>
                            </div>
                          </td>
                          <td>&nbsp;<small title="<?php echo $_LANG['000fs']; ?> - <?php echo $cData['langcf'][$row['sm.changefreq']]; ?>"><?php echo strtoupper($row['sm.changefreq']{0}); ?></small></td>
                        <?php } else { ?>
                          <td><div class="priority disabled" title="<?php echo $_LANG['000gx']; ?>">&nbsp;</div></td>
                          <td><small title="<?php echo $_LANG['000gx']; ?>">&ndash;</small></td>
                        <?php }
                      } else { ?> 
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      <?php } ?> 
                      <td><input type="submit" name="edit_<?php echo $row['md5']; ?>" value="<?php echo $_LANG['000fw']; ?>" title="<?php echo $_LANG['000fd']; ?>" /></td>
                    </tr>
                  <?php } 
                  if (!$y) { ?> 
                    <tr>
                      <td colspan="9">
                        <?php echo $_LANG['000gy']; ?> 
                        <?php if (!$cData['nofilters']) { ?> 
                          &ndash; <input type="submit" name="filter_Clear" value="<?php echo $_LANG['000gz']; ?>" title="<?php echo $_LANG['000h0']; ?>" />
                        <?php } ?> 
                       </td>
                    </tr>
                  <?php } ?> 
                </tbody>
              </table>
              <script type="text/javascript"><!--
                function checkAll(dir) {
                  var boxList = document.getElementById('mainlist').getElementsByTagName('input');
                  for (var x = 0; x < boxList.length; x++) if (boxList[x].name == "action[]" || boxList[x].name == "selectall") boxList[x].checked = dir;
                  return false;
                }
                function anyChecked() {
                  var boxList = document.getElementById('mainlist').getElementsByTagName('input');
                  for (var x = 0; x < boxList.length; x++) if (boxList[x].name == "action[]" && boxList[x].checked) return true;
                  return false;
                }
                function actionGo(thisType) {
                  if (document.getElementById(thisType).value == 'null') return false;
                  if (anyChecked()) {
                    if (document.getElementById(thisType).value == 'delete') {
                      return confirm("<?php echo $_LANG['000h1']; ?>");
                    } else return true;
                  }
                  return false;
                }
                var pagination = <?php echo $vData['c.pagination']; ?>;
                <?php if ($cData['rows']) { ?> 
                  document.getElementById('mainlist').allTypeA.disabled = '';
                  document.getElementById('mainlist').allTypeB.disabled = '';
                <?php } ?> 
              // --></script>
              <?php if ($_GET['start']) { ?> 
                <div>
                  <input type="hidden" name="start" value="<?php echo $_GET['start']; ?>" />
                </div>
              <?php } ?> 
            </form>
          <?php }
        }

    }
  } ?> 
</body>
</html>