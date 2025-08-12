<?php
/*
 Basic script for online credit card processing through Cambist.Com
 Copyright (c) 2002 CDS-Computers by Dan Smith
 (dan.smith@rvgnet.net) and released under the GPL version 2
 or later.   Use at your own risk. It is an adaptation of the
 script written by Glenda R. Snodgrass (grs@theneteffect.com)
 for processing cards through Authorize.Net

 This file is designed to be included in your normal shopping
 cart processing script. In Fishcart, it is included in orderproc.php

 This script requires installation of cURL (see
 <A TARGET="_top" HREF="http://curl.haxx.se/">http://curl.haxx.se/</A>)
 but uses cURL with PHP's exec() function, so you don't need PHP
 compiled with cURL support. NOTE: By default, most Linux servers
 have cURL installed.

 In Fishcart, the variables $app_code, $avs_code and $cvv2_code set below
 can be added to the order email sent to vendor for future reference
(capturing
 or refunding, etc.) like so:

  $body .= sprintf("CC Authorization Code:  %s\n",$app_code);
  $body .= sprintf("AVS Code:               %s\n",$avs_code);
  $body .= sprintf("CVV2 Code:              %s\n",$cvv2_code);

Most options are already defined below and just requires setting
them to match what you would like to receive back.
http://cambist.com/ids.html

CAMBIST TEST URL
 https://cambist.com/cgi-bin/authorize.pl
CAMBIST TEST CREDIT CARD NUMBER
 5419840000000003
CAMBIST TEST MERCHANT NAME
 Gizmo Company
CAMBIST TEST MERCHANT ID
 DEMO
In test mode, changing the last number of the price
to these below will result in the following response
1 - Approved
2 - Declined
3 - call
4 - Error
5 - Hold/Call
6 - Approved
7 - Approved
8 - Approved
9 - Approved
0 - Approved

****************************************
UnComment the following variables and
you can test the response without using
your shopping cart to feed the variables
***************************************** */
/*
$ttotal    =  '12.99';
$cc_number   =  '5419840000000003';
$ccexp_month  =  '07';
$ccexp_year   =  '2007';
$cc_name    =  'Some Name';
$billing_address1 =  '123 Some St.';
$billing_city  =  'Somewhere';
$billing_state  =  'OR';
$billing_zip  =  '44566 3036';
$cartid    =  '2003';
$cc_cvv     =   '123';
*/

/* **************************************
Your name as Cambist knows you and the
Merchant ID assigned by Cambist
***************************************** */
$MerchantName  =  'Cambist_Defined_Merchant_Name';
$MerchantID   =   'Cambist_ID_Here';

/* **************************************
These are the default and to change,
simply edit the Y/N or for capture,
change TransactionType to 'Book'.
***************************************** */
$UseCVV2   =  'N';
$AVSVerify   =  'N';
$TransactionType =  'Sale';

/* **************************************
Cambist only allows 8 alpha-num for customerid
Really doesn't matter what you send, so get last 3-digits of
 $cartid
***************************************** */
$customerid=substr($cartid,3);

/* **************************************
Change the variables in right column to
match shopping cart variables. Current
configuration is for use with FishCart
***************************************** */
$fulltotal    =   $ttotal;
$BillCreditCard     =  $cc_number;
$CVV2    =  $cc_cvv;
$ExpirationMonth =  $ccexp_month;
$ExpirationYear     =  $ccexp_year;
$customerid   =  $customerid;
$BillName   =  $cc_name;
$BillStreet   =  $billing_address1;
$BillCity   =  $billing_city;
$BillState   =  $billing_state;
$BillZip   =  $billing_zip;

/* **************************************
If empty cvv2 value, even if useCVV2 is set to N,
it can cause a Missing Data error,
so this forces at least sending a 0
***************************************** */
if(!$CVV2)
{
 $CVV2=0;
}else{
 $CVV2=$CVV2;
}
/* ******************************************
Cambist requires a valid HTTP_REFERER so adding
the -e option and including the URL of your secure
server sends this info to Cambist
********************************************* */

$refer='Add_URL_Here';

/* Shouldn't need to edit anything below this except for possible debugging
*/


$query_string  = '';
$query_string .= "MerchantID=$MerchantID";
$query_string .= "&MerchantName=$MerchantName";
$query_string .= "&TransactionType=$TransactionType";
$query_string .= "&fulltotal=$fulltotal";
$query_string .= "&UseCVV2=$UseCVV2";
$query_string .= "&BillCreditCard=$BillCreditCard";
$query_string .= "&CVV2=$CVV2";
$query_string .= "&ExpirationMonth=$ExpirationMonth";
$query_string .= "&ExpirationYear=$ExpirationYear";
$query_string .= "&customerid=$customerid";
$query_string .= "&BillName=$BillName";
$query_string .= "&BillStreet=$BillStreet";
$query_string .= "&BillCity=$BillCity";
$query_string .= "&BillState=$BillState";
$query_string .= "&BillZip=$BillZip";
$query_string .= "&DirectResponse=Y";
$query_string .= "&form_action=AUTHORIZE PAYMENT";
//Check if cURL is compiled and then choose correct method for connection
if(!$ch = curl_init())
{
    //If cURL is not compiled with PHP, this method will be used
    exec("curl -e $refer -d '$query_string' https://cambist.com/cgi-bin/authorize.pl", $CamRetStr);
        $auth_return =  preg_split("/[\t]+/", $CamRetStr[0]);
}
else
{
    //If cURL is compiled with PHP, this method will be used
    curl_setopt($ch, CURLOPT_URL,"https://cambist.com/cgi-bin/authorize.pl");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_REFERER, "$refer");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$query_string");
    $CamRetStr=curl_exec($ch);
    curl_close($ch);
        $auth_return =  preg_split("/[\t]+/", $CamRetStr);
} // end if(!$ch = curl_init()

        $approved = substr($auth_return[0],-1);

/* ***for debugging you can print the variables returned:*** */
/*
 $cnt = count($auth_return);
 for ($i = 0; $i < $cnt; $i++)
 {
   echo $auth_return[$i]."<BR>";
 }

*/

 /* *********************************************
 If customer credit card is approved, three variables below are set
 ($app_code, $avs_code & $cvv2_code) and this script exits and returns to
finish
 processing the order, email vendor, email customer, write info to
 database if used, etc. $app_code is the 6 digit code returned from
    credit card processor
 ************************************************ */
 if($approved=="Y")
 {
  $app_code = substr($auth_return[3],8,6);
        $avs_code = substr($auth_return[4],-1);
  $cvv2_code = substr($auth_return[6],9);
 }elseif($approved=="N")
 {
  /* *********************************************
  Edit this message to whatever you want. This is the response
  to the customer if their credit card is declined.
  ************************************************ */
  $err_code = substr($auth_return[1],14);
        echo "<b>Your order cannot be processed at this time, <br>as your
credit card was not accepted for the following reason:</b> <br>$err_code";
  print "<form>";
   print "<input type=\"button\" value=\"Try Again or Use A Different Credit
Card\" name=\"Back\" onClick=\"history.back()\">";
  print "</form>";
  exit;
 }elseif($approved=="")
 {
  /* *********************************************
  Edit this message to whatever you want. This would only happen if
  no response was returned back from the Cambist server.
  ************************************************ */
  echo "<b>The order cannot be processed at this time.";
        print "<form>";
   print "<input type=\"button\" value=\"Try Again?\" name=\"Back\"
onClick=\"history.back()\">";
  print "</form>";
  exit;
 }
?>
