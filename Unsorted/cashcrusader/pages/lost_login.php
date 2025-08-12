<? include("setup.php");?>
<? include("header.php");?>
<table width="85%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td align="center"><b><font size="3"><br>
</font> </b> 
<center>
<b><font size="3"><br>
</font> </b> 
</center>
<center>
<b><font size="3"> Can't remember your username or password?<br>
Enter your email address below and it will be sent to you.<br>
</font> </b> 
<form method=post>
<b><font size="3"> 
<input type=hidden name=user_form value=userinfo>
<input type=text name=userform[send_login_info]>
<input type=submit value="Send login info" name="submit">
</font> </b> 
</form>
<b><font size="3"><br>
<br>
<br>
</font> </b> 
</center>
</td>
</tr>
</table>
<br>
<? include("footer.php");?>
