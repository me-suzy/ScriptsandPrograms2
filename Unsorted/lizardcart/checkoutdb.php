<?
session_start();
session_register("first_name","last_name","address1","address2","city","state","zip","night_phone_a","day_phone_a","email","country","TOTAL","SUBTOTAL","SHIPPING","TAX","item_name","comment");
?>
<?
include ("config.inc.php");
?>
<? include ("header.php");?>



<br>
<P>
<font class="blacktext">
<h2>Step 2</h2>
<b>Make sure that the following information below is correct then add your credit card information</b>
</font>
<form action="<? echo "$secureurl" ?>lizardcart/thankyou.php" method=POST onSubmit="return CheckForm(this)">
<input type="hidden" name="SUBTOTAL" value="<? echo "$SUBTOTAL"?>"><br>
<input type="hidden" name="SHIPPING" value="<? echo "$SHIPPING"?>"><br>
<input type="hidden" name="TAX" value="<? echo "$TAX"?>">
<input type="hidden" name="item_name" value="<? echo "$item_name"?>">
<p>
<b><font class="greetext">Billing Information:</font></b><P>
  <table border="0" cellspacing="2" cellpadding="2">
  <TR>
  <TD><B>First Name:</B></TD><TD><input type="text" name="first_name" value="<? echo "$first_name"?>" length="32"></TD>
  </TR>
  <TR>
  <TD><B>Last Name:</B></TD><TD><input type="text" name="last_name" value="<? echo "$last_name"?>" length="64"></TD>
  </TR>
  <TR>
  <TD><B>Address1:</B></TD><TD><input type="text" name="address1" value="<? echo "$address1"?>" length="50"></TD>
  </TR>
  <TR>
  <TD><B>Address2:</B></TD><TD><input type="text" name="address2" value="<? echo "$address2"?>" length="100"></TD>
  </TR>
  <TR>
  <TD><B>City:</B></TD><TD><input type="text" name="city" value="<? echo "$city"?>" length="50"></TD>
  </TR>
  <TR>
  <TD><B>State:</B></TD><TD><input type="text" name="state" value="<? echo "$state"?>" length="50"></TD>
  </TR>
  <TR>
  <TD><B>Zip:</B></TD><TD><input type="text" name="zip" value="<? echo "$zip"?>" length="50"></TD>
  </TR>
  </TR>
  <TR>
  <TD><B>Country:</B></TD><TD><input type="text" name="country" value="US" length="50"></TD>
  </TR>  
  <TR>
  <TD><B>Evening Phone:</B></TD><TD><input type="text" name="night_phone_a" value="<? echo "$night_phone_a"?>" size="10" maxlength="15" length="3"></TD>
  </TR>
  <TR>
  <TD><B>Day Phone:</B></TD><TD><input type="text" name="day_phone_a" size="10" value="<? echo "$day_phone_a"?>" maxlength="15" length="3"></TD>
  </TR>
  <TR>
  <TD><B>Email Address:</B></TD><TD><input type="text" name="email" value="<? echo "$email"?>" size="30" maxlength="64"></TD>
  </TR>
    <TR>
  <TD><B>Amount to Charge:</B></TD><TD><input type=text name=TOTAL value="<? echo "$TOTAL"?>"></TD>
  </TR>
    <TR>
  <TD><B>CC Number:</B></TD><TD><input type=text name=CCNumber></TD>
  </TR>
    <TR>
  <TD><B>CC Expiration Date (mm/yyyy):</B></TD><TD><input type=text name=CCExpireDate></TD>
  </TR>
  </table>
<p>
<FONT class="blacktext">
<b><font class="greetext">Comments/Special Instructions:</font></b><P>
<TEXTAREA NAME="comment" ROWS=6 COLS=40><? echo "$comment"?></TEXTAREA>

</font>
<p>
<input type=hidden name=submit value=1>
<input type=submit Value="Submit Order"> <INPUT type=RESET value="Clear Form">
    </p>
    </center>    
    </FORM>    
</blockquote>
<? include ("footer.php");?>





