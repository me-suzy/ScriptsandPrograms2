<?
            if(!$dbh)dbconnect();
            $num_invoices=$num_invoices_paid=$num_invoices_unpaid=$num_batch=$amount_invoices_paid=$amount_invoices_unpaid=$sum_batch=0;
            list($num_invoices_paid,$amount_invoices_paid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount) FROM client_invoice WHERE client_id=$this_user[0] AND invoice_date_paid!=0 AND invoice_amount <= invoice_amount_paid"));
            list($num_invoices_unpaid,$amount_invoices_unpaid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount-invoice_amount_paid) FROM client_invoice WHERE client_id=$this_user[0] AND invoice_amount > invoice_amount_paid"));
            $num_invoices=$num_invoices_paid+$num_invoices_unpaid;
?>
        <tr>
          <td align=center>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=INVOICESTATS?>:</b><?=EF?></td><td><?=LFH?><b><?=INVOICESEARCH?>:</b><?=EF?> <?=SFB."[<a href=$page?op=view&tile=$tile&id=all>".VIEWALL."</a>]".EF?></td></tr>
             <tr>
               <td width=50% valign=top>
                 <table width=80%>
                  <tr><td>
                       <?=MFB?>
                       <?=MYINVOICES?>:<br>
                       <?=TOTALPAID?>:<br>
                       <?=TOTALNOWDUE?>:<br>
                       <?=EF?>
                      </td>
                      <td align=right>
                       <?=MFB?>
                       [<a href=<?=$page?>?op=view&tile=<?=$tile?>&id=all><?=$num_invoices?></a>]<br>
                       [<a href=<?=$page?>?op=view&tile=<?=$tile?>&id=paid><?=$num_invoices_paid?></a>]<br>
                       [<a href=<?=$page?>?op=view&tile=<?=$tile?>&id=due><?=$num_invoices_unpaid?></a>]<br>
                       <?=EF?>
                      </td>
                      <td align=right>
                       <?=MFB?>
                       &nbsp;<br>
                       <?=display_currency($amount_invoices_paid);?><br>
                       <?=display_currency($amount_invoices_unpaid);?><br>
                       <?=EF?>
                      </td>
                   </tr>
                  </table>
               </td>
               <td width=50% valign=top>
                  <table>
                  <form method=post action=user.php>
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
            <? if ($tier2&&($authnet_enabled||$echo_enabled)) { ?>
            <?=SFB?>
            <br><blockquote><?=MYSEARCHHELP?></blockquote>
            <?=EF?>
            <? } ?>
          </td>
        </tr>
