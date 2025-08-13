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

#########################################
#
# EMAIL FUNCTIONS
#
#########################################
function send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from)
{
         if($debug)echo "FUNCTION:send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from)<br><br>";
         GLOBAL $success,
                $user_email_from,
                $email_type,
                $where,
                $email_to,
                $email_id,
                $email_subject,
                $inv_email_subject,
                $email_body,
                $errors_to,
                $debug,
                $dbh,
                $none,
                $cc_email_id,
                $check_email_id,
                $paypal_email_id,
                $worldpay_email_id,
                $this_client,
                $this_invoice,
                $this_email,
                $allow_str_emails,
                $default_from,
                $default_x_sender,
                $default_return_path,
                $default_reply_to,
                $default_email_bcc,
                $default_errors_to,
                $version,
                $default_signature,
                $allow_html_emails;

         $email_subject  = ($email_subject)  ? strip_tags($email_subject) : NULL ;
         $email_body     = ($email_body&&$allow_html_emails) ? $email_body : strip_tags($email_body) ;
         $email_body_end = "\n";
         $email_body_end.= "\r\n";
         $email_body_end.= ($allow_html_emails) ? SFB.$default_signature.EF."\n" : "$default_signature\n" ;
         $email_body_end.= ($allow_html_emails) ? TFB."[$version]".EF."\n" : "[$version]\n" ;
         $email_cc       = ($email_cc)       ? $email_cc              : $default_email_bcc ;
         $email_from     = ($email_from)     ? $email_from            : $default_from ;
         $email_from     = ($user_email_from)? $user_email_from       : $email_from ;
         $x_sender       = ($email_from)     ? $email_from            : $default_x_sender ;
         $x_priority     = ($email_priority) ? $email_priority        : 3 ;
         $return_path    = ($email_from)     ? $email_from            : $default_return_path ;
         $reply_to       = ($email_from)     ? $email_from            : $default_reply_to ;
         $addtl_headers  = "Cc: $email_cc".CTRL."";
         //$addtl_headers .= "Bcc: $default_email_bcc".CTRL."";
         $addtl_headers .= "From: $email_from".CTRL."";
         if ($allow_html_emails) $addtl_headers .= "Content-Type: text/html; charset=".CHARSET."".CTRL."";
         $addtl_headers .= "X-Sender: $x_sender".CTRL."";
         $addtl_headers .= "X-Mailer: ModernBill via PHP/".phpversion()."".CTRL."";
         $addtl_headers .= "X-Priority: $x_priority".CTRL."";
         $addtl_headers .= "Return-Path: $return_path".CTRL."";
         $addtl_headers .= "Errors-To: $default_errors_to".CTRL."";

         # Send Emails
         $success["sent"]   = ($success["sent"])   ? $success["sent"]   : 0 ;
         $success["failed"] = ($success["failed"]) ? $success["failed"] : 0 ;

         $pre_email_subject = $email_subject;
         $pre_email_body    = $email_body;
         $pre_email_id      = $email_id;

         if (is_array($email_to))
         { # --> Loop through the $email_to array
             $i = 0;
             reset($email_to);
             foreach ($email_to as $value) {

               $this_client = load_client_array($email_to[$i]);

               $to = trim($this_client["client_fname"])." ".trim($this_client["client_lname"])." <".trim($this_client["client_email"]).">";

               if ($email_type=="invoice")
               { # ALL INVOICES PER CLIENT
                   $where = urldecode($where);
                   $result = mysql_query("SELECT * FROM client_invoice i WHERE i.client_id='$email_to[$i]' AND $where");
                   if($debug)echo SFB."[$where]".EF."<br>";
                   while($this_invoice=mysql_fetch_array($result)) {
                         if ($allow_str_emails)
                         {
                            if ($debug)echo "S>$email_subject = replace_invoice_email($email_subject,$email_id,".$this_invoice["invoice_id"].",1);<br>B>$email_body    = replace_invoice_email($email_body,1,".$this_invoice["invoice_id"].",NULL);</pre>";
                            switch($this_client["billing_method"])
                            {
                               case 1:  $email_id = $cc_email_id;      break;
                               case 2:  $email_id = $check_email_id;   break;
                               case 3:  $email_id = $cc_email_id;      break;
                               case 4:  $email_id = $check_email_id;   break;
                               case 5:  $email_id = $paypal_email_id;  break;
                               case 6:  $email_id = $worldpay_email_id;break;
                               default: $email_id = $check_email_id;   break;
                            }
                            #if ($this_client["billing_method"] == 1 && !$pre_email_id) $email_id = $cc_email_id;
                            #if ($this_client["billing_method"] == 2 && !$pre_email_id) $email_id = $check_email_id;
                            $email_id = ($pre_email_id) ? $pre_email_id : $email_id;
                            $email_subject = replace_invoice_email($pre_email_subject,$email_id,$this_invoice["invoice_id"],1);
                            $email_body    = replace_invoice_email($pre_email_body,$email_id,$this_invoice["invoice_id"],NULL);
                         }
                         $this_body = ($allow_html_emails) ? custom_nl2br(MFB.$email_body.$email_body_end,"_blank".EF) : $email_body.$email_body_end ;
                         if (!$debug&&mail($to,stripslashes($email_subject),stripslashes($this_body),$addtl_headers))
                         {
                             $success["sent"]++;
                         }
                         else
                         {
                             $success["failed"]++;
                         }
                   }
               }
               else
               { # GENERIC
                  if ($allow_str_emails)
                  {
                      $email_subject = replace_generic_email($pre_email_subject,$this_client["client_id"]);
                      $email_body    = replace_generic_email($pre_email_body,$this_client["client_id"]);
                  }

                  $this_body = ($allow_html_emails) ? custom_nl2br(MFB.$email_body.$email_body_end,"_blank".EF) : $email_body.$email_body_end ;
                  if (!$debug&&mail ($to,stripslashes($email_subject),stripslashes($this_body),$addtl_headers))
                  {
                      $success["sent"]++;
                  }
                  else
                  {
                      $success["failed"]++;
                  }
               }

             $i++;
             }

         }
         else
         { # --> Send single email <-- #

          if ($email_type=="user_contact")
          { # USER_CONTACT WITH EMAIL ADDRES

              $this_body = ($allow_html_emails) ? custom_nl2br(MFB.$email_body.$email_body_end,"_blank".EF) : $email_body.$email_body_end ;
              if (!$debug&&mail(trim($email_to),stripslashes($email_subject),stripslashes($this_body),$addtl_headers))
              {
                  $success["sent"]++;
              }
              else
              {
                  $success["failed"]++;
              }

          }
          else
          { # GENERIC WITH CLIENT_ID

               if ($none)
               {
                   $to = trim($email_to);
               }
               else
               {
                   $this_client=load_client_array($email_to);
                   $to = trim($this_client["client_fname"])." ".trim($this_client["client_lname"])." <".trim($this_client["client_email"]).">";
               }

               if ($email_type=="invoice")
               { # ALL INVOICES PER CLIENT
                   $result = mysql_query("SELECT * FROM client_invoice i WHERE i.client_id='$email_to[$i]' AND $where");
                   while($this_invoice=mysql_fetch_array($result)) {
                         if ($allow_str_emails&&is_int($this_client["client_id"]))
                         {
                            $email_subject = ($none) ? $email_subject : replace_invoice_email($pre_email_subject,$email_id,$this_invoice["invoice_id"],1);
                            $email_body    = ($none) ? $email_body : replace_invoice_email($pre_email_body,$email_id,$this_invoice["invoice_id"],NULL);
                         }

                         $this_body = ($allow_html_emails) ? custom_nl2br(MFB.$email_body.$email_body_end,"_blank".EF) : $email_body.$email_body_end ;
                         if (!$debug&&mail($to,stripslashes($email_subject),stripslashes($this_body),$addtl_headers))
                         {
                             $success["sent"]++;
                         }
                         else
                         {
                             $success["failed"]++;
                         }
                   }
               }
               else
               { # GENERIC
                  if ($allow_str_emails)
                  {
                      $email_subject = ($none) ? $email_subject : replace_generic_email($pre_email_subject,$this_client["client_id"]);
                      $email_body    = ($none) ? $email_body : replace_generic_email($pre_email_body,$this_client["client_id"]);
                  }

                  $this_body = ($allow_html_emails) ? custom_nl2br(MFB.$email_body.$email_body_end,"_blank".EF) : $email_body.$email_body_end ;
                  if (!$debug&&mail ($to,stripslashes($email_subject),stripslashes($this_body),$addtl_headers))
                  {
                      $success["sent"]++;
                  }
                  else
                  {
                      $success["failed"]++;
                  }
               }
            }
         }

         if($debug)echo $success["sent"].$success["failed"]."<br>mail($to,$email_subject,$email_body,$addtl_headers)";
}

