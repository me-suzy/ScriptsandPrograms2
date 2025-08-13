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

## SELECT FROM EMAIL_CONFIG
function email_config_select_box($id,$column)
{
         GLOBAL $dbh,$details_view;
         if (!$dbh) dbconnect();
         $email_config  = "\n<select name=\"$column\">\n";
         $email_config .= "<option value=\"0\">".IWRITEOWN."</option>\n";
         $where         = ($details_view) ? "WHERE email_id = '$id'" : NULL;
         $sql           = "SELECT email_id, $column FROM email_config $where ORDER BY $column";
         $result        = mysql_query($sql);
         while(list($email_id,$column_contents) = mysql_fetch_array($result)) {
               $column_contents = strip_tags(cuttext($column_contents,50));
               if ($id==$email_id) {
                   $selected = " SELECTED";
                   if ($details_view) {
                       $this = "[$email_id] $column_contents";
                       break;
                   }
               } else {
                   $selected = NULL;
               }
               $email_config   .= "<option value=\"$email_id\"$selected>[$email_id] $column_contents\n";
         }
         $email_config.= "</select>\n";
         return ($details_view) ? $this : $email_config ;
}

function email_signup_select_box($id,$name="email_id")
{
         GLOBAL $dbh,$details_view, $db_table;
         if (!$dbh) dbconnect();
         $email_config  = "\n<select name=$name>\n";
         $email_config .= "<option value=0>".SELECTEMAIL."</option>\n";
         $sql           = "SELECT email_id, email_title FROM email_config ";
         switch($db_table) {
                case package_type: $sql.= "WHERE email_title LIKE '%vortech%' ORDER BY email_title"; break;
                case client_info:  $sql.= "WHERE email_title LIKE '%welcome%' ORDER BY email_title"; break;
                default:           $sql.= "ORDER BY email_title" ; break;
         }
         $result = mysql_query($sql);
         while(list($this_email_id,$column_contents) = mysql_fetch_array($result)) {
               $column_contents = strip_tags(cuttext($column_contents,50));
               if ($id==$this_email_id) {
                   $selected = " SELECTED";
                   if ($details_view) {
                       $this="[$this_email_id] $column_contents";
                       break;
                   }
               } else {
                   $selected = NULL;
               }
         $email_config   .= "<option value=\"$this_email_id\"$selected>[$this_email_id] $column_contents\n";
         }
         $email_config.= "</select>\n";
         return ($details_view) ? $this : $email_config ;
}

function cp_select_box($client_id,$id)
{
         GLOBAL $dbh,$details_view;
         if (!$dbh) dbconnect();
         $package  = "<select name=cp_id>";
         $package .= "<option value=0>".SELECTPACKAGE."</option>\n";
         $sql      = "SELECT c.cp_id, p.pack_name FROM client_package c, package_type p WHERE c.pack_id=p.pack_id ";
         if ($id&&!$client_id) { $sql .= "AND c.cp_id='$id'"; }
         if (!$id&&$client_id) { $sql .= "AND c.client_id='$client_id'"; }
         if ($id&&$client_id)  { $sql .= "AND c.client_id='$client_id'"; }
         $cp_result = mysql_query($sql,$dbh);
         while(list($cp_id,$cp_name) = mysql_fetch_array($cp_result)) {
               $package.= "<option value=\"$cp_id\"";
               if ($id==$cp_id) {
                   $package.= " SELECTED ";
                   if ($details_view) {
                       $this=$cp_name;
                       break;
                   }
               }
               $package.= ">$cp_name</option>\n";
         }
         $package.= "</select>";
         return ($details_view) ? $this : $package ;
}

function cp_select_menu($client_id,$id)
{
         GLOBAL $dbh,$details_view;
         if (!$dbh) dbconnect();
         $package = "<select name=\"parent_cp_id\">";
         $sql     = "SELECT c.cp_id, p.pack_name FROM client_package c, package_type p WHERE c.pack_id=p.pack_id ";
         if ($id&&!$client_id) { $sql .= "AND c.cp_id='$id'"; }
         if (!$id&&$client_id) { $sql .= "AND c.client_id='$client_id'"; }
         if ($id&&$client_id)  { $sql .= "AND c.client_id='$client_id'"; }
         $package.= "<option value=''>".NONE."</option>\n";
         $cp_result = mysql_query($sql,$dbh);
         while(list($cp_id,$cp_name) = mysql_fetch_array($cp_result)) {
               $package.= "<option value=\"$cp_id\"";
               if ($id==$cp_id) {
                   $package.= " SELECTED ";
                   if ($details_view) {
                       $this=$cp_name;
                       break;
                   }
               }
               $package.= ">$cp_name</option>\n";
         }
         $package.= "</select>";
         return ($details_view) ? $this : $package ;
}

