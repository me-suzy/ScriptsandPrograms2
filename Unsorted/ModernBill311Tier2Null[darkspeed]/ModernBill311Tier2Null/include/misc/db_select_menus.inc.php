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

# --------------- #
#  "A" Functions  #
# --------------- #
function affiliate_pay_type_select_box($id,$name="aff_pay_type")
{
         GLOBAL $details_view,$affiliate_pay_types;
         $affiliate_select = "<select name=\"$name\">";
         foreach($affiliate_pay_types as $key => $value) {
                 $affiliate_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $affiliate_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $affiliate_select.= ">$value</option>\n";
         }
         $affiliate_select.= "</select>";
         return ($details_view) ? $this : $affiliate_select ;
}

function affiliate_cycle_select_box($id,$name="aff_pay_cycle")
{
         GLOBAL $details_view,$affiliate_cycles;
         $affiliate_select = "<select name=\"$name\">";
         foreach($affiliate_cycles as $key => $value) {
                 $affiliate_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $affiliate_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $affiliate_select.= ">$value</option>\n";
         }
         $affiliate_select.= "</select>";
         return ($details_view) ? $this : $affiliate_select ;
}

# --------------- #
#  "B" Functions  #
# --------------- #
function ban_type_select_box($id,$name="ban_type")
{
         GLOBAL $details_view,$ban_types;
         $ban_type_select = "<select name=\"$name\">";
         foreach($ban_types as $key => $value) {
                 $ban_type_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $ban_type_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $ban_type_select.= ">$value</option>\n";
         }
         $ban_type_select.= "</select>";
         return ($details_view) ? $this : $ban_type_select ;
}

