<?php
ob_start();
//include the header
require("top.php");
echo "<br>";
//show the form
echo "<center>";
echo "<form method=post action=?action=check><table><tr><td><font size=2>First name:</td><td><input type=text name=Fname></td></tr><tr><td><font size=2>Last name:</td><td><input type=text name=Lname></td></tr><tr><td><font size=2>User name:</td><td><input type=text name=Uname></td></tr><tr><td><font size=2>Email:</td><td><input type=text name=Email></td></tr><tr><td><font size=2>Password:</td><td><input type=password name=Pword></td></tr><tr><td><font size=2>Confirm password:</td><td><input type=password name=CPword></td></tr><tr><td></td><td><input type=submit value=Submit></td></tr></table></form></font>";
//see if action=check
if($_GET['action'] == 'check')
{
   //check if everything is filled in
   if($_POST['Fname'] == '' || $_POST['Lname'] == '' || $_POST['Uname'] == '' || $_POST['Email'] == '' || $_POST['Pword'] == '')
   {
      //if everything is not filled in than print out "please fill in all the field"
	  echo error("blank");
	  exit;
   }
   else
   {
      //check if the passwords match
	  if($_POST['Pword'] !== $_POST['CPword'])
	     {
		 //if they dont match than print out "passwords dont match"
		 echo error("password");
		 exit;
		 }
      else
	     {
		 //if they match, see if the user exists
         $result = mysql_query("SELECT * FROM loginphp
         WHERE Uname='{$_POST['Uname']}'") or die(mysql_error()); 
         $row = mysql_fetch_array( $result );
		 //if row uname equals blank than create the user
         if($row['Uname'] == '')
		    {
		    //create the user
            //$enc = md5($_POST['Pword']);
            $enc = $_POST['Pword'];
            mysql_query("INSERT INTO loginphp 
            (Fname, Lname, Uname, Email, Pword) VALUES('{$_POST['Fname']}', '{$_POST['Lname']}', '{$_POST['Uname']}', '{$_POST['Email']}', '{$enc}') ") 
            or die(mysql_error());
			//redirect to login.php?action=registered
			header("Location: login.php?action=registered");
		    }
		 else
		    {
			echo error("user");
			}
		 }
	}
}
//function error
function error($error)
{
   //if error is equal to blank than write "fill in all the fields"
   if($error == 'blank')
      {
      echo "<b>Please fill in all the fields</b>";
      }
   //if error is equal to password than write "the passwords dont match
   if($error == 'password')
      {
      echo "<b>The passwords do not match</b>";
      }
   //if error is equal to user than write "the username is already registered"
   if($error == 'user')
      {
      echo "<b>The username is already registered</b>";
      }
}
?>