function package_select_box($id,$cycle=0,$name="pack_id")
{
         GLOBAL $dbh,$details_view,$page;
         if (!$dbh) dbconnect();
         $package= "<select name=\"$name\">";
         $where = ($details_view&&$id) ? "WHERE pack_id = '$id'" : NULL;
         $sql = "SELECT pack_id,pack_name,pack_price FROM package_type $where";
         $result=mysql_query($sql);
         while(list($pack_id,$pack_name,$pack_price) = mysql_fetch_array($result)) {
               $package.= "<option value=\"$pack_id\"";
               if ($id==$pack_id) {
                   $package.= " SELECTED ";
                   if ($details_view) {
                       $this="<a href=$page?op=details&db_table=package_type&tile=package&print=&id=pack_id|$pack_id>$pack_name (".display_currency(split_price($pack_price,"price",$cycle)).")</a>";
                       break;
                   }
               }
               $package.= ">$pack_name ";
               $package.= ($cycle) ? "(".display_currency(split_price($pack_price,"price",$cycle)).")" : NULL ;
               $package.= "</option>\n";
         }
         $package.= "</select>";
         return ($details_view) ? $this : $package ;
}

function package_select_box_no_link($id,$cycle=0,$name="pack_id")
{
         GLOBAL $dbh,$details_view,$page;
         if (!$dbh) dbconnect();
         $package= "<select name=\"$name\">";
         $where = ($details_view&&$id) ? "WHERE pack_id = '$id'" : NULL;
         $sql = "SELECT pack_id,pack_name,pack_price FROM package_type $where";
         $result=mysql_query($sql);
         while(list($pack_id,$pack_name,$pack_price) = mysql_fetch_array($result)) {
               $package.= "<option value=\"$pack_id\"";
               if ($id==$pack_id) {
                   $package.= " SELECTED ";
                   if ($details_view) {
                       $this = $pack_name;
                       break;
                   }
               }
               $package.= ">$pack_name ";
               $package.= ($cycle) ? "($cycle:".display_currency(split_price($pack_price,"price",$cycle)).")" : NULL ;
               $package.= "</option>\n";
         }
         $package.= "</select>";
         return ($details_view) ? $this : $package ;
}

function client_select_box($id,$name="client_id",$display=NULL,$email_only=NULL)
{
         GLOBAL $dbh,$from,$details_view,$page,$tile,$this_user;
         if (!$dbh) dbconnect();
         $clients = "<select name=\"$name\">";
         $where = ($details_view&&$id) ? "WHERE client_id = '$id'" : NULL;
         $sql  = "SELECT client_id,client_fname,client_lname, client_email FROM client_info $where ORDER BY ";
         $sql .= ($email_only) ? "client_email" : "client_lname" ;
         $result = mysql_query($sql);
         $clients.= ($display) ? "<option value=0>".SELECT."</option>" : NULL ;
         while(list($this_client_id,$client_fname,$client_lname, $client_email) = mysql_fetch_row($result)) {
               $clients.= "<option value=\"$this_client_id\"";
               if ($id==$this_client_id) {
                   $clients.= " SELECTED ";
                   if ($details_view||$from=="client_id") {
                       if ($this_user) {
                           $this="$client_lname, $client_fname<input type=hidden name=client_id value=$this_client_id>";
                       } else {
                           $this="<a href=$page?op=client_details&db_table=client_info&tile=$tile&id=client_id|$this_client_id>$client_lname, $client_fname</a><input type=\"hidden\" name=\"$name\" value=\"$this_client_id\">";
                       }
                       break;
                   }
               }
               $this_select_option = ($email_only) ? $client_email : "$client_lname, $client_fname" ;
               $clients.= ($display) ? ">$this_select_option</option>\n" : ">$client_lname, $client_fname</option>\n" ;
         }
         $clients.= "</select>";
         return ($details_view||$from=="client_id") ? $this : $clients ;
}

