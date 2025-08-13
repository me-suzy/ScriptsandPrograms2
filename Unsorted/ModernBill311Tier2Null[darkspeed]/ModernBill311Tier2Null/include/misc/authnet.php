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

function authnet_gateway($string)
{
   GLOBAL $debug,
          $path_to_curl,
          $x_Login,
          $x_Version,
          $x_Test_Request,
          $x_Email_Customer,
          $this_charge,
          $x_Gateway;

   list($login_1,$login_2) = explode("|",$x_Login);
   $pw = ($login_2!="") ? "x_Password=$login_2&" : NULL ;

   $string = "x_Login=$login_1&".$pw."x_Version=$x_Version&x_ADC_URL=FALSE&x_ADC_Delim_Data=TRUE&x_Test_Request=$x_Test_Request&x_Email_Customer=$x_Email_Customer&$string";
   $string = str_replace("'","",$string);
   if($debug)echo SFB.$string.EF."<br>";

   switch ($x_Gateway) {
      case authorize:     $url = "https://secure.authorize.net/gateway/transact.dll";        break;
      case planetpayment: $url = "https://secure.planetpayment.com/gateway/transact.dll";    break;
      case ecx:           $url = "https://secure.quickcommerce.net/gateway/transact.dll";    break;
      case epn:           $url = "https://www.eprocessingnetwork.com/cgi-bin/an/order.pl";   break;
      case rtware:        $url = "https://www.rtware.com/cgi-bin/an/order.pl";               break;
      case mcps:          $url = "https://secure.merchantcommerce.net/gateway/transact.dll"; break;
      default:            $url = "https://secure.authorize.net/gateway/transact.dll";        break;
   }

   if ($path_to_curl=="PHP") {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_VERBOSE, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      $authorize = curl_exec($ch);
      curl_close ($ch);
      $this_charge = split("\,", $authorize);
   } else {
      @exec("$path_to_curl -d '$string' $url",$authorize,$ret);
      $this_charge = split("\,", $authorize[0]);
   }

   if($debug)
   {
      echo "<pre>";
      echo "exec(\"$path_to_curl -d '$string' $url\",$authorize,$ret);";
      print_r($this_charge);
      echo "<hr>";
      print_r($authorize);
      echo "<hr>";
      print_r($ret);
      echo "<hr>";
      for ($idx = 0; $idx < 39; ++$idx) { $pos = $idx+1; echo "Code".$pos.":  ".$this_charge[$idx]."<BR>"; }
      echo "</pre>";
   }
   return $this_charge;
}
?>