## REPLACE INVOICE SPECIFIC VARIABLES
function replace_invoice_email($email_text,$email_id=1,$invoice_id,$is_subject=NULL)
{
         GLOBAL $dbh,
                $allow_sql_emails,
                $date_format,
                $this_email,
                $this_invoice,
                $invoice_address,
                $this_client,
                $pp_item_name,
                $pp_item_number,
                $pp_amount;

         if (!$dbh) dbconnect();
         $details_view = 1;

         ## Load Arrays
         $this_email   = load_email_array($email_id);
         $this_invoice = load_invoice_array($invoice_id);
         $this_client  = load_client_array($this_invoice["client_id"]);

         ## String Replace
         if (!$is_subject&&ereg("%%LEAVE_FOR_ORIGINAL_INVOICE_HERE%%",$email_text))
         {
             $email_text = str_replace("%%LEAVE_FOR_ORIGINAL_INVOICE_HERE%%",$this_email["email_heading"]."\n".$this_email["email_body"]."\n".$this_email["email_footer"]."\n".$this_email["email_signature"]."\n",$email_text);
         }

         // invoice_payment_method

         // invoice_id
         $email_text     = str_replace("%%INVOICE_NUMBER%%",   $invoice_id,$email_text);
         // credit card type
         $email_text     = str_replace("%%INVOICE_CCTYPE%%",   $this_client['billing_cc_type'],$email_text);
         // transaction id
         $email_text     = str_replace("%%INVOICE_TRANSID%%",  $this_invoice['trans_id'],$email_text);
         // invoice_comments
         $email_text     = str_replace("%%INVOICE_COMMENTS%%",  $this_invoice['invoice_comments'],$email_text);
         // invoice_amount
         $email_text     = str_replace("%%INVOICE_AMOUNTDUE%%",display_currency($this_invoice["invoice_amount"]),$email_text);
         // invoice_date_due
         $email_text     = str_replace("%%INVOICE_DUEDATE%%",  date($date_format,$this_invoice["invoice_date_due"]),$email_text);
         // invoice_date_paid
         $email_text     = str_replace("%%INVOICE_DATE_PAID%%",date($date_format,$this_invoice["invoice_date_paid"]),$email_text);
         // invoice_stamp
         $email_text     = str_replace("%%INVOICE_STAMP%%",    date($date_format,$this_invoice["invoice_stamp"]),$email_text);
         // snapshot of HTML output
         $email_text     = str_replace("%%INVOICE_SNAPSHOT%%", $this_invoice["invoice_snapshot"],$email_text);
         // config setting - invoice address
         $email_text     = str_replace("%%INVOICE_ADDRESS%%",  strip_tags($invoice_address),$email_text);
         // invoice_date_entered
         $email_text     = str_replace("%%INVOICE_GENDATE%%",  date($date_format,$this_invoice["invoice_date_entered"]),$email_text);
         // calculation - remaining amount due
         $email_text     = str_replace("%%INVOICE_AMOUNTLEFT%%",display_currency($this_invoice["invoice_amount"]-$this_invoice["invoice_amount_paid"]),$email_text);
         // invoice_amount_paid
         $email_text     = str_replace("%%INVOICE_AMOUNT_PAID%%",display_currency($this_invoice["invoice_amount_paid"]),$email_text);
         // calculation - overdue days
         $overdue_days   = @round( ( ( mktime() - $this_invoice["invoice_date_due"] ) / ( 60 * 60 * 24 ) ) );
         $email_text     = str_replace("%%INVOICE_DAYSOVERDUE%%",$overdue_days,$email_text);

         // paypal & worldpay variables
         $pp_item_number = $this_invoice['invoice_id'];
         $pp_amount      = $this_invoice['invoice_amount'];
         $email_text     = str_replace("%%INVOICE_PAYPAL_LINK%%",generate_paypal_link($pp_item_name,$pp_item_number,$pp_amount,"link"),$email_text);
         $email_text     = str_replace("%%INVOICE_WORLDPAY_LINK%%",generate_worldpay_link($pp_item_name,$pp_item_number,$pp_amount,"link"),$email_text);

         return replace_generic_email($email_text,$this_client["client_id"]);
}

