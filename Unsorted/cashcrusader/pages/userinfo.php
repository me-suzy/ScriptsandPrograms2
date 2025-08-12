<? include("setup.php");?>
<? login();?>        
<? include("header.php");?>
<? include("account_menu.php");?>
<table width="85%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td> 
<form method="POST">
<table border=0  width=100% align="center" cellspacing="5" cellpadding="5">
<input type=hidden name=user_form value=userinfo>
<input type=hidden name=required_keywords value=3>
<input type=hidden name=required value='email,first_name,last_name,address,city,state,zipcode,country'>
<tr> 
<td> 
<div align="left"><font size="2"><b>Username:</b></font></div>
</td>
<td> <font size="2"> 
<? user("username");?>
</font></td>
</tr>
<? form_errors("email","You must place an email address in the email address field","The email address you select is already in use please try another","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td> 
<div align="left"><font size="2"><b>E-Mail:</b></font></div>
</td>
<td> <font size="2"> 
<input type="text" name="userform[email]" value="<? user("email");?>">
</font></td>
</tr>
<? form_errors("first_name","You must place your first name in the first name field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td> 
<div align="left"><font size="2"><b>First Name:</b></font></div>
</td>
<td> <font size="2"> 
<input type="text" name="userform[first_name]" value="<? user("first_name");?>">
</font></td>
</tr>
<? form_errors("last_name","You must place your last name in the last name field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td> 
<div align="left"><font size="2"><b>Last Name:</b></font></div>
</td>
<td> <font size="2"> 
<input type="text" name="userform[last_name]" value="<? user("last_name");?>">
</font></td>
</tr>
<? form_errors("address","You must place your street address in the address field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td> 
<div align="left"><font size="2"><b>Address:</b></font></div>
</td>
<td> <font size="2"> 
<input type="text" name="userform[address]" value="<? user("address");?>">
</font></td>
</tr>
<? form_errors("city","You must place your city in the city field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td> 
<div align="left"><font size="2"><b>City:</b></font></div>
</td>
<td> <font size="2"> 
<input type="text" name="userform[city]" value="<? user("city");?>">
</font></td>
</tr>
<? form_errors("state","You must place your state in the state field or type N/A if you do not have a state","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td> 
<div align="left"><font size="2"><b>State:</b></font></div>
</td>
<td> <font size="2"> 
<input type="text" name="userform[state]" value="<? user("state");?>">
</font></td>
</tr>
<? form_errors("zipcode","You must place your zip or postal code in the zip code field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td> 
<div align="left"><font size="2"><b>Zip Code:</b></font></div>
</td>
<td> <font size="2"> 
<input type="text" name="userform[zipcode]" value="<? user("zipcode");?>">
</font></td>
</tr>
<? form_errors("country","You must place your county in the country field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td> 
<div align="left"><font size="2"><b>Country:</b></font></div>
</td>
<td> <font size="2"> 
<input type="text" name="userform[country]" value="<? user("country");?>">
</font></td>
</tr>
<tr> 
<td colspan=2>&nbsp; 
<tr> 
<td colspan="2"> 
<hr>
</td>
</tr>
<tr> 
<td><font size="2"><b>Referrer:</b></font></td>
<td> <font size="2"> 
<? user("referrer");?>
</font></td>
</tr>
<tr> 
<td colspan="2"> 
<hr>
</td>
</tr>
<? form_errors("keyword","You must pick at least 3 interest groups","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td colspan=2 align=center> <font size="2"><b>Select categories of interests to 
you:<br>
<br>
</b> </font> 
<table border="0" cellspacing="1" cellpadding="1" width="100%">
<tr valign="top"> 
<td><font size="2"><b> 
<input type=checkbox name=keyword[0] value=Arts <? interests("arts","checked");?>>
Arts <br>
<input type=checkbox name=keyword[1] value=Automotive <? interests("automotive","checked");?>>
Automotive<br>
<input type=checkbox name=keyword[2] value=Business <? interests("business","checked");?>>
Business<br>
<input type=checkbox name=keyword[3] value=Computers <? interests("computers","checked");?>>
Computers<br>
<input type=checkbox name=keyword[4] value=Education <? interests("education","checked");?>>
Education<br>
<input type=checkbox name=keyword[5] value=Entertainment <? interests("entertainment","checked");?>>
Entertainment<br>
<input type=checkbox name=keyword[6] value=Financial <? interests("financial","checked");?>>
Financial</b></font></td>
<td><font size="2"><b> 
<input type=checkbox name=keyword[7] value=Games <? interests("games","checked");?>>
Games<br>
<input type=checkbox name=keyword[8] value=Health <? interests("health","checked");?>>
Health<br>
<input type=checkbox name=keyword[9] value=Home <? interests("home","checked");?>>
Home<br>
<input type=checkbox name=keyword[10] value=Internet <? interests("internet","checked");?>>
Internet<br>
<input type=checkbox name=keyword[11] value=News <? interests("news","checked");?>>
News<br>
<input type=checkbox name=keyword[12] value=Media <? interests("media","checked");?>>
Media<br>
<input type=checkbox name=keyword[13] value=Recreation <? interests("recreation","checked");?>>
Recreation<br>
</b></font></td>
<td><font size="2"><b> 
<input type=checkbox name=keyword[14] value=Reference <? interests("reference","checked");?>>
Reference<br>
<input type=checkbox name=keyword[15] value=Search <? interests("search","checked");?>>
Search<br>
<input type=checkbox name=keyword[16] value=Science <? interests("science","checked");?>>
Science<br>
<input type=checkbox name=keyword[17] value=Social <? interests("social","checked");?>>
Social <br>
<input type=checkbox name=keyword[18] value=Sports <? interests("sports","checked");?>>
Sports<br>
<input type=checkbox name=keyword[19] value=Technology <? interests("technology","checked");?>>
Technology<br>
<input type=checkbox name=keyword[20] value=Travel <? interests("travel","checked");?>>
Travel</b></font></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td colspan=2> 
<hr>
</td>
</tr>
<? form_errors("vacation","You have entered an invalid date. Please use the format MM/DD/YYYY","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td colspan=2 align=center><font size="2"><b>Are you going on a vacation? <br>
</b>If you would like to prevent your email from filling up while you are gone, 
simply put the date you will be back in the field below.<b><br>
</b> <b>You will not accumulate earnings<b><br>
</b> from your referrals while on vacation<br>
<input type=date name="userform[vacation]" value=<? user("vacation");?>>
<br>
</b>(use the format MM/DD/YYYY)</font></td>
</tr>
<tr> 
<td colspan=2> 
<hr>
</td>
</tr>
<tr> 
<td colspan=2 align=center><font size="2"><b>Current payment method: 
<? user("pay_type");?>
<br>
<select name="userform[pay_type]">
<option value="" selected>Select a payment method </option>
<option value="PayPal">PayPal </option>
<option value="Check">Check </option>
<option value="e-Gold">e-Gold</option>
</select>
</b></font></td>
</tr>
<tr> 
<td colspan=2 align=center><font size="2"><b>Payment account ID:<br>
<input type=text size=25 name="userform[pay_account]" value="<? user("pay_account");?>">
</b></font></td>
</tr>
<tr> 
<td colspan=2> 
<hr>
</td>
</tr>
<? form_errors("password","The password you entered did not match what you put in the confirmation field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><font size="2"><b>New Password:</b></font></td>
<td> <font size="2"> 
<input type=password name="userform[password]">
</font></td>
</tr>
<tr> 
<td><font size="2"><b>Confirm New Password:</b></font></td>
<td> <font size="2"> 
<input type=password name="userform[confirm_password]">
</font></td>
</tr>
<tr> 
<td colspan=2 align=center> <font size="2"><b> 
<input type="submit" value="Save Changes">
</b></font></td>
</tr>
</table>
</form>
<font size="2">&nbsp;</font></td>
</tr>
</table>
<br>
<br>
<br>
<br>
<form method=post>
<table  width=100% border=1 align="center">
<input type=hidden name=user_form value=userinfo>
<? form_errors("cancel_password","Your account was not cancelled because you entered your password incorrectly or your account holds a negitive cash balance","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td align=center bgcolor="#FFFFFF"> 
<p><b><font size="4" color="#FF0000">Cancel Your Account Here.</font></b></p>
<p><font size="2" color="#000000"><b>If you wish to cancel your account and forfeit 
your earnings and referrals enter your password and click on the delete button 
below</b><br>
<input type=password name=userform[cancel_password]>
<br>
<input type=submit value='DELETE YOUR ACCOUNT?' name="submit">
<br>
<br>
</font></p>
</td>
</tr>
</table>
</form>
<br>
<? include("footer.php");?>
