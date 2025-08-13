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

GLOBAL $this_num_results,$selectlimit,$client_package_display,$tile;

if (!$client_id) {echo ERROR; exit;}
##<-- START DISPLAY -->##
$sql = "SELECT * FROM client_info WHERE client_id = $client_id";
if($debug)echo SFB.$sql.EF."<br>";

start_html();
admin_heading($tile);

addslashes($result = mysql_query($sql,$dbh));
$this_client = mysql_fetch_array($result);
?>
<table width="<?=$a_tile_width?>" border=0 align=center cellspacing=0 cellpadding=2 bgcolor=FFFFFF>
  <tr valign=center align=center>
    <td>
    <?
      echo LFH.CLIENT." [<a href=$page?op=client_details&db_table=client_info&tile=$tile&id=client_id|$client_id>".LFH.$client_id.EF."</a>] :: ".$this_client['client_fname']." ".$this_client['client_lname']." :: <a href=mailto:".$this_client['client_email'].">".LFH.$this_client['client_email'].EF."</a> :: ".EF;
      echo SFB."<a href=\"$page?op=form&db_table=client_info&tile=$tile&id=client_id|$client_id\">".EDIT_IMG."</a>".EF;
      echo "&nbsp;";
      echo SFB."<a href=\"$page?op=delete&db_table=client_info&tile=$tile&id=client_id|$client_id\">".DELETE_IMG."</a>".EF;
    ?>
    </td>
  </tr>
</table>

