<?php
echo "<p>Please <b>log in</b> below or <a href=\"register.php\">register</a> to post on the forum:</b></p>

<form action=\"login.php\" method=\"post\">
	<p><label for=\"username\">Username</label><br /><input id=\"username\" size=\"16\" type=\"text\" name=\"username\" onfocus=\"if(this.value=='Enter a username') { this.value=''; } \" onblur=\"if(this.value=='') { this.value='Enter a username'; } \" "; if (strlen($username) != 0) { echo "value=\"$username\" class=\"input_normal\" "; } else { echo "value=\"Enter a username\" "; } echo "/></p>
	<p><label for=\"password\">Password</label><br /><input name=\"dummypassword\" id=\"dummypassword\" style=\"width: 124px;\" value=\"Enter a password\" onfocus=\"this.style.display='none'; document.getElementById('password').style.display='inline'; document.getElementById('password').focus();\" type=\"text\" /><input name=\"password\" id=\"password\" style=\"width: 124px; display: none;\" onblur=\"if(this.value==''){ this.style.display='none'; document.getElementById('dummypassword').style.display='inline'; }\" type=\"password\" value=\"\" class=\"input_normal\" /></p>
	<p>For security purposes, you will automatically be logged out when you leave the website.</p>
	<p><input type=\"image\" src=\"_images/form_submit.gif\" class=\"clear\" /></p>
	<p><a href=\"forgot.php\">forgotten password</a></p>
</form>\n";
?>