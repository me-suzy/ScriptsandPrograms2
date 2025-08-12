<?php 

// uDi - You Direct It, written by, and copyright Mike Cheesman.

require "config.php";
include "$header";
// form func
function admin_form() {
global $username, $site, $address, $password, $username, $email, $cloak, $PHP_SELF, $keywords, $author, $copyright, $description;
	print "<P>
<b>Account info</b>
<form name=\"edit\" action=\"$PHP_SELF?do=change\" method=\"post\">
<table>
<tr><td>Username:</td><td>$username</td></tr>
<tr><td>Site Name:</td><td><input type=text name=nsite size=15 value=\"$site\"></td></tr>
<tr><td>Site Address:</td><td><input type=text name=naddress size=15 value=\"$address\"></td></tr>
<tr><td>Email:</td><td><input type=text name=nemail size=15 value=\"$email\"></td></tr>
<tr><td>Author:</td><td><input type=text name=nauthor size=15 value=\"$author\" maxlenght=\"30\"></td></tr>
<tr><td>Copyright:</td><td><input type=text name=ncopyright size=15 value=\"$copyright\" maxlenght=\"50\"></td></tr>
<tr><td>Description:</td><td><input type=text name=ndescription size=15 value=\"$description\" maxlenght=\"70\"></td></tr>
<tr><td>Keywords:</td><td><input type=text name=nkeywords rows=6 cols=30 value=\"$keywords\" maxlenght=\"200\"></td></tr>
<tr><td>Cloaked URL?:</td><td><input type=checkbox name=ncloak value=\"yes\""; 
if ($cloak == yes) { 
print "checked>"; 
} else { print ">";
} 
print "Yes</td></tr>
<tr><td colspan=2><input type=submit value=Change></td></tr>
</table>
<input type=\"hidden\" name=\"pword\" value=\"$password\">
<input type=\"hidden\" name=\"uname\" value=\"$username\">
</form>
<br><br>
<P><b>Change Password</b>:<P>
<form name=\"pass\" action=\"$PHP_SELF?do=newpass\" method=\"post\">
<table>
<tr><td>New Password:</td><td><input type=password name=npassword1 size=15 maxlength=10></td></tr>
<tr><td>Confirm New Password:</td><td><input type=password name=npassword2 size=15 maxlength=10></td></tr>
<tr><td colspan=2><input type=submit value=Change></td></tr>
</table>
<input type=\"hidden\" name=\"pword\" value=\"$password\">
<input type=\"hidden\" name=\"uname\" value=\"$username\">
</form>
<br><br>
<table>
<form action=\"$PHP_SELF?do=delacct\" method=\"post\" name=\"del\">
<tr><td align=\"center\"><b>Delete account?</b><br><input type=\"checkbox\" name=\"deleteacct\" value=\"yes\"> Yes<br><input type=\"submit\" value=\"Delete Account\"></td></tr>
<input type=\"hidden\" name=\"pword\" value=\"$password\">
<input type=\"hidden\" name=\"uname\" value=\"$username\">
</form>
</table>
<br><br>";
}

function login_form() {
print "<form action=\"$PHP_SELF?do=login\" method=\"post\">
<table>
<tr>
<td>Username:</td><td>Password:</td></tr>
<tr><td><input type=\"text\" name=\"uname\"></td><td>
<input type=\"password\" name=\"pword\"></td></tr>
<tr><td colspan=\"2\" align=\"right\"><input type=\"submit\" value=\"Login\"></td></tr>
</table>
</form>";
}
// end form func

if (isset($uname) && isset($pword) && is_dir("$credir/$uname") && $do == login) {
require "$credir/$uname/config.php";
	if (($uname == $username) && ($pword == $password) && $do == login) {
	print "User Cpanel for <a href=\"$website/$username\" target=\"_blank\">$username</a>";
admin_form();
} else if (!is_dir("$credir/$uname") || isset($pword) && !isset($uname)) {	print "<font color=\"Red\">Wrong user/pass combo.  Please try again.</font>";
login_form(); } else { 
	print "<font color=\"Red\">Wrong user/pass combo.  Please try again.</font>";
login_form();
	}
} else if (!is_dir("$credir/$uname") || !$uname || !$pword && $do = login) {
print "Please login, by entering your username and password:";
login_form();
} else if (isset($pword) && !$uname && $do = login) {
	print "<font color=\"Red\">Wrong user/pass combo.  Please try again.</font>";
login_form();
} else {
print "These changes have been made to your account:";
}
if ($deleteacct == yes && $do == delacct) {
include "$credir/$uname/config.php";
unlink("$credir/$uname/index.php");
unlink("$credir/$uname/config.php");
rmdir("$credir/$uname");
print "<br>User account <b>$uname</b>, deleted.  <a href=\"signup.php\">Continue</a>";
}
if (isset($nsite) || isset($nemail) || isset($naddress) && $do = change) {
include "$credir/$uname/config.php";
$fp = fopen("$credir/$username/config.php", "w");
fwrite( $fp, "<?php
\$username = \"$username\";
\$password = \"$password\";
\$site = \"$nsite\";
\$address = \"$naddress\";
\$email = \"$nemail\";
\$cloak = \"$ncloak\";
\$author = \"$nauthor\";
\$copyright = \"$ncopyright\";
\$description = \"$ndescription\";
\$keywords = \"$nkeywords\";
?>");
fclose( $fp );
include "$credir/$uname/config.php";
	print "<br>Account Info Changed.";
admin_form();
}
if (isset($npassword1) || isset($npassword2) && $do = newpass) {
	if (strlen($npassword1) < 6) {
require "$credir/$uname/config.php";
	print "<br>None, your new password was too short";
admin_form();
} else if (isset($npassword1) && ($npassword1 == $npassword2) && $do = newpass) {
include "$credir/$uname/config.php";
$fp = fopen("$credir/$username/config.php", "w");
fwrite( $fp, "<?php
\$username = \"$username\";
\$password = \"$npassword1\";
\$site = \"$site\";
\$address = \"$address\";
\$email = \"$email\";
\$cloak = \"$cloak\";
\$author = \"$author\";
\$copyright = \"$copyright\";
\$description = \"$description\";
\$keywords = \"$keywords\";
?>");
fclose( $fp );
include "$credir/$uname/config.php";
	print "<br>Password Changed.";
admin_form();
} else {
include "$credir/$uname/config.php";
	print "<br>Passwords do not match.  Please try again.";
admin_form();
}
}
include "$footer"; ?>