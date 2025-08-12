<?php /* ***** Orca Forum - Head File ************************* */

/* ***************************************************************
* Orca Forum v4.3c
*  A simple threaded forum for a small community
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
*************************************************************** */

/* ***** SQL Information ************************************** */
$dData['hostname'] = "hostname";
$dData['username'] = "username";
$dData['password'] = "password";
$dData['database'] = "database";
$dData['tablename'] = "orcaforum";

/* ***** Other User Variables ********************************* */
$fData['admin'] = "admin";
$fData['password'] = "password";
$fData['notify'] = false;

$fData['timezone'] = "EST";
$fData['tzoffset'] = "-5";
$fData['dstadjust'] = true;

$fData['emailreply'] = "your@email.com";
$fData['emailfrom'] = "The Orca Forum";

$fData['wordwrap'] = 40;
$fData['threadspp'] = 15;
$fData['pagetitle'] = "The Orca Forum - The ultimate PHP forum for a small community";

$fData['threadcollapse'] = false;
$fData['avatarpx'] = 80;
$fData['maxthreads'] = 105;
$fData['oblastpost'] = true;

// $fData['ipban'][] = "123.123.123.123";
// $fData['ipban'][] = "123.123.123.";

// $fData['textban'][] = "casino";
// $fData['textban'][] = "gambling";

$iData['showimages'] = false;
$iData['plus'] = "orca/o_plus.png";
$iData['minus'] = "orca/o_minus.png";


/* ***** Database functions *********************************** */
function get_all() {
  global $dData;
  return mysql_query("SELECT * FROM `{$dData['tablename']}` ORDER BY `pid`;");
}
function get_row($id) {
  global $dData;
  $id = (int)$id;
  return mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE `pid`='$id';");
}
function get_kids($id) {
  global $dData, $fData;
  $id = (int)$id;
  return mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE `parent`='$id' ORDER BY ".(($fData['oblastpost']) ? "`recent` DESC": "`pid` DESC").";");
}

$db = mysql_connect($dData['hostname'], $dData['username'], $dData['password']) or die("Could not connect to the MySQL server!");
mysql_select_db($dData['database'], $db) or die("Could not connect to the database!");

$create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tablename']}` (
  `pid` int(11) NOT NULL auto_increment,
  `ip` text,
  `author` text,
  `subject` text,
  `message` longtext,
  `msghtml` longtext,
  `image` text,
  `date` int(11) default NULL,
  `recent` int(11) default NULL,
  `email` text,
  `notify` text,
  `parent` int(11) default NULL,
  PRIMARY KEY  (`pid`)
) TYPE=MyISAM;") or die("Could not create forum table!");


/* ***** Update to 4.3 **************************************** */
if ($result = mysql_query("SHOW COLUMNS FROM `{$dData['tablename']}`;")) {
  $dData['fields'] = array();
  for ($x = 0; $x < mysql_num_rows($result); $x++) $dData['fields'][] = mysql_result($result, $x, "Field");

  if (in_array("views", $dData['fields']))
    $alter = mysql_query("ALTER TABLE `{$dData['tablename']}` CHANGE `views` `recent` INTEGER;");

  if (!in_array("msghtml", $dData['fields'])) {
    $alter = mysql_query("ALTER TABLE `{$dData['tablename']}` ADD `msghtml` LONGTEXT AFTER `message`;");

    $mass = get_all();
    for ($x = 0; $x < mysql_num_rows($mass); $x++)
      $update = mysql_query("UPDATE `{$dData['tablename']}` SET `msghtml`='".addslashes(parseMessage(mysql_result($mass, $x, "message")))."' WHERE `pid`='".mysql_result($mass, $x, 'pid')."';");
  }
}