## REPLACE GENERIC VARIABLES
function replace_generic_email($email_text,$client_id)
{
         GLOBAL $dbh,$allow_sql_emails,$billing_types,$user_login_url,$date_format;
         if (!$dbh) dbconnect();
         $details_view = 1;

         ## Load this client's info
         $this_client = load_client_array($client_id);

         ## String Replace from table client_info
         $email_text = str_replace("%%DATE%%"         ,date($date_format),$email_text);
         $email_text = str_replace("%%FULLNAME%%"     ,$this_client["client_fname"]." ".$this_client["client_lname"],$email_text);
         $email_text = str_replace("%%CLIENTID%%"     ,$this_client["client_id"],$email_text);
         $email_text = str_replace("%%FIRSTNAME%%"    ,$this_client["client_fname"],$email_text);
         $email_text = str_replace("%%LASTNAME%%"     ,$this_client["client_lname"],$email_text);
         $email_text = str_replace("%%EMAIL%%"        ,$this_client["client_email"],$email_text);
         $email_text = str_replace("%%COMPANY%%"      ,$this_client["client_company"],$email_text);
         $email_text = str_replace("%%FULLADDRESS%%"  ,$this_client["client_address"]."\n".$this_client["client_city"].", ".$this_client["client_state"]." ".$this_client["client_zip"]."\n".$this_client["client_country"],$email_text);
         $email_text = str_replace("%%PHONE1%%"       ,$this_client["client_phone1"],$email_text);
         $email_text = str_replace("%%PHONE2%%"       ,$this_client["client_phone2"],$email_text);
         $email_text = str_replace("%%BILLINGMETHOD%%",$billing_types[$this_client["billing_method"]],$email_text);
         $email_text = str_replace("%%BILLINGTYPE%%"  ,$this_client["billing_cc_type"],$email_text);
         $email_text = str_replace("%%CCEXPDATE%%"    ,$this_client["billing_cc_exp"],$email_text);
         $email_text = str_replace("%%USERLOGINURL%%" ,$user_login_url,$email_text);

          $replace_array = array('client_id',
                                 'client_fname',
                                 'client_lname',
                                 'client_email',
                                 'client_company',
                                 'client_address',
                                 'client_city',
                                 'client_state',
                                 'client_zip',
                                 'client_country',
                                 'client_phone1',
                                 'client_phone2',
                                 'billing_method',
                                 'billing_cc_type',
                                 'billing_cc_num',
                                 'billing_cc_exp',
                                 'billing_cc_code',
                                 'client_password',
                                 'client_comments',
                                 'client_status',
                                 'client_stamp',
                                 'client_secondary_email',
                                 'client_username',
                                 'client_real_pass',
                                 'x_Bank_Name',
                                 'x_Bank_ABA_Code',
                                 'x_Bank_Acct_Num',
                                 'x_Drivers_License_Num',
                                 'x_Drivers_License_State',
                                 'x_Drivers_License_DOB',
                                 'apply_tax',
                                 'default_translation',
                                 'default_currency',
                                 'send_email_type',
                                 'secondary_contact',
                                 'client_field_1',
                                 'client_field_2',
                                 'client_field_3',
                                 'client_field_4',
                                 'client_field_5',
                                 'client_field_6',
                                 'client_field_7',
                                 'client_field_8',
                                 'client_field_9',
                                 'client_field_10');
         foreach ($replace_array as $value)
         {
                  $email_text = str_replace("%%".strtoupper($value)."%%",$this_client[$value],$email_text);
         }

         ## SQL Query str_replace
         ## Possible Security Hole! Use with Caution!
         if ($allow_sql_emails)
         {
             if (ereg("%SQL-1%(.*)%SQL-1%",$email_text,$select_regs)) $email_text = str_replace("%SQL-1%".$select_regs[1]."%SQL-1%",do_sql($select_regs[1],$client_id),$email_text);
             if (ereg("%SQL-2%(.*)%SQL-2%",$email_text,$select_regs)) $email_text = str_replace("%SQL-2%".$select_regs[1]."%SQL-2%",do_sql($select_regs[1],$client_id),$email_text);
             if (ereg("%SQL-3%(.*)%SQL-3%",$email_text,$select_regs)) $email_text = str_replace("%SQL-3%".$select_regs[1]."%SQL-3%",do_sql($select_regs[1],$client_id),$email_text);
             if (ereg("%SQL-4%(.*)%SQL-4%",$email_text,$select_regs)) $email_text = str_replace("%SQL-4%".$select_regs[1]."%SQL-4%",do_sql($select_regs[1],$client_id),$email_text);
             if (ereg("%SQL-5%(.*)%SQL-5%",$email_text,$select_regs)) $email_text = str_replace("%SQL-5%".$select_regs[1]."%SQL-5%",do_sql($select_regs[1],$client_id),$email_text);
         }
         return $email_text;
}

