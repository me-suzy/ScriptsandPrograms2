<?
include "./config.php";
if ($email && $pass1 && $pass2){
	if ($pass1 != $pass2) $status .= "Passwords do not match.<br>";
	$sql = "select email from $table where email='$email'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_fetch_row($result);
	if ($numrows!=0) $status .= "This email address has already been used to create an account. If you would like to create more than one account, you will need to use different email addresses for each one.<br>";

	if (!$status){
		$tablebg = "#FFFFFF";
		$tablebdr = "#000000";
		$tableclr = "#000000";
		$sql = "insert into $table values('', '$email', '$pass1', '', '', '', '', '$tablebg', '$tablebdr', '$tableclr', '$cat', '0', '0', '0', '0', now(), '', '0')";
		$result = mysql_query($sql) or die("Failed: $sql");
		$outmsg = "Below is your membership info for your banner exchange account.<br>Please hold on to this email for later use.<br><br>";
		$outmsg .= "Login: $email<br>Password: $pass1<br><br>";
		$outmsg .= "You can make changes to your account by visiting the URL below. If you find any problems with the website, please reply to this email! We encourage feedback, but please be as specific as possible. :)<br><br>";
		$outmsg .= "Log In: <a href=\"".$bx_url."edit.php?email=$email&password=$pass1\">".$bx_url."edit.php?email=$email&password=$pass1</a><br>";
		$subj = "Your BannerExchange Account";
		$header = "From: ".$adminemail."\n";
		$header .= "MIME-Version: 1.0\n";
		$header .= "Content-Type: text/html\n";
		$header .= "Content-Transfer-Encoding: 8bit\n\n";
		$header .= "$outmsg\n";
		$z = mail($email, $subj, "", $header);
		Header("Location: edit.php?email=$email&password=$pass1");
		exit;
	}
}
$pagetitle = "$bx_title: $create_page_title";
if ($headerfile) include $headerfile;
?> 
<blockquote>
  <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="red"> 
  <? print $status; ?>
  </font></blockquote>
<form name="form1" method="post" action="create.php">
  <blockquote> 
    <p><font size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>" color="<? print $tabletextcolor; ?>"><b>* 
      All fields required</b></font></p>
  </blockquote>
  <table width='<? print $tablewidth; ?>' border='<? print $tableborder; ?>' cellspacing='<? $cellspacing; ?>' cellpadding='<? print $cellpadding; ?>' align='center'>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="51%"><b><font color="<? print $tabletextcolor; ?>" size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>">Account 
        Info</font></b></td>
      <td width="49%" bgcolor="<? print $tablebgcolor; ?>"><font size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>"></font></td>
    </tr>
    <tr> 
      <td width="51%" bgcolor="<? print $tablebgcolor; ?>"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Email 
        Address (used as your login):</font></td>
      <td width="49%" bgcolor="<? print $tablebgcolor; ?>"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="text" name="email" value="<? print $email; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="51%" bgcolor="<? print $tablebgcolor; ?>"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Desired 
        Password:</font></td>
      <td width="49%" bgcolor="<? print $tablebgcolor; ?>"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="password" name="pass1" value="<? print $pass1; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="51%" bgcolor="<? print $tablebgcolor; ?>"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Confirm 
        Password:</font></td>
      <td width="49%" bgcolor="<? print $tablebgcolor; ?>"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="password" name="pass2" value="<? print $pass2; ?>">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="51%"><font color="<? print $tabletextcolor; ?>" size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>">Category:</font></td>
      <td width="49%"> <font color="<? print $tabletextcolor; ?>" size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>"> 
        <select name="cat">
          <? print getcategoriesascombo($cat); ?>
        </select>
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="51%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>">&nbsp;</font></td>
      <td width="49%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>"> 
        <input type="submit" value="<? print $signup_button_text; ?>">
        </font></td>
    </tr>
  </table>
  <blockquote>
    <p><font size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>" color="<? print $tabletextcolor; ?>"><b>Note: 
      Your account will not be active until you've created your advertisement 
      and inserted the HTML code on your site. You can do this after hitting the 
      Create My Account button.</b></font></p>
  </blockquote>
</form>

<?
print "<center>Powered by <a href='http://nukedweb.memebot.com/' target='_nukedweb'>MyBannerExchange</a></center>";
if ($footerfile) include $footerfile;
?>