function billing_method_select_box($id,$name="billing_method")
{
         GLOBAL $details_view,$billing_types;
         $billing_method="<select name=\"$name\">";
         foreach($billing_types as $key => $value) {
                 $billing_method.="<option value=\"$key\"";
                 if ($id==$key) {
                     $billing_method.=" SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $billing_method.=">$value</option>\n";
         }
         $billing_method.="</select>";
         return ($details_view) ? $this : $billing_method ;
}

# --------------- #
#  "C" Functions  #
# --------------- #
function call_status_select_box($id,$name="call_status")
{
         GLOBAL $details_view,$call_status_types;
         $call_status_select = "<select name=\"$name\">";
         foreach($call_status_types as $key => $value) {
                 $call_status_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $call_status_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $call_status_select.= ">$value</option>\n";
         }
         $call_status_select.= "</select>";
         return ($details_view) ? $this : $call_status_select ;
}

function category_type_select_box($id,$name="ctype")
{
         GLOBAL $details_view,$category_types;
         $category_select = "<select name=\"$name\">";
         foreach($category_types as $key => $value) {
                 $category_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $db_type_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $category_select.= ">$value</option>\n";
         }
         $category_select.= "</select>";
         return ($details_view) ? $this : $category_select ;
}

function cycle_select_box($id,$name="cp_billing_cycle")
{
         GLOBAL $details_view,$cycle_types;
         $cp_billing_cycle= "<select name=\"$name\">";
         foreach($cycle_types as $key => $value) {
                 $cp_billing_cycle.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $cp_billing_cycle.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $cp_billing_cycle.= ">$value</option>\n";
         }
         $cp_billing_cycle.= "</select>";
         return ($details_view) ? $this : $cp_billing_cycle ;
}

# --------------- #
#  "D" Functions  #
# --------------- #
function date_format_select_box($id,$name="date_format")
{
         GLOBAL $details_view,$date_format_types;
         $date_format_select = "<select name=\"$name\">";
         foreach($date_format_types as $key => $value) {
                 $date_format_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $date_format_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $date_format_select.= ">$value</option>\n";
         }
         $date_format_select.= "</select>";
         return ($details_view) ? $this : $date_format_select ;
}

function db_type_select_box($id,$name="db_type")
{
         GLOBAL $details_view,$db_types;
         $db_type_select = "<select name=\"$name\">";
         foreach($db_types as $key => $value) {
                 $db_type_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $db_type_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $db_type_select.= ">$value</option>\n";
         }
         $db_type_select.= "</select>";
         return ($details_view) ? $this : $db_type_select ;
}

# --------------- #
#  "L" Functions  #
# --------------- #
function language_select_box($id,$name="new_language")
{
         GLOBAL $details_view,$language_types;
         $language_select = "<select name=\"$name\">";
         foreach($language_types as $key => $value){
                 $language_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $language_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $language_select.= ">$value</option>\n";
         }
         $language_select.= "</select>";
         return ($details_view) ? $this : $language_select;
}

function log_type_select_box($id,$name="log_type")
{
         GLOBAL $details_view,$log_types;
         $log="<select name=\"$name\">";
         foreach($log_types as $key => $value) {
                 $log.="<option value=\"$key\"";
                 if ($id==$key) {
                     $log.=" SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $log.=">$value</option>\n";
         }
         $log.="</select>";
         return ($details_view) ? $this : $log ;
}

# --------------- #
#  "M" Functions  #
# --------------- #
function monitor_select_box($id=1,$name="monitor")
{
         GLOBAL $details_view,$monitor_types;
         $monitor_select = "<select name=\"$name\">";
         foreach($monitor_types as $key => $value) {
                 $monitor_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $monitor_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $monitor_select.= ">$value</option>\n";
         }
         $monitor_select.= "</select>";
         return ($details_view) ? $this : $monitor_select ;
}

# --------------- #
#  "P" Functions  #
# --------------- #
function pack_display_select_box($id,$name) // Fix me. No default value.
{
         GLOBAL $details_view,$pack_display_types;
         $id=($id)?$id:0;
         $pack_display_select= "<select name=\"$name\">";
         foreach($pack_display_types as $key => $value) {
                 $pack_display_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $pack_display_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $pack_display_select.= ">$value</option>\n";
         }
         $pack_display_select.= "</select>";
         return ($details_view) ? $this : $pack_display_select ;
}

function vortech_pack_sort_select_box($id,$name) // v3.1.0
{
         GLOBAL $details_view,$vortech_sort_types;
         $id=($id)?$id:0;
         $vortech_pack_sort= "<select name=\"$name\">";
         foreach($vortech_sort_types as $key => $value) {
                 $vortech_pack_sort.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $vortech_pack_sort.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $vortech_pack_sort.= ">$value</option>\n";
         }
         $vortech_pack_sort.= "</select>";
         return ($details_view) ? $this : $vortech_pack_sort ;
}

function vortech_pack_menu_display_select_box($id,$name) // v3.1.0
{
         GLOBAL $details_view,$vortech_pack_display_types;
         $id=($id)?$id:0;
         $vortech_pack_menu_display= "<select name=\"$name\">";
         foreach($vortech_pack_display_types as $key => $value) {
                 $vortech_pack_menu_display.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $vortech_pack_menu_display.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $vortech_pack_menu_display.= ">$value</option>\n";
         }
         $vortech_pack_menu_display.= "</select>";
         return ($details_view) ? $this : $vortech_pack_menu_display ;
}

function payment_select_box($id,$name="invoice_payment_method")
{
         GLOBAL $payment_types,$details_view;
         $payment_select = "<select name=\"$name\">";
         foreach($payment_types as $key => $value) {
                 $payment_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $payment_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $payment_select.= ">$value</option>\n";
         }
         $payment_select.= "</select>";
         return ($details_view) ? $this : $payment_select ;
}

function priority_select_box($id,$name="call_priority")
{
         GLOBAL $details_view,$priority_types;
         $priority_select = "<select name=\"$name\">";
         foreach($priority_types as $key => $value) {
                 $priority_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $priority_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $priority_select.= ">$value</option>\n";
         }
         $priority_select.= "</select>";
         return ($details_view) ? $this : $priority_select ;
}

# --------------- #
#  "R" Functions  #
# --------------- #
function registrar_select_box($id,$name="registrar_id")
{
         GLOBAL $registrar_types,$details_view;
         $registrar_select = "<select name=\"$name\">";
         foreach($registrar_types as $key => $value) {
                 $registrar_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $registrar_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $registrar_select.= ">$value</option>\n";
         }
         $registrar_select.= "</select>";
         return ($details_view) ? $this : $registrar_select ;
}

# --------------- #
#  "S" Functions  #
# --------------- #
function server_name_select_box($id,$all=NULL,$name="server")
{
         GLOBAL $details_view,$server_names;
         $server_name_select = "<select name=\"$name\">";
         if ($all) {
             $server_name_select .= "<option value=0 selected>".ALL."</option>";
         }
         foreach($server_names as $key => $value) {
                 $server_name_select.= "<option value=\"$value\"";
                 if ($id==$value) {
                     $server_name_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $server_name_select.= ">$value</option>\n";
         }
         $server_name_select.= "</select>";
         return ($details_view) ? $this : $server_name_select ;
}

function server_type_select_box($id,$all=NULL,$name="server_type")
{
         GLOBAL $details_view,$server_types;
         $server_type_select = "<select name=\"$name\">";
         if ($all) {
             $server_type_select .= "<option value=0 selected>".ALL."</option>";
         }
         foreach($server_types as $key => $value) {
                 $server_type_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $server_type_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $server_type_select.= ">$value</option>\n";
         }
         $server_type_select.= "</select>";
         return ($details_view) ? $this : $server_type_select ;
}

function status_select_box($id,$name) // Fix me. No default value.
{
         GLOBAL $details_view,$status_types;
         $id=($id)?$id:2;
         $status_select= "<select name=\"$name\">";
         foreach($status_types as $key => $value) {
                 $status_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $status_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $status_select.= ">$value</option>\n";
         }
         $status_select.= "</select>";
         return ($details_view) ? $this : $status_select ;
}

# --------------- #
#  "T" Functions  #
# --------------- #
function tax_type_select_box($name,$id) // Fix me, my values are backwards
{
         GLOBAL $details_view,$tax_types;
         $tax_type_select = "<select name=\"$name\">";
         foreach($tax_types as $key => $value) {
                 $tax_type_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $tax_type_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $tax_type_select.= ">$value</option>\n";
         }
         $tax_type_select.= "</select>";
         return ($details_view) ? $this : $tax_type_select ;
}

function theme_select_box($id,$name="new_theme")
{
         GLOBAL $details_view,$theme_types;
         $theme_select = "<select name=\"$name\">";
         foreach($theme_types as $key => $value) {
                 $theme_select.= "<option value=\"$key\"";
                 if ($id==$key) {
                     $theme_select.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $theme_select.= ">$value</option>\n";
         }
         $theme_select.= "</select>";
         return ($details_view) ? $this : $theme_select ;
}

function todo_status_select_box($id,$name="todo_status")
{
         GLOBAL $details_view,$todo_types;
         $todo_status = "<select name=\"$name\">";
         foreach($todo_types as $key => $value) {
                 $todo_status.= "<option value=\"$key\"";
                 if ($id=="$key") {
                     $todo_status.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $todo_status.= ">$value</option>\n";
         }
         $todo_status.= "</select>";
         return ($details_view) ? $this : $todo_status ;
}

function true_false_radio($name,$id=1) // Fix me. My values are backwards.
{
         GLOBAL $details_view,$true_false;
         $true_false_radio=NULL;
         foreach($true_false as $key => $value) {
                 $true_false_radio.= "<input type=radio name=\"$name\" value=\"$key\" ";
                 if ($id==$key) {
                     $true_false_radio.= " CHECKED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $true_false_radio.= "> ".SFB.$value.EF."&nbsp;\n";
         }
         return ($details_view) ? $this : $true_false_radio ;
}

#########################################
## These funtions are for REPORT TYPES ##
#########################################
## You can add any reports you want simply by adding the
## WHERE clause after the |
## EX. db_table|where_clause
function report_select_box($id=NULL)
{
         GLOBAL $tier2;
         $today_stamp = mktime();
         $first_stamp = mktime(0,0,0,date("m"),1,date("Y"));
         $last_stamp  = mktime(0,0,0,date("m")+1,-1,date("Y"));
         $domain_sql  = urlencode("WHERE ( domain_expires <= $last_stamp AND domain_expires >= $first_stamp ) AND monitor=1 ORDER BY domain_name");
         $client_sql  = urlencode("WHERE client_status != 0");
         $invoice_sql = urlencode("WHERE invoice_amount_paid != invoice_amount AND invoice_date_due <= $today_stamp ORDER BY invoice_date_due");
         $report_box  = "<select name=report_type>";
         $report_box .= "<option value=0>".SELECTREPORT."</option>";
         $report_box .= "<option value=0>- - - - - - - - - - - - - - - - - -</option>";

         if ($tier2) {
         $type = array("0|1"                         => "",
                       "0|2"                         => "---".CLIENTREPORTS."---",
                       "client_info|$client_sql"     => VIEWCLIENTS,
                       "client_package|"             => VIEWCLIENTPACKAGES,
                       "client_credit|"              => VIEWCLIENTCREDITS,
                       "0|3"                         => "",
                       "0|4"                         => "---".DOMAINREPORTS."---",
                       "domain_names|"               => VIEWDOMAINNAMES,
                       "domain_names|$domain_sql"    => VIEWEXPDOMAINS,
                       "0|5"                         => "",
                       "0|6"                         => "---".BILLINGREPORTS."---",
                       "client_invoice|"             => VIEWINVOICES,
                       "client_invoice|$invoice_sql" => VIEWOVERINVOICES,
                       "0|7"                         => "",
                       "0|8"                         => "---".BATCHREPORTS."---",
                       "authnet_batch|"              => VIEWAUTHNETBATCH,
                       "batch_details|"              => VIEWBATCHSUMM,
                       "0|9"                         => "",
                       "0|a"                         => "---".MISCREPORTS."---",
                       "package_type|"               => VIEWALLPACKAGES);
         } else {
         $type = array("0|1"                         => "",
                       "0|2"                         => "---".CLIENTREPORTS."---",
                       "client_info|$client_sql"     => VIEWCLIENTS,
                       "client_package|"             => VIEWCLIENTPACKAGES,
                       "client_credit|"              => VIEWCLIENTCREDITS,
                       "0|3"                         => "",
                       "0|4"                         => "---".DOMAINREPORTS."---",
                       "domain_names|"               => VIEWDOMAINNAMES,
                       "domain_names|$domain_sql"    => VIEWEXPDOMAINS,
                       "0|5"                         => "",
                       "0|6"                         => "---".BILLINGREPORTS."---",
                       "client_invoice|"             => VIEWINVOICES,
                       "client_invoice|$invoice_sql" => VIEWOVERINVOICES,
                       "0|7"                         => "",
                       "0|a"                         => "---".MISCREPORTS."---",
                       "package_type|"               => VIEWALLPACKAGES);
         }
         foreach($type as $key => $value) {
                 $report_box.= "<option value=\"$key\"";
                 if ($id=="$key") {
                     $report_box.= " SELECTED ";
                     if ($details_view) {
                         $this=$value;
                         break;
                     }
                 }
                 $report_box.= ">$value</option>\n";
         }
         $report_box.= "</select>";
         return ($details_view) ? $this : $report_box ;
}

#############################################
## These funtions are for all search forms ##
#############################################
## SEARCH CLIENT_INVOICES
function invoice_search_select_box($id=NULL)
{
         $type = array("invoice_id|id"             => INVOICENUM,
                       "invoice_date_entered|date" => BYMONTH);
         $search_box = "<select name=column_query>";
         foreach($type as $key => $value) {
                 $search_box.= "<option value=\"$key\">$value</option>\n";
         }
         $search_box.= "</select>";
         return $search_box ;
}

## SEARCH CLIENT_REGISTER
function register_select_box($id=NULL)
{
         $type = array("reg_bill"      => DEBIT,
                       "reg_payment"   => CREDIT,
                       "invoice_id"    => INVOICE,
                       "reg_date|date" => DATE,
                       "reg_desc"      => DESCRIPTION);
         $search_box = "<select name=column_query>";
         foreach($type as $key => $value) {
                 $search_box.= "<option value=\"$key\">$value</option>\n";
         }
         $search_box.= "</select>";
         return $search_box ;
}

## SEARCH PACKAGE_TYPES
function package_search_select_box($id=NULL)
{
         $type = array("pack_id|id" => PACKAGEID,
                       "pack_name"  => PACKAGENAME);
         $search_box = "<select name=column_query>";
         foreach($type as $key => $value) {
                 $search_box.= "<option value=\"$key\">$value</option>\n";
         }
         $search_box.= "</select>";
         return $search_box ;
}

## SEARCH PACKAGE_TYPES
function support_search_select_box($id=NULL)
{
         $type = array("call_question" => QUESTION,
                       "call_error"    => ERROR);
         $search_box = "<select name=column_query>";
         foreach($type as $key => $value) {
                 $search_box.= "<option value=\"$key\">$value</option>\n";
         }
         $search_box.= "</select>";
         return $search_box ;
}

## SEARCH EMAIL_CONFIGS
function email_search_select_box($id=NULL)
{
         $type = array("email_id|id"     => EMAILID,
                       "email_title"     => TITLE,
                       "email_heading"   => HEADING,
                       "email_body"      => BODY,
                       "email_footer"    => FOOTER,
                       "email_signature" => SIGNATURE);
         $search_box = "<select name=column_query>";
         foreach($type as $key => $value) {
                 $search_box.= "<option value=\"$key\">$value</option>\n";
         }
         $search_box.= "</select>";
         return $search_box ;
}

## SEARCH BATCH_DETAILS
function batch_details_search_select_box($id=NULL)
{
         $type = array("batch_id|id"      => BATCHID,
                       "batch_stamp|date" => BYMONTH);
         $search_box = "<select name=column_query>";
         foreach($type as $key => $value) {
                 $search_box.= "<option value=\"$key\">$value</option>\n";
         }
         $search_box.= "</select>";
         return $search_box ;
}

## SEARCH coupon_id
function coupon_search_select_box($id=NULL)
{
         $type = array("coupon_id|id"           => ID,
                       "coupon_code"            => COUPONCODE,
                       "coupon_percent_discount"=> PRECENTDISCOUNT,
                       "coupon_dollar_discount" => DOLLARDISCOUNT,
                       "coupon_comments"        => COMMENTS,
                       "coupon_start_stamp|date"=> STARTDATE,
                       "coupon_end_stamp|date"  => ENDDATE);
         $search_box = "<select name=column_query>";
         foreach($type as $key => $value) {
                 $search_box.= "<option value=\"$key\">$value</option>\n";
         }
         $search_box.= "</select>";
         return $search_box ;
}

## SEARCH TODO_LIST
function todo_search_select_box($id=NULL)
{
         $type = array("todo_id|id"       => TODOID,
                       "todo_title"       => TITLE,
                       "todo_desc"        => DESCRIPTION,
                       "batch_stamp|date" => BYMONTH);
         $search_box = "<select name=column_query>";
         foreach($type as $key => $value) {
                 $search_box.= "<option value=\"$key\">$value</option>\n";
         }
         $search_box.= "</select>";
         return $search_box ;
}

## SEARCH CLIENT_INFO
function search_select_box($id=NULL)
{
         $type = array("client_id|id"        => CLIENTID,
                       "1"                   => "----------",
                       "client_fname"        => FIRSTNAME,
                       "client_lname"        => LASTNAME,
                       "client_email"        => EMAIL,
                       "client_company"      => COMPANY,
                       "2"                   => "----------",
                       "billing_cc_type"     => CCNUM,
                       "3"                   => "----------",
                       "client_address"      => ADDRESS,
                       "client_city"         => CITY,
                       "client_state"        => STATE,
                       "client_zip"          => ZIP,
                       "client_country"      => COUNTRY,
                       "client_phone1"       => PHONE,
                       "client_phone2"       => FAX,
                       "4"                   => "----------",
                       "domain_name|domain"  => DOMAINNAME,
                       "ip|server"           => IP,
                       "server|server"       => SERVNAME,
                       "username|server"     => USERNAME,
                       "db_name|dbs"         => DBN,
                       "5"                   => "----------",
                       "log_comments|event"  => CLIENTNOTES);
         $search_box = "<select name=column_query>";
         foreach($type as $key => $value) {
                 $search_box.= "<option value=\"$key\">$value</option>\n";
         }
         $search_box.= "</select>";
         return $search_box ;
}
?>