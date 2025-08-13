<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+
##
## [DO NOT MODIFY/REMOVE BELOW]
##
if ($DIR && ($HTTP_COOKIE_VARS[DIR] || $HTTP_POST_VARS[DIR] || $HTTP_GET_VARS[DIR] || $_COOKIE[DIR] || $_POST[DIR] || $_GET[DIR])) {
    $ip   = $HTTP_SERVER_VARS[REMOTE_ADDR];
    $host = gethostbyaddr($ip);
    $url  = $HTTP_SERVER_VARS["HTTP_HOST"].$HTTP_SERVER_VARS["REQUEST_URI"];
    $admin= ($GLOBALS[SERVER_ADMIN]) ? $GLOBALS[SERVER_ADMIN] : "security@your.server.com";
    $body = "IP:\t$ip\nHOST:\t$host\nURL:\t$url\nVER:\t$version\nTIME:\t".date("Y/m/d: h:i:s")."\n";
    @mail($admin,"Possible breakin attempt.",$body,"From: $admin\r\n");
    print str_repeat(" ", 300)."\n";
    flush();
    ?>
    <html><head><body>
    <center><h3><tt><b><font color=RED>Security violation from: <?=$ip?> @ <?=$host?></font></b></tt></h3></center>
    <hr>
    <pre><? @system("traceroute ".escapeshellcmd($ip)." 2>&1"); ?></pre>
    <hr>
    <center><h2><tt><b><font color=RED>The admin has been alerted.</font></b></tt></h2></center>
    </body></html>
    <?
    exit;
}

include($DIR."include/misc/2checkout.php");

$string .= "x_invoice_num=".urlencode($x_Description)."&";
$string .= "x_amount=".     urlencode($x_Amount)."&";
if ($allow_credit_card&&$pay_method=="creditcard") {
   $string .= "x_Card_Num=".urlencode($x_Card_Num)."&";
   $string .= "x_Exp_Date=$x_Exp_Date&";
}
$string .= "x_First_Name=".urlencode($x_First_Name)."&";
$string .= "x_Last_Name=". urlencode($x_Last_Name)."&";
$string .= "x_Address=".   urlencode($x_Address)." ".urlencode($x_Address_2)."&";
$string .= "x_City=".      urlencode($x_City)."&";
$string .= "x_State=".     urlencode($x_State)."&";
$string .= "x_Zip=".       urlencode($x_Zip)."&";
$string .= "x_Country=".   urlencode($x_Country)."&";
$string .= "x_Phone=".     urlencode($x_Phone)."&";
$string .= "x_Email=$x_Email";

$this_charge=checkout_gateway($string);

switch($this_charge[0])
{
     case 1: ## APPROVED
          $auth_return = 1;
          $auth_code = $this_charge[4];
          $avs_code  = $this_charge[5];
          $trans_id  = $this_charge[6];
          if ($debug) echo "$auth_code,$avs_code,$trans_id<br>";
          $cc_result = "<b>".YOURORDERSUCCESS."</b><br>";
          $cc_result.= AUTHCODE.": $auth_code<br>";
          $cc_result.= AVSCODE.": $avs_code<br>";
          $cc_result.= TRANSID.": $trans_id<br>";
     break;

     case 2: ## DECLINED
          $auth_return = 2;
          $error_msg = "<b>".YOURORDERDECLINED."</b><br>";
          $declined=1;
          $z++;
     break;

     case 3: ## ERROR
          $auth_return = 3;
          $error_msg = "<b>".YOURORDEREERROR."</b><br>";
          $error=1;
          $z++;
     break;

     default: ## ERROR
          $auth_return = 4;
          $error_msg = "<b>".YOURORDEREERROR."</b><br>";
          $error=1;
          $z++;
     break;
}
?>