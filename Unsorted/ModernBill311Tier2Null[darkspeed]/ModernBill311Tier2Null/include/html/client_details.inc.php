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

$session_from = "op=client_details&db_table=client_info&tile=client&print=&id=$id";

GLOBAL $this_num_results,$selectlimit;

##<-- DEFINE DISPLAY OPTIONS -->##
$display_account_pops    = ($display_account_pops)    ? $display_account_pops    : TRUE ;
$display_account_details = ($display_account_details) ? $display_account_details : TRUE ;
$display_account_dbs     = ($display_account_dbs)     ? $display_account_dbs     : TRUE ;
$display_client_domains  = ($display_client_domains)  ? $display_client_domains  : TRUE ;
$display_client_packages = ($display_client_packages) ? $display_client_packages : TRUE ;
$display_client_invoices = ($display_client_invoices) ? $display_client_invoices : TRUE ;
$display_client_notes    = ($display_client_notes)    ? $display_client_notes    : TRUE ;
$display_client_credits  = ($display_client_credits)  ? $display_client_credits  : TRUE ;

##<-- DEFINE SELECT LIMITS -->##
$limit_client_notes    = ($limit_client_notes)    ? $limit_client_notes    : 10 ;


##<-- START DISPLAY -->##
$id  = explode("|",$id);
$sql = "SELECT * FROM $db_table WHERE $id[0]=$id[1]";
if($debug)echo SFB.$sql.EF."<br>";

/*.......CC EXP Date Logic.........*/
list($cc_exp_month,$cc_exp_year) = explode("/",mysql_one_data("SELECT billing_cc_exp FROM client_info WHERE $id[0]=$id[1]"));
if ( ( $cc_exp_month && $cc_exp_year ) && ( mktime() > mktime(0,0,0,$cc_exp_month,1,$cc_exp_year ) ) ) {
       $is_expired = TRUE;
       $cc_exp_string = "<font color=red>".EXPIRED."</font>";
} elseif ( $cc_exp_month && $cc_exp_year ) {
       $is_expired = FALSE;
       $time  = mktime(0,0,0,$cc_exp_month,1,$cc_exp_year) - mktime();
       $time  = round($time / (24 * 60 * 60));
       $time .= " ".DAYS;
       $cc_exp_string = "<font color=green>".$time."</font>";
} else {
       $cc_exp_string = "<font color=green>".NONE."</font>";
}

list($num_invoices_partial,$amount_invoices_partial)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount-invoice_amount_paid)
                                                                                        FROM client_invoice
                                                                                        WHERE client_id = $id[1]
                                                                                        AND invoice_amount_paid > .01
                                                                                        AND invoice_amount_paid < invoice_amount"));
list($num_credits,$amount_credits)=mysql_fetch_row(mysql_query("SELECT count(credit_id),sum(credit_amount) FROM client_credit WHERE  client_id =$id[1]"));
list($reg_count,$account_balance)=mysql_fetch_row(mysql_query("SELECT COUNT(reg_id), SUM(reg_payment)-SUM(reg_bill) FROM client_register WHERE client_id = $id[1]"));
list($num_invoices_paid,$amount_invoices_paid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount) FROM client_invoice WHERE invoice_date_paid!=0 AND  client_id = $id[1]"));
list($num_invoices_unpaid,$amount_invoices_unpaid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount) FROM client_invoice WHERE invoice_date_paid=0 AND  client_id =$id[1]"));
$num_packages = mysql_one_data("SELECT count(cp_id) FROM client_package WHERE client_id = $id[1]");
$num_aff      = mysql_one_data("SELECT count(aff_id) FROM affiliate_config WHERE client_id=$id[1]");
$num_pops     = mysql_one_data("SELECT count(pop_id) FROM account_pops WHERE client_id=$id[1]");
$num_dbs      = mysql_one_data("SELECT count(db_id) FROM account_dbs WHERE client_id=$id[1]");
$num_domains  = mysql_one_data("SELECT count(domain_id) FROM domain_names WHERE client_id=$id[1]");
$num_ad       = mysql_one_data("SELECT count(details_id) FROM account_details WHERE client_id=$id[1]");


