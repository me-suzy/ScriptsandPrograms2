<? include("setup.php");?>
<? include("header.php");?>
<table width="100%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td align="center"> 
<center>
<h3>
Error! 
Invalid username or password </h3>
<form method=post>
<center>
<b><font size="2">Can't remember your username or password?<br>
Enter your email address below and it will be sent to you</font></b>.<br>
<input type=hidden name=user_form value=userinfo>
<input type=text name=userform[send_login_info]>
<input type=submit value="Send login info" name="submit">
</form>
</center>
<form method=post>
<table border=0>
<tr> 
<td><b><font size="2">Username:</font></b></td>
<td> 
<input type=text name=username>
</td>
</tr>
<tr> 
<td><b><font size="2">Password:</font></b></td>
<td> 
<input type=password name=password>
</td>
</tr>
<tr> 
<td colspan=2><b><font size="2"> 
<input type=submit value="Login" name="submit">
</font></b></td>
</tr>
</table>
</form>
</center>
</td>
</tr>
</table>
<br>
<? include("footer.php");?>
