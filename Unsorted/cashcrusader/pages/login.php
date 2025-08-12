<? include("setup.php");?>
<? include("header.php");?>
<p>&nbsp;</p>
<table width="85%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td align="center"> 
<h2>
Login </h2>
<form method=post>
<center>
<table border=0>
<tr> 
<td><b><font size="2">Username:</font></b></td>
<td> <b><font size="2"> 
<input type=text name=username>
</font></b></td>
</tr>
<tr> 
<td><b><font size="2">Password:</font></b></td>
<td> <b><font size="2"> 
<input type=password name=password>
</font></b></td>
</tr>
<tr align="center"> 
<td colspan=2> <b><font size="2"> 
<input type=submit value="Login" name="submit">
</font></b></td>
</tr>
</table>
<b><font size="1"><a href="/pages/lost_login.php">(Forgot your password/ID?)</a></font></b> 
</center>
</form>
</td>
</tr>
</table>
<? include("footer.php");?>
