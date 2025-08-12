<?php

// uDi - You Direct It, written by, and copyright Mike Cheesman.

require "config.php";

if (!$PHP_AUTH_USER || !$PHP_AUTH_PW)
{
	header('WWW-Authenticate: Basic realm="uDi Admin"');
	header('HTTP/1.0 401 Unauthorized');
	echo 'Authorization Required.';
	exit;
}
else
{
	$tu = strtolower($PHP_AUTH_USER);
	$pu = strtolower($PHP_AUTH_PW);
	if ($tu == strtolower($adminusername) && $pu == strtolower($adminpass))
	{
$dirname = "$credir";
$dh = opendir($dirname);


print "<html>
<body>
<center>";
// START FUNCTIONS

function list_acct() {
global $dh, $dirname, $file, $website, $PHP_SELF, $count;
print "<table border=\"2\" cellpadding=\"3\" cellspacing=\"0\"><tr bgcolor=\"336699\"><th><font color=\"cccccc\">Account Name</font></th><th><font color=\"cccccc\">View</font></th><th><font color=\"cccccc\">Edit</font></th><th><font color=\"cccccc\">Delete</font></th></tr>";
while (gettype($file = readdir($dh)) != boolean)
	{
	if (is_dir("$dirname/$file") && $file != ".." && $file != ".") {
$count = $count + 1;
		print "<tr><th>$file</th><td><a href=\"$website/$file\" target=\"_blank\">View</a></td><td><a href=\"$PHP_SELF?do=edit&user=$file\">Edit</a></td><td><a href=\"$PHP_SELF?do=delete&user=$file\">Delete</a></td></tr>";
		}
	}
print "<caption align=\"top\"><b>Total users: $count</b>";
print "</table>";
closedir($dh);
}

function delete_acct() {
global $dirname, $user;
unlink("$dirname/$user/index.php");
unlink("$dirname/$user/config.php");
rmdir("$dirname/$user");
print "User account <b>$user</b>, deleted.";
}

function edit_acct() {
global $username, $npassword, $nsite, $naddress, $nemail, $ncloak, $user, $dirname, $fp, $nauthor, $ndescription, $ncopyright, $nkeywords;
include "$dirname/$user/config.php";
$fp = fopen("$dirname/$user/config.php", "w");
fwrite( $fp, "<?php
\$username = \"$user\";
\$password = \"$npassword\";
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
print "<b>$user</b> account information has been changed.<P>";
}

function acct_info() {
global $user, $site, $address, $email, $cloak, $password, $author, $description, $copyright, $keywords, $PHP_SELF;
	print "
<P><b>Change Account Info:</b>
<form name=\"edit\" action=\"$PHP_SELF?do=editc&user=$user\" method=\"post\">
<table>
<tr><td>Username:</td><td>$user</td></tr>
<tr><td>Site Name:</td><td><input type=text name=nsite size=15 value=\"$site\"></td></tr>
<tr><td>Site Address:</td><td><input type=text name=naddress size=15 value=\"$address\"></td></tr>
<tr><td>Your Email:</td><td><input type=text name=nemail size=15 value=\"$email\"></td></tr>
<tr><td>Author:</td><td><input type=text name=nauthor size=15 value=\"$author\" maxlength=\"30\"></td></tr>
<tr><td>Copyright:</td><td><input type=text name=ncopyright size=15 value=\"$copyright\" maxlength=\"50\"></td></tr>
<tr><td>Description:</td><td><input type=text name=ndescription size=15 value=\"$description\" maxlength=\"70\"></td></tr>
<tr><td>Keywords:</td><td><input type=text name=nkeywords rows=6 cols=30 value=\"$keywords\" maxlength=\"200\"></td></tr>
<tr><td>Cloaked URL?:</td><td><input type=checkbox name=ncloak value=\"yes\""; 
if ($cloak == yes) { 
print "checked>"; 
} else { print ">"; 
} 
print "Yes</td></tr>
</table>
<P><b>Change Password</b>:<P>
<table>
<tr><td>Password:</td><td><input type=password name=npassword size=15 maxlength=10 value=\"$password\"></td></tr>
<tr><td colspan=2><input type=submit value=Change></td></tr>
</table>
</form>
";
}

// END FUNCTIONS

if (!$do || $do == "") {
list_acct();
}
if (isset($user) && $do == editc) {
edit_acct();
include "$dirname/$user/config.php";
acct_info();
list_acct();
}

if (isset($user) && $do == edit) {
include "$dirname/$user/config.php";
$username = "$user";
acct_info();
list_acct();
}

if (isset($user) && $do == delete) {
print "Confirm delete user <b>$user</b>?";
print "<form action=\"$PHP_SELF?do=deletec&user=$user\" method=\"post\">";
print "<input type=\"submit\" value=\"Delete User\">";
print "</form>";
list_acct();
}

if (isset($user) && $do == deletec) {
delete_acct();
list_acct();
}
print "</center>
</body>
</html>";
}
}
?>