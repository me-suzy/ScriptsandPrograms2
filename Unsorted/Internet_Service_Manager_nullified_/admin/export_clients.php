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
foreach($clientfields as $fieldname=>$other){
$fields.=$fieldname.',';
}
echo '<a class="left_menu" href="export.php?fromtable=clients&fields='.$fields.'"><P>Click Here to download the client list</a>';

$fields="";
foreach($contactfields as $fieldname=>$other){
$fields.=$fieldname.',';
}
echo '<P><a class="left_menu" href="export.php?fromtable=contacts&fields='.$fields.'"><P>Click Here to download the contacts!</a>';


}else{

echo '<font face="'.$admin_font.'" size="2"><B>Please select the fields you wish to export..<P>';

echo '<form action="export_clients.php">Clients:<BR></B>';
echo '<input type=checkbox CHECKED name="clientfields[id]"> Client Id<BR>';
echo '<input type=checkbox CHECKED name="clientfields[name]"> Name<BR>';
echo '<input type=checkbox CHECKED name="clientfields[primary_contact|firstname/lastname-contacts-id- ]"> Primary Contact<BR>';
echo '<input type=checkbox CHECKED name="clientfields[date_added|date]"> Date Added<BR>';
echo '<input type=checkbox CHECKED name="clientfields[comments]"> Comments<BR>';
echo '<input type=checkbox CHECKED name="clientfields[billing_method]"> Billing Method<BR>';
echo '<input type=checkbox CHECKED name="clientfields[bill_to_contact|firstname/lastname-contacts-id- ]"> Billing Contact<BR>';
echo '<input type=checkbox CHECKED name="clientfields[account_balance]"> Account Balance<BR><BR>';

echo '<B>Contacts:<BR></B>';
echo '<input type=checkbox CHECKED name="contactfields[id]"> Contact ID<BR>';
echo '<input type=checkbox CHECKED name="contactfields[client_id]"> Client ID<BR>';
echo '<input type=checkbox CHECKED name="contactfields[client_id|name-clients-id- ]"> Client Name<BR>';
echo '<input type=checkbox CHECKED name="contactfields[title]"> Title<BR>';
echo '<input type=checkbox CHECKED name="contactfields[firstname]"> Firstname<BR>';
echo '<input type=checkbox CHECKED name="contactfields[lastname]"> Lastname<BR>';
echo '<input type=checkbox CHECKED name="contactfields[email]"> Email<BR>';
echo '<input type=checkbox CHECKED name="contactfields[phone]"> Phone<BR>';
echo '<input type=checkbox CHECKED name="contactfields[phone2]"> Phone2<BR>';
echo '<input type=checkbox CHECKED name="contactfields[username]"> Username<BR>';
echo '<input type=checkbox CHECKED name="contactfields[password]"> Password<BR>';
echo '<input type=checkbox CHECKED name="contactfields[comments]"> Comments<P>';

echo '<input type=submit name=export value="Export Now"></form>';
}

include "footer.php";
?>