function affiliate_select_box($id,$name="aff_code")
{
         GLOBAL $dbh,$details_view;
         if (!$dbh) dbconnect();
         $affiliates = "<select name=\"$name\">";
         $affiliates.= "<option value=0>".NONE."</option>";
         $where = ($details_view&&$id) ? "WHERE aff_code = '$id'" : NULL;
         $sql = "SELECT aff_id,aff_code FROM affiliate_config $where ORDER BY aff_code";
         $result = mysql_query($sql);
         while(list($aff_id,$aff_code) = mysql_fetch_row($result)) {
               $affiliates.= "<option value=\"$aff_code\"";
               if ($id == "$aff_code") {
                   $affiliates.= " SELECTED ";
                   if ($details_view) {
                       $this="<a href=$page?op=details&db_table=affiliate_config&tile=affiliate&&id=aff_id|$aff_id>$aff_code</a><input type=hidden name=\"$name\" value=\"$aff_code\">";
                       break;
                   }
               }
               $affiliates.= ">$aff_code</option>\n";
         }
         $affiliates.= "</select>";
         return ($details_view) ? $this : $affiliates ;
}

function admin_select_box($id,$name="admin_id")
{
         GLOBAL $dbh,$details_view,$this_admin;
         if (!$dbh) dbconnect();
         $admins = "<select name=\"$name\">";
         $where = ($details_view&&$id) ? "WHERE admin_id = '$id'" : NULL;
         $sql = "SELECT admin_id,admin_realname FROM admin $where ORDER BY admin_realname";
         $result = mysql_query($sql);
         $admin_id = ($admin_id) ? $admin_id : $this_admin[0] ;
         while(list($this_admin_id,$admin_realname) = mysql_fetch_row($result)) {
               $admins.= "<option value=\"$this_admin_id\"";
               if ($id==$this_admin_id) {
                   $admins.= " SELECTED ";
                   if ($details_view) {
                       $this="$admin_realname<input type=hidden name=admin_id value=$this_admin_id>";
                       break;
                   }
               }
               $admins.= ">$admin_realname</option>\n";
         }
         $admins.= "</select>";
         return ($details_view) ? $this : $admins ;
}

function tld_select_box($id)
{
         GLOBAL $dbh,$details_view;
         if (!$dbh) dbconnect();
         $domains = "<select name=\"tld_extension\">";
         $where = ($details_view&&$id) ? "tld_extension = '$id' AND" : NULL;
         $sql = "SELECT tld_extension FROM tld_config WHERE $where tld_accepted=2 ORDER BY tld_id";
         $result = mysql_query($sql);
         while(list($tld_extension) = mysql_fetch_row($result)) {
               $domains.= "<option value=\"$tld_extension\"";
               if ($id == "$tld_extension") {
                   $domains.= " SELECTED ";
                   if ($details_view) {
                       $this=$tld_extension;
                       break;
                   }
               }
               $domains.= ">.$tld_extension</option>\n";
         }
         $domains.= "</select>";
         return ($details_view) ? $this : $domains ;
}

