<? include("setup.php");?>
<? include("header.php");?>
<table width="85%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td align="center"> <font size="2"><b><br>
To complete the sign up process, you must provide the requested information below.</b></font><br>
<br>
<form method="POST">
<table border=0  width=100% cellspacing="5" cellpadding="5" align="center">
<input type=hidden name=required_keywords value=3>
<input type=hidden name=user_form value=signup>
<input type=hidden name=userform[code] value=<? userform("code");?>>
<input type=hidden name=required value='username,email,first_name,last_name,address,city,state,zipcode,country,password'>
<? form_errors("username","You must place a username in the username field","The username you select is already in use please try another","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><b><font size="2">Username:</font></b><br><font size=1>Use letters and numbers only</font></td>
<td> <font size="2"><b> 
<input type="text" name="userform[username]" maxlength=16 value="<? userform("username");?>">
</b></font></td>
</tr>
<? form_errors("email","You must place an email address in the email address field","The email address you select is already in use please try another","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><b><font size="2">E-Mail:</font></b></td>
<td> <font size="2"><b> 
<input type=hidden name="userform[email]" value="<? userform("email");?>">
<? userform("email");?>
</b></font></td>
</tr>
<? form_errors("first_name","You must place your first name in the first name field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><b><font size="2">First Name:</font></b></td>
<td> <font size="2"><b> 
<input type="text" name="userform[first_name]" value="<? userform("first_name");?>">
</b></font></td>
</tr>
<? form_errors("last_name","You must place your last name in the last name field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><b><font size="2">Last Name:</font></b></td>
<td> <font size="2"><b> 
<input type="text" name="userform[last_name]" value="<? userform("last_name");?>">
</b></font></td>
</tr>
<? form_errors("address","You must place your street address in the address field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><b><font size="2">Address:</font></b></td>
<td> <font size="2"><b> 
<input type="text" name="userform[address]" value="<? userform("address");?>">
</b></font></td>
</tr>
<? form_errors("city","You must place your city in the city field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><b><font size="2">City:</font></b></td>
<td> <font size="2"><b> 
<input type="text" name="userform[city]" value="<? userform("city");?>">
</b></font></td>
</tr>
<? form_errors("state","You must place your state in the state field or type N/A if you do not have a state","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><b><font size="2">State:</font></b></td>
<td> <font size="2"><b> 
<input type="text" name="userform[state]" value="<? userform("state");?>">
</b></font></td>
</tr>
<? form_errors("zipcode","You must place your zip or postal code in the zip code field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><b><font size="2">Zip Code:</font></b></td>
<td> <font size="2"><b> 
<input type="text" name="userform[zipcode]" value="<? userform("zipcode");?>">
</b></font></td>
</tr>
<? form_errors("country","You must place your county in the country field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><b><font size="2">Country:</font></b></td>
<td> <font size="2"><b> 
<input type="text" name="userform[country]" value="<? userform("country");?>">
</b></font></td>
</tr>
<tr> 
<td><font size="2"><b>Referred by:</b></font></td>
<td> <font size="2"> 
<input type=text name=refid value=<? referrer();?>>
<br>
(If no referrer, a GoldMember will be assigned)</font></td>
</tr>
<tr> 
<td colspan=2> 
<hr>
</td>
</tr>
<? form_errors("keyword","You must pick at least 3 interest groups","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td colspan=2 align=center><font size="2"><b> Select categories of interests to 
you:<br>
<br>
</b> </font> 
<table border="0" cellspacing="1" cellpadding="1" width="100%">
<tr valign="top"> 
<td><font size="2"><b> 
<input type=checkbox name=keyword[0] value=Arts <? interestsform("arts","checked");?>>
Arts <br>
<input type=checkbox name=keyword[1] value=Automotive <? interestsform("automotive","checked");?>>
Automotive<br>
<input type=checkbox name=keyword[2] value=Business <? interestsform("business","checked");?>>
Business<br>
<input type=checkbox name=keyword[3] value=Computers <? interestsform("computers","checked");?>>
Computers<br>
<input type=checkbox name=keyword[4] value=Education <? interestsform("education","checked");?>>
Education<br>
<input type=checkbox name=keyword[5] value=Entertainment <? interestsform("entertainment","checked");?>>
Entertainment<br>
<input type=checkbox name=keyword[6] value=Financial <? interestsform("financial","checked");?>>
Financial</b></font></td>
<td><font size="2"><b> 
<input type=checkbox name=keyword[7] value=Games <? interestsform("games","checked");?>>
Games<br>
<input type=checkbox name=keyword[8] value=Health <? interestsform("health","checked");?>>
Health<br>
<input type=checkbox name=keyword[9] value=Home <? interestsform("home","checked");?>>
Home<br>
<input type=checkbox name=keyword[10] value=Internet <? interestsform("internet","checked");?>>
Internet<br>
<input type=checkbox name=keyword[11] value=News <? interestsform("news","checked");?>>
News<br>
<input type=checkbox name=keyword[12] value=Media <? interestsform("media","checked");?>>
Media<br>
<input type=checkbox name=keyword[13] value=Recreation <? interestsform("recreation","checked");?>>
Recreation<br>
</b></font></td>
<td><font size="2"><b> 
<input type=checkbox name=keyword[14] value=Reference <? interestsform("reference","checked");?>>
Reference<br>
<input type=checkbox name=keyword[15] value=Search <? interestsform("search","checked");?>>
Search<br>
<input type=checkbox name=keyword[16] value=Science <? interestsform("science","checked");?>>
Science<br>
<input type=checkbox name=keyword[17] value=Social <? interestsform("social","checked");?>>
Social <br>
<input type=checkbox name=keyword[18] value=Sports <? interestsform("sports","checked");?>>
Sports<br>
<input type=checkbox name=keyword[19] value=Technology <? interestsform("technology","checked");?>>
Technology<br>
<input type=checkbox name=keyword[20] value=Travel <? interestsform("travel","checked");?>>
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
<tr> 
<td colspan=2 align=center> <font size="2"><b> 
<select name=userform[pay_type]>
<option value='' selected>Select a payment method 
<option value='PayPal'>PayPal 
<option value='e-Gold'>e-Gold 
<option value='Check'>Check 
</select>
</b></font></td>
</tr>
<tr> 
<td colspan=2 align=center><b><font size="2">Payment account ID:<br>
<input type=text size=25 value="<? userform("pay_account");?>" name=userform[pay_account]>
</font></b></td>
</tr>
<tr> 
<td colspan=2> 
<hr>
</td>
</tr>
<? form_errors("password","The password you entered did not match what you put in the confirmation field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td><b><font size="2">Password:</font></b></td>
<td> <font size="2"><b> 
<input type=password name=userform[password]>
</b></font></td>
</tr>
<tr> 
<td><b><font size="2">Confirm Password:</font></b></td>
<td> <font size="2"><b> 
<input type=password name=userform[confirm_password]>
</b></font></td>
</tr>
<tr> 
<td colspan=2 align=center> <font size="2"><b> 
<input type="submit" value="Signup">
</b></font></td>
</tr>
</table>
</form>
<b><font face="arial" size="2">&nbsp;<br>
</font></b></td>
</tr>
</table>
<? include("footer.php");?>
