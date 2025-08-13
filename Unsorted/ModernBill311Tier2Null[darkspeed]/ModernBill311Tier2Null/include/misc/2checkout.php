<?
#########################################################
#                                                       #
#         ModernBill .:. Client Billing System          #
#  Copyright © 2001 ModernBill   All Rights Reserved.   #
#                                                       #
#########################################################
function checkout_gateway($string)
{
   GLOBAL $debug,
          $path_to_curl,
          $checkout_sid,
          $checkout_test,
          $this_charge;

   $string = "x_Login=$checkout_sid&demo=$checkout_test&$string";
   $string = str_replace("'","",$string);
   if($debug)echo SFB.$string.EF."<br>";

   $url = "https://www.2checkout.com/cgi-bin/Abuyers/purchase.2c";

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