<table width="<?=$a_tile_width?>" border=0 align=center cellspacing=0 cellpadding=4 bgcolor=FFFFFF>
  <tr>
    <td>
    <?
         GLOBAL $page,
                $db_table,
                $date_format,
                $dbh,
                $op,
                $details_view,
                $tile,
                $debug,
                $client_id;
         ?>
         <?=start_box(PACKAGES)?>
         <table border=0 cellpadding=0 cellspacing=0 width=100% align=center>
         <tr><td bgcolor=DDDDDD>
         <?
         $db_table     = "client_package";
         $where        = "WHERE client_id = $client_id";
         $order        = ($order) ? $order : "cp_renew_stamp";
         $details_view = 1;
         $select_sql   = "SELECT * FROM client_package ";
         $limit        = $selectlimit;
         $order        = (!$order&&$select_order) ? $select_order : $order ;
         $sort         = (!$sort) ? "ASC" : $sort ;
         $select_sql  .= ($where&&!$search) ? " ".str_replace("\\",NULL,stripslashes(urldecode($where)))." " : NULL ; // WHERE is passed in, not via SEARCH
         $select_sql  .= ($order) ? "ORDER BY $order $sort " : "" ;
         $this_num_results = mysql_num_rows(mysql_query($select_sql,$dbh));
         $offset       = ($offset=="") ? 0 : $offset ;
         $select_sql  .= (!$recursive||$selectlimit) ? "LIMIT $offset,$limit" : NULL ;
         $this_sort    = $sort;
         if ($debug) echo $select_sql;
         $result       = mysql_query($select_sql,$dbh);
         $sort         = ($sort=="ASC") ? "DESC" : "ASC" ;
         ?>
         <table border=0 cellpadding=1 cellspacing=1 width=100% align=center>
               <tr><td align=center><? if ($order == "cp_id") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=cp_id&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".ID."</a>".EF?></b></td>
                   <td align=center><? if ($order == "pack_id") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=pack_id&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".PACKAGE."</a>".EF?></b></td>
                   <td align=center><? if ($order == "pack_price") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=pack_price&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".PRICEOVERRIDE."</a>".EF?></b></td>
                   <td align=center><? if ($order == "parent_cp_id") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=parent_cp_id&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".PARENT."</a>".EF?></b></td>
                   <td align=center><? if ($order == "cp_qty") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=cp_qty&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".QTY."</a>".EF?></b></td>
                   <td align=center><? if ($order == "cp_discount") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=cp_discount&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".DISCOUNT."</a>".EF?></b></td>
                   <td align=center><? if ($order == "cp_start_stamp") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=cp_start_stamp&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".STARTDATE."</a>".EF?></b></td>
                   <td align=center><? if ($order == "cp_renew_stamp") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=cp_renew_stamp&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".RENEWDATE."</a>".EF?></b></td>
                   <td align=center><? if ($order == "cp_renewed_on") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=cp_renewed_on&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".RENEWON."</a>".EF?></b></td>
                   <td align=center><? if ($order == "cp_billing_cycle") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=cp_billing_cycle&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".BILLINGCYCLE."</a>".EF?></b></td>
                   <td align=center><? if ($order == "cp_status") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=cp_status&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&where=".stripslashes(urlencode($where))."&client_id=$client_id\">".STATUS."</a>".EF?></b></td>
                   <td align=center><b><?=SFB.ACTION.EF?></b></td>
               </tr>
         <?
         while($this_package=mysql_fetch_array($result))
         {
           echo "<tr>
                 <td nowrap bgcolor=FFFFFF valign=top><a href=$page?op=details&tile=$tile&db_table=client_package&tile=$tile&print=$print&id=cp_id|$this_package[cp_id]>".$this_package[cp_id]."</a></td>
                 <td nowrap bgcolor=FFFFFF valign=top><a href=$page?op=details&tile=$tile&db_table=package_type&tile=$tile&print=$print&id=pack_id|$this_package[pack_id]>".package_select_box($this_package[pack_id],$this_package[cp_billing_cycle])."</a> ".list_domains($this_package[cp_id])."</td>
                 <td nowrap bgcolor=FFFFFF valign=top align=right>".display_currency($this_package[pack_price])."</td>
                 <td nowrap bgcolor=FFFFFF valign=top align=center><a href=$page?op=client_package&tile=$tile&tile=$tile&print=$print&client_id=$client_id&cp_id=$this_package[parent_cp_id]&display_parent_cp_id=1>$this_package[parent_cp_id]</a></td>
                 <td nowrap bgcolor=FFFFFF valign=top align=center>$this_package[cp_qty]</td>
                 <td nowrap bgcolor=FFFFFF valign=top align=center>$this_package[cp_discount]</td>
                 <td nowrap bgcolor=FFFFFF valign=top align=center>".stamp_to_date($this_package[cp_start_stamp])."</td>
                 <td nowrap bgcolor=FFFFFF valign=top align=center>".stamp_to_date($this_package[cp_renew_stamp])."</td>
                 <td nowrap bgcolor=FFFFFF valign=top align=center>".stamp_to_date($this_package[cp_renewed_on])."</td>
                 <td nowrap bgcolor=FFFFFF valign=top>".cycle_select_box($this_package[cp_billing_cycle])."</td>
                 <td nowrap bgcolor=FFFFFF valign=top>".status_select_box($this_package[cp_status],"cp_status")."</td>";
           ?>
                 <td bgcolor=FFFFFF align=center valign=top>
                 &nbsp;<?=SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&from=client_package&id=cp_id|$this_package[cp_id]\">".EDIT_IMG."</a>".EF?>&nbsp;
                       <?=SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&from=client_package&id=cp_id|$this_package[cp_id]\">".DELETE_IMG."</a>".EF?>&nbsp;</td>
                 </tr>
           <?
           $sql = "SELECT details_id, ip, server, server_type, username, password FROM account_details WHERE cp_id = $this_package[cp_id]";
           $result2 = mysql_query($sql);
           while($this_ad=mysql_fetch_array($result2))
           {
            //details_id, cp_id, domain_id, ip, server, server_type, username, password
            echo "<tr>
                   <td bgcolor=FFFFFF>&nbsp;</td>
                   <td colspan=10>[<B>".IP."</b>: $this_ad[ip]] [<b>".SERVNAME."</b>: $this_ad[server]] [<b>".SERVTYPE."</b>: ".server_type_select_box($this_ad[server_type])."] [<b>".USERNAME."</b>: $this_ad[username]] [<b>".PASSWORD_t."</b>: $this_ad[password]]<br></td>
                   <td bgcolor=FFFFFF align=right valign=center>
                       ".SFB."<a href=$page?op=form&tile=$tile&db_table=account_details&tile=$tile&id=details_id|$this_ad[details_id]>".EDIT_IMG."</a>&nbsp;
                       <a href=$page?op=delete&tile=$tile&db_table=account_details&tile=$tile&id=details_id|$this_ad[details_id]>".DELETE_IMG."</a>&nbsp;".EF."</td>
                  </tr>";
           }
           ?>
           <tr>
            <td colspan=12 bgcolor=FFFFFF>
             &nbsp;
            </td>
           </tr>
           <?
         }
         $details_view = 0;
         ?>
         </table>
         </td></tr>
         <tr><td bgcolor=FFFFFF align=center><?=PieceNavigation($db_table,$limit,$where)?><br></td></tr>
         </table>
         <?=stop_box()?>
    </td>
  </tr>


  <? if ($display_parent_cp_id) { ?>
  <tr>
    <td>
        <?
        $details_view = 1;
        $sql = "SELECT * FROM client_package WHERE client_id = $client_id AND cp_id = $cp_id ";
        $result = mysql_query($sql,$dbh);
        while($this_cp=mysql_fetch_array($result))
        {
            start_box(PACKAGE);
            ?>
            <table cellpadding=2 cellspacing=2 border=0 width=100%>
             <tr><td><b><?=LFH.PARENT.EF?></b><hr size=1></td><td><b><?=LFH.CHILDREN.EF?></b><hr size=1></td></tr>
             <tr>
              <td width=35% valign=top>
                  <table cellpadding=2 cellspacing=2 border=0 width=100%>
                  <tr><td colspan=2 bgcolor=DDDDDD><b><?=SFB.PACKAGE.":".EF?></b> <?=package_select_box($this_cp[pack_id],$this_cp[cp_billing_cycle]).EF?>&nbsp;<?=edit_delete($this_cp[cp_id])?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_1?>><b><?=SFB.ID.":".EF?></b></td><td bgcolor=<?=$cell_color_1?>><?=SFB?><?=$this_cp[cp_id]?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_2?>><b><?=SFB.DOMAIN.":".EF?></b></td><td bgcolor=<?=$cell_color_2?>><?=SFB.list_domains($cp_id).EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_1?>><b><?=SFB.PRICEOVERRIDE.":".EF?></b></td><td bgcolor=<?=$cell_color_1?>><?=SFB.display_currency($this_cp[pack_price]).EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_2?>><b><?=SFB.QTY.":".EF?></b></td><td bgcolor=<?=$cell_color_2?>><?=SFB.$this_cp[cp_qty].EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_1?>><b><?=SFB.DISCOUNT.":".EF?></b></td><td bgcolor=<?=$cell_color_1?>><?=SFB.$this_cp[cp_discount].EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_2?>><b><?=SFB.STARTDATE.":".EF?></b></td><td bgcolor=<?=$cell_color_2?>><?=SFB.stamp_to_date($this_cp[cp_start_stamp]).EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_1?>><b><?=SFB.RENEWDATE.":".EF?></b></td><td bgcolor=<?=$cell_color_1?>><?=SFB.stamp_to_date($this_cp[cp_renew_stamp]).EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_2?>><b><?=SFB.RENEWON.":".EF?></b></td><td bgcolor=<?=$cell_color_2?>><?=SFB.stamp_to_date($this_cp[cp_renewed_on]).EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_1?>><b><?=SFB.BILLINGCYCLE.":".EF?></b></td><td bgcolor=<?=$cell_color_1?>><?=SFB.cycle_select_box($this_cp[cp_billing_cycle]).EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_2?>><b><?=SFB.STATUS.":".EF?></b></td><td bgcolor=<?=$cell_color_2?>><?=SFB.status_select_box($this_cp[cp_status],"cp_status").EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_1?>><b><?=SFB.TIMESTAMP.":".EF?></b></td><td bgcolor=<?=$cell_color_1?>><?=SFB.stamp_to_date($this_cp[cp_stamp]).EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_2?>><b><?=SFB.COMMENTS.":".EF?></b></td><td bgcolor=<?=$cell_color_2?>><?=SFB.$this_cp[cp_comments].EF?></td></tr>
                  <tr><td bgcolor=<?=$cell_color_1?>><b><?=SFB.FEATURES.":".EF?></b></td><td bgcolor=<?=$cell_color_1?>><?=load_feature($this_cp[pack_id])?></tr>
                  </table>
              </td>
              <td width=65% valign=top><?=load_children($this_cp[cp_id])?></td>
             </tr>
            </table>
            <?
            stop_box();
        }
        ?>

    </td>
  </tr>
  <? } ?>
