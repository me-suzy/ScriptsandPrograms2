<?php
include ("atho.inc.php");
include ("config.inc.php");


$query = "SELECT * FROM var_notify"; 

$products_per_page="$perpage";
$numresults=mysql_query($query);
$numrows=mysql_num_rows($numresults);

if (empty($offset) || ($offset < 0)) {
	$offset=0;
}


$dbResult = mysql_query("select * from var_notify limit $offset,$products_per_page");

$title="Lizard Cart Product Administration";
?>

<? include ("header.php");?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#3366CC">
  <tr bgcolor=ffffff>
    <td colspan="2" align="center">    <form method="post" action="http://www.2tonewebdesign.com/lizardcartpp/admin/ordersearch.php"> 
   <b>Search</b> <select name="metode" size="1">
    <option value="first_name">First Name</option>
    <option value="last_name">Last Name</option>
	<option value="payment_date">Payment Date</option>
	<option value="payer_email">There Email</option>
    </select>
    <input type="text" name="search" size="25"> 
    <input type="submit" value="Begin Searching!!">
    </form></td></tr>
    <td width="50?">
	<font face="Verdana, Arial, Helvetica, sans-serif" size="2">
  <tr> 
    <td width="50?"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Pages (<?echo "$numrows"?>)</b></font></td>
    <td>
      <div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="White">Click on an item for Details</font></div>
    </td>
  </tr>
  <tr>
    <td colspan=3 align=center>
	<!--<a href="<? //echo "orderedit.php?action=0"?>"><font size=1 face="Verdana, Arial, Helvetica, sans-serif" color='white'>[ Add A Order ]</a>--><br></td>
  </tr>
