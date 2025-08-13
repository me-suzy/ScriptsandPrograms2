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

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

GLOBAL $total_due;

if(!$dbh)dbconnect();

## Mark Invoice as paid
if ($make_payments && $do=="edit" && $invoice_id) {

    $oops     = NULL;
    $submit   = 1;
    $db_table = "client_invoice";
    include("include/db_attributes.inc.php");
    include("include/misc/validate_form_input.inc.php");

    $oops .= (!$invoice_date_paid) ? "[".REQUIRED."] ".DATEPAID."<br>" : NULL ;
    $oops .= (!$invoice_amount_paid) ? "[".REQUIRED."] ".AMOUNTPAID."<br>" : NULL ;
    $oops .= (!$trans_id) ? "[".REQUIRED."] ".TRANSID."<br>" : NULL ;

    if ($oops) {
        $response = "<br>".LFB.INVOICE.": $invoice_id <br> <font color=red><b>".$oops."</b></font>".EF;
    } else {

        ## client_register entry
        $reg_desc = MANUALPAYMENT.": $trans_id";
        $reg_payment = $invoice_amount_paid;
        $this_invoice = mysql_one_array("SELECT * FROM client_invoice WHERE invoice_id = $invoice_id");
        register_insert($this_invoice[client_id],$reg_desc,$invoice_id,NULL,$reg_payment);

        ## update the invoice
        $pay_update_sql = "UPDATE client_invoice
                           SET invoice_amount_paid=invoice_amount_paid+$invoice_amount_paid,
                           invoice_date_paid='".date_to_stamp($invoice_date_paid)."',
                           invoice_payment_method='$invoice_payment_method',
                           auth_return='$auth_return',
                           auth_code='$auth_code',
                           avs_code='$avs_code',
                           trans_id='$trans_id',
                           batch_stamp='".date_to_stamp($batch_stamp)."',
                           invoice_stamp='".mktime()."'
                           WHERE invoice_id='$invoice_id'";
        if($debug)echo SFB.$pay_update_sql.EF."<br>";
        if (!mysql_query($pay_update_sql,$dbh)) { echo mysql_errno(). ": ".mysql_error(). "<br>"; return; }

        $total_paid_amount = $this_invoice[invoice_amount_paid] + $invoice_amount_paid;
        if ($this_invoice[invoice_amount]==$total_paid_amount) {
            @mysql_query("DELETE FROM authnet_batch WHERE x_Invoice_Num = $invoice_id",$dbh);
        } elseif ($this_invoice[invoice_amount]>$total_paid_amount) {
            $x_Amount = $this_invoice[invoice_amount] - $total_paid_amount;
            @mysql_query("UPDATE authnet_batch SET x_Amount = '$x_Amount' WHERE x_Invoice_Num = $invoice_id",$dbh);
        }


        if ( ( $override_email ) ||
             ( $send_client_email && $manual_email_id ) ) {
            $email_id       = $manual_email_id;
            $email_type     = "invoice";
            $where          = "i.invoice_id = $invoice_id";
            $email_to[0]    = mysql_one_data("SELECT client_id FROM client_invoice WHERE invoice_id = $invoice_id");
            $email_cc       = $inv_email_cc;
            $email_priority = $inv_email_priority;
            $email_subject  = $inv_email_subject;
            $email_from     = $inv_email_from;
            $email_body     = "%%LEAVE_FOR_ORIGINAL_INVOICE_HERE%%";
            @send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from);
        }
        $response = "<br>".LFB.INVOICE.": $invoice_id -- ".display_currency($invoice_amount_paid)." -- <font color=green><b>".PAID."</b></font>".EF;
    }
}


