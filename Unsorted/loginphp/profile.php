<?php
ob_start();
//include the header
require("top.php");
//check if the session Uname is in use
if($_SESSION['Uname'] == '' || $_SESSION['lp'] == '')
{
header("Location: login.php");
exit;
}
echo "<br><br>";
//get the users profile
$result = mysql_query("SELECT * FROM loginphp
WHERE Uname='{$_SESSION['Uname']}'") or die(mysql_error());

$row = mysql_fetch_array( $result );
echo "<table><form method=post action=profile.php?action=update>";
echo "<tr><td><font szie=2>First name:</td><td><input type=text name=Fname value=" . $row['Fname'] . "></td></tr>";
echo "<tr><td><font szie=2>Last name:</td><td><input type=text name=Lname value=" . $row['Lname'] . "></td></tr>";
echo "<tr><td><font szie=2>Username:</td><td><b>" . $row['Uname'] . "</b></td></tr>";
echo "<tr><td><font szie=2>Email:</td><td><input type=text name=Email value=" . $row['Email'] . "></td></tr>";
echo "<tr><td><a href=changepass.php>Change password</a></td><td></td></tr>";
echo "<tr><td></td><td><input type=submit value=Update></td></tr>";
echo "</form></table>";

if($_GET['action'] == 'update')
{
if($_POST['Fname'] == '' || $_POST['Lname'] == '' || $_POST['Email'] == '')
   {
      echo error("blank");
	  exit;
   }
   else
   {

	  //update
	  $result = mysql_query("UPDATE loginphp SET Fname='{$_POST['Fname']}' WHERE Uname='{$_SESSION['Uname']}'")
      or die(mysql_error());
	  $result = mysql_query("UPDATE loginphp SET Lname='{$_POST['Lname']}' WHERE Uname='{$_SESSION['Uname']}'")
      or die(mysql_error());
	  $result = mysql_query("UPDATE loginphp SET Email='{$_POST['Email']}' WHERE Uname='{$_SESSION['Uname']}'")
      or die(mysql_error());
	  $result = mysql_query("UPDATE loginphp SET Pword='{$_POST['Pword']}' WHERE Uname='{$_SESSION['Uname']}'")
      or die(mysql_error());
	  echo error("updated");
   }
}
function error($error)
{
if($error == 'blank')
{
echo "<b>Please fill in all the fields</b>";
}
if($error == 'updated')
{
echo "<b>Updated successfully</b>";
}
}
?>
