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

include("config.php");
GLOBAL $HTTP_POST_VARS;
//$debug = 1;

/*--------------------------------------
** BANNED IP FILTER & CLEAN INPUT
** Last Modified: v3.0.8
**-------------------------------------*/
banned_ip_check($REMOTE_ADDR);
session_register('cart');
include($DIR."include/misc/validate_form_input.inc.php");


/*--------------------------------------
** SET AFFILIATE ID FROM URL
** Syntax: /order/index.php?aid=123456
** Last Modified: v3.0.8
**-------------------------------------*/
if(isset($aid)&&$cart[affiliate]!=$aid)
{
   if ($debug) echo "<br>-->SET AFFILIATE ID<--<br>";
   list($num) = mysql_fetch_row(mysql_query("SELECT count(aff_code) FROM affiliate_config WHERE aff_code = '$aid' ",$dbh));
   if ($num==1) {
       $cart[affiliate] = $aid;
       $result = mysql_query("UPDATE affiliate_config SET aff_hits = aff_hits+1 WHERE aff_code = '$aid' ",$dbh);
   }
}


/*--------------------------------------
** INITIALIZE CART & RESET ORDER SESSION
** Last Modified: v3.0.8
**-------------------------------------*/
if (!isset($cart[num_items]) || isset($submit_clear))
{
   if ($debug) echo "<br>-->INITIALIZE CART & RESET ORDER SESSION<--<br>";

   // Initialize default $cart variables
   $cart = array("num_items"   => 0,
                 "domains"     => array(),
                 "packages"    => array(),
                 "addons"      => array(),
                 "coupons"     => array(),
                 "order_total" => 0,
                 "affiliate"   => $cart[affiliate]); // Don't loose affiliate ID

   // Reset order_completed session
   session_register('order_completed');
   $order_completed = NULL;
}


/*--------------------------------------
** VALIDATE DOMAIN CHECK
** Last Modified: v3.0.8
**-------------------------------------*/
if(isset($submit_register) || isset($submit_transfer) )
{
   if ($debug) echo "<br>-->VALIDATE DOMAIN CHECK<--<br>";
   $domain        = strtolower(trim($domain));
   $tld_extension = strtolower(trim($tld_extension));
   $domain_length = strlen(chop($domain));
   if ( $domain_length < 2 || // No single letter domain searches
        $domain_length >= 63 ||
        !is_valid_email("blah@$domain.$tld_extension") ||
        ereg("\.",$domain) )
   {
      // Start over if error
      $error_msg = DOMAINERROR;
      $step = "step_domain_search";
   }
   else
   {
      // Bypass if transfer with whois disabled
      if (isset($submit_transfer)&&$disable_whois)
      {
          $cart[domains]  = array();
          $cart[domains]["$domain.$tld_extension"] = array("transfer",$domain,$tld_extension,$domain_years,$domain_price);
          $step = "step_package_select";
      }
      else
      {
          // Log Domain & Redirect to Whois lookups
          $query     = "$domain.$tld_extension";
          $db_table  = "whois_stats";
          $ws_domain = $query;
          $ws_qty    = 1;
          $ws_from   = $HTTP_SERVER_VARS[REMOTE_ADDR];
          include($DIR."include/db_attributes.inc.php");
          if(!$dbh)dbconnect();
          @mysql_query($insert_sql,$dbh);
          $step = "step_whois_lookup";
      }
   }
}


