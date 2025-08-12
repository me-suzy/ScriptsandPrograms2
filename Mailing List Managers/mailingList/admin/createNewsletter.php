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
		  <form action="sendMail.php" method="post">
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="mailList">
		  <tr><td><input name="subject" type="text" id="subject" style="font-weight:bold" onFocus="this.value='';" value="enter Subject"></td></tr>
<tr> 
                <td><textarea name="content" cols="90" rows="30" id="content">Type you message here, do not use HTML code</textarea></td>
              </tr>
              <tr> 
                <td><input type="submit" name="Submit" value="Send Mail"></td>
              </tr>
            </table></form></td>
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
