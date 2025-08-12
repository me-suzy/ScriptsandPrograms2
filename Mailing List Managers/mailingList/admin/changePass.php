<?php
session_start();
if(@$_SESSION['admin'] != 1)
{
	header("location: login.php");
	exit();
}
/*Under the terms and condition of GPL license, you may use this software freely
  as long as you retain our copyright. I would like to thanks you for appriciating
  my time and effort contributed to this project.
  ~David Ausman - Hotwebtools.com 2005*/
?>
<html>
<head>
<title>Admin Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="css/text.css">
</head>

<body>
<table width="800" border="0" cellspacing="0" cellpadding="0" class="admin">
  <tr class="topnav"> 
    <td><a href="viewSubs.php" class="topnav">View Subscribers</a> | <a href="manageSubs.php" class="topnav">Manage 
      Subscribers</a> | <a href="createNewsletter.php" class="topnav">Create Newsletter</a> 
      | <a href="changePass.php" class="topnav">Change Password</a></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="10" class="agreement">
        <tr> 
          <td>
		  <?php
		  echo '<form action="changePassProcess.php" method="post">';
		  echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="mailList">';
		  echo '<tr class="mailListTR"><td>&nbsp;**all fields required<br></td><td></td></tr>';
		  echo '<tr><td>UserName:</td><td><input type="text" name="userName"></td></tr>';
		  echo '<tr><td>PassWord:</td><td><input type="text" name="passWord"></td></tr>';
		  echo '<tr><td>Email:</td><td><input type="text" name="email"></td></tr>';
		  echo '<tr><td>&nbsp;</td><td><input type="submit" value="Change Details"></td></tr>';
		  echo '</table></form>';
		  ?></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="800" cellpadding="0" cellspacing="0">
  <tr>
    <td class="copyright">Powered By <a href="http://www.hotwebtools.com">Hotwebtools.com</a></td>
  </tr>
  </table>
</body>
</html>
