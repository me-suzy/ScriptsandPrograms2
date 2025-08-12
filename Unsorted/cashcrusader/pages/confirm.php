<? include("setup.php");?>
<? include("header.php");?>
<center>
<h2>&nbsp;</h2>
<h2>&nbsp;</h2>
<h2>Sign-up</h2>
<table border=0>
<form method=post>
<? form_errors("email","You must place an email address in the email address field","The email address you selected is already in use. Please do not create another account","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
<tr> 
<td align="center"> <font size="2"><b>Please enter your Email address to sign-up:<br>
</b> <br>
<input type=hidden name=user_form value=signup>
<input type="text" name="userform[email]">
<br>
<br>
<input type="submit" value="Continue" name="submit">
</font> 
</form>
</table>
</center>
<? include("footer.php");?>
