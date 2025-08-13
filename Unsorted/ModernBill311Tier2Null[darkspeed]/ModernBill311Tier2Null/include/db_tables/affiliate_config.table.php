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

      $title = AFFILIATECONFIG;
      $parent = array(1=>"client_info");
      $args = array(array("column"         => "aff_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "client_id",
                           "required"      => 1,
                           "title"         => CLIENT,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => client_select_box($client_id),
                           "link_to_parent"=> 1,
                           "parent_op"     => "client_details"),
                    array("column"         => "aff_code",
                           "required"      => 0,
                           "title"         => AFFCODE,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 255,
                           "append"        => "<br>Define a UNIQUE affiliate code here.<Br>
                                                   Example: \"<b>123456</b>\"<br>
                                                   Usage: <font color=RED>/order/index.php?aid=123456</font>"),
                    array("column"         => "aff_hits",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => AFFHITS,
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 11),
                    array("column"         => "aff_count",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => AFFCOUNT,
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 11),
                    array("column"         => "aff_pay_time",
                           "required"      => 1,
                           "title"         => AFFPAYTIME,
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 11,
                           "append"        => "<br>How many days before the referral is paid?"),
                    array("column"         => "aff_pay_amount",
                           "required"      => 0,
                           "title"         => AFFPAYAMOUNT,
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 11,
                           "append"        => "<br>What is the payout percentage rate?<br>
                                                   Example: 10% = <b>.10</b>"),
                    array("column"         => "aff_pay_type",
                           "required"      => 0,
                           "title"         => AFFPAYTYPE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => affiliate_pay_type_select_box($aff_pay_type,"aff_pay_type")),
                    array("column"         => "aff_pay_cycle",
                           "required"      => 0,
                           "title"         => AFFPAYCYCLE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => affiliate_cycle_select_box($aff_pay_cycle,"aff_pay_cycle")),
                    array("column"         => "aff_pay_sum",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => AFFPAYSUM,
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 11),
                    array("column"         => "aff_status",
                           "required"      => 1,
                           "title"         => STATUS,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => status_select_box($aff_status,"aff_status")),
                    array("column"         => "aff_stamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($aff_stamp,"aff_stamp")));

      $select_sql = "SELECT * FROM $db_table ";

      $insert_sql = "INSERT INTO $db_table (aff_id,
                                            client_id,
                                            aff_code,
                                            aff_hits,
                                            aff_count,
                                            aff_pay_time,
                                            aff_pay_amount,
                                            aff_pay_type,
                                            aff_pay_cycle,
                                            aff_pay_sum,
                                            aff_status,
                                            aff_stamp) VALUES (NULL,
                                                               '$client_id',
                                                               '$aff_code',
                                                               '$aff_hits',
                                                               '$aff_count',
                                                               '$aff_pay_time',
                                                               '$aff_pay_amount',
                                                               '$aff_pay_type',
                                                               '$aff_pay_cycle',
                                                               '$aff_pay_sum',
                                                               '$aff_status',
                                                               '".mktime()."')";

      $update_sql = "UPDATE $db_table SET client_id='$client_id',
                                            aff_code='$aff_code',
                                            aff_pay_time='$aff_pay_time',
                                            aff_pay_amount='$aff_pay_amount',
                                            aff_pay_type='$aff_pay_type',
                                            aff_pay_cycle='$aff_pay_cycle',
                                            aff_status='$aff_status' WHERE aff_id='$aff_id'";

      $delete_sql = array("DELETE FROM $db_table WHERE aff_id='$aff_id'");
?>