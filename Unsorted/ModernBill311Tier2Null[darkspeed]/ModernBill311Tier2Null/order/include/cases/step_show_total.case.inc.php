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

        vortech_HTML_start(5);
        ?>
        <!------ CALCULATE TOTALS ------>
        <form method=post action="<?=$script_url?>index.php?<?=session_name()."=".session_id()?>">
        <? if($error_msg) display_error($error_msg); ?>
        <?=display_step(7)?>
        <? if (!$cart[coupons][0]) { ?>
        <?=vortech_TABLE_start(HAVEACOUPON)?>
        <table cellpadding=3 cellspacing=1 border=0  width=100%>
        <td bgcolor=<?=$tablebgcolor?> align=center valign=bottom>
        <input type=text name=coupon_code size=20 maxlength=100>&nbsp;<input value="<?=APPLYCOUPON?>" name=submit_coupon type=submit>
        </td>
        </tr>
        </table>
        <?=vortech_TABLE_stop()?>
        <br>
        <? } ?>
        <?=display_cart_no_output()?>
        <br>
        <?=vortech_TABLE_start(CONTACTINFO)?>
        <table cellpadding=3 cellspacing=1 border=0 width=100%>
        <tr><td bgcolor=<?=$tablebgcolor?> colspan=2 align=center>
            <b>-- <?=NEWCUSTOMERS?> --</b><br><i><?=NEWCUSTTEXT?></i>
                  <table width=90%>
                  <tr><td align=right width=35%><?=EMAIL?></td><td><input type=text name=email size=20 maxlength=200></td></tr>
                  <tr><td align=right><?=VALIDATEEMAIL?></td><td><input type=text name=validate_email size=20 maxlength=200></td></tr>
                  <tr><td colspan=2 align=center><input type=submit value="<?=IAMANEWCUSTOMER?>" name=submit_new></td></tr></td></tr>
                  </table>
            </td>
        </tr>
        <? if (!$cart[coupons][0] || ($cart[coupons][0] && $cart[coupons][0][11]!="1")) {  ?>
        <tr><td colspan=2 bgcolor=<?=$tablebgcolor?>><hr size=1></td></tr>
        <tr><td bgcolor=<?=$tablebgcolor?> colspan=2 align=center>
            <b>-- <?=EXISTINGCUSTOMERS?> --</b><br><i><?=EXISTCUSTTEXT?></i>
                  <table width=90%>
                  <tr><td align=right width=35%><?=EMAIL?></td><td><input type=text name=username size=20 maxlength=200></td></tr>
                  <tr><td align=right><?=PASSWORD_t?></td><td><input type=password name=password size=20 maxlength=200></td></tr>
                  <tr><td colspan=2 align=center><input value="<?=ADDTOMYACCOUNT?>" type=submit name=submit_add></td></tr>
                  </table>
            </td>
        </tr>
        <? } ?>
        </table>
        <?=vortech_TABLE_stop()?>
        </form>
        <?
        vortech_HTML_stop(5);
?>