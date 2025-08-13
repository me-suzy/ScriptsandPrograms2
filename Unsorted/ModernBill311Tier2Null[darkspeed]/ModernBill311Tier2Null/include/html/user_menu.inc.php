<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/


## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is a USER or log them out
if (!testlogin()||!$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

start_html();
user_heading($tile);
start_table(NULL,$a_tile_width,"center","#999999");

$include_path = "include/html/cases";
switch ($tile) {

   case contact:    include("$include_path/user.$tile.case.inc.php"); break;
   case mydomains:  include("$include_path/user.$tile.case.inc.php"); break;
   case myinfo:     include("$include_path/user.$tile.case.inc.php"); break;
   case myinvoices: include("$include_path/user.$tile.case.inc.php"); break;
   case mypackages: include("$include_path/user.$tile.case.inc.php"); break;
   case mynews:     include("$include_path/user.$tile.case.inc.php"); break;
   case mysupport:  include("$include_path/user.$tile.case.inc.php"); break;
   //case myplesk:    include("$include_path/user.$tile.case.inc.php"); break;
   //case myserver:   include("$include_path/user.$tile.case.inc.php"); break;
   default:
        if(!$dbh)dbconnect();
        $num_invoices_unpaid = $amount_invoices_unpaid = 0;
        list($num_invoices_unpaid,$amount_invoices_unpaid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount-invoice_amount_paid) FROM client_invoice WHERE client_id=$this_user[0] AND invoice_amount > invoice_amount_paid"));
        ?>
        <tr>
         <td align=center>
           <blockquote>
            <?=MFB."<b>".WELCOME." ".$this_user["client_fname"]." ".$this_user["client_lname"]?>!</b><br>
            <? if ($num_invoices_unpaid>0) { ?>
               <hr size=1 width=50%>
               <b><?=INVOICESNOWDUE?>:</b> <?=$num_invoices_unpaid?> @ <?=display_currency($amount_invoices_unpaid)?>
                  <? if ($tier2&&($paypal_enabled||$worldpay_enabled||$echo_enabled||$authnet_enabled)) { ?>
                     [<a href=<?=$page?>?op=view&tile=myinvoices&id=due><?=PAYONLINE?></a>]
                  <? } ?>
            <? } ?>
        <?
          if ($language_enabled || $theme_enabled)
          {
        ?>
            <hr size=1 width=50%>
            <form method=post action=<?=$page?>>
            <table align=center border=0 cellpadding=2 cellspacing=2>
              <tr><td colspan=2 align=center><?=SFB?><b><?=PLEASESELOPTIONS?></b><?=EF?></td></tr>
              <? if ($language_enabled) { ?> <tr><td width=30% align=right><?=SFB.LANGUAGE?>:<?=EF?></td><td><?=language_select_box($language);?></td></tr> <? } ?>
              <? if ($theme_enabled) { ?> <tr><td width=30% align=right><?=SFB.THEME?>:<?=EF?></td><td><?=theme_select_box($theme);?></td></tr> <? } ?>
            </table>
            <center><?=SUBMIT_IMG?></center>
            </form>
        <? } ?>
           </blockquote>
         </td>
        </tr>
        <?
   break;
}

stop_table();
stop_html();
?>