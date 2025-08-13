<?php
ob_start();
//include the header.php file
require("top.php");
echo "<br><br><br><br><center>";
//Write the login form out
echo "<form method=post action=login.php?action=check><table><tr><td><font size=2>Username:</font></td><td><input type=text name=Uname></td></tr><tr><td><font size=2>Password:</font></td><td><input type=password name=Pword></td></tr><tr><td></td><td><input type=submit value=Login></td></tr><tr><td><a href=forgotpassword.php><font size=1>Forgot password</font></a></td><td><a href=register.php><font size=1>Register</font></td></tr></table></form>";
//check the input
if($_GET['action'] == 'check')
{
//find the user
$result = mysql_query("SELECT * FROM loginphp WHERE Uname='{$_POST['Uname']}'") or die(mysql_error()); 
$row = mysql_fetch_array( $result ); //set $row to result
   if($row['Uname'] == "")
   {
      echo error();
   }
   else
   {
      //$enc = md5($_POST['Pword']);
      $enc = $_POST['Pword'];
      if($row['Pword'] == $enc)
	  {
          $_SESSION['Uname'] = $_POST['Uname'];
          $_SESSION['lp'] = 'pl'; 
	  header("Location: main.php");
          exit;
	  }
	  else
	  {
	     echo error();
	  }
   }
}
if($_GET['action'] == 'registered')
{
echo "<b>Registered successfully</b>";
}
function error()
{
echo "<b>The username/password is incorrect</b>";
}
?>