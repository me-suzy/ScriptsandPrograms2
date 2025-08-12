<?php include ('contact.conf'); ?>
<form action="includes/contact.php" method="post">
<table border="0" bgcolor="#FFFFFF" cellspacing="5">
<tr><td align="right"><font face="arial"><b>Your Name:</b></font></td>
<td><input type="text" size="<?php echo $name_width ?>" name="name"></td></tr>
<tr><td align="right"><font face="arial"><b>Email address:</b></font></td>
<td><input type="text" size="<?php echo $email_width ?>" name="formemail"></td></tr>
<tr><td valign="top" align="right"><font face="arial"><b>Comments:</b></font><br>
<font face="Arial" size="1">Use this space to ask questions or send us your feedback.</font></td>
<td><textarea name="comments" rows="<?php echo $message_height ?>" cols="<?php echo $message_width ?>"></textarea></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" value="Send">
<font face="arial" size="1">&nbsp;&nbsp;</font><b><font face="arial" size="1">press only once!</font></b></span>
</td></tr>
</table>
</form>