if (mysql_num_rows(mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE `recent`='';"))) {
  $index = mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE `parent`='-1';");
  for ($x = 0; $x < mysql_num_rows($index); $x++) $reindex = reindexThreadDown(mysql_result($index, $x, "pid"));
}


/* ***** Forum Functions ************************************** */
function dateStamp($time) {
  global $pageEncoding, $dateFormat, $lang;

  switch ($pageEncoding) {
    case 1: $timeStr = utf8_encode(gmstrftime($dateFormat, $time)); break;
    case 2: $timeStr = gmstrftime($dateFormat, $time); break;
    default: $timeStr = @htmlentities(gmstrftime($dateFormat, $time), ENT_COMPAT, $lang['charset']); break;
  }
  return $timeStr;
}

function listChildren($msg) {
  global $lData, $fData;

  $thisMsg = get_row($msg); ?>

  <li>
    <a class="of_subject" href="<?php echo $_SERVER['PHP_SELF']."?msg=$msg"; ?>"><?php echo htmlspecialchars(mysql_result($thisMsg, 0, "subject")); ?></a>
    <span class="of_author" title="<?php echo mysql_result($thisMsg, 0, "ip"); ?>"><?php echo htmlspecialchars(mysql_result($thisMsg, 0, "author")); ?></span>
    <span class="of_date<?php if (mysql_result($thisMsg, 0, "date") > time() - $_COOKIE['of_mark']) echo " of_new"; ?>"><?php echo dateStamp(mysql_result($thisMsg, 0, "date") + $fData['offset']); ?></span>
    <?php $nextLevel = get_kids($msg);
    if (mysql_num_rows($nextLevel)) { ?> 
      <ul>
        <?php for ($x = 0; $x < mysql_num_rows($nextLevel); $x++) {
          listChildren(mysql_result($nextLevel, $x, "pid"));
        } ?> 
      </ul>
    <?php } ?> 
  </li>
  <?php $lData['replies']++;
}

function reindexThreadDown($msg) {
  global $dData;

  $thisMsg = get_row($msg);
  if (mysql_num_rows($thisMsg)) {
    $time = mysql_result($thisMsg, 0, "date");

    $nextLevel = get_kids($msg);
    for ($x = 0; $x < mysql_num_rows($nextLevel); $x++) $time = max(reindexThreadDown(mysql_result($nextLevel, $x, "pid")), $time);
    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `recent`='$time' WHERE `pid`='$msg';");

    return $time;
  }
}

function reindexThreadUp($msg, $date) {
  global $dData;

  $set = mysql_query("UPDATE `{$dData['tablename']}` SET `recent`='$date' WHERE `pid`='$msg';");

  $thisMsg = get_row($msg);
  if (mysql_result($thisMsg, 0, "parent") != "-1") reindexThreadUp(mysql_result($thisMsg, 0, "parent"), $date);
}

function parseImage($input) {
  global $fData, $lang;

  if (!$input || $input == "http://") return NULL;
  if (preg_match("/http:\/\/\w\S*\.(png|gif|jpg|jpeg|bmp|php|asp)/i", $input)) {
    if ($dims = getimagesize_remote($input)) {
      if ($dims[0] <= $fData['avatarpx'] && $dims[1] <= $fData['avatarpx']) {
        return $input;
      } else if ($_POST['command'] == "Preview") $fData['error'] = sprintf($lang['avatar1'], $fData['avatarpx']);
    } else if ($_POST['command'] == "Preview") $fData['error'] = $lang['avatar2'];
  } else if ($_POST['command'] == "Preview") $fData['error'] = $lang['avatar3'];
  return "";
}

function getimagesize_remote($image_url) {
  if ($gis = @getimagesize($image_url)) {

  } else {
    $handle = @fopen($image_url, "rb");

    if ($handle) {
      $contents = "";
      while ($data = fread($handle, 8192)) $contents .= $data;
      fclose($handle);

      $im = @imagecreatefromstring($contents);
      if ($im) {
        $gis[0] = imagesx($im);
        $gis[1] = imagesy($im);
        $gis[3] = "width=\"{$gis[0]}\" height=\"{$gis[1]}\"";
        imagedestroy($im);
        return $gis;
      }
    }
  }
  return ($gis) ? $gis : false;
}

function parseEmail($input) {
  global $fData, $lang;

  if (!$input) return NULL;
  if (preg_match("/^([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)$/i", $input)) {
    return str_replace(array("@", "."), array("[at]", "[dot]"), $input);
  } else if ($_POST['command'] == "Preview") $fData['error'] = $lang['emaila'];
  return "";
}

function parseMessage($input) {
  global $fData, $lang, $pageEncoding;

  $input = htmlspecialchars($input);

  do {
    $length = strlen($input);
    $input = preg_replace("/\n?\[quote=?(.*?)\]\s*(.*?(?!\[quote.*?\]))\[\/quote\]\s*/is", "<blockquote><h5>$1 {$lang['parse2']}:</h5> <div>$2</div></blockquote> ", $input);
  } while ($length != strlen($input));
  $input = preg_replace("/".preg_quote("<blockquote><h5> {$lang['parse2']}:</h5>", "/")."/i", "<blockquote><h5>".$lang['parse1'].":</h5>", $input);

  do {
    $length = strlen($input);
    $input = preg_replace("/\[b\](.*?(?!\[b\]))\[\/b\]/is", "<strong>$1</strong>", $input);
  } while ($length != strlen($input));

  do {
    $length = strlen($input);
    $input = preg_replace("/\[i\](.*?(?!\[i\]))\[\/i\]/is", "<em>$1</em>", $input);
  } while ($length != strlen($input));

  do {
    $length = strlen($input);
    // $input = preg_replace("/\[link=(http:\/\/.*?)\](.*?(?!\[link=http:\/\/.*?\]))\[\/link\]/ise", "\"<a href=\\\"$1\\\" rel=\\\"nofollow\\\">\".str_replace(\"&\", \"&amp;\", (preg_match(\"/^(http(s)?|ftp):\/\//i\", \"$2\") && strlen(str_replace(\"&amp;\", \"&\", \"$2\")) > ".$fData['wordwrap']." - 2) ? substr(str_replace(\"&amp;\", \"&\", \"$2\"), 0, floor(".$fData['wordwrap']." / 2) - 2).\"...\".substr(str_replace(\"&amp;\", \"&\", \"$2\"), -(floor(".$fData['wordwrap']." / 2)) + 2) : str_replace(\"&amp;\", \"&\", \"$2\")).\"</a>\"", $input);
    $input = preg_replace("/\[link=(http:\/\/.*?)\](.*?(?!\[link=http:\/\/.*?\]))\[\/link\]/is", "<a href=\"$1\">$2</a>", $input);
  } while ($length != strlen($input));

  do {
    $length = strlen($input);
    $input = preg_replace("/\[img=(http:\/\/.*?)\]/is", "<img src=\"$1\" alt=\"\" />", $input);
  } while ($length != strlen($input));

  do {
    $length = strlen($input);
    $input = preg_replace("/\n?\[code\](.*?(?!\[code\]))\[\/code\]\s*/is", "<code>$1</code> ", $input);
  } while ($length != strlen($input));
  $input = preg_replace("/<code>((.*?(?!<code>))((https?|ftp):\/\/))/i", "<code>$2*$3", $input);

  $input = preg_replace("/\[\/?(quote=?.*?|b|i|link=?.*?|img=?.*?|code)\]/is", "", $input);

  $input = preg_replace("/(?<!>|src=|src=('|\")|href=|href=('|\")|\*)((https?|ftp):\/\/[^\s<>\"]+)(?=[.,!\)]\s|<|\s|$)/ie", "\"<a href=\\\"$0\\\">\".str_replace(\"&\", \"&amp;\", (strlen(str_replace(\"&amp;\", \"&\", \"$0\")) > ".$fData['wordwrap']." - 2) ? substr(str_replace(\"&amp;\", \"&\", \"$0\"), 0, floor(".$fData['wordwrap']." / 2) - 2).\"...\".substr(str_replace(\"&amp;\", \"&\", \"$0\"), -(floor(".$fData['wordwrap']." / 2)) + 2) : str_replace(\"&amp;\", \"&\", \"$0\")).\"</a>\"", $input);
  $input = preg_replace("/\*((https?|ftp):\/\/)/", "$1", $input);

  $input = htmlwrap($input, $fData['wordwrap'], "\n", "", "pre", ($pageEncoding == 1) ? true : false);

  if (preg_match_all("/<code>.*?<\/code>/s", $input, $pre1)) {
    for ($x = 0; $x < count($pre1[0]); $x++) {
      $pre2[$x] = str_replace("  ", "&nbsp;&nbsp;", $pre1[0][$x]);
      $pre2[$x] = preg_replace("/(\n|>) /", "$1&nbsp;", $pre2[$x]);
      $pre1[0][$x] = "/".preg_quote($pre1[0][$x], "/")."/";
    }
    $input = preg_replace($pre1[0], $pre2, $input);
  }

  return $input;
}

function htmlwrap($str, $width = "60", $break = "\n", $nobreak = "", $nobr = "pre", $utf = false) {
  $content = preg_split("/([<>])/", $str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
  $nobreak = explode(" ", $nobreak);
  $nobr = explode(" ", $nobr);

  $intag = false;
  $innbk = array();
  $innbr = array();
  $drain = "";
  $lbrks = "/?!%)-}]\\\"':;";
  if ($break == "\r") $break = "\n";
  $utf = ($utf) ? "u" : "";

  while (list(, $value) = each($content)) {
    switch ($value) {
      case "<": $intag = true; break;
      case ">": $intag = false; break;
      default:
        if ($intag) {
          if ($value{0} != "/") {
            preg_match("/^(.*?)(\s|$)/$utf", $value, $t);
            if ((!count($innbk) && in_array($t[1], $nobreak)) || in_array($t[1], $innbk)) $innbk[] = $t[1];
            if ((!count($innbr) && in_array($t[1], $nobr)) || in_array($t[1], $innbr)) $innbr[] = $t[1];
          } else {
            if (in_array(substr($value, 1), $innbk)) unset($innbk[count($innbk)]);
            if (in_array(substr($value, 1), $innbr)) unset($innbr[count($innbr)]);
          }
        } else if ($value) {
          if (!count($innbr)) $value = str_replace("\n", "\r", str_replace("\r", "", $value));
          if (!count($innbk)) {
            do {
              $store = $value;
              if (preg_match("/^(.*?\s|^)(([^\s&]|&(\w{2,5}|#\d{2,4});){".$width."})(?!(".preg_quote($break, "/")."|\s))(.*)$/s$utf", $value, $match)) {
                for ($x = 0, $ledge = 0; $x < strlen($lbrks); $x++) $ledge = max($ledge, strrpos($match[2], $lbrks{$x}));
                if (!$ledge) $ledge = strlen($match[2]) - 1;
                $value = $match[1].substr($match[2], 0, $ledge + 1).$break.substr($match[2], $ledge + 1).$match[6];
              }
            } while ($store != $value);
          }
          if (!count($innbr)) $value = str_replace("\r", "<br />\n", $value);
        }
    }
    $drain .= $value;
  }
  return $drain;
}

function snipThread($msg) {
  global $dData;

  $msgkids = get_kids($msg);
  if (mysql_num_rows($msgkids))
    for ($x = 0; $x < mysql_num_rows($msgkids); $x++) snipThread(mysql_result($msgkids, $x, "pid"));

  $delete = mysql_query("DELETE FROM `{$dData['tablename']}` WHERE `pid`='$msg';");
}

function findMatron($msg) {
  $read = $msg;
  do {
    $thisMat = get_row($read);
    $read = mysql_result($thisMat, 0, "parent");
  } while ($read != "-1");
  return array(mysql_result($thisMat, 0, "subject"), mysql_result($thisMat, 0, "author"), mysql_result($thisMat, 0, "date"), mysql_result($thisMat, 0, "pid"), mysql_result($thisMat, 0, "ip"));
}

function paginURI($input) {
  global $fData;

  if (isset($_GET['msg'])) $input .= "msg={$_GET['msg']}&amp;";
  if (isset($fData['search'])) $input .= "s=".urlencode($fData['search'])."&amp;";
  $input = preg_replace("/&amp;$/i", "", $input);
  return ($input == "?") ? "" : $input;
}

function unhtmlentities($string)  {
  $new_str = utf8_decode(str_replace("?", "**!!**!!**", $string));
  $string = (strpos($new_str, "?") !== false) ? $string : str_replace("**!!**!!**", "?", $new_str); 

  $trans_tbl = get_html_translation_table(HTML_ENTITIES);
  $trans_tbl = array_flip($trans_tbl);
  $ret = strtr($string, $trans_tbl);
  return preg_replace('/&#(\d+);/me', "chr('\\1')",$ret);
}


error_reporting(E_ALL);

/* ***** Magic Quotes Fix ************************************* */
if (get_magic_quotes_gpc()) {
  $fsmq = create_function('&$mData, $fnSelf', 'if (is_array($mData)) foreach ($mData as $mKey=>$mValue) $fnSelf($mData[$mKey], $fnSelf); else $mData = stripslashes($mData);');
  $fsmq($_POST, $fsmq);
  $fsmq($_GET, $fsmq);
  $fsmq($_ENV, $fsmq);
  $fsmq($_SERVER, $fsmq);
  $fsmq($_COOKIE, $fsmq);
}
set_magic_quotes_runtime(0);


/* ***** Manage Cookies *************************************** */
if (!isset($_COOKIE['of_mark'])) $_COOKIE['of_mark'] = 0; 


/* ***** Compile Forum Data *********************************** */
$_SERVER['PHP_SELF'] = preg_replace("/\?.*$/i", "", $_SERVER['REQUEST_URI']);

$fData['UA'] = "Orca Forum v4.3c";
$fData['msgtotal'] = mysql_num_rows(get_all());
$fData['offset'] =  $fData['tzoffset'] * 3600 + (($fData['dstadjust']) ? date("I") * 3600 : 0);
$fData['time'] = time() + $fData['offset'];
$fData['start'] = (isset($_GET['start'])) ? $_GET['start'] : 0;
$_POST['command'] = "";
if (isset($_POST['command_mark'])) $_POST['command'] = "Mark";
if (isset($_POST['command_prev'])) $_POST['command'] = "Preview";
if (isset($_POST['command_post'])) $_POST['command'] = "Post";


/* ***** Accept Incoming Post ********************************* */
if ($_SERVER['REQUEST_METHOD'] == "POST") {

  while (list($key, $value) = each($_POST)) $_POST[$key] = trim(str_replace(chr(13), "", $value));

  switch($_POST['command']) {

    /* ***** Preview ****************************************** */
    case "Preview":
      // Preview message setup

      break;

    /* ***** Post ********************************************* */
    case "Post":

      /* ***** Compile Post Data ************************************ */
      $pData['lastmsg'] = mysql_query("SELECT `date`, `message` FROM `{$dData['tablename']}` WHERE `ip`='{$_SERVER['REMOTE_ADDR']}' ORDER BY `pid` DESC LIMIT 1;");
      if (mysql_num_rows($pData['lastmsg'])) {
        $pData['lastmsg'] = mysql_fetch_assoc($pData['lastmsg']);
        $pData['lastmsg']['date'] += $fData['offset'];
      }

      if ($_POST['parent'] != "-1") {
        $pData['parent'] = get_row($_POST['parent']);
        if (mysql_num_rows($pData['parent'])) {
          $pData['parent'] = mysql_fetch_assoc($pData['parent']);
          $pData['parent']['date'] += $fData['offset'];
        }
      }

      if (isset($fData['ipban'])) foreach($fData['ipban'] as $ipban) if (strpos($_SERVER['REMOTE_ADDR'], $ipban) === 0) break 2;
      if (isset($fData['textban'])) foreach($fData['textban'] as $textban) if (strpos($_POST['message'], $textban) !== false) break 2;


      /* ***** Check Incoming Data ********************************** */
      if (isset($_POST['author']) && $_POST['author'] == $fData['admin'] && isset($_POST['subject']) && $_POST['subject'] == $fData['password']) {
        $delmess = (preg_match("/^\d/", $_POST['message'])) ? (int)$_POST['message'] : $_POST['parent'];

        if ($delmess != -1) {
          $check = get_row($delmess);

          if (mysql_num_rows($check)) {
            $fData['topPost'] = findMatron($delmess);

            snipThread($delmess);
            reindexThreadDown($fData['topPost'][3]);
            $fData['success'] = sprintf($lang['post1'], $delmess);
            $fData['msgtotal'] = mysql_num_rows(get_all());

          } else $fData['error'] = sprintf($lang['post2'], $delmess);
        } else $fData['error'] = $lang['post3'];

      } else if ((!isset($_POST['subject']) || !$_POST['subject']) && (!isset($_POST['message']) || !$_POST['message'])) {
        $fData['error'] = $lang['post4'];

      } else if (isset($pData['lastmsg']['date']) && $pData['lastmsg']['date'] + 30 > $fData['time']) {
        $fData['error'] = $lang['post5'];

      } else if (isset($pData['lastmsg']['message']) && similar_text($pData['lastmsg']['message'], $_POST['message']) > strlen($_POST['message']) * 0.95) {
        $fData['error'] = $lang['post6'];

      } else {
        
        if ($_POST['author']) {
          $qData['author'] = (strlen($_POST['author']) > 32) ? substr($_POST['author'], 0, 32) : $_POST['author'];
        } else $qData['author'] = $lang['messageb'];

        if ($_POST['subject']) {
          $qData['subject'] = (strlen($_POST['subject']) > 64) ? substr($_POST['subject'], 0, 64) : $_POST['subject'];
        } else $qData['subject'] = "<{$lang['message9']}>";

        if (!$_POST['message']) {
          $qData['message'] = $lang['messagea'];
          $qData['msghtml'] = $lang['messagea'];
          $qData['subject'] .= " (n/t)";
        } else {
          $qData['message'] = $_POST['message'];
          $qData['msghtml'] = parseMessage($_POST['message']);
        }

        $qData['image'] = (preg_match("/http:\/\/\w\S*\.(png|gif|jpg|jpeg|bmp|php|asp)/i", $_POST['image'])) ? $_POST['image'] : "";

        $qData['email'] = (preg_match("/^([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)$/i", $_POST['email'])) ? $_POST['email'] : "";

        $qData['notify'] = ($_POST['email'] && isset($_POST['notify'])) ? "yes" : "no";

        $qData['parent'] = $_POST['parent'];

        while (list($key, $value) = each($qData)) $qData[$key] = addslashes($value);

        $insert = mysql_query("INSERT INTO `{$dData['tablename']}` VALUES ('', '{$_SERVER['REMOTE_ADDR']}', '{$qData['author']}', '{$qData['subject']}', '{$qData['message']}', '{$qData['msghtml']}', '{$qData['image']}', '".time()."', '0', '{$qData['email']}', '{$qData['notify']}', '{$qData['parent']}')");

        $retrieve = mysql_query("SELECT * FROM `{$dData['tablename']}` ORDER BY `date` DESC;");
        if (mysql_result($retrieve, 0, "pid") % 10 == 0) $optimize = mysql_query("OPTIMIZE TABLE `{$dData['tablename']}`;");

        reindexThreadUp(mysql_result($retrieve, 0, "pid"), mysql_result($retrieve, 0, "date"));

        $fData['success'] = $lang['post7'];
        $fData['msgtotal']++;

        if (isset($_POST['cookify'])) {
          $cookout = base64_encode("{$qData['author']} :: {$qData['email']} :: {$qData['notify']} :: {$qData['image']}");
          setcookie("of_cookie", $cookout, time() + 2592000, $_SERVER['PHP_SELF']);
          $_COOKIE['of_cookie'] = $cookout;
        } else setcookie ("of_cookie", "", time() - 86400, $_SERVER['PHP_SELF']);

        if (($qData['parent'] != "-1" && $pData['parent']['notify'] == "yes") || ($qData['parent'] == "-1" && $fData['notify'])) {

          if ($qData['parent'] == "-1") {
            $lang['email1'] = $lang['email2'];
            $lang['subject1'] = $lang['subject2'];
            $pData['parent']['author'] = "";
            $pData['parent']['subject'] = "";
            $pData['parent']['date'] = time();
            $pData['parent']['email'] = $fData['emailreply'];
          }

          @ini_set("sendmail_from", $fData['emailreply']);

          $headers = "From: {$fData['emailfrom']} <{$fData['emailreply']}>\r\n";
          $headers .= "X-Sender: <{$fData['emailreply']}>\r\n";
          $headers .= "Return-Path: <{$fData['emailreply']}>\r\n";
          $headers .= "Errors-To: <{$fData['emailreply']}>\r\n";
          $headers .= "X-Mailer: PHP - {$fData['UA']}\r\n";
          $headers .= "X-Priority: 3\r\n";
          $headers .= "Date: ".date("r")."\r\n";
          $headers .= "Content-Type: text/plain; charset=UTF-8";

          $message = sprintf($lang['email1'], $pData['parent']['author'], $pData['parent']['subject'], dateStamp($pData['parent']['date'] + $fData['offset']),
                       $fData['timezone'], $qData['subject'], $qData['author'], dateStamp(time() + $fData['offset']), strip_tags($qData['msghtml']),
                       "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?msg=".mysql_result($retrieve, 0, "pid"));

          if (!@mail($pData['parent']['email'], $lang['subject1'], $message, $headers, "-f{$fData['emailreply']}"))
            @mail($pData['parent']['email'], $lang['subject1'], $message, $headers);

          @ini_restore("sendmail_from");

        }
      }
      break;

    /* ***** Mark ********************************************* */
    case "Mark":
      setcookie("of_mark", $_POST['time'], time() + 900, $_SERVER['PHP_SELF']);
      $_COOKIE['of_mark'] = $_POST['time'];

      break;

  }

/* ***** Accept Incoming Get ********************************** */
} else {

  if (isset($_GET['msg'])) {
    $_GET['msg'] = (int)$_GET['msg'];
    $fData['message'] = get_row($_GET['msg']);

    if (mysql_num_rows($fData['message'])) {
      $fData['matron'] = findMatron($_GET['msg']);

    } else {
      $fData['error'] = sprintf($lang['get1'], $_GET['msg']);
      unset($_GET['msg']);
      $fData['notfound'] = true;

    }
  }

  if (isset($_GET['s'])) {
    if ($fData['search'] = preg_replace("/[^\s\w]/i", "", preg_replace("/\s{2,}/i", " ", stripslashes(trim($_GET['s']))))) {
      $sList = explode(" ", $fData['search']);

      $buildq = "";
      foreach($sList as $term) $buildq .= " (`subject` LIKE '%".addslashes($term)."%' OR `message` LIKE '%".addslashes($term)."%') AND";

      $fData['sch'] = mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE".preg_replace("/\sAND$/i", "", $buildq).";");

      if (mysql_num_rows($fData['sch'])) {
        $mList = array();
        for ($x = 0; $x < mysql_num_rows($fData['sch']); $x++) {
          $matGet = findMatron(mysql_result($fData['sch'], $x, "pid"));
          $mList[] = $matGet[3];
        }
        $mList = array_unique($mList);

        $buildq = "";
        foreach ($mList as $matron) $buildq .= " `pid`='$matron' OR";
      
        $fData['schlist'] = mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE".preg_replace("/\sOR$/i", "", $buildq)." ORDER BY `pid` DESC;");

      } else $fData['schlist'] = mysql_query("SELECT * FROM `{$dData['tablename']}` LIMIT 0;");

      $fData['schlistrows'] = mysql_num_rows($fData['schlist']);

    } else unset($fData['search']);
  }
}


/* ***** Prepare Workspace ************************************ */
$vData['parent'] = "-1";

if (isset($_COOKIE['of_cookie'])) {
  $crumble = explode(" :: ", base64_decode($_COOKIE['of_cookie']));
  $vData['author'] = htmlspecialchars($crumble[0]);
  $vData['email'] = htmlspecialchars($crumble[1]);
  $vData['notify'] = $crumble[2];
  $vData['subject'] = "";
  $vData['message'] = "";
  $vData['image'] = htmlspecialchars($crumble[3]) or $vData['image'] = "http://";
  $vData['date'] = $fData['time'];
  $vData['cookify'] = "Yes";

} else {
  $vData['author'] = "";
  $vData['email'] = "";
  $vData['notify'] = "no";
  $vData['subject'] = "";
  $vData['message'] = "";
  $vData['image'] = "http://";
  $vData['date'] = $fData['time'];
  $vData['cookify'] = "No";

}

if ($_POST['command'] == "Preview") {
  $mData['author'] = htmlspecialchars((strlen($_POST['author']) > 32) ? substr($_POST['author'], 0, 32) : $_POST['author']);
  $mData['email'] = htmlspecialchars(parseEmail($_POST['email']));
  $mData['notify'] = (isset($_POST['notify'])) ? $_POST['notify'] : NULL;
  $mData['subject'] = htmlspecialchars((strlen($_POST['subject']) > 64) ? substr($_POST['subject'], 0, 64) : $_POST['subject']);
  $mData['message'] = parseMessage($_POST['message']) or $mData['subject'] .= " (n/t)";
  $mData['image'] = htmlspecialchars(parseImage($_POST['image']));
  $mData['date'] = $fData['time'];

  $vData['author'] = $mData['author'];
  $vData['email'] = htmlspecialchars($_POST['email']);
  $vData['notify'] = $mData['notify'];
  $vData['subject'] = htmlspecialchars($_POST['subject']);
  $vData['message'] = htmlspecialchars($_POST['message']);
  $vData['image'] = htmlspecialchars($_POST['image']);
  $vData['cookify'] = (isset($_POST['cookify'])) ? "Yes" : "No";
  $vData['parent'] = $_POST['parent'];

  if ($vData['parent'] != "-1") $mData['prevmsg'] = get_row($vData['parent']);

} else if (isset($_GET['msg'])) {
  $mData['author'] = htmlspecialchars(mysql_result($fData['message'], 0, "author"));
  $mData['email'] = htmlspecialchars(parseEmail(mysql_result($fData['message'], 0, "email")));
  $mData['subject'] = htmlspecialchars(mysql_result($fData['message'], 0, "subject"));
  $mData['message'] = mysql_result($fData['message'], 0, "msghtml");
  $mData['image'] = htmlspecialchars(parseImage(mysql_result($fData['message'], 0, "image")));
  $mData['date'] = mysql_result($fData['message'], 0, "date") + $fData['offset'];

  $vData['subject'] = (preg_match("/^re:\s/i", $mData['subject'])) ? $mData['subject'] : "Re: {$mData['subject']}";
    $vData['subject'] = preg_replace("/\s\(n\/t\)$/i", "", $vData['subject']);
  $vData['message'] = (mysql_result($fData['message'], 0, "message")) ? htmlspecialchars("[quote=".mysql_result($fData['message'], 0, "author")."]\n".mysql_result($fData['message'], 0, "message")."[/quote]\n") : "";
  $vData['parent'] = $_GET['msg'];

  if (mysql_result($fData['message'], 0, "parent") != "-1") $mData['prevmsg'] = get_row(mysql_result($fData['message'], 0, "parent"));

  $fData['pagetitle'] = $mData['subject'];
}

if (isset($mData['prevmsg'])) {
  $mData['prevsubject'] = htmlspecialchars(mysql_result($mData['prevmsg'], 0, "subject"));
  $mData['prevauthor'] = htmlspecialchars(mysql_result($mData['prevmsg'], 0, "author"));
  $mData['prevpid'] = mysql_result($mData['prevmsg'], 0, "pid");
}

$lData['top'] = get_kids($vData['parent']);
$lData['toprows'] = mysql_num_rows($lData['top']);
if ($fData['maxthreads'] != 0 && $vData['parent'] == "-1" && $lData['toprows'] > $fData['maxthreads'])
  for ($x = $fData['maxthreads']; $x < $lData['toprows']; $x++) snipThread(mysql_result($lData['top'], $x, "pid"));

if (isset($fData['search'])) {
  $lData['top'] = $fData['schlist'];
  $lData['toprows'] = $fData['schlistrows'];
}

if ($fData['start'] > $lData['toprows']) $fData['start'] = 0;


/* ***** Do not cache this page ******************************* */
if (!isset($fData['notfound'])) {
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
} else header("HTTP/1.1 404 Not Found"); ?>