</table>
<!-- PRINT STARTS -->
<? 
while ($row=mysql_fetch_object($dbResult)) {
?>
<script language="JavaScript">

   function Start(page) {

      OpenWin = this.open(page, "CtrlWindow", 'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=yes,resizable=yes,width=600,height=480');

   } 

</script>
<table width="100%" border="2">
<tr>
	<td colspan="2" align="center">
	  <font face="Verdana, Arial, Helvetica, sans-serif"><b><font size="1">
      <a href="javascript:Start('orderprint.php?std_ipn=<? echo "$row->std_ipn" ?>')">Print This Order</a>
      <a href='<? echo "orderedit.php?action=2&std_ipn=$row->std_ipn" ?>'>Delete</a>
	  <!--<a href="print/print.php"><b>Print This Order</b></a>-->
      </font></b></font>
	  </td>
</tr>
<tr>
	<td>IPN Order Number</td>
	<td><? echo "$row->std_ipn" ?></td>
</tr>
<tr>
	<td>Primary email address of the payment recipient (i.e., the merchant).</td>
	<td><? echo "$row->receiver_email" ?></td>
</tr>
<tr>
	<td>Business</td>
	<td><? echo "$row->business" ?></td>
</tr>
<tr>
	<td>Item Name</td>
	<td><? echo "$row->item_name" ?></td>
</tr>
<tr>
	<td>Quantity</td>
	<td><? echo "$row->quantity" ?></td>
</tr>
<tr>
	<td>Invoice number as passed by you</td>
	<td><? echo "$row->invoice" ?></td>
</tr>
<tr>
	<td>Custom value as passed by you</td>
	<td><? echo "$row->custom" ?></td>
</tr>
<tr>
	<td>Option 1 Name as requested by you</td>
	<td><? echo "$row->option_name1" ?></td>
</tr>
<tr>
	<td>Option 1 Choice as entered by your customer</td>
	<td><? echo "$row->option_selection1" ?></td>
</tr>
<tr>
	<td>Option 2 Name as requested by you</td>
	<td><? echo "$row->option_name2" ?></td>
</tr>
<tr>
	<td>Option 2 Choice as entered by your customer</td>
	<td><? echo "$row->option_selection2" ?></td>
</tr>
<tr>
	<td>If this is a shopping cart transaction, number of items in cart</td>
	<td><? echo "$row->num_cart_items" ?></td>
</tr>
<tr>
	<td>Payment Status The payment is:</td>
	<td><? echo "$row->payment_status" ?></td>
</tr>
<tr>
	<td>Pending Reason The payment is pending because it was made by an:</td>
	<td><? echo "$row->pending_reason" ?></td>
</tr>
<tr>
	<td>Payment Date</td>
	<td><? echo "$row->payment_date" ?></td>
</tr>
<tr>
	<td>Settle Amount</td>
	<td><? echo "$row->settle_amount" ?></td>
</tr>
<tr>
	<td>Settle Currency</td>
	<td><? echo "$row->settle_currency" ?></td>
</tr>
<tr>
	<td>Exchange Rate</td>
	<td><? echo "$row->exchange_rate" ?></td>
</tr>
<tr>
	<td>Full USD amount of the customer's payment</td>
	<td><? echo "$row->payment_gross" ?></td>
</tr>
<tr>
	<td>USD transaction fee associated with the payment</td>
	<td><? echo "$row->payment_fee" ?></td>
</tr>
<tr>
	<td>Full amount of the customer's payment</td>
	<td><? echo "$row->mc_gross" ?></td>
</tr>
<tr>
	<td>Transaction fee associated with the payment</td>
	<td><? echo "$row->mc_fee" ?></td>
</tr>
<tr>
	<td>Currency</td>
	<td><? echo "$row->mc_currency" ?></td>
</tr>
<tr>
	<td>Tax</td>
	<td><? echo "$row->tax" ?></td>
</tr>
<tr>
	<td>Transaction ID</td>
	<td><? echo "$row->txn_id" ?></td>
</tr>
<tr>
	<td>Type of Transaction</td>
	<td><? echo "$row->txn_type" ?></td>
</tr>
<tr>
	<td>Memo</td>
	<td><? echo "$row->memo" ?></td>
</tr>
<tr>
	<td>First Name</td>
	<td><? echo "$row->first_name" ?></td>
</tr>
<tr>
	<td>Last Name</td>
	<td><? echo "$row->last_name" ?></td>
</tr>
<tr>
	<td>Street</td>
	<td><? echo "$row->address_street" ?></td>
</tr>
<tr>
	<td>City</td>
	<td><? echo "$row->address_city" ?></td>
</tr>
<tr>
	<td>State</td>
	<td><? echo "$row->address_state" ?></td>
</tr>
<tr>
	<td>Zip Code</td>
	<td><? echo "$row->address_zip" ?></td>
</tr>
<tr>
	<td>Country</td>
	<td><? echo "$row->address_country" ?></td>
</tr>
<tr>
	<td>Address Status</td>
	<td><? echo "$row->address_status" ?></td>
</tr>
<tr>
	<td>Customer's primary email address</td>
	<td><? echo "$row->payer_email" ?></td>
</tr>
<tr>
	<td>Unique customer ID</td>
	<td><? echo "$row->payer_id" ?></td>
</tr>
<tr>
	<td>Customer has a Verified U.S. PayPal account</td>
	<td><? echo "$row->payer_status" ?></td>
</tr>
<tr>
	<td>This payment was funded with an</td>
	<td><? echo "$row->payment_type" ?></td>
</tr>
<tr>
	<td>Version of Instant Payment Notification</td>
	<td><? echo "$row->notify_version" ?></td>
</tr>
<tr>
	<td>An encrypted string used to validate the authenticity of the transaction</td>
	<td><? echo "$row->verify_sign" ?></td>
</tr>
<tr>
	<td>Status</td>
	<td><? echo "$row->status" ?></td>
</tr>
<tr>
	<td>Subscription Date</td>
	<td><? echo "$row->subscr_date" ?></td>
</tr>
<tr>
	<td>Period 1</td>
	<td><? echo "$row->period1" ?></td>
</tr>
<tr>
	<td>Period 2</td>
	<td><? echo "$row->period2" ?></td>
</tr>
<tr>
	<td>Period 3</td>
	<td><? echo "$row->period3" ?></td>
</tr>
<tr>
	<td>Amount 1</td>
	<td><? echo "$row->amount1" ?></td>
</tr>
<tr>
	<td>Amount 2</td>
	<td><? echo "$row->amount2" ?></td>
</tr>
<tr>
	<td>Amount 3</td>
	<td><? echo "$row->amount3" ?></td>
</tr>
<tr>
	<td>Recurring</td>
	<td><? echo "$row->recurring" ?></td>
</tr>
<tr>
	<td>Re Attempt</td>
	<td><? echo "$row->reattempt" ?></td>
</tr>
<tr>
	<td>Retry at</td>
	<td><? echo "$row->retry_at" ?></td>
</tr>
<tr>
	<td>Recurring Times</td>
	<td><? echo "$row->recur_times" ?></td>
</tr>
<tr>
	<td>Username</td>
	<td><? echo "$row->username" ?></td>
</tr>
<tr>
	<td>Password</td>
	<td><? echo "$row->password" ?></td>
</tr>
<tr>
	<td>Subscription ID</td>
	<td><? echo "$row->subscr_id" ?></td>
</tr>
<tr>
	<td colspan="2" align="center">
	  <font face="Verdana, Arial, Helvetica, sans-serif"><b><font size="1">
	  <a href="javascript:Start('orderprint.php?std_ipn=<? echo "$row->std_ipn" ?>')">Print This Order</a>
      <!--<a href='<?// echo "orderedit.php?action=0&std_ipn=$row->std_ipn" ?>'>Edit</a>-->
      <a href='<? echo "orderedit.php?action=2&std_ipn=$row->std_ipn" ?>'>Delete</a>
	  <!--<a href="print/print.php"><b>Print This Order</b></a>-->
      </font></b></font>
	  </td>
</tr>
</table>

<br>
<? 
} // while
?>
<!-- PRINT END -->
<?
print "<table width=100% ><tr><td colspan=2 align=center>\n";
print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">";
$prevoffset=$offset-$products_per_page;
if ($prevoffset >= 0) {
	print "<a href=\"$PHP_SELF?offset=$prevoffset\">PREV</a> &nbsp; \n";
} else {
	print "PREV &nbsp; \n";
}

// calculate number of pages needing links
$pages=intval($numrows/$products_per_page);

// $pages now contains int of pages needed unless there is a remainder from division
if ($numrows%$products_per_page) {
    // has remainder so add one page
    $pages++;
}

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$products_per_page*($i-1);
    if($newoffset==$offset) {
	    print "<b>$i</b> &nbsp; \n";
    } else {
	    print "<a href=\"$PHP_SELF?offset=$newoffset\">$i</a> &nbsp; \n";
    }
}

// check to see if last page
if (!(($offset/$products_per_page)==$pages) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$products_per_page;
    if ($newoffset < ($numrows+1)) {
	    print "<a href=\"$PHP_SELF?offset=$newoffset\">NEXT</a><p>\n";
    } else {
	    print "NEXT &nbsp;<p>\n";
    } 
	
} else {
	    print "NEXT &nbsp;<p>\n";
}
?>
</td></tr></table>
<? include ("footer.php");?>
