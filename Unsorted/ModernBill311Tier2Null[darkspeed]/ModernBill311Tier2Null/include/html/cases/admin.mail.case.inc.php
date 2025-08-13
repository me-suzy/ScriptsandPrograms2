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

if(!$dbh)dbconnect();
$num_emails=0;

@list($num_emails)=mysql_fetch_row(mysql_query("SELECT count(email_id) FROM email_config",$dbh));
   ?>
        <tr>
          <td align=center>


            <table cellpadding=2 cellspacing=2 border=0 align=center width=100%>
             <tr>
               <td><?=LFH?><b><?=EMAILTEMPLATES?>:</b><?=EF?></td>
               <td><?=LFH?><b><?=EMAILSEARCH?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=view&db_table=email_config&tile=<?=$tile?>><b><?=VIEWALL?></b></a>]<?=EF?></td>
             </tr>
             <tr>
               <td width=50% valign=top>
                   <?=MFB.TOTAL.":".EF?>&nbsp;<?=MFB?>[<a href=<?=$page?>?op=view&db_table=email_config&tile=<?=$tile?>><?=$num_emails?></a>]&nbsp;[<a href=<?=$page?>?op=form&db_table=email_config&tile=<?=$tile?>><b><?=ADD?></b></a>]<?=EF?><?=EF?>
               </td>
               <td width=50% valign=top>
                  <table>
                  <form method=post action=<?=$page?>>
                  <input type=hidden name=op value=view>
                  <input type=hidden name=search value=1>
                  <input type=hidden name=tile value=<?=$tile?>>
                  <input type=hidden name=db_table value=email_config>
                  <tr><td colspan=2><?=email_search_select_box();?></td></tr>
                  <tr><td><input type=text name=query size=15 maxlength=25></td><td><?=SEARCH_IMG?></td></tr>
                  </form>
                  </table>
               </td>
             </tr>
            </table>


