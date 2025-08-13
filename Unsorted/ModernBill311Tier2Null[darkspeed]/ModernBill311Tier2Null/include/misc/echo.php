<?
#########################################################
#                                                       #
#         ModernBill .:. Client Billing System          #
#  Copyright © 2001 ModernBill   All Rights Reserved.   #
#                                                       #
#########################################################
function echo_gateway($string)
{
   GLOBAL $debug,
          $path_to_curl,
          $echo_server,
          $merchant_echo_id,
          $merchant_pin,
          $echo_debug,
          $this_charge;

   $transaction_type = "EV";
   $order_type = "S";

   @mt_srand((double)microtime()*1000000);

   $counter = mt_rand();
   $string  = "transaction_type=$transaction_type&order_type=$order_type&counter=$counter&debug=$echo_debug&merchant_echo_id=$merchant_echo_id&merchant_pin=$merchant_pin&$string";
   $string  = str_replace("'","",$string);

   if($debug)echo SFB.$string.EF."<br>";

   if ($path_to_curl=="PHP") {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$echo_server);
      curl_setopt($ch, CURLOPT_VERBOSE, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      $authorize = curl_exec($ch);
      curl_close ($ch);
      $count=count($authorize);
   } else {
      @exec("$path_to_curl -d '$string' $echo_server",$authorize,$ret);
      $count=count($authorize);
   }

   for ($idx = 0; $idx < $count; ++$idx)
   {
      if (eregi("<ECHOTYPE3>(.*)</ECHOTYPE3>",$authorize[$idx],$echotype3))
      {
         eregi("<status>(.*)</status>",              $echotype3[1],$status);
         eregi("<auth_code>(.*)</auth_code>",        $echotype3[1],$auth_code);
         eregi("<avs_result>(.*)</avs_result",       $echotype3[1],$avs_result);
         eregi("<decline_code>(.*)</decline_code>",  $echotype3[1],$decline_code);
         eregi("<order_number>(.*)</order_number>",  $echotype3[1],$order_number);
         eregi("<merchant_name>(.*)</merchant_name>",$echotype3[1],$merchant_name);
         eregi("<tran_amount>(.*)</tran_amount>",    $echotype3[1],$tran_amount);
         eregi("<tran_date>(.*)</tran_date>",        $echotype3[1],$tran_date);
         eregi("<version>(.*)</version>",            $echotype3[1],$version);

         $this_charge['status']        = $status[1];
         $this_charge['auth_code']     = $auth_code[1];
         $this_charge['avs_result']    = $avs_result[1];
         $this_charge['decline_code']  = $decline_code[1];
         $this_charge['order_number']  = $order_number[1];
         $this_charge['merchant_name'] = $merchant_name[1];
         $this_charge['tran_amount']   = $tran_amount[1];
         $this_charge['tran_date']     = $tran_date[1];
         $this_charge['version']       = $version[1];
      }
   }
   if($debug)
   {
      echo "<pre>";
      print_r($authorize);
      echo "<hr>";
      print_r($this_charge);
      echo "</pre>";
   }
   return $this_charge;
/*
####### USAGE #######
$string  = "billing_ip_address=".urlencode($ip)."&";
$string .= "cc_number=".         urlencode($ccnum)."&";
$string .= "ccexp_month=".       urlencode($mm)."&";
$string .= "ccexp_year=".        urlencode($yy)."&";
$string .= "grand_total=".       urlencode($amount)."&";
$string .= "billing_name=".      urlencode($fname $lname)."&";
$string .= "billing_address1=".  urlencode($address1)."&";
$string .= "billing_address2=".  urlencode($address2)."&";
$string .= "billing_city=".      urlencode($city)."&";
$string .= "billing_state=".     urlencode($state)."&";
$string .= "billing_zip=".       urlencode($zip)."&";
$string .= "billing_country=".   urlencode($country)."&";
$string .= "billing_phone=".     urlencode($phone)."&";
$string .= "billing_fax=".       urlencode($fax)."&";
$string .= "billing_email=".     urlencode($email)."&";
$this_charge=echo_gateway($string);

###### status ######
G - Approved
D - Declined
C - Cancelled
T - Timeout waiting for host response
R - Received

###### Valid transaction types #######:
CK (System check)
AD (Address Verification)
AS (Authorization)
ES (Authorization and Deposit)
EV (Authorization and Deposit with Address Verification)
AV (Authorization with Address Verification)
DS (Deposit)
CR (Credit)
DV (Electronic Check Verification)
DD (Electronic Check Debit)
DC (Electronic Check Credit) See Notes.
*/
}
?>