## SELECT FROM DOMAIN_NAMES
function tld_price_select_box($domain_type,$domain_name,$domain_ext,$domain_years,$domain_price)
{
         GLOBAL $dbh,$details_view;
         if (!$dbh) dbconnect();
         $tlds = "<select name=domains[]>";
         $sql = "SELECT tld_transfer,tld_1y,tld_2y,tld_3y,tld_4y,tld_5y,tld_6y,tld_7y,tld_8y,tld_9y,tld_10y FROM tld_config WHERE tld_accepted=2 AND tld_extension='$domain_ext' ORDER BY tld_extension";
         list($tld_transfer,$tld_1y,$tld_2y,$tld_3y,$tld_4y,$tld_5y,$tld_6y,$tld_7y,$tld_8y,$tld_9y,$tld_10y) = mysql_fetch_array(mysql_query($sql,$dbh));

         if ( ( $domain_type == "transfer" ) && ( $tld_transfer == 0 ) ) {
                 $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|T|$tld_transfer\" ";
                 $tlds .= ($domain_years=="T") ? "SELECTED" : NULL;
                 $tlds .= ">".TRANSFER." @ ".FREE."</option>\n";
                 $this_tld = TRANSFER." @ ".FREE;
         } elseif ( $domain_type == "transfer" ) {
                 $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|T|$tld_transfer\" ";
                 $tlds .= ($domain_years=="T") ? "SELECTED" : NULL;
                 $tlds .= ">".TRANSFER." @ ".display_currency($tld_transfer)."</option>\n";
                 $this_tld = TRANSFER." @ ".display_currency($tld_transfer);
         } else {
              if ($tld_1y!=0) {
                  $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|1|$tld_1y\" ";
                  $tlds .= ($domain_years==1) ? "SELECTED" : NULL;
                  $tlds .= ">".ONEYEAR." @ ".display_currency($tld_1y)."</option>\n";
                  if ($domain_years==1) $this_tld = ONEYEAR." @ ".display_currency($tld_1y);
              }
              if ($tld_2y!=0) {
                  $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|2|$tld_2y\" ";
                  $tlds .= (!$domain_years || $domain_years==2) ? "SELECTED" : NULL;
                  $tlds .= ">".TWOYEARS." @ ".display_currency($tld_2y)."</option>\n";
                  if ($domain_years==2) $this_tld = TWOYEARS." @ ".display_currency($tld_2y);
              }
              if ($tld_3y!=0) {
                  $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|3|$tld_3y\" ";
                  $tlds .= ($domain_years==3) ? "SELECTED" : NULL;
                  $tlds .= ">".THREEYEARS." @ ".display_currency($tld_3y)."</option>\n";
                  if ($domain_years==3) $this_tld = THREEYEARS." @ ".display_currency($tld_3y);
              }
              if ($tld_4y!=0) {
                  $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|4|$tld_4y\" ";
                  $tlds .= ($domain_years==4) ? "SELECTED" : NULL;
                  $tlds .= ">".FOURYEARS." @ ".display_currency($tld_4y)."</option>\n";
                  if ($domain_years==4) $this_tld = FOURYEARS." @ ".display_currency($tld_4y);
              }
              if ($tld_5y!=0) {
                  $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|5|$tld_5y\" ";
                  $tlds .= ($domain_years==5) ? "SELECTED" : NULL;
                  $tlds .= ">".FIVEYEARS." @ ".display_currency($tld_5y)."</option>\n";
                  if ($domain_years==5) $this_tld = FIVEYEARS." @ ".display_currency($tld_5y);
              }
              if ($tld_6y!=0) {
                  $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|6|$tld_6y\" ";
                  $tlds .= ($domain_years==6) ? "SELECTED" : NULL;
                  $tlds .= ">".SIXYEARS." @ ".display_currency($tld_6y)."</option>\n";
                  if ($domain_years==6) $this_tld = SIXYEARS." @ ".display_currency($tld_6y);
              }
              if ($tld_7y!=0) {
                  $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|7|$tld_7y\" ";
                  $tlds .= ($domain_years==7) ? "SELECTED" : NULL;
                  $tlds .= ">".SEVENYEARS." @ ".display_currency($tld_7y)."</option>\n";
                  if ($domain_years==7) $this_tld = SEVENYEARS." @ ".display_currency($tld_7y);
              }
              if ($tld_8y!=0) {
                  $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|8|$tld_8y\" ";
                  $tlds .= ($domain_years==8) ? "SELECTED" : NULL;
                  $tlds .= ">".EIGHTYEARS." @ ".display_currency($tld_8y)."</option>\n";
                  if ($domain_years==8) $this_tld = EIGHTYEARS." @ ".display_currency($tld_8y);
              }
              if ($tld_9y!=0) {
                  $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|9|$tld_9y\" ";
                  $tlds .= ($domain_years==9) ? "SELECTED" : NULL;
                  $tlds .= ">".NINEYEARS." @ ".display_currency($tld_9y)."</option>\n";
                  if ($domain_years==9) $this_tld = NINEYEARS." @ ".display_currency($tld_9y);
              }
              if ($tld_10y!=0) {
                  $tlds .= "<option value=\"$domain_type|$domain_name|$domain_ext|10|$tld_10y\" ";
                  $tlds .= ($domain_years==10) ? "SELECTED" : NULL;
                  $tlds .= ">".TENYEARS." @ ".display_currency($tld_10y)."</option>\n";
                  if ($domain_years==10) $this_tld = TENYEARS." @ ".display_currency($tld_10y);
              }
         } #-> transfer
         $tlds.= "</select>";
         return ($details_view) ? $this_tld : $tlds ;
}

