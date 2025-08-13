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
<table width=100%>
 <tr><td width=100%>
  <table width=100% cellpadding=2 cellspacing=5 style='border:2 solid #336699;'>
   <tr bgcolor=#336699><td width=100%><font size=2><b>Complete Your Order</td></tr>
   <tr><td width=100%>
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
     <tr><td align=right bgcolor=#DEEDFD width=30%>Recipient's Name</td>	<td bgcolor=#dddddd><input type=text size=25 name=r_name></td></tr>
     <tr><td align=right bgcolor=#A8BAD4 width=30%>Street Address</td>		<td bgcolor=#eeeeee><textarea rows=3 cols=20 name=r_address></textarea></td></tr>
     <tr><td align=right bgcolor=#DEEDFD width=30%>City</td>			<td bgcolor=#dddddd><input type=text size=25 name=r_city></td></tr>
     <tr><td align=right bgcolor=#A8BAD4 width=30%>State</td>			<td bgcolor=#eeeeee><input type=text size=25 name=r_state></td></tr>
     <tr><td align=right bgcolor=#DEEDFD width=30%>ZIP or Postal Code</td>	<td bgcolor=#dddddd><input type=text size=25 name=r_zip></td></tr>
     <tr><td align=right bgcolor=#A8BAD4 width=30%>Country</td>			<td bgcolor=#eeeeee><select name=r_country><option value=US>USA<option value=Canada>Canada<option value=UK>United Kingdom</select></td></tr>
     <tr><td align=right bgcolor=#DEEDFD width=30%>Email Address</td>		<td bgcolor=#dddddd><input type=text size=25 name=r_email></td></tr>
    </table> 
    <table width=100% cellspacing=2> 
     <tr bgcolor=#eeeeee><td colspan=2 class=both height=25 valign=middle><b>Sender Information...</b></td></tr>
     <tr><td align=right bgcolor=#DEEDFD width=30%>Sender's Name</td>   	<td bgcolor=#dddddd><input type=text size=25 name=s_name></td></tr>
     <tr><td align=right bgcolor=#A8BAD4 width=30%>Email Address</td>		<td bgcolor=#dddddd><input type=text size=25 name=s_email><br><small>This email must match your PayPal email address</small></td></tr>
     <tr><td align=right bgcolor=#DEEDFD width=30%>Street Address</td>		<td bgcolor=#eeeeee><textarea rows=3 cols=20 name=s_address></textarea></td></tr>
     <tr><td align=right bgcolor=#A8BAD4 width=30%>City</td>			<td bgcolor=#dddddd><input type=text size=25 name=s_city></td></tr>
     <tr><td align=right bgcolor=#DEEDFD width=30%>State</td>			<td bgcolor=#eeeeee><input type=text size=25 name=s_state></td></tr>
     <tr><td align=right bgcolor=#A8BAD4 width=30%>ZIP or Postal Code</td>	<td bgcolor=#dddddd><input type=text size=25 name=s_zip></td></tr>
     <tr><td align=right bgcolor=#DEEDFD width=30%>Country</td>			<td bgcolor=#eeeeee><select name=s_country><option value=US>USA<option value=Canada>Canada<option value=UK>United Kingdom</select></td></tr>
     <tr><td align=right bgcolor=#A8BAD4 width=30%>Phone Number</td>		<td bgcolor=#dddddd><input type=text size=25 name=s_phone></td></tr>
    </table> 
    <table width=100% cellspacing=2> 
     <tr bgcolor=#eeeeee><td colspan=2 class=both height=25 valign=middle><b>Additional Information...(Optional)</b></td></tr>
     <tr><td align=right bgcolor=#DEEDFD width=30%>Auction Site</td>   		<td bgcolor=#dddddd><input type=text size=25 name=o_auction></td></tr>
     <tr><td align=right bgcolor=#A8BAD4 width=30%>Item Number</td>		<td bgcolor=#dddddd><input type=text size=25 name=o_item></td></tr>
     <tr><td align=right bgcolor=#DEEDFD width=30%>Sender User ID</td>		<td bgcolor=#eeeeee><input type=text size=25 name=o_id></td></tr>
     <tr><td align=right bgcolor=#A8BAD4 width=30%>Item Description</td>	<td bgcolor=#dddddd><textarea rows=3 cols=20 name=o_description></textarea></td></tr>
    </table> 
    <table width=100% cellspacing=2> 
     <tr bgcolor=#eeeeee><td class=both height=25 valign=middle><b>Terms Agreement</b></td></tr>
     <tr><td align=center><input type=checkbox name=terms>I agree to the <a class=main href=?v=legal>Terms of Service</a></td></tr>
    </table> 
     
   </td></tr>
   <tr bgcolor=#A8BAD4><td height=25 width=100% align=center colspan=2>
    <input type=hidden name=payment value=$payment>
    <input type=hidden name=send value='$send'>
    <input type=image src=image/continue.gif name=check_entry>
   </td></tr>
     
  </table>
 </td></tr>
</table>";
?>