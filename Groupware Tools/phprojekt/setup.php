<?php

// setup.php - PHProjekt Version 5.0
// copyright © 2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: johann $
// $Id: setup.php,v 1.29.2.4 2005/09/10 12:03:56 johann Exp $

// set some variables
error_reporting(0);

// bypass lib authentication (will use it's own) ...
define('avoid_auth','1');
$path_pre = './';

if (!defined('PHPR_SESSION_NAME')) define('PHPR_SESSION_NAME', 'PHPRSETUP');


// include routine for session_register
include_once('./lib/gpcs_vars.inc.php');

// overwrite imported variables with request variables, if they are updated in the form
// session variables stay untouched
foreach($_REQUEST as $key => $value) {
    if (isset($_SESSION[$key]) && (!is_array($_REQUEST[$key]))) {
        $GLOBALS[$key] = $value;
    };
}
// check some possible exploits 
$step   = isset($_REQUEST['step']) ? $_REQUEST['step'] : ''; 
$setup  = isset($_REQUEST['setup']) ? $_REQUEST['setup'] : (isset($_SESSION['setup']) ? $_SESSION['setup'] : 'install');
$langua = isset($_REQUEST['langua']) ? $_REQUEST['langua'] : (isset($_SESSION['langua']) ? $_SESSION['langua'] : 'en');

if (strlen($langua) > 3 or strlen($step) > 2) die('You are not allowed to do this!');

// *** first check some limits and possible errors
// still PHP3? -> update!
if (substr(phpversion(),0,1) == '3') die ('<b>sorry, PHP 4 required!</b><br><br> Please download the current version at <a href="http://www.php.net">www.php.net</a>');

