<?php
include ("atho.inc.php");
include ("config.inc.php");


define (INITIAL_PAGE,0);
define (UPDATE_ENTRY,1);
define (DELETE_ENTRY,2);
define (ADD_ENTRY,3);

if (empty ($action))
        $action = INITIAL_PAGE;

$title="Lizard Cart Product Administration";
?>

<? include ("header.php");?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#3366CC">
  <tr bgcolor=ffffff>
    <td colspan=2></td></tr>
    <td width="50?">
        <font face="Verdana, Arial, Helvetica, sans-serif" size="2">
  <tr>
    <td width="50?"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Orders From IPN</b></font></td>
    <td>
      <div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="white">Click
        on an order for Details</font></div>
    </td>
  </tr>
  <tr>
    <td colspan=3 align=center>
        <!--<a href="<? //echo "orderedit.php?action=3"?>"><font size=1 face="Verdana, Arial, Helvetica, sans-serif" color='White'>[ Add A Order ]</a>--></td>
  </tr>
</table>
<?
switch($action)
{
case DELETE_ENTRY:
        delete_entry($std_ipn,$confirmed);
        break;
case UPDATE_ENTRY:
	if ($std_ipn) {
	    
		$query = "UPDATE var_notify ";
		$query .= "SET ";
	$query.="ipn_id ='$ipn_id' , receiver_email ='$receiver_email' , business ='$business' , item_name ='$item_name' , item_number ='$item_number' , quantity ='$quantity' , invoice ='$invoice' , custom ='$custom' , option_name1 ='$option_name1' , option_selection1 ='$option_selection1' , option_name2 ='$option_name2' , option_selection2 ='$option_selection2' , num_cart_items ='$num_cart_items' , payment_status ='$payment_status' , pending_reason ='$pending_reason' , payment_date ='$payment_date' , settle_amount ='$settle_amount' , settle_currency ='$settle_currency' , exchange_rate ='$exchange_rate' , payment_gross ='$payment_gross' , payment_fee ='$payment_fee' , mc_gross ='$mc_gross' , mc_fee ='$mc_fee' , mc_currency ='$mc_currency' , tax ='$tax' , txn_id ='$txn_id' , txn_type ='$txn_type' , memo ='$memo' , first_name ='$first_name' , last_name ='$last_name' , address_street ='$address_street' , address_city ='$address_city' , address_state ='$address_state' , address_zip ='$address_zip' , address_country ='$address_country' , address_status ='$address_status' , payer_email ='$payer_email' , payer_id ='$payer_id' , payer_status ='$payer_status' , payment_type ='$payment_type' , subscr_date ='$subscr_date' , period1 ='$period1' , period2 ='$period2' , period3 ='$period3' , amount1 ='$amount1' , amount2 ='$amount2' , amount3 ='$amount3' , recurring ='$recurring' , reattempt ='$reattempt' , retry_at ='$retry_at' , recur_times ='$recur_times' , username ='$username' , password ='$password' , subscr_id ='$subscr_id' , notify_version ='$notify_version' , verify_sign ='$verify_sign' , status ='$status'";
	$query .= " WHERE std_ipn = \"$std_ipn\"";
	if (mysql_query ($query) && mysql_affected_rows () > 0)
		print ("Entry $std_ipn updated successfully.\n");
	else
		print ("Entry not updated. mysql_error() \n");

	}
	break;
case ADD_ENTRY;
	if ($receiver_email) {
		$std_ipn = add_new($ipn_id,$receiver_email,$business,$item_name,$item_number,$quantity,$invoice,$custom,$option_name1,$option_selection1,$option_name2,$option_selection2,$num_cart_items,$payment_status,$pending_reason,$payment_date,$settle_amount,$settle_currency,$exchange_rate,$payment_gross,$payment_fee,$mc_gross,$mc_fee,$mc_currency,$tax,$txn_id,$txn_type,$memo,$first_name,$last_name,$address_street,$address_city,$address_state,$address_zip,$address_country,$address_status,$payer_email,$payer_id,$payer_status,$payment_type,$subscr_date,$period1,$period2,$period3,$amount1,$amount2,$amount3,$recurring,$reattempt,$retry_at,$recur_times,$username,$password,$subscr_id,$notify_version,$verify_sign,$status) ;
	}
        break;
default:
        break;
}


$dbResult = mysql_query("select * from var_notify where std_ipn='$std_ipn'");
$row=mysql_fetch_object($dbResult);
?>
<div> 
    <form name="edit" METHOD=POST action="<? echo "$PHP_SELF"?>">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td> 

          <table width="100%" border="2">
