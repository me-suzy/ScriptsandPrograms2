<?
if(!$user[id])
{
	lib_redirect("You must be logged in first.", "ad.php", 3);
	exit;
}

$zid = $user['sid'];
$S[paypal_default] = number_format($S[paypal_default],2);

$out .= "
<font size=+1><b>Add Money To Your Account</b></font><p>
<font size=2>
<table width=100% border=0 cellspacing=0 cellpadding=5>
<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">
<tr>
	<td valign=top bgcolor=EEEEEE width=60%>
		Add money to your account by clicking on the Paypal logo
		to the right. As soon as you are finished the transaction 
		you will be returned to your account screen, and your ads 
		will go online.<p>
		<table width=100% border=0 cellspacing=0 cellpadding=3>
		<tr>
			<td width=1><b>Amount:&nbsp;</b></td>
			<td><input type=\"text\" name=\"amount\" value=\"$S[paypal_default]\" size=10></td>
		</tr>
		</table>
	</td>
	<td width=2%>&nbsp;</td>
	<td align=center width=38% valign=top>
		<font size=1>We accept Credit Cards and Checks</font><br>
		<img alt=\"We Accept All Major Credit Cards and E-checks\" src=\"creditcards.gif\" border=0>
		<br>
		&nbsp;
		<br>
			<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
			<input type=\"hidden\" name=\"business\" value=\"$S[paypal_email]\">
			<input type=\"hidden\" name=\"item_name\" value=\"$S[paypal_transaction_name]\">
			<input type=\"hidden\" name=\"item_number\" value=\"10\">
			<input type=\"hidden\" name=\"return\" value=\"$S[paypal_success]\">
			<input type=\"hidden\" name=\"cancel_return\" value=\"$S[paypal_cancel]\">
			<input type=\"hidden\" name=\"no_note\" value=\"1\">
			<input type=\"hidden\" name=\"no_shipping\" value=\"1\">
			<input type=\"hidden\" name=\"custom\" value=\"$zid\">
			<input type=\"image\" src=\"https://www.paypal.com/images/x-click-butcc.gif\" border=\"0\" name=\"submit\" alt=\"Make payments with PayPal - it's fast, free and secure!\">
			<br>
			<font size=1>Click above</font>				
	</td>
</tr>		
</form>
</table> 
</font>
 ";
 
lib_main($out); 
 
?>


