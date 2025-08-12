
Edit the conf file and change the entries to reflect your website.
If you are using a php page for your contact page you can just add this line to the page.

<?php include ('includes/mail_form.php'); ?>

If you are using HTML page for your contact page then you must just copy and paste the following.

<form action="includes/contact.php" method="post">
<table border="0" bgcolor="#FFFFFF" cellspacing="5">
<tr><td align="right"><font face="arial"><b>Your Name:</b></font></td>
<td><input type="text" size="20" name="name"></td></tr>
<tr><td align="right"><font face="arial"><b>Email address:</b></font></td>
<td><input type="text" size="20" name="formemail"></td></tr>
<tr><td valign="top" align="right"><font face="arial"><b>Comments:</b></font><br>
<font face="Arial" size="1">Use this space to ask questions or send us your feedback.</font></td>
<td><textarea name="comments" rows="5" cols="20"></textarea></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" value="Send">
<font face="arial" size="1">&nbsp;&nbsp;</font><b><font face="arial" size="1">press only once!</font></b></span>
</td></tr>
</table>
</form>

This will work just as well but you will need to manually adjust the size of items in the form.
The items in the config file for size will not work unless the page is PHP or your server can execute php in an HTML page.

Copy all files to your include directory.
If you do not have an includes directory you must make one.

You return page can be anywhere but you must set the $return_page= location of page in the config file "contact.conf"
After this is done test the contact page to be sure it works properly.

If you wish to have an email sent you when an abuse takes place set $notify_abuse=true;

The script is free to use but if you need support then a charge will be required of $10.00.

Version 1.01
------------
  Corrections and bug fixes:
  Fixed abuse mail warning message to mail on minor attempts.
  Changed the message to sent.

CW3 Web Hosting
info@cw3host.com

Vincent G.