<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="export";
include "../conf.php";
include "auth.php";

include "header.php";

echo '<font face="'.$admin_font.'" size="2">';

if($export){

$fields="";
foreach($invoicefields as $fieldname=>$other){
$fields.=$fieldname.',';
}
echo '<P><a class="left_menu" href="export.php?cond='.$cond.'&fromtable=invoices&fields='.$fields.'"><P>Click Here to download all invoices!</a>';

$fields="";
foreach($receiptfields as $fieldname=>$other){
$fields.=$fieldname.',';
}
echo '<P><a class="left_menu" href="export.php?cond='.$cond2.'&fromtable=money_received&fields='.$fields.'"><P>Click Here to download the receipts!</a>';


}else{

echo '<font face="'.$admin_font.'" size="2"><B>Please select the fields you wish to export..<P>';

echo '<form action="export_billing.php">Invoices:<BR></B>';
echo '<input type=checkbox CHECKED name="invoicefields[id]"> Invoice Id<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[to_client|name-clients-id- ]"> Client<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[project_id|project_name-projects-id-]"> Project Name<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[project_id]"> Project Id<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[stage_id]"> Stage Id<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[amount]"> Amount<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[sales_tax]"> Sales Tax<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[admin_id|firstname/lastname-admins-id- ]"> Issuing Admin<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[date|date]"> Date Issued<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[due_date|date]"> Date Due<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[date_paid|date]"> Date Paid<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[paid|binary]"> Paid (Yes/No)<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[sent_to|firstname/lastname-contacts-id- ]"> Sent To (contact)<BR>';
echo '<input type=checkbox CHECKED name="invoicefields[sent_type]"> Sent Type<P>';
$last30=time()-2592000;
echo 'From last: <select name=cond>
<option value="">All Invoices</option>
<option value="WHERE paid=\'0\'">Not Paid</option>
<option value="WHERE date>=\''.$last30.'\'">Last 30 Days</option>
</select><P>';

echo '<form action="export_billing.php">Receipts:<BR></B>';
echo '<input type=checkbox CHECKED name="receiptfields[id]"> Receipt Id<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[amount]"> Receipt Id<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[client_id|name-clients-id]"> Client Name<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[client_id]"> Client Id<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[invoice|account]"> Invoice Id<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[comments]"> Comments<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[date|date]"> Date<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[method]"> Payment Method<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[method_identifier]"> Payment UID<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[admin_id|firstname/lastname-admins-id- ]"> Admin Name<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[admin_id]"> Admin ID<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[receipt]"> Client Receipt<BR>';
echo '<input type=checkbox CHECKED name="receiptfields[authkey]"> Authorisation Key<P>';

echo 'From last: <select name=cond2>
<option value="">All Receipts</option>
<option value="WHERE date>=\''.$last30.'\'">Last 30 Days</option>
</select><P>';


echo '<input type=submit name=export value="Export Now"></form>';
}

include "footer.php";
?>