## REPLACE GENERIC VARIABLES
function replace_package_summary_email($email_text,$client_id,$cp_id=NULL)
{
         GLOBAL $dbh,$allow_sql_emails,$billing_types,$user_login_url,$cycle_types;
         if (!$dbh) dbconnect();
         $details_view = 1;

         ## Load this client's info
         $this_client = load_client_array($client_id);

         @list($total_active_packages)=mysql_fetch_row(mysql_query("SELECT count(pack_id) FROM client_package WHERE client_id = '".$this_client["client_id"]."' AND cp_status = 2"));
         @list($total_inactive_packages)=mysql_fetch_row(mysql_query("SELECT count(pack_id) FROM client_package WHERE client_id = '".$this_client["client_id"]."' AND cp_status = 1"));

         $email_text  = str_replace("%%PACK_TOTAL_ACTIVE%%",$total_active_packages,$email_text);
         $email_text  = str_replace("%%PACK_TOTAL_INACTIVE%%",$total_inactive_packages,$email_text);

         $sql  = "SELECT * FROM client_package WHERE ";
         $sql .= ($cp_id) ? "cp_id = '$cp_id'" : "client_id = '".$this_client["client_id"]."'" ;
         $result=mysql_query($sql);
         while($this_client_package=mysql_fetch_array($result))
         {

            ## Map Domain Names
            $list_domains=$domain_name=NULL;
            $sql="SELECT d.domain_name FROM account_details a, domain_names d WHERE a.domain_id=d.domain_id AND a.cp_id='".$this_client_package['cp_id']."'";
            $domain_result=mysql_query($sql,$dbh);
            if (!$domain_result) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
            while(list($domain_name)=mysql_fetch_array($domain_result))
            {
                $list_domains .= $domain_name.",";
            }
            $list_domains = ($list_domains) ? substr($list_domains,0,-1) : NULL ;

            ## Map Account Details
            $sql="SELECT * FROM account_details WHERE cp_id='".$this_client_package['cp_id']."'";
            $ad_result=mysql_query($sql,$dbh);
            if (!$ad_result) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
            while($this_account_details=mysql_fetch_array($ad_result))
            {
                list($domain_name)=mysql_fetch_array(mysql_query("SELECT domain_name FROM domain_names WHERE domain_id='".$this_account_details['domain_id']."'"));
                $email_text = str_replace("%%AD_DOMAIN%%",$domain_name,$email_text);
                $email_text = str_replace("%%AD_IP%%",$this_account_details['ip'],$email_text);
                $email_text = str_replace("%%AD_SERVER%%",$this_account_details['server'],$email_text);
                $email_text = str_replace("%%AD_SERVER_TYPE%%",$server_types[$this_account_details['server_type']],$email_text);
                $email_text = str_replace("%%AD_USERNAME%%",$this_account_details['username'],$email_text);
                $email_text = str_replace("%%AD_PASSWORD%%",$this_account_details['password'],$email_text);
            }

            ## Map Account DBs
            $sql="SELECT * FROM account_dbs WHERE cp_id='".$this_client_package['cp_id']."'";
            $db_result=mysql_query($sql,$dbh);
            if (!$db_result) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
            while($this_account_dbs=mysql_fetch_array($db_result))
            {
                $email_text = str_replace("%%DB_TYPE%%",$db_types[$this_account_dbs['db_type']],$email_text);
                $email_text = str_replace("%%DB_NAME%%",$this_account_dbs['db_name'],$email_text);
                $email_text = str_replace("%%DB_USER%%",$this_account_dbs['db_user'],$email_text);
                $email_text = str_replace("%%DB_PASS%%",$this_account_dbs['db_pass'],$email_text);
            }

            ## Map Account POPs
            $sql="SELECT * FROM account_pops WHERE cp_id='".$this_client_package['cp_id']."'";
            $pops_result=mysql_query($sql,$dbh);
            if (!$pops_result) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
            while($this_account_pops=mysql_fetch_array($pops_result))
            {
                $email_text = str_replace("%%POP_REAL_NAME%%",$this_account_pops['pop_real_name'],$email_text);
                $email_text = str_replace("%%POP_USERNAME%%",$this_account_pops['pop_username'],$email_text);
                $email_text = str_replace("%%POP_PASSWORD%%",$this_account_pops['pop_password'],$email_text);
                $email_text = str_replace("%%POP_SPACE%%",$this_account_pops['pop_space'],$email_text);
                $email_text = str_replace("%%POP_FTP%%",$this_account_pops['pop_ftp'],$email_text);
                $email_text = str_replace("%%POP_TELNET%%",$this_account_pops['pop_telnet'],$email_text);
            }

            ## Start Replacing
            @$this_package=mysql_fetch_array(mysql_query("SELECT * FROM package_type WHERE pack_id = ".$this_client_package["pack_id"].""));
            $email_text  = str_replace("%%LISTALLDOMAINS%%", $list_domains,$email_text);
            $email_text  = str_replace("%%PACK_NAME%%",      $this_package["pack_name"],$email_text);
            $email_text  = str_replace("%%PACK_PRICE%%",     $this_package["pack_price"],$email_text);
            $email_text  = str_replace("%%CP_STATUS%%",      $status_types[$this_client_package["cp_status"]],$email_text);
            $email_text  = str_replace("%%CP_QTY%%",         $this_client_package["cp_qty"],$email_text);
            $email_text  = str_replace("%%CP_DISCOUNT%%",    $this_client_package["cp_discount"],$email_text);
            $email_text  = str_replace("%%CP_BILLINGCYCLE%%",$cycle_types[$this_client_package["cp_billing_cycle"]],$email_text);
            $email_text  = str_replace("%%CP_STARTDATE%%",   stamp_to_date($this_client_package["cp_start_stamp"]),$email_text);
            $email_text  = str_replace("%%CP_RENEWDATE%%",   stamp_to_date($this_client_package["cp_renew_stamp"]),$email_text);
            $email_text  = str_replace("%%CP_RENEWON%%",     stamp_to_date($this_client_package["cp_renewed_on"]),$email_text);
         }
         return $email_text;
}

