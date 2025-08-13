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
echo "<form method=post action=?action=change>";
echo "<br><table>";
echo "<tr><td>Old password:</td><td><input type=password name=Opass></td></tr>";
echo "<tr><td>New password:</td><td><input type=password name=Npass></td></tr>";
echo "<tr><td>New passsword again:</td><td><input type=password name=Npassa></td></tr>";
echo "<tr><td></td><td><input type=submit value=Change></td></tr>";
echo "</table><br>";
echo "</form>";
if($_GET['action'] == 'change')
{
if($_POST['Opass'] == $row['Pword'])
{
   if($_POST['Npass'] == $_POST['Npassa'])
   {
   $result = mysql_query("UPDATE loginphp SET Pword='{$_POST['Npass']}' WHERE Uname='{$_SESSION['Uname']}'")
   or die(mysql_error());
   echo "<br><font color=red><b>Password has been changed successfully</b></font><br>";
   }
   else
   {
   echo "<br><font color=red><b>Passwords dont match</b></font><br>";
   }
}
else
{
echo "<br><font color=red><b>Old password is incorrect</b></font><br>";
}
}

?>

