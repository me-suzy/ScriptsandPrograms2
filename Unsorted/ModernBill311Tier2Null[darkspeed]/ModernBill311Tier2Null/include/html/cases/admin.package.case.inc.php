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

## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

if(!$dbh)dbconnect();
$num_packages=$active_packages=$inactive_packages=0;
list($active_packages)=mysql_fetch_row(mysql_query("SELECT count(pack_id) FROM package_type WHERE pack_status=2"));
list($inactive_packages)=mysql_fetch_row(mysql_query("SELECT count(pack_id) FROM package_type WHERE pack_status=1"));
$num_packages=$active_packages+$inactive_packages;

## PACK DISPLAY
$this_vortech_config=mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type='vortech_type1'"));
$table_width           = $this_vortech_config["config_35"];
$contact_final_width   = $this_vortech_config["config_36"];
$outerborder           = $this_vortech_config["config_37"];
$innerborder           = $this_vortech_config["config_38"];
$headercolor           = $this_vortech_config["config_39"];
$headertextcolor       = $this_vortech_config["config_40"];
$tablebgcolor          = $this_vortech_config["config_41"];
$tablebgcolor2         = $this_vortech_config["config_42"];
$allow_xyear           = $this_vortech_config["config_8"];
$xyear_name            = $this_vortech_config["config_9"];
$allow_monthly         = $this_vortech_config["config_27"];
$monthly_name          = $this_vortech_config["config_28"];
$allow_quarterly       = $this_vortech_config["config_29"];
$quarterly_name        = $this_vortech_config["config_30"];
$allow_semiannual      = $this_vortech_config["config_31"];
$semiannual_name       = $this_vortech_config["config_32"];
$allow_annual          = $this_vortech_config["config_33"];
$annual_name           = $this_vortech_config["config_34"];

?>
<tr>
 <td>
   <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
    <tr><td><?=LFH?><b><?=PACKAGESTATS?>:</b><?=EF?></td><td><?=LFH?><b><?=PACKAGESEARCH?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=view&db_table=package_type&tile=<?=$tile?>><?=VIEWALL?></a>]<?=EF?></td></tr>
    <tr>
      <td width=50% valign=top>
        <table>
         <tr><td>
              <?=MFB?>
              <?=TTLPACKS?>:<br>
              <?=TOTALACTIVE?>:<br>
              <?=TOTALINACTIVE?>:<br>
              <?=EF?>
             </td>
             <td align=right>
              <?=MFB?>
              [<a href=<?=$page?>?op=view&db_table=package_type&tile=<?=$tile?>><?=$num_packages?></a>]<br>
              [<a href=<?=$page?>?op=view&db_table=package_type&tile=<?=$tile?>&where=<?=urlencode("WHERE pack_status=2")?>><?=$active_packages?></a>]<br>
              [<a href=<?=$page?>?op=view&db_table=package_type&tile=<?=$tile?>&where=<?=urlencode("WHERE pack_status=1")?>><?=$inactive_packages?></a>]<br>
              <?=EF?>
             </td>
             <td align=right>
              <?=MFB?>
              [<a href=<?=$page?>?op=form&db_table=package_type&tile=<?=$tile?>><b><?=ADD?></b></a>]<br>
              &nbsp;<br>
              &nbsp;<br>
              <?=EF?>
             </td>
          </tr>
         </table>
      </td>
      <td width=50% valign=top>
         <table>
         <form method=post action=<?=$page?>>
         <input type=hidden name=op value=view>
         <input type=hidden name=search value=1>
         <input type=hidden name=tile value=<?=$tile?>>
         <input type=hidden name=db_table value=package_type>
         <tr><td colspan=2><?=package_search_select_box();?></td></tr>
         <tr><td><input type=text name=query size=15 maxlength=25></td><td><?=SEARCH_IMG?></td></tr>
         </form>
         </table>
      </td>
    </tr>
   </table>
 </td>
