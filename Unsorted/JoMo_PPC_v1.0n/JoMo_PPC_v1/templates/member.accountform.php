
<table border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#E0E0E0">
 <tr valign="top">
  <td align="left">
  	Welcome <%$member.firstName%> <%$member.lastName%>!<br>
  	<%$msg%>
  </td>
  
 </tr>

<tr valign="top">
  <td align="left">
  	<b>Account info</b><br>
  	Current Balance: $<%$account.balance%>	<br>
  	your account is <b><%if $account.isActive == 1%>active<%else%>not active<%/if%></b><br>
  	minimum available account balance: $<%$minBalance%><br>
  </td>
</tr>

<tr>
  <td colspan="" align="center">
  <br>

					<%if $payment_debug == 1%>
					
					<form action="http://<%$siteURL%>?mode=members&memberMode=payment_notify" method="post">			
						<input type="hidden" name="custom" value="<%$member.memberID%>">
						deposit money:$<input type="text" name = "amount" value="10" style="width:50;">
						
						<input type="submit" name="" value="debug paypal">
						
					</form>
						
					<%else%>
  					
        			<form name="formPaypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" onclick="return onFormSubmit();">
						<input type="hidden" name="cmd" value="_xclick">
						<input type="hidden" name="business" value="<%$paypal_account%>">
						<input type="hidden" name="item_number" value="1">
						<input type="hidden" name="item_name" value="deposit money for member account">
						
						<%if $paypalFee == 1%>
						Paypal payments are charged a transaction fee. This fee will be subtracted from your total deposit;
						To see paypal transaction fee click <a href="http://www.paypal.com/cgi-bin/webscr?cmd=p/gen/fees-outside
" target="_blank">here</a>
						<%else%>
						<%/if%>
						<br>
						deposit money: (via PayPal) $<input type="text" name = "amount" value="10" style="width:50;">

						<!--						
						<input type="hidden" name="amount" value="10">
						-->
						
						<input type="hidden" name="custom" value="<%$member.memberID%>">
					
						<input type="hidden" name="return" value="http://<%$siteURL%>?mode=members&memberMode=payment_result&PHPSESSID=<%$session%>&is_success=1">
						<input type="hidden" name="cancel_return" value="http://<%$siteURL%>?mode=members&memberMode=payment_result&PHPSESSID=<%$session%>&is_success=0">
						<input type="hidden" name="notify_url" value="http://<%$siteURL%>?mode=members&memberMode=payment_notify">
					
						<input type="image" src="http://images.paypal.com/images/x-click-but01.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
					</form>
					<br>
					<form name="formPaypal" action="https://www.2checkout.com/cgi-bin/sbuyers/cartpurchase.2c" method="post">
					<img src="../images/ccards.gif"> Credit Card Deposit: $<input type="text" name = "total" value="10" style="width:50;">
					<input type=hidden name=sid value="<%$num_2checkout%>">
					<input type=hidden name=cart_order_id value="<%$member.memberID%>">
					<%if $demoMode == 1 %>
					<input type=hidden name=demo value='Y'>
					<%/if%>
					<input type=submit border="0" value='Pay'>
					<%if $demoMode == 1 %>
					(Demo Mode)
					<%/if%>
					</form>
					<%/if%>	

        <!--
        <input type="submit" value="deposit(for debug only)">
        -->
        
        
        <input type="hidden" name = "mode" value="members">
        <input type="hidden" name = "memberMode" value="account">

		<input type="hidden" name = "cmd" value="deposit">
        <input type="hidden" name = "memberID" value="<%$member.memberID%>">
        
  </td>

 </tr>
 
 <tr valign="top">
  <td align="center">
  	<a href="<%$selfURL%>?mode=members&memberMode=viewtrans">View Past Transactions </a>
  </td>
</tr>

</table>

