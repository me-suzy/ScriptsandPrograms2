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

        vortech_HTML_start(3);
        ?>
        <!------- SELECT PACKAGES ------>
        <form method=post action="<?=$script_url_non_secure?>index.php?<?=session_name()."=".session_id()?>">
        <? if($error_msg) display_error($error_msg); ?>
        <?=display_step(4)?>
        <?=vortech_TABLE_start(BUILDPACKAGE)?>
        <table cellpadding=3 cellspacing=1 border=0  width=100%>
        <?
        foreach($cart[domains] as $key => $value)
        {
            list($register,$domain,$tld_extension,$domain_years,$domain_price) = $value; // ??
            echo "<tr>
                  <td bgcolor=$tablebgcolor width=35%><b>$key</b></td>
                  <td bgcolor=$tablebgcolor>";
            echo tld_price_select_box($register,$domain,$tld_extension,$domain_years,$domain_price);
            echo "</td></tr>";
            $domain_ordered = TRUE;
        }
        //list($pack_id,$pack_plan,$this_price,$this_setup) = $cart[packages]; // ??
        foreach($cart[packages] as $key => $value)
        {
            list($pack_id,$pack_plan) = $value;
            break;
        }
        $details_view = $child_package = ($pack_id||$pack_plan) ? FALSE : FALSE ;
        $align = ($details_view) ? "RIGHT" : "LEFT" ;
        ?>
          <?
          // Split Display Logic to Support v2 Style
          if ($package_menu_display_type==3) {
          ?>
                <tr><td bgcolor=<?=$tablebgcolor?>><nobr><b><?=SELECTPACKAGE?></b></nobr></td><td bgcolor=<?=$tablebgcolor?> align=<?=$align?>>
                <?
                echo vortech_package_select_menu($pack_display,$pack_id,$pack_plan);
                $details_view = $child_package = ($pack_id||$pack_plan) ? FALSE : TRUE ;
                ?>
                </td></tr>
                <tr><td bgcolor=<?=$tablebgcolor?>><nobr><b><?=BILLINGCYCLE?></b></nobr></td><td bgcolor=<?=$tablebgcolor?> align=<?=$align?>>
                <?
                $package_select_menu_3 .= "<select name=type3_plan><br><br>";
                if ($allow_monthly) {
                $package_select_menu_3 .= "<option value=\"1\" ";
                $package_select_menu_3 .= ($pack_plan == 1) ? "SELECTED" : NULL ;
                $package_select_menu_3 .= ">".PAY." $monthly_name</option>";
                $is_plan_selected = TRUE;
                }
                if ($allow_quarterly) {
                $package_select_menu_3 .= "<option value=\"3\" ";
                $package_select_menu_3 .= ($pack_plan == 3) ? "SELECTED" : NULL ;
                $package_select_menu_3 .= ">".PAY." $quarterly_name</option>";
                $is_plan_selected = TRUE;
                }
                if ($allow_semiannual) {
                $package_select_menu_3 .= "<option value=\"6\" ";
                $package_select_menu_3 .= ($pack_plan == 6) ? "SELECTED" : NULL ;
                $package_select_menu_3 .= ">".PAY." $semiannual_name</option>";
                $is_plan_selected = TRUE;
                }
                if ($allow_annual) {
                $package_select_menu_3 .= "<option value=\"12\" ";
                $package_select_menu_3 .= ($pack_plan == 12) ? "SELECTED" : NULL ;
                $package_select_menu_3 .= ">".PAY." $annual_name</option>";
                $is_plan_selected = TRUE;
                }
                if ($allow_xyear) {
                $package_select_menu_3 .= "<option value=\"24\" ";
                $package_select_menu_3 .= ($pack_plan == 24) ? "SELECTED" : NULL ;
                $package_select_menu_3 .= ">".PAY." $xyear_name</option>";
                $is_plan_selected = TRUE;
                }
                if (!$is_plan_selected) {
                $package_select_menu_3 .= "<option value=\"1\" ";
                $package_select_menu_3 .= ($pack_plan == 1) ? "SELECTED" : NULL ;
                $package_select_menu_3 .= ">".PAY." $monthly_name</option>";
                }
                $package_select_menu_3 .= "</select><br>";
                echo $package_select_menu_3;
                ?>
        <? } else { ?>
                <tr><td bgcolor=<?=$tablebgcolor?>><nobr><b><?=SELECTPACKAGE?></b></nobr></td><td bgcolor=<?=$tablebgcolor?> align=<?=$align?>>
                <?
                echo vortech_package_select_menu($pack_display,$pack_id,$pack_plan);
                $details_view = $child_package = ($pack_id||$pack_plan) ? FALSE : TRUE ;
                ?>
                </td></tr>
        <? } ?>
        <tr>
        <td bgcolor=<?=$tablebgcolor?> colspan=2 align=center>
        <?=SELECT2?>&nbsp;<input value="<?=CONTINUE_t?>" name=submit_addon_select type=submit>&nbsp;<?=OR_t?>&nbsp;<input value="<?=SEARCHAGAIN?>" name=submit_search_again type=submit>
        <? if (!$display_package_comparisons) { ?>
        <br>
        <br>
        <a href=compare.php target=_blank><?=COMPAREPACAKGES?></a>
        <? } ?>
        </td>
        </tr>
        </table>
        <?=vortech_TABLE_stop()?>
        </form>

