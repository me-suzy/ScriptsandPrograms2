<?
echo "
<table width=100%>
        <tr><td width=100% height=125 align=center valign=middle style='border:1 solid #000000;'>
          <img border=0 src=image/sm.gif></td></tr>
        <tr><td width=100%>
         <table width=100% style='border:2 solid #336699;'>
          <tr bgcolor=#336699><td width=100%><font color=#FFFFFF><b>Send A Money Order!</b></font></td></tr>
          <tr><td width=100%>
           <table width=100% cellpadding=2 cellspacing=5>
            <tr><td width=100% align=center colspan=2>
             Please enter the amount of the Money Order you would like to purchase:
            </td></tr>
            <tr bgcolor=#EBEDCC><td height=25 width=100% align=center colspan=2>
             <b>Amount in US $</b><input type=text size=10 name=payment><small> (example: 123.45)</small>
            </td></tr>
            <tr><td height=25 width=100% colspan=2>
             <small>Please select delivery method for sending your money order:</small>
            </td></tr>
            <tr bgcolor=#DEEDFD><td height=25>
             <input type=radio name=send value=first><small><b>First Class Mail</b> -> Received in 3-5 days</small>
            </td><td bgcolor=#EBEDCC width=10%><b>FREE</b></td></tr>
            <tr bgcolor=#DEEDFD><td height=25>
             <input type=radio name=send value=regular><small><b>First Class Mail</b> -> Received in 4-7 days (International Orders)</small>
            </td><td bgcolor=#EBEDCC width=10%><b>FREE</b></td></tr>
            <tr bgcolor=#DEEDFD><td height=25>
             <input type=radio name=send value=priority><small><b>Priority Mail</b> -> Received in 2-3 days (United States)</small>
            </td><td bgcolor=#EBEDCC width=10%><b>$6.00</b></td></tr>
            <tr bgcolor=#DEEDFD><td height=25>
             <input type=radio name=send value=express><small><b>Express Mail</b> -> Overnight Delivery in Most US Areas</small>
            </td><td bgcolor=#EBEDCC width=10%><b>$15.00</b></td></tr>
            <tr><td height=25 width=100% colspan=2>
             <tiny>Click continue to go to the next step:</tiny>
            </td></tr>
            <tr bgcolor=#A8BAD4><td height=25 width=100% align=center colspan=2>
             <input type=image src=image/continue.gif>
            </td></tr>
            
           </table>
          </td></tr>
         </table>
        </td></tr>
       </table>";
?>