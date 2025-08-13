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

if (!$included) {
     $is_popup=TRUE;
     start_short_html();
}
?>

<? switch($type) { case general: ?>
<hr size=1 width=98%>
<table cellpadding=2 cellspacing=2 border=0 align=center width=550>
 <tr><td colspan=2><?=LFH?><b><?=EMAILSHORTCUTS?>:</b><?=EF?><br>
                   <?=SFB?><b><?=HINT?>:</b> <?=SHORTCUTHINTS?><?=EF?><br><br></td></tr>
 <tr><td width=30%><?=LFH?><b><?=SHORTCUT?>:</b><?=EF?></td><td><?=LFH?><b><?=TRANSLATESTO?>:</b><?=EF?></td></tr>
 <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%DATE%%<?=EF?></td>
     <td valign=top><?=SFB?>date(<?=$date_format?>) Ex. (<?=$date_format_types[$date_format]?>)<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%FULLNAME%%<?=EF?></td>
     <td valign=top><?=SFB?>client_fname client_lname Ex. John Smith<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%FIRSTNAME%%<?=EF?></td>
     <td valign=top><?=SFB?>client_fname Ex. John<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%LASTNAME%%<?=EF?></td>
     <td valign=top><?=SFB?>client_lname Ex. Smith<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%EMAIL%%<?=EF?></td>
     <td valign=top><?=SFB?>client_email Ex. jsmith@smitty.com<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%COMPANY%%<?=EF?></td>
     <td valign=top><?=SFB?>client_company Ex. Company or Domain<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%FULLADDRESS%%<?=EF?></td>
     <td valign=top><?=SFB?>client_address<br>client_city, client_state client_zip client_country<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%PHONE1%%<?=EF?></td>
     <td valign=top><?=SFB?>client_phone1<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%PHONE2%%<?=EF?></td>
     <td valign=top><?=SFB?>client_phone2<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%BILLINGMETHOD%%<?=EF?></td>
     <td valign=top><?=SFB?>billing_method<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%BILLINGTYPE%%<?=EF?></td>
     <td valign=top><?=SFB?>billing_cc_type<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%USERLOGINURL%%<?=EF?></td>
     <td valign=top><?=SFB.$user_login_url.EF?></td></tr>
<?
          $replace_array = array('client_id',
                                 'client_fname',
                                 'client_lname',
                                 'client_email',
                                 'client_company',
                                 'client_address',
                                 'client_city',
                                 'client_state',
                                 'client_zip',
                                 'client_country',
                                 'client_phone1',
                                 'client_phone2',
                                 'billing_method',
                                 'billing_cc_type',
                                 'billing_cc_num',
                                 'billing_cc_exp',
                                 'billing_cc_code',
                                 'client_password',
                                 'client_comments',
                                 'client_status',
                                 'client_stamp',
                                 'client_secondary_email',
                                 'client_username',
                                 'client_real_pass',
                                 'x_Bank_Name',
                                 'x_Bank_ABA_Code',
                                 'x_Bank_Acct_Num',
                                 'x_Drivers_License_Num',
                                 'x_Drivers_License_State',
                                 'x_Drivers_License_DOB',
                                 'apply_tax',
                                 'default_translation',
                                 'default_currency',
                                 'send_email_type',
                                 'secondary_contact',
                                 'client_field_1',
                                 'client_field_2',
                                 'client_field_3',
                                 'client_field_4',
                                 'client_field_5',
                                 'client_field_6',
                                 'client_field_7',
                                 'client_field_8',
                                 'client_field_9',
                                 'client_field_10');
         foreach ($replace_array as $value)
         {
          ?>
           <tr>
               <td valign=top><nobr><?=SFB?>%%<?=strtoupper($value)?>%%<?=EF?></nobr></td>
               <td valign=top><?=SFB?>$this_client[<?=$value?>]<?=EF?></td></tr>
          <?
         }
?>
</table>

<?  break; case invoice: ?>

<hr size=1 width=98%>
<table cellpadding=2 cellspacing=2 border=0 align=center width=550>
 <tr><td colspan=2><?=LFH?><b><?=SPECIALSHORTCUTS?>:</b><?=EF?><br>
                   <?=SFB?><b><?=HINT?>:</b> <?=SPECSHORTCUTHINTS?><?=EF?><br><br></td></tr>
 <tr><td width=30%><?=LFH?><b><?=SHORTCUT?>:</b><?=EF?></td><td><?=LFH?><b><?=TRANSLATESTO?>:</b><?=EF?></td></tr>
 <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_NUMBER%%<?=EF?></td>
     <td valign=top><?=SFB?>invoice_id<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_AMOUNTDUE%%<?=EF?></td>
     <td valign=top><?=SFB?>invoice_amount<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_AMOUNTLEFT%%<?=EF?></td>
     <td valign=top><?=SFB?>invoice_amount - invoice_amount_paid<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_DUEDATE%%<?=EF?></td>
     <td valign=top><?=SFB?>invoice_date_due<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_DATE_PAID%%<?=EF?></td>
     <td valign=top><?=SFB?>invoice_date_paid<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_AMOUNT_PAID%%<?=EF?></td>
     <td valign=top><?=SFB?>invoice_amount_paid<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_GENDATE%%<?=EF?></td>
     <td valign=top><?=SFB?>invoice_date_entered<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_STAMP%%<?=EF?></td>
     <td valign=top><?=SFB?>invoice_stamp<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_OVERDUEDAYS%%<?=EF?></td>
     <td valign=top><?=SFB?>Number of days over due.<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_SNAPSHOT%%<?=EF?></td>
     <td valign=top><?=SFB.HTMLOUTPUT.EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_CCTYPE%%<?=EF?></td>
     <td valign=top><?=SFB.CCEXAMPLETRANSLATE.EF?><br>
                    <?=SFB.FOREMAILONLY.EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_TRANSID%%<?=EF?></td>
     <td valign=top><?=SFB?>trans_id<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_PAYPAL_LINK%%<?=EF?></td>
     <td valign=top><?=SFB?>Dynamic PayPal URL<?=EF?></td></tr>
 <tr>
     <td valign=top><nobr><?=SFB?>%%INVOICE_WORLDPAY_LINK%%<?=EF?></nobr></td>
     <td valign=top><?=SFB?>Dynamic WorldPay URL<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_ADDRESS%%<?=EF?></td>
     <td valign=top><?=SFB.nl2br($invoice_address).EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%INVOICE_COMMENTS%%<?=EF?></td>
     <td valign=top><?=SFB?>invoice_comments<?=EF?></td></tr>
</table>

<? break; case sql: ?>

<hr size=1 width=98%>
<table cellpadding=2 cellspacing=2 border=0 align=center width=550>
 <tr><td colspan=2><?=LFH?><b><?=ADDITIONALSQL?>:</b><?=EF?><br>
                   <?=SFB?><b><?=strtoupper(WARNING)?>:</b> <?=SQLWARNING?><?=EF?><br><br></td></tr>
 <tr><td colspan=2><?=LFH?><b><?=SHORTCUT?>:</b><?=EF?></td></tr>
 <tr><td colspan=2><?=SFB?>%SQL-1% client_stamp FROM client_info WHERE client_id %SQL-1%<?=EF?><br>
                   <?=SFB?><b><?=HINT?>:</b><?=YOUCANUSE?>: SQL-1, SQL-2, SQL-3, SQL-4, & SQL-5<?=EF?><br><br></td></tr>
 <tr><td colspan=2><?=LFH?><b><?=TRANSLATESTO?>:</b><?=EF?></td></tr>
 <tr><td colspan=2><?=SFB?>SELECT client_stamp FROM client_info WHERE client_id = current_client_id<?=EF?><br><br></td></tr>
 <tr><td colspan=2><?=LFH?><b>Usage:</b><?=EF?></td></tr>
 <tr><td colspan=2><?=SFB?>Your account was last updated on: %SQL-1% client_stamp FROM client_info WHERE client_id %SQL-1%<?=EF?></td></tr>
</table>

<? break; case vortech: ?>

<hr size=1 width=98%>
<table cellpadding=2 cellspacing=2 border=0 align=center width=550>
 <tr><td colspan=2><?=LFH?><b>Vortech Signup Emails:</b><?=EF?><br>
                   <?=SFB?><b><?=HINT?>:</b> These variables will ONLY parse in the Vortech Signup Emails!<?=EF?><br><br></td></tr>
 <tr><td width=30%><?=LFH?><b><?=SHORTCUT?>:</b><?=EF?></td><td><?=LFH?><b><?=TRANSLATESTO?>:</b><?=EF?></td></tr>
 <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[FIRSTNAME]]<?=EF?></td>
     <td valign=top><?=SFB?>firstname<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[LASTNAME]]<?=EF?></td>
     <td valign=top><?=SFB?>lastname<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[EMAIL]]<?=EF?></td>
     <td valign=top><?=SFB?>email<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[COMPANYNAME]]<?=EF?></td>
     <td valign=top><?=SFB?>customer_company<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[ADDRESS]]<?=EF?></td>
     <td valign=top><?=SFB?>address_1."\n".$address_2<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[CITY]]<?=EF?></td>
     <td valign=top><?=SFB?>city<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[STATE]]<?=EF?></td>
     <td valign=top><?=SFB?>state<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[ZIPCODE]]<?=EF?></td>
     <td valign=top><?=SFB?>zipcode<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[COUNTRY]]<?=EF?></td>
     <td valign=top><?=SFB?>country<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[PHONE]]<?=EF?></td>
     <td valign=top><?=SFB?>phone_ac<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[FAX]]<?=EF?></td>
     <td valign=top><?=SFB?>fax_ac<?=EF?></td></tr>
 <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[REFERRER]]<?=EF?></td>
     <td valign=top><?=SFB?>referrer<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[DOMAIN]]<?=EF?></td>
     <td valign=top><?=SFB?>domain<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[USERNAME]]<?=EF?></td>
     <td valign=top><?=SFB?>username<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[PASSWORD]]<?=EF?></td>
     <td valign=top><?=SFB?>pass<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[COMMENTS]]<?=EF?></td>
     <td valign=top><?=SFB?>comments<?=EF?></td></tr>
 <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[IP]]<?=EF?></td>
     <td valign=top><?=SFB?>getenv("REMOTE_ADDR")<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[PACKAGE]]<?=EF?></td>
     <td valign=top><?=SFB?>hostingplan<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[CONTRACTTERM]]<?=EF?></td>
     <td valign=top><?=SFB?>contractplan<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[FRONTPAGE]]<?=EF?></td>
     <td valign=top><?=SFB?>frontpage<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[PRICE]]<?=EF?></td>
     <td valign=top><?=SFB?>display_currency($packageprice)<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[DOMAININFO]]<?=EF?></td>
     <td valign=top><?=SFB?><?=DOMAINSTATUS?>: years <?=YEARREG?><br><?=REGFEE?>: display_currency($domain_price)<?=EF?></td></tr>
 <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[PAYMENTMETHOD]]<?=EF?></td>
     <td valign=top><?=SFB?>pay_method<?=EF?></td></tr>
  <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?>_START_CCTEXT_<?=EF?></td>
     <td valign=top><?=SFB?>&lt;empty&gt;<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[CCTYPE]]<?=EF?></td>
     <td valign=top><?=SFB?>cc_type<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[CCNUMBER]]<?=EF?></td>
     <td valign=top><?=SFB?>credit_secure<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[CCEXP]]<?=EF?></td>
     <td valign=top><?=SFB?>expdate<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[CCCODE]]<?=EF?></td>
     <td valign=top><?=SFB?>x_CC_Code<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[CCNUMBER]]<?=EF?></td>
     <td valign=top><?=SFB?>credit_secure<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>_STOP_CCTEXT_<?=EF?></td>
     <td valign=top><?=SFB?>&lt;empty&gt;<?=EF?></td></tr>
 <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?>_START_PAYPALTEXT_<?=EF?></td>
     <td valign=top><?=SFB?>&lt;empty&gt;<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[PAYPALLINK]]<?=EF?></td>
     <td valign=top><?=SFB?>generate_worldpay_link OR generate_paypal_link<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>_STOP_PAYPALTEXT_<?=EF?></td>
     <td valign=top><?=SFB?>&lt;empty&gt;<?=EF?></td></tr>
 <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?>_START_INVOICETEXT_<?=EF?></td>
     <td valign=top><?=SFB?>&lt;empty&gt;<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[PAYADDRESS]]<?=EF?></td>
     <td valign=top><?=SFB?>company_address<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>_STOP_INVOICETEXT_<?=EF?></td>
     <td valign=top><?=SFB?>&lt;empty&gt;<?=EF?></td></tr>
 <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?><strike>[[SETUPPRICE]]</strike><?=EF?></td>
     <td valign=top><?=SFB?>display_currency($setup)<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?><strike>[[PRORATEINFO]]</strike><?=EF?></td>
     <td valign=top><?=SFB?>prorated_days Day(s): display_currency($pro_pay)<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?><strike>[[SUBTOTAL]]</strike><?=EF?></td>
     <td valign=top><?=SFB?>display_currency($initial_charge)<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?><strike>[[TAXDUE]]</strike><?=EF?></td>
     <td valign=top><?=SFB?>display_currency($tax_due)<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?><strike>[[TOTALDUE]]</strike><?=EF?></td>
     <td valign=top><?=SFB?>display_currency($this_total)<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>[[DISPLAY_CART]]<?=EF?></td>
     <td valign=top><?=SFB?>This is an HTML snapshot of the order.<?=EF?></td></tr>
