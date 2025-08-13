<?
include ("atho.inc.php");
include ("config.inc.php");

$dbResult = mysql_query("select * from var_notify where std_ipn='$std_ipn'");
$row=mysql_fetch_object($dbResult);
?>
<div> 

<table width="100%" border="2">
	  <tr align="center" valign="middle">
		<td colspan="2" align="center"><form><input type=button value="Print This Order" onclick="print()"></form></td>
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
	  <tr align="center" valign="middle">
		<td colspan="2" align="center"><form><input type=button value="Print This Order" onclick="print()"></form></td>
	  </tr>
</table>

        </td>
      </tr>
    </table>
    <br>
  </div>
