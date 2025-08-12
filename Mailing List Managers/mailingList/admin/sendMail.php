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
include '../inc/config.php';
include '../inc/conn.php';
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

		  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="mailList">
<tr> 
                  <td>
				  <?php
				  ///send newsletter
				  $q = mysql_query("select * from admin");
				  while($result = mysql_fetch_array($q))
				  {
				  	$adminEmail = $result['adminEmail'];
				  }
				  
				  $q = mysql_query("select * from mailList");
				  $num = mysql_num_rows($q);
				  while($result = mysql_fetch_array($q))
				  {
					$to = $result['emailAddress'];
					$hello = "Hello ".ucfirst(strtolower($result['name']))."\n\n";
					$unsubscribe = "\n\n\n-----------\n **Click here if you wish to unsubscribe this newsletter. \n".$installationUrl."/unsubscribe.php?add=".$to;
					$body = $hello.$content.$unsubscribe;
					$headers  = 'FROM: '.$adminEmail.' ' . "\r\n";
					$headers  .= 'Reply-To: '.$adminEmail.' ' . "\r\n";
					$headers .= 'X-MAILER: PHP'.phpversion();
					mail($to, $_POST['subject'], $body, $headers);
				  }
				  ///display message
				  echo '<b>Your mail had been send to '.$num.' recipients</b>';
				  ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="800" cellpadding="0" cellspacing="0">
  <tr>
    <td class="copyright">Powered By <a href="http://www.hotwebtools.com">Hotwebtools.com</a></td>
  </tr>
  </table>
<?php
mysql_free_result($q);
mysql_close($conn);
?>
</body>
</html>