<tr>
	<td>IPN Order Number</td>
	<td><input name=std_ipn type=text size=35 value="<? echo "$row->std_ipn" ?>"></td>
</tr>
<tr>
	<td>Primary email address of the payment recipient (i.e., the merchant).</td>
	<td><input name=receiver_email type=text size=35 value="<? echo "$row->receiver_email" ?>"></td>
</tr>
<tr>
	<td>Business</td>
	<td><input name=business type=text size=35 value="<? echo "$row->business" ?>"></td>
</tr>
<tr>
	<td>Item Name</td>
	<td><input name=item_name type=text size=35 value="<? echo "$row->item_name" ?>"></td>
</tr>
<tr>
	<td>Quantity</td>
	<td><input name=quantity type=text size=35 value="<? echo "$row->quantity" ?>"></td>
</tr>
<tr>
	<td>Invoice number as passed by you</td>
	<td><input name=invoice type=text size=35 value="<? echo "$row->invoice" ?>"></td>
</tr>
<tr>
	<td>Custom value as passed by you</td>
	<td><input name=custom type=text size=35 value="<? echo "$row->custom" ?>"></td>
</tr>
<tr>
	<td>Option 1 Name as requested by you</td>
	<td><input name=option_name1 type=text size=35 value="<? echo "$row->option_name1" ?>"></td>
</tr>
<tr>
	<td>Option 1 Choice as entered by your customer</td>
	<td><input name=option_selection1 type=text size=35 value="<? echo "$row->option_selection1" ?>"></td>
</tr>
<tr>
	<td>Option 2 Name as requested by you</td>
	<td><input name=option_name2 type=text size=35 value="<? echo "$row->option_name2" ?>"></td>
</tr>
<tr>
	<td>Option 2 Choice as entered by your customer</td>
	<td><input name=option_selection2 type=text size=35 value="<? echo "$row->option_selection2" ?>"></td>
</tr>
<tr>
	<td>If this is a shopping cart transaction, number of items in cart</td>
	<td><input name=num_cart_items type=text size=35 value="<? echo "$row->num_cart_items" ?>"></td>
</tr>
<tr>
	<td>Payment Status The payment is:</td>
	<td><input name=payment_status type=text size=35 value="<? echo "$row->payment_status" ?>"></td>
</tr>
<tr>
	<td>Pending Reason The payment is pending because it was made by an:</td>
	<td><input name=pending_reason type=text size=35 value="<? echo "$row->pending_reason" ?>"></td>
</tr>
<tr>
	<td>Payment Date</td>
	<td><input name=payment_date type=text size=35 value="<? echo "$row->payment_date" ?>"></td>
</tr>
<tr>
	<td>Settle Amount</td>
	<td><input name=settle_amount type=text size=35 value="<? echo "$row->settle_amount" ?>"></td>
</tr>
<tr>
	<td>Settle Currency</td>
	<td><input name=settle_currency type=text size=35 value="<? echo "$row->settle_currency" ?>"></td>
</tr>
<tr>
	<td>Exchange Rate</td>
	<td><input name=exchange_rate type=text size=35 value="<? echo "$row->exchange_rate" ?>"></td>
</tr>
<tr>
	<td>Full USD amount of the customer's payment</td>
	<td><input name=payment_gross type=text size=35 value="<? echo "$row->payment_gross" ?>"></td>
</tr>
<tr>
	<td>USD transaction fee associated with the payment</td>
	<td><input name=payment_fee type=text size=35 value="<? echo "$row->payment_fee" ?>"></td>
</tr>
<tr>
	<td>Full amount of the customer's payment</td>
	<td><input name=mc_gross type=text size=35 value="<? echo "$row->mc_gross" ?>"></td>
</tr>
<tr>
	<td>Transaction fee associated with the payment</td>
	<td><input name=mc_fee type=text size=35 value="<? echo "$row->mc_fee" ?>"></td>
</tr>
<tr>
	<td>Currency</td>
	<td><input name=mc_currency type=text size=35 value="<? echo "$row->mc_currency" ?>"></td>
</tr>
<tr>
	<td>Tax</td>
	<td><input name=tax type=text size=35 value="<? echo "$row->tax" ?>"></td>
</tr>
<tr>
	<td>Transaction ID</td>
	<td><input name=txn_id type=text size=35 value="<? echo "$row->txn_id" ?>"></td>
</tr>
<tr>
	<td>Type of Transaction</td>
	<td><input name=txn_type type=text size=35 value="<? echo "$row->txn_type" ?>"></td>
</tr>
<tr>
	<td>Memo</td>
	<td><input name=memo type=text size=35 value="<? echo "$row->memo" ?>"></td>