</table>

<? break; case welcome: ?>

<hr size=1 width=98%>
<table cellpadding=2 cellspacing=2 border=0 align=center width=550>
 <tr><td colspan=2><?=LFH?><b>Welcome Emails:</b><?=EF?><br>
                   <?=SFB?><b><?=HINT?>:</b> These variables will ONLY parse if the Client Package is defined!<?=EF?><br><br></td></tr>
 <tr><td width=30%><?=LFH?><b><?=SHORTCUT?>:</b><?=EF?></td><td><?=LFH?><b><?=TRANSLATESTO?>:</b><?=EF?></td></tr>
 <tr><td colspan=2><hr></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%PACK_TOTAL_ACTIVE%%<?=EF?></td>
     <td valign=top><?=SFB?>Total Active Packages<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%PACK_TOTAL_INACTIVE%%<?=EF?></td>
     <td valign=top><?=SFB?>Total Inactive Packages<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%AD_DOMAIN%%<?=EF?></td>
     <td valign=top><?=SFB?>$domain_name<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%AD_IP%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_details['ip']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%AD_SERVER%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_details['server']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%AD_SERVER_TYPE%%<?=EF?></td>
     <td valign=top><?=SFB?>$server_types[$this_account_details['server_type']]<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%AD_USERNAME%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_details['username']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%AD_PASSWORD%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_details['password']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%DB_TYPE%%<?=EF?></td>
     <td valign=top><?=SFB?>$db_types[$this_account_dbs['db_type']]<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%DB_NAME%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_dbs['db_name']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%DB_USER%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_dbs['db_user']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%DB_PASS%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_dbs['db_pass']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%POP_REAL_NAME%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_pops['pop_real_name']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%POP_USERNAME%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_pops['pop_username']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%POP_PASSWORD%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_pops['pop_password']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%POP_SPACE%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_pops['pop_space']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%POP_FTP%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_pops['pop_ftp']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%POP_TELNET%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_account_pops['pop_telnet']<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%LISTALLDOMAINS%%<?=EF?></td>
     <td valign=top><?=SFB?>$list_domains<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%PACK_NAME%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_package["pack_name"]<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%PACK_PRICE%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_package["pack_price"]<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%CP_STATUS%%<?=EF?></td>
     <td valign=top><?=SFB?>$status_types[$this_client_package["cp_status"]]<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%CP_QTY%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_client_package["cp_qty"]<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%CP_DISCOUNT%%<?=EF?></td>
     <td valign=top><?=SFB?>$this_client_package["cp_discount"]<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%CP_BILLINGCYCLE%%<?=EF?></td>
     <td valign=top><?=SFB?>$cycle_types[$this_client_package["cp_billing_cycle"]]<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%CP_STARTDATE%%<?=EF?></td>
     <td valign=top><?=SFB?>stamp_to_date($this_client_package["cp_start_stamp"])<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%CP_RENEWDATE%%<?=EF?></td>
     <td valign=top><?=SFB?>stamp_to_date($this_client_package["cp_renew_stamp"])<?=EF?></td></tr>
 <tr>
     <td valign=top><?=SFB?>%%CP_RENEWON%%<?=EF?></td>
     <td valign=top><?=SFB?>stamp_to_date($this_client_package["cp_renewed_on"])<?=EF?></td></tr>

 </table>

<? break; }
if (!$included) {
     stop_short_html();
}
?>