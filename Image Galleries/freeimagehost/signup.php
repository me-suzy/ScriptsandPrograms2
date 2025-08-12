<?
include "./config.php";

if ($go=="1"){
	$pass1 = strtolower($pass1);
	$pass2 = strtolower($pass2);
	$email = strtolower($email);
	if (!$email) $status .= "An email address is required.<br>";
	if (!$pass2) $status .= "Please confirm your password.<br>";
	if (!$pass1) $status .= "A password for your account is required.<br>";
	if ((!$status) && ($pass1!=$pass2)) $status .= "Passwords do not match.<br>";
	$sql = "select email from $tableusers where email='$email'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows!=0) $status .= "The Email Address you've entered (".$email.") has been registered here.<br>";

	if (!$status){
		$sql = "insert into $tableusers values('', '$email', '$pass1', '0', '', '0', '0', '0', now())";
		$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
		Header("Location: members.php?email=$email&password=$pass1");
		exit;
	}

}
$pagetitle = $ihtitle.": Create Account";
if ($headerfile) include $headerfile;
?>
<b><font size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>" color="#FF0000"><? if ($status) print "The following error(s) have occured:<br>".$status; ?></font></b><br>
<form name="form1" method="post" action="signup.php">
  <table width="<? print $tablewidth; ?>" border="<? print $bordersize; ?>" cellspacing="<? print $cellspacing; ?>" cellpadding="<? print $cellpadding; ?>" align="center">
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="30%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Email:</font></td>
      <td width="70%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="text" name="email" value="<? print $email; ?>">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="30%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Password:</font></td>
      <td width="70%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="password" name="pass1" value="<? print $pass1; ?>">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="30%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Password 
        Again:</font></td>
      <td width="70%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="password" name="pass2" maxlength="255" value="<? print $pass2; ?>">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="30%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">&nbsp;</font></td>
      <td width="70%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="hidden" name="go" value="1">
        <input type="submit" value="Create Account">
        </font></td>
    </tr>
  </table>

</form>

<? 
print "<center>Powered by <a href='http://nukedweb.memebot.com/' target='_nukedweb'>FreeImageHost</a></center>";
if ($footerfile) include $footerfile; ?>