<? if ($display_package_types) { ?>
        <table cellpadding=0 cellspacing=0 border=0 bgcolor=<?=$outerborder?> align=center width=100%>
        <tr>
        <td>
        <table cellpadding=3 cellspacing=1 border=0 width=100%>
        <tr bgcolor=<?=$headercolor?>>
        <td align=center><?=SFB?><font color=<?=$headertextcolor?>><b><?=PACKAGES?></b></font><?=EF?></td>
        <td align=center width=20%><?=SFB?><font color=<?=$headertextcolor?>><b><?=PRICE?></b></font><?=EF?></td>
        <td align=center width=20%><?=SFB?><font color=<?=$headertextcolor?>><b><?=SETUP?></b></font><?=EF?></td>
        </tr>
        <?
        ## MAIN PACKAGES
        $result = mysql_query("SELECT pack_id,
                                    pack_name,
                                    pack_price,
                                    pack_setup
                             FROM package_type
                             WHERE pack_status=2
                             AND pack_display=$pack_display
                             ORDER BY pack_price DESC");
        while(list($pack_id,$pack_name,$pack_price,$pack_setup) = mysql_fetch_array($result))
        {
            ?>
            <tr>
             <td bgcolor=<?=$tablebgcolor?> align=left><nobr>&nbsp;<?=SFB?><b><?=$pack_name?></b><?=EF?></nobr></td>
             <td bgcolor=<?=$tablebgcolor?> align=center><nobr><?=SFB?><?=display_currency(split_price($pack_price,1,"price"))?>/<?=MONTHLY?><?=EF?></nobr></td>
             <td bgcolor=<?=$tablebgcolor?> align=center><nobr><?=SFB?><?=display_currency(split_price($pack_setup,1,"setup"))?>/<?=ONETIME?><?=EF?></nobr></td>
            </tr>
            <?
            $num = mysql_one_data("SELECT count(pack_id) FROM package_feature WHERE pack_id = $pack_id");

            if($num>0)
            {
                echo "<tr><td colspan=3 bgcolor=$tablebgcolor>";
                ## PACKAGE FEATURES
                $result2 = mysql_query("SELECT * FROM package_feature WHERE pack_id = $pack_id ORDER BY feature_name");
                while($this_pack_feature = mysql_fetch_array($result2))
                // feature_id, pack_id, feature_name, feature_comments
                {
                ?>
                    <?=SFB?><li><b><?=$this_pack_feature[feature_name]?>:</b> <?=$this_pack_feature[feature_comments]?><?=EF?><br>
                <?
                }
                echo "</td></tr>";
            }
        }
        ?>
        </table>
        </td>
        </tr>
        </table>
        <br>
<? } /* DISPLAY PACKAGES 1 */ ?>

<? if ($display_package_comparisons) { ?>
        <? /* DISPLAY PACKAGES 2 */ ?>
        <table cellpadding=0 cellspacing=0 border=0 bgcolor=<?=$outerborder?> align=center width=100%>
        <tr>
        <td>
        <table cellpadding=3 cellspacing=1 border=0 width=100%>
         <tr>
           <td bgcolor=<?=$headercolor?> align=center valign=bottom><?=SFB?><font color=<?=$headertextcolor?>><b><?=FEATURES?></b></font><?=EF?></td>
        <?
        ## MAIN PACKAGES
        $unsorted_array = array();
        $result = mysql_query("SELECT pack_id, pack_price
                               FROM package_type
                               WHERE pack_status=2
                               AND pack_display=$pack_display");
        while(list($pack_id,$pack_price) = mysql_fetch_array($result))
        {
            $unsorted_array[$pack_id] = display_currency($pack_price,1);
        }
        asort ($unsorted_array);
        reset ($unsorted_array);
        while(list($pack_id,$pack_price) = each ($unsorted_array))
        {
                $result = mysql_query("SELECT pack_id,
                                            pack_name,
                                            pack_price,
                                            pack_setup
                                     FROM package_type
                                     WHERE pack_id = $pack_id");
                while(list($pack_id,$pack_name,$pack_price,$pack_setup) = mysql_fetch_array($result))
                {
                    $package_array[] = $pack_id;
                    ?>
                     <td bgcolor=<?=$headercolor?> align=center>
                         <nobr><?=SFB?><font color=<?=$headertextcolor?>><b><?=$pack_name?></b></font><?=EF?></nobr>
                         <? if (!$suppress_price_from_comparison) { ?>
                            <br>
                            <nobr><?=SFB.PRICE?>: <?=display_currency(split_price($pack_price,"price",1))?>/<?=MONTHLY?><?=EF?></nobr><br>
                            <nobr><?=SFB.SETUP?>: <?=display_currency(split_price($pack_setup,"setup",1))?>/<?=ONETIME?><?=EF?></nobr>
                         <? } ?>
                     </td>

                    <?
                }
        }
        ?>
        </tr>
        <?
                ## PACKAGE FEATURES
                for($i=0;$i<=count($package_array)-1;$i++)
                    $where .= " pack_id = $package_array[$i] OR";

                $where = substr($where, 0, -3);

                $sql = "SELECT DISTINCT(feature_name) FROM package_feature WHERE ($where)";
                $result2 = mysql_query($sql);
                if(!$result2){ echo ERROR; }
                while(list($feature_name) = mysql_fetch_array($result2))
                {
                ?>
                    <tr>
                     <td bgcolor=<?=$tablebgcolor?> align=left><nobr><?=SFB?><b><?=$feature_name?>:</b><?=EF?></nobr></td>
                     <?
                     for($i=0;$i<=count($package_array)-1;$i++){
                         $feature_comments = mysql_one_data("SELECT feature_comments FROM package_feature WHERE pack_id = $package_array[$i] AND feature_name LIKE '".addslashes($feature_name)."'");
                         ?>
                         <td bgcolor=<?=$tablebgcolor?> align=center><?=SFB?><?=$feature_comments?><?=EF?></td>
                         <?
                     }
                     ?>
                    </tr>
                <?
                }
                ?>
                <tr>
                <td bgcolor=<?=$headercolor?> align=center><nobr><?=SFB?><font color=<?=$headertextcolor?>><b><?=BILLINGCYCLE?></b></font><?=EF?></nobr></td>
                <?
                for($i=0;$i<=count($package_array)-1;$i++){
                    ?>
                    <td bgcolor=<?=$headercolor?> align=center><nobr><?=SFB?><font color=<?=$headertextcolor?>><b><?=PRICE?></b></font><?=EF?></nobr></td>
                    <?
                }
                ?>
                </tr>
                <?
                if ($allow_monthly)
                {
                ?>
                    <tr>
                     <td bgcolor=<?=$tablebgcolor?> align=left><nobr><?=SFB?><b><?=$monthly_name?>:<br>+ <?=SETUP?>:</b><?=EF?></nobr></td>
                     <?
                     for($i=0;$i<=count($package_array)-1;$i++) {
                         list($pack_price,$pack_setup) = mysql_fetch_array(mysql_query("SELECT pack_price,pack_setup FROM package_type WHERE pack_id = $package_array[$i]"));
                         ?>
                         <td bgcolor=<?=$tablebgcolor?> align=center>
                         <? if (split_price($pack_price,"price",1)==0) { ?>
                         <?=NA?>
                         <? } else { ?>
                         <nobr><b><?=display_currency(split_price($pack_price,"price",1))?></b> / <?=MONTHLY?><?=EF?></nobr><br>
                         <nobr><?=display_currency(split_price($pack_setup,"setup",1))?> / <?=ONETIME?><?=EF?></nobr>
                         <? } ?>
                         </td>
                         <?
                     }
                     ?>
                    </tr>
                <?
                }
                if ($allow_quarterly)
                {
                ?>
                    <tr>
                     <td bgcolor=<?=$tablebgcolor?> align=left><nobr><?=SFB?><b><?=$quarterly_name?>:<br>+ <?=SETUP?>:</b><?=EF?></nobr></td>
                     <?
                     for($i=0;$i<=count($package_array)-1;$i++) {
                         list($pack_price,$pack_setup) = mysql_fetch_array(mysql_query("SELECT pack_price,pack_setup FROM package_type WHERE pack_id = $package_array[$i]"));
                         ?>
                         <td bgcolor=<?=$tablebgcolor?> align=center>
                         <? if (split_price($pack_price,"price",3)==0) { ?>
                         <?=NA?>
                         <? } else { ?>
                         <nobr><b><?=display_currency(split_price($pack_price,"price",3))?></b> / <?=MONTHLY?><?=EF?></nobr><br>
                         <nobr><?=display_currency(split_price($pack_setup,"setup",3))?> / <?=ONETIME?><?=EF?></nobr>
                         <? } ?>
                         </td>
                         <?
                     }
                     ?>
                    </tr>
                <?
                }
                if ($allow_semiannual)
                {
                ?>
                    <tr>
                     <td bgcolor=<?=$tablebgcolor?> align=left><nobr><?=SFB?><b><?=$semiannual_name?>:<br>+ <?=SETUP?>:</b><?=EF?></nobr></td>
                     <?
                     for($i=0;$i<=count($package_array)-1;$i++) {
                         list($pack_price,$pack_setup) = mysql_fetch_array(mysql_query("SELECT pack_price,pack_setup FROM package_type WHERE pack_id = $package_array[$i]"));
                         ?>
                         <td bgcolor=<?=$tablebgcolor?> align=center>
                         <? if (split_price($pack_price,"price",6)==0) { ?>
                         <?=NA?>
                         <? } else { ?>
                         <nobr><b><?=display_currency(split_price($pack_price,"price",6))?></b> / <?=MONTHLY?><?=EF?></nobr><br>
                         <nobr><?=display_currency(split_price($pack_setup,"setup",6))?> / <?=ONETIME?><?=EF?></nobr>
                         <? } ?>
                         </td>
                         <?
                     }
                     ?>
                    </tr>
                <?
                }
                if ($allow_annual)
                {
                ?>
                    <tr>
                     <td bgcolor=<?=$tablebgcolor?> align=left><nobr><?=SFB?><b><?=$annual_name?>:<br>+ <?=SETUP?>:</b><?=EF?></nobr></td>
                     <?
                     for($i=0;$i<=count($package_array)-1;$i++) {
                         list($pack_price,$pack_setup) = mysql_fetch_array(mysql_query("SELECT pack_price,pack_setup FROM package_type WHERE pack_id = $package_array[$i]"));
                         ?>
                         <td bgcolor=<?=$tablebgcolor?> align=center>
                         <? if (split_price($pack_price,"price",12)==0) { ?>
                         <?=NA?>
                         <? } else { ?>
                         <nobr><b><?=display_currency(split_price($pack_price,"price",12))?></b> / <?=MONTHLY?><?=EF?></nobr><br>
                         <nobr><?=display_currency(split_price($pack_setup,"setup",12))?> / <?=ONETIME?><?=EF?></nobr>
                         <? } ?>
                         </td>
                         <?
                     }
                     ?>
                    </tr>
                <?
                }
                if ($allow_xyear)
                {
                ?>
                    <tr>
                     <td bgcolor=<?=$tablebgcolor?> align=left><nobr><?=SFB?><b><?=$xyear_name?>:<br>+ <?=SETUP?>:</b><?=EF?></nobr></td>
                     <?
                     for($i=0;$i<=count($package_array)-1;$i++) {
                         list($pack_price,$pack_setup) = mysql_fetch_array(mysql_query("SELECT pack_price,pack_setup FROM package_type WHERE pack_id = $package_array[$i]"));
                         ?>
                         <td bgcolor=<?=$tablebgcolor?> align=center>
                         <? if (split_price($pack_price,"price",24)==0) { ?>
                         <?=NA?>
                         <? } else { ?>
                         <nobr><b><?=display_currency(split_price($pack_price,"price",24))?></b> / <?=MONTHLY?><?=EF?></nobr><br>
                         <nobr><?=display_currency(split_price($pack_setup,"setup",24))?> / <?=ONETIME?><?=EF?></nobr>
                         <? } ?>
                         </td>
                         <?
                     }
                     ?>
                    </tr>
                <?
                }
                ?>
        </table>
        </td>
        </tr>
        </table>
<? } /*$display_package_comparisons*/ ?>
        <br>
        <?
        vortech_HTML_stop(3);
?>