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

if(!$dbh)dbconnect();
$submit=TRUE;
$this_total = $cart[order_total];

if (!$account_addon)
{
    ## INSERT CLIENT
    $db_table         = "client_info";
    $do               = "add";
    $client_fname     = addslashes($x_First_Name);
    $client_lname     = addslashes($x_Last_Name);
    $client_email     = $x_Email;
    $client_company   = addslashes($x_Company);
    $client_address   = addslashes($x_Address."\n".$x_Address_2);
    $client_city      = addslashes($x_City);
    $client_state     = $x_State;
    $client_zip       = $x_Zip;
    $client_country   = $x_Country;
    $client_phone1    = $x_Phone;
    $client_phone2    = $x_Fax;
    switch ($pay_method) {
       case creditcard: $billing_method = 1;            break;
       case echeck:     $billing_method = $echeck_id;   break;
       case worldpay:   $billing_method = $worldpay_id; break;
       case paypal:     $billing_method = $paypal_id;   break;
       default:         $billing_method = 2;            break;
    }
    $billing_cc_type  = ""; ## LEAVE BLANK
    $billing_cc_num   = $x_Card_Num;
    $billing_cc_exp   = $x_Exp_Date;
    $billing_cc_code  = $x_Card_Code;
    $client_password  = $password;
    $client_comments  = addslashes($comments);
    $client_status    = 3;  ## PENDING
    $client_stamp     = mktime();
    $client_secondary_email = $client_secondary_email;
    $client_username        = $username;
    $client_real_pass       = $password;
    $x_Bank_Name            = $x_Bank_Name;
    $x_Bank_ABA_Code        = $x_Bank_ABA_Code;
    $x_Bank_Acct_Num        = $x_Bank_Acct_Num;
    $x_Drivers_License_Num  = $x_Drivers_License_Num;
    $x_Drivers_License_State= $x_Drivers_License_State;
    $x_Drivers_License_DOB  = $x_Drivers_License_DOB;
    $apply_tax              = $tax_enabled;
    $default_translation    = $default_language;
    $default_currency       = $currency;
    $send_email_type        = 1;
    $secondary_contact      = $secondary_contact;
    include($DIR."include/config/config.client_extras.php");
    for($ic=1;$ic<=10;$ic++)
    {
       if (${"client_field_active_$ic"} && ${"client_field_vortech_$ic"})
       {
           $this_input_value = array("column"  => "client_field_$ic",
                               "required"      => ${"client_field_required_$ic"},
                               "title"         => ${"client_field_title_$ic"},
                               "type"          => ${"client_field_type_$ic"},
                               "size"          => ${"client_field_size_$ic"},
                               "maxlength"     => ${"client_field_maxlength_$ic"},
                               "admin_only"    => ${"client_field_admin_only_$ic"},
                               "append"        => ${"client_field_append_$ic"},
                               "default_value" => ${"client_field_append_$ic"});

            $this_input_value[column] = ${$this_input_value[column]};
       }
    }

    include($DIR."include/db_attributes.inc.php");
    $result = mysql_query($insert_sql,$dbh);
    if ($debug || !$result) {
        mail($order_email,"ORDER ERROR [client_info]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
    }
    $master_client_id = $client_id = mysql_insert_id();
    if($debug)echo "added client: $insert_sql<br>";
}
else
{
    $master_client_id = $client_id = $this_user[client_id];
}

    if($cart[affiliate])
    {
       list($num) = mysql_fetch_row(mysql_query("SELECT count(aff_code) FROM affiliate_config WHERE aff_code = '$cart[affiliate]' ",$dbh));
       if ($num==1) {
           $result = mysql_query("UPDATE affiliate_config SET aff_count = aff_count+1 WHERE aff_code = '$cart[affiliate]' ",$dbh);
       }
    }

    ## ADD PACKAGE(S)
    foreach($cart[packages] as $key => $value)
    {
       list($pack_id,$pack_plan,$this_price,$this_setup) = $value;
       $db_table         = "client_package";
       $do               = "add";
       $parent_cp_id     = NULL;
       $cp_qty           = 1;
       $aff_code         = $cart[affiliate];

        foreach($cart[coupons] as $key => $value)
        {
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
                 $coupon_renews,
                 $coupon_misc2) = $value;
       }
       if ($coupon_percent_discount>0 && $coupon_new_only && !$account_addon && $coupon_renews) {
           $cp_discount  = $coupon_percent_discount;
           $pack_price   = 0;
       } elseif ($coupon_percent_discount>0 && !$coupon_new_only && $coupon_renews) {
           $cp_discount  = $coupon_percent_discount;
           $pack_price   = 0;
       } elseif ($coupon_dollar_discount>0 && $coupon_new_only && !$account_addon && $coupon_renews) {
           $cp_discount  = 0;
           $this_price   = get_price($pack_id,1);
           $pack_price   = $this_price[price] - $coupon_dollar_discount;
       } elseif ($coupon_dollar_discount>0 && !$coupon_new_only && $coupon_renews) {
           $cp_discount  = 0;
           $this_price   = get_price($pack_id,1);
           $pack_price   = $this_price[price] - $coupon_dollar_discount;
       } else {
           $cp_discount  = 0;
           $pack_price   = 0;
       }

       $cp_start_stamp   = date($date_format);
       if ($debug) { echo "<h1>pack_plan: $pack_plan</h1>"; }
       $cp_billing_cycle = $pack_plan;
       if ($allow_pro_rate_billing && date("j") >= $prorate_threshhold) {
           $this_pack_plan = $pack_plan;
       } elseif ($allow_pro_rate_billing && date("j") < $prorate_threshhold) {
           $this_pack_plan = 0;
       } else {
           $this_pack_plan = $pack_plan;
       }
       //$this_pack_plan   = ($allow_pro_rate_billing && date("j") >= $prorate_threshhold) ? $pack_plan : 0;
       $cp_renew_stamp   = ($allow_pro_rate_billing) ?
                            date($date_format,mktime(0,0,0,date("m")+$this_pack_plan+1,01,date("Y"))) :
                            date($date_format,mktime(0,0,0,date("m")+$this_pack_plan,date("d"),date("Y"))) ;
       $cp_status        = 2; ## ACTIVE
       $cp_comments      = AUTOSIGNUPFORM."\n";
       $cp_renewed_on    = date($date_format) ;
       /*
       $cp_renewed_on    = ($allow_pro_rate_billing) ?
                            date($date_format,mktime(0,0,0,date("m")+1,01,date("Y"))) :
                            date($date_format) ;
       */
       $cp_stamp         = mktime();
       include($DIR."include/db_attributes.inc.php");
       $result = mysql_query($insert_sql,$dbh);
       if ($debug || !$result) {
            mail($order_email,"ORDER ERROR [client_package]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
       }
       $parent_cp_id     = mysql_insert_id();
       if($debug)echo "added package: $insert_sql<br>";
    }

    ## ADD ADDON PACKAGE(S)
    foreach($cart[addons] as $key => $value)
    {
       list($pack_id,$pack_plan,$this_price,$this_setup) = $value;
       $db_table         = "client_package";
       $do               = "add";
       $parent_cp_id     = $parent_cp_id;
       $cp_qty           = 1;
       $aff_code         = $cart[affiliate];

        foreach($cart[coupons] as $key => $value)
        {
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
                 $coupon_renews,
                 $coupon_misc2) = $value;
       }
       if ($coupon_percent_discount>0 && $coupon_new_only && !$account_addon && $coupon_renews) {
           $cp_discount  = $coupon_percent_discount;
           $pack_price   = 0;
       } elseif ($coupon_percent_discount>0 && !$coupon_new_only && $coupon_renews) {
           $cp_discount  = $coupon_percent_discount;
           $pack_price   = 0;
       } elseif ($coupon_dollar_discount>0 && $coupon_new_only && !$account_addon && $coupon_renews) {
           $cp_discount  = 0;
           $this_price   = get_price($pack_id,1);
           $pack_price   = $this_price[price] - $coupon_dollar_discount;
       } elseif ($coupon_dollar_discount>0 && !$coupon_new_only && $coupon_renews) {
           $cp_discount  = 0;
           $this_price   = get_price($pack_id,1);
           $pack_price   = $this_price[price] - $coupon_dollar_discount;
       } else {
           $cp_discount  = 0;
           $pack_price   = 0;
       }

       $cp_start_stamp   = date($date_format);
       $cp_billing_cycle = $pack_plan;
       if ($allow_pro_rate_billing && date("j") >= $prorate_threshhold) {
           $this_pack_plan = $pack_plan;
       } elseif ($allow_pro_rate_billing && date("j") < $prorate_threshhold) {
           $this_pack_plan = 0;
       } else {
           $this_pack_plan = $pack_plan;
       }
       //$this_pack_plan   = ($allow_pro_rate_billing && date("j") >= $prorate_threshhold) ? $pack_plan : 0;
       $cp_renew_stamp   = ($allow_pro_rate_billing) ?
                            date($date_format,mktime(0,0,0,date("m")+$this_pack_plan+1,01,date("Y"))) :
                            date($date_format,mktime(0,0,0,date("m")+$this_pack_plan,date("d"),date("Y"))) ;
       $cp_status        = 2; ## ACTIVE
       $cp_comments      = AUTOSIGNUPFORM."\n";
       $cp_renewed_on    = date($date_format) ;
       $cp_stamp         = mktime();
       include($DIR."include/db_attributes.inc.php");
       $result = mysql_query($insert_sql,$dbh);
       if ($debug || !$result) {
           mail($order_email,"ORDER ERROR [client_package]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
       }
       $cp_id = mysql_insert_id();
       if($debug)echo "added package: $insert_sql<br>";
    }

    ## ADD DOMAIN(S)
    foreach($cart[domains] as $key => $value)
    {
     list($register,$domain,$tld_extension,$domain_years,$domain_price) = $value;
       $db_table         = "domain_names";
       $do               = "add";
       $domain_name      = "$domain.$tld_extension";
       $domain_created   = date($date_format);
       $domain_expires   = date($date_format,mktime(0,0,0,date("m"),date("d"),date("Y")+$domain_years));
       list($registrar_id) = mysql_fetch_row(mysql_query("SELECT registrar_id FROM tld_config WHERE tld_extension = '$tld_extension'",$dbh));
       $registrar_id     = ($registrar_id) ? $registrar_id : 1 ;
       $monitor          = ($register=="register") ? 1 : 2 ;
       include($DIR."include/db_attributes.inc.php");
       $result = mysql_query($insert_sql,$dbh);
       if ($debug || !$result) {
           mail($order_email,"ORDER ERROR [domain_names]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
       }
       $domain_id = mysql_insert_id();
       if($debug)echo "added domain: $insert_sql<br>";

       ## ADD DOMAIN PACKAGE(S)
       $db_table         = "client_package";
       $do               = "add";
       list($pack_id) = mysql_fetch_row(mysql_query("SELECT pack_id FROM tld_config WHERE tld_extension = '$tld_extension'"));
       $parent_cp_id     = $parent_cp_id;
       $pack_price       = $domain_price;
       $cp_qty           = 1;
       $aff_code         = $cart[affiliate];

       /* REMOVED SO COUPON DOES NOT APPLY TO DOMAINS
       if ($coupon_percent_discount && $coupon_new_only && !$account_addon && $coupon_misc1)
           $cp_discount  = $coupon_percent_discount;
       elseif ($coupon_percent_discount && !$coupon_new_only && $coupon_misc1)
           $cp_discount  = $coupon_percent_discount;
       else
           $cp_discount  = 0;
       */

       $cp_discount      = 0;
       $cp_start_stamp   = date($date_format);
       $cp_renew_stamp   = ($allow_pro_rate_billing) ?
                            date($date_format,mktime(0,0,0,date("m"),01,date("Y")+$domain_years)) :
                            date($date_format,mktime(0,0,0,date("m"),date("d"),date("Y")+$domain_years)) ;
       switch ($domain_years) {
          case 1:  $cp_billing_cycle = 111;  break;
          case 2:  $cp_billing_cycle = 124;  break;
          case 3:  $cp_billing_cycle = 136;  break;
          case 4:  $cp_billing_cycle = 148;  break;
          case 5:  $cp_billing_cycle = 160;  break;
          case 6:  $cp_billing_cycle = 172;  break;
          case 7:  $cp_billing_cycle = 184;  break;
          case 8:  $cp_billing_cycle = 196;  break;
          case 9:  $cp_billing_cycle = 1108; break;
          case 10: $cp_billing_cycle = 1120; break;
          default; $cp_billing_cycle = 0; break;
       }
       $cp_billing_cycle = ($cp_billing_cycle!="") ? $cp_billing_cycle : 1 ;
       $cp_status        = 2; ## ACTIVE
       $cp_comments      = AUTOSIGNUPFORM."\n".$domain_name;
       $cp_renewed_on    = ($allow_pro_rate_billing) ?
                            date($date_format,mktime(0,0,0,date("m"),01,date("Y"))) :
                            date($date_format) ;
       $cp_stamp         = mktime();
       include($DIR."include/db_attributes.inc.php");
     // If we are transfering the domain, DO NOT INSERT the client_package
     // because there is no need to track it.
     if ($register!="transfer") {
       $result = mysql_query($insert_sql,$dbh);
       if ($debug || !$result) {
           mail($order_email,"ORDER ERROR [client_package]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
       }
       $cp_id = mysql_insert_id();

       if($debug)echo "added package: $insert_sql<br>";

       ## ADD ACCOUNT DETAILS
       $db_table          = "account_details";
       $do                = "add";
       $domain_id         = $domain_id;
       $cp_id             = $cp_id;
       $ip                = "";
       $server            = $default_server_name;
       $server_type       = $default_server_type;
       $username          = $username;
       $password          = $password;
       $insert_sql        = "INSERT INTO $db_table (details_id, client_id, cp_id, domain_id, ip, server, server_type, username, password) VALUES (NULL, '$master_client_id', '$cp_id', '$domain_id', '$ip', '$server', '$server_type', '$username', '$password')";
       $result = mysql_query($insert_sql,$dbh);
       if ($debug || !$result) {
           mail($order_email,"ORDER ERROR [account_details]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
       }
       if($debug)echo "added details: $insert_sql<br>";

       if (!$ad&&$parent_cp_id) // Make sure there is a parent package
       {
           $cp_id = $parent_cp_id;
           include($DIR."include/db_attributes.inc.php");
           $result = mysql_query($insert_sql,$dbh);
           if ($debug || !$result) {
               mail($order_email,"ORDER ERROR [account_details]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
           }
       }
       $ad = TRUE;
     } // end if $register!="transfer"
    }

    ## ADD ACCOUNT DETAILS
    if (!$ad)
    {
       $db_table          = "account_details";
       $do                = "add";
       $cp_id             = $parent_cp_id;
       $domain_id         = $domain_id ;
       $ip                = "";
       $server            = $default_server_name;
       $server_type       = $default_server_type;
       $username          = $username;
       $password          = $password;
       include($DIR."include/db_attributes.inc.php");
       $result = mysql_query($insert_sql,$dbh);
       if ($debug || !$result) {
           mail($order_email,"ORDER ERROR [account_details]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
       }
       if($debug)echo "added details: $insert_sql<br>";
    }

    # ADD INVOICE
    $db_table               = "client_invoice";
    $do                     = "add";
    $invoice_amount         = $this_total;
    $invoice_amount_paid    = ($auth_return == 1) ? $this_total : 0 ;
    $invoice_date_entered   = mktime();
    $dd                     = ($dd_static) ? $due_on_this_day : date("d")+$due_on_this_day ;
    $invoice_date_due       = mktime(0,0,0,date("m"),$dd,date("Y"));
    $invoice_date_paid      = ($auth_return == 1) ? mktime() : 0 ;
    $batch_stamp            = ($auth_return == 1) ? mktime() : 0 ;
    switch ($pay_method) {
       case creditcard: $invoice_payment_method = 1;            break;
       case echeck:     $invoice_payment_method = $echeck_id;   break;
       case worldpay:   $invoice_payment_method = $worldpay_id; break;
       case paypal:     $invoice_payment_method = $paypal_id;   break;
       case account_addon:
            if($debug)echo "SELECT billing_method FROM client_info WHERE client_id = $master_client_id";
            list($invoice_payment_method)=mysql_fetch_row(mysql_query("SELECT billing_method FROM client_info WHERE client_id = $master_client_id"));
       break;
       default:         $invoice_payment_method = 2;            break;
    }
    $invoice_payment_method = ($invoice_payment_method) ? $invoice_payment_method : 0 ;
    $invoice_snapshot       = addslashes(display_cart_no_output());
    $invoice_comments       = AUTOSIGNUPFORM;
    $invoice_stamp          = mktime();
    $auth_code              = $auth_code;
    $avs_code               = $avs_code;
    $trans_id               = $trans_id;
    $insert_sql = "INSERT INTO $db_table (invoice_id,
                                          client_id,
                                          invoice_amount,
                                          invoice_amount_paid,
                                          invoice_date_entered,
                                          invoice_date_due,
                                          invoice_date_paid,
                                          invoice_payment_method,
                                          invoice_snapshot,
                                          auth_return,
                                          auth_code,
                                          avs_code,
                                          trans_id,
                                          batch_stamp,
                                          invoice_comments,
                                          invoice_stamp) VALUES (NULL,
                                                                 '$master_client_id',
                                                                 '".str_replace(",","",display_currency($invoice_amount,1))."',
                                                                 '".str_replace(",","",display_currency($invoice_amount_paid,1))."',
                                                                 '$invoice_date_entered',
                                                                 '$invoice_date_due',
                                                                 '$invoice_date_paid',
                                                                 '$invoice_payment_method',
                                                                 '$invoice_snapshot',
                                                                 '$auth_return',
                                                                 '$auth_code',
                                                                 '$avs_code',
                                                                 '$trans_id',
                                                                 '$batch_stamp',
                                                                 '$invoice_comments',
                                                                 '$invoice_stamp')";
    $result = mysql_query($insert_sql,$dbh);
    if ($debug || !$result) {
        mail($order_email,"ORDER ERROR [client_invoice]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
    }
    $invoice_id = mysql_insert_id();
    if($debug)echo "added invoice: $insert_sql<br>";

    ## client_register entry debit
    $reg_desc = SIGNUPINVOICE;
    $reg_bill = $invoice_amount;
    register_insert($master_client_id,$reg_desc,$invoice_id,$reg_bill,0);

    ## client_register entry credit
    if ( (!$account_addon) && ( $tier2 && $allow_signup_charge && ( $pay_method=="echeck" || $pay_method=="creditcard" ) ) )
    {
        $reg_desc = SIGNUPPAYMENT;
        $reg_payment = $invoice_amount_paid;
        register_insert($master_client_id,$reg_desc,$invoice_id,0,$reg_payment);
    }

    # ENTER TODO NOTE
    if ($pay_method == "creditcard") {
        $todo_title = "*".ENCRYPTCC."*";
        $todo_stamp = mktime();
        $todo_desc  = NEWCCCLIENT." [$master_client_id: $x_First_Name $x_Last_Name]\n <a href=$admin_page?op=quick_encrypt&tile=todo&id=$master_client_id&stamp=$todo_stamp>".CLICKHERE."</a> [$admin_page?op=quick_encrypt&tile=todo&id=$master_client_id&stamp=$todo_stamp] ".ECRYPTBEFORENEXTBATCH."!";
        $insert_sql = "INSERT INTO todo_list (todo_id,todo_title,todo_desc,admin_id,todo_status,todo_due,todo_stamp) VALUES (NULL,'$todo_title','$todo_desc','$master_client_id','1','$todo_stamp','$todo_stamp')";
        $result = mysql_query($insert_sql,$dbh);
        if ($debug || !$result) {
            mail($order_email,"ORDER ERROR [todo_list]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
        }
    } elseif ($pay_method == "account_addon") {
        $todo_title = "*".ADDONS."*";
        $todo_stamp = mktime();
        $todo_desc  = "[$master_client_id: $x_First_Name $x_Last_Name]\n <a href=$admin_page?op=client_package&tile=client&client_id=$master_client_id&cp_id=$cp_id&display_parent_cp_id=1>".CLICKHERE."</a>";
        $insert_sql = "INSERT INTO todo_list (todo_id,todo_title,todo_desc,admin_id,todo_status,todo_due,todo_stamp) VALUES (NULL,'$todo_title','$todo_desc','$master_client_id','1','$todo_stamp','$todo_stamp')";
        $result = mysql_query($insert_sql,$dbh);
        if ($debug || !$result) {
            mail($order_email,"ORDER ERROR [account_addon]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
        }
    }

    # ADD EVENT LOG
    $db_table      = "event_log";
    $do            = "add";
    $log_comments  = REGISTEREDON.": ".date("$date_format: h:i:s")." ".FROM.": ".getenv("REMOTE_ADDR")."\n<br>";
    $log_comments .= REFERRER.": $referrer\n<br>";
    $log_comments .= "x_Cust_ID=".$HTTP_SERVER_VARS["REMOTE_ADDR"]."$x_Email\n<br>";
    $insert_sql    = "INSERT INTO $db_table (log_id, client_id, log_type, log_comments, log_stamp) VALUES (NULL, '$master_client_id', 3, '$log_comments', '".mktime()."')";
    $result = mysql_query($insert_sql,$dbh);
    if ($debug || !$result) {
        mail($order_email,"ORDER ERROR [event_log]: ".date("Y/m/d: h:i:s"),$insert_sql."\n\nMYSQL_ERROR_NUM: ".mysql_errno().":\nMYSQL_ERROR: ".mysql_error(),"From: $order_email");
    }
?>