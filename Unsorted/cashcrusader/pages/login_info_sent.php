<? include("setup.php");?>
<? include("header.php");?>
<center>
<font size="4">Your username and new password have been sent
to your email address on file with us. </font> 
<form method=post>
<center>
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
</center>
</form>
<? include("footer.php");?>