// START CLIENT INFO DISPLAY
start_html();
admin_heading($tile);

addslashes($result = mysql_query($sql,$dbh));
$this_client = mysql_fetch_array($result);
?>
<table width="<?=$a_tile_width?>" border=0 align=center cellspacing=0 cellpadding=2 bgcolor=FFFFFF>
  <tr valign=center align=center>
    <td>
    <?
      echo LFH.CLIENT." [<a href=$page?op=client_details&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]>".LFH.$id[1].EF."</a>] :: ".$this_client['client_fname']." ".$this_client['client_lname']." :: <a href=mailto:".$this_client['client_email'].">".LFH.$this_client['client_email'].EF."</a> :: ".EF;
      echo SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]\">".EDIT_IMG."</a>".EF;
      echo "&nbsp;";
      echo SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]\">".DELETE_IMG."</a>".EF;
    ?>
    </td>
  </tr>
</table>

<table width="<?=$a_tile_width?>" border=0 align=center cellspacing=0 cellpadding=2 bgcolor=FFFFFF>
  <tr valign=top>
    <td>
    <?
        addslashes($result = mysql_query($sql,$dbh));
        start_table(NULL,"100%");
        build_form($args,$result);
        stop_table();
    ?>
    </td>
    <td>
      <table width="100%" border=0 align=center cellspacing=0 cellpadding=2>
        <tr>
          <td>
            <?=start_box(STATS."/".ACTION)?>
              <table border=0 align=left cellspacing=0 cellpadding=2>
               <tr>
                <? /* STATS LEFT COLUMN */ ?>
                <td valign=top>

                  <table border=0 align=left cellspacing=0 cellpadding=2>
                  <? /* CLIENT REGISTER TOTALS */ ?>
                  <tr>
                   <td><?=SFB.REGISTERBALANCE.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=menu&db_table=client_register&tile=client_register&where=<?=urlencode("WHERE client_id = $id[1]")?>><?=$reg_count?></a>]<?=EF?>
                       <?=SFB.display_currency($account_balance).EF?></td>
                  </tr>

                  <? /* INVOICE TOTALS */ ?>
                  <tr>
                   <td><?=SFB.INVOICESPAID.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=client_invoice&tile=<?=$tile?>&where=<?=urlencode("WHERE invoice_date_paid!=0 AND client_id = $id[1]")?>><?=$num_invoices_paid?></a>]<?=EF?>
                       <?=SFB.display_currency($amount_invoices_paid).EF?></td>
                  </tr>

                  <tr>
                   <td><?=SFB.INVOICESDUE.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=client_invoice&tile=<?=$tile?>&where=<?=urlencode("WHERE invoice_date_paid=0 AND client_id = $id[1]")?>><?=$num_invoices_unpaid?></a>]<?=EF?>
                       <?=SFB.display_currency($amount_invoices_unpaid).EF?>
                       <?=SFB?>[<a href=<?=$page?>?op=form&db_table=client_invoice&tile=<?=$tile?>&<?=$id[0]?>=<?=$id[1]?>&from=client_id><b><?=ADD?></b></a>]<?=EF?></td>
                  </tr>

                  <tr>
                   <td><?=SFB.INVOICESPARTIAL.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=client_invoice&tile=<?=$tile?>&where=<?=urlencode("WHERE client_id = $id[1] AND invoice_amount_paid > .01 AND invoice_amount_paid < invoice_amount")?>><?=$num_invoices_partial?></a>]<?=EF?>
                       <?=SFB.display_currency($amount_invoices_partial).EF?></td>
                  </tr>

                  <? /* CLIENT CREDIT TOTALS */ ?>
                  <tr>
                   <td><?=SFB.CREDITS.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=client_credit&tile=<?=$tile?>&where=<?=urlencode("WHERE client_id = $id[1]")?>><?=$num_credits?></a>]<?=EF?>
                       <?=SFB.display_currency($amount_credits).EF?>
                       <?=SFB?>[<a href=<?=$page?>?op=form&db_table=client_credit&tile=<?=$tile?>&<?=$id[0]?>=<?=$id[1]?>&from=client_id><b><?=ADD?></b></a>]<?=EF?></td>
                  </tr>

                  <tr><td colspan=2><hr size=1></td></tr>

                  <? /* CLIENT PACKAGE TOTALS */ ?>
                  <tr>
                   <td><?=SFB.PACKAGES.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=client_package&tile=<?=$tile?>&client_id=<?=$id[1]?>><?=$num_packages?></a>]
                               [<a href=<?=$page?>?op=form&db_table=client_package&tile=<?=$tile?>&<?=$id[0]?>=<?=$id[1]?>&from=client_id><b><?=ADD?></b></a>]<?=EF?></td>
                  </tr>

                  <? /* CLIENT DOMAIN TOTALS */ ?>
                  <tr>
                   <td><?=SFB.DOMAINS.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=domain_names&tile=<?=$tile?>&where=<?=urlencode("WHERE client_id = $id[1]")?>><?=$num_domains?></a>]
                               [<a href=<?=$page?>?op=form&db_table=domain_names&tile=<?=$tile?>&<?=$id[0]?>=<?=$id[1]?>&from=client_id><b><?=ADD?></b></a>]<?=EF?></td>
                  </tr>

                  <? /* ACCOUNT DETAILS TOTALS */ ?>
                  <tr>
                   <td><?=SFB.ACCOUNTDETAILS.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=account_details&tile=<?=$tile?>&where=<?=urlencode("WHERE client_id = $id[1]")?>><?=$num_ad?></a>]
                               [<a href=<?=$page?>?op=form&db_table=account_details&tile=<?=$tile?>&<?=$id[0]?>=<?=$id[1]?>&from=client_id><b><?=ADD?></b></a>]<?=EF?></td>
                  </tr>

                  <? /* ACCOUNT DBS */ ?>
                  <tr>
                   <td><?=SFB.ACCOUNTDBS.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=account_dbs&tile=<?=$tile?>&where=<?=urlencode("WHERE client_id = $id[1]")?>><?=$num_dbs?></a>]<?=EF?>
                    <?=SFB?>[<a href=<?=$page?>?op=form&db_table=account_dbs&tile=<?=$tile?>&<?=$id[0]?>=<?=$id[1]?>&from=client_id><b><?=ADD?></b></a>]<?=EF?></td>
                  </tr>

                  <? /* ACCOUNT POPS */ ?>
                  <tr>
                   <td><?=SFB.ACCOUNTPOPS.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=account_pops&tile=<?=$tile?>&where=<?=urlencode("WHERE client_id = $id[1]")?>><?=$num_pops?></a>]
                               [<a href=<?=$page?>?op=form&db_table=account_pops&tile=<?=$tile?>&<?=$id[0]?>=<?=$id[1]?>&from=client_id><b><?=ADD?></b></a>]<?=EF?></td>
                  </tr>

                  <? /* AFFILIATES */ ?>
                  <tr>
                   <td><?=SFB.AFFILIATES.":".EF?></td>
                   <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=affiliate_config&tile=<?=$tile?>&where=<?=urlencode("WHERE client_id = $id[1]")?>><?=$num_aff?></a>]
                               [<a href=<?=$page?>?op=form&db_table=affiliate_config&tile=<?=$tile?>&<?=$id[0]?>=<?=$id[1]?>&from=client_id><b><?=ADD?></b></a>]<?=EF?></td>
                  </tr>
                  </table>
                </td>

                <? /* STATS MIDDLE COLUMN */ ?>
                <td valign=center align=center>
                    <? for($i=1;$i<=25;$i++){ echo ".<br>"; } ?>
                </td>

                <? /* STATS RIGHT COLUMN */ ?>
                <td valign=top>

                  <table border=0 align=left cellspacing=0 cellpadding=2>

                  <tr>
                   <td><li><?=SFB.CCEXPIRED.": <b>$cc_exp_string</b>".EF?> <?=SFB?>[<a href=<?=$page?>?op=mail&tile=mail&step=2&email_type=exp_cc_single&id=<?=$id[1]?>><?=EMAIL?></a>]<?=EF?></td>
                  </tr>

                  <tr>
                   <td><li><?=SFB?><a href=<?=$page?>?op=update_cc&db_table=<?=$db_table?>&tile=<?=$tile?>&id=<?=$id[0]?>|<?=$id[1]?>><?=UPDATECC?></a><?=EF?></td>
                  </tr>

                  <tr>
                   <td><li><?=SFB?><a href=# onClick=OpenWindow('<?=$page?>?op=view_cc&db_table=<?=$db_table?>&tile=<?=$tile?>&id=<?=$id[0]?>|<?=$id[1]?>','SecureOnlinePayment','toolbar=no,location=no,status=yes,menubar=no,scrollbars=no,resizable=no,width=500,height=400')><?=VIEWCC?></a><?=EF?></td>
                  </tr>

                  <tr><td><hr size=1></td></tr>

                  <tr>
                   <td><li><?=SFB?><a href=<?=$page?>?op=client_login&db_table=<?=$db_table?>&tile=<?=$tile?>&id=<?=$id[1]?>><?=LOGINAS." ".$this_client['client_fname']." ".$this_client['client_lname']?></a><?=EF?></td>
                  </tr>

                  <tr>
                   <td><li><?=SFB?><a href=<?=$page?>?op=change_pw&db_table=<?=$db_table?>&tile=<?=$tile?>&id=<?=$id[0]?>|<?=$id[1]?>><?=UPDATEPW?></a><?=EF?></td>
                  </tr>

                  <tr><td><hr size=1></td></tr>

                  <tr>
                   <td><li><?=SFB?><a href=<?=$page?>?op=mail&tile=mail&step=2&email_type=domain_summary&id=<?=$id[1]?>><?=SENDDOMSUM?></a><?=EF?></td>
                  </tr>

                  <tr>
                   <td><li><?=SFB?><a href=<?=$page?>?op=mail&tile=mail&step=2&email_type=package_summary&id=<?=$id[1]?>><?=SENDPACKSUM?></a><?=EF?></td>
                  </tr>

                  <tr>
                   <td><li><?=SFB?><a href=<?=$page?>?op=mail&tile=mail&step=2&email_type=account_details&id=<?=$id[1]?>><?=SENDACTDETAILS?></a><?=EF?></td>
                  </tr>

                  <tr>
                   <td><li><?=SFB?><a href=<?=$page?>?op=mail&tile=mail&step=2&email_type=inv_summary&id=<?=$id[1]?>><?=SENDINVHISTORY?></a><?=EF?></td>
                  </tr>

                  <tr><td><hr size=1></td></tr>

                  <tr>
                   <td><li><?=SFB?><a href="#" target="geninvoice" onClick='window.open("<?=$page?>?op=gen_inv&tile=billing&client_id=<?=$id[1]?>", "geninvoice", "width=475, height=380, status=yes, scrollbars=1, resizable=1"); return false;'><?=GI?></a><?=EF?></td>
                  </tr>

                  <tr><td><hr size=1></td></tr>

                  <form method=post action=<?=$admin_page?>>
                  <? $details_view = 0; ?>
                  <tr>
                   <td align=center><b><?=SFB.UPDATESTATUS.EF?></b></td>
                  </tr>
                  <tr>
                   <td>
                    <?=status_select_box($this_client['client_status'],"new_status_id")."&nbsp;".GO_IMG?>
                   </td>
                  </tr>
                  <? $details_view = 1; ?>
                  <input type=hidden name=op value="quick_status_update">
                  <input type=hidden name=client_id value="<?=$this_client['client_id']?>">
                  <input type=hidden name=tile value="<?=$tile?>">
                  <input type=hidden name=this_status_id value="<?=$this_client['client_status']?>">
                  </form>

                  <tr>
                   <td><hr size=1></td>
                  </tr>

                  <form method=post action=<?=$admin_page?>>
                  <? $details_view = 0; ?>
                  <tr>
                   <td align=center><b><?=SFB.WELCOMEEMAIL.EF?></b></td>
                  </tr>
                  <tr>
                   <td>
                    <?=email_signup_select_box(NULL,"email_id")?>
                   </td>
                  </tr>
                  <tr>
                   <td>
                    <?=cp_select_box($this_client['client_id'],NULL)."&nbsp;".GO_IMG?>
                   </td>
                  </tr>
                  <? $details_view = 1; ?>
                  <input type=hidden name=op value="mail">
                  <input type=hidden name=id value="<?=$this_client['client_id']?>">
                  <input type=hidden name=tile value="mail">
                  <input type=hidden name=step value="2">
                  <input type=hidden name=email_type value="package_welcome">
                  <input type=hidden name=this_status_id value="<?=$this_client['client_status']?>">
                  </form>

                  </table>

                </td>
              </tr>
            </table>
            <?=stop_box()?>

           </td>
         </tr>
       </table>
    </td>
  </tr>
