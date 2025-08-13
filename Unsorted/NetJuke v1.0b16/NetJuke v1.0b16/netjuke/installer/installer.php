<?php

$browser = true;

if (    (strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"mozilla/4"))
     && (strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"netscape")) ) $browser = false;

if ( strstr($_SERVER['HTTP_USER_AGENT'],"Mozilla/3") ) $browser = false;

if ( strstr($_SERVER['HTTP_USER_AGENT'],"Mozilla/2") ) $browser = false;

if ( strstr($_SERVER['HTTP_USER_AGENT'],"Mozilla/1") ) $browser = false;

if ($browser == false) {

?>

  <div style="font-family: sans-serif;">
    <b style="color: #FF0000;">BROWSER VERSION ERROR</b>
    <br><br>
    Browser: <span style="color: #FF0000;"><?php echo  $_SERVER['HTTP_USER_AGENT'] ?></span>
    <br><br>
    Sorry, but you cannot use Netscape 4.x (or lower) to install the netjuke because of major javascript compatibility issues.
    <br><br>
    Please use the Open-Source <a href="http://www.mozilla.org/releases/" target="_blank">Mozilla</a>,
    or <a href="http://home.netscape.com/computing/download/" target="_blank">Netscape 6</a>,
    or MS Internet Explorer, or <a href="http://www.konqueror.org/" target="_blank">Konqueror</a>, etc.
    <br><br>
    Sorry for this limitation, and thank you for trying and / or using the Artekopia Netjuke
  </div>

<?php

  exit;

}

if (phpversion() < 4.1) {

?>

  <div style="font-family: sans-serif;">
    <b style="color: #FF0000;">PHP VERSION ERROR</b>
    <br><br>
    Your PHP Version: <span style="color: #FF0000;"><?php echo phpversion() ?></span>
    <br>Required PHP Version: <span style="color: #FF0000;">4.1 and above.</span>
    <br><br>
    Sorry, but you need a version of PHP higher than 4.1 to run the netjuke because of major changes made to PHP in and after version 4.1.
    <br><br>
    Please see the <a href="http://www.php.net/" target="_blank">PHP</a> web site to upgrade your existing copy of PHP on the computer running this application.
    <br><br>
    Sorry for this limitation, and thank you for trying and / or using the Artekopia Netjuke
  </div>

<?php

  exit;

}

if (!isset($_REQUEST)) {

?>

  <div style="font-family: sans-serif;">
    <b style="color: #FF0000;">PHP CONFIG ERROR</b>
    <br><br>
    Your PHP Version: <span style="color: #FF0000;"><?php echo phpversion() ?></span>
    <br><br>
    Sorry, but PHP is somehow unable to process input adequately.
    <br>
    Unable to access the $_REQUEST pre-defined variable.
    <br><br>
    Please see the <a href="http://netjuke.sourceforge.net/" target="_blank">Netjuke Web Site</a> for more info and help.
    <br><br>
    Sorry, and thank you for trying and / or using the Artekopia Netjuke
  </div>

<?php

  exit;

}

define("ACCEPT_INSTALL","Accept & Install");
define("ACCEPT_UPGRADE","Accept & Upgrade");

define("INSTALL_STR","install");
define("UPGRADE_STR","upgrade");

define("DO_INSTALL","Install Form");
define("DO_UPGRADE","Upgrade Form");

define("DO_INSTALL_GO","Proceed With Install");
define("DO_UPGRADE_GO","Proceed With Upgrade");

define("NETJUKE_VERSION_FILE","../VERSION.txt");

if (file_exists(NETJUKE_VERSION_FILE)) {
  $fp = fopen(NETJUKE_VERSION_FILE,'r');
  define('NETJUKE_VERSION',@fread($fp, @filesize(NETJUKE_VERSION_FILE)));
  fclose($fp);
} else {
  define('NETJUKE_VERSION', '(?)');
}

if ( ($_REQUEST['do'] == ACCEPT_INSTALL) || ($_REQUEST['do'] == ACCEPT_UPGRADE) ) {

  include ("./lib/inc-docs.php");

} elseif ( ($_REQUEST['do'] == DO_INSTALL) || ($_REQUEST['do'] == DO_UPGRADE) ) {

  include ("./lib/inc-form.php");

} elseif ( ($_REQUEST['do'] == DO_INSTALL_GO) || ($_REQUEST['do'] == DO_UPGRADE_GO) ) {

  include ("./lib/inc-generate.php");

} else {

  include ("./lib/inc-license.php");

}

exit;



########################################

function alert ($msg) {
   
  echo "
   <HTML>
   <SCRIPT LANGUAGE=\"Javascript\">
   <!--
     alert(\"$msg\");
     self.history.go(-1);
   //-->
   </SCRIPT>
   </HTML>
  ";

  exit;

}

########################################

?>
