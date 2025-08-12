<?php

// Your Site
$website = "http://www.themudd.org/udi"; // Website address (also it's $website/USER
$sitename = "Yoursite Redirection"; // website name, NAME Redirection suggested...
$adminmail = "admin@yoursite.net"; // Admin/Contact email
$adminusername = "username"; // Control panel username
$adminpass = "password"; // Control Panel password

$header = "header.txt"; // Header file.
$footer = "footer.txt"; // Footer file.

// info
$credir = "/home/themudd/public_html/udi"; // Absolute path to $website

function signup() {
global $PHP_SELF, $website, $password1, $password2, $email, $address, $site, $username, $keywords, $author, $copyright, $description;
print "<form name=\"signup\" action=\"$PHP_SELF?do=signup\" method=\"post\"><table><tr><td>Username:</td><td><input type=text name=username size=15 maxlength=20 value=\"$username\"><br><small><i>$website/Username</i></small></td></tr><tr><td>Password:</td><td><input type=password name=password1 size=15 maxlength=10 value=\"$password1\"><br><small><i>must be 6 - 10 characters</i></small></td></tr><tr><td>Confirm Password:</td><td><input type=password name=password2 size=15 maxlength=10 value=\"$password2\"></td></tr><tr><td>Site Title:</td><td><input type=text name=site size=15 value=\"$site\"></td></tr><tr><td>Site URL:</td><td><input type=text name=address size=15 value=\""; 
if(empty($address)){ print "http://"; }else{ print "$address";}
print "\"></td></tr>
<tr><td>Contact Email:</td><td><input type=text name=email size=15 value=\"$email\"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Author:</td><td><input type=text name=author size=15 value=\"$author\" maxlenght=\"30\"></td></tr>
<tr><td>Copyright:</td><td><input type=text name=copyright size=15 value=\"$copyright\" maxlenght=\"50\"></td></tr>
<tr><td>Description:</td><td><input type=text name=description size=15 value=\"$description\" maxlenght=\"70\"></td></tr>
<tr><td>Keywords:</td><td><input type=text name=keywords rows=6 cols=30 value=\"$keywords\" maxlenght=\"200\"></td></tr>
<tr><td>Cloaked URL?:</td><td><input type=checkbox name=ncloak value=\"yes\" checked disabled>Yes <input type=\"hidden\" name=\"cloak\" value=\"yes\"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan=2><input type=submit value=Create></td></tr>
</table>
</form>
";
}
function creaccnt() {
global $site, $email, $address, $username, $password1, $password2, $credir, $fp, $website, $sitename, $adminmail, $cloak, $keywords, $author, $copyright, $description;
if ( isset($username)) {
if ( is_dir("$username")) {
print "<i>$username</i> is already in use. please try another username<P>";
signup();
} else if ($password1 != $password2) {
print "<font color=\"Red\">Passwords do not match, please try again.</font><br>";
signup();
} else if (strlen($password1) < 6) {
print "<font color=\"Red\">Password must be 6 to 10 characters</font><br>";
signup();
} else if (strlen($username) > 20) {
print "<font color=\"Red\">Username cannot be more than 20 characters</font><br>";
signup();
} else {
mkdir("$credir/$username", 0777);
copy ("$credir/ind.php", "$credir/$username/index.php");
copy ("$credir/cfg.php", "$credir/$username/config.php");
opendir("$credir/$username");
chmod("$credir/$username", 0777);
chmod("$credir/$username/index.php", 0777);
chmod("$credir/$username/config.php", 0777);
$fp = fopen("$credir/$username/index.php", 'w');
fwrite( $fp, "<?php require \"config.php\"; ?>
<?php if (\$cloak == yes) {
print \"<html>
<META name=\\\"Author\\\" content=\\\"$author\\\">
<META name=\\\"Copyright\\\" content=\\\"$copyright\\\">
<META name=\\\"Description\\\" content=\\\"$description\\\">
<META name=\\\"Keywords\\\" content=\\\"$keywords\\\">
<title>\$site</title>
<frameset rows=\\\"100%,*\\\" framespacing=\\\"0\\\" frameborder=\\\"0\\\" border=\\\"0\\\">
<frame src=\\\"\$address\\\" name=\\\"main\\\">
</frameset>
<noframes>
<meta http-equiv=\\\"refresh\\\" content=\\\"0; url=\$address\\\">
<body>
<a href=\\\"\$address\\\">\$address</a>
</body>
</noframes>
</html>\"; 
} else {
header (\"Location: $address\");
}
?>
");
fclose( $fp );
$fp = fopen("$credir/$username/config.php", 'w');
fwrite( $fp, "
<?php
\$username = \"$username\";
\$password = \"$password1\";
\$site = \"$site\";
\$address = \"$address\";
\$email = \"$email\";
\$cloak = \"$cloak\";
\$author = \"$author\";
\$copyright = \"$copyright\";
\$description = \"$description\";
\$keywords = \"$keywords\";
?>
" );
fclose( $fp );
include "signed.php";
}
}
}
?>