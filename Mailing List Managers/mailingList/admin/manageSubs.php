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
		  <?php
		  if(empty($set))
		  { 
		  	$set = 0;
		  }
		  echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="mailList">';
		  echo '<tr class="mailListTR"><td>Name:</td><td>Email</td><td>Date Join</td><td>Delete</td></tr>';
		  $q = mysql_query("select * from mailList order by tstamp desc limit ".$set.", 100");
		  $num = mysql_num_rows($q);
		  while($result = mysql_fetch_array($q))
		  {
		  	echo '<tr>
				  	<td>'.ucfirst(strtolower($result['name'])).'</td>
				  	<td>'.$result['emailAddress'].'</td>
					<td>'.$result['subTime'].'</td>
				  	<td><a href="delMember.php?id='.$result['id'].'">DELETE</a></td>
				  </tr>';
		  }
		  echo '</table>';
		  ///paging
		  if($num > 100)
		  {
		  	$totalPages = ceil($num / 100) + 1;
			echo '<table width="100%" cellpadding="0" cellspacing="0">';
			echo '<tr><td>';
			for($i = 1; $i < $totalPages; $i++)
			{
				if($i == 1)
				{
					$val = 0;
				}
				else
				{
					$val = $i * 100;
				}
				echo '<a href="manageSubs.php?set='.$val.'">'.$i.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
			}
			echo '</td></tr></table>';
		  }
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
<?php 
mysql_free_result($q);
mysql_close($conn);
?>
</body>
</html>
