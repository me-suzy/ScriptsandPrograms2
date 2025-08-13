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

      $title = COUPONCODES;
      $args = array(array("column"         => "coupon_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "coupon_code",
                           "required"      => 0,
                           "title"         => COUPONCODE,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255),
                    array("column"         => "coupon_percent_discount",
                           "required"      => 0,
                           "title"         => PRECENTDISCOUNT,
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 5,
                           "append"        => "(".EXAMPLE.": ".FREE." = 100% = 1.00, 50% = .50)<br>".PERCORDOLLARNOTBOTH.")"),
                    array("column"         => "coupon_dollar_discount",
                           "required"      => 0,
                           "title"         => DOLLARDISCOUNT,
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 10,
                           "append"        => "(".EXAMPLE.": \$10 = 10.00)<br>".PERCORDOLLARNOTBOTH.")"),
                    array("column"         => "coupon_comments",
                           "required"      => 0,
                           "title"         => COMMENTS,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "nl2br"         => 1,
                           "cuttext"       => 15),
                    array("column"         => "coupon_status",
                           "required"      => 1,
                           "title"         => STATUS,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => status_select_box($coupon_status,"coupon_status")),
                    array("column"         => "coupon_start_stamp",
                           "required"      => 1,
                           "title"         => STARTDATE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($coupon_start_stamp,"coupon_start_stamp",$default_date)),
                    array("column"         => "coupon_end_stamp",
                           "required"      => 1,
                           "title"         => ENDDATE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($coupon_end_stamp,"coupon_end_stamp",$default_date)),
                    array("column"         => "coupon_expire_string",
                           "required"      => 0,
                           "title"         => EXPIREDTEXT,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>(".EXAMPLE.": ".IMSORRYCOUPONEXPIRED.")"),
                    array("column"         => "coupon_count",
                           "required"      => 0,
                           "title"         => COUPONCOUNT,
                           "type"          => "TEXT",
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "size"          => 40,
                           "maxlength"     => 255),
                    array("column"         => "coupon_max_count",
                           "required"      => 0,
                           "title"         => MAXREDEMPTIONS,
                           "type"          => "TEXT",
                           "size"          => 11,
                           "maxlength"     => 11,
                           "append"        => "<br>".MAXNUMBEROFREDEMPTIONS."<br>"),
                    array("column"         => "coupon_new_only",
                           "required"      => 0,
                           "title"         => NEWCLIENTSONLY,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("coupon_new_only",$coupon_new_only),
                           "append"        => "<br>".VALIDONLYFORNEW."<br>"),
                    array("column"         => "coupon_misc1",
                           "required"      => 0,
                           "title"         => REACCURRING,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("coupon_misc1",$coupon_misc1),
                           "append"        => "<br>".APPLYFOREVEYRENEWAL."<br>"),
                    array("column"         => "coupon_misc2",
                           "required"      => 0,
                           "title"         => NOTUSED,
                           "type"          => "TEXT",
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "size"          => 1,
                           "maxlength"     => 1));

      if($submit||$do=="edit"||$do=="add")
      {
         if (!date_check($coupon_start_stamp)) $oops .= "[".ERROR."] ".STARTDATEINVALID."<br>";
         if (!date_check($coupon_end_stamp))   $oops .= "[".ERROR."] ".ENDDATEINVALID."<br>";
         $coupon_start_stamp = (date_check($coupon_start_stamp)) ? date_to_stamp($coupon_start_stamp) : 0 ;
         $coupon_end_stamp   = (date_check($coupon_end_stamp))   ? date_to_stamp($coupon_end_stamp) : 0 ;
      }

      $select_sql = "SELECT coupon_id,
                            coupon_code,
                            coupon_percent_discount,
                            coupon_dollar_discount,
                            coupon_start_stamp,
                            coupon_end_stamp,
                            coupon_count,
                            coupon_max_count,
                            coupon_new_only,
                            coupon_status FROM $db_table ";

      $insert_sql = "INSERT INTO $db_table (coupon_id,
                                            coupon_code,
                                            coupon_percent_discount,
                                            coupon_dollar_discount,
                                            coupon_comments,
                                            coupon_status,
                                            coupon_start_stamp,
                                            coupon_end_stamp,
                                            coupon_expire_string,
                                            coupon_count,
                                            coupon_max_count,
                                            coupon_new_only,
                                            coupon_misc1,
                                            coupon_misc2) VALUES (NULL,
                                                                  '$coupon_code',
                                                                  '$coupon_percent_discount',
                                                                  '$coupon_dollar_discount',
                                                                  '$coupon_comments',
                                                                  '$coupon_status',
                                                                  '$coupon_start_stamp',
                                                                  '$coupon_end_stamp',
                                                                  '$coupon_expire_string',
                                                                  '$coupon_count',
                                                                  '$coupon_max_count',
                                                                  '$coupon_new_only',
                                                                  '$coupon_misc1',
                                                                  '$coupon_misc2')";

    $update_sql = "UPDATE $db_table SET coupon_code='$coupon_code',
                                        coupon_percent_discount='$coupon_percent_discount',
                                        coupon_dollar_discount='$coupon_dollar_discount',
                                        coupon_comments='$coupon_comments',
                                        coupon_status='$coupon_status',
                                        coupon_start_stamp='$coupon_start_stamp',
                                        coupon_end_stamp='$coupon_end_stamp',
                                        coupon_expire_string='$coupon_expire_string',
                                        coupon_count='$coupon_count',
                                        coupon_max_count='$coupon_max_count',
                                        coupon_new_only='$coupon_new_only',
                                        coupon_misc1='$coupon_misc1',
                                        coupon_misc2='$coupon_misc2'
                                    WHERE coupon_id='$coupon_id' ";

      $delete_sql = array("DELETE FROM $db_table WHERE coupon_id='$coupon_id'");
?>