<? include("setup.php");?>
<? include("header.php");?>
<table width="85%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td> 
<p align="center"><font size="2"><br>
</font><b><font size="3">Paid Advertising</font></b><font size="2"><br>
<br>
</font></p>
<p><font size="2"><b>Are you interested in advertising with our company?</b></font></p>
<div align="left"><font size="2">Below are the different types of advertising 
services we provide.<br>
We offer many options and are confident, you will find one to fit your needs.</font> 
</div>
<font size="2"><br>
<br>
</font> 
<hr align="left">
<font size="2"><br>
</font> 
<div align="left"><font size="2"><b>How it works:</b><br>
</font> 
<ul>
<li><font size="2">Select one of our advertising packages from below.</font></li>
<li><font size="2">Pay for your advertising campaign first.<br>
</font></li>
<li><font size="2">After you have paid for your ad package, please fill out the 
&quot;Order Form&quot;<br>
below to complete the advertisement ordering process.<br>
</font></li>
<li><font size="2"> Complete all the information requested on the form then click 
submit.</font></li>
<li><font size="2">Once your payment has been confirmed, we will process your 
order immediately.</font></li>
</ul>
</div>
<hr align="center">
<font size="2"><br>
</font> 
<div align="left"> <font size="2"><b>Advertising Packages</b><br>
<br>
<b>A Paid-eMail to Members of our Program.</b><br>
All of the people who are receiving the e-mail have all requested it, thus giving 
you, the advertiser, a great selection of interested viewers.<br>
</font></div>
<ul>
<li> 
<div align="left"><font size="2">?</font></div>
</li>
<li><font size="2">?</font></li>
<li><font size="2">?<br>
<br>
</font></li>
</ul>
<font size="2"><b>Banner Impressions Advertising</b><br>
Your banner inserted into our banner rotation on the top of each page of the website.<br>
This is a great way to catch peoples interest, just as much publicity, if not 
more than, advertising in the member e-Mails. The banners will be displayed on 
every page of the site, randomly mixed in with other banners.<br>
</font> 
<ul>
<li><font size="2">?</font></li>
<li><font size="2">?</font></li>
<li><font size="2">?<br>
<br>
</font></li>
</ul>
<p><font size="2"><b>Paid to Click:</b> <br>
Banner 468 x 60 Exposures have a unique price of X cents per click and will last 
until the purchased number of clicks has been reached. Our members earn X cents 
per click, these are UNIQUE VISITORS as each user may click only once per ad. 
These ads are visible in the members paid2click area.<br>
</font></p>
<ul>
<li><font size="2">?</font></li>
<li><font size="2">?</font></li>
<li><font size="2">?</font></li>
<font size="2"><br>
<br>
</font> 
</ul>
<font size="2"><b>Payments may be sent directly through ? to advertising@??<br>
</b><br>
<b>After </b>you have paid for your ad package, please fill out the &quot;Order 
Form&quot; <br>
below to complete the advertisement ordering process. <br>
<br>
<br>
</font> 
<hr>
<table width=400 cellpadding="5" cellspacing="5">
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
<td colspan=2><font size="2"><b>AD Information</b><br>
Please include any information that you will want,<strong><br>
</strong> displayed with your advertisement<br>
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
<p>&nbsp;</p>
</td>
</tr>
</table>
<? include("footer.php");?>