function domain_select_box($id=0,$name="domain_id",$switch=NULL,$client_id=NULL)
{
         GLOBAL $dbh,$details_view;
         if (!$dbh) dbconnect();
         $domains = "<select name=\"$name\">";
         $where = ($details_view&&$id) ? "WHERE domain_id = '$id'" : NULL;
         $where = ($client_id) ? "WHERE client_id = '$client_id'" : $where;
         $sql = "SELECT domain_id,client_id,domain_name FROM domain_names $where ORDER BY domain_name";
         $result = mysql_query($sql);
         $domains.= ($switch) ? "<option value=0>".SELECT."</option>" : "<option value=0>".NONE."</option>" ;
         while(list($domain_id,$client_id,$domain_name) = mysql_fetch_row($result)) {
               $domains.= ($switch) ? "<option value=\"$client_id\"" : "<option value=\"$domain_id\"" ;
               if ($id==$domain_id) {
                   $domains.= " SELECTED ";
                   if ($details_view) {
                       $this = $domain_name;
                       break;
                   }
               }
               $domains.= ">$domain_name</option>\n";
         }
         $domains.= "</select>";
         return ($details_view) ? $this : $domains ;
}
/*
function domain_select_box($id=0,$name="domain_id",$switch=NULL)
{
         GLOBAL $dbh,$details_view,$client_id;
         if (!$dbh) dbconnect();
         $domains = "<select name=\"$name\">";
         $where = ($details_view&&$id) ? "WHERE domain_id = '$id'" : NULL;
         $where = ($client_id) ? "WHERE client_id = '$client_id'" : $where;
         $sql = "SELECT domain_id,client_id,domain_name FROM domain_names $where ORDER BY domain_name";
         $result = mysql_query($sql);
         $domains.= ($switch) ? "<option value=0>".SELECT."</option>" : "<option value=0>".NONE."</option>" ;
         while(list($domain_id,$client_id,$domain_name) = mysql_fetch_row($result)) {
               $domains.= ($switch) ? "<option value=\"$client_id\"" : "<option value=\"$domain_id\"" ;
               if ($id==$domain_id) {
                   $domains.= " SELECTED ";
                   if ($details_view) {
                       $this = $domain_name;
                       break;
                   }
               }
               $domains.= ">$domain_name</option>\n";
         }
         $domains.= "</select>";
         return ($details_view) ? $this : $domains ;
}
*/

function faq_category_select_box($id,$name="cid")
{
         GLOBAL $op,$dbh,$details_view;
         if (!$dbh) dbconnect();
         $faq_categories = "<select name=\"$name\">";
         $where = ($details_view&&$id) ? "WHERE cid = '$id'" : NULL;
         $sql = "SELECT cid,cname FROM faq_categories $where ORDER BY cname";
         $result = mysql_query($sql);
         while(list($cid,$cname) = mysql_fetch_row($result)) {
               $faq_categories.= "<option value=\"$cid\"" ;
               if ($id==$cid) {
                   $faq_categories.= " SELECTED ";
                   if ($details_view) {
                       $this=$cname;
                       break;
                   }
               }
               $faq_categories.= ">$cname</option>\n";
         }
         $faq_categories.= "</select>";
         return ($details_view) ? $this : $faq_categories ;
}

function invoice_select_box($id,$name="invoice_id")
{
         GLOBAL $dbh,$details_view,$total_due;
         if (!$dbh) dbconnect();
         $invoices = "<select name=\"$name\">";
         $where    = ($details_view&&$id) ? "invoice_id = '$id' AND " : NULL;
         $sql      = "SELECT * FROM client_invoice WHERE $where invoice_amount > invoice_amount_paid";
         $result   = mysql_query($sql);
         while($this_invoice = mysql_fetch_array($result)) {
               $invoices.= "<option value=\"".$this_invoice[invoice_id]."\"" ;
               if ($id==$this_invoice[invoice_id]) {
                   $invoices.= " SELECTED ";
                   $total_due = $this_invoice[invoice_amount]-$this_invoice[invoice_amount_paid];
                   if ($details_view) {
                       $this=$this_invoice[invoice_id];
                       break;
                   }
               }
               $invoices.= ">".str_pad($this_invoice[invoice_id] , 10 , "_") .
                               str_pad(display_currency($this_invoice[invoice_amount]-$this_invoice[invoice_amount_paid]) , 15 , "_") .
                               str_pad(mysql_one_data("SELECT CONCAT_WS(\" \",client_lname,client_fname) FROM client_info WHERE client_id = $this_invoice[client_id]"), 30 , "_") .
                               str_pad(stamp_to_date($this_invoice[invoice_date_due])  , 12 , "_" , STR_PAD_LEFT)   ."</option>\n";
         }
         $invoices.= "</select>";
         return ($details_view) ? $this : $invoices ;
}

function support_select_box($id,$name="call_id")
{
         GLOBAL $dbh,$details_view;
         if (!$dbh) dbconnect();
         $support_call = "<select name=\"$name\">";
         $where = ($details_view&&$id) ? "WHERE call_id = '$id'" : NULL;
         $sql = "SELECT * FROM support_desk $where ORDER BY call_id";
         $result = mysql_query($sql);
         while($this_call = mysql_fetch_array($result)) {
               $support_call.= "<option value=\"$this_call[call_id]\"";
               if ($id==$this_call[call_id]) {
                   $support_call.= " SELECTED ";
                   if ($details_view) {
                       $this=$this_call[call_subject];
                       break;
                   }
               }
               $support_call.= ">".$this_call[call_subject]."</option>\n";
         }
         $support_call.= "</select>";
         return ($details_view) ? $this : $support_call ;
}
?>