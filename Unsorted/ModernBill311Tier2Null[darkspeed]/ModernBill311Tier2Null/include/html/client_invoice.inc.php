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
if (!testlogin())  { Header("Location: http://$standard_url?op=logout"); exit; }

session_register('session_from');
$session_from = "op=client_invoice&db_table=client_invoice&tile=billing&id=$id";

GLOBAL $success,
       $this_admin,
       $this_user;

       $details_view = 1;
       $id = explode("|",$id);
       $this_invoice = mysql_one_array("SELECT * FROM client_invoice WHERE invoice_id = $id[1]");
       if ($this_invoice[invoice_id]==0) { echo ERROR; exit; }
       $this_client  = @mysql_one_array("SELECT * FROM client_info WHERE client_id = ".$this_invoice[client_id]."");

       if (!$this_invoice[invoice_id])
       {
            start_short_html($title);
            echo SFB."<center>[".ERROR."] ".MISSINGORINVALID."</center>".EF;
            stop_short_html();
            exit;
       }

       $total_due = $this_invoice[invoice_amount] - $this_invoice[invoice_amount_paid];

## START INVOICE
start_short_html($title);
?>
<table width=700 border=0 align=center cellspacing=0 cellpadding=4>
  <tr>
    <td>
     <center><b><?=LFH?><font size=+1><?=INVOICECAPS?></font><?=EF?></b></center>
    </td>
  </tr>

  <tr valign=top><td><hr size=1 noshade></td></tr>

  <tr>
    <td>
        <table width=100% border=0>
               <tr>
                <td><?=MFH.nl2br($invoice_address).EF?></td>
                <td align=center valign=middle>
                    <?
                    if ( ($this_invoice['invoice_amount']==$this_invoice['invoice_amount_paid']) ) {
                        $is_paid = TRUE;
                        echo LFB."<font color=green size=+1><b>".PAID2."</b></font><br>".EF;
                    } elseif ( ($this_invoice['invoice_amount']>$this_invoice['invoice_amount_paid']) &&
                               ($this_invoice['invoice_date_paid']==0) &&
                               ($this_invoice['invoice_date_due'] < mktime() ) ) {
                        echo LFB."<font color=red size=+1><b>".OVERDUE."</b></font><br>".EF;
                        $now_due=1;
                    } else {
                        echo LFB."<font color=gray size=+1><b>".NOWDUE."</b></font><br>".EF;
                        $now_due=1;
                    }
                    if ($now_due&&$this_admin&&!$success) {
                        ?>
                        <form method=POST action=<? echo "$page?op=mail&db_table=$db_table&tile=mail&id=$id[0]|$id[1]"; ?>>
                        <input type=hidden name=resend_email value=1>
                        <input type=hidden name=step value=2>
                        <input type=hidden name=email_type value=from_invoice>
                        <?=$email_config?>
                        <input type=submit name=submit value="<?=RESEND?>!">
                        </form>
                        <?
                    }
                    if ($now_due&&$tier2&&($authnet_enabled||$echo_enabled)) {
                        if ($this_admin) { $url_args = "&client_id=$this_invoice[client_id]"; }
                        echo MFB."<b><a href=# onClick=OpenWindow('$https://$secure_url"."$page?op=pay_invoice&id=".$this_invoice['invoice_id']."$url_args','SecureOnlinePayment','toolbar=no,location=no,status=yes,menubar=no,scrollbars=no,resizable=yes,width=300,height=300')>".PAYONLINE."</a></b>".EF."<br><br>";
                    }
                    if ($this_user&&$now_due&&$paypal_enabled&&$tier2) {
                        $pp_item_number=$this_invoice["invoice_id"];
                        $pp_amount=$this_invoice["invoice_amount"]-$this_invoice["invoice_amount_paid"];
                        echo generate_paypal_link($pp_item_name,$pp_item_number,$pp_amount,"button");
                    }
                    if ($this_user&&$now_due&&$worldpay_enabled&&$tier2) {
                        $pp_item_number=$this_invoice["invoice_id"];
                        $pp_amount=$this_invoice["invoice_amount"]-$this_invoice["invoice_amount_paid"];
                        echo generate_worldpay_link($pp_item_name,$pp_item_number,$pp_amount,"button");
                    }
                    if ($this_admin&&!$this_user) {
                        echo SFB."<a href=\"$page?op=form&db_table=client_invoice&tile=$tile&id=invoice_id|$this_invoice[invoice_id]\">".EDIT_IMG."</a>".EF;
                        echo "&nbsp;";
                        echo SFB."<a href=\"$page?op=delete&db_table=client_invoice&tile=$tile&id=invoice_id|$this_invoice[invoice_id]&session_from=0\">".DELETE_IMG."</a>".EF;
                    }
                    ?>
                </td>
              </tr>
        </table>
    </td>
  </tr>

  <tr valign=top><td><hr size=1 noshade></td></tr>

  <tr>
    <td>
        <table width=100% border=0>
               <tr>
                <td valign=middle><blockquote>
                    <?=MFB?>
                       <? if ($this_admin) { ?> <a href="<?=$page?>?op=client_details&db_table=client_info&tile=client&id=client_id|<?=$this_invoice[client_id]?>">[<?=ID.": ".$this_invoice[client_id]?>]</a><br> <? } ?>
                       <b><?=$this_client[client_fname]?> <?=$this_client[client_lname]?></b><br>
                       <? if ($this_client[client_company]) { echo $this_client[client_company]."<br>"; } ?>
                       <i><?=$this_client[client_address]?></i><br>
                       <i><?=$this_client[client_city]?>, <?=$this_client[client_state]?> <?=$this_client[client_zip]?></i><br>
                       <?=$this_client[client_phone1]?><br>
                       <? if ($this_client[client_phone2]) { echo $this_client[client_phone2]; } ?>
                    <?=EF?>
                    </blockquote>
                </td>
                <td valign=middle>
                  <table border="0" cellspacing="0" cellpadding="1" align=center>
                  <tr>
                  <td bgcolor=DDDDDD>
                    <table border="0" cellspacing="1" cellpadding="3" align=center width=100%>
                    <tr>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><b><?=SFB.INVOICENUM.EF?></b></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><b><?=SFB.CREATEDON.EF?></b></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><b><?=SFB.DUEDATE.EF?></b></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><b><?=SFB.AMOUNT.EF?></b></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><b><?=SFB.TOTALDUE.EF?></b></nobr></td>
                    </tr>
                    <tr>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><?=SFB.$this_invoice[invoice_id].EF?></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><?=SFB.date_input_generator($this_invoice[invoice_date_entered],"invoice_date_entered").EF?></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><?=SFB.date_input_generator($this_invoice[invoice_date_due],"invoice_date_due").EF?></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><?=SFB.display_currency($this_invoice[invoice_amount]).EF?></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><?=SFB.display_currency($total_due).EF?></nobr></td>
                    </tr>
                    <tr>
                    <td colspan=5 bgcolor=FFFFFF>&nbsp;</td>
                    </tr>
                    <tr>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><b><?=SFB.BATCHDATE.EF?></b></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><b><?=SFB.AUTHRET.EF?></b></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><b><?=SFB.AUTHCODE.EF?></b></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><b><?=SFB.AVS.EF?></b></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><b><?=SFB.TRANSID.EF?></b></nobr></td>
                    </tr>
                    <?
                    $batch_stamp = ($this_invoice[batch_stamp]) ? date_input_generator($this_invoice[batch_stamp],"batch_stamp") : "-";
                    $auth_return = ($this_invoice[auth_return]) ? $this_invoice[auth_return] : "-";
                    $auth_code = ($this_invoice[auth_code]) ? $this_invoice[auth_code] : "-";
                    $avs_code = ($this_invoice[avs_code]) ? $this_invoice[avs_code] : "-";
                    $trans_id = ($this_invoice[trans_id]) ? $this_invoice[trans_id] : "-";
                    ?>
                    <tr>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><?=SFB.$batch_stamp.EF?></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><?=SFB.$auth_return.EF?></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><?=SFB.$auth_code.EF?></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><?=SFB.$avs_code.EF?></nobr></td>
                    <td width="20%" align=center bgcolor=FFFFFF><nobr><?=SFB.$trans_id.EF?></b></nobr></td>
                    </tr>
                    </table>
                  </td>
                  </tr>
                  </table>
                </td>
              </tr>
        </table>
    </td>
  </tr>

  <tr valign=top><td><hr size=1 noshade></td></tr>

  <tr valign=top><td align=center><?=SFB.$this_invoice[invoice_snapshot].EF?></td></tr>

  <tr valign=top><td><hr size=1 noshade></td></tr>

  <?  if ($this_invoice[invoice_comments]) { ?>
          <tr valign=top>
            <td align=center><?=SFB.$this_invoice[invoice_comments].EF?></td>
          </tr>
          <tr valign=top><td><hr size=1 noshade></td></tr>
  <? } ?>

  <?  if ($this_invoice[invoice_footer]) { ?>
          <tr valign=top>
            <td align=center><?=SFB.$this_invoice[$invoice_footer].EF?></td>
          </tr>
          <tr valign=top><td><hr size=1 noshade></td></tr>
  <? } ?>

  <tr>
    <td>
    <?
    $client_id    = $this_invoice[client_id];
    $invoice_id   = $this_invoice[invoice_id];
    $invoice_page = TRUE;
    $db_table     = "client_register";
    $order        = ($order) ? $order : "reg_date";
    $id           = "invoice_id|$invoice_id";
    ?>
    <?=display_account_register($db_table,"WHERE invoice_id = $this_invoice[invoice_id]",$order,$sort,$offset,$limit)?>
    </td>
  </tr>

</table>
<?=stop_short_html()?>