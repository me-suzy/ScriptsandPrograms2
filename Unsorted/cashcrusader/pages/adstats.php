<? include("setup.php");?>
<? login();?>  
<? include("header.php");?>
<? include("account_menu.php");?>
<br>
<br>
<table border=0 align="center">
<tr> 
<td valign=top> 
<table width=100% border=1 bordercolor="#666666" cellpadding="5" cellspacing="0">
<tr> 
<th align=center colspan=4><font color="#000000" face="Arial" size="3">Email Ads</font></th>
</tr>
<tr> 
<th align=center> <font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad 
Name</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Clicks</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Expires</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
viewed</font></th>
</tr>
<? email_ad_stats("<tr><td align=right><font face=arial color=000000>","</td><td align=right><font face=arial color=000000>","</td></tr>","show");?>
</table>
<br>
<table width=100% border=1 bordercolor="#666666" cellpadding="5" cellspacing="0">
<tr> 
<th align=center colspan=4><font color="ffffff" face="Arial" size="3"><font color="#000000">HTML 
Ads</font></font></th>
</tr>
<tr> 
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad 
Name</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Views</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Expires</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
viewed</font></th>
</tr>
<? html_ad_stats("<tr><td align=right><font face=arial color=000000>","</td><td align=right><font face=arial color=000000>","</td></tr>","show");?>
</table>
<br>
<table width=100% border=1 bordercolor="#666666" cellpadding="5" cellspacing="0">
<tr> 
<th align=center colspan=6><font color="ffffff" face="Arial" size="3"><font color="#000000">Banner 
Ads</font></font></th>
</tr>
<tr> 
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad 
Name</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Views</font></th>
<th><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Clicks</font></th>
<th><font size="2" face="Verdana, Arial, Helvetica, sans-serif">CTR</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Expires</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
viewed</font></th>
</tr>
<? banner_ad_stats("<tr><td align=right><font face=arial color=000000>","</td><td align=right><font face=arial color=000000>","</td></tr>","show");?>
</table>
<br>
<table width=100% border=1 cellpadding="5" cellspacing="0" bordercolor="#666666">
<tr> 
<th align=center colspan=4><font color="ffffff" face="Arial" size="3"><font color="#000000">Pop-Under 
Ads</font></font></th>
</tr>
<tr> 
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad 
Name</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Views</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Expires</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
Last viewed</font></th>
</tr>
<? popup_ad_stats("<tr><td align=right><font face=arial color=000000>","</td><td align=right><font face=arial color=000000>","</td></tr>","popunder");?>
</table>
<br>
<table width=100% border=1 bordercolor="#666666" cellpadding="5" cellspacing="0">
<tr> 
<th align=center colspan=4> <font color="ffffff" face="Arial" size="3"><font color="#000000">Pop-Up 
Ads</font></font></th>
</tr>
<tr> 
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad 
Name</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Views</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Expires</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
Last viewed</font></th>
</tr>
<? popup_ad_stats("<tr><td align=right><font face=arial color=000000>","</td><td align=right><font face=arial color=000000>","</td></tr>","popup");?>
</table>
<br>
<table width=100% border=1 bordercolor="#666666" cellpadding="5" cellspacing="0">
<tr> 
<th align=center colspan=6><font color="ffffff" face="Arial" size="3"><font color="#000000">Paid 
To Click Ads</font></font></th>
</tr>
<tr> 
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad 
Name</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Views</font></th>
<th><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Clicks</font></th>
<th><font size="2" face="Verdana, Arial, Helvetica, sans-serif">CTR</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Expires</font></th>
<th align=center><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
viewed</font></th>
</tr>
<? ptc_ad_stats("<tr><td align=right><font face=arial color=000000>","</td><td align=right><font face=arial color=000000>","</td></tr>","show");?>
</table>
</td>
</tr>
</table>
<br>
<? include("footer.php");?>