</tr>
<tr>
 <td>
 <hr>
 <?
 switch ($type) {
    case matrix: // FEATURES MATRIX
         ?>
          <center><b><?=LFH.FEATURES." [vortech_type$pack_display]".EF?></b></center><br>
          <table cellpadding=0 cellspacing=0 border=0 bgcolor=<?=$outerborder?> align=center width=100%>
          <tr>
          <td>
          <table cellpadding=3 cellspacing=1 border=0 width=100%>
          <tr>
           <td bgcolor=<?=$tablebgcolor?> align=center valign=bottom><?=SFB?><b><?=FEATURES?></b><?=EF?></td>
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
                     <td bgcolor=<?=$tablebgcolor?> align=center><nobr><?=SFB?><a href=<?=$page?>?op=details&db_table=package_type&tile=package&id=pack_id|<?=$pack_id?>><b><?=$pack_name?></b><a/><?=EF?></nobr></td>
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
                         list($feature_id,$feature_comments) = mysql_one_array("SELECT feature_id,feature_comments FROM package_feature WHERE pack_id = $package_array[$i] AND feature_name LIKE '".addslashes($feature_name)."'");
                         ?>
                         <td bgcolor=<?=$tablebgcolor?> align=center>
                             <? if($feature_id) { ?>
                             <?=SFB?><a href=<?=$page?>?op=form&db_table=package_feature&tile=package&id=feature_id|<?=$feature_id?>><?=$feature_comments?></a><?=EF?></td>
                             <? } else { ?>
                             <?=SFB?><b>[<a href=<?=$page?>?op=form&db_table=package_feature&pack_id=<?=$package_array[$i]?>&feature_name=<?=urlencode($feature_name)?>>+</a>]</b><?=EF?></td>
                             <? } ?>
                         <?
                     }
                     ?>
                    </tr>
                <?
                }
                ?>
                <tr>
                <td bgcolor=<?=$tablebgcolor?> align=right><nobr><?=SFB?><b><?=ADDFEATURE?>:</b><?=EF?></nobr></td>
                <?
                for($i=0;$i<=count($package_array)-1;$i++){
                    ?>
                    <td bgcolor=<?=$tablebgcolor?> align=center>
                    <?=SFB?><b>[<a href=<?=$page?>?op=form&db_table=package_feature&pack_id=<?=$package_array[$i]?>>+</a>]</b><?=EF?></td>
                    <?
                }
                ?>
                </tr>

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
         <?
    break;

    default: // VORTECH SIGNUPS
         ?>
          <center><b><?=LFH.VORTECHPACKAGESETUP.EF?></b></center><br>
          <center><b><?=SFB.CHILDINSTRUCTIONS.EF?></b></center><br>
          <?
          if (!$dbh) dbconnect();
          $result = mysql_query("SELECT distinct(pack_display)
                                 FROM package_type
                                 WHERE pack_display >= 1
                                 ORDER BY pack_display");
          while(list($pack_display) = mysql_fetch_array($result))
          {
          ?>
           <table cellpadding=0 cellspacing=0 border=0 bgcolor=<?=$outerborder?> align=center width=590>
            <tr>
             <td>
              <table cellpadding=3 cellspacing=1 border=0 width=100%>
               <tr bgcolor=<?=$headercolor?>>
                <td align=center><?=SFB?><b><a href="<?=$page?>?op=form&db_table=config&tile=config&from=client_package&id=config_type|vortech_type<?=$pack_display?>"><font color=<?=$headertextcolor?>>[vortech_type<?=$pack_display?>]</font></a></b><?=EF?>&nbsp;&nbsp;
                                 <?=SFB?><b><a href="<?=$page?>?op=menu&tile=package&type=matrix&pack_display=<?=$pack_display?>"><font color=<?=$headertextcolor?>>[<?=VIEWFEATURES?>]</font></a></b><?=EF?></td>
                <td align=center width=45><?=SFB?><font color=<?=$headertextcolor?>><b><?=PRICE?></b></font><?=EF?></td>
                <td align=center width=45><?=SFB?><font color=<?=$headertextcolor?>><b><?=SETUP?></b></font><?=EF?></td>
                <td align=center width=45><?=SFB?><font color=<?=$headertextcolor?>><b><?=COST?></b></font><?=EF?></td>
                <td align=center width=45><?=SFB?><font color=<?=$headertextcolor?>><b><?=PROFIT?></b></font><?=EF?></td>
                <td align=center width=45><?=SFB?><font color=<?=$headertextcolor?>><b><?=MARGIN?></b></font><?=EF?></td>
                <td align=center width=45><?=SFB?><font color=<?=$headertextcolor?>><b><?=NUMBER?></b></font><?=EF?></td>
               </tr>
               <?
               ## MAIN PACKAGES
               $result2 = mysql_query("SELECT pack_id,
                                             pack_name,
                                             pack_price,
                                             pack_setup,
                                             pack_cost,
                                             (pack_price - pack_cost) as pack_profit
                                      FROM package_type
                                      WHERE pack_status=2
                                      AND pack_display=$pack_display
                                      ORDER BY pack_name");
               while(list($pack_id,$pack_name,$pack_price,$pack_setup,$pack_cost,$pack_profit) = mysql_fetch_array($result2))
               {
                     if ($pack_profit==0&&$pack_price==0) {
                        $margin = 0;
                     } else {
                        $profit = $pack_price - $pack_cost;
                        if ($profit > 0) {
                            $margin     = (($pack_price - $pack_cost) / $pack_price) * 100;
                            $font_color = "GREEN";
                        } elseif ($profit < 0) {
                            $margin     = ($pack_price>0) ? (($pack_price - $pack_cost) / $pack_price) * 100 : ($pack_price / ($pack_price - $pack_cost)) * 100 ;
                            $font_color = "RED";
                        } else {
                            $margin     = (($pack_price - $pack_cost) / $pack_price) * 100;
                            $font_color = "000000";
                        }
                     }
                     $pack_count = mysql_one_data("SELECT count(pack_id) FROM client_package WHERE pack_id = $pack_id");
                     ?>
                     <tr>
                      <td bgcolor=<?=$tablebgcolor?>><nobr><?=SFB?><b>[<a href=<?=$page?>?op=form&db_table=package_relationships&tile=<?=$tile?>&parent_pack_id=<?=$pack_id?>&from=package_admin>+</a>]</b>&nbsp;<a href=<?=$page?>?op=details&db_table=package_type&tile=package&id=pack_id|<?=$pack_id?>><b><?=$pack_name?></b><a/><?=EF?></nobr></td>
                      <td bgcolor=<?=$tablebgcolor?> align=right><nobr><?=SFB?><?=display_currency($pack_price)?><?=EF?></nobr></td>
                      <td bgcolor=<?=$tablebgcolor?> align=right><nobr><?=SFB?><?=display_currency($pack_setup)?><?=EF?></nobr></td>
                      <td bgcolor=FFFFFF align=right><nobr><?=SFB?><?=display_currency($pack_cost)?><?=EF?></nobr></td>
                      <td bgcolor=FFFFFF align=right><nobr><?=SFB?><font color=<?=$font_color?>><?=display_currency($pack_profit)?></font><?=EF?></nobr></td>
                      <td bgcolor=FFFFFF align=right><nobr><?=SFB?><font color=<?=$font_color?>><?=number_format($margin,1)?>%</font><?=EF?></nobr></td>
                      <td bgcolor=FFFFFF align=center><nobr><?=SFB?><a href=<?=$page?>?op=view&db_table=client_package&tile=<?=$tile?>&where=<?=urlencode("WHERE pack_id = $pack_id")?>><?=$pack_count?></a><?=EF?></nobr></td>
                     </tr>
                     <?
                     $there_are_1_packages = TRUE;

                     ## CHILDREN PACKAGES
                     $result3 = mysql_query("SELECT child_pack_id FROM package_relationships WHERE parent_pack_id = $pack_id");
                     while($this_child_pack_id = mysql_fetch_array($result3))
                     {
                           ## CHILD PACKAGES
                           $result4 = mysql_query("SELECT pack_id,
                                                          pack_name,
                                                          pack_price,
                                                          pack_setup,
                                                          pack_cost,
                                                          (pack_price - pack_cost) as pack_profit
                                                   FROM package_type
                                                   WHERE pack_id = \"$this_child_pack_id[child_pack_id]\"");
                           while(list($child_pack_id,
                                      $child_pack_name,
                                      $child_pack_price,
                                      $child_pack_setup,
                                      $child_pack_cost,
                                      $child_pack_profit) = mysql_fetch_array($result4))
                           {
                           if ($child_pack_profit==0&&$child_pack_price==0) {
                               $margin = 0;
                           } else {
                               $profit = $child_pack_price - $child_pack_cost;
                               if ($profit > 0) {
                                  $margin     = ($child_pack_price > 0) ? (($child_pack_price - $child_pack_cost) / $child_pack_price) * 100 : 0 ;
                                  $font_color = "GREEN";
                               } elseif ($profit < 0) {
                                  $margin     = ($child_pack_price > 0) ? (($child_pack_price - $child_pack_cost) / $child_pack_price) * 100 : 0 ;
                                  $font_color = "RED";
                               } else {
                                  $margin     = ($child_pack_price > 0) ? (($child_pack_price - $child_pack_cost) / $child_pack_price) * 100 : 0 ;
                                  $font_color = "000000";
                               }
                           }
                           $pr_id = mysql_one_data("SELECT pr_id
                                        FROM package_relationships
                                        WHERE parent_pack_id = $pack_id
                                        AND child_pack_id = $this_child_pack_id[child_pack_id]");

                           $pack_count = 0;
                           $sql = "SELECT client_id FROM client_package WHERE pack_id = $pack_id";
                           $temp_result = mysql_query($sql);
                           while(list($client_id) = mysql_fetch_row($temp_result))
                           {
                                $pack_count += mysql_one_data("SELECT count(cp_id)
                                                               FROM client_package
                                                               WHERE client_id = $client_id
                                                               AND parent_cp_id = $pack_id
                                                               AND pack_id = $this_child_pack_id[child_pack_id]");

                           }
                           ?>
                                 <tr>
                                  <td bgcolor=<?=$tablebgcolor?> align=left><nobr><?=SFB?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>[<a href="<?=$page?>?op=delete&db_table=package_relationships&tile=<?=$tile?>&from=package_admin&id=pr_id|<?=$pr_id?>">-</a>]</b>&nbsp;<a href=<?=$page?>?op=details&db_table=package_type&tile=package&id=pack_id|<?=$child_pack_id?>><?=$child_pack_name?><a/><?=EF?></nobr></td>
                                  <td bgcolor=<?=$tablebgcolor?> align=right><nobr><?=SFB?><?=display_currency($child_pack_price)?><?=EF?></nobr></td>
                                  <td bgcolor=<?=$tablebgcolor?> align=right><nobr><?=SFB?><?=display_currency($child_pack_setup)?><?=EF?></nobr></td>
                                  <td bgcolor=FFFFFF align=right><nobr><?=SFB?><?=display_currency($child_pack_cost)?><?=EF?></nobr></td>
                                  <td bgcolor=FFFFFF align=right><nobr><?=SFB?><font color=<?=$font_color?>><?=display_currency($child_pack_profit)?></font><?=EF?></nobr></td>
                                  <td bgcolor=FFFFFF align=right><nobr><?=SFB?><font color=<?=$font_color?>><?=number_format($margin,1)?>%</font><?=EF?></nobr></td>
                                  <td bgcolor=FFFFFF align=center><nobr><?=SFB?><a href=<?=$page?>?op=view&db_table=client_package&tile=<?=$tile?>&where=<?=urlencode("WHERE parent_cp_id = $pack_id AND pack_id = $this_child_pack_id[child_pack_id]")?>><?=$pack_count?></a><?=EF?></nobr></td>
                                 </tr>
                           <?
                           $there_are_1_children = TRUE;
                           }
                     }
               }
               if (!$there_are_1_packages) {
                    echo "<tr><td colspan=3 align=center>".NOPACKAGESFOUND."</td></tr>";
               }
               ?>
              </table>
             </td>
            </tr>
           </table>
           <br>
           <?
           } // END LOOP
           ?>
          <hr>
          <?
      break;
 } // END SWITCH
 ?>
 </td>
</tr>