## REPLACE SQL QUERIES
function do_sql($select_regs,$client_id)
{
         GLOBAL $dbh,$variable;
         if (!$dbh) dbconnect();
         $sql = "SELECT $select_regs='$client_id' ";
         $sql = str_replace("client_cc_num","client_cc_type",$sql);
         $sql = str_replace("client_password","client_lname",$sql);
         $result=mysql_query($sql,$dbh);
         if (mysql_num_rows($result)) {
             list($variable)=mysql_fetch_row($result);
             return $variable;
         } else {
             return "[".ERROR."]";
         }
}

## THIS_CLIENT ARRAY
function load_client_array($id)
{
         GLOBAL $dbh,$this_client;
         if (!$dbh) dbconnect();
         return ($id) ? mysql_fetch_array(mysql_query("SELECT * FROM client_info WHERE client_id = $id ",$dbh)) : NULL;
}

## THIS_PACKAGE ARRAY
function load_cp_array($id)
{
         GLOBAL $dbh,$this_cp;
         if (!$dbh) dbconnect();
         $this_cp=mysql_fetch_array(mysql_query("SELECT * FROM client_package WHERE cp_id = $id ",$dbh));
         return $this_cp;
}

## THIS_INVOICE ARRAY
function load_invoice_array($id)
{
         GLOBAL $dbh,$this_invoice;
         if (!$dbh) dbconnect();
         $this_invoice=mysql_fetch_array(mysql_query("SELECT * FROM client_invoice WHERE invoice_id = $id ",$dbh));
         return $this_invoice;
}