</tr>
<tr>
	<td>First Name</td>
	<td><input name=first_name type=text size=35 value="<? echo "$row->first_name" ?>"></td>
</tr>
<tr>
	<td>Last Name</td>
	<td><input name=last_name type=text size=35 value="<? echo "$row->last_name" ?>"></td>
</tr>
<tr>
	<td>Street</td>
	<td><input name=address_street type=text size=35 value="<? echo "$row->address_street" ?>"></td>
</tr>
<tr>
	<td>City</td>
	<td><input name=address_city type=text size=35 value="<? echo "$row->address_city" ?>"></td>
</tr>
<tr>
	<td>State</td>
	<td><input name=address_state type=text size=35 value="<? echo "$row->address_state" ?>"></td>
</tr>
<tr>
	<td>Zip Code</td>
	<td><input name=address_zip type=text size=35 value="<? echo "$row->address_zip" ?>"></td>
</tr>
<tr>
	<td>Country</td>
	<td><input name=address_country type=text size=35 value="<? echo "$row->address_country" ?>"></td>
</tr>
<tr>
	<td>Address Status</td>
	<td><input name=address_status type=text size=35 value="<? echo "$row->address_status" ?>"></td>
</tr>
<tr>
	<td>Customer's primary email address</td>
	<td><input name=payer_email type=text size=35 value="<? echo "$row->payer_email" ?>"></td>
</tr>
<tr>
	<td>Unique customer ID</td>
	<td><input name=payer_id type=text size=35 value="<? echo "$row->payer_id" ?>"></td>
</tr>
<tr>
	<td>Customer has a Verified U.S. PayPal account</td>
	<td><input name=payer_status type=text size=35 value="<? echo "$row->payer_status" ?>"></td>
</tr>
<tr>
	<td>This payment was funded with an</td>
	<td><input name=payment_type type=text size=35 value="<? echo "$row->payment_type" ?>"></td>
</tr>
<tr>
	<td>Version of Instant Payment Notification</td>
	<td><input name=notify_version type=text size=35 value="<? echo "$row->notify_version" ?>"></td>
</tr>
<tr>
	<td>An encrypted string used to validate the authenticity of the transaction</td>
	<td><input name=verify_sign type=text size=35 value="<? echo "$row->verify_sign" ?>"></td>
</tr>
<tr>
	<td>Status</td>
	<td><input name=status type=text size=35 value="<? echo "$row->status" ?>"></td>
</tr>
<tr>
	<td>Subscription Date</td>
	<td><input name=subscr_date type=text size=35 value="<? echo "$row->subscr_date" ?>"></td>
</tr>
<tr>
	<td>Period 1</td>
	<td><input name=period1 type=text size=35 value="<? echo "$row->period1" ?>"></td>
</tr>
<tr>
	<td>Period 2</td>
	<td><input name=period2 type=text size=35 value="<? echo "$row->period2" ?>"></td>
</tr>
<tr>
	<td>Period 3</td>
	<td><input name=period3 type=text size=35 value="<? echo "$row->period3" ?>"></td>
</tr>
<tr>
	<td>Amount 1</td>
	<td><input name=amount1 type=text size=35 value="<? echo "$row->amount1" ?>"></td>
</tr>
<tr>
	<td>Amount 2</td>
	<td><input name=amount2 type=text size=35 value="<? echo "$row->amount2" ?>"></td>
</tr>
<tr>
	<td>Amount 3</td>
	<td><input name=amount3 type=text size=35 value="<? echo "$row->amount3" ?>"></td>
</tr>
<tr>
	<td>Recurring</td>
	<td><input name=recurring type=text size=35 value="<? echo "$row->recurring" ?>"></td>
</tr>
<tr>
	<td>Re Attempt</td>
	<td><input name=reattempt type=text size=35 value="<? echo "$row->reattempt" ?>"></td>
</tr>
<tr>
	<td>Retry at</td>
	<td><input name=retry_at type=text size=35 value="<? echo "$row->retry_at" ?>"></td>
</tr>
<tr>
	<td>Recurring Times</td>
	<td><input name=recur_times type=text size=35 value="<? echo "$row->recur_times" ?>"></td>
</tr>
<tr>
	<td>Username</td>
	<td><input name=username type=text size=35 value="<? echo "$row->username" ?>"></td>
</tr>
<tr>
	<td>Password</td>
	<td><input name=password type=text size=35 value="<? echo "$row->password" ?>"></td>
</tr>
<tr>
	<td>Subscription ID</td>
	<td><input name=subscr_id type=text size=35 value="<? echo "$row->subscr_id" ?>"></td>