</table>
<?
stop_html();



function load_feature($pack_id)
{
         GLOBAL $dbh;
         if(!$dbh)dbconnect();
         $result2 = mysql_query("SELECT feature_name, feature_comments FROM package_feature WHERE pack_id='$pack_id' ORDER BY feature_name");
         $package_type = "<ul>";
         while(list($feature_name, $feature_comments) = mysql_fetch_array($result2))
         {
               $package_type .= "<li><b>$feature_name:</b> $feature_comments</li>";
         }
         $package_type .= "</ul>";
         return $package_type;
}

function load_children($cp_id)
{
         GLOBAL $dbh;
         if(!$dbh)dbconnect();
        $details_view = 1;
        $sql = "SELECT * FROM client_package WHERE parent_cp_id = $cp_id";
        $result3 = mysql_query($sql,$dbh);
        while($this_cp=mysql_fetch_array($result3))
        {
            ?>
            <table cellpadding=2 cellspacing=2 border=0 width=100%>
             <tr><td colspan=2 bgcolor=DDDDDD><b><?=SFB.PACKAGE.":".EF?></b> <?=package_select_box($this_cp[pack_id],$this_cp[cp_billing_cycle]).EF?>&nbsp;<?=edit_delete($this_cp[cp_id])?></td></tr>
             <tr>
              <td width=50% valign=top>
                  <table cellpadding=2 cellspacing=2 border=0 width=100%>
                  <tr><td width=50%><b><?=SFB.ID.":".EF?></b></td><td><?=SFB?><?=$this_cp[cp_id]?></td></tr>
                  <tr><td><b><?=SFB.DOMAIN.":".EF?></b></td><td><?=SFB.list_domains($cp_id).EF?></td></tr>
                  <tr><td><b><?=SFB.PRICEOVERRIDE.":".EF?></b></td><td><?=SFB.display_currency($this_cp[pack_price]).EF?></td></tr>
                  <tr><td><b><?=SFB.QTY.":".EF?></b></td><td><?=SFB.$this_cp[cp_qty].EF?></td></tr>
                  <tr><td><b><?=SFB.DISCOUNT.":".EF?></b></td><td><?=SFB.$this_cp[cp_discount].EF?></td></tr>
                  <tr><td><b><?=SFB.COMMENTS.":".EF?></b></td><td><?=SFB.$this_cp[cp_comments].EF?></td></tr>
                  <tr><td><b><?=SFB.FEATURES.":".EF?></b></td><td><?=load_feature($this_cp[pack_id])?></td></tr>
                  </table>
              </td>
              <td width=50% valign=top>
                  <table cellpadding=2 cellspacing=2 border=0 width=100%>
                  <tr><td width=50%><b><?=SFB.STARTDATE.":".EF?></b></td><td><?=SFB.stamp_to_date($this_cp[cp_start_stamp]).EF?></td></tr>
                  <tr><td><b><?=SFB.RENEWDATE.":".EF?></b></td><td><?=SFB.stamp_to_date($this_cp[cp_renew_stamp]).EF?></td></tr>
                  <tr><td><b><?=SFB.RENEWON.":".EF?></b></td><td><?=SFB.stamp_to_date($this_cp[cp_renewed_on]).EF?></td></tr>
                  <tr><td><b><?=SFB.BILLINGCYCLE.":".EF?></b></td><td><?=SFB.cycle_select_box($this_cp[cp_billing_cycle]).EF?></td></tr>
                  <tr><td><b><?=SFB.STATUS.":".EF?></b></td><td><?=SFB.status_select_box($this_cp[cp_status],"cp_status").EF?></td></tr>
                  <tr><td><b><?=SFB.TIMESTAMP.":".EF?></b></td><td><?=SFB.stamp_to_date($this_cp[cp_stamp]).EF?></td></tr>
                  </table>
              </td>
             </tr>
            </table>
            <hr size=1>
            <?
        }
}
/*
cp_id,
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
cp_stamp
*/
?>