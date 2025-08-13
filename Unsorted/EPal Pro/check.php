<!--
-->
<?
/*****************************************************************/
/* Program Name         : EPal Pro                               */
/* Program Version      : 1.0                                    */
/* Program Author       : BlizSoft                               */
/* Site                 : http://www.BlizSoft.com                */
/* Email                : software@BlizSoft.com                  */
/*                                                               */
/* Copyright (c) 2003 BlizSoft.com All rights reserved.          */
/* Do NOT remove any of the copyright notices in the script.     */
/* This script can not be distributed or resold by anyone else   */
/* than the author, unless special permisson is given.           */
/*                                                               */
/*****************************************************************/

$fee = round($payment * .0889,2); 
if ($send == 'first')    { $shipping = "0.00"; }
if ($send == 'regular')  { $shipping = "0.00"; }
if ($send == 'priority') { $shipping = "6.00"; }
if ($send == 'express')  { $shipping = "15.00"; }
$total = round($payment + $fee + $shipping,2);

echo "
<input type=hidden name=send    	value='$send'>
<input type=hidden name=r_name    	value='$r_name'>
<input type=hidden name=r_address    	value='$r_address'>
<input type=hidden name=r_city   	value='$r_city'>
<input type=hidden name=r_state   	value='$r_state'>
<input type=hidden name=r_zip     	value='$r_zip'>
<input type=hidden name=r_country 	value='$r_country'>
<input type=hidden name=r_email   	value='$r_email'>
<input type=hidden name=s_name    	value='$s_name'>
<input type=hidden name=s_email    	value='$s_email'>
<input type=hidden name=s_address    	value='$s_address'>
<input type=hidden name=s_city    	value='$s_city'>
<input type=hidden name=s_state   	value='$s_state'>
<input type=hidden name=s_zip     	value='$s_zip'>
<input type=hidden name=s_country 	value='$s_country'>
<input type=hidden name=s_phone   	value='$s_phone'>
<input type=hidden name=o_auction_site  value='$o_auction_site'>
<input type=hidden name=o_item_num    	value='$o_item_num'>
<input type=hidden name=o_id    	value='$o_id'>
<input type=hidden name=o_description 	value='$o_description'>
<input type=hidden name=total value='$total'>
<input type=hidden name=payment value='$payment'>
<input type=hidden name=shipping value='$shipping'>
<input type=hidden name=method value='$method'>
<input type=hidden name=fee value='$fee'>
<table width=100%>
 <tr><td width=100%>
  <table width=100% cellpadding=2 cellspacing=5 style='border:2 solid #336699;'>
   <tr bgcolor=#336699><td width=100%><font size=2><b>Complete Your Order</td></tr>";
   if (!$r_name) 	{ echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter recipients name.</td></tr>"; }
   elseif (!$r_address) { echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter recipients mailing address.</td></tr>"; }
   elseif (!$r_city) 	{ echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter recipients city.</td></tr>"; }
   elseif (!$r_state) 	{ echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter recipients state.</td></tr>"; }
   elseif (!$r_zip) 	{ echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter recipients ZIP or postal code.</td></tr>"; }
   elseif (!$r_country) { echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter recipients country.</td></tr>"; }
   elseif (!$s_name) 	{ echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter senders name.</td></tr>"; }
   elseif (!$s_email) 	{ echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter senders email address.</td></tr>"; }
   elseif (!eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$', $s_email)) { echo "<tr><td width=100%><font color=#990000><b>ERROR.  Invalid sender email address.  Please go back and enter a valid email address.</td></tr>"; }
   elseif (!$s_address) { echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter senders mailing address.</td></tr>"; }
   elseif (!$s_city) 	{ echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter senders city.</td></tr>"; }
   elseif (!$s_state) 	{ echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter senders state.</td></tr>"; }
   elseif (!$s_zip) 	{ echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter senders ZIP or postal code.</td></tr>"; }
   elseif (!$s_country) { echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter senders country.</td></tr>"; }
   elseif (!$s_phone) 	{ echo "<tr><td width=100%><font color=#990000><b>ERROR.  Please go back and enter senders phone.</td></tr>"; }
   elseif (!eregi('^[0-9 ()-]*$', $s_phone)) { echo "<tr><td width=100%><font color=#990000><b>ERROR.  Invalid phone number.  Please go back and enter a valid phone number.</td></tr>"; }
   elseif ($terms != 'on') { echo "<tr><td width=100%><font color=#990000><b>ERROR.  You must agree to the Terms of Service.  Please go back and read the Terms of Service before agreeing.</td></tr>"; }
   else { 
    if (!$o_auction) { $o_auction = ''; }
    if (!$o_item) 	{ $o_item = ''; }
    if (!$o_id) 	{ $o_id = ''; }
    if (!$o_description){ $o_description = ''; }
    echo "
    <tr><td width=100%>
     <font color=#990000><b>Please review your order below. Please press the back button on your browser to correct any errors.</b</font>
     <table width=100% bgcolor=#EBEDCC>
      <tr>
       <td width=50% align=right>Money Order Amount:</td><td width=50%>$"; convert($payment); echo "</td>
      </tr><tr> 
       <td width=50% align=right>Associated Fee:</td>    <td width=50%>$"; convert($fee); echo "</td>
      </tr><tr> 
       <td width=50% align=right>Shipping:</td>          <td width=50%>$"; convert($shipping); echo "</td>
      </tr><tr> 
       <td width=50% align=right>Total to be Paid:</td>  <td width=50%>$"; convert($total); echo "</td>
      <tr>
      </td></tr>
     </table>
    </td></tr>
    <tr><td width=100%>
     <table width=100% cellspacing=2> 
      <tr bgcolor=#eeeeee><td colspan=2 class=both height=25 valign=middle><b>Ship Money Order To...</b></td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=30%>Recipient's Name</td>	<td bgcolor=#dddddd>$r_name</td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=30%>Street Address</td>		<td bgcolor=#eeeeee>$r_address</textarea></td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=30%>City</td>			<td bgcolor=#dddddd>$r_city</td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=30%>State</td>			<td bgcolor=#eeeeee>$r_state</td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=30%>ZIP or Postal Code</td>	<td bgcolor=#dddddd>$r_zip</td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=30%>Country</td>		<td bgcolor=#eeeeee>$r_country</td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=30%>Email Address</td>		<td bgcolor=#dddddd>$r_email</td></tr>
     </table> 
     <table width=100% cellspacing=2> 
      <tr bgcolor=#eeeeee><td colspan=2 class=both height=25 valign=middle><b>Sender Information...</b></td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=30%>Sender's Name</td>   	<td bgcolor=#dddddd>$s_name</td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=30%>Email Address</td>		<td bgcolor=#dddddd>$s_email</td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=30%>Street Address</td>		<td bgcolor=#eeeeee>$s_address</textarea></td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=30%>City</td>			<td bgcolor=#dddddd>$s_city</td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=30%>State</td>			<td bgcolor=#eeeeee>$s_state</td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=30%>ZIP or Postal Code</td>	<td bgcolor=#dddddd>$s_zip</td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=30%>Country</td>		<td bgcolor=#eeeeee>$s_country</td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=30%>Phone Number</td>		<td bgcolor=#dddddd>$s_phone</td></tr>
     </table> 
     <table width=100% cellspacing=2> 
      <tr bgcolor=#eeeeee><td colspan=2 class=both height=25 valign=middle><b>Additional Information...(Optional)</b></td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=30%>Auction Site</td>   		<td bgcolor=#dddddd>$o_auction</td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=30%>Item Number</td>		<td bgcolor=#dddddd>$o_item</td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=30%>Sender User ID</td>		<td bgcolor=#eeeeee>$o_id</td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=30%>Item Description</td>	<td bgcolor=#dddddd>$o_description</textarea></td></tr>
     </table> 
      
    </td></tr>
    <tr bgcolor=#A8BAD4><td height=25 width=100% align=center colspan=2>
     <input type=hidden name=payment value=$payment>
     <input type=hidden name=send value=$send>
     <input type=image name=confirmed src=image/continue.gif>
    </td></tr>";
   } echo "  
  </table>
 </td></tr>
</table>";
?>