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

if ($s_name && $s_email && $r_name && $r_email && $payment) {
 if (!$detail) { $detail=''; } else { $detail="\nDetails: ".$detail; }
 if (!$comment) { $comment=''; } else { $comment="\nComments: ".$comment; }
 if (!$shipping) { $shipping=''; } else { $shipping="\nShipping: ".$shipping; }
 if (!eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$', $s_email)) { echo "<tr><td width=100%><font color=#990000><b>ERROR.  Invalid sender email address.  Please go back and enter a valid email address.</td></tr>"; }
 elseif (!eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$', $r_email)) { echo "<tr><td width=100%><font color=#990000><b>ERROR.  Invalid recipient email address.  Please go back and enter a valid email address.</td></tr>"; }
 elseif (!eregi('^[0-9.]*$', $payment)) { echo "<tr><td width=100%><font color=#990000><b>ERROR.  Invalid cash amount.  Please go back and enter a valid cash amount.</td></tr>"; }
 else {
   $subject = 'Money Request';
   $message = "You have just received a money request, submitted on $today. Following are the details of the request.\n\nFrom: $s_name\nEmail Address: $s_email\nRequest Amount: $$payment$detail$comment$shipping";
   $extra = "From: $s_email";
   mail($r_email, $subject, $message, $extra);
   echo "
   <table width=100%>
    <tr><td width=100%>
     <table width=100% cellpadding=2 cellspacing=5 style='border:2 solid #336699;'>
      <tr bgcolor=#336699><td width=100%><font size=2><b>Thank You</td></tr>
      <tr><td width=100%>
      <b>Thank you for sending a Money Request. Your request has been delivered to the address specified.</b>
      </td></tr>
     </table>
    </td></tr>
   </table>";
 }     
} else { echo "
 <table width=100%>
  <tr><td width=100%>
   <table width=100% cellpadding=2 cellspacing=5 style='border:2 solid #336699;'>
    <tr bgcolor=#336699><td width=100%><font size=2><b>Request Money</td></tr>
    <tr><td width=100%>
     <table width=100% cellspacing=2> 
      <tr bgcolor=#eeeeee><td colspan=2 class=both height=25 valign=middle><b>Participant Information</b></td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=40%>*Your Name</td>		  <td bgcolor=#dddddd><input type=text size=25 name=s_name></td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=40%>*Your Email Address</td>	  <td bgcolor=#dddddd><input type=text size=25 name=s_email></td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=40%>*Recipient's Name</td>	  <td bgcolor=#dddddd><input type=text size=25 name=r_name></td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=40%>*Recipient's Email Address</td><td bgcolor=#dddddd><input type=text size=25 name=r_email></td></tr>
     </table> 
     <table width=100% cellspacing=2> 
      <tr bgcolor=#eeeeee><td colspan=2 class=both height=25 valign=middle><b>Transaction Details</b></td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=40%>*Requested Amount in US $</td><td bgcolor=#dddddd><input type=text size=10 name=payment><small> (example: 123.45)</small></td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=40%>Item # or Details</td>	 <td bgcolor=#dddddd><input type=text size=25 name=detail></td></tr>
      <tr><td align=right bgcolor=#DEEDFD width=40%>Comments/Instructions</td>	 <td bgcolor=#eeeeee><textarea rows=3 cols=20 name=comment>Please go to http://www.site-name.com to send me a money order using your PayPal account.</textarea></td></tr>
      <tr><td align=right bgcolor=#A8BAD4 width=40%>Shipping Details</td>	 <td bgcolor=#eeeeee><textarea rows=3 cols=20 name=shipping></textarea></td></tr>
     </table> 
      
    </td></tr>
    <tr bgcolor=#A8BAD4><td height=25 width=100% align=center colspan=2>
     <input type=image src=image/request.gif name=request>
    </td></tr>
      
   </table>
  </td></tr>
 </table>";
}
?>