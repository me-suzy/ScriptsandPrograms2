<? include("setup.php");?>
<? include("header.php");?>
<table width="85%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td> 
<center>
<font size="2"> <br>
Feel free to contact us after you have completely read the <a href="/pages/help.php">FAQ</a> 
section of the site.<b><br>
YOU WILL NOT RECEIVE A REPLY IF YOUR QUESTION <br>
HAS ALREADY BEEN ANSWERED IN THE HELP SECTION OF THE SITE!</b></font> 
</center>
<font size="2"><br>
</font> 
<hr align="center">
<font size="2"><br>
<br>
</font> 
<table width=400 cellpadding="5" cellspacing="5" border="0">
<!-- DO NOT EDIT BELOW THIS POINT UNLESS YOU ABSOLUTELY KNOW WHAT YOU ARE DOING. IF YOU ALTER THE FORM AND MESS IT UP IT MAKE SURE YOU HAVE A BACKUP BECAUSE WE WILL NOT FIX IT FOR FREE -->
<form method="POST">
<font size="2"><b> 
<input type=hidden name=user_form value="email">
<input type=hidden name=email_to value="support">
<input type=hidden name=required value="message_type,bquestion_type,subject_type,email_from">
</b> </font> 
<tr> 
<td><font size="2"><b>Your Name:</b></font></td>
<td><font size="2"><b> 
<input type="text" name="userform[name]" value=<? userform("name");?>>
</b></font></td>
</tr>
<font size="2"><b> 
<? form_errors("email_from","You must place your email address in the email field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
</b> </font> 
<tr> 
<td><font size="2"><b>Your Email:</b></font></td>
<td><font size="2"><b> 
<input type="text" name="userform[email_from]" value=<? userform("email_from");?>>
</b></font></td>
</tr>
<tr> 
<td><font size="2"><b>Your Username:</b></font></td>
<td><font size="2"><b> 
<input type="text" name="userform[username]" value=<? userform("username");?>>
</b></font></td>
</tr>
<font size="2"><b> 
<? form_errors("subject_type","You must select a subject","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
</b> </font> 
<tr> 
<td><font size="2"><b>Question Type:</b></font></td>
<td> <font size="2"><b> 
<select name="userform[subject_type]">
<option value="" selected>Choose Your Subject Type:<font face="arial"></font></option>
<option value="Paid E-Mail Question">Paid E-Mail Question<font face="arial"></font></option>
<option value="Signup/Confirmation Question">Signup/Confirmation Question<font face="arial"></font></option>
<option value="Earnings/Points Question">Earnings/Points Question<font face="arial"></font></option>
<option value="Personal Information Question">Personal Information Question<font face="arial"></font></option>
<option value="Advertising Question">Advertising Question<font face="arial"></font></option>
<option value="Other">Other<font face="arial"></font></option>
</select>
</b></font></td>
</tr>
<font size="2"><b> 
<? form_errors("bquestion_type","You must enter a brief description of your question","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
</b> </font> 
<tr> 
<td><font size="2"><b>Brief Description of Your Question:</b></font></td>
<td><font size="2"><b> 
<input type="text" name="userform[bquestion_type]" value="<? userform("bquestion_type");?>">
</b></font></td>
</tr>
<font size="2"><b> 
<? form_errors("message_type","You must place message in the message box","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");?>
</b> </font> 
<tr> 
<td colspan=2><font size="2"><b>Describe Your Question in Full:<br>
<textarea cols="40" name="userform[message_type]" rows="7" wrap="virtual"><? userform("message_type");?></textarea>
<br>
<input type="submit" value="Contact Us" name="submit">
<input type="reset" value="Clear Form" name="reset">
</b></font></td>
</tr>
</form>
</table>
<font size="2"><br>
<br>
</font> </td>
</tr>
</table>
<? include("footer.php");?>
