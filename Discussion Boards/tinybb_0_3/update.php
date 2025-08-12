<?php
session_start();
require_once("headers.php");

if (isset($_POST[submit])) {
  if (strlen($username) == 0) {
    echo "<p><b>Please enter a username.</b></p>\n";
    $form="1";
  }
  elseif (strlen($_POST[password]) == 0) {
    echo "<p><b>Please enter a password.</b></p>\n";
    $form="1";
  }
  elseif (strlen($_POST[firstname]) == 0) {
    echo "<p><b>Please enter a firstname.</b></p>\n";
    $form="1";
  }
  elseif (strlen($_POST[surname]) == 0) {
    echo "<p><b>Please enter a surname.</b></p>\n";
    $form="1";
  }
  elseif (strlen($_POST[email]) == 0) {
    echo "<p><b>Please enter an email address.</b></p>\n";
    $form="1";
  }
  else {
    $username_current=$_SESSION['tinybb'];
    $sql="SELECT count(*) FROM tinybb_members WHERE username='$_POST[username]' AND username != '$username_current'";
    $count=mysql_result(mysql_query($sql),0);
    if ($count == 0) {
      $sql="UPDATE tinybb_members SET username='$_POST[username]', password='$_POST[password]', firstname='$_POST[firstname]', surname='$_POST[surname]' WHERE email='$_POST[email]'";
      if (mysql_query($sql)) {
        echo "<p><b>Thank you for updating your details.</b></p>\n";
        if ($username_current != $_POST[username]) {
          echo "<p>Please <a href=\"update.php?logout\">log back in</a> to use your new username.</p>\n";
        }
      }
      else { echo "<pre>$sql</pre>\n";
        echo "<p><b>There has been an erorr processing your request.</b></p>\n<p>Please <a href=\"javascript:history.go(-1)\">go back</a> and try again.</p>\n";
      }
    }
    else {
      echo "<p>The username <b>$_POST[username]</b> already exists, please enter another.</p>\n";
      $form="1";
    }
  }
}
elseif (isset($_SESSION['tinybb']))
{
  $username=$_SESSION['tinybb'];

  $sql="SELECT * FROM tinybb_members WHERE username='$username'";
  $result=mysql_query($sql);
  while ($row = mysql_fetch_array($result)) {
    $id=$row[id];
    $password=$row[password];
    $firstname=$row[firstname];
    $surname=$row[surname];
    $email=$row[email];
    $form="1";
  }
}
else {
  echo "<p><b>You need to log in or <a href=\"register.php\">register</a> to use this section of the site.</b></p>\n";
}

if ($form == '1') {
  if (!isset($username)) { $username = $_POST[username]; }
  if (!isset($password)) { $username = $_POST[password]; }
  if (!isset($email)) { $username = $_POST[email]; }
  if (!isset($firstname)) { $username = $_POST[firstname]; }
  if (!isset($surname)) { $username = $_POST[surname]; }

  echo "<form action=\"update.php\" method=\"post\">
	<table summary=\"Update form\" width=\"100%\">
		<tr>
			<td width=\"20%\"><label for=\"username\">Username</label></td>
			<td width=\"80%\"><input type=\"text\" id=\"username\" name=\"username\" size=\"15\" value=\"$username\" /></td>
		</tr>
		<tr>
			<td><label for=\"password\">Password</label></td>
			<td><input type=\"password\" id=\"password\" name=\"password\" size=\"15\" value=\"$password\" /></td>
		</tr>
		<tr>
			<td>E-mail</td>
			<td><b>$email</b></td>
		</tr>
		<tr>
			<td><label for=\"firstname\">First Name</label></td>
			<td><input type=\"text\" id=\"firstname\" name=\"firstname\" size=\"15\" value=\"$firstname\" /></td>
		</tr>
		<tr>
			<td><label for=\"surname\">Surname</label></td>
			<td><input type=\"text\" id=\"surname\" name=\"surname\" size=\"15\" value=\"$surname\" /></td>
		</tr>
		<tr>
			<td></td>
			<td><br /><input type=\"hidden\" name=\"email\" value=\"$_POST[email]\" /><input type=\"hidden\" name=\"submit\" /><input type=\"image\" class=\"clear\" src=\"_images/form_submit.gif\" /></td>
		</tr>
	</table>
</form>\n";
}

require_once("footers.php");
?>