</table>


<table width="<?=$a_tile_width?>" border=0 align=center cellspacing=0 cellpadding=2 bgcolor=FFFFFF>
  <tr>

    <? /* CLIENT PACKAGE FULL <TR></TR> */ ?>
    <td>
    <br>
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
         $client_id    = $id[1];
         $db_table     = "client_package";
         $where        = "WHERE client_id = $id[1]";
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
         $op = "client_package";
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
                 <td bgcolor=FFFFFF align=right valign=center>
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
         ?>
         </table>
         </td></tr>
         <tr><td bgcolor=FFFFFF align=center><?=PieceNavigation($db_table,$limit,$where)?><br></td></tr>
         </table>
         <?=stop_box()?>
    </td>
  </tr>

 <? /* CLIENT REGISTER FULL <TR></TR> */ ?>
 <tr>
    <td>
    <br>
    <?
    GLOBAL $sort;
    $db_table  = "client_register";
    $op        = "menu";
    $tile      = "client_register";
    $order     = "reg_date";
    $sort      = NULL;
    $suppress_add = 1;
    ?>
    <?=display_account_register($db_table,"WHERE client_id = $id[1]",$order,$sort,$offset,$limit)?>
    </td>
 </tr>

 <? /* CLIENT NOTES FULL <TR></TR> */ ?>
 <tr>
    <td>
    <br>
    <?
    $recursive   = 1;
    $selectlimit = $limit_client_notes;
    $where       = "WHERE client_id = $id[1] ORDER BY log_id DESC";
    $db_table    = "event_log";
    include("include/db_attributes.inc.php");
    ?>
    <?=start_box("$title [<a href=$page?op=form&db_table=$db_table&tile=$tile&$id[0]=$id[1]&from=client_id><b>".SFW.ADD.EF."</b></a>]")?>
      <table width="100%" border=0 align=center cellspacing=0 cellpadding=2>
        <tr>
          <td>
            <?
            start_table(NULL,"100%");
            echo "<tr><td>";
            $details_link = "details";
            //display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
            $details_view = 1;
            display_list(NULL,$select_sql,$where,$db_table,NULL,NULL,NULL,NULL);
            echo "</td></tr>";
            if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode("WHERE $id[0]=$id[1] ").">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
            stop_table();
            ?>
          </td>
         </tr>
      </table>
    <?=stop_box()?>
    <? $recursive=$recursive_sql = NULL; ?>
    </td>
 </tr>

</table>

<?
stop_html();
?>