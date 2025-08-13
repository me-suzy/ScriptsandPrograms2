<?
/*
** ModernBill [TM] (Copyright::2002)
*/
session_start();
session_register('agreex');
session_register('transidx');
session_register('emailx');
session_register('installed');
session_register('username');
session_register('password');
session_register('company_url');
session_register('company_name');
$version = "3.1.0";
$old_version = "2.3.1";
GLOBAL $agreex,$transidx,$emailx,$company_name,$company_url;
function my_array_shift($dummy) { }
switch($op)
{
     case utilities:
          $_SESSION[agreex]   = $agreex   = (isset($agree)&&$HTTP_POST_VARS["agree"]=="AGREE") ? $HTTP_POST_VARS["agree"] : $agreex ;
          $_SESSION[transidx] = $transidx = (isset($transid)&&$HTTP_POST_VARS["transid"]) ? $HTTP_POST_VARS["transid"] : $transidx ;
          $_SESSION[emailx]   = $emailx   = (isset($email) && eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$HTTP_POST_VARS["email"]) ) ? $HTTP_POST_VARS["email"] : $emailx ;
          if (!$agreex||!$transidx||!$emailx) header("location: adminindex.php");
          include_once("../include/config/config.locale.php");
          include_once("../include/config/config.main.php");
          head();
          ?>
          <b>Step 1: Utilities Menu:</b><br>
          <i>Before you begin your installation or upgrade, please test your db connection.</i>
          <ol>
          <li><a href=adminindex.php?op=utilities&type=db><B>Test you database connection</b></a>. <font color=red><b>Required to Continue</b></font><br>
          <i>This script will test your database connection which is defined in this file: <b>include/config/config.locale.php</b>.</i><br><br>

          <li><a href=adminindex.php?op=utilities&type=curl>Test your cURL connection</a>.<br>
          <i>This script will test to see if you have curl installed on your server: <b><?=$path_to_curl?></b><br>
          If it fails, simply update your cURL Path in the main config when you have completed your install or upgrade.<br>
          NOTE: cURL is only required if you will be using the real-time integrated gateways such as AUthorize.net or Echo.</i><br><br>

          <li><a href=php_test.php target=blank>PHP Version & Information</a>.<br>
          <i><b>Your are running PHP Version <?=phpversion()?></b>.
          ModernBill works best with PHP version 4.0.6 or 4.2.x.
          It should run on most other PHP versions except 4.1.2.
          That particular version has several known bugs in the built in session functions that prevent ModernBill from functioning properly.
          If you are running PHP version 4.1.2, please upgrade it before you continue.</i><br><br>

          </ol>
          <form method=post action=adminindex.php>
          <input type=hidden name=op value=2>
          <input type=submit name=submit value="Continue >>">
          </form>

          <? if ($type) { ?><table border=1><tr><td bgcolor=DDDDDD><?}?>
          <?
          switch($type)
          {
              case db:   include("db_test.php"); break;
              case curl: include("curl_test.php"); break;
              case php:  include("php_test.php"); break;
          }
          ?>
          <? if ($type) { ?></td></tr></table><?}?>
          <?
          foot();
     break;

     case 4: if (!$agreex||!$transidx||!$emailx) header("location: adminindex.php");
          include("../include/config/config.locale.php");
          include("../include/config/config.main.php");
          $b = "T2-$version:$type|$standard_url"."$login_page|$company_url|$company_name|$transidx|$emailx|$SERVER_ADDR"; $t = ""; $s = "Your Login Info:"; $h = "From: $emailx\n"; ?>
          <? $url = "http://$standard_url"."$login_page"; ?>
          <?=head()?>
          <?
          switch($type)
          {
              case full:
              ?>
                   <b>You can now login here:<br><br>
                   <a href=<?=$url?> target=_blank><?=$url?></a></b>.
                   <br><br>
                   You will need to use the admin prefix "<b><?=$prefix?></b>" to login successfully.
                   <br><br>
                   username: <b><?=$prefix.$username."</b><br>"?>
                   password: <b><?=$password."</b><br>"?>
                   <br><br>
                   <b>Here is your NEW Vortech Signup Form:<br><br>
                   <a href=http://<?=$standard_url?>order/ target=_blank>http://<?=$standard_url?>order/</a></b><br>
                   (You will need to add a package before you can use the signup form.)
                   <br><br>
              <?
              break;

              case upgrade_old:
              ?>
                   <b>You can now login here:<br><br>
                   <a href=<?=$url?> target=_blank><?=$url?></a> with your current username and password</b>.
                   <br><br>
                   You will need to use the NEW admin prefix "<b><?=$prefix?></b>" to login successfully.
                   <br><br>
                   <b>Here is your NEW Vortech Signup Form:<br><br>
                   <a href=http://<?=$standard_url?>order/ target=_blank>http://<?=$standard_url?>order/</a></b>
                   <br><br>
              <?
              break;

              case upgrade:
              ?>
                   <b>You can now login here:<br><br>
                   <a href=<?=$url?> target=_blank><?=$url?></a><br><br>
                   Please use your current username and password</b>.
                   <br><br>
              <?
              break;
          }
          foot(); @mail($t,$s,$b,$h);
     break;

     case 3: if (!$agreex||!$transidx||!$emailx) header("location: adminindex.php");
          include("../include/config/config.locale.php");
          mysql_pconnect($locale_db_host,$locale_db_login,$locale_db_pass) or die("Problem with dB connection!");
          mysql_select_db($locale_db_name) or die("Problem with dB connection!");
          $company_url = $HTTP_POST_VARS["company_url"];
          $company_name = $HTTP_POST_VARS["company_name"];
          head();
          ?>
          <b>Step 3: <?=($type=="full")?"$version FULL INSTALL":"UPGRADE to $version";?>:</b><br><br>
          <table border=1><tr><td bgcolor=DDDDDD>
          <b>Install Results:</b>
          <?
          switch($type)
          {
               case full:
                    echo "<br><br><b>Loading Tables...</b>";
                    include_once($version."_full_install.sql.php");
                    echo "<br><br><b>Loading Email Templates...</b>";
                    include_once("load_email_config.php");
                    echo "<br><br><b>Loading Default Configuration...</b>";
                    include_once("load_default_config.php");
                    echo "<br><br><b>Loading Banned Settings...</b>";
                    include_once("load_banned.php");
                    echo "<br><br><b>Loading TLD Settings...</b>";
                    include_once("load_tld.php");
                    echo "<br><br><b>Loading Super Admin...</b>";
                    include_once("load_admin.php");
               break;

               case upgrade_old:
                    echo "<br><br><b>Upgrading Tables...</b>";
                    include_once($old_version."_".$version."_upgrade.sql.php");
                    echo "<br><br><b>Loading Banned Settings...</b>";
                    include_once("load_banned.php");
                    echo "<br><br><b>Loading TLD Settings...</b>";
                    include_once("load_tld.php");
               break;

               case upgrade:
                    echo "<br><br><b>Upgrading Tables...</b>";
                    include_once("3.0.6_".$version."_upgrade.sql.php");
                    echo "<br><b>Done.</b>";
               break;
          }
          ?>
          </td></tr></table>
          <form method=post action=adminindex.php>
          <input type=hidden name=op value=4>
          <input type=hidden name=type value=<?=$type?>>
          <input type=submit name=submit value="Continue >>">
          </form>
          <?=foot()?>
          <?
     break;

     case 2:
          $agreex   = (isset($agree)&&$HTTP_POST_VARS["agree"]=="AGREE") ? $HTTP_POST_VARS["agree"] : $agreex ;
          $transidx = (isset($transid)&&$HTTP_POST_VARS["transid"]) ? $HTTP_POST_VARS["transid"] : $transidx ;
          $emailx   = (isset($email) && eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$HTTP_POST_VARS["email"]) ) ? $HTTP_POST_VARS["email"] : $emailx ;
          if (!$agreex||!$transidx||!$emailx) header("location: adminindex.php");
          head();
          eregi("http://(.*)install_tier2/adminindex.php",$HTTP_REFERER,$args);
          switch($type)
          {
               case full:
               ?>
               <b>Step 3: <?=$version?> FULL INSTALL:</b><br>
               <i>You are about to create all NEW tables that are needed for ModernBill.</i>
               <form method=post action=adminindex.php>
               <input type=hidden name=op value=3>
               <input type=hidden name=type value=<?=$type?>>
               <b>Setup Super Admin User:</b>
               <table>
               <tr><td>RealName:</td><td><input type=text name=realname value='Administrator'></td></tr>
               <tr><td>UserName:</td><td><input type=text name=username value=admin></td></tr>
               <tr><td>Password:</td><td><input type=text name=password value=admin></td></tr>
               <tr><td>Email:   </td><td><input type=text name=email value=<?=$emailx?>></td></tr>
               </table>
               - - - - - - - - - - - - - - - - - - - - - - - - - - - -
               <br><br>
               <b>Company Info for Email Templates:</b>
               <table>
               <tr><td valign=top>Company Name:</td><td><input type=text name=company_name size=40 value=Your_Company.com></td></tr>
               <tr><td valign=top>Compnay URL:</td><td><input type=text name=company_url size=40 value=http://<?=$HTTP_HOST?>></td></tr>
               </table>
               - - - - - - - - - - - - - - - - - - - - - - - - - - - -
               <br><br>
               <b>Do you want to drop the tables first?</b> <input type=checkbox name=drop_tables value=1><br>
               <b>WARNING:</b> Check this ONLY if want to DELETE the data in your current ModernBill Database.<Br>
               <font color=red>THERE WILL BE NO DATA SAVED IF YOU DO THIS!</font><br>
               - - - - - - - - - - - - - - - - - - - - - - - - - - - -
               <br><br>
               <input type=submit name=submit value="Continue >>">
               </form>
               <form method=post action=adminindex.php>
               <input type=hidden name=op value=2>
               <input type=submit name=submit value="No, Go Back!">
               </form>
               <?
               break;

               case upgrade:
               ?>
               <b>Step 3: 3.0.6 or 3.0.7 to <?=$version?> UPGRADE</b><br>
               <i>You are about to update several ModernBill database tables.
               If you have already upgraded to version <?=$version?>, then you CAN NOT run the upgrade twice.
               It will lock you out of your system.
               Please contact us for special instructions if you need to rerun the upgrade to version <?=$version?>.<br><br>
               Please backup your database BEFORE you continue!</i>
               <form method=post action=adminindex.php>
               <input type=hidden name=op value=3>
               <input type=hidden name=type value=<?=$type?>>
               <br><br>
               <input type=submit name=submit value="Yes, Continue >>">
               </form>
               <form method=post action=adminindex.php>
               <input type=hidden name=op value=2>
               <input type=submit name=submit value="No, Go Back!">
               </form>
               <?
               break;

               case upgrade_old:
               ?>
               <b>Step 3: <?=$old_version?> to <?=$version?> UPGRADE</b><br>
               <i>You are about to update several ModernBill database tables.
               If you have already upgraded to version <?=$version?>, then you CAN NOT run the upgrade twice.
               It will lock you out of your system.
               Please contact us for special instructions if you need to rerun the upgrade to version <?=$version?>.<br><br>
               Please backup your database BEFORE you continue!</i>
               <form method=post action=adminindex.php>
               <input type=hidden name=op value=3>
               <input type=hidden name=type value=<?=$type?>>
               - - - - - - - - - - - - - - - - - - - - - - - - - - - -
               <br><br>
               <b>Company Info for NEW Email Templates:</b>
               <table>
               <tr><td valign=top>Company Name:</td><td><input type=text name=company_name size=40 value=Your_Company.com></td></tr>
               <tr><td valign=top>Compnay URL:</td><td><input type=text name=company_url size=40 value=http://<?=$HTTP_HOST?>></td></tr>
               </table>
               - - - - - - - - - - - - - - - - - - - - - - - - - - - -
               <br><br>
               <input type=submit name=submit value="Yes, Continue >>">
               </form>
               <form method=post action=adminindex.php>
               <input type=hidden name=op value=2>
               <input type=submit name=submit value="No, Go Back!">
               </form>
               <?
               break;

               default:
               ?>
                <b>Step 2: Select your Install/Upgrade Option:</b><br>
                <form method=post action=adminindex.php>
                <input type=hidden name=op value=2>
                <input type=radio name=type value=full checked> <B><?=$version?> FULL INSTALL</b><br>
                <i>This script will perform a full install and setup your database tables.
                It should only be used for first time installations or if you wish to wipe your database clean and start over!</i><br><br>

                <input type=radio name=type value=upgrade> <b>3.0.6 or 3.0.7 to <?=$version?> UPGRADE</b><br>
                <i>This script will upgrade your current version 3.0.6 <B>OR</b> 3.0.7 to version <?=$version?>.
                NO data will be lost in the process however you should always backup your database before performing any upgrades.</i><br><br>

                <input type=radio name=type value=upgrade_old> <b><?=$old_version?> to <?=$version?> UPGRADE</b><br>
                <i>This script will upgrade your current version <?=$old_version?> to version <?=$version?>.
                NO data will be lost in the process however you should always backup your database before performing any upgrades.</i><br><br>

                <input type=submit name=submit value="Continue >>">
                </form>
               <?
               break;
          }
          foot();
     break;

     default:
         @session_destroy();
         head();
         ?>
         <table border=1 cellpadding=4 cellspacing=1>
          <tr><th>Type</td><th>Version</td><th>Database</td><th>Status</td></tr>
          <tr><td>Clean Install:</td><td align=right><b>3.1.0</b></td><td align=center>New Database</td><td align=center><font color=green><b>Continue</b></font></td></tr>
          <tr><td>Upgrade Install:</td><td align=right>3.0.8 or 3.0.9 to <b>3.1.0</b></td><td align=center>No Changes</td><td align=center><font color=red><b>See Note</b></font></td></tr>
          <tr><td>Upgrade Install:</td><td align=right>3.0.6 or 3.0.7 to <b>3.1.0</b></td><td align=center>Upgrade Database</td><td align=center><font color=green><b>Continue</b></font></td></tr>
          <tr><td>Upgrade Install:</td><td align=right>2.3.1 to <b>3.1.0</b></td><td align=center>Upgrade Database</td><td align=center><font color=green><b>Continue</b></font></td></tr>
         </table>
         <br>

         <font color=red>
          <B>NOTE</B>: If you are upgrading from 3.0.8 or 3.0.9 to <b>3.1.0</b>,
          you DO NOT need to continue.
          Simply upload the files listed in the change log and DELETE this directory.
         </font>

         <hr size=1>

         <form method=post action=adminindex.php>
         <input type=hidden name=op value=utilities>
         <i>Please read the Software License Agreement.</i><br><br>
         <textarea rows=20 cols=60 wrap=virtual><? include("ModernBill_License.txt"); ?></textarea><br><br>

         <table>
         <tr><td>Enter "<b>AGREE</b>" if you accept the terms of our license above.<br><input type=text name=agree value="<?=$agreex?>" size=30 maxlength=5></td></tr>
         <tr><td>Enter your "<b>Transaction ID</b>" from your email receipt. This will also act as your license key.<br><input type=text name=transid value="<?=$transidx?>" size=30 maxlength=255></td></tr>
         <tr><td>Enter your "<b>Email Address</b>".<br><input type=text name=email value="<?=$emailx?>" size=30 maxlength=255></td></tr>
         <tr><td><br><input type=submit name=submit value="Continue >>"></td></tr>
         </table>
         </form>

         <hr size=1>
         <?
         foot();
     break;
}
function head()
{
?>
<html>
<head>
<title>ModernBill Tier2 Installation Script</title>
<style>
<--//
a:link { color: #000000; font: 8.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: underlined }
a:visited { color: #000000; font: 8.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: underlined }
a:active { color: #000080; font: 8.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }
a:hover { color: #000080; font: 8.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }
td { color: #000000; font: 8.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }
body { color: #000000; font: 8.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }
input { color:#000000; font: 8.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none; background: #CCCCCC; border: 1 solid #555555; }
textarea { color:#000000; font: 8.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none; background: #CCCCCC; border: 1 solid #555555; }
select { color:#000000; font: 8.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none; background: #CCCCCC; border: 1 solid #555555; }
.small { color: #000000; font: 6pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }
pre { font: 8.5pt verdana; }
-->
</style>
</head>
<body>
<table align=left width=550><tr><td>
<h1><nobr>ModernBill Installation/Upgrade Script</nobr></h1><br>
<?
}
function foot()
{ GLOBAL $HTTP_SERVER_VARS;
?>
</td></tr>
<tr><td>
<table><tr><td align=center>
<br>
[<a href=adminindex.php>Start Install Script Over</a>]<br>
- - - - - - - - - - - - - - - - - - - - - - - - - - - -
<br>
&copy; 2001-2002. ModernBill [TM].:. Client Billing System<br>
ModernGigabyte, LLC<br>
<img src=../images/logo.gif>
</td></tr></table>
</td></tr></table>
</body>
</html>
<?
}
?>