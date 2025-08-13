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

if(!$dbh)dbconnect();

// Find Email Template for Parent Package
// --------------------------------------
foreach($cart[packages] as $key => $value)
{
    list($pack_id,$pack_plan,$this_price,$this_setup) = $value;
    $this_package_array = mysql_fetch_array(mysql_query("SELECT * FROM package_type WHERE pack_id=$pack_id",$dbh));
}

// Find Email Template for Domain Only Orders
// ------------------------------------------
foreach($cart[domains] as $key => $value)
{
    list($register,$domain,$tld_extension) = $value;
    list($pack_id) = @mysql_fetch_array(mysql_query("SELECT pack_id FROM tld_config WHERE tld_extension='$tld_extension'",$dbh));
    $this_domain_package_array = @mysql_fetch_array(mysql_query("SELECT * FROM package_type WHERE pack_id=$pack_id",$dbh));
}

// Which template should we use?
// ------------------------------
if ( count($this_package_array)>0 && $this_package_array["email_override"]&&$this_package_array["email_id"])
{
   $this_email_id   = $this_package_array["email_id"];
   $this_email_text = mysql_fetch_array(mysql_query("SELECT * FROM email_config WHERE email_id=$this_email_id",$dbh));
}
elseif ( count($this_domain_package_array)>0 && $this_domain_package_array["email_override"]&&$this_domain_package_array["email_id"])
{
   $this_email_id   = $this_domain_package_array["email_id"];
   $this_email_text = mysql_fetch_array(mysql_query("SELECT * FROM email_config WHERE email_id=$this_email_id",$dbh));
}

$credit_secure = "xxxx-xxxx-xxxx-".substr($x_Card_Num,-4);

