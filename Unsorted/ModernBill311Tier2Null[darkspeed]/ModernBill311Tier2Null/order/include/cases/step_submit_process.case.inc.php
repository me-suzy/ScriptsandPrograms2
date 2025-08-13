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

        session_register('order_completed');
        if ($order_completed) {
            vortech_HTML_start(7);
            display_error(DUPLICATEORDER);
            vortech_HTML_stop(7);
            if (!$debug) { exit; }
        }
        $x_Amount = $cart[order_total];
        if (isset($cart[coupons][0][0])) {
            $sql = "UPDATE coupon_codes SET coupon_count=coupon_count+1 WHERE coupon_id = ".$cart[coupons][0][0]."";
            if ($debug) { echo "coupon: $sql"; }
            $result = mysql_query($sql);
        }
        $z = 0;
        if ( (!$account_addon) && ( $tier2 && $allow_signup_charge && ( $pay_method=="echeck" || $pay_method=="creditcard" ) ) )
        {
           if ($authnet_enabled)
               include("include/authnet.inc.php");
           elseif ($echo_enabled)
               include("include/echo.inc.php");
           elseif ($linkpoint_enabled)
               include("include/linkpount.inc.php");
           elseif ($checkout_enabled)
               include("include/2checkout.inc.php");
        }
        if ($z == 0)
        {
           # --> Insert New Clients in the ModernBill DB
           if ($insert_new_clients) include("include/insert.inc.php");

           # --> PayPal Amounts
           $pp_amount = $invoice_amount;
           $pp_item_number = $invoice_id;

           # --> Send Signup Emails
           include("include/signup_email.inc.php");
           if ($allow_email_to_client)
           {
               $body = ($use_html_signup_email) ? custom_nl2br(MFB.$this_email_heading."\n".$this_email_body."\n".$this_email_footer."\n--\n".$this_email_signature.EF) :
                                                $this_email_heading."\n".$this_email_body."\n".$this_email_footer."\n--\r\n".$this_email_signature ;
               @mail($x_Email,stripslashes($subject),stripslashes(str_replace("{DISPLAYCARTHERE}",display_cart_no_output(),$body)),$headers);
           }
           if ($debug) echo custom_nl2br($this_email_heading."\n".$this_email_body."\n".$this_email_footer."\n".$this_email_signature);
           $body = ($use_html_signup_email) ? custom_nl2br(SFB.$message.$message_admin.EF) : $message.$message_admin ;
           @mail($order_email,stripslashes($subject),stripslashes(str_replace("{DISPLAYCARTHERE}",display_cart_no_output(),$body)),$headers);

           # --> Stop the refreshers :)
           $order_completed = TRUE;
        }
        # --> PayPal Amounts [FIX ME -- Am I needed here too?]
        $pp_amount = $invoice_amount;
        $pp_item_number = $invoice_id;
        vortech_HTML_start(8);
        ?>
        <?=display_step(9)?>
        <?=display_cart_no_output()?>
        <br>
        <?=vortech_TABLE_start(ORDERCRESULTS)?>
        <table cellpadding=3 cellspacing=1 border=0 width=100%>
        <tr><td colspan=2 bgcolor=<?=$tablebgcolor?> align=center>
        <?
        switch ($pay_method) {

           case account_addon:
                echo THISORDERADDED."<br><a href=$user_login_url target=_blank>$user_login_url</a><br><br>".nl2br($company_address)."<br><br>" ;
                if ($allow_paypal&&$paypal_enabled&&$tier2) {
                    echo "<br>".PAYWITHPAYPAL."<br>".generate_paypal_link($pp_item_name,$pp_item_number,$pp_amount,"button")."<br><br>";
                }
                if ($allow_worldpay&&$worldpay_enabled&&$tier2) {
                    echo "<br>".generate_worldpay_link($pp_item_name,$pp_item_number,$pp_amount,"button")."<br><br>";
                }
           break;

           case creditcard:
                echo $cc_result.$error_msg."<br><br>";
           break;

           case echeck:
                echo $cc_result.$error_msg."<br><br>";
           break;

           case paypal:
                if ($allow_paypal&&$paypal_enabled&&$tier2) {
                    echo "<br>".PAYWITHPAYPAL."<br>".generate_paypal_link($pp_item_name,$pp_item_number,$pp_amount,"button")."<br><br>";
                }
           break;

           case worldpay:
                if ($allow_worldpay&&$worldpay_enabled&&$tier2) {
                    echo "<br>".generate_worldpay_link($pp_item_name,$pp_item_number,$pp_amount,"button")."<br><br>";
                }
           break;

           default:
                echo SENDPAYMENTTO.":<br>".nl2br($company_address)."<br><br>";
           break;
        }
        ?>
        </td></tr>
        </table>
        <?=vortech_TABLE_stop()?>
        <br>
        <?
        vortech_HTML_stop(8);
?>