/*--------------------------------------
** ADD PACKAGE FROM URL
** Last Modified: v3.0.8
**-------------------------------------*/
if(isset($pack_id)) // FIX ME -- What is we do not offer PackPlan?
{
   if ($debug) echo "<br>-->ADD PACKAGE FROM URL<--<br>";
   // Reset All Packages
   $cart[packages] = array();

   // Clean Package ID
   $pack_id = ($_GET_VARS[pack_id]) ? $_GET_VARS[pack_id] : $HTTP_GET_VARS[pack_id] ;

   // Clean Pacakge Plan (Billing Cycle)
   $pack_plan = ($HTTP_GET_VARS[pack_plan]||$_GET_VARS[pack_plan]) ? $pack_plan : "1" ;

   // Grab the price for the billing cycle
   $this_get_price = get_price($pack_id,$pack_plan);

   // Validate the billing cycle
   switch ($pack_plan) {
      case 1:  $valid_plan = ($allow_monthly&&$this_get_price[price]>0)   ? TRUE : FALSE ; break;
      case 3:  $valid_plan = ($allow_quarterly&&$this_get_price[price]>0) ? TRUE : FALSE ; break;
      case 6:  $valid_plan = ($allow_semiannual&&$this_get_price[price]>0)? TRUE : FALSE ; break;
      case 12: $valid_plan = ($allow_annual&&$this_get_price[price]>0)    ? TRUE : FALSE ; break;
      case 24: $valid_plan = ($allow_xyear&&$this_get_price[price]>0)     ? TRUE : FALSE ; break;
      default: $valid_plan = FALSE; break;
   }

   // Validate the Package
   if (is_valid_package($pack_id)&&$valid_plan) {
       if (is_parent($pack_id)) {
           $cart[packages][$pack_id] = array($pack_id,$pack_plan,"URL");
           $step = ($allow_domain_register||$allow_domain_transfer) ? "step_domain_search" : "step_addon_select" ;
       } else {
           $cart[packages][$pack_id] = array($pack_id,$pack_plan,"URL");
           if (($allow_domain_register||$allow_domain_transfer)&&(!count($cart[domains])>0)) {
                $step = "step_domain_search";
           } else {
                $skip_show_total = "skip_show_total";
           }
       }
   } else {
       $step = "step_domain_search";
   }
   if ($debug) {
       echo "<pre>STEP = $step<br>";
       print_r($cart[packages]);
       echo "</pre>";
   }
}


/*--------------------------------------
** MAIN PACKAGE PLANS & DOMAIN LOGIC
** Last Modified: v3.0.8
**-------------------------------------*/
if( isset($submit_search_again)    ||
    isset($submit_search_continue) ||
    isset($submit_package_select)  ||
    isset($submit_addon_select) )
{
   if ($debug) echo "<br>-->MAIN PACKAGE PLANS & DOMAIN LOGIC<--<br>";
   // Reset All Packages & Domains
   $cart[domains]  = array();

   // Only reset the cart if the pack is NOT from the URL
   foreach ($cart[packages] as $key => $value)  //v3.1.1
   {
       if (ereg("URL",$value[2])) {
           $added_from_url = TRUE;
       }
   }
   if (!$added_from_url) {
        $cart[packages] = array();
   }

   // ADD DOMAINS TO CART
   $count = count($domains);
   for($i=0;$i<=$count-1;$i++)
   {
       // $domain_type|$domain_name|$domain_ext|1|$tld_1y
       list($register,$domain,$tld_extension,$domain_years,$domain_price) = explode("|",$domains[$i]);
       if ($domain)
       {
          // Add Domains
          $cart[domains]["$domain.$tld_extension"] = array($register,$domain,$tld_extension,$domain_years,$domain_price);
          $is_domain_added = TRUE;
       }
   }

   // Go back to start if no domains selected
   if (isset($submit_package_select) && count($domains) == 0) {
       $submit_search_again   = TRUE;
       $submit_package_select = NULL;
       $step = "step_domain_search";
   }

   // Test for type3_package && type3_plan - v3.1.0
   if ($type3_package && $type3_plan && $type3_package!="DOMAIN") {
      // Add Packages - v3.1.0
      // Replace the current pack_id if there is one in there already
      $cart[packages][$type3_package] = array($type3_package,$type3_plan);
      if (!is_parent($type3_package)) {
           if (isset($submit_search_again)) {
               $step = "step_domain_search";
           } else {
               $skip_show_total = "skip_show_total";
           }
      }
   } else {
      // ADD PACKAGES TO CART
      $count = count($packages);
      for($i=0;$i<=$count-1;$i++)
      {
          // $pack_id,$pack_plan,$this_price,$this_setup
          list($pack_id,$pack_plan) = explode("|",$packages[$i]);
          if ($pack_id >0 && $pack_id == $this_package_menu_2)
          {
             // Add Packages
             $cart[packages][$pack_id] = array($pack_id,$pack_plan);
             if (!is_parent($pack_id)) {
                  if  (isset($submit_search_again)) {
                       $step = "step_domain_search";
                  } else {
                       $skip_show_total= "skip_show_total";
                  }
             }
          }
      }
   }

   if ($debug) {
       echo "<pre>";
       echo "PACAKGES: "; print_r($domains);
       echo "DOMAINS: ";  print_r($packages);
       echo "</pre>";
   }
}


