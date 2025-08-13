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

        vortech_HTML_start(4);
        ?>
        <!--- SELECT ADD-ON PACKAGES --->
        <form method=post action="<?=$script_url_non_secure?>index.php?<?=session_name()."=".session_id()?>">
        <? if($error_msg) display_error($error_msg); ?>
        <?=display_step(5)?>
        <?=vortech_TABLE_start(PACKAGEADDONS)?>
        <table cellpadding=3 cellspacing=1 border=0  width=100%>
        <?
        foreach($cart[domains] as $key => $value)
        {
            list($register,$domain,$tld_extension,$domain_years,$domain_price) = $value; // ??
            echo "<tr>
                  <td bgcolor=$tablebgcolor width=35%><b>$key</b></td>
                  <td bgcolor=$tablebgcolor align=right><nobr>";
            $details_view = TRUE;
            echo tld_price_select_box($register,$domain,$tld_extension,$domain_years,$domain_price);
            $details_view = FALSE;
            echo " +</nobr></td></tr>";
            $total_domain += $domain_price;
        }
        foreach($cart[packages] as $key => $value)
        {
            list($pack_id,$pack_plan,$this_price,$this_setup) = $value;
            echo "<tr>
                  <td bgcolor=$tablebgcolor width=35%><b>".MAINPACKAGE."</b></td>
                  <td bgcolor=$tablebgcolor align=right><nobr>";
            $details_view = $child_package = TRUE;
            echo vortech_package_select_menu($pack_display,$pack_id,$pack_plan);
            $details_view = $child_package = FALSE;
            echo " +</nobr></td></tr>";
            $total_package_price += $this_price;
            $total_package_setup += $this_setup;
        }
        foreach($cart[packages] as $key => $value)
        {
             list($parent_pack_id,$billing_cycle) = $value;
             echo find_child_packages($parent_pack_id,$billing_cycle);
        }
        ?>
        <tr>
        <td bgcolor=<?=$tablebgcolor?> colspan=2 align=center>
        <?=SELECT2?>&nbsp;<input value="<?=CONTINUE_t?>" name=submit_show_total type=submit>&nbsp;<?=OR_t?>&nbsp;<input value="<?=GOBACK?>" name=submit_go_back type=submit>
        </td>
        </tr>
        </table>
        <?=vortech_TABLE_stop()?>
        </form>
        <?
        vortech_HTML_stop(4);
?>