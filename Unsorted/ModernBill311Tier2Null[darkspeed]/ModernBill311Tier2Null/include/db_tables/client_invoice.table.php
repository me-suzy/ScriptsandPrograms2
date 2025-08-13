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

 /* ----------------- CLIENT_INVOICE ---------------------*/
      $title = INVOICES;
      $parent = array(1=>"client_info");
      $details_link = "client_invoice";
      $args = array(array("column"         => "invoice_id",
                           "required"      => 0,
                           "title"         => INVNUM,
                           "type"          => "HIDDEN"),
                    array("column"         => "client_id",
                           "required"      => 1,
                           "title"         => CLIENT,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => client_select_box($client_id),
                           "link_to_parent"=> 1,
                           "parent_op"     => "client_details"),
                    array("column"         => "invoice_amount",
                           "required"      => 1,
                           "title"         => AMOUNT,
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 15,
                           "append"        => "(".PRICEFORMAT.")"),
                    array("column"         => "invoice_amount_paid",
                           "required"      => 0,
                           "title"         => TOTALPAID,
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 15,
                           "append"        => "(".PRICEFORMAT.")"),
                    array("column"         => "due",
                           "title"         => TOTALDUE),
                    array("column"         => "invoice_date_entered",
                           "required"      => 1,
                           "title"         => DATECREATED,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($invoice_date_entered,"invoice_date_entered",stamp_to_date(mktime())),
                           "append"        => "(".DATEFORMAT2.")"),
                    array("column"         => "invoice_date_due",
                           "required"      => 1,
                           "title"         => DUEDATE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($invoice_date_due,"invoice_date_due",stamp_to_date(mktime(0,0,0,date("m"),date("d")+15,date("Y")))),
                           "append"        => "(".DATEFORMAT2.")"),
                    array("column"         => "invoice_date_paid",
                           "required"      => 0,
                           "title"         => DATEPAID,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($invoice_date_paid,"invoice_date_paid"),
                           "append"        => "(".DATEFORMAT2.")"),
                    array("column"         => "invoice_payment_method",
                           "required"      => 1,
                           "title"         => PAYMENTMETHOD,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => payment_select_box($invoice_payment_method)),
                    array("column"         => "invoice_snapshot",
                           "required"      => 0,
                           "title"         => DETAILS,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => invoice_display($invoice_snapshot)),
                    array("column"         => "auth_return",
                           "required"      => 0,
                           "title"         => AUTHRET,
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 35,
                           "append"        => "(".IFCC.")"),
                    array("column"         => "auth_code",
                           "required"      => 0,
                           "title"         => AUTHCODE,
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 35,
                           "append"        => "(".IFCC.")"),
                    array("column"         => "avs_code",
                           "required"      => 0,
                           "title"         => AVS,
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 35,
                           "append"        => "(".IFCC.")"),
                    array("column"         => "trans_id",
                           "required"      => 0,
                           "title"         => TRANSID,
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 35,
                           "append"        => "(".TRANSIDORCHECK.")"),
                    array("column"         => "batch_stamp",
                           "required"      => 0,
                           "title"         => BATCHDATE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($batch_stamp,"batch_stamp"),
                           "append"        => "(".IFCC.": ".DATEFORMAT2.")"),
                    array("column"         => "invoice_comments",
                           "required"      => 0,
                           "title"         => COMMENTS,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap),
                    array("column"         => "invoice_stamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($invoice_stamp,"invoice_stamp")));
      if(($submit||$do=="add"||$do=="edit")&&!$make_payments){
         if (!date_check($invoice_date_entered)) $oops .= "[".ERROR."] ".CREATEDINVALID."<br>";
         if (!date_check($invoice_date_due)) $oops .= "[".ERROR."] ".DUEDINVALID."<br>";
         if ($invoice_date_paid&&!date_check($invoice_date_paid)) $oops .= "[".ERROR."] ".PAIDINVALID."<br>";
         if ($batch_stamp&&!date_check($batch_stamp)) $oops .= "[".ERROR."] ".BATCHDATEINVLAID."<br>";
           $invoice_date_entered = (date_check($invoice_date_entered)) ? date_to_stamp($invoice_date_entered) : 0 ;
           $invoice_date_due     = (date_check($invoice_date_due))     ? date_to_stamp($invoice_date_due)     : 0 ;
           $invoice_date_paid    = (date_check($invoice_date_paid))    ? date_to_stamp($invoice_date_paid)    : 0 ;
           $batch_stamp          = (date_check($batch_stamp))          ? date_to_stamp($batch_stamp)          : 0 ;
      } elseif ($submit&&$make_payments) {
         if ($invoice_date_paid&&!date_check($invoice_date_paid)) $oops .= "[".ERROR."] ".PAIDINVALID."<br>";
         if ($batch_stamp&&!date_check($batch_stamp)) $oops .= "[".ERROR."] ".BATCHDATEINVLAID."<br>";
      }

      $select_sql = "SELECT invoice_id,
                            client_id,
                            invoice_amount,
                            invoice_amount_paid,
                            (invoice_amount - invoice_amount_paid) as due,
                            invoice_date_entered,
                            invoice_date_due,
                            invoice_date_paid,
                            invoice_payment_method,
                            auth_return,
                            batch_stamp FROM $db_table ";

   $recursive_sql = "SELECT invoice_id,
                            invoice_amount,
                            invoice_amount_paid,
                            (invoice_amount - invoice_amount_paid) as due,
                            invoice_date_entered,
                            invoice_date_due,
                            invoice_date_paid,
                            invoice_payment_method,
                            auth_return,
                            batch_stamp FROM $db_table ";

      $insert_sql = "INSERT INTO $db_table (invoice_id, client_id, invoice_amount, invoice_amount_paid, invoice_date_entered, invoice_date_due, invoice_date_paid, invoice_payment_method, invoice_snapshot, invoice_comments, invoice_stamp) VALUES (NULL, '$client_id', '$invoice_amount', '$invoice_amount_paid', '$invoice_date_entered', '$invoice_date_due', '$invoice_date_paid', '$invoice_payment_method', '$invoice_snapshot', '$invoice_comments', '$invoice_stamp')";

      $update_sql = "UPDATE $db_table SET client_id='$client_id',
                                          invoice_amount='$invoice_amount',
                                          invoice_amount_paid='$invoice_amount_paid',
                                          invoice_date_entered='$invoice_date_entered',
                                          invoice_date_due='$invoice_date_due',
                                          invoice_date_paid='$invoice_date_paid',
                                          invoice_payment_method='$invoice_payment_method',
                                          auth_return='$auth_return',
                                          auth_code='$auth_code',
                                          avs_code='$avs_code',
                                          trans_id='$trans_id',
                                          invoice_comments='$invoice_comments',
                                          invoice_stamp='$invoice_stamp' WHERE invoice_id='$invoice_id'";

      $delete_sql = array("DELETE FROM $db_table WHERE invoice_id='$invoice_id'",
                          "DELETE FROM authnet_batch WHERE x_Invoice_Num='$invoice_id'",
                          "DELETE FROM client_register WHERE invoice_id='$invoice_id'");
?>