// session test: test for real path to files or db option
if (!is_dir(session_save_path()) and !preg_match("/^[a-zA-Z0-9]*\.[a-zA-Z0-9]*$/", session_save_path())) {
    echo "<b>There is no path to store the sessions given in the php.ini. Please run the session test in the tst script env_test.php to check whether sessions work in your environment. If this is not the case then you should define a temp path in the variable session.save_path which has write permissions for the webserver.<br><br>";
}
//$langua = "de";
if ($langua) {
  $langua = preg_replace('°[^\w]°', '', $langua);
  // include the language file ...
  include_once("./lang/$langua.inc.php");
  // ... and check whether this was successful
  // this is nasty, function is already defined in lib.inc.php, but I can't include lib.inc.php here (don't know why)
  function __($textid) {
    include_once ('lang/'.$GLOBALS['langua'].'.inc.php');
    return isset($GLOBALS['_lang'][$textid]) ? $GLOBALS['_lang'][$textid] : $textid;
  }
  if (!__('Hostname')) { die("Panic! I can't include the language file for $langua from folder '/lang' ..."); }
}
else {
  // check whether language files exist in their directory
  if (!file_exists('./lang/de.inc.php') and !file_exists('./lang/en.inc.php')) {
      die("Oops! Can't include or find the language files in the directory 'lang'!
           The folder structure must be kept while unzipping and the path to the language dir
           (as well to the /lib and other directories) must be in the include_path variable in the php.ini\n");
  }
}

// config exists? then check authentification
if ((file_exists("./config.inc.php") and filesize('./config.inc.php') > 0) or
    (file_exists('../../config.inc.php') and filesize('../../config.inc.php') > 0)) {
  if (!isset($_SESSION['ok'])) {
    if (isset($_REQUEST['admin_pw'])) {
      include_once('./lib/lib.inc.php');
      include_once('./lib/db/'.$db_type.'.inc.php');
      constants_to_vars();
      $result = db_query("SELECT pw, acc
                            FROM ".DB_PREFIX."users
                           WHERE nachname = '$nachname'");
      while ($row = db_fetch_row($result)) {
        // crypting password
        if (PHPR_PW_CRYPT || $pw_crypt == 1) $enc_pw = encrypt($admin_pw, $row[0]);
        else $enc_pw = $admin_pw;
        // check authentification
        if (ereg("a", $row[1]) and $enc_pw == $row[0]) {
            // the value does not matter, just the fact, that $_SESSION['ok'] is set
            $_SESSION['ok'] = 0;
        }
        else {  // not an admin password
          // destroy the session - on some system the first, on some system the second function doesn't work :-))
          @session_unset();
          @session_destroy();
          die('This is not an admin login combination! Please check whether you have given the <u>last</u> name of the admin login!');
        }
      }
    }
    // no admin_pw given? -> show him the form
    else {
      session_unset();
      echo "
<form action='setup.php' method='post'>
    <br>Config.inc.php found!<br>Please enter:<br>
    <table>
        <tr>
            <td>admin <b>last name</b>:</td>
            <td><input type='text' name='nachname'></td>
        </tr>
        <tr>
            <td>admin password:</td>
            <td><input type='password' name='admin_pw'></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>(e.g. root/root)</td>
        </tr>
    </table>
    <input type='submit' value='submit'>
</form>\n";
      exit;
    }
  }

  // last thing for extisting config: writeable?
  if (!is_writable("config.inc.php") and !is_writable("../../config.inc.php")) { die("<br><b>PANIC! config.inc.php can't be written! Please ensure that the webserver is able to write a new config"); }

}
// end check authentification
// else -> config not ex. -> test write for config. If it fails, show message
else {
  if (!$step) {
    $fp = @fopen("config.inc.php", 'wb+');
    // impossible to write the test file? -> error message
    if (!$fp) {
      die('Alert: Cannot create file "config.inc.php"!<br>The webserver needs the permission to write the file config.inc.php in the PHProjekt root directory.');
    }
    else {
      fclose($fp);
      unlink("config.inc.php");
      // if ok is already there, it is already in the session.
      // $_SESSION['ok'] =& $ok;
    }
  }
}

// next check for config file: if update or config, look whether the file is writeable
if ($setup <> "install") {
    if ( (file_exists("config.inc.php") and !is_writeable("config.inc.php")) or
         (file_exists("../../config.inc.php") and !is_writeable("../../config.inc.php")) ) {
        die ("Please remove the write protection");
    }
}


// **********
// write header of html output

// set charset
if      (eregi('pl|cz|hu|si', $langua)) $lcfg = 'charset=iso-8859-2';
else if (eregi('ee|lt|lv', $langua))    $lcfg = 'charset=windows-1257';
else if ($langua=='sk')                 $lcfg = 'charset=windows-1250';
else if ($langua=='ru')                 $lcfg = 'charset=windows-1251';
else if ($langua=='tw')                 $lcfg = 'charset=big5';
else if ($langua=='zh')                 $lcfg = 'charset=gb2312';
else if ($langua=='jp')                 $lcfg = 'charset=EUC-JP';
else                                    $lcfg = 'charset=iso-8859-1';

echo "<html>
<head>
<title>PHProjekt SETUP</title>
<style type='text/css' media='screen'>@import './layout/default/default_css.php';</style>
<style type='text/css' media='print'>@import './layout/default/default_css.php?print=1';</style>
<!--[if gte IE 5]><style type='text/css' media='screen'>@import './layout/default/default_css_ie.php';</style><![endif]-->
<meta http-equiv='Content-Type' content='text/html; $lcfg' />
<link rel='shortcut icon' href='./favicon.ico' />
</head>
<body bgcolor='#B8B8B8'>

<br /><br />
";
if ($step == '3') echo "<div style='margin-left:150px;'>\n";
else              echo "<center>\n";

// only Configuration? -> send him to the big form
if ($step == "1" and $setup <> "install") $step = "2a";

// ... but make sure the files stepx.php will not accessed directly
// check whether the lib has been included - authentication!
define("setup_included", "1");

// begin steps
include("./setup/step".$step.".php");

if ($step == '3') echo "\n</div>";
else              echo "\n</center>";
echo "
<br /><br />

</body>
</html>
";

?>