for ($i = 2; $i <= 5; $i++)
{
     /*-- Client Information --*/
     $this_email_text[$i] = str_replace("[[FIRSTNAME]]"    ,$x_First_Name,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[LASTNAME]]"     ,$x_Last_Name,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[EMAIL]]"        ,$x_Email,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[COMPANYNAME]]"  ,$x_Company,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[ADDRESS]]"      ,$x_Address."\n".$x_Address_2,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[CITY]]"         ,$x_City,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[STATE]]"        ,$x_State,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[ZIPCODE]]"      ,$x_Zip,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[COUNTRY]]"      ,$x_Country,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[PHONE]]"        ,$x_Phone,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[FAX]]"          ,$x_Fax,$this_email_text[$i]);

     /*-- Additional Information --*/
     $this_email_text[$i] = str_replace("[[REFERRER]]"     ,$referrer,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[DOMAIN]]"       ,$domain_name,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[USERNAME]]"     ,$username,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[PASSWORD]]"     ,$password,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[COMMENTS]]"     ,$comments,$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[IP]]"           ,getenv("REMOTE_ADDR"),$this_email_text[$i]);

     $this_email_text[$i] = str_replace("[[DISPLAYCART]]"  ,"<table width=450><tr><td>".display_cart_no_output()."</td></tr></table>",$this_email_text[$i]);

     /*-- Billing Information --*/
     $this_email_text[$i] = str_replace("[[PAYMENTMETHOD]]",$pay_method,$this_email_text[$i]);

     if ($pay_method == "creditcard")
     {
          ## Credi Card
          $this_email_text[$i] = str_replace("[[CCHOLDERNAME]]",$x_Card_Name,$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[CCBANK]]"     ,$x_Card_Bank,$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[CCNUMBER]]"   ,$credit_secure,$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[CCRAW]]"      ,$x_Card_Num,$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[CCEXP]]"      ,$x_Exp_Date,$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[CCCODE]]"     ,$x_Card_Code,$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_PAYPALTEXT_(.*)_STOP_PAYPALTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_INVOICETEXT_(.*)_STOP_INVOICETEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_ECTEXT_(.*)_STOP_ECTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = str_replace("_START_CCTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = str_replace("_STOP_CCTEXT_","",$this_email_text[$i]);
     }
     elseif ($pay_method == "echeck"&&$allow_echeck&&$echeck_enabled&&$tier2)
     {
          ## eCheck
          $this_email_text[$i] = str_replace("[[ECBANKNAME]]"    ,$x_Bank_Name,$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[ECABACODE]]"     ,$x_Bank_ABA_Code,$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[ECACCOUNTNUM]]"  ,$x_Bank_Acct_Num,$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[ECLICENCENUM]]"  ,$x_Drivers_License_Num,$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[ECLICENSESTATE]]",$x_Drivers_License_State,$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[ECLICENSEDOB]]"  ,$x_Drivers_License_DOB,$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_CCTEXT_(.*)_STOP_CCTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_INVOICETEXT_(.*)_STOP_INVOICETEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_PAYPALTEXT_(.*)_STOP_PAYPALTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = str_replace("_START_ECTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = str_replace("_STOP_ECTEXT_","",$this_email_text[$i]);
     }
     elseif ($pay_method == "worldpay"&&$worldpay_enabled&&$tier2)
     {
          ## WorldPay
          $this_email_text[$i] = str_replace("[[PAYPALLINK]]",generate_worldpay_link($pp_item_name,$pp_item_number,$pp_amount,"link"),$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_CCTEXT_(.*)_STOP_CCTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_ECTEXT_(.*)_STOP_ECTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_INVOICETEXT_(.*)_STOP_INVOICETEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = str_replace("_START_PAYPALTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = str_replace("_STOP_PAYPALTEXT_","",$this_email_text[$i]);
     }
     elseif ($pay_method == "paypal"&&$paypal_enabled&&$tier2)
     {
          ## PayPal
          $this_email_text[$i] = str_replace("[[PAYPALLINK]]",generate_paypal_link($pp_item_name,$pp_item_number,$pp_amount,"link"),$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_CCTEXT_(.*)_STOP_CCTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_ECTEXT_(.*)_STOP_ECTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_INVOICETEXT_(.*)_STOP_INVOICETEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = str_replace("_START_PAYPALTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = str_replace("_STOP_PAYPALTEXT_","",$this_email_text[$i]);
     }
     else
     {
          ## Invoice/Check
          $this_email_text[$i] = str_replace("[[PAYADDRESS]]" ,$company_address,$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_PAYPALTEXT_(.*)_STOP_PAYPALTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_CCTEXT_(.*)_STOP_CCTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = ereg_replace("_START_ECTEXT_(.*)_STOP_ECTEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = str_replace("_START_INVOICETEXT_","",$this_email_text[$i]);
          $this_email_text[$i] = str_replace("_STOP_INVOICETEXT_","",$this_email_text[$i]);
     }

     /*
     $this_email_text[$i] = str_replace("[[SETUPPRICE]]"   ,display_currency($setup),$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[PRORATEINFO]]"  ,PRA." $prorated_days Day(s): ".display_currency($pro_pay),$this_email_text[$i]);
     if ($tax_enabled)
     {
          $this_email_text[$i] = str_replace("[[SUBTOTAL]]" ,display_currency($initial_charge),$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[TAXDUE]]"   ,display_currency($tax_due),$this_email_text[$i]);
          $this_email_text[$i] = str_replace("[[TOTALDUE]]" ,display_currency($this_total),$this_email_text[$i]);
     }
     else
     {
          $this_email_text[$i] = str_replace("[[TOTALDUE]]" ,display_currency($initial_charge),$this_email_text[$i]);
     }
     */

     $this_email_text[$i] = str_replace("[[PAYADDRESS]]" ,$company_address,$this_email_text[$i]);

     $this_email_text[$i] = str_replace("[[PACKAGE]]" ,"PACKAGE IS DEPRACATED, PLEASE USE DISPLAYCART",$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[CONTRACTTERM]]" ,"CONTRACTTERM IS DEPRACATED, PLEASE USE DISPLAYCART",$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[FRONTPAGE]]" ,"FRONTPAGE IS DEPRACATED, PLEASE USE DISPLAYCART",$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[DOMAININFO]]" ,"DOMAININFO IS DEPRACATED, PLEASE USE DISPLAYCART",$this_email_text[$i]);
     $this_email_text[$i] = str_replace("[[CCTYPE]]" ,"CCTYPE IS DEPRACATED, PLEASE USE DISPLAYCART",$this_email_text[$i]);

} # <-- END FOR LOOP


$this_email_heading   = $this_email_text[2];
$this_email_body      = $this_email_text[3];
$this_email_footer    = $this_email_text[4];
$this_email_signature = $this_email_text[5];

$recipient = "$x_Last_Name, $x_First_Name <$x_Email>";
$subject   = "$company_name: ".SIGNUPEMAILSUBJECT." $x_First_Name $x_Last_Name";

$message .= "<b>".CONTACTINFO."</b>\n";
$message .= "-------------------\n";
$message .= FIRSTNAME.": $x_First_Name\n";
$message .= LASTNAME.":  $x_Last_Name\n";
$message .= COMPORDOM.": $x_Company\n";
$message .= ADDRESS.":\n$x_Address";
$message .= ($x_Address_2!="") ? "$x_Address_2\n" : NULL ;
$message .= "$x_City, $x_State $x_Zip $x_Country\n\n";
$message .= PHONE.": $x_Phone\n";
$message .= ($x_Fax) ? FAX.": $x_Fax\n" : NULL ;
$message .= EMAIL.": <a href=mailto:$x_Email>$x_Email</a>\n";
$message .= "\n\n";

$message .= "<b>".PURCHASEINFO."</b>\n";
$message .= "--------------------\n\n";
$message .= "<table width=450><tr><td>{DISPLAYCARTHERE}</td></tr></table>";
$message .= "<br clear=left>";

$message .= "\n<b>".ACCOUNTINFO."</b>\n";
$message .= "-------------------\n";
if ($referrer)              $message .= REFERREDBY.": $referrer\n";
if ($allow_domain_username) $message .= USERNAME.": $username\n";
if ($allow_domain_password) $message .= PASSWORD_t.": $password\n";
$message .= "-------------------\n";
$message .= URL.": <A href=$user_login_url>$user_login_url</a>\n";
$message .= LOGIN."/".EMAIL.": $x_Email\n";
$message .= PASSWORD_t.": $password\n";
$message .= "\n\n";


if ($comments!="") {
    $message .= "<b>".COMMENTS."</b>\n";
    $message .= "-------------------\n";
    $message .= "$comments\n";
    $message .= "\n\n";
}

$message .= "<b>".BILLINGINFO."</b>\n";
$message .= "-------------------\n";
$message .= PAYMENTMETHOD.": ".strtoupper($pay_method)."\n";

if ($pay_method == "creditcard")
{
    $message_secure .= CARDHOLDER.": $x_Card_Name\n";
    $message_secure .= CCNUMBER.": $credit_secure\n";
    $message_secure .= EXPIRATIONDATE2.": $x_Exp_Date\n";
    if ($require_cvvc_code) $message_secure .= CCCODE.": $x_Card_Code\n";

    $message_admin  .= CARDHOLDER.": $x_Card_Name\n";
    $message_admin  .= ($send_secure_cc) ? CCNUMBER.": $credit_secure\n" : CCNUMBER.": $x_Card_Num\n" ;
    $message_admin  .= EXPIRATIONDATE2.": $x_Exp_Date\n";
    if ($require_cvvc_code) $message_admin  .= CCCODE.": $x_Card_Code\n";
}
elseif ($pay_method == "echeck")
{
    $message_secure .= BANKNAME.": $x_Bank_Name\n";
    $message_secure .= BANKABACODE.": $x_Bank_ABA_Code\n";
    $message_secure .= BANKACCOUNTNUM.": $x_Bank_Acct_Num\n";
    $message_secure .= DRIVERSLICENSE.": $x_Drivers_License_Num\n";
    $message_admin  .= DRIVERSESTATE.": $x_Drivers_License_State\n";
    $message_admin  .= DRIVERSDOB.": $x_Drivers_License_DOB\n";
}
elseif (($pay_method == "paypal" || $account_addon) && $paypal_enabled&&$tier2)
{
    $message_secure .= PAYWITHPAYPAL."\n";
    $message_secure .= generate_paypal_link($pp_item_name,$pp_item_number,$pp_amount,"link")."\n";
}
elseif (($pay_method == "worldpay" || $account_addon) && $worldpay_enabled&&$tier2)
{
    $message_secure .= PAYWITHWORLDPAY."\n";
    $message_secure .= generate_worldpay_link($pp_item_name,$pp_item_number,$pp_amount,"link")."\n";
}
else
{
    $message_secure .= SENDPAYMENTTO.":\n";
    $message_secure .= nl2br($company_address)."\n";
}

$message .= "\n\n";

$message_admin .= "\n".IP.": ".getenv("REMOTE_ADDR")."\n";
$message_admin .= "\n".date("Y-M-d: h:i:s")."\n";

$headers .= ($default_from) ? "From: $default_from".CTRL : "From: $order_email".CTRL;
$headers .= "Return-Path: $order_email".CTRL;
if ($use_html_signup_email) $headers .= "Content-Type: text/html; charset=\"".CHARSET."\"".CTRL;
?>