/*--------------------------------------
** PACKAGE ADD-ONS
** Last Modified: v3.0.8
**-------------------------------------*/
if( isset($submit_show_total) )
{
   if ($debug) echo "<br>-->PACKAGE ADD-ONS<--<br>";
   // Reset All Packages Add-Ons
   $cart[addons]  = array();

   $count = count($packages);
   for($i=0;$i<=$count-1;$i++)
   {
       // $pack_id,$pack_plan,$this_price,$this_setup
       list($pack_id,$pack_plan) = explode("|",$packages[$i]);
       if ($pack_id)
       {
          // Add Packages
          $cart[addons][$pack_id] = array($pack_id,$pack_plan);
       }
   }
   if ($debug) { echo "<pre>COUPON:"; print_r($packages); echo "</pre>"; }
}


/*--------------------------------------
** VALIDATE COUPON
** Last Modified: v3.0.8
**-------------------------------------*/
if( isset($submit_coupon) && isset($coupon_code) )
{
   if ($debug) echo "<br>-->VALIDATE COUPON<--<br>";
   // Reset All Packages Add-Ons
   $cart[coupons]  = array();

   list($coupon_id,
        $coupon_code,
        $coupon_percent_discount,
        $coupon_dollar_discount,
        $coupon_comments,
        $coupon_status,
        $coupon_start_stamp,
        $coupon_end_stamp,
        $coupon_expire_string,
        $coupon_count,
        $coupon_max_count,
        $coupon_new_only,
        $coupon_misc1,
        $coupon_misc2) = validate_coupon($coupon_code);

   if ($coupon_id)
   {
      if ( ( $coupon_start_stamp <= mktime(0,0,0,date("m"),date("d"),date("Y")) ) &&
           ( $coupon_end_stamp >= mktime(0,0,0,date("m"),date("d"),date("Y")) ) &&
           ( ( $coupon_max_count == 0 ) ||
             ( $coupon_count < $coupon_max_count ) ) )
      {
           $coupon_is_valid = TRUE;
           $cart[coupons][] = array($coupon_id,
                                    $coupon_code,
                                    $coupon_percent_discount,
                                    $coupon_dollar_discount,
                                    $coupon_comments,
                                    $coupon_status,
                                    $coupon_start_stamp,
                                    $coupon_end_stamp,
                                    $coupon_expire_string,
                                    $coupon_count,
                                    $coupon_max_count,
                                    $coupon_new_only,
                                    $coupon_misc1,
                                    $coupon_misc2);
      }
      else
      {
           $error_msg       = $coupon_expire_string;
           $coupon_is_valid = FALSE;
      }
   }
   if ($debug) { echo "<pre>COUPON: $coupon_id</pre>"; }
}


/*--------------------------------------
** SUBMIT ORDER, EXISTING CLIENT LOGIN
** Last Modified: v3.0.8
**-------------------------------------*/
if (isset($submit_add))
{
    if ($debug) echo "<br>-->SUBMIT ORDER, EXISTING CLIENT LOGIN<--<br>";
    GLOBAL $this_user;
    if (vortech_login($username,$password))
    {
        $client_id     = $this_user[client_id];
        $x_First_Name  = $this_user[client_fname];
        $x_Last_Name   = $this_user[client_lname];
        $x_Email       = $this_user[client_email];
        $x_Company     = $this_user[client_company];
        $x_Address     = $this_user[client_address];
        $x_City        = $this_user[client_city];
        $x_State       = $this_user[client_state];
        $x_Zip         = $this_user[client_zip];
        $x_Country     = $this_user[client_country];
        $x_Phone       = $this_user[client_phone1];
        $x_Fax         = $this_user[client_phone2];
        $pay_method    = "account_addon";
        $account_addon = TRUE;
        $step          = "step_submit_process";
    }
    else
    {
        $error_msg = BADLOGIN;
        $step      = "step_show_total";
    }
}


