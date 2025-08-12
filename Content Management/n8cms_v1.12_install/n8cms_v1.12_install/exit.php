<?
require ('_.php');
require ('functions.php');
//pageheader();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Login Form</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body onload="login.username.focus()">
<form action="checkuser.php" method="post" name="login" id="login">
  <table width="50%" border="0" cellpadding="4" cellspacing="0">
    <tr> 
      <td width="22%">Username</td>
      <td width="78%"><input class='selectbox' name="username" type="text" id="username" tabindex=1></td>
    </tr>
    <tr> 
      <td>Password</td>
      <td><input name="password" type="password" class='selectbox' id="password" tabindex=2></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td ><input class='button' type="submit" name="Submit" value="Submit" tabindex=3></td></tr>
  </table>

</form>
</body>
</html>