## THIS_EMAIL ARRAY
function load_email_array($id)
{
         GLOBAL $dbh,$this_email;
         if (!$dbh) dbconnect();
         $this_email=mysql_fetch_array(mysql_query("SELECT * FROM email_config WHERE email_id = $id ",$dbh));
         return $this_email;
}

## THIS_ACCOUNT_DETAILS ARRAY
function load_account_details_array($id)
{
         GLOBAL $dbh,$this_email;
         if (!$dbh) dbconnect();
         $this_account_details=mysql_fetch_array(mysql_query("SELECT * FROM account_details WHERE details_id = $id ",$dbh));
         return $this_email;
}

## THIS_ACCOUNT_POPS ARRAY
function load_account_pops_array($id)
{
         GLOBAL $dbh,$this_email;
         if (!$dbh) dbconnect();
         $this_account_pops=mysql_fetch_array(mysql_query("SELECT * FROM account_pops WHERE pop_id = $id ",$dbh));
         return $this_email;
}

## THIS_ACCOUNT_DBS ARRAY
function load_account_dbs_array($id)
{
         GLOBAL $dbh,$this_email;
         if (!$dbh) dbconnect();
         $this_account_dbs=mysql_fetch_array(mysql_query("SELECT * FROM account_dbs WHERE db_id = $id ",$dbh));
         return $this_email;
}
?>