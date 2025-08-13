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

include("config.php");
GLOBAL $HTTP_POST_VARS;

vortech_HTML_start("compare_");
?>
<? /* DISPLAY COMPARE PACKAGES 2 */ ?>
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
                <nobr><?=SFB.PRICE?>: <?=display_currency(split_price($pack_price,"price",1))?> / <?=MONTHLY?><?=EF?></nobr><br>
                <nobr><?=SFB.SETUP?>: <?=display_currency(split_price($pack_setup,"setup",1))?> / <?=ONETIME?><?=EF?></nobr>
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
  while(list($feature_name) = mysql_fetch_array($result2))
  // feature_id, pack_id, feature_name, feature_comments
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
<br>
<?
vortech_HTML_stop("compare_");
?>