/*--------------------------------------
** SUBMIT ORDER, NEW CLIENT
** Last Modified: v3.0.8
**-------------------------------------*/
if (isset($submit_new))
{
   if ($debug) echo "<br>-->SUBMIT ORDER, NEW CLIENT<--<br>";
   $client_id = 0;
   $error_msg = ($enable_banned_email_checks) ? banned_email_check($email) : NULL ;

   // Validate Email
   if ($email == $validate_email && is_valid_email(strtolower(trim($email))) && $error_msg=="" )
   {
      $x_Email = strtolower(trim($email));
      $num = mysql_one_data("SELECT count(client_id) FROM client_info WHERE client_email = '$x_Email'");
      // Check for duplicate client
      if ($num > 0) {
            $step                = "step_show_total";
            $error_msg           = DUPLICATEUSER;
      } else {
            $step                = "step_show_form";
            $variable_type       = "text";
            $pass_variable_type  = "password";
            $radio_variable_type = "radio";
            $stop                = TRUE;
      }
   }
   else
   {
      // send to order form
      $error_msg = ($error_msg) ? nl2br($error_msg) : EMAILERROR ;
      $step      = "step_show_total";
   }
}


/*--------------------------------------
** SUBMIT ORDER, VALIDATE INPUT
** Last Modified: v3.0.8
**-------------------------------------*/
if (isset($submit_verify))
{
    if ($debug) echo "<br>-->SUBMIT ORDER, VALIDATE INPUT<--<br>";
    include("include/signup_error_checking.inc.php");
    if ($z) // $z = # errors
    {
        $variable_type       = "text";
        $pass_variable_type  = "password";
        $radio_variable_type = "radio";
        $stop                = TRUE;
    }
    else
    {
        $pass_variable_type=$variable_type=$radio_variable_type="hidden";
        $stop = FALSE;
    }
    $step = "step_show_form";
}


/*--------------------------------------
** SUBMIT ORDER, NEEDS CORRECTION
** Last Modified: v3.0.8
**-------------------------------------*/
if (isset($submit_correct))
{
    if ($debug) echo "<br>-->SUBMIT ORDER, NEEDS CORRECTION<--<br>";
    $variable_type       = "text";
    $pass_variable_type  = "password";
    $radio_variable_type = "radio";
    $stop                = TRUE;
    $step                = "step_show_form";
}


/*--------------------------------------
** MAIN NAVIGATION LOGIC
** Last Modified: v3.0.8
**-------------------------------------*/
if(isset($submit_search_again))    $step = "step_domain_search";
if(isset($submit_search_continue)) $step = "step_domain_continue";
if(isset($submit_package_select))  $step = "step_package_select";
if(isset($submit_skip))            $step = "step_package_select";
if(isset($submit_go_back))         $step = "step_package_select";
if(isset($submit_addon_select))    $step = "step_addon_select";
if(isset($submit_show_total))      $step = "step_show_total";
if(isset($submit_coupon))          $step = "step_show_total";
if(isset($skip_show_total))        $step = "step_show_total";
if(isset($submit_process))         $step = "step_submit_process";
$step = ($step) ? $step : "step_domain_search" ;



/*--------------------------------------
** MAIN DISPLAY LOGIC
** Last Modified: v3.0.8
**-------------------------------------*/
switch ($step) {
   /*----------------------------
   -------- DOMAIN SEARCH -------
   *---------------------------*/
   case step_domain_search:  include("include/cases/$step.case.inc.php"); break;
   /*----------------------------
   ---- DOMAIN SEARCH RESULTS ---
   *---------------------------*/
   case step_whois_lookup:   include("include/cases/$step.case.inc.php"); break;
   /*----------------------------
   ------- SELECT PACKAGES ------
   *---------------------------*/
   case step_package_select: include("include/cases/$step.case.inc.php"); break;
   /*----------------------------
   --- SELECT ADD-ON PACKAGES ---
   *---------------------------*/
   case step_addon_select:   include("include/cases/$step.case.inc.php"); break;
   /*----------------------------
   ------ CALCULATE TOTALS ------
   *---------------------------*/
   case step_show_total;     include("include/cases/$step.case.inc.php"); break;
   /*----------------------------
   ---------- SHOW FORM ---------
   *---------------------------*/
   case step_show_form:      include("include/cases/$step.case.inc.php"); break;
   /*----------------------------
   -------- PROCESS ORDER -------
   *---------------------------*/
   case step_submit_process: include("include/cases/$step.case.inc.php"); break;
   /*----------------------------
   --- DEFAULT = DOMAIN SEARCH --
   *---------------------------*/
   default: include("include/cases/step_domain_search.case.inc.php"); break;
}
?>