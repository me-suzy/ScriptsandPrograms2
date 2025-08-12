<? include("setup.php");?>
<? login();?>  
<? include("header.php");?>
<? include("account_menu.php");?>
<table width="85%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td> 
<p align="center"><font size="3"><b>If you qualify to redeem your cash or points 
earnings,<br>
You will see redemption options listed below.<br>
</b> </font></p>
<p><font size="2">We offer our members the opportunity to redeem their points 
or cash earned here.<b><br>
</b><br>
When you qualify, you will have the option to order:</font></p>
<table width="302" cellspacing="0" cellpadding="0">
<tr> 
<td> 
<ul>
<li><font size="2">Paid E-Mail Ad sent to the membership</font></li>
<li><font size="2">Banner Advertising</font></li>
<li><font size="2">Paid2Click Banner Advertising</font></li>
</ul>
</td>
</tr>
</table>
<font size="2">The type of advertising that you qualify for will be displayed 
below.<br>
<b><br>
</b>Simply click the redeem button and then come back here to complete the order 
form.<br>
<br>
</font> 
<hr>
<center>
<font size="2"><b>You qualify for the following redemption offers.</b><br>
<br>
</font> 
</center>
<p align="center"><font size="2"> 
<? redeem_list();?>
<br>
</font></p>
<hr>
<br>
<table border=0 width=400 cellpadding="5" cellspacing="5">
<tr> 
<td colspan=2> 
<p align="center"> 
<!-- DO NOT EDIT BELOW THIS POINT UNLESS YOU ABSOLUTELY KNOW WHAT YOU ARE DOING. IF YOU ALTER THE FORM AND MESS IT UP IT MAKE SURE YOU HAVE A BACKUP BECAUSE WE WILL NOT FIX IT FOR FREE -->
</p>
<form method="POST">
<input type=hidden name=user_form value="email">
<input type=hidden name=email_to value="redemptions">
<input type=hidden name=required value="ad_info,email_from,username">
<input type=hidden name=userform[subject] value='Redemption request for advertising'>
<? form_errors("email_from","You must place your email address in the email field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<table width="387" align="center">
<tr> 
<td width="33%"> 
<div align="right"><font size="2"><b>Your Email:</b></font></div>
</td>
<td width="67%"> 
<input type="text" name="userform[email_from]" value=<? userform("email_from");?>>
</td>
</tr>
<? form_errors("username","You must place your user name in the username field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td width="33%"> 
<div align="right"><font size="2"><b>Your Username:</b></font></div>
</td>
<td width="67%"> 
<input type="text" name=userform[username] value=<? userform("username");?>>
</td>
</tr>
<? form_errors("ad_info","You must place you advertisement in the ad info box","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td colspan="2"> 
<p><font size="2"><b><br>
AD Information<br>
</b>Please include any information that you will want<b><br>
</b> displayed with your advertisement.</font></p>
<p> 
<textarea cols="40" name="userform[ad_info]" rows="7" wrap="virtual"><? userform("ad_info");?></textarea>
</p>
</td>
</tr>
<tr align="center"> 
<td colspan="2"> 
<input type="submit" value="Send advertising request" name="submit">
<input type="reset" value="Clear Form" name="reset">
</td>
</tr>
</table>
</form>
<br>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<? include("footer.php");?>