<? switch ($step) {
      default:
      ?>
      <hr size=1 width=98%>
      <form method=post action=<?=$page?>?tile=mail>
      <input type=hidden name=step value=2>
      <table cellpadding=2 cellspacing=2 border=0 align=center width=100%>
             <tr><td colspan=4><?=LFH?><b><?=SEND?> <?=EMAIL?> (<?=STEP1?> - <?=FILTER?>):</b><?=EF?> <?=SFB?><i><?=YOUCANFILTER?>:</i><?=EF?></td></tr>
             <tr>
               <td width=45% valign=top>
               <?=start_box(ALLGENERAL)?>
                <table width=100% border=0 cellpadding=2 cellspacing=2>
                 <tr>
                  <td valign=middle align=right><nobr><?=SFB?><b><?=GENERALEMAILS?>:</b><?=EF?></nobr></td>
                  <td valign=middle align=left><input type=radio name=email_type value=general checked> <?=SFB?><b>&lt;--<?=SELECT?></b><?=EF?></td>
                 </tr>
                 <tr>
                  <td valign=middle align=right><nobr><?=SFB?><b><?=CCEXPEMAILS?>:</b><?=EF?></nobr></td>
                  <td valign=middle align=left><input type=radio name=email_type value=exp_cc_all></td>
                 </tr>
                 <tr>
                  <td valign=middle align=right><nobr><?=SFB?><b><?=CLIENTSTATUS?>:</b><?=EF?></nobr></td>
                  <td valign=middle align=left>
                                  <select name=client_status>
                                  <option value=2 selected><?=ACTIVE?></option>
                                  <option value=1><?=INACTIVE?></option>
                                  <option value=3><?=PENDING?></option>
                                  <option value=4><?=CANCELED?></option>
                                  <option value=5><?=FRAUD?></option>
                                  <option value=0><?=ALL?></option>
                                  </select>
                  </td>
                 </tr>
                <tr>
                 <td width=10% valign=middle align=right><nobr>
                 <?=SFB?><b><?=SERVTYPE?>:</b><?=EF?>
                 </nobr>
                 </td>
                 <td valign=middle align=left>
                 <? echo server_type_select_box($id,1); ?>
                 </td>
                </tr>
                <tr>
                 <td width=10% valign=middle align=right><nobr>
                 <?=SFB?><b><?=SERVNAME?>:</b><?=EF?>
                 </nobr>
                 </td>
                 <td valign=middle align=left>
                 <? echo server_name_select_box($id,1); ?>
                 </td>
                </tr>
               </table>
              <?=stop_box()?>
              </td>

              <td width=10% valign=middle align=center><?=LFH?><b><?=OR_t?></b><?=EF?></td>

              <td width=45% valign=top align=center>
              <?=start_box(ALLINVOICE)?>
                <table width=100% border=0 cellpadding=2 cellspacing=2>
                  <tr>
                     <td valign=middle align=right><nobr><?=SFB?><b><?=INVOICEEMAILS?>:</b><?=EF?></nobr></td>
                     <td valign=middle align=left><input type=radio name=email_type value=invoice> <?=SFB?><b>&lt;--<?=SELECT?></b><?=EF?></td>
                   </tr>
                   <tr>
                     <td width=10% valign=middle align=right><nobr><?=SFB?><b><?=INVTYPE?>:</b><?=EF?></nobr></td>
                     <td width=20% valign=top align=left>
                         <select name=invoice_type>
                         <option value=1 ><?=INVNOWDUE?></option>
                         <option value=2 selected><?=INVOVERDUE?></option>
                         </select>
                     </td>
                   </tr>
                   <tr><td colspan=2 align=center><br><?=SFB?><b><?=HINT?>:</b><?=EF?> <?=SFB.STRREPLANCEHINT.EF?></td></tr>
                   <tr><td colspan=2>&nbsp;</td></tr>
                </table>
               <?=stop_box()?>
              </td>
             </tr>
        </table>

        <hr size=1 width=98%>
        <table cellpadding=2 cellspacing=2 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=SEND?> <?=EMAIL?> (<?=STEP2?> - <?=MESSAGE?>):</b><?=EF?></td></tr>
             <tr><td align=center><?=email_config_select_box($id,"email_title")?>&nbsp;<?=CONT_IMG?></td></tr>
        </table>
        </form>

        <hr size=1 width=98%>
        <table align=left>
          <tr>
          <td><?=LFH?><b><?=EMAILSHORTCUTS?>:</b><?=EF?>
          <ul>
            <li><a href=# target="shortcuts1" onClick='window.open("<?=$page?>?op=shortcuts&type=general", "shortcuts1", "width=625, height=480, status=yes, scrollbars=1, resizable=1"); return false;'>General Shortcuts</a>
            <li><a href=# target="shortcuts2" onClick='window.open("<?=$page?>?op=shortcuts&type=invoice", "shortcuts2", "width=625, height=480, status=yes, scrollbars=1, resizable=1"); return false;'>Invoice ONLY Shortcuts</a>
            <li><a href=# target="shortcuts3" onClick='window.open("<?=$page?>?op=shortcuts&type=sql", "shortcuts3", "width=625, height=480, status=yes, scrollbars=1, resizable=1"); return false;'>SQL Shortcuts</a>
            <li><a href=# target="shortcuts4" onClick='window.open("<?=$page?>?op=shortcuts&type=vortech", "shortcuts4", "width=625, height=480, status=yes, scrollbars=1, resizable=1"); return false;'>Vortech Signup ONLY Shortcuts</a>
            <li><a href=# target="shortcuts5" onClick='window.open("<?=$page?>?op=shortcuts&type=welcome", "shortcuts5", "width=625, height=480, status=yes, scrollbars=1, resizable=1"); return false;'>Welcome ONLY Shortcuts</a>
          </ul>
          </td>
          </tr>
         </table>

<? break;

             case 2:
             switch ($email_type) {

                 ## Display resolved single invoice for single client.
                 ##
                 ##
                 case from_invoice:
                      $id=explode("|",$id);
                      $this_invoice = load_invoice_array($id[1]);
                      if (!$this_invoice)  { echo SFB."<center>[".ERROR."] ".MISSINGORINVALID."</center>".EF; return; }
                      $this_client  = load_client_array($this_invoice["client_id"]);
                      switch($this_client["billing_method"])
                      {
                           case 1:  $email_id = $cc_email_id;     break;
                           case 2:  $email_id = $check_email_id;  break;
                           case 3:  $email_id = $cc_email_id;  break;
                           case 4:  $email_id = $check_email_id;  break;
                           case 5:  $email_id = $paypal_email_id; break;
                           case 6:  $email_id = $worldpay_email_id; break;
                           default: $email_id = $check_email_id;  break;
                      }
                      $email_to       = "<input type=hidden name=email_to[] value=".$this_client["client_id"].">".MFB."<b>".$this_client["client_fname"]." ".$this_client["client_lname"]." &lt;".$this_client["client_email"]."&gt;</b>".EF;
                      $email_subject  = replace_invoice_email($inv_email_subject,$email_id,$this_invoice["invoice_id"],1);
                      $email_body     = "%%LEAVE_FOR_ORIGINAL_INVOICE_HERE%%";
                      $body           = replace_invoice_email($email_body,$email_id,$this_invoice["invoice_id"],NULL);
                 break;

                 ## Display resolved package summary for single client.
                 ##
                 ##
                 case package_welcome:
                      $this_client   = load_client_array($id);
                      $email_to      = "<input type=hidden name=email_to[] value=".$this_client["client_id"].">".MFB."<b>".$this_client["client_fname"]." ".$this_client["client_lname"]." &lt;".$this_client["client_email"]."&gt;</b>".EF;
                      $email_subject = HELLO.": ".$this_client["client_fname"]." ".$this_client["client_lname"];
                      $this_email    = load_email_array($email_id);
                      $welcome_text  = $this_email['email_heading']."\n".$this_email['email_body']."\n".$this_email['email_footer']."\n".$this_email['email_signature'];
                      $welcome_text  = replace_generic_email($welcome_text,$this_client['client_id']);
                      $body          = replace_package_summary_email($welcome_text,$this_client['client_id'],$cp_id);
                 break;

                 ## Display resolved expired credit card notice for single client.
                 ##
                 ##
                 case exp_cc_single:
                      $this_client   = load_client_array($id);
                      $email_to      = "<input type=hidden name=email_to[] value=".$this_client["client_id"].">".MFB."<b>".$this_client["client_fname"]." ".$this_client["client_lname"]." &lt;".$this_client["client_email"]."&gt;</b>".EF;
                      $email_subject = HELLO.": ".$this_client["client_fname"]." ".$this_client["client_lname"];
                      $email_id      = $expired_cc_email_id;
                      $this_email    = load_email_array($email_id);
                      $body          = $this_email['email_heading']."\n".$this_email['email_body']."\n".$this_email['email_footer']."\n".$this_email['email_signature'];
                      $body          = replace_generic_email($body,$this_client['client_id']);
                 break;

                 ## Display unresolved general email template.
                 ##
                 ##
                 case exp_cc_all:
                      $sql = "SELECT client_id, client_fname, client_lname, client_email, billing_cc_exp
                              FROM client_info WHERE billing_cc_exp != '' ";
                      $sql .= ($client_status==0) ? NULL : "AND client_status=$client_status " ;
                      if($debug)echo SFB.$sql.EF."<br>";
                      $result=mysql_query($sql);
                      $num = mysql_num_rows($result);
                      if ($num >= 1) {
                         $email_to = "<select name=email_to[] multiple size=8>";
                         while(list($client_id, $client_fname, $client_lname, $client_email, $billing_cc_exp) = mysql_fetch_array($result)) {
                               list($cc_exp_month,$cc_exp_year) = explode("/",$billing_cc_exp);
                               if ( ( $cc_exp_month && $cc_exp_year ) && ( mktime(0,0,0,date("m"),date("d"),date("Y")) > mktime(0,0,0,$cc_exp_month,1,$cc_exp_year ) ) ) {
                                      $email_to.= "<option value=\"$client_id\" selected>$client_lname, $client_fname [$client_email]</option>\n";
                                      $matches_found = TRUE;
                               }
                         }
                         $no_matches_found = ($matches_found) ? NULL : TRUE ;
                         $email_to.= "</select>";
                      } else {
                         $email_to = "<input type=text name=email_to value=\"\" size=40 maxlength=255> ".SFB." (".SEPBYCOMMA.") ".EF;
                         $email_to.= "<input type=hidden name=none value=1>";
                         $no_matches_found=1;
                      }
                      $email_subject = HELLO.": ".$this_client["client_fname"]." ".$this_client["client_lname"];
                      $email_id      = $expired_cc_email_id;
                      $this_email    = load_email_array($email_id);
                      $body          = $this_email['email_heading']."\n".$this_email['email_body']."\n".$this_email['email_footer']."\n".$this_email['email_signature'];
                 break;

                 ## Display resolved package summary for single client.
                 ##
                 ##
                 case package_summary:
                      @list($total_active_packages)=mysql_fetch_row(mysql_query("SELECT count(pack_id) FROM client_package WHERE client_id = $id AND cp_status = 2"));
                      @list($total_inactive_packages)=mysql_fetch_row(mysql_query("SELECT count(pack_id) FROM client_package WHERE client_id = $id AND cp_status = 1"));
                      $this_client   = load_client_array($id);
                      $email_to      = "<input type=hidden name=email_to[] value=".$this_client["client_id"].">".MFB."<b>".$this_client["client_fname"]." ".$this_client["client_lname"]." &lt;".$this_client["client_email"]."&gt;</b>".EF;
                      $email_subject = PACKAGESUMMARY.": ".$this_client["client_fname"]." ".$this_client["client_lname"];
                      $body         .= PACKAGESUMMARY." (".stamp_to_date(mktime()).")\n";
                      $body         .= "\n--------\n\n";
                      $body         .= TOTALACTIVE.":\t$total_active_packages\n";
                      $body         .= TOTALINACTIVE.":\t$total_inactive_packages\n";
                      $body         .= "\n--------\n\n";
                      $body         .= DETAILS.":\n";
                      $body         .= "\n--------\n\n";
                      $result=mysql_query("SELECT * FROM client_package WHERE client_id = $id");
                      while($this_client_package=mysql_fetch_array($result))
                      {
                              @$this_package=mysql_fetch_array(mysql_query("SELECT * FROM package_type WHERE pack_id = ".$this_client_package["pack_id"].""));
                              $body .= PACKAGE.": \t".$this_package["pack_name"]."\n";
                              $body .= PRICE.": \t".display_currency($this_package["pack_price"])."\n";
                              $body .= QTY.": \t".$this_client_package["cp_qty"]."\n";
                              $body .= DISCOUNT.": \t".$this_client_package["cp_discount"]."\n";
                              $body .= BILLINGCYCLE.": \t".$cycle_types[$this_client_package["cp_billing_cycle"]]."\n";
                              $body .= STATUS.": \t".$status_types[$this_client_package["cp_status"]]."\n";
                              $body .= STARTDATE.": \t".stamp_to_date($this_client_package["cp_start_stamp"])."\n";
                              $body .= RENEWDATE.": \t".stamp_to_date($this_client_package["cp_renew_stamp"])."\n";
                              $body .= RENEWON.": \t".stamp_to_date($this_client_package["cp_renewed_on"])."\n";
                              $body .= "\n--------\n\n";
                      }
                 break;

                 ## Display resolved domain summary for single client.
                 ##
                 ##
                 case domain_summary:
                      @list($total_domains)=mysql_fetch_row(mysql_query("SELECT count(domain_id) FROM domain_names WHERE client_id = $id"));
                      $this_client   = load_client_array($id);
                      $email_to      = "<input type=hidden name=email_to[] value=".$this_client["client_id"].">".MFB."<b>".$this_client["client_fname"]." ".$this_client["client_lname"]." &lt;".$this_client["client_email"]."&gt;</b>".EF;
                      $email_subject = DOMAINSUMMARY.": ".$this_client["client_fname"]." ".$this_client["client_lname"];
                      $body         .= TTLDOMS.":\t$total_domains\n";
                      $body         .= "\n--------\n\n";
                      $body         .= DETAILS.":\n";
                      $body         .= "\n--------\n\n";
                      $result=mysql_query("SELECT * FROM domain_names WHERE client_id = $id ORDER BY domain_name");
                      while($this_domain=mysql_fetch_array($result))
                      {
                              $body .= DOMAIN.": \t".$this_domain["domain_name"]."\n";
                              $body .= CREATEDON.": \t".stamp_to_date($this_domain["domain_created"])."\n";
                              $body .= EXPIRES.": \t".stamp_to_date($this_domain["domain_expires"])."\n";
                              $body .= REGISTRAR.": \t".$registrar_types[$this_domain["registrar_id"]]."\n";
                              $body .= MONITOR.": \t".$monitor_types[$this_domain["monitor"]]."\n";
                              $body .= "\n--------\n\n";
                      }
                 break;

                 ## Display resolved invoice summary for single client.
                 ##
                 ##
                 case inv_summary:
                      $num_invoices_paid=$amount_invoices_paid=$num_invoices_unpaid=$amount_invoices_unpaid=0;
                      @list($num_invoices_paid,$amount_invoices_paid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount) FROM client_invoice WHERE invoice_date_paid!=0 AND client_id = $id"));
                      @list($num_invoices_unpaid,$amount_invoices_unpaid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount) FROM client_invoice WHERE invoice_date_paid=0 AND client_id = $id"));
                      $this_client   = load_client_array($id);
                      $email_to      = "<input type=hidden name=email_to[] value=".$this_client["client_id"].">".MFB."<b>".$this_client["client_fname"]." ".$this_client["client_lname"]." &lt;".$this_client["client_email"]."&gt;</b>".EF;
                      $email_subject = INVOICESUMMARY.": ".$this_client["client_fname"]." ".$this_client["client_lname"];
                      $body         .= INVOICESUMMARY." (".stamp_to_date(mktime()).")\n";
                      $body         .= "\n--------\n\n";
                      $body         .= TOTALPAID.":\t$num_invoices_paid for ".display_currency($amount_invoices_paid)."\n";
                      $body         .= TOTALDUE.":\t$num_invoices_unpaid for ".display_currency($amount_invoices_unpaid)."\n";
                      $body         .= "\n--------\n\n";
                      $body         .= DETAILS.":\n";
                      $body         .= "\n--------\n\n";
                      $result=mysql_query("SELECT * FROM client_invoice WHERE client_id = $id ORDER BY invoice_id DESC");
                      while($this_invoice=mysql_fetch_array($result))
                      {
                              $body .= INVNUM.":\t".$this_invoice["invoice_id"]."\n";
                              $body .= DATECREATED.":\t".stamp_to_date($this_invoice["invoice_date_entered"])."\n";
                              $body .= AMOUNT.":\t".display_currency($this_invoice["invoice_amount"])."\n";
                              $body .= DUEDATE.":\t".stamp_to_date($this_invoice["invoice_date_due"])."\n";
                              $body .= DATEPAID.":\t";
                              $body .= ($this_invoice["invoice_date_paid"]>0) ? stamp_to_date($this_invoice["invoice_date_paid"]) : NA ;
                              $body .= "\n";
                              $body .= PAID.":\t".display_currency($this_invoice["invoice_amount_paid"])."\n";
                              $body .= TRANSID.":\t".$this_invoice["trans_id"]."\n";
                              $body .= "\n--------\n\n";
                      }
                 break;

                 ## Display resolved account details for single client.
                 ##
                 ##
                 case account_details:
                      @list($accounts)=mysql_fetch_row(mysql_query("SELECT count(details_id) FROM account_details WHERE client_id = $id"));
                      $this_client   = load_client_array($id);
                      $email_to      = "<input type=hidden name=email_to[] value=".$this_client["client_id"].">".MFB."<b>".$this_client["client_fname"]." ".$this_client["client_lname"]." &lt;".$this_client["client_email"]."&gt;</b>".EF;
                      $email_subject = ACCOUNTDETAILS.": ".$this_client["client_fname"]." ".$this_client["client_lname"];
                      $body         .= ACCOUNTDETAILS." (".stamp_to_date(mktime()).")\n";
                      $body         .= "\n--------\n\n";
                      $result=mysql_query("SELECT * FROM account_details WHERE client_id = $id");
                      while($this_account=mysql_fetch_array($result))
                      {
                              @$this_domain=mysql_fetch_array(mysql_query("SELECT * FROM domain_names WHERE domain_id = ".$this_account["domain_id"].""));
                              $body .= DOMAIN.": \t".$this_domain["domain_name"]."\n";
                              $body .= IP.": \t".$this_account["ip"]."\n";
                              $body .= SERVNAME.": \t".$this_account["server"]."\n";
                              $body .= SERVTYPE2.": \t".$server_types[$this_account["server_type"]]."\n";
                              $body .= USERNAME.": \t".$this_account["username"]."\n";
                              $body .= PASSWORD_t.": \t".$this_account["password"]."\n";
                              //$body .= DBN.": \t".$this_account["dbname"]."\n";
                              //$body .= DBU.": \t".$this_account["dbuser"]."\n";
                              //$body .= DBP.": \t".$this_account["dbpass"]."\n";
                              $body .= "\n--------\n\n";
                      }
                 break;

                 ## Display unresolved general email template.
                 ##
                 ##
                 case general:
                      $sql = "SELECT DISTINCT c.client_id, c.client_fname, c.client_lname, c.client_email
                              FROM client_info c LEFT JOIN account_details d ON d.client_id=c.client_id ";
                      $sql .= ($client_status==0) ? NULL : "WHERE c.client_status=$client_status " ;
                      $sql .= ($client_status==0&&$server_type) ? "WHERE d.server_type=$server_type " : NULL ;
                      $sql .= ($client_status&&$server_type) ? "AND d.server_type=$server_type " : NULL ;
                      $sql .= ($client_status&&$server_name) ? "AND d.server=$server_name " : NULL ;
                      $sql .= "ORDER BY c.client_lname";
                      if($debug)echo SFB.$sql.EF."<br>";
                      $result=mysql_query($sql);
                      $num = mysql_num_rows($result);
                      if ($num >= 1) {
                         $email_to = "<select name=email_to[] multiple size=8>";
                         while(list($client_id, $client_fname,$client_lname,$client_email) = mysql_fetch_array($result)) {
                               $email_to.= "<option value=\"$client_id\" selected>$client_lname, $client_fname [$client_email]</option>\n";
                         }
                         $email_to.= "</select>";
                         @list($heading)=mysql_fetch_row(mysql_query("SELECT email_heading FROM email_config WHERE email_id=$email_title",$dbh));
                         @list($body)=mysql_fetch_row(mysql_query("SELECT email_body FROM email_config WHERE email_id=$email_title",$dbh));
                         @list($footer)=mysql_fetch_row(mysql_query("SELECT email_footer FROM email_config WHERE email_id=$email_title",$dbh));
                         @list($signature)=mysql_fetch_row(mysql_query("SELECT email_signature FROM email_config WHERE email_id=$email_title",$dbh));
                      } else {
                         $email_to = "<input type=text name=email_to value=\"\" size=40 maxlength=255> ".SFB." (".SEPBYCOMMA.") ".EF;
                         $email_to.= "<input type=hidden name=none value=1>";
                         $no_matches_found=1;
                      }
                 break;

                 ## Display unresolved invoice email template.
                 ##
                 ##
                 case invoice:
                     $sql = "SELECT DISTINCT c.client_id, c.client_fname, c.client_lname, c.client_email FROM
                             client_info c, client_invoice i WHERE c.client_id=i.client_id ";
                     $sql .= ($client_status==0) ? NULL : "AND c.client_status=$client_status " ;
                     if ($invoice_type==1) { // NOWDUE
                         $where = "(i.invoice_amount > i.invoice_amount_paid AND
                                    i.invoice_date_paid = 0 AND
                                    i.invoice_date_due > ".mktime().") " ;
                     } elseif ($invoice_type==2) {  // OVERDUE
                         $where = "(i.invoice_amount > i.invoice_amount_paid AND
                                    i.invoice_date_paid = 0 AND
                                    i.invoice_date_due < ".mktime().") " ;
                     }
                     $sql .= "AND ".$where;
                     $sql .= "ORDER BY c.client_lname";
                     if($debug)echo SFB.$sql.EF."<br>";
                     $invoice_result=mysql_query($sql,$dbh);
                     $num = mysql_num_rows($invoice_result);
                     if ($num >= 1) {
                         $email_to = "<select name=email_to[] multiple size=8>";
                         while(list($client_id,$client_fname,$client_lname,$client_email) = mysql_fetch_array($invoice_result)) {
                                     list($ttl_inv,$sum_inv)=mysql_fetch_row(mysql_query("SELECT count(i.client_id), sum(i.invoice_amount) FROM client_invoice i WHERE i.client_id='$client_id' AND $where",$dbh));
                               $email_to.= "<option value=\"$client_id\" selected>$client_lname, $client_fname [$client_email] $ttl_inv Due: ".display_currency($sum_inv)."</option>\n";
                         }
                         $email_to.= "</select><input type=hidden name=auto_invoice value=1>";
                         $email_subject = $inv_email_subject;
                         @list($heading)=mysql_fetch_row(mysql_query("SELECT email_heading FROM email_config WHERE email_id=$email_title",$dbh));
                         @list($body)=mysql_fetch_row(mysql_query("SELECT email_body FROM email_config WHERE email_id=$email_title",$dbh));
                         @list($footer)=mysql_fetch_row(mysql_query("SELECT email_footer FROM email_config WHERE email_id=$email_title",$dbh));
                         @list($signature)=mysql_fetch_row(mysql_query("SELECT email_signature FROM email_config WHERE email_id=$email_title",$dbh));
                         $signature .= "%%LEAVE_FOR_ORIGINAL_INVOICE_HERE%%";
                     } else {
                         $email_to = "<input type=text name=email_to value=\"\" size=40 maxlength=255> ".SFB." (".SEPBYCOMMA.") ".EF;
                         $email_to.= "<input type=hidden name=none value=1>";
                         $no_inv_found=1;
                     }
                 break;

                 ##
                 ## UNRESOLVED
                 ##
                 default:
                     $no_matches_found=1;
                 break;
            }
          ?>
           <hr size=1 width=98%>
            <table cellpadding=2 cellspacing=2 border=0 align=center width=100%>
            <form method=post action=<?=$page?>?tile=mail>
            <input type=hidden name=email_type value=<?=$email_type?>>
            <input type=hidden name=where value=<?=urlencode($where)?>>
            <input type=hidden name=step value=3>
             <tr><td colspan=2><?=LFH?><b><?=SENDEMAIL?> (<?=STEP3?> - <?=COMPOSE?>):</b><?=EF?> <?=SFB?><i><?=YOURSELECTIONS?>:</i><?=EF?></td></tr>

             <? if ($no_inv_found)
                { # For now, we suppress the email.
                    echo "<tr><td colspan=2 align=center>";
                    echo "<b>".MFB.NOINVFOUND.EF."</b><br>".SFB;
                    go_back();
                    echo EF."</td></tr>";
                }
                elseif ($no_matches_found)
                { # For now, we suppress the email.
                    echo "<tr><td colspan=2 align=center>";
                    echo "<b>".MFB.NOMATCHESFOUND.EF."</b><br>".SFB;
                    go_back();
                    echo EF."</td></tr>";
                }
                else
                {
             ?>

             <tr><td width=15% align=right>
                 <?=SFB?><b><?=TO?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <?=$email_to?>
                 </td>
              </tr>
             <tr><td width=15% align=right>
                 <?=SFB?><b><?=CC?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <input type=text name=email_cc value="<?=$default_email_bcc?>" size=50 maxlength=255> <?=SFB?>(<?=SEPBYCOMMA?>)<?=EF?>
                 </td>
              </tr>
             <tr><td width=15% align=right>
                 <?=SFB?><b><?=FROM?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <input type=text name=email_from value="<?=$this_admin["admin_realname"]?> &lt;<?=$this_admin["admin_email"]?>&gt;" size=50 maxlength=255> <?=SFB?>(<?=strtoupper(FIRST)?> <?=strtoupper(LAST)?> &lt;<?=strtoupper(EMAIL)?>&gt;)<?=EF?>
                 </td>
              </tr>
             <tr><td width=15% align=right>
                 <?=SFB?><b><?=PRIORITY?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <?
                 /*
                 1 = Highest Priority
                 2 = High Priority
                 3 = Normal Priority
                 4 = Low Priority
                 */
                 ?>
                 <select name=email_priority>
                 <option value=3 SELECTED><?=NORMAL?></option>
                 <option value=1><?=HIGH?></option>
                 <option value=4><?=LOW?></option>
                 </select>
                 </td>
              </tr>
             <tr><td colspan=2 align=center><?=SFB?><b><?=HINT?>:</b><?=EF?> <?=SFB.STRREPLANCEHINT.EF?></td></tr>
             <tr><td width=15% align=right>
                 <?=SFB?><b><?=SUBJECT?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <input type=text name=email_subject value="<?=$email_subject?>" size=50 maxlength=255>
                 </td>
              </tr>
             <tr><td width=15% align=right>
                 <?=SFB?><b><?=EMAIL." ".MESSAGE?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <textarea name=email_body rows=35 cols=85 VIRTUAL maxlength=100000><?=$heading."\n".$body."\n".$footer."\n".$signature."\n"?></textarea>
                 </td>
              </tr>
             <tr><td colspan=2 align=center><?=SEND_IMG?></td></tr>

           <?} /* End $none_found */ ?>

             </form>
             </table>
          <? $included = TRUE; include("include/html/email_shortcuts.inc.php"); ?>
          <? break;

            /* --- SEND EMAIL ---*/
            case 3:
            GLOBAL $success,$where;
            @send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from);
          ?>
           <hr size=1 width=98%>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=EMAILSTATS?>:</b><?=EF?></td></tr>
             <tr>
               <td valign=top>
                 <table>
                  <tr><td>
                       <?=MFB?>
                       <?=TESS?>:<br>
                       <?=TENS?>:<br>
                       <?=EF?>
                      </td>
                      <td align=right>
                       <?=MFB?>
                       <?=$success["sent"]?><br>
                       <?=$success["failed"]?><br>
                       <?=EF?>
                      </td>
                   </tr>
                  </table>
               </td>
             </tr>
            </table>
          <? break;
           }
          ?>
          </td>
        </tr>