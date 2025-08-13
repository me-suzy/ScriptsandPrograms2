<?
include ("config.inc.php");
?>
<? include ("header.php");?>

<!-- PayPal -->
<FONT CLASS="checkout">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" onSubmit="return CheckForm(this)">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?echo "$paypalemail"?>">
<input type="hidden" name="return" value="<?echo "$returnurl"?>">
<input type="hidden" name="cancel_return" value="<?echo "$cancelurl"?>"> 
<input type="hidden" name="image_url" value="<?echo "$ssllogo"?>">
<input type="hidden" name="cn" value="Additional comments">
    <NOBR>
    <SCRIPT>
        CheckoutCart();
    </SCRIPT>
    </NOBR>
<br>
<P>
<font class="blacktext">
<b>Please fill out the following information below to complete your order.</b>
</font>

<p>
<b><font class="greetext">Billing Information:</font></b><P>
  <table border="0" cellspacing="2" cellpadding="2">
  <TR>
  <TD><B>First Name:</B></TD><TD><input type="text" name="first_name" length="32"></TD>
  </TR>
  <TR>
  <TD><B>Last Name:</B></TD><TD><input type="text" name="last_name" length="64"></TD>
  </TR>
  <TR>
  <TD><B>Address1:</B></TD><TD><input type="text" name="address1" length="50"></TD>
  </TR>
  <TR>
  <TD><B>Address2:</B></TD><TD><input type="text" name="address2" length="100"></TD>
  </TR>
  <TR>
  <TD><B>City:</B></TD><TD><input type="text" name="city" length="50"></TD>
  </TR>
  <TR>
  <TD><B>State:</B></TD><TD><input type="text" name="state" length="50"></TD>
  </TR>
  <TR>
  <TD><B>Zip:</B></TD><TD><input type="text" name="zip" length="50"></TD>
  </TR>
  <TR>
  <TD><B>Evening Phone:</B></TD><TD><input type="text" name="night_phone_a" size="3" maxlength="3" length="3"><input type="text" name="night_phone_b" size="3" maxlength="3" length="3"><input type="text" name="night_phone_c" size="4" maxlength="4" length="4"></TD>
  </TR>
  <TR>
  <TD><B>Day Phone:</B></TD><TD><input type="text" name="day_phone_a" size="3" maxlength="3" length="3"><input type="text" name="day_phone_b" size="3" maxlength="3" length="3"><input type="text" name="day_phone_c" size="4" maxlength="4" length="4"></TD>
  </TR>
  <TR>
  <TD><B>Email Address:</B></TD><TD><input type="text" name="email" size="30" maxlength="64"></TD>
  </TR> 
  </table>
<!-- <p>
<FONT class="blacktext">
<b><font class="greetext">Comments/Special Instructions:</font></b><P>
<TEXTAREA NAME="cn" ROWS=6 COLS=40>
</TEXTAREA>
</font>
<p> -->
<input type=submit Value="Submit Order"> <INPUT type=RESET value="  Clear Form  ">
    </p>
    </center>    
    </FORM>    
</blockquote>

<!-- LinkPoint

   <FORM ACTION="https://secure.linkpt.net/lpcentral/servlet/lppay" METHOD="POST">	
      <SCRIPT>
         CheckoutCart();
      </SCRIPT>
   <INPUT type="hidden" name="storename" value="--ENTER YOUR STORE ID HERE--">	
   <INPUT type=submit value="Proceed to payment">
   </FORM>	
   


WorldPay

   <FORM ACTION="https://select.worldpay.com/wcc/purchase" METHOD="POST">	
      <SCRIPT>
         CheckoutCart();
      </SCRIPT>
   <input type=hidden name="instId" value="--ENTER WORLDPAY ID HERE--">
   <input type=hidden name="currency" value="USD">
   <input type=hidden name="testMode" value="0">
   <input type=hidden name="cartId" value="WebPurchase">
   <INPUT type=submit value="Proceed to payment">
   </FORM>	
   


Authorize.net

   <FORM ACTION="https://secure.authorize.net/gateway/transact.dll" METHOD="POST">	
      <SCRIPT>
         CheckoutCart();
      </SCRIPT>
   <INPUT TYPE=HIDDEN NAME="x_Login" VALUE="--ENTER YOUR ID HERE--">
   <INPUT type=submit value="Proceed to payment">
   </FORM> -->

<? include ("footer.php");?>


