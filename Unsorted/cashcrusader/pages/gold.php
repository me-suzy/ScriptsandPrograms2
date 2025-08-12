<? include("setup.php");?>
<? include("header.php");?>
<table width="85%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td align="center"> <font size="2"><strong><a href=/pages/enter.php>Earnings Stats</a> 
| <a href=/pages/adstats.php> Advertising Stats</a> | <a href=/pages/reflinks.php>Referral 
Links</a><br>
<a href=/pages/userinfo.php>User Account Info</a> | <a href=/pages/gold.php>Gold 
Membership</a> | <a href=/pages/ptc.php>Paid2Click</a> | <a href=/pages/index.php?username=LOGOUT&password=LOGOUT>Log-Out</a> 
</strong></font><br>
</td>
</tr>
</table>
<table width="85%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td> 
<p align="center"><font size="2"><br>
</font><b><font size="3">Gold Membership Benefits</font></b></p>
<p><font size="2"> <br>
<b>As a Gold Member you will receive the following benefits:</b></font> </p>
<ul>
<li><font size="2"> <b>Free Referrals</b> -Your referral url will be randomly 
inserted for surfers visiting the website without a referral url.<br>
</font> 
<li> <font size="2">?<br>
</font> 
<li><font size="2">?<br>
</font> 
<li><font size="2">?<br>
</font> 
<li><font size="2">Gold membership is $? for a ? Membership.<br>
</font></li>
</ul>
<font size="2">Please pay for your membership first, then return to fill out the 
order form below to complete your order.<br>
<br>
</font> <br>
<br>
<font size="2"><b>Payments may also be sent directly through:<br>
</b>PayPal to advertising@?<br>
eGold to Account # ?<br>
<br>
<b>After </b>you have paid for your ad package, please fill out the &quot;Order 
Form&quot; <br>
below to complete the advertisement ordering process.<br>
<br>
</font> 
<hr>
<font size="2"> <br>
</font> 
<table width=400 cellpadding="5" cellspacing="5" border="0">
<!-- DO NOT EDIT BELOW THIS POINT UNLESS YOU ABSOLUTELY KNOW WHAT YOU ARE DOING. IF YOU ALTER THE FORM AND MESS IT UP IT MAKE SURE YOU HAVE A BACKUP BECAUSE WE WILL NOT FIX IT FOR FREE -->
<form method="POST">
<font size="2"> 
<input type=hidden name=user_form value="email">
<input type=hidden name=email_to value="advertising">
<input type=hidden name='userform[referrer]' value='<? referrer();?>'>
<input type=hidden name=required value="ad_info,pay,agree_type,ad_type,email_from">
<input type=hidden name=userform[subject] value='Purchase request for advertising'>
</font> 
<tr> 
<td width="168"><font size="2"><b> Your Name:</b></font></td>
<td width="304"> <font size="2"> 
<input type="text" name="userform[name]" value="<? userform("name");?>">
</font></td>
</tr>
<font size="2"> 
<? form_errors("email_from","You must place your email address in the email field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
</font> 
<tr> 
<td width="168"><font size="2"><b>Your Email:</b></font></td>
<td width="304"> <font size="2"> 
<input type="text" name="userform[email_from]" value="<? userform("email_from");?>">
</font></td>
</tr>
<tr> 
<td width="168"><font size="2"><b>Your Username:</b></font></td>
<td width="304"> <font size="2"> 
<input type="text" name="userform[username]" value="<? userform("username");?>">
</font></td>
</tr>
<font size="2"> 
<? form_errors("ad_type","You must enter the name of the ad package you wish to run","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
</font> 
<tr> 
<td width="168"><font size="2"><b> Type of advertising:</b></font></td>
<td width="304"> <font size="2"> 
<input type=text name=userform[ad_type] value="<? userform("ad_type");?>">
</font></td>
</tr>
<font size="2"> 
<? form_errors("pay","You must enter the type of payment that you will be using in the Payment Method field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
</font> 
<tr> 
<td width="168"><font size="2"><b> Payment Method:<br>
</b></font></td>
<td width="304"> <font size="2"> 
<input type=text name=userform[pay] value="<? userform("pay");?>">
</font></td>
</tr>
<font size="2"> 
<? form_errors("ad_info","You must place you advertisement in the ad info box","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
</font> 
<tr> 
<td colspan=2><font size="2"><b>AD Information:</b><br>
(Please include any information that you will want to be displayed with your advertisement)<br>
<textarea cols="40" name="userform[ad_info]" rows="7" wrap="virtual"><? userform("ad_info");?></textarea>
<br>
<br>
</font></td>
</tr>
<font size="2"> 
<? form_errors("agree_type","You must check this box","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
</font> 
<tr> 
<td colspan=2><font size="2"> 
<input type="checkbox" name="userform[agree_type]" value="Yes">
Check this box if you have submitted this form truthfully, and you have every 
intention of making the required payment for the advertising.<br>
<br>
<input type="submit" value="Send advertising request" name="submit">
<input type="reset" value="Clear Form" name="reset">
<br>
<br>
</font></td>
</tr>
</form>
</table>
</td>
</tr>
</table>
<font face="arial" size="2"><br>
</font> 
<? include("footer.php");?>

