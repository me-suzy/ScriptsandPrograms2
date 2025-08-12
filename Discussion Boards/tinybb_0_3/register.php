<?php
$js = "function checkCheckBoxes(theForm) {\n if (\n  theForm.confirm.checked == false) {\n  alert ('Please tick the box to confirm that\\nyou have read and agree to our\\nadvertiser terms and conditions');\n  return false;\n }\nelse {\n  return true;\n }\n}\n";
require_once("headers.php");
if (isset($_POST['submit'])) {
  $username=strtolower($_POST[username]);
  $password=strtolower($_POST[password]);
  if (strlen($username) == 0) {
    echo "<p><b>Please enter a username.</b></p>\n";
    $form="1";
  }
  elseif (strlen($password) == 0) {
    echo "<p><b>Please enter a password.</b></p>\n";
    $form="1";
  }
  elseif (strlen($_POST[email]) == 0) {
    echo "<p><b>Please enter a email.</b></p>\n";
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
  else {
    $sql="SELECT count(*) FROM tinybb_members WHERE username='$username'";
    $count=mysql_result(mysql_query($sql),0);
    if ($count == 0) {
      $sql="SELECT count(*) FROM tinybb_members WHERE email='$_POST[email]'";
      $count=mysql_result(mysql_query($sql),0);
      if ($count == 0) {
        $sql="INSERT INTO tinybb_members SET flag='0', username='$username', password='$password', email='$_POST[email]', firstname='$_POST[firstname]', surname='$_POST[surname]'";
        if (mysql_query($sql)) {
          $id=md5($email);
          $to = "$_POST[firstname] $_POST[surname] <$_POST[email]>";
          $headers = "From: $tinybb_title <$tinybb_email>\n";
          $subject = "Your Account";
          $message = "Hi $_POST[firstname]\n\nThank you for registering with $tinybb_title.\n\nTo complete your registration we ask you to confirm your e-mail address.\n\nTo do this, simply click the following link:\n".$tinybb_url."/".$tinybb_folder."/activate.php?id=$id\n\nIf you need any help, just reply to this email.\n\n$tinybb_title";
          mail($to, $subject, $message, $headers);
          echo "<p><b>Thank you for registering.</b></p>\n<p>You now need to activate your account by clicking the link in an e-mail which has been sent to you at <b>$_POST[email]</b>.</p>\n";
        }
        else {
          echo "<p><b>There has been an error processing your request.</b></p>\n<p>Please try again.\n";
          $form="1";
        }
      }
      else {
        echo "	<p>The email address <b>$_POST[email]</b> already exists, please enter another.</p>\n";
        $form="1";
      }
    }
    else {
      echo "<p>The username <b>$username</b> already exists, please enter another.</p>\n";
      $form="1";
    }
  }
}
else {
  print
<<<END
<p><b>By registering you will receive regular e-mail updates on what's happening at Drakes Cork &amp; Cask as well as unlimited access to our forum.</b></p>
<p>To register, simply complete the form below and confirm your registration.</p>
<p>If you receive any problems, please feel free to <a href="../contact/index.php">contact us</a>.</p>\n
END;
  $form="1";
}

if ($form == '1') {
  echo "<form action=\"register.php\" method=\"post\" name=\"theform\" onsubmit=\"return checkCheckBoxes(this);\">
	<table summary=\"Registration form\" width=\"100%\">
		<tr>
			<td><label for=\"username\">Username</label></td>
			<td><input type=\"text\" id=\"username\" name=\"username\" size=\"15\" value=\"$username\" /></td>
		</tr>
		<tr>
			<td><label for=\"password\">Password</label></td>
			<td><input type=\"password\" id=\"password\" name=\"password\" size=\"15\" value=\"$password\" /></td>
		</tr>
		<tr>
			<td><label for=\"email\">E-mail</label></td>
			<td><input type=\"text\" id=\"email\" name=\"email\" size=\"30\" value=\"$_POST[email]\" /></td>
		</tr>
		<tr>
			<td><label for=\"firstname\">First Name</label></td>
			<td><input type=\"text\" id=\"firstname\" name=\"firstname\" size=\"15\" value=\"$_POST[firstname]\" /></td>
		</tr>
		<tr>
			<td><label for=\"surname\">Surname</label></td>
			<td><input type=\"text\" id=\"surname\" name=\"surname\" size=\"15\" value=\"$_POST[surname]\" /></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<br />
				<input type=\"checkbox\" name=\"confirm\" id=\"confirm\" value=\"1\" class=\"clear\" "; if ($_POST[confirm] == '1') { echo "checked=\"checked\" "; } echo "/>
				<label for=\"confirm\">Please tick this box to confirm that you have read and agree with our <a href=\"terms.php\" target=\"_blank\">terms of use</a>.</label>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><br /><input type=\"hidden\" name=\"submit\" /><input type=\"image\" src=\"_images/form_submit.gif\" class=\"clear\" /></td>
		</tr>
	</table>
</form>\n";
}

require_once("footers.php");
?>