$num_invoices=$num_invoices_paid=$num_invoices_unpaid=$num_batch=$amount_invoices_paid=$amount_invoices_unpaid=$sum_batch=0;
if ($tier2) { list($num_batch,$sum_batch)=mysql_fetch_row(mysql_query("SELECT count(an_id), sum(x_Amount) FROM authnet_batch")); }
if ($tier2) { list($num_declined,$sum_declined)=mysql_fetch_row(mysql_query("SELECT count(a.an_id), sum(a.x_Amount) FROM authnet_batch a, client_invoice i WHERE a.x_Invoice_Num=i.invoice_id AND i.auth_return=2")); }
if ($tier2) { list($num_error,$sum_error)=mysql_fetch_row(mysql_query("SELECT count(a.an_id), sum(a.x_Amount) FROM authnet_batch a, client_invoice i WHERE a.x_Invoice_Num=i.invoice_id AND i.auth_return=3")); }
list($num_invoices_paid,$amount_invoices_paid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount) FROM client_invoice WHERE invoice_date_paid!=0 AND invoice_amount <= invoice_amount_paid"));
list($num_invoices_unpaid,$amount_invoices_unpaid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount-invoice_amount_paid) FROM client_invoice WHERE invoice_amount > invoice_amount_paid"));
$num_invoices=$num_invoices_paid+$num_invoices_unpaid;
   ?>
        <tr>
          <td>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=INVOICESTATS?>:</b><?=EF?></td><td><?=LFH?><b><?=INVOICESEARCH?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=view&db_table=client_invoice&tile=<?=$tile?>><?=VIEWALL?></a>]<?=EF?></td></tr>
             <tr>
               <td width=50% valign=top>
                 <table width=80%>
                  <tr><td width=60%>
                       <?=MFB?>
                       <?=TOTALINVOICES?>:<br>
                       <?=TOTALPAID?>:<br>
                       <?=TOTALDUE?>:<br>
                       <? if ($tier2) { ?><?=TOTALCURRENTBATCH?>:<br><? } ?>
                       <? if ($tier2) { ?><?=TOTALDECLINED?>:<br><? } ?>
                       <? if ($tier2) { ?><?=TOTALERROR?>:<br><? } ?>
                       <?=EF?>
                      </td>
                      <td align=right>
                       <?=MFB?>
                       <?=$num_invoices?><br>
                       <?=$num_invoices_paid?><br>
                       [<a href=<?=$page?>?op=view&db_table=client_invoice&tile=<?=$tile?>&where=<?=urlencode("WHERE invoice_amount > invoice_amount_paid")?>><?=$num_invoices_unpaid?></a>]<br>
                       <? if ($tier2) { ?>[<a href=<?=$page?>?op=view&db_table=authnet_batch&tile=<?=$tile?>><?=$num_batch?></a>]<br><?}?>
                       <? if ($tier2) { ?>[<a href=<?=$page?>?op=view&db_table=client_invoice&tile=<?=$tile?>&where=<?=urlencode("WHERE auth_return=2")?>><?=$num_declined?></a>]<br><?}?>
                       <? if ($tier2) { ?>[<a href=<?=$page?>?op=view&db_table=client_invoice&tile=<?=$tile?>&where=<?=urlencode("WHERE auth_return=3")?>><?=$num_error?></a>]<br><?}?>
                       <?=EF?>
                      </td>
                      <td align=right>
                       <?=MFB?>
                       &nbsp;<br>
                       <?=display_currency($amount_invoices_paid)?><br>
                       <?=display_currency($amount_invoices_unpaid)?><br>
                       <? if ($tier2) {?><?=display_currency($sum_batch)?><br><?}?>
                       <? if ($tier2) {?><?=display_currency($sum_declined)?><br><?}?>
                       <? if ($tier2) {?><?=display_currency($sum_error)?><br><?}?>
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
                  <input type=hidden name=db_table value=client_invoice>
                  <tr><td colspan=2><?=invoice_search_select_box();?></td></tr>
                  <tr><td><input type=text name=query size=15 maxlength=25></td><td><?=SEARCH_IMG?></td></tr>
                  </form>
                  </table>
               </td>
             </tr>
            </table>
           <hr size=1 width=98%>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=BATCHSETUP?>:</b><?=EF?></td><td><? if ($tier2) {?><?=LFH?><b><?=SEARCHBATCHDETAILS?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=view&db_table=batch_details&tile=<?=$tile?>><?=VIEWALL?></a>]<?}?></td></tr>
             <tr><td width=50% valign=top>
                   <?=MFB?>
                   &nbsp;<b><?=STEP?> 1:</b>&nbsp;<a href="#" target="geninvoice" onClick='window.open("<?=$page?>?op=gen_inv", "geninvoice", "width=475, height=380, status=yes, scrollbars=1, resizable=1"); return false;'><?=GI?></a><br>
                   <? if ($tier2) {?>&nbsp;<b><?=STEP?> 2:</b>&nbsp;<a href=<?=$page?>?op=gen_batch&tile=<?=$tile?>><?=GB?></a>&nbsp;|&nbsp;<a href=<?=$page?>?op=clear_batch&tile=<?=$tile?>><?=CLEARBATCH?></a><br><?}?>
                   <? if ($tier2) {?>&nbsp;<b><?=STEP?> 3a:</b>&nbsp;<a href="#" target="runbatch" onClick='window.open("<?=$page?>?op=run_batch", "runbatch", "width=475, height=380, status=yes, scrollbars=1, resizable=1"); return false;'><?=BATCH?>: <?=RUN?></a><br><?}?>
                   <? if ($tier2) {?>&nbsp;<b><?=STEP?> 3b:</b>&nbsp;<a href=<?=$page?>?op=exp_batch&tile=<?=$tile?>><?=BATCH?>: <?=EXPORT?></a><br><?}?>
                   <?=EF?>
                 </td>
                 <td width=50% valign=top>
                  <? if ($tier2) {?>
                  <table>
                  <form method=post action=<?=$page?>>
                  <input type=hidden name=op value=view>
                  <input type=hidden name=search value=1>
                  <input type=hidden name=tile value=<?=$tile?>>
                  <input type=hidden name=db_table value=batch_details>
                  <tr><td colspan=2><?=batch_details_search_select_box();?></td></tr>
                  <tr><td><input type=text name=query size=15 maxlength=25></td><td><?=SEARCH_IMG?></td></tr>
                  </form>
                  </table>
                  <?}?>
               </td>
              </tr>
             </table>

           <hr size=1 width=98%>

           <?=start_box(QUICKPAYMENTS)?>
           <form method=post action=<?=$page?>?op=menu&tile=<?=$tile?>>
           <input type=hidden name=make_payments value=1>
           <input type=hidden name=do value=edit>

            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr>
                <td colspan=2>
                  <table>
                   <tr>
                    <td>&nbsp;</td>
                    <td><?=SFB."[".str_pad(ID,10,"_").
                                   str_pad(TOTALDUE,15,"_").
                                   str_pad(NAME,30,"_").
                                   str_pad(DUEDATE,12,"_",STR_PAD_LEFT)."]".EF?></td>
                   </tr>
                   <tr>
                    <td align=right><b><?=SFB.SELECTINVOICE.":".EF?></b></td>
                    <td><?=invoice_select_box($id)?>&nbsp;<?=SUBMIT_IMG?></td>
                   </tr>
                   <tr>
                    <td>&nbsp;</td>
                    <td><input type=checkbox name=override_email value=1 CHECKED>&nbsp;<?=CHECKTOSEND?></td>
                   </tr>
                  </table>
                  <br><br>
                </td>
             </tr>
             <tr valign=top>
                 <td width="50%">
                   <table width="100%" border="0" cellspacing="1" cellpadding="1">
                     <tr>
                       <td width="25%" align=right><?=SFB?><?=DATEPAID?>:<?=EF?></td>
                       <td width="50%"><input type=text name=invoice_date_paid value="<?=date($date_format)?>" size=15 maxlength=255>&nbsp;<?=SFB."($date_format)".EF?></td>
                     </tr>
                     <tr>
                       <td width="25%" align=right><?=SFB?><?=AMOUNTPAID?>:<?=EF?></td>
                       <td width="50%"><input type=text name=invoice_amount_paid value="" size=15 maxlength=255></td>
                     </tr>
                     <tr>
                       <td width="25%" align=right><?=SFB?><?=PAYMENTMETHOD?>:<?=EF?></td>
                       <td width="50%"><?=payment_select_box($invoice_payment_method)?></td>
                     </tr>
                     <tr>
                       <td width="25%" align=right><?=SFB?><?=TRANSID?>:<?=EF?></td>
                       <td width="50%"><input type=text name=trans_id value="" size=15 maxlength=255>&nbsp;<?=SFB."(".IDORNUM.")".EF?></td>
                     </tr>
                   </table>
                 </td>
                 <td width="50%">
                   <table width="100%" border="0" cellspacing="1" cellpadding="1">
                     <tr>
                       <td width="25%" align=right><?=SFB?><?=BATCHDATE?>:<?=EF?></td>
                       <td width="50%"><input type=text name=batch_stamp value="" size=15 maxlength=255>&nbsp;<?=SFB."($date_format)".EF?></td>
                     </tr>
                     <tr>
                       <td width="25%" align=right><?=SFB?><?=AUTHRET?>:<?=EF?></td>
                       <td width="50%"><input type=text name=auth_return value="" size=15 maxlength=255></td>
                     </tr>
                     <tr>
                       <td width="25%" align=right><?=SFB?><?=AUTHCODE?>:<?=EF?></td>
                       <td width="50%"><input type=text name=auth_code value="" size=15 maxlength=255></td>
                     </tr>
                     <tr>
                       <td width="25%" align=right><?=SFB?><?=AVS?>:<?=EF?></td>
                       <td width="50%"><input type=text name=avs_code value="" size=15 maxlength=255></td>
                     </tr>
                   </table>
                 </td>
               </tr>
             </table>
             <center><b><?=$response?></b></center>
           </form>
           <?=stop_box()?>
          </td>
        </tr>