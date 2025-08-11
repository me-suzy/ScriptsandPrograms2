<?php
include 'connect.php';
session_start();

?>
<center>
<?php
include "title.php";
include "admin/var.php";
print "<title>$sitetitle</title>";
?>
</center>
<br><br>
<center>
<link rel="stylesheet" href="style.css" type="text/css">
<?php
$user=$_SESSION['user'];
$getid="SELECT * from b_users where username='$user'";
$getid2=mysql_query($getid) or die("could not get user");
$getid3=mysql_fetch_array($getid2);
print "<table class='maintable'><tr class='headline'><td colspan='4'><center>Delete RPS Challenge Logs</center></td></tr>";
print "<tr class='forumrow'><td>";
if(isset($_POST['submit']))
{
  $deletelogs="Delete from b_rps where challenged='$getid3[userID]' and accept='1'";
  mysql_query($deletelogs) or die("Could not get logs");
  print "Logs deleted. Back to <A href='index.php'>Forums</a>.";
}
print "</table>"; 
?>



</center>   









<br><br>
<center>
