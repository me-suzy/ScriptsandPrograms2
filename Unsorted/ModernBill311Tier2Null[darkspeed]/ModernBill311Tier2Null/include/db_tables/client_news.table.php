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
/*
CREATE TABLE client_news (
   bigint(255) NOT NULL default '0',
   text NOT NULL,
  Post_user text NOT NULL,
  Post_email text NOT NULL,
  Date text NOT NULL,
  Time text NOT NULL,
  Headline_date text NOT NULL,
  Date_time text NOT NULL,
  Text text NOT NULL,
  Modify_date text NOT NULL,
  Modify_user text NOT NULL,
  mainpage enum('N','Y') NOT NULL default 'N',
  mainid int(255) NOT NULL default '0'
) TYPE=MyISAM;
*/
 /* ----------------- CLIENT_NEWS ---------------------*/
      $title = NEWS;
      $args = array(array("column"         => "ID",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),

                    array("type"           => "HEADERROW",
                           "title"         => CLIENTNEWS),

                    array("column"         => "Subject",
                           "required"      => 1,
                           "title"         => SUBJECT,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255),

                    array("column"         => "pack_id",
                           "required"      => 1,
                           "title"         => PACKAGE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => package_select_box($pack_id,$cp_billing_cycle)." ".map_domains($cp_id,1),
                           "link_to_parent"=> 2),

                    array("column"         => "pack_price",
                           "required"      => 0,
                           "title"         => PRICEOVERRIDE,
                           "type"          => "TEXT",
                           "size"          => 7,
                           "maxlength"     => 11,
                           "default_value" => "0.00",
                           "append"        => "(".OVERRIDEPRICETEXT.")"),
                    array("column"         => "parent_cp_id",
                           "required"      => 0,
                           "title"         => PARENT,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => cp_select_menu($client_id,$parent_cp_id),
                           "link_to_parent"=> 3),
                    array("column"         => "cp_qty",
                           "required"      => 1,
                           "title"         => QTY,
                           "type"          => "TEXT",
                           "size"          => 3,
                           "maxlength"     => 5,
                           "default_value" => "1",
                           "append"        => "(".QUANTITY.")"),
                    array("column"         => "cp_discount",
                           "required"      => 1,
                           "title"         => DISCOUNT,
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 5,
                           "default_value" => PRICEFORMAT,
                           "append"        => "(.00 ".ASPERCENTAGE.")"),
                    array("column"         => "cp_start_stamp",
                           "required"      => 1,
                           "title"         => STARTDATE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($cp_start_stamp,"cp_start_stamp",stamp_to_date(mktime())),
                           "append"        => "(".DATEFORMAT2.")"),
                    array("column"         => "cp_renew_stamp",
                           "required"      => 1,
                           "title"         => RENEWDATE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($cp_renew_stamp,"cp_renew_stamp",stamp_to_date(mktime(0,0,0,date("m")+1,1,date("Y")))),
                           "append"        => "(".DATEFORMAT3.")"),
                    array("column"         => "cp_renewed_on",
                           "required"      => 0,
                           "no_edit"       => 0,
                           "title"         => RENEWON,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($cp_renewed_on,"cp_renewed_on"),
                           "append"        => "(".AUTOUPDATED.")"),
                    array("column"         => "cp_billing_cycle",
                           "required"      => 1,
                           "title"         => BILLINGCYCLE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => cycle_select_box($cp_billing_cycle)),
                    array("column"         => "cp_status",
                           "required"      => 1,
                           "title"         => STATUS,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => status_select_box($cp_status,"cp_status")),
                    array("column"         => "cp_comments",
                           "required"      => 0,
                           "title"         => COMMENTS,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap),
                    array("column"         => "cp_stamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($cp_stamp,"cp_stamp")),
                    array("column"         => "aff_code",
                           "required"      => 0,
                           "admin_only"    => 1,
                           "title"         => AFFCODE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => affiliate_select_box($aff_code),
                           "link_to_parent"=> 4),
                    array("column"         => "aff_last_paid",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "admin_only"    => 1,
                           "title"         => AFFPAID,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($aff_last_paid,"aff_last_paid")));

      if($submit||$do=="edit"||$do=="add"){
         if (!date_check($cp_start_stamp)) $oops .= "[".ERROR."] ".STARTDATEINVALID."<br>";
         if (!date_check($cp_renew_stamp)) $oops .= "[".ERROR."] ".RENEWDATEINVALID."<br>";
         if ($cp_renewed_on&&!date_check($cp_renewed_on)) $oops .= "[".ERROR."] ".RENEWONINVALID."<br>";
         $raw_cp_start_stamp = (date_check($cp_start_stamp)) ? date_to_stamp($cp_start_stamp) : 0 ;
         $raw_cp_renew_stamp = (date_check($cp_renew_stamp)) ? date_to_stamp($cp_renew_stamp) : 0 ;
         $raw_cp_renewed_on  = (date_check($cp_renewed_on))  ? date_to_stamp($cp_renewed_on)  : 0 ;
      }
      $select_sql = "SELECT cp_id, client_id, pack_id, cp_start_stamp, cp_renew_stamp, cp_renewed_on, cp_billing_cycle, cp_status, aff_code, aff_last_paid FROM $db_table ";

   $recursive_sql = "SELECT cp_id, pack_id, pack_price, cp_qty, cp_discount, cp_start_stamp, cp_renew_stamp, cp_renewed_on, cp_billing_cycle, cp_status, aff_code, aff_last_paid FROM $db_table ";

 $user_select_sql = "SELECT cp_id, pack_id, pack_price, cp_qty, cp_discount, cp_start_stamp, cp_renew_stamp, cp_renewed_on, cp_billing_cycle, cp_status FROM $db_table ";

      $insert_sql = "INSERT INTO $db_table (cp_id,
                                            client_id,
                                            pack_id,
                                            pack_price,
                                            parent_cp_id,
                                            cp_qty,
                                            cp_discount,
                                            cp_start_stamp,
                                            cp_renew_stamp,
                                            cp_billing_cycle,
                                            cp_status,
                                            cp_comments,
                                            cp_renewed_on,
                                            cp_stamp,
                                            aff_code,
                                            aff_last_paid) VALUES (NULL,
                                                              '$client_id',
                                                              '$pack_id',
                                                              '$pack_price',
                                                              '$parent_cp_id',
                                                              '$cp_qty',
                                                              '$cp_discount',
                                                              '$raw_cp_start_stamp',
                                                              '$raw_cp_renew_stamp',
                                                              '$cp_billing_cycle',
                                                              '$cp_status',
                                                              '$cp_comments',
                                                              '$raw_cp_renewed_on',
                                                              '".mktime()."',
                                                              '$aff_code',
                                                              '$aff_last_paid')";

      $update_sql = "UPDATE $db_table
                     SET client_id='$client_id',
                         pack_id='$pack_id',
                         pack_price='$pack_price',
                         parent_cp_id='$parent_cp_id',
                         cp_qty='$cp_qty',
                         cp_discount='$cp_discount',
                         cp_start_stamp='$raw_cp_start_stamp',
                         cp_renew_stamp='$raw_cp_renew_stamp',
                         cp_billing_cycle='$cp_billing_cycle',
                         cp_status='$cp_status',
                         cp_comments='$cp_comments',
                         cp_renewed_on='$raw_cp_renewed_on',
                         cp_stamp='".mktime()."',
                         aff_code='$aff_code' WHERE cp_id='$cp_id'";

      $delete_sql = array("DELETE FROM client_package WHERE cp_id='$cp_id'",
                          "DELETE FROM account_details WHERE cp_id='$cp_id'",
                          "DELETE FROM account_pops WHERE cp_id='$cp_id'",
                          "DELETE FROM account_dbs WHERE cp_id='$cp_id'");
?>