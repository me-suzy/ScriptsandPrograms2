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
	$sql = "select email from $table where email='$email'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows!=0) $status .= "The Email Address you've entered (".$email.") has been registered here.<br>";

	if (!$status){
		$sql = "insert into $table values('', '$email', '$pass1', 'My Forum', '', '<html>\n<head>\n<title>[pagetitle]</title>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n</head>\n<body bgcolor=\"#FFFFFF\" text=\"#000000\">\n\n', '</body>\n</html>\n', '', '', '#000000', '1', '3', '6', '', '', '1', '1', '0', '0000-00-00 00:00:00', now())";
		$result = mysql_query($sql) or die("Failed: $sql");
		setcookie ("ckForumAdminEmail", "$email", "315360000");
		setcookie ("ckForumAdminPassword", "$pass1", "315360000");
		Header("Location: members.php?email=$email&password=$pass1");
		exit;
	}

}

$pagetitle = $fhtitle.": Create Account";
if ($headerfile) include $headerfile;
print $bannerhtmlcode;
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
<center><font face='Verdana, Arial, Helvetica, sans-serif' size='-1'><b>Powered 
          By <a href='http://nukedweb.memebot.com/' target='_other'>ForumHost</a></b></font></center>
<? if ($footerfile) include $footerfile; ?>