</tr>
	  <tr valign=top>
		<td colspan=2>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="1">
			<input type=hidden name=std_ipn value="<?echo "$row->std_ipn"?>">
			<? if ($row->std_ipn) { 
				$value="Update" ;
				print "<input type=hidden name=action value=1>";
			} else {
				$value="Add";
				print "<input type=hidden name=action value=3>";
			}?>
			<input type=submit value="<?echo "$value"?>">
			<a href="<? echo "$PHP_SELF?action=2&std_ipn=$row->std_ipn"?>">Delete</a>
			</font>
		</td>
	  </tr>
	  </table>

        </td>
      </tr>
    </table>
    </form>
    <br>
  </div>
<?


function add_new($ipn_id,$receiver_email,$business,$item_name,$item_number,$quantity,$invoice,$custom,$option_name1,$option_selection1,$option_name2,$option_selection2,$num_cart_items,$payment_status,$pending_reason,$payment_date,$settle_amount,$settle_currency,$exchange_rate,$payment_gross,$payment_fee,$mc_gross,$mc_fee,$mc_currency,$tax,$txn_id,$txn_type,$memo,$first_name,$last_name,$address_street,$address_city,$address_state,$address_zip,$address_country,$address_status,$payer_email,$payer_id,$payer_status,$payment_type,$subscr_date,$period1,$period2,$period3,$amount1,$amount2,$amount3,$recurring,$reattempt,$retry_at,$recur_times,$username,$password,$subscr_id,$notify_version,$verify_sign,$status) {
$q="INSERT INTO var_notify (ipn_id,receiver_email,business,item_name,item_number,quantity,invoice,custom,option_name1,option_selection1,option_name2,option_selection2,num_cart_items,payment_status,pending_reason,payment_date,settle_amount,settle_currency,exchange_rate,payment_gross,payment_fee,mc_gross,mc_fee,mc_currency,tax,txn_id,txn_type,memo,first_name,last_name,address_street,address_city,address_state,address_zip,address_country,address_status,payer_email,payer_id,payer_status,payment_type,subscr_date,period1,period2,period3,amount1,amount2,amount3,recurring,reattempt,retry_at,recur_times,username,password,subscr_id,notify_version,verify_sign,status) VALUES ('$ipn_id','$receiver_email','$business','$item_name','$item_number','$quantity','$invoice','$custom','$option_name1','$option_selection1','$option_name2','$option_selection2','$num_cart_items','$payment_status','$pending_reason','$payment_date','$settle_amount','$settle_currency','$exchange_rate','$payment_gross','$payment_fee','$mc_gross','$mc_fee','$mc_currency','$tax','$txn_id','$txn_type','$memo','$first_name','$last_name','$address_street','$address_city','$address_state','$address_zip','$address_country','$address_status','$payer_email','$payer_id','$payer_status','$payment_type','$subscr_date','$period1','$period2','$period3','$amount1','$amount2','$amount3','$recurring','$reattempt','$retry_at','$recur_times','$username','$password','$subscr_id','$notify_version','$verify_sign','$status') ";
if(!mysql_query($q))
        die("Could not add Page");
return mysql_insert_id();
echo mysql_error();


}

function delete_entry($std_ipn,$confirmed)
{
if ($confirmed == "yes") {
        $q = "DELETE FROM std_notify where std_ipn=\"$std_ipn\"";
	if (!mysql_query($q)) {
		die("Cound not delete std_ipn $std_ipn\n");
	} else {
		print "$std_ipn Deleted from Order Table.";
		?>
		              <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="orderlist.php"><font color="#336699">Back
			                  to orders</font></a></font></div>
		<?
		exit;
	}
} else if ($confirmed == "no") {
    //Do nothing
}else{
        print "<TABLE ALIGN=CENTER><TR><TD>\n";
	print "<form action=\"$PHP_SELF\">";
	print "<input type=hidden name=\"std_ipn\" value=$std_ipn>";
	print "<input type=hidden name=action value=2>";
		   
	print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">";
        print "Are you sure you want to delete std_ipn $std_ipn?<br>\n";
                print "<TABLE><TR><TD>YES</TD><TD>NO</TD></TR>\n";
        print "<TR><TD><input type=radio name=confirmed value=yes></TD>\n";
        print "<TD><input type=radio name=confirmed value=no><input type=hidden name=DELETE value=1></TD></TR>";
        print "<TR><TD><input type=submit value=CONFIRM></td></tr></TABLE>\n";
        print "</TD></TR>\n";
	?>
	              <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="orderlist.php"><font color="#336699">Back
		                  to orders</font></a></font></div>
	<?
 	exit;
}
}
?>
          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="orderlist.php"><font color="#336699">Back 
            to orders</font></a></font></div>
	
<? include ("footer.php");?>
		
<?
exit;
?>


